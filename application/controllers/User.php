<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 用户model
 */
class User extends GT_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->helper('url_helper');
    }

    /**
     * 默认方法
     * @return mixed
     */
    public function index()
    {
        if($this->isPost()){
            $params = $this->input->post();
            var_dump($params);
        }else{
            $params = $this->input->get();
            $data['users'] = $this->user_model->findAll();
            $data['title'] = 'User Index';
            $this->render('user/index', $data);
        }
     }

    /**
     * 添加用户方法
     * @return mixed
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
            $this->render('user/add', $data);
        }
    }

    /**
     * 用户编辑方法
     * @return json or html
     */
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
            $this->render('user/edit', $data);
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

    /**
     * 删除用户
     * @return json
     */
    public function delete(){
        if($this->isPost()){
            $params = $this->input->post();
        }else{
            $params = $this->input->get();
        }
        if(!$params['id']){
            $result = array_for_result(false, 'id needed', $params);
        }else{
            $ret = $this->user_model->deleteUser($params['id']);
            $result = array_for_result($ret, $this->user_model->getModelError(), $params);
        }
        $this->renderJson($result);
    }

}