<?php

namespace Asset\Controller;
use Common\Controller\AdminbaseController;
class TencentCosController extends AdminbaseController
{

    function __construct(){
        spl_autoload_register(function($class){
            $dir = dirname(__FILE__);
            $class = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
            include($dir.DIRECTORY_SEPARATOR.$class);
        });
    }

    // 创建文件夹
    public function createFolder($bucketName, $dstFolder){
        $createFolderRet = \Qcloud_cos\Cosapi::createFolder($bucketName, $dstFolder);
        return $createFolderRet;
    }

    // 删除文件夹
    public function delFolder($bucketName, $dstFolder){
        $delRet = \Qcloud_cos\Cosapi::delFolder($bucketName, $dstFolder);
        return $delRet;
    }

    // 上传文件
    public function upload($bucketName, $srcPath, $dstPath){
        $bizAttr    = "";
        $insertOnly = 0;
        $sliceSize  = 3 * 1024 * 1024;
        $uploadRet  = \Qcloud_cos\Cosapi::upload($bucketName, $srcPath, $dstPath, $bizAttr, $sliceSize, $insertOnly);
        return $uploadRet;
    }

    // 删除文件
    public function delFile($bucketName, $dstPath){
        $delRet = \Qcloud_cos\Cosapi::delFile($bucketName, $dstPath);
        return $delRet;
    }

    // 查询文件信息
    public function stat($bucketName, $dstPath)
    {
        $statRet = \Qcloud_cos\Cosapi::stat($bucketName, $dstPath);
        return $statRet;
    }

}