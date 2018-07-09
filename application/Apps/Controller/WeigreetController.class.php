<?php

/**
 * 新学期贺卡营销工具
 */
namespace Apps\Controller;
use Common\Controller\ActivitybaseController; 
use Common\Controller\HomebaseController;

class WeigreetController extends ActivitybaseController {
	protected $activity;
	protected $activity_params;
	protected $activity_user;
	protected $activity_friend;

	protected $activity_user_data;
	protected $activity_user_prize;
	protected $jiangpai;
	protected $share_data;//定义分享数据
	function _initialize(){
		parent::_initialize();
		$this->activity = D("Activity");
		$this->activity_params = D("ActivityParams");
		$this->activity_user = D("ActivityUser");
		$this->activity_friend = D("ActivityFriend");
		$this->activity_user_data = D("ActivityUserData");
	}
	public function index() {
		set_wechat_info();
	  	$aid = I("get.id",0,"intval");
	  	$shareid = I("get.shareid",0,"intval");
	  	$url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/weigreet/index', ['id' => $aid, 'shareid' => $shareid]);
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('weigreet', 'index', $aid, 0, $shareid);
		} else {
			$this->entry($url);
		}
	  	$this->add_userinfo($aid);
	  	$cando=1;
	  	$item = sp_get_activity($aid);
	  	if (!$item) {
		  $this->error('活动不存在');
	  	}
		//判断活动是否开始。
		if ($item['begintime'] > time()) {
			$cando = -1;
		}
		if ($item['endtime'] <= time()) {
		  $cando = 0;
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

	  	if ((!empty($user) && $userid == $shareid) || $shareid == 0 || empty($shareuser)) {
			$is_me = 1;
			$shareavatar = $user['avatar'];
			$shareid = intval($user['id']);
			$sharename = $user['username'];
			$shareuser = $user;
	  	}else{
			$shareid = intval($shareuser['id']);
			$shareavatar = $shareuser['avatar'];
			$sharename = $shareuser['username'];
	  	}
	  	if (empty($sharename)) {
			$sharename = $user_info['nickname'];
		}
	  	if (empty($shareavatar)) {
			$shareavatar = $user_info['headimgurl'];
		}
	  	//获取点赞记录
	  	$jilu = $this->activity_friend->where("aid = $aid and uid = $shareid")->order("createtime desc")->limit(14)->select();
	  	if (!empty($user)) {
			$is_register=1;
		}

	  	$url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/weigreet/index', ['id' => $aid, 'shareid' => $shareid]);
	  	$this->share_data = [
			'title'	=> $item['share_title'],
			'desc'  => $item['share_des'],
			'url' => $url,
			'imgUrl' => $item['share_img']
		];
	  	$uwhere = "aid = $aid and mobile <> ''";
	  	//分页
	  	$pagesize = 15;
	  	$utotal = $this->activity_user->where($uwhere)->count();
	  	$page = new \Page($utotal,$pagesize);

	  	$ulist = $this->activity_user->where($uwhere)->order("total_num desc")->limit($page->firstRow . ',' . $page->listRows)->select();

	  	$this->assign("userid",$userid);
	  	$this->assign("shareid",$shareid);
	  	$this->assign("ulist",$ulist);
	  	$this->assign("sharename",$sharename);
	  	$this->assign("shareavatar",$shareavatar);
	  	$this->assign("shareuser",$shareuser);
	  	$this->assign("page",$page->show('default'));

	  	$this->assign("jilu",$jilu);
	  	$this->assign("is_me",$is_me);
	  	$this->assign("is_register",$is_register);

	  	$this->assign("share",$this->share_data);
	  	$this->assign("params",$params);
	  	$this->assign($params);
	  	$this->assign($item);

	  	$this->assign("user_info",$user_info);
	  	$this->assign("user",$user);
	  	$this->display();
	}

