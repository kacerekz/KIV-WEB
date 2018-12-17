<?php


include_once ("../models/database.class.php");
$db = new Database();

if (isset($_POST['post_id']) && isset($_POST['rev-select'])){

    $post = $db->DBSelectOne("posts", "*", array(
        array("column"=>"id_posts", "symbol"=>"=", "value"=>$_POST['post_id'])
    ));

    if (isset($post)){

        if (!isset($post['review_id1'])){
            $db->DBUpdateExpanded(
                "posts",
                array("review_id1"=>$_POST['rev-select']),
                array(array("column"=>"id_posts", "symbol"=>"=", "value"=>$_POST['post_id']))
            );

        } else if (!isset($post['review_id2'])){
            $db->DBUpdateExpanded(
                "posts",
                array("review_id1"=>$_POST['rev-select']),
                array(array("column"=>"id_posts", "symbol"=>"=", "value"=>$_POST['post_id']))
            );

        } else if (!isset($post['review_id2'])){
            $db->DBUpdateExpanded(
                "posts",
                array("review_id1"=>$_POST['rev-select']),
                array(array("column"=>"id_posts", "symbol"=>"=", "value"=>$_POST['post_id']))
            );

        }

    }

}

if (isset($_POST['delete-rev'])){

    $post = $db->DBSelectOne("posts", "*", array(
        array("column"=>"id_posts", "symbol"=>"=", "value"=>$_POST['post_id'])
    ));

    for ($i = 1; $i <= 3; $i++){
        if ($post['review_id'.$i] == $_POST['user_id']){
            $db->DBUpdateExpanded(
                "posts",
                array("review_id".$i=>"NULL"),
                array(array("column"=>"id_posts", "symbol"=>"=", "value"=>$_POST['post_id']))
            );
        }
    }

}

header("Location: ../../index.php?page=rvwass");
exit;
