<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['uri_segment'] = 3;
$config['num_links'] = 2;
$config['per_page'] = 20;
$config['use_page_numbers']=true;

$config['full_tag_open'] = '<ul class="page-on clearfix">';  
$config['full_tag_close'] = '</ul>';  
$config['first_tag_open'] = '<li class="link">';  
$config['first_tag_close'] = '</li>';  
$config['prev_tag_open'] = '<li class="link">';  
$config['prev_tag_close'] = '</li>';  
$config['next_tag_open'] = '<li class="link">';  
$config['next_tag_close'] = '</li>';  
$config['cur_tag_open'] = '<li class="active link"><a>';  
$config['cur_tag_close'] = '</a></li>';  
$config['last_tag_open'] = '<li class="link">';  
$config['last_tag_close'] = '</li>';  
$config['num_tag_open'] = '<li class="link">';  
$config['num_tag_close'] = '</li>';
$config['attributes'] = array('class' => 'myclass');//给所有<a>标签加上class

$config['first_link']= '首页';  
$config['next_link']= '下一页';  
$config['prev_link']= '上一页';  
$config['last_link']= '末页'; 

// $config['enable_query_strings'] = true;
// $config['page_query_string'] = true;
