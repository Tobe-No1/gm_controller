<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Charge_model extends CI_Model
{
    public function __construct(){
      		parent::__construct();
      		$this->game_db = $this->load->database('game',TRUE);
            $this->game_mysql_model = new Mysql_model('game');

    }

    /**
     * getBroads 得到记录
     * @param  string $where 条件
     * @param  intval $start 开始记录
     * @param  intval $limit 限制条数
     * @return array         
     */
    public function getRecords($where, $start, $limit){
        $res = $this->game_mysql_model->get_results('charge', $where, 'create_time desc', $start, $limit);
        return $res;
    }
    
    public function getRecord($where,$select) {
        $res = $this->game_mysql_model->get_results('charge', $where, '', 0, 0, $select);
        return $res[0];
        
    }

    /**
     * getBroadsCount 获得记录数量
     * @param  array $where 条件
     * @return intval        
     */
    public function getRecordsCount($where){

        $count = $this->game_mysql_model->get_count('charge', $where);
        return $count;
    }

    /**
     * 获得
     * @param $uid 用户ID
     * @return array
     */
    public function getUserId($str)
    {
        if($res = $this->isExists(array('uid' => $str))){
            $uid = $res[0]['uid'];
        }else if($res = $this->isExists(array('name' => $str))){
            $uid = $res[0]['uid'];
        }else if($res = $this->isExists(array('invite_id' => $str))){
            $uid = $res[0]['uid'];
        }else{
            $uid = 0;
        }
        return $uid;
    }

    public function isExists($where){
            $res = $this->game_mysql_model->get_results('user', $where);
        return $res;
    }
}