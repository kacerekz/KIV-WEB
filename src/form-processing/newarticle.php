<?php

include ("../models/database.class.php");
$db = new Database();

if (isset($_POST['delete'])){

    $db->DBDelete("posts", array(
        array("column"=>"id_posts", "symbol"=>"=", "value"=>$_POST['post_id'])), "");


    header("Location: ../../index.php?page=articles");
    exit;
}


else {

    if (isset($_POST['save-draft'])){
        $status = 1;
    } else if (isset($_POST['submit'])){
        $status = 2;
    }

    if (isset($_POST['post_id'])){
        $db->DBUpdateExpanded( "posts",
            array(
                "title"         => $_POST['title'],
                "text"          => $_POST['content'],
                "datetime"      => date("Y-m-d H:i:s"),
                "status"        => $status
            ),

            array(
                array("column" => "id_posts",   "symbol"=>"=",    "value" => $_POST['post_id'])
            )
        );

    } else {
        $db->DBInsertExpanded("posts", array(
            array("column" => "title",          "value" => $_POST['title']),
            array("column" => "text",           "value" => $_POST['content']),
            array("column" => "datetime",       "value" => date("Y-m-d H:i:s")),
            array("column" => "status",         "value" => $status),
            array("column" => "users_id_user",  "value" => $_POST['user_id'])
        ));

    }

    header("Location: ../../index.php?page=articles");
    exit;
}

