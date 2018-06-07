<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Broad_model extends CI_Model
{
    public function __construct(){
      		parent::__construct();
      		$this->ci_db = $this->load->database('default',TRUE);
    }

    /**
     * getBroads 得到当前用户创建的广播
     * @param  string $where 条件
     * @param  intval $start 开始记录
     * @param  intval $limit 限制条数
     * @return array         
     */
    public function getBroads($where, $start, $limit){
        $res = $this->mysql_model->get_results('mg_broadcast', $where, 'create_time desc', $start, $limit);
        return $res;
    }

    /**
     * getBroadsCount 获得在线用户数量
     * @param  array $where 条件
     * @return intval        
     */
    public function getBroadsCount($where){
        $count = $this->mysql_model->get_count('mg_broadcast', $where);
        return $count;
    }

    public function addBroad($post){
    	$res1 = $this->db->insert('mg_broadcast', $post);
    	$res2 = $this->syncBroad();
    	return ($res1 && $res2)?true:false;
    }

    public function editBroad($post, $broad_id){
    	$res1 = $this->db->where('id', $broad_id)->update('mg_broadcast', $post);
    	$res2 = $this->syncBroad();
    	return ($res1 && $res2)?true:false;
    }

    public function delBroad($broad_id){
    	$res1 = $this->db->where('id',$broad_id)->delete('mg_broadcast');
    	$res2 = $this->syncBroad();
    	return ($res1 && $res2)?true:false;
    }

    private function syncBroad(){
    	return true;
    }
}