<?php
namespace Apps\Controller;
use Common\Controller\ActivitybaseController; 
class EnvelopeController extends ActivitybaseController {
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
    public function index()
	{
		set_wechat_info();
		//获取活动id。
	  $aid = I("get.id",0,"intval");
	  //看是否是分享过来的页面。
	  $uid=I('get.uid',0,'intval');
	  //定义分享链接。
		$url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/envelope/index', ['id' => $aid, 'uid' => $uid]);
	  //检查浏览器及获取公众号信息。
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('envelope', 'index', $aid, $uid);
		} else {
			$this->entry($url);
		}
	  //获取报名人数
	  $join_count = $this->activity_user->where("aid=$aid and username <>''")->count();
	  $this->assign('join_count',$join_count);
	  
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
//		$user_info = [
//			'openid' => 'ogkJt0-w-2oEV03VhTbLhzzh0Y1k',
//			'nickname' => '空壳',
//			'avatar' => 'http://test-10057315.file.myqcloud.com/data/upload/20170821/599a3bd31d384.jpg'
//		];
	  $this->assign("user_info",$user_info);
	  //获取自己的信息。
	  $user=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
	  
	  if($user['update_time']<strtotime('today')){
			$data['day_num']=0;
			$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->save($data);
			$user=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
	  }
	  
