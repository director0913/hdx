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
	
	protected $activity_nyyvote_user;

	function _initialize() {
		parent::_initialize();
		$this->form_model = D("Common/Form");
		$this->activity_model = D("Common/Activity");
		$this->activityfriend_model = D("Common/ActivityFriend");
		$this->activityparams_model = D("Common/ActivityParams");
		$this->activityuser_model = D("Common/ActivityUser");
		$this->activityuserdata_model = D("Common/ActivityUserData");
		$this->activityuserprize_model = D("Common/ActivityUserPrize");
		$this->activity_nyyvote_user=D("NyyvoteUser");
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
		$pagecount = 10;
		$page = new \Think\Page($app_cha_num , $pagecount);
		$page->setConfig('prev','<');
		$page->setConfig('next','>');
		$show = $page->show();
		$list = $this->activity_model->where($where)->order('createtime desc')->limit($page->firstRow.','.$page->listRows)->select();
		foreach($list as &$v){
			//$total = $this->formrow_model->where("form_id=".$v[id])->count();
			$taid = $v['id'];
			$total = $this->activityuser_model->where("aid=$taid and username<>''")->count();
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
		//如果是砍价活动，获取商品最低价。
		if($activity['type']=="weiprice")
		{
			$price=$this->activityparams_model->where(array('aid'=>$id,'field'=>'p_y_price'))->find();
			$this->assign('price',$price);
		}
		$keyword=I('get.keyword');
		$this->assign('id',$id);
		$this->assign('keyword',$keyword);
		$users = D('users'); 
		$users_info=$users->where('id='.$uid)->find();
		$this->assign('users_info',$users_info);
		//根据参数查询对应信息。
		if($activity['type']=='questionnaire'){
			$where = "aid=$id";
		}else{
			$where = 'aid='.$id." and username<>''";
		}
		//超级团购特殊处理
		if($activity['type']=='ngroupbuy'){
			$tuan=I('get.tuan',0,'intval');
			if($tuan==1){
				//团员列表
				$pid=I('get.pid',0,'intval');
				if($pid){
					$where.=" and pid = $pid";
				}
			}else{
				//团长列表
				$where.=' and pid =0';
			}
		}
		//查询所有报名数量。
		$user_all_num=$this->activityuser_model->where($where)->count();
		$this->assign('user_all_num',$user_all_num);
		if($keyword)
		{
			//$where.=" and nickname like '%{$keyword}%'";
			$where.=" and (nickname like '%{$keyword}%' or mobile like '%{$keyword}%' or username like '%{$keyword}%')";
		}
		//查询符合条件的报名数量。
		$user_num=$this->activityuser_model->where($where)->count();
		//echo "where".$where."user_num".$user_num;
		$pagecount = 20;
		$page = new \Think\Page($user_num , $pagecount);
		$page->parameter = array('id'=>$id); 
		//$page->parameter = $row; 
		$page->setConfig('prev','<');
		$page->setConfig('next','>');
		$show = $page->show();
		if($activity['type']=='midautumn')
			$list = $this->activityuser_model->where($where)->order('per_num desc,update_time Asc')->limit($page->firstRow.','.$page->listRows)->select();
		else $list = $this->activityuser_model->where($where)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
		if($id==3563){
			dump($list);
		}
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display(":app_show_join");
	}
	/**
	*显示参加活动情况 新投票user
	**/
	public function app_show_join_nyyvote()
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
		
		$where = "aid=$id";
		//查询所有报名数量。
		$user_all_num=$this->activity_nyyvote_user->where($where)->count();
		$this->assign('user_all_num',$user_all_num);
		if($keyword)
		{
			$where.=" and username like '%{$keyword}%' ";
		}
		//查询符合条件的报名数量。
		$user_num=$this->activity_nyyvote_user->where($where)->count();
		$pagecount = 20;
		$page = new \Think\Page($user_num , $pagecount);
		$page->parameter = array('id'=>$id); 
		//$page->parameter = $row; 
		$page->setConfig('prev','<');
		$page->setConfig('next','>');
		$show = $page->show();
		$list = $this->activity_nyyvote_user->where($where)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display(":app_show_join");
	}
	/**
	*显示参加活动情况 新投票奖品
	**/
	public function prize_nyyvote()
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
		//$this->activityuserprize_model = D("Common/ActivityUserPrize");$this->activityfriend_model = D("Common/ActivityFriend");
		$users = D('users'); 
		$users_info=$users->where('id='.$uid)->find();
		$this->assign('users_info',$users_info);
		
		$where = "aid=$id";
		//查询所有报名数量。
		$user_all_num=$this->activityuserprize_model->where($where)->count();
		$this->assign('user_all_num',$user_all_num);
		if($keyword)
		{
			$where.=" and (username like '%{$keyword}%' or name like '%{$keyword}%' )";
		}
		//查询符合条件的报名数量。
		$user_num=$this->activityuserprize_model->where($where)->count();
		$pagecount = 20;
		$page = new \Think\Page($user_num , $pagecount);
		$page->parameter = array('id'=>$id); 
		//$page->parameter = $row; 
		$page->setConfig('prev','<');
		$page->setConfig('next','>');
		$show = $page->show();
		$list = $this->activityuserprize_model->where($where)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
		/*$Model = new \Think\Model(); // 实例化一个model对象 没有对应任何数据表
		//$sql="SELECT distinct from_user,id  FROM `cmf_activity_friend`;";
		$sql="SELECT distinct from_user,nickname,id  FROM `cmf_activity_user`;";
		echo $sql;
		$res=$Model->query($sql);
		print_r($res);*/
		$this->display(":app_show_help_nyyvote");
	}
	/**
	*显示留言情况 新投票user
	**/
	public function app_show_nyyvote_msg()
	{
		$uid=get_current_userid();
		$where = 'uid='.$uid.' and valid = 1';
		//查询活动数量。
		$activity_num=$this->form_model->where($where)->count();
		$this->assign('activity_num',$activity_num);
		//查询应用数量。
		$app_num=$this->activity_model->where($where)->count();
		$this->assign('app_num',$app_num);
		
/*		//获取参数信息。
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
		
		$where = "aid=$id";
		//查询所有报名数量。
		$user_all_num=$this->activity_nyyvote_user->where($where)->count();
		$this->assign('user_all_num',$user_all_num);
		if($keyword)
		{
			$where.=" and nickname like '%{$keyword}%'";
		}
		//查询符合条件的报名数量。
		$user_num=$this->activity_nyyvote_user->where($where)->count();
		$pagecount = 20;
		$page = new \Think\Page($user_num , $pagecount);
		$page->parameter = array('id'=>$id); 
		//$page->parameter = $row; 
		$page->setConfig('prev','<');
		$page->setConfig('next','>');
		$show = $page->show();
		$list = $this->activity_nyyvote_user->where($where)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$show);*/
		$this->display(":app_show_nyyvote_msg");
	}
	/**
	*删除报名选项
	**/
	public function form_item_delete()
	{
		$id = I("post.id",0,"intval");
		$res = D("ActivityUser")->where(array("id"=>$id))->delete();
		if ($res) {
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
		$uid = get_current_userid();
		$where = 'uid = '.$uid.' and valid = 1';
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
		$xuanze=I('get.xuanze',0,'intval');
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
		$pagecount = 10;
		$page = new \Think\Page($user_num , $pagecount);
		$page->parameter = array('aid'=>$id,'userid'=>$userid); 
		//$page->parameter = $row; 
		$page->setConfig('prev','<');
		$page->setConfig('next','>');
		$show = $page->show();
		if($activity['type']=='nationday'){
			//猜礼盒必须正序，否则礼盒一二三顺序会乱
			$list = $this->activityfriend_model->where($where)->order('id')->limit($page->firstRow.','.$page->listRows)->select();
		}else{
			if($activity['type']=='weiprices'){
				$where = 'aid='.$id." and uid=".$userid." and per_num=".$xuanze;
			}
			$list = $this->activityfriend_model->where($where)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
		}

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
	public function export_data(){
		$aid = I("get.id",0,"intval");
		$help = I("get.help",0,"intval");
		if($help==1)
		{
			$helpid = I("get.helpid",0,"intval");
			$userlist=$this->activityfriend_model->where('aid='.$aid." and uid=".$helpid)->order('id asc')->select();
			//echo $this->activityfriend_model->getLastSql();die();
		}
		$count = $this->activityuser_model->where('aid='.$aid." and username<>''")->count();
		$userlist=$this->activityuser_model->where('aid='.$aid." and username<>''")->order('id asc')->select();
		if(in_array($activity['type'],array('shareprize'))){
			$userlist=$this->activityuser_model->where('aid='.$aid." and username<>''")->order('total_num desc,id asc')->select();
		}
		//新团购特殊处理
		$tuan_list=array();
		$my_activity=$this->activity_model->where(array('id'=>$aid))->find();
		if(in_array($my_activity['type'],array('ngroupbuy'))){
			$userlist='';
			$first_list=$this->activityuser_model->where('aid='.$aid." and username<>'' and pid=0")->select();
			foreach($first_list as $vo){
				$second_list=$this->activityuser_model->where('aid='.$aid." and username<>'' and pid=".$vo['id'])->select();
				$userlist[]=$vo;
				foreach($second_list as $vv){
					$userlist[]=$vv;
				}
			}
		}
		$activity=$this->activity_model->where('id='.$aid)->find();
		$header = array("昵称","姓名","手机号","注册时间","奖品");
		//邀请有礼特殊处理
		if(in_array($activity['type'],array('invitegift','groupbuy'))){
			$header = array("昵称","姓名","手机号","注册时间","推荐人","备注");
		}
		if($activity['type']=='groupbuy'){
			$header = array("昵称","姓名","手机号","注册时间","推荐人","课程","备注");
		}
		if($activity['type']=='ngroupbuy'){
			$header = array("昵称","姓名","手机号","注册时间","开团者","备注",);
		}
		if($activity['type']=='midautumn'){
			$header = array("昵称","姓名","手机号","注册时间","月饼种类","集齐时间");
		}
		if($activity['type']=='flower'){
			$header = array("昵称","姓名","手机号","注册时间","献花数");
		}
		if($activity['type']=='yyvote'){
			$header = array("昵称","姓名","手机号","注册时间","投票数");
		}
		if($activity['type']=='weigreet'){
			$header = array("昵称","姓名","手机号","注册时间","点赞数");
		}
		if(in_array($activity['type'],array('weiprice')))
		{
			$header = array("昵称","姓名","手机号","注册时间","备注","当前剩余价格");
		}
		if(in_array($activity['type'],array('weicheer','weishare')))
		{
			$header = array("昵称","姓名","手机号","注册时间","助力值");
		}
		if(in_array($activity['type'],array('weidiom')))
		{
			$header = array("昵称","姓名","手机号","注册时间","分值");
		}
		if(in_array($activity['type'],array('shareprize')))
		{
			$header = array("昵称","姓名","手机号","注册时间","分值");
		}
		if(in_array($activity['type'],array('nenvelope')))
		{
			$header = array("昵称","姓名","手机号","注册时间","狂欢值");
		}
		if(in_array($activity['type'],array('christmas')))
		{
			$header = array("昵称","姓名","手机号","注册时间","点灯次数");
		}
		if(in_array($activity['type'],array('classjoin')))
		{
			$header = array("昵称","姓名","手机号","注册时间","年级","备注");
			$class_result=D('ClassJoin')->where(array('aid'=>$aid))->select();
			$class_list=array();
			foreach($class_result as $vo){
				$class_list[$vo['id']]=$vo['name'];
			}
		}
		if(in_array($activity['type'],array('nweishare')))
		{
			$header = array("昵称","姓名","手机号","注册时间","助力值");
		}
		if(in_array($activity['type'],array('envelope')))
		{
			$header = array("昵称","姓名","手机号","注册时间","积分值");
		}
		if(in_array($activity['type'],array('weilight'))){
			$header = array("昵称","姓名","手机号","注册时间","灯笼数","五灯全亮需要人数");
			$ligth_type='';
			$light_steprandom='';
			$light_step=1;
			$light_last=0;
			if($activity['type']=='weilight'){
				//点灯笼
				$result = D('ActivityParams')->where(array('aid'=>$activity['id']))->select();
				foreach($result as $vo){
					if($vo['field']=='steptype'){
						 $light_type=$vo['value'];
					}
					if($vo['field']=='steprandom'){
						 $light_steprandom=$vo['value'];
					}
					if($vo['field']=='step'){
						 $light_step=$vo['value'];
					}
				}
				if($light_type==1){
					//固定增加
					$light_last=$light_step*4;
				}
				if($light_type==0){
					$light_str=$light_steprandom;
					$light_str=explode(",",$light_str);
					$light_last=$light_str[3];
				}
			}
		}
		if(in_array($activity['type'],array('weiflower'))){
			$header = array("昵称","姓名","手机号","注册时间","生长值");
		}
		if(in_array($activity['type'],array('adventure'))){
			$header = array("昵称","姓名","手机号","注册时间","答题次数","最高分");
		}
		if(in_array($activity['type'],array('assessment'))){
			$header = array("昵称","姓名","手机号","注册时间","答题次数","最高分","备注");
		}
		if(in_array($activity['type'],array('womenday'))){
			$header = array("昵称","姓名","手机号","注册时间","投票数","对母亲的寄语","对老师的寄语");
		}
		if(in_array($activity['type'],array('flychicken')))
		{
			$header = array("昵称","姓名","手机号","注册时间","最高积分");
		}
		if(in_array($activity['type'],array('turnover'))){
			$header = array("昵称","姓名","手机号","注册时间","最好成绩","参与次数");
		}
		if(in_array($activity['type'],array('sharpeyes'))){
			$header = array("昵称","姓名","手机号","注册时间","最好成绩","参与次数","备注");
		}
		$list = array();
		 
		foreach($userlist as $vf){
			$tmp = array($vf['nickname']);
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
			$tmp[]=date("Y-m-d H:i:s",$vf['createtime']);
			if(in_array($activity['type'],array('invitegift','groupbuy','ngroupbuy'))){
				$tmp[]=$vf['pname'];
			}else if(in_array($activity['type'],array('midautumn'))){
				//分组获取月饼
				$binglist=$this->activityfriend_model->where('aid='.$aid." and uid=".$vf['id'])->group('per_num')->select();
				$bingstr="";
				for($i=0;$i<count($binglist);$i++)
				{
					switch($binglist[$i]['per_num'])
					{
						case 1:$bingstr.="莲蓉，"; break;
                    	case 2:$bingstr.="冰皮，"; break;
                    	case 3:$bingstr.="五仁，"; break;
                    	case 4:$bingstr.="绿豆沙，"; break;
                    	case 5:$bingstr.="蛋黄，"; break;
					}
				}
				$tmp[]=$bingstr;
				$tmp[]=$vf['update_time']==0?"":date("Y-m-d H:i:s",$vf['update_time']);
			}else if(in_array($activity['type'],array('weiprice'))){
				$price=$this->activityparams_model->where(array('aid'=>$aid,'field'=>'p_y_price'))->find();
				$tmp[]=$vf['remark'];
				$tmp[]=$price['value']-$vf['price'];
			}else if(in_array($activity['type'],array('yyvote','flower'))){
				$tmp[]=$vf['per_num'];
			}elseif(in_array($activity['type'],array('weigreet'))){
				$tmp[]=$vf['total_num'];
			}elseif(in_array($activity['type'],array('weicheer'))){
				$tmp[]=$vf['total_num'];
			}elseif(in_array($activity['type'],array('weishare'))){
				$tmp[]=$vf['per_num'];
			}elseif(in_array($activity['type'],array('weidiom'))){
				$tmp[]=$vf['day_num'];
			}elseif(in_array($activity['type'],array('shareprize'))){
				$tmp[]=$vf['total_num'];
			}elseif(in_array($activity['type'],array('nenvelope'))){
				$tmp[]=$vf['total_num'];
			}elseif(in_array($activity['type'],array('christmas'))){
				$tmp[]=$vf['total_num'];
			}elseif(in_array($activity['type'],array('classjoin'))){
				$tmp[]=$class_list[$vf['total_num']];
				$tmp[]=$vf['remark'];
			}elseif(in_array($activity['type'],array('nweishare'))){
				$tmp[]=$vf['total_num'];
			}elseif(in_array($activity['type'],array('envelope'))){
				$tmp[]=$vf['per_num'];
			}elseif(in_array($activity['type'],array('weilight'))){
				$tmp[]=$vf['remark'];
				$need=$light_last-$vf['total_num'];
				if($need<0){
					$need=0;
				}
				$tmp[]=$need;
			}elseif(in_array($activity['type'],array('turnover','sharpeyes'))){
				$tmp[]=$vf['parent_msg'];
				$tmp[]=$vf['total_num'];
			}elseif(in_array($activity['type'],array('weiflower'))){
				$tmp[]=$vf['total_num'];
			}elseif(in_array($activity['type'],array('adventure'))){
				$tmp[]=$vf['total_num'];
				$tmp[]=$vf['per_num'];
			}elseif(in_array($activity['type'],array('assessment'))){
				$tmp[]=$vf['total_num'];
				$tmp[]=$vf['per_num'];
			}elseif(in_array($activity['type'],array('flower','flychicken'))){
				$tmp[]=$vf['per_num'];
			}elseif(in_array($activity['type'],array('womenday'))){
				$tmp[]=$vf['per_num'];
				$tmp[]=$vf['parent_msg'];
				$tmp[]=$vf['teacher_msg'];
			}else{
				$jiangarr=array();
				for($i=0;$i<count($jianglist);$i++)
				{
					//$jiangarr[]=$jianglist[$i]['name'].date("Y-m-d H:m:s",$jianglist[$i]['createtime']);
					$jiangarr[]=$jianglist[$i]['name'];
				}
				$tmp[]=implode(",",$jiangarr);
			}
			if($activity['type']=='groupbuy'){
				$course_temp = array();
				$course_array = json_decode($vf['course'],true);
				foreach($course_array as $cc){
					$course_temp[] = $cc['course_name'];
				}
				$tmp[]=implode(",",$course_temp);
			}
			if(in_array($activity['type'],array('assessment','groupbuy','ngroupbuy','invitegift','sharpeyes'))){
				$tmp[]=$vf['remark'];
			}
			if(in_array($activity['type'],array('ngroupbuy'))){
				$tmp[]=$vf['parent_msg'];
				$tmp[]=$vf['teacher_msg'];
			}
			$list[] = $tmp;
		}
	   $filename = time();
	   header("Content-Type: application/octet-stream"); 
	   header("Content-Type: application/download"); 
	   header("Content-Transfer-Encoding: binary"); 
	   header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
	   header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
	   header("Pragma: no-cache"); 
	   header("Content-type:application/vnd.ms-excel");
	   header("Content-Disposition:attachment;filename=".$filename.".xls");
	   echo iconv('utf-8', 'gbk', implode("\t", $header)), "\n";
	   foreach($list as $vg){
			foreach($vg as &$gg){
				$gg = iconv('utf-8', 'gbk', $gg);
			}
			echo implode("\t",$vg)."\n";
		}
		exit();
	}
	//导出新投票
	public function export_data_nyyvote(){
		$aid = I("get.id",0,"intval");
		$help = I("get.help",0,"intval");
		if($help==1)
		{
			$helpid = I("get.helpid",0,"intval");
			$userlist=$this->activityfriend_model->where('aid='.$aid." and uid=".$helpid)->order('id asc')->select();
			//echo $this->activityfriend_model->getLastSql();die();
		}
		$count = D("NyyvoteUser")->where('aid='.$aid)->count();
		$userlist=D("NyyvoteUser")->where('aid='.$aid)->order('id asc')->select();
		$activity=$this->activity_model->where('id='.$aid)->find();
		/*$header = array("昵称","姓名","手机号","注册时间","票数");//,"校区","班级","指导老师","学生姓名","花朵数量","网页地址"
		$list = array(); 
		foreach($userlist as $vf){
			$tmp = array($vf['nickname']);
			$tmp[] = $vf['username'];
			$tmp[]=$vf['mobile'];
			$tmp[]=date("Y-m-d H:i:s",$vf['createtime']);
			$tmp[]=$vf['total_num'];
			$list[] = $tmp;
		}*/
		$header = array("姓名","花朵数量","选手地址","校区","班级","指导老师","头像","作品照片1","作品照片2","作品照片3","作品照片4","作品照片5","作品照片6","作品照片7","个人照片1","个人照片2","个人照片3","个人照片4","个人照片5","个人照片6");//,"老师寄语","父母寄语"
		$list = array(); 
		foreach($userlist as $vf){
			$tmp = array($vf['username']);
			$tmp[] = $vf['total_num'];
			$url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/nyyvote/show_detail',array('aid'=>$vf['aid'],'uid'=>$vf['id']));
			$tmp[]=$url;
			$tmp[]=$vf['school'];
			$tmp[]=$vf['class'];
			$tmp[]=$vf['teacher'];
			$tmp[]=$vf['avatar'];
			for($i=1;$i<=7;$i++){
				$tmp[]=$vf['work_photo'.$i];
			}
			for($j=1;$j<=6;$j++){
				$tmp[]=$vf['self_photo'.$j];
			}
			/*$tmp[]=$vf['teacher_message'];
			$tmp[]=$vf['parent_message'];*/
			$list[] = $tmp;
		}
	   $filename = time();
	   header("Content-Type: application/octet-stream"); 
	   header("Content-Type: application/download"); 
	   header("Content-Transfer-Encoding: binary"); 
	   header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
	   header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
	   header("Pragma: no-cache"); 
	   header("Content-type:application/vnd.ms-excel");
	   header("Content-Disposition:attachment;filename=".$filename.".xls");
	   echo iconv('utf-8', 'gbk', implode("\t", $header)), "\n";
	   foreach($list as $vg){
			foreach($vg as &$gg){
				$gg = iconv('utf-8', 'gbk', $gg);
			}
			echo implode("\t",$vg)."\n";
		}
		exit();
	}
	//导出中奖管理
	public function export_prize_data(){
		$aid = I("get.id",0,"intval");
		$count = D("ActivityUserPrize")->where('aid='.$aid)->count();
		$userlist=D("ActivityUserPrize")->where('aid='.$aid)->order('id asc')->limit(60000)->select();
		$activity=$this->activity_model->where('id='.$aid)->find();
		$header = array("姓名","联系电话","奖品详情");
		if($activity['type']=='nyyvote'){
			$header = array("姓名","联系电话","领奖校区","奖品详情","日期","老生","老生校区");
		}else{
			$header = array("姓名","联系电话","奖品详情","日期");
		}
		$list = array(); 
		foreach($userlist as $vf){
			$tmp = array($vf['username']);
			$tmp[] = $vf['mobile'];
			if($activity['type']=='nyyvote'){
				$tmp[]=$vf['remark'];
			}
			$tmp[]=$vf['name'];
			$tmp[]=date("Y-m-d H:i:s",$vf['createtime']);
			if($activity['type']=='nyyvote'){
				$tmp[]=$vf['pname'];
				$tmp[]=$vf['pschool'];
			}
			$list[] = $tmp;
		}
	   $filename = time();
	   header("Content-Type: application/octet-stream"); 
	   header("Content-Type: application/download"); 
	   header("Content-Transfer-Encoding: binary"); 
	   header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
	   header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
	   header("Pragma: no-cache"); 
	   header("Content-type:application/vnd.ms-excel");
	   header("Content-Disposition:attachment;filename=".$filename.".xls");
	   echo iconv('utf-8', 'gbk', implode("\t", $header)), "\n";
	   foreach($list as $vg){
			foreach($vg as &$gg){
				$gg = iconv('utf-8', 'gbk', $gg);
			}
			echo implode("\t",$vg)."\n";
		}
		exit();
	}
	public function export_prize_data_bat(){
		$p=I("get.p",0,"intval");
		$size=50000;
		$aid = I("get.id",0,"intval");
		$count = D("ActivityUserPrize")->where('aid='.$aid)->count();
		$userlist=D("ActivityUserPrize")->where('aid='.$aid)->order('id asc')->limit(($p-1)*$size,$size)->select();
		/*$sql=D("ActivityUserPrize")->getLastSql();
		echo $sql;
		die();*/
		$activity=$this->activity_model->where('id='.$aid)->find();
		$header = array("姓名","联系电话","奖品详情");
		if($activity['type']=='nyyvote'){
			$header = array("姓名|年龄","联系电话","领奖校区","奖品详情","日期","老生","老生校区");
		}else{
			$header = array("姓名","联系电话","奖品详情","日期");
		}
		$list = array(); 
		foreach($userlist as $vf){
			$tmp = array($vf['username'].'|'.$vf['age']);
			$tmp[] = $vf['mobile'];
			if($activity['type']=='nyyvote'){
				$tmp[]=$vf['remark'];
			}
			$tmp[]=$vf['name'];
			$tmp[]=date("Y-m-d H:i:s",$vf['createtime']);
			if($activity['type']=='nyyvote'){
				$tmp[]=$vf['pname'];
				$tmp[]=$vf['pschool'];
			}
			$list[] = $tmp;
		}
	   $filename = time();
	   header("Content-Type: application/octet-stream"); 
	   header("Content-Type: application/download"); 
	   header("Content-Transfer-Encoding: binary"); 
	   header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
	   header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
	   header("Pragma: no-cache"); 
	   header("Content-type:application/vnd.ms-excel");
	   header("Content-Disposition:attachment;filename=".$filename.".xls");
	   echo iconv('utf-8', 'gbk', implode("\t", $header)), "\n";
	   foreach($list as $vg){
			foreach($vg as &$gg){
				$gg = iconv('utf-8', 'gbk', $gg);
			}
			echo implode("\t",$vg)."\n";
		}
		exit();
	}
	public function export_friend_data(){
		$aid = I("get.id",0,"intval");
		$uid = I("get.uid",0,"intval");
		$count = $this->activityfriend_model->where(array('aid'=>$aid,'uid'=>$uid))->count();
		$userlist= $this->activityfriend_model->where(array('aid'=>$aid,'uid'=>$uid))->order('id asc')->select();
		$activity=$this->activity_model->where('id='.$aid)->find();
		$header = array("昵称","助跑值","日期");
		$list = array(); 
		foreach($userlist as $vf){
			$tmp = array($vf['nickname']);
			$tmp[] = $vf['total_num'];
			$tmp[]=date("Y-m-d H:i:s",$vf['createtime']);
			$list[] = $tmp;
		}
	   $filename = time();
	   header("Content-Type: application/octet-stream"); 
	   header("Content-Type: application/download"); 
	   header("Content-Transfer-Encoding: binary"); 
	   header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
	   header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
	   header("Pragma: no-cache"); 
	   header("Content-type:application/vnd.ms-excel");
	   header("Content-Disposition:attachment;filename=".$filename.".xls");
	   echo iconv('utf-8', 'gbk', implode("\t", $header)), "\n";
	   foreach($list as $vg){
			foreach($vg as &$gg){
				$gg = iconv('utf-8', 'gbk', $gg);
			}
			echo implode("\t",$vg)."\n";
		}
		exit();
		
	}


	public function test_mingdu(){
		$aid = 995;
		$userlist=$this->activityuser_model->where('aid='.$aid." and username<>''")->select();
		$filename = time();
		$header = array("昵称","姓名","手机号","注册时间","奖品");
		/**header("Content-Type: application/octet-stream"); 
	    header("Content-Type: application/download"); 
	    header("Content-Transfer-Encoding: binary"); 
	    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
	    header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
	    header("Pragma: no-cache"); 
	    header("Content-type:application/vnd.ms-excel");
		header("Content-Disposition:attachment;filename=".$filename.".xls");
		echo iconv('utf-8', 'gbk', implode("\t", $header)), "\n";
		**/
		foreach($userlist as $v){
			$nickname = iconv('utf-8', 'gbk', $v['nickname']);
			$username = iconv('utf-8', 'gbk', $v['username']);  
			$mobile =   iconv('utf-8', 'gbk', $v['mobile']);   $v['mobile'];
			$rtime = date("Y-m-d",$v['createtime']);
			$price = iconv('utf-8', 'gbk', "暂时没有");
			$vv = array($nickname,$username,$mobile,$rtime,$price);
			echo implode("\t",$vv)."\n";
			//echo iconv('utf-8', 'gbk', implode("\t", $vv)), "\n"; 
		}
	}
	//奖品记录
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
		$pagecount = 10;
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
	//详情(未使用)
	public function app_show_details()
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
		$pagecount = 10;
		$page = new \Think\Page($user_num , $pagecount);
		$page->parameter = array('aid'=>$aid,'userid'=>$userid,'help'=>$help); 
		//$page->parameter = $row; 
		$page->setConfig('prev','<');
		$page->setConfig('next','>');
		$show = $page->show();
		$list = $this->activityuserprize_model->where($where)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display(":app_show_details");
	}
	/**
	*删除报名选项
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
		$new=I('get.new',0,'intval');
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
		$pagecount = 10;
		$page = new \Think\Page($user_num , $pagecount);
		$page->parameter = array('aid'=>$aid,'userid'=>$userid); 
		//$page->parameter = $row; 
		$page->setConfig('prev','<');
		$page->setConfig('next','>');
		$show = $page->show();
		//获取所有答题记录
		$list=D('Common/QuestionnaireUserData')->where($where)->limit($page->firstRow.','.$page->listRows)->select();
		//遍历数据获取记录
		for($i=0;$i<count($list);$i++)
		{
			if($list[$i]['qid']==0)
			{
				//获取系统题目
				$result=D('Common/ActivityParams')->where("aid=".$aid." and field='tititle'")->find();
				$list[$i]['title']=$result['value'];
				//根据选项获取题目分类。
				$xiang=D('Common/AnswerType')->where("aid=".$aid." and id=".$list[$i]['iid'])->find();
				$list[$i]['right']=$xiang['name'];
			}
			else
			{
				//获取题目
				$result=D('Common/QuestionnaireProblem')->where("aid=".$aid." and id=".$list[$i]['qid'])->find();
				$list[$i]['title']=$result['title'];
				$list[$i]['right']=$result['item'.$list[$i]['iid']];
			}
		}
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display(":app_answer");
	}
	//题目分类管理
	public function app_challenge_type()
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
		$aid=I('get.id',0,'intval');
		$new=I('get.new',0,'intval');
		//获取活动信息。
		$activity=$this->activity_model->where('id='.$aid)->find();
		$this->assign('activity',$activity);
		$this->assign('aid',$aid);
		//根据参数查询对应信息。
		$where = 'aid='.$aid;
		$list = D("Common/AnswerType")->where($where)->order('listorder desc')->select();
		$this->assign('list',$list);
		if($new==1)
		{
			//如果题目数量不够，就添加空对象。
			for($i=count($list);$i<4;$i++)
			{
				$list[]=array('id'=>0,'listorder'=>0,'name'=>"");
			}
			$this->assign('list',$list);
			//获取第一题的题目。
			$tititle=$this->activityparams_model->where("aid=".$aid." and field='tititle'")->find();
			$this->assign('tititle',$tititle);
			$this->display(":app_newquestionnaire_type");
		}
		else $this->display(":app_challenge_type");
	}
	//题目分类管理---->nchallenge
	public function app_nchallenge_type()
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
		$aid=I('get.id',0,'intval');
		$new=I('get.new',0,'intval');
		//获取活动信息。
		$activity=$this->activity_model->where('id='.$aid)->find();
		$this->assign('activity',$activity);
		$this->assign('aid',$aid);
		//根据参数查询对应信息。
		$where = 'aid='.$aid;
		$list = D("Common/AnswerType")->where($where)->order('listorder desc')->select();
		$this->assign('list',$list);
		if($new==1)
		{
			//如果题目数量不够，就添加空对象。
			for($i=count($list);$i<4;$i++)
			{
				$list[]=array('id'=>0,'listorder'=>0,'name'=>"");
			}
			$this->assign('list',$list);
			//获取第一题的题目。
			$tititle=$this->activityparams_model->where("aid=".$aid." and field='tititle'")->find();
			$this->assign('tititle',$tititle);
			$this->display(":app_nnewquestionnaire_type");
		}
		else $this->display(":app_challenge_type");
	}
	//第一题选项更新。
	public function newquestionnaire_type_post()
	{
		//获取活动id。
		$aid = I("get.id",0,"intval");
		$tititle=I("post.tititle");
		//获取题目信息。
		$yiti=$this->activityparams_model->where("aid=".$aid." and field='tititle'")->find();
		//有就修改信息。
		if(empty($yiti))
		{
			$data=array('aid'=>$aid,'name'=>"第一题问题",'value'=>$tititle,'field'=>"tititle");
			$this->activityparams_model->add($data);
		}
		else $this->activityparams_model->where(array('id'=>$yiti['id']))->save(array('value'=>$tititle));
		//没有就添加信息。
		//获取所有id。
		$idls = I("post.id");
		//获取所有序号。
		$listorderls = I("post.listorder");
		//获取所有内容。
		$namels = I("post.name");
		//遍历id数组。
		$youcuo=false;
		for($i=0;$i<count($idls);$i++)
		{
			$data=array('listorder'=>$listorderls[$i],
						'name'=>$namels[$i]);
			if(intval($idls[$i])>0)
			{
				//修改数据。
				$res=D("Common/AnswerType")->where(array('id'=>$idls[$i]))->save($data);
			}
			else
			{
				$data['aid']=$aid;
				//添加数据。
				$res=D("Common/AnswerType")->add($data);
			}
			/*if(!$res)
			{
				$youcuo=true;
			}*/
		}
		$result['status']=$youcuo?0:1;
		$result['msg']=$youcuo?"部分数据保存失败，请到在当前页面检查并重试。":"";
		$this->ajaxReturn($result);
	}
	//题目分类更新
	public function challenge_type_post()
	{
		$id = I("post.id",0,"intval");
		$data['aid'] = I("get.aid",0,"intval");
		$data['listorder'] = I("post.listorder",0,"intval");
		$data['name'] = I("post.name");
		//如果id为0就添加。否则就修改。
		if($id==0)
			$res=D("Common/AnswerType")->add($data);
		else $res=D("Common/AnswerType")->where(array('id'=>$id))->save($data);
		$result['status']=$res?1:0;
		if(!$res)
			$result['msg']="操作失败！";
		else
		{
			$result['id']=$res;
			$result['url']=U('portal/app/app_challenge_answer',array('aid'=>$data['aid'],'tid'=>$result['id']));
		}
		$this->ajaxReturn($result);
	}
	/**
	*删除题目分类
	**/
	public function challenge_type_delete()
	{
		$id = I("post.id",0,"intval");
		if (D("Common/AnswerType")->delete($id)!==false) {
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}
	//题目管理
	public function app_challenge_answer()
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
		$new=I('get.new',0,'intval');
		$tid=I('get.tid',0,'intval');
		//获取活动信息。
		$activity=$this->activity_model->where('id='.$aid)->find();
		$this->assign('activity',$activity);
		$this->assign('aid',$aid);
		$this->assign('new',$new);
		$this->assign('tid',$tid);
		//根据参数查询对应信息。
		$where = 'aid='.$aid." and type_id=".$tid;
		$list = D("Common/QuestionnaireProblem")->where($where)->select();
		$this->assign('list',$list);
		$this->display(":app_challenge_answer");
	}
	public function show_test(){
		//$result=D("AnswerType")->limit(10)->select();
		$result=D("QuestionnaireProblem")->where(array('aid'=>3207))->select();
		
		print_r($result);
	}
	/**
	*提交题目，在这里对题目进行新增、修改和删除处理。
	**/
	public function challenge_answer_post()
	{
		//获取活动id。
		$aid = I("get.aid",0,"intval");
		//获取分类id。
		$typeid = I("get.typeid",0,"intval");
		//获取所有id。
		$idls = I("post.id");
		//获取所有正确答案。
		$rightls = I("post.right");
		//获取所有问题。
		$titlels = I("post.title");
		//获取所有选项。
		$item1ls = I("post.item1");
		$item2ls = I("post.item2");
		$item3ls = I("post.item3");
		$item4ls = I("post.item4");
		
		$typels = I("post.type");

		//将id拼接成字符串。
		$idstr=count($idls)>0?implode(",",$idls):0;
		//删除不存在的数据记录。
		D("Common/QuestionnaireProblem")->where("aid=".$aid." and type_id=".$typeid." and id not in(".$idstr.")")->delete();
		//遍历id数组。
		$youcuo=false;
		for($i=0;$i<count($idls);$i++)
		{
			$data=array('title'=>$titlels[$i],
						'right'=>$rightls[$i],
						'item1'=>$item1ls[$i],
						'item2'=>$item2ls[$i],
						'item3'=>$item3ls[$i],
						'item4'=>$item4ls[$i],
						'type'=>$typels[$i],
						);
			if(intval($idls[$i])>0)
			{
				//修改数据。
				$res=D("Common/QuestionnaireProblem")->where(array('id'=>$idls[$i]))->save($data);
			}
			else
			{
				$data['aid']=$aid;
				$data['type_id']=$typeid;
				//添加数据。
				$res=D("Common/QuestionnaireProblem")->add($data);
			}
			/*if(!$res)
			{
				$youcuo=true;
			}*/
		}
		$result['status']=$youcuo?0:1;
		$result['msg']=$youcuo?"部分数据保存失败，请到在当前页面检查并重试。":"";
		$this->ajaxReturn($result);
	}
	//查看答题记录，这里是显示得分信息的。
	public function app_answer_data()
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
		//获取这个会员的所有答题记录，这里用多表查询。
		$list=D('Common/QuestionnaireUserData')->where(array('aid'=>$aid,'uid'=>$userid))->select();
		//获取所有题目分类。
		$typels=D('Common/AnswerType')->where(array('aid'=>$aid))->select();
		//替换类型id为类型名称。
		for($i=0;$i<count($list);$i++)
		{
			if($list[$i]['iid']>0)
			{
				for($j=0;$j<count($typels);$j++)
				{
					if($list[$i]['iid']==$typels[$j]['id'])
					{
						$list[$i]['typename']=$typels[$j]['name']; break;
					}
				}
			}
			else $list[$i]['typename']="未记录";
		}
		$this->assign('aid',$aid);
		$this->assign('userid',$userid);
		$this->assign('list',$list);
		$this->display(":app_answer_data");
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
		$pagecount = 10;
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
		$pagecount = 10;
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
	/*获取头部信息*/
	public function get_activity_headinfo()
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
		$aid=I('get.id',0,'intval');
		$userid=I('get.userid',0,'intval');
		//获取活动信息。
		$activity=$this->activity_model->where('id='.$aid)->find();
		
		return $result=array($activity,$aid);
	}
	
	/*查看content列表*/
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
		$aid=I('get.id',0,'intval');
		$userid=I('get.userid',0,'intval');
		//获取活动信息。
		$activity=$this->activity_model->where('id='.$aid)->find();
		$this->assign('activity',$activity);
		$this->assign('aid',$aid);
		
		$user_list=D('WeicircleContent')->where('aid='.$aid)->order('id desc')->select();
		$this->assign('user_list',$user_list);
		$this->display(":app_weicircle_info");
	}
	/*添加content页面*/
	public function app_weicircle_add()
	{	
		$result=$this->get_activity_headinfo();
		$this->assign('activity',$result[0]);
		$this->assign('aid',$result[1]);
		
		$this->display(":app_weicircle_edit");
	}
	/*删除content*/
	public function app_weicircle_delete()
	{
		$id=I('post.id',0,'intval');
		D('WeicircleContent')->where('id='.$id)->delete();
		$res['status']=1;
		$this->ajaxReturn($res);
	}
	/*提交content列表*/
	public function app_weicircle_post()
	{
		$cid=I('post.cid',0,'intval');
		$aid=I('get.id',0,'intval');
		$nickname=I("post.msg_nickname");
		$thumb=I("post.thumb");
		$time=I("post.msg_time");
		$pic1=I("post.pic1");
		$pic2=I("post.pic2");
		$content=I("post.msg_content");
		$praise=I("post.msg_praise");
		$link=I("post.link");
		$listorder=I("post.listorder");
		$data=array('aid'=>$aid,
					'nickname'=>$nickname,
					'thumb'=>$thumb,
					'time'=>$time,
					'pic1'=>$pic1,
					'pic2'=>$pic2,
					'content'=>$content,
					'praise'=>$praise,
					'link'=>$link,
					'listorder'=>$listorder,
			);
		if($cid>0)
		{
			D('WeicircleContent')->where('id='.$cid)->save($data);
		}
		else
		{
			D('WeicircleContent')->add($data);
		}	
		$res['status']=1;
		$this->ajaxReturn($res);
	}
	/*跳转content编辑页面*/
	public function app_weicircle_edit()
	{
		$result=$this->get_activity_headinfo();
		$this->assign('activity',$result[0]);
		$this->assign('aid',$result[1]);
			
		$aid=I('get.id',0,'intval');
		$cid=I('get.cid',0,'intval');
		$content=D('WeicircleContent')->find($cid);
		$this->assign('content',$content);
		$this->display(":app_weicircle_edit");
	}
	public function app_weicircle_msg()
	{
		$result=$this->get_activity_headinfo();
		$this->assign('activity',$result[0]);
		$this->assign('aid',$result[1]);	
		
		$aid=I('get.id',0,'intval');
		$cid=I('get.cid',0,'intval');
		//查询评论
		$msg_list=D('WeicircleMsg')->where(array('aid'=>$aid,'cid'=>$cid))->select();
		$this->assign('msg_list',$msg_list);
		$this->display(":app_weicircle_msg");
	}
	public function app_weicircle_msg_add()
	{
		$result=$this->get_activity_headinfo();
		$this->assign('activity',$result[0]);
		$this->assign('aid',$result[1]);
		
		$aid=I('get.id',0,'intval');
		$cid=I('get.cid',0,'intval');
		//查找对应信息，循环输出
		$msg_list=D('WeicircleMsg')->where(array('aid'=>$aid,'cid'=>$cid))->select();
		$this->assign('msg_list',$msg_list);
		$this->display(":app_weicircle_msg_add");
	}
	public function app_weicircle_msg_delete()
	{
		$id=I('post.id',0,'intval');
		D('WeicircleMsg')->where('id='.$id)->delete();
		$res['status']=1;
		$this->ajaxReturn($res);
	}
	public function app_weicircle_msg_post()
	{
	
		//获取活动id。
		$aid = I("get.id",0,"intval");
		//获取分类id。
		$cid = I("get.cid",0,"intval");
		//获取所有id。
		$idls = I("post.msg_id");
		//获取所有正确答案。
		$nicknamels = I("post.nickname");
		//获取所有问题。
		$backnicknamels = I("post.backnickname");
		//获取所有选项。
		$contentls = I("post.content");
		//将id拼接成字符串。
		$idstr=count($idls)>0?implode(",",$idls):0;
		//删除不存在的数据记录。
		D("WeicircleMsg")->where("aid=".$aid." and cid=".$cid." and id not in(".$idstr.")")->delete();
		//遍历id数组。
		$youcuo=false;
		for($i=0;$i<count($idls);$i++)
		{
			$data=array('nickname'=>$nicknamels[$i],
						'backnickname'=>$backnicknamels[$i],
						'content'=>$contentls[$i],
						'createtime'=>time());
			if(intval($idls[$i])>0)
			{
				//修改数据。
				$res=D("WeicircleMsg")->where(array('id'=>$idls[$i]))->save($data);
				$result['msg'].=D("WeicircleMsg")->getLastSql();
			}
			else
			{
				$data['aid']=$aid;
				$data['cid']=$cid;
				//添加数据。
				$res=D("WeicircleMsg")->add($data);
			}
			if(!$res)
			{
				$youcuo=true;
			}
		}
		$result['status']=$youcuo?0:1;
		$result['msg'].=$youcuo?"":"部分数据保存失败，请到在当前页面检查并重试。";
		$this->ajaxReturn($result);
	}
	public function app_weicircle_msg_edit()
	{
		$result=$this->get_activity_headinfo();
		$this->assign('activity',$result[0]);
		$this->assign('aid',$result[1]);
		
		$msg_id=I('get.msg_id',0,'intval');
		$msg=D('WeicircleMsg')->where('id='.$msg_id)->find();
		$this->assign('msg',$msg);
		$this->display(":app_weicircle_msg_edit");
	}
	public function show_nn(){
		$result=D("ActivityUser")->where(array('aid'=>3142))->select();
		var_dump($result);
	}
}


