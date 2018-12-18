<?php

include_once ("../models/database.class.php");
$db = new Database();

if (isset($_POST['post_id']) && isset($_POST['rev-select'])) {

    $post = $db->DBSelectOne("posts", "*", array(
        array("column" => "id_posts", "symbol" => "=", "value" => $_POST['post_id'])
    ));

    if (isset($post)) {

        if ($post['reviewer_id1'] == 0){
            $db->DBUpdateExpanded(
                "posts",
                array("reviewer_id1"=>$_POST['rev-select']),
                array(array("column"=>"id_posts", "symbol"=>"=", "value"=>$_POST['post_id']))
            );

        } else if ($post['reviewer_id2'] == 0){
            $db->DBUpdateExpanded(
                "posts",
                array("reviewer_id2"=>$_POST['rev-select']),
                array(array("column"=>"id_posts", "symbol"=>"=", "value"=>$_POST['post_id']))
            );

        } else if ($post['reviewer_id3'] == 0){
            $db->DBUpdateExpanded(
                "posts",
                array("reviewer_id3"=>$_POST['rev-select']),
                array(array("column"=>"id_posts", "symbol"=>"=", "value"=>$_POST['post_id']))
            );

        }

    }

}

if (isset($_POST['delete-rev'])){

    $post = $db->DBSelectOne("posts", "*", array(
        array("column"=>"id_posts", "symbol"=>"=", "value"=>$_POST['post_id'])
    ));

    $del_index = null;

    for ($i = 1; $i <= 3; $i++){
        if ($post['reviewer_id'.$i] == $_POST['user_id']){
            $db->DBUpdateExpanded(
                "posts",
                array("reviewer_id".$i=>"0"),
                array(array("column"=>"id_posts", "symbol"=>"=", "value"=>$_POST['post_id']))
            );
            $del_index = $i;

        } else if (isset($del_index) && $post['reviewer_id'.$i] != 0) {
            $db->DBUpdateExpanded(
                "posts",
                array("reviewer_id".$del_index => $post['reviewer_id'.$i]),
                array(array("column"=>"id_posts", "symbol"=>"=", "value"=>$_POST['post_id']))
            );
            $db->DBUpdateExpanded(
                "posts",
                array("reviewer_id".$i => "0"),
                array(array("column"=>"id_posts", "symbol"=>"=", "value"=>$_POST['post_id']))
            );
            $del_index = $i;
        }
    }



}

header("Location: ../../index.php?page=rvwass");
exit;

/*
 echo "<pre style='margin-top: 60px'>";
var_dump($post);
echo "</pre>";
}
}
/*
*/