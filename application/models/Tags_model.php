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

    /**
     * edit tags
     * @param array $params
     * @return bool
     */
    public function editTags($params){
        if( !isset($params[$this->_pk]) || empty($params[$this->_pk]) ){
            $this->setModelError('tags id needed');
            return false;
        }
        $ret = $this->updateBypk($params, $params[$this->_pk]);
        if($ret === false){
            $this->setModelError('tags edit flased with sql return false!');
            return false;
        }else{
            $this->setModelError('tags edit success');
            return true;
        }
    }

    /**
     * 删除标签
     * @param array $ids id数组
     * @return bool
     */
    public function deleteTags($ids){
        if(!is_array($ids)){
            $ids = (array)$ids;
        }
        $success_num = 0;
        $total_num = count($ids);
        foreach($ids as $id){
            if($id <= 0){
                $_error = 'undefined tags id';
                $this->setModelError($_error);
            }else{
                $message = $this->getById($id);
                $ret = $this->deleteById($id);
                $ret = (bool)$ret;
                if($ret == false){
                    $_error = 'delete fail';
                    $this->setModelError($_error);
                }else{
                    $success_num ++;
                    $this->trace_model->addTrace('delete', 'delete tags, tag name:'.$message['tag_name']);
                }
            }
        }
        if($success_num == $total_num){
            $this->setModelError('delete success');
            return true;
        }else{
            $this->setModelError("delete data total num:$total_num, success num:$success_num");
            return false;
        }
    }

    /**
     * get all tags by params
     * @param array $params
     * @return array
     */
    public function getAllTags($params){
        $cond = array();
        if(isset($params['page'])){
            $cond['page'] = $params['page'] ? $params['page']:1;
        }
        if(isset($params['limit'])){
            $cond['limit'] = $params['limit'] ? $params['limit']:10;
        }
        if(!$cond){
            return $this->findAllByAttr(array());
        }else{
            return $this->findAllByAttr($cond);
        }
    }

}