<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class AppcenterController extends AdminbaseController{
	
	protected $appcenter_model;
	
	function _initialize() {
		parent::_initialize();
		$this->appcenter_model = D("Common/Appcenter");		
	}
	
	function index(){
		$slides=$this->appcenter_model->where($where)->order("listorder ASC")->select();
		$this->assign('slides',$slides);
		$this->display();
	}
	
	function add(){
		$this->display();
	}
	
	function add_post(){
		if(IS_POST){
			if ($this->appcenter_model->create()) {
				$_POST['thumb']=sp_asset_relative_url($_POST['thumb']);
				$_POST['index_thumb']=sp_asset_relative_url($_POST['index_thumb']);
				if ($this->appcenter_model->add()!==false) {
					$this->success("添加成功！", U("appcenter/index"));
				} else {
					$this->error("添加失败！");
				}
			} else {
				$this->error($this->appcenter_model->getError());
			}
		}
	}
	
	function edit(){
		$id= intval(I("get.id"));
		$appcenter=$this->appcenter_model->where("id=$id")->find();
		$this->assign($appcenter);
		$this->display();
	}
	
	function edit_post(){
		if(IS_POST){
			if ($this->appcenter_model->create()) {
				$_POST['thumb']=sp_asset_relative_url($_POST['thumb']);
				$_POST['index_thumb']=sp_asset_relative_url($_POST['index_thumb']);
				if ($this->appcenter_model->save()!==false) {
					$this->success("保存成功！", U("appcenter/index"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->appcenter_model->getError());
			}
				
		}
	}
	
	function delete(){
		if(isset($_POST['ids'])){
			$ids = implode(",", $_POST['ids']);
			if ($this->appcenter_model->where("id in ($ids)")->delete()!==false) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}else{
			$id = intval(I("get.id"));
			if ($this->appcenter_model->delete($id)!==false) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}
		
	}
	
	function toggle(){
		if(isset($_POST['ids']) && $_GET["display"]){
			$ids = implode(",", $_POST['ids']);
			$data['status']=1;
			if ($this->appcenter_model->where("id in ($ids)")->save($data)!==false) {
				$this->success("显示成功！");
			} else {
				$this->error("显示失败！");
			}
		}
		if(isset($_POST['ids']) && $_GET["hide"]){
			$ids = implode(",", $_POST['ids']);
			$data['status']=0;
			if ($this->appcenter_model->where("id in ($ids)")->save($data)!==false) {
				$this->success("隐藏成功！");
			} else {
				$this->error("隐藏失败！");
			}
		}
	}
	    //隐藏
	function ban(){
    	$id=intval($_GET['id']);
			$data['status']=0;
    	if ($id) {
    		$rst = $this->appcenter_model->where("id in ($id)")->save($data);
    		if ($rst) {
    			$this->success("隐藏成功！");
    		} else {
    			$this->error('隐藏失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
    //显示
    function cancelban(){
    	$id=intval($_GET['id']);
		$data['status']=1;
    	if ($id) {
    		$result = $this->appcenter_model->where("id in ($id)")->save($data);
    		if ($result) {
    			$this->success("启用成功！");
    		} else {
    			$this->error('启用失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
	//排序
	public function listorders() {
		$status = parent::_listorders($this->appcenter_model);
		if ($status) {
			$this->success("排序更新成功！");
		} else {
			$this->error("排序更新失败！");
		}
	}
	
}