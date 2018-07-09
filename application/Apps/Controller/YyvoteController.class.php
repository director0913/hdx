<?php

/**
 * 投票类营销工具
 */
namespace Apps\Controller;
use Common\Controller\ActivitybaseController; 
use Common\Controller\HomebaseController;

class YyvoteController extends ActivitybaseController {
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
	  $url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/yyvote/index',array('id'=>$aid,'shareid'=>$shareid));
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('yyvote', 'index', $aid, 0,$shareid);
		} else {
			$this->entry($url);
		}
//		$this->entry($url);
	  //$this->add_userinfo($aid);
	  /*****公共信息处理******/
	  //获取参与人次数
	  $vote_count = $this->activity_user->where(array("aid"=>$aid))->sum("per_num");
	   
	  //获取报名人数
	  $join_count = $this->activity_user->where(" aid=$aid and username<>''")->count();
	  //echo "参与人数:".$vote_count."报名人数:".$join_count;
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
	  $this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' => C('DOMAIN_NAME').U('apps/yyvote/index',array('id'=>$aid,'shareid'=>$shareid)),
			'imgUrl' =>$item['share_img']
	  );
	  $uwhere = "aid=$aid and mobile<>''";
	  if($keyword){
		 $uwhere.=" and (username like '%$keyword%')";
	  }
	  //分页
	  $pagesize = 300;
	  $utotal = $this->activity_user->where($uwhere)->count();
	  $page = new \Page($utotal,$pagesize);

	  $ulist = $this->activity_user->where($uwhere)->order(array('id'=>'asc'))->limit($page->firstRow . ',' . $page->listRows)->select();

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
	public function my_sort($arrays,$sort_key,$sort_order=SORT_ASC,$sort_type=SORT_NUMERIC ){ 
		if(is_array($arrays)){ 
			foreach ($arrays as $array){ 
				if(is_array($array)){ 
					$key_arrays[] = $array[$sort_key]; 
				}else{ 
					return false; 
				} 
			} 
		}else{ 
			return false; 
		}
		array_multisort($key_arrays,$sort_order,$sort_type,$arrays); 
		return $arrays; 
	}
	//报名
	public function vote_join(){
	  $aid = I("get.id",0,"intval");
	  $shareid = I("get.shareid",0,"intval");
	  $url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/yyvote/index',array('id'=>$aid,'shareid'=>$shareid));
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('yyvote', 'index', $aid, 0,$shareid);
		} else {
			$this->entry($url);
		}
//		$this->entry($url);

	  /*****公共信息处理******/
	  //获取参与人次数
	 //获取参与人次数
	   $vote_count = $this->activity_user->where(array("aid"=>$aid))->sum("per_num");
	  //获取报名人数
	  $join_count = $this->activity_user->where(" aid=$aid and username<>''")->count();
	  /*******公共信息处理结束*****************/

	  $item = sp_get_activity($aid);
	  if(!$item)
	  {
		  $this->error('活动不存在');
	  }
	  $cando=1;
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

	  $this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' => C('DOMAIN_NAME').U('apps/yyvote/index',array('id'=>$aid,'shareid'=>$shareid)),
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
	public function test_data(){
		 $ulist = $this->activity_user->where("aid=2524")->order(array('id'=>'asc'))->select();
		 foreach($ulist as $key=>$v){
			 echo "更新中";
			 $this->activity_user->where(array('id'=>$v['id']))->save(array('listorder'=>$key+1));
			 echo $this->activity_user->getLastSql();
		 }
		 die();
	}
	//活动排名
	public function rank(){

	  $aid = I("get.id",0,"intval");
	  $shareid = I("get.shareid",0,"intval");
	  $url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/yyvote/index',array('id'=>$aid,'shareid'=>$shareid));
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('yyvote', 'index', $aid, 0,$shareid);
		} else {
			$this->entry($url);
		}
//		$this->entry($url);
	  
	  /*****公共信息处理******/
	  //获取参与人次数
	  $vote_count = $this->activity_user->where(array("aid"=>$aid))->sum("per_num");
	   
	  //获取报名人数
	  $join_count = $this->activity_user->where(" aid=$aid and username<>''")->count();
	  /*******公共信息处理结束*****************/

	  $item = sp_get_activity($aid);
	  if(!$item)
	  {
		  $this->error('活动不存在');
	  }
	  $cando=1;
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

	  $this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' => C('DOMAIN_NAME').U('apps/yyvote/index',array('id'=>$aid,'shareid'=>$shareid)),
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
	  $url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/yyvote/index',array('id'=>$aid,'shareid'=>$shareid));
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('yyvote', 'index', $aid, 0,$shareid);
		} else {
			$this->entry($url);
		}
