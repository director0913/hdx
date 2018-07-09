<?php

/**
 *献花营销工具
 */
namespace Apps\Controller;
use Common\Controller\ActivitybaseController; 
use Common\Controller\HomebaseController;

class FlowerController extends ActivitybaseController {
	protected $form_model;
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
		$this->form_model = D("Common/Form");
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
	  $keyword = I("get.keyword");
	  $url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/flower/index',array('id'=>$aid,'shareid'=>$shareid));
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('flower', 'index', $aid, 0,$shareid);
		} else {
			$this->entry($url);
		}
	  $this->add_userinfo($aid);
	  /*****公共信息处理******/
	  //获取参与人次数
	  $vote_count = $this->activity_user->where(array("aid"=>$aid))->sum("per_num");
	  //获取报名人数
	  $join_count = $this->activity_user->where("aid=$aid and username<>''")->count();
	  /*******公共信息处理结束*****************/
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
		if(empty($sharename)){$sharename = $user['nickname'];}
		 $shareuser = $user;
	  }else{
		$shareid=intval($shareuser['id']);
		$shareavatar=$shareuser['avatar'];
		$sharename=$shareuser['username'];
		if(empty($sharename)){$sharename = $shareuser['nickname'];}
	  }
	  if(!empty($user)){$is_register=1;}
	  $share_url='http://'.$_SERVER['HTTP_HOST'].U('apps/flower/index',array('id'=>$aid,'shareid'=>$shareid));
	  $url = $share_url;
	  $this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' => $url,
			'imgUrl' => $item['share_img']
	  );
	  $uwhere = "aid=$aid and mobile<>''";
	  if(!empty($keyword)){
		 $uwhere.=" and (id='$keyword' or username like '%$keyword%')";
	  }
	  //分页
	  $pagesize = 15;
	  $utotal = $this->activity_user->where($uwhere)->count();
	  //echo  $this->activity_user->getLastSql();
	  //die();
	  $page = new \Page($utotal,$pagesize);
		
	  $ulist = $this->activity_user->where($uwhere)->order("per_num desc")->limit($page->firstRow . ',' . $page->listRows)->select();
	  $sql = $this->activity_user->getLastSql();
	 
	  $this->assign("vote_count",$vote_count);
	  $this->assign("join_count",$join_count);
	  $this->assign("userid",$userid);
	  $this->assign("shareid",$shareid);
	  $this->assign("ulist",$ulist);
	  $this->assign("sharename",$sharename);
	  $this->assign("shareavatar",$shareavatar);
	  $this->assign("shareuser",$shareuser);
	  $this->assign("is_me",$is_me);
	  $this->assign("is_register",$is_register);
	  $this->assign("share",$this->share_data);
	  $this->assign($params);
	  $this->assign($item);
	  $this->assign("page",$page->show('default'));
	  $this->assign("user_info",$user_info);
	  $this->assign("user",$user);
	  $this->assign("keyword",$keyword);

	  $this->display();
    }
	//报名
	public function vote_join(){
	  $aid = I("get.id",0,"intval");
	  $shareid = I("get.shareid",0,"intval");
	  $url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/flower/vote_join',array('id'=>$aid,'shareid'=>$shareid));;
		$this->entry($url);
	  $this->add_userinfo($aid);
	  $cando=1;
	  /*****公共信息处理******/
	  //获取参与人次数
	   $vote_count = $this->activity_user->where(array("aid"=>$aid))->sum("per_num");
	  //获取报名人数
	  $join_count = $this->activity_user->where("aid=$aid and username<>''")->count();
	  /*******公共信息处理结束*****************/

	  $item = sp_get_activity($aid);
	  if(!$item)
	  {
		  $this->error('活动不存在');
	  }

	  $url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/flower/index',array('id'=>$aid,'shareid'=>$shareid));
	  $this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' => $url,
			'imgUrl' =>$item['share_img']
	  );
	  $this->assign("cando",$cando);
	  $params = sp_get_activity_params($aid);	
	  $params = array_column($params, 'value', 'field');

	  $this->assign("vote_count",$vote_count);
	  $this->assign("join_count",$join_count);

	  $this->assign("share",$this->share_data);
	  $this->assign("params",$params);
	  $this->assign($params);
	  $this->assign($item);
	  $this->display();
	
	}
	//活动排名
	public function rank(){
	  $aid = I("get.id",0,"intval");
	  $shareid = I("get.shareid",0,"intval");
	  //$url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/flower/index',array('id'=>$aid,'shareid'=>$shareid));
	  $share_url=U('apps/flower/index',array('id'=>$aid,'shareid'=>$shareid));
	  $url = $this->auth_url.$share_url;
		$this->entry($url);
	  $this->add_userinfo($aid);
	  $cando=1;
	  /*****公共信息处理******/
	  //获取参与人次数
	  $vote_count = $this->activity_user->where(array("aid"=>$aid))->sum("per_num");
	  //获取报名人数
	  $join_count = $this->activity_user->where("aid=$aid and username<>''")->count();
	  /*******公共信息处理结束*****************/

	  $item = sp_get_activity($aid);
	  if(!$item)
	  {
		  $this->error('活动不存在');
	  }

	  $url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/flower/index',array('id'=>$aid,'shareid'=>$shareid));
	  $this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' => $url,
			'imgUrl' =>$item['share_img']
	  );
	  //分页
	  $pagesize = 15;
	  $utotal = $this->activity_user->where("aid=$aid and mobile<>''")->count();
	  $page = new \Page($utotal,$pagesize);
		
	  $ulist = $this->activity_user->where("aid=$aid and mobile<>''")->order("per_num desc")->limit($page->firstRow . ',' . $page->listRows)->select();

	  $this->assign("ulist",$ulist);
	  $params = sp_get_activity_params($aid);	
	  $params = array_column($params, 'value', 'field');
	  $this->assign("share",$this->share_data);

	  $this->assign("vote_count",$vote_count);
	  $this->assign("join_count",$join_count);
      $this->assign("page",$page->show('default'));
	  $this->assign($params);
	  $this->assign($item);
	  $this->display();
	}
	//活动规则
	public function rule(){
	  $aid = I("get.id",0,"intval");
	  $shareid = I("get.shareid",0,"intval");
	  //$url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/flower/index',array('id'=>$aid,'shareid'=>$shareid));
	  $share_url=U('apps/flower/index',array('id'=>$aid,'shareid'=>$shareid));
	  $url = $this->auth_url.$share_url;
	  	$this->entry($url);
	  $this->add_userinfo($aid);
	  $cando=1;
	  /*****公共信息处理******/
	  //获取参与人次数
	  $vote_count = $this->activity_user->where(array("aid"=>$aid))->sum("per_num");
	  //获取报名人数
	  $join_count = $this->activity_user->where("aid=$aid and username<>''")->count();
	  /*******公共信息处理结束*****************/

	  $item = sp_get_activity($aid);
	  if(!$item)
	  {
		  $this->error('活动不存在');
	  }
	  $url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/flower/index',array('id'=>$aid,'shareid'=>$shareid));
	  $this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' => $url,
			'imgUrl' =>$item['share_img']
	  );
	  $this->assign("cando",$cando);
	  $params = sp_get_activity_params($aid);	
	  $params = array_column($params, 'value', 'field');
	  $this->assign("vote_count",$vote_count);
	  $this->assign("join_count",$join_count);
	  $this->assign("share",$this->share_data);
	  $this->assign($params);
	  $this->assign($item);
	  $this->display();
	}
	//注册用户
	public function register(){
		
		$aid=I('get.id',0,'intval');
		$data=array(
			'username'=>I("post.name"),
			'mobile'=>I("post.tel"),
			'remark'=>I("post.remark"),
			'age'=>I("post.age"),
			'class'=>I("post.cless"),
			'school'=>I("post.school"),
			'thumb'=>I('post.thumb'),
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
			$res1['url'] = U('apps/flower/index',array('id'=>$aid,'shareid'=>$result['id']));
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
	//处理送花
	public function add_vote(){
		$aid = I("get.id",0,"intval");
		$shareid = I("post.shareid",0,"intval");
		$is_vote_history = $this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->count();
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
		}
		if($activity['endtime']<$currtime){
			$this->error("活动已结束！");
		}
	}
	//好友状态分析
	function analyze_activitys_friend($aid,$uid){
		
		$userInfo = session('userInfo');
		if(empty($userInfo)){
			$this->error("获取您的信息失败，请重新打开页面进行投票！");
		}
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
		$ftoday =  date("Y-m-d",$friend['update_time']);
		if($ftoday<$today){
			//清空当天的点赞数
			$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->save(array('day_num'=>0,'update_time'=>time()));
		}
		//$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->save(array('update_time'=>time()));
		//判断当天的次数和总次数是否已用完
		if($friend['total_num']>=$activity['total_num']){
			$this->error("您的次数已用完！");
		}
		if($friend['day_num']>=$activity['day_num']){
			$this->error("您今天的次数已用完！");
		}
		//分析此人是否参了活动
		if(!empty($user['mobile'])){
			$f_total = $this->activity_friend->where(array("aid"=>$aid,"from_user"=>$userInfo['openid']))->sum('total_num');
			if($f_total>=$activity['join_total']){
				$this->error("您的次数已用完！");
			}
		}
		$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->setInc('total_num');
		$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->setInc('day_num');
		$this->activity_user->where(array("aid"=>$aid,"id"=>$uid))->setInc('per_num'); //此人得累计点赞数量
		$this->success("送花成功！");
	}
	//投票个性化字段保存
	function save_flower($aid){
		$data = array(
			array("aid"=>$aid,"name"=>"显示前多少人","field"=>"show_total","value"=>I('post.show_total')),
			array("aid"=>$aid,"name"=>"每页显示条数","field"=>"page_total","value"=>I('post.page_total')),
			array("aid"=>$aid,"name"=>"学校","field"=>"school_name","value"=>I('post.school_name')),
			array("aid"=>$aid,"name"=>"报名需填信息","field"=>"other_remark","value"=>I('post.other_remark')),
			array("aid"=>$aid,"name"=>"校区","field"=>"other_school","value"=>I('post.other_school')),
			array("aid"=>$aid,"name"=>"年龄","field"=>"age","value"=>I('post.age')),
			array("aid"=>$aid,"name"=>"班级","field"=>"cless","value"=>I('post.cless')),
			array("aid"=>$aid,"name"=>"学校","field"=>"school","value"=>I('post.school')),
			array("aid"=>$aid,"name"=>"其他","field"=>"qita","value"=>I('post.qita')),
			array("aid"=>$aid,"name"=>"是否开启前台报名","field"=>"is_front_join","value"=>I('post.is_front_join')),
			array("aid"=>$aid,"name"=>"是否开启报名审核","field"=>"is_check","value"=>I('post.is_check')),
			array("aid"=>$aid,"name"=>"宣传语","field"=>"slogan","value"=>I('post.slogan')),
			//array("aid"=>$aid,"name"=>"报名者献花次数","field"=>"join_total","value"=>I('post.join_total'))
		);
		sp_save_activity_params($data);
	}
	//处理添加用户
	public function app_add_user(){
		$uid=get_current_userid();
		$where = 'uid='.$uid.' and valid = 1';
		//查询活动数量。
		$activity_num=$this->form_model->where($where)->count();
		$this->assign('activity_num',$activity_num);
		//查询应用数量。
		$app_num=$this->activity->where($where)->count();
		$this->assign('app_num',$app_num);
		$users = D('users'); 
		$users_info=$users->where('id='.$uid)->find();
		$this->assign('users_info',$users_info);

		$aid = I("get.aid",0,"intval");
		$id = I("get.id",0,"intval");
		$activity = $this->activity->where(array("id"=>$aid))->find();
		$user = $this->activity_user->where(array("id"=>$id))->find();
		$this->assign("aid",$aid);
		if(IS_POST){
			$id = I("post.id",0,"intval");
			if(empty($id)){
				$_POST['nickname'] = I("post.username");
				$_POST['avatar'] = I("post.thumb");
			}
			$_POST['createtime'] =time();
			$res = $this->activity_user->create();
			if($res){
				if($id){
					$result = $this->activity_user->save();
				}else{	
					$result = $this->activity_user->add();
				}
				if($result){
					$this->success("保存成功！",U('portal/app/app_show_join',array('id'=>$aid,'menu'=>1)));
				}else{
					$this->error($this->activity_user->getError());
				}
			}
		}
		$this->assign($user);
		$this->assign("activity",$activity);
		$this->display();
	}
}

