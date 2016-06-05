<?php
/**
 * trace_model表模型，记录api获取相关log
 */
class Trace_model extends GT_Model {
    /**
     * table name
     */
    protected $_table_name = 'trace';

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
     * 添加trace
     * @param string $level
     * @param string $message
     * @param string $obj_id
     * @param string $obj_type
     * @param array $params
     * @return array
     */
    public function addTrace($level='', $message='', $obj_id='', $obj_type='',$params=array()){
        $data = array();
        $return = false;
        $allow_level = array('warning', 'notice', 'error', 'add', 'edit', 'update', 'delete');
        $obj_allow_level = array('user','tips');
        if(!in_array($obj_type, $obj_allow_level)){
            $error = '"'.$obj_type.'" type is not allow by trace object type';
            return array_for_result(false, $error);
        }

        if(is_array($allow_level) && !in_array($level, $allow_level)){
            $error = '"'.$level.'" level is not allow by trace';
            $return = array_for_result(false, $error);
        }else{
            //need to do
            $data['user_id'] = USER_ID;
            $data['level'] = $level;
            $data['message'] = $message;
            $data['obj_id'] = $obj_id;
            $data['obj_type'] = $obj_type;
            $data['create_time'] = _NOW_;
            $ret = $this->insert($data);
            if(!$ret){
                $error = 'trace add fail! level:'.$level.', message:'.$message;
                $return = array_for_result(false, $error);
            }else{
                $msg = 'trace add success, level:'.$level.', message:'.$message;
                $return = array_for_result(true, $msg);
            }
            return $return;
        }
    }


    /**
     * 返回所有操作记录
     * @return array
     */
    public function findAll(){
        $data =  $this->findAllByAttr(array());
        if(!empty($data)){
            return $data;
        }else{
            return array();
        }
    }

    /**
     * 根据用id或id数组删除trace
     * @param  array $ids
     * @return bool
     */
    public function deleteTaces($ids){
        if(!is_array($ids)){
            $ids = (array)$ids;
        }
        $success_num = 0;
        $total_num = count($ids);
        foreach($ids as $id){
            if($id <= 0){
                $_error = 'undefined trace id';
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