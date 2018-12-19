<?php

if(isset($_POST['user_id'])){

    include ("../models/database.class.php");
    $db = new Database();

    $user = $db->DBSelectOne("users", "*", array(
        array("column"=>"id_user", "symbol"=>"=", "value"=>$_POST['user_id'])
    ));

    $user = $db->DBSelectOne("users", "*", array(
        array("column"=>"id_user", "symbol"=>"=", "value"=>$_POST['user_id'])
    ));

    if ($user['rights_id_rights'] == 2){

        $posts = array();

        for ($j = 1; $j <= 3; $j++) {
            $posts_temp = $db->DBSelectAll("posts", "*", array(
                array("column" => "reviewer_id".$j, "symbol" => "=", "value" => $_POST['user_id']),
                array("column" => "status", "symbol" => "<", "value" => "3")
            ));

            foreach ($posts_temp as $p){
                $posts[] = $p;
            }
        }

        foreach ($posts as $post){
            $del_index = null;

            for ($i = 1; $i <= 3; $i++){
                if ($post['reviewer_id'.$i] == $_POST['user_id']){
                    $db->DBUpdateExpanded(
                        "posts",
                        array("reviewer_id".$i=>"0"),
                        array(array("column"=>"id_posts", "symbol"=>"=", "value"=>$post['id_posts']))
                    );
                    $del_index = $i;

                } else if (isset($del_index) && $post['reviewer_id'.$i] != 0) {
                    $db->DBUpdateExpanded(
                        "posts",
                        array("reviewer_id".$del_index => $post['reviewer_id'.$i]),
                        array(array("column"=>"id_posts", "symbol"=>"=", "value"=>$post['id_posts']))
                    );
                    $db->DBUpdateExpanded(
                        "posts",
                        array("reviewer_id".$i => "0"),
                        array(array("column"=>"id_posts", "symbol"=>"=", "value"=>$post['id_posts']))
                    );
                    $del_index = $i;
                }
            }
        }
    }

    $db->DBUpdateExpanded(
        "rating",
        array("users_id_user" => "1"), // GhostUser
        array(array("column"=>"users_id_user", "symbol"=>"=", "value"=>$_POST['user_id']))
    );

    for ($i = 1; $i <= 3; $i++){
        $db->DBUpdateExpanded(
            "posts",
            array("reviewer_id".$i => "1"), // GhostUser
            array(array("column"=>"reviewer_id".$i, "symbol"=>"=", "value"=>$_POST['user_id']))
        );
    }

    $db->DBUpdateExpanded(
        "posts",
        array("users_id_user" => "1"), // GhostUser
        array(array("column"=>"users_id_user", "symbol"=>"=", "value"=>$_POST['user_id'])),
        "");

    $db->DBDelete(
        "posts",
        array(array("column"=>"users_id_user", "symbol"=>"=", "value"=>$_POST['user_id'])),
        "");

    $db->DBDelete(
        "users",
        array(array("column"=>"id_user", "symbol"=>"=", "value"=>$_POST['user_id'])),
        "");

}

header("Location: ../../index.php?page=usrmng");
exit;