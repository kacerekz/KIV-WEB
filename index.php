<?php

// TWIG sablona ********************************************
require_once 'vendor/autoload.php';
$loader = new Twig_Loader_Filesystem('twig-templates');
$twig = new Twig_Environment($loader);

// Stranky *************************************************
$pages = array("login", "home", "about", "contacts", "settings",
    "rvwass", "usrmng",
    "articles", "newarticle",
    "reviews", "newreview");

// Prihlas uzivtele? ***************************************
include_once ("src/controllers/login-manager.class.php");
$login = new LoginManager;

if(isset($_POST["login-form"])){
    if(isset($_POST["action"]) && $_POST["action"]=="login" && isset($_POST["username"])){

        if($_POST["username"]!=""){
            echo "logging in";
            $login->login($_POST["username"]);

        } else {
            // vrat uzivatele na login, prepni formular na cerveno - nezadal username
            echo "Přihlášení se nezdařilo: nebylo zadáno jméno uživatele.<br>";
        }
    }
} else if(isset($_GET["logout"]) && $_GET["logout"]=="true"){
    $login->logout();
    echo "logging out";
}

if ($login->isUserLoged()){
    $data['user'] = $login->getUser();
}

// Zjisti pozadovanou stranku z URL ************************
$page = "";
if(isset($_GET["page"])){
    if (in_array($_GET["page"], $pages)){
        $page = $_GET["page"];
    }
} else {
    $page = "home";
}

// Nastav data pro sablonu *********************************
$data['page'] = $page;
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
    $controller = new Error404($twig);
    $controller->viewPage($data);
}
