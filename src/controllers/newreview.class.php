<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 02-Dec-18
 * Time: 2:47 PM
 */

require_once "controller.class.php";

class NewReview extends Controller
{
    public $auth = array("2");

    public function viewPage($data){

        if (isset($_POST['user_id']) && isset($_POST['post_id'])){
            include_once ("src/models/database.class.php");
            $db = new Database();

            $data["post"] = $db->DBSelectOne("posts", "*",
                array(
                    array("column" => "id_posts",   "symbol"=>"=",    "value" => $_POST['post_id'])
                )
            );

            $data["review"] = $db->DBSelectOne("rating", "*",
                array(
                    array("column" => "posts_id_posts",   "symbol"=>"=",    "value" => $_POST['post_id']),
                    array("column" => "users_id_user",   "symbol"=>"=",    "value" => $_POST['user_id'])
                ));

            $data["scripts"] = array("https://cdn.ckeditor.com/ckeditor5/11.1.1/classic/ckeditor.js");
            parent::viewPage($data);
        };

        header("Location: index.php");
        exit;
    }
}