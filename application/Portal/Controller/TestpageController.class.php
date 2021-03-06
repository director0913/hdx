<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace Portal\Controller;
use Common\Controller\MemberbaseController; 
/**
 * 首页
 */
class TestpageController extends MemberbaseController {
	
	/**
	*创建D模型
	**/
	protected $form_model;
	protected $formitem_model;
	protected $formdata_model;
	protected $formrow_model;

	function _initialize() {
		parent::_initialize();
		$this->form_model = D("Common/Form");
		$this->formitem_model = D("Common/FormItem");
		$this->formdata_model = D("Common/FormData");
		$this->formrow_model = D("Common/FormRows");
	}
	/**
	*首页
	**/
	public function index() {
		//查询所有信息
		$uid=get_current_userid();
		$activity_num=$this->form_model->where('uid='.$uid)->order('id asc')->count();
		$this->assign('activity_num',$activity_num);
		/*$form_list=$this->form_model->where('uid='.$uid)->order('id asc')->select();
		foreach($form_list as &$v){
			$total = $this->formrow_model->where("form_id=".$v[id])->count();
			$v['total'] = $total;
		}
		$this->assign('form_list',$form_list); */
		$where = 'uid='.$uid;
		$count = $this->form_model->where($where)->count();
		$pagecount = 5;
		$page = new \Think\Page($count , $pagecount);
		$page->parameter = $row; //此处的row是数组，为了传递查询条件
		/*$page->setConfig('first','首页');
		$page->setConfig('prev','上一页');
		$page->setConfig('next','下一页');
		$page->setConfig('last','尾页');*/
		$page->setConfig('prev','<');
		$page->setConfig('next','>');
		
		//$page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% 第 '.I('p',1).' 页/共 %TOTAL_PAGE% 页 ( '.$pagecount.' 条/页 共 %TOTAL_ROW% 条)');
		$show = $page->show();
		$list = $this->form_model->where($where)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
		
		
		
    	$this->display(":testpage");
    }
	
