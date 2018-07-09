<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace Portal\Controller;
use Common\Controller\HomebaseController;
use Com\Wechat;
use Com\WechatAuth;
/**
 * 首页
 */
class MjoinController extends HomebaseController {
	/**
	* 创建D模型
	**/
	protected $form_model;
	protected $formitem_model;
	protected $formdata_model;
	protected $formrow_model;
	function _initialize() {
        $this->form_model = D("Common/Form");
        $this->formitem_model = D("Common/FormItem");
        $this->formdata_model = D("Common/FormData");
        $this->formrow_model = D("Common/FormRows");
        // $wechat = new WechatAuth(C('WX_APPID'),C('WX_APPSECRET'));
        // $signPackage = $wechat->GetSignPackage();
        // $signPackage['appid'] = C('WX_APPID');
        // $this->assign("signPackage",$signPackage );
	}

	/**
	* 入口文件处理微信自定义入口
	**/
	public function entry($url)
	{
		//return true;
		$user = session('userInfo');
		if (empty($user)) {
			$wechat = new WechatAuth(C('WX_APPID'),C('WX_APPSECRET'));
			$code = $_GET['code'];
			if ($code) {
				$token = $wechat->getAccessToken('code',$code);
				$userInfo = $wechat->getUserInfo($token);
				session('userInfo',$userInfo);
			} else {
				$codeUrl = $wechat->getRequestCodeURL($url,1);
				header("Location:$codeUrl");
				die();
			}
		}
	}

	/**
	 * 检查用户状态
	 */
	public function  check_mobile_permis($uid)
	{
	    $user_status = M('Users')->where(array("id"=>$uid))->getField("user_status");

		if ($user_status == 0) {
			$this->error('此账号已过期，请联系客服人员！');
		}

		$user = M('Users')->where(array("id"=>$uid))->find();

		if ($user['level'] == 0) {
			$register_time = intval(strtotime($user['create_time']));
			$differ = $user['valid_time'] - time();
			// if ($differ<0) {
			// 	$this->error('此账号已过期，请升级！');
			// }
		}
	}

	/**
	*首页
	**/
	public function index()
	{
		$form_id = I('get.id', 0, 'intval');
		$uid = I('get.uid', 0, 'intval');
		$form = D('form');
		$form_info = $form->where('id = '.$form_id)->find();
		$submitter_id = intval($form_info['uid']); // 发布者id
		//判断是不是外部模版
		if ($form_info['is_outside']==1) {
		//	var_dump(U($form_info['m_action'].'/'.$form_info['c_action'].'/'.$form_info['a_action']));die;
			if ($form_info['c_action']=='Sprout') {
			//	var_dump(get_host_name().'/mengwa');die;
				redirect(get_host_name().'/mengwa');
			}
			if ($form_info['c_action']=='Fanke') {
				//var_dump(get_host_name().'/fanke/details.html');die;
				redirect(get_host_name().'/fanke/details.html');
			}
			if ($form_info['m_action']=='Collage') {
				//var_dump(get_host_name().'/fanke/details.html');die;
				redirect(get_host_name().'/fanke/details.html');
			}
			redirect(U($form_info['m_action'].'/'.$form_info['c_action'].'/'.$form_info['a_action']));
		}
        // 判断活动是否过期
//        if ($form_info['endtime'] < time()) {
//            remind();
//        }

		if ($submitter_id) {
			$this->check_mobile_permis($submitter_id);
		}
		
		if ($form_info['type'] == 2) {
			if (!empty($form_info['thumb'])) {
				$thumb = json_decode($form_info['thumb'],true);
				$form_info['thumb'] = $thumb[0]['url'];
			}
		}
		
		//分析活动是否开始
		$curr_time = time();
		$valid_form = 1;
		if ($curr_time < $form_info['begintime']) {
			$valid_form =- 1;
		}
		if ($curr_time > $form_info['endtime']) {
			$valid_form =- 2;
		}

		//增加访问量
		$form_info['hits']++;
		$data['hits'] = $form_info['hits'];
		$form->where('id = '.$form_id)->save($data);
		$this->assign('form_info', $form_info);
		$count=$this->formrow_model->where('form_id = ' . $form_id)->count();
		$this->assign('count',$count); 
		//报名头像
		$user_infos = $this->formrow_model->where(array('form_id' => $form_id))->order('createtime desc')->select();
		$time = time();
		foreach($user_infos as $key =>$v)
		{
			$user_infos[$key]['day'] = round(($time-$v['createtime']) / 3600 / 24);
			// 关闭了微信，所以不能获取微信用户的头像，手动设置一个默认的，开启微信以后再删除此代码！
			if (empty($v['avatar'])) {
				$user_infos[$key]['avatar'] = '/public/images/headicon.png';
			}
		}
		$this->assign('user_infos',$user_infos); 
		
		$is_regist = false;
		$user = session('userInfo');
		$result = $this->formrow_model->where(array('form_id' => $form_id, 'from_user' => $user['openid']))->find();
		if ($result) {
			$is_regist = true;
		}
		$this->assign('is_regist',$is_regist);
		$result = $this->formrow_model->where(array('form_id'=>$form_id,'from_user'=>$user['openid']))->find();
		if ($result) {
			//注册，uid就是自己
			$uid = $result['id'];
		} else {
			//没注册
			if ($uid!=0) {
				//看这个uid是谁
				$result = $this->formrow_model->where(array('form_id' => $form_id, 'id' => $uid))->find();
				if ($result == false) {
					$uid = 0;
				}
			}
		}

		$share = [
			'title'	=> $form_info['name'],
			'desc'  => $form_info['name'],
			'url' => 'http://'.$_SERVER['HTTP_HOST'].U('portal/mjoin/index',array('id' => $form_id, 'uid' => $uid)),
			'imgUrl' => $form_info['thumb']
		];
		$updateshare_url = 'http://'.$_SERVER['HTTP_HOST'].U('portal/mjoin/update_share',array('id'=>$form_id));
		$this->assign('update_url',$updateshare_url);
		$this->assign('share',$share);
		$this->assign('uid',$uid);
		$this->assign('valid_form',$valid_form);
		$this->display(":".$form_info['option1']);
    }

