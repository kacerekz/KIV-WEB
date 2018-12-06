<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 02-Dec-18
 * Time: 2:59 PM
 */

class LoginView
{
    public function __construct() {
    }

    public static function viewPage($twig, $data){
        $template = $twig->load("login.twig");
        echo $template->render($data);
    }
}