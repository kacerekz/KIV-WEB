<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 02-Dec-18
 * Time: 2:47 PM
 */

require_once "controller.class.php";

// Övladec stranky s informacemi o konferenci
class About extends Controller
{
    public $auth = array("0", "1", "2", "3");

    public function viewPage($data){
        parent::viewPage($data);
    }
}