<?php

/**
 * 用户模块，处理用户相关内容
 */
class User_model extends GT_Model {
    /**
     * table name
     * @var string
     */
    protected $_table_name = 'user';

    /**
     * table private key
     * @var string
     */
    protected $_pk = 'user_id';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('trace_model');
    }


    /**
     * 登录验证函数,true 通过，false失败
     * @param array $params
     * @return bool
     */
    public function checkLogin($params){
        if(!$params['username']){
            $this->setModelError('username undefine');
            return false;
        }
        if(!$params['password']){
            $this->setModelError('password undefine');
            return false;
        }
        $cond['username'] = $params['username'];
        $cond['password'] = $this->createPasswordString($params['password']);
        $query = $this->db->get_where($this->_table_name,$cond);
        $db_result = $query->result();
        if(!$db_result){
            $this->setModelError('username and password try logining failed');
            return false;
        }
        $this->setModelError('login success');
        return true;
    }

    /**
     * 生成密码md5串
     * @param string $password
     * @return string  md5 value
     */
    private function createPasswordString($password){
        return MD5(MD5($password).'goitt'.MD5($password) );
    }

    /**
     * 添加用户
     * @param array $params
     * @return bool
     */
    public function addUser($params){
        if(!$params['username']){
            $this->setModelError('username undefine');
            return false;
        }
        if(!$params['password']){
            $this->setModelError('password undefine');
            return false;
        }
        if($this->findByUsername($params['username'])){
            $this->setModelError('username '.$params['username'].'has been exists');
            return false;
        }
        $data['username'] = $params['username'];
        $data['password'] = $this->createPasswordString($params['password']);
        $data['create_time'] = time();
        $data[$this->_pk] = make_shard_id(VSID);
        return $this->db->insert($this->_table_name, $data);
    }

    /**
     * 根据用户账号查找用户是否存在
     * @param  string $username 用户名
     * @return array
     */
    public function findByUsername($username){
        $cond['username'] = $username;
        $query = $this->db->get_where($this->_table_name, $cond);
        return $query->row_array();
    }

    /**
     * 根据参数获取用户表信息,不能根据用户密码获取
     * @param array $cond 搜索参数
     * @param int $page 获取的页数
     * @param int $limit 条目数
     * @return array
     */
    public function getUserByParams($cond, $page=1, $limit=10){
        if(isset($params['password'])){
            unset($params['password']);
        }
        if(!$page){
            $page = 1;
        }else{
            $page = intval($page) ? intval($page):1;
        }
        if(!$limit){
            $limit = 10;
        }else{
            $limit = intval($limit) ? intval($limit):1;
        }
        $offset = ($page - 1) * $limit;
        $query = $this->db->get_where($this->_table_name, $cond, $limit, $offset);
        $result = $query->result_array();
        if(!empty($result)){
            return $result;
        }
        return array();
    }

    /**
     * 编辑用户相关信息
     * @param array $params 参数
     * @return bool
     */
    public function editUser($params){
        if(!isset($params[$this->_pk]) || empty($params[$this->_pk])){
            $this->setModelError('user id needed');
            return false;
        }
        if($params['password']){
            if( !isset($params['re_password']) || ($params['password'] != $params['re_password']) ) {
                $this->setModelError('please set common password for password repeat');
                return false;
            }else{
                if(empty($params['password'])){
                    unset($params['password']);
                }else{
                    $params['password'] = $this->createPasswordString($params['password']);
                }
                unset($params['re_password']);
            }
        }
        $params['update_time'] = time();
        $ret = $this->updateBypk($params, $params[$this->_pk]);
        if($ret === false){
            return false;
        }else{
            return true;
        }

    }

    /**
     * 返回所有用户数据
     * @return array
     */
    public function findAll(){
        $cond['is_enabled'] = '1';
        $query = $this->db->get_where($this->_table_name, $cond);
        $data = $query->result_array();
        if(!empty($data)){
            return $data;
        }else{
            return array();
        }
    }

    /**
     * 根据用id或id数组删除用户
     * @param array $ids
     * @return bool
     */
    public function deleteUser($ids){
        if(!is_array($ids)){
            $ids = (array)$ids;
        }
        $success_num = 0;
        $total_num = count($ids);
        foreach($ids as $id){
            if($id <= 0){
                $_error = 'undefined user id';
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
                    $this->trace_model->addTrace('delete', 'delete user, id:'.$message['id']);
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