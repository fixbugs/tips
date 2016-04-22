<?PHP
defined('BASEPATH') OR exit('No direct script access allowed');

class test extends GT_Controller{
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

    public function testfunc(){
        $res = getRandomStr(2);
        var_dump($res);
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

    public function testmodel(){
        $this->load->model('test_model');
        $ret = $this->test_model->getTableDesign();
        var_dump($ret);
    }

    public function testhooks(){
        $params = $this->input->post();
        $last_line = system('bash /var/www/tips/.git/hooks/post-receive', $retval);
        $return_arr['line'] = $last_line;
        $return_arr['cmret'] = $retval;
        $return_arr['status'] = true;
        $this->renderJson($return_arr);
    }

    public function testolcoding(){
        $params = $this->input->get();
        if(!$params['code']){
            $code = 'echo 1;';
        }else{
            $code = $params['code'];
        }
        ob_start();
        $res = eval( $code);
        $output = trim(ob_get_clean());
        $result = array(
            'code' => $code,
            'output' => $output,
            'code_res' => $res,
            'status' => true,
            );
        $this->renderJson($result);
    }

    public function testcurlml(){
        $urls = array(
            'http://stat.leju.com/api/data/getrankdatabyuids?app_key=aeaa676f40d5974c335323cafc52c7c8&unique_id=abcdefg,bbccdd',
            'http://stat.leju.com/api/data/getrank?app_key=aeaa676f40d5974c335323cafc52c7c8&plat_key=pc',
            'http://stat.leju.com/api/data/getrankrecord?app_key=aeaa676f40d5974c335323cafc52c7c8&unique_id=abcdefg&plat_key=pc',
            );
        $result = curl_get_ml($urls);
        var_dump($result);
    }

    public function testtags(){
        $this->load->model('tags_model');
        $params['page'] = 1;
        $params['limit'] =1;
        $data = $this->tags_model->getAllTags($params);
        var_dump($this->tags_model->getLastSql());
        var_dump($data);
    }

    public function testsort(){
        $this->load->model('sort_model');
        $start_time = time();
        $data = array(2, 77 , 88, 55, 33, 2, 5, 6);
        $af_data = $this->sort_model->test($data);
        $used_time = time()- $start_time;
        var_dump($used_time.'s');
    }

    public function testphpfunc(){
        $a = array('a', 'b');
        $b = array('c', 'd');
        $c = $a + $b;
        var_dump($c);
        var_dump(array_merge($a, $b));
        var_dump("-----------1-----------");

        $a = array(0=>'a', 1=>'b');
        $b = array(0=>'c', 1=>'b');
        $c = $a + $b;
        var_dump($c);
        var_dump(array_merge($a, $b));
        var_dump("-----------2-----------");

        $a = array('a', 'b');
        $b = array(0=>'c', 1=>'b');
        $c = $a + $b;
        var_dump($c);
        var_dump(array_merge($a, $b));
        var_dump("-----------3-----------");

        $a = array(0=>'a', 1=>'b');
        $b = array('0'=>'c', '1'=>'b');
        $c = $a + $b;
        var_dump($c);
        var_dump(array_merge($a, $b));
        var_dump("-----------4-----------");
    }

    public function testspec(){
        echo '1'.print(2)+3;//511 + > print > string

        $a = 0x01;
        $b = 0x02;
        echo $a===$b>>$a; //1 >> > ===

        print (196*100) !== (double)1960;//1

    }

    public function testphpcode(){
        $str = "abcdefg";
        echo strrevv($str);
    }

}

