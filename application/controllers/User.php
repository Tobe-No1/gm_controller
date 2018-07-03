<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller {

    private $model = 'user_m';

    public function __construct() {
        parent::__construct();
        $this->load->model($this->model);
        $this->game_db = $this->load->database('game', TRUE);
        if (!isset($_SESSION['user_info']) && empty($_SESSION['user_info'])) {
            $url = get_url('/index.php/Login/login');
            header("Location:$url");
        }
        $this->__user_info = $this->session->userdata('user_info');
        $this->role_names = $this->config->item('role_names');
        $this->output->enable_profiler(false);
    }

    public function baseinfo() {
        $mg_user_id = $_SESSION['user_info']['mg_user_id'];
        $sql = "select * from `mg_user` where mg_user_id={$mg_user_id}";
        $data = $this->mysql_model->query($sql, 1);
        $this->load->view('mon_basicinfo', $data);
    }

    //绑定列表
    public function bind_user_list() {
        $pid = $_SESSION['user_info']['mg_user_id'];
        $sql2 = "select b.* from bind_user as a left join user as b on a.uid = b.uid where a.pid =  " . $pid;
        $tmp = $this->game_db->query($sql2)->result_array();
        $data['players'] = $tmp;
        $this->load->view('mon_bind_user_list', $data);
    }

    public function add_bind_user() {

        if ($_POST) {
            //写入数据库
            $data = array(
                'pid' => $_SESSION['user_info']['mg_user_id'],
                'uid' => trim($_POST['uid']),
            );

            $sql = "select * from bind_user where uid = " . $data['uid'];
            $tmp = $this->game_db->query($sql)->result_array();
            if (!empty($tmp)) {
                $data['message'] = $_POST['uid'] . '已经绑定在系统中，请核查';
                $data['url'] = site_url('user/bind_user_list');
                $this->load->view('mon_message', $data);
                return;
            }

            $tag = $this->game_db->insert('bind_user', $data);
            if ($tag == true) {
                $data['message'] = '绑定成功';
                $data['url'] = site_url('user/bind_user_list');
            } else {
                $data['message'] = '绑定失败';
                $data['url'] = site_url('user/add/');
            }
            $this->load->view('mon_message', $data);
        } else {
            $data = array();
            $this->load->view('mon_add_bind_user', $data);
        }
    }
     public function ajax_qunzhu_num() {
        $uid = isset($_POST['uid']) ? $_POST['uid'] : 0;
        $can_qunzhu = isset($_POST['can_qunzhu']) ? $_POST['can_qunzhu'] : 0;
        $re = array('status' => 0);
        if (!empty($uid) && !empty($can_qunzhu)) {
            //查询是否存在
            $sql = "update mg_user set can_qunzhu = {$can_qunzhu} where mg_user_id = {$uid}";
            $this->db->query($sql);
        }
        echo json_encode($re);
    }
    
    public function ajax_change_phone() {
        $uid = isset($_POST['uid']) ? $_POST['uid'] : 0;
        $phone = isset($_POST['phone']) ? $_POST['phone'] : 0;
        $re = array('status' => 0);
        if (!empty($uid) && !empty($phone)) {
            //查询是否存在
            $sql = "update mg_user set phone = {$phone} where mg_user_id = {$uid}";
            $this->db->query($sql);
        }
        echo json_encode($re);
    }

    public function fang_room_list() {
        $login_uid = $this->__user_info['mg_user_id'];
        $where = '';

        if (isset($_GET['stime'])) {
            $stime = $_GET['stime'];
        } else {
            $stime = $this->uri->segment(3, date("Y-m-d", strtotime("-3 day")));
            $_GET['stime'] = $stime;
        }

        if (isset($_GET['etime'])) {
            $etime = $_GET['etime'];
        } else {
            $etime = $this->uri->segment(4, date("Y-m-d"));
            $_GET['etime'] = $etime;
        }
        
        if(isset($_GET['uid'])) {
            $uid = $_GET['uid'];
        }else{
            $uid = $this->uri->segment(5, 0);
            $_GET['uid'] = $uid;
        }
        
        $where .= ' and create_time >= ' . strtotime($stime) . ' and create_time <' . (strtotime($etime) + 86400);

        if ($uid > 0) {
            $where .= ' and uid=' . $uid;
        }

        $current = $this->uri->segment(6, 1); // 页数
        $limit = $this->session->userdata('limit');
        $start = ($current - 1) * $limit;
        //获得记录
        $sql = "select * from fang_room_log where qunzhu = {$login_uid} {$where} order by create_time desc limit $start,$limit";
        $list = $this->game_db->query($sql)->result_array();
        //获得总数
        $sql2 = "select count(*) as count,sum(cost_card) as cost_card from fang_room_log where qunzhu = {$login_uid} {$where}";
        $count_info = $this->game_db->query($sql2)->row_array();
        $count = $count_info['count'];
        $this->load->library('pagination');
        $config['base_url'] = site_url("User/fang_room_list/{$stime}/{$etime}/{$uid}");
        $config['total_rows'] = $count;
        $config['uri_segment'] = 6;

        $this->pagination->initialize($config);
        $page_link = $this->pagination->create_links();
        $data = array(
            'list' => $list,
            'show_condition' => $_GET,
            'page_link' => $page_link,
            'cost_card' => $count_info['cost_card'],
            'count'     => $count,
        );
        //调用视图
        $this->load->view('mon_fang_room_log', $data);
    }
    
    public function ajax_room_close(){
        if($_POST) {
            $room_id = $_POST['room_id'];
            $login_uid = $this->__user_info['mg_user_id'];

            $sql = "update vip_room_list set status = 1 where uid = {$login_uid} and room_id = {$room_id} ";
            $this->game_db->query($sql);

            $key = 'f42afdb92e2e66c24dfb9';
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            socket_connect($socket, $this->config->item('GMIP'), $this->config->item('GMPORT'));
            $arr = array(
                'cmd' => 34,
                'room_id' => $room_id,
                'account' => 'admin',
            );
            $str = json_encode($arr);
            $size = strlen($str);
            $binary_str = pack("na" . $size, $size, $str);
            socket_write($socket, $binary_str, strlen($binary_str));
            socket_close($socket);
            echo json_encode(array('status'=>0));
        }
    }
    
    public function vip_room_list() {

        $login_uid = $this->__user_info['mg_user_id'];
        $where = " ";

        if (isset($_GET['stime'])) {
            $stime = $_GET['stime'];
        } else {
            $stime = $this->uri->segment(3, date("Y-m-d"));
            $_GET['stime'] = $stime;
        }

        if (isset($_GET['etime'])) {
            $etime = $_GET['etime'];
        } else {
            $etime = $this->uri->segment(4, date("Y-m-d"));
            $_GET['etime'] = $etime;
        }

        $where .= ' and create_time >= ' . strtotime($stime) . ' and create_time <' . (strtotime($etime) + 86400);

//        $sql = "select a.*, sum(cost_card) as cost_card from vip_room_list as a left join room_log as b on a.uid = b.qunzhu and a.room_id = b.room_id where ".$where;
//        $list = $this->game_db->query($sql)->result_array();
//        
        $sql = "select * from vip_room_list where uid = {$login_uid} order by create_time desc ";
        $list = $this->game_db->query($sql)->result_array();
        
        foreach($list as &$li) {
            $sql2 = "select sum(cost_card) as cost_card from room_log where qunzhu = {$li['uid']} and room_id = {$li['room_id']}" . $where;
            $rec = $this->game_db->query($sql2)->row_array();
            $li['cost_card'] = $rec['cost_card'];
            
        }
        $data = array(
            'list' => $list,
            'show_condition' => $_GET,
        );
        //调用视图
        $this->load->view('mon_vip_room_log', $data);
    }
    
    public function vip_room_list2() {
        $login_uid = $this->__user_info['mg_user_id'];
        $where = "  ";

        if (isset($_GET['stime'])) {
            $stime = $_GET['stime'];
        } else {
            $stime = $this->uri->segment(3, date("Y-m-d"));
            $_GET['stime'] = $stime;
        }

        if (isset($_GET['etime'])) {
            $etime = $_GET['etime'];
        } else {
            $etime = $this->uri->segment(4, date("Y-m-d"));
            $_GET['etime'] = $etime;
        }
        
        if (isset($_GET['room_id'])) {
            $room_id = $_GET['room_id'];
        } else {
            $room_id = $this->uri->segment(5);
            $_GET['room_id'] = $room_id;
        }

        $where .= ' and b.create_time >= ' . strtotime($stime) . ' and b.create_time <' . (strtotime($etime) + 86400) . ' and b.room_id = '.$room_id;

        $current = $this->uri->segment(6, 1); // 页数
        $limit = $this->session->userdata('limit');
        $start = ($current - 1) * $limit;
//        获得记录
        $sql = "select b.* from vip_room_list as a left join  room_log as b on a.room_id = b.room_id and a.uid = b.qunzhu where a.uid = {$login_uid} {$where} order by b.create_time desc limit $start,$limit";
        $list = $this->game_db->query($sql)->result_array();
        //获得总数
        $sql2 = "select count(b.id) as count from vip_room_list as a left join  room_log as b on a.room_id = b.room_id  and a.uid = b.qunzhu where a.uid = {$login_uid} {$where}";
        $count_info = $this->game_db->query($sql2)->row_array();
        $count = $count_info['count'];
        $this->load->library('pagination');
        $config['base_url'] = site_url('User/vip_room_list2/' . $stime . '/' . $etime . '/' . $room_id);
        $config['total_rows'] = $count;
        $config['uri_segment'] = 6;

        $this->pagination->initialize($config);
        $page_link = $this->pagination->create_links();
        $data = array(
            'list' => $list,
            'page_link' => $page_link,
            'show_condition' => $_GET,
        );
        //调用视图
        $this->load->view('mon_vip_room_log2', $data);
    }

    public function del_bind_user() {
        $uid = trim($_GET['uid']);
        if ($uid) {
            $sql = "delete from bind_user where uid = " . $uid;
            $tmp = $this->game_db->query($sql);
            $data = array();
            $data['message'] = '解除绑定成功';
            $data['url'] = site_url('user/bind_user_list');
            $this->load->view('mon_message', $data);
        }
    }
    
    public function fenghao_player() {
        $data = array('players' => array());
        $where = '';
        $uid = 0;
        if (isset($_GET['uid']) && $_GET['uid'] > 0 ) {
            $uid = $_GET['uid'];
        } else{
             $uid = $this->uri->segment(3, 0); // 页数
             $_GET['uid'] = $uid;
        }
        if($uid > 0 ) {
            $where .= " and uid = $uid";
        }
        
        $current = $this->uri->segment(4, 1); // 页数
        $limit = 15;//$this->session->userdata('limit');
        $start = ($current - 1) * $limit;
        
        $sql2 = "select * from user where status = 1 $where limit $start,$limit";
        $list = $this->game_db->query($sql2)->result_array();
        
        $sql = "select count(uid) as count from user where status = 1 $where";
        $info = $this->game_db->query($sql)->row_array();
        $count = $info['count'];
        $this->load->library('pagination');        
        $config['base_url'] = site_url('User/fenghao_player/' . $uid);
        $config['total_rows'] = $count;
        $config['per_page'] = $limit;
        $config['uri_segment'] = 4;
        
        $this->pagination->initialize($config);
        $page_link = $this->pagination->create_links();
//        echo $count;exit;
        $login_uid = $this->__user_info['mg_user_id'];
        $data = array(
            'players' => $list,
            'page_link' => $page_link,
            'show_condition' => $_GET,
            'cur_login_id'  => $login_uid,
        );
        
        $this->load->view('mon_fenghao_player.php', $data);
    }
    
    public function player() {
        $data = array('players' => array());
        $where = '';
        $uid = 0;
        if (isset($_GET['uid']) && $_GET['uid'] > 0 ) {
            $uid = $_GET['uid'];
        } else{
             $uid = $this->uri->segment(3, 0); // 页数
             $_GET['uid'] = $uid;
        }
        if($uid > 0 ) {
            $where .= " and uid = $uid";
        }
        $qunzhu = -1;
        if (isset($_GET['qunzhu']) && $_GET['qunzhu'] >= 0 ) {
            $qunzhu = $_GET['qunzhu'];
        } else{
            $qunzhu = $this->uri->segment(4, -1); // 页数
             $_GET['qunzhu'] = $qunzhu;
        }
        if($qunzhu >= 0 ) {
            $where .= " and qunzhu = $qunzhu";
        }
        
        $current = $this->uri->segment(5, 1); // 页数
        $limit = $this->session->userdata('limit');
        $start = ($current - 1) * $limit;
        
        $sql2 = "select * from user where 1=1 $where limit $start,$limit";
        $list = $this->game_db->query($sql2)->result_array();
        foreach($list as $k=>$v){
            $mg_user_id = $v['uid'];
            $sql = "select p_mg_user_id from mg_user where mg_user_id = {$mg_user_id}";
            if($result = $this->db->query($sql)->row_array()){
                $list[$k]['p_user_id'] = $result['p_mg_user_id'];
            }else{
                $list[$k]['p_user_id'] = $v['invite_id'];
            }
        }
        $sql = "select count(uid) as count from user where 1=1 $where";
        $info = $this->game_db->query($sql)->row_array();
        $count = $info['count'];
        $this->load->library('pagination');        
        $config['base_url'] = site_url('User/player/' . $uid .'/'.$qunzhu);
        $config['total_rows'] = $count;
        $config['uri_segment'] = 5;
        
        $this->pagination->initialize($config);
        $page_link = $this->pagination->create_links();
        $data = array(
            'players' => $list,
            'page_link' => $page_link,
            'show_condition' => $_GET,
        );
        
        $this->load->view('mon_player.php', $data);
    }

    public function exchange() {
        $data = array('players' => array());
        $where = ' and uid = -1';
        $uid1 = $uid2 = 0;
        $state = 0;
        if (isset($_GET['uid1']) && $_GET['uid1'] > 0 && isset($_GET['uid2']) && $_GET['uid2'] > 0) {
            $uid1 = $_GET['uid1'];
            $uid2 = $_GET['uid2'];
        } else{
            $uid1 = $uid2 = $this->uri->segment(3, 0); // 页数
            $_GET['uid1'] = $uid1;
            $_GET['uid2'] = $uid2;
        }
        if($uid1 > 0 && $uid2 > 0) {
            $where = " and uid in($uid1,$uid2)";
        }
        if(isset($_GET['state']) && $_GET['state'] > 0){
            $state = $_GET['state'];
        }
        $sql2 = "select uid,account,name,sex,status from user where 1=1 $where";
        $list = $this->game_db->query($sql2)->result_array();
        $_list = $list;
        if($state == 1){
            $userdata1 = array_splice($_list[1],1);
            $userdata2 = array_splice($_list[0],1);

            $this->user_m->game_mysql_model->trans_begin();
            //account是唯一的键，交换两个用户数据的时候，先把account替换成uid。
            $this->user_m->game_mysql_model->update('user', array(
                'account'=>$uid1
            ), array(
                'uid' => $uid1
            ));
            $this->user_m->game_mysql_model->update('user', array(
                'account'=>$uid2
            ), array(
                'uid' => $uid2
            ));
            $this->user_m->game_mysql_model->update('user', $userdata1, array(
                'uid' => $uid1
            ));
            $this->user_m->game_mysql_model->update('user', $userdata2, array(
                'uid' => $uid2
            ));
            $msg = "exchange wechat_account ".$list[0][uid]."--".$list[0][account]." and ".$list[1][uid]."--".$list[1][account];
            if ($this->user_m->game_mysql_model->trans_status()) {
                $return = array(
                    'uid'=>$_SESSION['user_info']['mg_user_id'],
                    'type'=>2,
                    'operation'=>$msg,
                    'time'=>time(),
                );

//                write_log_file('exchange_wxchat',$return);
                $this->game_db->insert('account_log',$return);
                $this->user_m->game_mysql_model->trans_commit(); // 提交事务
                alert('交换成功');
            } else {
                $this->user_m->game_mysql_model->trans_rollback(); // 回滚事务
                alert('交换失败');
            }
        }

        $data = array(
            'players' => $list,
        );
        $this->load->view('mon_exchange.php', $data);
    }

    public function cancel_qunzhu() {
        if ($_POST) {
            $uid = trim($_POST['uid']);
            if ($this->game_db->where('uid', $uid)->update('user', array('qunzhu' => 0)) == true) {
                $re = array('status' => 0);
            } else {
                $re = array('status' => 1);
            }
            echo json_encode($re);
        }
    }
    
    public function ajax_change_invite() {
        if ($_POST) {
            $uid = trim($_POST['uid']);
            $invite_id = trim($_POST['invite_id']);
//            if (!empty($invite_id)) {
                if ($this->game_db->where('uid', $uid)->update('user', array('invite_id' => $invite_id)) == true) {
                    $re = array('status' => 0);
                } else {
                    $re = array('status' => 1);
                }
//            } else {
//                $re = array('status' => 1);
//            }
            echo json_encode($re);
        }
    }

    public function ajax_change_recommend() {
        if ($_POST) {
            $uid = trim($_POST['uid']);
            $recommend_id = trim($_POST['recommend_id']);
            $sql = "select * from mg_user where mg_user_id = ". $recommend_id;

            if($result = $this->db->query($sql)->row_array()){
                $level = $result['level']+1;
                $flag1 = $this->db->where('mg_user_id', $uid)->update('mg_user', array('p_mg_user_id' => $recommend_id,'level'=> $level));
                $flag2 = $this->game_db->where('uid',$uid)->update('user',array('invite_id'=>$recommend_id));
                if ($flag1 == true && $flag2 == true) {
                    $re = array('status' => 0);
                    $return = array($uid,$recommend_id);
                    write_log_file('change_recommend',$return);
                } else {
                    $re = array('status' => 1);
                }
            }else{
                $re = array('status' => 2);
            }
            echo json_encode($re);
        }
    }

    public function ajax_change_status() {
        if ($_POST) {
            $uid = trim($_POST['uid']);

            $sql = "select * from user where uid = " . $uid;
            $user = $this->game_db->query($sql)->row_array();

            $status = $user['status'] == 0 ? 1 : 0;

            if ($this->game_db->where('uid', $uid)->update('user', array('status' => $status)) == true) {
                $re = array('status' => $status);
            } else {
                $re = array('status' => $status);
            }
            echo json_encode($re);
        }
    }

    public function ajax_edit_phone() {
        $mg_user_id = $_SESSION['user_info']['mg_user_id'];
        $this->user_m->updateUser($_POST, $mg_user_id);
        $re = array('status' => 0);
        echo json_encode($re);
    }

    /**
     * 编辑用户
     * @param int $mg_user_id 如果为0是添加用户，否则编辑用户
     * @return bool
     */
    public function agent_add() {
        $data['token'] = random_string('md5');
        $_SESSION['token'] = $data['token'];
        $data['add_status'] = 1;
        $this->load->view('mon_agent_add', $data);
    }

    /**
     * 处理编辑用户
     */
    public function doAdd() {
        $this->load->database();
        // post数据接收
        $token = $this->input->post('token', true);
        $mg_user_id = intval($this->input->post('mg_user_id', true));
        $mg_name = $this->input->post('mg_name', true);
        //1、验证TOKEN
        if ($token != $_SESSION['token']) {
            return false;
        }

        //----------数据验证------------- 
        // 2、通用验证唯一性
        $data['message'] = '';
        if ($mg_user_id == 0) {
            $this->Json(TRUE, '请输入正确的游戏ID');
        }

        //查找此代码号
        $sql = "select * from user where uid = " . $mg_user_id;
        $user = $this->game_db->query($sql)->row_array();
        if (empty($user)) {
            $this->Json(TRUE, '输入的玩家id不存在');
        }

        // 级别处理
        $level = $_SESSION['user_info']['level'];
        $level_in = $level + 1;

        if ($level >= 1 && $user['invite_id'] != $this->__user_info['mg_user_id'] ) {
            $this->Json(TRUE, '该玩家没有绑定您的邀请码');
        }

        // 验证跳转
        if ($data['message']) {
            $data['url'] = site_url('user/add/');
            return false;
        }
        // ---------------数据处理--------------
        //接收数据
        $post = array(
            'mg_user_id' => $mg_user_id,
            'mg_user_name' => $mg_user_id,
            'mg_name' => $mg_name,
            'mg_user_pwd' => md5('888888'),
            'phone' => '0',
            'invotecode' => $mg_user_id,
            'level' => $level_in,
            'p_mg_user_id' => $_SESSION['user_info']['mg_user_id'],
        );
        //写入数据库
        $msg = '';
        $tag = $this->{$this->model}->insertUser($post);
        if ($tag == 0) {
            $sql = "update user set invite_id = ".$mg_user_id.",qunzhu = 1 where uid = ".$mg_user_id;
            $this->game_db->query($sql);
            $msg = '激活代理成功';
//            $return = array('uid'=>$_SESSION['user_info']['mg_user_id'],'type'=>1,'operation'=>'add agent '. $mg_user_id,'time'=>time());
//            $this->game_db->insert('account_log',$return);
//            write_log_file('agent_add',$return);
        } elseif ($tag == 1) {
            $msg = $mg_user_id . '已经存在';
        } else {
            $msg = '激活代理失败';
        }
        $this->Json(TRUE, $msg);
    }
    
    /* Json 相关 */

    private function Json($v_status, $v_msg, $v_array = array()) {
        $out_array = array(
            'status' => $v_status,
            'msg' => $v_msg
        );
        die(json_encode(array_merge($out_array, $v_array)));
    }
    
    /**
     * 删除用户
     * @param $mg_user_id
     * @return bool
     */
    public function delete($mg_user_id) {
        $uid = intval($mg_user_id);
        // 不能是有下级的
        $uids = $this->{$this->model}->getInvoteStr($uid, 'array');
        // dump(count($uids));
        if (count($uids) > 1) {
            $data['message'] = '已经有下级，不能删除';
        } else if (intval($_SESSION['user_info']['mg_user_id']) == $uid) {
            $data['message'] = '不能删除自己';
        } else { // 可以删除
            if ($this->{$this->model}->deleteUser($mg_user_id)) {
                $data['message'] = '删除会员成功';
            } else {
                $data['message'] = '删除会员失败';
            }
        }
        $data['url'] = site_url('user/table');
        //返回消息
        $this->load->view('message.html', $data);
    }
    
      // 代理管理
    public function agent_boka() {
        $mgUserId = intval($this->__user_info['mg_user_id']);
        $sql = "select `card` from mg_user where mg_user_id={$mgUserId}";
        $row = $this->mysql_model->query($sql, 1);
        // 我的房卡
        $fangka = intval($row['card']);

        $dayStart = date('Y-m-d') . ' 00:00:00';
        $dayEnd = date('Y-m-d') . ' 23:59:59';
        $sql2 = "select SUM(`count`) as count from user_props_consume_history where user_id={$mgUserId} AND flag=2 AND create_time > '{$dayStart}' AND create_time < '{$dayEnd}'";
        $row2 = $this->mysql_model->query($sql2, 1);
        // 今日拔卡
        $baka = intval($row2['count']);
        $view_var['fangka'] = $fangka;
        $view_var['baka'] = $baka;
        $view_var['user_name'] = $this->__user_info['mg_user_name'];
        $this->load->view('mon_agent_boka', $view_var);
    }
    
    
    /* 玩家管理相关 */
    public function player_boka() {
        $mgUserId = intval($this->__user_info['mg_user_id']);

        $sql = "select `card` from mg_user where mg_user_id={$mgUserId}";
        $row = $this->mysql_model->query($sql, 1);
        // 我的房卡
        $fangka = intval($row['card']);

        $dayStart = date('Y-m-d') . ' 00:00:00';
        $dayEnd = date('Y-m-d') . ' 23:59:59';
        $sql2 = "select SUM(`count`) as count from user_props_consume_history where user_id={$mgUserId} AND flag=1 AND create_time > '{$dayStart}' AND create_time < '{$dayEnd}'";
        $row2 = $this->mysql_model->query($sql2, 1);
        // 今日售卡
        $shouka = intval($row2['count']);

        $view_var['fangka'] = $fangka;
        $view_var['shouka'] = $shouka;
        $view_var['user_name'] = $this->__user_info['mg_user_name'];

        // 统计
        // 今日售卡
        $sql = "SELECT ifnull(SUM(count),0) as count FROM user_props_consume_history 
			WHERE TO_DAYS(create_time) = TO_DAYS(NOW()) AND flag=1 AND user_id={$mgUserId} AND props_type_id=36";
        $row = $this->mysql_model->query($sql, 1);
        $view_var['user_room_card_today'] = empty($row['count']) ? 0 : $row['count'];
        // 昨日售卡
        $sql = "SELECT ifnull(SUM(count),0) as count FROM user_props_consume_history 
			WHERE TO_DAYS( NOW( ) ) - TO_DAYS( create_time) = 1 AND flag=1 AND user_id={$mgUserId} AND props_type_id=36";
        $row = $this->mysql_model->query($sql, 1);
        $view_var['user_room_card_yesterday'] = empty($row['count']) ? 0 : $row['count'];
        // 上周售卡
        $sql = "SELECT ifnull(SUM(count),0) as count FROM user_props_consume_history 
			WHERE YEARWEEK(date_format(create_time,'%Y-%m-%d')) = YEARWEEK(now())-1 AND flag=1 AND user_id={$mgUserId} 
			AND props_type_id=36";
        $row = $this->mysql_model->query($sql, 1);
        $view_var['user_room_card_last_week'] = empty($row['count']) ? 0 : $row['count'];
        $this->load->view('mon_player_boka', $view_var);
    }

    public function player_gold() {
        $mgUserId = intval($this->__user_info['mg_user_id']);

//        $sql = "select `card` from mg_user where mg_user_id={$mgUserId}";
//        $row = $this->mysql_model->query($sql, 1);
//        // 我的房卡
//        $fangka = intval($row['card']);
//
//        $dayStart = date('Y-m-d') . ' 00:00:00';
//        $dayEnd = date('Y-m-d') . ' 23:59:59';
//        $sql2 = "select SUM(`count`) as count from user_props_consume_history where user_id={$mgUserId} AND flag=1 AND create_time > '{$dayStart}' AND create_time < '{$dayEnd}'";
//        $row2 = $this->mysql_model->query($sql2, 1);
//        // 今日售卡
//        $shouka = intval($row2['count']);
//
//        $view_var['fangka'] = $fangka;
//        $view_var['shouka'] = $shouka;
//        $view_var['user_name'] = $this->__user_info['mg_user_name'];

        // 统计
        // 今日售卡
        $sql = "SELECT ifnull(SUM(count),0) as count FROM user_props_consume_history 
			WHERE TO_DAYS(create_time) = TO_DAYS(NOW()) AND flag=1 AND user_id={$mgUserId} AND props_type_id=36";
        $row = $this->mysql_model->query($sql, 1);
        $view_var['user_room_card_today'] = empty($row['count']) ? 0 : $row['count'];
        // 昨日售卡
        $sql = "SELECT ifnull(SUM(count),0) as count FROM user_props_consume_history 
			WHERE TO_DAYS( NOW( ) ) - TO_DAYS( create_time) = 1 AND flag=1 AND user_id={$mgUserId} AND props_type_id=36";
        $row = $this->mysql_model->query($sql, 1);
        $view_var['user_room_card_yesterday'] = empty($row['count']) ? 0 : $row['count'];
        // 上周售卡
        $sql = "SELECT ifnull(SUM(count),0) as count FROM user_props_consume_history 
			WHERE YEARWEEK(date_format(create_time,'%Y-%m-%d')) = YEARWEEK(now())-1 AND flag=1 AND user_id={$mgUserId} 
			AND props_type_id=36";
        $row = $this->mysql_model->query($sql, 1);
        $view_var['user_room_card_last_week'] = empty($row['count']) ? 0 : $row['count'];
        $this->load->view('mon_player_gold', $view_var);
    }

    public function ajax_get_user() {
        $v_query_user_id = $this->input->post('query_user_id', TRUE) + 0;
        $sql = "select * from user where uid = {$v_query_user_id}";
        $user = $this->game_db->query($sql)->row_array();
        if (empty($user)) {
            $this->Json(FALSE, '用户ID不存在，请重新输入！');
        }
        $user['qunzhu'] = $user['qunzhu'] == 0 ? '否' : '是';
        $user['status'] = $user['status'] == 0 ? '正常' : '封号';
        $this->Json(TRUE, '用户查询成功！', $user);
    }

    public function ajax_setQunzhu() {
//        $this->ajax_is_login();
        $v_query_user_id = $this->input->post('user_id', TRUE) + 0;
        $is_qunzhu = 1;//$this->input->post('is_qunzhu');
        $ownUserId = intval($this->__user_info['mg_user_id']);
        
       
        
         // 发卡人的房卡数量
        $sql = "SELECT * FROM mg_user WHERE mg_user_id={$ownUserId} ";
        $row = $this->mysql_model->query($sql, 1);
        
        if($row['can_qunzhu'] - $row['used_qunzhu'] <= 0) {
            $this->Json(TRUE, '次数据限制！');
        }
        
            
        $sql = "update user set qunzhu = {$is_qunzhu} where uid = {$v_query_user_id}";
        $this->game_db->query($sql);
        
        
        
        $this->mysql_model->update('mg_user', array(
            'used_qunzhu' => intval($row['used_qunzhu']) + 1
                ), array(
            'mg_user_id' => $ownUserId
        ));
        
        $this->Json(TRUE, '设置成功！');
    }
    
    
    public function ajax_setFenghao() {
//        $this->ajax_is_login();
        $v_query_user_id = $this->input->post('user_id', TRUE) + 0;
        $ownUserId = intval($this->__user_info['mg_user_id']);
        
        $sql = "select * from user where uid = {$v_query_user_id}";
        $info = $this->game_db->query($sql)->row_array();
        if($info['status'] == 1) {
            die(json_encode(array('status'=>1)));
        }
        
        $sql2 = "select * from user where uid = {$v_query_user_id}";
        $info2 = $this->game_db->query($sql2)->row_array();
        $locker_name = $info2['name'];
        
        $sql = "update user set status = 1,locker_id = {$ownUserId},locker_name = '{$locker_name}' where uid = {$v_query_user_id}";
        $this->game_db->query($sql);
         $re = array('status'=>0);
        die(json_encode($re));
    }
    

    public function ajax_player_boka() {
        $v_user_id = intval($this->input->post('user_id'));
        $v_room_card_number = intval($this->input->post('room_card_number'));

        $ownUserId = intval($this->__user_info['mg_user_id']);
        $level = intval($this->__user_info['level']);
        // 判断房卡数量是否正确
        if ($v_room_card_number <= 0 && $level > 0) {
            $this->Json(TRUE, '数量不正确');
        }

        // 发卡人的房卡数量
        $sql = "SELECT card FROM mg_user WHERE mg_user_id={$ownUserId} ";
        $row = $this->mysql_model->query($sql, 1);
        //change_by_lk_161026
        if (!$row && $level > 1 ) {
            $this->Json(TRUE, '发卡人没有卡');
        }
        
        if($v_room_card_number < 0 && $ownUserId != 1 ){
             $this->Json(FALSE, '拨卡数量不能为负数！');
        }
        
        //change_by_lk_161026
        if (( intval($row['card']) < $v_room_card_number) && $level > 0  ) {
            $this->Json(FALSE, '房卡数量不足！');
        }

        // 判断用户ID是否存在
        $sql2 = "select * from user where uid = {$v_user_id}";
        $v_second_party = $this->game_db->query($sql2)->row_array();
        if (!$v_second_party) {
            $this->Json(FALSE, '用户ID不合法！');
        }
        
        //自己下线代理
        //$login_uid = $this->__user_info['mg_user_id'];
        //$filter_str = $this->user_m->getInvoteStr($login_uid);
        //$filter_arr = explode(',', $filter_str);
        //$filter_arr[] = $login_uid;
        //if($login_uid != 1 && $v_user_id != $login_uid && !in_array($v_second_party['invite_id'],$filter_arr)){
          //  $this->Json(FALSE, '不是您体系内玩家！');
        //}
        

        $add_number = intval($v_room_card_number / $this->config->item('AddExNum'));
        // 开始交易
        $this->mysql_model->trans_begin();

        $this->mysql_model->update('mg_user', array(
            'card' => intval($row['card']) - $v_room_card_number
                ), array(
            'mg_user_id' => $ownUserId
        ));

        $order_id = $this->mysql_model->insert('user_props_consume_history', array(
            'props_type_id' => 36,
            'user_id' => $ownUserId,
            'accept_user_id' => $v_user_id,
            'count' => $v_room_card_number,
            'flag' => 1,
            'create_time' => date('Y-m-d H:i:s')
        ));

        if ($add_number > 0) {
            $order_id = $this->mysql_model->insert('user_props_consume_history', array(
                'props_type_id' => 36,
                'user_id' => $ownUserId,
                'accept_user_id' => $v_user_id,
                'count' => $add_number,
                'flag' => 3,
                'create_time' => date('Y-m-d H:i:s')
            ));
        }

        if ($this->mysql_model->trans_status()) {
            $this->mysql_model->trans_commit(); // 提交事务
            //加房卡
            $key = 'f42afdb92e2e66c24dfb9';
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            socket_connect($socket, $this->config->item('GMIP'), $this->config->item('GMPORT'));
            $arr = array(
                'cmd' => 23,
                'uid' => $v_user_id,
                'account' => $v_second_party['account'],
                'card' => $v_room_card_number,
                'card_ex' => $add_number,
                'card_add' => 0,
                'gm_order_id' => $order_id,
            );
            ksort($arr);
            $sign_str = '';
            foreach ($arr as $k => $v) {
                $sign_str .= $v;
            }
            $sign_str .= $key;
            $arr['sign'] = md5($sign_str);
            $str = 'PHP3'.json_encode($arr);
            $size = strlen($str);
            $binary_str = pack("na" . $size, $size, $str);
            socket_write($socket, $binary_str, strlen($binary_str));
            socket_close($socket);

            $this->Json(TRUE, '发放成功！');
        } else {
            $this->mysql_model->trans_rollback(); // 回滚事务
            $this->Json(FALSE, '发放失败！');
        }
    }
    public function ajax_player_gold() {
        $v_user_id = intval($this->input->post('user_id'));
        $v_room_gold_number = intval($this->input->post('room_gold_number'));

        $ownUserId = intval($this->__user_info['mg_user_id']);
        $level = intval($this->__user_info['level']);
        // 判断房卡数量是否正确
        if ($v_room_gold_number <= 0 && $level > 0) {
            $this->Json(TRUE, '数量不正确');
        }
        if($v_room_gold_number < 0 && $ownUserId != 1 ){
            $this->Json(FALSE, '拨金币数量不能为负数！');
        }

        // 判断用户ID是否存在
        $sql2 = "select * from user where uid = {$v_user_id}";
        $v_second_party = $this->game_db->query($sql2)->row_array();
        if (!$v_second_party) {
            $this->Json(FALSE, '用户ID不合法！');
        }
        $add_number = intval($v_room_gold_number / $this->config->item('AddExNum'));
        // 开始交易
        $this->mysql_model->trans_begin();
        $order_id = $this->mysql_model->insert('user_props_consume_history', array(
            'props_type_id' => 56,  //36房卡，56金币
            'user_id' => $ownUserId,
            'accept_user_id' => $v_user_id,
            'count' => $v_room_gold_number,
            'flag' => 1,
            'create_time' => date('Y-m-d H:i:s')
        ));

        if ($add_number > 0) {
            $order_id = $this->mysql_model->insert('user_props_consume_history', array(
                'props_type_id' => 36,
                'user_id' => $ownUserId,
                'accept_user_id' => $v_user_id,
                'count' => $add_number,
                'flag' => 3,
                'create_time' => date('Y-m-d H:i:s')
            ));
        }
        if ($this->mysql_model->trans_status()) {
            $this->mysql_model->trans_commit(); // 提交事务
            //加金币
            $key = 'f42afdb92e2e66c24dfb9';
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            socket_connect($socket, $this->config->item('GMIP'), $this->config->item('GMPORT'));
            $arr = array(
                'cmd' => 23,
                'uid' => $v_user_id,
                'account' => $v_second_party['account'],
                'sign'=>'gold',
                'gold' => $v_room_gold_number,
                'gold_add' => 0,
                'gm_order_id' => $order_id,
            );
            ksort($arr);
            $sign_str = '';
            foreach ($arr as $k => $v) {
                $sign_str .= $v;
            }
            $sign_str .= $key;
            $arr['sign'] = md5($sign_str);
            $str = 'PHP6'.json_encode($arr);
            $size = strlen($str);
            $binary_str = pack("na" . $size, $size, $str);
            socket_write($socket, $binary_str, strlen($binary_str));
            socket_close($socket);

            $this->Json(TRUE, '发放成功！');
        } else {
            $this->mysql_model->trans_rollback(); // 回滚事务
            $this->Json(FALSE, '发放失败！');
        }
    }

    // --------------------------------------------------
    //查询卡的数量
    public function ajax_get_agent() {
        $ownUserId = intval($this->__user_info['mg_user_id']);
        $level = intval($this->__user_info['level']);

        $v_query_user_id = intval($this->input->post('query_user_id', TRUE));
        $sql = "SELECT *  FROM mg_user t1  WHERE mg_user_id={$v_query_user_id}";
        $row = $this->mysql_model->query($sql, 1);

        if (!$row) {
            $this->Json(false, '用户不存在！');
        }
        
        // 判断用户ID是否存在
        $sql2 = "select * from user where uid = {$v_query_user_id}";
        $v_second_party = $this->game_db->query($sql2)->row_array();
        if (!$v_second_party) {
            $this->Json(FALSE, '用户ID不合法！');
        }
        $out_user['head'] = $v_second_party['head'];
        $out_user['count'] = intval($row['card']);
        $out_user['user_id'] = $v_query_user_id;
        $out_user['user_name'] = $row['mg_user_name'];
        $this->Json(TRUE, '用户查询成功！', $out_user);
    }

    public function ajax_boka_agent() {
        $v_user_id = intval($this->input->post('user_id'));
        $v_room_card_number = intval($this->input->post('room_card_number'));

        $ownUserId = intval($this->__user_info['mg_user_id']);
        $level = intval($this->__user_info['level']);


		if($v_room_card_number < 0 && $ownUserId != 1 ){
             $this->Json(FALSE, '拨卡数量不能为负数！');
        }

        // 获取房卡数量
        $sql = "SELECT `card` FROM mg_user WHERE mg_user_id={$ownUserId}";
        $row = $this->mysql_model->query($sql, 1);

        //change_by_lk_161026
        if ((intval($row['card']) < $v_room_card_number ) && $level > 0 )  {
            $this->Json(FALSE, '房卡数量不足！');
        }

        $sql2 = "SELECT * FROM mg_user WHERE mg_user_id=$v_user_id";
        $v_second_party = $this->mysql_model->query($sql2, 1);
        if (empty($v_second_party)) {
            $this->Json(FALSE, '收卡用户不存在！');
        }

        // 判断用户ID合法性
        if ($v_user_id == $ownUserId) {
            $this->Json(FALSE, '不能和自己交易！');
        }
        
//        //自己下线代理
//        $login_uid = $this->__user_info['mg_user_id'];
//        $filter_str = $this->user_m->getInvoteStr($login_uid);
//        $filter_arr = explode(',', $filter_str);
//        $filter_arr[] = $login_uid;
//        if($login_uid !=2 && !in_array($v_second_party['p_mg_user_id'],$filter_arr)){
//            $this->Json(FALSE, '不是您体系内的代理！');
//        }
        

        // 如果对方用户不存在 user_account_props 记录 新增一条
        $sql5 = "SELECT `card` FROM mg_user WHERE mg_user_id={$v_user_id}";
        $row5 = $this->mysql_model->query($sql5, 1);

        $originCount = intval($row5['card']);

        // 开始交易
        $this->mysql_model->trans_begin();

        // 发卡用户减卡
        $this->mysql_model->update('mg_user', array(
            'card' => intval($row['card']) - $v_room_card_number
                ), array(
            'mg_user_id' => $ownUserId
        ));

        // 收卡用户加卡
        $this->mysql_model->update('mg_user', array(
            'card' => $originCount + $v_room_card_number
                ), array(
            'mg_user_id' => $v_user_id
        ));

        // 添加流水
        $this->mysql_model->insert('user_props_consume_history', array(
            'props_type_id' => 36,
            'user_id' => $ownUserId,
            'accept_user_id' => $v_user_id,
            'count' => $v_room_card_number,
            'flag' => 2,
            'create_time' => date('Y-m-d H:i:s')
        ));
        // 添加流水
        $this->mysql_model->insert('user_props_purchase_history', array(
            'props_type_id' => 36,
            'user_id' => $v_user_id,
            'send_user_id' => $ownUserId,
            'count' => $v_room_card_number,
            'flag' => 2,
            'create_time' => date('Y-m-d H:i:s')
        ));

        if ($this->mysql_model->trans_status()) {
            $this->mysql_model->trans_commit(); // 提交事务
            $this->Json(TRUE, '发放成功！');
        } else {
            $this->mysql_model->trans_rollback(); // 回滚事务
            $this->Json(FALSE, '发放失败！');
        }
    }
    
     public function agent_list() {
        $uid = isset($_GET['uid']) ? trim($_GET['uid']) : 0;
        if ($uid) {
            $uids = $this->user_m->getInvoteStr($this->__user_info['mg_user_id']);
            $uids_list = explode(',', $uids);
            if (!in_array($uid, $uids_list)) {
                $uid = $this->__user_info['mg_user_id'];
            }
        } else {
            $uid = intval($this->__user_info['mg_user_id']);
        }

        $sql = "select mg_name,mg_user_id,invotecode from `mg_user` where p_mg_user_id={$uid}";
        $list = $this->mysql_model->query($sql, 2);
        foreach ($list as $k => $v) {
            $sql = "select `head` from `user` where uid ={$v['mg_user_id']}";
            $row = $this->game_db->query($sql, 1)->row_array();
            $list[$k]['head'] = isset($row['head']) ? $row['head'] : '';
        }
        $temp['list'] = $list;
        $this->load->view('mon_user', $temp);
    }
    
     public function ajax_update_agent() {
        $mgUserId = isset($_POST['uid']) ? $_POST['uid'] : 0;
        $mg_name = isset($_POST['mg_name']) ? $_POST['mg_name'] : '';
        if(empty($mg_name)) {
            die($re = array('status'=>1));
        }
        $sql = "update mg_user set mg_name = '{$mg_name}' where mg_user_id = {$mgUserId}";
        $this->db->query($sql);
        $re = array('status'=>1);
        die(json_encode($re));
    }
	
	//发放奖励（拨卡）
    public function ajax_award_boka() {
        $v_user_id = intval($this->input->post('uid'));//中奖玩家id
        $v_award_id = intval($this->input->post('award_id'));
        $award = $this->config->item('da_zhuan_pan');
        $v_room_card_number = intval($award[$v_award_id]['txt']);//中奖发卡数量
        $v_award_key = intval($this->input->post('award_key'));//中奖号码
        $v_award_status = intval($this->input->post('award_status'));//是否领奖
//        $ownUserId = intval($this->__user_info['mg_user_id']);//后台登录id
        $time = time();
        $re =array();
        if($v_award_status ==0){//未颁奖
            $sql = "select card from user where uid = {$v_user_id}";
            $row = $this->game_db->query($sql)->row_array();
            $current_number = intval($row['card']);
            $number = $current_number+$v_room_card_number;
            $sql2 = "update user set card ={$number} where uid={$v_user_id}";
            $this->game_db->query($sql2);
            $sql3 = "update da_zhuan_pan_log set status = 1,get_time ={$time} where uid ={$v_user_id} and `key` ={$v_award_key}";
            $this->game_db->query($sql3);
            $re = array('status'=>0);
        }else{//已颁奖
//            $this->Json(FALSE, '已领奖，发放失败！');
            $re = array('status'=>1);
        }
        echo json_encode($re);
    }

    public function player_list() {
        $temp['mgid'] = $mgUserId = intval($this->__user_info['mg_user_id']);
        $sql = "select `uid`,`name`,`head` from `user` where invite_id ={$mgUserId}";
        $temp['list'] = $this->game_db->query($sql, 2)->result_array();
        $this->load->view('mon_gamer', $temp);
    }
	
	public function club_pic() {
        $club_id =  isset($_REQUEST['club_id']) ? $_REQUEST['club_id'] : 0; 
        $ownUserId = intval($this->__user_info['mg_user_id']);
        $msg = '';
        if( $club_id > 0){  
            $sql = "SELECT * FROM clubs_members	 WHERE club_id={$club_id} and uid={$ownUserId} and privilage = 100";
            $row = $this->game_db->query($sql, 1)->row_array();
            if($row['club_id'] > 0){
				$file = $_FILES['file'];//得到传输的数据 
				$name = $file['name'];//得到文件名称  
				$type = strtolower(substr($name,strrpos($name,'.')+1)); //得到文件类型，并且都转化成小写
				$allow_type = array('png'); //定义允许上传的类型 
				if(!in_array($type, $allow_type)){//判断文件类型是否被允许上传
					$msg = '图片必须是png 格式!';
				}else{ 
					$file_new_name = "/home/wwwroot/gm/pic/" . $club_id . '.' . $type; 
					if(move_uploaded_file($file['tmp_name'],$file_new_name)){
						$msg = '上传成功!';
						//$url = $this->config->item('base_url').'pic/'.$club_id . '.' . $type;
						//$sql = "update club set club_head = '{$url}' where club_id={$club_id}";
						//$this->game_db->query($sql);
					}else{
						$msg = "上传失败!";
					}
				}

            }else{ 
                $msg = "上传失败!你不是该俱乐部创建者!";
            }
        }  

        $temp['list'] = $msg;
        $this->load->view('club/club_pic', $temp);
    }

    /*
     * 这里路径需要注意,在window和linux的时候有区别，window用反斜杠\，linux用斜杠/
     * 需要给二维码生成的目录修改权限。/home/wwwroot/gm/pic/
     * */
	public function qrcode()
    {
        $width = 150;
        $height = 25;
        $im = imagecreate($width,$height);
        $uid = '邀请码:'.$this->__user_info['mg_user_id'];
//        $uid = '邀请码:222222';
        $bgColor = imagecolorallocate($im, 242, 242, 242);
        imagefill($im, 0, 0, $bgColor);
        $fontfile = ROOTDIR."system/fonts/msyh.ttc";
        $color = imagecolorallocate($im, 0, 0, 0);
//        $str = iconv('UTF-8','GB2312',"看有一个月a"); /*将 gb2312 的字符集转换成 UTF-8 的字符,本身就是utf8，不需要转*/
        ImageTTFText($im,12, 0,30,12, $color,$fontfile,$uid);
        $this->load->library('qrcode');
        $userid = intval($this->__user_info['mg_user_id']);
        $domain = $this->config->item('codeUrl');
        $app = $this->config->item('codeApp');
        $url = $domain."/invite.php?uid=".$userid."&pack=".$app;
        $logo = ROOTDIR.'pic/head.png';
        $this->qrcode->png($url,ROOTDIR.'pic/qrcode.png',QR_ECLEVEL_L,5,5);
        $QR =ROOTDIR.'pic/qrcode.png';
        if ($logo !== FALSE) {
            $QR = imagecreatefromstring(file_get_contents($QR));
            $logo = imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR);//二维码图片宽度
            $QR_height = imagesy($QR);//二维码图片高度
            $logo_width = imagesx($logo);//logo图片宽度
            $logo_height = imagesy($logo);//logo图片高度
            $logo_qr_width = $QR_width / 6;
            $scale = $logo_width/$logo_qr_width;
            $logo_qr_height = $logo_height/$scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, -0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
            imagecopymerge($QR,$im,25,-3,0,-10,$width,$height,50);
        }
        imagepng($QR, ROOTDIR.'pic/helloweba.png');
        $data = array(
            'url'=>r_url_pic('helloweba.png'),
            'msg'=>'长按保存至相册',
        );
        $this->load->view('mon_qrcode',$data);

    }
	
	public function award_query()
    {   //通过表单提交过来的uid和award_id查表
        $where_str = "";
        if(isset($_POST['uid']) && isset($_POST['award_key'])){
            $uid = $this->input->post('uid');
            $key = $this->input->post('award_key');
            $where_str = " and uid =". $uid." and `key` ='". $key."'";
        }

        $current = $this->uri->segment(4, 1); // 页数
        $limit = $limit = $this->session->userdata('limit');
        $start = ($current - 1) * $limit;

        $sql = "select * from da_zhuan_pan_log where 1=1 ".$where_str." order by uid asc limit ".$start.", ".$limit;;
        $list = $this->game_db->query($sql)->result_array();

        $sql2 = "select count(*) as count from da_zhuan_pan_log where 1=1 ".$where_str;
        $tmp_count = $this->game_db->query($sql2)->row_array();
        $count = $tmp_count['count'];
        $this->load->library('pagination');
        $url = site_url('User/award_query/'.$uid);
        $config['base_url'] = $url;
        $config['total_rows'] = $count;
        $config['per_page'] = $limit;
        $config['uri_segment'] = 4;
        $config['use_page_numbers']=true;
        $this->pagination->initialize($config);
        $page_link = $this->pagination->create_links();

        $data = array(
            'list'              =>$list,
            'page_link'         =>$page_link,
            'demo'=>$where_str,
        );
        $this->load->view('mon_award_query',$data);
    }

}
