<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_m extends CI_Model
{
    public function __construct(){
      		parent::__construct();
            $this->ci_db = $this->load->database('default',TRUE);
      		$this->game_db = $this->load->database('game',TRUE);
            $this->game_mysql_model = new Mysql_model('game');
    }
    
    /**
     * 获得用户列表
     * @param $uid
     * @return array
     */
    public function getUserList($uid)
    {
        $invote = $this->getInvoteStr($uid);
        if($_SESSION['user_info']['level'] == 0){
            $condition = '';
        }else{
            $condition = "and mu.mg_user_id in  ( " . $invote . ")";
        }
        $sql = "SELECT distinct mu.*, mu.card AS curr_count 
                FROM `mg_user` mu               
                WHERE 1=1 {$condition} ORDER BY mu.mg_user_id";
        $list = $this->db->query($sql)->result_array();
        return $list;
    }

    /**
     * 获得用户搜索结果
     * @param $uid 用户ID
     * @return array
     */
    public function getUserInfo($str)
    {
        if($res = $this->isExists(array('mg_user_id' => $str))){
            $uid = $res['mg_user_id'];
        }else if($res = $this->isExists(array('mg_user_name' => $str))){
            $uid = $res['mg_user_id'];
        }else{
            return $list = array();
        }
        $userList =  $this->getInvoteStr($_SESSION['user_info']['mg_user_id']);
        $userArray = explode(',',$userList);
        if(in_array($uid,$userArray)){
            //change_by_lk_new_3_start
            $sql = "SELECT mu.*, mu.card AS curr_count
                    FROM `mg_user` mu 
                    WHERE mu.mg_user_id = {$uid} or mu.mg_name = '{$uid}'";
            //change_by_lk_new_3_end 
            $list = $this->db->query($sql)->result_array();
        }else{
            $list = array();
        }
        return $list;
    }
    
    public function getInvote($uid) {
        $arr = array();
        $sql = "select mg_user_id,level from mg_user where p_mg_user_id = ".$uid;
        $datas = $this->db->query($sql)->result_array();
        if(!empty($datas)){
            foreach ($datas as $data) {
                $arr[] = $data['mg_user_id'];
                $tmp = $this->getInvote($data['mg_user_id']);
                $arr = array_merge($arr,$tmp);
            }
        }
        return $arr;
    }
    public function getInvote2($uids,&$arr) {
//        $tmps = array();
//        $sql = "select mg_user_id from mg_user where p_mg_user_id in( ". implode(',', $uids) .")";
//        $datas = $this->db->query($sql)->result_array();
//        if(!empty($datas)){
//            foreach ($datas as $data) {
//                $arr[] = $data['mg_user_id'];
//                $tmps[] = $data['mg_user_id'];
//            }
//            list($a,$b) = $this->getInvote2($tmps, $arr);
//            $arr = array_merge($b,$arr);
//        }
//        return array($tmps,$arr);
        
        $tmps = array();
        $sql = "select mg_user_id from mg_user where p_mg_user_id in( ". implode(',', $uids) .")";
        $datas = $this->db->query($sql)->result_array();
        if(!empty($datas)){
            foreach ($datas as $data) {
                $arr[] = $data['mg_user_id'];
                $tmps[] = $data['mg_user_id'];
            }
            $this->getInvote2($tmps, $arr);
        }
        
    }
    
    public function checkInvote($puid, $uid) {
        while (true) {
            $sql = "select p_mg_user_id,mg_user_id from mg_user where mg_user_id = ".$uid;
            $info = $this->db->query($sql)->row_array();
            if(empty($info)) {
                return false;
            }else{
                if($info['p_mg_user_id']==$puid) {
                    return true;
                }else{
                    $uid = $info['p_mg_user_id'];
                }
            }
        }
    }


    /**
     * 获得子mg_id字符串
     * @param $uid
     * @return string
     */
    public function getInvoteStr($uid, $return_type = 'string')
    {
        $arr = $this->getInvote($uid);
        $arr[] = $uid;
        if($return_type=='string') {
            return implode(',', $arr);
        }
        return $arr;
    }
    
    /**
     * 获得子mg_id字符串
     * @param $uid
     * @return string
     */
    public function getInvoteStr2($uid, $return_type = 'string')
    {
        $arr = $this->getInvote($uid);
//        $arr[] = $uid;
        if($return_type=='string') {
            return implode(',', $arr);
        }
        return $arr;
    }
    
    
    /**
     * 插入用户
     * @param $post
     * @return bool
     */
    public function insertUser($post)
    {       
            if($this->isExists(array('mg_user_id' => $post['mg_user_id']))){
                return 1;
            }
            if($this->db->insert('mg_user', $post)){
                return 0;
            }else{
                return 2;
            }
    }

    /**
     * 更新用户
     * @param $post
     * @param $mg_user_id
     * @return bool
     */
    public function updateUser($post,$mg_user_id)
    {
        if($this->db->where('mg_user_id',$mg_user_id)->update('mg_user',$post)){
//             $sql = "update user set invotecode = {$mg_user_id} where user_id = {$post['invotecode']}";
//             if($this->db->query($sql)){
                return true;
//             }else{
//                 return false;
//             }
        }else{
            return false;
        }
        
    }

    /**
     * 删除用户
     * @param $mg_user_id
     * @return mixed
     */
    public function deleteUser($mg_user_id)
    {
        return $this->db->where('mg_user_id',$mg_user_id)->delete('mg_user');
    }

    private function getArray($childs,$user_id=0){ 
        $sql = "SELECT distinct mu.*, mu.card AS curr_count 
                FROM `mg_user` mu               
                WHERE mu.p_mg_user_id = $user_id and mu.status = 1 and mu.mg_user_id in (".$childs.") ORDER BY mu.level DESC, mu.create_time DESC";
                // echo $sql;exit;
        $result = $this->db->query($sql)->result_array();
        $arr = array(); 
        $i=0;
        if($result){//如果有子类
            foreach($result as $rows){
                $rows['list'] = $this->getArray($childs,$rows['mg_user_id']); //调用函数，传入参数，继续查询下级
                $arr[$i] = $rows; //组合数组
                $arr[$i]['is_have'] = $rows['list']?1:0;

                $i++;
            } 
            return $arr; 
        } 
    }

    public function getAllUsers($uid){

        $u_list = $this->getInvoteStr($_SESSION['user_info']['mg_user_id'], 'array');
        if(!in_array($uid, $u_list)){
            return array();
        }
        $new_list = $this->getArray($this->getInvoteStr($uid), $uid);
        return $new_list;
    }

    /**
     * getOnLineUser 获得在线用户记录
     * @param  string $where 条件
     * @param  intval $start 开始记录
     * @param  intval $limit 限制条数
     * @return array         
     */
    public function getOnLineUser($where, $start, $limit){
        // $sql = "select * from mg_user where ".$where." limit ".$start.','.$limit;
        // $res = $this->db->query($sql)->result_array();
        $res = $this->game_mysql_model->get_results('user', $where, 'create_time desc', $start, $limit);
        return $res;
    }

    /**
     * getOnLineUserCount 获得在线用户数量
     * @param  array $where 条件
     * @return intval        
     */
    public function getOnLineUserCount($where){
        $count = $this->game_mysql_model->get_count('user', $where);
        // echo $this->game_mysql_model->last_query_sql();exit;
        return $count;
    }
    /**
     * [isExists 是否在在用户]
     * @param  array  $where [条件]
     * @param  integer $type  [1:增加时， 0是编辑时]
     * @param  integer $uid   作为对比的id
     * @return fixed        
     */
    public function isExists($where, $type = 1, $uid = 'default'){
        if($type){
            // 增加时不能有重复
            $res = $this->ci_db->get_where('mg_user', $where)->row_array();
        }else{
            if($uid == 'default'){
                die('必须带个uid过来，排除本身');
            }
            // 编辑时不能有重复,必须带个uid过来，排除本身
            $res = $this->ci_db->get_where('mg_user', $where)->row_array();

            if($res && $res['mg_user_id'] != $uid){
                $res = true; // 有重复，不可用
            } else{
                $res = false; // 无重复，可用
            }
        }
        return $res;
    }

    public function getAllUids($user_id = 1){
        $u_list = $this->getInvoteStr($user_id,'array'); // 1是admin的id
        return $u_list;
    }

    public function getNextLower($uid){
        $sql = "SELECT distinct mu.*, mu.card AS curr_count 
                FROM `mg_user` mu               
                WHERE mu.p_mg_user_id = $uid ORDER BY mu.create_time DESC";
                // echo $sql;exit;
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
}