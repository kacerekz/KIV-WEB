<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 06-Dec-18
 * Time: 10:05 PM
 */

require_once "controller.class.php";

class Reviews extends Controller
{
    public $auth = array("2");

    public function viewPage($data){
        include_once ("src/models/database.class.php");
        $db = new Database();

        $articles = array();

        for ($i = 1; $i<=3; $i++){
            $articles_temp = $db->DBSelectAll("posts", "*", array(
                    array("column"=>"reviewer_id".$i, "symbol"=>"=", "value"=>$data['user']['id_user']))
            );

            foreach ($articles_temp as $article){
                $articles[] = $article;
            }
        }

        foreach ($articles as $article){

            // Zjisteni udaju o autorovi
            $article['author'] = $db->DBSelectOne("users", "name, login", array(
                array("column"=>"id_user",  "symbol"=>"=",  "value"=>$article['users_id_user'] )
            ));

            $data['articles'][] = $article;
        }


        parent::viewPage($data);
    }
}