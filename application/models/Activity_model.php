<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Activity_model extends CI_Model
{
    public function __construct(){
      		parent::__construct();
      		$this->game_db = $this->load->database('game',TRUE);
            $this->game_mysql_model = new Mysql_model('game');
    }

    /**
     * getBroads 得到当前用户创建的广播
     * @param  string $where 条件
     * @param  intval $start 开始记录
     * @param  intval $limit 限制条数
     * @return array         
     */
    public function getBroads($where, $start, $limit){
        
        $res = $this->game_mysql_model->get_results('config_huodong', $where, 'id desc', $start, $limit);
        return $res;
    }

    /**
     * getBroadsCount 
     * @param  array $where 条件
     * @return intval        
     */
    public function getBroadsCount($where){
        $count = $this->game_mysql_model->get_count('config_huodong', $where);
        return $count;
    }

    public function addBroad($post){
    	$res1 = $this->game_mysql_model->insert('config_huodong', $post);
    	$res2 = $this->syncBroad();
    	return ($res1 && $res2)?true:false;
    }

    public function editBroad($post,$mtype){
    	$res1 = $this->game_db->update('config_huodong', $post, array('mtype'=>$mtype));
    	$res2 = $this->syncBroad();
    	return ($res1 && $res2)?true:false;
    }

    public function delBroad($broad_id){
        $tmp = $this->game_mysql_model->get_where('config_huodong', array('id' => 1))->row_array();
    	$res1 = $this->game_mysql_model->where('id',1)->delete('config_huodong');
    	$res2 = $this->syncBroad();
        // 删除图片
        unlink('uploads/huodong/'.$tmp['hd_img']);
    	return ($res1 && $res2)?true:false;
    }

    private function syncBroad(){
    	return true;
    }
}