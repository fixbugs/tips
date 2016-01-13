<?PHP
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
        $this->load->model('user_model');
        $params['username'] = 'admin';
        $params['password'] = '123456';
        $ret = $this->user_model->checkLogin($params);
        var_dump($ret);
    }
}
