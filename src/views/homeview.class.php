<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 01-Dec-18
 * Time: 8:29 PM
 */

class HomeView
{

    public function __construct() {
    }

    public static function viewPage($twig, $data){
        $template = $twig->load("home.twig");
        echo $template->render($data);
    }

}