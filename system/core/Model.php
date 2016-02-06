<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2015, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/config.html
 */
class CI_Model {

    /**
     * model error info
     */
    protected $_error_message = '';

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		log_message('info', 'Model Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * __get magic
	 *
	 * Allows models to access CI's loaded classes using the same
	 * syntax as controllers.
	 *
	 * @param	string	$key
	 */
	public function __get($key)
	{
		// Debugging note:
		//	If you're here because you're getting an error message
		//	saying 'Undefined Property: system/core/Model.php', it's
		//	most likely a typo in your model code.
		return get_instance()->$key;
	}

    /**
     *@param stirng $message
     */
    public function setModelError($message){
        $this->_error_message = $message;
    }

    /**
     *@return string error message
     */
     public function getModelError(){
        return $this->_error_message;
     }

}

/**
 * abstract function as common function 
 */
abstract class GT_Model extends CI_Model{

    protected $_table_name = '';

    protected $_pk = '';

    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    public function getById($id){
        $this->_check_model_value();
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

    public function updateBypk($data, $id){
        $this->_check_model_value();
        $this->db->where($this->_pk, $id);
        $ret = $this->db->update($this->_table_name, $data);
        return $ret;
    }

    public function sum($table_col, $cond=array()){
        $this->_check_model_value();
        $this->db->select_sum($table_col);
        if($cond){
            $this->db->where($cond);
        }
        $query = $this->db->get($this->_table_name);
        $result = $query->row_array();
        if(!empty($result)){
            return $result;
        }
        return array();
    }

    public function count($cond=array()){
        $this->_check_model_value();
        if($cond){
            $this->db->where($cond);
        }
        $query = $this->db->count_all_results($this->_table_name, FALSE);
        $result = $query;
        if(!empty($result)){
            return $result;
        }
        return 0;
    }

    public function escapeCommonParams(&$params){
        if(isset($params['debug'])){
            unset($params['debug']);
        }
        if(isset($params['debgu'])){
            unset($params['debgu']);
        }
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