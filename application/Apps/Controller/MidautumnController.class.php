<?php

/**
 * 中秋节
 */
namespace Apps\Controller;
use Common\Controller\ActivitybaseController; 
class MidautumnController extends ActivitybaseController {
	protected $activity;
	protected $activity_params;
	protected $activity_user;
	protected $activity_user_data;
	protected $activity_friend;
	protected $share_data;//定义分享数据
	function _initialize(){
		parent::_initialize();
		$this->activity = D("Activity");
		$this->activity_params = D("ActivityParams");
		$this->activity_user = D("ActivityUser");
		$this->activity_user_data = D("ActivityUserData");
		$this->activity_friend = D("ActivityFriend");
		$this->activity_user_prize = D("ActivityUserPrize");
	}
	//对个性化字段的处理。
	function save($aid){
		$data = array(
			array("aid"=>$aid,"name"=>"学校名称","field"=>"school_name","value"=>I('post.school_name')),
			array("aid"=>$aid,"name"=>"领奖二维码","field"=>"erweima","value"=>I('post.erweima')),
			array("aid"=>$aid,"name"=>"奖品名称","field"=>"prize_name","value"=>I('post.prize_name')),
			array("aid"=>$aid,"name"=>"完成任务人数","field"=>"finish_within","value"=>I('post.finish_within')),
			array("aid"=>$aid,"name"=>"报名需填信息","field"=>"other_remark","value"=>I('post.other_remark')),
			array("aid"=>$aid,"name"=>"校区","field"=>"other_school","value"=>I('post.other_school')),
			array("aid"=>$aid,"name"=>"年龄","field"=>"age","value"=>I('post.age')),
			array("aid"=>$aid,"name"=>"班级","field"=>"cless","value"=>I('post.cless')),
			array("aid"=>$aid,"name"=>"学校","field"=>"school","value"=>I('post.school')),
			array("aid"=>$aid,"name"=>"其他","field"=>"qita","value"=>I('post.qita')),
		);
		sp_save_activity_params($data);
	}
    public function index() {
		set_wechat_info();
		//获取活动id。
		$aid = I("get.id",0,"intval");
		//$this->activity_friend->where(array("aid"=>$aid))->delete();
		//删除当前活动下的所有帮助信息。
		//$this->activity_user->where(array("aid"=>$aid))->delete();
		//$this->activity_user->where(array('aid'=>$aid))->save(array('day_num'=>3,'per_num'=>0));
		//删除自己的信息。
		$this->assign("aid",$aid);
		//看是否是分享过来的页面。
		$uid=I('get.uid',0,'intval');
		//定义分享链接。
		$url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/midautumn/index',array('id'=>$aid,'uid'=>$uid));
		//检查浏览器及获取公众号信息。
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('midautumn', 'index', $aid, $uid);
		} else {
			$this->entry($url);
		}
		//获取扩展字段。
		$activity = sp_get_activity($aid);
		if(!$activity)
		{
			$this->error('活动不存在');
		}
		$status=0;
		$cando=1;
		//判断活动是否开始。
		if($activity['begintime']>time())
		{
			$cando=0;
			$status=-1;
			$xinximsg="活动未开始";
		}
		//判断活动是否结束。
		if($activity['endtime']<=time())
		{
			$cando=0;
			$status=-1;
			$xinximsg="活动已结束";
		}
		$params = sp_get_activity_params($aid);	
		$activity['params'] = array_column($params, 'value', 'field');
		$this->add_userinfo($aid);
		//获取粉丝详细信息。
		$user_info=session('userInfo');
		$this->assign("user_info",$user_info);
		//获取自己的信息。
		$share_info=$user=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
		$this->assign("user",$user);
		$shareuid=0;
		$isme=1;
		//是否注册
		$is_regist=false;
		if($uid!=0)
		{
			//获取分享人信息。
			$share_info=$this->activity_user->where(array('id'=>$uid))->find();
			if(!empty($share_info['username'])&&!empty($share_info['mobile']))
			{
		  		$shareuid=$uid;
				if($share_info['from_user']!=$user_info['openid'])
				{
					$isme=0;
				}
			}
			else
			{
				$this->error('分享人信息不存在！',U("apps/midautumn/index",array("id"=>$aid,"sui"=>time())));
			}
		}
		else if(!empty($user['mobile']))
		{
			$shareuid=$user['id'];
		}
		$chuanuid=$shareuid!=0?$shareuid:$user['id'];
		if(!empty($user['mobile']))
		{
			$is_regist=true;
		}
		$this->assign("status",$status);
		$this->assign("is_regist",$is_regist);
		$this->assign("isme",$isme);
		$this->assign("params",$activity['params']);
		$this->assign("chuanuid",$chuanuid);
		$friend_list=array();
		//如果有分享人信息就改变分享链接。
		//$random_url = time();
		if($shareuid!=0)
		{
			$share_url='http://'.$_SERVER['HTTP_HOST'].U('apps/midautumn/index',array('id'=>$aid,'uid'=>$shareuid));
			//$url='http://'.$random_url.'.qihuangongfang.com'.U('apps/midautumn/index',array('id'=>$aid,'uid'=>$shareuid));
			//$zhuuser=$this->activity_user->where(array('aid'=>$aid,'id'=>$shareuid))->find();
			$groupdata=$this->activity_friend->where('aid='.$aid.' and uid='.$shareuid." and per_num>0")->group('per_num')->select();
			$groupcount=count($groupdata);
			//获取有月饼的信息。
			$bing_list=array();
			//获取所有帮助信息。
			$friend_list=$this->activity_friend->where(array('aid'=>$aid,'uid'=>$shareuid))->order('createtime desc ')->select();
			//获取所有第一个不同月饼的信息。
			for($i=count($friend_list)-1;$i>=0;$i--)
			{
				$zai=false;
				for($j=0;$j<count($bing_list);$j++)
				{
					if($friend_list[$i]['per_num']==$bing_list[$j]['per_num'])
					{
						$zai=true; break;
					}
				}
				if(!$zai&&$friend_list[$i]['per_num']!=0)
				{
					$bing_list[]=$friend_list[$i];
				}
			}
		}
		//否则，分享链接是自己的。
		else
		{
			$share_url='http://'.$_SERVER['HTTP_HOST'].U('apps/midautumn/index',array('id'=>$aid));
			//$url='http://'.$random_url.'.qihuangongfang.com'.U('apps/midautumn/index',array('id'=>$aid));
		}
	  	$url = $share_url;
		$this->share_data = array(
			'title'	=>$activity['share_title'],
			'desc'  =>$activity['share_des'],
			'url' => $url,
			'imgUrl' =>$activity['share_img']
		);
//		$total_num=$this->splitnum($zhuuser['total_num']);
//		$this->assign("total_num",$total_num);
		$this->assign("shareuid",$shareuid);
		$this->assign("groupcount",$groupcount);
		$this->assign("share",$this->share_data);
		$this->assign("activity",$activity);
		$this->assign("share_info",$share_info);
		$this->assign("user_info",$user_info);
		//如果是自己，判断自己的一次加油是否被使用了，如果total_num==0可以给自己加油。
		//如果是给别人加油，判断自己的加油总次数。
		if($isme==0)
		{
			//获取这个人的总加油次数和当天加油次数。
			$total_num=$this->activity_friend->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->count();
			//如果自己已经注册过了，就判断帮别人的总次数是否已经达到。
			if(!empty($user['mobile']))
			{
				if($item['per_num']>0&&$total_num>=$item['per_num']&&$cando==1)
				{
					$cando=0;
					$xinximsg="总次数已用完";
				}
			}
			else
			{
				//如果自己没有注册过，就根据总次数和每天次数进行判断。
				if($item['total_num']>0&&$total_num>=$item['total_num']&&$cando==1)
				{
					$cando=0;
					$xinximsg="总次数已用完";
				}
				if($item['day_num']>0&&$cando==1)
				{
					$today = strtotime(date("Y-m-d",time()));
					$day_num=$this->activity_friend->where("aid=".$aid." and from_user='".$user_info['openid']."' and update_time>=".$today)->count();
					if($day_num>=$item['day_num'])
					{
						$cando=0;
						$xinximsg="当天次数已用完";
					}
				}
			}
		}
		$this->assign("cando",$cando);
		$this->assign("xinximsg",$xinximsg);
		$this->assign("friend_list",$friend_list);
		$this->assign("bing_list",$bing_list);
		//统计参与人员的数量。
		$canyucount=20+$this->activity_user->where('aid='.$aid.' and mobile is not null')->count();
		$this->assign("canyucount",$canyucount);
		$this->assign("domain_name",C('DOMAIN_NAME'));
		$this->display();
    }
	public function index1() {
		set_wechat_info();
		$aid = I("get.id",0,"intval");
		$url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/midautumn/index1',array('id'=>$aid));
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('midautumn', 'index1', $aid);
		} else {
			$this->entry($url);
		}
		$item = sp_get_activity($aid);
		if(!$item)
		{
			$this->error('抱歉，活动被删除或不存在！');
		}
		$params = sp_get_activity_params($aid);
		$params = array_column($params, 'value', 'field');

		$this->share_data = array(
			'title'	=> $item['share_title'],
			'desc'  => $item['share_des'],
			'url' => $url,
			'imgUrl' => $item['share_img']
		);
		//获取前5个获取的奖品。
		$prizels=$this->activity_user_prize->where(array('aid'=>$aid))->limit(10)->select();
		$hjstr="";
		foreach($prizels as $hj)
		{
			$hjstr.=$hj['username']."获得：".$hj['type'].$hj['name']."&nbsp;&nbsp;&nbsp;&nbsp;";
		}
		$this->assign("hjstr",$hjstr);
		$this->add_userinfo($aid);
		$user_info=session('userInfo');
		//获取个人信息。
		$user=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
		$this->assign("user",$user);
		$this->assign("share",$this->share_data);
		$this->assign("params",$params);
		$this->assign($params);
		$this->assign($item);
		$this->assign('id',$aid);
		$this->display();
	}
	/**
	 * 判读是否能够抽奖
	 */
	public function check_status(){
		$aid = I("get.id", 0, "intval");
		$userInfo = session('userInfo');
		$this->analyze_activitys($aid);
		$this->sp_analyze_activity_user($userInfo,$aid);
		$this->success("可以抽奖！");
	}
	/**
	 *获得奖品
	 */
	public function get_gift(){
		$aid = I("get.id",0,"intval");
		$params = sp_get_activity_params($aid);
		$params = array_column($params, 'value', 'field');
		$user_info = session('userInfo');
		$zhi = 1;
		//更新用户的当天参与情况，如果中奖了就更新中奖值。
		if (!empty(cookie('bigwheel_jiang'))) {
			cookie('bigwheel_jiang', null);
		}
		$this->activity_user->where(["aid" => $aid, "from_user" => $user_info['openid']])->save(['is_lottery' => 1]);
		$user = $this->activity_user->where(["aid" => $aid, "from_user" => $user_info['openid']])->find();
		if ($user) {
			$count = $this->activity_user_prize->where(['aid' => $aid, 'uid' => $user['id']])->count();
			if (!empty($count)) {
				$zhi = 0;
			}
		}
		if ($zhi > 0) {
			//产生一个1-100的随机数。
			$rand = rand(1, 100);
			//如果奖品没有了，几率就为0；
			$params['prize1_rate'] = $params['prize1_total'] > 0 && $params['prize1_only'] == 0 ? $params['prize1_rate'] : 0;
			$params['prize2_rate'] = $params['prize2_total'] > 0 && $params['prize2_only'] == 0 ? $params['prize2_rate'] : 0;
			$params['prize3_rate'] = $params['prize3_total'] > 0 && $params['prize3_only'] == 0 ? $params['prize3_rate'] : 0;
			$params['prize4_rate'] = $params['prize4_total'] > 0 && $params['prize4_only'] == 0 ? $params['prize4_rate'] : 0;
			$params['prize5_rate'] = $params['prize5_total'] > 0 && $params['prize5_only'] == 0 ? $params['prize5_rate'] : 0;
			$params['prize6_rate'] = $params['prize6_total'] > 0 && $params['prize6_only'] == 0 ? $params['prize6_rate'] : 0;
			$jiang = [
				$params['prize1_rate'],
				$params['prize2_rate'],
				$params['prize3_rate'],
				$params['prize4_rate'],
				$params['prize5_rate'],
				$params['prize6_rate']
			];
			//将奖品中的几率相加，哪个几率相加后的结果包含随机数，就在哪里停。
			$num = 0;
			$zhi =- 1;
			for ($i = 0; $i < 6; $i++) {
				if ($rand > $num && $rand <= $jiang[$i] + $num) {
					$zhi=$i;
					break;
				}
				$num += $jiang[$i];
			}
			$zhi += 1;
			if ($zhi > 0) {
				cookie('bigwheel_jiang',$zhi,3600*7*24);
			} else {
				//如果中奖为空，则查看用户的中奖信息中是否已经中过必中的一次奖，如果没有中，就查找必中的奖并中一次，根据名称对比。
				if (empty($user)) {
					for ($i = 0; $i < count($jiang); $i++) {
						if ($params['prize'.($i+1).'_only'] == 1 && $params['prize'.($i+1).'_total'] > 0) {
							$zhi = $i + 1;
							cookie('bigwheel_jiang', $zhi, 3600 * 7 * 24);
							break;
						}
					}
				} else {
					//如果用户已经注册
					$jianglist = $this->activity_user_prize->where(["aid" => $aid, "from_user" => $user_info['openid']])->select();
					// 遍历奖品数据。
					for ($i = 0; $i < count($jiang); $i++) {
						// 如果遇到必中的奖品就遍历中奖记录看是否已经中奖。
						if ($params['prize'.($i+1).'_only'] == 1) {
							$zai = false;
							for ($j = 0; $j < count($jianglist); $j++) {
								if ($params['prize'.($i+1).'_name'] == $jianglist[$j]['name']) {
									$zai = true;
									break;
								}
							}
							if (!$zai && $params['prize'.($i+1).'_total'] > 0) {
								$zhi = $i + 1;
								cookie('bigwheel_jiang', $zhi, 3600 * 7 * 24);
								break;
							}
						}
					}
				}
			}
		}

		if ($zhi > 0) {
			// 中奖的礼品数量-1;
			M()->execute("UPDATE `cmf_activity_params` SET `value` = `value` - 1 WHERE aid =" . $aid . " AND `field` = 'prize " . $zhi . "_total'");
		}
		//更新客户信息。
		$jiang = [
			'type' => $params['prize'.$zhi.'_type'],
			'name' => $params['prize'.$zhi.'_name'],
			'thumb' => $params['prize'.$zhi.'_thumb']
		];
		$result['jiang'] = $jiang;
		if ($zhi == 3 || $zhi == 4) {
			$zhi++;
		} else if ($zhi == 5 || $zhi == 6) {
			$zhi += 2;
		} else if($zhi == 0) {
			//产生一个随机数判断应该显示空奖的位置。
			$suizhi = rand(0,1);
			if($suizhi == 0) {
				$zhi = 3;
			} else {
				$zhi = 6;
			}
		}
		$zhi--;
		$result['status'] = $zhi;
		$this->ajaxReturn($result);
	}
	/**
	 *判断是否注册
	 **/
	public function check_regist(){
		$aid = I("get.id",0,"intval");
		$userInfo = session('userInfo');
		$res = $this->activity_user->where(array("aid"=>$aid,"from_user"=>$userInfo['openid']))->find();
		if($res['mobile']){
			$result['status']=1;
		}else{
			$result['status']=0;
		}
		$this->ajaxReturn($result);
	}
	/**
	 * 领取奖品
	 */
	public function add_gift(){
		$aid = I("get.id", 0, "intval");
		$data = [
			'username' => I("post.name"),
			'mobile' => I("post.mobile"),
			'age' => I("post.age"),
			'class' => I("post.cless"),
			'school' => I("post.school"),
			'remark' => I("post.remark"),
		];
		$userInfo = session('userInfo');
		$this->activity_user->where(["aid" => $aid, "from_user" => $userInfo['openid']])->save(['is_lottery' => 1]);
		$result = $this->activity_user->where(["aid" => $aid, "from_user" => $userInfo['openid']])->find();
		$zhi = cookie('bigwheel_jiang');
		$params = $this->activity_params->where("aid=$aid and (field='prize".$zhi."_name' or field='prize".$zhi."_type' or field='prize".$zhi."_thumb')")->select();
		$jiang = array_column($params, 'value', 'field');
		if ($result) {
			$res['status'] = 1;
			//更新user信息
			if (!$result['mobile']) {
				$this->activity_user->where(array('aid'=>$aid,'from_user'=>$userInfo['openid']))->save($data);
			}
			//添加中奖信息
			$prize_data = [
				"aid" => $aid,
				"uid" => $result['id'],
				"from_user" => $userInfo['openid'],
				"username" => $data['username'],
				"mobile" => $data['mobile'],
				"name" => $jiang['prize'.$zhi.'_name'],
				"type" => $jiang['prize'.$zhi.'_type'],
				"thumb" => $jiang['prize'.$zhi.'_thumb'],
				'createtime'=>time()
			];
			if (!$this->activity_user_prize->add($prize_data)) {
				$res['status'] = -1;
				$res['msg'] = "添加中奖信息失败";
				$this->ajaxReturn($res);
			}
			$res['jiang'] = [
				'name' => $jiang['prize'.$zhi.'_name'],
				'type' => $jiang['prize'.$zhi.'_type'],
				'thumb' => $jiang['prize'.$zhi.'_thumb']
			];
		} else {
			$res['status'] = -1;
			$res['msg'] = "添加中奖用户失败";
		}
		$this->ajaxReturn($res);
	}
	public function getbingname($num)
	{
		$bing=array();
        switch($num)
        {
        	case 1: $bing["num"]=1;$bing["name"]="莲蓉";$bing["tuname"]="lianrong"; break;
            case 2: $bing["num"]=2;$bing["name"]="冰皮";$bing["tuname"]="bingpi"; break;
            case 3: $bing["num"]=3;$bing["name"]="五仁";$bing["tuname"]="wuren"; break;
            case 4: $bing["num"]=4;$bing["name"]="绿豆沙";$bing["tuname"]="dousha"; break;
            case 5: $bing["num"]=5;$bing["name"]="蛋黄";$bing["tuname"]="danhuang"; break;
            case 6: $bing["num"]=6;$bing["name"]="空";$bing["tuname"]="kong"; break;
        }
		return $bing;
	}
	public function getnonum($cearray,$dataarray)
	{
		//返回第一个不在的值。
		//如果都在就返回-1。
		for($i=0;$i<count($cearray);$i++)
		{
			for($j=0;$j<count($dataarray);$j++)
			{
				if($cearray[$i]==$dataarray[$j]['per_num'])
				{
					$zai=true; break;
				}
			}
			if(!$zai)
				return $cearray[$i];
		}
		return -1;
	}
	public function getbing()
	{
		$aid=I('get.id',0,'intval');
		$finish_within=I('get.finish_within',0,'intval');
		$uid=I('get.uid',0,'intval');
		$isme=I('get.isme',0,'intval');
		$result=array('bingnum'=>-1,
					  'xin'=>1);
		if($isme==1)
		{
			//获取这个人信息。
			$user=$this->activity_user->where(array('aid'=>$aid,'id'=>$uid))->find();
			if(empty($user['mobile']))
			{
				//产生一个1-5的随机数
				$result['bingnum']=rand(1,5);
				//产生一个随机的最后中的数。
				$suizhi=rand(1,4);
				$suizhi=$suizhi==$result['bingnum']?5:$suizhi;
				//更改自己的一个值为这个值。
				$this->activity_user->where(array('aid'=>$aid,'id'=>$uid))->save(array('day_num'=>$suizhi));
			}
			else
			{
				$result['status']=0;
				$result['msg']="您已经注册过了！";
				$this->ajaxReturn($result);
			}
		}
		else
		{
			$t=I('get.t',0,'intval');
			if($t==1){
				$this->analyze_activitys_friend($aid,$uid);
			}
		
			//获取活动信息。
			$activity=sp_get_activity($aid);
			//获取这个人帮助别人的次数信息。
			$user_info=session('userInfo');
			//获取自己的信息。
			$user=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
			//获取这个人的总加油次数和当天加油次数。
			$total_num=$this->activity_friend->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->count();
			//$result['msg']="自己参与总次数".$total_num;
			//如果自己已经注册过了，就判断帮别人的总次数是否已经达到。
			if(!empty($user['mobile']))
			{
				//$result['msg'].="；已注册；设定次数".$activity['per_num'];
				if($activity['per_num']>0&&$total_num>=$activity['per_num'])
				{
					$result['status']=0;
					$result['msg']="总次数已用完";
					$this->ajaxReturn($result);
				}
			}
			else
			{
				//$result['msg'].="；未注册；设定总次数：".$activity['total_num']."；设定当天次数".$activity['day_num'];
				//如果自己没有注册过，就根据总次数和每天次数进行判断。
				if($activity['total_num']>0&&$total_num>=$activity['total_num'])
				{
					$result['status']=0;
					$result['msg']="总次数已用完";
					$this->ajaxReturn($result);
				}
				if($activity['day_num']>0)
				{
					$today = strtotime(date("Y-m-d",time()));
					$day_num=$this->activity_friend->where("aid=".$aid." and from_user='".$user_info['openid']."' and createtime>=".$today)->count();
					//$result['msg'].="；自己的当天次数".$day_num;
					if($day_num>=$activity['day_num'])
					{
						$result['status']=0;
						$result['msg']="当天次数已用完";
						$this->ajaxReturn($result);
					}
				}
			}
			//获取要帮助的人的信息。
			$halpuser=$this->activity_user->where(array('aid'=>$aid,'id'=>$uid))->find();
			//获取设定的范围。
			$dizhi=20;
			$gaozhi=30;
			switch($finish_within)
			{
				case 1: $dizhi=30; $gaozhi=40; break;
				case 2: $dizhi=40; $gaozhi=50; break;
			}
			//获取被帮忙的次数。
			$helpcount=$this->activity_friend->where(array('aid'=>$aid,'uid'=>$uid))->count();
			$groupdata=$this->activity_friend->where(array('aid'=>$aid,'uid'=>$uid))->order("createtime Asc")->group('per_num')->select();
			$c=0;
			$deyi=0;
			for($i=0;$i<count($groupdata);$i++)
			{
				if($groupdata[$i]['per_num']!=0)
				{
					$c++;
					if($deyi==0)
						$deyi=$groupdata[$i]['per_num'];
				}
			}
			if($c>=5)
			{
				$result['status']=0;
				$result['msg']="他的月饼已经集齐了，去帮别人吧！";
				$this->ajaxReturn($result);
			}
			//获取应该得到的月饼。
			//$result['msg'].="；被帮助次数：".$helpcount."；随机数：".$suiji;
			//范围分5份。
			$yifen=$gaozhi/5;
			$result['msg'].=$deyi.":".$halpuser['day_num'].":".$helpcount.":".$yifen.":";
			if($helpcount>=$yifen*0&&$helpcount<=$yifen*1)
			{
				if($helpcount+1==$yifen*1)
				{
					$result['bingnum']=1;
				}
				else
				{
					$suiji=rand(1,100);
					//获取已经中的。
					$result['bingnum']=$suiji%2;
					$result['msg'].="1".":".$suiji;
				}
			}
			if($helpcount>$yifen*1&&$helpcount<=$yifen*2)
			{
				if($helpcount+1==$yifen*2)
				{
					$result['bingnum']=2;
				}
				else
				{
					$suiji=rand(1,100);
					//获取已经中的。
					$result['bingnum']=$suiji%3;
					$result['msg'].="2".":".$suiji;
				}
			}
			if($helpcount>$yifen*2&&$helpcount<=$yifen*3)
			{
				if($helpcount+1==$yifen*3)
				{
					$result['bingnum']=3;
				}
				else
				{
					$suiji=rand(1,100);
					//获取已经中的。
					$result['bingnum']=$suiji%4;
					$result['msg'].="3".":".$suiji;
				}
			}
			if($helpcount>$yifen*3&&$helpcount<=$yifen*4)
			{
				if($helpcount+1==$yifen*4)
				{
					$result['bingnum']=4;
				}
				else
				{
					$suiji=rand(1,100);
					//获取已经中的。
					$result['bingnum']=$suiji%5;
					$result['msg'].="4".":".$suiji;
				}
			}
			if($helpcount>$yifen*4&&$helpcount<=$yifen*5)
			{
				if($helpcount+1==$yifen*5)
				{
					$result['bingnum']=5;
				}
				else
				{
					$suiji=rand(1,100);
					//获取已经中的。
					$result['bingnum']=$suiji%6;
					$result['msg'].="5".":".$suiji;
				}
			}
			$result['bingnum']=$result['bingnum']==0?6:$result['bingnum'];
			if($result['bingnum']==$deyi)
			{
				$result['bingnum']=1;
			}
			else if($result['bingnum']==1)
			{
				$result['bingnum']=$deyi;
			}
			if($result['bingnum']==$halpuser['day_num'])
			{
				$result['bingnum']=5;
			}
			else if($result['bingnum']==5)
			{
				$result['bingnum']=$halpuser['day_num'];
			}
			$result['msg'].=":".$result['bingnum'];
			for($i=0;$i<count($groupdata);$i++)
			{
				if($result['bingnum']==6||$groupdata[$i]['per_num']==$result['bingnum'])
				{
					//$result['msg'].="；判断不新";
					$result['xin']=0;break;
				}
			}
			//添加一条自己的记录
			$jilu=array('aid'=>$aid,
						'uid'=>$uid,
						'from_user'=>$user['from_user'],
						'nickname'=>$user['nickname'],
						'avatar'=>$user['avatar'],
						'createtime'=>time(),
						'per_num'=>$result['bingnum']==6?0:$result['bingnum']);
			//$result['msg'].="；记录信息".json_encode($jilu);
			$this->activity_friend->add($jilu);
			//$result['msg'].="执行过了添加".$result["xin"];
		}
		if($result["xin"]==1&&$isme==0)
		{
			//$result['msg'].="；新";
			$sql="update __PREFIX__activity_user set per_num=per_num+1 where aid=".$aid." and id=".$uid;
			M()->execute($sql);
			//如果得到的是最后一个月饼。
			if($result['bingnum']==$halpuser['day_num'])
				$this->activity_user->where(array('aid'=>$aid,'id'=>$uid))->save(array('update_time'=>time()));
		}
		$result["bing"]=$this->getbingname($result['bingnum']);
		$result["time"]=date("Y-m-d H:i:s",time());
		$result["status"]=1;
		$this->ajaxReturn($result);
	}
	public function analyze_activitys_friend($aid,$uid){
		$userInfo = session('userInfo');
		$activity = D("Activity")->where(array("id"=>$aid,"valid"=>1))->find();
		$user = $this->activity_user->where(array("aid"=>$aid,"from_user"=>$userInfo['openid']))->find();
		//分析此好友是否存在
		$friend=$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->find();
		if(empty($userInfo['openid'])){
			$this->error("您的信息获取失败，请重新打开！");die();
		}
		if(empty($friend)&&!empty($userInfo['openid'])){
			$data = array(
				'aid' => $aid,
				'uid' => $uid,
				'from_user' => $userInfo['openid'],
				'nickname'  => $userInfo['nickname'],
				'avatar'  => $userInfo['headimgurl'],
				'createtime' => time(),
				'update_time' => time()

			);
			$this->activity_friend->add($data);
			$friend=$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->find();
		}
		$today = date("Y-m-d",time());
		$ftoday =  date("Y-m-d",$friend['update_time']);
		if($ftoday<$today){
			//清空当天的点赞数 
			$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->save(array('day_num'=>0,'day_value'=>'','update_time'=>time()));
		}
				
			//获取这个人帮助别人的次数信息。
			//获取自己的信息。
			$user=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$userInfo['openid']))->find();
			//获取这个人的总加油次数和当天加油次数。
			$total_num=$this->activity_friend->where(array('aid'=>$aid,'from_user'=>$userInfo['openid']))->sum('total_num');
			//$result['msg']="自己参与总次数".$total_num;
			//如果自己已经注册过了，就判断帮别人的总次数是否已经达到。
			if(!empty($user['mobile']))
			{
				//$result['msg'].="；已注册；设定次数".$activity['per_num'];
				if($activity['per_num']>0&&$total_num>=$activity['per_num'])
				{
					$result['status']=0;
					$result['msg']="总次数已用完";
					$this->ajaxReturn($result);
				}
			}
			else
			{
				//$result['msg'].="；未注册；设定总次数：".$activity['total_num']."；设定当天次数".$activity['day_num'];
				//如果自己没有注册过，就根据总次数和每天次数进行判断。
				if($activity['total_num']>0&&$total_num>=$activity['total_num'])
				{
					$result['status']=0;
					$result['msg']="总次数已用完";
					$this->ajaxReturn($result);
				}
				if($activity['day_num']>0)
				{
					$today = strtotime(date("Y-m-d",time()));
					$day_num=$this->activity_friend->where(array('aid'=>$aid,'from_user'=>$userInfo['openid']))->sum('day_num');
					//$result['msg'].="；自己的当天次数".$day_num;
					if($day_num>=$activity['day_num'])
					{
						$result['status']=0;
						$result['msg']="当天次数已用完";
						$this->ajaxReturn($result);
					}
				}
			}
			//获取要帮助的人的信息。
			$halpuser=$this->activity_user->where(array('aid'=>$aid,'id'=>$uid))->find();
			//获取设定的范围。
			$dizhi=20;
			$gaozhi=30;
			switch($finish_within)
			{
				case 1: $dizhi=30; $gaozhi=40; break;
				case 2: $dizhi=40; $gaozhi=50; break;
			}
			//获取被帮忙的次数。
			$helpcount=$this->activity_friend->where(array('aid'=>$aid,'uid'=>$uid))->count();
			$groupdata=$this->activity_friend->where(array('aid'=>$aid,'uid'=>$uid))->order("createtime Asc")->group('per_num')->select();
			$c=0;
			$deyi=0;
			for($i=0;$i<count($groupdata);$i++)
			{
				if($groupdata[$i]['per_num']!=0)
				{
					$c++;
					if($deyi==0)
						$deyi=$groupdata[$i]['per_num'];
				}
			}
			if($c>=5)
			{
				$result['status']=0;
				$result['msg']="他的月饼已经集齐了，去帮别人吧！";
				$this->ajaxReturn($result);
			}
			//获取应该得到的月饼。
			//$result['msg'].="；被帮助次数：".$helpcount."；随机数：".$suiji;
			//范围分5份。
			$yifen=$gaozhi/5;
			$result['msg'].=$deyi.":".$halpuser['day_num'].":".$helpcount.":".$yifen.":";
			if($helpcount>=$yifen*0&&$helpcount<=$yifen*1)
			{
				if($helpcount+1==$yifen*1)
				{
					$result['bingnum']=1;
				}
				else
				{
					$suiji=rand(1,100);
					//获取已经中的。
					$result['bingnum']=$suiji%2;
					$result['msg'].="1".":".$suiji;
				}
			}
			if($helpcount>$yifen*1&&$helpcount<=$yifen*2)
			{
				if($helpcount+1==$yifen*2)
				{
					$result['bingnum']=2;
				}
				else
				{
					$suiji=rand(1,100);
					//获取已经中的。
					$result['bingnum']=$suiji%3;
					$result['msg'].="2".":".$suiji;
				}
			}
			if($helpcount>$yifen*2&&$helpcount<=$yifen*3)
			{
				if($helpcount+1==$yifen*3)
				{
					$result['bingnum']=3;
				}
				else
				{
					$suiji=rand(1,100);
					//获取已经中的。
					$result['bingnum']=$suiji%4;
					$result['msg'].="3".":".$suiji;
				}
			}
			if($helpcount>$yifen*3&&$helpcount<=$yifen*4)
			{
				if($helpcount+1==$yifen*4)
				{
					$result['bingnum']=4;
				}
				else
				{
					$suiji=rand(1,100);
					//获取已经中的。
					$result['bingnum']=$suiji%5;
					$result['msg'].="4".":".$suiji;
				}
			}
			if($helpcount>$yifen*4&&$helpcount<=$yifen*5)
			{
				if($helpcount+1==$yifen*5)
				{
					$result['bingnum']=5;
				}
				else
				{
					$suiji=rand(1,100);
					//获取已经中的。
					$result['bingnum']=$suiji%6;
					$result['msg'].="5".":".$suiji;
				}
			}
			$result['bingnum']=$result['bingnum']==0?6:$result['bingnum'];
			if($result['bingnum']==$deyi)
			{
				$result['bingnum']=1;
			}
			else if($result['bingnum']==1)
			{
				$result['bingnum']=$deyi;
			}
			if($result['bingnum']==$halpuser['day_num'])
			{
				$result['bingnum']=5;
			}
			else if($result['bingnum']==5)
			{
				$result['bingnum']=$halpuser['day_num'];
			}
			$result['msg'].=":".$result['bingnum'];
			for($i=0;$i<count($groupdata);$i++)
			{
				if($result['bingnum']==6||$groupdata[$i]['per_num']==$result['bingnum'])
				{
					//$result['msg'].="；判断不新";
					$result['xin']=0;break;
				}
			}
			//添加一条自己的记录
			
			$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->setInc('day_num');
			$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->setInc('total_num');
			$friend=$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->find();
			
			$new=$result['bingnum']==6?0:$result['bingnum'];
			//判断是否新月饼
			$index_new=0;
			$flag_new=0;
			$old=explode(",",$friend['remark']);
			for($i=0;$i<count($old);$i++){
				if($old[$i]==$new){
					break;
				}else{
					$index_new++;
				}
			}
			if($index_new==count($old)){
				$flag_new=1;
			}
			$new=$friend['remark'].$new.',';
			$frienddata['remark']=$new;
			$frienddata['update_time']=time();
			$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->save($frienddata);
			
			if($flag_new=1)
			{
				$result["status"]=1;
				$result["msg"]=$result["bingnum"]."m".$flag_new;
				$this->ajaxReturn($result);
				//$result['msg'].="；新";
				$sql="update __PREFIX__activity_user set per_num=per_num+1 where aid=".$aid." and id=".$uid;
				M()->execute($sql);
				//如果得到的是最后一个月饼。
				if($result['bingnum']==$halpuser['day_num'])
					$this->activity_user->where(array('aid'=>$aid,'id'=>$uid))->save(array('update_time'=>time()));
			}
			$result["bing"]=$this->getbingname($result['bingnum']);
			$result["time"]=date("Y-m-d H:i:s",time());
			$result["status"]=1;
			$this->ajaxReturn($result);
			
		
	}
	/*注册*/
	public function register()
	{
		$user_info = session('userInfo');
		$aid = I("get.id",0,"intval");
		$data = [
			'username' => I("post.name"),
			'mobile' => I("post.mobile"),
			'remark' => I("post.remark"),
			'age' => I("post.age"),
			'class' => I("post.cless"),
			'school' => I("post.school"),
			'per_num' => 1,
		];
		$result = $this->activity_user->where(['aid' => $aid, 'from_user' => $user_info['openid']])->find();
		if($result)
		{
			$this->activity_user->where(['aid' => $aid, 'from_user' => $user_info['openid']])->save($data);
			//添加一条获奖记录
			$jilu = array(
				'aid' => $aid,
				'uid' => $result['id'],
				'from_user' => $user_info['openid'],
				'nickname' => $result['nickname'],
				'avatar' => $result['avatar'],
				'createtime' => time(),
				'per_num' => I('post.num')
			);
			$this->activity_friend->add($jilu);
			$res['status']=1;
			$res['msg']="注册成功";
			//$res['url']='http://'.$_SERVER['HTTP_HOST'].U('apps/midautumn/index',array('id'=>$aid,'uid'=>$result['id'],'ss'=>1));
			$res['url']=U('apps/midautumn/index',array('id'=>$aid,'ss'=>1));
		} else {
			$res['status'] = -1;
			$res['msg'] = "注册失败";
		}
		$this->ajaxReturn($res);
	}
	
		 //获取随机数判断应得的奖品是什么。
    public function getjiang()
    {
		$id=I('get.id',0,'intval');
		//获取当前活动奖品信息。
		$params = $this->activity_params->where("aid=$id")->select();
		$params=array_column($params, 'value', 'field');
		//$params = sp_get_activity_params($id);
		if(!empty(cookie('cheerchina_jiang')))
		{
			cookie('cheerchina_jiang',null);
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
		
		if($zhi!=0)
		{
			cookie('cheerchina_jiang',$zhi,3600*7*24);
		}
		else
		  //如果中奖为空，则查看用户的中奖信息中是否已经中过必中的一次奖，如果没有中，就查找必中的奖并中一次，根据名称对比。
		{
			if(empty($user))
			{
				for($i=0;$i<count($jiang);$i++)
				{
					if($params['prize'.($i+1).'_only']==1&&$params['prize'.($i+1).'_total']>0)
					{
						$zhi=$i+1;
						cookie('cheerchina_jiang',$zhi,3600*7*24);
						break;
					}
				}
			}
			else     //如果用户已经注册
			{
				$jianglist = $this->activity_user_prize->where(array("aid"=>$id,"from_user"=>$user_info['openid']))->select();
				//$result['msg']=json_encode($jianglist);
				//$result['msg'].=$user_info['openid']."；".$aid;
				//$this->ajaxReturn($result);
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
							cookie('cheerchina_jiang',($zhi),3600*7*24);
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
		$this->ajaxReturn($result);
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
		//$list =$this->activity_user->where(array('aid'=>$aid))->order('total_num desc')->limit(($page_u-1)*$size.",".$size)->select();
		$list =$this->activity_user->where('aid='.$aid.' and mobile is not null')->order('total_num desc')->limit(($page_u-1)*$size.",".$size)->select();
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
	/*加油*/
	public function jiayou()
	{
		$aid=I('post.id',0,'intval');
		$uid=I('post.uid',0,'intval');
		$user_info=session('userInfo');
		
		$user=$this->activity_user->where(array('id'=>$uid))->find();
		/*$res['status']=0;
		//$res['msg']=$uid."；".$aid;
			$res['msg']=$user['from_user']."喝彩成功".$user_info['openid'];
		$this->ajaxReturn($res);*/	
		//如果是自己给自己加油就更改状态并返回是否可以继续的标记。
		if($user['from_user']==$user_info['openid'])
		{
			//$this->activity_user->where(array('id'=>$uid))->setInc('total_num')->save(array('per_num'=>1));
			$sql="update cmf_activity_user set total_num=total_num+1,per_num=1 where id=".$uid;
			M()->execute($sql);
			$res['status']=1;
			$res['msg']="喝彩成功";
			$res['goon']=0;
			$res['xinximsg']="自己只能帮自己喝彩一次";
		}
		else
		{
			//获取活动信息。
			$activity=sp_get_activity($aid);
			//获取自己的总共助力次数和当天助力次数。
			if($activity['total_num']>0)
			  {
				  //获取这个人的总加油次数和当天加油次数。
				  $total_num=$this->activity_friend->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->count();
				  if($total_num>=$activity['total_num'])
				  {
					  $res['status']=0;
						$res['msg']="总次数已用完";
						$res['goon']=0;
						$this->ajaxReturn($res);
				  }
			  }
			  if($activity['day_num']>0)
			  {
				  $today = strtotime(date("Y-m-d",time()));
				  $day_num=$this->activity_friend->where("aid=".$aid." and from_user='".$user_info['openid']."' and update_time>=".$today)->count();
				  if($day_num>=$activity['day_num'])
				  {
					  $res['status']=0;
						$res['msg']="当天次数已用完";
						$res['goon']=0;
						$this->ajaxReturn($res);
				  }
			  }
			  //写入数据
			  $frienddata=array('uid'=>$uid,
			  					'aid'=>$aid,
								'from_user'=>$user_info['openid'],
								'nickname'=>$user_info['nickname'],
								'avatar'=>$user_info['headimgurl'],
								'update_time'=>time());
			  $this->activity_friend->add($frienddata);
			  //更新指定的会员。
			  $this->activity_user->where(array('id'=>$uid))->setInc('total_num');
			  //如果还可以再点一次就返回1。
			  $res['goon']=1;
			  if($activity['total_num']==0||($activity['total_num']>0&&$total_num<$activity['total_num']+1))
			  {
				  $res['goon']=0;
				  $res['xinximsg']="总喝彩次数已用完";
			  }
			  else if($activity['day_num']==0||($activity['day_num']>0&&$day_num<$activity['day_num']+1))
			  {
				  $res['goon']=0;
				  $res['xinximsg']="当天喝彩次数已用完";
			  }
			  //判断是否可以继续写入
			  $res['status']=1;
			  $res['msg']="喝彩成功";
		}
		//自己页面加油次数加1
		$num=I('post.total_num',0,'intval');
		$num++;
		$num=$this->splitnum($num);
		$res['total_num']=$num;
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
	/*分割加油总次数*/
	public function splitnum($num)
	{
		$num=strval($num);
		$ling="";
		if(strlen($num)<=6)
		{
			for($i=0;$i<6-strlen($num);$i++)
			{
				$ling.="0";
			}
		}
		else $num="999999";
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
	function analyze_activitys($aid)
	{
		$activity = D("Activity")->where(["id" => $aid, "valid" => 1])->find();
		if (empty($activity)) {
			$this->error("该活动不存在！");
		}
		$currtime = time();
		if ($activity['begintime'] > $currtime) {
			$this->error("活动尚未开始！");
		}
		if ($activity['endtime'] < $currtime) {
			$this->error("活动已结束！");
		}
	}
	/**
	* 分析用户的状态--分析用户的三个指标
	*/
	function sp_analyze_activity_user($user_info,$aid=0){
		$from_user = $user_info['openid'];
		$is_lottery = D("ActivityUser")->where(["aid" => $aid, "from_user" => $from_user])->getField('is_lottery');
		if (!empty($is_lottery)) {
			$this->error("您已抽过奖！");
		}
	}
	/**
	 *宝箱
	 */
	public function gift(){
		$aid = I("get.id",0,"intval");
		$item = sp_get_activity($aid);
		$userInfo = session('userInfo');
		$from_user = $userInfo['openid'];
		$list = $this->activity_user_prize->where(array("aid"=>$aid,"from_user"=>$userInfo['openid']))->order('id desc')->select();
		$url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/bigwheel/index',array('id'=>$aid));;
		$this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' =>$url,
			'imgUrl' =>$item['share_img']
		);
		$this->add_userinfo($aid);
		$this->assign("share",$this->share_data);
		$this->assign("list",$list);
		$this->assign($item);
		$this->display();
	}
	/**
	 * 活动规则
	 */
	public function rule(){
		$aid = I("get.id",0,"intval");
		$item = sp_get_activity($aid);
		$params = sp_get_activity_params($aid);
		$params = array_column($params, 'value', 'field');
		$this->add_userinfo($aid);

		$content_data=sp_content_page($item['rule']);
		$item['rule']=$content_data['content'];
		//分享内容定义区
		$url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/bigwheel/index',array('id'=>$aid));
		$this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' =>$url,
			'imgUrl' =>$item['share_img']
		);
		$this->assign("share",$this->share_data);
		$this->assign($params);
		$this->assign($item);
		$this->display();
	}
	/**
	 * 排行榜
	 */
	public function rank(){
		$aid = I("get.id",0,"intval");
		$item = sp_get_activity($aid);
		$list = $this->activity_user->where("aid = ".$aid." and per_num > 0")->order('per_num desc')->limit(10)->select();
		foreach ($list AS $key => $value) {
			$list[$key]['per_num'] = $value['per_num'] - 1;
		}
		//分享内容定义区
		$url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/bigwheel/index',array('id'=>$aid));
		$this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' =>$url,
			'imgUrl' =>$item['share_img']
		);
		$this->add_userinfo($aid);
		$this->assign("share",$this->share_data);
		$this->assign("list",$list);
		$this->assign($item);
		//业务逻辑待实现
		$this->display();
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
	public function showuser(){
		$uid=I('get.uid',0,'intval');
		$result=$this->activity_user->where(array('id'=>$uid))->select();
		print_r($result);
	}
	public function showfriend(){
		$uid=I('get.uid',0,'intval');
		$result=$this->activity_friend->where(array('uid'=>$uid))->select();
		print_r($result);
	}
	
}