	//注册用户
	public function register(){
		$aid = I("get.id",0,"intval");
		$data=array(
			'username'=>I("post.name"),
			'mobile'=>I("post.tel"),
			'remark'=>I("post.remark"),
			'age'=>I("post.age"),
			'class'=>I("post.cless"),
			'school'=>I("post.school"),
			'thumb'=>I('post.thumb'),
			'parent_msg'=>I('post.message'),
		);
		//活动分析
		$this->analyze_activitys($aid);
		$user_info=session('userInfo');
		$result=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
		if(!empty($result['mobile'])){
			$res1['status']=-1;
			$res1['info']="您已报过名，不能重复报名！";
			$this->ajaxReturn($res1);
		}
		$res = $this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->save($data);
		if($res){
			$res1['status']=1;
			$res1['uid']=$result['id'];
			$res1['avatar'] = $result['avatar'];
			$res1['name'] =$data['username'];
			$res1['url'] = U('apps/weigreet/index',array('id'=>$aid,'shareid'=>$result['id']));
			$res1['info']="注册成功！";
		}else{
			$res1['status']=-1;
			$res1['info']="注册失败失败！";
		}
		$this->ajaxReturn($res1);
	}
	//更新画板
	public function update_user(){
		$aid=I('get.id',0,'intval');
		$data['thumb']=I('post.thumb');
		$data['createtime'] = time();
		$user_info=session('userInfo');
		$res = $this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->save($data);
		if($res){
			$this->success("更新成功！");
		}else{
			$this->error("更新失败！");
		}
	}
	//更新祝福语
	public function update_message(){
		$aid=I('get.id',0,'intval');
		$data['parent_msg']=I('post.message');
		$data['createtime'] = time();
		$user_info=session('userInfo');
		$res = $this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->save($data);
		if($res){
			$this->success("更新成功！");
		}else{
			$this->error("更新失败！");
		}
	}
	//处理点赞
	public function add_vote(){
		$aid = I("get.id",0,"intval");
		$shareid = I("get.shareid",0,"intval");
		$is_vote_history = $this->activity_friend->where(array("aid"=>$aid,"uid"=>$shareid,"from_user"=>$userInfo['openid']))->count();
		$user_info=session('userInfo');
		$this->analyze_activitys($aid);
		$this->analyze_activitys_friend($aid,$shareid);
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
			$this->error("活动未开始！");
			//$this->error("活动尚未开始！");
		}
		if($activity['endtime']<$currtime){
			$this->error("活动已结束！");
		}
	}
	//好友状态分析
	function analyze_activitys_friend($aid,$uid){
		
		$userInfo = session('userInfo');
		$activity = D("Activity")->where(array("id"=>$aid,"valid"=>1))->find();
		$user = $this->activity_user->where(array("aid"=>$aid,"from_user"=>$userInfo['openid']))->find();
		//分析此好友是否存在
		$friend=$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->find();
		
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
		$ftoday = date("Y-m-d",$friend['update_time']);
		if($ftoday<$today){
			//清空当天的点赞数
			$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->save(array('day_num'=>0,'update_time'=>time()));
		}
		//判断当天的次数和总次数是否已用完
		if($friend['total_num']>=$activity['total_num']){
			$this->error("您的次数已用完！");
			exit();
		}
		/**if($friend['per_num']>=$activity['total_num']){
			$this->error("您的次数已用完！");
		}**/
		if($friend['day_num']>=$activity['day_num']){
			$this->error("您今天的次数已用完！");
			exit();
		}
		//分析此人是否参了活动
		if(!empty($user)){
			$f_total = $this->activity_friend->where(array("aid"=>$aid,"from_user"=>$userInfo['openid']))->sum('total_num');
			if($f_total>=$activity['join_total']){
				$this->error("您的次数已用完！");
			}
		}
		$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->setInc('total_num');
		$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->setInc('day_num');
		//$this->activity_user->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->setInc('per_num');
		$this->activity_user->where(array("aid"=>$aid,"id"=>$uid))->setInc('total_num'); //此人得累计点赞数量
		$res = array('status'=>1,'avatar'=>$userInfo['headimgurl'],'is_vote_history'=>0);
		$this->ajaxReturn($res);
	}
	//显示用户
	public function showu(){
		
		$result=$this->activity_user->where('aid in(2024)')->select();
		print_r($result);
	}
	public function saveu(){
		$data['update_time']=1480521600;	
		$result=$this->activity_user->where('aid in(2024)')->save($data);
		print_r($result);
	}
	//shanchu 用户
	public function deleteu(){
		$result=$this->activity_user->where('aid in (2024)')->delete();
		print_r($result);
	}
	
	
	
}


