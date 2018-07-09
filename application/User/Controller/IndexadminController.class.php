<?php

/**
 * 会员
 */
namespace User\Controller;
use Common\Controller\AdminbaseController;
class IndexadminController extends AdminbaseController {
    function index(){
    	$users_model=M("Users");
		$where = array("user_type"=>2);
		if(IS_POST){
			$level = I("post.level",0,"intval");
			$keyword = I("post.keyword");
			$start_time =  I("post.start_time");
			$end_time =  I("post.end_time");
			$_GET['level'] = $level;
			$_GET['keyword'] = $keyword;
			$_GET['start_time'] = $start_time;
			$_GET['end_time'] = $end_time;
		}else{
			$level = $_GET['level'];
			$keyword = $_GET['keyword'];
			$start_time =  $_GET['start_time'];
			$end_time =  $_GET['end_time'];
		}
		if($level){
			$where['level'] = $level;
			if($level==4){
				$where['level'] = 0;
			}
		}
		$time = array();
		if($start_time&&$end_time){
			$where['valid_time'] = array(array('egt',strtotime($start_time)),array('lt',strtotime($end_time))) ;
		}elseif($start_time){
			$where['valid_time'] = array(array('egt',strtotime($start_time))) ;
		}elseif($end_time){
			$where['valid_time'] = array(array('elt',strtotime($end_time))) ;
		}
		
		if(!empty($keyword)){
			$where['_string'] = " (user_login like '%$keyword%' or user_nicename like '%$keyword%' or school like '%$keyword%' or remark1 like '%$keyword%' or remark2 like '%$keyword%' )";

		}
		//echo "edn_time:".$end_time;
    	$count=$users_model->where($where)->count();
		
		//echo $users_model->getLastSql();
    	$page = $this->page($count, 20);
    	$lists = $users_model
    	->where($where)
    	->order(" valid_time asc")
    	->limit($page->firstRow . ',' . $page->listRows)
    	->select();
		$curr_time = time();
		foreach($lists as &$v){
			if($v['pid']){
				$v['recommend'] = $users_model->where(array('id'=>$v['pid']))->getField("user_nicename");
			}
			$where1 = "uid = ".$v['id']." and endtime>=".$curr_time;
			//分析此用户是否有进行中的报名表单  营销活动
			$is_form = D("Form")->where($where1)->count();
			$is_activity = D("Activity")->where($where1)->count();
			$v['is_form'] = $is_form;
			$v['is_activity'] = $is_activity;
		}
		$is_export = I("post.is_export");
		//echo "is_export:".$is_export ;die();
		if($is_export==1){
			//导出数据
			$export_data = $users_model
			->where($where)
			->order(" valid_time asc")
			->select();
			$filename = time();
			$header  = array("用户名","昵称|机构名称","备注","用户级别","注册时间","账号有效期","处理状态","进行中的报名表单","进行中的营销工具");
			header("Content-Type: application/octet-stream"); 
		    header("Content-Type: application/download"); 
		    header("Content-Transfer-Encoding: binary"); 
		    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
		    header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
		    header("Pragma: no-cache"); 
		    header("Content-type:application/vnd.ms-excel");
		    header("Content-Disposition:attachment;filename=".$filename.".xls");
		    echo iconv('utf-8', 'gbk', implode("\t", $header)), "\n";
			// 会员级别从1开始，0位用来占位。
			$level = ['占位', '免费会员', '会员', '黄金会员', '私人定制会员', '校营通会员'];
		    foreach($export_data as $vg){
				$temp = array(iconv('utf-8', 'gbk',$vg['user_login']),iconv('utf-8', 'gbk',$vg['user_nicename']."|".$vg['school']),iconv('utf-8', 'gbk',$vg['remark1']."|".$vg['remark2']));
				$where2 = "uid = ".$vg['id']." and endtime>=".$curr_time;
				//分析此用户是否有进行中的报名表单  营销活动
				$is_form = D("Form")->where($where2)->count();
				$is_activity = D("Activity")->where($where2)->count();
				$level_t = $vg['level'];
				$temp[] = iconv('utf-8', 'gbk',$level[$level_t]) ;
				$temp[] = $vg['create_time'];
				$temp[] = iconv('utf-8', 'gbk',date("Y-m-d",$vg['valid_time']));
				$deal = $vg['is_deal']?"已处理":"未处理";
				$temp[] = iconv('utf-8', 'gbk',$deal);
				$temp[] = $is_form;
				$temp[] = $is_activity;
				echo implode("\t",$temp)."\n";
			}
			die();
		}
		//统计
	    $pcount=$users_model->where(array("user_type"=>2,"level"=>0))->count(); //普通会员
		$p1count=$users_model->where(array("user_type"=>2,"level"=>1))->count(); //399会员量
		$p2count=$users_model->where(array("user_type"=>2,"level"=>2))->count(); //3999会员量
		$p3count=$users_model->where(array("user_type"=>2,"level"=>3))->count(); //只能用5套营销工具的

		$this->assign("count",$count);
		$this->assign("pcount",$pcount);
		$this->assign("p1count",$p1count);
		$this->assign("keyword",$keyword);
		$this->assign("p2count",$p2count);
		$this->assign("p3count",$p3count);

		$this->assign("level",$level);
//    	$this->assign('lists', $lists);
		$this->assign('lists', $lists);
		//$this->assign("formget",$_POST);
		$this->assign("formget",$_GET);
		//$this->assign("current_page",$page->GetCurrentPage());
		unset($_GET[C('VAR_URL_PARAMS')]);
		//$this->assign("formget",$_GET);
    	$this->assign("page", $page->show('Admin'));
    	
    	$this->display(":index");
    }
    function edit(){
		$id=intval($_GET['id']);
    	$rst = M("Users")->where(array("id"=>$id,"user_type"=>2))->find();
		//var_dump($rst);
		if(empty($rst)){
			//$this->error("用户不存在");
		}
		$roles = array("1"=>"幼儿培训","2"=>"初高中培训","3"=>"幼儿园","4"=>"早教机构","5"=>"文化课培训","6"=>"素质教育课程","7"=>"其他");
		$amount = array("1"=>"300人以下","2"=>"300-800人","3"=>"800-3000人","4"=>"3000人以上");
		$permissions = D("slide")->where(array('slide_cid'=>1,'slide_status'=>1))->select();
		$this->assign("permissions",$permissions);
		$this->assign($rst);
		$this->assign("roles",$roles);
		$this->assign("amount",$amount);
		$this->display(":edit");
	}
	//备注信息处理
	function remark(){
		$id=intval($_GET['id']);
    	$rst = M("Users")->where(array("id"=>$id,"user_type"=>2))->find();
		$roles = array("1"=>"幼儿培训","2"=>"初高中培训","3"=>"幼儿园","4"=>"早教机构","5"=>"文化课培训","6"=>"素质教育课程","7"=>"其他");
		$amount = array("1"=>"300人以下","2"=>"300-800人","3"=>"800-3000人","4"=>"3000人以上");
		$permissions = D("slide")->where(array('slide_cid'=>1,'slide_status'=>1))->select();
		$this->assign("permissions",$permissions);
		$this->assign($rst);
		$this->assign("roles",$roles);
		$this->assign("amount",$amount);
		$this->display(":remark");
	}
	//保存备注信息
	function remark_post(){
		$_POST['permission'] = implode(",",I('post.permission'));
		$_POST['update_time'] = time();
		$_POST['valid_time'] = strtotime($_POST['valid_time']);
		$id = I("post.id",0,"intval");
		$users_model=M("Users");
		if($users_model->create()){
			if($id){
				$res = $users_model->save();
			}else{
				$res = $users_model->add();
			}
		}
		if($res){
			$this->success("保存成功！");
		}else{
			$this->error('保存失败！');
		}
	}
	//用户信息处理
	function edit_post(){
		$item = I("post.item");
		$id = I("post.id",0,"intval");
		$_POST['item'] = implode(",",$item);
		$_POST['user_type'] =2;
		$_POST['user_login'] =$_POST['user_login'];
		$_POST['permission'] = implode(",",I('post.permission'));
		$_POST['update_time'] = time();
		$_POST['mobile'] = $_POST['user_login'];
		$users_model=M("Users");
		$password = I("post.user_pass");
		if(empty($password)){
			unset($_POST['user_pass']);
		}else{
			$_POST['user_pass'] = sp_password($password);
		}
		if(!$id){
			$_POST['create_time'] =  date("Y-m-d H:i:s");
			$_POST['update_time'] = time();
		}else{
			$user_t = $users_model->where(array("id"=>$id))->find();
			if($user_t['create_time'] == "0000-00-00 00:00:00"){
				$_POST['create_time'] =  date("Y-m-d H:i:s");
			}
		}
		$_POST['valid_time'] = strtotime($_POST['valid_time']);
		
		if($users_model->create()){
			if($id){
				$res = $users_model->save();
			}else{
				$res = $users_model->add();
			}
		}
		if($res){
			$this->success("保存成功！");
		}else{
			$this->error('保存失败！');
		}
	}
    function ban(){
    	$id=intval($_GET['id']);
    	if ($id) {
    		$rst = M("Users")->where(array("id"=>$id,"user_type"=>2))->setField('user_status','0');
    		if ($rst) {
    			$this->success("会员拉黑成功！", U("indexadmin/index"));
    		} else {
    			$this->error('会员拉黑失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
    public function delete(){
		$id=intval($_GET['id']);
		if($id){
			$rst = M("Users")->where(array('id'=>$id))->delete();
			if ($rst) {
    			$this->success("会员删除成功！", U("indexadmin/index"));
    		} else {
    			$this->error('会员删除失败！');
    		}
		}else{
			$this->error('数据传入失败！');
		}
	}
    function cancelban(){
    	$id=intval($_GET['id']);
    	if ($id) {
    		$rst = M("Users")->where(array("id"=>$id,"user_type"=>2))->setField('user_status','1');
    		if ($rst) {
    			$this->success("会员启用成功！", U("indexadmin/index"));
    		} else {
    			$this->error('会员启用失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }
}
