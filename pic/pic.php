<?php

$file = $_FILES['file'];//得到传输的数据
//得到文件名称
$name = $file['name'];
$club_id = $_REQUEST['club_id'];
$uid = $_REQUEST['uid'];
$type = strtolower(substr($name,strrpos($name,'.')+1)); //得到文件类型，并且都转化成小写
$allow_type = array('jpg','jpeg','gif','png'); //定义允许上传的类型
//判断文件类型是否被允许上传
if(!in_array($type, $allow_type)){
  //如果不被允许，则直接停止程序运行
  return ;
}
//判断是否是通过HTTP POST上传的
if(!is_uploaded_file($file['tmp_name'])){
  //如果不是通过HTTP POST上传的
  return ;
}
$file_new_name = dirname(__FILE__) . "/" . $club_id . '.' . $type; //上传文件的存放路径
//开始移动文件到相应的文件夹
if(move_uploaded_file($file['tmp_name'],$file_new_name)){
  echo "Successfully!";
}else{
  echo "Failed!";
}