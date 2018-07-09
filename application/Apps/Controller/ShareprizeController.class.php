<?php

/**
 * 助跑赢大奖
 */
namespace Apps\Controller;
use Common\Controller\ActivitybaseController; 
class ShareprizeController extends ActivitybaseController {
	protected $jiangpai;
	protected $activity;
	protected $activity_params;
	protected $activity_user;
	protected $activity_user_data;
	protected $activity_user_prize;
	protected $activity_friend;
	protected $share_data;//定义分享数据
	function _initialize(){
		parent::_initialize();
		$this->activity = D("Activity");
		$this->activity_params = D("ActivityParams");
		$this->activity_user = D("ActivityUser");
		$this->activity_user_data = D("ActivityUserData");
		$this->activity_friend = D("ActivityFriend");
		$this->activity_user_prize = D("ActivityUserPrize");
		$this->jiangpai = D("Ad");
	}
    public function index() {
		set_wechat_info();
	  //获取活动id。
	  $aid = I("get.id",0,"intval");
	  //看是否是分享过来的页面。
	  $uid=I('get.uid',0,'intval');
	  //定义分享链接。
	  $url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/shareprize/index',array('id'=>$aid,'uid'=>$uid));
	  //检查浏览器及获取公众号信息。
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('shareprize', 'index', $aid, $uid);
		} else {
			$this->entry($url);
		}
	  if($_GET['code']){
		$flag = session('flag_redirect');
		if(!$flag){
			session('flag_redirect',1);
			header("Location:$url");
			die();
		}
	  }
	  //$this->add_userinfo($aid);
	  /*****公共信息处理******/
	  //获取参与人次数
	  $vote_count = $this->activity_friend->where(array("aid"=>$aid))->count();
	  //获取报名人数
	  $join_count = $this->activity_user->where("aid=$aid and username <>''")->count();
	  $this->assign('vote_count',$vote_count);
	  $this->assign('join_count',$join_count);
	  /*******公共信息处理结束*****************/
	  //获取扩展字段。
	  $item = sp_get_activity($aid);
	   if(!$item)
	  {
		  $this->error('活动不存在');
	  }
	  $status=0;
	  $cando=1;
	  //判断活动是否开始。
	  if($item['begintime']>time())
	  {
		  $status=-1;
		  $cando=0;
	  }
	  if($item['endtime']<=time())
	  {
		  $status=-2;
		  $cando=0;
	  }
	  $this->assign("status",$status);
	  $this->assign("cando",$cando);
	  //判断活动是否结束。
	  $params = sp_get_activity_params($aid);	
	  $params = array_column($params, 'value', 'field');
	  //获取粉丝详细信息。
	  $user_info=session('userInfo');
	  $this->assign("user_info",$user_info);
	  //获取自己的信息。
	  $user=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
	  $this->assign("user",$user);
	  //宝箱信息
	  $prize_list=D("ActivityUserPrize")->order('id desc')->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->select();
	  $this->assign("prize_list",$prize_list);
	  
