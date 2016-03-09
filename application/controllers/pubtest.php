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

}