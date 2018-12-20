<?php

include ("../models/database.class.php");
$db = new Database();

if (isset($_POST['delete'])){

    $db->DBDelete("rating", array(
        array("column"=>"posts_id_posts", "symbol"=>"=", "value"=>$_POST['post_id'])), "");

    $db->DBDelete("posts", array(
        array("column"=>"id_posts", "symbol"=>"=", "value"=>$_POST['post_id'])), "");

    header("Location: ../../index.php?page=articles");
    exit;
}

else {

    if(isset($_FILES["file"])) {
        $dir = "../../user-files/";
        $file = $dir . $_POST['user_id'] ."_". $_POST['post_id'].".pdf";
        $ext = strtolower(pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION));

        if($ext == "pdf"){
            move_uploaded_file($_FILES["file"]["tmp_name"], $file);
        }
    }

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

