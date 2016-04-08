<?php

class Tags_model extends GT_model{

    protected $_table_name = 'tags';

    protected $_pk = 'tag_id';

    public function __construct(){
        parent::__construct();
    }

}