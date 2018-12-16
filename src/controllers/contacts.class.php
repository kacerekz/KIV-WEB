<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 02-Dec-18
 * Time: 2:47 PM
 */

require("controller.class.php");

class Contacts extends Controller
{
    public function viewPage($data){
        include("src/models/database.class.php");
        $db = new Database();
        $contacts = $db->DBSelectAll("contacts", "*", array());
        $data['contacts'] = $contacts;
        parent::viewPage($data);
    }
}