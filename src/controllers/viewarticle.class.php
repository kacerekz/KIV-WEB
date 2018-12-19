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

        if (isset($_POST['post_id'])) {

            include_once("src/models/database.class.php");
            $db = new Database();

            $post = $db->DBSelectOne("posts", "*", array(
                array("column"=>"id_posts", "symbol"=>"=", "value"=>$_POST['post_id'])
            ));

            if ($post['status'] != 4 && (
                    $data['user']['rights_id_rights'] != "3" ||
                    $data['user']['id_user'] != $post['users_id_user'] ||
                    $data['user']['id_user'] != $post['reviewer_id1'] ||
                    $data['user']['id_user'] != $post['reviewer_id2'] ||
                    $data['user']['id_user'] != $post['reviewer_id3']
                )){
                header("Location: index.php");
                exit;
            }

            $data['post'] = $post;

            parent::viewPage($data);
        }

    }
}