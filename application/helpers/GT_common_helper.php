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
    if (is_dir($dir) || @mkdir($dir, $mode))
    {
        return true;
    }
    if (!mk_dir(dirname($dir), $mode)) {
        return false;
    }
    return @mkdir($dir, $mode);
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
    if(preg_match('/^(https?:\/\/)?([a-z0-9.-]+)(\/.*)?$/i', $url, $matches)){
        return $matches[2];
    }
    return false;
}

//获取根域名
function get_domain($url)
{
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
function startWith($str, $needle){
    return strpos($str, $needle) === 0;
}

/**
 * 判断字符串后缀
 *
 * @param $str 需要判断的字符串
 * @param $needle 后缀
 * @return bool
 */
function endWith($str, $needle){
    $length = strlen($needle);
    if($length == 0){
        return true;
    }
    return (substr($str, -$length) === $needle);
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

/**
 * 获取根域名
 * @param  string $url 需要获取域名的url
 * @return string
 */
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

/**
 * 多线程获取url返回结果，只支持get，
 * example $urls = array(
 *   'k1'=>'http://stat.leju.com/api/data/getrankdatabyuids?app_key=aeaa676f40d5974c335323cafc52c7c8&unique_id=abcdefg,bbccdd',
 *  'key2'=>'http://stat.leju.com/api/data/getrank?app_key=aeaa676f40d5974c335323cafc52c7c8&plat_key=pc',
 *  'kk3'=>'http://stat.leju.com/api/data/getrankrecord?app_key=aeaa676f40d5974c335323cafc52c7c8&unique_id=abcdefg&plat_key=pc',
 *   );
 * @param  array $url_arr url数组，可带索引
 * @return array
 */
function curl_get_ml($url_arr){
    $mh = curl_multi_init();
    foreach ($url_arr as $i => $url) {
        $conn[$i] = curl_init($url);
        curl_setopt($conn[$i], CURLOPT_RETURNTRANSFER,1);
        curl_multi_add_handle ($mh, $conn[$i]);
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
        $res[$i] = curl_multi_getcontent($conn[$i]);
        curl_close($conn[$i]);
    }
    return $res;
}

/**
 * 提交GET请求，curl方法
 * @param string  $url       请求url地址
 * @param mixed   $data      GET数据,数组或类似id=1&k1=v1
 * @param array   $header    头信息 注：若要绑定host,需设置$header['host']="要绑定的域名或ip"
 * @param int     $timeout   超时时间
 * @param int     $port      端口号
 * @return array             请求结果,
 *                            如果出错,返回结果为array('error'=>'','result'=>''),
 *                            未出错，返回结果为array('result'=>''),
 */
function curl_get($url, $data = array(), $header = array(), $timeout = 3, $port = 80)
{
    $start_time = time();
    $req_data = $data;
    $ch = curl_init();
    if (!empty($data)) {
        $data = is_array($data) ? http_build_query($data) : $data;
        $url .= (strpos($url, '?') ? '&' : "?") . $data;
    }
    $setheader = array();
    if(isset($header['host'])){  //绑定host
        //如果host是ip
        if(preg_match('/^[0-9]{1,3}(\.[0-9]{1,3}){3}$/', $header['host'])){
            $ip = $header['host'];
            $host = get_url_domain($url);
            if($host){
                $setheader = array("Host:".$host);
                $url = preg_replace("/{$host}/", $ip, $url, 1);
            }
        }else{
            $setheader = array("Host:".$header['host']);
        }
        unset($header['host']);
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_POST, 0);
    //curl_setopt($ch, CURLOPT_HEADER, true);          //显示文件头信息
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  //设定不跟随header发送的location
    //curl_setopt($ch, CURLOPT_PORT, $port);
    !empty($setheader) && curl_setopt($ch, CURLOPT_HTTPHEADER, $setheader);

    $data = array();
    $result = array();
    $result['result'] = curl_exec($ch);
    curl_close($ch);
    return $result;
}

/**
 * 提交POST请求，curl方法
 * @param string  $url       请求url地址
 * @param mixed   $data      POST数据,数组或类似id=1&k1=v1
 * @param array   $header    头信息 注：若要绑定host,需设置$header['host']="要绑定的域名或ip"
 * @param int     $timeout   超时时间
 * @param int     $port      端口号
 * @return string            请求结果,
 *                            如果出错,返回结果为array('error'=>'','result'=>''),
 *                            未出错，返回结果为array('result'=>''),
 */
function curl_post($url, $data = array(), $header = array(), $timeout = 3, $port = 80)
{
    $start_time = time();
    $req_data = $data;
    if(isset($header['host'])){  //绑定host
        //如果host是ip
        if(preg_match('/^[0-9]{1,3}(\.[0-9]{1,3}){3}$/', $header['host'])){
            $ip = $header['host'];
            $host = get_url_domain($url);
            if($host){
                $header[] = "Host:".$host;
                $url = preg_replace("/{$host}/", $ip, $url,1);
            }
        }else{
            $header[] = "Host:".$header['host'];
        }
        unset($header['host']);
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    //curl_setopt($ch, CURLOPT_HEADER, true);          //显示文件头信息
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  //设定不跟随header发送的location
    //curl_setopt($ch, CURLOPT_PORT, $port);
    !empty ($header) && curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = array();

    $result['result'] = curl_exec($ch);
    if (0 != curl_errno($ch)) {
        $result['error']  = "Error:\n" . curl_error($ch);
    }elseif(empty($result['result'])){
        $result['error']  = "Error:Empty return";
    }
    return $result;
}

/**
 * 根据url获取根域名
 * @param string $url
 * @return bool or string
 */
function get_url_domain($url){
    if(preg_match('/^(https?:\/\/)?([a-z0-9.-]+)(\/.*)?$/i', $url,$matches)){
        return $matches[2];
    }
    return false;
}

/**
 * 对数组进行编码转换
 *
 * @param strint       $in_charset   输入编码
 * @param string       $out_charset  输出编码
 * @param string|array  $arr         输入数据
 * @return array                     返回数组
 */
function iconv_mixed($in_charset, $out_charset, $arr)
{
    if (strtolower($in_charset) == "utf8" || strtolower($in_charset) == 'utf-8') {
        $in_charset = "UTF-8";
    }

    if (is_array($arr)) {
        foreach ($arr as $key => $value) {
            $arr[$key] = iconv_mixed($in_charset, $out_charset . "//IGNORE", $value);
        }
    } else {
        if (!is_numeric($arr)) {
            $arr = iconv($in_charset, $out_charset . "//IGNORE", $arr);
        }
    }
    return $arr;
}

/**
 * 按UNICODE编码截取字符串前$length个字符
 * @param string $str
 * @param int $length
 */
function cn_substr($string, $length)
{
    if ($length == 0) {
        return '';
    }

    $newlength = 0;
    if (strlen($string) > $length) {
        for($i=0; $i < $length; $i++)
        {
            if(!isset($string{$newlength})) break;
            $a = base_convert(ord($string{$newlength}), 10, 2);
            $newlength++;
            $a = substr('00000000'.$a, -8);

            if (substr($a, 0, 1) == 0) {
                continue;
            } elseif (substr($a, 0, 3) == 110) {
                $newlength ++;
            } elseif (substr($a, 0, 4) == 1110) {
                $newlength += 2;
            } elseif (substr($a, 0, 5) == 11110) {
                $newlength += 3;
            } elseif (substr($a, 0, 6) == 111110) {
                $newlength += 4;
            } elseif (substr($a, 0, 7) == 1111110) {
                $newlength += 5;
            } else {
                $newlength ++;
            }
            $i++;
        }

        return substr($string, 0, $newlength);
    } else {
        return $string;
    }
}

/**
 * xml串转成数组
 * @param string $xml_string xml串
 * @return array $data       失败返回空数组
 */
function xml_array($xml_string)
{
    $xml_string = preg_replace('/(<\?xml\s+version=(\'|\")1.0(\'|\")\s+encoding=)([\"\'a-z-0-9]+)(\s*\?>)/i',
        '$1"utf-8"$5', $xml_string);

    $xml_string = str_replace('<![CDATA[', '', $xml_string);
    $xml_string = str_replace(']]>', '', $xml_string);
    $xml = @simplexml_load_string( $xml_string );
    if (false === $xml) {
        return array();
    }
    $data = array();
    simple_xml_array($xml, $data);
    return $data;
}

/**
 * simplexml对象转成数组
 * @param object $simple_xml
 * @param array $data
 */
function simple_xml_array($simple_xml, &$data){
    $simple_xml = (array) $simple_xml;
    foreach ($simple_xml as $k => $v){
        if ($k === '@attributes')
        {
            continue;
        }
        $v = (array)$v;
        foreach ($v as $k1 => $v1){
            if ($k1 !== '@attributes') {
                if (is_array($v1)) {
                    $data[$k][$k1] = array();
                    simple_xml_array($v1,  $data[$k][$k1]);
                } elseif ($v1 instanceof SimpleXMLElement ) {
                    $k2 = $v1->getName();
                    if($k2 === $k){
                        $data[$k][$k1] = array();
                        simple_xml_array($v1, $data[$k][$k1]);
                    }else{
                        $data[$k][$k2] = array();
                        simple_xml_array($v1, $data[$k][$k2]);
                    }
                } else {
                    $data[$k][$k1] = $v1;
                }
            }
        }
    }
}

/**
* 过滤调字符串中的所有空格
* @param string $str
* @return string
*/
function trimall($str){
    $repleace_arr = array(" ", "　", "\t", "\n", "\r");
    $repleace_value = array("", "", "", "", "");
    return str_replace($repleace_arr, $repleace_value, $str);
}

/**
 * 取得当前时间的微秒数
 */
function getFloatMicroTime(){
    list($usec, $sec) = explode(' ', microtime());
    return ((float)$usec + (float)$sec);
}

/**
 * 计算字符数(不是字节数)
 * @param	  string   $str 字符串
 * @return	  int　　	字符数
 */
function countStr($str){
    $i = 0;
    $count = 0;
    $len = strlen ($str);
    while ($i < $len) {
        $chr = ord ($str[$i]);
        $count++;
        $i++;
        if($i >= $len) break;
        if($chr & 0x80) {
            $chr <<= 1;
            while ($chr & 0x80) {
                $i++;
                $chr <<= 1;
            }
        }
    }
    return $count;
}

/**
* 获取用户HTTP请求的分析结果，依赖于第三方库Http_Package_Analysis
* @return array
*/
function getUserHttpPackageAnaResult(){
    $CI = & get_instance();
    $CI->load->library('Http_Package_Analysis.php');
    return $CI->http_package_analysis->analysisResult();
}

/**
* 根据HTTP包，记录更新统计信息
*/
function setCountInfo(){

    $CI = & get_instance();
    $packageAnalysisResult = getUserHttpPackageAnaResult();

    $data['id'] = make_shard_id(VSID);
    $data['url'] = $packageAnalysisResult['url'];
    $data['refer'] = $packageAnalysisResult['refer'];
    $data['user_agent'] = $packageAnalysisResult['user_agent'];
    $data['user_ip'] = $packageAnalysisResult['user_ip'];
    $data['create_time'] = $packageAnalysisResult['create_time'];
    $data['http_info'] = json_encode($_SERVER);

    $ip_info = $packageAnalysisResult['ip_info'];
    $CI->load->library('stringtopy');

//根据ua的md5值判断是否是同一个用户，根据上一次这个ua的访问判断是否增加uv，pv直接按照访问总量统计，uv按照当前计数统计
$other_data['pre_page_url'] = $packageAnalysisResult['pre_page_url'];
$other_data['now_page_url'] = $packageAnalysisResult['now_page_url'];
$other_data['domain'] = $packageAnalysisResult['domain'];
$other_data['domain_md5'] = $packageAnalysisResult['domain_md5'];
$other_data['city'] = $packageAnalysisResult['city'];
$other_data['equipment'] = $packageAnalysisResult['equipment'];
$other_data['equipment_type'] = $packageAnalysisResult['equipment_type'];
$other_data['user_system'] = $packageAnalysisResult['platform'];
$other_data['cookie'] = isset($_COOKIE) ? $_COOKIE:'';
$other_data['ua_md5'] = $packageAnalysisResult['user_agent_md5'];
$other_data['now_url_md5'] = $packageAnalysisResult['now_url_md5'];
$other_data['pre_url_md5'] = $packageAnalysisResult['pre_url_md5'];

    $system_count_model = $CI->load->model('system_count_model');
    $CI->system_count_model->insert($data);
}

/**
* 根据ip获取ip的相关信息,需要加入本地ip库相关处理
* @param string $ip
* @return array
*/
function getCityInfoByIp($ip){
    $url = 'http://ip.taobao.com/service/getIpInfo.php?ip='.$ip;
    $res = curl_get($url);
    $default_result = array(
        'country' => '未知ip',
        'country' => '0',
        'area' => '',
        'area_id' => '',
        'region' => '',
        'region_id' => '',
        'city' => '',
        'city_id'  => '',
        'county' => '',
        'county_id' => '',
        'isp' => '',
        'isp_id' => '',
        'ip' => $ip,
    );
    if($res['result']){
        $result = json_decode($res['result'], true);
        if($result['data']['city'] == '省直辖县级行政区划' && $result['data']['county']){
            $result['data']['city'] = $result['data']['county'];
        }
    }else{
        return $default_result;
    }
    if(!$result['data']){
        return $default_result;
    }
    return $result['data'];
}

/**
* @description 判断是否是移动设备访问
* @return bool
*/
function isMobile (){
    $_SERVER['ALL_HTTP'] = isset( $_SERVER['ALL_HTTP'] ) ? $_SERVER['ALL_HTTP'] : '';
    $mobile_browser      = '0';
    if ( preg_match( '/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i' , strtolower( $_SERVER['HTTP_USER_AGENT'] ) ) ){
        $mobile_browser++;
    }
    if ( (isset( $_SERVER['HTTP_ACCEPT'] )) and (strpos( strtolower( $_SERVER['HTTP_ACCEPT'] ) , 'application/vnd.wap.xhtml+xml' ) !== false) ){
        $mobile_browser++;
    }
    if ( isset( $_SERVER['HTTP_X_WAP_PROFILE'] ) ){
        $mobile_browser++;
    }
    if ( isset( $_SERVER['HTTP_PROFILE'] ) ){
        $mobile_browser++;
    }
    $mobile_ua           = strtolower( substr( $_SERVER['HTTP_USER_AGENT'] , 0 , 4 ) );
    $mobile_agents       = array(
        'w3c ' , 'acs-' , 'alav' , 'alca' , 'amoi' , 'audi' , 'avan' , 'benq' , 'bird' , 'blac' ,
        'blaz' , 'brew' , 'cell' , 'cldc' , 'cmd-' , 'dang' , 'doco' , 'eric' , 'hipt' , 'inno' ,
        'ipaq' , 'java' , 'jigs' , 'kddi' , 'keji' , 'leno' , 'lg-c' , 'lg-d' , 'lg-g' , 'lge-' ,
        'maui' , 'maxo' , 'midp' , 'mits' , 'mmef' , 'mobi' , 'mot-' , 'moto' , 'mwbp' , 'nec-' ,
        'newt' , 'noki' , 'oper' , 'palm' , 'pana' , 'pant' , 'phil' , 'play' , 'port' , 'prox' ,
        'qwap' , 'sage' , 'sams' , 'sany' , 'sch-' , 'sec-' , 'send' , 'seri' , 'sgh-' , 'shar' ,
        'sie-' , 'siem' , 'smal' , 'smar' , 'sony' , 'sph-' , 'symb' , 't-mo' , 'teli' , 'tim-' ,
        'tosh' , 'tsm-' , 'upg1' , 'upsi' , 'vk-v' , 'voda' , 'wap-' , 'wapa' , 'wapi' , 'wapp' ,
        'wapr' , 'webc' , 'winw' , 'winw' , 'xda' , 'xda-'
        );
    if ( in_array( $mobile_ua , $mobile_agents ) ){
        $mobile_browser++;
    }
    if ( strpos( strtolower( $_SERVER['ALL_HTTP'] ) , 'operamini' ) !== false ){
        $mobile_browser++;
    }
    // Pre-final check to reset everything if the user is on Windows
    if ( strpos( strtolower( $_SERVER['HTTP_USER_AGENT'] ) , 'windows' ) !== false ){
        $mobile_browser      = 0;
    }
    // But WP7 is also Windows, with a slightly different characteristic
    if ( strpos( strtolower( $_SERVER['HTTP_USER_AGENT'] ) , 'windows phone' ) !== false ){
        $mobile_browser++;
    }
    if ( $mobile_browser > 0 ){
        return true;
    }else{
        return false;
    }
}

/**
 * 安全过滤函数
 *
 * @param $string
 * @return string
 */
function safe_replace($string){
    $string = str_replace('%20', '', $string);//空格
    $string = str_replace('%2b', '', $string);//加号+
    $string = str_replace('%27', '', $string);//单引号'
    $string = str_replace('%2527', '', $string);
    $string = str_replace('*', '', $string);
    $string = str_replace('"', '&quot;', $string);//双引号
    $string = str_replace("'", '', $string);
    $string = str_replace('"', '', $string);
    $string = str_replace(';', '', $string);
    $string = str_replace('<', '&lt;', $string);
    $string = str_replace('>', '&gt;', $string);
    $string = str_replace("{", '', $string);
    $string = str_replace('}', '', $string);
    return $string;
}

/**
 * 返回所有字符的数组
 * @return array
 */
function getCharArr(){
    $rand_str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ~@#()_=!`$%^&*{}|?';
    $char_array = str_split($rand_str);
    return $char_array;
}

/**
 * 获取特定长度的随机字符串
 */
function getRandomStr($length){
    $rand_str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ~@#()_=!`$%^&*{}|?';
    $max = strlen($rand_str) -1;
    $result_str = '';
    for($i=0; $i<$length; $i++){
        $result_str .= $rand_str[rand(0, $max)];
    }
    return $result_str;
}

/**
 * 字符反转
 * @return string
 */
function strrevv($str){
    $len = strlen($str);
    $newstr = '';
    for( $i = $len -1; $i>=0; $i-- ){
        $newstr .= $str[$i];
    }
    return $newstr;
}

/**
 * 过滤xss，
 * @param string &$string
 * @param bool $low
 * @return void
 */
function clean_xss(&$string, $low = False){
    if (! is_array ( $string )){
        $string = trim ( $string );
        $string = strip_tags ( $string );
        $string = htmlspecialchars ( $string );
        if ($low){
            return True;

        }
        $string = str_replace ( array ('"', "\\", "'", "/", "..", "../", "./", "//" ), '', $string );
        $no = '/%0[0-8bcef]/';
        $string = preg_replace ( $no, '', $string );
        $no = '/%1[0-9a-f]/';
        $string = preg_replace ( $no, '', $string );
        $no = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';
        $string = preg_replace ( $no, '', $string );
        return True;
    }
    $keys = array_keys ( $string );
    foreach ( $keys as $key ){
        clean_xss ( $string [$key] );

    }
}

/**
 * 输入过滤
 */
function inputCheck($params) {
    if(!get_magic_quotes_gpc()) {
        $post = addslashes($params);
    }
    $params = str_replace("_", "\_", $params);
    $params = str_replace("%", "\%", $params);
    $params = nl2br($params);
    $params = htmlspecialchars($params);
    return $params;
}

/**
 * 注入字符串判断
 * @param string $sql_str
 * @return bool or string
 */
function injectCheck($sql_str){
    $check = preg_match('/select|insert|update|delete|sleep|\'|\\*|\*|\.\.\/|\.\/|union|into|load_file|outfile/i', $sql_str);
    if($check){
        return false;
    }
    return $sql_str;
}


/**
 * 大位数加法实现
 * @param int $a
 * @param int $b
 * @return string
 */
function bigDataAdd($a, $b){
    $m = strlen($a);
    $n = strlen($b);
    $a_arr = str_split($a);
    $a_arr = array_reverse($a_arr);
    $b_arr = str_split($b);
    $b_arr = array_reverse($b_arr);
    $num = $m>$n ? $m:$n;
    $result_arr = array_fill(0, $num + 1, 0);
    $result = '';
    $flag = 0;
    for($i=0; $i<$num; $i++){
        $tmp_a = isset($a_arr[$i]) ? $a_arr[$i]:0;
        $tmp_b = isset($b_arr[$i]) ? $b_arr[$i]:0;
        $tmp_sum = $tmp_a + $tmp_b + $flag;
        if($tmp_sum > 9){
            $flag = 1;
        }else{
            $flag = 0 ;
        }
        $result[$i] = $tmp_sum % 10;
        $result[$i + 1] = intval($tmp_sum / 10);
    }
    $result = array_reverse($result);
    if(!$result[0]){
        unset($result[0]);
    }
    return implode('', $result);
}

/**
 * 加密解密函数
 * @param string $string
 * @param string $operation 'E' or 'D'
 * @param string $key 'unknown sec key'
 * @return string
 */
function encrypt($string, $operation, $key=''){
    $key = md5($key);
    $key_length = strlen($key);
    $string = $operation=='D' ? base64_decode($string):substr(md5($string.$key),0,8).$string;
    $string_length = strlen($string);
    $rndkey = $box = array();
    $result = '';
    for($i=0; $i<=255; $i++){
        $rndkey[$i] = ord($key[$i % $key_length]);
        $box[$i] = $i;
    }
    for($j=$i=0; $i<256; $i++){
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    for($a=$j=$i=0; $i<$string_length; $i++){
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr( ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]) );
    }
    if($operation == 'D'){
        if(substr($result, 0, 8) == substr(md5(substr($result, 8).$key), 0, 8)){
            return substr($result, 8);
        }else{
            return'';
        }
    }else{
        return str_replace('=', '', base64_encode($result));
    }
}

/**
 * 字段断行处理函数，用于断字处截取字符串
 * @param string $string
 * @param int $limit
 * @param char $break
 * @param string $pad
 * @return string
 */
function lineWordTruncate($string, $limit, $break='.', $pad='...'){
    if(strlen($string) <= $limit){
        return $string;
    }
    if(false !== ($breakpoint = strpos($string, $break, $limit)) ){
        if($breakpoint < strlen($string) - 1){
            $string = substr($string, 0, $breakpoint) . $pad;
        }
    }
    return $string;
}


/**
 * 移除代码中的注释
 * @param string $content 代码块
 * @return string
 */
function strip_whitespace($content) {
    $remove_head = false;
    $stripStr = '';
    if(!startWith($content, '<?php')){
        $content = '<?php ' . $content;
        $remove_head = true;
    }
    //分析php源码
    $tokens = token_get_all($content);
    $last_space = false;
    for ($i = 0, $j = count ($tokens); $i < $j; $i++){
        if (is_string ($tokens[$i])){
            $last_space = false;
            $stripStr .= $tokens[$i];
        }
        else{
            switch ($tokens[$i][0]){
                //过滤各种PHP注释
            case T_COMMENT:
            case T_DOC_COMMENT:
                break;
            //过滤空格
            // case T_WHITESPACE:
            //     if (!$last_space){
            //         $stripStr .= ' ';
            //         $last_space = true;
            //     }
            //     break;
            default:
                $last_space = false;
                $stripStr .= $tokens[$i][1];
            }
        }
    }
    if($remove_head){
        $stripStr = str_replace('<?php ', '', $stripStr);
    }
    return $stripStr;
}

/**
 * 加密字符串
 * 对传入的key进行加密,根据当前时间，有效时间1小时
 * @param string $key
 * @return string
 */
function encrypt_string_by_time($key='tips.goitt.com'){
    $now_time = time() + 60*60;
    $main_str = (string)$now_time . $key;
    return base64_encode(base64_encode($main_str));
}

/**
 * 解密字符串
 * 对传入的key进行解密,返回解密出来的时间
 * @param string $seckey 密钥串
 * @param string $key
 * @return string
 */
function decrypt_string_by_time($seckey,$key='tips.goitt.com'){
    $dec_string = base64_decode(base64_decode($seckey));
    if(! (substr($dec_string, 10) == $key) ){
        return false;
     }
    return substr($dec_string,0,10);
}