<?php

if(isset($_POST['user_id'])){

    include ("../models/database.class.php");
    $db = new Database();

    $db->DBDelete(
        "users",
        array(array("column"=>"id_user", "symbol"=>"=", "value"=>$_POST['user_id'])),
        "");
}

echo "<pre style='margin-top: 60px'>";
var_dump($_POST/*$data/*$_SESSION*/);
echo "</pre>";

header("Location: ../../index.php?page=usrmng");
exit;