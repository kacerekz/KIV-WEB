<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 06-Dec-18
 * Time: 10:08 PM
 */

require_once "controller.class.php";

class ViewArticle extends Controller
{
    public $auth = array("0", "1", "2", "3");

    public function viewPage($data){

        if (isset($_GET['id'])) {

            include_once("src/models/database.class.php");
            $db = new Database();

            $article = $db->DBSelectOne("posts", "*", array(
                array("column"=>"id_posts", "symbol"=>"=", "value"=>$_GET['id'])
            ));

            $article['author'] = $db->DBSelectOne("users", "name, login", array(
                array("column"=>"id_user",  "symbol"=>"=",  "value"=>$article['users_id_user'] )
            ));

            $review = $db->DBSelectOne("rating", "*", array(
                array("column"=>"posts_id_posts", "symbol"=>"=", "value"=>$_GET['id']),
                array("column"=>"users_id_user", "symbol"=>"=", "value"=>$data['user']['id_user'])
            ));


            if (!$review && $article['status'] != 4 && (
                    $data['user']['rights_id_rights'] != "3" &&
                    $data['user']['id_user'] != $article['users_id_user'] &&
                    $data['user']['id_user'] != $article['reviewer_id1'] &&
                    $data['user']['id_user'] != $article['reviewer_id2'] &&
                    $data['user']['id_user'] != $article['reviewer_id3']
                )){
                header("Location: index.php");
                exit;
            }

            if (file_exists("user-files/".$article['users_id_user']."_".$_GET['id'].".pdf")){
                $article['file'] = "true";
            }

            $data['post'] = $article;

            parent::viewPage($data);
            exit;
        }

        header("Location: index.php");
        exit;
    }
}