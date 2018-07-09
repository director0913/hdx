<?php

/**
 * 会员中心
 */
namespace User\Controller;
use Common\Controller\MemberbaseController;
class ProfileController extends MemberbaseController {
	
	function _initialize(){
		parent::_initialize();
	}
	
    //编辑用户资料
	public function edit() {
		$this->assign($this->user);
    	$this->display();
    }
	//更新session提醒
    public function update_session(){
		session("is_notice",1);
		$this->success("消息提醒成功！");
	}
    public function edit_post() {
		
    	if(IS_POST){
    		$_POST['id']=$this->userid;
			$_POST['update_time'] = time();
			$_POST['item'] = implode(",",$_POST['item']);
			//var_dump($_POST,true);
			//die();
    	//	if ($this->users_model->field('id,user_nicename,user_pass,mobile')->create()) {
			if ($this->users_model->create()) {
				if ($this->users_model->save()!==false) {
					$this->user=$this->users_model->find($this->userid);
					sp_update_current_user($this->user);
					$Url=$_SERVER['HTTP_REFERER'];
					$this->success("保存成功！",U("portal/activity/index"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->users_model->getError());
			}
    	}
    	
    }
    
    public function password() {
		$uid=get_current_userid();
		$form=D('form');
		$activity_num=$form->where('uid='.$uid)->order('id asc')->count();
		$this->assign('activity_num',$activity_num);
		
		$users = D('users'); 
		$users_info=$users->where('id='.$uid)->find();
		if(empty($users_info)){
			$this->error("暂时没有信息！");
		}
		$this->assign('users_info',$users_info);
		//查询应用数量。
		$where = 'uid='.$uid.' and valid = 1';
		$app_num=D('activity')->where($where)->count();
		$this->assign('app_num',$app_num);
		
    	$this->display();
    }
    
    public function password_post() {
    	if (IS_POST) {
    		if(empty($_POST['old_password'])){
    			$this->error("原始密码不能为空！");
    		}
    		if(empty($_POST['password'])){
    			$this->error("新密码不能为空！");
    		}
    		$admin=$this->users_model->where(array('id'=>$this->userid))->find();
    		$old_password=$_POST['old_password'];
    		$password=$_POST['password'];
    		if(sp_compare_password($old_password, $admin['user_pass'])){
    			if($_POST['password']==$_POST['repassword']){
    				if(sp_compare_password($password, $admin['user_pass'])){
    					$this->error("新密码不能和原始密码相同！");
    				}else{
    					$data['user_pass']=sp_password($password);
    					$data['id']=$this->userid;
    					$r=$this->users_model->save($data);
    					if ($r!==false) {
    						$this->success("修改成功！");
    					} else {
    						$this->error("修改失败！");
    					}
    				}
    			}else{
    				$this->error("密码输入不一致！");
    			}
    	
    		}else{
    			$this->error("原始密码不正确！");
    		}
    	}
    	 
    }
    
    
    function bang(){
    	$oauth_user_model=M("OauthUser");
    	$oauths=$oauth_user_model->where(array("uid"=>$this->userid))->select();
    	$new_oauths=array();
    	foreach ($oauths as $oa){
    		$new_oauths[strtolower($oa['from'])]=$oa;
    	}
    	$this->assign("oauths",$new_oauths);
    	$this->display();
    }
    
    function avatar(){
		$this->assign($this->user);
    	$this->display();
    }
    
    function avatar_upload(){
    	$config=array(
    			'rootPath' => './'.C("UPLOADPATH"),
    			'savePath' => './avatar/',
    			'maxSize' => 512000,//500K
    			'saveName'   =>    array('uniqid',''),
    			'exts'       =>    array('jpg', 'png', 'jpeg'),
    			'autoSub'    =>    false,
    	);
    	$driver_type = sp_is_sae()?"Sae":'Local';//TODO 其它存储类型暂不考虑
    	$upload = new \Think\Upload($config,$driver_type);//
    	$info=$upload->upload();
    	//开始上传
    	if ($info) {
    	//上传成功
    	//写入附件数据库信息
    		$first=array_shift($info);
    		$file=$first['savename'];
    		$_SESSION['avatar']=$file;
    		$this->ajaxReturn(sp_ajax_return(array("file"=>$file),"上传成功！",1),"AJAX_UPLOAD");
    	} else {
    		//上传失败，返回错误
    		$this->ajaxReturn(sp_ajax_return(array(),$upload->getError(),0),"AJAX_UPLOAD");
    	}
    }
    
    function avatar_update(){
    	if(!empty($_SESSION['avatar'])){
    		$targ_w = intval($_POST['w']);
    		$targ_h = intval($_POST['h']);
    		$x = $_POST['x'];
    		$y = $_POST['y'];
    		$jpeg_quality = 90;
    		
    		$avatar=$_SESSION['avatar'];
    		$avatar_dir=C("UPLOADPATH")."avatar/";
    		if(sp_is_sae()){//TODO 其它存储类型暂不考虑
    			$src=C("TMPL_PARSE_STRING.__UPLOAD__")."avatar/$avatar";
    		}else{
    			$src = $avatar_dir.$avatar;
    		}
    		
    		$avatar_path=$avatar_dir.$avatar;
    		
    		
    		if(sp_is_sae()){//TODO 其它存储类型暂不考虑
    			$img_data = sp_file_read($avatar_path);
    			$img = new \SaeImage();
    			$size=$img->getImageAttr();
    			$lx=$x/$size[0];
            	$rx=$x/$size[0]+$targ_w/$size[0];
            	$ty=$y/$size[1];
            	$by=$y/$size[1]+$targ_h/$size[1];
    			
    			$img->crop($lx, $rx,$ty,$by);
    			$img_content=$img->exec('png');
    			sp_file_write($avatar_dir.$avatar, $img_content);
    		}else{
    			$image = new \Think\Image();
    			$image->open($src);
    			$image->crop($targ_w, $targ_h,$x,$y);
    			$image->save($src);
    		}
    		
    		$result=$this->users_model->where(array("id"=>$this->userid))->save(array("avatar"=>$avatar));
    		$_SESSION['user']['avatar']=$avatar;
    		if($result){
    			$this->success("头像更新成功！");
    		}else{
    			$this->error("头像更新失败！");
    		}
    		
    	}
    }
    public function do_avatar() {
		$imgurl=I('post.imgurl');
		//去'/'
		$imgurl=str_replace('/','',$imgurl);
		$old_img=$this->user['avatar'];
		$this->user['avatar']=$imgurl;
		$res=$this->users_model->where(array("id"=>$this->userid))->save($this->user);		
		if($res){
			//更新session
			session('user',$this->user);
			//删除旧头像
			sp_delete_avatar($old_img);
		}else{
			$this->user['avatar']=$old_img;
			//删除新头像
			sp_delete_avatar($imgurl);
		}
		$this->ajaxReturn($res);
	}       
}