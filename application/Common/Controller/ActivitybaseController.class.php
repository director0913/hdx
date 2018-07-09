<?php
namespace Common\Controller;
use Common\Controller\HomebaseController;
use Com\Wechat;
use Com\WechatAuth;
class ActivitybaseController extends HomebaseController{
	protected $user_model;
	protected $user;
	protected $userid;

	protected $activity;
	protected $activity_params;
	protected $activity_user;
	protected $activity_friend;

	function _initialize() {
		parent::_initialize();
		$this->activity = D("Common/Activity");
		$this->activity_params = D("Common/ActivityParams");
		$this->activity_user = D("Common/ActivityUser");
		$this->activity_friend = D("Common/ActivityFriend");
        $aid = I("get.id");
        // 检查应用是否过期
//        $activity_info = $this->activity->field('endtime')->where('id = '.$aid)->find();
//        if (!empty($activity_info['endtime']) && $activity_info['endtime'] < time()) {
//            remind();
//        }
		$wechat = new WechatAuth(C('WX_APPID'),C('WX_APPSECRET'));
		$signPackage = $wechat->GetSignPackage();
		$signPackage['appid'] = C('WX_APPID');
		$this->assign("signPackage",$signPackage );
		$this->assign("REMOTE_UPLOAT_PATH",C('REMOTE_UPLOAT_PATH'));
		$updateshare_url = 'http://'.$_SERVER['HTTP_HOST'].U('portal/mjoin/update_acshare', array('id' => $aid));
		$this->assign('update_url',$updateshare_url);
	}
	/**
	* 入口文件处理微信自定义入口
	**/
	public function entry($url){
		//return true;
		$user = session('userInfo');
		if(empty($user)){
			$wechat = new WechatAuth(C('WX_APPID'),C('WX_APPSECRET'));
			$code = $_GET['code'];
			if($code){
				try{
					$token = $wechat->getAccessToken('code',$code);
					$userInfo = $wechat->getUserInfo($token);
					session('userInfo',$userInfo);
					$aid = I("get.id");
					$this->activity->where(array("id"=>$aid))->setInc("hits");
				}catch(Exception $e){
					echo $url;die();
				}
			}else{
				$codeUrl = $wechat->getRequestCodeURL($url,1);
				header("Location:$codeUrl");
				die();
			}
		}else{
			$aid = I("get.id");
			$this->activity->where(array("id"=>$aid))->setInc("hits");
		}
	}
	public function entry1($url){
		//return true;
		$user = session('userInfo');
		if(empty($user)){
			$wechat = new WechatAuth(C('WX_APPID'),C('WX_APPSECRET'));
			$code = $_GET['code'];
			if($code){
				$token = $wechat->getAccessToken('code',$code);
				$userInfo = $wechat->getUserInfo($token);
				session('userInfo',$userInfo);
			}else{
				$codeUrl = $wechat->getRequestCodeURL($url,1);
				header("Location:$codeUrl");
				die();
			}
		}
	}


	/**
	* $id 活动id  公共函数定义区根据情况进行抽象
	* 查询活动
	***/
	public function findActivity($id=0){
		return $this->activity->where(array("id"=>$id))->find();
	}
	/**
	* $id 活动id
	* 删除活动
	*/
	public function deleteActivity($id=0){
		return $this->activity->where(array("id"=>$id))->save(array("valid"=>0));
	}
	/**
	*@param string $tag 查询标签，以字符串方式传入,例："ids:1,2;field:thumb,name,description,title;limit:0,8;order:id asc,createtime desc;where:id>0;"
	* 查询发布者发布的营销活动
	*/
	public function selectActivity($tag){
		$where=array();
		$tag=sp_param_lable($tag);
		$field = !empty($tag['field']) ? $tag['field'] : '*';
		$limit = !empty($tag['limit']) ? $tag['limit'] : '';
		$order = !empty($tag['order']) ? $tag['order'] : 'id';
		//根据参数生成查询条件
		$where['valid'] = array('eq',1);
		if (isset($tag['ids'])) {
			$where['term_id'] = array('in',$tag['ids']);
		}
		if (isset($tag['where'])) {
			$where['_string'] = $tag['where'];
		}
		return $this->activity->field($field)->where($where)->order($order)->limit($limit)->select();
	}
	public function sp_get_activity_params($aid)
	{
		$params = $this->activity_params->where("aid=$aid")->select();
		return array_column($params, 'value', 'field');
	}
}