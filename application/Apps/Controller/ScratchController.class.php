<?php

/**
 * 刮刮乐
 */
namespace Apps\Controller;
use Common\Controller\ActivitybaseController; 
use Common\Controller\HomebaseController;

class ScratchController extends ActivitybaseController {
	protected $activity;
	protected $activity_params;
	protected $activity_user;
	protected $activity_user_data;
	protected $activity_user_prize;
	protected $jiangpai;
	protected $share_data;//定义分享数据
	function _initialize(){
		parent::_initialize();
		$this->activity = D("Activity");
		$this->activity_params = D("ActivityParams");
		$this->activity_user = D("ActivityUser");
		$this->activity_user_data = D("ActivityUserData");
		$this->activity_user_prize = D("ActivityUserPrize");
	}
    public function index() {
		set_wechat_info();
		$aid = I("get.id",0,"intval");
		$shareid = I("get.shareid",0,"intval");
		$type = I("get.type");
		$url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/scratch/index',array('id'=>$aid,'shareid'=>$shareid));
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('scratch', 'index', $aid, 0,$shareid);
		} else {
			$this->entry($url);
		}
//		$this->entry($url);

		$cando=1;
		$item = sp_get_activity($aid);
		if(!$item)
		{
		  $this->error('活动不存在');
		}
		//判断活动是否开始。
		if($item['begintime']>time())
		{
		  $cando=-1;
		}
		if($item['endtime']<=time())
		{
		  $cando=0;
		}
		$this->add_userinfo($aid);
		$params = sp_get_activity_params($aid);
		$params = array_column($params, 'value', 'field');
		//分享内容定义区

		$user_info=session('userInfo');
		$shareuser = $this->activity_user->where("aid=$aid and id=$shareid")->find();
		$user =  $this->activity_user->where("aid = $aid and from_user ='".$user_info['openid']."'")->find();
		if(!empty($user)){$is_register=1;}

