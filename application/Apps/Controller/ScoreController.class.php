<?php

/**
 * 大转盘助力活动
 */
namespace Apps\Controller;
use Common\Controller\ActivitybaseController; 
class ScoreController extends ActivitybaseController {
	protected $jiangpai;
	protected $activity;
	protected $activity_params;
	protected $activity_user;
	protected $activity_user_data;
	protected $activity_user_prize;
	protected $activity_friend;
	protected $share_data;//定义分享数据
	
	protected $form_model;
	function _initialize(){
		parent::_initialize();
		$this->activity = D("Activity");
		$this->activity_params = D("ActivityParams");
		$this->activity_user = D("ActivityUser");
		$this->activity_user_data = D("ActivityUserData");
		$this->activity_friend = D("ActivityFriend");
		$this->activity_user_prize = D("ActivityUserPrize");
		$this->jiangpai = D("Ad");
		$this->chengyu = D("WeidiomsChengyu");
		
		$this->form_model = D("Common/Form");
	}
    public function index()
	{
		set_wechat_info();
	  	// 获取活动id。
	  	$aid = I("get.id",0,"intval");
	  	// 看是否是分享过来的页面。
		$uid = I('get.uid',0,'intval');
	  	// 定义分享链接。
	  	$url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/score/index', ['id' => $aid, 'uid' => $uid]);
	  	// 检查浏览器及获取公众号信息。
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('score', 'index', $aid, $uid);
		} else {
			$this->entry($url);
		}
	  	$this->add_userinfo($aid);
	  	// 获取扩展字段。
	  	$item = sp_get_activity($aid);
		if (!$item) {
			$this->error('活动不存在');
		}
	  	// 活动创建者
	  	$activity_list = $this->get_activity_list($item['uid'], $item['type']);
	  	$this->assign('item',$item);
	  	$this->assign('activity_list',$activity_list);

	  	$this->share_data = [
			'title'	=> $item['share_title'],
			'desc' => $item['share_des'],
			'url' => $url,
			'imgUrl' =>sp_get_asset_upload_path($item['share_img'],true)
		];
	  	$this->assign("share", $this->share_data);
	  
		if ($uid>0) {
			$his_info = $this->activity_user->where('id = '.$uid)->find();
			if ($his_info) {
				$item = sp_get_activity($aid);
				$this->assign('item', $item);
				$this->assign('his_info', $his_info);
				$url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/score/index',['id' => $aid, 'uid' => $uid]);
				$this->share_data = array(
					'title'	=> $item['share_title'],
					'desc' => $item['share_des'],
					'url' => $url,
					'imgUrl' => sp_get_asset_upload_path($item['share_img'], true)
				);
				$this->assign("share",$this->share_data);
				$this->display('result');
			}
			else
			{
				$this->display();
			}
	  	} else {
			$this->display();
	  	}
}
	
