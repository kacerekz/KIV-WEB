<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 06-Dec-18
 * Time: 7:34 PM
 */

class Session
{

    public function __construct(){
        session_start();
        setcookie(session_name(),session_id(),time()+30*60);
    }

    public function addToSession($name, $value){
        $_SESSION[$name] = $value;
    }

    public function removeFromSession($name){
        unset($_SESSION[$name]);
    }

    public function isSessionSet($name){
        return isset($_SESSION[$name]);
    }

    public function readSession($name){
        if($this->isSessionSet($name)){
            return $_SESSION[$name];
        } else {
            return null;
        }
    }

}