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
        if (isset($_GET['unique']) && $_GET['unique'] == "false"){
            $data['user_exists'] = 1;
            unset($_GET['unique']);
        } else {
            $data['user_exists'] = 0;
        }

        if (isset($_GET['success']) && $_GET['success'] == "false"){
            $data['wrong_login'] = 1;
            unset($_GET['unique']);
        } else {
            $data['wrong_login'] = 0;
        }

        $data["cssfile"] = array("css/login.css", "css/form.css");

        echo "<pre style='margin-top: 60px'>";
        var_dump($_GET/*$data/*$_SESSION*/);
        echo "</pre>";

        parent::viewPage($data);
    }

}