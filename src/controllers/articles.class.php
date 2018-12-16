<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 06-Dec-18
 * Time: 10:05 PM
 */

require("controller.class.php");

class Articles extends Controller
{
    public function viewPage($data){
        $db = new Database();
        $data['articles'] = $db->DBSelectAll("posts", "*", array(
            array("column"=>"users_id_user", "symbol"=>"=", "value"=>$data['user']['id_user'])
        ));

        parent::viewPage($data);
    }
}