<?php

// TWIG sablona ********************************************
require_once 'vendor/autoload.php';
$loader = new Twig_Loader_Filesystem('twig-templates');
$twig = new Twig_Environment($loader);

// Stranky *************************************************
$pages = array("login", "home", "about", "contacts");

// Zjisti pozadovanou stranku z URL ************************
if(isset($_GET["page"]) && in_array($_GET["page"], $pages)){
    $page = $_GET["page"];
} else {
    $page = "home";
}

// Nastav data pro sablonu *********************************
$data['title'] = "WebCON 2018";
$data['index'] = array_search($page, $pages);
$filename = "src/controllers/".$page.".class.php";

// Ziskej sablonu ******************************************
if ( file_exists($filename) && !is_dir($filename) ) {
    include_once($filename);
    $controller = new $page($twig);
    $controller->viewPage($data);
} else {
    include_once("src/controllers/error404.class.php");
    // Error controller etc
}

// Zobraz stranku ******************************************
