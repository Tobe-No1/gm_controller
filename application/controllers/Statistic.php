<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Statistic extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_m');
        if(!isset($_SESSION['user_info']) && empty($_SESSION['user_info'])){
           $url = get_url('/index.php/Login/menu');
           header("Location:$url");
           die();
        }
        $this->game_db = $this->load->database('game', TRUE);
        $this->db = $this->load->database('default', TRUE);
        $this->output->enable_profiler(false);
        $this->__user_info = $this->session->userdata('user_info');
    }
    
    /**
     * 拨卡统计
     */
    public function boka() {
        $this->load->database();
        $this->load->model('mysql_model');
        $this->load->model('user_m');
        $userInfo = $this->session->userdata('user_info');
        $current_user_id = $userInfo['mg_user_id'];

        // 所有当前用户的下级
        $user_list_show = $this->user_m->getUserList($current_user_id);
        // 获得卡类型
        $flag_params = $this->db->get_where('mg_config_param', array('type' => 1))->result_array();
        // 余卡查询
        $query3 = $this->db->get_where('mg_user', [
            'mg_user_id' => intval($userInfo['mg_user_id'])
        ]);
        $user = $query3->row_array();

        $where_str = '1 ';
        // 用户id
        //会员选项
        $user_id = $this->uri->segment(3, -2);
        if (isset($_POST['user_id']) && $_POST['user_id'] > 0) {
            $user_id = $this->input->post('user_id');
        }
        if ((int) $user_id > 0) {
            $where_str .= ' and ch.user_id in (' . $user_id . ')';
        } else if ((int) $user_id == -2) {
            $uids = $this->user_m->getInvoteStr($current_user_id);
            $where_str .= ' and ch.user_id in (' . $uids . ')';
        }
        // dump($where_str);
        // 类别
        $flag = $this->uri->segment(4, 0);
        if (isset($_POST['flag']) && $_POST['flag'] > 0) {
            $flag = $this->input->post('flag');
        }
        if ((int) $flag > 0) {
            $where_str .= ' and ch.flag = ' . $flag;
        }
        $stime_1 = $this->uri->segment(5, date("Y-m-d"));
        $etime_1 = $this->uri->segment(6, date("Y-m-d"));
        if (isset($_POST['stime1']) && $_POST['stime1'] && isset($_POST['etime1']) && $_POST['etime1']) {
            $stime = $this->input->post('stime1');
            $etime = $this->input->post('etime1');
            $stime_1 = date('Y-m-d', strtotime($stime));
            $stime_2 = date('H:i:s', strtotime($stime));
            $etime_1 = date('Y-m-d', strtotime($etime));
            $etime_2 = date('H:i:s', strtotime($etime_2));
        }
        $stime1 = strtotime($stime_1);
        $etime1 = strtotime($etime_1) + 86400;
        $start_time = $stime1;
        $end_time = $etime1;

        if ($etime1 > $stime1 && isset($_POST['stime1']) && $_POST['stime1'] && isset($_POST['etime1']) && $_POST['etime1']) {
            $start_time = $stime1;
            $end_time = $etime1;
        }
        $where_str .= ' and ch.create_time >= ' . "'" . date('Y-m-d H:i:s', $start_time) . "'";
        $where_str .= ' and ch.create_time < ' . "'" . date('Y-m-d H:i:s', $end_time) . "'";
        //获得列表

        $accept_user_id = $this->uri->segment(7, 0); // 页数
        if (isset($_POST['accept_user_id']) && $_POST['accept_user_id'] > 0) {
            $accept_user_id = $this->input->post('accept_user_id');
        }
        if ($accept_user_id > 0) {
            $where_str .= ' and ch.accept_user_id=' . $accept_user_id;
        }

        $current = $this->uri->segment(8, 1); // 页数
        $limit = $limit = $this->session->userdata('limit');
        $start = ($current - 1) * $limit;
        $sql = "select ch.user_id,ch.create_time, ch.accept_user_id, u.mg_name, ch.count,u.level 
            , p.remark as flag from user_props_consume_history ch 
            left join mg_user u on u.mg_user_id = ch.user_id 
            left join mg_config_param p on ch.flag = p.value and p.type = 1 
            where " . $where_str .
                " order by ch.create_time desc limit " . $start . ", " . $limit;
        $list = $this->mysql_model->query($sql, 2);
        $sql2 = "select count(*) as c,sum(count) as total from user_props_consume_history ch where " . $where_str;
        $tmp_count = $this->mysql_model->query($sql2);
        $count = $tmp_count['c'];
        $this->load->library('pagination');
        $url = site_url('Statistic/boka/' . $user_id . '/' . $flag . '/' . $stime_1 . '/' . $etime_1  . '/' . $accept_user_id);
        $config['base_url'] = $url;
        $config['total_rows'] = $count;
        $config['per_page'] = $limit;
        $config['uri_segment'] = 8;
        $config['use_page_numbers'] = true;

        $this->pagination->initialize($config);
        $page_link = $this->pagination->create_links();

        $data = array(
            'list' => $list,
            'start' => $start,
            'page_link' => $page_link,
            'props_count' => $user['card'],
            'user_list_show' => $user_list_show,
            'flag_params' => $flag_params,
            'role_names' => $this->config->item('role_names'),
            'count' => $count,
            'total' => $tmp_count['total'],
            'show_condition' => array(
                'user_id' => $user_id,
                'flag' => $flag,
                'stime' => $stime_1,
                'etime' => $etime_1,
                'accept_user_id' => $accept_user_id,
            )
        );
        $this->load->view('mon_stat_boka', $data);
    }
    
    
    public function room_log(){
        $data = array();
        if($_POST) {
            $data['room_id'] = $_POST['room_id'];
            $sql2 = "select * from room_log where room_id =  " . $data['room_id'];
            $tmp = $this->game_db->query($sql2)->row_array();
            if(($tmp)){
                $data['create_time'] = $tmp['create_time'];
                $info = json_decode($tmp['content'],true);
                $data['list'] = $info['players'];
            }
        }
        $this->load->view('mon_room_log.php',$data);
        
    }
    
    public function onLine(){

        $where = 'ifnull(room_id,0) > 0';

        $current = $this->uri->segment(3,1); // 页数
        $limit = $this->session->userdata('limit');
        $start = ($current - 1) * $limit;

        //获得用户列表
        $list = $this->user_m->getOnLineUser($where, $start, $limit);
        $count = $this->user_m->getOnLineUserCount($where);
        $this->load->library('pagination');
        $config['base_url'] = site_url('statistic/onLine');
        $config['total_rows'] = $count;
        $config['per_page'] = $limit;
        $config['uri_segment'] = 3;
        $config['use_page_numbers']=true;
        
        $this->pagination->initialize($config); 
        $page_link = $this->pagination->create_links();
       
        
        
        $data = array(
            'current_users_num'  => $current_users_num,
            'list'               => $list,
            'page_link'          => $page_link,
            );
        
        //调用视图
        $this->load->view('mon_online.php',$data);
    }

    // 基本统计与留存
    public function base(){

        $login_uid = $this->__user_info['mg_user_id'];
        if($login_uid != 1 && $login_uid !=2) {
            die("没有权限");
        }
        $where = array(1=>1);
        $current = $this->uri->segment(3,1); // 页数
        $limit = 15;
        $start = ($current - 1) * $limit;
        $where_str2 = '';
        if ($login_uid != 1) {
            $uids = $this->user_m->getInvoteStr2($login_uid);
            if (!empty($uids)) {
                $uids = $uids . ',' . $login_uid;
            } else {
                $uids = $login_uid;
            }
            $where_str2 = ' and b.invite_id in (' . $uids . ')';
        }
        // echo $limit;exit;
        //获得金币充值金额(mtype=2),玩家充值金额（mtype=1）
        $sql = "select id,mtype from products";
        $res = $this->game_db->query($sql)->result_array();
        $product_id = '0,';
        $product_id2 = '0,';
        foreach($res as $k=>$v) {
            if($v['mtype']==2){
                $product_id .= $v['id'].',';
            }
            if($v['mtype']==1){
                $product_id2 .= $v['id'].',';
            }
        }
        $product_id = substr($product_id,0,strlen($product_id)-1);
        $product_id2 = substr($product_id2,0,strlen($product_id2)-1);
        $sql1 = "select FROM_UNIXTIME(a.pay_time,'%Y-%m-%d') AS t,sum(a.amount) as total_money from charge as a left join user as b on a.uid = b.uid where a.status = 1 and product_id in (".$product_id.") " . $where_str2." group by t ";
        $sql2 = "select FROM_UNIXTIME(a.pay_time,'%Y-%m-%d') AS t,sum(a.amount) as player_total_money from charge as a left join user as b on a.uid = b.uid where a.status = 1 and product_id in (".$product_id2.") " . $where_str2." group by t ";
        $total_info_gold = $this->game_db->query($sql1)->result_array();
        $total_player_gold = $this->game_db->query($sql2)->result_array();
        $total_info = array();
        $total_player = array();
        $total_gold = array();
        foreach($total_info_gold as $k => $v){
            $total_info[$v['t']] = $v;
        }
        foreach($total_player_gold as $k => $v){
            $total_player[$v['t']] = $v;
        }
        if(count($total_info)>0){
            foreach ($total_info as $k => $v){
                foreach ($total_player as $s => $t){
                    if($k == $s){
                        $total_gold[$k]['total_money']=$v['total_money'];
                        $total_gold[$k]['player_total_money'] = $t['player_total_money'];
                    }else{
                        $total_gold[$s]['player_total_money'] = $t['player_total_money'];
                        $total_gold[$s]['total_money'] = 0;
                    }
                    if(!array_key_exists($k,$total_gold)){
                        $total_gold[$k]['total_money'] = $v;
                        $total_gold[$k]['player_total_money'] = 0;
                    }
                }
            }
        }else{
            $total_gold = $total_player;
            foreach($total_player as $k=>$k){
                $total_gold[$k]['total_money']=0;
            }
        }
        //获得记录
        $list = $this->mysql_model->get_results('mg_base_statistic', $where, 'create_time desc, id desc', $start, $limit);
        foreach($list as $k=>$v){
            $list[$k]['gold_count']=0;
            $list[$k]['player_gold']=0;
            foreach($total_gold as $s=>$t){
                if($s==$v['create_time']){
                    $list[$k]['gold_count']=intval($t['total_money']/100);
                    $list[$k]['player_gold']=intval($t['player_total_money']/100);
                }
            }
        }
        //获得总数
        $count = $this->mysql_model->get_count('mg_base_statistic', $where);

        $this->load->library('pagination');
        $config['base_url'] = site_url('statistic/base');
        $config['total_rows'] = $count;
        $config['uri_segment'] = 3;
        $config['per_page'] = $limit;
        $this->pagination->initialize($config);
        $page_link = $this->pagination->create_links();

         // 当前用户数量
        $arr = array(
                'cmd'       => 36,
                'account'   => 'admin',
        );
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_connect($socket, $this->config->item('GMIP'), $this->config->item('GMPORT'));
        $str = 'PHP4'.json_encode($arr);
        $size = strlen($str);
        $binary_str = pack("na".$size, $size, $str);
        socket_write($socket, $binary_str,  strlen($binary_str));
        $buf = socket_read($socket, 2048);
        $a = json_decode(substr($buf, 2),true);
        socket_close($socket);

        $current_users_num = $a['num'];


        $data = array(
            'current_users_num' => $current_users_num,
            'list'               => $list,
            'page_link'          => $page_link,
            );
        //调用视图
        $this->load->view('mon_statistic', $data);
    }
	
	 //统计消耗
    public function cost()
    {
        $where_time = "";
        if($_GET['time']==true){
            $stime = strtotime($this->input->get('time'));
            $etime = $stime+3600*24;
            $where_time = " and create_time >=".$stime." and create_time <".$etime;
        }

//        $list = array();
        $sql = "select sum(count) as '俱乐部消耗' from clubs_card_log where game_type!=0 and opt=2".$where_time;
        $club_cost = $this->game_db->query($sql)->row_array();

        $sql2 = "select sum(num) as '群主消耗' from card_log where change_type=0 and game_type!=0 and type=6".$where_time;
        $qunzhu_cost = $this->game_db->query($sql2)->row_array();
        $game_type = $this->config->item('game_type');
        $majiang_type = $this->config->item('majiang_type');
        $game_cost = array();
        $majiang_cost = array();
        $majiang_cost2 = array();
        $majiang_cost3 = array();
        foreach ($game_type as $k => $v){
            $sql = "select sum(num) as game_cost from card_log where change_type=0 and game_type=".$k.$where_time;//从card_log中获取记录
            $result = $this->game_db->query($sql)->row_array();
            $sql2 = "select sum(count) as game_cost from clubs_card_log where opt=2 and game_type=".$k.$where_time;//从clubs_card_log中，opt=2为消耗
            $result2 = $this->game_db->query($sql2)->row_array();
            if($result['game_cost']||$result2['game_cost']){
                $game_cost[$v]=$result['game_cost']+$result2['game_cost'];
                if($k==2){
                    //普通场麻将记录
                    $sql="select sub_type,sum(num) as majiang_cost from card_log where change_type=0 and game_type=2".$where_time." group by sub_type order by majiang_cost desc";
                    $majiang = $this->game_db->query($sql)->result_array();
                    foreach($majiang as $v){
                        $majiang_cost[$majiang_type[$v['sub_type']]]=$v['majiang_cost'];
                    }
                    //俱乐部麻将记录
                    $sql2="select sub_type,sum(count) as majiang_cost from clubs_card_log where game_type=2 and opt=2".$where_time." group by sub_type order by majiang_cost desc";
                    $majiang2 = $this->game_db->query($sql2)->result_array();
                    foreach($majiang2 as $v){
                        $majiang_cost2[$majiang_type[$v['sub_type']]]=$v['majiang_cost'];
                    }

                    if(count($majiang_cost)>0){
                        foreach($majiang_cost as $k=>$v){
                            foreach ($majiang_cost2 as $s=>$t){
                                if($k==$s){
                                    $majiang_cost3[$k]=$v+$t;
                                }else{
                                    if(!array_key_exists($s,$majiang_cost3)){
                                        $majiang_cost3[$s] = $t;
                                    }
                                }
                            }
                            if(!array_key_exists($k,$majiang_cost3)){
                                $majiang_cost3[$k] = $v;
                            }
                        }
                    }else{
                        $majiang_cost3 = $majiang_cost2;
                    }

                    arsort($majiang_cost3);
                }
            }
        }

        arsort($game_cost);
        $list = array_merge($club_cost,$qunzhu_cost,$game_cost);
//        $list['sub_majing'] = $majiang;
        $data = array(
            'list'=>$list,
            'majiang'=>$majiang_cost3,
            'time' =>$etime,
        );
//        var_dump($majiang_cost);die();
        $this->load->view('mon_cost',$data);
    }

    //玩家游戏消耗明细
    public function play_cost()
    {
        $where_time = "";
        if($_GET['time']==true){
            $stime = strtotime($this->input->get('time'));
            $etime = $stime+3600*24;
            $where_time = " and create_time >=".$stime." and create_time <".$etime;
        }
        $uid = "";
        if($_GET['uid']==true){
            $uid = $_GET['uid'];
        }
        $sql ="select sum(count) as '俱乐部消耗' from clubs_card_log where club_id in(select club_id from clubs_members where uid = {$uid} and privilage=100) and game_type!=0 and opt=2 ".$where_time;
        $club_cost = $this->game_db->query($sql)->row_array();
        $sql2 = "select sum(num) as '群主消耗' from card_log where change_type=0 and game_type!=0 and type=6 and uid={$uid}".$where_time;
        $qunzhu_cost = $this->game_db->query($sql2)->row_array();
        $game_type = $this->config->item('game_type');
        $majiang_type = $this->config->item('majiang_type');
        $game_cost = array();
        $majiang_cost = array();
        $majiang_cost2 = array();
        $majiang_cost3 = array();
        foreach ($game_type as $k => $v){
            $sql = "select sum(num) as game_cost from card_log where uid={$uid} and change_type=0 and game_type=".$k.$where_time;//从card_log中获取记录
            $result = $this->game_db->query($sql)->row_array();
            $sql2 = "select sum(count) as game_cost from clubs_card_log where club_id in(select club_id from clubs_members where uid={$uid} and privilage=100) and opt=2 and game_type=".$k.$where_time;//从clubs_card_log中，opt=2为消耗
            $result2 = $this->game_db->query($sql2)->row_array();
            if($result['game_cost']||$result2['game_cost']){
                $game_cost[$v]=$result['game_cost']+$result2['game_cost'];
                if($k==2){
                    $sql="select sub_type,sum(num) as majiang_cost from card_log where uid={$uid} and change_type=0 and game_type=2".$where_time." group by sub_type order by majiang_cost desc";
                    $majiang = $this->game_db->query($sql)->result_array();
                    foreach($majiang as $v){
                        $majiang_cost[$majiang_type[$v['sub_type']]]=$v['majiang_cost'];
                    }
                    $sql2="select sub_type,sum(count) as majiang_cost from clubs_card_log where club_id in(select club_id from clubs_members where uid={$uid} and privilage=100) and game_type=2 and opt=2".$where_time." group by sub_type order by majiang_cost desc";
                    $majiang2 = $this->game_db->query($sql2)->result_array();
                    foreach($majiang2 as $v){
                        $majiang_cost2[$majiang_type[$v['sub_type']]]=$v['majiang_cost'];
                    }

                    if(count($majiang_cost)>0){
                        foreach($majiang_cost as $k=>$v) {
                            foreach ($majiang_cost2 as $s => $t) {
                                if ($k == $s) {
                                    $majiang_cost3[$k] = $v + $t;
                                } else {
                                    if (!array_key_exists($s, $majiang_cost3)) {
                                        $majiang_cost3[$s] = $t;
                                    }
                                }
                            }
                            if (!array_key_exists($k, $majiang_cost3)) {
                                $majiang_cost3[$k] = $v;
                            }
                        }
                    }else{
                        $majiang_cost3 = $majiang_cost2;
                    }

                    arsort($majiang_cost3);
                }
            }
        }

        $list = array_merge($club_cost,$qunzhu_cost,$game_cost);
        arsort($list);
        $data = array(
            'list'=>$list,
            'majiang'=>$majiang_cost3,
            'time' =>$etime,
        );
        $this->load->view('mon_play_cost',$data);
    }

    //七天乐消耗排行
    public function paihang(){
        $login_uid = $this->__user_info['mg_user_id'];
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
//        $limit = $this->session->userdata('limit');
//        $limit = 10;
//        $start = ($current - 1) * $limit;

//        $sql2 = "select * from user where 1=1 $where limit $start,$limit";
//        $sql2 = "select user.uid,user.name,SUM(room_log.ju) AS ju,room_log.create_time AS t from user left join room_log on user.uid=room_log.qunzhu where user.qunzhu=1 AND room_log.create_time>$time AND ju>3 GROUP BY user.uid ORDER BY t DESC ,ju DESC limit $start,$limit";
        $sql3 = "select FROM_UNIXTIME(r.create_time,'%Y-%m-%d') AS t from user u left join room_log r on u.uid=r.qunzhu where u.qunzhu=1 AND r.real_ju>=3 GROUP BY t ORDER BY t DESC ,r.real_ju DESC";
        $date = $this->game_db->query($sql3)->result_array();
        foreach($date as $k=>$v){
            $date[$k]=$v['t'];
        }
        $sql2 = "select u.uid,u.name,u.name_alias,SUM(r.real_ju) AS ju,FROM_UNIXTIME(r.create_time,'%Y-%m-%d') AS t from user u left join room_log r on u.uid=r.qunzhu where u.qunzhu=1 AND r.real_ju>=3 AND FROM_UNIXTIME(r.create_time,'%Y-%m-%d')='{$date[$current-1]}' GROUP BY u.uid ORDER BY ju DESC limit 0,100";
        $list = $this->game_db->query($sql2)->result_array();
//        $sql = "select user.uid,user.name,SUM(room_log.ju) AS ju,FROM_UNIXTIME(room_log.create_time,'%Y-%m-%d') AS t from user left join room_log on user.uid=room_log.qunzhu where user.qunzhu=1 AND ju>3 GROUP BY user.uid,t ORDER BY t DESC ,ju DESC";
//        $info = $this->game_db->query($sql)->result_array();
        $count = count($date);
        $this->load->library('pagination');
//        $config['base_url'] = site_url('User/player/' . $uid .'/'.$qunzhu);
        $config['base_url'] = site_url('Statistic/paihang/' . $uid .'/'.$qunzhu);
        $config['total_rows'] = $count;
        $config['uri_segment'] = 5;

        $this->pagination->initialize($config);
        $page_link = $this->pagination->create_links($count);
        $data = array(
            'players' => $list,
            'page_link' => $page_link,
            'show_condition' => $_GET,
            'count' => $date,
        );
        if($login_uid != 1 && $login_uid !=2) {
            $this->load->view('mon_rank1.php', $data);
        }else{
            $this->load->view('mon_rank.php', $data);
        }
    }

    //玩家消耗排行
    public function cost_rank(){
        $login_uid = $this->__user_info['mg_user_id'];
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
        //时间表
	$data= array();
        $sql3 = "select FROM_UNIXTIME(b.create_time,'%Y-%m-%d') AS t from user a left join card_log b on a.uid=b.uid where b.change_type=0 GROUP BY t ORDER BY t DESC limit 0,31";
        $date1 = $this->game_db->query($sql3)->result_array();
        $sql4 = "select FROM_UNIXTIME(create_time,'%Y-%m-%d') AS t from clubs_card_log where opt=2 GROUP BY t ORDER BY t DESC limit 0,31";
        $date2 = $this->game_db->query($sql4)->result_array();
	if(!$date1 && !$date2){return;}
        $_date = array_merge($date1,$date2);
	foreach($_date as $k=>$v){
            $_date[$k]=$v['t'];
        }
        $_date = array_unique($_date);
        arsort($_date);
        foreach($_date as $val){
            $date[] = $val;
        }
        //玩家id,name表
        $sql = "select uid,name from user";
        $members1 = $this->game_db->query($sql)->result_array();
        $members = $this->combine($members1,'uid','name');
        //玩家消耗
        $sql2 = "select a.uid,SUM(b.num) AS cost from user a left join card_log b on a.uid=b.uid where b.change_type=0 AND (b.type=1 OR b.type =6) AND FROM_UNIXTIME(b.create_time,'%Y-%m-%d')='{$date[$current-1]}' GROUP BY a.uid ORDER BY cost DESC limit 0,100";
        $list1 = $this->game_db->query($sql2)->result_array();
        $_list1 = $this->combine($list1,'uid','cost');
//        俱乐部玩家消耗
        $sql4 = "select b.uid,SUM(a.count) AS cost from clubs_card_log a left join clubs_members b on a.club_id=b.club_id where a.opt=2 AND b.privilage=100 AND FROM_UNIXTIME(a.create_time,'%Y-%m-%d')='{$date[$current-1]}' GROUP BY b.uid ORDER BY cost DESC limit 0,100";
        $list2 = $this->game_db->query($sql4)->result_array();
        $_list2 = $this->combine($list2,'uid','cost');
        $list = array();
        if(count($_list1)>0){
            foreach ($_list1 as $k => $v){
                if(count($_list2)>0){
                    foreach ($_list2 as $s=>$t){
                        if($k == $s){
                            $list[$k]=$v+$t;
                        }else{
                            if(!array_key_exists($k,$list)){
                                $list[$k]=$v;
                            }
                            if(!array_key_exists($s,$list)){
                                $list[$s]=$t;
                            }
                        }
                    }
                }else{
                    $list = $_list1;
                }
            }
        }else{
           $list = $_list2;
        }

        arsort($list);
        foreach($list as $k=>$v){
            foreach($members as $s=>$t){
                if($k==$s){
                    $list[$k]=array('name'=>$t,'cost'=>$v,'t'=>$date[$current-1]);
                }
            }
        }
//        var_dump($list);die();
        $count = count($date);
        $this->load->library('pagination');
//        $config['base_url'] = site_url('User/player/' . $uid .'/'.$qunzhu);
        $config['base_url'] = site_url('Statistic/cost_rank/' . $uid .'/'.$qunzhu);
        $config['total_rows'] = $count;
        $config['uri_segment'] = 5;

        $this->pagination->initialize($config);
        $page_link = $this->pagination->create_links($count);
        $data = array(
            'players' => $list,
            'page_link' => $page_link,
            'show_condition' => $_GET,
            'count' => $date,
        );
            $this->load->view('mon_cost_rank.php', $data);
    }
    //推荐代理查询
    public function recommend_query() {
        $user_id = $_SESSION['user_info']['mg_user_id'];
        if (isset($_GET['stime'])) {
            $stime = $_GET['stime'];
        } else {
            $stime = date("Y-m-01");
        }

        if (isset($_GET['etime'])) {
            $etime = $_GET['etime'];
        } else {
            $etime = date("Y-m-d");
        }

        $where_str = 'and p_mg_user_id = '.$user_id;

//        $user_id = $this->uri->segment(3, 0);

        //获得列表
        $current = $this->uri->segment(4, 1); // 页数
        $limit = $limit = $this->session->userdata('limit');
        $start = ($current - 1) * $limit;
        $sql = "select * from mg_user as m where 1=1 ".$where_str. " order by mg_user_id asc limit ".$start.", ".$limit;
        $list = $this->db->query($sql,2)->result_array();

        //获取代理下线总消费
        if ($stime && $etime) {
            $ustime = strtotime($stime);
            $uetime = strtotime($etime) + 86400;
            $wherestr = ' and a.pay_time >= ' . $ustime . ' and a.pay_time <' . $uetime;

        }
        $list_amount = array();
        $amount = 0;
        foreach($list as $k=>$v){
            $where_str2 = ' and b.invite_id in (' . $v['mg_user_id'] . ')';
            $sql3 = "select count(a.charge_id) as count,sum(a.amount) as total_money from charge as a left join user as b on a.uid = b.uid where a.status = 1 " . $wherestr . $where_str2;
            $list_amount = $this->game_db->query($sql3)->row_array();
            $list[$k] = array_merge($v,$list_amount);
            $amount += $list_amount['total_money'];
        }

        $sql2 = "select count(*) as count from mg_user as m where 1=1 ".$where_str;
        $tmp_count = $this->db->query($sql2)->row_array();
        $count = $tmp_count['count'];
        $this->load->library('pagination');
        $url = site_url('Gm/agent_list/'.$user_id);
        $config['base_url'] = $url;
        $config['total_rows'] = $count;
        $config['per_page'] = $limit;
        $config['uri_segment'] = 4;
        $config['use_page_numbers']=true;
        $this->pagination->initialize($config);
        $page_link = $this->pagination->create_links();
        $data = array(
            'amount'                =>$amount,
            'list'                  => $list,
            'start'                 => $start,
            'page_link'             => $page_link,
            'role_names'            => $this->config->item('role_names'),
            'count'                 => $count,
            'show_condition'      => array(
                'stime' => $stime,
                'etime' => $etime,
            )
        );
        $this->load->view('mon_recommend_list', $data);
    }

    /**
     * 查询统计()
     * user_props_consume_history 用户道具消费历史
     */
    public function user_charge() {
        $login_uid = $this->__user_info['mg_user_id'];
        if($login_uid != 1 && $login_uid != 2 ) {
            die("没有权限");
        }
        
        $this->load->database();
        $this->load->model('mysql_model');
        $this->load->model('user_m');
        $userInfo = $this->session->userdata('user_info');
        $current_user_id = $userInfo['mg_user_id'];
        $where_str = '';
        // 用户id
        //会员选项
        $user_id = $this->uri->segment(3, -2);
        if(isset($_POST['user_id']) && $_POST['user_id'] > 0){
            $user_id = $this->input->post('user_id');
        }
        $stime_1 = $this->uri->segment(5, date("Y-m-d"));
        $etime_1 = $this->uri->segment(6, date("Y-m-d"));
        if(!empty($_POST['stime1'])) {
            $stime_1 = $_POST['stime1'];
        }
        if(!empty($_POST['etime1'])) {
            $etime_1 = $_POST['etime1'];
        }
        $start_time = strtotime($stime_1);
        $end_time = strtotime($etime_1) + 86400;

        $where_str .= ' and a.pay_time >= '.$start_time;
        $where_str .= ' and a.pay_time < '.$end_time;
        
        $uid = $this->uri->segment(7, 0);
        if(!empty($_POST['uid'])) {
            $uid = trim($_POST['uid']);
        }
        if($uid > 0) {
            $where_str .= " and a.uid = ".$uid;
        }
        
        $status = $this->uri->segment(8, -1);
        if(isset($_POST['status'])) {
            $status = trim($_POST['status']);
        }
        if($status>=0) {
            $where_str .= " and a.status = ".$status;
        }
        
        //获得列表
        $current = $this->uri->segment(9, 1); // 页数
        $limit = $limit = $this->session->userdata('limit');;
        $start = ($current - 1) * $limit;
        $sql = "select a.*,b.name from charge as a left join user as b on a.uid = b.uid  where 1=1 ".$where_str.
               " order by a.pay_time desc limit ".$start.", ".$limit;
        $list = $this->game_db->query($sql,2)->result_array();
        $sql2 = "select count(a.charge_id) as count,sum(a.amount) as total_money from charge as a left join user as b on a.uid = b.uid where 1=1 ".$where_str;
        $tmp_count = $this->game_db->query($sql2)->row_array();
        $count = $tmp_count['count'];
        $this->load->library('pagination');
        $url = site_url('Statistic/user_charge/'.$user_id.'/0/'.$stime_1.'/'.$etime_1.'/'.$uid.'/'.$status);
        $config['base_url'] = $url;
        $config['total_rows'] = $count;
        $config['per_page'] = $limit;
        $config['uri_segment'] = 9;
        $config['use_page_numbers']=true;
        
        $this->pagination->initialize($config); 
        $page_link = $this->pagination->create_links();

        $data = array(
            'list'                  => $list,
            'start'                 => $start,
            'page_link'             => $page_link,
            'role_names'            => $this->config->item('role_names'),
            'count'                 => $count,
            'total'                 => intval($tmp_count['total_money']/100),
            'invite_code'           => $userInfo['invotecode'],
            'status'                => array(0=>'未支付',1=>'已支付',2=>'异常1',3=>'异常2'),
            'show_condition'      => array(
                'user_id' => $user_id,
                'stime' => $stime_1,
                'etime' => $etime_1,
                'uid'   => $uid,
                'status'    => $status,
                )
            );
        $this->load->view('mon_user_charge', $data);
    }
    
    /**
     * 后台充值查询
     */
    public function agent_charge() {
        $this->load->database();
        $this->load->model('mysql_model');
        $this->load->model('user_m');
        
        $where_str = '';
        //会员选项
        $user_id = $this->uri->segment(3, 0);
        if(isset($_POST['user_id']) && $_POST['user_id'] > 0){
            $user_id = $this->input->post('user_id');
        }
        if((int) $user_id > 0){
            $where_str = ' and a.user_id = '.$user_id;
        }
        
        $stime_1 = $this->uri->segment(5, date("Y-m-d",strtotime("-3 day")));
        $etime_1 = $this->uri->segment(6, date("Y-m-d"));
        if(!empty($_POST['stime1'])) {
            $stime_1 = $_POST['stime1'];
        }
        if(!empty($_POST['etime1'])) {
            $etime_1 = $_POST['etime1'];
        }
        $start_time = strtotime($stime_1);
        $end_time = strtotime($etime_1) + 86400;

        $where_str .= " and a.pay_time >= '".date('Y-m-d H:i:s',$start_time)."'";
        $where_str .= " and a.pay_time < '".date('Y-m-d H:i:s',$end_time)."'";
        
        //获得列表
        $current = $this->uri->segment(7, 1); // 页数
        $limit = $limit = $this->session->userdata('limit');
        $start = ($current - 1) * $limit;
        $sql = "select a.*,b.level,b.mg_name from mg_user_charge as a left join mg_user as b on a.user_id = b.mg_user_id where a.active = 1 ". $where_str. " order by a.pay_time desc limit ".$start.", ".$limit;
        $list = $this->db->query($sql,2)->result_array();
        $sql2 = "select sum(rmb) as total_money,count(*) as count from mg_user_charge as a where a.active = 1 ". $where_str;
        $tmp_count = $this->db->query($sql2)->row_array();
        $count = $tmp_count['count'];
        $this->load->library('pagination');
        $url = site_url('Statistic/agent_charge/'.$user_id.'/0/'.$stime_1.'/'.$etime_1);
        $config['base_url'] = $url;
        $config['total_rows'] = $count;
        $config['per_page'] = $limit;
        $config['uri_segment'] = 7;
        $config['use_page_numbers']=true;
        
        $this->pagination->initialize($config); 
        $page_link = $this->pagination->create_links();

        $data = array(
            'list'                  => $list,
            'start'                 => $start,
            'page_link'             => $page_link,
            'role_names'            => $this->config->item('role_names'),
            'count'                 => $count,
            'total'                 => $tmp_count['total_money'],
            'show_condition'      => array(
                'user_id' => $user_id,
                'stime' => $stime_1,
                'etime' => $etime_1,
                )
            );
        $this->load->view('mon_agent_charge', $data);
    }
// 将二维数组的某个$v[$flag]作为本数组的key
    function combine($arr,$key,$value){
        $array = array();
        foreach($arr as $k=>$v){
            $array[$v[$key]] = $v[$value];
        }
        return $array;
    }

}
