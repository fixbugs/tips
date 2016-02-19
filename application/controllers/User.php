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
        if($this->isPost()){
            $params = $this->isPost() ? $this->input->post():$this->input->get();
            $ret = $this->user_model->addUser($params);
            if($ret){
                $result = array_for_result(true, $this->user_model->getModelError());
            }else{
                $result = array_for_result(false, $this->user_model->getModelError());
            }
            $this->renderJson($result);
        }else{
            $params = $this->input->get();
            $data['title'] = 'User Add';
            $data['action'] = 'add';
            $this->load->view('templates/header', $data);
            $this->load->view('user/edit', $data);
            $this->load->view('templates/footer', $data);
        }
    }

    public function edit(){
        if($this->isPost()){
            $params = $this->isPost() ? $this->input->post():$this->input->get();
            $ret = $this->tips_model->editUser($params);
            if($ret){
                $result = array_for_result(true, $this->user_model->getModelError());
            }else{
                $result = array_for_result(false, $this->user_model->getModelError());
            }
            $this->renderJson($result);
        }else{
            $params = $this->input->get();
            if(!$params['id']){
                exit('id needed');
            }
            $user_data = $this->user_model->getById($params['id']);
            if(!$user_data){
                exit("id error");
            }
            $data['data'] = $user_data;
            $data['title'] = 'User Edit';
            $data['action'] = 'edit';
            $this->load->view('templates/header', $data);
            $this->load->view('user/edit', $data);
            $this->load->view('templates/footer', $data);
        }
    }

    /**
     * 获取所有用户信息
     * @return json
     */
    public function listall(){
        $data = $this->user_model->findAll();
        $result = array_for_list($data, array(), true, 'success');
        $this->renderJson($result);
    }

}