/* 显示这个用户创建所有成绩查询活动 */
public function get_activity_list($uid, $type)
{
	$activity_list = $this->activity->where(['uid' => $uid, 'type' => $type, 'valid' => 1])->select();
	return $activity_list;
}
/*真正开始查询成绩*/
public function result()
{
	$kaoshi = I('post.type', 0, 'intval');
	$username = I('post.name');
	$mobile = I('post.tel');
	$result = $this->activity_user->where(['aid' => $kaoshi, 'username' => $username, 'mobile' => $mobile])->find();
	$item = sp_get_activity($kaoshi);
	$this->assign('item', $item);
	$this->assign('result', $result);

	$url = C('DOMAIN_NAME').U('apps/score/index',array('id'=>$kaoshi,'uid'=>$result['id']));
	$this->share_data = [
		'title'	=>$item['share_title'],
		'desc' =>$item['share_des'],
		'url' => $url,
		'imgUrl' => sp_get_asset_upload_path($item['share_img'], true)
	];
	$this->assign("share",$this->share_data);
	
	$this->display('result');
}
	
	
	
	
	
	/*测试首页预览
	*/
	public function yuindex() {
      //$this->display("/Thimes/weishare/yuindex");
	  $this->display('/Weicheer/guaka');
    }
	/*测试分享预览
	*/
	public function yufenxiang() {
      $this->display("/Thimes/weishare/yufenxiang");
    }
	/*获取用户列表*/
	public function get_user_list($aid)
	{
		$user_list=D('WeicircleContent')->where('aid='.$aid)->select();
		//添加对应的评论信息
		foreach($user_list as $key=>$tmp)
		{
			//$tmp['id']
			$msg_list=D('WeicircleMsg')->where('cid='.$tmp['id'])->select();
			$user_list[$key]['msg_list']=$msg_list;
		}
		return $user_list;	
	}
	
	
	
	//注册用户
	public function regist()
	{
		$name=I('post.name');
		$mobile=I('post.mobile');
		$aid=I('get.id',0,'intval');
		//$res['msg']=$name.">>>".$mobile.">>>".$aid;
		//$this->ajaxReturn($res);
		$user_info=session('userInfo');	
		$user=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();	
		//再次获取用户基本信息
		$from_user = $user_info['openid'];
		$avatar=$user_info['headimgurl'];
		//获取完毕
		$item = sp_get_activity($aid);
		$now=time();
		if($now>$item['endtime']){
			$res['status']=-2;
			$res['msg']="活动已结束";	
			$this->ajaxReturn($res);
		}
		if($now<$item['begintime']){
			$res['status']=-1;
			$res['msg']="活动还未开始";	
			$this->ajaxReturn($res);
		}
		if(!empty($user['mobile'])){
			$res['status']=0;
			$res['msg']="您已报过名了，不能重复报名！";	
			$this->ajaxReturn($res);
		}
		//添加姓名，电话
		$user_data = array(
							'username' => $name,
							'mobile' => $mobile,
						);
		$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->save($user_data);
		$res['status']=1;
		$res['msg']='ok';
		$this->ajaxReturn($res);
	}

	/*我的喝彩记录*/
	public function morefriend()
	{
		$aid=I('get.id',0,'intval');
		$page_f=I('post.page_f',0,'intval');
		$size=3;
		$user_info=session('userInfo');
		$user=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
		$uid=$user['id'];
		
		$list =$this->activity_friend->where(array('aid'=>$aid,'uid'=>$uid))->order('update_time desc')->limit(($page_f-1)*$size.",".$size)->select();
		if($list)
		{
			$res['status']=1;	
			$res['list']=$list;
		}
		else
		{
			$res['status']=-1;	
		}
		
		$this->ajaxReturn($res);
	}
	
	/*加载排行榜*/
	public function moreuser()
	{
		$aid=I('get.id',0,'intval');
		$page_u=I('post.page_u',0,'intval');
		$size=1;
	    $list=$this->activity_user->where('aid='.$aid.' and mobile is not null')->order('total_num desc')->limit(($page_u-1)*$size.",".$size)->select();
		if($list)
		{
			$res['status']=1;	
			$res['list']=$list;
		}
		else
		{
			$res['status']=-1;	
		}
		$this->ajaxReturn($res);
	}

	/*记录中奖用户信息*/
	public function getPrize()
	{
		$aid=I('get.id',0,'intval');
		$data['username']=I('post.name');
		$data['mobile']=I('post.mobile');
		$zhi=cookie('cheerchina_jiang');
		//根据奖值获取奖品。
		$params = $this->activity_params->where("aid=$aid and (field='prize".$zhi."_name' or field='prize".$zhi."_type' or field='prize".$zhi."_thumb')")->select();
		$jiang=array_column($params, 'value', 'field');
		$user_info=session('userInfo');
		if($user_info['openid'])
		{
			$res['status']=1;
			//添加中奖信息
			$prize_data = array("aid"=>$aid,"uid"=>0,"from_user"=>$user_info['openid'],"username"=>$data['username'],"mobile"=>$data['mobile'],"name"=>$jiang['prize'.$zhi.'_name'],"type"=>$jiang['prize'.$zhi.'_type'],"thumb"=>$jiang['prize'.$zhi.'_thumb'],'createtime'=>time());
			if(!$this->activity_user_prize->add($prize_data))
			{
				$res['status']=-1;
				$res['msg']="添加中奖信息失败";
				$this->ajaxReturn($res);
			}
			$res['jiang']=array('name'=>$jiang['prize'.$zhi.'_name'],'type'=>$jiang['prize'.$zhi.'_type'],'thumb'=>$jiang['prize'.$zhi.'_thumb']);
		}
		else
		{
			$res['status']=-1;
			$res['msg']="添加中奖用户失败";
		}
		$this->ajaxReturn($res);
	}
	/*获取头部信息*/
	public function get_activity_headinfo()
	{
		$uid=get_current_userid();
		$where = 'uid='.$uid.' and valid = 1';
		//查询活动数量。
		$activity_num=$this->form_model->where($where)->count();
		//查询应用数量。
		$app_num=$this->activity->where($where)->count();
		$users = D('users'); 
		$users_info=$users->where('id='.$uid)->find();
		//获取参数信息。
		$aid=I('get.id',0,'intval');
		$userid=I('get.userid',0,'intval');
		//获取活动信息。
		$activity=$this->activity->where('id='.$aid)->find();
		
		return $result=array($activity_num,$app_num,$users_info,$activity);
	}
	
	
	
	/*后台     打开展示成绩页面*/
	public function show_score()
	{
		$result=$this->get_activity_headinfo();
		$this->assign('activity_num',$result[0]);
		$this->assign('app_num',$result[1]);
		$this->assign('users_info',$result[2]);
		$this->assign('activity',$result[3]);
		
		$aid=I('get.id',0,'intval');
		//查询已有成绩
		$score_list=$this->activity_user->where('aid='.$aid." and username <>'' ")->select();
		$this->assign('score_list',$score_list);
		$this->display('show_score');
	}
	/*后台     打开添加成绩页面*/
	public function score_add()
	{
		$result=$this->get_activity_headinfo();
		$this->assign('activity_num',$result[0]);
		$this->assign('app_num',$result[1]);
		$this->assign('users_info',$result[2]);
		$this->assign('activity',$result[3]);
		
		
		$aid=I('get.id',0,'intval');
		//查询已有成绩
		//$score_list=$this->activity_user->where('aid='.$aid)->select();
		//$this->assign('score_list',$score_list);
		$this->display('score_add');
	}
	/*后台     完成添加成绩页面*/
	public function score_post()
	{
		$aid=I('get.id',0,'intval');
		//获取所有正确答案。
		$nicknamels = I("post.nickname");
		//获取所有问题。
		$backnicknamels = I("post.backnickname");
		//获取所有选项。
		$contentls = I("post.content");
		//遍历id数组。
		$youcuo=false;
		for($i=0;$i<count($nicknamels);$i++)
		{
				$data=array(
						'aid'=>$aid,
						'username'=>$nicknamels[$i],            //表示姓名
						'mobile'=>$backnicknamels[$i],          //表示手机号或身份证号
						'remark'=>$contentls[$i],              //表示成绩
						'createtime'=>time());
				//添加数据。
				$res=$this->activity_user->add($data);
				if(!$res)
				{
					$youcuo=true;
				}				
		}
		$result['status']=1;
		$result['msg'].=$youcuo?"":"部分数据保存失败，请到在当前页面检查并重试。";
		$this->ajaxReturn($result);
	}
	/*编辑单个人的成绩*/
	public function score_one_post()
	{
		$uid=I('post.uid',0,'intval');
		$username = I("post.username");
		$mobile = I("post.mobile");
		$score = I("post.score");
		//$res['msg']='uid:'.$uid.'username'.$username.'mobile'.$mobile.'score'.$score;
		//更新
		$user_info=array(
				'username'=>$username,
				'mobile'=>$mobile,
				'remark'=>$score,
				);
		$this->activity_user->where('id='.$uid)->save($user_info);
		$res['status']=1;
		$this->ajaxReturn($res);
	}
	/*删除某一个人的成绩*/
	public function score_delete()
	{
		$id=I('post.id',0,'intval');
		$this->activity_user->where('id='.$id)->delete();
		$res['status']=1;
		$this->ajaxReturn($res);
	}
	/*
	public function score_post()
	{
		$aid=I('get.id',0,'intval');
		//获取所有id。
		$idls = I("post.msg_id");
		//获取所有正确答案。
		$nicknamels = I("post.nickname");
		//获取所有问题。
		$backnicknamels = I("post.backnickname");
		//获取所有选项。
		$contentls = I("post.content");
		
		//将id拼接成字符串。
		$idstr=count($idls)>0?implode(",",$idls):0;
		//删除不存在的数据记录。
		$this->activity_user->where("aid=".$aid." and id not in(".$idstr.")")->delete();
		//$result['msg']="aid=".$aid." and id not in(".$idstr.")";
		//遍历id数组。
		$youcuo=false;
		for($i=0;$i<count($idls);$i++)
		{
			$data=array('username'=>$nicknamels[$i],            //表示姓名
						'mobile'=>$backnicknamels[$i],          //表示手机号或身份证号
						'remark'=>$contentls[$i],              //表示成绩
						'createtime'=>time());
			if(intval($idls[$i])>0)
			{
				//修改数据。
				$res=$this->activity_user->where(array('id'=>$idls[$i]))->save($data);
			}
			else
			{
				$data['aid']=$aid;
				//添加数据。
				$res=$this->activity_user->add($data);
			}
			if(!$res)
			{
				$youcuo=true;
			}
		}
		$result['status']=1;
		$result['msg'].=$youcuo?"":"部分数据保存失败，请到在当前页面检查并重试。";
		$this->ajaxReturn($result);
	}
	
	
	*/
	/*后台     打开成绩编辑页面*/
	public function score_edit()
	{
		$result=$this->get_activity_headinfo();
		$this->assign('activity_num',$result[0]);
		$this->assign('app_num',$result[1]);
		$this->assign('users_info',$result[2]);
		$this->assign('activity',$result[3]);
		
		$aid=I('get.id',0,'intval');
		$uid=I('get.uid',0,'intval');
		//查询已有成绩
		$score_info=$this->activity_user->where('id='.$uid)->find();
		$this->assign('score_info',$score_info);
		$this->display('score_edit');
	}
	
	/*导入测试*/
	public function score_import()
	{
		$result=$this->get_activity_headinfo();
		$this->assign('activity_num',$result[0]);
		$this->assign('app_num',$result[1]);
		$this->assign('users_info',$result[2]);
		$this->assign('activity',$result[3]);

		
		$this->display('score_import');
	}
	/*接收上传文件*/
	public function score_jieshou()
	{
		  $aid=I('post.jiluid',0,'intval');
		  if (! empty ( $_FILES ['file_stu']['name'] ))
		 {
			$tmp_file = $_FILES ['file_stu']['tmp_name'];
			//echo $tmp_file.">>".$_FILES ['file_stu']['name'].">>".$_FILES ['file_stu']['tmp_name'];
			$file_types = explode ( ".", $_FILES ['file_stu'] ['name'] );
			$file_type = $file_types [count ( $file_types ) - 1];
			 /*判别是不是.xls文件，判别是不是excel文件*/
			if( !in_array($file_type, array('xls','xlsx')) ){
				 $this->error ( '不是Excel文件，重新上传' );
			} 
			//设置上传路径
			 $savePath = SITE_PATH . '/data/upload/';
			//以时间来命名上传的文件
			 $str = date ( 'Ymdhis' ); 
			 $file_name = $str . "." . $file_type;
			 /*是否上传成功*/
			 if (! copy ( $tmp_file, $savePath . $file_name )) 
			  {
				  $this->error ( '上传失败' );
			  }
			/*
			   *对上传的Excel数据进行处理生成编程数据,这个函数会在下面第三步的ExcelToArray类中
			  注意：这里调用执行了第三步类里面的read函数，把Excel转化为数组并返回给$res,再进行数据库写入
			*/
			//echo VENDOR_PATH;
			require_once VENDOR_PATH.'PHPExcel/PHPExcel/IOFactory.php';
			require_once VENDOR_PATH.'PHPExcel/PHPExcel.php';
			//判断文件版本，选择对应的解析文件
			if($file_type=='xlsx'){
				require_once VENDOR_PATH.'PHPExcel/PHPExcel/Reader/Excel2007.php';
				$objReader = \PHPExcel_IOFactory::createReader('Excel2007');
					}else{
				require_once VENDOR_PATH.'PHPExcel/PHPExcel/Reader/Excel5.php';
				$objReader = \PHPExcel_IOFactory::createReader('Excel5');
			}
			$objPHPExcel = $objReader->load($savePath . $file_name);
			//var_dump($objPHPExcel);
			//die();
			$sheet = $objPHPExcel->getSheet(0);// 读取第一个工作表(编号从 0 开始) 
			$highestRow = $sheet->getHighestRow(); // 取得总行数
			$highestColumn = $sheet->getHighestColumn(); // 取得总列数
			//echo "行数".$highestRow."列数".$highestColumn;
		   $ExamPaper_arr=array();
		   for($j=2;$j<=$highestRow;$j++)
			{
				for($k='A';$k<=$highestColumn;$k++)
				{
					//读取单元格
					$ExamPaper_arr[$j][$k]= $objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue();
				}
			}
			foreach ($ExamPaper_arr as $key => $value) 
			{
				$data['aid']=$aid;
				$data['username'] = empty($value['A'])?0:$value['A'];
				$data['mobile'] = empty($value['B'])?0:$value['B'];
				$data['remark'] = empty($value['C'])?0:$value['C'];
				$data['createtime'] = time();
				$this->activity_user->add($data);
			}
		   $url=U('apps/score/show_score',array('id'=>$aid));
		   header("Location:".$url);
		}		
}
	
	
	
	/***公共方法抽象定义区****/
	//判断当前用户是否存在，不存在则增加
	function add_userinfo($aid=0){
		$aid = I("get.id",0,"intval");
		$userInfo = session('userInfo');
		$res = $this->activity_user->where(array("aid"=>$aid,"from_user"=>$userInfo['openid']))->count();
		if(empty($res)){
			$data = array(
				'aid' => $aid,
				'from_user' => $userInfo['openid'],
				'nickname'  => $userInfo['nickname'],
				'avatar'  => $userInfo['headimgurl'],
				'createtime' => time()
			);
			$this->activity_user->add($data);
		}
	}
	/**
	* 分析活动的状态
	* $aid 活动id
	*/
	function analyze_activitys($aid){
		$activity = D("Activity")->where(array("id"=>$aid,"valid"=>1))->find();
		if(empty($activity)){
			$this->error("该活动不存在！");
		}
		$currtime = time();
		if($activity['begintime']>$currtime){
			$this->error("活动尚未开始！");
		}
		if($activity['endtime']<$currtime){
			$this->error("活动已结束！");
		}
	}
	/**
	* 分析用户的状态--分析用户的三个指标
	*/
	function sp_analyze_activity_user($user_info,$aid=0){
		//$user_info = session("userInfo");
		$from_user = $user_info['openid'];
		$activity = D("Activity")->where(array("id"=>$aid,"valid"=>1))->find();
		$user = D("ActivityUser")->where(array("aid"=>$aid,"from_user"=>$from_user))->find();
		$tday = strtotime(date("Y-m-d",time()));
		if($user['update_time']<$tday){
			$user['day_num']=0;
			D("ActivityUser")->where(array("aid"=>$aid,"from_user"=>$from_user))->save(array('day_num'=>0));
		}
		if($user['day_num']>=$activity['day_num']){ $this->error("您今日的次数已用完！"); }
		if($user['total_num']>=$activity['total_num']){ $this->error("您参与该活动的总次数已用完！"); }
		if($user['per_num']>=$activity['per_num']){ $this->error("您总次数已用完！"); }
	}
	/**
	* 分析好友的状态
	**/
	function sp_analyze_activity_friend($aid=0,$uid=0){
		$user_info = session("userInfo");
		$from_user = $user_info['openid'];
		$activity = D("Activity")->where(array("id"=>$aid,"valid"=>1))->find();
		$user = D("ActivityUser")->where(array("aid"=>$aid,"from_user"=>$from_user,"uid"=>$uid))->find();
		if($user['day_num']>=$activity['day_num']){ $this->error("您今日的次数已用完！"); }
		if($user['total_num']>=$activity['total_num']){ $this->error("您参与该活动的总次数已用完！"); }
		if($user['per_num']>=$activity['per_num']){ $this->error("您总次数已用完！"); }
	}
	
}

