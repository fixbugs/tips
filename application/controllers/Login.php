<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 用户登录model
 */
class Login extends GT_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('user_model');
        $this->load->helper('url_helper');
    }

    public function index(){
        if($this->isPost()){
            $params = $this->input->post();
            $return_url = $params['return_url'];
            unset($params['return_url']);
            $ret = $this->user_model->checkLogin($params);
            if($ret){
                if($return_url){
                    $result = array_for_result(true, 'login success', array(), $return_url);
                }else{
                    $result = array_for_result(true, 'login success', array(), '/index.php/tips/index');
                }
                $cookie_d['u_id'] = $ret;
                $cookie_d['sso_key'] = encrypt_string_by_time();
                $cookie_data = json_encode($cookie_d);
                setcookie('admin_permit', $cookie_data, time()+3600, '/', getDomain($_SERVER['HTTP_HOST']));
            }else{
                $result = array_for_result(false, 'login failed');
            }
            $this->renderJsonp($result, $params);
        }else{
            $params = $this->input->get();
            $return_url = isset($params['returnurl']) ? $params['returnurl']:'';
            $data['title'] = 'Login';
            $data['return_url'] = $return_url;
            $this->render('login/index', $data);
        }
    }

    public function quit(){
        setcookie('admin_permit', '', 0, '/', get_domain($_SERVER['HTTP_HOST']));
        $this->_gotoLogin();
    }

}