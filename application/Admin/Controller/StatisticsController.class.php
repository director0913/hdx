<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class StatisticsController extends AdminbaseController
{
	public function index()
	{
		$result = M('statistics')->select();
//		echo "<pre>";
//		print_r($result);exit;
		$this->assign('result', $result);
		$this->display();
	}
}