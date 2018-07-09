<?php
/*
 * jQuery File Upload Plugin PHP Example
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */
/**
error_reporting(E_ALL | E_STRICT);
require('UploadHandler.php');
$upload_handler = new UploadHandler();
**/
//echo "文件上传测试";
//die();
header('Content-type: application/json');

if($_FILES["image"]["error"]!=0){
  $result = array('status'=>0,'msg'=>'上传错误');
  echo json_encode($result);exit();
}

if( !in_array($_FILES["image"]["type"], array('image/gif','image/jpeg','image/bmp')) ){
  $result = array('status'=>0,'msg'=>'图片格式错误');
  echo json_encode($result);exit();
}

if($_FILES["image"]["size"] > 2000000){//判断是否大于2M
  $result = array('status'=>0,'msg'=>'图片大小超过限制');
  echo json_encode($result);exit();
}

$filename = substr(md5(time()),0,10).mt_rand(1,10000);
$ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);

$localName = "../".$filename.'.'.$ext;
$result  = array('status'=>0,'msg'=>$_FILES["image"]["tmp_name"]);

if ( move_uploaded_file($_FILES["image"]["tmp_name"], $localName) == true) {
  $lurl = 'http://code.tongyishidai.com/code/copy/'.$localName;
  $result  = array('status'=>1,'msg'=>$lurl);
}else{
  $result  = array('status'=>0,'msg'=>'error');
}
echo json_encode($result);
