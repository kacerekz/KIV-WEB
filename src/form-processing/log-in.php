<?php
include ("../models/database.class.php");
include ("../controllers/login-manager.class.php");

$db = new Database();
$lm = new LoginManager();

$user = $db->DBSelectOne("users", "*", array(
    array("column"=>"login", "symbol" => "=", "value" => $_POST['username'])));


if (isset($user['pass']) && password_verify($_POST['password'], trim($user['pass']))){

    if ($user['blocked'] == 0){
        $lm->login($user);
        header("Location: ../../index.php?page=home");
        exit;
    } else {
        header("Location: ../../index.php?page=login&blocked=true");
        exit;
    }

} else {
    header("Location: ../../index.php?page=login&success=false");
    exit;
}
