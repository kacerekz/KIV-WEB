<?php

include_once ("../models/database.class.php");
$db = new Database();

if (isset($_POST['post_id']) && isset($_POST['rev-select'])) {

    $db->DBUpdateExpanded("rating",
        array(
            "status"=>"1"       // Vraceno k prepracovani (coz vypada jako kdyby recenze jeste nebyla odeslana -> recenzent na ni muze znovu pracovat)
        ),
        array(
            array("column" => "posts_id_posts",   "symbol"=>"=",    "value" => $_POST['post_id']),
            array("column" => "users_id_user",   "symbol"=>"=",    "value" => $_POST['rev-select'])
        )
    );

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

    $db->DBUpdateExpanded("rating",
        array(
            "status"=>"3"       // Zamitnuto (Declined)
        ),
        array(
            array("column" => "posts_id_posts",   "symbol"=>"=",    "value" => $_POST['post_id']),
            array("column" => "users_id_user",   "symbol"=>"=",    "value" => $_POST['user_id'])
        )
    );

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

if (isset($_POST['accept'])){
    $db->DBUpdateExpanded(
        "posts",
        array("status" => "4"),
        array(array("column"=>"id_posts", "symbol"=>"=", "value"=>$_POST['post_id']))
    );

} else if (isset($_POST['decline'])){
    $db->DBUpdateExpanded(
        "posts",
        array("status" => "3"),
        array(array("column"=>"id_posts", "symbol"=>"=", "value"=>$_POST['post_id']))
    );
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