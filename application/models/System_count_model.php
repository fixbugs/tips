<?php
/**
 * system_count表模型，记录系统点击model
 */
class System_count_model extends GT_Model {

    protected $_table_name = 'system_count';

    protected $_pk = 'id';

    public function __construct(){
        parent::__construct();
    }

    /**
     * 获取参数索取系统统计条目内容
     * @param array $cond
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getAllSystemCount($cond, $page=1, $limit=10){
        $cond['page'] = $page;
        $cond['limit'] = $limit;
        $result = $this->findAllByAttr($cond);
        if(!empty($result)){
            return $result;
        }
        return array();
    }

    /**
     * 根据用户ip获取访问数据
     * @param string $ip IP
     * @param int $page 1
     * @param int $limit 10
     * @return array
     */
    public function getDataByIp($ip, $page=1, $limit=10){
        if(ip2long($ip)=='-1'){
            return array();
        }
        $cond['user_ip'] = $ip;
        $cond['limit'] = $limit;
        $cond['page'] = $page;
        $this->setOrderBy('create_time', 'DESC');
        $data = $this->findAllByAttr($cond);
        if($data){
            return $data;
        }
        return array();
    }

}