//		$this->entry($url);
	  //$this->add_userinfo($aid);
	  
	  /*****公共信息处理******/
	  //获取参与人次数
	  $vote_count = $this->activity_user->where(array("aid"=>$aid))->sum("per_num");
	  //获取报名人数
	  $join_count = $this->activity_user->where(" aid=$aid and username<>''")->count();
	  /*******公共信息处理结束*****************/

	  $item = sp_get_activity($aid);
	  if(!$item)
	  {
		  $this->error('活动不存在');
	  }
	  $cando=1;
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

	  $this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' => C('DOMAIN_NAME').U('apps/yyvote/index',array('id'=>$aid,'shareid'=>$shareid)),
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
		$data['username']=I('post.name');
		$data['mobile']=I('post.tel');
		$data['remark']=I('post.remark');
		$data['thumb']=I('post.thumb');
		//活动分析
		$this->analyze_activitys($aid);
		$user_info=session('userInfo');
		$result=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
		if(!empty($result['mobile'])){
			$res1['status']=-1;
			$res1['info']="您已报过名，不能重复报名！";
			$this->ajaxReturn($res1);
		}
		$data['listorder'] = $this->activity_user->where(array('aid'=>$aid))->max("listorder");
		$data['listorder'] = $data['listorder']+1;
		$this->add_userinfo($aid);
		$res = $this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->save($data);
		if($res){
			$res1['status']=1;
			$res1['uid']=$result['id'];
			$res1['avatar'] = $result['avatar'];
			$res1['name'] =$data['username'];
			$res1['url'] = 'http://'.$_SERVER['HTTP_HOST'].U('apps/yyvote/index',array('id'=>$aid,'shareid'=>$result['id']));
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
	//处理点赞
	public function add_vote(){
		$aid = I("get.id",0,"intval");
		$shareid = I("post.shareid",0,"intval");
		$date = date("Y-m-d");

		$activity_info = M('activity')->field('day_num')->where("id = ".$aid)->find();
        // 对设备进行限制
		if (!empty(cookie('device'))) {
		    $device_count = M('activity_limit')->where("aid = ".$aid." AND device = '".cookie('device')."' AND timestamp = '".$date."'")->count();
		    if ($device_count >= $activity_info['day_num']) {
                $this->error("该设备投票次数达到每日最大限制！");
            }
        }

		$this->analyze_activitys($aid);
		$this->analyze_activitys_friend($aid,$shareid);


	}
	// 写入用户投票记录
    public function add_limit($aid,$openid,$name){
	    if (empty(cookie('device'))) {
            cookie('device',rand(11111,99999999),3600000);
        }
        M('activity_limit')->data([
            'aid' => $aid,
            'ip' => $_SERVER['REMOTE_ADDR'],
            'device' => cookie('device'),
            'name' => $name,
            'openid' => $openid,
            'timestamp' => date("Y-m-d")
        ])->add();
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
		if(empty($userInfo['openid'])){
			$this->error("您的信息获取失败，请重新打开！");die();
		}
		if($uid==271180){
			//$this->error("该用户投票出现异常，请稍后再试！");die();
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
			$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->save(array('day_num'=>0,'update_time'=>time()));
		}
		//分析此人是否参了活动
		if(!empty($user['mobile'])){
			$f_total = $this->activity_friend->where(array("aid"=>$aid,"from_user"=>$userInfo['openid']))->sum('total_num');
			//$this->error($aid."您的参与次数已用完！".$f_total);
			if($activity['join_total']>0&&$f_total>=$activity['join_total']){
				$this->error("您的参与次数已用完！");
			}
		}
		//判断当天的次数和总次数是否已用完
		if($activity['total_num']>0&&$friend['total_num']>=$activity['total_num']){
			$this->error("您的次数已用完！");
		}
		if($friend['day_num']>=$activity['day_num']){
			$this->error("您今天的次数已用完！");
		}
		
		$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->setInc('total_num');
		$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->setInc('day_num');
		$this->activity_user->where(array("aid"=>$aid,"id"=>$uid))->setInc('per_num'); //此人得累计点赞数量
        $this->add_limit($aid,$userInfo['openid'],$userInfo['nickname']);
		$this->success("投票成功！");
	}
	//投票个性化字段保存
	function save_yyvote($aid){
		$data = array(
			array("aid"=>$aid,"name"=>"报名需填信息","field"=>"other_remark","value"=>I('post.other_remark')),
			array("aid"=>$aid,"name"=>"显示前多少人","field"=>"show_total","value"=>I('post.show_total')),
			array("aid"=>$aid,"name"=>"每页显示条数","field"=>"page_total","value"=>I('post.page_total')),
			array("aid"=>$aid,"name"=>"是否开启前台报名","field"=>"is_front_join","value"=>I('post.is_front_join')),
			array("aid"=>$aid,"name"=>"是否开启报名审核","field"=>"is_check","value"=>I('post.is_check')),
			//array("aid"=>$aid,"name"=>"报名者投票次数","field"=>"join_total","value"=>I('post.join_total'))
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
			$_POST['listorder'] =I("post.listorder");
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
		$this->assign("activity",$activity);
		$this->assign($user);
		$this->display();
	}
	
}

