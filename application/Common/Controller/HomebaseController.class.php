<?php
namespace Common\Controller;
use Common\Controller\AppframeController;
use Common\Controller\JavaApiUserController;

class HomebaseController extends AppframeController {
	
	// public function __construct()
	// {
	// 	//$this->set_cas_init();
	// 	//$this->set_action_success_error_tpl();
	// 	parent::__construct();
 //        if (isset($_SESSION["user"])) {
 //            $users_model = M('users');
 //            $users_info = $users_model->field('user_login, level, valid_time')->where('id = ' . $_SESSION["user"]['id'])->find();
	// 		// 重新生成session
 //            if (!empty($users_info['user_login'])) {
 //                session('user',null);
 //                $this->user_login($users_info['user_login']);
 //            }
 //        }
	// }
	
	// function _initialize() {

	// 	parent::_initialize();
	// 	//$site_options=get_site_options();
	// 	$this->assign($site_options);

	// 	$ucenter_syn=C("UCENTER_ENABLED");

	// 	if($ucenter_syn){
			
	// 		if(!isset($_SESSION["user"])){
	// 			if(!empty($_COOKIE['thinkcmf_auth'])  && $_COOKIE['thinkcmf_auth']!="logout"){
	// 				$thinkcmf_auth=sp_authcode($_COOKIE['thinkcmf_auth'],"DECODE");
	// 				$thinkcmf_auth=explode("\t", $thinkcmf_auth);
	// 				$auth_username=$thinkcmf_auth[1];
	// 				$users_model=M('Users');
	// 				$where['user_login']=$auth_username;
	// 				$user=$users_model->where($where)->find();
	// 				if(!empty($user)){
	// 					$is_login = true;
	// 					$_SESSION["user"]=$user;
	// 				}
	// 			}
	// 		}else{
	// 		}
	// 	}
		
	// 	if(sp_is_user_login()){
	// 		$this->assign("user",sp_get_current_user());
	// 	}
		
	// }

	/**
	 * 初始化CAS
	 */
	private function set_cas_init()
	{
		include_once './Phpcas/Phpcas.php';
//		\Phpcas::setDebug(); // 日志
		\Phpcas::setVerbose(false); // 启用详细错误信息，生产环境下设置为false。
		\Phpcas::client(CAS_VERSION_2_0, C('CAS_DOMAIN'), C('CAS_PORT'), C('CAS_DIRECTORY')); // 初始化CAS
		\Phpcas::setNoCasServerValidation(); // 不设置CAS服务器的SSL验证，生产环境下注释掉该方法。
		// 其他站点已经登录，该站未登陆的情况下将自动登录
		// if (!sp_is_user_login()) {
		// 	if (CONTROLLER_NAME !== 'Base') {
		// 		if (!empty($this->checkAuthentication())) {
		// 			$mobile = $this->get_cas_user();
		// 			if (!$this->check_user_exist($mobile)) {
		// 				$this->user_register($mobile);
		// 			}
		// 			$this->user_login($mobile);
		// 			redirect(__ROOT__."/");
		// 		}
		// 	}
		// }
	}

	/**
	 * 强制认证，认证失败则定向到CAS服务器
	 */
	public function forceAuthentication()
	{
		\Phpcas::forceAuthentication();
	}

	/**
	 * 松散认证，认证失败不作为，认证成功返回1。
	 */
	public function checkAuthentication()
	{
		return \Phpcas::checkAuthentication();
	}

	/**
	 * 获取CAS认证账号
	 */
	public function get_cas_user()
	{
//		return \Phpcas::getUser();
	}

	/**
	 * CAS认证注销
	 */
	public function cas_logout()
	{
		\Phpcas::logout(['service' => 'http://' . $_SERVER['HTTP_HOST']]);
	}

