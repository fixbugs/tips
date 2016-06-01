<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 公共处理Controller
 */
class GT_Controller extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->_initContorller();
        $this->load->library('theme');
        $this->load->library('stringtopy');
    }

    /**
     * init controller more thing
     * @return null
     */
    public function _initContorller(){
        $this->_loginCheck();
        $this->_initConst();
        $this->_initServer();
    }

    /**
     * add sth into SERVER
     * @return void
     */
    public function _initServer(){
        //write http request info into server
        setCountInfo();
    }

    /**
     * init const var
     * @return null
     */
    public function _initConst(){
        define('USER_ID', $this->_user['user_id']);
        define('USER_NAME', $this->_user['username']);
    }

    /**
     * check user login and set session
     * @return null
     */
    public function _loginCheck(){
        if(!$this->isLogin()){
            $this->_gotoLogin();
        }else{
            //get user info
            $this->load->model('user_model');
            $this->_user = $this->user_model->findByUsername('admin');
        }
    }

    /**
     * template show
     * @return void
     */
    public function render($template, $data=array()){
        $this->theme->render($template, $data);
    }

    /**
     * h5 template show
     * @return void
     */
    public function h5render($template, $data=array()){
        if($data && !isset($data['asserts_url'])){
            $data['asserts_url'] = '';
        }
        $this->theme->h5render($template, $data);
    }

    /**
     * set layout show,default set true for show layout
     * @return void
     */
    public function setLayout($need=true){
        $this->theme->setLayout($need);
    }

    /**
     * check is login
     * @return boolean [description]
     */
    public function isLogin(){
        return true;
        //check user login cookie
        //cookie rule
        //uid lt ssokey
        if(!$_COOKIE['admin_permit']){
            return false;
        }else{
            return $this->check_permit( $_COOKIE['admin_permit']);
        }

        return true;
    }

    /**
     * check login cooike
     * @param string $admin_permit COOKIE
     * @return bool or exit
     */
    public function check_permit($admin_permit=''){
        $permit = json_decode($admin_permit, true);
        if($permit['u_id'] && $permit['sso_key']){
            //todo need add u_id and sso_key check
            $dec_time = decrypt_string_by_time($permit['sso_key']);
            if($dec_time - time() >= 0 ){
                return true;
            }
        }else{
            $this->_gotoLogin();
        }
        $this->_gotoLogin();
    }

    /**
     * check user permit by ssokey and cookie
     * @param string $sso_key 统一分配的ssokey
     * @param string $admin_permit 权限cookie值
     * @return bool
     */
    public function checkPermit($sso_key, $admin_permit=''){
        $permit = json_decode($admin_permit, true);
        $pro_key = md5($sso_key.'_'.$permit['lt'].'_'.$permit['uid']);
        $key = substr($pro_key,2,1).substr($pro_key,7,1).substr($pro_key,17,1).substr($pro_key,25,1).substr($pro_key,31,1);
        $key_pos = strpos($permit['permit'], $key);
        if($key_pos !== false && ($key_pos % 5) == 0 && (time()-$permit['lt']) <72000 ){
            if(!empty($admin_permit)){
                $_COOKIE['admin_permit'] = $admin_permit;
                setcookie('admin_permit', $admin_permit, time()+72000, '/', getDomain($_SERVER['HTTP_HOST']));
            }
            return true;
        }
        return false;
    }

    /**
     * redirect to error page
     * @return null redirect
     */
    public function _gotoError($message,$return_url=''){
        if ($return_url == '') {
            $return_url = ! empty ( $_SERVER ['HTTP_REFERER'] ) ? $_SERVER ['HTTP_REFERER'] : '/';
        }
        if (_IS_AJAX_) {
            $result = array_for_result ( false, $message, array(), $return_url );
            echo $this->renderJson($result);
            exit ();
        } else {
            $this->assign ( 'message', $message );
            $this->assign ( 'return_url', $return_url );
            //todo view to show message v
            //echo $this->render ( 'message' );
            exit ($message);
        }
    }

    /**
     * redirect to login page
     * @return null redirect
     */
    public function _gotoLogin($return_url=''){
        setcookie('admin_permit', '', 0, '/', get_domain($_SERVER['HTTP_HOST']));
        $total_login_url = 'http://'.$_SERVER['HTTP_HOST'].'/index.php/login/index';
        $return_url = $return_url=='' ? urlencode("http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) : urlencode($return_url);
        header("Location:" . $total_login_url . "?returnurl=" . $return_url);
        exit();
    }

}