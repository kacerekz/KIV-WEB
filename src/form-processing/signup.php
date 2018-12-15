<?php

include ("../models/database.class.php");

$db = new Database();

$user = $db->DBSelectOne("users", "*", array(
    array("column"=>"login", "symbol" => "=", "value" => $_POST['username'])));

if (isset($user)){

    global $err;
    $err[] = "username_exists";

    header("Location: ../../index.php?page=login");
    exit;

} else {
    $db->DBInsertExpanded("users", array(
        array("column" => "name",               "value" => $_POST['fullname']),
        array("column" => "login",              "value" => $_POST['username']),
        array("column" => "pass",               "value" => password_hash("".$_POST['password'], PASSWORD_DEFAULT)),
        array("column" => "email",              "value" => $_POST['email']),
        array("column" => "rights_id_rights",   "value" => "1"),
        array("column" => "is_logged",          "value" => "0"),
        array("column" => "last_active",        "value" => "0000-00-00 0:0:0")));

}




