<?php
class User_model extends CI_Model {
    /**
     * table name
     */
    protected $_table_name = 'user';

    public function __construct()
    {
        $this->load->database();
        //$this->db->from('user');
    }

    /**
     * 根据id获取用户信息
     * @return array
     */
    public function getById($id){
        $cond['user_id'] = $id;
        $query = $this->db->get_where($this->_table_name, $cond);
        $result = $query->row_array();
        if(!empty($result)){
            return $result;
        }
        return array();
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
        $data['user_id'] = make_shard_id(VSID);
        return $this->db->insert($this->_table_name, $data);
    }

    /**
     * 根据用户账号查找用户是否存在，
     * @param  string $username 用户名
     * @return array
     */
    public function findByUsername($username){
        $cond['username'] = $username;
        $query = $this->db->get_where($this->_table_name,$cond);
        return $query->row_array();
    }

    /**
     * 返回所有用户数据
     * @return array
     */
    public function findAll(){
        $cond['is_enabled'] = '1';
        $query = $this->db->get_where($this->_table_name,$cond);
        $data = $query->result_array();
        if(!empty($data)){
            return $data;
        }else{
            return array();
        }
    }

    /**
     * 通过id删除用户
     * @return bool
     */
    public function deleteById($id){
        $cond['user_id'] = $id;
        $ret = $this->db->delete($this->_table_name, $cond);
        return $ret;
    }

    /**
     * 根据用id或id数组删除用户
     * @param $ids array
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