<?php
/**
 * 定义系统常量
 */ 

define('_DIR_SEPARATOR_',DIRECTORY_SEPARATOR);
define('_DS_', 			_DIR_SEPARATOR_);
define('_PS_', 			PATH_SEPARATOR);
define('_ROOT_', 		dirname(dirname(__FILE__)) . _DIR_SEPARATOR_);

define('_DOMAIN_', 		'http://' . 'tips.goitt.com');
define('_URL_', 		_DOMAIN_ . $_SERVER['REQUEST_URI']);

define('_IS_AJAX_',		isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? true : false);
define('_MAGIC_QUOTES_GPC_', get_magic_quotes_gpc() ? true : false);

define('_NOW_', time());

define('CORE_VSID', 300);

define('VSID',CORE_VSID);

/**
 *定义系统设置
 */
date_default_timezone_set('PRC');