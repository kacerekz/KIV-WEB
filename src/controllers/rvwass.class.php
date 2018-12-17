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
    public $auth = array("3");

    public function viewPage($data){
        include_once ("src/models/database.class.php");
        $db = new Database();

        $articles = $db->DBSelectAll("posts", "*", array(
            array("column"=>"status",  "symbol"=>">=",  "value"=>"2" )
        ));

        $reviewers = $db->DBSelectAll("users", "*", array(
            array("column"=>"rights_id_rights",  "symbol"=>"=",  "value"=>"2" )
        ));

        foreach ($articles as $article){
            $article['author'] = $db->DBSelectOne("users", "name, login", array(
                array("column"=>"id_user",  "symbol"=>"=",  "value"=>$article['users_id_user'] )
            ));


            for ($i = 1; $i<=3; $i++){
                if (isset($article['review_id'.$i]) && $article['review_id'.$i] != 0){
                    $article['review'.$i] = $db->DBSelectOne("users", "id_user, name, login", array(
                        array("column"=>"id_user",  "symbol"=>"=",  "value"=>$article['review_id'.$i]),
                    ));

                    $review = $db->DBSelectOne("rating", "*", array(
                        array("column"=>"users_id_user",  "symbol"=>"=",  "value"=>$article['review_id'.$i] ),
                        array("column"=>"posts_id_posts",  "symbol"=>"=",  "value"=>$article['id_posts'] )
                    ));

                    if (isset($review)){
                        $article['review'.$i]['review'] = $review;
                    }
                }
            }

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


/*
        echo "<pre style='margin-top: 60px'>";
        var_dump($data['articles']);
        echo "</pre>";
*/

        parent::viewPage($data);
    }
}