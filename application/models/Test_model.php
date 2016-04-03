<?php

class Test_model extends GT_model{

    protected $_table_name = 'user';

    protected $_pk = 'user_id';

    public function __construct(){
        parent::__construct();
    }

    public function test(){
        // $id = '6094091879463563272';
        // $ret = $this->getById($id);
        // var_dump($ret);
        $num = 21;
        $rand_str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ~@#()_=!`$%^&*{}|?';
        $rand_array = str_split($rand_str);
        $res = '';
        for($i=0;$i<=$num;$i++){
            foreach($rand_array as $str){
                $status = false;
                while(true){
                    $start_time = microtime(true);
                                        var_dump($this->getLastSql());
                    if(microtime(true)-$start_time>4){
                        pr("okokokok");
                        $res .= $str;
                        $status = true;
                        sleep(1);
                        break;
                    }
                    break;
                    sleep(1);
                }
                if($status){
                    break;
                }
            }
        }
        var_dump($res);
        die("ddd");
        #$data['password'] = "bsed";
        $result = $this->findByAttr($data);
        var_dump($this->getLastSql());
        var_dump($result);
    }

}