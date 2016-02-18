<?php

/**
 * 打印debug信息
 * @param mixed $str 需要打印的字符串或数组
 * @return void
 */
function pr($str){
    print_r("---------");
    print_r($str);
    print_r("---------");
}

/**
 * 将时间戳转换成中文时间输出格式
 * @param int $timestamp 时间戳
 * @return string
 */
function convert_time_to_zh($timestamp = 0){
    return date('Y-m-d H:i:s', $timestamp);
}

/**
 * 循环创建目录
 *
 * @param string $dir
 * @param int $mode
 * @return boolean
 */
function mk_dir($dir, $mode = 0755)
{
    if (is_dir($dir) || @mkdir($dir,$mode))
    {
        return true;
    }
    if (!mk_dir(dirname($dir),$mode)) {
        return false;
    }
    return @mkdir($dir,$mode);
}

/**
 * 递归将特殊字符为HTML字符编码
 *
 * @param array|string  $data
 * @return array|string
 */
function dhtmlspecialchars($data)
{
    if (is_array($data)) {
    	foreach ($data as $key => $value) {
    	    $data[$key] = dhtmlspecialchars($value);
    	}
    } else {
        $data = htmlspecialchars($data);
    }
    return $data;
}

/**
* @去除XSS（跨站脚本攻击）的函数
* @author By qiqing
* @param	string	 $val 字符串参数，可能包含恶意的脚本代码如<script language="javascript">alert("hello world");</script>
* @return 	string	 处理后的字符串
**/
function removeXss($val){
	// remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
	// this prevents some character re-spacing such as <java\0script>
	// note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
	$val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);

	// straight replacements, the user should never need these since they're normal characters
	// this prevents like <IMG SRC=@avascript:alert('XSS')>
	$search = 'abcdefghijklmnopqrstuvwxyz';
	$search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$search .= '1234567890!@#$%^&*()';
	$search .= '~`";:?+/={}[]-_|\'\\';
	for ($i=0; $i<strlen($search); $i++){
		// ;? matches the ;, which is optional
		// 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
		// @ @ search for the hex values
		$val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
		// @ @ 0{0,7} matches '0' zero to seven times
		$val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
	}

	// now the only remaining whitespace attacks are \t, \n, and \r
	$ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
	$ra2 = array('on', 'onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
	$ra = array_merge($ra1, $ra2);

	$found = true; // keep replacing as long as the previous round replaced something
	while($found == true){
		$val_before = $val;
		for ($i = 0; $i < sizeof($ra); $i++) {
			$pattern = '/';
			for ($j = 0; $j < strlen($ra[$i]); $j++) {
				if($j > 0){
					$pattern .= '(';
					$pattern .= '(&#[xX]0{0,8}([9ab]);)';
					$pattern .= '|';
					$pattern .= '|(&#0{0,8}([9|10|13]);)';
					$pattern .= ')*';
				}
				$pattern .= $ra[$i][$j];
			}
			$pattern .= '/i';
			$replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
			$val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
			if ($val_before == $val) {
				// no replacements were made, so exit the loop
				$found = false;
			}
		}
	}
	return $val;
}

/**
 * 递归将HTML字符编码还原
 *
 * @param array|string $data
 * @return array|string
 */
function dhtmlspecialchars_decode($data)
{
    if (is_array($data)) {
    	foreach ($data as $key => $value) {
    	    $data[$key] = dhtmlspecialchars_decode($value);
    	}
    } else {
        $data = htmlspecialchars_decode($data);
    }
    return $data;
}

/**
 * 获取客户端IP, 参考zend frmaework
 *
 * @param  boolean $checkProxy  是否检查代理
 * @return string
 */
