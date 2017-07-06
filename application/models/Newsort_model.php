<?php

function bubbleSort($data_arr, $len){
    for($i=0;$i<$len;$i++){
        $flag = false;
        for($j=$len-1;$j>$i;$j--){
            if($data_arr[$j] < $data_arr[$j-1]){
                $tmp = $data_arr[$j];
                $data_arr[$j] = $data_arr[$j-1];
                $data_arr[$J-1] = $tmp;
                $flag = true;
            }
        }
        if(!$flag){
            return $data_arr;
        }
    }
}