<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GT_Model extends CI_Model{

    protected $_table_name = '';

    protected $_pk = '';

    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    public function getById($id){
        $his->_check_model_value();
        $cond[$this->_pk] = $id;
        $query = $this->db->get_where($this->_table_name, $cond);
        $result = $query->row_array();
        if(!empty($result)){
            return $result;
        }
        return array();
    }

    public function deleteById($id){
        $this->_check_model_value();
        $cond[$this->_pk] = $id;
        $ret = $this->db->delete($this->_table_name, $cond);
        return $ret;
    }

    private function _check_model_value(){
        if(!$this->_table_name){
            exit('table name needed');
        }
        if(!$this->_pk){
            exit('Undefined pk value');
        }
    }

}