function getClientIp($checkProxy = true)
{
    $ip = '127.0.0.1';
    if($checkProxy && isset($_SERVER['HTTP_CLIENT_IP']))
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    elseif($checkProxy && isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    elseif(!empty($_SERVER['REMOTE_ADDR']))
        $ip = $_SERVER['REMOTE_ADDR'];

    return $ip;
}

/**
 * 注册全局环境变量
 * @param string	$key	变量名
 * @param mixed		$value	变量值
 * @param string	$type	变量的命名空间
 * @return void
 */
function add_gvar($key, $value, $type = ''){
    if(empty($key)){
        return false;
    }else{
        if(!empty($type)){
            $GLOBALS[$type][$key] = $value;
        } else {
            $GLOBALS[$key] = $value;
        }
        return true;
    }
}

/**
 * 获取全局环境变量
 * @param string $key	变量名
 * @param string $type	变量所在的命名空间
 * @return	mixed
 */
function get_gvar($key, $type=''){
    if(empty($key)){
        return false;
    }else{
        if(!empty($type)){
            return isset($GLOBALS[$type][$key]) ? $GLOBALS[$type][$key] : false;
        } else {
            return isset($GLOBALS[$key]) ? $GLOBALS[$key] : false;
        }
    }
}

/**
 * 获取网址域名
 *
 * @param string $url
 * @return mixed string/bool
 */
function getUrlDomain($url){
    if(preg_match('/^(https?:\/\/)?([a-z0-9.-]+)(\/.*)?$/i', $url,$matches)){
        return $matches[2];
    }
    return false;
}

/**
 * 检出输入中是否有空格,有则返回true，无则返回false
 * @param  string $value [description]
 * @return bool
 */
function spaceCheck($value){
    $trim_value = trim($value);
    $pos_ret = strpos($trim_value, " ");
    if(!($pos_ret === false)){
        return true;
    }
    if(strlen($trim_value) != strlen($value)){
        return true;
    }
    return false;
}

/**
 * 判断字符串前缀
 *
 * @param $str 需要判断的字符串
 * @param $needle 前缀
 * @return bool
 */
function startWith($str,$needle){
    return strpos($str,$needle) === 0;
}

/**
 * 判断字符串后缀
 *
 * @param $str 需要判断的字符串
 * @param $needle 后缀
 * @return bool
 */
function endWith($str,$needle){
    $length = strlen($needle);
    if($length == 0){
        return true;
    }
    return (substr($str,-$length) === $needle);
}

/**
 * 字符串命名风格转换
 * type 0 将Java风格转换为C的风格 1 将C风格转换为Java的风格
 * @param string $name 字符串
 * @param integer $type 转换类型
 * @return string
 */
function parseName($name, $type=0) {
    if ($type) {
        return ucfirst(preg_replace_callback('/_([a-zA-Z])/', function($match){return strtoupper($match[1]);}, $name));
    } else {
        return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
    }
}

/**
 * 校验正常输入，去除非法输入，返回true或false
 *
 * @param string $value 需要校验的输入字符串
 * @return bool
 */
function checkNormalInput($value){
    return preg_match( "/^[a-zA-Z0-9~!@#$%^&*()_ （）{}【】、|，。 ~！：；;\.\x80-\xff]+/", $value, $match) && ($match[0] == $value);
}

/**
 * 返回前端操作结果需要的对象
 * @param       bool    $status         状态信息(true或false)
 * @param       string  $message        需要输出的提示信息
 * @param       mixed   $params         需要输出的附加信息
 * @return      array   封装后的输出信息格式
 */
function array_for_result($status, $message = '', $params=array(), $redirect_url='')
{
    return array(
        'status'        => (bool)$status,
        'message'       => $message,
        'params'        => $params,
        'redirect'      => $redirect_url
    );

}

/**
 * 为前端列表页输出相应的数据
 * @param array         $data           列表的数据信息
 * @param array         $global         输出全局的变量信息
 * @param bool          $status         状态信息
 * @param array         $message        输出信息
 * @param array         $fields         表头需要显示的字段
 * @return array        前端列表所需要的数据格式
 */
function array_for_list($data=array(), $global=array(), $status=true, $message='', $fields = array()){
    if($status == false && empty($message)){
        $message = '由于某些原因，获取数据失败';
    }
    if($status == true && empty($message) && empty($data)){
        $message = '暂无相关数据';
    }
    if(!is_array($global)){
        $global = (array)$global;
    }
    if(!is_array($fields)){
        $fields = (array)$fields;
    }
    if(!is_array($data)){
        $data = (array)$data;
    }

    if(empty($global)){
        $global = $_GET;
    }

    $return = array(
        'result' =>     array(
            'status'    =>      $status,
            'message'   =>      $message
        ),
        'global' =>     $global,
        'fields' =>     empty($fields) ? array():array($fields),
        'data'   =>     $data,
    );

    return $return;
}

/**
 * 动态代码执行
 * @param string $func_str 函数字符串
 * @param array  $func_params 函数参数
 * @return mixed
 */
function get_dynamic_code_result($func_str, $func_params=array()){
    if(!$func_str){
        return false;
    }
    $t_str = str_replace('\$', '$', $func_str);
    try{
        $funa = eval('return '.$t_str);
    }catch (Exception $e){
        return false;
    }
    return $funa($func_params);

}

function getDomain($url){
    if(substr($url, 0, 4) == 'http') {
        $rs = parse_url($url);
        $host = $rs['host'];
    }elseif($index = strpos($url, '/')) {
        $host = substr($url, 0, $index);
    }else{
        $host = $url;
    }
    $arr = explode('.', $host);
    $last = array_pop($arr);
    $map = array('com','net','org','gov','cc','biz','info');
    $last2 = array_pop($arr);
    if(in_array($last2, $map)) {
        $last3 = array_pop($arr);
        $domain = $last3.'.'.$last2.'.'.$last;
    }else{
        $domain = $last2.'.'.$last;
    }
    return $domain;
}

function curl_get_ml($url_arr){
    $mh = curl_multi_init();
    foreach ($url_arr as $i => $url) {
        $conn[$i]=curl_init($url);
        curl_setopt($conn[$i],CURLOPT_RETURNTRANSFER,1);
        curl_multi_add_handle ($mh,$conn[$i]);
    }
    do {
        $mrc = curl_multi_exec($mh,$active);
    } while ($mrc == CURLM_CALL_MULTI_PERFORM);
    while ($active and $mrc == CURLM_OK) {
        if (curl_multi_select($mh) != -1) {
            do {
                $mrc = curl_multi_exec($mh, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        }
    }
    foreach ($url_arr as $i => $url) {
        $res[$i]=curl_multi_getcontent($conn[$i]);
        curl_close($conn[$i]);
    }
    return $res;
}