<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class OrderController extends AdminbaseController
{
	public function index()
	{
		$db = C('DB_XYT');
		$result = M()->db(1,$db)->query("SELECT `alias`, `amount`, `update_time` FROM `shop_trade` WHERE `appid` = '".C('APP_ID')."' AND `status` = 2 ORDER BY `update_time` DESC");

		foreach ($result AS $key => $value) {
			$users = M('users')->field('user_login, user_nicename')->where('id = '.$value['alias'])->find();
			if (!empty($users['user_login'])) {
				$result[$key]['phone'] = $users['user_login'];
				$result[$key]['name'] = $users['user_nicename'];
			}
		}

		$this->assign('result', $result);
		$this->display();
	}
}