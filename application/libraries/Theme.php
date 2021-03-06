<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *主题系统
 */
class Theme{
    /**
     * set is need layout show, default true
     */
    private $_needLayout = true;

    /**
     * used CI instance function to get CI handle
     */
    protected $CI;

    /**
     * init theme for theme for CI
     */
    public function __construct(){
        $this->CI =& get_instance();
    }

    /**
     * 渲染模板方法
     * @return void
     */
    public function render($template, $data){
        if($this->_needLayout){
            $this->CI->load->view('templates/header', $data);
            $this->CI->load->view($template, $data);
            $this->CI->load->view('templates/footer', $data);
        }else{
            $this->CI->load->view($template,$data);
        }
    }

    /**
     * 渲染模板方法
     * @return void
     */
    public function h5render($template, $data){
        if($this->_needLayout){
            $this->CI->load->view('h5/header', $data);
            $this->CI->load->view('h5/'.$template, $data);
            $this->CI->load->view('h5/footer', $data);
        }else{
            $this->CI->load->view($template,$data);
        }
    }

    /**
     * 设置是否展示公共框架
     * @return void
     */
    public function setLayout($need=false){
        $this->_needLayout = $need;
    }

}