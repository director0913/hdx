<?php

/**
 * 邀请有礼
 */
namespace Apps\Controller;
use Common\Controller\ActivitybaseController; 
use Common\Controller\HomebaseController;

class InvitegiftController extends ActivitybaseController {
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
		$this->jiangpai = D("Ad");
	}
    public function index() {
		set_wechat_info();
	  $aid = I("get.id",0,"intval");
	  $shareid = I("get.shareid",0,"intval");
	  $url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/invitegift/index',array('id'=>$aid,'shareid'=>$shareid));
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('invitegift', 'index', $aid, 0, $shareid);
		} else {
			$this->entry($url);
		}

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
	  $this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' => C('DOMAIN_NAME').U('apps/invitegift/code',array('id'=>$aid,'shareid'=>$shareid)),
			'imgUrl' =>$item['share_img']
	  );

	  $qrcode_url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/invitegift/geterweima',array('id'=>$aid,'shareid'=>$shareid));
	  $this->assign("qrcode_url",$qrcode_url);
	  $this->assign("userid",$userid);
	  $this->assign("shareid",$shareid);
	  $this->assign("sharename",$sharename);
	  $this->assign("shareavatar",$shareavatar);
	  $this->assign("jilu",$jilu);
	  $this->assign("is_me",$is_me);
	  $this->assign("is_register",$is_register);
	  $this->add_userinfo($aid);
	  $this->assign("share",$this->share_data);
	  $this->assign("params",$params);
	  $this->assign($item);
	  
	  $this->assign("user_info",$user_info);
	  $this->assign("user",$user);
	  $this->display();
    }
	
	/*测试首页预览
	*/
	public function yuindex() {
      $this->display("/Thimes/invitegift/yuindex");
    }
	/*测试分享预览
	*/
	public function yufenxiang() {
      $this->display("/Thimes/invitegift/yufenxiang");
    }
	//注册用户
	public function register1(){
		
		$aid=I('get.id',0,'intval');
		$data['username']=I('post.name');
		$data['mobile']=I('post.tel');
		$data['pid'] = I('get.pid',0,"intval");
		//活动分析
		$this->analyze_activitys($aid);
		$user_info=session('userInfo');
		$result=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
		if($data['pid']){
			if($data['pid']!=$result['id']){
				$pp = $this->activity_user->where(array('aid'=>$aid,'id'=>$data['pid']))->find();
				$data['pname'] = $pp['username'];
			}
		}
		$res = $this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->save($data);
		if($res){
			$res1['status']=1;
			$res1['uid']=$result['id'];
			$res1['avatar'] = $result['avatar'];
			$res1['name'] =$data['username'];
			$res1['url'] = 'http://'.$_SERVER['HTTP_HOST'].U('apps/invitegift/geterweima',array('id'=>$aid,'shareid'=>$result['id']));
			$res1['msg']="注册成功！";
		}else{
			$res1['status']=-1;
			$res1['msg']="注册失败失败！";
		}
		exit(json_encode($res1,true));
	}
	//二维码识别后处理函数
	public function code(){
		set_wechat_info();
	  $aid = I("get.id",0,"intval");
	  $shareid = I("get.shareid",0,"intval");
	  $url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/invitegift/code',array('id'=>$aid,'shareid'=>$shareid));
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('invitegift', 'code', $aid, 0,$shareid);
		} else {
			$this->entry($url);
		}

	  $canyu=1;
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
	  $this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' => C('DOMAIN_NAME').U('apps/invitegift/index',array('id'=>$aid,'shareid'=>$shareid)),
			'imgUrl' =>$item['share_img']
	  );
	  $user_info=session('userInfo');
	  $shareuser = $this->activity_user->where("aid=$aid and id=$shareid")->find();
	  $user =  $this->activity_user->where("aid=$aid and from_user='".$user_info['openid']."' and username<>''")->find();
	  $userid = intval($user['id']);
	  $is_me = 0;$is_register = 0;
	  if((!empty($user)&&$userid==$shareid)||$shareid==0||empty($shareuser)){
		$is_me =1;
		$shareavatar=$user['avatar'];
		$shareid=$user['id'];
		$sharename=$user['username'];
		//获取自己的邀请记录
		
	  }else{
		$shareid=$shareuser['id'];
		$shareavatar=$shareuser['avatar'];
		$sharename=$shareuser['username'];
	  }
	  if(!empty($user)){$is_register=1;}

	  $this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' => C('DOMAIN_NAME').U('apps/invitegift/index',array('id'=>$aid,'shareid'=>$shareid)),
			'imgUrl' =>$item['share_img']
	  );
	  $canyu=0;
	  if($user['pid']>0){
		$canyu=1;
	  }
	  $this->assign("canyu",$canyu);
	  //$jilu=$this->activity_user->where("aid=$aid and pid=$shareid")->select();
	  $jilu=$this->activity_user->where("aid=$aid")->select();
	  $this->add_userinfo($aid);
	  $this->assign("userid",$userid);
	  $this->assign("shareid",$shareid);
	  $this->assign("sharename",$sharename);
	  $this->assign("shareavatar",$shareavatar);
	  $this->assign("jilu",$jilu);
	  $this->assign("is_me",$is_me);
	  $this->assign("is_register",$is_register);

	  $this->assign("share",$this->share_data);
	  $this->assign($params);
	  $this->assign($item);
	  $user_info=session('userInfo');
	  $this->assign("user_info",$user_info);
	  $this->assign("user",$user);
	  $this->display();
	}
	//生成二维码
	public function geterweima(){
		vendor("phpqrcode.phpqrcode");
		$aid = I("get.id",0,"intval");
		$shareid = I("get.shareid",0,"intval");
		$avatar=false;
		//拼接链接
		$value='http://'.$_SERVER['HTTP_HOST'].U('apps/invitegift/code',array('id'=>$aid,'shareid'=>$shareid));
		$errorCorrectionLevel = 'L';//容错级别   
		$matrixPointSize = 6;//生成图片大小   
		$getimg="erweima.png";
		$QRcode = new \QRcode();
		//生成二维码图片  
		
		$QRcode::png($value,false);
		$res['status']=1;
		$res['getimg']=$getimg;
		exit(json_encode($res));
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
	
}

