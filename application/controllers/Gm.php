<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 本页复制来自广播，请按需要更改变量名
 */
class Gm extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('activity_model');
        if (!isset($_SESSION['user_info']) && empty($_SESSION['user_info'])) {
            show_error('未登录');
            return false;
        }
        $this->db = $this->load->database('default', TRUE);
        $this->game_db = $this->load->database('game', TRUE);
        $login_uid = $_SESSION['user_info']['mg_user_id'];
        if($login_uid != 1 && $login_uid != 2) {
            die("没有权限");
        }
        $this->load->model('user_m');
    }

    public function agent_query() {
        $data = array();
        $this->load->view('mon_agent_query.php', $data);
    }
    
    public function charge_pwd() {
        $mgUserId = isset($_POST['uid']) ? $_POST['uid'] : 0;
        $pwd = isset($_POST['pwd']) ? $_POST['pwd'] : '';
        if(empty($pwd)) {
            die($re = array('status'=>1));
        }
        $pwd = md5($pwd);
        $sql = "update mg_user set mg_user_pwd = '{$pwd}' where mg_user_id = {$mgUserId}";
        $this->db->query($sql);
        $re = array('status'=>0);
        die(json_encode($re));
        
    }
    
    public function clean_agent() {
        $mgUserId = isset($_POST['uid']) ? $_POST['uid'] : 0;
        // 查找下线个数
        $sql = "select count(*) as num from mg_user where p_mg_user_id = ".$mgUserId;
        $info = $this->db->query($sql)->row_array();
        if($info['num'] != 0) {
            $re = array('status'=>1);
            die(json_encode($re));
        }
        //查找绑定玩家
        $sql = "select count(*) as num from user where invite_id = ".$mgUserId;
        $info = $this->game_db->query($sql)->row_array();        
        if($info['num'] != 0) {
            $re = array('status'=>2);
            die(json_encode($re));
        }
        
        $sql = "delete from mg_user where mg_user_id = {$mgUserId}";
        $this->db->query($sql);
        
        $re = array('status'=>0);
        die(json_encode($re));
    }
    
    public function history_yeji() {
        $mgUserId = isset($_POST['uid']) ? $_POST['uid'] : 0;
        $where_str = '';
        if ($mgUserId != 1) {
            $uids = array($mgUserId);
            $this->user_m->getInvote2(array($mgUserId),$uids);
            if(!empty($uids)) {
                 $uids = implode(',', $uids) . ',' . $mgUserId;
            }else{
                $uids = $mgUserId;
            }
            $where_str .= ' and b.invite_id in (' . $uids . ')';
            
        }
        $sql2 = "select count(a.charge_id) as count,sum(a.amount) as total_money from charge as a left join user as b on a.uid = b.uid where a.status = 1 " . $where_str;
        $total_info = $this->game_db->query($sql2)->row_array();
        
        $re = array('total_money'=>$total_info['total_money']/100);
        echo json_encode($re);
    }
    
    public function day15_yeji() {
        $mgUserId = isset($_POST['uid']) ? $_POST['uid'] : 0;
        $where_str = ' and a.pay_time >= ' . strtotime('-15 day') . ' and a.pay_time <' . time();
        if ($mgUserId != 1) {
            $uids = array($mgUserId);
            $this->user_m->getInvote2(array($mgUserId),$uids);
            if(!empty($uids)) {
                $uids = array_unique($uids);
                 $uids = implode(',', $uids) . ',' . $mgUserId;
            }else{
                $uids = $mgUserId;
            }
            $where_str .= ' and b.invite_id in (' . $uids . ')';
            
        }
        $sql2 = "select count(a.charge_id) as count,sum(a.amount) as total_money from charge as a left join user as b on a.uid = b.uid where a.status = 1 " . $where_str;
        $total_info = $this->game_db->query($sql2)->row_array();
        
        $re = array('total_money'=>$total_info['total_money']/100);
        echo json_encode($re);
    }
    
    public function upate_invotecode() {
        $uid = isset($_POST['uid']) ? $_POST['uid'] : 0;
        $code = isset($_POST['code']) ? $_POST['code'] : 0;
        $re = array('status' => 0);
        if (!empty($uid) && !empty($code)) {
            //查询是否存在
            $sql = "select mg_user_id from mg_user where invotecode = {$code}";
            $record = $this->db->query($sql)->row_array();
            if(empty($record)) {
                $sql = "update mg_user set invotecode = {$code} where mg_user_id = {$uid}";
                $this->db->query($sql);
            }else{
                $re = array('status' => 1);
            }
        }
        echo json_encode($re);
    }
    
    public function agent_list() {
       
        $where_str = '';
        $user_id = $this->uri->segment(3, 0);
        if(isset($_POST['user_id']) && $_POST['user_id'] > 0){
            $user_id = $this->input->post('user_id');
        }
        if($user_id>0) {
            $where_str .= " and mg_user_id = {$user_id}";
        }
        
        //获得列表
        $current = $this->uri->segment(4, 1); // 页数
        $limit = $limit = $this->session->userdata('limit');;
        $start = ($current - 1) * $limit;
        $sql = "select * from mg_user where 1=1 ".$where_str. " order by mg_user_id asc limit ".$start.", ".$limit;
        $list = $this->db->query($sql,2)->result_array();
        $sql2 = "select count(*) as count from mg_user where 1=1 ".$where_str;
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
            'list'                  => $list,
            'start'                 => $start,
            'page_link'             => $page_link,
            'role_names'            => $this->config->item('role_names'),
            'count'                 => $count,
            'show_condition'      => array(
                'user_id' => $user_id,
                )
            );
        $this->load->view('mon_agent_list', $data);
    }

    public function ajax_docharge() {
        $charge_id = isset($_POST['charge_id']) ? $_POST['charge_id'] : 0;
        $re = array('status' => 0);
        if ($charge_id) {
            //上线
            $sql = "update charge set status = 1 where charge_id =" . $charge_id;
            $this->game_db->query($sql);
            $re = array('status' => 1);
        }
        echo json_encode($re);
    }

    public function ajax_invotecode_query() {
        $code = isset($_POST['code']) ? $_POST['code'] : 0;
        $data['query_info'] = '未查到相关信息';
        if ($code) {
            //上线
            $list = array();
            $sql = "select mg_user_id,level,invotecode from mg_user where invotecode like '%{$code}%'";
            $lists = $this->db->query($sql)->result_array();
            if (!empty($lists)) {
                $str = '';
                foreach ($lists as $li) {
                    $str .= sprintf("代理id:%d,等级:%d,邀请码:%s<br/>", $li['mg_user_id'], $li['level'], $li['invotecode']);
                }
                $data['query_info'] = $str;
            }
        }
        echo json_encode($data);
    }

    public function ajax_agent_query() {
        $agent_id = isset($_POST['agent_id']) ? $_POST['agent_id'] : 0;
        $data['query_info'] = '未查到代理信息';
        if ($agent_id) {
            //上线
            $list = array();
            $sql = "select mg_user_id,p_mg_user_id,level from mg_user where mg_user_id = " . $agent_id;
            $tmp = $this->db->query($sql)->row_array();
            if (!empty($tmp)) {
                $list[] = array('id' => $tmp['mg_user_id'], 'level' => $tmp['level']);
                while (true) {
                    $sql = "select mg_user_id,level,p_mg_user_id from mg_user where mg_user_id = " . $tmp['p_mg_user_id'];
                    $tmp = $this->db->query($sql)->row_array();
                    if (!empty($tmp)) {
                        array_unshift($list, array('id' => $tmp['mg_user_id'], 'level' => $tmp['level']));
                    } else {
                        break;
                    }
                }
                $str = '';
                foreach ($list as $li) {
                    $str .= sprintf(" %d(%d) --> ", $li['id'], $li['level']);
                }
                $data['query_info'] = $str;
            }
        }
        echo json_encode($data);
    }

}
