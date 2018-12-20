<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 02-Dec-18
 * Time: 2:47 PM
 */

require_once "controller.class.php";

// Ovladac stranky s kontakty
class Contacts extends Controller
{
    // Pristupova prava
    public $auth = array("0", "1", "2", "3");

    public function viewPage($data){

        include_once ("src/models/database.class.php");
        $db = new Database();
        $contacts = $db->DBSelectAll("contacts", "*", array());
        $data['contacts'] = $contacts;
        parent::viewPage($data);
    }
}