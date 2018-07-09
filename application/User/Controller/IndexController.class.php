<?php

/**
 * 会员注册登录
 */
namespace User\Controller;
use Common\Controller\HomebaseController;
class IndexController extends HomebaseController {
    //登录
	public function index() {
		$id=I("get.id");
		
		$users_model=M("Users");
		
		$user=$users_model->where(array("id"=>$id))->find();
		
		if(empty($user)){
			$this->error("查无此人！");
		}
		$this->assign($user);
		$this->display(":index");

    }
    public function test_login(){
		$user_info  = json_decode($_COOKIE['userInfo'],true);
		if($user_info){
			$this->ajaxReturn(array("status"=>1,"user"=>$user_info));
		}else{
			$this->ajaxReturn(array("status"=>0,"info"=>"此用户未登录！"));
		}
	}
    function is_login(){
		/**$user_info  = json_decode($_COOKIE['userInfo'],true);
		if($user_info){
			$this->ajaxReturn(array("status"=>1,"user"=>$user_info));
		}else{
			if(sp_is_user_login()){
				$this->ajaxReturn(array("status"=>1,"user"=>sp_get_current_user()));
			}else{
				$this->ajaxReturn(array("status"=>0,"info"=>"此用户未登录！"));
			}
			//$this->ajaxReturn(array("status"=>0,"info"=>"此用户未登录！"));
		}**/
		
    	if(sp_is_user_login()){
    		$this->ajaxReturn(array("status"=>1,"user"=>sp_get_current_user()));
    	}else{
    		$this->ajaxReturn(array("status"=>0,"info"=>"此用户未登录！"));
    	}
    }

    //退出
    public function logout(){
    	$ucenter_syn=C("UCENTER_ENABLED");
    	$login_success=false;
    	if($ucenter_syn){
    		include UC_CLIENT_ROOT."client.php";
    		echo uc_user_synlogout();
    	}
    	session("user",null);//只有前台用户退出
		session("is_notice",null);//只有前台用户退出
		setcookie("userInfo", "", time()-3600,"/");

		$flag=I('get.flag',0,'intval');
		if($flag!=1)
		{
			redirect(__ROOT__."/");
		}
		else
		{
			//$this->success("退出",U('portal/mindex/index'));
			//redirect(__ROOT__."index.php/portal/mindex/index");
			$url=U('portal/mindex/index');
			header("Location:".$url);			
		}
    	
    }
	
	public function logout2(){
    	$ucenter_syn=C("UCENTER_ENABLED");
    	$login_success=false;
    	if($ucenter_syn){
    		include UC_CLIENT_ROOT."client.php";
    		echo uc_user_synlogout();
    	}
		if(isset($_SESSION["user"])){
		$referer=$_SERVER["HTTP_REFERER"];
			session("user",null);//只有前台用户退出
			$_SESSION['login_http_referer']=$referer;
			$this->success("退出成功！",__ROOT__."/");
		}else{
			redirect(__ROOT__."/");
		}
    }
}
