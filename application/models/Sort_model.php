<?php

class Sort_model extends GT_model{

    //protected $_table_name = '';

    //protected $_pk = '';

    public function __construct(){
        parent::__construct();
    }

    public function test($data){
        pr($data);
        $new_data = $this->selectSort($data, count($data));
        pr($new_data);
        return $new_data;
    }

    /**
     * 基础排序
     * @param array $data_arr
     * @param int $len
     * @param array
     */
    public function selectSort($data_arr, $len){
        for($i=0; $i<$len; $i++){
            $min_pos = $i;
            for($j= $i+1; $j<$len; $j++){
                if($data_arr[$j] < $data_arr[$min_pos]){
                    $min_pos = $j;
                }
            }
            if($min_pos != $i){
                $temp = $data_arr[$i];
                $data_arr[$i] = $data_arr[$min_pos];
                $data_arr[$min_pos] = $temp;
            }
        }
        return $data_arr ? $data_arr:array();
    }

}