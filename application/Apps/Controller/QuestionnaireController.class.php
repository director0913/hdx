<?php

/**
 * 大转盘助力活动
 */
namespace Apps\Controller;
use Common\Controller\ActivitybaseController; 
class QuestionnaireController extends ActivitybaseController {
	protected $activity;
	protected $activity_params;
	protected $activity_user;
	protected $activity_user_data;
	protected $activity_user_prize;
	protected $questionnaire_problem;
	protected $questionnaire_user_data;
	protected $share_data;//定义分享数据
	function _initialize(){
		parent::_initialize();
		$this->activity = D("Activity");
		$this->activity_params = D("ActivityParams");
		$this->activity_user = D("ActivityUser");
		$this->activity_user_data = D("ActivityUserData");
		$this->activity_user_prize = D("ActivityUserPrize");
		$this->questionnaire_problem = D("QuestionnaireProblem");
		$this->questionnaire_user_data = D("QuestionnaireUserData");
	}
	//对个性化字段的处理。
	function save_questionnaire($aid,$yuanaid){
		$data = array(
			array("aid"=>$aid,"name"=>"学校名称","field"=>"school","value"=>I('post.school')),
		);
		sp_save_activity_params($data);
		//处理问题和答案。
		//获取所有填写的信息。
		$itemid=I('post.itemid');
		$itemtitle=I('post.itemtitle');
		$item1=I('post.item1');
		$item2=I('post.item2');	
		$item3=I('post.item3');
		if($yuanaid==$aid)
		{
			//将所有的id拼接起来
			$newitemid="0";
			for($i=0;$i<count($itemid);$i++)
			{
				if($itemid[$i]>0) $newitemid.=",".$itemid[$i];
			}
			//删除不存在的id。
			D("QuestionnaireProblem")->where('aid='.$aid.' and id not in('.$newitemid.')')->delete();
		}
		//遍历数据，如果有id就更新数据，没有id就插入数据。
		for($i=0;$i<count($itemid);$i++)
		{
			$data=array('title'=>$itemtitle[$i],
						'item1'=>$item1[$i],
						'item2'=>$item2[$i],
						'item3'=>$item3[$i]);
			if($itemid[$i]>0&&$yuanaid==$aid)
			{
				D("QuestionnaireProblem")->where('id='.$itemid[$i])->save($data);
			}
			else
			{
				$data['aid']=$aid;
				D("QuestionnaireProblem")->add($data);
			}
		}
	}
	public function index() {
		set_wechat_info();
	  $aid = I("get.id",0,"intval");
	  $url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/questionnaire/index',array('id'=>$aid));
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('questionnaire', 'index', $aid);
		} else {
			$this->entry($url);
		}
//	  $this->entry($url);
	  $cando=1;
	  $msg="";
	  $item = sp_get_activity($aid);
	  if(!$item)
	  {
		  $this->error('活动不存在');
	  }
	  //判断活动是否开始。
	  if($item['begintime']>time())
	  {
		  $cando=0;
		  $msg="活动未开始";
	  }
	  if($item['endtime']<=time())
	  {
		  $cando=0;
		  $msg="活动已结束";
	  }
	  $item['titlezhuan']=str_replace("，","<br/>",$item['title']);
	  $item['titlehuan']=str_replace("，","",$item['title']);
		$params = $this->activity_params->where("aid=$aid")->select();
		$item['params']=array_column($params, 'value', 'field');
	  /*if($cando==1)
	  {
		  $this->add_userinfo($aid);
	  }*/

	  //分享内容定义区
	  $this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' => C('DOMAIN_NAME').U('apps/questionnaire/index',array('id'=>$aid)),
			'imgUrl' =>$item['share_img']
	  );
	  $this->assign("share",$this->share_data);
	  $this->assign("item",$item);
	  //宝箱
	  $user_info=session('userInfo');
	  $user=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
	  if($user)
	  {
			$prize_list=$this->activity_user_prize->where(array('aid'=>$aid,'uid'=>$user['id']))->order('createtime desc ')->select();
			$this->assign("prize_list",$prize_list);
	  }
	  if($user['update_time']>0&&$cando==1)
	  {
		  $cando=0;
		  $msg="你已经参与过了，不能再参与了";
	  }
	  $this->assign("cando",$cando);
	  $this->assign("msg",$msg);
	  $this->assign("user",$user);
	  $this->display();
    }
	public function answer() {
	  $aid = I("get.id",0,"intval");
	  $url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/questionnaire/index',array('id'=>$aid));
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('questionnaire', 'index', $aid);
		} else {
			$this->entry($url);
		}
