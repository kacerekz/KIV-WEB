<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 06-Dec-18
 * Time: 10:04 PM
 */

require_once "controller.class.php";

// Ovladac stranky pro spravu uzivatel
class UsrMng extends Controller
{
    public $auth = array("3");

    public function viewPage($data){
        include_once ("src/models/database.class.php");
        $db = new Database();
        $users = $db->DBSelectAll(
            "users",
            "id_user, name, login, email, rights_id_rights, blocked",
            array(),
            "ORDER BY `users`.`id_user` DESC");
        $data['users'] = $users;

        parent::viewPage($data);
    }
}