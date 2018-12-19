<?php

include ("../models/database.class.php");
$db = new Database();

$status = 0;

if (isset($_POST['status'])){
    $status = $_POST['status'];
} else if (isset($_POST['save-draft'])){
    $status = 1;
} else if (isset($_POST['submit'])){
    $status = 2;
}

if (isset($_POST['quick-submit'])) {
    $db->DBUpdateExpanded( "rating",
        array(
            "datetime"      => date("Y-m-d H:i:s"),
            "status"        => $status
        ),

        array(
            array("column" => "posts_id_posts",   "symbol"=>"=",    "value" => $_POST['post_id']),
            array("column" => "users_id_user",   "symbol"=>"=",    "value" => $_POST['user_id'])
        )
    );

} else if (isset($_POST['review-exists'])){
    $db->DBUpdateExpanded( "rating",
        array(
            "orig"          => $_POST['orig'],
            "acc"           => $_POST['acc'],
            "lang"          => $_POST['lang'],
            "text"          => $_POST['content'],
            "datetime"      => date("Y-m-d H:i:s"),
            "status"        => $status
        ),

        array(
            array("column" => "posts_id_posts",   "symbol"=>"=",    "value" => $_POST['post_id']),
            array("column" => "users_id_user",   "symbol"=>"=",    "value" => $_POST['user_id'])
        )
    );

} else {
    $db->DBInsertExpanded("rating", array(
        array("column" => "orig",          "value" => $_POST['orig']),
        array("column" => "acc",          "value" => $_POST['acc']),
        array("column" => "lang",          "value" => $_POST['lang']),
        array("column" => "text",           "value" => $_POST['content']),
        array("column" => "datetime",       "value" => date("Y-m-d H:i:s")),
        array("column" => "status",         "value" => $status),
        array("column" => "users_id_user",  "value" => $_POST['user_id']),
        array("column" => "posts_id_posts",  "value" => $_POST['post_id'])
    ));

}

header("Location: ../../index.php?page=reviews");
exit;