//	  $this->entry($url);
	  $item = sp_get_activity($aid);
	  if(!$item)
	  {
		  $this->error('活动不存在');
	  }
	  //判断活动是否开始。
	  if($item['begintime']>time())
	  {
		  $this->error('活动未开始',U('apps/questionnaire/index',array('id'=>$item['id'])));
	  }
	  if($item['endtime']<=time())
	  {
		  $this->error('活动已结束',U('apps/questionnaire/index',array('id'=>$item['id'])));
	  }
	  $item['titlezhuan']=str_replace("，","<br/>",$item['title']);
	  $item['titlehuan']=str_replace("，","",$item['title']);
	  $this->add_userinfo($aid);
	  //分享内容定义区
	  $this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' => 'http://'.$_SERVER['HTTP_HOST'].U('apps/questionnaire/index',array('id'=>$aid)),
			'imgUrl' =>$item['share_img']
	  );
	  $this->assign("share",$this->share_data);
	  $user_info=session('userInfo');
	  $user=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
	  $this->assign("user",$user);
	  if($user['update_time']>0)
	  {
		  $this->error('你已经参与过了，不能再参与了',U('apps/questionnaire/index',array('id'=>$item['id'])));
	  }
	  //获取其他字段。
	  $params = sp_get_activity_params($aid);	
	  $item['params'] = array_column($params, 'value', 'field');
	  $this->assign("item",$item);
	  //开始获取题目
	  $problemls=$this->questionnaire_problem->where(array('aid'=>$aid))->select();
	  $this->assign("problemls",$problemls);
	  $this->assign("aid",$aid);
	  $this->display("answer");
    }
	//保存用户填写的值。
	public function setchecked()
	{
		//获取活动id。
		$id=I('get.id',0,'intval');
		//获取题目信息。
		$tiidarr=I('post.tiidarr');
		//获取用户选择信息。
		$checkarr=I('post.checkarr');
		if(count($tiidarr)!=count($checkarr))
		{
			$result['status']=-1;
			$result['msg']="大侠，填完再提交吧！";
			$this->ajaxReturn($result);
		}
		//获取用户信息。
		$user_info=session('userInfo');
		$user=$this->activity_user->where(array('aid'=>$id,'from_user'=>$user_info['openid']))->find();
		if($user['update_time']>0)
		{
			$result['status']=-2;
			$result['msg']="您已经参与过了，不能再次参与了！";
			$this->ajaxReturn($result);
		}
		$data=array('aid'=>$id,
					'uid'=>$user['id']);
		//更改用户最后参与时间。
		$this->activity_user->where(array('id'=>$user['id']))->save(array('update_time'=>time()));
		for($i=0;$i<count($tiidarr);$i++)
		{
			//循环写入数据。
			$data['qid']=intval($tiidarr[$i]);
			$data['iid']=intval($checkarr[$i]);
			$this->questionnaire_user_data->add($data);
		}
		$result['status']=1;
		$result['msg']="保存信息成功";
		$this->ajaxReturn($result);
		//保存信息。
	}
	 //获取随机数判断应得的奖品是什么。
    public function getjiang()
	{
		$aid = I("get.id",0,"intval");
		$item = sp_get_activity($aid);
		$params = sp_get_activity_params($aid);	
		$params = array_column($params, 'value', 'field');
		//更新用户的当天参与情况，如果中奖了就更新中奖值。
		if(!empty(cookie('questionnaire_jiang')))
		{
			cookie('questionnaire_jiang',null);
		}
		//产生一个1-100的随机数。
		$rand=rand(1,100);
		//测试获取的奖品。
		//$rand=90;
		//如果奖品没有了，几率就为0；
		$params['prize1_rate']=$params['prize1_total']>0&&$params['prize1_only']==0?$params['prize1_rate']:0;
		$params['prize2_rate']=$params['prize2_total']>0&&$params['prize2_only']==0?$params['prize2_rate']:0;
		$params['prize3_rate']=$params['prize3_total']>0&&$params['prize3_only']==0?$params['prize3_rate']:0;
		$params['prize4_rate']=$params['prize4_total']>0&&$params['prize4_only']==0?$params['prize4_rate']:0;
		$params['prize5_rate']=$params['prize5_total']>0&&$params['prize5_only']==0?$params['prize5_rate']:0;
		$params['prize6_rate']=$params['prize6_total']>0&&$params['prize6_only']==0?$params['prize6_rate']:0;
		$jiang=array($params['prize1_rate'],$params['prize2_rate'],$params['prize3_rate'],$params['prize4_rate'],$params['prize5_rate'],$params['prize6_rate']);
		//将奖品中的几率相加，哪个几率相加后的结果包含随机数，就在哪里停。
		$num=0;
		$zhi=-1;
		for($i=0;$i<6;$i++)
		{
			if($rand>$num&&$rand<=$jiang[$i]+$num)
			{
				$zhi=$i;
				break;
			}
			$num+=$jiang[$i];
		}
		$zhi+=1;
		if($zhi>0)
		{
			cookie('questionnaire_jiang',$zhi,3600*7*24);
		}
		else
		//如果中奖为空，则查看用户的中奖信息中是否已经中过必中的一次奖，如果没有中，就查找必中的奖并中一次，根据名称对比。
		{
			$user_info=session('userInfo');
			$user=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
			if(empty($user))
			{
				for($i=0;$i<count($jiang);$i++)
				{
					if($params['prize'.($i+1).'_only']==1&&$params['prize'.($i+1).'_total']>0)
					{
						$zhi=$i+1;
						cookie('bigwheel_jiang',$zhi,3600*7*24);
						break;
					}
				}
			}
			else     //如果用户已经注册
			{
				$jianglist = $this->activity_user_prize->where(array("aid"=>$aid,"from_user"=>$user_info['openid']))->select();
				//遍历奖品数据。
				for($i=0;$i<count($jiang);$i++)
				{
					//如果遇到必中的奖品就遍历中奖记录看是否已经中奖。
					if($params['prize'.($i+1).'_only']==1)
					{
						$zai=false;
						for($j=0;$j<count($jianglist);$j++)
						{
							if($params['prize'.($i+1).'_name']==$jianglist[$j]['name'])
							{
								$zai=true; break;
							}
						}
						if(!$zai&&$params['prize'.($i+1).'_total']>0)
						{
							$zhi=$i+1;
							cookie('questionnaire_jiang',($zhi),3600*7*24);
							break;
						}
					}
				}
			}
		}
		if($zhi>0)
		{
			//中奖的礼品数量-1;
			$sql="update cmf_activity_params set value=value-1 where aid=".$aid." and field='prize".$zhi."_total'";
			M()->execute($sql);
		}
		$jiang=array('type'=>$params['prize'.$zhi.'_type'],'name'=>$params['prize'.$zhi.'_name'],'thumb'=>$params['prize'.$zhi.'_thumb']);
		$result['jiang']=$jiang;
		if($zhi==3||$zhi==4)
			$zhi++;
		else if($zhi==5||$zhi==6)
			$zhi+=2;
		else if($zhi==0)
		{
			//产生一个随机数判断应该显示空奖的位置。
			$suizhi=rand(0,1);
			if($suizhi==0)
				$zhi=3;
			else $zhi=6;
		}
		$result['status']=$zhi;
		$result['jiang']=$jiang;
		$this->ajaxReturn($result);
	}
	/*记录中奖用户信息*/
	public function lingjiang()
	{
		$user_info=session('userInfo');
		$data['from_user']=$user_info['openid'];
		$data['nickname']=$user_info['nickname'];
		$data['avatar']=$user_info['headimgurl'];
		$time=time();
		$data['createtime']=$time;
		$data['update_time']=$time;
		
		$aid=I('get.id',0,'intval');
		$data['username']=I('post.name');
		$data['mobile']=I('post.mobile');
		$data['aid']=$aid;
		$zhi=cookie('questionnaire_jiang');
		//根据奖值获取奖品。
		$params = $this->activity_params->where("aid=$aid and (field='prize".$zhi."_name' or field='prize".$zhi."_type' or field='prize".$zhi."_thumb')")->select();
		$jiang=array_column($params, 'value', 'field');
		$user=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
		if($user)
		{
			//更新user信息
			$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->save($data);
		}
		else
		{
			$this->activity_user->add($data);
		}
		$res['status']=1;
		//添加中奖信息
		$prize_data = array("aid"=>$aid,"uid"=>$user['id'],"from_user"=>$user_info['openid'],"username"=>$data['username'],"mobile"=>$data['mobile'],"name"=>$jiang['prize'.$zhi.'_name'],"type"=>$jiang['prize'.$zhi.'_type'],"thumb"=>$jiang['prize'.$zhi.'_thumb'],'createtime'=>time());
		if(!$this->activity_user_prize->add($prize_data))
		{
			$res['status']=-1;
			$res['msg']="添加中奖信息失败";
			$this->ajaxReturn($res);
		}
		$res['jiang']=array('name'=>$jiang['prize'.$zhi.'_name'],'type'=>$jiang['prize'.$zhi.'_type'],'thumb'=>$jiang['prize'.$zhi.'_thumb']);
		$this->ajaxReturn($res);
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

