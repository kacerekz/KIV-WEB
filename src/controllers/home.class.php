<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 01-Dec-18
 * Time: 8:26 PM
 */

require_once "controller.class.php";

// Ovladac domovske stranky - vypis publikovanych clanku
class Home extends Controller
{
    public $auth = array("0", "1", "2", "3");

    public function viewPage($data){

        include_once ("src/models/database.class.php");
        $db = new Database();
        $articles = $db->DBSelectAll("posts", "*", array(
            array("column"=>"status", "symbol"=>"=", "value"=>"4")
        ), "ORDER BY `posts`.`id_posts` DESC");

        foreach ($articles as $article){
            // Zjisteni udaju o autorovi
            $article['author'] = $db->DBSelectOne("users", "name", array(
                array("column"=>"id_user",  "symbol"=>"=",  "value"=>$article['users_id_user'] )
            ));

            $data['articles'][] = $article;
        }

        parent::viewPage($data);
    }
}