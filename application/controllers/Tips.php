<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tips extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('tips_model');
        $this->load->helper('url_helper');
    }

    /**
     * 默认入口方法
     * @return html
     */
    public function index()
    {
        if($this->isPost()){
            $params = $this->input->post();
            var_dump($params);
        }else{
            $params = $this->input->get();
            $data['title'] = 'Tips Index';
            $data['tips'] = $this->tips_model->findAll();
            $this->load->view('templates/header', $data);
            $this->load->view('tips/index', $data);
            $this->load->view('templates/footer', $data);
        }
     }

    /**
     * 添加提醒方法
     * @return json
     */
    public function add()
    {
        if($this->isPost()){
            $params = $this->isPost()? $this->input->post():$this->input->get();
            $ret = $this->tips_model->addTips($params);
            if($ret){
                $result = array_for_result(true,$this->tips_model->getModelError());
            }else{
                $result = array_for_result(flase,$this->tips_model->getModelError());
            }
            $this->renderJson($result);
        }else{
            $params = $this->input->get();
            $data['title'] = 'Tips Add';
            $this->load->view('templates/header', $data);
            $this->load->view('tips/edit', $data);
            $this->load->view('templates/footer', $data);
        }
        //$params = $this->isPost()? $this->input->post():$this->input->get();
    }

    /**
     * 获取提醒
     * @return json
     */
    public function listall(){
        $data = $this->tips_model->findAll();
        $result = array_for_list($data,array(),true,'success');
        $this->renderJson($result);
    }

    public function test(){
        // $ret = $this->tips_model->getAllTips('6097389606834319388');
        $params['tips_id'] = '6097389606834319388';
        //$params['tips_message'] = 'test edit';
        $ret = $this->tips_model->getTipsByParams(array(), 1, 10);
        //$ret = $this->tips_model->count(array('parent_id'=>$params['tips_id']));
        //$ret = $this->tips_model->editTips($params);
        var_dump($ret);
    }

}