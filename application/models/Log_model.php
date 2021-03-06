<?php

/**
 * log表模型，记录用户操作记录
 */
class Log_model extends GT_Model {
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
        parent::__construct();
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
            $ret = $this->insert($data);
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
     * 返回所有log
     * @return array
     */
    public function findAll(){
        $data = $this->findAllByAttr(array());
        if(!empty($data)){
            return $data;
        }else{
            return array();
        }
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
                $_error = 'undefined log id';
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
                    $this->trace_model->addTrace('delete', 'delete log, id:'.$message['id']);
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