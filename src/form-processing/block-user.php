<?php

include_once ("../models/database.class.php");
$db = new Database();

if ($_POST['blocked'] == 0 ){

    $db->DBUpdateExpanded(
        "users",
        array("blocked"=>"1"),
        array(array("column"=>"id_user", "symbol"=>"=", "value"=>$_POST['user_id']))
    );

} else if ($_POST['blocked'] == 1 ){

    $db->DBUpdateExpanded(
        "users",
        array("blocked"=>"0"),
        array(array("column"=>"id_user", "symbol"=>"=", "value"=>$_POST['user_id']))
    );

}

header("Location: ../../index.php?page=usrmng");
exit;