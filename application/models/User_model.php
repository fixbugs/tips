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

}