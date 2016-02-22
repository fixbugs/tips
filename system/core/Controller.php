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
 * Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {

	/**
	 * Reference to the CI singleton
	 *
	 * @var	object
	 */
	private static $instance;

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		self::$instance =& $this;

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');
		$this->load->initialize();
        //$this->_initContorller();
		log_message('info', 'Controller Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Get the CI singleton
	 *
	 * @static
	 * @return	object
	 */
	public static function &get_instance()
	{
		return self::$instance;
	}

	/**
     * 渲染JSON数据
     * @param array $data
     * @param array $options  json_encode的参数,详细参见json_encode参数
     */
    public function renderJson($data=array(), $options=JSON_UNESCAPED_UNICODE)
    {
        ob_clean();
        $content = json_encode($data, $options);
        //gzip压缩
        if(!headers_sent() && extension_loaded("zlib") && strstr($_SERVER["HTTP_ACCEPT_ENCODING"],"gzip")
            && strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 6') === false){
            $content = gzencode($content,9);
            header("Content-Encoding: gzip");
            header("Vary: Accept-Encoding");
            header("Content-Length: ".strlen($content));
        }
        if(!headers_sent()){
            header('Content-type: application/json; charset=utf-8');
        }
        echo $content;
        exit(0);
    }

    /**
     * 渲染jsonp和json数据
     * @param  array $result  需要返回的数据结果数组
     * @param  array $params  用户传入的参数数组
     * @param  string $options json_encode的参数,详细参见json_encode参数
     * @return finally          
     */
    public function renderJsonp($result, $params, $options=JSON_UNESCAPED_UNICODE){
        $this->getViewer()->needLayout(false);
        ob_clean();
        $content = json_encode($result, $options);
        if(!empty($params['jsonpcallback'])){
            $jsonpcallback = isset($params['jsonpcallback']) ? trim($params['jsonpcallback']) : '';
            if(!empty($jsonpcallback)){
                echo $jsonpcallback."(".$content.")";
            }else{
                echo $content;
            }
        }else if(!empty($params['callback'])){
            $callback = isset($params['callback']) ? trim($params['callback']) : '';
            if(!empty($callback)){
                echo $callback."(".$content.")";
            }else{
                echo $content;
            }
        }else{
            echo $content;
        }
        exit(0);
    }

    /**
     * 返回是否为ajax请求
     * @return boolean
     */
    public function isAjax()
    {
        return array_key_exists('HTTP_X_REQUESTED_WITH', $_SERVER);
    }

    /**
     * 返回是否为post表单
     * @return boolean
     */
    public function isPost()
    {
        return 'POST' == $_SERVER['REQUEST_METHOD'];
    }

}
