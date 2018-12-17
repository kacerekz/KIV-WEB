<?php

// TWIG sablona ********************************************
require_once 'vendor/autoload.php';
$loader = new Twig_Loader_Filesystem('twig-templates');
$twig = new Twig_Environment($loader);

// Stranky *************************************************
$auth = "0";
$pages = array("login", "home", "about", "contacts", "settings",
    "rvwass", "usrmng",
    "articles", "newarticle",
    "reviews", "newreview");

// Je/ma byt uzivatel prihlasen? ***************************
include_once ("src/controllers/login-manager.class.php");
$login = new LoginManager;
$data['lm'] = $login;

if(isset($_GET["logout"]) && $_GET["logout"]=="true"){
    $login->logout();
}

if ($login->isUserLoged()){
    $data['user'] = $login->getUser();

    // Odhlasi uzivatele pokud jiz neni v databazi
    include_once ("src/models/database.class.php");
    $db = new Database();

    $user = $db->DBSelectOne("users", "*", array(
        array("column"=>"id_user", "symbol" => "=", "value" => $data['user']['id_user'])));

    if (!isset($user)){
        $login->logout();
        unset($data['user']);
    }

    $auth = $data['user']['rights_id_rights'];
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

    if (in_array($auth, $controller->auth)){
        $controller->viewPage($data);

    } else {
        include_once("src/controllers/errors.class.php");
        $data['page'] = "errors";
        $data['errtext'] = "You don't have access to this page.";
        $controller = new Errors($twig);
        $controller->viewPage($data);
    }

} else {
    include_once("src/controllers/errors.class.php");
    $data['page'] = "errors";
    $data['errtext'] = "There's nothing here.";
    $controller = new Errors($twig);
    $controller->viewPage($data);
}
