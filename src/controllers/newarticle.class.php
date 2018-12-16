<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 02-Dec-18
 * Time: 2:47 PM
 */

require("controller.class.php");

class NewArticle extends Controller
{
    public function viewPage($data){

        if (isset($_POST['mode']) && $_POST['mode'] == "edit"){
            $db = new Database();

            $data["post"] = $db->DBSelectOne("posts", "*",
                array(
                    array("column" => "id_posts",   "symbol"=>"=",    "value" => $_POST['post_id'])
                ));
        };

        $data["scripts"] = array("https://cdn.ckeditor.com/ckeditor5/11.1.1/classic/ckeditor.js");
        parent::viewPage($data);
    }
}