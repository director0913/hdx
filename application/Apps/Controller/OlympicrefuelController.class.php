<?php

/**
 * 大转盘助力活动
 */
namespace Apps\Controller;
use Common\Controller\ActivitybaseController; 
class OlympicrefuelController extends ActivitybaseController {
	protected $activity;
	protected $activity_params;
	protected $activity_user;
	protected $activity_user_data;
	protected $activity_user_prize;
	protected $jiangpai;
	protected $share_data;//定义分享数据
	function _initialize(){
		parent::_initialize();
		$this->activity = D("Activity");
		$this->activity_params = D("ActivityParams");
		$this->activity_user = D("ActivityUser");
		$this->activity_user_data = D("ActivityUserData");
		$this->activity_user_prize = D("ActivityUserPrize");
		$this->jiangpai = D("Ad");
	}
    public function index() {
		set_wechat_info();
	  $aid = I("get.id",0,"intval");
	  $url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/olympicrefuel/index',array('id'=>$aid));
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('olympicrefuel', 'index', $aid);
		} else {
			$this->entry($url);
		}
//		$this->entry($url);
	  $cando=1;
	  $item = sp_get_activity($aid);
	  if(!$item)
	  {
		  $this->error('活动不存在');
	  }
	  //判断活动是否开始。
	  if($item['begintime']>time())
	  {
		  $cando=-1;
	  }
	  if($item['endtime']<=time())
	  {
		  $cando=0;
	  }
	  $this->assign("cando",$cando);
	  $params = sp_get_activity_params($aid);	
	  $params = array_column($params, 'value', 'field');

