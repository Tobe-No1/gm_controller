<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Charge extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->game_db = $this->load->database('game', TRUE);
        $this->__user_info = $this->session->userdata('user_info');
    }

    // 是否登陆判断
    private function is_login() {
        if (empty($this->__user_info)) {
            $url = get_url('/index.php/Login/menu');
            header("Location:$url");
            die(); // 跳到玩家管理页面
        }
    }

    public function view_charge_ph() {
        $this->is_login();
        $this->load->database();
        $userInfo = $this->session->userdata('user_info');
        $mg_user_id = intval($userInfo['mg_user_id']);

        $query = $this->db->get_where('mg_user', [
            'mg_user_id' => $mg_user_id
        ]);

        // 用户信息
        $user = $query->row_array();

        // 获取房卡数量
        $sql = "SELECT * FROM mg_products WHERE active =1 ";
        $query1 = $this->db->query($sql, 1);
        $list = $query1->result();

        // 余卡查询
        $query3 = $this->db->get_where('mg_user_account_props', [
            'mg_user_id' => intval($user['mg_user_id'])
        ]);
        $user_account_props = $query3->row_array();
        $arr = $query1->result_array();
        $this->load->view('mon_charge_yh', ['list' => $arr, 'mg_user_account_props' => $user_account_props]);
    }

    //支付
    public function pay_ph() {
        $userInfo = $this->session->userdata('user_info');
        $mg_user_id = intval($userInfo['mg_user_id']);

        $pid = intval($_GET['pid']);
        $sql = 'SELECT * FROM mg_products WHERE pid =' . $pid;
        $query1 = $this->db->query($sql, 1);
        $charge = $query1->row_array();
        $u_ip = $this->input->ip_address();
        $trans_id = $this->makeTransId($pid, $mg_user_id, $charge['price'], $u_ip);

        $data = array(
            'spid' => $this->config->item('appid'),
            'orderid' => $trans_id,
            'mz' => $charge['price'],
            'uid' => $mg_user_id,
            'spsuc' => $this->config->item('returnUrl'),
            'ordertype' => 2,
            'interfacetype' => 4,
            'productname' => $charge['name'],
        );

        $sign_str = $data['spid'] . $data['orderid'] . $this->config->item('appkey') . $data['uid'] . $data['spsuc'] . $data['ordertype'] . $data['interfacetype'];
        $data['sign'] = strtoupper(md5($sign_str));
        echo "<form style=\"display: none;\" action=\"http://dsfzf.vnetone.com/createorder/index\" method=\"get\" id=\"payform\">";
        foreach ($data as $k => $v) {
            echo "<input type=\"hidde\" name=\"$k\" value=\"{$v}\" >";
        }
        echo "<input type=\"submit\" value=\"提交\" >" .
        "</form>" .
        "<script type=\"text/javascript\">" .
        "document.getElementById('payform').submit();" .
        "</script>";
    }

    public function view_charge() {
        $this->is_login();

        $this->load->database();
        $userInfo = $this->session->userdata('user_info');
        $mg_user_id = intval($userInfo['mg_user_id']);

        $query = $this->db->get_where('mg_user', [
            'mg_user_id' => $mg_user_id
        ]);

        // 用户信息
        $user = $query->row_array();
        $level = $_SESSION['user_info']['level'];

        // 获取房卡数量
        $sql = "SELECT * FROM mg_products WHERE active =1  order by charge_id desc";
        $tmps = $this->db->query($sql, 1)->result_array();
        //$tmps = $query1->row_array();

        $list = array();
        foreach ($tmps as $record) {

            $tmp = true;
            if (!empty($record['mg_level'])) {
                $arr = explode(',', $record['mg_level']);
                if (!in_array($level, $arr)) {
                    $tmp = false;
                }
            }
            if (!empty($record['mg_ids'])) {
                $arr = explode(',', $record['mg_ids']);
                if (!in_array($mg_user_id, $arr)) {
                    $tmp = false;
                }
            }
            if ($tmp == true) {
                $list[] = $record;
            }
        }


        // 余卡查询
        $query3 = $this->db->get_where('mg_user', [
            'mg_user_id' => intval($user['mg_user_id'])
        ]);
        $user = $query3->row_array();

        $this->load->view('mon_charge', ['list' => $list, 'mg_user' => $user]);
    }

    //支付
    public function pay() {
        $userInfo = $this->session->userdata('user_info');
        $mg_user_id = intval($userInfo['mg_user_id']);

        $pid = intval($_GET['pid']);
        $sql = 'SELECT * FROM mg_products WHERE pid =' . $pid;
        $query1 = $this->db->query($sql, 1);
        $charge = $query1->row_array();
        $u_ip = $this->input->ip_address();
        $trans_id = $this->makeTransId($pid, $mg_user_id, $charge['price'], $u_ip);

        $data = array(
            'amount' => $charge['price'] * 100,
            'appid' => $this->config->item('pay_appid'),
            'body' => $charge['desc2'],
            'clientIp' => $u_ip,
            'mchntOrderNo' => $trans_id,
            'notifyUrl' => $this->config->item('notifyUrl'),
            'returnUrl' => $this->config->item('returnUrl'),
            'subject' => $charge['name']
        );

        ksort($data);
        $data['key'] = $this->config->item('pay_appkey');
        $dataArr = [];
        foreach ($data as $k => $v) {
            if ($k != 'signature' and $v != '') {
                $dataArr[] = $k . '=' . $v;
            }
        }
        $data['signature'] = md5(implode('&', $dataArr));
        $dataStr = json_encode($data);
        $this->load->library('mycrypt');
        $jsonEncy = $this->mycrypt->encrypt($dataStr);
        echo "<form style=\"display: none;\" action=\"http://www.palmf.cn/sdk/api/v1.0/cli/order_h5/0\" method=\"post\" id=\"payform\">" .
        "<input type=\"text\" name=\"orderInfo\" value=\"" . $jsonEncy . "\">" .
        "<input type=\"submit\" value=\"提交\" >" .
        "</form>" .
        "<script type=\"text/javascript\">" .
        "document.getElementById('payform').submit();" .
        "</script>";
    }

    /**
     * 获取随机号码
     * @author liuk
     */
    public static function StaticgetCode($length = 32, $mode = 0) {
        switch ($mode) {
            case '1':
                $str = '1234567890';
                break;
            case '2':
                $str = 'abcdefghijklmnopqrstuvwxyz';
                break;
            case '3':
                $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            default:
                $str = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
        }

        $result = '';
        $l = strlen($str) - 1;
        $num = 0;

        for ($i = 0; $i < $length; $i ++) {
            $num = rand(0, $l);
            $a = $str[$num];
            $result = $result . $a;
        }
        return $result;
    }

    public function makeTransId($pid, $mg_user_id, $price, $u_ip) {
        $trans_id = 'N' . $this->StaticgetCode(4) . time();

        $this->mysql_model->insert('mg_user_charge', array(
            'transaction_id' => $trans_id,
            'product_id' => $pid,
            'user_id' => $mg_user_id,
            'rmb' => $price,
            'active' => 0,
            'client_ip' => $u_ip,
        ));

        return $trans_id;
    }

    public function Callback_ph() {
        $data = $_GET;
        $spid = $data['spid'];
        file_put_contents('/tmp/logs-pay-' . date('Y-m-d'), json_encode($data), FILE_APPEND);

        $sign_str = $data['oid'] . $data['sporder'] . $data['spid'] . $data['mz'] . $this->config->item('appkey');
        if (strtoupper(md5($sign_str)) != $data['md5']) {
            file_put_contents('/tmp/logs-pay-' . date('Y-m-d'), 'sign error', FILE_APPEND);
            die('fail');
        }

        $ret = $this->callback_agent($data['sporder'], $data['spid'], $data['mz']);
        if ($ret['code'] == 1) {
            echo 'fail';
        } else {
            echo 'ok';
        }
    }

    public function CallBack_LYNet() {
        $postStr = file_get_contents('php://input');
        $arr = json_decode($postStr, true);

        if (empty($arr)) {
            echo '{"success":false}';
            die();
        }
        $this->insertLogRecord($postStr);

        $amount = $arr['amount'];
        $orderNo = $arr['orderNo'];
        $mchntOrderNo = $arr['mchntOrderNo'];

        $appkey = $this->config->item('pay_appkey');
        $check = $this->checkly($arr, $appkey);
        if (!$check) {
            echo '{"success":false}';
            die();
        }

        $price = $amount / 100;
        $ret = $this->callback_agent($mchntOrderNo, $orderNo, $price, '', '');
        if ($ret['code'] == 1) {
            echo '{"success":false}';
            die();
        } else {
            echo '{"success":true}';
            die();
        }
    }

    public function checkly($params, $app_key) {
        ksort($params);
        $str = '';
        $signature = $params['signature'];
        foreach ($params as $k => $v) {
            if ($k != 'signature' and $v !== '') {
                $str .= $k . '=' . $v . '&';
            }
        }
        $str .= 'key=' . $app_key;
        if ($signature == md5($str)) {
            return true;
        }
        return false;
    }

    public function array_remove_empty(&$arr, $trim = true) {
        if (!is_array($arr))
            return false;
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                self::array_remove_empty($arr[$key]);
            } else {
                $value = ($trim == true) ? trim($value) : $value;
                if ($value == "") {
                    unset($arr[$key]);
                } else {
                    $arr[$key] = $value;
                }
            }
        }
        // var_dump($arr);
        return $arr;
    }

    function callback_agent($trans_id, $order_id, $price) {
        if (strlen($trans_id) == 0) {
            $return['msg'] = '订单为空！';
            $return['code'] = 1;
            return $return;
        }
        $sql_trans = "  SELECT uach.user_id, uact.pid, uact.price, uact.num, uact.name
                         FROM mg_user_charge uach LEFT JOIN mg_products uact ON uach.product_id = uact.pid
                	     where uach.transaction_id = '" . $trans_id . "' and uach.active = 0";
        $ret_trans = $this->mysql_model->query($sql_trans, 1);
        if (!$ret_trans) {
            $return['msg'] = '无此订单！';
            $return['code'] = 1;
            return $return;
        } else {

            $u_id = $ret_trans["user_id"];
            $num = $ret_trans["num"];
            $price = $ret_trans["price"];
            $pid = $ret_trans["pid"];
            $goods_name = $ret_trans["name"];
        }

        //加减卡
        $update_add = 'update mg_user set card = card +' . $num . ' where mg_user_id = ' . $u_id;
        $this->db->query($update_add);

        $proid = 36;
        $sendflag = 4; //4:购(买)卡

        $this->mysql_model->insert('user_props_consume_history', array(
            'props_type_id' => $proid,
            'user_id' => $u_id,
            'count' => $num,
            'flag' => $sendflag
        ));

        $update_2 = "UPDATE mg_user_charge SET active = 1, pay_time = '" . date('Y-m-d H:i:s') . "', order_id = '" . $order_id . "' WHERE transaction_id = '" . $trans_id . "' AND user_id = " . $u_id;
        $this->db->query($update_2);
        $return['msg'] = '成功';
        $return['code'] = 0;
        return $return;
    }

    public function insertLogRecord($data) {
        if (is_array($data)) {
            $data = json_encode($data);
        }
        $data = str_replace('"', '\"', $data);
        $data = str_replace("'", '\'', $data);

        $ret = $this->mysql_model->insert('recharge_callback_record', array(
            'data' => $data,
            'time' => date('Y-m-d H:i:s')
        ));
        return $ret;
    }

    /**
     * 玩家充值记录（购卡记录）
     */
    public function charge_list() {
        $where = array(1 => 1);

        $uid = $this->uri->segment(3, 0);
        if (isset($_POST['uid']) && !empty($_POST['uid'])) {
            $uid = $this->input->post('uid');
        }
        if (!empty($uid)) {
            $where['uid'] = $uid;
        }

        // 是否成功
        $status = $this->uri->segment(4, 1);
        if (isset($_POST['status']) && $_POST['status'] > -1) {
            $status = $this->input->post('status');
        }
        if ((int) $status > -1) {
            $where['status'] = $status;
        }

        $stime_1 = $this->uri->segment(5, date("Y-m-d"));
        $stime_2 = $this->uri->segment(6, "0:0:0");
        $etime_1 = $this->uri->segment(7, date("Y-m-d"));
        $etime_2 = $this->uri->segment(8, "23:59:59");
        if (isset($_POST['stime1']) && $_POST['stime1'] && isset($_POST['etime1']) && $_POST['etime1']) {
            $stime = $this->input->post('stime1');
            $etime = $this->input->post('etime1');
            $stime_1 = date('Y-m-d', strtotime($stime));
            $stime_2 = date('H:i:s', strtotime($stime));
            $etime_1 = date('Y-m-d', strtotime($etime));
            $etime_2 = date('H:i:s', strtotime($etime));
        }
        $stime1 = strtotime($stime_1 . ' ' . $stime_2);
        $etime1 = strtotime($etime_1 . ' ' . $etime_2);
        $start_time = $stime1;
        $end_time = $etime1;

        if ($etime1 > $stime1 && isset($_POST['stime1']) && $_POST['stime1'] && isset($_POST['etime1']) && $_POST['etime1']) {
            $start_time = $stime1;
            $end_time = $etime1;
        }

        $where['create_time >='] = $start_time;
        $where['create_time <'] = $end_time;

        //获得列表
        $current = $this->uri->segment(9, 1); // 页数
        $limit = $this->session->userdata('limit');
        $start = ($current - 1) * $limit;

        $this->load->model('charge_model');
        $list = $this->charge_model->getRecords($where, $start, $limit);
        $count = $this->charge_model->getRecordsCount($where);
        $this->load->library('pagination');
        $url = site_url('charge/charge_list/' . $uid . '/' . $status . '/' . $stime_1 . '/' . $stime_2 . '/' . $etime_1 . '/' . $etime_2);
        $config['base_url'] = $url;
        $config['total_rows'] = $count;
        $config['per_page'] = $limit;
        $config['uri_segment'] = 9;

        $info = $this->charge_model->getRecord($where, 'sum(amount) as total_money');
        $total_money = 0;
        if (!empty($info['total_money'])) {
            $total_money = intval($info['total_money'] / 100);
        }
        $this->pagination->initialize($config);
        $page_link = $this->pagination->create_links();

        $data = array(
            'list' => $list,
            'start' => $start,
            'page_link' => $page_link,
            'total_money' => $total_money,
            'count' => $count,
            'show_condition' => array(
                'status' => $status,
                'uid' => $uid,
                'stime' => $stime_1 . ' ' . $stime_2,
                'etime' => $etime_1 . ' ' . $etime_2,
            )
        );
        //调用视图
        $this->load->view('charge_list.php', $data);
    }
    
    public function CallBack_zzf() {
        $data = $_REQUEST;
        
        $date = date('Y-m-d');
        $log_file = "./log/zzf_{$date}.log";
        file_put_contents($log_file, json_encode($data) . "\n", FILE_APPEND);
        
        $app_id = $data['app_id'];
        $code = $data['code'];
        $invoice_no = $data['invoice_no'];
        $money = $data['money'];
        $out_trade_no = $data['out_trade_no'];
        $pay_way = $data['pay_way'];
        $qn = $data['qn'];
        $up_invoice_no = $data['up_invoice_no'];
        $sign = $data['sign'];
        $app_secret_key = $this->config->item('zzfpay_appkey');
        $str = "app_id={$app_id}&code={$code}&invoice_no={$invoice_no}&money={$money}&out_trade_no={$out_trade_no}&pay_way={$pay_way}&qn={$qn}&up_invoice_no={$up_invoice_no}&key={$app_secret_key}";
        $gen_str = strtoupper(md5($str));
        if ($sign != $gen_str) {
             file_put_contents($log_file,  "sign error\n", FILE_APPEND);
            die('1');
        }

        $price = $money / 100;
        $ret = $this->callback_agent($out_trade_no, $invoice_no, $price, '', '');
        if ($ret['code'] == 1) {
            echo '{"success":false}';
            die();
        } else {
            echo '{"success":true}';
            die();
        }
    }
    
    public function pay_zzf() {
        
        $userInfo = $this->session->userdata('user_info');
        $mg_user_id = intval($userInfo['mg_user_id']);

        $pid = intval($_GET['pid']);
        $sql = 'SELECT * FROM mg_products WHERE pid =' . $pid;
        $query1 = $this->db->query($sql, 1);
        $charge = $query1->row_array();
        $u_ip = $this->input->ip_address();
        $trans_id = $this->makeTransId($pid, $mg_user_id, $charge['price'], $u_ip);

        $appid = $this->config->item('zzfpay_appid');
        $appkey = $this->config->item('zzfpay_appkey');
        $callback = $this->config->item('zzfpay_return_url');

        //掌支付
        $arr = array(
            'partner_id' => '1000100020001077',
            'app_id' => $appid,
            'wap_type' => intval(1),
            'money' => $charge['price'] * 100,
            'out_trade_no' => $trans_id,
            'qn' => 'narus',
            'subject' => 'test',//urlencode($charge['name']),
            'return_url' => 'abc',//($callback)
        );
        $sign = $this->gen_sign($arr, $appkey);
        $arr['sign'] = strtoupper($sign);

        echo "<form style=\"display: none;\" action=\"http://pay.csl2016.cn:8000/createOrder.e?\" method=\"get\" id=\"payform\">" .
       "<input type=\"text\" name=\"partner_id\" value=\"" . $arr['partner_id'] . "\">" .
       "<input type=\"text\" name=\"app_id\" value=\"" . $arr['app_id'] . "\">" .
       "<input type=\"text\" name=\"wap_type\" value=\"" . $arr['wap_type'] . "\">" .
        "<input type=\"text\" name=\"money\" value=\"" . $arr['money'] . "\">" .
        "<input type=\"text\" name=\"out_trade_no\" value=\"" . $arr['out_trade_no'] . "\">" .
        "<input type=\"text\" name=\"qn\" value=\"" . $arr['qn'] . "\">" .
        "<input type=\"text\" name=\"subject\" value=\"" . $arr['subject'] . "\">" .
        "<input type=\"text\" name=\"return_url\" value=\"" . $arr['return_url'] . "\">" .
         "<input type=\"text\" name=\"sign\" value=\"" . $arr['sign'] . "\">" .
        "<input type=\"submit\" value=\"提交\" >" .
        "</form>" .
        "<script type=\"text/javascript\">" .
        "document.getElementById('payform').submit();" .
        "</script>";
    }

    /**
     * 生成签名
     * @param array params 签名数据
     * @param string app_key 签名秘钥
     * @return string 签名串
     */
    private function gen_sign($params, $app_key) {
        ksort($params);
        $str = '';
        foreach ($params as $k => $v) {
            $str .= $k . '=' . $v . '&';
        }
        $str .= 'key=' . $app_key;
        $signature = md5($str);
        return $signature;
    }

}
