<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 02-Dec-18
 * Time: 2:48 PM
 */

class Login
{
    private $twig;

    public function __construct($twig) {
        $this->twig = $twig;
    }

    public function viewPage($data){
        //get articles from DB
        include("src/views/loginview.class.php");
        $data["cssfile"] = array("css/login.css", "css/form.css");
        LoginView::viewPage($this->twig, $data);
    }

}