<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
define('LOG_PATH',ROOTDIR .'/log/');
if ( ! function_exists('dump'))
{
	/**
	 * dump 打印
	 * @param  mixed $val 值
	 * @return mixed    
	 */
	function dump( $val )
	{
		if(is_array($val))
		{
			echo '<pre>';
			print_r($val);
		}else{
			var_dump($val);
		}
	}
}
// 产生随机字符串
if ( ! function_exists('getRandChar'))
{
	function get_rand_char($length){
	   $str = null;
	   #$strPol = "0123456789abcdefghijklmnopqrstuvwxyz";
	   $strPol = "0123456789";
	   $max = strlen($strPol)-1;

	   for($i=0;$i<$length;$i++){
	    $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
	   }

	   return $str;
	  }
 }
// 跳转
if ( ! function_exists('alert'))
{
    /**
     * [alert 页面跳转]
     * @param  [string] $msg [提示信息]
     * @param  [string] $url [跳转的路径]
     * @return [type]      [description]
     */
    function alert($msg,$url=''){
        echo "<script>alert('$msg');";
        if($url){
            echo "window.location.href='$url';";
        }else{
            echo "window.history.go(-1);";
        }
        echo "</script>";
    }
}

function is_empty($value){
	if($value){
		return $value;
	}else{
		return "";
	}
}
function write_log_file($plat,$data) {
    if (is_array($data)) {
        file_put_contents(LOG_PATH . $plat . date('Y-m-d') . ".log", date('Y-m-d H:i:s') . @json_encode($data) . "\n", FILE_APPEND);
    } else {
        file_put_contents(LOG_PATH . $plat . date('Y-m-d') . ".log", date('Y-m-d H:i:s') . $data . "\n", FILE_APPEND);
    }
}
// function user_info_show($info, $number){
// 	if(strlen($info) > $number){
// 		$info = substr($info, 0, $number);
// 	}
// 	foreach($where){

// 	}
// }
// function mbstringtoarray($str,$charset="utf-8") {
//   $str1 = '';
//   for ($i=0; $i < count($str); $i++) { 
//   	$str1 .= $str[$i] . '<br>';
//   }
//   return $str1;
// }
// if ( ! function_exists('config_pagination')){

// 	function config_pagination( $base_url , $total_rows , $per_page , $uri_segment ){
// 		$config['num_links'] = 6;
// 		$config['base_url'] = $base_url;
// 		$config['total_rows'] = $total_rows;
// 		$config['per_page'] = $per_page;
// 		$config['uri_segment'] = $uri_segment;
// 		$config['reuse_query_string'] = TRUE;
// 		return $config;
// 	}

// }

// function get_condition($field, $segment, $default = '', $method = 'post' ){
// 	$CI =& get_instance();
// 	$value = $CI->uri->segment($segment, $default);

// 	if(strtolower($method) == 'post'){
// 		$value = $this->input->post($field);
// 	}else if(strtolower($method) == 'get'){
// 		$value = $this->input->get($field);
// 	}else{
// 		$value = $this->input->param($field);
// 	}
//     if( $value ){
//     	return $value;
//     }else{
//     	return false;
//     }
// }