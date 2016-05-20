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

    /**
     * 获取参数索取系统统计条目内容
     * @param array $cond
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getAllSystemCount($cond, $page=1, $limit=10){
        if(!$page){
            $page = 1;
        }else{
            $page = !empty($page) ? intval($page):1;
        }
        if(!$limit){
            $limit =10;
        }else{
            $limit = !empty($limit) ? intval($limit):1;
        }
        $offset = ($page - 1) * $limit;
        $query = $this->db->get_where($this->_table_name, $cond, $limit, $offset);
        $result = $query->result_array();
        if(!empty($result)){
            return $result;
        }
        return array();
    }

    //public function 

}