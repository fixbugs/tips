<?php

class Count_model extends GT_model{

    protected $_table_name = 'count';

    protected $_pk = 'id';

    public function __construct(){
        parent::__construct();
    }

    

    public function test(){
        $id = '6094091879463563272';
        $ret = $this->getById($id);
        var_dump($ret);
    }

}