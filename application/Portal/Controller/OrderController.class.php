<?php
namespace Portal\Controller;
use Common\Controller\HomebaseController;
use Common\Controller\JavaApiAppController;
use Common\Controller\JavaApiShopController;
/*
 * 订单相关
 */
class OrderController extends HomebaseController
{
    // 注册APP信息
	public function registered_app()
	{
		$data = [
		    "appCode" => C('APP_CODE'),
            "appName" => C('APP_NAME'),
            "payReturnUrl" => C('DOMAIN_NAME').C('PAY_RETURN_URL'),
            "payNotifyUrl" => C('DOMAIN_NAME').C('PAY_NOTICE_URL')
        ];
		$javaApiApp = new JavaApiAppController();
		$result = $javaApiApp->registered($data);
		if (empty($result->code)) {
			return $result->appid;
		}
    }

	// 修改APP信息
	public function modify_app()
	{
        $data = [
            "appCode" => C('APP_CODE'),
            "appName" => C('APP_NAME'),
            "payReturnUrl" => C('DOMAIN_NAME').C('PAY_RETURN_URL'),
            "payNotifyUrl" => C('DOMAIN_NAME').C('PAY_NOTICE_URL')
        ];
		$javaApiApp = new JavaApiAppController();
		$javaApiApp->modify(C('APP_ID'), $data);
	}

	// 获取指定的app信息
	public function get_app_info()
	{
		$javaApiApp = new JavaApiAppController();
		$result = $javaApiApp->get_app_info();
		if (empty($result->code)) {
			return $result->appid;
		}
	}

	// 添加订单信息
	public function add_order_info()
	{
		$user = sp_get_current_user();
		if (!$user) {
			echo json_encode(['code' => -1]); // 未登陆
			exit;
		}
                 echo json_encode(['code' => -2]); // 未登陆
                 exit;

		$data = [
			'alias'=> $user['id'],
			//'amount' => C('AMOUNT'),
                          'amount'=>1080,
			'subject' => '登录账号为 '.$user['user_login'].' 的用户购买招生秀会员'
		];
		$javaApiShop = new JavaApiShopController();
		$result = $javaApiShop->shop_add($data);
		if (!empty($result->code)) {
			echo json_encode(['code' => -2]); // 创建订单失败
			exit;
		}

		$info = ['code' => 0, 'orderId' => $result->orderId];
		echo json_encode($info);
		exit;
	}

	// 支付
	public function pay() {
		$orderId = I('get.orderId');
		$payType = I('get.payType');
		$data = ['orderId'=> $orderId, 'payChannelType' => $payType];
		$javaApiShop = new JavaApiShopController();
		$result = $javaApiShop->shop_pay($data);
        $this->display(":vip");
		if (empty($result->code)) {
			var_dump($result);
		}
	}

	// 支付后的返回页面
	public function pay_return_page()
	{
		redirect(__ROOT__ . "/");
        exit;
	}

