<?php

class Sort_model extends GT_model{

    //protected $_table_name = '';

    //protected $_pk = '';

    public function __construct(){
        parent::__construct();
    }

    public function test($data){
        pr($data);
        $new_data = $this->shellSort($data, count($data));
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

    /**
     * 冒泡排序
     */
    public function bubbleSort($data_arr, $len){
        for($i=0; $i<$len; $i++){
            $flag = false;
            for($j=$len-1; $j>$i; $j--){
                if($data_arr[$j] < $data_arr[$j-1]){
                    $temp = $data_arr[$j];
                    $data_arr[$j] = $data_arr[$j-1];
                    $data_arr[$j-1] = $temp;
                    $flag = true;
                }
            }
            if(!$flag){
                return $data_arr;
            }
        }
        return $data_arr ? $data_arr:array();
    }

    /**
     * 插入排序
     */
    public function insertSort($data_arr, $len){
        for($i=1; $i<=$len-1; $i++){
            $j = $i-1;
            $temp = $data_arr[$i];
            while($j >= 1 ){
                if($data_arr[$j] > $temp){
                    $data_arr[$j+1] = $data_arr[$j];
                    $j--;
                }else{
                    break;
                }
            }
            $data_arr[$j+1] = $temp;
        }
        return $data_arr;
    }

    /**
     * 希尔排序
     */
    public function shellSort($data_arr, $len){
        for($gap=5; $gap>0; $gap-=2){
            for($i = $gap+1; $i< $len; $i++){
                $j = $i - $gap;
                $temp = $data_arr[$i];
                while($j >= 1){
                    if($data_arr[$j]> $temp){
                        $data_arr[$j + $gap] = $data_arr[$j];
                        $j -= $gap;
                    }else{
                        break;
                    }
                }
                $data_arr[$j+$gap] = $temp;
            }
        }
        return $data_arr;
    }

}