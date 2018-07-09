<?php

/**
 * 大转盘助力活动
 */
namespace Apps\Controller;
use Common\Controller\ActivitybaseController; 
class WeishareController extends ActivitybaseController {
	protected $jiangpai;
	protected $activity;
	protected $activity_params;
	protected $activity_user;
	protected $activity_user_data;
	protected $activity_user_prize;
	protected $activity_friend;
	protected $share_data;//定义分享数据
	function _initialize()
	{
		parent::_initialize();
		$this->activity = D("Activity");
		$this->activity_params = D("ActivityParams");
		$this->activity_user = D("ActivityUser");
		$this->activity_user_data = D("ActivityUserData");
		$this->activity_friend = D("ActivityFriend");
		$this->activity_user_prize = D("ActivityUserPrize");
		$this->jiangpai = D("Ad");
	}
    public function index()
	{
		set_wechat_info();
		$status = 0;
		$shareuid = 0;
		$isme = 1;
		$aid = I("get.id",0,"intval"); // 获取活动id。
	  	$uid = I('get.uid',0,'intval'); // 看是否是分享过来的页面。
		$user_info=session('userInfo'); //获取粉丝详细信息。
	  	$url = 'http://'.$_SERVER['HTTP_HOST'] . U('apps/weishare/index', array('id' => $aid, 'uid' => $uid)); // 定义分享链接。
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('weishare', 'index', $aid, $uid);
		} else {
			$this->entry($url);
		}
//		$this->entry($url); //检查浏览器及获取公众号信息。

	  	//获取扩展字段。
	  	$item = sp_get_activity($aid);
	   	if (empty($item)) {
		  	$this->error('活动不存在');
	  	}
	  	//判断活动是否开始。
	  	if ($item['begintime']>time()) {
		  	$status = -1;
	  	}
	  	if ($item['endtime']<=time()) {
		  	$status = -2;
	  	}

	  	//判断活动是否结束。
	  	$params = sp_get_activity_params($aid);
	  	$params = array_column($params, 'value', 'field');

	  	//获取自己的信息。
	  	$user = $this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();

	  	//是否注册
	  	$is_regist = false;
	  	if ($uid != 0) {
		  	//获取分享人信息。
		  	$his_info = $this->activity_user->where(array('id'=>$uid))->find();
		  	if (!empty($his_info['username'])&&!empty($his_info['mobile'])) {
				$shareuid = $uid;
				if ($his_info['from_user']!=$user_info['openid']) {
					$isme = 0;
				}
		  	} else {
			  $status = -2;
		  	}
	  	} else if (!empty($user['mobile'])) {
			$shareuid = $user['id'];
		}
		if (!empty($user['mobile'])) {
			$is_regist = true;
		}
	  	// 自己助力值
	  	if ($is_regist) {
			$myscore=$this->activity_user->field('per_num')
				->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
	  		$this->assign("myscore",$myscore);
		 	$sql = "select count(*) from cmf_activity_user where aid=$aid and per_num >".intval($myscore['per_num']);
			$pm = M()->query($sql);
			$mypm = intval($pm[0]['count(*)']);
			$mypm++;
			$this->assign("mypm", $mypm);
			
	  	}
	  	//别人助力值
	   	if($isme == 0) {
			$hisscore = $this->activity_user->field('per_num')->where(array('id'=>$uid))->find();
	  		$this->assign("hisscore",$hisscore);
			$sql = "select count(*) from cmf_activity_user where aid=$aid and per_num >".intval($hisscore['per_num']);
		 	$pm = M()->query($sql);
			$hispm = intval($pm[0]['count(*)']);
			$hispm++;
			$this->assign("hispm", $hispm);
	  	}

