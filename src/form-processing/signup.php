<?php

include ("../models/database.class.php");
$db = new Database();

$user = $db->DBSelectOne("users", "login", array(
    array("column"=>"login", "symbol" => "=", "value" => $_POST['username'])));

if (isset($user['login'])){
    header("Location: ../../index.php?page=login&unique=false");
    exit;

} else {
    $db->DBInsertExpanded("users", array(
        array("column" => "name",               "value" => $_POST['fullname']),
        array("column" => "login",              "value" => $_POST['username']),
        array("column" => "pass",               "value" => password_hash($_POST['password'], PASSWORD_DEFAULT)),
        array("column" => "email",              "value" => $_POST['email']),
        array("column" => "rights_id_rights",   "value" => "1"),
        array("column" => "blocked",          "value" => "0"))
    );

    include ("../controllers/login-manager.class.php");
    $lm = new LoginManager();

    $user = $db->DBSelectOne("users", "*", array(
        array("column"=>"login", "symbol" => "=", "value" => $_POST['username'])));
    $lm->login($user);

    header("Location: ../../index.php?page=home");
    exit;
}




