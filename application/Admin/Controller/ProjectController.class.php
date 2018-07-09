<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class ProjectController extends AdminbaseController{
	protected $ad_model;
	
	function _initialize() {
		parent::_initialize();
		$this->form_model = D("Common/Form");
		$this->formitem_model = D("Common/FormItem");
		$this->formdata_model = D("Common/FormData");

	}
	
	function index(){
		$ads=$this->form_model->where(array('type'=>2))->select();
		$this->assign("ads",$ads);
		$this->display();
	}
	
	function add(){
		$this->display();
	}
	
	function add_post(){
		if(IS_POST){
			//$_POST['rule']=htmlspecialchars_decode($_POST['rule']);
			$_POST['description']=htmlspecialchars_decode($_POST['description']);

			if(!empty($_POST['photos_alt']) && !empty($_POST['photos_url'])){
				foreach ($_POST['photos_url'] as $key=>$url){
					$photourl=sp_asset_relative_url($url);
					$_POST['smeta'][]=array("url"=>$photourl,"alt"=>$_POST['photos_alt'][$key]);
				}
			}
			$_POST['type']=2;
			$_POST['thumb'] = json_encode($_POST['smeta']);
			$_POST['begintime'] = strtotime($_POST['begintime']);
			$_POST['endtime'] = strtotime($_POST['endtime']);
			$formid = $this->form_model->add($_POST);
			if ($formid!==false) {
				$fname = I('post.fname');
				$type = I('post.type');
				$default = I('post.default');
				$item = array();
				foreach($fname as $key=>$v){
					$item[] = array(
						'form_id' => $formid,
						'name' => $fname[$key],
						'type' => $type[$key],
						'default' => $default[$key]
					);
				}
				$this->formitem_model->addAll($item);
				$this->success(L('ADD_SUCCESS'), U("form/index"));
			} else {
				$this->error(L('ADD_FAILED'));
			}
		}
	}
	
	function edit(){
		$id=I("get.id",0,"intval");
		$ad=$this->form_model->where(array("id"=>$id))->find();
		$ad['begintime'] = date('Y-m-d',$ad['begintime']);
		$ad['endtime'] = date('Y-m-d',$ad['endtime']);

		/**查询出表单项***/
		$formitem = $this->formitem_model->where("form_id=$id")->select();
		$smeta = json_decode($ad['thumb'],true);
		/****分析此报名表单下是否有报名数据****/
		$ishave_data = $this->formdata_model->where("form_id=$id")->count();
		$this->assign("ishave_data",$ishave_data);
		$this->assign("smeta",$smeta);
		$this->assign("formitem",$formitem);
		$this->assign('ad',$ad);
		$this->display();
	}
	
	function edit_post(){
		if (IS_POST) {
			//$_POST['rule']=htmlspecialchars_decode($_POST['rule']);
			$_POST['description']=htmlspecialchars_decode($_POST['description']);
			if(!empty($_POST['photos_alt']) && !empty($_POST['photos_url'])){
				foreach ($_POST['photos_url'] as $key=>$url){
					$photourl=sp_asset_relative_url($url);
					$_POST['smeta']['thumb'][]=array("url"=>$photourl,"alt"=>$_POST['photos_alt'][$key]);
				}
			}
			$_POST['begintime'] = strtotime($_POST['begintime']);
			$_POST['endtime'] = strtotime($_POST['endtime']);
			$_POST['thumb'] = json_encode($_POST['smeta']['thumb']);
			$form = $_POST;
			if ($this->form_model->save($form)!==false) {
				/***处理表单项***/
				$fids = I('post.fids');
				$fname = I('post.fname');
				$type = I('post.type');
				$default = I('post.default');
				$formid = I('post.id',0,'intval');
				foreach($fname as $key=>$v){
					$item = array(
						'form_id' => $formid,
						'name' => $fname[$key],
						'type' => $type[$key],
						'default' => $default[$key]
					);
					if($fids[$key]){
						$item['id'] = $fids[$key];
						$this->formitem_model->save($item);
					}else{
						$this->formitem_model->add($item);
					}
				}
				/****表单项处理结束*****/
				$this->success("保存成功！", U("form/index"));
			} else {
				$this->error("保存失败！");
			}
		}
	}
	
	/**
	 *  删除
	 */
	function delete(){
		$id = I("get.id",0,"intval");
		if ($this->form_model->delete($id)!==false) {
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}
	/*
	  删除报名数据项
	*/
	function delete_item(){
		$id = I("post.id",0,"intval");
		if ($this->formitem_model->delete($id)!==false) {
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}

	/*
		报名模板显示或隐藏
	*/
	function toggle()
	{
		if (isset($_POST['ids']) && $_GET["display"]) {
			$ids = implode(",", $_POST['ids']);
			$data['recommend'] = 1;
			if ($this->form_model->where("id in ($ids)")->save($data) !== false) {
				$this->success("显示成功！");
			} else {
				$this->error("显示失败！");
			}
		}
		if (isset($_POST['ids']) && $_GET["hide"]) {
			$ids = implode(",", $_POST['ids']);
			$data['recommend'] = 0;
			if ($this->form_model->where("id in ($ids)")->save($data) !== false) {
				$this->success("隐藏成功！");
			} else {
				$this->error("隐藏失败！");
			}
		}
	}
	
	function all_list(){
		$types = array("templet1"=>"普通活动报名","templet2"=>"多样模板","templet3"=>"优惠券");
		$statuss = array("全部","未开始","进行中","已结束");

		$where = " type=1 ";
		if(IS_POST){
			$option = I("post.type");
			$keyword = I("post.keyword");
			$status = I("post.status",0,"intval");
			$begintime = I("post.start_time");
			$endtime = I("post.end_time");
			$_GET = $_POST;
		}else{
			$option = $_GET['type'];
			$keyword = $_GET['keyword'];
			$status =  $_GET['status'];
			$begintime = $_GET['start_time'];
			$endtime = $_GET['end_time'];
		}
		$curr_time = time();
		$tcount = $this->form_model->where(" type=1")->count();
		$wcount = $this->form_model->where(" type=1 and begintime>$curr_time")->count();
		$jcount = $this->form_model->where(" type=1 and begintime<$curr_time and endtime>$curr_time")->count();
		$ecount = $this->form_model->where(" type=1 and endtime<$curr_time")->count();

		if($status==1){
			$where.=" and begintime>".time();
		}
		if($status==2){
			$where.=" and (begintime<".time()." and endtime>".time().")";
		}
		if($status==3){
			$where.=" and endtime<".time();
		}

		if(!empty($option)){
			$where .= " and option3='".$option."'";
		}
		if(!empty($keyword)){
			$where.=" and (name like '%$keyword%')";
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
		$count = $this->form_model->where($where)->count();
		$page = $this->page($count, 20);
		$ads=$this->form_model->where($where)
			->order("createtime DESC")
			->limit($page->firstRow . ',' . $page->listRows)	
			->select();
		$this->assign("list",$ads);
		$this->assign("tcount",$tcount);
		$this->assign("wcount",$wcount);
		$this->assign("jcount",$jcount);
		$this->assign("ecount",$ecount);
		$this->assign("count",$count);

		$this->assign("count",$count);
		$this->assign("type",$types);
		$this->assign("status",$statuss);
		$this->assign("formget",$_GET);
		$this->assign("page", $page->show('Admin'));
		$this->display();
	}
	//排序
	public function listorders() {
		$status = parent::_listorders($this->form_model);
		if ($status) {
			$this->success("排序更新成功！");
		} else {
			$this->error("排序更新失败！");
		}
	}
}