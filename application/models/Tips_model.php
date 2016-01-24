<?php

class Tips_model extends GT_Model{
    /**
     * table name
     * @var string
     */
    protected $_table_name = 'tips';

    /**
     * table private key
     * @var string
     */
    protected $_pk = 'tips_id';

    public function __construct()
    {
        parent::__construct();
    }

    public function findAll(){
        $query = $this->db->get_where($this->_table_name, $cond);
        $data = $query->result_array();
        if(!empty($data)){
            return $data;
        }else{
            return array();
        }
    }

    public function addTips($params){
        if( !isset($params['message']) || empty($params['message'])){
            $this->setModelError('tips messge needed');
            return false;
        }
        if(isset($params['parent_id']) && $params['parent_id'] && $this->getById($params['parent_id'])){
            $data['parent_id'] = $params['parent_id'];
        }
        $data['tips_message'] = $params['message'];
        $data[$this->_pk] = make_shard_id(VSID);
        $data['create_time'] = time();
        $data['user_id'] = USER_ID;
        $data['status'] = 'nonstart';
        return $this->db->insert($this->_table_name, $data);
    }



}