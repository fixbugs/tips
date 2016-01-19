<?PHP
defined('BASEPATH') OR exit('No direct script access allowed');

class test extends CI_Controller{
    //contronller name cannot same as app name
    //public function test($params='home'){
    //  $data['title'] = 'hello';
        //$this->load->view('templates/header',$data);
        //die('aa');
        //$this->load->view('templates/footer',$data);
    //}

    public function view($page = 'home')
    {
        $data['title'] = ucfirst($page); // Capitalize the first letter
        $this->load->view('templates/header', $data);
        $this->load->view('templates/footer', $data);
    }

    public function testuser(){
        $id = make_shard_id(VSID);
        echo $id;
        $this->load->model('user_model');
        $params['username'] = 'admin';
        $params['password'] = '123456';
        echo USER_ID;
        echo USER_NAME;
        //        $ret = $this->user_model->deleteById('6094095278095511561');
        //var_dump($ret);
    }

    public function testhooks(){
        $params = $this->input->post();
        $last_line = system('bash /var/www/tips/.git/hooks/post-receive', $retval);
        $return_arr['line'] = $last_line;
        $return_arr['cmret'] = $retval;
        $return_arr['status'] = true;
        $this->renderJson($return_arr);
    }
}