	  $shareuid=0;
	  $isme=1;
	  //是否注册
	  $is_regist=false;
	  if($uid!=0)
	  {
		  //获取分享人信息。
		  $his_info=$this->activity_user->where(array('id'=>$uid))->find();
		  if(!empty($his_info['username'])&&!empty($his_info['mobile']))
		  {
		  		$shareuid=$uid;
				if($his_info['from_user']!=$user_info['openid'])
				{
					$isme=0;
				}
		  }
		  else
		  {
			  $status=-2;
		  }
	  }
	  else if(!empty($user['mobile']))
		{
			$shareuid=$user['id'];
		}
		if(!empty($user['mobile']))
		{
			$is_regist=true;
		}
	  if($uid!=0){
	  	$friend_count=$this->activity_friend->where(array('aid'=>$aid,'uid'=>$uid))->count();
		$this->assign('friend_count',$friend_count);
	  }else{
	  	$user=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
		$uid=$user['id'];
	  	$friend_count=$this->activity_friend->where(array('aid'=>$aid,'uid'=>$uid))->count();
		$this->assign('friend_count',$friend_count);
	  }
	  $this->assign("status",$status);
	  $this->assign("is_regist",$is_regist);
	  $this->assign("isme",$isme);
	  //如果有分享人信息就改变分享链接。
	  if($shareuid!=0)
	  {
	  	$url= 'http://'.$_SERVER['HTTP_HOST'].U('apps/shareprize/index',array('id'=>$aid,'uid'=>$shareuid));
	  }
	  //否则，分享链接是自己的。
	  else
	  {
		  $url= 'http://'.$_SERVER['HTTP_HOST'].U('apps/shareprize/index',array('id'=>$aid));
	  }
	  $this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' => $url,
			'imgUrl' =>$item['share_img']
	  );
	  $this->assign("shareuid",$shareuid);
	  $this->assign("share",$this->share_data);
	  $this->assign("params",$params);
	  $this->assign("item",$item);
	  $user_info=session('userInfo');
	  $this->assign("his_info",$his_info);
	  $this->assign("user_info",$user_info);
	  if($user['total_num']){
	  	$rate=$user['total_num'];
	  }
	  if($his_info['total_num']){
	  	$rate=$his_info['total_num'];
	  }
	  $rate=ceil($rate/$item['total_num']*100);
	  $this->assign('rate',$rate);
      $this->display();
    }
	/*注册*/
	public function regist()
	{
		$user_info=session('userInfo');
		$aid=I('get.id',0,'intval');
		$data=array(
			'aid'=>$aid,
			'from_user'=>$user_info['openid'],
			'nickname'=>$user_info['nickname'],
			'avatar'=>$user_info['headimgurl'],
			'username'=>I("post.name"),
			'mobile'=>I("post.mobile"),
			'remark'=>I("post.remark"),
			'age'=>I("post.age"),
			'class'=>I("post.cless"),
			'school'=>I("post.school"),
			'createtime'=>time(),
			'update_time'=>time(),
		);
		$item = sp_get_activity($aid);
		$params = sp_get_activity_params($aid);	
		$params = array_column($params, 'value', 'field');
		//助力值初始值
		$result=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
		if($result){
			$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->save($data);
		}else{
			$this->activity_user->add($data);
		}
		$res['status']=1;
		$res['msg']="注册成功";
		//防止缓存
		$res['url']='http://'.$_SERVER['HTTP_HOST'].U('apps/shareprize/index',array('id'=>$aid,'ss'=>1));
		$this->ajaxReturn($res);
	}
	/*助力*/
	public function zhuli()
	{
		$aid=I('get.id',0,'intval');
		$uid=I('get.uid',0,'intval');
		$user_info=session('userInfo');
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
					  $res['status']=-1;
						$res['msg']="总次数已用完";
						$res['goon']=0;
						$this->ajaxReturn($res);
				  }
			  }
			  if($activity['day_num']>0)
			  {
				  $today = strtotime(date("Y-m-d",time()));
				  $day_num=$this->activity_friend->where("aid=".$aid." and uid=".$uid." and from_user='".$user_info['openid']."' and createtime>=".$today)->count();
				  if($day_num>=$activity['day_num'])
				  {
					  $res['status']=-2;
						$res['msg']="当天次数已用完";
						$res['goon']=0;
						$this->ajaxReturn($res);
				  }
			  }
			  if($user['total_num']>=$activity['total_num']){
			  		 $res['status']=-3;
					 $res['msg']="助跑已完成";
					 $res['goon']=0;
					 $this->ajaxReturn($res);
			  }
			  //更新指定的会员。
			  //增加积分
			  //活动扩展信息
			  $params = sp_get_activity_params($aid);	
	  		  $params = array_column($params, 'value', 'field');
			  $type=$params['steptype'];
			  $step=1;
			  $hisscore=I('post.hisscore',0,'intval');
			  if($type==1)
			  {
			  	//固定增加
				$step=1;
				if($params['step'])
				{
					$step=intval($params['step']);
				}
				if(($step+$user['total_num'])>=$activity['total_num']){
					//$data['update_time'] = time();
					$data['total_num']=$activity['total_num'];
					$step=$activity['total_num']-$user['total_num'];
					$this->activity_user->where(array('id'=>$uid))->save($data);   //记录助力值，total_num
					$res['newhisscore']=$activity['total_num'];	
				}else{
					//$this->activity_user->where(array('id'=>$uid))->save(array('update_time'=>time()));   //更新下时间
					$this->activity_user->where(array('id'=>$uid))->setInc('total_num',$step);   //记录助力值，total_num	
					$res['newhisscore']=$step+$hisscore;	
				}
				
			  }
			  if($type==0)
			  {
			  	//随机增加
				$step=1;
				if($params['steprandom'])
				{
					$str=$params['steprandom'];
					$str=explode(",",$str);
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
				if(($step+$user['total_num'])>=$activity['total_num']){
					$data['total_num']=$activity['total_num'];
					$step=$activity['total_num']-$user['total_num'];
					$this->activity_user->where(array('id'=>$uid))->save($data);   //记录助力值，total_num
					$res['newhisscore']=$activity['total_num'];	
				}else{
					$this->activity_user->where(array('id'=>$uid))->setInc('total_num',$step);   //记录助力值，total_num	
					$res['newhisscore']=$step+$hisscore;	
				}		
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
			  //更新下参与用户的时间
			  if($user['total_num']<$activity['total_num']){
				$this->activity_user->where(array('id'=>$uid))->save(array('update_time'=>time()));   //更新下时间
			  }
			  //判断是否可以继续写入*/
			  $res['status']=1;
			  $res['msg']="助力成功"."step".$step;
			  $res['step']=$step;
			  session('is_zhuli',1);  //设置session
		}
		$this->ajaxReturn($res);	
	}
	public function analyze_activitys_friend($aid,$uid){			  
			$userInfo = session('userInfo');
			$activity = D("Activity")->where(array("id"=>$aid,"valid"=>1))->find();
			$user = $this->activity_user->where(array("id"=>$uid))->find();
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
			if($activity['per_num']>0){
				  //获取这个人的总加油次数和当天加油次数。
				  $total_num=$this->activity_friend->where(array("aid"=>$aid,"from_user"=>$userInfo['openid']))->sum('total_num');
				  if($total_num>=$activity['per_num']){
						$res['status']=-1;
						$res['msg']="总次数已用完";
						$this->ajaxReturn($res);
				  }
			  }
			  if($activity['day_num']>0){
				  $today = strtotime(date("Y-m-d",time()));
				  $day_num=$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->sum('day_num');
				  if($day_num>=$activity['day_num']){
						$res['status']=-2;
						$res['msg']="当天次数已用完";
						$this->ajaxReturn($res);
				  }
			  }
			  if($user['total_num']>=$activity['total_num']){
					 $res['status']=-3;
					 $res['msg']="助跑已完成";
					 $this->ajaxReturn($res);
			  }
			  //更新指定的会员。
			  //增加积分
			  //活动扩展信息
			  $params = sp_get_activity_params($aid);	
	  		  $params = array_column($params, 'value', 'field');
			  $type=$params['steptype'];
			  $step=1;
			  $hisscore=I('post.hisscore',0,'intval');
			  if($type==1)
			  {
			  	//固定增加
				$step=1;
				if($params['step'])
				{
					$step=intval($params['step']);
				}
				if(($step+$user['total_num'])>=$activity['total_num']){
					$data['total_num']=$activity['total_num'];
					$step=$activity['total_num']-$user['total_num'];
					$this->activity_user->where(array('id'=>$uid))->save($data);   //记录助力值，total_num
					$res['newhisscore']=$activity['total_num'];	
				}else{
					$this->activity_user->where(array('id'=>$uid))->setInc('total_num',$step);   //记录助力值，total_num	
					$res['newhisscore']=$step+$hisscore;	
				}
				
			  }
			  if($type==0)
			  {
			  	//随机增加
				$step=1;
				if($params['steprandom'])
				{
					$str=$params['steprandom'];
					$str=explode(",",$str);
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
				if(($step+$user['total_num'])>=$activity['total_num']){
					$data['total_num']=$activity['total_num'];
					$step=$activity['total_num']-$user['total_num'];
					$this->activity_user->where(array('id'=>$uid))->save($data);   //记录助力值，total_num
					$res['newhisscore']=$activity['total_num'];	
				}else{
					$this->activity_user->where(array('id'=>$uid))->setInc('total_num',$step);   //记录助力值，total_num	
					$res['newhisscore']=$step+$hisscore;	
				}		
			  }
			  $this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->setInc('day_num');
		      $this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->setInc('total_num');
		      
			  $friend=$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->find();
		  	  $frienddata['day_value']=intval($friend['day_value'])+$step;
		      $frienddata['total_value']=intval($friend['total_value'])+$step;
		      $frienddata['update_time']=time();
		      $this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->save($frienddata);
			  //更新下参与用户的时间
			  if($user['total_num']<=$activity['total_num']){
				$this->activity_user->where(array('id'=>$uid))->save(array('update_time'=>time()));   //更新下时间
			  }
			  //判断是否可以继续写入*/
			  $res['status']=1;
			  $res['msg']="助力成功"."step".$step;
			  $res['step']=$step;
			  session('is_zhuli',1);  //设置session
			  $this->ajaxReturn($res);
	}
	//打开中奖界面
	public function get_prize(){
		$aid=I('get.id',0,'intval');
		$uid=I('get.uid',0,'intval');
		$item = sp_get_activity($aid);
		$params = sp_get_activity_params($aid);	
	    	$params = array_column($params, 'value', 'field');
		$this->assign('item',$item);
		$this->assign('params',$params);
		//查宝箱
		$userInfo = session('userInfo');
		$prize_list=D("ActivityUserPrize")->order('id desc')->where(array('aid'=>$aid,'from_user'=>$userInfo['openid']))->select();
		$this->assign("prize_list",$prize_list);
		//中奖后的姓名电话号码
		$prize_user=D("ActivityUserPrize")->where(array('aid'=>$aid,'from_user'=>$userInfo['openid']))->find();
		if($prize_user){
			$this->assign("prize_user",$prize_user);
		}
		//今天还有几次参与机会
		$today=strtotime('today');
		$sql="select count(*) from cmf_activity_friend where aid=$aid and uid=$uid and  from_user='".$userInfo['openid']."'  and createtime>=$today";
		$Model = new \Think\Model(); // 实例化一个model对象 没有对应任何数据表
		$day_num=$Model->query($sql);
		/*print_r($day_num);
		die();*/
		$day_num=$day_num[0]['count(*)']; 
		$sql=$Model->getLastSql();
		$this->assign('a',$item['day_num']);
		$this->assign('b',$day_num);
		$day_num=$item['day_num']-$day_num;
		$this->assign('day_num',$day_num);
		$url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/shareprize/index',array('id'=>$aid,'uid'=>$uid));
		//定义分享
		$this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' =>$url,
			'imgUrl' =>$item['share_img']
	  	);
		$this->assign("share",$this->share_data);
		$this->display('choujiang');
	}
	//计算中奖函数
	public function get_gift(){
		$aid = I("get.id",0,"intval");
		$activity = sp_get_activity($aid);
		$params = sp_get_activity_params($aid);	
		$activity['params'] = array_column($params, 'value', 'field');
		//查已经中奖几次
		$userInfo = session('userInfo');
		$prize_count=D("ActivityUserPrize")->where(array('aid'=>$aid,'from_user'=>$userInfo['openid']))->count();
		if($prize_count>=$activity['params']['zhongjiang_max']){
			$result['status']=-1;
			$result['msg']="没有中奖";
			$this->ajaxReturn($result);
		}
		$rate_kong=100-$activity['params']['prize1_rate']-$activity['params']['prize2_rate']-$activity['params']['prize3_rate'];
		$zhi=1;
		if($rate_kong<0)
		$rate_kong=0;
		$prize_arr = array( 
		  '0' => array('id'=>1,'prize'=>$activity['params']['prize1_name'],'v'=>$activity['params']['prize1_rate']), 
		  '1' => array('id'=>2,'prize'=>$activity['params']['prize2_name'],'v'=>$activity['params']['prize2_rate']), 
		  '2' => array('id'=>3,'prize'=>$activity['params']['prize3_name'],'v'=>$activity['params']['prize3_rate']), 
		  '3' => array('id'=>4,'prize'=>'下次没准就能中哦','v'=>$rate_kong), 
		);
		foreach ($prize_arr as $key => $val) { 
		  $arr[$val['id']] = $val['v']; 
		} 
		$rid = $this->get_rand($arr); //根据概率获取奖项id 		  
		$res['yes'] = $prize_arr[$rid-1]['prize']; //中奖项
		if($activity['params']['prize'.$rid.'_total']<=0){
			$res['yes']="下次没准就能中哦";
		} 
		unset($prize_arr[$rid-1]); //将中奖项从数组中剔除，剩下未中奖项 
		shuffle($prize_arr); //打乱数组顺序 
		for($i=0;$i<count($prize_arr);$i++){ 
		  $pr[] = $prize_arr[$i]['prize']; 
		} 
		$res['no'] = $pr;
		//减少库存，增加中奖记录
		if($res['yes']!="下次没准就能中哦"){
			$this->activity_params->where(array('aid'=>$aid,'field'=>'prize'.$rid.'_total'))->setDec('value');
			$result['status']=1;
			$result['id']=$rid;
			$result['name']=$activity['params']['prize'.$rid.'_name'];
			$result['type']=$activity['params']['prize'.$rid.'_type'];
			$result['pic']=$activity['params']['prize'.$rid.'_thumb'];
		}else{
			$result['status']=-1;
			$result['msg']="没有中奖";
		}
		$this->ajaxReturn($result);
	}
	function get_rand($proArr) { 
	  $result = ''; 
	  //概率数组的总概率精度 
	  $proSum = array_sum($proArr); 
	  
	  //概率数组循环 
	  foreach ($proArr as $key => $proCur) { 
		$randNum = mt_rand(1, $proSum); 
		if ($randNum <= $proCur) { 
		  $result = $key; 
		  break; 
		} else { 
		  $proSum -= $proCur; 
		} 
	  } 
	  unset ($proArr); 
	  
	  return $result; 
	} 
	/**
	* 领取奖品   添加prize表
	*/
	public function add_gift(){
		$aid = I("get.id",0,"intval");
		$uid = I("get.uid",0,"intval");
		$data['username']=I('post.name');
		$data['mobile']=I('post.mobile');
		$prize_id=I('post.prize_id',0,'intval');
		$userInfo = session('userInfo');
		$activity = sp_get_activity($aid);
		$params = sp_get_activity_params($aid);	
		$activity['params'] = array_column($params, 'value', 'field');
		
		$data['aid']=$aid;
		$data['from_user']=$userInfo['openid'];
		$data['thumb']=$activity['params']['prize'.$prize_id.'_thumb'];
		$data['type']=$activity['params']['prize'.$prize_id.'_type'];
		$data['name']=$activity['params']['prize'.$prize_id.'_name'];
		$data['createtime']=time();
		D("ActivityUserPrize")->add($data);
		$result['status']=1;
		$result['msg']="领奖成功";
		$this->ajaxReturn($result);
	}
	//处理后台显示奖品删除
	public function delete_shareprize_prize(){
		$id=I('post.id',0,'intval');
		$result=D("ActivityUserPrize")->where(array('id'=>$id))->delete();
		if($result){
			$res['status']=1;
		}else{
			$res['status']=-1;
			$res['msg']="删除失败";
		}
		$this->ajaxReturn($res);
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
		$this->activity_user->where(array('id'=>$masterid))->setDec('total_num',$step);
		$res['status']=1;
		$this->ajaxReturn($res);
	}
	/*我的喝彩记录*/
	public function morefriend()
	{
		$aid=I('get.id',0,'intval');
		$page_f=I('post.page_f',0,'intval');
		$size=15;
		$uid=I('get.uid',0,'intval');
		if($uid!=0){
			$list =$this->activity_friend->where(array('aid'=>$aid,'uid'=>$uid))->order('createtime desc')->limit(($page_f-1)*$size.",".$size)->select();
			if($list)
			{
				$res['status']=1;	
				$res['list']=$list;
			}
			else
			{
				$res['status']=-1;	
			}
		}else{
			$user_info=session('userInfo');
			$user=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
			$uid=$user['id'];
			$list =$this->activity_friend->where(array('aid'=>$aid,'uid'=>$uid))->order('update_time desc')->limit(($page_f-1)*$size.",".$size)->select();
			if($list)
			{
				$res['status']=1;	
				$res['list']=$list;
			}
			else
			{
				$res['status']=-1;	
			}
		}
		$this->ajaxReturn($res);
	}
	
	/*加载排行榜*/
	public function moreuser()
	{
		$aid=I('get.id',0,'intval');
		$page_u=I('post.page_u',0,'intval');
		$size=15;
		if($page_u==1)
		{
			$count=$this->activity_user->where('aid='.$aid.' and mobile is not null')->count();
		}
		$zongliang=$count;
		$pagecount=ceil($zongliang/$size);
		$pagecount=$pagecount==0?1:$pagecount;
		$list=$this->activity_user->where('aid='.$aid.' and mobile is not null')->order('total_num desc,update_time asc')->limit(($page_u-1)*$size.",".$size)->select();
		foreach($list as &$vo){
			$vo['update_time']=date("y-m-d H:i:s",$vo['update_time']);
		}
		if($list)
		{
			$res['status']=1;	
			$res['pagecount']=$pagecount;
			$res['list']=$list;
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
				'createtime' => time(),
				'update_time' => time()
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
	function save_shareprize($aid){
		$data = array(
			array("aid"=>$aid,"name"=>"最高得分限制","field"=>"max","value"=>I('post.max'),0,"intval"),
			array("aid"=>$aid,"name"=>"助力积分方式","field"=>"steptype","value"=>I('post.steptype')),
			array("aid"=>$aid,"name"=>"固定助力积分","field"=>"step","value"=>I('post.step'),0,"intval"),
			array("aid"=>$aid,"name"=>"助力随机积分范围","field"=>"steprandom","value"=>I('post.steprandom')),
			array("aid"=>$aid,"name"=>"助力积分方式","field"=>"steptype","value"=>I('post.steptype')),
			array("aid"=>$aid,"name"=>"最多中奖次数","field"=>"zhongjiang_max","value"=>I('post.zhongjiang_max'),0,"intval"),
			array("aid"=>$aid,"name"=>"报名需填信息","field"=>"other_remark","value"=>I('post.other_remark')),
			array("aid"=>$aid,"name"=>"校区","field"=>"other_school","value"=>I('post.other_school')),
			array("aid"=>$aid,"name"=>"年龄","field"=>"age","value"=>I('post.age')),
			array("aid"=>$aid,"name"=>"班级","field"=>"cless","value"=>I('post.cless')),
			array("aid"=>$aid,"name"=>"学校","field"=>"school","value"=>I('post.school')),
			array("aid"=>$aid,"name"=>"其他","field"=>"qita","value"=>I('post.qita')),
			
			
			array("aid"=>$aid,"name"=>"奖品1图片","field"=>"prize1_thumb","value"=>I('post.prize1_thumb')),
			array("aid"=>$aid,"name"=>"奖品1名称","field"=>"prize1_name","value"=>I('post.prize1_name')),
			array("aid"=>$aid,"name"=>"奖品1数量","field"=>"prize1_total","value"=>I('post.prize1_total')),
			array("aid"=>$aid,"name"=>"奖品1概率","field"=>"prize1_rate","value"=>I('post.prize1_rate')),
			array("aid"=>$aid,"name"=>"奖品1类型","field"=>"prize1_type","value"=>I('post.prize1_type')),

			array("aid"=>$aid,"name"=>"奖品2图片","field"=>"prize2_thumb","value"=>I('post.prize2_thumb')),
			array("aid"=>$aid,"name"=>"奖品2名称","field"=>"prize2_name","value"=>I('post.prize2_name')),
			array("aid"=>$aid,"name"=>"奖品2数量","field"=>"prize2_total","value"=>I('post.prize2_total')),
			array("aid"=>$aid,"name"=>"奖品2概率","field"=>"prize2_rate","value"=>I('post.prize2_rate')),
			array("aid"=>$aid,"name"=>"奖品2类型","field"=>"prize2_type","value"=>I('post.prize2_type')),

			array("aid"=>$aid,"name"=>"奖品3图片","field"=>"prize3_thumb","value"=>I('post.prize3_thumb')),
			array("aid"=>$aid,"name"=>"奖品3名称","field"=>"prize3_name","value"=>I('post.prize3_name')),
			array("aid"=>$aid,"name"=>"奖品3数量","field"=>"prize3_total","value"=>I('post.prize3_total')),
			array("aid"=>$aid,"name"=>"奖品3概率","field"=>"prize3_rate","value"=>I('post.prize3_rate')),
			array("aid"=>$aid,"name"=>"奖品3类型","field"=>"prize3_type","value"=>I('post.prize3_type')),
		);
		sp_save_activity_params($data);
		
	}
	//显示用户
	public function showu(){
		$result=$this->activity_user->where("username = '林政洋' ")->select();
		print_r($result);
	}
	public function saveu(){
		$data['total_num']=5000;	
		$result=$this->activity_user->where('id =905804')->save($data);
		print_r($result);
	}
	//shanchu 用户
	public function deleteu(){
		$result=$this->activity_user->where('aid in (2043)')->delete();
		print_r($result);
	}
	public function showuser(){
		$uid=I('get.uid',0,'intval');
		$result=$this->activity_user->where(array('id'=>$uid))->select();
		print_r($result);
	}
	public function showfriend(){
		$result=$this->activity_friend->where(array('aid'=>2272))->select();
		print_r($result);
	}

}

