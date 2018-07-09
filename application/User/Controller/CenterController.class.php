<?php

/**
 * 会员中心
 */
namespace User\Controller;
use Common\Controller\MemberbaseController;
class CenterController extends MemberbaseController {
	
	function _initialize(){
		parent::_initialize();
	}
    //会员中心
	public function index() {
		//查找会员基本信息
		$id=get_current_userid();
		$users = D('users'); 
		$users_info=$users->where('id='.$id)->find();
		if(empty($users_info)){
			$this->error("暂时没有信息！");
		}
		$this->assign('users_info',$users_info);
		$form=D('form');
		$where = 'uid='.$id.' and valid = 1';
		$activity_num = $form->where($where)->count();
		$this->assign('activity_num',$activity_num);
		//查询应用数量。
		$app_num=D('activity')->where($where)->count();
		$this->assign('app_num',$app_num);
    	$this->display(':home');
    }
	//更新会员信息
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
			$users->where('id='.$id)->save($data);
			//更改激活码的状态。
			D('Regcode')->where('id='.$regcode['id'])->save(array('status'=>1));
			$this->success("升级成功！",U('portal/activity/index',array('menu'=>3)));
		}
	}
}
