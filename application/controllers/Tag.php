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
}