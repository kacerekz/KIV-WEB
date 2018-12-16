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
        include_once("session.class.php");
        $this->ses = new Session;
    }

    public function login($user){
        $this->ses->addToSession($this->user, $user);
    }

    public function logout(){
        $this->ses->removeFromSession($this->user);
    }

    public function isUserLoged(){
        return $this->ses->isSessionSet($this->user);
    }

    public function getUser(){
        return  $this->ses->readSession($this->user);
    }

}