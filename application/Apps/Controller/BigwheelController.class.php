<?php

/**
 * 幸运大转盘
 */
namespace Apps\Controller;
use Common\Controller\ActivitybaseController; 
class BigwheelController extends ActivitybaseController {
	protected $activity;
	protected $activity_params;
	protected $activity_user;
	protected $activity_user_data;
	protected $activity_user_prize;
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
	  $url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/bigwheel/index',array('id'=>$aid));
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('bigwheel', 'index', $aid);
		} else {
			$this->entry($url);
		}
	  $item = sp_get_activity($aid);
	  if(!$item)
	  {
		  $this->error('抱歉，活动被删除或不存在！');
	  }
	  $params = sp_get_activity_params($aid);	
	  $params = array_column($params, 'value', 'field');

	  $this->share_data = array(
			'title'	=> $item['share_title'],
			'desc'  => $item['share_des'],
			'url' => $url,
			'imgUrl' => $item['share_img']
	  );
	  //获取前5个获取的奖品。
	  $prizels=$this->activity_user_prize->where(array('aid'=>$aid))->limit(10)->select();
	  $hjstr="";
		foreach($prizels as $hj)
		{
			$hjstr.=$hj['username']."获得：".$hj['type'].$hj['name']."&nbsp;&nbsp;&nbsp;&nbsp;";
		}
	  $this->assign("hjstr",$hjstr);
	  $this->add_userinfo($aid);
	  $user_info=session('userInfo');
	  //获取个人信息。
	  $user=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
	  $this->assign("user",$user);
	  $this->assign("share",$this->share_data);
	  $this->assign("params",$params);
	  $this->assign($params);
	  $this->assign($item);
	  $this->display();
    	}
	
	/**
	* 活动规则
	*/
	public function rule(){
	  $aid = I("get.id",0,"intval");
	  $item = sp_get_activity($aid);
	  $params = sp_get_activity_params($aid);	
	  $params = array_column($params, 'value', 'field');
	  $this->add_userinfo($aid);

	  $content_data=sp_content_page($item['rule']);
      $item['rule']=$content_data['content'];
	  //分享内容定义区
	  $url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/bigwheel/index',array('id'=>$aid));
	  $this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' =>$url,
			'imgUrl' =>$item['share_img']
	  );
	  $this->assign("share",$this->share_data);
	  $this->assign($params);
	  $this->assign($item);
	  $this->display();
	}
	/**
	* 排行榜
	*/
	public function rank(){
	   $aid = I("get.id",0,"intval");
	   $item = sp_get_activity($aid);
		$list = $this->activity_user->where("aid=".$aid." and per_num>0")->order('per_num desc')->select();
	   //分享内容定义区
	  $url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/bigwheel/index',array('id'=>$aid));
	  $this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' =>$url,
			'imgUrl' =>$item['share_img']
	  );
	  $this->add_userinfo($aid);
	  $this->assign("share",$this->share_data);
	  $this->assign("list",$list);
	  $this->assign($item);
	   //业务逻辑待实现
	  $this->display();
	}
	/**
	*宝箱
	*/
	public function gift(){
		$aid = I("get.id",0,"intval");
		$item = sp_get_activity($aid);
		$userInfo = session('userInfo');
		$from_user = $userInfo['openid'];
		$list = $this->activity_user_prize->where(array("aid"=>$aid,"from_user"=>$userInfo['openid']))->order('id desc')->select();
	  	$url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/bigwheel/index',array('id'=>$aid));;
		$this->share_data = array(
				'title'	=>$item['share_title'],
				'desc'  =>$item['share_des'],
				'url' =>$url,
				'imgUrl' =>$item['share_img']
		);
		$this->add_userinfo($aid);
	    $this->assign("share",$this->share_data);
	    $this->assign("list",$list);
		$this->assign($item);
	    $this->display();
	}
	/**
	* 注册
	**/
	public function register(){
		if(IS_POST){
			$userInfo = session('userInfo');
			$aid = I("get.id",0,"intval");
			$avatar = $userInfo['headimgurl'];
			$_POST['aid'] =$aid;
			$_POST['createtime'] = time();
			$_POST['from_user'] = $userInfo['openid'];
			$_POST['nickname'] = $userInfo['nickname'];
			$_POST['avatar'] = $userInfo['headimgurl'];
			$userInfo = session('userInfo');
			$res = $this->activity_user->where(array("aid"=>$aid,"from_user"=>$userInfo['openid']))->find();
			$_POST['id'] = $res['id'];
			if($res){
				$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->save($data);
			}else{
				$this->activity_user->add($data);
			}
		}
		$this->success("注册成功！");
	}
	/**
	* 判读是否能够抽奖
	*/
	public function check_status(){
		$aid = I("get.id",0,"intval");
		$userInfo = session('userInfo');
		$this->analyze_activitys($aid);
		$this->sp_analyze_activity_user($userInfo,$aid);
		$this->success("可以抽奖！");
	}
	/**
	*获得奖品
	*/
	public function get_gift(){
		  $aid = I("get.id",0,"intval");
		  $item = sp_get_activity($aid);
		  $params = sp_get_activity_params($aid);	
		  $params = array_column($params, 'value', 'field');
		  //更新用户的当天参与情况，如果中奖了就更新中奖值。
		  if(!empty(cookie('bigwheel_jiang')))
		{
			cookie('bigwheel_jiang',null);
		}
		$zhi=1;
		$user_info=session('userInfo');
		$user = $this->activity_user->where(array("aid"=>$aid,"from_user"=>$user_info['openid']))->find();
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
				cookie('bigwheel_jiang',$zhi,3600*7*24);
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
							cookie('bigwheel_jiang',$zhi,3600*7*24);
							break;
						}
					}
				}
				else     //如果用户已经注册
				{
					$jianglist = $this->activity_user_prize->where(array("aid"=>$aid,"from_user"=>$user_info['openid']))->select();
					//$result['msg']=json_encode($jianglist);
					//$result['msg'].=$user_info['openid']."；".$aid;
					//$this->ajaxReturn($result);
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
								cookie('bigwheel_jiang',($zhi),3600*7*24);
								break;
							}
						}
					}
				}
			}
		}
			
		if($zhi>0)
		{
			//中奖的礼品数量-1;
			$sql="update cmf_activity_params set value=value-1 where aid=".$aid." and field='prize".$zhi."_total'";
		 	M()->execute($sql);
		}
		//更新客户信息。
		$res = $this->activity_user->where("aid=".$aid." and id=".$user['id'])->save(array('update_time'=>time()));
		$sql="update cmf_activity_user set day_num=day_num+1,total_num=total_num+1 where aid=".$aid." and id=".$user['id'];
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
		//$result['msg']=json_encode($jiang);
		$this->ajaxReturn($result);
	}
	/**
	*判断是否注册
	**/
	public function check_regist(){
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
	/**
	* 领取奖品
	*/
	public function add_gift(){
		$aid = I("get.id",0,"intval");
		$data=array(
			'username'=>I("post.name"),
			'mobile'=>I("post.mobile"),
			'age'=>I("post.age"),
			'class'=>I("post.cless"),
			'school'=>I("post.school"),
			'remark'=>I("post.remark"),
		);
		$userInfo = session('userInfo');
		$result = $this->activity_user->where(array("aid"=>$aid,"from_user"=>$userInfo['openid']))->find();
		$zhi=cookie('bigwheel_jiang');
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
			//会员中奖次数+1.
			$sql="update cmf_activity_user set per_num=per_num+1 where aid=".$aid." and id=".$result['id'];
		 	M()->execute($sql);
			$res['jiang']=array('name'=>$jiang['prize'.$zhi.'_name'],'type'=>$jiang['prize'.$zhi.'_type'],'thumb'=>$jiang['prize'.$zhi.'_thumb']);
		}
		else
		{
			$res['status']=-1;
			$res['msg']="添加中奖用户失败";
		}
		$this->ajaxReturn($res);
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
			$this->activity_user->add($data);
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
	/**
	* 分析好友的状态
	**/
	function sp_analyze_activity_friend($aid=0,$uid=0){
		$user_info = session("userInfo");
		$from_user = $user_info['openid'];
		$activity = D("Activity")->where(array("id"=>$aid,"valid"=>1))->find();
		$user = D("ActivityUser")->where(array("aid"=>$aid,"from_user"=>$from_user,"uid"=>$uid))->find();
		if($user['day_num']>=$activity['day_num']&&$activity['day_num']!=0){ $this->error("您今日的次数已用完！"); }
		if($user['total_num']>=$activity['total_num']&&$activity['total_num']!=0){ $this->error("您参与该活动的总次数已用完！"); }
		if($user['per_num']>=$activity['per_num']){ $this->error("您总次数已用完！"); }
	}
	
}

