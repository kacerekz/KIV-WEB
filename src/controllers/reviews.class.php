<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 06-Dec-18
 * Time: 10:05 PM
 */

require_once "controller.class.php";

// Ovladac stranky recenzenta, ktera ukazuje vsechny jeho recenze
class Reviews extends Controller
{
    public $auth = array("2");

    public function viewPage($data){
        include_once ("src/models/database.class.php");
        $db = new Database();

        // NAJDE POUZE PRO AKTIVNI RECENZE
        $articles = array();

        for ($i = 1; $i<=3; $i++){
            $articles_temp = $db->DBSelectAll("posts", "*", array(
                    array("column"=>"reviewer_id".$i, "symbol"=>"=", "value"=>$data['user']['id_user']))
            );

            foreach ($articles_temp as $article){
                $articles[] = $article;
            }
        }

        for ($i = 1; $i<=4; $i++){
            foreach ($articles as $article){
                if ($article['status'] != $i){
                    continue;
                }
                // Zjisteni udaju o autorovi
                $article['author'] = $db->DBSelectOne("users", "name, login", array(
                    array("column"=>"id_user",  "symbol"=>"=",  "value"=>$article['users_id_user'] )
                ));

                $article['review'] = $db->DBSelectOne("rating", "*", array(
                    array("column"=>"posts_id_posts",  "symbol"=>"=",  "value"=>$article['id_posts'] ),
                    array("column"=>"users_id_user",  "symbol"=>"=",  "value"=>$data['user']['id_user'] )
                ));

                if (!$article['review']){
                    unset($article['review']);
                }

                $data['articles'][] = $article;
            }
        }


        // PRIDANI ODMITNUTYCH RECENZI
        $declined = $db->DBSelectAll("rating", "*", array(
            array("column"=>"status",  "symbol"=>"=",  "value"=>"3" ),
            array("column"=>"users_id_user",  "symbol"=>"=",  "value"=>$data['user']['id_user'])
        ));

        foreach ($declined as $review){

            $article = $db->DBSelectOne("posts", "*", array(
                array("column"=>"id_posts",  "symbol"=>"=",  "value"=>$review['posts_id_posts']),
            ));

            // Zjisteni udaju o autorovi
            $article['author'] = $db->DBSelectOne("users", "name, login", array(
                array("column"=>"id_user",  "symbol"=>"=",  "value"=>$article['users_id_user'] )
            ));

            $article['review'] = $review;

            $data['articles'][] = $article;
        }

        parent::viewPage($data);
    }
}