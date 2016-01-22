<?php

class Test extends CommonModel{

    protected $_table_name = 'user';

    protected $_pk = '';

    public function __construct(){
        parent::__construct();
    }

    public function test(){
        $id = '6094091879463563272';
        $ret = $this->getById($id);
        var_dump($ret);
    }

}