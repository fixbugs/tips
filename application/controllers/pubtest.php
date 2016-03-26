<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class pubtest extends GT_Controller{

    public function test(){
        pr("pubtest test action");
        $st = ' fdf fff ';
        var_dump(trimall($st));
    }

    public function count(){
        pr('count test');
        //setCountInfo();
        pr('count end');
    }

    public function pc(){
        pr('ok');
        //$this->load->library('mobile_detect');
        //$detect = $this->mobile_detect->getTabletDevices();
        //var_dump($detect);
    }

}