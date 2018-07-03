<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->game_db = $this->load->database('game', TRUE);
        $this->db = $this->load->database('default', TRUE);
        $this->load->model('user_m');
//        $this->load->model('mysql_model');
        $this->__user_info = $this->session->userdata('user_info');
        $this->role_names = $this->config->item('role_names');
        $this->output->enable_profiler(false);
    }

    public function player_invite() {
        $recommet_agent_id = trim($_GET['uid']);
        $redirect_uri = get_url("/index.php/Welcome/player_qcode_download/{$recommet_agent_id}");
        $appid = $this->config->item('appid');
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";
        header("Location:$url"); // 跳到玩家管理页面
    }

    public function get_token() {
        $appid = $this->config->item('appid');
        $appkey = $this->config->item('appkey');
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appkey}";
        $sql = "select * from mg_token";
        $exist = $this->db->query($sql)->row_array();
        if (isset($exist)) {
        //如果存在数据
            //如果数据已过期
            if ($exist['expires'] + $exist['update_time'] < time()) {
                $time = time();
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_HEADER, 0);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //这个是重点。
                $data_token = curl_exec($curl);
                curl_close($curl);
                $token = json_decode($data_token, true);
                $sql = "update mg_token set access_token = '{$token['access_token']}',update_time = '{$time}' where access_token = '{$exist['access_token']}'";
                $this->db->query($sql);
                $access_token = $token['access_token']; //返回更新的token
            } else {
                $access_token = $exist['access_token']; //返回查询的token
            }
        }
        //如果没有数据
        else {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //这个是重点。
            $data_token = curl_exec($curl);
            curl_close($curl);
            $token = json_decode($data_token, true);
            $sql = "insert into mg_token values(1,'{$token['access_token']}',300," . time() . ")";
            $this->db->query($sql);
            $access_token = $token['access_token']; //返回新建的token
        }
        return $access_token; //返回access_token
    }

    /* public function get_token()
      {
      $appid = $this->config->item('appid');
      $appkey = $this->config->item('appkey');
      $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appkey}";
      $sql = "select * from mg_token";
      $exist = $this->db->query($sql)->row_array();
      if(isset($exist))
      //如果存在数据
      {
      //如果数据已过期
      if($exist['expires']+$exist['update_time']<time()){
      $time = time();
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_HEADER, 0);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //这个是重点。
      $data_token = curl_exec($curl);
      curl_close($curl);
      $token = json_decode($data_token, true);
      $sql = "update mg_token set access_token = '{$token['access_token']}',update_time = '{$time}' where access_token = '{$exist['access_token']}'";
      $this->db->query($sql);
      $access_token = $token['access_token']; //返回更新的token
      } else {
      $access_token = $exist['access_token']; //返回查询的token
      }
      }
      //如果没有数据
      else {
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_HEADER, 0);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //这个是重点。
      $data_token = curl_exec($curl);
      curl_close($curl);
      $token = json_decode($data_token, true);
      $sql = "insert into mg_token values(1,'{$token['access_token']}',{$token['expires_in']},".time().")";
      $this->db->query($sql);
      $access_token = $token['access_token']; //返回新建的token
      }
      return $access_token; //返回access_token
      } */

    public function player_qcode_download() {
        $recomment_agent_id = $this->uri->segment(3, 1);
        $code = trim($_GET['code']);
        if (!empty($code)) {
            $appid = $this->config->item('club_appid');
            $appkey = $this->config->item('club_appkey');
            //$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$appkey}&code={$code}&grant_type=authorization_code";
            //$info = json_decode(file_get_contents($url), true);

            $url = sprintf('https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code', $appid, $appkey, $code);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $tmp = curl_exec($ch);
            curl_close($ch);
            if (!$tmp) {
                alert('拉取微信失败');
                header("Location:" . $this->config->item('download_url') . "?v=" . date('mdHi')); //跳到玩家管理页面
                return;
            }
            $access_token = json_decode($tmp, true);
            if (isset($access_token['errcode'])) {
                alert('获取token失败');
                header("Location:" . $this->config->item('download_url') . "?v=" . date('mdHi')); //跳到玩家管理页面
                return;
            }
            $userinfo_url = sprintf("https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s", $access_token['access_token'], $access_token['openid']);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $userinfo_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $tmp_info = curl_exec($ch);
            curl_close($ch);
            if (!$tmp_info) {
                alert('获取用户信息失败');
                header("Location:" . $this->config->item('download_url') . "?v=" . date('mdHi')); //跳到玩家管理页面
                return;
            }
            $info = json_decode($tmp_info, true);
            if (!empty($info['unionid'])) {
                $unionid = $info['unionid'];
                //查找是否存在
                $sql = "select * from user where account = '{$unionid}'";
                $user = $this->game_db->query($sql)->row_array();
                if (!empty($user) && $user['invite_id']==0) {
                    $this->game_db->update('user',array('invite_id'=>$recomment_agent_id),array('account'=>$unionid));
                    header("Location:" . $this->config->item('download_url') . "?v=" . date('mdHi')); //跳到玩家管理页面
                    return;
                }

                $sql = "select * from recommend where account = '{$unionid}'";
                $recommend = $this->game_db->query($sql)->row_array();
                if (!empty($recommend)) {
                    alert('已绑定邀请码');
                    header("Location:" . $this->config->item('download_url') . "?v=" . date('mdHi')); //跳到玩家管理页面
                    return;
                }

                $agent_id = 0;
                //查询代理
                $sql = "select * from mg_user where mg_user_id = {$recomment_agent_id}";
                $mg_user = $this->db->query($sql)->row_array();

                if (!empty($mg_user)) {
                    $agent_id = $recomment_agent_id;
                } else {
                    //查用代理
                    $sql = "select * from user where uid = {$recomment_agent_id}";
                    $user = $this->game_db->query($sql)->row_array();
                    if ($user['uid'] > 0) {
//                        $agent_id = $user['uid'];
                        $agent_id = $user['invite_id'];
                    }
                }
                $data = array(
                    'account' => $unionid,
                    'recommend_id' => $recomment_agent_id,
                    'agent_id' => $agent_id,
                    'create_time' => time(),
                );
                $this->game_db->insert('recommend', $data);
            }
        }
        alert('获取code失败');
        header("Location:" . $this->config->item('download_url') . "?v=" . date('mdHi')); //跳到玩家管理页面
    }

    /*
     * 流程优化，即使用户失败，也可以看到正常页面
     * 需要把club_id和recomment_agent_id传递进invite();
     * */
    public function club_invite()
    {
        $recomment_agent_id = $this->uri->segment(3, 1);
        $code = trim($_GET['code']);
        $clubid = trim($_GET['clubid']);
        if (!empty($code)) {
            $appid = $this->config->item('club_appid');
            $appkey = $this->config->item('club_appkey');
            $url = sprintf('https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code', $appid, $appkey, $code);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $tmp = curl_exec($ch);
            curl_close($ch);
            if (!$tmp) {
                header("Location:" . site_url('Welcome/invite')."?invite_id=".$recomment_agent_id."&club_id=".$clubid); //跳到玩家管理页面
                return;
            }
            $access_token = json_decode($tmp, true);
            if (isset($access_token['errcode'])) {
                header("Location:" . site_url('Welcome/invite')."?invite_id=".$recomment_agent_id."&club_id=".$clubid); //跳到玩家管理页面
                return;
            }
            $userinfo_url = sprintf("https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s", $access_token['access_token'], $access_token['openid']);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $userinfo_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $tmp_info = curl_exec($ch);
            curl_close($ch);
            if (!$tmp_info) {
                header("Location:" . site_url('Welcome/invite')."?invite_id=".$recomment_agent_id."&club_id=".$clubid); //跳到玩家管理页面
                return;
            }
            $info = json_decode($tmp_info, true);
            if (!empty($info['unionid'])) {
                $unionid = $info['unionid'];
                header("Location:" . site_url('Welcome/invite')."?account=".$unionid."&invite_id=".$recomment_agent_id."&club_id=".$clubid);
                return;
            }
        }
        header("Location:" . site_url('Welcome/invite')."?invite_id=".$recomment_agent_id."&club_id=".$clubid); //跳到玩家管理页面
    }

    public function invite()
    {
        $data = array();
        if(isset($_GET['invite_id'])){
            $invite_id = trim($_GET['invite_id']);
            $sql = "select * from user WHERE uid = ".$invite_id;
            $result = $this->game_db->query($sql)->row_array();
            $invite_name = $result['name'];
            $invite_head = $result['head'];
            $data['invite_id'] = $invite_id;
            $data['invite_name'] = $invite_name;
            $data['invite_head'] = $invite_head;
        }

        $club_name = "";
        if(isset($_GET['club_id'])){
            $club_id = trim($_GET['club_id']);
            $sql = "select * from clubs where id=".$club_id;
            $result = $this->game_db->query($sql)->row_array();
            $club_name = $result['club_name'];
            $data['club_id'] = $club_id;
            $data['club_name'] = $club_name;
        }

        if(isset($_GET['account'])){
            $account = trim($_GET['account']);
            $data['account'] = $account;
        }else{
            $this->load->view('mon_club_invite.php',$data);
            alert('请先注册');
            return;
        }

        $sql = "select * from user where account='{$account}'";
        $user = $this->game_db->query($sql)->row_array();
        if(empty($user)){//没有注册
            $this->load->view('mon_club_invite.php',$data);
            alert('请先注册');
            return;
        }

        $data['uid'] = $user['uid'];
        $this->load->view('mon_club_invite.php',$data);
    }

    public function ajax_club_invite()
    {
        if ($_POST) {
            $invite_id = (int)trim($_POST['invite_id']);
            $club_id = (int)trim($_POST['club_id']);
            $uid = (int)trim($_POST['uid']);
            $account = $_POST['account'];
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            socket_connect($socket, $this->config->item('GMIP'), $this->config->item('GMPORT'));
            $arr = array(
                'cmd' => 23,
                'uid' => $uid,
                'invite_id' => $invite_id,
                'club_id' => $club_id,
                'account' =>$account,
            );
            $str = 'PHP8'.json_encode($arr);
            $size = strlen($str);
            $binary_str = pack("na" . $size, $size, $str);
            socket_write($socket, $binary_str, strlen($binary_str));
            $buf = socket_read($socket, 2048);
            $a = json_decode(substr($buf, 2),true);
            socket_close($socket);
            echo json_encode($a);
        }
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
        //$url = get_url('/index.php/Welcome/view_cart');
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
//
//        if (!$v_captcha) {
//            $this->Json(false, '请输入验证码');
//        }
        // 判断验证码是否正确
//        $code = strtolower($this->session->userdata('code'));
//        if (strtolower($v_captcha) != $code) {
//            $this->Json(false, '请输入正确的验证码');
//        }

        $query = $this->db->get_where('mg_user', [
            'mg_user_name' => $v_username,
            'mg_user_pwd' => md5($v_password),
            'status' => 1
        ]);

        $user = $query->row_array();

        if (empty($user)) {
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
    public function view_upPasswrod() {
        $this->is_login();
        $temp['footer'] = $this->config->item('footer');
        $this->load->view('mon_pwd', $temp);
        //$this->load->view('index_modifyPassword');
    }

    public function ajax_upPassword() {
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

    // --------------------------------------------------
    
    public function agree_privary() {
        $mgUserId = intval($this->__user_info['mg_user_id']);
        $agree_time = date('Y-m-d H:i:s');
        $sql = "update mg_user set agree_privary = 1,agree_time = '{$agree_time}' where mg_user_id ={$mgUserId} ";
        $this->db->query($sql);
        echo json_encode(array('status' => 0));
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
            $where_str .= ' and b.invite_id in (' . $uids . ')';
        }
        $sql2 = "select count(a.charge_id) as count,sum(a.amount) as total_money from charge as a left join user as b on a.uid = b.uid where a.status = 1 " . $where_str;
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
        $this->load->view('mon_menu', $temp);
    }

    public function mon_cl() {
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
            $where_str2 = ' and b.invite_id in (' . $uids . ')';
        }
        //查询充值总额

        $sql2 = "select count(a.charge_id) as count,sum(a.amount) as total_money from charge as a left join user as b on a.uid = b.uid where a.status = 1 " . $where_str . $where_str2;
        $total_info = $this->game_db->query($sql2)->row_array();

        //我直接下线
        $where_str3 = ' and b.invite_id =' . $current_user_id;
        $sql2 = "select count(a.charge_id) as count,sum(a.amount) as total_money from charge as a left join user as b on a.uid = b.uid where a.status = 1 " . $where_str . $where_str3;
        $xiaxian = $this->game_db->query($sql2)->row_array();

        $temp['total_money'] = intval($total_info['total_money'] / 100);
        $temp['xiaxian'] = intval($xiaxian['total_money'] / 100);
        $temp['daili'] = $temp['total_money'] - $temp['xiaxian'];
        $temp['show_condition'] = array(
            'stime' => $stime,
            'etime' => $etime,
        );
//        
//        if($wherestr2 != '') {
//            $sql2 = "select a.*,b.mg_user_id,b.level from mg_user_charge as a left join mg_user as b on a.user_id = b.mg_user_id where a.active = 1 " . $wherestr . $wherestr2;
//            $lists = $this->db->query($sql2)->result_array();
//        }else{
//            $lists = array();
//        }
//        
//        
//        $sql3 = "select * from mg_products ";
//        $tmps = $this->db->query($sql3)->result_array();
//        $products = array();
//        foreach($tmps as $tmp) {
//            $products[$tmp['pid']] = $tmp;
//        }
//        
//        $total = 0;
//        foreach($lists as $k => $li) {
//            //计算返利
//            if($info['level']>=4) {
//                $lists[$k]['fan'] = 0;
//            }else{
//                if($info['level'] == 2) {
//                    $fan = $products[$li['product_id']]['num'] * 0.1;
//                    $total += $fan;
//                    $lists[$k]['fan'] = $fan;
//                }elseif($info['level'] == 3) {
//                    $fan = $products[$li['product_id']]['num'] * 0.2;
//                    $total += $fan;
//                    $lists[$k]['fan'] = $fan;
//                }else{
//                    $lists[$k]['fan'] = 0;
//                }
//            }
//        }
//        $temp['total'] = $total;

        $this->load->view('mon_cl', $temp);
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
     * 下线
     */
    public function mon_ulistb() {
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
        $where_str2 = ' and invite_id = ' . $mgUserId;
        $sql2 = "select a.*,b.name from user as b left join charge as a  on a.uid = b.uid where a.status = 1 " . $where_str . $where_str2;
        $datas = $this->game_db->query($sql2)->result_array();
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
                $tmp = ' and b.invite_id in (' . $uids_str . ')';
                $sql2 = "select count(a.charge_id) as count,sum(a.amount) as total_money from charge as a left join user as b on a.uid = b.uid where a.status = 1 " . $where_str . $tmp;
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
        $this->load->view('mon_ulistb', $temp);
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
            $sql2 = "select a.*,b.mg_user_id,b.level from mg_user_charge as a left join mg_user as b on a.user_id = b.mg_user_id where a.active = 1 " . $wherestr . $tmp;
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
//        $uids = $this->user_m->getInvoteStr($this->__user_info['mg_user_id']);
//        $uids_list = explode(',', $uids);
//        if (!in_array($mgUserId, $uids_list)) {
//            $mgUserId = $this->__user_info['mg_user_id'];
//        }
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

    public function mon_clist() {
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

        $where_str .= " and b.invite_id = " . $mgUserId;

        $temp['mgid'] = $mgUserId;
        $sql2 = "select a.*,b.name from charge as a left join user as b on a.uid = b.uid where a.status = 1 " . $where_str;
        $list = $this->game_db->query($sql2)->result_array();

        $temp['clist'] = $list;
        $this->load->view('mon_clist', $temp);
    }

    private function uinfo($id) {
        $sql = "select `mg_name`,`level`,`invotecode` from `mg_user` where mg_user_id={$id}";
        $row = $this->mysql_model->query($sql, 1);
        $info['uname'] = $row['mg_name'];
        $info['level'] = $row['level'];
        $info['icode'] = $row['invotecode'];
        $info['agree_privary'] = $row['agree_privary'];
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

    private function mcount($id, $stime = null, $etime = null) {
        $mall['a_all'] = 0;
        $mall['clist'] = array();
        if (isset($stime)) {
            $start = strtotime($stime);
        } else {
            $start = strtotime(date('Y-m-d', time()));
        }
        if (isset($etime)) {
            $end = strtotime($etime) + 24 * 60 * 60;
        } else {
            $end = strtotime(date('Y-m-d', time())) + 24 * 60 * 60;
        }
        $sql = "select mg_user_id,level from `mg_user` where p_mg_user_id={$id}";
        $row = $this->mysql_model->query($sql, 2);
        foreach ($row as $l) {
            $ul[] = $l['mg_user_id'];
        }
        if (isset($ul) and is_array($ul)) {
            $ul_id = implode(',', $ul);
            $sql = "select sum(amount)/100 as icount from `charge` where uid in ({$ul_id}) and `status` =1 and `create_time`>={$start} and `create_time`<{$end}";
            $row = $this->game_db->query($sql, 1)->row_array();
            $mall['a_all'] = $row['icount'];
            $sql = "select uid,charge_id,amount/100 as mmount from `charge` where uid in ({$ul_id}) and `status` =1 and `create_time`>={$start} and `create_time`<{$end}";
            $mall['clist'] = $this->game_db->query($sql, 2)->result_array();
        }
        $mall['t_all'] = $mall['a_all'];
        return $mall;
    }

    /* Json 相关 */

    private function Json($v_status, $v_msg, $v_array = array()) {
        $out_array = array(
            'status' => $v_status,
            'msg' => $v_msg
        );
        die(json_encode(array_merge($out_array, $v_array)));
    }

    //post
    private function http($url, $data = NULL, $json = false) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if (!empty($data)) {
            if ($json && is_array($data)) {
                $data = json_encode($data);
            } else {
                $data = http_build_query($data);
            }
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            if ($json) { // 发送JSON数据
                curl_setopt($curl, CURLOPT_HEADER, 0);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json; charset=utf-8',
                    'Content-Length:' . strlen($data)
                ));
            }
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($curl);
        $errorno = curl_errno($curl);

        if ($errorno) {
            return array(
                'errorno' => false,
                'errmsg' => $errorno
            );
        }
        curl_close($curl);

        return json_decode($res, true);
    }

    public function view_content() {
        $this->is_login();
        $this->load->view('content');
    }

    public function weixin()
    {
        //数据初始化
        $signature=$_GET['signature'];//微信加密签名，signature结合了开发者填写的token参数和请求中的timestamp参数、nonce参数。
        $timestamp=$_GET['timestamp'];//时间戳
        $nonce=$_GET['nonce'];//随机数
        $echostr=$_GET['echostr'];//随机字符串
        $token="sudagame";

        //校验
        //1.将token、timestamp、nonce三个参数进行字典序排序
        $tmpArr=array($token,$timestamp,$nonce);
        sort($tmpArr,SORT_STRING);

        //2.将三个参数字符串拼接成一个字符串进行sha1加密
        $tmpStr=sha1(implode($tmpArr));

        //3.开发者获得加密后的字符串可与signature对比，标识该请求来源于微信
        if($tmpStr==$signature){
            echo $echostr;
        }else{
            echo false;
        }
    }

}
