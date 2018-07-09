<?php
namespace Apps\Controller;
use Common\Controller\ActivitybaseController;
class WechatController extends ActivitybaseController {

	function _initialize()
	{
		parent::_initialize();
	}

    public function index()
	{
		$controller = I('get.controller');
		$action = I('get.action');
		$id = I('get.id');
		$uid = I('get.uid');
		$shareid = I('get.shareid');

		$this->entry('http://xiu.xiaoyingtong.net/index.php/apps/score/index/id/32.shtml');
		if (session('userInfo')) {
			$userInfo = session('userInfo');
			$url = 'http://ceshi.xiaoyingtong.net'.U('apps/'.$controller.'/'.$action.'/id/'.$id.'/uid/'.$uid.'/shareid/'.$shareid, ['nickname' => $userInfo['nickname'], 'openid' => $userInfo['openid']]);
			header("Location: $url");
		} else {
			echo "没有获取到session！";
		}
	}
}

