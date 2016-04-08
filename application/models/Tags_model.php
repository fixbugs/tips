<?php

class Tags_model extends GT_model{

    protected $_table_name = 'tags';

    protected $_pk = 'tag_id';

    public function __construct(){
        parent::__construct();
    }

    /**
     * add tags model function
     * @param array $params input Array
     * @return bool
     */
    public function addTags($params){
        if(!isset($params['tag_name']) || empty($params['tag_name'])){
            $this->setModelError('tag name needed');
            return false;
        }
        if(!isset($params['tag_type']) || empty($params['tag_type']) ){
            $this->setModelError('tag type needed');
            return false;
        }
        $data['tag_name'] = $params['tag_name'];
        $data['tag_type'] = $params['tag_type'];
        $data['user_id'] = USER_ID;
        $data['create_time'] = time();
        $data['tag_id'] = make_shard_id(VSID);
        return $this->insert($data);
    }

}