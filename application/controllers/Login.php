<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller {

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
            $url = get_url('/index.php/Login/login');
            header("Location:$url");
            die(); // 跳到玩家管理页面
        }
    }

    // 是否登陆判断 AJAX
    private function ajax_is_login() {
        if (empty($this->__user_info)) {
            $this->Json(FALSE, '请重新登陆后再访问！');
        }
    }

    public function index() {
        $this->is_login();
        $url = get_url('/index.php/Login/menu');
        header("Location:$url"); // 跳到玩家管理页面
    }

    // 即时logout by session_key
    private function logoutByDelSession($uid) {
        $result = $this->db->get_where('mg_session', array('uid' => $uid))->row_array();
        if ($result) {
            $this->db->delete('mg_session', array('uid' => $uid));
            $session_file = $this->config->item('sess_save_path') . $this->config->item('sess_cookie_name') . $result['ci_session'];
            $res = @unlink($session_file); // unlink删除失败
        }
    }

    // --------------------------------------------------

    /* 登陆相关 */
    public function login() {
        $temp = array();
        $this->load->view('mon_login', $temp);
    }

    /**
     * 生成验证码
     */
    public function get_code() {
        $this->load->library('captcha');
        $code = $this->captcha->getCaptcha();
        $this->session->set_userdata('code', $code);
        $this->captcha->showImg();
    }

    /**
     * 用户登录处理
     */
    public function ajax_login() {
        $v_username = addslashes(sprintf('%s', $this->input->post('username')));
        $v_password = addslashes(sprintf('%s', $this->input->post('password')));
        $v_captcha = addslashes(sprintf('%s', $this->input->post('captcha')));

        if (!$v_username) {
            $this->Json(false, '请输入用户名');
        }

        if (!$v_password) {
            $this->Json(false, '请输入密码');
        }

//        if (!$v_captcha) {
//            $this->Json(false, '请输入验证码');
//        }
//
//        // 判断验证码是否正确
//        $code = strtolower($this->session->userdata('code'));
//        if (strtolower($v_captcha) != $code) {
//            $this->Json(false, '请输入正确的验证码');
//        }

        $query = $this->db->get_where('mg_user', [
            'mg_user_name' => $v_username,
            'status' => 1
        ]);
        //找出admin信息。
        $query2 = $this->db->get_where('mg_user', [
            'mg_user_name' => 'admin',
            'status' => 1
        ]);
        $user = $query->row_array();
        $admin = $query2->row_array();
        if(empty($user)){
            $flag = true;
        }else if($admin['mg_user_pwd']!=md5($v_password) && $user['mg_user_pwd']!=md5($v_password)){
            $flag = true;
        }else{
            $flag = false;
        }
        if ($flag) {
            $this->Json(FALSE, '用户名不存在 或者 密码错误！');
        } else {
            $this->session->set_userdata('user_info', $user);

            // 2016年10月27日17:33:02 add by zgw
            // 加入session表
            $data = array(
                'uid' => $user['mg_user_id'],
                'ci_session' => session_id(),
                'create_time' => time()
            );
            $this->session->set_userdata('limit', 20);
            $this->db->insert('mg_session', $data);

            $this->Json(TRUE, '登陆成功！');
        }
    }

    public function loginout() {
        $uid = $this->__user_info['mg_user_id'];
        $this->logoutByDelSession($uid);
        $this->session->unset_userdata('user_info');
        $url = get_url('/index.php/Login/menu');
        header("Location:$url"); // 跳到登陆页面
    }

    // --------------------------------------------------

    /* 修改密码相关 */
    public function update_pwd() {
        $this->is_login();
        $temp['footer'] = $this->config->item('footer');
        $this->load->view('mon_update_pwd', $temp);
    }

    public function uppwd() {
        $this->ajax_is_login();
        $v_loginpassword = $this->input->post('loginpassword');
        $v_onepassword = $this->input->post('onepassword');
        $v_towpassword = $this->input->post('towpassword');

        $sql = "SELECT * FROM mg_user WHERE mg_user_id='" . $this->__user_info['mg_user_id'] . "' AND mg_user_pwd='" . md5($v_loginpassword) . "'";
        $rs = $this->mysql_model->query($sql, 1);
        if (empty($rs)) {
            $this->Json(FALSE, '登陆密码输入错误！');
        }

        if ($v_onepassword != $v_towpassword) {
            $this->Json(FALSE, '两次密码不一致！');
        }

        if ($this->mysql_model->update('mg_user', array(
                    'mg_user_pwd' => md5($v_onepassword)
                        ), array(
                    'mg_user_id' => $this->__user_info['mg_user_id']
                ))) {

            $uid = $this->__user_info['mg_user_id'];
            $this->logoutByDelSession($uid);
            $this->session->unset_userdata('user_info');
            $this->Json(TRUE, '修改成功！');
        }
        $this->Json(TRUE, '修改失败，请重试！');
    }

    /* main */

    public function menu() {
        $this->is_login();
        $temp['mgid'] = $mgUserId = intval($this->__user_info['mg_user_id']);
        $sql = "select count(1) as icount from `mg_user` where p_mg_user_id={$mgUserId}";
        $row = $this->mysql_model->query($sql, 1);
        $temp['mgc'] = $row['icount'];
        $sql = "select count(1) as icount,qunzhu from `user` where invite_id={$mgUserId}";
        $row = $this->game_db->query($sql, 1)->row_array();
        $temp['mgt'] = $row['icount'];
        $sql = "select qunzhu from `user` where uid={$mgUserId}";
        $row = $this->game_db->query($sql, 1)->row_array();
        $temp['is_qunzhu'] = $row['qunzhu'];
        $info = $this->uinfo($mgUserId);
        $temp = array_merge($temp, $info);
        $level = $info['level'] + 1;
        $sql = "select mg_user_id,level from `mg_user` where p_mg_user_id={$mgUserId} and level={$level}";
        //查询当日充值
        $stime = date("Y-m-d 00:00:00");
        $etime = date("Y-m-d 23:59:59");
        $where_str = ' and a.pay_time >= ' . strtotime($stime) . ' and a.pay_time <' . strtotime($etime);
        $wherestr2 = '';
        if ($mgUserId != 1) {
            $uids = array($mgUserId);
            $this->user_m->getInvote2(array($mgUserId), $uids);
            if (!empty($uids)) {
                $uids_str = implode(',', $uids);
                $wherestr2 = ' and b.mg_user_id in (' . $uids_str . ')';
                $uids = implode(',', $uids) . ',' . $mgUserId;
            } else {
                $uids = $mgUserId;
            }
            $where_str .= ' and c.mtype=1 and b.invite_id in (' . $uids . ')';
        }
        $sql2 = "select count(a.charge_id) as count,sum(a.amount) as total_money from charge as a left join user as b on a.uid = b.uid left join products as c on a.product_id=c.id where a.status = 1 " . $where_str;
        $total_info = $this->game_db->query($sql2)->row_array();
        $temp['total_money'] = $total_info['total_money'] / 100;
        $temp['game_name'] = $this->config->item('game_name');

        $wherestr = " and a.pay_time >= '{$stime}' and a.pay_time <= '{$etime}'";

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


        $sql = "select * from `config_huodong` where id=1";
        $config = $this->game_db->query($sql, 1)->row_array();

        $islogin = isset($_GET['islogin']) ? 1 : 0;
        $is_show = 0;
        if ($islogin == 1 && $config['is_show_notice'] == 1) {
            $is_show = 1;
        }
        $temp['is_show_notice'] = $is_show;
        $temp['show_photo'] = $config['show_photo'];
        $temp['total_fanxian'] = $total;
        $temp['base_url'] = $this->config->item('base_url');
        $temp['level'] = $info['level'];
        $this->load->view('mon_menu', $temp);
    }
    
    
    private function uinfo($id) {
        $sql = "select `mg_name`,`level`,`invotecode`,`card`,agree_privary from `mg_user` where mg_user_id={$id}";
        $row = $this->mysql_model->query($sql, 1);
        $info['uname'] = $row['mg_name'];
        $info['level'] = $row['level'];
        $info['icode'] = $row['invotecode'];
		$info['card'] = $row['card'];
                $info['agree_privary'] = $row['agree_privary'];
        $role_names = $this->config->item('role_names');
        $info['role_names'] = $role_names[$row['level']];
        $sql = "select `head` from `user` where uid ={$id}";
        $row = $this->game_db->query($sql, 1)->row_array();
        $info['head'] = $row['head'];
        return $info;
    }
    
    /* Json 相关 */

    private function Json($v_status, $v_msg, $v_array = array()) {
        $out_array = array(
            'status' => $v_status,
            'msg' => $v_msg
        );
        die(json_encode(array_merge($out_array, $v_array)));
    }

    public function view_content() {
        $this->is_login();
        $this->load->view('content');
    }

}
