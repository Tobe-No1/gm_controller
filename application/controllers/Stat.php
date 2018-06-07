<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Stat extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->game_db = $this->load->database('game', TRUE);
        $this->db = $this->load->database('default', TRUE);
        $this->load->model('user_m');
        $this->__user_info = $this->session->userdata('user_info');
        $this->role_names = $this->config->item('role_names');
        $this->output->enable_profiler(false);
    }

    // 是否登陆判断
    private function is_login() {
        if (empty($this->__user_info)) {
            $url = get_url('/index.php/Login/menu');
            header("Location:$url");
            die(); // 跳到玩家管理页面
        }
    }

    // --------------------------------------------------

    /* 登陆相关 */
    public function view_login() {
        //$this->load->view('login');
        $temp['footer'] = $this->config->item('footer');
        $this->load->view('mon_login', $temp);
    }
    
    
    

    public function charge_base() {
        $this->is_login();
        $where_str = '';
        $wherestr = '';
        if (isset($_GET['stime'])) {
            $stime = $_GET['stime'];
        } else {
            $stime = date("Y-m-d");
        }
        if (isset($_GET['etime'])) {
            $etime = $_GET['etime'];
        } else {
            $etime = date("Y-m-d");
        }

        if ($stime && $etime) {
            $ustime = strtotime($stime);
            $uetime = strtotime($etime) + 86400;
            $where_str .= ' and a.pay_time >= ' . $ustime . ' and a.pay_time <' . $uetime;
            $wherestr = " and a.pay_time >= '{$stime}' and a.pay_time <= '{$etime}'";
        }

        $current_user_id = intval($this->__user_info['mg_user_id']);

        $temp['mgid'] = $mgUserId = intval($this->__user_info['mg_user_id']);
        $info = $this->uinfo($mgUserId);
        $temp = array_merge($temp, $info);

        $where_str2 = '';
        $wherestr2 = '';
        if ($current_user_id != 1) {
//            $uids = $this->user_m->getInvoteStr($current_user_id);
            $uids = $this->user_m->getInvoteStr2($current_user_id);
            if (!empty($uids)) {
                $wherestr2 = ' and b.mg_user_id in (' . $uids . ')';
                $uids = $uids . ',' . $current_user_id;
            } else {
                $uids = $current_user_id;
            }
            $where_str2 = ' and c.mtype=1 and b.invite_id in (' . $uids . ')';
        }
        //查询充值总额
//        $sql = "select id from products where mtype = 2";
//        $res = $this->game_db->query($sql)->result_array();
//        $product_id = '';
//        foreach($res as $k=>$v) {
//            $product_id .= $v['id'].',';
//        }
//        $product_id = substr($product_id,0,strlen($product_id)-1);
////        echo $product_id;die();
//        $sql1 = "select count(a.charge_id) as count,sum(a.amount) as total_money from charge as a left join user as b on a.uid = b.uid where a.status = 1 and product_id in (".$product_id.") " . $where_str . $where_str2;
//        $total_info_gold = $this->game_db->query($sql1)->row_array();

        $sql2 = "select count(a.charge_id) as count,sum(a.amount) as total_money from charge as a left join user as b on a.uid = b.uid left join products as c on a.product_id=c.id where a.status = 1 " . $where_str . $where_str2;
        $total_info = $this->game_db->query($sql2)->row_array();

        //我直接下线
        $where_str3 = ' and c.mtype=1 and b.invite_id =' . $current_user_id;
        $sql2 = "select count(a.charge_id) as count,sum(a.amount) as total_money from charge as a left join user as b on a.uid = b.uid left join products as c on a.product_id=c.id where a.status = 1 " . $where_str . $where_str3;
        $xiaxian = $this->game_db->query($sql2)->row_array();


	//玩家充值金币算公司下线
        $sql3 = "select count(a.charge_id) as count,sum(a.amount) as total_money from charge as a left join user as b on a.uid = b.uid left join products as c on a.product_id=c.id where a.status = 1 and c.mtype != 1 " . $where_str;
        $_xiaxian = $this->game_db->query($sql3)->row_array();
//        $temp['total_money_gold'] = intval($total_info_gold['total_money'] / 100);
        $temp['total_money'] = intval($total_info['total_money'] / 100);
        if($current_user_id != 1){
            $temp['xiaxian'] = intval($xiaxian['total_money'] / 100);
        }else{
            $temp['xiaxian'] = intval(($xiaxian['total_money'] + $_xiaxian['total_money'])/ 100);
        }
        $temp['daili'] = $temp['total_money'] - $temp['xiaxian'];
        $temp['show_condition'] = array(
            'stime' => $stime,
            'etime' => $etime,
        );
        $this->load->view('mon_charge_base', $temp);
    }

    public function mon_fanxian() {
        $this->is_login();

        $where_str = '';
        $wherestr = '';

        if (isset($_GET['stime'])) {
            $stime = $_GET['stime'];
        } else {
            $stime = date("Y-m-d");
        }

        if (isset($_GET['etime'])) {
            $etime = $_GET['etime'];
        } else {
            $etime = date("Y-m-d");
        }

        if ($stime && $etime) {
            $ustime = strtotime($stime);
            $uetime = strtotime($etime) + 86400;
            $where_str .= ' and a.pay_time >= ' . $ustime . ' and a.pay_time <' . $uetime;
            $etime_date = date('Y-m-d', $uetime);
            $wherestr = " and a.pay_time >= '{$stime}' and a.pay_time < '{$etime_date}'";
        }

        $current_user_id = intval($this->__user_info['mg_user_id']);

        $temp['mgid'] = $mgUserId = intval($this->__user_info['mg_user_id']);
        $info = $this->uinfo($mgUserId);
        $temp = array_merge($temp, $info);

        $where_str2 = '';
        $wherestr2 = '';
        if ($current_user_id != 1) {
            $uids = $this->user_m->getInvoteStr2($current_user_id);
            if (!empty($uids)) {
                $wherestr2 = ' and b.mg_user_id in (' . $uids . ')';
            }
        }
        $temp['show_condition'] = array(
            'stime' => $stime,
            'etime' => $etime,
        );

        if ($wherestr2 != '') {
            $sql2 = "select a.*,b.mg_user_id,b.level from mg_user_charge as a left join mg_user as b on a.user_id = b.mg_user_id where a.active = 1 " . $wherestr . $wherestr2;
            $lists = $this->db->query($sql2)->result_array();
        } else {
            $lists = array();
        }

        $sql3 = "select * from mg_products ";
        $tmps = $this->db->query($sql3)->result_array();
        $products = array();
        foreach ($tmps as $tmp) {
            $products[$tmp['pid']] = $tmp;
        }

        $total = 0;
        foreach ($lists as $k => $li) {
            //计算返利

            $lists[$k]['card_num'] = $products[$li['product_id']]['num'];

            if ($info['level'] >= 4) {
                $lists[$k]['fan'] = 0;
            } else {
                if ($info['level'] == 2) {
                    $fan = $products[$li['product_id']]['num'] * 0.1;
                    $total += $fan;
                    $lists[$k]['fan'] = $fan;
                } elseif ($info['level'] == 3) {
                    $fan = $products[$li['product_id']]['num'] * 0.2;
                    $total += $fan;
                    $lists[$k]['fan'] = $fan;
                } else {
                    $lists[$k]['fan'] = 0;
                }
            }
        }
        $temp['total'] = $total;

        $this->load->view('mon_fanxian', $temp);
    }

    /**
     * 下线代理
     */
    public function charge_agent() {
        $this->is_login();
        if (isset($_GET['s'])) {
            $stime = $_GET['s'];
        } else {
            $stime = $this->uri->segment(5, date("Y-m-d"));
        }
        if (isset($_GET['e'])) {
            $etime = $_GET['e'];
        } else {
            $etime = $this->uri->segment(7, date("Y-m-d"));
        }

        $t1 = microtime(true);

        $where_str = '';
        $wherestr = '';
        if ($stime && $etime) {
            $ustime = strtotime($stime);
            $uetime = strtotime($etime) + 86400;
            $where_str .= ' and a.pay_time >= ' . $ustime . ' and a.pay_time <' . $uetime;
            $wherestr = " and a.pay_time >= '{$stime}' and a.pay_time <= '{$etime}'";
        }
        if (!empty($_GET['user_id'])) {
            $mgUserId = trim($_GET['user_id']);
            if ($mgUserId != $this->__user_info['mg_user_id']) {
                if ($this->user_m->checkInvote($this->__user_info['mg_user_id'], $mgUserId) == false) {
                    $mgUserId = $this->__user_info['mg_user_id'];
                }
            }
        } else {
            $mgUserId = intval($this->__user_info['mg_user_id']);
        }
        $sql = "select * from mg_user where mg_user_id = " . $mgUserId;
        $user_info = $this->mysql_model->query($sql, 1);
        //发展玩家充值
        $where_str2 = ' and c.mtype = 1 and invite_id = ' . $mgUserId;
        $sql2 = "select a.*,b.name from user as b left join charge as a  on a.uid = b.uid left join products as c on a.product_id=c.id where a.status = 1 " . $where_str . $where_str2;
        $datas1 = $this->game_db->query($sql2)->result_array();
        $sql3 = "select a.*,b.name from user as b left join charge as a on a.uid = b.uid left join products as c on a.product_id = c.id where a.status = 1 and c.mtype != 1" . $where_str;
        $datas2 = $this->game_db->query($sql3)->result_array();
	if($mgUserId !=1){
            $datas = $datas1;
        }else{
            $datas = array_merge($datas1,$datas2);
        }
        $self_total = 0;
        foreach ($datas as $d) {
            $self_total += $d['amount'];
        }
        $sql = "select * from mg_user where p_mg_user_id = " . $mgUserId;
        $list = $this->mysql_model->query($sql, 2);
        $xiaxian_total = 0;
        foreach ($list as $k => $t) {
            $uids = array($t['mg_user_id']);
            $this->user_m->getInvote2(array($t['mg_user_id']), $uids);
            if (!empty($uids)) {
                $uids_str = implode(',', $uids);
               // if($mgUserId!=1){
               //     $tmp = ' and a.product_id<20 and b.invite_id in(' . $uids_str . ')';
               // }else{
               //     $tmp = ' and b.invite_id in (' . $uids_str . ')';
               // }
               // $sql2 = "select count(a.charge_id) as count,sum(a.amount) as total_money from charge as a left join user as b on a.uid = b.uid where a.status = 1 " . $where_str . $tmp;
               $tmp = ' and c.mtype=1 and b.invite_id in(' . $uids_str . ')';
               $sql2 = "select count(a.charge_id) as count,sum(a.amount) as total_money from charge as a left join user as b on a.uid = b.uid left join products as c on a.product_id=c.id where a.status = 1 " . $where_str . $tmp;
		 $info = $this->game_db->query($sql2)->row_array();
            } else {
                $info['total_money'] = 0;
            }

            $list[$k]['total_money'] = intval($info['total_money'] / 100);
            $xiaxian_total += $info['total_money'];
        }

//        echo (microtime(true) - $t1) * 1000 . ":t3\n";

        $temp['user_info'] = $user_info;
        $temp['ulistb'] = $list;
        $temp['slist'] = $datas;
        $temp['s'] = $stime;
        $temp['e'] = $etime;
        $temp['self_total'] = intval($self_total / 100);
        $temp['xiaxian_total'] = intval($xiaxian_total / 100);
        $temp['mgid'] = intval($this->__user_info['mg_user_id']);
        $this->load->view('mon_charge_agent', $temp);
    }

    public function xiaxian_charge() {

        $stime = $_GET['stime'];
        $etime = $_GET['etime'];

        $wherestr = '';
        if ($stime && $etime) {
            $etime_date = date('Y-m-d', strtotime($etime) + 86400);
            $wherestr = " and a.pay_time >= '{$stime}' and a.pay_time <= '{$etime_date}'";
        }

        $mgUserId = intval($this->__user_info['mg_user_id']);

        $sql = "select * from mg_user where mg_user_id = " . $mgUserId;
        $user_info = $this->mysql_model->query($sql, 1);


        $uids = $this->user_m->getInvoteStr2($mgUserId);
        $tmp = '';
        if (!empty($uids)) {
            $tmp = ' and b.mg_user_id in (' . $uids . ')';
            $sql2 = "select a.*,b.mg_user_id,b.level from mg_user_charge as a left join mg_user as b on a.user_id = b.mg_user_id where a.active = 1 AND a.product_id<20 " . $wherestr . $tmp;
            $lists = $this->db->query($sql2)->result_array();
        } else {
            $lists = array();
        }

        $sql3 = "select * from mg_products ";
        $tmps = $this->db->query($sql3)->result_array();
        $products = array();
        foreach ($tmps as $tmp) {
            $products[$tmp['pid']] = $tmp;
        }

        $total = 0;
        foreach ($lists as $k => $li) {
            $lists[$k]['card_num'] = $products[$li['product_id']]['num'];
            //计算返利
            if ($user_info['level'] >= 4) {
                $lists[$k]['fan'] = 0;
            } else {
                if ($user_info['level'] == 2) {
                    $fan = $products[$li['product_id']]['num'] * 0.1;
                    $total += $fan;
                    $lists[$k]['fan'] = $fan;
                } elseif ($user_info['level'] == 3) {
                    $fan = $products[$li['product_id']]['num'] * 0.2;
                    $total += $fan;
                    $lists[$k]['fan'] = $fan;
                } else {
                    $lists[$k]['fan'] = 0;
                }
            }
        }

        $temp = array();
        $temp['user_info'] = $user_info;
        $temp['lists'] = $lists;
        $temp['total'] = $total;
        $this->load->view('mon_fanxian_list', $temp);
    }

    /**
     * 最近数据
     */
    public function lately_data() {
        $this->is_login();
        $mgUserId = trim($_GET['user_id']);
        if ($mgUserId != $this->__user_info['mg_user_id']) {
            if ($this->user_m->checkInvote($this->__user_info['mg_user_id'], $mgUserId) == false) {
                $mgUserId = $this->__user_info['mg_user_id'];
            }
        }

        $sql = "select * from mg_user where mg_user_id = " . $mgUserId;
        $user_info = $this->mysql_model->query($sql, 1);
        $user_info['date'] = date('Y/m/d', strtotime("-10 day")) . " - " . date('Y/m/d');
        //20天
        $where_str = ' and a.pay_time >= ' . strtotime("-20 day");
//        $uid = $this->user_m->getInvoteStr($mgUserId);
        $uids = array($mgUserId);
        $this->user_m->getInvote2(array($mgUserId), $uids);
        $where_str .= ' and b.invite_id in (' . implode(',', $uids) . ')';
        $sql2 = "select sum(a.amount) as total_money,from_unixtime(pay_time,'%Y-%m-%d') as stat_date from charge as a left join user as b on a.uid = b.uid where a.status = 1 " . $where_str . " group by stat_date asc";
        $tmps = $this->game_db->query($sql2)->result_array();

        $records = array();
        foreach ($tmps as $v) {
            $records[$v['stat_date']] = $v;
        }

        $datas = array();
        $dates = array();
        for ($i = 20; $i > 0; $i--) {
            $dates[] = date('Y-m-d', strtotime("-{$i} day"));
        }

        foreach ($dates as $v) {
            if (isset($records[$v])) {
                $datas[$v] = $records[$v];
            } else {
                $datas[$v] = array('stat_date' => $v, 'total_money' => 0);
            }
        }

        $tmp['datas'] = $datas;
        $tmp['user_info'] = $user_info;
        $this->load->view('mon_lately_data', $tmp);
    }

    public function charge_xiaxian() {
        $this->is_login();
        if (isset($_GET['s'])) {
            $stime = $_GET['s'];
        } else {
            $stime = $this->uri->segment(5, date("Y-m-d"));
        }
        if (isset($_GET['e'])) {
            $etime = $_GET['e'];
        } else {
            $etime = $this->uri->segment(7, date("Y-m-d"));
        }
        $where_str = '';
	$wherestr = '';
        if ($stime && $etime) {
            $ustime = strtotime($stime);
            $uetime = strtotime($etime) + 86400;
            $where_str .= ' and a.pay_time >= ' . $ustime . ' and a.pay_time <' . $uetime;
        }

        if (!empty($_GET['user_id'])) {
            $mgUserId = trim($_GET['user_id']);
            $uids = $this->user_m->getInvoteStr($this->__user_info['mg_user_id']);
            $uids_list = explode(',', $uids);
            if (!in_array($mgUserId, $uids_list)) {
                $mgUserId = $this->__user_info['mg_user_id'];
            }
        } else {
            $mgUserId = intval($this->__user_info['mg_user_id']);
        }

        $wherestr .= " and b.invite_id = " . $mgUserId;

        $temp['mgid'] = $mgUserId;
        //card_amount表示充值房卡的金额，gold_amount表示充值金币的金额,mtype=1直接玩家购买的房卡金额，mtype=2购买金币金额，mtype=3代理购买房卡
        $sql2 = "select sum(a.amount) as card_amount,a.uid,b.name from charge as a left join user as b on a.uid = b.uid left join products as c on a.product_id = c.id where a.status = 1 and c.mtype = 1" . $where_str.$wherestr." group by uid";
        $_list1 = $this->game_db->query($sql2)->result_array();
        foreach($_list1 as $k=>$v){
            $_list1[$k]['gold_amount'] = 0;
        }
        $sql2 = "select sum(a.amount) as card_amount,a.uid,b.name from charge as a left join user as b on a.uid = b.uid left join products as c on a.product_id = c.id where a.status = 1 and c.mtype = 3" . $where_str.$wherestr." group by uid";
        $_list2 = $this->game_db->query($sql2)->result_array();
        foreach($_list2 as $k=>$v){
            $_list2[$k]['gold_amount'] = 0;
        }

        $sql3 = "select sum(a.amount) as gold_amount,a.uid,b.name from user as b left join charge as a on a.uid = b.uid left join products as c on a.product_id = c.id where a.status = 1 and c.mtype = 2" . $where_str." group by uid";
        $list2 = $this->game_db->query($sql3)->result_array();
        foreach($list2 as $k=>$v){
            $list2[$k]['card_amount']=0;
        }
        $list = array();
        if($mgUserId !=1){
            $list = $_list1;
        }else{
            $list1 = array_merge($_list1,$_list2);
            foreach($list1 as $v){
                $list[$v['uid']] = $v;
                foreach($list2 as $s=>$t){
                    if($v['uid']==$t['uid']){
                        $list[$v['uid']]['card']=$v['card']+$t['card'];
                        $list[$v['uid']]['gold']=$v['gold']+$t['gold'];
                        unset($list2[$s]);
                    }else{
                        $list[$t['uid']] = $t;
                    }
                }
            }
        }
        $temp['clist'] = $list;
        $this->load->view('mon_charge_xiaxian', $temp);
    }

    private function uinfo($id) {
        $sql = "select `mg_name`,`level`,`invotecode` from `mg_user` where mg_user_id={$id}";
        $row = $this->mysql_model->query($sql, 1);
        $info['uname'] = $row['mg_name'];
        $info['level'] = $row['level'];
        $info['icode'] = $row['invotecode'];
        $role_names = $this->config->item('role_names');
        $info['role_names'] = $role_names[$row['level']];
        $sql = "select sum(amount)/100 as icount from `charge` where uid ={$id} and `status` =1";
        $row = $this->game_db->query($sql, 1)->row_array();
        $info['mcost'] = $row['icount'];
        $sql = "select `head` from `user` where uid ={$id}";
        $row = $this->game_db->query($sql, 1)->row_array();
        $info['head'] = '';
        if (isset($row['head']))
            $info['head'] = '<img src="' . $row['head'] . '" style="height: 15px;width: 15px;">';
        return $info;
    }

    /* 玩家管理相关 */

    public function view_cart() {
        $this->is_login();
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

        $view_var['footer'] = $this->config->item('footer');
        $this->load->view('mon_card', $view_var);
        //$this->load->view('index_issueCard', $view_var);
    }

    /* Json 相关 */

    private function Json($v_status, $v_msg, $v_array = array()) {
        $out_array = array(
            'status' => $v_status,
            'msg' => $v_msg
        );
        die(json_encode(array_merge($out_array, $v_array)));
    }

    /*子服务器响应函数*/
    public function charge()
    {
        $current_user_id = 1;
        $where_str='';
        $where_str2='1';
        $where_str2 .= ' and ch.user_id in (' . $current_user_id . ')';
        if (isset($_GET['stime'])) {
            $stime = $_GET['stime'];
        } else {
            $stime = date("Y-m-d");
        }

        if (isset($_GET['etime'])) {
            $etime = $_GET['etime'];
        } else {
            $etime = date("Y-m-d");
        }
        if ($stime && $etime) {
            $ustime = strtotime($stime);
            $uetime = strtotime($etime) + 86400;
            $where_str .= ' and a.pay_time >= ' . $ustime . ' and a.pay_time <' . $uetime;
            $where_str2 .=' and ch.create_time >= ' . "'" . date('Y-m-d H:i:s', $ustime) . "'" . ' and ch.create_time < ' . "'" . date('Y-m-d H:i:s',$uetime) . "'";
        }
        $sql = "select count(*) as c,sum(count) as total_boka from user_props_consume_history ch where " . $where_str2;
        $total_boka = $this->mysql_model->query($sql);
        $sql2 = "select sum(a.amount) as total_money from charge as a left join user as b on a.uid = b.uid where a.status = 1 " . $where_str;
        $total_info = $this->game_db->query($sql2)->row_array();
        $data = array(
            'total_money'=>$total_info['total_money'],
            'total_boka'=>$total_boka['total_boka'],
        );
        echo json_encode($data);
    }

}
