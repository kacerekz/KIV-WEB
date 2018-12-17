<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 06-Dec-18
 * Time: 10:08 PM
 */

require_once "controller.class.php";

class Errors extends Controller
{
    public $auth = array("0", "1", "2", "3");

    public function viewPage($data){
        parent::viewPage($data);
    }
}