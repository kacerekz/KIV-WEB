<?php

include_once ("../models/database.class.php");
$db = new Database();

// Pokud uzivatel neni zablokovan, zablokuje ho a odebere mu recenze, je-li recenzentem
if ($_POST['blocked'] == 0 ){

    $db->DBUpdateExpanded(
        "users",
        array("blocked"=>"1"),
        array(array("column"=>"id_user", "symbol"=>"=", "value"=>$_POST['user_id']))
    );

    $user = $db->DBSelectOne("users", "*", array(
        array("column"=>"id_user", "symbol"=>"=", "value"=>$_POST['user_id'])
    ));

    // Odebrani recenzi - posun z vyssich pozic na nizsi pro spravnou funkcnost pridelovani recenzi
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


// Byl li uzivatel zablokovan, odblokuje ho
} else if ($_POST['blocked'] == 1 ){

    $db->DBUpdateExpanded(
        "users",
        array("blocked"=>"0"),
        array(array("column"=>"id_user", "symbol"=>"=", "value"=>$_POST['user_id']))
    );

}

header("Location: ../../index.php?page=usrmng");
exit;