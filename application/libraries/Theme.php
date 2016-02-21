<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *主题系统
 */
class Theme{

    protected $CI;
    public function __construct(){
        $this->CI =& get_instance();
    }

    public function render($template, $data){
        $this->CI->load->view('templates/header', $data);
        $this->CI->load->view($template, $data);
        $this->CI->load->view('templates/footer', $data);
    }

}