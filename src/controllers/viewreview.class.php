<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 06-Dec-18
 * Time: 10:08 PM
 */

require_once "controller.class.php";

class ViewReview extends Controller
{
    public $auth = array("1", "2", "3");

    public function viewPage($data){

        if (isset($_POST['post_id']) && isset($_POST['user_id'])){

            include_once ("src/models/database.class.php");
            $db = new Database();

            $post = $db->DBSelectOne("posts", "title, users_id_user", array(
                array("column"=>"id_posts", "symbol"=>"=", "value"=>$_POST['post_id'])
            ));

            if (($data['user']['rights_id_rights'] == "1" && $data['user']['id_user'] != $post['users_id_user'])
                or
                ($data['user']['rights_id_rights'] == "2") && $data['user']['id_user'] != $_POST['user_id'])
            {
                header("Location: index.php");
                exit;
            }

            $data["review"] = $db->DBSelectOne("rating", "*",
                array(
                    array("column" => "posts_id_posts",   "symbol"=>"=",    "value" => $_POST['post_id']),
                    array("column" => "users_id_user",   "symbol"=>"=",    "value" => $_POST['user_id']))
            );

            $reviewer = $db->DBSelectOne("users", "login",
                array(
                    array("column" => "id_user",   "symbol"=>"=",    "value" => $_POST['user_id']))
            );

            $data['post_title'] = $post['title'];
            $data["reviewer"] = $reviewer['login'];

            parent::viewPage($data);
            exit;
        }

        header("Location: index.php");
        exit;
    }
}