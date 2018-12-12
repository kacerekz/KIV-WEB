<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 02-Dec-18
 * Time: 2:47 PM
 */

require("controller.class.php");

class NewReview extends Controller
{
    public function viewPage($data){
        $data["scripts"] = array("https://cdn.ckeditor.com/ckeditor5/11.1.1/classic/ckeditor.js");
        parent::viewPage($data);
    }
}