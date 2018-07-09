<?php

/**
 * 大转盘助力活动
 */
namespace Apps\Controller;
use Common\Controller\ActivitybaseController; 
class WeidiomController extends ActivitybaseController {
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
		$this->chengyu = D("WeidiomsChengyu");
	}
    public function index() {
		set_wechat_info();
	  //获取活动id。
	  $aid = I("get.id",0,"intval");
	  //看是否是分享过来的页面。
	  $uid=I('get.uid',0,'intval');
	  //定义分享链接。
	  $url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/weidiom/index',array('id'=>$aid,'uid'=>$uid));
	  //检查浏览器及获取公众号信息。
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('weidiom', 'index', $aid, $uid);
		} else {
			$this->entry($url);
		}
//	  $this->entry($url);
	 //访问量
	  //$sql="update cmf_activity set hits=hits+1 where id=$aid;";
	  //M()->execute($sql);
	  //获取扩展字段。
	  $item = sp_get_activity($aid);
	   if(!$item)
	  {
		  $this->error('活动不存在');
	  }
	  $status=0;
	  //判断活动是否开始。
	  if($item['begintime']>time())
	  {
		  $status=-1;
	  }
	  if($item['endtime']<=time())
	  {
		  $status=-2;
	  }
	  $this->assign("status",$status);
	  //判断活动是否结束。
	  $params = sp_get_activity_params($aid);	
	  $params = array_column($params, 'value', 'field');
	  $this->assign('item',$item);
	  $this->assign('params',$params);
	  //获取粉丝详细信息。
	  $user_info=session('userInfo');
	  $this->assign("user_info",$user_info);
	  //获取自己的信息。
	  $user=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
	  $this->assign("user",$user);
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
	  //自己助力值
	  if($is_regist)
	  {
			$word=$user['remark'];
	  }
	  //别人助力值
	   if($isme==0)
	  {
			$word=$his_info['remark'];
			$flag=$his_info['per_num'];
			$word1=mb_substr($word,0,1,'utf-8');
			$word2=mb_substr($word,1,1,'utf-8');
			$word3=mb_substr($word,2,1,'utf-8');
			$word4=mb_substr($word,3,1,'utf-8');
			//成语四个字
			  $this->assign("word1",$word1);
			  $this->assign("word2",$word2);
			  $this->assign("word3",$word3);
			  $this->assign("word4",$word4);
			//下标
			 $this->assign("flag",$flag);
	  }
	  //成语
	  $this->assign("word",$word);
	  
	  $this->assign("status",$status);
	  $this->assign("is_regist",$is_regist);
	  $this->assign("isme",$isme);
	  //如果有分享人信息就改变分享链接。

	  if($shareuid!=0)
	  {
	  	$url= C('DOMAIN_NAME').U('apps/weidiom/index',array('id'=>$aid,'uid'=>$shareuid));
		$zhuuser=$this->activity_user->where(array('aid'=>$aid,'id'=>$shareuid))->find();
	  }
	  //否则，分享链接是自己的。
	  else
	  {
		  $url= C('DOMAIN_NAME').U('apps/weidiom/index',array('id'=>$aid,'uid'=>$shareuid));
	  }
	  $this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' => $url,
			'imgUrl' =>$item['share_img']
	  );
	  $this->assign("shareuid",$shareuid);
	  $this->assign("share",$this->share_data);
	  $this->assign("his_info",$his_info);
	  //已报名
	  $user_num=$this->activity_user->where('aid='.$aid.' and mobile is not null')->count();
	  $this->assign("user_num",$user_num);
	  //参与人数
	  $friend_num=$this->activity_friend->where('aid='.$aid)->count();
	  $this->assign("friend_num",$friend_num);
