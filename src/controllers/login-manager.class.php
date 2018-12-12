<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 06-Dec-18
 * Time: 7:35 PM
 */

class LoginManager
{
    private $ses; // objekt MySession
    private $user = "user"; // nazev sessny pro jmeno

    public function __construct(){
        include_once("src/controllers/session.class.php");
        $this->ses = new Session;
    }

    public function login($userName){
        include_once("src/models/database.class.php");
        $db = new Database();
        $user = $db->DBSelectOne("users", "*", array(array("column" => "login", "value" => $userName, "symbol" => "=")), "");
        if (isset($user)){
            $this->ses->addToSession($this->user, $user);
            return $user;
        }
    }

    public function logout(){
        $this->ses->removeFromSession($this->user);
    }

    public function isUserLoged(){
        return $this->ses->isSessionSet($this->user);
    }

    public function getUser(){
        return  $this->ses->readSession($this->user);;
    }

}