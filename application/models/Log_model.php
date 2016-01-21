<?php

/**
 * log表模型，记录用户操作记录
 */
class Log_model extends CI_Model {
    /**
     * table name
     * @var string
     */
    protected $_table_name = 'log';

    /**
     * table private key
     * @var string
     */
    protected $_pk = 'id';

    public function __construct()
    {
        $this->load->database();
        //$this->db->from('user');
    }

    /**
     * 添加操作log
     * @param string $level
     * @param string $message
     * @param array $params
     * @return array
     */
    public function addLog($level='', $message='', $params=array()){
        $data = array();
        $return = false;
        $allow_level = array('warning', 'notice', 'error', 'add', 'edit', 'update', 'delete');

        if(is_array($allow_level) && !in_array($level, $allow_level)){
            $error = '"'.$level.'" level is not allow by log';
            $return = array_for_result(false, $error);
        }else{
            //need to do
            $data['user_id'] = USER_ID;
            $data['level'] = $level;
            $data['message'] = $message;
            $data['create_time'] = _NOW_;
            $data[$this->_pk] = make_shard_id(CORE_VSID);
            $ret = $this->db->insert($this->_table_name, $data);
            if(!$ret){
                $error = 'log add fail! level:'.$level.', message:'.$message;
                $return = array_for_result(false, $error);
            }else{
                $msg = 'log add success, level:'.$level.', message:'.$message;
                $return = array_for_result(true, $msg);
            }
            return $return;
        }
    }

    /**
     * 根据id获取日志表数据
     * @param int $id
     * @return array
     */
    public function getById($id){
        $cond[$this->_pk] = $id;
        $query = $this->db->get_where($this->_table_name, $cond);
        $result = $query->row_array();
        if(!empty($result)){
            return $result;
        }
        return array();
    }

    /**
     * 返回所有用户数据
     * @return array
     */
    public function findAll(){
        $query = $this->db->get_where($this->_table_name,array());
        $data = $query->result_array();
        if(!empty($data)){
            return $data;
        }else{
            return array();
        }
    }

    /**
     * 通过id删除日志
     * @return bool
     */
    public function deleteById($id){
        $cond[$this->_pk] = $id;
        $ret = $this->db->delete($this->_table_name, $cond);
        return $ret;
    }

    /**
     * 根据用id或id数组删除日志
     * @param  array $ids
     * @return bool
     */
    public function deleteLogs($ids){
        if(!is_array($ids)){
            $ids = (array)$ids;
        }
        $success_num = 0;
        $total_num = count($ids);
        foreach($ids as $id){
            if($id <= 0){
                $_error = 'undefined blank list id';
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
                    $this->_traceModel->addTrace('delete', 'delete ip blank list, id:'.$message['id']);
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