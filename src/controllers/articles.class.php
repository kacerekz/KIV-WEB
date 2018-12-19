<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 06-Dec-18
 * Time: 10:05 PM
 */

require_once "controller.class.php";

class Articles extends Controller
{
    public $auth = array("1");

    public function viewPage($data){
        include_once ("src/models/database.class.php");
        $db = new Database();

        $articles = $db->DBSelectAll("posts", "*", array(
            array("column"=>"users_id_user", "symbol"=>"=", "value"=>$data['user']['id_user'])
        ));

        foreach ($articles as $article){

            for ($i = 1; $i <= 3; $i++){

                if ($article['reviewer_id'.$i] == "0"){
                    continue;
                }

                $article['review'.$i] = $db->DBSelectOne("rating", "*", array(
                    array("column"=>"users_id_user", "symbol"=>"=", "value"=>$article['reviewer_id'.$i]),
                    array("column"=>"posts_id_posts", "symbol"=>"=", "value"=>$article['id_posts']),
                    array("column"=>"status", "symbol"=>">", "value"=>"1")
                ));

                if (!$article['review'.$i]){
                    unset($article['review'.$i]);
                }
            }

            $data['articles'][] = $article;
        }

        parent::viewPage($data);
    }
}