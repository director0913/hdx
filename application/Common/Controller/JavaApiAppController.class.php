<?php
namespace Common\Controller;
use Common\Controller\JavaApiBaseController;
class JavaApiAppController extends JavaApiBaseController {
	
	/*
     * 注册App信息
     */
    public function registered($data, $code = 0)
    {
        return $this->send([
            'url' => '/app',
            'method' => 'post',
            'data' => $data
        ], $code);
    }

    /*
     * 修改App信息
     */
    public function modify($appid, $data, $code = 0)
    {
        return $this->send([
            'url' => '/app/' . $appid,
            'method' => 'patch',
            'data' => $data
        ], $code);
    }

    /*
     * 获取相应的APP信息
     */
    public function get_app_info($appcode = 'zsx', $appname = '招生秀', $code = 0)
    {
        return $this->send([
            'url' => '/app/single?appCode='.$appcode.'&appName='.$appname,
            'method' => 'get'
        ], $code);
    }
}