	// 异步通知页面
	public function pay_notice_page()
    {
        $order_id = I('post.orderId');

        $db = C('DB_XYT');
        $shop_trade_sql = "SELECT `alias`, `status` FROM `shop_trade` WHERE `order_id` = {$order_id}";
        $shop_trade = M()->db(1,$db)->query($shop_trade_sql);

        if (!empty($shop_trade[0]['alias']) && $shop_trade[0]['status'] == 2) {
			// 更新招生秀用户信息表
			M('users')->where('id = '.$shop_trade[0]['alias'])->save([
				'level' => 5,
				'valid_time' => strtotime('+1 year')
			]);

			$vip_begin_time = date("Y-m-d H:i:s");
			$vip_end_time = date("Y-m-d H:i:s",strtotime("+1 year"));

			$organization = M()->db(1,$db)->query("SELECT `id` FROM `crm_organization` WHERE cid = {$shop_trade[0]['alias']}");
			if (!empty($organization[0]['id'])) {
				// 机构表存在该信息
				$crm_organization_sql = "UPDATE `crm_organization` SET `level_id` = 5,
`high_level_id` = null, `vip_begin_time` = '{$vip_begin_time}', `current_vip_begin_time` = '{$vip_begin_time}',
`vip_end_time` = '{$vip_end_time}', `status` = 0, `source` = '购买招生秀会员' WHERE `cid` = {$shop_trade[0]['alias']}";
				M()->db(1,$db)->query($crm_organization_sql);
			} else {
				// 机构表不存在该信息
				$customer_sql = "SELECT `name` FROM `customer` WHERE `id` = {$shop_trade[0]['alias']}";
				$customer = M()->db(1,$db)->query($customer_sql);
				$name = !empty($customer[0]['name']) ? $customer[0]['name'] : '';

				$organization_insert = "INSERT INTO `crm_organization`
(`cid`,`name`,`level_id`,`high_level_id`,`vip_begin_time`,`current_vip_begin_time`,`vip_end_time`,`create_time`,`update_time`)
VALUES ({$shop_trade[0]['alias']}, '".$name."', 5, null,
'".$vip_begin_time."', '".$vip_begin_time."', '".$vip_end_time."', '".$vip_begin_time."', '".$vip_begin_time."')";
				M()->db(1,$db)->query($organization_insert);

				$year = date('Y');
				$date = date('Y-m-d H:i:s');
				$organization_id = M()->db(1,$db)->query("SELECT `id` FROM `crm_organization` WHERE `cid` = {$shop_trade[0]['alias']}");
				M()->db(1,$db)->query("INSERT INTO `crm_business_operation_info` (`oid`, `create_time`, `update_time`, `version`) VALUES ({$organization_id[0]['id']}, '".$date."', '".$date."', {$year})");
			}

			$level_info = M()->db(1,$db)->query("SELECT `level_id` FROM `crm_organization` WHERE `cid` = {$shop_trade[0]['alias']}");
			if (!empty($level_info[0]['level_id']) && $level_info[0]['level_id'] == 5) {
				
				//购买会员增加积分
                $this->addUserScore($shop_trade[0]['alias'],$db,$order_id);

			    //回调成功，给用户的上级加佣金
                $this->addUserCommission($shop_trade[0]['alias'],$db,$order_id);
				
				echo json_encode(['code' => 0, 'msg' => 'ok']);
			} else {
				echo json_encode(['code' => -1, 'msg' => '失败']);
			}
			exit;
        }
    }
	
	/**
     * 给用户加积分
     * @param $uid
     */
    private function addUserScore($uid,$db,$order_id=''){
        //同一个订单，只加一次积分
        $score_rec_sql = "SELECT * from user_score_record where uid = {$uid} and score_type = 1 and get_type = 14 and order_id = '{$order_id}'";
        $score_rec = M()->db(1,$db)->query($score_rec_sql);
        if(empty($score_rec)){
            $buy_member_score_sql = "SELECT `id`,`param_name`,`param_real_val` FROM `sys_param` WHERE `param_name` = 'pa_score_buy_member'";
            $buy_member_score_info = M()->db(1,$db)->query($buy_member_score_sql);
            $buy_member_score = $buy_member_score_info[0]['param_real_val'];

            $customer_sql = "SELECT `current_score` FROM `customer` WHERE `id` = {$uid}";
            $customer = M()->db(1,$db)->query($customer_sql);
            $current_score = $customer[0]['current_score'] + $buy_member_score;
            $customer_upd_sql = "UPDATE `customer` SET `current_score` = {$current_score} where  `id` = {$uid}";

            M()->db(1,$db)->query($customer_upd_sql);


            //添加用户积分记录
            $date = date('Y-m-d H:i:s');
            $score_ins_sql = "insert into user_score_record (`uid`, `score_type`, `get_type`,`consume_type`,`order_id`,`score`,`created_at`,`updated_at`) VALUES ({$uid},1,14,0,'{$order_id}',{$buy_member_score},'".$date."','".$date."')";
            M()->db(1,$db)->query($score_ins_sql);
        }
    }

