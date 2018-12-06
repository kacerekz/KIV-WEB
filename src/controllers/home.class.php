<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 01-Dec-18
 * Time: 8:26 PM
 */

class Home
{
    private $twig;

    public function __construct($twig) {
        $this->twig = $twig;
    }

    public function viewPage($data){
        //get articles from DB
        include("src/views/homeview.class.php");
        $data["cssfile"] = array("css/home.css", "css/reviews-table.css");
        HomeView::viewPage($this->twig, $data);
    }

}