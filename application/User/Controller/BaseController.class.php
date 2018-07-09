<?php
namespace User\Controller;
use Common\Controller\HomebaseController;
/**
 * 会员基本操作类
 */
class BaseController extends HomebaseController {
	
	/**
	 * 登录
	 */
	public function login()
	{
		// if (empty($this->checkAuthentication())) {
  //   		$this->forceAuthentication();
  //   	} else {
    		if (sp_is_user_login()) {

    			redirect(__ROOT__."/");
    		} else {

    			//$mobile = $this->get_cas_user();
    			if (!$this->check_user_exist($mobile)) {
    				echo "string";die;
    				$this->user_register($mobile);
    			}

    			$this->user_login($mobile);
    			redirect(__ROOT__."/");
    		}
    	//}
	}

	/**
	 * 退出
	 */
	public function logout()
	{
		session("user",null);//只有前台用户退出
		session("is_notice",null);//只有前台用户退出
		setcookie("userInfo", "", time()-3600,"/");
		//$this->cas_logout();
	}
   
}