	/**
	 * 获取校营通用户信息
	 */
	protected function get_other_user_info($mobile)
	{
		$javaApiUser = new JavaApiUserController();
		$phone = $javaApiUser->security_encrypt($mobile);
		$db = C('DB_XYT');
		$customerSql = "SELECT `id`, `name`, `portrait` FROM `customer` WHERE `phone` = '" . $phone . "'";
		$customer = M()->db(1,$db)->query($customerSql);
		$organizationSql = "SELECT `name`, `vip_end_time` FROM `crm_organization` WHERE `cid` = " . $customer[0]['id'];
		$organization = M()->db(1)->query($organizationSql);
		return [
			'id' => $customer[0]['id'],
			'name' => $customer[0]['name'],
			'phone' => $mobile,
			'school' => !empty($organization[0]['name']) ? $organization[0]['name'] : '',
			'avatar' => !empty($this->check_avatar(C('QQ_CLOUD_URL') . $customer[0]['portrait'])) ? C('QQ_CLOUD_URL') . $customer[0]['portrait'] : C('QQ_CLOUD_URL') . '/touxiang.png',
			'valid_time' => !empty($organization[0]['vip_end_time']) ? strtotime($organization[0]['vip_end_time']) : strtotime('+1 year'),
		];
	}

	// 检测头像是否存在
	protected function check_avatar($avatar)
	{
		if (@fopen($avatar, 'r')) { 
		    return true;
		}

		return false;
	}

	/**
	 * 检查用户是否存在
	 */
	protected function check_user_exist($mobile)
	{
		$result = M('users')->field('id')->where("user_login = '" . $mobile . "'")->find();
		return empty($result['id']) ? false : true;
	}

	/**
	 * 登录
	 */
	protected function user_login($mobile)
	{
		$usersModel = M('users');

		$result = $usersModel->where('user_login = ' . $mobile)->find();

		$level = @file_get_contents(C('API_URL') . '/api/v3/user/get_user_level?uid=' . $result['id']);

		if (empty($level) || !is_numeric($level)) {
			$level = 1;
		}

		$result['level'] = $level;

		$_SESSION["user"] = $result;

		$result['expire'] = 3600*24*7;

		session('user',$result,604800);

		setcookie("userInfo",json_encode($result,true),time()+3600*24*365,"/");

		$data = [
            'last_login_time' => date("Y-m-d H:i:s"),
            'last_login_ip' => get_client_ip(0,true),
			'level' => $level
        ];

		 $usersModel->where(array('id'=>$result["id"]))->save($data);
	}

	/**
	 * 注册
	 */
	protected function user_register($mobile)
	{
		$info = $this->get_other_user_info($mobile); // 得到校营通用户信息

		$level = @file_get_contents(C('API_URL') . '/api/v3/user/get_user_level?uid=' . $info['id']);

		if (empty($level) || !is_numeric($level)) {
			$level = 1;
		}

		$data = [
			'id' => $info['id'],
			'user_login' => $mobile,
			'user_nicename' => $info['name'],
			'avatar' => $info['avatar'],
			'create_time' => date('Y-m-d H:i:s'),
			'user_type' => 2,
			'mobile' => $mobile,
			'level' => $level,
			'valid_time' => $info['valid_time']
		];

		M('users')->data($data)->add();
	}
	
	/**
	 * 检查用户登录
	 */
	protected function check_login()
	{
		if(!isset($_SESSION["user"])){
			redirect(U("/user/base/login"));
		}
		
	}
	
	/**
	 * 检查用户状态
	 */
	protected function  check_user(){
//	    $user_status = M('Users')->where(array("id"=>sp_get_current_userid()))->getField("user_status");
//		$user = M('Users')->where(array("id"=>sp_get_current_userid()))->find();
//
//		if($user_status==2){
//			$this->error('您还没有激活账号，请激活后再使用！',U("user/login/active"));
//		}
//
//		if($user_status==0){
//			$this->error('此账号已过期，请联系客服人员！',__ROOT__."/");
//		}
//		//如果是试用用户，超过七天则不能再用
//		if($user['level']==0){
//			$register_time = intval(strtotime($user['create_time']));
//			$differ = $user['valid_time'] - time();
//			if($differ<0){
//				//$this->error('此账号已过期，请升级！',__ROOT__."/");
//				$this->error('此账号已在' . date('Y-m-d H:i:s',$user['valid_time']) . '过期，请升级！',U("portal/index/show_shengji",array("menu"=>2)));
//			}
//		}
	}
	
