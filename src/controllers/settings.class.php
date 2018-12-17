<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 17-Dec-18
 * Time: 6:25 PM
 */

require_once "controller.class.php";
class Settings extends Controller
{
    public $auth = array("1", "2", "3");

    public function viewPage($data){
        parent::viewPage($data);
    }
}