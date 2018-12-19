<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 02-Dec-18
 * Time: 2:47 PM
 */

require_once "controller.class.php";

class NewArticle extends Controller
{
    public $auth = array("1");


    public function viewPage($data){

        if (isset($_POST['mode']) && $_POST['mode'] == "edit"){
            include_once ("src/models/database.class.php");
            $db = new Database();

            $data["post"] = $db->DBSelectOne("posts", "*",
                array(
                    array("column" => "id_posts",   "symbol"=>"=",    "value" => $_POST['post_id'])
                )
            );

            if ($data["post"]['status'] >= 3 ){
                header("Location: index.php?page=viewarticle&id=".$_POST['post_id']);
                exit;
            }


        };

        $data["scripts"] = array("https://cdn.ckeditor.com/ckeditor5/11.1.1/classic/ckeditor.js");
        parent::viewPage($data);
    }
}