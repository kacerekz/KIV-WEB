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

// Je/ma byt uzivatel prihlasen? ***************************
include_once ("src/controllers/login-manager.class.php");
$login = new LoginManager;

if(isset($_GET["logout"]) && $_GET["logout"]=="true"){
    $login->logout();
}


if ($login->isUserLoged()){
    $data['user'] = $login->getUser();

    // Prodlouzi platnost prihlaseni, NEBO UZIVATELE ODHLASI POKUD JE NA BLACKLISTU
    include("src/models/database.class.php");
    $db = new Database();

    $user = $db->DBSelectOne("users", "*", array(
        array("column"=>"ID_USER", "symbol" => "=", "value" => $data['user']['id_user'])));

    if ($user['blacklisted'] == 1){
        setcookie(session_name(),session_id(),time()+0);
    } else {
        $login->logout();
    }

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