	/**
	 * 发送注册激活邮件
	 */
	protected  function _send_to_active(){
		$option = M('Options')->where(array('option_name'=>'member_email_active'))->find();
		if(!$option){
			$this->error('网站未配置账号激活信息，请联系网站管理员');
		}
		$options = json_decode($option['option_value'], true);
		//邮件标题
		$title = $options['title'];
		$uid=$_SESSION['user']['id'];
		$username=$_SESSION['user']['user_login'];
	
		$activekey=md5($uid.time().uniqid());
		$users_model=M("Users");
	
		$result=$users_model->where(array("id"=>$uid))->save(array("user_activation_key"=>$activekey));
		if(!$result){
			$this->error('激活码生成失败！');
		}
		//生成激活链接
		$url = U('user/register/active',array("hash"=>$activekey), "", true);
		//邮件内容
		$template = $options['template'];
		$content = str_replace(array('http://#link#','#username#'), array($url,$username),$template);
	
		$send_result=sp_send_email($_SESSION['user']['user_email'], $title, $content);
	
		if($send_result['error']){
			$this->error('激活邮件发送失败，请尝试登录后，手动发送激活邮件！');
		}
	}
	
	/**
	 * 加载模板和页面输出 可以返回输出内容
	 * @access public
	 * @param string $templateFile 模板文件名
	 * @param string $charset 模板输出字符集
	 * @param string $contentType 输出类型
	 * @param string $content 模板输出内容
	 * @return mixed
	 */
	public function display($templateFile = '', $charset = '', $contentType = '', $content = '', $prefix = '') {
		//echo $this->parseTemplate($templateFile);
		parent::display($this->parseTemplate($templateFile), $charset, $contentType);
	}
	
	/**
	 * 获取输出页面内容
	 * 调用内置的模板引擎fetch方法，
	 * @access protected
	 * @param string $templateFile 指定要调用的模板文件
	 * 默认为空 由系统自动定位模板文件
	 * @param string $content 模板输出内容
	 * @param string $prefix 模板缓存前缀*
	 * @return string
	 */
	public function fetch($templateFile='',$content='',$prefix=''){
	    $templateFile = empty($content)?$this->parseTemplate($templateFile):'';
		return parent::fetch($templateFile,$content,$prefix);
	}
	
