<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 本页复制来自广播，请按需要更改变量名
 */
class Activity extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('activity_model');
        if (!isset($_SESSION['user_info']) && empty($_SESSION['user_info'])) {
            show_error('未登录');
            return false;
        }
        $this->output->enable_profiler(false);
        $this->pay_ways = array('1' => '明天动力', '2' => '掌支付');
    }

    public function index() {
        $user_info = $this->session->userdata('user_info');
        $where = array('1' => 1);

        $current = $this->uri->segment(3, 1); // 页数
        $limit = $this->session->userdata('limit'); // 条数;
        $start = ($current - 1) * $limit;

        //获得广播列表
        $list = $this->activity_model->getBroads($where, $start, $limit);
        
        $count = $this->activity_model->getBroadsCount($where);
        $this->load->library('pagination');
        $config['base_url'] = site_url('activity/index/');
        $config['total_rows'] = $count;
        $config['per_page'] = $limit;
        $config['uri_segment'] = 3;
        $this->pagination->initialize($config);
        $page_link = $this->pagination->create_links();
        $data = array(
            'list' => $list,
            'start' => $start,
            'page_link' => $page_link,
            'pay_ways' => $this->pay_ways,
        );
        //调用视图
        $this->load->view('mon_actlist.php', $data);
    }

    public function add() {
        if (isset($_POST['fix_msg']) && $_POST['fix_msg']) {
            $config['upload_path'] = getcwd() . '/uploads/huodong/';
            $config['allowed_types'] = 'gif|jpg||jpeg|png';
            $config['file_name'] = date('Ymd') . uniqid();

            $this->load->helper('form', 'url');
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('hd_img')) {
                $msg = $this->upload->display_errors();
                $data['url'] = site_url('activity/add');
            } else {
                $data_file = $this->upload->data();
                // dump($data_file);exit;
                $data = array(
                    'qq' => $this->input->post('qq', true),
                    'wx' => $this->input->post('wx', true),
                    'delegate' => $this->input->post('delegate', true),
                    'hd_img' => $data_file['file_name'],
                    'fix_msg' => $this->input->post('fix_msg', true),
                    'shuoming' => $this->input->post('shuoming', true),
                    'pay_way' => $this->input->post('pay_way', true),
                     'start_time' => strtotime($this->input->post('start_time')),
                     'end_time' =>  strtotime($this->input->post('end_time')),
                     'jiange' => $this->input->post('jiange', 180),
                     'send_times' => $this->input->post('send_times', 1),
                     'mtype' => $this->input->post('mtype', 1),
                );

                if ($this->activity_model->addBroad($data)) {
                    $msg = "增加成功";
                } else {
                    $msg = "增加失败";
                }
                $data['url'] = site_url('activity/index');
            }
            $data['message'] = $msg;

            $this->load->view('message.html', $data);
            return false;
        }
        $this->load->view('activity/add.php');
    }

    public function edit() {
        $token = md5('pm_broad');
        $broad_id = $this->uri->segment(3, 0);
        if ($broad_id > 0) {
            if (isset($_POST['id']) && isset($_POST['token']) && md5('pm_broad') == $_POST['token']) {
                $broad_id = $this->input->post('id', true);
                $data = array(
                    'qq' => $this->input->post('qq', true),
                    'wx' => $this->input->post('wx', true),
                    'delegate' => $this->input->post('delegate', true),
                    'fix_msg' => $this->input->post('fix_msg', true),
                    'youhui' => $this->input->post('youhui', true),
                    'shuoming' => $this->input->post('shuoming', true),
                    'rat' => $this->input->post('rat', true),
                    //'pay_way' => $this->input->post('pay_way', true),
                    'is_show_notice' => $this->input->post('is_show_notice', 0),
                    'start_time' => strtotime($this->input->post('start_time', 0)),
                     'end_time' =>  strtotime($this->input->post('end_time', 0)),
                     'jiange' => $this->input->post('jiange', 180),
                     'send_times' => $this->input->post('send_times', 1),
                     'mtype' => $broad_id,
                     'create_time'=>time(),
                );
                if ($data['rat'] > 10) {
                    $data['rat'] = 10;
                }

                $wechat = $this->input->post('wechat', true);
                $alipay = $this->input->post('alipay', true);
                
                $an_wechat = $this->input->post('an_wechat', true);
                $an_alipay = $this->input->post('an_alipay', true);

                $pay_way = array();
                if($wechat > 0) {   $pay_way[] = $wechat;   }
                if($alipay > 0) {   $pay_way[] = $alipay;   }
                $data['pay_way'] = implode(',', array_unique($pay_way));

                $pay_way = array();
                if($wechat > 0) {   $pay_way[] = $wechat;   }
                if($alipay > 0) {   $pay_way[] = $alipay;   }
                $data['pay_way'] = implode(',', array_unique($pay_way));
                    
                $config['upload_path'] = getcwd() . '/uploads/huodong/';
                $config['allowed_types'] = 'gif|jpg||jpeg|png';
                $config['file_name'] = date('Ymd') . uniqid();

                $this->load->helper('form', 'url');
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($_FILES['hd_img']['error'] == 0) {
                    if ($this->upload->do_upload('hd_img')) {
                        $data_file = $this->upload->data();
                        $data['hd_img'] = base_url('/uploads/huodong/' . $data_file['file_name']);
                    } else {
                        $msg = $this->upload->display_errors();
                        $data['message'] = $msg;
                        $data['url'] = site_url('activity/index');
                        $this->load->view('message.html', $data);
                        return false;
                    }
                }

                $config2['upload_path'] = getcwd() . '/uploads/huodong/';
                $config2['allowed_types'] = 'gif|jpg||jpeg|png';
                $config2['file_name'] = date('Ymd') . uniqid();

                $this->load->library('upload', $config2);
                $this->upload->initialize($config2);

                if ($_FILES['show_photo']['error'] == 0) {
                    if ($this->upload->do_upload('show_photo')) {
                        $data_file = $this->upload->data();
                        $data['show_photo'] = base_url('/uploads/huodong/' . $data_file['file_name']);
                    } else {
                        $msg = $this->upload->display_errors();
                        $data['message'] = $msg;
                        $data['url'] = site_url('activity/index');
                        $this->load->view('message.html', $data);
                        return false;
                    }
                }

                if ($this->activity_model->editBroad($data, $broad_id)) {
                    $msg = "修改成功";
                } else {
                    $msg = "修改失败";
                }
                
                $data['message'] = $msg;
                $data['url'] = site_url('activity/index');
                $data['footer'] = $this->config->item('footer');
                $this->load->view('mon_message', $data);
                return false;
            }
            // 获得
            $where = array('id' => $broad_id);
            $this->game_db = $this->load->database('game', TRUE);
            $res = $this->game_db->get_where('config_huodong', $where)->row_array();
            $data = array(
                'token' => $token,
                'id' => $broad_id,
                'pay_ways' => $this->pay_ways,
                'res' => $res,
            );
            
            $style = explode(',', $res['style']);
            sort($style);
            $pay_way = explode(',', $res['pay_way']);
            $wechat = 0;
            $alipay = 0;
            foreach($style as $v) {
                if($v == 1) {
                    foreach($pay_way as $k=> $p) {
                        if(in_array($p, array(7,6,4,1))) {
                            $wechat = $p;
                            unset($pay_way[$k]);
                            break;
                        }
                    }
                }else{
                     foreach($pay_way as $p) {
                        if(in_array($p, array(5,1))) {
                            $alipay = $p;break;
                        }
                    }
                }
            }
            $data['wechat'] = $wechat;
            $data['alipay'] = $alipay;
            $this->load->view('mon_actedit.php', $data);
        } else {
            $data['message'] = '非法访问';
            $data['url'] = site_url('activity/index');
            $this->load->view('message.html', $data);
            return false;
        }
    }

    public function del($id) {
        if ($this->activity_model->delBroad($id)) {
            $msg = "删除成功";
        } else {
            $msg = "删除失败";
        }
        $data['message'] = $msg;
        $data['url'] = site_url('activity/index');
        $this->load->view('message.html', $data);
        return false;
    }

    public function board() {
        if (!empty($_POST)) {
            $times = isset($_POST['times']) ? intval($_POST['times']) : 1;
            $jiange = isset($_POST['jiange']) ? intval($_POST['jiange']) : 600;
            $content = trim($_POST['content']);
            if($times > 0 && $jiange > 0){
                $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
                socket_connect($socket, $this->config->item('GMIP'), $this->config->item('GMPORT'));
                $arr = array(
                    'cmd'       => 605,
                    'send_type' => 2,
                    'jiange'    => $jiange,
                    'times'     => $times,
                    'content'   => $content,
                );
                $str = json_encode($arr);
                $size = strlen($str);
                $binary_str = pack("na".$size, $size, $str);
                socket_write($socket,$binary_str,  strlen($binary_str));
                socket_close($socket);
                $msg = '发布成功';
            }else{
                $msg = '参数不正确';
            }
            $data['message'] = $msg;
            $data['url'] = site_url('Login/menu');
            $this->load->view('message.html', $data);
            return false;
        }
        $this->load->view('mon_board.php');
    }

}
