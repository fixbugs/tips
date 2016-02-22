<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends GT_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('news_model');
        $this->load->helper('url_helper');
    }

    public function index()
    {
        $params = $this->input->get();
        var_dump($params);
        $data['news'] = $this->news_model->get_news();
    }

    public function view($slug = NULL)
    {
        $data['news_item'] = $this->news_model->get_news($slug);
    }
}