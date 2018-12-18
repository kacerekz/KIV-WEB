<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 06-Dec-18
 * Time: 10:03 PM
 */

require_once "controller.class.php";

class Rvwass extends Controller
{

    private $review_count = 3;

    public $auth = array("3");

    public function viewPage($data){

        include_once ("src/models/database.class.php");
        $db = new Database();

        // Vyber vsech autory dokoncenych clanku
        $articles = $db->DBSelectAll("posts", "*", array(
            array("column"=>"status",  "symbol"=>">=",  "value"=>"2" )
        ));

        // Vyber vsech recenzentu
        $reviewers = $db->DBSelectAll("users", "id_user, login", array(
            array("column"=>"rights_id_rights",  "symbol"=>"=",  "value"=>"2" ),
            array("column"=>"blocked",  "symbol"=>"=",  "value"=>"0" )
        ));
        $data['reviewers'] = $reviewers;

        foreach ($articles as $article){

            $rev_cnt = 0;

            // Zjisteni udaju o autorovi
            $article['author'] = $db->DBSelectOne("users", "name, login", array(
                array("column"=>"id_user",  "symbol"=>"=",  "value"=>$article['users_id_user'] )
            ));

            for ($i = 1; $i<=$this->review_count; $i++){
                if ($article['reviewer_id'.$i] != 0){
                    $rev_cnt++;

                    // Vyber recenzenta, pokud byl pridelen
                    $reviewer = $db->DBSelectOne("users", "id_user, login", array(
                        array("column"=>"id_user",  "symbol"=>"=",  "value"=>$article['reviewer_id'.$i])
                    ));

                    // Vyber recenze, pokud existuje
                    if (isset($reviewer)){
                        $article['reviewer'.$i] = $reviewer;

                        $review = $db->DBSelectOne("rating", "*", array(
                            array("column"=>"users_id_user",  "symbol"=>"=",  "value"=>$article['reviewer_id'.$i] ),
                            array("column"=>"posts_id_posts",  "symbol"=>"=",  "value"=>$article['id_posts'] )
                        ));

                        if (isset($review)){
                            $article['review'.$i]['review'] = $review;
                        }
                    }
                }
            }

            $article['review_count'] = $rev_cnt;
            $data['articles'][] = $article;
        }

        /*
        foreach ($articles as $article){

            WHAT THE EVER LIVING FUCK IS THIS FOR


            $article['reviewers'] = array();

            foreach ($reviewers as $reviewer){
                $old_review = $db->DBSelectOne("rating", "*", array(
                    array("column"=>"users_id_user", "symbol"=>"=", "value"=>$reviewer['id_user']),
                    array("column"=>"posts_id_posts", "symbol"=>"=", "value"=>$article['id_posts'])
                ));

                if (!$old_review){
                    $article['reviewers'][] = $reviewer;
                }
            }

            $data['articles'][] = $article;
        };
*/

/*
        echo "<pre style='margin-top: 60px'>";
        var_dump($data['articles']);
        echo "</pre>";
*/

        parent::viewPage($data);
    }
}