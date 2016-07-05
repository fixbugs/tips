<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 提醒控制器
 */
class Tag extends GT_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('tags_model');
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
            $data['title'] = 'Tag List';
            $data['tags'] = $this->tags_model->getAllTags($params);
            $this->render('tag/index', $data);
        }
    }

    /**
     * 添加标签方法
     * @return mixed
     */
    public function add(){
        if($this->isPost()){
            $params = $this->isPost() ? $this->input->post():$this->input->get();
            $ret = $this->tags_model->addTags($params);
            if($ret){
                $result = array_for_result(true, $this->tags_model->getModelError());
            }else{
                $result = array_for_result(false, $this->tags_model->getModelError());
            }
            $this->renderJson($result);
        }else{
            $params = $this->input->get();
            $data['title'] = 'Tag Add';
            $data['action'] = 'add';
            $this->render('tag/add', $data);
        }
    }

}