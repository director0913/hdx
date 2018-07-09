<?php
namespace Common\Controller;
use Common\Controller\JavaApiBaseController;
class JavaApiUserController extends JavaApiBaseController {
	
	/*
    * 手机号加密
    */
    public function security_encrypt($phone, $code = 0)
    {
        return $this->send([
            'url' => '/security/encrypt',
            'method' => 'post',
            'data' => [
                'algorithm' => 'AES',
                'content' => $phone
            ]
        ], $code);
    }

    /*
    * 手机号解密
    * get
    * @param int $phone 手机号
    */
    public function security_decrypt($phone, $code = 0)
    {
        return $this->send([
            'url' => '/security/decrypt',
            'method' => 'post',
            'data' => [
                'algorithm' => 'AES',
                'content' => $phone
            ]
        ], $code);
    }

    	
}