		$this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' =>  C('DOMAIN_NAME').U('apps/scratch/index',array('id'=>$aid,'shareid'=>$shareid)),
			'imgUrl' =>$item['share_img']
		);
		//获取中奖者的信息
		$prizels=$this->activity_user_prize->where(array('aid'=>$aid))->limit(10)->select();
		$hjstr="";
		foreach($prizels as $hj)
		{
			$hjstr.=$hj['username']."获得：".$hj['type'].$hj['name']."&nbsp;&nbsp;&nbsp;&nbsp;";
		}
		if(empty($hjstr)){
			$hjstr ="暂无人中奖";
		}

		$this->assign("hjstr",$hjstr);
		$this->assign("hjstr",$hjstr);
		$this->assign("cando",$cando);
		$this->assign("is_register",$is_register);
		$this->assign("share",$this->share_data);
		$this->assign($params);
		$this->assign($item);
		$this->assign("user_info",$user_info);
		$this->assign("user",$user);
		$this->display();
    }
	public function getjiang(){
		  $aid = I("get.id",0,"intval");
		  $item = sp_get_activity($aid);
		  $params = sp_get_activity_params($aid);	
		  $params = array_column($params, 'value', 'field');
		  //更新用户的当天参与情况，如果中奖了就更新中奖值。
		  if(!empty(cookie('scratch_jiang'))){
				cookie('scratch_jiang',null);
		  }
		 
		$zhi=1;
		$user_info=session('userInfo');
		$user = $this->activity_user->where(array("aid"=>$aid,"from_user"=>$user_info['openid']))->find();
		$this->analyze_activitys($aid);
		$this->sp_analyze_activity_user($user_info,$aid);
		if($user)
		{
			$count=$this->activity_user_prize->where(array('aid'=>$aid,'uid'=>$user['id']))->count();
			if($count>=$item['per_num'])
			{
				$zhi=0;
			}
		}
		if($zhi>0)
		{
			//产生一个1-100的随机数。
			$rand=rand(1,100);
			//测试获取的奖品。
			//$rand=90;
			//如果奖品没有了，几率就为0；
			$params['prize1_rate']=$params['prize1_total']>0&&$params['prize1_only']==0?$params['prize1_rate']:0;
			$params['prize2_rate']=$params['prize2_total']>0&&$params['prize2_only']==0?$params['prize2_rate']:0;
			$params['prize3_rate']=$params['prize3_total']>0&&$params['prize3_only']==0?$params['prize3_rate']:0;
			$params['prize4_rate']=$params['prize4_total']>0&&$params['prize4_only']==0?$params['prize4_rate']:0;
			$params['prize5_rate']=$params['prize5_total']>0&&$params['prize5_only']==0?$params['prize5_rate']:0;
			$params['prize6_rate']=$params['prize6_total']>0&&$params['prize6_only']==0?$params['prize6_rate']:0;
			$jiang=array($params['prize1_rate'],$params['prize2_rate'],$params['prize3_rate'],$params['prize4_rate'],$params['prize5_rate'],$params['prize6_rate']);
			//将奖品中的几率相加，哪个几率相加后的结果包含随机数，就在哪里停。
			$num=0;
			$zhi=-1;
			for($i=0;$i<6;$i++)
			{
				if($rand>$num&&$rand<=$jiang[$i]+$num)
				{
					$zhi=$i;
					break;
				}
				$num+=$jiang[$i];
			}
			$zhi+=1;
			if($zhi>0)
			{
				cookie('scratch_jiang',$zhi,3600*7*24);
			}
			else
			//如果中奖为空，则查看用户的中奖信息中是否已经中过必中的一次奖，如果没有中，就查找必中的奖并中一次，根据名称对比。
			{
				if(empty($user))
				{
					for($i=0;$i<count($jiang);$i++)
					{
						if($params['prize'.($i+1).'_only']==1&&$params['prize'.($i+1).'_total']>0)
						{
							$zhi=$i+1;
							cookie('scratch_jiang',$zhi,3600*7*24);
							break;
						}
					}
				}
				else     //如果用户已经注册
				{
					$jianglist = $this->activity_user_prize->where(array("aid"=>$aid,"from_user"=>$user_info['openid']))->select();
					//遍历奖品数据。
					for($i=0;$i<count($jiang);$i++)
					{
						//如果遇到必中的奖品就遍历中奖记录看是否已经中奖。
						if($params['prize'.($i+1).'_only']==1)
						{
							$zai=false;
							for($j=0;$j<count($jianglist);$j++)
							{
								if($params['prize'.($i+1).'_name']==$jianglist[$j]['name'])
								{
									$zai=true; break;
								}
							}
							if(!$zai&&$params['prize'.($i+1).'_total']>0)
							{
								$zhi=$i+1;
								cookie('scratch_jiang',($zhi),3600*7*24);
								break;
							}
						}
					}
				}
			}
		}
		$userid = intval($user['id']);
		if($zhi>0)
		{
			//中奖的礼品数量-1;
			$sql="update cmf_activity_params set value=value-1 where aid=".$aid." and field='prize".$zhi."_total'";
		 	M()->execute($sql);
			//会员中奖次数+1.
			$sql="update cmf_activity_user set per_num=per_num+1 where aid=".$aid." and id=".intval($userid);
		 	M()->execute($sql);
		}
		//更新客户信息。
		$res = $this->activity_user->where("aid=".$aid." and id=".$userid)->save(array('update_time'=>time()));
		$sql="update cmf_activity_user set day_num=day_num+1,total_num=total_num+1 where aid=".$aid." and id=".$userid;
		M()->execute($sql);
		$jiang=array('type'=>$params['prize'.$zhi.'_type'],'name'=>$params['prize'.$zhi.'_name'],'thumb'=>$params['prize'.$zhi.'_thumb']);
		$result['jiang']=$jiang;
		if($zhi==3||$zhi==4)
			$zhi++;
		else if($zhi==5||$zhi==6)
			$zhi+=2;
		else if($zhi==0)
		{
			//产生一个随机数判断应该显示空奖的位置。
			$suizhi=rand(0,1);
			if($suizhi==0)
				$zhi=3;
			else $zhi=6;
		}
		$zhi--;
		$result['status']=$zhi;
		$this->ajaxReturn($result);
	}
	public function getregist(){
		$aid = I("get.id",0,"intval");
		$userInfo = session('userInfo');
		$res = $this->activity_user->where(array("aid"=>$aid,"from_user"=>$userInfo['openid']))->find();
		if($res['mobile']){
			$result['status']=1;
		}else{
			$result['status']=0;
		}
		$this->ajaxReturn($result);
	}
	public function insertjiang(){
		$aid = I("get.id",0,"intval");
		$data['username']=I('post.name');
		$data['mobile']=I('post.mobile');
		$userInfo = session('userInfo');
		$result = $this->activity_user->where(array("aid"=>$aid,"from_user"=>$userInfo['openid']))->find();
		$zhi=cookie('scratch_jiang');
		$params = $this->activity_params->where("aid=$aid and (field='prize".$zhi."_name' or field='prize".$zhi."_type' or field='prize".$zhi."_thumb')")->select();
		$jiang=array_column($params, 'value', 'field');
		if($result)
		{
			$res['status']=1;
			//更新user信息
			if(!$result['mobile'])
				$this->activity_user->where(array('aid'=>$aid,'from_user'=>$userInfo['openid']))->save($data);
			//添加中奖信息
			$prize_data = array("aid"=>$aid,"uid"=>$result['id'],"from_user"=>$userInfo['openid'],"username"=>$data['username'],"mobile"=>$data['mobile'],"name"=>$jiang['prize'.$zhi.'_name'],"type"=>$jiang['prize'.$zhi.'_type'],"thumb"=>$jiang['prize'.$zhi.'_thumb'],'createtime'=>time());
			if(!$this->activity_user_prize->add($prize_data))
			{
				$res['status']=-1;
				$res['msg']="添加中奖信息失败";
				$this->ajaxReturn($res);
			}
			$res['jiang']=array('name'=>$jiang['prize'.$zhi.'_name'],'type'=>$jiang['prize'.$zhi.'_type'],'thumb'=>$jiang['prize'.$zhi.'_thumb']);
			$this->activity_user->where(array('aid'=>$aid,'from_user'=>$userInfo['openid']))->setInc("award_num");
		}
		else
		{
			$res['status']=-1;
			$res['msg']="添加中奖用户失败";
		}
		$this->ajaxReturn($res);
	}
	/**我的奖品*/
	public function myprize(){
	  $aid = I("get.id",0,"intval");
	  $shareid = I("get.shareid",0,"intval");
	  $type = I("get.type");
	  $url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/scratch/index',array('id'=>$aid));
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('scratch', 'index', $aid);
		} else {
			$this->entry($url);
		}
