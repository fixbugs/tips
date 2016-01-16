<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->helper('url_helper');
    }

    public function index()
    {
        if($this->isPost()){
            $params = $this->input->post();
            var_dump($params);
        }else{
            $params = $this->input->get();
            var_dump($params);
        }
     }

    /**
     * 添加用户方法
     * @return json
     */
    public function add()
    {
        $params = $this->isPost()? $this->input->post():$this->input->get();
        $ret = $this->user_model->addUser($params);
        if($ret){
            $result = array_for_result(true,$this->user_model->getModelError());
        }else{
            $result = array_for_result(flase,$this->user_model->getModelError());
        }
        $this->renderJson($result);
    }

    /**
     * 获取所有用户信息
     * @return json
     */
    public function listall(){
        $data = $this->user_model->findAll();
        $result = array_for_list($data,array(),true,'success');
        $this->renderJson($result);
    }

}