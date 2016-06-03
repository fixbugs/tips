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
 * abstract function as common function, rewrite CI_Model for easy use
 */
abstract class GT_Model extends CI_Model{

    /**
     * 连接的数据库表名
     */
    protected $_table_name = '';

    /**
     * 和数据库表名一致的表主键
     */
    protected $_pk = '';

    /**
     * 查询数据库时获取的字段名
     */
    protected $_field = '*';

    /**
     * 查询数据库时的排序数组，允许多个字段排序
     */
    protected $_order = array();

    /**
     * 初始化方法
     */
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    /**
     * 设置需要查询的字段，以逗号分隔
     * @param string $field  EM:id,url,cteatetime
     * @return void
     */
    public function select($field){
        if(!$field){
            $this->_field = $field;
        }else{
            $this->_field = '*';
        }
    }

    /**
     * 设置表名
     */
    public function setTableName($table_name){
        $this->_table_name = $table_name;
    }

    /**
     * 设置表主键
     */
    public function setTablePk($pk){
        $this->_pk = $pk;
    }

    /**
     * 根据条件获取单条数据
     * @param array $cond 条件数组
     * @return array
     */
    public function findByAttr($cond){
        $this->_check_model_value();
        if($this->_order){
            foreach($this->_order as $order_arr){
                $this->db->order_by($order_arr['order_field'], $order_arr['direction']);
            }
        }
        $query = $this->db->get_where($this->_table_name, $cond);
        $result = $query->row_array();
        if(!empty($result)){
            return $result;
        }
        return array();
    }

    /**
     * 根据条件获取所有数据
     * @param array $cond 条件数组
     * @param string $field 搜索的字段，默认为全部
     * @return array();
     */
    public function findAllByAttr($cond, $field='*'){
        $this->_check_model_value();
        if(isset($cond['page'])){
            if(!isset($cond['limit'])){
                $cond['limit'] = 10;
            }
            $page = intval($cond['page']) ? intval($cond['page']):1;
            unset($cond['page']);
        }
        if(isset($cond['limit'])){
            $limit = intval($cond['limit']) ? intval($cond['limit']):10;
            unset($cond['limit']);
        }
        if(!empty($cond)){
            $this->db->where($cond);
        }
        if($this->_field){
            $this->db->select($this->_field);
        }else{
            $this->db->select($field);
        }
        if($this->_order){
            foreach($this->_order as $order_arr){
                $this->db->order_by($order_arr['order_field'], $order_arr['direction']);
            }
        }
        if(isset($page) && $page){
            $offset = ($page - 1) * $limit;
            $query = $this->db->get($this->_table_name, $limit, $offset);
        }elseif(isset($limit) && $limit){
            $query = $this->db->get($this->_table_name, $limit);
        }else{
            $query = $this->db->get($this->_table_name);
        }
        return $query->result_array();
    }

    /**
     * model增加方法
     * @param array $data 需要插入数据库的数据
     * @return bool
     */
    public function insert($data){
        $this->_check_model_value();
        if(!$data[$this->_pk]){
            $data[$this->_pk] = make_shard_id(VSID);
        }
        return $this->db->insert($this->_table_name, $data);
    }

    /**
     * model 批量插入数据库方法
     * @param array $data 二位数组
     * @return bool
     */
    public function insertAll($data){
        $this->_check_model_value();
        foreach($data as $k=>$v){
            if(!$data[$k][$this->_pk]){
                $data[$k][$this->_pk] = make_shard_id(VSID);
            }
        }
        return $this->db->insert_batch($this->_table_name, $data);
    }

    /**
     * 根据id获取对应model表的信息
     * @param int $id 64位唯一ID
     * @return array
     */
    public function getById($id){
        $this->_check_model_value();
        $cond[$this->_pk] = $id;
        if($this->_order){
            foreach($this->_order as $order_arr){
                $this->db->order_by($order_arr['order_field'], $order_arr['direction']);
            }
        }
        $query = $this->db->get_where($this->_table_name, $cond);
        $result = $query->row_array();
        if(!empty($result)){
            return $result;
        }
        return array();
    }

    /**
     * 根据ID删除model表的对应信息
     * @param int $id 64位唯一ID
     * @return bool
     */
    public function deleteById($id){
        $this->_check_model_value();
        $cond[$this->_pk] = $id;
        $ret = $this->db->delete($this->_table_name, $cond);
        return $ret;
    }

    /**
     * 根据条件删除数据
     * @param array $cond 删除条件
     * @return bool
     */
    public function deleteAll($cond){
        $this->_check_model_value();
        return $this->db->delete($thi->_table_name, $cond);
    }

    /**
     * 根据主键值更新数据
     * @param array $data 需要更新的数据值
     * @param int $id 64位唯一ID
     * @return bool
     */
    public function updateBypk($data, $id){
        $this->_check_model_value();
        $this->db->where($this->_pk, $id);
        $ret = $this->db->update($this->_table_name, $data);
        return $ret;
    }

    /**
     * 根据键名和条件批量更新数据
     * @param array $data 需要更新的数据集，二维数组
     * @param string $key_name 需要更新的数据库列名
     * @return bool
     */
    public function updateAll($data, $key_name){
        $this->_check_model_value();
        foreach($data as $k=>$v){
            if(!$data[$k][$key_name]){
                return false;
            }
        }
        return $this->db->update_batch($this->_table_name, $data, $key_name);
    }

    /**
     * 根据参数获取对应字段的和值
     * @param string $table_col 表字段名
     * @param array $cond 统计的条件数组
     * @return array
     */
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

    /**
     * 根据参数获取对应字段个数
     * @param array $cond 统计的条件数组
     * @return array
     */
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

    /**
     * 获取数据库中表名的总条数
     * @return int
     */
    public function countTotal(){
        $this->_check_model_value();
        return $this->db->count_all($this->_table_name);
    }

    /**
     * 去除掉公共的不需要的参数
     * @param array $params 参数数组
     * @return void
     */
    public function escapeCommonParams(&$params){
        if(isset($params['debug'])){
            unset($params['debug']);
        }
        if(isset($params['debgu'])){
            unset($params['debgu']);
        }
    }

    /**
     * 获取最后一次执行的sql
     * @return string
     */
    public function getLastSql(){
        return $this->db->last_query();
    }

    /**
     * 校验model层的数据完整性，公共校验
     * @return void error info
     */
    private function _check_model_value(){
        if(!$this->_table_name){
            exit('table name needed');
        }
        if(!$this->_pk){
            exit('Undefined pk value');
        }
    }

    /**
     * 清楚查询条件
     * @return void
     */
    public function clearCondition(){
        $this->_field = '*';
    }

    /**
     * 设置model排序顺序
     * @param string $order
     * @param string $direction ASC or DESC
     * @return true
     */
    public function setOrderBy($order, $direction='ASC'){
        $tmp['order_field'] = $order;
        $tmp['direction'] = strtoupper($direction);
        $this->_order[] = $tmp;
        unset($tmp);
        return true;
    }

    /**
     * 获取表设计字段
     * @return array
     */
    public function getTableDesign(){
        $this->_check_model_value();
        $db = $this->db->database;
        $sql = 'select COLUMN_NAME as a from information_schema.COLUMNS where table_name ="'. $this->_table_name.'" and table_schema = "'. $db .'";';
        $data = $this->db->query($sql);
        $data = $data->result_array();
        if($data){
            foreach($data as $k=>$v){
                $result[] = $v['a'];
            }
            return $result;
        }
        return array();
    }

}