//	  $this->entry($url);
	  $cando=1;
	  $item = sp_get_activity($aid);
	  $params = sp_get_activity_params($aid);	
	  $params = array_column($params, 'value', 'field');


	  $this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' => C('DOMAIN_NAME').U('apps/scratch/index',array('id'=>$aid)),
			'imgUrl' =>$item['share_img']
	  );
	  $userInfo = session('userInfo');
	  $from_user = $userInfo['openid'];
	  $list = $this->activity_user_prize->where(array("aid"=>$aid,"from_user"=>$userInfo['openid']))->order('id desc')->select();
	 // var_dump($list);
	  //echo $this->activity_user_prize->getLastSql();
	 // die();
	  $this->assign("share",$this->share_data);
	  $this->assign($params);
	  $this->assign("list",$list);
	  $this->assign($item);
	  $this->display();
	}
	/**排行榜***/
	public function rank(){

	  $aid = I("get.id",0,"intval");
	  $shareid = I("get.shareid",0,"intval");
	  $type = I("get.type");
	  $url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/scratch/index',array('id'=>$aid));
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('scratch', 'index', $aid);
		} else {
			$this->entry($url);
		}
//	  $this->entry($url);
	  $cando=1;
	  $item = sp_get_activity($aid);
	  $params = sp_get_activity_params($aid);	
	  $params = array_column($params, 'value', 'field');


	  $this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' => C('DOMAIN_NAME').U('apps/scratch/index',array('id'=>$aid)),
			'imgUrl' =>$item['share_img']
	  );
	  $list = $this->activity_user->where("aid=".$aid." and per_num>0")->order('per_num desc')->select();
	  $this->assign("list",$list);
	  $this->assign("share",$this->share_data);
	  $this->assign($params);
	  $this->assign($item);
	  $this->display();
	}
	/**活动规则***/
	public function rule(){
	  $aid = I("get.id",0,"intval");
	  $shareid = I("get.shareid",0,"intval");
	  $type = I("get.type");
	  $url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/scratch/index',array('id'=>$aid));
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('scratch', 'index', $aid);
		} else {
			$this->entry($url);
		}
