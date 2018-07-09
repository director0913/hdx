<?php


/**
 * 手机端处理
 */
namespace Portal\Controller;
use Common\Controller\HomebaseController;
use Com\Wechat;
use Com\WechatAuth;
class MindexController extends HomebaseController  {
	protected $activity;
	protected $activity_params;	
	protected $form_model;
	protected $formdata_model;
	protected $formrow_model;
    protected $activityuser_model;
	protected $activityfriend_model;
	protected $activityuserprize_model;
	protected $share_data;
	protected $users;

	function _initialize(){
		parent::_initialize();
		$this->activity = D("Activity");
		$this->activity_params = D("ActivityParams");
		$this->form_model = D("Common/Form");
		$this->formdata_model = D("Common/FormData");
		$this->formrow_model = D("Common/FormRows");
		$this->activityuser_model = D("Common/ActivityUser");
		$this->activityfriend_model = D("Common/ActivityFriend");
		$this->activityuserprize_model = D("Common/ActivityUserPrize");
		$this->users = D("Users");
		
		$wechat = new WechatAuth(C('WX_APPID'),C('WX_APPSECRET'));
		$signPackage = $wechat->GetSignPackage();
		$signPackage['appid'] = C('WX_APPID');
		$this->assign("signPackage",$signPackage );
		$this->assign("REMOTE_UPLOAT_PATH",C('REMOTE_UPLOAT_PATH'));
		$aid = I("get.id");
		//$updateshare_url = 'http://'.$_SERVER['HTTP_HOST'].U('portal/mjoin/update_acshare',array('id'=>$aid));
		$updateshare_url = "";
		$this->activity->where(array("id"=>$aid))->setInc("hits");
		$this->assign('update_url',$updateshare_url);
		$url='http://'.$_SERVER['HTTP_HOST'].U('portal/mindex/index');
		$share_data = array(
			'title'	=>"同翼时代微活动发布平台",
			'desc'  =>"微活动平台，您身边的招生专家",
			'url' => $url,
			'imgUrl' =>'__TMPL__Apps/common/ac_share.jpg'
		);
	    $this->assign('share',$share_data);
		$menu = I("get.menu",1,"intval");
		$this->assign("menu",$menu);

		
	}
	/**
	* 入口文件处理微信自定义入口
	**/
	public function entry($url,$type=true){
		//return true;
		$user = session('wxInfo');
		if(empty($user)){
			$wechat = new WechatAuth(C('WX_APPID'),C('WX_APPSECRET'));
			$code = $_GET['code'];
			if($code){
				$token = $wechat->getAccessToken('code',$code);
				$userInfo = $wechat->getUserInfo($token);
				//查找openid对应的user
				$result=$this->users->where(array('from_user'=>$userInfo['openid']))->find();
				if($result){
					if($type){
						session('user',$result);
					}
				}
				session('wxInfo',$userInfo);
			}else{
				$codeUrl = $wechat->getRequestCodeURL($url,1);
				header("Location:$codeUrl");
				die();
			}
		}
	}
	public function init_login($wxInfo,$type=true){
		if($wxInfo&&$type){
			$result=$this->users->where(array('from_user'=>$wxInfo['openid']))->find();
			//echo "from_user:".$wxInfo['openid'];
			//var_dump($result);
			//die();
			if($result){
				if($type){
					session('user',$result);
				}
			}
		}
	}
	//更新登录用户的微信信息
	public function wechat_save(){
		$uid=get_current_userid();
		$user=$this->users->where(array('id'=>$uid))->find();
		//获取粉丝详细信息。
		$user_info=session('wxInfo');
		if($user_info){
			$data['from_user']=$user_info['openid'];
			$data['nickname']=$user_info['nickname'];
			$data['wx_avatar']=$user_info['headimgurl'];
			$wx_user = $this->users->where(array('from_user'=>$user_info['openid']))->find();
			//var_dump($wx_user);
			$this->users->where(array('from_user'=>$user_info['openid']))->save(array('from_user'=>''));
			$this->users->where(array('id'=>$uid))->save($data);
		}
	}
	//个人中心
	public function user_center(){
		$menu=I('get.menu',0,'intval');
		$this->assign('menu',$menu);
		$uid=get_current_userid();
		if(empty($wxInfo)){
			$this->entry($url,false);
		}
		$wxUser = session('wxInfo');
		//var_dump($wxUser);
		//保存微信信息
	    $this->wechat_save();
		if($uid)
		{
			$user=D('users')->where('id='.$uid)->find();
			$this->assign('user',$user);
			$this->display("/mobile/user");
		}
		else
		{
			//echo "未登录";
			$this->display("/mobile/clickLogin");
		}
		
	}
	//登录页面
	public function login(){
		$url='http://'.$_SERVER['HTTP_HOST'].U('portal/mindex/login');
		if(empty($wxInfo)){
			$this->entry($url,false);
		}
		$wxInfo = session('wxInfo');
		//var_dump($wxInfo);
		$this->display("/mobile/login");
	}
	
