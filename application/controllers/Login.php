<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 用户model
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
            var_dump($params);
        }else{
            $params = $this->input->get();
            $data['title'] = 'Login Index';
            $this->render('login/index', $data);
        }
    }

}