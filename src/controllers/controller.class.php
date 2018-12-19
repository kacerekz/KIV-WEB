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

        if (isset($_GET['i'])){
            $data['index'] = $_GET['i'];

        } else if (!isset($data['index'])){
            $data['index'] = 0;
        }

        if(!isset($data['per_page'])){
            $data['per_page'] = 10;
        }

        if(!isset($data['max_index'])){
            $data['max_index'] = 0;
        }

        if ($data['index'] > $data['max_index']){
            $data['index'] = $data['index']-$data['per_page'];
        }

        if ($data['index'] < 0){
            $data['index'] = 0;
        }

        $cssf = "css/".$data['page'].".css";
        if (!key_exists("cssfile", $data) && file_exists($cssf ) && is_file($cssf)){
            $data["cssfile"] = array("$cssf ");
        }

        include("src/views/view.class.php");
        View::viewPage($this->twig, $data);
    }
}