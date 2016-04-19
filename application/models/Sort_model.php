<?php

class Sort_model extends GT_model{

    //protected $_table_name = '';

    //protected $_pk = '';

    public function __construct(){
        parent::__construct();
    }

    public function test($data){
        pr($data);
        $new_data = $this->heapSort($data, count($data)-1 );
        pr($data);
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

    /**
     * 合并排序
     */
    public function mergeSort(&$data_arr, $low, $high){
        if($low<$high){
            $mid = $low + ($high - $low)/2;
            $this->mergeSort($data_arr, $low, $mid);
            $this->mergeSort($data_arr, $mid+1, $high);
            $this->merge($data_arr, $low, $mid, $high);
        }
    }

    /**
     * 合并排序合并数组
     */
    public function merge(&$data_arr, $low, $mid, $high){
        $i = $low;
        $j = $mid+1;
        $k = $i;
        while($i <= $mid && $j <= $high){
            if($data_arr[$i] <= $data_arr[$j]){
                $b[$k++] = $data_arr[$i];
            }else{
                $b[$k++] = $data_arr[$j];
                $j++;
            }
        }
        while( $i<=$mid){
            $b[$k++] = $data_arr[$i++];
        }
        while( $j<= $high){
            $b[$k++] = $data_arr[$j++];
        }
        for($x=0,$i=$low; $x < $high-$low+1; $x++, $i++){
            $data_arr[$i] = $b[$i];
        }

    }

    /**
     * 快排
     * @param array &$data_arr
     * @param int $p 数组序号，最小
     * @param int $r 数组序号，最大
     * @return void
     */
    public function quickSort(&$data_arr, $p, $r){
        if($p < $r){
            $x = $data_arr[$r];
            $i = $p -1;
            for($j = $p; $j < $r; $j++){
                if($data_arr[$j] < $x){
                    $i++;
                    $temp = $data_arr[$j];
                    $data_arr[$j] = $data_arr[$i];
                    $data_arr[$i] = $temp;
                }
            }
            $temp = $data_arr[$i+1];
            $data_arr[$i+1] = $data_arr[$r];
            $data_arr[$r] = $temp;
            $this->quickSort($data_arr, $p, $i);
            $this->quickSort($data_arr, $i+2, $r);
        }
    }

    /**
     * 堆排序
     * @param array $data_arr
     * @param int $n 数组最大索引
     * @return array
     */
    public function heapSort($data_arr, $n){
        for($i = $n/2; $i>0; $i--){
            $this->maxHeapify($data_arr, $i, $n);
        }
        while($n > 0){
            $temp = $data_arr[$n];
            $data_arr[$n] = $data_arr[0];
            $data_arr[0] = $temp;

            --$n;
            $this->maxHeapify($data_arr, 0, $n);
        }
        return $data_arr;
    }

    /**
     * 最大堆排序
     * @param array $data_arr 需要排序的数组，引用方式
     * @param int $p 最小的数组索引
     * @param int $n 最大的数组索引
     * @return void
     */
    public function maxHeapify(&$data_arr, $p, $n){
        $left = 2*$p;
        $right = 2*$p + 1;
        $large = $p;
        if($left <= $n && $data_arr[$left] > $data_arr[$p] ){
            $large = $left;
        }
        if($right <= $n && $data_arr[$right] > $data_arr[$large] ){
            $large = $right;
        }
        if($large != $p){
            $temp = $data_arr[$p];
            $data_arr[$p] = $data_arr[$large];
            $data_arr[$large] = $temp;
            $this->maxHeapify($data_arr, $large, $n);
        }
    }

}