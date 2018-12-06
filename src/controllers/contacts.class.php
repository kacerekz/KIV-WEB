<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 02-Dec-18
 * Time: 2:47 PM
 */

class Contacts
{
    private $twig;

    public function __construct($twig) {
        $this->twig = $twig;
    }

    public function viewPage($data){
        // Predava sablone kontakty
        include("src/models/database.class.php");
        $db = new Database();
        $contacts = $db->getAllContacts();
        $data['contacts'] = $contacts;

        // Zobrazuje sablonu
        include("src/views/contactsview.class.php");
        $data["cssfile"] = array("css/contacts.css");
        ContactsView::viewPage($this->twig, $data);
    }

}