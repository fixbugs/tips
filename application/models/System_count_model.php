<?php
/**
 * system_count表模型，记录系统点击model
 */
class System_count_model extends GT_Model {

    protected $_table_name = 'system_count';

    protected $_pk = 'id';

    public function __construct(){
        parent::__construct();
    }

}