	/**
	*添加
	**/
	public function add_post() {
		$_POST['uid']=get_current_userid();
		if(IS_POST){
			if ($this->form_model->create()){
				if ($this->form_model->add()!==false) {
					$this->success(L('ADD_SUCCESS'), U("activity/index"));
				} else {
					$this->error(L('ADD_FAILED'));
				}
			} else {
				$this->error($this->form_model->getError());
			}
		}
    }	
	/**
	*删除
	**/
	public function delete() {
		$id=I('get.id',0,'intval');
		if ($this->form_model->delete($id)!==false) {
			if($this->formitem_model->where('form_id='.$id)->delete()){
				$this->success("删除成功！",U("activity/index"));
			}else{
				$this->error("删除失败！");
			}
		} else {
			$this->error("删除失败！");
		}
    }
	/**
	*编辑，显示原始数据
	**/
	public function edit() {
		$id=I('get.id',0,'intval');
		$info=$this->form_model->where('id='.$id)->find();
		if(empty($info)){
			$this->error("暂时没有信息！");
		}
		
		$begin_fen =  date("H:i",$info['begintime']);
		
		$end_fen = date("H:i",$info['endtime']);
		$time_array = array(
			"00:00","0:30","01:00","01:30","02:00","02:30","03:00","03:30","04:00","04:30","05:00","05:30","06:00","06:30","07:00","07:30","08:00","08:30",
			"09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30",
			"17:00","17:30","18:00","18:30","19:00","19:30","20:00","20:30","21:00","21:30","22:00","22:30","23:00","23:30"
		);
		//分析模板的附图
		$tpl = $this->form_model->where('id='.$info['tid'])->find();
		$photos = json_decode($tpl['thumb'],true);
		//var_dump($photos);
		$this->assign('photos',$photos);
		$this->assign('info',$info);
		/**
		*私有表单部分
		**/
		$form_item_list=$this->formitem_model->where('form_id='.$id)->order('id asc')->select();
		/***
		*分析此模板是否有报名数据
		**/
		$form_data_count = $this->formrow_model->where(array('form_id'=>$id))->count();
		$this->assign('form_item_list',$form_item_list); 
		
		$this->assign('form_data_count',$form_data_count); 
		$this->assign("time",$time_array);
		$this->assign("begin_fen",$begin_fen);
		$this->assign("end_fen",$end_fen);
    	$this->display(":editactivity");
    }
	/**
	*更新 ，数据
	**/
	public function edit_post() {
		$id=$_POST['id'];
		$data['name']=$_POST['name'];
		$data['begintime']=strtotime($_POST['begintime']." ".$_POST['begintime_fen']);
		$data['endtime']=strtotime($_POST['endtime']." ".$_POST['endtime_fen']);
		$data['address']=$_POST['address'];
		$data['total_limit']=I('post.total_limit',0,'intval');
		$data['description']=htmlspecialchars_decode($_POST['description']);
		$data['rule']=$_POST['rule'];
		$data['thumb']=$_POST['thumb'];
		$data['createtime']=time();
		$data['is_distri']=I("post.is_distri");
		$data['hide_mobile']=I("post.hide_mobile");
		$data['hide_username']=I("post.hide_username");
		$data['contact']=I("post.contact");
		$data['phone']=I("post.phone");
		$data['coupon_price']=I("post.coupon_price");
		$danxuan_str = htmlspecialchars_decode(I("post.danxun_str"));
		$duoxuan_str = htmlspecialchars_decode(I("post.duoxuan_str"));
		
		$danxuan = json_decode($danxuan_str,true);
		$duoxuan = json_decode($duoxuan_str,true);
		//$images=I('post.images');
		//var_dump($images);
		//die();
		if (IS_POST) {
			if ($this->form_model->where('id='.$id)->save($data)!==false) {
				$fids = I("post.fids");
				$text=I('post.text');
				$for_index = 0;
				$textids = I("post.textids");
				foreach($text as $key=>$v){
					$form_temp = array(
						'name' =>$v
					);
					if($textids[$key]){
						$result=$this->formitem_model->where(array('id'=>$textids[$key]))->save($form_temp);
					}else{
						$form_temp = array(
							'name' =>$v,
							'form_id'=>$id,
							 'type' => 1
						);
						$this->formitem_model->add($form_temp);
					}
					//$for_index ++;
				}
				$number=I('post.number');
				$numberids=I('post.numberids');
				foreach($number as $key=>$v){
					$form_temp = array(
						'name' =>$v
					);
					if($numberids[$key]){
						$result=$this->formitem_model->where(array('id'=>$numberids[$key]))->save($form_temp);
						
					}else{
						$form_temp = array(
							'name' =>$v,
							'form_id'=>$id,
							 'type' => 2
						);
						$this->formitem_model->add($form_temp);
					}
					//$for_index ++;
				}
			
				$images=I('post.images');
				$imagesids=I('post.imagesids');
				foreach($images as $key=>$v){
					$form_temp = array(
						'name' =>$v
					);
					if($imagesids[$key]){
						$result=$this->formitem_model->where(array('id'=>$imagesids[$key]))->save($form_temp);
					}else{
						$form_temp = array(
							'name' =>$v,
							'form_id'=>$id,
							 'type' => 4
						);
						$this->formitem_model->add($form_temp);
					}
					//$for_index ++;
				}
				$textarea=I('post.textarea');
				$textareaids=I('post.textareaids');
				foreach($textarea as $key=>$v){
					$form_temp = array(
						'name' =>$v
					);
					if($textareaids[$key]){
						$result=$this->formitem_model->where(array('id'=>$textareaids[$key]))->save($form_temp);
					}else{
						$form_temp = array(
							'name' =>$v,
							'form_id'=>$id,
							 'type' => 5
						);
						$this->formitem_model->add($form_temp);
					}
					//$for_index ++;
				}
				foreach($danxuan as $v){
					$form_temp = array(
						'form_id'=>	$id,
						'type' =>6,
						'default' =>$v['item'],
						'name' =>$v['title']
					);
					if($v['id']){
						$this->formitem_model->where(array('id'=>$v['id']))->save($form_temp);
					}else{
						$this->formitem_model->add($form_temp);
					}
				}
				foreach($duoxuan as $v){
					$form_temp = array(
						'form_id'=>	$id,
						'type' =>7,
						'default' =>$v['item'],
						'name' =>$v['title']
					);
					if($v['id']){
						$this->formitem_model->where(array('id'=>$v['id']))->save($form_temp);
					}else{
						$this->formitem_model->add($form_temp);
					}
					
				}
				$this->success("保存成功！", U("activity/index"));
				
			} else {
				$this->error("保存失败！");
			} 
		}
    }
	/**
	*查id
	**/
	public function query()
	{
		$id=I('post.cx',0,'intval');
		$uid=get_current_userid();
		if($id!=0)
		{
			$form_list=$this->form_model->where('id='.$id.' AND uid='.$uid)->select();
		}
		else
		{
			$form_list=$this->form_model->order('id asc')->select();
		}
		if(!empty($form_list))
		{
			$this->assign('form_list',$form_list); 
			$this->display(":activity");
		}
		else
		{
			$this->error("查找失败！");
		}
	}
	/**
	*增加报名选项
	**/
	public function form_item_add_post()
	{
		$id=$_POST['form_id'];
		if(IS_POST){
			$form_item=D('form_item');
			$rules = array(
					//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
					array('name', 'require', '名称不能为空！', 1 ),
					array('type', 'require', '类型不能为空！', 1 )
			);
			if($form_item->validate($rules)->create()===false){
				$this->error($form_item->getError());
			}else
			{
				if ($form_item->add()!==false) {
					$this->success(L('ADD_SUCCESS'), U("activity/edit/id/".$id));
				} else {
					$this->error(L('ADD_FAILED'));
				}
			}
		}
	}
	/**
	*删除报名选项
	**/
	public function form_item_delete()
	{
		$id = I("post.id",0,"intval");
		if ($this->formitem_model->delete($id)!==false) {
			//删除与之相关的报名数据
			$this->formdata_model->where(array('item_id'=>$id))->delete();
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}
	/**
	*编辑报名选项
	**/
	public function form_item_edit()
	{
		$id=I('get.form_item_id',0,'intval');
		$info=$this->formitem_model->where('id='.$id)->find();
		if(empty($info)){
			$this->error("暂时没有信息！");
		}
		$this->assign('info',$info);
    	$this->display(":form_item_edit");
	}
	/**
	*更新报名选项
	**/
	public function form_item_edit_post()
	{
		if (IS_POST) {
			if ($this->formitem_model->create()) {
				if ($this->formitem_model->save()!==false) {
					$this->success("保存成功！", U("activity/edit/id/".$_POST['form_id']));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($form_item->getError());
			}
		}
	}
	/**
	*显示参加活动情况
	**/
	public function show_join()
	{
		$form_id=I('get.id',0,'intval');
		$this->assign('select',2);
		$activity=$this->form_model->where('id='.$form_id)->find();
		$this->assign('activity',$activity);
		$form_item_info=$this->formitem_model->where('form_id='.$form_id)->order('id asc')->select();
		if(empty($form_item_info))
		{
			$this->error("查看失败！");
		}
		$this->assign('form_item_info',$form_item_info);
		$header = array();
		$header = array("微信标示","昵称","头像");
		foreach($form_item_info as $v){
			$header[] = $v['name'];
		}
		/**查询出所有的数据**/
		$total = $this->formrow_model->where(array('form_id'=>$form_id))->count();
		$form_row = $this->formrow_model->where(array('form_id'=>$form_id))->select();
		foreach($form_row as &$v){
			$form_data = $this->formdata_model->where(array('form_id'=>$form_id,'from_user'=>$v['from_user']))->select();
			foreach($form_data as $vv){
				$v['fields'][$vv['item_id']] = $vv['value'];
			}
		}
		$uid=get_current_userid();
		$activity_num=$this->form_model->where('uid='.$uid)->order('id asc')->count();
		$this->assign('activity_num',$activity_num);
		$this->assign("total",$total);
		foreach($form_row as $key=>&$v)
		{
			$sql="select f.name,d.value from cmf_form_data d,cmf_form_item f where d.form_id=f.form_id and d.item_id=f.id and f.form_id=".$form_id." and d.uid=".$form_row[$key]['id'];
		    $res = M()->query($sql);
			$v['info_detail']=$res;
		}
		
		$sql="select distinct f.* from cmf_form_rows f,cmf_form_rows c where f.id=c.pid and f.form_id={$form_id}";
		$yaoqing = M()->query($sql);
		foreach($yaoqing as $key=>&$vv)
		{
			$sql="select f.name,d.value from cmf_form_data d,cmf_form_item f where d.form_id=f.form_id and d.item_id=f.id and f.form_id=".$form_id." and d.uid=".$yaoqing[$key]['id']." ;";
		    $res = M()->query($sql);
			$vv['info_detail']=$res;
		}
		//邀请人uid=当前id
		foreach($yaoqing as $key=>&$yq)
		{
			$sql="select * from cmf_form_rows  where pid=".$yaoqing[$key]['id'];
			$res= M()->query($sql);
			foreach($res as $j=>&$yq_detail)
			{
				$query="select f.name,d.value from cmf_form_data d,cmf_form_item f where d.form_id=f.form_id and d.item_id=f.id and f.form_id=".$form_id." and d.uid=".$yq_detail['id'];
		    	$detail = M()->query($query);
				$yq_detail['info_detail']=$detail;
			}
			$yq['relation']=$res;
		}
		$this->assign("yaoqing",$yaoqing);
		$sql="select count(*) from (
									 select distinct f.from_user 
									  from cmf_form_rows f,cmf_form_rows c 
									 where f.id=c.pid and f.form_id={$form_id} 
									) b";
		//处理导出数据
		$export_type = I("get.export_type");
		if($export_type=='baoming'){

			$this->success("报名数据导出成功！");
		}elseif($export_type=='yaoqing'){
			$this->success("邀请数据导出成功！");
		}
		$yaoqing_count = M()->query($sql);
		$yaoqing_num=$yaoqing_count[0]['count(*)'];
		$this->assign("yaoqing_num",$yaoqing_num);
		$this->assign("info_array",$info_array);
		$this->assign("header",$header);
		$this->assign("form_row",$form_row);
		$this->display(":show_join");
	}
	/**
	* 导出报名数据
	**/
	public function export_data(){
		$form_id = I("get.id",0,"intval");
		$form = $this->form_model->where(array("id"=>$form_id))->find();
		$form_item_info=$this->formitem_model->where('form_id='.$form_id)->order('id asc')->select();
		$form_row = $this->formrow_model->where(array('form_id'=>$form_id))->select();
		foreach($form_row as &$v){
			$form_data = $this->formdata_model->where(array('form_id'=>$form_id,'from_user'=>$v['from_user']))->select();
			foreach($form_data as $vv){
				$v['fields'][$vv['item_id']] = $vv['value'];
			}
		}
		$header = array("姓名","手机号","邀请者");
		foreach($form_item_info as $v){
			$header[]=$v['name'];
		}
		$list = array();
		
		foreach($form_row as $v){
			$tmp = array(
				$v['username'],
				$v['mobile']
				//$v['pid'],
			);
			if($v['pid']){
				$ttd = $this->formrow_model->where(array('id'=>intval($v['pid'])))->find();
				$tmp[] = $ttd['username'];
			}else{
				$tmp[] ="";
			}
			foreach($form_item_info as $vv){
				$tmp_id = $vv['id'];
				$tmp[]=$v['fields'][$tmp_id];
			}
			$list[] = $tmp;
		}
		$filename = time();
		header("Content-type:application/vnd.ms-excel");
		header("Content-Disposition:attachment;filename=".$filename.".xls");
		echo iconv('utf-8', 'gbk', implode("\t", $header)), "\n";
		foreach($list as $vg){
			echo iconv('utf-8', 'gbk', implode("\t", $vg)), "\n";
		}
		exit();
	}
	/**
	*删除报名用户数据
	**/
	public function delete_join(){
		$from_user = I('get.from_user');
		$form_id = I("get.form_id",0,"intval");
		$this->formrow_model->where(array('form_id'=>$form_id,'from_user'=>$from_user))->delete();
		$this->formdata_model->where(array('form_id'=>$form_id,'from_user'=>$from_user))->delete();
		$this->success("删除成功！",U('activity/show_join',array('id'=>$form_id)));
	}
	
	/**
	*发布活动
	**/
	public function addactivity()
	{
		$id=I('get.tpl_id',0,'intval');
		$info=$this->form_model->where('id='.$id)->find();
		if(empty($info)){
			$this->error("暂时没有信息！");
		}
		$this->assign('info',$info);
		$photos = json_decode($info['thumb'],true);
		$this->assign('photos',$photos);
		/**
		*私有表单部分
		**/
		$form_item_list=$this->formitem_model->where('form_id='.$id)->order('id asc')->select();
		$this->assign('form_item_list',$form_item_list); 
  	
		$this->display(":addactivity");
	}
	/**
	*提交活动post
	**/
	public function addactivity_post()
	{
		if(IS_POST){
			$data['uid']=get_current_userid();
			$data['name']=$_POST['name'];
			$data['begintime']=strtotime($_POST['begintime']." ".$_POST['begintime_fen']);
			$data['endtime']=strtotime($_POST['endtime']." ".$_POST['endtime_fen']);
			$data['address']=$_POST['address'];
			$data['total_limit']=I('post.total_limit',0,'intval');
			$data['description']=htmlspecialchars_decode($_POST['description']);
			$data['rule']=$_POST['rule'];
			//$data['option1']=$['option1'];
			$data['option1']=I("post.option1");
			$data['option2']=I("post.option2");
			$data['option3']=I("post.option3");
			$data['option4']=I("post.option4");
			$data['option5']=I("post.option5");
			$data['contact']=I("post.contact");
			$data['phone']=I("post.phone");
			$data['coupon_price']=I("post.coupon_price");

			$data['tid']=I("post.tid");
			$data['is_distri']=I("post.is_distri");
			$data['hide_mobile']=I("post.hide_mobile");
			$data['hide_username']=I("post.hide_username");
			$data['createtime']=time();
			$danxuan_str = htmlspecialchars_decode(I("post.danxun_str"));
			$duoxuan_str = htmlspecialchars_decode(I("post.duoxuan_str"));
			
			$danxuan = json_decode($danxuan_str,true);
			$duoxuan = json_decode($duoxuan_str,true);

			
			if ($this->form_model->create()){
				$result=$this->form_model->add($data);
				if($result){
					//开始添加私有表单部分
					$text=I('post.text');
					foreach($text as $v){
						$form_temp = array(
							'form_id'=>	$result,
							'type' =>1,
							'name' =>$v
						);
						$this->formitem_model->add($form_temp);
					}
					$number=I('post.number');
					foreach($number as $v){
						$form_temp = array(
							'form_id'=>	$result,
							'type' =>2,
							'name' =>$v
						);
						$this->formitem_model->add($form_temp);
					}
					$images=I('post.images');
					foreach($images as $v){
						$form_temp = array(
							'form_id'=>	$result,
							'type' =>4,
							'name' =>$v
						);
						$this->formitem_model->add($form_temp);
					}
					$textarea=I('post.textarea');
					foreach($textarea as $v){
						$form_temp = array(
							'form_id'=>	$result,
							'type' =>5,
							'name' =>$v
						);
						$this->formitem_model->add($form_temp);
					}
					foreach($danxuan as $v){
						$form_temp = array(
							'form_id'=>	$result,
							'type' =>6,
							'default' =>$v['item'],
							'name' =>$v['title']
						);
						$this->formitem_model->add($form_temp);
					}
					foreach($duoxuan as $v){
						$form_temp = array(
							'form_id'=>	$result,
							'type' =>7,
							'default' =>$v['item'],
							'name' =>$v['title']
						);
						$this->formitem_model->add($form_temp);
					}
					$this->success("保存成功！", U("activity/index"));	
			} else {
					$this->error(L('ADD_FAILED'));
			}
			} else {
				$this->error($this->form_model->getError());
			}
		}
		$this->display(":testaddactivity");
	}
	/*更新备注
	**/
	public function remark_post()
	{
		$id=I('post.id',0,'intval');
		$data['remark']=I('post.remark');
		if($this->formrow_model->where('id='.$id)->save($data))
		{
			$res['status']  = 1;
		}
		else
		{
			$res['status']  = -1;
			$dres['msg']  = "添加备注失败";
			
		}
		$this->ajaxReturn($res);
		
	}
	/*删除，改变status状态
	**/
	public function status_post()
	{
		$id=I('post.id',0,'intval');
		$data['status']=0;
		if($this->formrow_model->where('id='.$id)->save($data))
		{
			$res['status']  = 1;
			$res['msg']  = "删除报名成功";
		}
		else
		{
			$res['status']  = -1;
			$res['msg']  = "删除报名失败";
			
		}
		$this->ajaxReturn($res);
		
	}
	public function show_baoming(){
		$form_id=I('get.form_id',0,'intval');
		$status=I('get.status',0,'intval');
		$this->assign('select',$status);
		$activity=$this->form_model->where(array('id'=>$form_id))->find();
		$this->assign('activity',$activity);
		$form_item_info=$this->formitem_model->where(array('form_id'=>$form_id))->order('id asc')->select();
		if(empty($form_item_info))
		{
			$this->error("查看失败！");
		}
		$this->assign('form_item_info',$form_item_info);
		$header = array();
		$header = array("微信标示","昵称","头像");
		foreach($form_item_info as $v){
			$header[] = $v['name'];
		}
		/**查询出所有的数据**/
		$total = $this->formrow_model->where(array('form_id'=>$form_id,'status'=>$status))->count();
		$form_row = $this->formrow_model->where(array('form_id'=>$form_id,'status'=>$status))->select();
		foreach($form_row as &$v){
			$form_data = $this->formdata_model->where(array('form_id'=>$form_id,'from_user'=>$v['from_user']))->select();
			foreach($form_data as $vv){
				$v['fields'][$vv['item_id']] = $vv['value'];
			}
		}
		$uid=get_current_userid();
		$activity_num=$this->form_model->where(array('uid'=>$uid))->order('id asc')->count();
		$this->assign('activity_num',$activity_num);
		$this->assign("total",$total);
		foreach($form_row as $key=>&$v)
		{
			$sql="select f.name,d.value from cmf_form_data d,cmf_form_item f where d.form_id=f.form_id and d.item_id=f.id and f.form_id=".$form_id." and d.uid=".$form_row[$key]['id'];
		    $res = M()->query($sql);
			$v['info_detail']=$res;
		}
		$sql="select distinct f.* from cmf_form_rows f,cmf_form_rows c where f.id=c.pid and f.form_id={$form_id} and f.status={$status}";
		$yaoqing = M()->query($sql);
		foreach($yaoqing as $key=>&$vv)
		{
			$sql="select f.name,d.value from cmf_form_data d,cmf_form_item f where d.form_id=f.form_id and d.item_id=f.id and f.form_id=".$form_id." and d.uid=".$yaoqing[$key]['id'];
		    $res = M()->query($sql);
			$vv['info_detail']=$res;
		}
		//邀请人uid=当前id
		foreach($yaoqing as $key=>&$yq)
		{
			$sql="select * from cmf_form_rows  where pid=".$yaoqing[$key]['id']." and status=".$status;
			$res= M()->query($sql);
			$yq['relation']=$res;
		}
		$this->assign("yaoqing",$yaoqing);
		//$sql="select count(*) from cmf_form_rows  where form_id=".$form_id." AND pid=0 and status=".$status;
		$sql="select count(*) from (
									 select distinct f.from_user 
									  from cmf_form_rows f,cmf_form_rows c 
									 where f.id=c.pid and f.form_id={$form_id} and f.status={$status} 
									) b";
		$yaoqing_count = M()->query($sql);
		$yaoqing_num=$yaoqing_count[0]['count(*)'];
		$this->assign("yaoqing_num",$yaoqing_num);
		$this->assign("info_array",$info_array);
		$this->assign("header",$header);
		$this->assign("form_row",$form_row);
		$this->display(":show_join");
	}
	
}


