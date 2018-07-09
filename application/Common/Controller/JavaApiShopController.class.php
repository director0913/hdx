<?php
namespace Common\Controller;
use Common\Controller\JavaApiBaseController;
class JavaApiShopController extends JavaApiBaseController {
	
	/*
     * 添加订单信息
     */
    public function shop_add($data, $code = 0)
    {
        return $this->send([
            'url' => '/shop/trades',
            'method' => 'post',
            'data' => $data
        ], $code);
    }

    /*
     * 获取支付信息
     */
    public function shop_pay($data, $code = 0)
    {
        return $this->send([
            'url' => '/shop/trades/pay/info/html',
            'method' => 'post',
            'data' => $data
        ], $code);
    }
}