	  $this->assign("user",$user);
	  $my_status=1;
	  if($user['total_num']>=$item['per_num']){
	  	$my_status=-1;
	  }else{
	  	if($user['day_num']>=$item['day_num']){
			$my_status=-2;
		}
	  }
	  $this->assign('my_status',$my_status);
	  
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
	  $this->assign("status",$status);
	  $this->assign("is_regist",$is_regist);
	  $this->assign("isme",$isme);
	  //如果有分享人信息就改变分享链接。
	  if($shareuid!=0)
	  {
		$share_url='http://'.$_SERVER['HTTP_HOST'].U('apps/envelope/index',array('id'=>$aid,'uid'=>$shareuid));
	  }
	  //否则，分享链接是自己的。
	  else
	  {
		  $share_url='http://'.$_SERVER['HTTP_HOST'].U('apps/envelope/index',array('id'=>$aid));
	  }
	  $url = $share_url;
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
      $this->display();
    }
	public function qiangbao(){
		$user_info=session('userInfo');
		$aid=I('get.id',0,'intval');
		
		$is_open=session('is_open');
		$share_url=U('apps/envelope/index',array('id'=>$aid));
	  	$url = $this->auth_url.$share_url;
		if($is_open!=1){
			//$url='http://'.$_SERVER['HTTP_HOST'].U('apps/envelope/index',array('id'=>$aid));
			
			header('Location: '.$url);
		}
		$this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' => $url,
			'imgUrl' =>sp_get_asset_upload_path($item['share_img'],true)
		);
		$item = sp_get_activity($aid);
		$params = sp_get_activity_params($aid);	
		$params = array_column($params, 'value', 'field');
		$this->assign('item',$item);
		$this->assign('params',$params);
		
		$user_info=session('userInfo');
		//获取自己的信息。
		$user=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
		$this->assign("user",$user);
		
		session('is_open',NULL);
		$this->assign("share",$this->share_data);
		$this->display();
	}
	public function update_fenshu(){
		$aid=I('get.id',0,'intval');
		$fenshu=I('post.fenshu',0,'intval');
		//$this->error('fenshu'.$fenshu."aid".$aid);
		$user_info=session('userInfo');
		$user_data=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
		
		$sql=$this->activity_user->getLastSql();
		//$this->error('fs'.$fenshu.">".$user_data['per_num'].">".$user_data['id'].">".$user_info['openid'].">".$sql);
		if($user_data['per_num']<$fenshu){
			$data['per_num']=$fenshu;//个人最高分
		}
		$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->setInc('total_num');
		$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->setInc('day_num');
		$data['update_time']=time();
		$result=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->save($data);
		if($result){
			$this->success('更新成功');
		}else{
			$this->error('更新失败');
		}
	}
	/*注册*/
	public function regist()
	{
		$user_info=session('userInfo');
		$aid=I('get.id',0,'intval');
		$data=array(
			'from_user'=>$user_info['openid'],
			'nickname'=>$user_info['nickname'],
			'avatar'=>$user_info['headimgurl'],
			'createtime'=>time(),
			'update_time'=>time(),
			'aid'=>$aid,
			'username'=>I("post.username"),
			'mobile'=>I("post.mobile"),
			'age'=>I("post.age"),
			'class'=>I("post.cless"),
			'school'=>I("post.school"),
			'remark'=>I("post.remark"),
		);
		$item = sp_get_activity($aid);
		$params = sp_get_activity_params($aid);	
		$params = array_column($params, 'value', 'field');
		$result=$this->activity_user->add($data);
		if($result){
			$this->success("注册成功",U('apps/envelope/qiangbao',array('id'=>$aid)));
		}else{
			$this->error("注册失败");
		}
	}
	public function moreuser()
	{
		$aid=I('get.id',0,'intval');
		$page_u=I('post.page_u',0,'intval');
		$size=15;
		//$size=1;
		if($page_u==1)
		{
			$count=$this->activity_user->where('aid='.$aid.' and mobile is not null')->count();
		}
		$zongliang=$count;
		$pagecount=ceil($zongliang/$size);
		$pagecount=$pagecount==0?1:$pagecount;
		$list=$this->activity_user->distinct(true)->field(array('aid','from_user','nickname','username','mobile','createtime','day_num','total_num','per_num','avatar'))->where('aid='.$aid.' and mobile is not null')->order('per_num desc,update_time asc')->limit(($page_u-1)*$size.",".$size)->select();
		//distinct(true)
		foreach($list as &$vo){
			$vo['update_time']=date("m-d",$vo['update_time']);
		}
		if($list)
		{
			$res['status']=1;	
			$res['pagecount']=$pagecount;
			$res['list']=$list;
		}
		$this->ajaxReturn($res);
	}
	/***公共方法抽象定义区****/
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
	
	/*保存个性字段*/
	function save_envelope($aid){
		$data = array(
			array("aid"=>$aid,"name"=>"助力积分方式","field"=>"steptype","value"=>I('post.steptype')),
			array("aid"=>$aid,"name"=>"固定助力积分","field"=>"step","value"=>I('post.step'),0,"intval"),
			array("aid"=>$aid,"name"=>"助力随机积分范围","field"=>"steprandom","value"=>I('post.steprandom')),
			array("aid"=>$aid,"name"=>"报名需填信息","field"=>"other_remark","value"=>I('post.other_remark')),
			array("aid"=>$aid,"name"=>"校区","field"=>"other_school","value"=>I('post.other_school')),
			array("aid"=>$aid,"name"=>"年龄","field"=>"age","value"=>I('post.age')),
			array("aid"=>$aid,"name"=>"班级","field"=>"cless","value"=>I('post.cless')),
			array("aid"=>$aid,"name"=>"学校","field"=>"school","value"=>I('post.school')),
			array("aid"=>$aid,"name"=>"其他","field"=>"qita","value"=>I('post.qita')),
		);
		sp_save_activity_params($data);
	}
	public function showu(){
		$aid=2562;
		$result=$this->activity_user->where(array('aid'=>$aid))->select();
		print_r($result);
	}
	public function addu(){
		$data=array('aid'=>2562,'from_user'=>'oqd6JxIy7Pyg_AD3AezfbveF6IaU','nickname'=>'DNA','username'=>'丁扬','createtime'=>'1484273307','per_num'=>21,'total_num'=>2,'update_time'=>'1484208455','avatar'=>'http://wx.qlogo.cn/mmopen/Y3WgNLFjO0fgMIHKzEibVZjGT83vtgIdv0dL9UN79nmpwcGk7YnnCooXnmicbsuqSKHwvGmP8VxMr47O5MWRxQB5r5cqlse4vO/0','mobile'=>'12312312312');
		$result=$this->activity_user->add($data);
		print_r($result);
	}
	public function saveu(){
		$id=986250;
		$data['update_time']='1484208455';
		$result=$this->activity_user->where(array('id'=>$id))->save($data);
		print_r($result);
	}
	public function deleteu(){
		$id=1003948;
		$result=$this->activity_user->where(array('id'=>$id))->delete();
		print_r($result);
	}
	
}

