<?php

/**
 * 验证码处理
 */
namespace Api\Controller;
use Think\Controller;
class CheckcodeController extends Controller {
	
	//快捷登录接口----适用于体验活动或者营销工具部分
	public function quick_login(){
		$mobile = I("post.mobile");
		$n_register = I("post.n_register"); //用于推广注册
		if(empty($mobile)){
			$this->error("手机号不能为空！");
		}
		if(strlen($mobile)!=11){
			$this->error("请输入11位手机号！");
		}
		$where['user_login'] = $mobile;
	    $users_model = M("Users");
	    $result = $users_model->where($where)->find();
		if($n_register){
			if($result){
				$this->error("此手机号已注册！");
			}
		}
		if(empty($result)){
			//新增注册用户
			$endtime = time()+3600*24*7;
			$result = array(
				"user_login" => $mobile,
				"user_pass" => $mobile,
				"mobile" => $mobile,
				"user_nicename" => "匿名用户",
				"user_email" => $mobile,
				"user_url" => " ",
				"user_type" => 2,
				"valid_time" => $endtime,
				"create_time" =>  date("Y-m-d H:i:s"),
				"update_time" => time()
			);
			$users_model->add($result);
		}
		//处理验证码逻辑
		$vertify = mt_rand(999,10000);
		$setKey = "TONGYISHIDAI.CN";
		$expire = 1800;//单位为秒
		$key        =  md5($setKey);
        $code       =  md5($vertify);
        $secode     =  array();
        $secode['verify_code'] = $code; // 把校验码保存到session
        $secode['verify_time'] = NOW_TIME;  // 验证码创建时间
        session($key, $secode);

		$send_url = 'http://sms.pica.com/zqhdServer/sendSMS.jsp?';
	    $content = "尊敬的用户：您的验证码：".$vertify."，工作人员不会索取，请勿泄露。";
	    $send_params = array(
			'regcode' => 'JYTY-CRM-0100-JSXCPQ',
			'pwd'=>md5('54241172'),
			'phone' => $mobile,
			'CONTENT'=>iconv('UTF-8', 'gbk', $content),
			'extnum' =>'',
			'level' => 1,
			'schtime'=>'',
			'reportflag'=>'',
			'url'=>'',
			'smstype' =>4,
			'hardKEY' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'
	    );
		$sendparams = http_build_query($send_params);
		$send_url = $send_url.$sendparams;
		$res = file_get_contents($send_url);
		$res = intval($res);
		if($res==0){
			//sp_mobile_code_log($mobile, $vertify,3600);
			$this->success("短信发送成功！");
		}else{
			$this->error("短信发送失败，稍后重试！");
		}
		/**
		//调用短信接口
		$wsdl = "http://211.99.191.148/mms/services/info?wsdl";
		$client = new \SoapClient($wsdl);
		$param = array('in0'=>'JYTY-CRM-0100-JSXCPQ','in1'=>'54241172','in2'=>'同翼时代','in3'=>'同翼活动','in4'=>'','in5'=>'18703612752','in6'=>'石玉良','in7'=>'','in8'=>'','in9'=>'','in10'=>'18703612752');
		$ret = $client->register($param);
		$content1 = "尊敬的用户：您的验证码：".$vertify."，工作人员不会索取，请勿泄露。";
		if($ret->out=='0'){  
			$content = urlencode(iconv('UTF-8', 'gbk', $content1));
			$param = array('in0'=>'JYTY-CRM-0100-JSXCPQ','in1'=>'54241172','in2'=>$mobile,'in3'=>$content,'in4'=>'','in5'=>'1','in6'=>'','in7'=>'1','in8'=>'','in9'=>'4');
			$ret = $client->sendSMS($param);
			if ($ret->out=='0'){  
				exit(json_encode(array('status'=>1,'msg'=>"短信发送成功！")));
			}else{  
				exit(json_encode(array('status'=>0,'msg'=>"短信发送失败，稍后重试！")));
			}
		}else{
			exit(json_encode(array('status'=>0,'msg'=>"短信发送失败，稍后重试！")));
		}
		**/
	}
	//手机短信验证码发送
	public function sendMobbile_msg(){
		$mobile = I("post.mobile");
		$type = I("post.type","");
		if(empty($mobile)){
			$this->error("手机号不能为空！");
		}
		if(strlen($mobile)!=11){
			$this->error("请输入11位手机号！");
		}
		$where['user_login']=$mobile;
	    $users_model=M("Users");
	    $result = $users_model->where($where)->find();
		if($type=="register"){
			if(!empty($result)){
				$this->error("此手机号已注册！");
			}
		}else{
			if(empty($result)){
				$this->error("此手机号未注册！");
			}
		}
		$vertify = mt_rand(999,10000);
		$setKey = "TONGYISHIDAI.CN";
		$expire = 1800;//单位为秒
		$key        =  md5($setKey);
        $code       =  md5($vertify);
        $secode     =  array();
        $secode['verify_code'] = $code; // 把校验码保存到session
        $secode['verify_time'] = NOW_TIME;  // 验证码创建时间
        session($key, $secode);

		$send_url = 'http://sms.pica.com/zqhdServer/sendSMS.jsp?';
	    $content = "尊敬的用户：您的验证码：".$vertify."，工作人员不会索取，请勿泄露。";
	    $send_params = array(
			'regcode' => 'JYTY-CRM-0100-JSXCPQ',
			'pwd'=>md5('54241172'),
			'phone' => $mobile,
			'CONTENT'=>iconv('UTF-8', 'gbk', $content),
			'extnum' =>'',
			'level' => 1,
			'schtime'=>'',
			'reportflag'=>'',
			'url'=>'',
			'smstype' =>4,
			'hardKEY' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'
	    );
		$sendparams = http_build_query($send_params);
		$send_url = $send_url.$sendparams;
		$res = file_get_contents($send_url);
		$res = intval($res);
		if($res==0){
			//sp_mobile_code_log($mobile, $vertify,3600);
			$this->success("短信发送成功！");
		}else{
			$this->error("短信发送失败，稍后重试！");
		}

		/**
		//调用短信接口
		$wsdl = "http://211.99.191.148/mms/services/info?wsdl";
		$client = new \SoapClient($wsdl);
		$param = array('in0'=>'JYTY-CRM-0100-JSXCPQ','in1'=>'54241172','in2'=>'同翼时代','in3'=>'同翼活动','in4'=>'','in5'=>'18703612752','in6'=>'石玉良','in7'=>'','in8'=>'','in9'=>'','in10'=>'18703612752');
		$ret = $client->register($param);
		$content1 = "尊敬的用户：您的验证码：".$vertify."，工作人员不会索取，请勿泄露。";
		if ($ret->out=='0'){  
			$content = urlencode(iconv('UTF-8', 'gbk', $content1));
			$param = array('in0'=>'JYTY-CRM-0100-JSXCPQ','in1'=>'54241172','in2'=>$mobile,'in3'=>$content,'in4'=>'','in5'=>'1','in6'=>'','in7'=>'1','in8'=>'','in9'=>'4');
			$ret = $client->sendSMS($param);
			if ($ret->out=='0'){  
				exit(json_encode(array('status'=>1,'msg'=>"短信发送成功！")));
			}else{  
				exit(json_encode(array('status'=>0,'msg'=>"短信发送失败，稍后重试！")));
			}
		}else{
			exit(json_encode(array('status'=>0,'msg'=>"短信发送失败，稍后重试！")));
		}
		**/
	}
	/**
	*发送短信验证码
	**/
	public function sendMsg(){
		$mobile = I("post.mobile");
		$bie = I("post.bie");
		if(empty($mobile)){
			exit(json_encode(array('status'=>0,'msg'=>"手机号不能为空！")));
		}
		if(strlen($mobile)!=11){
			exit(json_encode(array('status'=>0,'msg'=>"请输入11位手机号！")));
		}
		//判断手机号是否已注册
		$where['mobile']=$mobile;
	    $users_model=M("Users");
	    $result = $users_model->where($where)->count();
		if($result&&!$bie){
			exit(json_encode(array('status'=>0,'msg'=>"此手机号已注册！")));
		}
		//if(!$result&&$bie=="zhaohui")
		if(!$result&&!$bie)
		{
			exit(json_encode(array('status'=>0,'msg'=>"此手机号未注册！")));
		}
		$vertify = mt_rand(999,10000);
		$setKey = "TONGYISHIDAI.CN";
		$expire = 1800;//单位为秒
		$key        =  md5($setKey);
        $code       =  md5($vertify);
        $secode     =  array();
        $secode['verify_code'] = $code; // 把校验码保存到session
        $secode['verify_time'] = NOW_TIME;  // 验证码创建时间
        session($key, $secode);
		$send_url = 'http://sms.pica.com/zqhdServer/sendSMS.jsp?';
	    $content = "尊敬的用户：您的验证码：".$vertify."，工作人员不会索取，请勿泄露。";
	    $send_params = array(
			'regcode' => 'JYTY-CRM-0100-JSXCPQ',
			'pwd'=>md5('54241172'),
			'phone' => $mobile,
			'CONTENT'=>iconv('UTF-8', 'gbk', $content),
			'extnum' =>'',
			'level' => 1,
			'schtime'=>'',
			'reportflag'=>'',
			'url'=>'',
			'smstype' =>4,
			'hardKEY' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'
	    );
		$sendparams = http_build_query($send_params);
		$send_url = $send_url.$sendparams;
		$res = file_get_contents($send_url);
		$res = intval($res);
		if($res==0){
			//sp_mobile_code_log($mobile, $vertify,3600);
			$this->success("短信发送成功！");
		}else{
			$this->error("短信发送失败，稍后重试！");
		}
		/**
		//调用短信接口
		$wsdl = "http://211.99.191.148/mms/services/info?wsdl";
		$client = new \SoapClient($wsdl);
		$param = array('in0'=>'JYTY-CRM-0100-JSXCPQ','in1'=>'54241172','in2'=>'同翼时代','in3'=>'同翼活动','in4'=>'','in5'=>'18703612752','in6'=>'石玉良','in7'=>'','in8'=>'','in9'=>'','in10'=>'18703612752');
		$ret = $client->register($param);
		$content1 = "尊敬的用户：您的验证码：".$vertify."，工作人员不会索取，请勿泄露。";
		if ($ret->out=='0'){  
			$content = urlencode(iconv('UTF-8', 'gbk', $content1));
			$param = array('in0'=>'JYTY-CRM-0100-JSXCPQ','in1'=>'54241172','in2'=>$mobile,'in3'=>$content,'in4'=>'','in5'=>'1','in6'=>'','in7'=>'1','in8'=>'','in9'=>'4');
			$ret = $client->sendSMS($param);
			if ($ret->out=='0'){  
				exit(json_encode(array('status'=>1,'msg'=>"短信发送成功！")));
			}else{  
				exit(json_encode(array('status'=>0,'msg'=>"短信发送失败，稍后重试！")));
			}
		}else{
			exit(json_encode(array('status'=>0,'msg'=>"短信发送失败，稍后重试！")));
		}**/
	}
	public function check_mobile_vertify(){
		$vertify = I("post.vertify");
		if(empty($vertify)){
			exit(json_encode(array('status'=>0,'msg'=>"验证码不能为空！")));
		}
		$res = $this->check_vertify($vertify);
		if($res){
			exit(json_encode(array('status'=>1,"验证码校验成功！")));
		}else{
			exit(json_encode(array('status'=>0,"验证码校验失败！")));
		}
	}
	public function check_vertify($code){
		$key = md5("TONGYISHIDAI.CN");
		//echo $key;
        // 验证码不能为空
        $secode = session($key);
		//echo "xxxdddffff";
		//print_r($secode,true);
		var_dump($secode);
		//die();
        if(empty($code) || empty($secode)) {
            return false;
        }
        // session 过期
        if(NOW_TIME - $secode['verify_time'] >1800) {
            session($key, null);
            return false;
        }

        if(md5($code) == $secode['verify_code']) {
            $this->reset && session($key, null);
            return true;
        }
        return false;
	}
    public function index() {
    	
    	$length=4;
    	if (isset($_GET['length']) && intval($_GET['length'])){
    		$length = intval($_GET['length']);
    	}
    	
    	//设置验证码字符库
    	$code_set="";
    	if(isset($_GET['charset'])){
    		$code_set= trim($_GET['charset']);
    	}
    	
    	$use_noise=1;
    	if(isset($_GET['use_noise'])){
    		$use_noise= intval($_GET['use_noise']);
    	}
    	
    	$use_curve=1;
    	if(isset($_GET['use_curve'])){
    		$use_curve= intval($_GET['use_curve']);
    	}
    	
    	$font_size=25;
    	if (isset($_GET['font_size']) && intval($_GET['font_size'])){
    		$font_size = intval($_GET['font_size']);
    	}
    	
    	$width=0;
    	if (isset($_GET['width']) && intval($_GET['width'])){
    		$width = intval($_GET['width']);
    	}
    	
    	$height=0;
    		
    	if (isset($_GET['height']) && intval($_GET['height'])){
    		$height = intval($_GET['height']);
    	}
    	
    	/* $background="";
    	if (isset($_GET['background']) && trim(urldecode($_GET['background'])) && preg_match('/(^#[a-z0-9]{6}$)/im', trim(urldecode($_GET['background'])))){
    		$background=trim(urldecode($_GET['background']));
    	} */
    	//TODO ADD Backgroud param!
    	
    	$config = array(
	        'codeSet'   =>  !empty($code_set)?$code_set:"2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY",             // 验证码字符集合
	        'expire'    =>  1800,            // 验证码过期时间（s）
	        'useImgBg'  =>  false,           // 使用背景图片 
	        'fontSize'  =>  !empty($font_size)?$font_size:25,              // 验证码字体大小(px)
	        'useCurve'  =>  $use_curve===0?false:true,           // 是否画混淆曲线
	        'useNoise'  =>  $use_noise===0?false:true,            // 是否添加杂点	
	        'imageH'    =>  $height,               // 验证码图片高度
	        'imageW'    =>  $width,               // 验证码图片宽度
	        'length'    =>  !empty($length)?$length:4,               // 验证码位数
	        'bg'        =>  array(243, 251, 254),  // 背景颜色
	        'reset'     =>  true,           // 验证成功后是否重置
    	);
    	$Verify = new \Think\Verify($config);
    	$Verify->entry();
    }
    

}

