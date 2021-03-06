<?php

/**
 * 任务模型，处理任务相关内容
 */
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
        $this->load->model('trace_model');
        //$this->db = $this->load->database('testdb', true);
    }

    /**
     * 获取全部
     * @param array $cond
     * @return array
     */
    public function findAll( $cond=array()){
        $data = $this->findAllByAttr($cond);
        if(!empty($data)){
            return $data;
        }
        return array();
    }

    /**
     * 根据参数获取提示
     * @param array $cond 获取参数
     * @param int $page 页数
     * @param int $limit 条目数
     * @return array
     */
    public function getTipsByParams($cond, $page=1, $limit=10){
        $cond['page'] = $page;
        $cond['limit'] = $limit;
        $result = $this->findAllByAttr($cond);
        if(!empty($result)){
            $data = $this->getTree($result, 0);
            return $data;
        }
        return array();
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
        $data = $this->getTree($data, '0');
        if(!empty($data)){
            return $data;
        }
        return array();
    }

    /**
     * 获取无线分级树
     * @param array $data 数据数组
     * @param int   $parent_id 父id，获取父节点传入0
     * @return array
     */
    public function getTree($data, $parent_id=0){
        $tree = array();
        foreach($data as $k=>$v){
            if($v['parent_id'] == $parent_id){
                $v['parent_id'] = $this->getTree($data, $v[$this->_pk]);
                $tree[] = $v;
            }
        }
        return $tree;
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
        $ret = $this->insert($data);
        if($ret){
            $this->setModelError('tips add success');
        }else{
            $this->setModelError('tips add failed');
        }
        return $ret;
    }

    /**
     * 编辑提示
     * @param array $parmas
     * @return bool
     */
    public function editTips($params){
        if( !isset($params[$this->_pk]) || empty($params[$this->_pk]) ){
            $this->setModelError('tips id needed');
            return false;
        }
        $ret = $this->updateBypk($params, $params[$this->_pk]);
        if($ret === false){
            $this->setModelError('tips edit flased with sql return false!');
            return false;
        }else{
            $this->setModelError('tips edit success');
            return true;
        }

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
                    $this->trace_model->addTrace('delete', 'delete tips, id:'.$message['id']);
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
     * 更改提示任务状态
     * @param int $tips_id
     * @param string $set_status
     * @return bool
     */
    public function changeStatus($tipsId, $setStatus=''){
        $tipsData = $this->getById($tipsId);
        if(!$tipsData){
            $this->setModelError("tips id error for,please use anbled tips id");
            return false;
        }
        $lastStatus = $set_status ? $setStatus:$tipsData['status'];
        $nextStatus = self::getNextStatus($lastStatus);
        $data['status'] = $nextStatus;
        $data[$this->_pk] = $tipsId;
        return $this->editTips($data);
    }

    /**
     * 获取下一个提示状态
     * @param string $status
     * @return string
     */
    public function getNextStatus($status="nonstart"){
        $result = '';
        switch($status){
        case 'end': $result='over';break;
        case 'continue': $result='completed';break;
        case 'hold': $result='continue';break;
        case 'handle': $result='hold';break;
        case 'start'; $result='handle';break;
        case 'nonstart': $result='start';break;
        default:$result='start';break;
        }
        return $result;
    }

}