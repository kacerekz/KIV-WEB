<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 02-Dec-18
 * Time: 2:59 PM
 */

class AboutView
{
    public function __construct() {
    }

    public static function viewPage($twig, $data){
        $template = $twig->load("about.twig");
        echo $template->render($data);
    }
}