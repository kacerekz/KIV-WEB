<?php

// Zmena prav uzivatele

if($_POST['rights-select'] >= 1 && $_POST['rights-select'] <= 3){

    include_once ("../models/database.class.php");
    $db = new Database();

    $db->DBUpdateExpanded(
        "users",
        array("rights_id_rights"=>$_POST['rights-select']),
        array(array("column"=>"id_user", "symbol"=>"=", "value"=>$_POST['user_id']))
    );

}

header("Location: ../../index.php?page=usrmng");
exit;