	  //分享内容定义区
	  $this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' => C('DOMAIN_NAME').U('apps/olympicrefuel/index',array('id'=>$aid)),
			'imgUrl' =>$item['share_img']
	  );
	  $this->add_userinfo($aid);
	  $this->assign("share",$this->share_data);
	  $this->assign("params",$params);
	  $this->assign("item",$item);
	  //加油总次数
	  $total_num=$this->splitnum($item['total_num']);
	  $this->assign("total_num",$total_num);
	  //加油情况展示
	  //$user_list=$this->activity_user->where(array('aid'=>$aid))->order('createtime desc')->limit(10)->select();
	  $user_list=$this->activity_user->where('aid='.$aid.' and total_num >0')->order('createtime desc')->limit(10)->select();
	  $this->assign('user_list',$user_list);
	  //获取奖牌数。
	  $jiangpaizong=$this->jiangpai->where(array('id'=>1))->find();
	  $jiangpai=explode('，',$jiangpaizong['ad_name']);
	  $this->assign('jiangpai',$jiangpai);
	  //宝箱
	  $user_info=session('userInfo');
	  $user=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
	  if($user)
	  {
			$prize_list=$this->activity_user_prize->where(array('aid'=>$aid,'uid'=>$user['id']))->order('createtime desc ')->select();
			$this->assign("prize_list",$prize_list);
	  }
	  //今天是否加油
	  $today = strtotime(date("Y-m-d",time()));
	  $is_jiayou=0;
	  if($user)
	  {
		  //这里要限制总次数。
		  //if(per_num)
	  		if($today<=$user['update_time'])
			{
				$is_jiayou=1;
			}
	  }
	  $this->assign("is_jiayou",$is_jiayou);
	  $this->assign("user",$user);

     $this->display();
    }
	 //获取随机数判断应得的奖品是什么。
    public function getjiang()
    {
		$id=I('get.id',0,'intval');
		//获取当前活动奖品信息。
		$params = $this->activity_params->where("aid=$id")->select();
		$params=array_column($params, 'value', 'field');
		//$params = sp_get_activity_params($id);
		if(!empty(cookie('olympicrefuel_jiang')))
		{
			cookie('olympicrefuel_jiang',null);
		}
		//获取当前用户信息。
		$user_info=session('userInfo');
		$user=$this->activity_user->where(array('aid'=>$id,'from_user'=>$user_info['openid']))->find();
		//否则直接进行。
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
		$result['msg']=$zhi."；";
		if($zhi!=0)
		{
			cookie('olympicrefuel_jiang',$zhi,3600*7*24);
		}
		else
		//如果中奖为空，则查看用户的中奖信息中是否已经中过必中的一次奖，如果没有中，就查找必中的奖并中一次，根据名称对比。
		{
			if(empty($user))
			{
				$result['msg'].="wu；";
				for($i=0;$i<count($jiang);$i++)
				{
					if($params['prize'.($i+1).'_only']==1&&$params['prize'.($i+1).'_total']>0)
					{
						$zhi=$i+1;
						cookie('olympicrefuel_jiang',$zhi,3600*7*24);
						break;
					}
				}
			}
			else     //如果用户已经注册
			{
				$result['msg'].="you；";
				$jianglist = $this->activity_user_prize->where(array("aid"=>$id,"from_user"=>$user_info['openid']))->select();
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
							cookie('olympicrefuel_jiang',$zhi,3600*7*24);
							break;
						}
					}
				}
			}
		}
		if($zhi!=0)
		{
			//中奖的礼品数量-1;
			$sql="update cmf_activity_params set value=value-1 where aid=".$id." and field='prize".$zhi."_total'";
		 	M()->execute($sql);
		}
		$jiang=array('type'=>$params['prize'.$zhi.'_type'],'name'=>$params['prize'.$zhi.'_name'],'thumb'=>$params['prize'.$zhi.'_thumb']);
		$result['jiang']=$jiang;
		$result['status']=$zhi;
		$result['msg'].=$zhi;
		$this->ajaxReturn($result);
	}
	/*加油*/
	public function jiayou()
	{
		$aid=I('post.id',0,'intval');
		$sql="update cmf_activity set total_num=total_num+1 where id=".$aid;
		M()->execute($sql);
			//更新自己加油时间
			$user_info=session('userInfo');
			$sql="update cmf_activity_user set total_num=total_num+1,update_time=".time()." where aid=".$aid." and from_user='".$user_info['openid']."'";
			M()->execute($sql);
			//$update['update_time']=time();
			//$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->save($update);
			//自己页面加油次数加1
			$num=I('post.total_num',0,'intval');
			$num++;
			$num=$this->splitnum($num);
			$res['status']=1;
			$res['total_num']=$num;
		$this->ajaxReturn($res);	
	}
	/*记录中奖用户信息*/
	public function getPrize()
	{
		$aid=I('get.id',0,'intval');
		$data['username']=I('post.name');
		$data['mobile']=I('post.mobile');
		$zhi=cookie('olympicrefuel_jiang');
		$res['msg']=$zhi;
		//根据奖值获取奖品。
		$params = $this->activity_params->where("aid=$aid and (field='prize".$zhi."_name' or field='prize".$zhi."_type' or field='prize".$zhi."_thumb')")->select();
		$jiang=array_column($params, 'value', 'field');
		$user_info=session('userInfo');
		$result=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
		if($result)
		{
			$res['status']=1;
			//更新user信息
			if(!$result['mobile'])
				$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->save($data);
			//添加中奖信息
			$prize_data = array("aid"=>$aid,"uid"=>$result['id'],"from_user"=>$user_info['openid'],"username"=>$data['username'],"mobile"=>$data['mobile'],"name"=>$jiang['prize'.$zhi.'_name'],"type"=>$jiang['prize'.$zhi.'_type'],"thumb"=>$jiang['prize'.$zhi.'_thumb'],'createtime'=>time());
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
	/*分割加油总次数*/
	public function splitnum($num)
	{
		$num=strval($num);
		$ling="";
		if(strlen($num)<=5)
		{
			for($i=0;$i<5-strlen($num);$i++)
			{
				$ling.="0";
			}
		}
		else $num="99999";
		return $ling.$num;
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

