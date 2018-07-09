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
class AppController extends MemberbaseController {
	
	/**
	*创建D模型
	**/
	protected $form_model;
	protected $activity_model;
	protected $activityfriend_model;
	protected $activityparams_model;
	protected $activityuser_model;
	protected $activityuserdata_model;
	protected $activityuserprize_model;

	function _initialize() {
		parent::_initialize();
		$this->form_model = D("Common/Form");
		$this->activity_model = D("Common/Activity");
		$this->activityfriend_model = D("Common/ActivityFriend");
		$this->activityparams_model = D("Common/ActivityParams");
		$this->activityuser_model = D("Common/ActivityUser");
		$this->activityuserdata_model = D("Common/ActivityUserData");
		$this->activityuserprize_model = D("Common/ActivityUserPrize");
	}
	/**
	*首页
	**/
	public function index() {
		$uid=get_current_userid();
		$where = 'uid='.$uid.' and valid = 1';
		//查询活动数量。
		$activity_num=$this->form_model->where($where)->count();
		$this->assign('activity_num',$activity_num);
		//查询应用数量。
		$app_num=$this->activity_model->where($where)->count();
		$this->assign('app_num',$app_num);
		
		//查询出符合条件的活动数量。
		$keyword=I('get.keyword');
		if($keyword)
		{
			$where.=" and title like '%{$keyword}%'";
		}
		$app_cha_num=$this->activity_model->where($where)->count();
		$users = D('users'); 
		$users_info=$users->where('id='.$uid)->find();
		$this->assign('users_info',$users_info);
		$pagecount = 5;
		$page = new \Think\Page($app_cha_num , $pagecount);
		$page->setConfig('prev','<');
		$page->setConfig('next','>');
		$show = $page->show();
		$list = $this->activity_model->where($where)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
		foreach($list as &$v){
			//$total = $this->formrow_model->where("form_id=".$v[id])->count();
			$total = $this->activityuser_model->where(array("aid"=>$v[id]))->count();
			$v['total'] = $total;
		}
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->assign('keyword',$keyword);
		$full=1;
		if(empty(trim($users_info['school']))&&empty(trim($users_info['contact']))&&empty(trim($users_info['mobile'])))
		{
			//echo "xxx:需要提醒！！";
			if($_SESSION['is_notice']==1){
				$full=0;
			}
		}
		else $full=0;
		$this->assign('full',$full);
		$this->display(":app");
    }
	/**
	*删除
	**/
	public function delete() {
		$id=I('post.id',0,'intval');
		//修改活动状态。
		$uid=get_current_userid();
		if ($this->activity_model->where(array("id"=>$id,"uid"=>$uid))->save(array('valid'=>0))!=false){
			$this->success("删除成功！",U("app/index"));
		} else {
			$this->error("删除失败！");
		}
    }
	/**
	*显示参加活动情况
	**/
	public function app_show_join()
	{
		$uid=get_current_userid();
		$where = 'uid='.$uid.' and valid = 1';
		//查询活动数量。
		$activity_num=$this->form_model->where($where)->count();
		$this->assign('activity_num',$activity_num);
		//查询应用数量。
		$app_num=$this->activity_model->where($where)->count();
		$this->assign('app_num',$app_num);
		
		//获取参数信息。
		$id=I('get.id',0,'intval');
		//获取活动信息。
		$activity=$this->activity_model->where('id='.$id)->find();
		$this->assign('activity',$activity);
		$keyword=I('get.keyword');
		$this->assign('id',$id);
		$this->assign('keyword',$keyword);
		$users = D('users'); 
		$users_info=$users->where('id='.$uid)->find();
		$this->assign('users_info',$users_info);
		//根据参数查询对应信息。
		$where = 'aid='.$id;
		//查询所有报名数量。
		$user_all_num=$this->activityuser_model->where($where)->count();
		$this->assign('user_all_num',$user_all_num);
		if($keyword)
		{
			$where.=" and nickname like '%{$keyword}%'";
		}
		//查询符合条件的报名数量。
		$user_num=$this->activityuser_model->where($where)->count();
		$pagecount = 5;
		$page = new \Think\Page($user_num , $pagecount);
		$page->parameter = array('id'=>$id); 
		//$page->parameter = $row; 
		$page->setConfig('prev','<');
		$page->setConfig('next','>');
		$show = $page->show();
		$list = $this->activityuser_model->where($where)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display(":app_show_join");
	}
	/**
	*删除报名选项
	**/
	public function form_item_delete()
	{
		$id = I("post.id",0,"intval");
		if ($this->activityuser_model->delete($id)!==false) {
			//删除与之相关的报名数据
			$this->activityuserprize_model->where(array('uid'=>$id))->delete();
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}
	/**
	*显示参加帮助情况
	**/
	public function app_show_help()
	{
		$uid=get_current_userid();
		$where = 'uid='.$uid.' and valid = 1';
		//查询活动数量。
		$activity_num=$this->form_model->where($where)->count();
		$this->assign('activity_num',$activity_num);
		//查询应用数量。
		$app_num=$this->activity_model->where($where)->count();
		$this->assign('app_num',$app_num);
		$users = D('users'); 
		$users_info=$users->where('id='.$uid)->find();
		$this->assign('users_info',$users_info);
		
		//获取参数信息。
		$id=I('get.aid',0,'intval');
		$userid=I('get.userid',0,'intval');
		//获取活动信息。
		$activity=$this->activity_model->where('id='.$id)->find();
		$this->assign('activity',$activity);
		$keyword=I('get.keyword');
		$this->assign('id',$id);
		$this->assign('userid',$userid);
		$this->assign('keyword',$keyword);
		//根据参数查询对应信息。
		$where = 'aid='.$id." and uid=".$userid;
		//查询所有报名数量。
		$user_all_num=$this->activityfriend_model->where($where)->count();
		$this->assign('user_all_num',$user_all_num);
		if($keyword)
		{
			$where.=" and nickname like '%{$keyword}%'";
		}
		//查询符合条件的报名数量。
		$user_num=$this->activityfriend_model->where($where)->count();
		$pagecount = 5;
		$page = new \Think\Page($user_num , $pagecount);
		$page->parameter = array('aid'=>$id,'userid'=>$userid); 
		//$page->parameter = $row; 
		$page->setConfig('prev','<');
		$page->setConfig('next','>');
		$show = $page->show();
		$list = $this->activityfriend_model->where($where)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display(":app_show_help");
	}
	/**
	*删除报名选项
	**/
	public function form_help_delete()
	{
		$id = I("post.id",0,"intval");
		if ($this->activityfriend_model->delete($id)!==false) {
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}
	/**
	* 导出报名数据
	**/
	/*public function export_data(){
		$aid = I("get.id",0,"intval");
		$help = I("get.help",0,"intval");
		$userlist=$this->activityuser_model->where('aid='.$aid." and mobile is not null")->order('id asc')->select();
		$header = array("昵称","姓名","手机号","注册时间","奖品");
		$list = array();
		foreach($userlist as $vf){
			$tmp = array(
				$vf['nickname'],
				$vf['username'],
				$vf['mobile'],
				date("Y-m-d H:m:s",$vf['createtime'])
			);
			$jianglist=$this->activityuserprize_model->where('aid='.$aid." and from_user=".$vf['from_user'])->order('id asc')->select();
			$jiangarr=array();
			for($i=0;$i<count($jianglist);$i++)
			{
				$jiangarr[]=$jianglist[$i]['name'];
			}
			$tmp[]=implode(",",$jiangarr);
			$list[] = $tmp;
			unset($tmp);
		}
		$filename = time();
		header("Content-type:application/vnd.ms-excel");
		header("Content-Disposition:attachment;filename=".$filename.".xls");
		echo iconv('utf-8', 'gbk', implode("\t", $header)), "\n";
		foreach($list as $vg){
			echo iconv('utf-8', 'gbk', implode("\t", $vg)), "\n";
		}
		exit();
	}*/
	public function export_data(){
		$aid = I("get.id",0,"intval");
		$help = I("get.help",0,"intval");
		if($help==1)
		{
			$helpid = I("get.helpid",0,"intval");
			$userlist=$this->activityfriend_model->where('aid='.$aid." and uid=".$helpid)->order('id asc')->select();
			//echo $this->activityfriend_model->getLastSql();die();
		}
		$userlist=$this->activityuser_model->where('aid='.$aid." and mobile is not null")->order('id asc')->select();
		$activity=$this->activity_model->where('id='.$aid)->find();
		$header = array("昵称","姓名","手机号","注册时间","奖品");
		//邀请有礼特殊处理
		if(in_array($activity['type'],array('invitegift','groupbuy'))){
			$header = array("昵称","姓名","手机号","注册时间","推荐人");
		}
		
		$list = array();
		foreach($userlist as $vf){
			$tmp = array(
				$vf['nickname']
			);
			$jianglist=$this->activityuserprize_model->where('aid='.$aid." and from_user='".$vf['from_user']."'")->order('id asc')->select();
			if($help==0)
			{
				$tmp[]=$vf['username'];
				$tmp[]=$vf['mobile'];
			}
			else if(count($jianglist)>0)
			{
				$tmp[]=$jianglist[0]['username'];
				$tmp[]=$jianglist[0]['mobile'];
			}
			$tmp[]=date("Y-m-d H:m:s",$vf['createtime']);
			if(in_array($activity['type'],array('invitegift','groupbuy'))){
				$tmp[]=$vf['pname'];
			}else{
				$jiangarr=array();
				for($i=0;$i<count($jianglist);$i++)
				{
					$jiangarr[]=$jianglist[$i]['name'];
				}
				$tmp[]=implode(",",$jiangarr);
			}
			
			$list[] = $tmp;
			unset($tmp);
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
	public function app_show_prize()
	{
		$uid=get_current_userid();
		$where = 'uid='.$uid.' and valid = 1';
		//查询活动数量。
		$activity_num=$this->form_model->where($where)->count();
		$this->assign('activity_num',$activity_num);
		//查询应用数量。
		$app_num=$this->activity_model->where($where)->count();
		$this->assign('app_num',$app_num);
		$users = D('users'); 
		$users_info=$users->where('id='.$uid)->find();
		$this->assign('users_info',$users_info);
		
		//获取参数信息。
		$aid=I('get.aid',0,'intval');
		$userid=I('get.userid',0,'intval');
		$help=I('get.help',0,'intval');
		//获取活动信息。
		$activity=$this->activity_model->where('id='.$aid)->find();
		$this->assign('activity',$activity);
		$keyword=I('get.keyword');
		$this->assign('aid',$aid);
		$this->assign('keyword',$keyword);
		//如果是帮助，到帮助表中查openid，否则到会员表中查openid。
		if($help==1)
		{
			$user=$this->activityfriend_model->where('id='.$userid)->find();
		}
		else
		{
			$user=$this->activityuser_model->where('id='.$userid)->find();
		}
		//根据参数查询对应信息。
		$where = 'aid='.$aid." and from_user='".$user['from_user']."'";
		//查询所有报名数量。
		$user_all_num=$this->activityuserprize_model->where($where)->count();
		$this->assign('user_all_num',$user_all_num);
		if($keyword)
		{
			$where.=" and name like '%{$keyword}%'";
		}
		//查询符合条件的报名数量。
		$user_num=$this->activityuserprize_model->where($where)->count();
		$pagecount = 5;
		$page = new \Think\Page($user_num , $pagecount);
		$page->parameter = array('aid'=>$aid,'userid'=>$userid,'help'=>$help); 
		//$page->parameter = $row; 
		$page->setConfig('prev','<');
		$page->setConfig('next','>');
		$show = $page->show();
		$list = $this->activityuserprize_model->where($where)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display(":app_show_prize");
	}
	/**
	*删除奖品选项
	**/
	public function form_prize_delete()
	{
		$id = I("post.id",0,"intval");
		if ($this->activityuserprize_model->delete($id)!==false) {
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}
	public function app_answer()
	{
		$uid=get_current_userid();
		$where = 'uid='.$uid.' and valid = 1';
		//查询活动数量。
		$activity_num=$this->form_model->where($where)->count();
		$this->assign('activity_num',$activity_num);
		//查询应用数量。
		$app_num=$this->activity_model->where($where)->count();
		$this->assign('app_num',$app_num);
		$users = D('users'); 
		$users_info=$users->where('id='.$uid)->find();
		$this->assign('users_info',$users_info);
		
		//获取参数信息。
		$aid=I('get.aid',0,'intval');
		$userid=I('get.userid',0,'intval');
		//获取活动信息。
		$activity=$this->activity_model->where('id='.$aid)->find();
		$this->assign('activity',$activity);
		/*$keyword=I('get.keyword');*/
		$this->assign('aid',$aid);
		/*$this->assign('keyword',$keyword);*/
		//根据参数查询对应信息。
		$where = 'aid='.$aid." and uid=".$userid;
		/*if($keyword)
		{
			$where.=" and name like '%{$keyword}%'";
		}*/
		//查询符合条件的报名数量。
		$user_num=D('Common/QuestionnaireUserData')->where($where)->count();
		$pagecount = 5;
		$page = new \Think\Page($user_num , $pagecount);
		$page->parameter = array('aid'=>$aid,'userid'=>$userid); 
		//$page->parameter = $row; 
		$page->setConfig('prev','<');
		$page->setConfig('next','>');
		$show = $page->show();
		$sql="select qud.id,qud.aid,qud.uid,qud.iid,qp.title,qp.item1,qp.item2,qp.item3 from cmf_questionnaire_problem as qp,cmf_questionnaire_user_data as qud where qud.aid=".$aid." and qud.qid=qp.id and qud.uid=".$userid;
		$Model = new \Think\Model();
		$list=$Model->query($sql);
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display(":app_answer");
	}
	public function app_weidiom()
	{
		$uid=get_current_userid();
		$where = 'uid='.$uid.' and valid = 1';
		//查询活动数量。
		$activity_num=$this->form_model->where($where)->count();
		$this->assign('activity_num',$activity_num);
		//查询应用数量。
		$app_num=$this->activity_model->where($where)->count();
		$this->assign('app_num',$app_num);
		$users = D('users'); 
		$users_info=$users->where('id='.$uid)->find();
		$this->assign('users_info',$users_info);
		
		//获取参数信息。
		$aid=I('get.aid',0,'intval');
		$userid=I('get.userid',0,'intval');
		//获取活动信息。
		$activity=$this->activity_model->where('id='.$aid)->find();
		$this->assign('activity',$activity);
		/*$keyword=I('get.keyword');*/
		$this->assign('aid',$aid);
		/*$this->assign('keyword',$keyword);*/
		//根据参数查询对应信息。
		$where = 'aid='.$aid." and uid=".$userid;
		/*if($keyword)
		{
			$where.=" and name like '%{$keyword}%'";
		}*/
		//查询符合条件的报名数量。
		
		$user_num=D('Common/QuestionnaireUserData')->where($where)->count();
		$pagecount = 5;
		$page = new \Think\Page($user_num , $pagecount);
		$page->parameter = array('aid'=>$aid,'userid'=>$userid); 
		//$page->parameter = $row; 
		$page->setConfig('prev','<');
		$page->setConfig('next','>');
		$show = $page->show();
		//$sql="select qud.id,qud.aid,qud.uid,qud.iid,qp.title,qp.item1,qp.item2,qp.item3 from cmf_questionnaire_problem as qp,cmf_questionnaire_user_data as qud where qud.aid=".$aid." and qud.qid=qp.id and qud.uid=".$userid;
		$sql ="select * from cmf_activity_friend where aid=".$aid." and uid=".$userid;
		$Model = new \Think\Model();
		$list=$Model->query($sql);
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->assign('sql',$sql);
		$this->display(":app_weidiom");
	}
	public function app_weicheer()
	{
		$uid=get_current_userid();
		$where = 'uid='.$uid.' and valid = 1';
		//查询活动数量。
		$activity_num=$this->form_model->where($where)->count();
		$this->assign('activity_num',$activity_num);
		//查询应用数量。
		$app_num=$this->activity_model->where($where)->count();
		$this->assign('app_num',$app_num);
		$users = D('users'); 
		$users_info=$users->where('id='.$uid)->find();
		$this->assign('users_info',$users_info);
		
		//获取参数信息。
		$aid=I('get.aid',0,'intval');
		$userid=I('get.userid',0,'intval');
		//获取活动信息。
		$activity=$this->activity_model->where('id='.$aid)->find();
		$this->assign('activity',$activity);
		/*$keyword=I('get.keyword');*/
		$this->assign('aid',$aid);
		/*$this->assign('keyword',$keyword);*/
		//根据参数查询对应信息。
		$where = 'aid='.$aid." and uid=".$userid;
		/*if($keyword)
		{
			$where.=" and name like '%{$keyword}%'";
		}*/
		//查询符合条件的报名数量。
		
		$user_num=D('Common/QuestionnaireUserData')->where($where)->count();
		$pagecount = 5;
		$page = new \Think\Page($user_num , $pagecount);
		$page->parameter = array('aid'=>$aid,'userid'=>$userid); 
		//$page->parameter = $row; 
		$page->setConfig('prev','<');
		$page->setConfig('next','>');
		$show = $page->show();
		//$sql="select qud.id,qud.aid,qud.uid,qud.iid,qp.title,qp.item1,qp.item2,qp.item3 from cmf_questionnaire_problem as qp,cmf_questionnaire_user_data as qud where qud.aid=".$aid." and qud.qid=qp.id and qud.uid=".$userid;
		$sql ="select * from cmf_activity_friend where aid=".$aid." and uid=".$userid;
		$Model = new \Think\Model();
		$list=$Model->query($sql);
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->assign('sql',$sql);
		$this->display(":app_weicheer");
	}
	public function app_weicircle_info()
	{
		$uid=get_current_userid();
		$where = 'uid='.$uid.' and valid = 1';
		//查询活动数量。
		$activity_num=$this->form_model->where($where)->count();
		$this->assign('activity_num',$activity_num);
		//查询应用数量。
		$app_num=$this->activity_model->where($where)->count();
		$this->assign('app_num',$app_num);
		$users = D('users'); 
		$users_info=$users->where('id='.$uid)->find();
		$this->assign('users_info',$users_info);
		
		//获取参数信息。
		$aid=I('get.aid',0,'intval');
		$userid=I('get.userid',0,'intval');
		//获取活动信息。
		$activity=$this->activity_model->where('id='.$aid)->find();
		$this->assign('activity',$activity);
		/*$keyword=I('get.keyword');*/
		$this->assign('aid',$aid);
		/*$this->assign('keyword',$keyword);*/
		//根据参数查询对应信息。
		$where = 'aid='.$aid." and uid=".$userid;
		
		//$sql ="select * from cmf_activity_friend where aid=".$aid." and uid=".$userid;
		//$Model = new \Think\Model();
		//$list=$Model->query($sql);
		//$this->assign('list',$list);
		$this->display(":app_weicircle_info");
	}
	public function app_weicircle_add()
	{	
		$this->display(":app_weicircle_add");
	}
	public function app_weicircle_post()
	{	
		echo "123";
		$res['status']=1;
		$res['msg']=">>>>";
		echo json_encode($res,true);
		die();
		//$this->display(":app_weicircle_info");
	}
}