	/**
	*报名
	**/
	public function join_in()
	{
		$form_id = I('get.id',0,'intval');
		$uid = I('get.uid',0,'intval');
		$form_info = $this->form_model->where('id='.$form_id)->find();
		$this->assign('form_info',$form_info); 
		
		$form_item_list = $this->formitem_model->where('form_id='.$form_id)->order('id asc')->select();
		$random_url = time();
		$pro_tocle = 'http://'.$random_url.'.qihuangongfang.com';
		$shareurl = $pro_tocle.U('portal/mjoin/index',array('id'=>$form_id,'uid'=>$uid));

		$share = [
			'title'	=>$form_info['name'],
			'desc' =>$form_info['name'],
			'url' => $shareurl,
			'imgUrl' => sp_get_asset_upload_path($form_info['thumb'], true)
		];
		$updateshare_url = 'http://'.$_SERVER['HTTP_HOST'].U('portal/mjoin/update_share',array('id'=>$form_id));
		$this->assign('update_url',$updateshare_url);
		$this->assign('uid',$uid);
		$this->assign('share',$share);
		$this->assign('form_item_list',$form_item_list); 
    	$this->display(":enroll");
    }
	
	public function add_post()
	{
		$form_id = I('post.form_id');
		$pid = I('post.uid');
		$time = time();
		$userInfo = session('userInfo');
		$from_user = $userInfo['openid'];
		$createtime = $time;
		$nickname = $userInfo['nickname'];
		$avatar = $userInfo['headimgurl'];
		$result = $this->formrow_model->where(array('form_id' => $form_id, 'from_user' => $from_user))->find();
		if ($result) {
			$this->error("已报名");
		}

		if (IS_POST) {
			$username = I("post.username");
			$mobile = I("post.mobile");
			if (empty($username) || empty($mobile)) {
				$this->error("姓名或手机号不能为空！");
			}

			// 开启微信以后，可以屏蔽该判断
			$find_repeat = $this->formrow_model->field('id')->where(array('form_id' => $form_id, 'mobile' => $mobile))->find();
			if (!empty($find_repeat['id'])) {
				$this->success("手机号码重复！",U("portal/mjoin/form_pay",array('id'=>$form_id,'uid'=>$row_id)));
			}

			$results = htmlspecialchars_decode(I("post.result_str"));
			$results = json_decode($results,true);
			$form_id = I('post.form_id');
			$text = I('post.text');
			$data['from_user'] = $from_user;
			$data['createtime'] = $createtime;
			$data['form_id'] = $form_id;
			$data['nickname'] = $nickname;
			$data['avatar'] = $avatar;
			$data['username'] = I("post.username");
			$data['mobile'] = I("post.mobile");
			$data['pid'] = $pid;
			$data['type'] = "1"; //普通报名
			$row_id = $this->formrow_model->add($data);
			$fids = I("post.fids");
			$for_index = 0;

			foreach ($results AS $v) {
				if ($v['value']) {
					$form_temp = [
						'form_id'=>$form_id,
						'item_id'=>$v['id'],
						'value'=>$v['value'],
						'uid'=>$row_id,
						'from_user'=>$from_user,
						'nickname'=>$nickname,
						'avatar'=>$avatar
					];
					$this->formdata_model->add($form_temp);
				}
			}
			
			$this->success("报名成功！",U("portal/mjoin/form_pay",array('id'=>$form_id,'uid'=>$row_id)));
		}
	}
	/*
	*邀请有礼
	*/
	public function invite()
	{
		$form_id=I('get.id',0,'intval');
		$uid=I('get.uid',0,'intval');
		$form_info=$this->form_model->where('id='.$form_id)->find();
		if($form_info)
		{
			$this->assign('form_info',$form_info); 
		}
		else
		{
			$this->error($this->form_model->getError());
		}
		$random_url = time();
		$pro_tocle = 'http://'.$random_url.'.qihuangongfang.com';
		$shareurl = $pro_tocle.U('portal/mjoin/index',array('id'=>$form_id,'uid'=>$uid));
		$share = array(
			'title'	=>$form_info['name'],
			'desc'  =>$form_info['name'],
			'url' => $shareurl,
			'imgUrl' =>sp_get_asset_upload_path($form_info['thumb'],true)
		);
		$updateshare_url = 'http://'.$_SERVER['HTTP_HOST'].U('portal/mjoin/update_share',array('id'=>$form_id));
		$this->assign('update_url',$updateshare_url);
		$this->assign('uid',$uid);
		$this->assign('share',$share);
		$this->display(":invite");
	}
	/*
	*我的邀请
	*/
	public function myInvite()
	{
		$form_id=I('get.id',0,'intval');
		$uid=I('get.uid',0,'intval');
		$form_info=$this->form_model->where('id='.$form_id)->find();
		if($form_info)
		{
			$this->assign('form_info',$form_info); 
		}
		else
		{
			$this->error($this->form_model->getError());
		}
		//自己
		$userInfo = session('userInfo');
		$from_user = $userInfo['openid'];
		$myself=$this->formrow_model->where(array('form_id'=>$form_id,'from_user'=>$from_user))->find();
		if($myself)
		{
			$this->assign('myself',$myself); 
		}
		else
		{
			$this->error("查询我的信息失败");
		}
		//别人
		$friend_list=$this->formrow_model->where(array('form_id'=>$form_id,'pid'=>$myself['id']))->select();
		if($friend_list)
		{
			$this->assign('friend_list',$friend_list); 
		}
		$random_url = time();
		$pro_tocle = 'http://'.$random_url.'.qihuangongfang.com';

		$shareurl = $pro_tocle.U('portal/mjoin/index',array('id'=>$form_id,'uid'=>$myself['id']));
		$share = array(
			'title'	=>$form_info['name'],
			'desc'  =>$form_info['name'],
			//'url' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'],
			'url' => $shareurl,
			'imgUrl' =>sp_get_asset_upload_path($form_info['thumb'],true)
		);
		$updateshare_url = 'http://'.$_SERVER['HTTP_HOST'].U('portal/mjoin/update_share',array('id'=>$form_id));
		$this->assign('update_url',$updateshare_url);
		$this->assign('share',$share);
		$this->assign('uid',$uid);
		$this->display(":myInvite");
	}
	public function invite_post()
	{
		if(IS_POST)
		{	
			$userInfo = session('userInfo');
			$data['from_user']=$userInfo['openid'];
			$data['form_id']=$_POST['form_id'];
			$data['nickname']=$userInfo['nickname'];
			$data['username']=$_POST['name'];
			$data['mobile']=$_POST['tel'];
			$data['avatar']=$userInfo['headimgurl'];
			$data['createtime']=time();
			$data['pid']=I('post.uid',0,'intval');
			//分销报名
			$data['type']="0";
			$result=$this->formrow_model->where(array('from_user'=>$data['from_user'],'form_id'=>$data['form_id']))->find();
			if($result)
			{
				$this->error("已注册");
			}
			$row_id = $this->formrow_model->add($data);
			$this->success("邀请成功！",U("portal/mjoin/index",array('id'=>$data['form_id'],'uid'=>$row_id)));
		}
	}
	//更新分享次数
	public function update_share(){
		$userInfo = session('userInfo');
		$from_user = $userInfo['openid'];
		
		$form_id = I("get.id",0,"intval");
		
		$user=$this->formrow_model->where(array('from_user'=>$from_user,'form_id'=>$form_id))->find();
		
		$data['share_hits'] =$user['share_hits']+1;
		$res = $this->formrow_model->where(array("id"=>$user['id']))->save($data);
		if($res){
			$this->success("分享成功！");
		}else{
			$this->error("分享失败！");
		}
	}
	//更新分享次数
	public function update_acshare(){
		$id = I("get.id",0,"intval");
		$res =D("Activity")->where(array("id"=>$id))->setInc("share_total");
		if($res){
			$this->success("分享成功！");
		}else{
			$this->error("分享失败！");
		}
	}
	//报名表单支付
	public function form_pay(){
		$form_id=I('get.id',0,'intval');
		$uid=I('get.uid',0,'intval');
		$form_info=$this->form_model->where('id='.$form_id)->find();
		if(empty($form_info['paycode_thumb'])){
			$redirect = U("mjoin/index",array('id'=>$form_id,'uid'=>$uid));
			header("Location:$redirect");
		}
		$this->assign("form_info",$form_info);
		//var_dump($form_info);
		$this->display(":form_pay");
	}
}


