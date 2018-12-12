<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 06-Dec-18
 * Time: 11:15 PM
 */

class View
{
    public function __construct() {
    }

    public static function viewPage($twig, $data){
        $template = $twig->load($data["page"].".twig");
        echo $template->render($data);
    }
}