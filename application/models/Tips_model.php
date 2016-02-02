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

    public function __construct(){
        parent::__construct();
    }

    /**
     * 获取全部
     * @param array $cond
     * @return array
     */
    public function findAll($cond){
        $query = $this->db->get_where($this->_table_name, $cond);
        $data = $query->result_array();
        if(!empty($data)){
            return $data;
        }else{
            return array();
        }
    }

    /**
     * 获取全部提示，多层级
     * @param string $user_id 用户id
     * @return array
     */
    public function getAllTips($user_id){
        $cond['user_id'] = $user_id;
        $this->db->set($cond);
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->group_by('parent_id');
        $query = $this->db->get();
        $data = $query->result_array();
        if(!empty($data)){
            return $data;
        }else{
            return array();
        }
    }

    /**
     * 添加提示
     * @param array $params 添加参数
     * @return bool
     */
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

    /**
     * 删除提示
     * @param array $ids id数组
     * @return bool
     */
    public function deleteTips($ids){
        if(!is_array($ids)){
            $ids = (array)$ids;
        }
        $success_num = 0;
        $total_num = count($ids);
        foreach($ids as $id){
            if($id <= 0){
                $_error = 'undefined tips id';
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
                    $this->_traceModel->addTrace('delete', 'delete tips, id:'.$message['id']);
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

}