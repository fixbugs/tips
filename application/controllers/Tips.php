<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 提醒控制器
 */
class Tips extends GT_Controller {

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
            $this->render('tips/index', $data);
        }
     }

    /**
     * h5 页面展示入口
     * @return html
     */
    public function h5index(){
        $params = $this->input->get();
        $this->load->library('Plugin_Array');
        $data['asserts_url'] = '';
        $datas = $this->tips_model->findAll();
        $data['tips'] = $this->plugin_array->sortByCol($datas, 'create_time', SORT_DESC);
        $this->h5render('tips/index', $data);
    }

    /**
     * 添加提醒方法
     * @return mixed
     */
    public function add()
    {
        if($this->isPost()){
            $params = $this->isPost() ? $this->input->post():$this->input->get();
            $ret = $this->tips_model->addTips($params);
            if($ret){
                $result = array_for_result(true, $this->tips_model->getModelError());
            }else{
                $result = array_for_result(false, $this->tips_model->getModelError());
            }
            $this->renderJson($result);
        }else{
            $params = $this->input->get();
            $data['title'] = 'Tips Add';
            $data['action'] = 'add';
            $this->render('tips/add', $data);
        }
    }

    /**
     * 编辑提醒方法
     * @return mixed
     */
    public function edit(){
        if($this->isPost()){
            $params = $this->isPost() ? $this->input->post():$this->input->get();
            $ret = $this->tips_model->editTips($params);
            if($ret){
                $result = array_for_result(true, $this->tips_model->getModelError());
            }else{
                $result = array_for_result(false, $this->tips_model->getModelError());
            }
            $this->renderJson($result);
        }else{
            $params = $this->input->get();
            if(!$params['id']){
                exit('id needed');
            }
            $tips_data = $this->tips_model->getById($params['id']);
            if(!$tips_data){
                exit("id error");
            }
            $data['data'] = $tips_data;
            $data['title'] = 'Tips Edit';
            $data['action'] = 'edit';
            $this->render('tips/edit', $data);
        }
    }

    /**
     * 获取提醒
     * @return json
     */
    public function listall(){
        $data = $this->tips_model->findAll();
        $result = array_for_list($data, array(), true, 'success');
        $this->renderJson($result);
    }

    /**
     * 删除提醒
     * @return json
     */
    public function delete(){
        $params = $this->isPost() ? $this->input->post():$this->input->get();
        if(!$params['id']){
            $result = array_for_result(false, 'id needed');
        }else{
            $ret = $this->tips_model->deleteTips($params['id']);
            if($ret){
                $result = array_for_result(true, $this->tips_model->getModelError());
            }else{
                $result = array_for_result(false, $this->tips_model->getModelError());
            }
        }
        $this->renderJson($result);
    }

    /**
     * 更改提示状态
     * @return json
     */
    public function changestatus(){
        $params = $this->isPost() ? $this->input->post():$this->input->get();
        if(!$params['tips_id']){
            $result = array_for_result(false, 'id needed');
        }else{
            $ret = $this->tips_model->changeStatus($params['tips_id'], $params['status']);
            if($ret){
                $result = array_for_result(true, $this->tips_model->getModelError());
            }else{
                $result = array_for_result(false, $this->tips_model->getModelError());
            }
        }
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