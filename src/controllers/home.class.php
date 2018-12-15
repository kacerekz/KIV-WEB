<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 01-Dec-18
 * Time: 8:26 PM
 */

require("controller.class.php");

class Home extends Controller
{
    public function viewPage($data){

        echo "<pre style='margin-top: 60px'>";
        var_dump($_SESSION);
        echo "</pre>";

        parent::viewPage($data);
    }
}