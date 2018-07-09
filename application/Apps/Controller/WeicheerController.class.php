<?php

/**
 * 喝彩
 */
namespace Apps\Controller;
use Common\Controller\ActivitybaseController; 
class WeicheerController extends ActivitybaseController {
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
	  	$uid = I('get.uid',0,'intval');
	  	//定义分享链接。
	  	$url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/weicheer/index',array('id'=>$aid,'uid'=>$uid));
	  	//检查浏览器及获取公众号信息。
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('weicheer', 'index', $aid, $uid);
		} else {
			$this->entry($url);
		}
	  	//获取扩展字段。
	  	$item = sp_get_activity($aid);
	   	if(!$item)
	  	{
			$this->error('活动不存在');
	  	}
	  	$status=0;
	  	$cando=1;
	  
	  	//判断活动是否开始。
	  	if ($item['begintime']>time()) {
		  	$status=-1;
	  	}
	  	if ($item['endtime']<=time()) {
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
		  	} else {
			  	$status=-2;
		  	}
	  	} elseif(!empty($user['mobile'])) {
			$shareuid=$user['id'];
		}
		if(!empty($user['mobile']))
		{
			$is_regist=true;
		}
	  	//自己助力值
	  	if($is_regist) {
			$myscore=$this->activity_user->field('total_num')->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
		 	$sql="select count(*) from cmf_activity_user where aid=$aid and total_num >".intval($myscore['total_num']);
			$pm=M()->query($sql);
			$mypm=intval($pm[0]['count(*)']);
			$mypm++;
			$this->assign("mypm",$mypm);
			
	  	}
	  	//别人助力值
	   	if($isme==0) {
			$hisscore=$this->activity_user->field('total_num')->where(array('id'=>$uid))->find();
			$sql="select count(*) from cmf_activity_user where aid=$aid and total_num >".intval($hisscore['total_num']);
		 	$pm=M()->query($sql);
			$hispm=intval($pm[0]['count(*)']);
			$hispm++;
			$this->assign("hispm",$hispm);
	  	}
	  	$this->assign("status",$status);
	  	$this->assign("is_regist",$is_regist);
	  	$this->assign("isme",$isme);
	  	//如果有分享人信息就改变分享链接。
	  	if($shareuid!=0) {
			$share_url='http://'.$_SERVER['HTTP_HOST'].U('apps/weicheer/index',array('id'=>$aid,'uid'=>$shareuid));
	  	} else {
			//否则，分享链接是自己的。
		  	$share_url='http://'.$_SERVER['HTTP_HOST'].U('apps/weicheer/index',array('id'=>$aid));
	  	}
	  	$url = $share_url;
	  	$this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' => $url,
			'imgUrl' => $item['share_img']
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
      	$this->display();
    }
	public function test_upload(){
		echo "测试文件上传！！！";
		$savepath=date('Ymd').'/';
		$base64 = "data:image/jpeg;base64,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";


		$type_limit = array('jpg','jpeg','png');
		if(preg_match('/data:\s*image\/(\w+);base64,/iu',$base64,$tmp)){
			if(!in_array($tmp[1],$type_limit)){
				//$data['status']=0;
				//$data["msg"]='图片格式不正确，只支持jpg,jpeg,png!';
				echo "图片格式不正确";
			}
		}else{
			$data['status']=0;
			//$data["msg"]='抱歉！上传失败，请重新再试!';
			echo "抱歉上传失败！";
		}
		echo $tmp[1];
		echo "图片编码没有问题！！！！！";
		$thumb = str_replace(' ','+',$base64);

		$thumb = str_replace($tmp[0], '', $thumb);

		$thumb = base64_decode($thumb);
		$savepath='avatar/';
		//var_dump($thumb);
		$path = './'.C("UPLOADPATH").$savepath.uniqid().".".trim($tmp[1]);
		$filename = "./data/upload/".uniqid().".".trim($tmp[1]);
		sp_file_write($path,$thumb);
		//echo $path;
		//$file = file($filename);
		//echo $filename;
        	//上传处理类
		$config=array(
				'rootPath' => './'.C("UPLOADPATH"),
				'savePath' => $savepath,
				'maxSize' => 11048576,
				'saveName'   =>    array('uniqid',''),
				'exts'       =>    array('jpg', 'gif', 'png', 'jpeg',"txt",'zip'),
				'autoSub'    =>    false,
		);
		$upload = new \Think\Upload($config);//
		$xx = file($path);
		var_dump($xx);
		//$info = $upload->uploadOne($path);
		//var_dump($info);
		//echo './'.C("UPLOADPATH");
		//echo $savepath;
		//echo uniqid();
		//echo $upload->getSaveName("");
		die();
	}
	public function check()
    	{	
        	$user_info=session('userInfo');
		$thumb = I('post.thumb');
		$aid = I('post.aid',0,'intval');
		$res['status']=-1;
		$item = sp_get_activity($aid);
		if(time()>$item['endtime']){
			$res['status']=0;
			$res['msg']="活动已结束";	
			echo json_encode($res,true);
			die();
		}
		if(time()<$item['begintime']){
			$res['status']=0;
			$res['msg']="活动还未开始";	
			echo json_encode($res,true);
			die();
		}
		if(empty($thumb)){
			$res['status']=0;
			$res['msg']="请上传照片";	
			echo json_encode($res,true);
			die();
		}
		$user=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
		//$res['msg']=$user['nickname'].'>>'.$user['mobile'].">>".$this->activity_user->getLastSql();
		if(!empty($user['mobile'])){
			$res['status']=0;
			$res['msg']="您已报过名了，不能重复报名！";	
			echo json_encode($res,true);
			die();
		}
		//更新用户信息 用户名字，电话，头像
		$data=array(
			'from_user'=>$user_info['openid'],
			'nickname'=>$user_info['nickname'],
			'avatar'=>$user_info['headimgurl'],
			'createtime'=>time(),
			'update_time'=>time(),
			'aid'=>$aid,
			'username'=>I("post.name"),
			'mobile'=>I("post.tel"),
			'age'=>I("post.age"),
			'class'=>I("post.cless"),
			'school'=>I("post.school"),
			'remark'=>I("post.remark"),
			'thumb'=>$thumb,
		);
		//添加 人气值
		$params = sp_get_activity_params($aid);	
	    $params = array_column($params, 'value', 'field');
		$data['total_num']=$params['pvalue'];
		$result=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->save($data);
		if($result){
			$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->save($data);
		}else{
			$this->activity_user->add($data);
			$this->activity_params->where(array('aid'=>$aid,'field'=>'maxjoin'))->setDec('value');
		}
		$res['status']=1;
		$res['msg']="已注册";	
		echo json_encode($res,true);
		die();
    	}
	//给朋友喝彩 相当于在friend表中写数据
	public function cheer()
	{
		$aid=I('post.aid',0,'intval');
		$uid=I('post.uid',0,'intval');
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
			  if($activity['day_num']>0)
			  {
				  $today = strtotime(date("Y-m-d",time()));
				  $day_num=$this->activity_friend->where("aid=".$aid." and from_user='".$user_info['openid']."' and update_time>=".$today)->count();
				  //$res['msg']=$day_num.'>>>'.$activity['day_num'];
				  if($day_num>=$activity['day_num'])
				  {
					  	$res['status']=0;
						$res['msg']="当天次数已用完";
						echo json_encode($res,true);
						die();
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
			  //写入数据
			  $friend_info['uid']=$uid;
			  $friend_info['aid']=$aid;
			  $friend_info['from_user']=$user_info['openid'];
			  $friend_info['nickname']=$user_info['nickname'];
			  $friend_info['avatar']=$user_info['headimgurl'];
			  $friend_info['createtime']=time();
			  $friend_info['update_time']=time();
			  $friend_info['total_num']=$pvalue;
			  $this->activity_friend->add($friend_info);
		}
		echo json_encode($res,true);
		die();
	}
	public function analyze_activitys_friend($aid,$uid){
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
		//获取活动信息。
		$activity=sp_get_activity($aid);
		  if($activity['day_num']>0)
		  {
			  $today = strtotime(date("Y-m-d",time()));
			  $day_num=$this->activity_friend->where(array('aid'=>$aid,'from_user'=>$userInfo['openid']))->sum('day_num');
			  if($day_num>=$activity['day_num'])
			  {
					$res['status']=0;
					$res['msg']="当天次数已用完";
					echo json_encode($res,true);
					die();
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
	/*跳挂卡*/
	public function guajiang()
	{
		set_wechat_info();
		  //获取活动id。
		  $aid = I("get.id",0,"intval");
		  //看是否是分享过来的页面。
		  $uid=I('get.uid',0,'intval');
		  //定义分享链接。
		  //$url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/weicheer/index',array('id'=>$aid,'uid'=>$uid));
		  $share_url=U('apps/weicheer/index',array('id'=>$aid,'uid'=>$uid));
	  	  $url =$this->auth_url.$share_url;
		  //检查浏览器及获取公众号信息。
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('weicheer', 'guajiang', $aid, $uid);
		} else {
			$this->entry($url);
		}
		  //获取扩展字段。
		  $item = sp_get_activity($aid);
		  $status=0;
		  $cando=1;  
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
		  $prize_name=$this->zhongjiang($aid);
		  $this->assign('prize_name',$prize_name);
		  //中奖人列表
		 $prize=$this->activity_user_prize->where('aid='.$aid)->order('id desc')->select();
		 $prize_array = array();
		 foreach($prize as $v){
				$prize_array[]=$v['username']."刮中了".$v['name'];
			}
		$prize_str= implode(" ",$prize_array);
			if(!$prize_str){
				$prize_str="暂无人中奖";}
		$this->assign('prize_str',$prize_str);
		$this->display('/Weicheer/guaka');
	}
	/*中奖函数*/
	public function zhongjiang($aid)
	{
			$aid=$aid;
			$item = sp_get_activity($aid);
			$params = sp_get_activity_params($aid);	
		    $params = array_column($params, 'value', 'field');
			//在这里设计中奖函数
			$prize_arr = array(   
			'0' => array('id'=>1,'prize'=>$params['prize1_name'],'v'=>$params['prize1_rate'],'num'=>$params['prize1_total'],'pic'=>$params['prize1_pic']),   
			'1' => array('id'=>2,'prize'=>$params['prize2_name'],'v'=>$params['prize2_rate'],'num'=>$params['prize2_total'],'pic'=>$params['prize2_pic']),   
			'2' => array('id'=>3,'prize'=>$params['prize3_name'],'v'=>$params['prize3_rate'],'num'=>$params['prize3_total'],'pic'=>$params['prize3_pic']),   
			'3' => array('id'=>4,'prize'=>$params['prize4_name'],'v'=>$params['prize4_rate'],'num'=>$params['prize4_total'],'pic'=>$params['prize4_pic']),   
			'4' => array('id'=>5,'prize'=>$params['prize5_name'],'v'=>$params['prize5_rate'],'num'=>$params['prize5_total'],'pic'=>$params['prize5_pic']), 
			'5' => array('id'=>6,'prize'=>$params['prize6_name'],'v'=>$params['prize6_rate'],'num'=>$params['prize6_total'],'pic'=>$params['prize6_pic']),   
			//'6' => array('id'=>7,'prize'=>'下次没准就能中哦','v'=>50,'num'=>0,'pic'=>0),   
		);    
			/* 
			 * 每次前端页面的请求，PHP循环奖项设置数组， 
			 * 通过概率计算函数get_rand获取抽中的奖项id。 
			 * 将中奖奖品保存在数组$res['yes']中， 
			 * 而剩下的未中奖的信息保存在$res['no']中， 
			 * 最后输出json个数数据给前端页面。 
			 */  
			foreach ($prize_arr as $key => $val) {   
				$arr[$val['id']] = $val['v'];   
			}   
			$rid = $this->get_rand($arr); //根据概率获取奖项id   
			//判断奖品数量
			if((int)($prize_arr[$rid-1]['num'])>0)
			{
				$res['yes'] = $prize_arr[$rid-1]['prize'];
				$res['pic']=$prize_arr[$rid-1]['pic'];
			}
			else
			{
				$res['yes'] = "下次没准就能中哦";
			}
			unset($prize_arr[$rid-1]); //将中奖项从数组中剔除，剩下未中奖项   
			shuffle($prize_arr); //打乱数组顺序   
			for($i=0;$i<count($prize_arr);$i++){   
				$pr[] = $prize_arr[$i]['prize'];   
			}
			return $res['yes'];
	}
	//计算中奖概率
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
	//添加中奖人信息
	public function addprize()
	{
		$username=I('post.name');
		$mobile=I('post.tel');
		$aid=I('post.aid',0,'intval');
		$prize_kind=I('post.prize_name');
		//$res['msg']='username'.$username.'mobile'.$mobile.'aid'.$aid.'prize_kind'.$prize_kind;
		//更新活动的库存
		$item = sp_get_activity($aid);
		$params = sp_get_activity_params($aid);	
		$params = array_column($params, 'value', 'field');
		$flag=0;
		if($params['prize1_name']==$prize_kind){
			if($params['prize1_total']){
				$this->activity_params->where(array('aid'=>$aid,'field'=>'prize1_total'))->setDec('value');
				$flag='prize1';
			}else{
				$res['status']=-1;
				$res['msg']="Sorry,您来晚了，该奖品已被领完！";
				exit(json_encode($res,true));
			}
		}
		if($params['prize2_name']==$prize_kind){
			if($params['prize2_total']){
				$this->activity_params->where(array('aid'=>$aid,'field'=>'prize2_total'))->setDec('value');
				$flag='prize2';
			}else{
				$res['status']=-2;
				$res['msg']="Sorry,您来晚了，该奖品已被领完！";
				exit(json_encode($res,true));
			}
		}
		if($params['prize3_name']==$prize_kind){
			if($params['prize3_total']){
				$this->activity_params->where(array('aid'=>$aid,'field'=>'prize3_total'))->setDec('value');
				$flag='prize3';
			}else{
				$res['status']=-3;
				$res['msg']="Sorry,您来晚了，该奖品已被领完！";
				exit(json_encode($res,true));
			}
		}
		if($params['prize4_name']==$prize_kind){
			if($params['prize4_total']){
				$this->activity_params->where(array('aid'=>$aid,'field'=>'prize4_total'))->setDec('value');
				$flag='prize4';
			}else{
				$res['status']=-4;
				$res['msg']="Sorry,您来晚了，该奖品已被领完！";
				exit(json_encode($res,true));
			}
		}
		if($params['prize5_name']==$prize_kind){
			if($params['prize5_total']){
				$this->activity_params->where(array('aid'=>$aid,'field'=>'prize5_total'))->setDec('value');
				$flag='prize5';
			}else{
				$res['status']=-5;
				$res['msg']="Sorry,您来晚了，该奖品已被领完！";
				exit(json_encode($res,true));
			}
		}
		if($params['prize6_name']==$prize_kind){
			if($params['prize6_total']){
				$this->activity_params->where(array('aid'=>$aid,'field'=>'prize6_total'))->setDec('value');
				$flag='prize6';
			}else{
				$res['status']=-6;
				$res['msg']="Sorry,您来晚了，该奖品已被领完！";
				exit(json_encode($res,true));
			}
		}	
		//获取基本信息，向prize表添加数据
		$user_info=session('userInfo');
		$prize_info['aid']=$aid;
		$prize_info['from_user']=$user_info['openid'];
		$prize_info['username']=$username;
		$prize_info['mobile']=$mobile;
		$prize_info['thumb']=$params[$flag.'_thumb'];
		$prize_info['type']=$params[$flag.'_type'];
		$prize_info['name']=$prize_kind;
		$prize_info['createtime']=time();
		$this->activity_user_prize->add($prize_info); 
		$res['status']=1;
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
	/*保存个性字段*/
	function save_weicheer($aid){
		$data = array(
			array("aid"=>$aid,"name"=>"访问量","field"=>"hits","value"=>0),
			array("aid"=>$aid,"name"=>"人气值","field"=>"pvalue","value"=>I('post.pvalue'),0,"intval"),
			array("aid"=>$aid,"name"=>"最大报名人数限制","field"=>"maxjoin","value"=>I('post.maxjoin'),0,"intval"),
			array("aid"=>$aid,"name"=>"喝彩后是否跳转","field"=>"steptype","value"=>I('post.steptype'),0,"intval"),
			array("aid"=>$aid,"name"=>"报名需填信息","field"=>"other_remark","value"=>I('post.other_remark')),
			array("aid"=>$aid,"name"=>"校区","field"=>"other_school","value"=>I('post.other_school')),
			array("aid"=>$aid,"name"=>"年龄","field"=>"age","value"=>I('post.age')),
			array("aid"=>$aid,"name"=>"班级","field"=>"cless","value"=>I('post.cless')),
			array("aid"=>$aid,"name"=>"学校","field"=>"school","value"=>I('post.school')),
			array("aid"=>$aid,"name"=>"其他","field"=>"qita","value"=>I('post.qita')),
			array("aid"=>$aid,"name"=>"跳转链接","field"=>"link","value"=>I('post.link'),0,"intval"),
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
			
			array("aid"=>$aid,"name"=>"奖品4图片","field"=>"prize4_thumb","value"=>I('post.prize4_thumb')),
			array("aid"=>$aid,"name"=>"奖品4名称","field"=>"prize4_name","value"=>I('post.prize4_name')),
			array("aid"=>$aid,"name"=>"奖品4数量","field"=>"prize4_total","value"=>I('post.prize4_total')),
			array("aid"=>$aid,"name"=>"奖品4概率","field"=>"prize4_rate","value"=>I('post.prize4_rate')),
			array("aid"=>$aid,"name"=>"奖品4类型","field"=>"prize4_type","value"=>I('post.prize4_type')),

			array("aid"=>$aid,"name"=>"奖品5图片","field"=>"prize5_thumb","value"=>I('post.prize5_thumb')),
			array("aid"=>$aid,"name"=>"奖品5名称","field"=>"prize5_name","value"=>I('post.prize5_name')),
			array("aid"=>$aid,"name"=>"奖品5数量","field"=>"prize5_total","value"=>I('post.prize5_total')),
			array("aid"=>$aid,"name"=>"奖品5概率","field"=>"prize5_rate","value"=>I('post.prize5_rate')),
			array("aid"=>$aid,"name"=>"奖品5类型","field"=>"prize5_type","value"=>I('post.prize5_type')),

			array("aid"=>$aid,"name"=>"奖品6图片","field"=>"prize6_thumb","value"=>I('post.prize6_thumb')),
			array("aid"=>$aid,"name"=>"奖品6名称","field"=>"prize6_name","value"=>I('post.prize6_name')),
			array("aid"=>$aid,"name"=>"奖品6数量","field"=>"prize6_total","value"=>I('post.prize6_total')),
			array("aid"=>$aid,"name"=>"奖品6概率","field"=>"prize6_rate","value"=>I('post.prize6_rate')),
			array("aid"=>$aid,"name"=>"奖品6类型","field"=>"prize6_type","value"=>I('post.prize6_type')),
			
		);
		sp_save_activity_params($data);
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
		$data['update_time']='1480521600';
		$result=$this->activity_friend->where(array('uid'=>$uid))->save($data);
		print_r($result);
	}
	
	
}

