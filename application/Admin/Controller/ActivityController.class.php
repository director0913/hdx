<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class ActivityController extends AdminbaseController{
	protected $ad_model;
	
	function _initialize() {
		parent::_initialize();
		$this->activity_model = D("Common/Activity");
	}
	
	function index(){
		/**$type = array(
			"olympicrefuel"	=>"奥运加油",
			"cheerchina" => "喝彩中国队",
			"bigwheel" =>"大转盘",
			"weishare" => "微助力",
			"invitegift" => "邀请有礼"
		);**/
		$statuss = array("全部","未开始","进行中","已结束");
		$type = D("Slide")->where("slide_status=1 and module<>''")->select();
		if(IS_POST){
			$types = I("post.type");
			$begintime = I("post.begintime");
			$endtime = I("post.endtime");
			$keyword = I("post.keyword");
			$status = I("post.status",0,"intval");
			$begintime = I("post.start_time");
			$endtime = I("post.end_time");
			$_GET = $_POST;
		}else{
			$types = $_GET['type'];
			$begintime = $_GET['begintime'];
			$endtime = $_GET['endtime'];
			$keyword = $_GET['keyword'];
			$status = $_GET['status'];
			$begintime = $_GET['start_time'];
			$endtime = $_GET['end_time'];
		}
		$where = " valid=1 ";
		$curr_time = time();
		$tcount = $this->activity_model->where(" valid=1")->count();
		$wcount = $this->activity_model->where(" valid=1 and begintime>$curr_time")->count();
		$jcount = $this->activity_model->where(" valid=1 and begintime<$curr_time and endtime>$curr_time")->count();
		$ecount = $this->activity_model->where(" valid=1 and endtime<$curr_time")->count();

		if($types){
			$where.=" and a.type='".$types."'";
		}
		if($status==1){
			$where.=" and a.begintime>=$curr_time";
		}
		if($status==2){
			$where.=" and (a.begintime<$curr_time and a.endtime>$curr_time)";
		}
		if($status==3){
			$where.=" and a.endtime<$curr_time";
		}
		//按照时间过滤
		if(!empty($begintime)){
			$begin_time = strtotime($begintime);
			$where.=" and createtime>=".$begin_time;
		}
		if(!empty($endtime)){
			$end_time = strtotime($endtime);
			$where.=" and createtime<=".$end_time;
		}
		/**
		if($begintime){
			$begintime = strtotime($begintime);
			$where.=" and a.begintime>=$begintime";
		}
		if($endtime){
			$endtime = strtotime($endtime);
			$where.=" and a.endtime<=$endtime";
		}**/
		if($keyword){
			$where.=" and (a.title like '%$keyword%' or u.user_login like '%$keyword%' or  u.user_nicename like '%$keyword%')";
		}
		$count = $this->activity_model->alias("a")->join(" cmf_users u on a.uid = u.id")->where($where)->count();
		$page = $this->page($count, 20);
		$ads=$this->activity_model->alias("a")
			 ->join(" cmf_users u on a.uid = u.id")
			 ->field("a.*,u.school,u.user_login,u.contact,u.user_nicename")
			 ->where($where)
			 ->order("hits DESC")
			 ->limit($page->firstRow . ',' . $page->listRows)
			 ->select();
		//echo $this->activity_model->getLastSql();
		$this->assign("page", $page->show('Admin'));
		$this->assign("ads",$ads);
		$this->assign("tcount",$tcount);
		$this->assign("wcount",$wcount);
		$this->assign("jcount",$jcount);
		$this->assign("ecount",$ecount);
		$this->assign("count",$count);

		$this->assign("type",$type);
		$this->assign("statuss",$statuss);
		$this->assign("status",$status);
		$this->assign("formget",$_GET);
		$this->display();
	}
	
	function add(){
		$this->display();
	}
	
	function add_post(){
		if(IS_POST){
			if ($this->activity_model->create()){
				if ($this->activity_model->add()!==false) {
					$this->success(L('ADD_SUCCESS'), U("activity/index"));
				} else {
					$this->error(L('ADD_FAILED'));
				}
			} else {
				$this->error($this->activity_model->getError());
			}
		
		}
	}
	
	function edit(){
		$id=I("get.id");
		//$ad=$this->activity_model->where("ad_id=$id")->find();
		$ad=$this->activity_model->where(array("id"=>$id))->find();
		//$this->assign($ad);
		$this->assign('ad',$ad);
		$this->display();
	}
	
	function edit_post(){
		if (IS_POST) {
			if ($this->activity_model->create()) {
				if ($this->activity_model->save()!==false) {
					$this->success("保存成功！", U("ad/index"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->activity_model->getError());
			}
		}
	}
	
	function test(){
		echo "json测试xxxx";
		$mm = array("id"=>1,"name"=>"shiyuliang");
		$data['ad_content']= json_encode($mm,true);
		$data['ad_name'] ="mmxxx";
		$res = $this->activity_model->add($data);
		echo "天假完毕".$res;
	}
	/**
	 *  删除
	 */
	function delete(){
		$id = I("get.id",0,"intval");
		if ($this->activity_model->delete($id)!==false) {
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}
	
	function toggle(){
		if(isset($_POST['ids']) && $_GET["display"]){
			$ids = implode(",", $_POST['ids']);
			$data['recommend']=1;
			if ($this->activity_model->where("id in ($ids)")->save($data)!==false) {
				$this->success("推荐成功！");
			} else {
				$this->error("推荐失败！");
			}
		}
		if(isset($_POST['ids']) && $_GET["hide"]){
			$ids = implode(",", $_POST['ids']);
			$data['recommend']=0;
			if ($this->activity_model->where("id in ($ids)")->save($data)!==false) {
				$this->success("取消推荐成功！");
			} else {
				$this->error("取消推荐失败！");
			}
		}
	}
	
	
	
	
	
}