	  	// 如果有分享人信息就改变分享链接。
	  	if ($shareuid != 0) {
	  		$url =  C('DOMAIN_NAME') . U('apps/weishare/index', array('id' => $aid, 'uid' => $shareuid));
			$zhuuser = $this->activity_user->where(array('aid' => $aid, 'id' => $shareuid))->find();
	  	} else {
			//否则，分享链接是自己的。
		  	$url= C('DOMAIN_NAME') . U('apps/weishare/index',array('id' => $aid, 'uid' => $shareuid));
	  	}
	  	$this->share_data = [
			'title'	=> $item['share_title'],
			'desc' => $item['share_des'],
			'url' => $url,
			'imgUrl' => $item['share_img']
		];
	  	$this->assign("total_num", $this->splitnum($zhuuser['total_num']));
		$this->assign("status", $status);
		$this->assign("user",$user);
		$this->assign("is_regist", $is_regist);
		$this->assign("isme", $isme);
	  	$this->assign("shareuid", $shareuid);
	  	$this->assign("share", $this->share_data);
	  	$this->assign("params", $params);
	  	$this->assign("item", $item);
	  	$this->assign("his_info", $his_info);
		$this->assign("user_info",$user_info);
      	$this->display();
    }
	/* 测试首页预览 */
	public function yuindex() {
      $this->display("/Thimes/weishare/yuindex");
    }
	/*测试分享预览
	*/
	public function yufenxiang() {
      $this->display("/Thimes/weishare/yufenxiang");
    }
	/*注册*/
	public function regist()
	{
		$user_info = session('userInfo');
		$time = time();
		$aid = I('get.id',0,'intval');
		$username = I('post.name');
		$mobile = I('post.mobile');

		$data = [
			'from_user' => $user_info['openid'],
			'nickname' => $user_info['nickname'],
			'avatar' => $user_info['headimgurl'],
			'createtime' => $time,
			'update_time' => $time,
			'aid' => $aid,
			'username' => $username,
			'mobile' => $mobile
		];

		$params = sp_get_activity_params($aid);	
		$params = array_column($params, 'value', 'field');

		// 助力值初始值
		$data['per_num'] = !empty($params['start']) ? $params['start'] : 0;
		
		// 减少助力卡片数量（报名人员限制 count表示可报名数量）
		if (empty($params['count'])) {
			$res = [
				'status' => -1,
				'msg' => '报名人数已满'
			];
		} else {
			if (intval($params['count']) > 0) {
				$result=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
				if (!empty($result)) {
					$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->save($data);
				} else {
					$this->activity_user->add($data);
				}
				$this->activity_params->where(array('aid' => $aid,'field' => 'count'))->setDec('value');
				$res = [
					'status' => 1,
					'msg' => '注册成功',
					'url' => 'http://' . $_SERVER['HTTP_HOST'] . U('apps/weishare/index', array('id' => $aid, 'ss' => 1))
				];
			} else {
				$res = [
					'status' => -1,
					'msg' => '报名人数已满'
				];
			}
		}
		$this->ajaxReturn($res);
	}
	/*助力*/
	public function zhuli()
	{
		$aid=I('get.id',0,'intval');
		$uid=I('get.uid',0,'intval');
		
		$user_info=session('userInfo');
		if(empty($user_info)){
			$this->error("获取您的信息失败，请重新打开页面进行投票！");
		}
		$user=$this->activity_user->where(array('id'=>$uid))->find();
		if($user['from_user']!=$user_info['openid'])
		{
			$t=I('get.t',0,'intval');
			if($t==1){
				
				$this->analyze_activitys_friend($aid,$uid);
			}
			//获取活动信息。
			$activity=sp_get_activity($aid);

			//获取自己的总共助力次数和当天助力次数。
			if($activity['per_num']>0)
			  {
				  //获取这个人的总加油次数和当天加油次数。
				  $total_num=$this->activity_friend->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->count();
				  if($total_num>=$activity['per_num'])
				  {
					  $res['status']=0;
						$res['msg']="总次数已用完";
						$res['goon']=0;
						$this->ajaxReturn($res);
				  }
			  }
			  if($activity['day_num']>0)
			  {
				  $today = strtotime(date("Y-m-d",time()));
				  $day_num=$this->activity_friend->where("aid=".$aid." and from_user='".$user_info['openid']."' and update_time>=".$today)->count();
				  if($day_num>=$activity['day_num'])
				  {
					  $res['status']=0;
						$res['msg']="当天次数已用完";
						$res['goon']=0;
						$this->ajaxReturn($res);
				  }
			  }
			  
			  //更新指定的会员。
			  $this->activity_user->where(array('id'=>$uid))->setInc('total_num');//增加助力次数
			  //增加积分
			  
			  
			  //活动扩展信息
			   $params = sp_get_activity_params($aid);	
	  		   $params = array_column($params, 'value', 'field');
			  $type=$params['steptype'];
			  $step=1;
			  if($type==1)
			  {
			  	//固定增加
				$step=1;
				if($params['step'])
				{
					$step=intval($params['step']);
				}
				//$this->activity_user_data->where(array('aid'=>$aid,'uid'=>$uid,'field'=>'start'))->setInc('value',$step);
				$this->activity_user->where(array('id'=>$uid))->setInc('per_num',$step);   //per_num 记录助力值， total_num记录助力次数				
				$hisscore=I('post.hisscore',0,'intval');
				$res['newhisscore']=$step+$hisscore;
			  }
			  
			  if($type==0)
			  {
			  	//随机增加
				$step=1;
				if($params['steprandom'])
				{
					$str=$params['steprandom'];
					$str=explode("、",$str);
					if(count($str)>1)
					{
						$s=$str[0];
						$e=$str[1];
					}
					else
					{
						$s=1;
						$e=$str[0];
					}
					$step=mt_rand($s, $e);
				}
				//$this->activity_user_data->where(array('aid'=>$aid,'uid'=>$uid,'field'=>'start'))->setInc('value',$step);
				$this->activity_user->where(array('id'=>$uid))->setInc('per_num',$step);   //per_num 记录助力值， total_num记录助力次数		
				$hisscore=I('post.hisscore',0,'intval');
				$res['newhisscore']=$step+$hisscore;	
			  }
			 
			 //写入数据
			  $frienddata=array('uid'=>$uid,
			  					'aid'=>$aid,
								'from_user'=>$user_info['openid'],
								'nickname'=>$user_info['nickname'],
								'avatar'=>$user_info['headimgurl'],
								'total_num'=>$step,
								'createtime'=>time(),
								'update_time'=>time()
								);
			  $this->activity_friend->add($frienddata);
			  //判断是否可以继续写入*/
			  $res['status']=1;
			  $res['msg']="助力成功";
		}
		$this->ajaxReturn($res);	
	}
	public function analyze_activitys_friend($aid,$uid){
		$userInfo = session('userInfo');

		//分析此好友是否存在
		$friend=$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->find();
		if(empty($userInfo['openid'])){
			$this->error("您的信息获取失败，请重新打开！");die();
		}
		if(empty($friend)&&!empty($userInfo['openid'])){
			$data = array(
				'aid' => $aid,
				'uid' => $uid,
				'from_user' => $userInfo['openid'],
				'nickname'  => $userInfo['nickname'],
				'avatar'  => $userInfo['headimgurl'],
				'createtime' => time(),
				'update_time' => time()

			);
			$this->activity_friend->add($data);
			$friend=$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->find();
		}
		$today = date("Y-m-d",time());
		$ftoday =  date("Y-m-d",$friend['update_time']);
		if($ftoday<$today){
			//清空当天的点赞数 
			$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->save(array('day_num'=>0,'day_value'=>'','update_time'=>time()));
		}
		//获取活动信息。
			$activity=sp_get_activity($aid);

			//获取自己的总共助力次数和当天助力次数。
			if($activity['per_num']>0)
			  {
				  //获取这个人的总加油次数和当天加油次数。
				  $total_num=$this->activity_friend->where(array('aid'=>$aid,'from_user'=>$userInfo['openid']))->sum('total_num');
				  if($total_num>=$activity['per_num'])
				  {
					  $res['status']=0;
						$res['msg']="总次数已用完".$total_num.">>>".$activity['per_num'];
						$res['goon']=0;
						$this->ajaxReturn($res);
				  }
			  }
			  if($activity['day_num']>0)
			  {
				  $today = strtotime(date("Y-m-d",time()));
				  $day_num=$this->activity_friend->where(array('aid'=>$aid,'from_user'=>$userInfo['openid']))->sum('day_num');
				  if($day_num>=$activity['day_num'])
				  {
					  $res['status']=0;
						$res['msg']="当天次数已用完";
						$res['goon']=0;
						$this->ajaxReturn($res);
				  }
			  }
		  //活动扩展信息
		  $params = sp_get_activity_params($aid);	
		  $params = array_column($params, 'value', 'field');
		  $pvalue=$params['pvalue'];
		  if($pvalue==0)
		  {
				$pvalue=1;
		  }
		  $this->activity_user->where(array('id'=>$uid))->setInc('total_num',$pvalue);   //total_num记录助力值	
		  $this->activity_user->where(array('id'=>$uid))->setInc('per_num');  	         //per_num记录助力次数	
		  $hisscore=I('post.hisscore',0,'intval');
		  $res['status']=1;
		  $res['newhisscore']=$pvalue+$hisscore;
		  $res['msg']="助力成功".$pvalue.">>>".$hisscore;
		  //写入数据
		  $this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->setInc('day_num');
		  $this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->setInc('total_num');
		  $friend=$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->find();
		  $frienddata['day_value']=intval($friend['day_value'])+$pvalue;
		  $frienddata['total_value']=intval($friend['total_value'])+$pvalue;
		  $frienddata['update_time']=time();
		  $this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->save($frienddata);
		  echo json_encode($res,true);
		  die();
	}
	//删除中奖记录
	public function delete_friend(){
		$aid=I('post.aid',0,'intval');
		$id=I('post.id',0,'intval');//朋友id
		$masterid=I('post.masterid',0,'intval');//主人id
		//读取活动
		$item = sp_get_activity($aid);
		$params = sp_get_activity_params($aid);	
	    $params = array_column($params, 'value', 'field');
		//print_r($params);
		$friend=$this->activity_friend->where('id='.$id)->find();
		$step=intval($friend['total_num']);
		//删除朋友
		$this->activity_friend->where('id='.$id)->delete();
		//更新user
		$this->activity_user->where(array('id'=>$masterid))->setDec('per_num',$step);
		$res['status']=1;
		$this->ajaxReturn($res);
	}
	/*我的喝彩记录*/
	public function morefriend()
	{
		$aid=I('get.id',0,'intval');
		$page_f=I('post.page_f',0,'intval');
		$size=15;
		$user_info=session('userInfo');
		$user=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
		$uid=$user['id'];
		
		$list =$this->activity_friend->where(array('aid'=>$aid,'uid'=>$uid))->order('update_time desc')->limit(($page_f-1)*$size.",".$size)->select();
		if($list)
		{
			foreach($list as &$v){
				if(empty($v['nickname'])){
					$v['nickname'] = " ";
				}
			}
			$res['status']=1;	
			$res['list']=$list;
		}
		else
		{
			$res['status']=-1;	
		}
		
		$this->ajaxReturn($res);
	}
	
	/*加载排行榜*/
	public function moreuser()
	{
		$aid = I('get.id',0,'intval');
		$page_u = I('post.page_u',0,'intval');
		$size = 400;
		$front = I('get.sortcount',0,'intval');
		if ($page_u == 1) {
			$count = $this->activity_user->where('aid='.$aid.' and mobile is not null')->count();
		}
		$zongliang = $count > $front ? $front : $count;
		$pagecount = ceil($zongliang / $size);
		$pagecount = $pagecount == 0 ? 1 : $pagecount;
		$list = $this->activity_user->where('aid = '.$aid.' and mobile is not null')->order('per_num desc')->limit(($page_u - 1) * $size.", ".$size)->select();
		if ($list) {
			$res['status'] = 1;
			$res['pagecount'] = $pagecount;
			$res['list'] = $list;
		}
		$this->ajaxReturn($res);
	}
	
	/*记录中奖用户信息*/
	public function getPrize()
	{
		$aid=I('get.id',0,'intval');
		$data['username']=I('post.name');
		$data['mobile']=I('post.mobile');
		$zhi=cookie('cheerchina_jiang');
		//根据奖值获取奖品。
		$params = $this->activity_params->where("aid=$aid and (field='prize".$zhi."_name' or field='prize".$zhi."_type' or field='prize".$zhi."_thumb')")->select();
		$jiang=array_column($params, 'value', 'field');
		$user_info=session('userInfo');
		if($user_info['openid'])
		{
			$res['status']=1;
			//添加中奖信息
			$prize_data = array("aid"=>$aid,"uid"=>0,"from_user"=>$user_info['openid'],"username"=>$data['username'],"mobile"=>$data['mobile'],"name"=>$jiang['prize'.$zhi.'_name'],"type"=>$jiang['prize'.$zhi.'_type'],"thumb"=>$jiang['prize'.$zhi.'_thumb'],'createtime'=>time());
			if(!$this->activity_user_prize->add($prize_data))
			{
				$res['status']=-1;
				$res['msg']="添加中奖信息失败";
				$this->ajaxReturn($res);
			}
			$res['jiang']=array('name'=>$jiang['prize'.$zhi.'_name'],'type'=>$jiang['prize'.$zhi.'_type'],'thumb'=>$jiang['prize'.$zhi.'_thumb']);
		}
		else
		{
			$res['status']=-1;
			$res['msg']="添加中奖用户失败";
		}
		$this->ajaxReturn($res);
	}
	/*分割加油总次数*/
	public function splitnum($num)
	{
		$num=strval($num);
		$ling="";
		if(strlen($num)<=6)
		{
			for($i=0;$i<6-strlen($num);$i++)
			{
				$ling.="0";
			}
		}
		else $num="999999";
		return $ling.$num;
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
		//$user_info = session("userInfo");
		$from_user = $user_info['openid'];
		$activity = D("Activity")->where(array("id"=>$aid,"valid"=>1))->find();
		$user = D("ActivityUser")->where(array("aid"=>$aid,"from_user"=>$from_user))->find();
		$tday = strtotime(date("Y-m-d",time()));
		if($user['update_time']<$tday){
			$user['day_num']=0;
			D("ActivityUser")->where(array("aid"=>$aid,"from_user"=>$from_user))->save(array('day_num'=>0));
		}
		if($user['day_num']>=$activity['day_num']){ $this->error("您今日的次数已用完！"); }
		if($user['total_num']>=$activity['total_num']){ $this->error("您参与该活动的总次数已用完！"); }
		if($user['per_num']>=$activity['per_num']){ $this->error("您总次数已用完！"); }
	}
	/**
	* 分析好友的状态
	**/
	function sp_analyze_activity_friend($aid=0,$uid=0){
		$user_info = session("userInfo");
		$from_user = $user_info['openid'];
		$activity = D("Activity")->where(array("id"=>$aid,"valid"=>1))->find();
		$user = D("ActivityUser")->where(array("aid"=>$aid,"from_user"=>$from_user,"uid"=>$uid))->find();
		if($user['day_num']>=$activity['day_num']){ $this->error("您今日的次数已用完！"); }
		if($user['total_num']>=$activity['total_num']){ $this->error("您参与该活动的总次数已用完！"); }
		if($user['per_num']>=$activity['per_num']){ $this->error("您总次数已用完！"); }
	}
	
	/*保存个性字段*/
	function save_weishare($aid){
		$data = array(
			array("aid"=>$aid,"name"=>"助力值名称","field"=>"markname","value"=>I('post.markname')),
			array("aid"=>$aid,"name"=>"是否显示倒计时","field"=>"iscountdown","value"=>I('post.iscountdown')),
			array("aid"=>$aid,"name"=>"卡片名称","field"=>"cardname","value"=>I('post.cardname')),
			array("aid"=>$aid,"name"=>"卡片数量","field"=>"count","value"=>I('post.count'),0,"intval"),
			array("aid"=>$aid,"name"=>"显示前","field"=>"sortcount","value"=>I('post.sortcount'),0,"intval"),
			array("aid"=>$aid,"name"=>"最高得分限制","field"=>"max","value"=>I('post.max'),0,"intval"),
			array("aid"=>$aid,"name"=>"初始分值","field"=>"start","value"=>I('post.start'),0,"intval"),
			array("aid"=>$aid,"name"=>"积分单位","field"=>"unit","value"=>I('post.unit')),
			array("aid"=>$aid,"name"=>"固定助力积分","field"=>"step","value"=>I('post.step'),0,"intval"),
			array("aid"=>$aid,"name"=>"助力随机积分范围","field"=>"steprandom","value"=>I('post.steprandom')),
			array("aid"=>$aid,"name"=>"助力积分方式","field"=>"steptype","value"=>I('post.steptype')),
		);
		sp_save_activity_params($data);
	}
	public function showuser(){
		$uid=I('get.uid',0,'intval');
		$result=$this->activity_user->where(array('id'=>$uid))->select();
		print_r($result);
	}
	public function showfriend(){
		$result=$this->activity_friend->where(array('aid'=>2292))->select();
		print_r($result);
	}
	
}

