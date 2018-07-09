<?php

/**
 * 附件上传
 */
namespace Asset\Controller;
use Common\Controller\AdminbaseController;
class AssetController extends AdminbaseController 
{
    public function _initialize() 
    {
    	$adminid = sp_get_current_admin_id();
    	$userid = sp_get_current_userid();
    	if (empty($adminid) && empty($userid)) {
    		//exit("非法上传！");
    	}
    }
	
	 /**
     * swfupload 上传 
     */
    public function swfupload_mobile()
    {
        if (IS_POST) {
			$savepath = date('Ymd').'/';
            //上传处理类
            $config = [
            		'rootPath' => './'.C("UPLOADPATH"),
            		'savePath' => $savepath,
            		'maxSize' => 11048576,
            		'saveName'   =>    array('uniqid',''),
            		'exts'       =>    array('jpg', 'gif', 'png', 'jpeg',"txt",'zip'),
            		'autoSub'    =>    false,
            ];
			$upload = new \Think\Upload($config);
			$info = $upload->upload();
            //开始上传
            if ($info) {
                //写入附件数据库信息
                $first = array_shift($info);
                if (!empty($first['url'])) {
                	$url = $first['url'];
                } else {
                	$url = C("TMPL_PARSE_STRING.__UPLOAD__").$savepath.$first['savename'];
                }

                $image = new \Think\Image();
                $image->open('.'.$url);
                $image->thumb(600, 600,1)->save('.'.$url);

                /*
                    腾讯云存储Start
                */
                R('TencentCos/upload', array(C('QQ_CLOUD_BUCKET'), '.'.$url, $url));
                unlink('.' . $url);
                $url = C('QQ_CLOUD_URL') . $url;
//                $uploadInfo = R('TencentCos/upload', array(C('QQ_CLOUD_BUCKET'), '.'.$url, $url));
//                if ($uploadInfo['code'] == 0) {
//                    unlink('.' . $url);
//                    $url = C('QQ_CLOUD_URL') . $url;
//                } else {
//                    if (strpos($url, "https") === 0 || strpos($url, "http") === 0) {
//
//                    } else {
//                        $host=(is_ssl() ? 'https' : 'http')."://".$_SERVER['HTTP_HOST'];
//                        $url=$host.$url;
//                    }
//                }
                /*
                    腾讯云存储End
                */

                $result = array('status'=>1,'msg'=>$url,'filename'=>$first['savename']);
				echo json_encode($result);
				exit;
            } else {
                //上传失败，返回错误
				$result = array('status'=>0,'msg'=> $upload->getError());
				json_encode($result);
                exit;
            }
        } else {
            $this->display(':swfupload');
        }
    }
	
    /**
     * swfupload 上传 
     */
    public function swfupload()
    {
        if (IS_POST) {
			$savepath = date('Ymd').'/';
            //上传处理类
            $config = array(
                'rootPath' => './'.C("UPLOADPATH"),
                'savePath' => $savepath,
                'maxSize' => 11048576,
                'saveName' => array('uniqid',''),
                'exts' => array('jpg', 'gif', 'png', 'jpeg',"txt",'zip'),
                'autoSub' => false,
            );
			$upload = new \Think\Upload($config);
			$info = $upload->upload();
            //开始上传
            if ($info) {
                //上传成功
                //写入附件数据库信息
                $first=array_shift($info); 
                if(!empty($first['url'])){
                	$url=$first['url'];
                }else{
                	$url=C("TMPL_PARSE_STRING.__UPLOAD__").$savepath.$first['savename'];
                }

                $image = new \Think\Image();
                $image->open('.'.$url);
                $image->thumb(600, 600,1)->save('.'.$url);

                /*
                    腾讯云存储Start
                */
                R('TencentCos/upload', array(C('QQ_CLOUD_BUCKET'), '.'.$url, $url));
                unlink('.' . $url);
                $url = C('QQ_CLOUD_URL') . $url;
//                $uploadInfo = R('TencentCos/upload', array(C('QQ_CLOUD_BUCKET'), '.'.$url, $url));
//                if ($uploadInfo['code'] == 0) {
//                    unlink('.' . $url);
//                    $url = C('QQ_CLOUD_URL') . $url;
//                } else {
//                    $url = C('domain') . $url;
//                }
                /*
                    腾讯云存储End
                */
                
				echo "1," . $url.",".'1,'. $first['name']; 

				exit;
            } else {
                //上传失败，返回错误
                exit("0," . $upload->getError());
            }
        } else {
            $this->display(':swfupload');
        }
    }

}
