<?php
/**
 * api_log表模型，记录api获取相关log
 */
class Api_log_model extends GT_Model {
    /**
     * table name
     */
    protected $_table_name = 'api_log';

    /**
     * table private key
     * @var string
     */
    protected $_pk = 'id';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('trace_model');
    }

    /**
     * 添加apilog
     * @param string $level
     * @param string $message
     * @param string $error_num
     * @param array $params
     * @return array
     */
    public function addLog($level='', $message='', $error_num='', $params=array()){
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
            $data['error_num'] = $error_num;
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
     * 返回所有api log data
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
     * 根据用id或id数组删除API日志
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
                $_error = 'undefined api log id';
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
                    $this->trace_model->addTrace('delete', 'delete api log, id:'.$message['id']);
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