    /**
     * 给上级加佣金
     * @param $uid
     */
    private function addUserCommission($uid,$db,$order_id){
        $commission1_sql = "SELECT `id`,`param_name`,`param_real_val` FROM `sys_param` WHERE `param_name` = 'pa_commission_rate'";
        $commission2_sql = "SELECT `id`,`param_name`,`param_real_val` FROM `sys_param` WHERE `param_name` = 'pa_commission_rate2'";

        $commission1_info = M()->db(1,$db)->query($commission1_sql);
        $commission2_info = M()->db(1,$db)->query($commission2_sql);

        $commission1_rate = $commission1_info[0]['param_real_val'];
        $commission2_rate = $commission2_info[0]['param_real_val'];

        $customer_sql = "SELECT `id`, `parent`, `parent_top` FROM `customer` WHERE `id` = {$uid}";
        $customer = M()->db(1,$db)->query($customer_sql);
        if(!empty($customer[0]['parent'])){
            //判断改用户是否返过佣金，如果已返，则一级佣金不在返还
            //一级返利 500 ，仅返一次
            //二级返利 200 ，返多次
            //新老用户一样，不区分是否会员
            $commission_rec_sql = "SELECT * from user_commission_record where uid = {$customer[0]['parent']} and buy_user = {$uid}";
            $commission_rec = M()->db(1,$db)->query($commission_rec_sql);

            if(empty($commission_rec) || count($commission_rec) == 0){
                if($customer[0]['parent'] != $uid){
                    $this->addUserParentCommission($customer[0]['parent'],$commission1_rate,$uid,$order_id,$db);
                }
            }
        }

        if(!empty($customer[0]['parent_top'])){
            //避免出现自己的父亲是自己加佣金情况
            if($customer[0]['parent_top'] != $uid){
                $commission_rec_sql = "SELECT * from user_commission_record where uid = {$customer[0]['parent_top']} and buy_user = {$uid} and order_id = '{$order_id}'";
                $commission_rec = M()->db(1,$db)->query($commission_rec_sql);
                if(empty($commission_rec)){//同一个订单不能重复加佣金
                    $this->addUserParentCommission($customer[0]['parent_top'],$commission2_rate,$uid,$order_id,$db);
                }
            }
        }

    }

    /**
     * @param $p_uid
     * @param $commission
     * @param $buy_user
     * @param $order_id
     */
    private function  addUserParentCommission($p_uid,$commission,$buy_user,$order_id,$db){
        if($p_uid > 0){
            $customer_sql = "SELECT `id`, `account` FROM `customer` WHERE `id` = {$p_uid}";
            $customer = M()->db(1,$db)->query($customer_sql);

            $order_info_sql = "SELECT * FROM shop_trade WHERE order_id = '{$order_id}'";
            $order_info = M()->db(1,$db)->query($order_info_sql);

            if($commission > 0){
                $account = round($customer[0]['account'] + $commission,2);
                $pay_tpype = 0;
                $order_amt = 0;
                if($order_info){
                    $pay_tpype = $order_info[0]['pay_channel_type'];
                    $order_amt = $order_info[0]['amount'];
                }

                $customer_upd_sql1 = "UPDATE `customer` SET `account` = {$account}  where  `id` = {$p_uid}";
                $customer_upd_sql2 = "UPDATE `customer` SET `commission` = `commission` + {$commission}  where  `id` = {$p_uid}";
                M()->db(1,$db)->query($customer_upd_sql1);
                M()->db(1,$db)->query($customer_upd_sql2);

                $date = date('Y-m-d H:i:s');
                //加佣金记录
                $commission_ins_sql = "insert into user_commission_record (`uid`, `buy_user`, `order_id`,`commission`,`account_left`,`created_at`,`updated_at`,`pay_type`,`order_amt`) VALUES ({$p_uid},{$buy_user},'{$order_id}',{$commission},{$account},'".$date."','".$date."',{$pay_tpype},{$order_amt})";
                M()->db(1,$db)->query($commission_ins_sql);
                //加账户流水
                $this->addAccountFlow($p_uid,5,$commission,$order_id,$account,$db);
            }
        }
    }

    /**
     * @param $p_uid
     * @param $type
     * @param $commission
     * @param $order_id
     * @param $account
     */
    private function addAccountFlow($uid,$type,$amount,$order_id,$account_left=0,$db){
        $date = date('Y-m-d H:i:s');
        $sql = "insert into user_account_flow (`uid`, `opt_type`, `amount`,`account_left`,`order_id`,`created_at`,`updated_at`) VALUES ({$uid},{$type},{$amount},{$account_left},'{$order_id}','".$date."','".$date."')";
        M()->db(1,$db)->query($sql);
    }
	
}
