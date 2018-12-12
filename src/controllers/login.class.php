<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 02-Dec-18
 * Time: 2:48 PM
 */

require("controller.class.php");

class Login extends Controller
{
    public function viewPage($data){
        $data["cssfile"] = array("css/login.css", "css/form.css");
        parent::viewPage($data);
    }

}