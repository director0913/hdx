<?php
namespace Api\Controller;
use Common\Controller\AdminbaseController;
class MapController extends AdminbaseController {


	function _initialize() {
	}
	
	function report_post(){
		file_put_contents("report.txt",print_r($_POST,true));
		$this->ajaxReturn(array(
			'status' => 1,
			'info' =>'数据上传成功！'
		));
		die();
	}
	function index(){
		$lng=I("get.lng","121.481798");
		$lat=I("get.lat","31.238845");
		$this->assign("lng",$lng);
		$this->assign("lat",$lat);
		$this->display();
	}
	
}