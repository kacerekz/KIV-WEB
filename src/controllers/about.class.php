<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 02-Dec-18
 * Time: 2:47 PM
 */

class About
{
    private $twig;

    public function __construct($twig) {
        $this->twig = $twig;
    }

    public function viewPage($data){
        //get articles from DB
        include("src/views/aboutview.class.php");
        $data["cssfile"] = array("css/about.css");
        AboutView::viewPage($this->twig, $data);
    }

}