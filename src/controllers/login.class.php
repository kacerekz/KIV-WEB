<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 02-Dec-18
 * Time: 2:48 PM
 */

require_once "controller.class.php";

class Login extends Controller
{
    public $auth = array("0");

    public function viewPage($data){

        if (isset($_POST['login']) || isset($_POST['signup'])){

            include_once ("src/models/database.class.php");
            $db = new Database();

            // PRIHLASENI, kontrola hesla & zda neni uzivatel zablokovany
            if (isset($_POST['login'])){

                $login = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
                $pass = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

                $user = $db->DBSelectOne("users", "*", array(
                    array("column"=>"login", "symbol" => "=", "value" => $login)));

                // Overeni udaju
                if (password_verify($pass, $user['pass'])){
                    // Neni uzivatel zablokovany?
                    if ($user['blocked'] == 0){
                        $data['lm']->login($user);
                        header("Location: index.php?page=home");
                        exit;

                    } else {
                        $data['blocked_user'] = true;
                    }
                    // Spatne udaje
                } else {
                    $data['wrong_login'] = true;
                }


                // REGISTRACE, kontrola, ze neexistuje stejny login
            } else {

                $fullname = filter_var($_POST['fullname'], FILTER_SANITIZE_STRING);
                $login = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
                $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
                $pass = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

                $user = $db->DBSelectOne("users", "login", array(
                    array("column"=>"login", "symbol" => "=", "value" => $login)));

                // Neexistuje jiz stejny login?
                if (isset($user['login'])){
                    $data['usrname_not_unique'] = 1;

                } else {
                    $db->DBInsertExpanded("users", array(
                            array("column" => "name",               "value" => $fullname),
                            array("column" => "login",              "value" => $login),
                            array("column" => "pass",               "value" => password_hash($pass, PASSWORD_DEFAULT)),
                            array("column" => "email",              "value" => $email),
                            array("column" => "rights_id_rights",   "value" => "1"),
                            array("column" => "blocked",          "value" => "0"))
                    );

                    $user = $db->DBSelectOne("users", "*", array(
                        array("column"=>"login", "symbol" => "=", "value" => $login)));

                    $data['lm']->login($user);

                    header("Location: index.php?page=home");
                    exit;
                }

            }
        }

        $data["cssfile"] = array("css/login.css", "css/form.css");

        echo "<pre style='margin-top: 60px'>";
        var_dump($_POST);
        echo "</pre>";

        parent::viewPage($data);
    }

}