	//退出
	public function loginout(){
		session("user",null);
		setcookie("userInfo","", time() - 3600,"/");
		$url = 'http://'.$_SERVER['HTTP_HOST'].U('portal/mindex/login');
		header('Location: '.$url);
	}
    public function index() {
	  $url='http://'.$_SERVER['HTTP_HOST'].U('portal/mindex/index');
	  $wxInfo = session('wxInfo');
	  $from_user = $wxInfo['openid'];
	  if(empty($wxInfo)){
		$this->entry($url,true);
	  }else{
		$this->init_login($wxInfo,true);
	  }
	  $menu=2;
	  $this->assign('menu',$menu);
	  $type=I('get.type',0,'intval');
	  $this->assign('type',$type);
	  $this->display("/mobile/appCenter");
    }
	//注册
	public function regist(){
		$url='http://'.$_SERVER['HTTP_HOST'].U('portal/mindex/register');
		$wxInfo = session('wxInfo');
		if(empty($wxInfo)){
			$this->entry($url,false);
		}
		$this->display("/mobile/register");
	}
	//注册新页面
	public function registnew(){
		$url='http://'.$_SERVER['HTTP_HOST'].U('portal/mindex/registnew');
		$wxInfo = session('wxInfo');
		$from_user = $wxInfo['openid'];
		if(empty($wxInfo)){
			$this->entry($url);
		}
		$this->assign('show_invate',1);
		$this->display("/mobile/registnew");
	}
	public function registnew_save(){
		$vertify = I("post.vertify");
 		$res = check_mobile_vertify($vertify);
 		if(!$res){
			$this->error("验证码错误!");
		}
		$data['user_nicename']=I('post.user_nicename');
		$data['mobile']=I('post.mobile');
		$data['school']=I('post.school');
		$pid=I('post.pid',0,'intval');
		$data['pid']=$pid;
		$data['user_login']=I('post.mobile');
		$wxInfo=session('wxInfo');
		$data['from_user']=$wxInfo['openid'];
		$data['nickname']=$wxInfo['nickname'];
		$data['wx_avatar']=$wxInfo['headimgurl'];
		$data['user_type']=2;
		
		$user=$this->users->where(array('user_login'=>$data['user_login']))->find();
		if($user){
			$data['create_time']=date("Y-m-d H:i:s",time());
			$result= $this->users->where(array('id'=>$user['id']))->save($data);
		}else{
			$data['create_time']=date("Y-m-d H:i:s",time());
			$data['valid_time'] = strtotime('1 months');
			$result=$this->users->add($data);
		}
		if($result){
			$count = $this->users->where(array('pid'=>$pid))->count();
			if($count==2){
				 $puser =  $this->users->where(array('id'=>$pid))->find();
				 if($puser['level']==0){
				 	$dd['valid_time'] = strtotime("11 months",$puser['valid_time']);
				 	$this->users->where(array('id'=>$pid))->save($dd);
				 }
			}
		}else{
			$this->error("注册失败！");
		}
		$this->success("注册成功");
	}
	//新页面注册完成后
	public function registnew_finish(){
		
	}
	//邀请有礼页面
	public function invite(){
		//没注册返回注册
		$uid=I('get.uid',0,'intval');
		$url='http://'.$_SERVER['HTTP_HOST'].U('portal/mindex/invite',array('uid'=>$uid));
		$wxInfo = session('wxInfo');
		if(empty($wxInfo)){
			$this->entry($url);
		}
		$from_user = $wxInfo['openid'];
		//查询用户
		$self=$this->users->where(array('from_user'=>$from_user))->find();
		$this->assign('self',$self);
		$self_id = intval($self['id']);
		if($uid==0){
			$uid = $self_id;
		}
		$share_url = 'http://'.$_SERVER['HTTP_HOST'].U('portal/mindex/invite',array('uid'=>$uid));
		$share_data = array(
			'title'	=>"同翼时代微活动发布平台",
			'desc'  =>"微活动平台，您身边的招生专家",
			'url' => $share_url,
			'imgUrl' =>'__TMPL__Apps/common/ac_share.jpg'
		);
	    $this->assign('share',$share_data);
		if($uid==$self_id){
			if(empty($self)){
				//$regist_url = 'http://'.$_SERVER['HTTP_HOST'].U('portal/mindex/regist');
				//改为老登录
				$regist_url = 'http://'.$_SERVER['HTTP_HOST'].U('portal/mindex/login');
				header('Location: '.$regist_url);
			}
			//则显示自己的昵称，头像，邀请记录，二维码
			$this->assign('user',$self);
			$yaoqing_list=$this->users->where(array('pid'=>$uid))->select();
			$this->assign('yaoqing_list',$yaoqing_list);
			$this->assign('uid',$uid);
			$this->display("/mobile/yaoqing");	
		}else{
			//显示邀请者的昵称，头像，二维码
			$his=$this->users->where(array('id'=>$uid))->find();
			$this->assign('user',$his);
			$this->assign('nolist',1);
			$this->assign('uid',$uid);
			$this->display("/mobile/yaoqing");	
		}
		
		
	}
	//注册下一步
	public function zhuce(){
		$this->display("/mobile/zhuce");
	}
	//忘记密码
	public function forget(){
		$this->display("/mobile/forget");
	}
	//修改密码页面
	public function revise(){
		$this->display("/mobile/revise");
	}
	//找回密码，填写新密码
	public function setnewpassword(){
		$hash=I('get.hash');
		$this->assign('hash',$hash);
		$this->display("/mobile/reset");
	}
	//查看个人资料
	public function myData(){
		$uid=get_current_userid();
		//$uid=get_current_userid();
		$user=D('users')->where('id='.$uid)->find();
		$this->assign('user',$user);
		$this->display("/mobile/myData");
	}
	//查看反馈意见
	public function advice(){
		$this->display("/mobile/advice");
	}
	//查看联系客服
	public function kefu(){
		$this->display("/mobile/operator");
	}
	//查看活动 我的活动
	public function activity(){
		$url='http://'.$_SERVER['HTTP_HOST'].U('portal/mindex/activity');
		$wxInfo = session('wxInfo');
		$from_user = $wxInfo['openid'];
		if(empty($wxInfo)){
			$this->entry($url,true);
		}else{
		   $this->init_login($wxInfo,true);
	    }
		$uid=get_current_userid();
		//$uid=get_current_userid();
		//保存微信信息
	    $this->wechat_save();
		if($uid){
			$where = 'uid='.$uid.' and valid = 1';
			//查询活动数量。
			$activity_cha_num=$this->form_model->where($where)->count();
			$users = D('users'); 
			$pagecount = 10;
			$page = new \Think\Page($activity_cha_num , $pagecount);
			$page->parameter = $row; 
			$page->setConfig('prev','<');
			$page->setConfig('next','>');
			$page->rollPage=5;
			$show = $page->show();
			$list = $this->form_model->where($where)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
			foreach($list as &$v){
				$total = $this->formrow_model->where(array("form_id"=>$v[id],"status"=>1))->count();
				$v['total'] = $total;
			}
			$menu=1;
			$this->assign('list',$list);
			$this->assign('page',$show);
			$this->assign('menu',$menu);	
			$this->display("/mobile/myactivity"); //我的活动
		}else{
			$is_login='f';
			$this->assign('is_login',$is_login);
			$this->display("/mobile/myactivity");
		}
	}
	//查看活动 我的活动 原版
	public function application(){
		$uid=get_current_userid();
		//echo "<br><br><br>uid".$uid;
		//print_r(session('userInfo'));
		//$uid=get_current_userid();
		//保存微信信息
	    $this->wechat_save();
		if($uid){
			//应用
			$uid=get_current_userid();
			$where = 'uid='.$uid.' and valid = 1';
			//查询应用数量。
			$app_cha_num=$this->activity->where($where)->count();
			$users = D('users'); 			
			$pagecount = 10;
			$page = new \Think\Page($app_cha_num , $pagecount);
			$page->setConfig('prev','<');
			$page->setConfig('next','>');
			$page->rollPage=5;
			$show = $page->show();
			$list = $this->activity->where($where)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
			foreach($list as &$v){
				$taid = $v['id'];
				$total = $this->activityuser_model->where("aid=$taid and username<>''")->count();
				$v['total'] = $total;
			}
			$this->assign('applist',$list);
			$this->assign('apppage',$show);			
			$menu=1;
			$this->assign('menu',$menu);	
			$this->display("/mobile/myapplication");
		}else{
			$is_login='f';
			$this->assign('is_login',$is_login);
			$this->display("/mobile/myapplication");
		}	
	}
	//查看活动报名管理
	public function user_list(){
		$id=I('get.id',0,'intval');
		//默认查询条件
		$where='form_id='.$id." and status=1";
		//查询符合条件的数量。
		$keyword=I('get.keyword');
		if($keyword)
		{
			$where.=" and (username like '%{$keyword}%'"." or mobile like '%{$keyword}%')";
		}
		
		$count = D('FormRows')->where($where)->count();// 查询满足要求的总记录数
		$Page  = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$Page->setConfig('prev','<');
		$Page->setConfig('next','>');
		$Page->rollPage=5;
		$show  = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list =  D('FormRows')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		//添加邀请人
		foreach($list as $key=>$tmp)
		{
			if($tmp['pid']!=0)
			{
				$result=D('FormRows')->where('id='.$tmp['pid'])->find();
				if($result)
				{
					$list[$key]['yq']=$result['username'];
				}
			}
		}
		
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display("/mobile/list");
	}
	//查看报名管理详细信息
	public function user_listDetail(){
		$pid=I('get.pid',0,'intval');
		$yaoqings=D('FormRows')->where('pid='.$pid)->select();
		$this->display("/mobile/listDetail");
	}
	//查看 看看
	public function appCenter(){
		$url='http://'.$_SERVER['HTTP_HOST'].U('portal/mindex/index');
		$wxInfo = session('wxInfo');
		$from_user = $wxInfo['openid'];
		if(empty($wxInfo)){
			$this->entry($url,true);
		}else{
			$this->init_login($wxInfo,true);
		}
		$uid=get_current_userid();
		$menu=I('get.menu',0,'intval');
		$this->assign('menu',$menu);
		$this->display("/mobile/appCenter");
	}
	//应用预览summary
	public function summary(){
		//应用id
		$id=I('get.id',0,'intval');
		$slide_id = I('get.slide_id',0,'intval');
		$slide = D("Slide")->where(array('slide_id'=>$slide_id))->find();
		$app=$this->activity->where('id='.$id)->find();
		$this->assign('app',$app);
		$this->assign('slide',$slide);
		$this->display("/mobile/summary");
	}
	//提交意见
	public function suggest_post(){
		//$uid=get_current_userid();
		$uid=get_current_userid();
		$user=D('users')->where('id='.$uid)->find();
		$data['full_name'] = $user['user_nicename'];
		$data['title'] = I('post.suggest');
		$data['msg'] = I('post.suggest');//意见
		$data['email'] = I('post.lianxi');//联系方式
		$data['createtime'] =  date ( "Y-m-d H:i:s" ,time());
		$result=D('guestbook')->add($data);
		if($result)
		{
			$url=U('portal/mindex/user_center');
			header("Location:".$url);
		}
	}
	//查看会员特权
	public function power(){
		$this->display("/mobile/power");
	}
	//查看 帮助中心
	public function help(){
		$menu=I('get.menu',0,'intval');
		$this->assign('menu',$menu);
		$this->display("/mobile/help");
	}
	//查看应用的报名管理app_list
	public function app_list(){
		$id=I('get.id',0,'intval');
		$application=$this->activity->where('id='.$id)->find();
		$this->assign('application',$application);
		//默认查询条件
		$where='aid='.$id." and mobile is not null";
		//根据参数查询对应信息。
		if($application['type']=='questionnaire'){
			$where = "aid=$id";
		}
		//查询符合条件的数量。
		$keyword=I('get.keyword');
		if($keyword)
		{
			$where.=" and (username like '%{$keyword}%'"." or nickname like '%{$keyword}%'"." or mobile like '%{$keyword}%')";
		}
		$count = D('ActivityUser')->where($where)->count();// 查询满足要求的总记录数
		$Page  = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show  = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = D('ActivityUser')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display("/mobile/app_list");
	}
	//查看应用 成语接龙的记录app_listDetail
	public function app_listDetail(){
		/*if($_COOKIE['userInfo']){
			$userInfo = json_decode($_COOKIE['userInfo'],true);
			$uid = $userInfo['id'];
		}else{
			$uid=get_current_userid();
		}*/
		$uid=I('get.uid',0,'intval');
		$id=I('get.id',0,'intval');
		$application=$this->activity->where('id='.$id)->find();
		$this->assign('application',$application);
		//获取本人的信息
		$me=D('ActivityUser')->where('id='.$uid)->find();
		$this->assign('me',$me);
		
		$count = D('ActivityFriend')->where(array('aid'=>$me['aid'],'uid'=>$me['id']))->count();// 查询满足要求的总记录数
		$Page  = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$Page->setConfig('prev','<');
		$Page->setConfig('next','>');
		$Page->rollPage=5;
		$show  = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$friends = D('ActivityFriend')->where(array('aid'=>$me['aid'],'uid'=>$me['id']))->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('friends',$friends);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display("/mobile/app_listDetail");
	}
	//宝箱
	public function baoxiang(){
		//获取参数信息。
		$aid=I('get.id',0,'intval');
		$userid=I('get.uid',0,'intval');
		$help=I('get.help',0,'intval');
		//获取活动信息。
		$activity=$this->activity->where('id='.$aid)->find();
		//如果是帮助，到帮助表中查openid，否则到会员表中查openid。
		if($help==1)
		{
			$user=$this->activityfriend_model->where('id='.$userid)->find();
		}
		else
		{
			$user=$this->activityuser_model->where('id='.$userid)->find();
		}
		//根据参数查询对应信息。
		$where = 'aid='.$aid." and from_user='".$user['from_user']."'";
		//查询所有报名数量。
		$user_all_num=$this->activityuserprize_model->where($where)->count();
		//查询符合条件的报名数量。
		$user_num=$this->activityuserprize_model->where($where)->count();
		$pagecount = 10;
		$page = new \Think\Page($user_num , $pagecount);
		$page->parameter = array('id'=>$aid,'uid'=>$userid,'help'=>$help); 
		//$page->parameter = $row; 
		$page->setConfig('prev','<');
		$page->setConfig('next','>');
		$page->rollPage=5;
		$show = $page->show();
		$list = $this->activityuserprize_model->where($where)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->assign('application',$activity);

		$this->display("/mobile/baoxiang");
	}
	//有奖问答
	public function app_answer()
	{
		//获取参数信息。
		$aid=I('get.id',0,'intval');
		$userid=I('get.uid',0,'intval');
		//获取活动信息。
		$application=$this->activity->where('id='.$aid)->find();
		$this->assign('application',$application);
		//获取本人的信息
		$me=D('ActivityUser')->where('id='.$userid)->find();
		$this->assign('me',$me);
		//根据参数查询对应信息。
		$where = 'aid='.$aid." and uid=".$userid;
		/*if($keyword)
		{
			$where.=" and name like '%{$keyword}%'";
		}*/
		//查询符合条件的报名数量。
		$user_num=D('Common/QuestionnaireUserData')->where($where)->count();
		$pagecount = 10;
		$page = new \Think\Page($user_num , $pagecount);
		$page->parameter = array('id'=>$aid,'uid'=>$userid); 
		//$page->parameter = $row; 
		$page->setConfig('prev','<');
		$page->setConfig('next','>');
		$show = $page->show();
		$sql="select qud.id,qud.aid,qud.uid,qud.iid,qp.title,qp.item1,qp.item2,qp.item3 from cmf_questionnaire_problem as qp,cmf_questionnaire_user_data as qud where qud.aid=".$aid." and qud.qid=qp.id and qud.uid=".$userid;
		$Model = new \Think\Model();
		$list=$Model->query($sql);
		$this->assign('friends',$list);
		$this->assign('page',$show);
		$this->display("/mobile/app_answer");
	}
	//学霸
	//查看答题记录，这里是显示得分信息的。
	public function app_answer_data()
	{
		//获取参数信息。
		$aid=I('get.id',0,'intval');
		$userid=I('get.uid',0,'intval');
		//获取活动信息。
		$application=$this->activity->where('id='.$aid)->find();
		$this->assign('application',$application);
		//获取本人的信息
		$me=D('ActivityUser')->where('id='.$userid)->find();
		$this->assign('me',$me);
		//获取这个会员的所有答题记录，这里用多表查询。
		$list=D('Common/QuestionnaireUserData')->where(array('aid'=>$aid,'uid'=>$userid))->select();
		//获取所有题目分类。
		$typels=D('Common/AnswerType')->where(array('aid'=>$aid))->select();
		//替换类型id为类型名称。
		for($i=0;$i<count($list);$i++)
		{
			if($list[$i]['iid']>0)
			{
				for($j=0;$j<count($typels);$j++)
				{
					if($list[$i]['iid']==$typels[$j]['id'])
					{
						$list[$i]['typename']=$typels[$j]['name']; break;
					}
				}
			}
			else $list[$i]['typename']="未记录";
		}
		$this->assign('friends',$list);
		$this->display("/mobile/app_answer_data");
	}
	//weigreet
	public function app_show_help()
	{	
		//获取参数信息。
		$aid=I('get.id',0,'intval');
		$userid=I('get.uid',0,'intval');
		//获取活动信息。
		$application=$this->activity->where('id='.$aid)->find();
		$this->assign('application',$application);
		//获取本人的信息
		$me=D('ActivityUser')->where('id='.$userid)->find();
		$this->assign('me',$me);
		//根据参数查询对应信息。
		$where = 'aid='.$aid." and uid=".$userid;
		//查询所有报名数量。
		$user_all_num=D('ActivityFriend')->where($where)->count();
		$this->assign('user_all_num',$user_all_num);
		//查询符合条件的报名数量。
		$user_num=D('ActivityFriend')->where($where)->count();
		$pagecount = 10;
		$page = new \Think\Page($user_num , $pagecount);
		$page->parameter = array('id'=>$aid,'uid'=>$userid); 
		//$page->parameter = $row; 
		$page->setConfig('prev','<');
		$page->setConfig('next','>');
		$page->rollPage=5;
		$show = $page->show();
		//国庆猜礼盒必须正序排列，否则礼盒一二三顺序会乱
		$list=array();
		if($application['type']=='nationday'){
			$list = D('ActivityFriend')->where($where)->order('id asc')->limit($page->firstRow.','.$page->listRows)->select();	
		}else{
			$list = D('ActivityFriend')->where($where)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
		}
		$this->assign('friends',$list);
		$this->assign('page',$show);
		$this->display("/mobile/app_show_help");
	}
	//生成二维码
	public function geterweima(){
		vendor("phpqrcode.phpqrcode");
		$uid = I("get.uid",0,"intval");
		$avatar=false;
		//拼接链接
		$share_url = 'http://'.$_SERVER['HTTP_HOST'].U('portal/mindex/registnew',array('uid'=>$uid));
		$errorCorrectionLevel = 'L';//容错级别   
		$matrixPointSize = 6;//生成图片大小   
		$getimg="erweima.png";
		$QRcode = new \QRcode();
		//生成二维码图片  
		$QRcode::png($share_url,false);
	}
	public function test(){
		//$result=D('users')->where(array('user_login'=>'18203689501'))->select();
		$result=D('users')->where(array('user_login'=>'18037998505'))->select();
		$result2=D('users')->where(array('user_login'=>'17093668718'))->select();
		//17093669335    9
		//17093669329     6
		//17093668128     11
		//$result=D('users')->where(array('pid'=>3))->select();
		print_r($result);
		echo "<br><br>";
		print_r($result2);
	}
	public function updateu(){
		/*$data=array('pid'=>3);
		$result=D('users')->where(array('id'=>459))->save($data);
		$result=D('users')->where(array('id'=>459))->select();
		print_r($result);*/
		$data=array('from_user'=>'');
		$result=D('users')->where(array('id'=>3))->save($data);
		$result=D('users')->where(array('id'=>3))->select();
		print_r($result);
	}
	public function deleteu(){
		$id=I('get.id',0,'intval');
		D('users')->where(array('id'=>$id))->delete();
	}
	public function ttt(){
		$result=D('users')->where(array('pid'=>71))->select();
		print_r($result[0]);
		echo "<br>";
		print_r($result[1]);
		echo "<br>";
		print_r($result[2]);
		echo "<br>";
		print_r($result[3]);
		echo "<br>";
		$user=D('users')->where(array('id'=>3))->find();
		print_r($user);
	}
	public function prints(){
		echo "session:wx<br><br><br>";
		print_r(session("wxInfo"));
		echo "<br>userInfo<br><br>";
		print_r(session("user"));
		echo "<br>cookie<br><br>";
		print_r($_COOKIE['userInfo']);
		echo "<br>end<br><br>";
	}
	
}

