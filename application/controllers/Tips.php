<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tips extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('tips_model');
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
     * 添加提醒方法
     * @return json
     */
    public function add()
    {
        $params = $this->isPost()? $this->input->post():$this->input->get();
        $ret = $this->tips_model->addTips($params);
        if($ret){
            $result = array_for_result(true,$this->tips_model->getModelError());
        }else{
            $result = array_for_result(flase,$this->tips_model->getModelError());
        }
        $this->renderJson($result);
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

}