	/**
	 * 自动定位模板文件
	 * @access protected
	 * @param string $template 模板文件规则
	 * @return string
	 */
	public function parseTemplate($template='') {
		
		$tmpl_path=C("SP_TMPL_PATH");
		define("SP_TMPL_PATH", $tmpl_path);
		// 获取当前主题名称
		$theme      =    C('SP_DEFAULT_THEME');
		if(C('TMPL_DETECT_THEME')) {// 自动侦测模板主题
			$t = C('VAR_TEMPLATE');
			if (isset($_GET[$t])){
				$theme = $_GET[$t];
			}elseif(cookie('think_template')){
				$theme = cookie('think_template');
			}
			if(!file_exists($tmpl_path."/".$theme)){
				$theme  =   C('SP_DEFAULT_THEME');
			}
			cookie('think_template',$theme,864000);
		}
		
		$theme_suffix="";
		
		if(C('MOBILE_TPL_ENABLED') && sp_is_mobile()){//开启手机模板支持
		    
		    if (C('LANG_SWITCH_ON',null,false)){
		        if(file_exists($tmpl_path."/".$theme."_mobile_".LANG_SET)){//优先级最高
		            $theme_suffix  =  "_mobile_".LANG_SET;
		        }elseif (file_exists($tmpl_path."/".$theme."_mobile")){
		            $theme_suffix  =  "_mobile";
		        }elseif (file_exists($tmpl_path."/".$theme."_".LANG_SET)){
		            $theme_suffix  =  "_".LANG_SET;
		        }
		    }else{
    		    if(file_exists($tmpl_path."/".$theme."_mobile")){
    		        $theme_suffix  =  "_mobile";
    		    }
		    }
		}else{
		    $lang_suffix="_".LANG_SET;
		    if (C('LANG_SWITCH_ON',null,false) && file_exists($tmpl_path."/".$theme.$lang_suffix)){
		        $theme_suffix = $lang_suffix;
		    }
		}
		
		$theme=$theme.$theme_suffix;
		
		C('SP_DEFAULT_THEME',$theme);
		
		$current_tmpl_path=$tmpl_path.$theme."/";
		// 获取当前主题的模版路径
		define('THEME_PATH', $current_tmpl_path);
		
		C("TMPL_PARSE_STRING.__TMPL__",__ROOT__."/".$current_tmpl_path);
		
		C('SP_VIEW_PATH',$tmpl_path);
		C('DEFAULT_THEME',$theme);
		
		define("SP_CURRENT_THEME", $theme);
		
		if(is_file($template)) {
			return $template;
		}
		$depr       =   C('TMPL_FILE_DEPR');
		$template   =   str_replace(':', $depr, $template);
		
		// 获取当前模块
		$module   =  MODULE_NAME;
		if(strpos($template,'@')){ // 跨模块调用模版文件
			list($module,$template)  =   explode('@',$template);
		}
		
		
		// 分析模板文件规则
		if('' == $template) {
			// 如果模板文件名为空 按照默认规则定位
			$template = "/".CONTROLLER_NAME . $depr . ACTION_NAME;
		}elseif(false === strpos($template, '/')){
			$template = "/".CONTROLLER_NAME . $depr . $template;
		}
		
		$file = sp_add_template_file_suffix($current_tmpl_path.$module.$template);
		$file= str_replace("//",'/',$file);
		if(!file_exists_case($file)) E(L('_TEMPLATE_NOT_EXIST_').':'.$file);
		return $file;
	}
	
	/**
	 * 设置错误，成功跳转界面
	 */
	private function set_action_success_error_tpl(){
		$theme      =    C('SP_DEFAULT_THEME');
		if(C('TMPL_DETECT_THEME')) {// 自动侦测模板主题
			if(cookie('think_template')){
				$theme = cookie('think_template');
			}
		}
		//by ayumi手机提示模板
		$tpl_path = '';
		if(C('MOBILE_TPL_ENABLED') && sp_is_mobile() && file_exists(C("SP_TMPL_PATH")."/".$theme."_mobile")){//开启手机模板支持
			$theme  =   $theme."_mobile";
			$tpl_path=C("SP_TMPL_PATH").$theme."/";
		}else{
			$tpl_path=C("SP_TMPL_PATH").$theme."/";
		}
		
		//by ayumi手机提示模板
		$defaultjump=THINK_PATH.'Tpl/dispatch_jump.tpl';
		$action_success = sp_add_template_file_suffix($tpl_path.C("SP_TMPL_ACTION_SUCCESS"));
		$action_error = sp_add_template_file_suffix($tpl_path.C("SP_TMPL_ACTION_ERROR"));
		if(file_exists_case($action_success)){
			C("TMPL_ACTION_SUCCESS",$action_success);
		}else{
			C("TMPL_ACTION_SUCCESS",$defaultjump);
		}

		if(file_exists_case($action_error)){
			C("TMPL_ACTION_ERROR",$action_error);
		}else{
			C("TMPL_ACTION_ERROR",$defaultjump);
		}
	}
	
	
}