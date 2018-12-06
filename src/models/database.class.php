<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 01-Dec-18
 * Time: 8:16 PM
 */

class Database
{
    // PDO objekt databaze
    private $db;

    public function __construct() {
        $dbName = "web_con";
        $this->db = new PDO("mysql:host=localhost;dbname=$dbName", "root", "");
    }

    private function doQuery($query){
        // Pro spravnou cestinu
        $q = "SET 
            character_set_results = 'utf8', 
            character_set_client = 'utf8', 
            character_set_connection = 'utf8', 
            character_set_database = 'utf8', 
            character_set_server = 'utf8'";
        $this->db->query($q);
        // Dotaz ktery nas zajima
        $res = $this->db->query($query);

        // Predelat!!
        if (!$res) {
            $error = $this->db->errorInfo();
            echo $error[2]; // toto by melo byt osetreno lepe !
            return null;
        } else {
            return $res;
        }
    }

    public function getAllContacts(){
        $q = "SELECT * FROM contacts;";
        $result = $this->doQuery($q);
        return $result->fetchAll();
    }

}