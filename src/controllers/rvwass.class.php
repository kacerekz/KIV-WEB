<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 06-Dec-18
 * Time: 10:03 PM
 */

require("controller.class.php");

class Rvwass extends Controller
{
    public function viewPage($data){
        $data["cssfile"] = array("css/reviews-table.css");
        parent::viewPage($data);
    }
}