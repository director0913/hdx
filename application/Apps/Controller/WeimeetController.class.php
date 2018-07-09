<?php

/**
 * 活动邀请函
 */
namespace Apps\Controller;
use Common\Controller\ActivitybaseController; 
use Common\Controller\HomebaseController;

class WeimeetController extends ActivitybaseController {
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
	}
    public function index() {
		set_wechat_info();
	  $aid = I("get.id",0,"intval");
	  $shareid = I("get.shareid",0,"intval");
	  $url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/weimeet/index',array('id'=>$aid,'shareid'=>$shareid));
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('weimeet', 'index', $aid, 0,$shareid);
		} else {
			$this->entry($url);
		}
//	  	$this->entry($url);
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
	  $this->assign("cando",$cando);
	  $params = sp_get_activity_params($aid);	
	  $params = array_column($params, 'value', 'field');
	  //分享内容定义区
	 
	  $user_info=session('userInfo');
	  $shareuser = $this->activity_user->where("aid=$aid and id=$shareid")->find();
	  $user =  $this->activity_user->where("aid=$aid and from_user='".$user_info['openid']."' and username<>''")->find();
	  $userid = intval($user['id']);
	  $is_me = 0;$is_register = 0;
	  if((!empty($user)&&$userid==$shareid)||$shareid==0||empty($shareuser)){
		$is_me =1;
		$shareavatar=$user['avatar'];
		$shareid=intval($user['id']);
		$sharename=$user['username'];
		//获取自己的邀请记录
		$jilu=$this->activity_user->where("aid=$aid and pid=$shareid")->select();
	  }else{
		$shareid=intval($shareuser['id']);
		$shareavatar=$shareuser['avatar'];
		$sharename=$shareuser['username'];
	  }
	  if(!empty($user)){$is_register=1;}

	  $random_url = time();


	  $this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' => C('DOMAIN_NAME').U('apps/weimeet/index',array('id'=>$aid,'shareid'=>$shareid)),
			'imgUrl' =>$item['share_img']
	  );
	  if($shareid){
		$redirect = U("Apps/weimeet/detail",array("id"=>$aid,"shareid"=>$shareid)); 
		header("Location:$redirect");
	  }
	  $this->assign("userid",$userid);
	  $this->assign("shareid",$shareid);
	  $this->assign("sharename",$sharename);
	  $this->assign("shareavatar",$shareavatar);
	  $this->assign("jilu",$jilu);
	  $this->assign("is_me",$is_me);
	  $this->assign("is_register",$is_register);
	  //$this->add_userinfo($aid);
	  $this->assign("share",$this->share_data);
	  $this->assign("params",$params);
	  $this->assign($item);
	  
	  $this->assign("user_info",$user_info);
	  $this->assign("user",$user);
	  $this->display();
    }
	
	public function detail(){

	  $aid = I("get.id",0,"intval");
	  $shareid = I("get.shareid",0,"intval");
	  $url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/invitegift/index',array('id'=>$aid,'shareid'=>$shareid));
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('invitegift', 'index', $aid, 0,$shareid);
		} else {
			$this->entry($url);
		}
//	  	$this->entry($url);
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
	  $this->assign("cando",$cando);
	  $params = sp_get_activity_params($aid);	
	  $params = array_column($params, 'value', 'field');
	  //分享内容定义区
	 
	  $user_info=session('userInfo');
	  $shareuser = $this->activity_user->where("aid=$aid and id=$shareid")->find();
	  $user =  $this->activity_user->where("aid=$aid and from_user='".$user_info['openid']."' and username<>''")->find();
	  $userid = intval($user['id']);
	  $is_me = 0;$is_register = 0;
	  if((!empty($user)&&$userid==$shareid)||$shareid==0||empty($shareuser)){
		$is_me =1;
		$shareavatar=$user['avatar'];
		$shareid=intval($user['id']);
		$sharename=$user['username'];
	  }else{
		$shareid=intval($shareuser['id']);
		$shareavatar=$shareuser['avatar'];
		$sharename=$shareuser['username'];
	  }
	  //获取自己的邀请记录
	  $jilu=$this->activity_user->where("aid=$aid and (pid=$shareid or id=$shareid)")->select();
	  //echo $this->activity_user->getLastSql();
	  if(!empty($user)){$is_register=1;}


	  $this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' => C('DOMAIN_NAME').U('apps/weimeet/index',array('id'=>$aid,'shareid'=>$shareid)),
			'imgUrl' =>$item['share_img']
	  );
	  $this->assign("useer",$user);
	  $this->assign("shareuser",$shareuser);
	  $this->assign("userid",$userid);
	  $this->assign("shareid",$shareid);
	  $this->assign("sharename",$sharename);
	  $this->assign("shareavatar",$shareavatar);
	  $this->assign("joins",$jilu);
	  $this->assign("is_me",$is_me);
	  $this->assign("is_register",$is_register);
	 // $this->add_userinfo($aid);
	  $this->assign("share",$this->share_data);
	  $this->assign($params);
	  $this->assign($item);
	  
	  $this->assign("user_info",$user_info);
	  $this->assign("user",$user);
	  $this->display();
	}

	//注册用户
	public function register(){
		$user_info=session('userInfo');
		$data['from_user']=$user_info['openid'];
		$data['nickname']=$user_info['nickname'];
		$data['avatar']=$user_info['headimgurl'];
		$time=time();
		$data['createtime']=$time;
		$data['update_time']=$time;
		
		
		$aid=I('get.id',0,'intval');
		$data['username']=I('post.name');
		$data['mobile']=I('post.tel');
		$data['aid']=$aid;
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
		
		if($result){
			$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->save($data);
		}else{
			$test=$this->activity_user->add($data);
		}
		$res1['status']=1;
		$res1['uid']=$result['id'];
		$res1['avatar'] = $result['avatar'];
		$res1['name'] =$data['username'];
		$res1['url'] = 'http://'.$_SERVER['HTTP_HOST'].U('apps/weimeet/detail',array('id'=>$aid,'shareid'=>$data['pid']));
		$res1['msg']="注册成功！";
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
			//$this->error("该活动不存在！");
			$res1['status']=-1;
			$res1['msg']="该活动不存在！";
			exit(json_encode($res1,true));
		}
		$currtime = time();
		if($activity['begintime']>$currtime){
			$res1['status']=-1;
			$res1['msg']="活动未开始！";
			exit(json_encode($res1,true));
			//$this->error("活动尚未开始！");
		}
		if($activity['endtime']<$currtime){
			//$this->error("活动已结束！");
			$res1['status']=-1;
			$res1['msg']="活动已结束！";
			exit(json_encode($res1,true));
		}
	}	
}