//	  $this->entry($url);
	  $cando=1;
	  $item = sp_get_activity($aid);
	  $params = sp_get_activity_params($aid);	
	  $params = array_column($params, 'value', 'field');

	  $this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' =>  C('DOMAIN_NAME').U('apps/scratch/index',array('id'=>$aid)),
			'imgUrl' =>$item['share_img']
	  );
	  $this->assign("share",$this->share_data);
	  $this->assign($params);
	  $this->assign($item);
	  $this->display();
	}

	//注册用户
	public function register(){
		
		$aid=I('get.id',0,'intval');
		$data['username']=I('post.name');
		$data['mobile']=I('post.tel');
		$data['pid'] = I('get.shareid',0,"intval");
		$type = I("post.type",1,"intval");
		if($type==2){
			$temp_array = array(
				"type" =>2,
				"join_total" =>I("post.join_total"),
				"age" =>I("post.age"),
				"remark" =>I("post.remark"),
			);
			$data['remark'] = json_encode($temp_array,true);
		}
		if($type==1){
			$temp_array = array(
				"type" =>1,
				"area" =>I("post.area"),
				"school" =>I("post.school"),
				"remark" =>I("post.remark"),
			);
			$data['remark'] = json_encode($temp_array,true);
		}
		//活动分析
		$this->analyze_activitys($aid);
		$user_info=session('userInfo');
		$result=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
		if(!empty($result['mobile'])){
			$res1['status']=-1;
			$res1['msg']="您已报过名，不能重复报名！";
			exit(json_encode($res1,true));
		}
		if($data['pid']){
			if($data['pid']!=$result['id']){
				$pp = $this->activity_user->where(array('aid'=>$aid,'id'=>$data['pid']))->find();
				$data['pname'] = $pp['username'];
			}else{
				unset($data['pid']);
			}
		}
		$res = $this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->save($data);
		if($res){
			$res1['status']=1;
			$res1['uid']=$result['id'];
			$res1['avatar'] = $result['avatar'];
			$res1['name'] =$data['username'];
			//$res1['url'] = 'http://'.$_SERVER['HTTP_HOST'].U('apps/weimeet/detail',array('id'=>$aid,'shareid'=>$data['pid']));
			$res1['msg']="注册成功！";
		}else{
			$res1['status']=-1;
			$res1['msg']="注册失败失败！";
		}
		exit(json_encode($res1,true));
	}
	
	/***公共方法抽象定义区****/
	//判断当前用户是否存在，不存在则增加
	function add_userinfo($aid=0){
		$aid = I("get.id",0,"intval");
		$userInfo = session('userInfo');
		$res = $this->activity_user->where(array("aid"=>$aid,"from_user"=>$userInfo['openid']))->count();
		if(empty($res)){
			$data = array(
				'aid' => $aid,
				'from_user' => $userInfo['openid'],
				'nickname'  => $userInfo['nickname'],
				'avatar'  => $userInfo['headimgurl'],
				'createtime' => time()
			);
			if(!empty($userInfo['openid'])){
				$this->activity_user->add($data);
			}
		}
	}
	/**
	* 分析活动的状态
	* $aid 活动id
	*/
	function analyze_activitys($aid){
		$activity = D("Activity")->where(array("id"=>$aid,"valid"=>1))->find();
		if(empty($activity)){
			$this->error("该活动不存在！");
		}
		$currtime = time();
		if($activity['begintime']>$currtime){
			$this->error("活动尚未开始！");
		}
		if($activity['endtime']<$currtime){
			$this->error("活动已结束！");
		}
	}
	/**
	* 分析用户的状态--分析用户的三个指标
	*/
	function sp_analyze_activity_user($user_info,$aid=0){
		$user_info = session("userInfo");
		$from_user = $user_info['openid'];
		$activity = D("Activity")->where(array("id"=>$aid,"valid"=>1))->find();
		$user = D("ActivityUser")->where(array("aid"=>$aid,"from_user"=>$from_user))->find();
		$tday = strtotime(date("Y-m-d",time()));
		if($user['update_time']<$tday&&$user['day_num']!=0){
			D("ActivityUser")->where("id=".$user['id'])->save(array('day_num'=>0));
		}
		if($user['day_num']>=$activity['day_num']&&$activity['day_num']>0){ $this->error("您今日的次数已用完！"); }
		if($user['total_num']>=$activity['total_num']&&$activity['total_num']>0){ $this->error("您参与该活动的总次数已用完！"); }
		//if($user['per_num']>=$activity['per_num']){ $this->error("您总次数已用完！"); }
	}
}