//	  $this->assign("visit_num",$visit_num);
	  //$this->add_userinfo($aid);
	  $t=I('get.t',0,'intval');
	  if($t==1){
      	$this->display('indextest');
	  }else{
	  	$this->display();
	  }
    }
	//注册用户
	public function regist()
	{
		$name=I('post.name');
		$mobile=I('post.tel');
		$aid=I('get.id',0,'intval');
		$user_info=session('userInfo');	
		$user=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();	
		//再次获取用户基本信息
		$from_user = $user_info['openid'];
		$avatar=$user_info['headimgurl'];
		//获取完毕
		//$res['msg']='name'.$name.'mobile'.$mobile.'aid'.$aid;
		//现则$word
		$item = sp_get_activity($aid);
		$now=time();
		if($now>$item['endtime']){
			$res['status']=-2;
			$res['msg']="活动已结束";	
			echo json_encode($res,true);
			die();
		}
		if($now<$item['begintime']){
			$res['status']=-1;
			$res['msg']="活动还未开始";	
			echo json_encode($res,true);
			die();
		}
		if(!empty($user['mobile'])){
			$res['status']=0;
			$res['msg']="您已报过名了，不能重复报名！";	
			echo json_encode($res,true);
			die();
		}
		//添加成语
		$word_info=$this->chengyu->where('aid='.$aid)->find();
		//人气值
		$item = sp_get_activity($aid);
		$params = sp_get_activity_params($aid);	
	    $params = array_column($params, 'value', 'field');
		//添加姓名，电话
		$time=time();
		$user_data = array(
							'from_user'=>$user_info['openid'],
							'nickname'=>$user_info['nickname'],
							'avatar'=>$user_info['headimgurl'],
							'createtime'=>$time,
							'update_time'=>$time,
							'aid'=>$aid,
							'username' => $name,
							'mobile' => $mobile,
							'day_num'=>$params['pvalue'],               //day_num 表示人气值,用于排行榜次序
							'per_num'=>0,                               //per_num 表示对应成语在成语库中下标，
						    'remark'=>$word_info['idioms'],				//remark  表示成语
						);
		$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->add($user_data);
		$res['status']=1;
		echo json_encode($res,true);
		die();
	}
	/*开始接龙*/
	//给别人接龙
	public function jielong()
	{
		$aid=I('get.id',0,'intval');
		$uid=I('post.uid',0,'intval');
		$flag=I('post.flag',0,'intval');
		$word=I('post.word');
		
		$t=I('get.t',0,'intval');
		if($t==1){
			$this->analyze_activitys_friend($aid,$uid,$flag,$word);
		}
			
		//判断是否符合喝三大条件
		//获取活动信息。
			$activity=sp_get_activity($aid);
			$user_info=session('userInfo');
			/*if($activity['total_num']>0)
			  {
				  //获取这个人的总加油次数和当天加油次数。
				  $result= $this->activity_user->field('total_num')->where(array('id'=>$uid))->find();
				  $total_num=$result['total_num'];
				  //$res['msg']='activity'.$activity['total_num'].'total_num'.$total_num;
				  if($total_num>=$activity['total_num'])
				  {
					    $res['status']=0;
						$res['msg']="该好友被接龙次数已用完";
						echo json_encode($res,true);
						die();
				  }
			  }*/
			//获取自己的总共助力次数和当天助力次数。
			if($activity['per_num']>0)
			  {
				  //获取这个人的总加油次数和当天加油次数。
				  $per_num=$this->activity_friend->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->count();
				  if($per_num>=$activity['per_num'])
				  {
					  $res['status']=0;
						$res['msg']="接龙总次数已用完";
						echo json_encode($res,true);
						die();
				  }
			  }			  
			  if($activity['day_num']>0)
			  {
				  $today = strtotime(date("Y-m-d",time()));
				  $day_num=$this->activity_friend->where("aid=".$aid." and from_user='".$user_info['openid']."' and createtime>=".$today)->count();
				  if($day_num>=$activity['day_num'])
				  {
					  	$res['status']=0;
						$res['msg']="当天接龙次数已用完";
						echo json_encode($res,true);
						die();
				  }
			  }
		//判断喝彩条件结束

		//获取基本信息开始
		$user_info=session('userInfo');
		$user=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();		
		//再次获取用户基本信息
		$from_user = $user_info['openid'];
		$avatar=$user_info['headimgurl'];
		$nickname=$user_info['nickname'];
		//获取基本信息结束
		//查找活动
		$item = sp_get_activity($aid);
		$params = sp_get_activity_params($aid);	
	    $params = array_column($params, 'value', 'field');
		//增加user的人气值 和次数
		$this->activity_user->where(array('id'=>$uid))->setInc('day_num',$params['pvalue']);
		$this->activity_user->where(array('id'=>$uid))->setInc('total_num');
		//，以及修改成语的下标和对应的成语
		$chengyuku=$this->chengyu->where('aid='.$aid)->select();
		if($flag>=count($chengyuku)-1)
		{
			$flag=0;
		}
		else
		{
			$flag++;
		}
		$user_ex['per_num']= $flag;                               //下标
		$user_ex['remark']=$chengyuku[$flag]['idioms'];				//成语
		$this->activity_user->where(array('id'=>$uid))->save($user_ex);
		//插入到friend信息
		$friend_data = array(
							'aid' => $aid,
							'uid' => $uid,
							'from_user' => $from_user,
							'avatar' => $avatar,
							'nickname' => $nickname,
							'createtime' => time(),
							'total_num' =>$params['pvalue'],     //total_num用于保存增加的人气值，次数
							'remark' => $word                          //remark用于保存接龙的成语
						);
		$this->activity_friend->add($friend_data);
		//返回成语
		$res['status']=1;
		//标记
		$res['flag']=$user_ex['per_num'];
		$word=$chengyuku[$flag]['idioms'];
		$res['word']=$word;
		if($flag==0)
		{
			$flag=count($chengyuku)-1;
		}
		else
		{
			$flag--;
		}
		$res['des']=$chengyuku[$flag]['idioms_des'];
		$res['total']=$user['total_num'];
		
		//返回新的成语
		$res['word1']=mb_substr($word,0,1,'utf-8');
		$res['word2']=mb_substr($word,1,1,'utf-8');
		$res['word3']=mb_substr($word,2,1,'utf-8');
		$res['word4']=mb_substr($word,3,1,'utf-8');
		
		echo json_encode($res,true);
		die();
	}
	public function analyze_activitys_friend($aid,$uid,$flag,$word){
		$userInfo = session('userInfo');
		$activity = D("Activity")->where(array("id"=>$aid,"valid"=>1))->find();
		$user = $this->activity_user->where(array("aid"=>$aid,"from_user"=>$userInfo['openid']))->find();
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
		
		//判断是否符合喝三大条件
		//获取活动信息。
			$activity=sp_get_activity($aid);
			//获取自己的总共助力次数和当天助力次数。
			if($activity['per_num']>0){
				  //获取这个人的总加油次数和当天加油次数。
				  $total_num=$this->activity_friend->where(array('aid'=>$aid,'from_user'=>$userInfo['openid']))->sum('total_num');
				  if($total_num>=$activity['per_num']){
					  $res['status']=0;
					  $res['msg']="接龙总次数已用完";
					  echo json_encode($res,true);
					  die();
				  }
			 }			  
			 if($activity['day_num']>0){
				  $today = strtotime(date("Y-m-d",time()));
				  $day_num=$this->activity_friend->where(array('aid'=>$aid,'from_user'=>$userInfo['openid']))->sum('day_num');
				  if($day_num>=$activity['day_num']){
					  	$res['status']=0;
						$res['msg']="当天接龙次数已用完";
						echo json_encode($res,true);
						die();
				  }
			  }
		//判断喝彩条件结束
		//查找活动
		$item = sp_get_activity($aid);
		$params = sp_get_activity_params($aid);	
	    $params = array_column($params, 'value', 'field');
		//增加user的人气值 和次数
		$this->activity_user->where(array('id'=>$uid))->setInc('day_num',$params['pvalue']);
		$this->activity_user->where(array('id'=>$uid))->setInc('total_num');
		//，以及修改成语的下标和对应的成语
		$chengyuku=$this->chengyu->where('aid='.$aid)->select();
		if($flag>=count($chengyuku)-1)
		{
			$flag=0;
		}
		else
		{
			$flag++;
		}
		$user_ex['per_num']= $flag;                               //下标
		$user_ex['remark']=$chengyuku[$flag]['idioms'];				//成语
		//$res['msg']=$flag.">>>".$user_ex['remark'];
		$this->activity_user->where(array('id'=>$uid))->save($user_ex);
		//插入到friend信息
		$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->setInc('day_num');
		$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->setInc('total_num');
		$friend=$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->find();
		$frienddata['day_value']=intval($friend['day_value'])+$params['pvalue'];
		$frienddata['total_value']=intval($friend['total_value'])+$params['pvalue'];//day_value 记录今天人气 total_value总人气 remark成语组 
		$frienddata['remark']=$friend['remark'].$word.',';
		$frienddata['update_time']=time();
		$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->save($frienddata);
		//返回成语
		$res['status']=1;
		//标记
		$res['flag']=$user_ex['per_num'];
		$word=$chengyuku[$flag]['idioms'];
		$res['word']=$word;
		if($flag==0)
		{
			$flag=count($chengyuku)-1;
		}
		else
		{
			$flag--;
		}
		$res['des']=$chengyuku[$flag]['idioms_des'];
		$res['total']=$user['total_num'];
		
		//返回新的成语
		$res['word1']=mb_substr($word,0,1,'utf-8');
		$res['word2']=mb_substr($word,1,1,'utf-8');
		$res['word3']=mb_substr($word,2,1,'utf-8');
		$res['word4']=mb_substr($word,3,1,'utf-8');
		echo json_encode($res,true);
		die();
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
		$aid=I('get.id',0,'intval');
		$page_u=I('post.page_u',0,'intval');
		$size=15;
	    $list=$this->activity_user->where('aid='.$aid.' and mobile is not null')->order('total_num desc')->limit(($page_u-1)*$size.",".$size)->select();
		if($list)
		{
			$res['status']=1;	
			$res['list']=$list;
		}
		else
		{
			$res['status']=-1;	
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
	/*保存个性化字段*/
	function save_weidiom($aid,$yuanaid){
		//保存扩展字段。
		$data = array(
			array("aid"=>$aid,"name"=>"设置积分值","field"=>"pvalue","value"=>I('post.pvalue'),0,"intval"),
		);
		sp_save_activity_params($data);
		$itemid = I("post.course_id");
		$itemtitle = I("post.course_name");
		$item1 = I("post.course_yprice");
		if($yuanaid==$aid)
		{
			//将所有的id拼接起来
			$newitemid="0";
			for($i=0;$i<count($itemid);$i++)
			{
				if($itemid[$i]>0) $newitemid.=",".$itemid[$i];
			}
			//删除不存在的id。
			D("WeidiomsChengyu")->where('aid='.$aid.' and id not in('.$newitemid.')')->delete();
		}
		//遍历数据，如果有id就更新数据，没有id就插入数据。
		for($i=0;$i<count($itemid);$i++)
		{
			$data=array('idioms'=>trim($itemtitle[$i]),
						'idioms_des'=>trim($item1[$i]));
			if($itemid[$i]>0&&$yuanaid==$aid)
			{
				D("WeidiomsChengyu")->where('id='.$itemid[$i])->save($data);
			}
			else
			{
				$data['aid']=$aid;
				D("WeidiomsChengyu")->add($data);
			}
		}
	}
	
	
	public function showuser(){
		$uid=I('get.uid',0,'intval');
		$result=$this->activity_user->where(array('id'=>$uid))->select();
		print_r($result);
	}
	public function showfriend(){
		$uid=I('get.uid',0,'intval');
		$result=$this->activity_friend->where(array('uid'=>$uid))->select();
		print_r($result);
	}
	public function savefriend(){
		$uid=I('get.uid',0,'intval');
		$data['update_time']='1477929600';
		$result=$this->activity_friend->where(array('uid'=>$uid))->save($data);
		print_r($result);
	}
	public function deletefriend(){
		$fid=I('get.fid',0,'intval');
		$result=$this->activity_friend->where(array('id'=>$fid))->delete();
		print_r($result);
	}
	
}

