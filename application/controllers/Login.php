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
            $ret = $this->user_model->checkLogin($params);
            if($ret){
                $result = array_for_result(true, 'login success');
            }else{
                $result = array_for_result(false, 'login failed');
            }
            $this->renderJsonp($result);
        }else{
            $params = $this->input->get();
            $data['title'] = 'Login Index';
            $this->render('login/index', $data);
        }
    }

    public function quit(){
        setcookie('admin_permit', '', 0, '/', get_domain($_SERVER['HTTP_HOST']));
        $this->_gotoLogin();
    }

}