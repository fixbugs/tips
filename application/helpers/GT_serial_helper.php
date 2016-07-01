<?php

/**
 * 生成64位id
 * @param int $vsid
 * @return true
 */
function make_shard_id($vsid)
{
    return makeSerialId($vsid);
}

/**
 * 生成一个新格式全局序列ID
 *
 * 格式：(42B microtime) + (12B vsid) + (10B autoinc)
 *
 * @access public
 * @param  void
 * @return mixed  $serial_id
 */
function makeSerialId($vsid)
{
    if(!is_numeric($vsid) || $vsid < 1 || $vsid > 4095)
        return false;
    else
        $vsid = (int)$vsid;

    $auto_inc_sig = getNextValueByShareMemory();
    if(empty($auto_inc_sig))
        return false;

    $ntime = microtime(true);
    $time_sig = intval($ntime * 1000);
    $serial_id = $time_sig << 12 | $vsid;
    $serial_id = $serial_id << 10 | ($auto_inc_sig % 1024);
    return (string)$serial_id;
}

/**
 * 通过本机共享内存件来生成一个auto_increment序列
 *
 * 序列类似MySQL的auto_increment
 *
 * @access private
 * @param  void
 * @return mixed
 */
function getNextValueByShareMemory()
{
    $addr = '127.0.0.1';
    if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        $addr = $_SERVER['HTTP_X_FORWARDED_FOR'];
    elseif(!empty($_SERVER['SERVER_ADDR']))
        $addr = $_SERVER['SERVER_ADDR'];

    $skey = 'global_serial_generator_seed_'.$addr;
    $ikey = crc32($skey);

    $sem = $shm = null;
    $retry_times = 1;
    do{
        $sem = sem_get($ikey, 1, 0777);
        $shm = shm_attach($ikey, 128, 0777);
        if(is_resource($sem) && is_resource($shm))
            break;

        $cmd = "ipcrm -M 0x00000000; ipcrm -S 0x00000000; ipcrm -M {$ikey} ; ipcrm -S {$ikey}";
        $last_line = exec($cmd, $output, $retval);
    }while($retry_times-- > 0);

    if(!sem_acquire($sem)){
        return false;
    }
    $next_value = false;
    if(shm_has_var($shm, $ikey)){
        shm_put_var($shm, $ikey, $next_value=shm_get_var($shm, $ikey)+1);
    }else{
        shm_put_var($shm, $ikey, $next_value=1);
    }
    $shm && shm_detach($shm);
    $sem && sem_release($sem);
    return $next_value;
}

/**
 * 从新格式全局序列ID反解析出虚拟shard编号
 * @access public
 * @param  int  $serialId  新格式全局序列ID
 * @return int  $vsid      虚拟shard编号或者false
 */
function extractVirtShardId($serialId)
{
    if(!$serialId || !is_numeric($serialId)){
        return false;
    }else{
        $serialId = (int)$serialId;
    }
    if(isCompatSerialId($serialId)){
        $oldId = $flag = $vsid = 0;
        if(!extractCompatSerialInfo($serialId, $oldId, $flag, $vsid))
            return false;
        else
            return $vsid;
    }
    elseif(isGlobalSerialId($serialId)){
        return $serialId >> 10 & (0xFFF);
    }else{
        return false;
    }
}

/**
 * 是否是兼容格式全局序列ID
 * @access public
 * @param  int  $serialId
 * @return bool
 */
function isCompatSerialId($serialId)
{
    $high28b = $serialId >> 36;
    if(0 == $high28b)
        return false;
    $high4b = $serialId >> 60 & 0xF; // 最高4位的值
    return 0 == $high4b;
}

/**
 * 解析是兼容格式全局序列ID获取对应的信息
 *
 * 格式：(4B 0) + (12B flag) + (12B vsid) + (36B old id)
 *
 * @access public
 * @param  int  $serialId
 * @param  int  $oldId  老式36-integer
 * @param  int  $flag   老式12-integer ID的类型标识
 * @param  int  $vsid   该ID记录的虚拟shard编号（12-integer）
 * @return mixed
 */
function extractCompatSerialInfo($serialId, &$oldId, &$flag, &$vsid)
{
    if(!$serialId || !is_numeric($serialId)){
        return false;
    }else{
        $serialId = (int)$serialId;
    }

    if(!isCompatSerialId($serialId)){
        return false;
    }
    $oldId = $serialId & 0xFFFFFFFFF;
    $vsid = $serialId >> 36 & 0xFFF;
    $flag = $serialId >> 48 & 0xFFF;
    return true;
}

/**
 * 判断是否是新格式的新格式的全局序列id
 * @access public
 * @param  int  $serialId  新格式全局序列ID
 * @return bool
 */
function isGlobalSerialId($serialId)
{
    $high28b = $serialId >> 36;
    if(!$high28b){
        return false;
    }
    $high4b = ($serialId >> 60) & 0xF; // 最高4位的值
    return 0 != $high4b;
}

/**
 * 返回是否为有效64位ID
 * @access private
 * @param  int  $serialId
 * @return bool
 */
function isValidSerialId($serialId)
{
    return isGlobalSerialId($serialId) || isCompatSerialId($serialId);
}
