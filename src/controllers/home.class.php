<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 01-Dec-18
 * Time: 8:26 PM
 */

require_once "controller.class.php";

class Home extends Controller
{
    public $auth = array("0", "1", "2", "3");

    public function viewPage($data){

        echo "<pre style='margin-top: 60px'>";
        var_dump($data/*$_SESSION*/);
        echo "</pre>";

        parent::viewPage($data);
    }
}