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

    //        return $query->row_array();

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

    private function createPasswordString($password){
        return MD5(MD5($password).'goitt'.MD5($password) );
    }

    public function addUser($params){
        if(!$params['username']){
            $this->setModelError('username undefine');
            return false;
        }
        if(!$params['password']){
            $this->setModelError('password undefine');
            return false;
        }
        $data['username'] = $params['username'];
        $data['password'] = $this->createPasswordString($params['password']);
        
    }

}