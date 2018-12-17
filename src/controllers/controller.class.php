<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 06-Dec-18
 * Time: 11:19 PM
 */

class Controller
{
    protected $twig;
    public $auth = array();

    public function __construct($twig) {
        $this->twig = $twig;
    }

    public function viewPage($data){
        $cssf = "css/".$data['page'].".css";
        if (!key_exists("cssfile", $data) && file_exists($cssf ) && is_file($cssf)){
            $data["cssfile"] = array("$cssf ");
        }

        include("src/views/view.class.php");
        View::viewPage($this->twig, $data);
    }
}