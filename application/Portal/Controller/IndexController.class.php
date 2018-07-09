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
class IndexController extends HomebaseController
{
	//首页
	public function index()
	{
		phpinfo();die;
		$banner = M('banner')->where("`name` = 'activity'")->order('`order` DESC, `id` DESC')->limit(1)->find();
		$sql = "SELECT f.id, f.thumb, f.option3, f.name, f.createtime, u.avatar, u.user_nicename FROM cmf_form f, cmf_users u WHERE f.uid = u.id AND f.type = 1 AND f.recommend = 1 ORDER BY listorder";

		$default = M()->query($sql);


		foreach ($default as $k => $v) {
			//var_dump( $default);die;
			
				if (!empty($v['thumb'])) {
					$thumb = json_decode($v['thumb'],true);
						//var_dump($thumb);die;
					$default[$k]['thumb'] = $thumb[0]['url'];
				}
			
		}
	
		$this->assign('banner', $banner);
		$this->assign('default', $default);
//		$this->assign('default', sp_getRecforms());
		$this->display(":index");
    }
	/**
	*应用中心
	*/
	public function application()
	{
		$banner = M('banner')->where("`name` = 'application'")->order('`order` DESC, `id` DESC')->select();
		$this->assign('banner',$banner);
        $this->assign('domain_name',C('DOMAIN_NAME'));
		$this->assign('app_center',sp_getslide('app_center'));
		$this->display(":application");
	}
	/****
	* 应用中心微官网
	**/
	public function weisite(){
		$uid=get_current_userid();
		$users = D('users'); 
		$users_info=$users->where('id='.$uid)->find();
		if(empty($users_info['school'])||empty($users_info['contact'])||empty($users_info['mobile']))
		{
			$full=false;
		}
		else
		{
			$full=true;
		}
		$list = D("Slide")->where(array("slide_cid"=>3))->select();
		$this->assign('users_info',$users_info);
		$this->assign('list',$list);
		$this->assign('full',$full);
		$this->display(":weisite");
	}
	public function show_slogan()
	{
		$this->assign('domain_name',C('DOMAIN_NAME'));
		$this->display(":slogan");
	}
	// 提交技术支持页面收集的用户信息
	public function add_statistics()
	{
		$name = I('post.name');
		$phone = I('post.phone');

		if (empty($name) || empty($phone)) {
			$this->ajaxReturn(['code' => -1, 'result' => '姓名或手机号不能为空！']);
		}

		if (M('statistics')->where("phone = '".$phone."'")->find()) {
			$this->ajaxReturn(['code' => -3, 'result' => '已提交过信息！']);
		}

		if (M('statistics')->data(['name' => $name, 'phone' => $phone,'create_time' => date('Y-m-d H:i:s')])->add()) {
			$this->ajaxReturn(['code' => 0, 'result' => '提交成功！']);
		} else {
			$this->ajaxReturn(['code' => -4, 'result' => '提交失败，请刷新页面重新填写！']);
		}

		$this->ajaxReturn(['code' => -5, 'result' => '出现异常，请刷新页面！']);
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
	*会员特权
	*/
	public function show_vip()
	{
		$uid = get_current_userid();
		$users = D('users'); 
		$users_info = $users->where('id='.$uid)->find();
		if (empty($users_info['school']) || empty($users_info['contact']) || empty($users_info['mobile'])) {
			$full = false;
		} else {
			$full = true;
		}
		$banner = M('banner')->where("`name` = 'member'")->order('`order` DESC, `id` DESC')->limit(1)->find();
		$this->assign('banner',$banner);
		$this->assign('users_info', $users_info);
		$this->assign('full', $full);
		$this->assign('domain_name', C('DOMAIN_NAME'));
		$this->display(":vip");
	}
	public function show_shengji()
	{
		$uid=get_current_userid();
		$users = D('users'); 
		$users_info=$users->where('id='.$uid)->find();
		if(empty($users_info['school'])||empty($users_info['contact'])||empty($users_info['mobile']))
		{
			$full=false;
		}
		else
		{
			$full=true;
		}
		
		$where = 'uid='.$uid.' and valid = 1';
		//查询活动数量。
		$activity_num=D("Common/Form")->where($where)->count();
		$this->assign('activity_num',$activity_num);
		//查询应用数量。
		$app_num=D("Common/Activity")->where($where)->count();
		$this->assign('app_num',$app_num);
		$this->assign('users_info',$users_info);
		$this->assign('full',$full);
		$this->display(":shengji");
	}
	public function templet1()
	{
		$option=I('get.option');
		$tpl_id = I("get.tpl_id",0,"intval");
		$form = D("Form")->where(array("id"=>$tpl_id))->find();
		if($form['type']==2){
			if(!empty($form['thumb'] )){
				$thumb = json_decode($form['thumb'],true);
				$form['thumb'] = $thumb[0]['url'];
			}
		}
		//var_dump($form);
		$this->assign($form);
		$this->display(":".$option);
	}
	//更新会员信息 --- 升级专用
	public function update_user(){
		$id=get_current_userid();
		$users = D('users'); 
		$users_info=$users->where('id='.$id)->find();
		if(empty($users_info)){
			$this->error("暂时没有信息！");
		}
		//查询激活码是否存在
		$regcode = I("post.regcode");
		if(empty($regcode)){
			$this->error("注册码不能为空！");
		}
		$regcode = D('Regcode')->where(array('code'=>$regcode,'status'=>0))->find();
		if(empty($regcode)){
			$this->error("您输入的激活码无效！");
		}else{
			$data['level'] = $regcode['type'];
			$data['valid_time'] = time()+24*3600*365;
			$users->where('id='.$id)->save($data);
			//更改激活码的状态。
			D('Regcode')->where('id='.$regcode['id'])->save(array('status'=>1));
			$this->success("升级成功！",U('portal/activity/index',array('menu'=>3)));
		}
	}
	
	public function getSlide(){
		$id = I("post.id",0,"intval");
		$result = D("Slide")->where(array("slide_id"=>$id))->find();
		$this->ajaxReturn($result);
	}
	//机器人专区
	public function dash_region(){
		$ce = I("get.ce",0,"intval");
		$uid=get_current_userid();
		$uid = intval($uid);
		$users = D('users'); 
		$users_info=$users->where('id='.$uid)->find();
		if(empty($users_info['school'])||empty($users_info['contact'])||empty($users_info['mobile']))
		{
			$full=false;
		}
		else
		{
			$full=true;
		}
		$this->assign('users_info',$users_info);
		$this->assign('full',$full);
		$this->display(":dash_region");
	}
	public function testm(){
		$result=M("Users")->where(array('mobile'=>'11011011011'))->select();
		print_r($result);
	}
	public function updatem(){
		$data['from_user']='';
		$data['nickname']='';
		$data['wx_avatar']='';		
		$result=M("Users")->where(array('id'=>3))->save($data);
		print_r($result);
	}
	public function showuserd(){
		$result=M('User')->where(array('mobile'=>'13667431577'))->select();
		print_r($result);
	}
	public function clean_wx_access_token()
    {
        S('wx_access_token', null);
        S('access_token', null);
        session('userInfo', null);
        echo "<p style='font-size:48px;'>清理数据完成,请点左上角返回微信！</p>";
        exit;
    }
}


