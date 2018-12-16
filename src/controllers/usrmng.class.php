<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 06-Dec-18
 * Time: 10:04 PM
 */

require("controller.class.php");

class UsrMng extends Controller
{
    public function viewPage($data){
        $db = new Database();
        $users = $db->DBSelectAll("users", "id_user, name, login, email, rights_id_rights, blocked", array());
        $data['users'] = $users;

        parent::viewPage($data);
    }
}