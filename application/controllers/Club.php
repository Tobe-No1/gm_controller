<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Club extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_m');
        if (!isset($_SESSION['user_info']) && empty($_SESSION['user_info'])) {
            $url = get_url('/index.php/Login/menu');
            header("Location:$url");
            die();
        }
        $this->game_db = $this->load->database('game', TRUE);
        $this->db = $this->load->database('default', TRUE);
        $this->output->enable_profiler(false);
        $this->__user_info = $this->session->userdata('user_info');

        $this->privilages = array(
            100 => '主席       ',
            90 => '副主席',
            50 => '普通成员',
            20 => '被邀请的成员',
        );

        $this->games = array(
            0 => '无',
            1 => '牛牛',
            2 => '麻将',
            3 => '十三水',
            4 => '炸金花',
            5 => '斗地主',
            6 => '跑得快',
            7 => '三同',
            8 => '28杠',
        );
        
        $this->types = array(
                    1 => '开房扣卡      ',
                    2 => '充值          ',
                    3 => '上下分',
                    4 => '系统赠送      ',
                    5 => '邀请赠送      ',
                    6 => '群主开房      ',
                    7 => '日常领取      ',
                    8 => '救济',
                    9 => '输赢',
                    10 => '机器人上分    ',
                    11 => '机器人下分    ',
                    13 => '代理卡        ',
                    14 => '洗牌          ',
                    15 => '金币场赢得金币',
                    16 => '俱乐部砖石    ',
                    17 => '首充奖励      ',
                    18  => '贡献',
            );
    }

    public function members() {

        $login_uid = $this->__user_info['mg_user_id'];
        $clubs = $this->game_db->query("select b.* from clubs_members as a left join clubs b  on a.club_id = b.id where a.privilage = 100 and  a.uid = {$login_uid}")->result_array();

        // 用户id
        //会员选项
        $club_id = 0;
        if (isset($_POST['club_id']) && $_POST['club_id'] > 0) {
            $club_id = $this->input->post('club_id');
        }
        if ($club_id == 0 && count($clubs) > 0) {
            $club_id = $clubs[0]['id'];
        }
        $where_str = " where a.club_id = $club_id";

        $uid = 0;
        if (!empty($_POST['uid'])) {
            $uid = trim($_POST['uid']);
        }
        if ($uid > 0) {
            $where_str .= " and a.uid = " . $uid;
        }

        $sql = "select a.*,b.name from clubs_members as a left join user as b on a.uid = b.uid " . $where_str;
//        echo $sql;
//        die();
        $members = $this->game_db->query($sql)->result_array();
        $data = array(
            'members' => $members,
            'uid' => $uid,
            'club_id' => $club_id,
            'clubs' => $clubs,
            'privilages' => $this->privilages,
        );
        $this->load->view('mon_club_members', $data);
    }

    public function add_score() {

        $club_id = $_POST['club_id'];
        $uid = $_POST['uid'];
        $score = $_POST['score'];
        //{club_id=xxx,uid=xxx,points=xxx}

        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_connect($socket, $this->config->item('GMIP'), $this->config->item('GMPORT'));
        $arr = array(
            'club_id' => $club_id,
            'uid'       => $uid,
            'points' => $score,
        );
        $str = 'PHP7' . json_encode($arr);
        $size = strlen($str);
        $binary_str = pack("na" . $size, $size, $str);
        socket_write($socket, $binary_str, strlen($binary_str));
        socket_close($socket);
        
        echo json_encode(array('status'=>0, 'msg'=>'上下分成功'));
    }

    /**
     * 查询统计()
     * user_props_consume_history 用户道具消费历史
     */
    public function point_detail() {
        $login_uid = $this->__user_info['mg_user_id'];
        $clubs = $this->game_db->query("select b.* from clubs_members as a left join clubs b  on a.club_id = b.id where a.privilage = 100 and  a.uid = {$login_uid}")->result_array();

        $where_str = '';
        // 用户id
        //会员选项
        $club_id = $this->uri->segment(3, 0);
        if (isset($_POST['club_id']) && $_POST['club_id'] > 0) {
            $club_id = $this->input->post('club_id');
        }
        if ($club_id == 0 && count($clubs) > 0) {
            $club_id = $clubs[0]['id'];
        }
        $where_str = " where a.club_id = $club_id";

        $stime_1 = $this->uri->segment(5, date("Y-m-d"));
        $etime_1 = $this->uri->segment(6, date("Y-m-d"));
        if (!empty($_POST['stime1'])) {
            $stime_1 = $_POST['stime1'];
        }
        if (!empty($_POST['etime1'])) {
            $etime_1 = $_POST['etime1'];
        }
        $start_time = strtotime($stime_1);
        $end_time = strtotime($etime_1) + 86400;

        $where_str .= ' and a.create_time >= ' . $start_time;
        $where_str .= ' and a.create_time < ' . $end_time;

        $uid = $this->uri->segment(7, 0);
        if (!empty($_POST['uid'])) {
            $uid = trim($_POST['uid']);
        }
        if ($uid > 0) {
            $where_str .= " and a.uid = " . $uid;
        }

        //获得列表
        $current = $this->uri->segment(8, 1); // 页数
        $limit = $limit = $this->session->userdata('limit');
        ;
        $start = ($current - 1) * $limit;
        $sql = " select a.*,b.name from points_log as a left join user as b on a.uid = b.uid " . $where_str . " order by a.id desc limit " . $start . ", " . $limit;
        $list = $this->game_db->query($sql, 2)->result_array();
        $sql2 = " select * from points_log as a left join user as b on a.uid = b.uid " . $where_str;
        $tmp_count = $this->game_db->query($sql2)->row_array();
        $count = $tmp_count['count'];
        $this->load->library('pagination');
        $url = site_url('Club/point_detail/' . $club_id . '/0/' . $stime_1 . '/' . $etime_1 . '/' . $uid);
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
            'clubs' => $clubs,
            'games' => $this->games,
            'privilages' => $this->privilages,
            'types' => $this->types,
            'count' => $count,
            'club_id'   => $club_id,
            'show_condition' => array(
                'club_id' => $club_id,
                'stime' => $stime_1,
                'etime' => $etime_1,
                'uid' => $uid,
            )
        );
        $this->load->view('mon_club_points', $data);
    }

}
