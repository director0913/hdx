<?php

/**
 * 大转盘助力活动
 */
namespace Apps\Controller;
use Common\Controller\ActivitybaseController; 
class WeipriceController extends ActivitybaseController {
	protected $activity;
	protected $activity_params;
	protected $activity_user;
	protected $activity_user_data;
	protected $activity_user_prize;
	protected $answer_type;
	protected $questionnaire_problem;
	protected $questionnaire_user_data;
	protected $weiprice_rule;
	protected $activity_friend;
	protected $share_data;//定义分享数据
	function _initialize(){
		parent::_initialize();
		$this->activity = D("Activity");
		$this->activity_params = D("ActivityParams");
		$this->activity_user = D("ActivityUser");
		$this->activity_user_data = D("ActivityUserData");
		$this->activity_user_prize = D("ActivityUserPrize");
		$this->answer_type = D("AnswerType");
		$this->questionnaire_problem = D("QuestionnaireProblem");
		$this->questionnaire_user_data=D("QuestionnaireUserData");
		$this->weiprice_rule=D("WeipriceRule");
		$this->activity_friend = D("ActivityFriend");
	}
	//对个性化字段的处理。
	function save_weiprice($aid,$yuanaid){
		//保存扩展字段。
		$data = array(
			array("aid"=>$aid,"name"=>"背景音乐","field"=>"bg","value"=>I('post.bg')),
			array("aid"=>$aid,"name"=>"关注引导链接","field"=>"follow_url","value"=>I('post.follow_url')),
			array("aid"=>$aid,"name"=>"商品名称","field"=>"p_name","value"=>I('post.p_name')),
			array("aid"=>$aid,"name"=>"商品描述","field"=>"p_dec","value"=>I('post.p_dec')),
			array("aid"=>$aid,"name"=>"商品缩略图","field"=>"p_preview_pic","value"=>I('post.p_preview_pic')),
			array("aid"=>$aid,"name"=>"商品库存","field"=>"p_kc","value"=>I('post.p_kc'),0,"intval"),
			array("aid"=>$aid,"name"=>"商品原价","field"=>"p_y_price","value"=>I('post.p_y_price'),0,"floatval"),
			array("aid"=>$aid,"name"=>"商品最低价","field"=>"p_low_price","value"=>I('post.p_low_price'),0,"floatval"),
			array("aid"=>$aid,"name"=>"报名需填信息","field"=>"other_remark","value"=>I('post.other_remark')),
			array("aid"=>$aid,"name"=>"校区","field"=>"other_school","value"=>I('post.other_school')),
			array("aid"=>$aid,"name"=>"年龄","field"=>"age","value"=>I('post.age')),
			array("aid"=>$aid,"name"=>"班级","field"=>"cless","value"=>I('post.cless')),
			array("aid"=>$aid,"name"=>"学校","field"=>"school","value"=>I('post.school')),
			array("aid"=>$aid,"name"=>"其他","field"=>"qita","value"=>I('post.qita')),
			array("aid"=>$aid,"name"=>"是否需要支付","field"=>"is_pay","value"=>I('post.is_pay')),
			array("aid"=>$aid,"name"=>"支付二维码","field"=>"pay_thumb","value"=>I('post.pay_thumb')),
		);
		sp_save_activity_params($data);
		//获取所有填写的信息。
		$rule_id=I('post.rule_id');
		//$rule_pice=I('post.rule_pice');
		$rule_pice=array();
		$rule_piceshi=I('post.rule_piceshi');
		$rule_picezhi=I('post.rule_picezhi');
		for($i=0;$i<count($rule_id);$i++)
		{
			$rule_piceshi[$i]=$rule_piceshi[$i]==""?0:$rule_piceshi[$i];
			$rule_picezhi[$i]=$rule_picezhi[$i]==""?0:$rule_picezhi[$i];
			if($rule_piceshi[$i]>$rule_picezhi[$i])
			{
				$linshi=$rule_piceshi[$i];
				$rule_piceshi[$i]=$rule_picezhi[$i];
				$rule_picezhi[$i]=$linshi;
			}
			$rule_pice[]=$rule_piceshi[$i]==0&&$rule_picezhi[$i]==0?0:$rule_piceshi[$i]."、".$rule_picezhi[$i];
		}
		$rule_start=I('post.rule_start');
		$rule_end=I('post.rule_end');
		if($yuanaid==$aid)
		{
			//将所有的id拼接起来
			$newrule_id="0";
			for($i=0;$i<count($rule_id);$i++)
			{
				if($rule_id[$i]>0) $newrule_id.=",".$rule_id[$i];
			}
			//删除不存在的id。
			D("WeipriceRule")->where('aid='.$aid.' and id not in('.$newrule_id.')')->delete();
		}
		//遍历数据，如果有id就更新数据，没有id就插入数据。
		for($i=0;$i<count($rule_id);$i++)
		{
			$data=array('pice'=>$rule_pice[$i],
						'start'=>$rule_start[$i],
						'end'=>$rule_end[$i]);
			if($rule_id[$i]>0&&$yuanaid==$aid)
			{
				D("WeipriceRule")->where('id='.$rule_id[$i])->save($data);
			}
			else
			{
				$data['aid']=$aid;
				D("WeipriceRule")->add($data);
			}
		}
		return true;
	}
    public function index()
	{
		set_wechat_info();
		// 获取分享id。
		$aid = I("get.id",0,"intval");
		$uid = I("get.uid",0,"intval");
		$url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/weiprice/index',array('id'=>$aid,'uid'=>$uid));
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('weiprice', 'index', $aid, $uid);
		} else {
			$this->entry($url);
		}
		$item = sp_get_activity($aid);
		if (!$item) {
			$this->error('抱歉，活动被删除或不存在！');
		}
		$astatus=1;
		// 判断活动是否开始或结束。
		if ($item['begintime']>time()) {
			$astatus = -1;
			$axinxi = "活动还未开始。";
		} else if($item['endtime']<time()) {
			$astatus = -1;
			$axinxi = "活动已结束。";
		}
		// 获取活动额外信息。
		$params = sp_get_activity_params($aid);
		$item['params'] = array_column($params, 'value', 'field');
		$user_info = session('userInfo');
		if (empty($user_info['openid'])) {
			$result = ['status'=>0, 'msg'=>"您的信息获取失败，请重新打开！"];
			$this->ajaxReturn($result);
		}
		// 获取个人信息。
		$user = $this->activity_user->where(['aid' => $aid, 'from_user' => $user_info['openid']])->find();
		$sharearr = ['id' => $aid];
		if ($uid > 0) {
			$sharearr['uid'] = $uid;
		} else if($user['mobile']) {
			$sharearr['uid'] = $user['id'];
		}
		// 分享内容定义区
		$share_url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/weiprice/index',$sharearr);
		$this->share_data = array(
			'title'	=> $item['share_title'],
			'desc'  => $item['share_des'],
			'url' => $share_url,
			'imgUrl' => $item['share_img']
		);
		$this->assign("user",$user);
		$this->assign("astatus",$astatus);
		$this->assign("axinxi",$axinxi);
		$this->assign("share",$this->share_data);
		$this->assign("item",$item);
		$this->assign("aid",$aid);
		$this->assign("uid",$uid);
		// 如果自己没有参与且不是分享过来的页面，就到注册页面。
		if (!$user['mobile'] && $uid==0) {
			$this->display("regist");
		} else {
			// 如果没有uid或者uid等于自己di，说明是自己。
			$isme = $uid>0 && $uid != $user['id'] ? false : true;
			// 如果有分享id，就获取分享人信息。否则就指定信息为分享人信息。
			if(!$isme) {
				$shareuser = $this->activity_user->where(['aid' => $aid, 'id' => $uid])->find();
			} else {
				$shareuser = $user;
			}
			// 当前价=总价减去个人砍掉价。
			$dqprice = $item['params']['p_y_price'] - $shareuser['price'];
			// 进度=被砍掉的价格除以（总价-最低价）。
			$jindu = intval($shareuser['price'] / ($item['params']['p_y_price'] - $item['params']['p_low_price']) * 100);
			$this->assign("dqprice", $dqprice);
			$this->assign("jindu", $jindu);
			$this->assign("shareuser", $shareuser);
			$this->assign("isme", $isme);
			$this->display();
		}
    }
	//执行注册。
	public function regist(){
		$aid = I("get.id",0,"intval");
		$uid = I("get.uid",0,"intval");
		//查看商品数量。
		$params = sp_get_activity_params($aid);
		$params = array_column($params, 'value', 'field');
		if($params['p_kc']<=0)
		{
			$result=array('status'=>0,
						  'msg'=>"商品数量不足！");
			$this->ajaxReturn($result);
		}
		$data=array(
			'username'=>I("post.name"),
			'mobile'=>I("post.mobile"),
			'age'=>I("post.age"),
			'class'=>I("post.cless"),
			'school'=>I("post.school"),
			'remark'=>I("post.remark"),
		);
		if($data['username']=="")
		{
			$result=array('status'=>0,
						  'msg'=>"姓名不能为空！");
			$this->ajaxReturn($result);
		}
		if($data['mobile']=="")
		{
			$result=array('status'=>0,
						  'msg'=>"手机号不能为空！");
			$this->ajaxReturn($result);
		}
		if(strlen($data['mobile'])!=11)
		{
			$result=array('status'=>0,
						  'msg'=>"手机号不正确！");
			$this->ajaxReturn($result);
		}
		
		$user_info=session('userInfo');
		$data['from_user']=$user_info['openid'];
		$data['nickname']=$user_info['nickname'];
		$data['avatar']=$user_info['headimgurl'];
		$time=time();
		$data['createtime']=$time;
		$data['update_time']=$time;
		$data['aid']=$aid;
		
		//修改姓名和手机号。
		$result=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
		if($result){
			$res=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->save($data);
		}else{
			$res=$this->activity_user->add($data);
		}
		if($res)
		{
			//商品数量-1;
			$sql="update cmf_activity_params set value=value-1 where aid=".$aid." and field='p_kc'";
			M()->execute($sql);
		}
		$result=array('status'=>$res?1:0,
					  'msg'=>$res?"":"注册失败！");
		$this->ajaxReturn($result);
	}
	//执行砍价
	public function kanjia(){
		$aid = I("get.id",0,"intval");
		$uid = I("get.uid",0,"intval");
		
		$t=I('get.t',0,'intval');
		if($t==1){
			
			$this->analyze_activitys_friend($aid,$uid);
		}
		
		//获取活动信息。
		$item = sp_get_activity($aid);
		//获取活动附加信息。
		$params = sp_get_activity_params($aid);
		$item['params'] = array_column($params, 'value', 'field');
		//获取这个人信息。
		$user_info=session('userInfo');
		//判断次数限制。
		if($item['total_num']>0)
		{
			//获取这个人为对应的人的砍价记录。
			$count=$this->activity_friend->where(array('aid'=>$aid,'uid'=>$uid,'from_user'=>$user_info['openid']))->count();
			if($count>=$item['total_num'])
			{
				$result=array('status'=>0,
							  'msg'=>"你已经帮TA砍过了！");
				$this->ajaxReturn($result);
			}
		}
		if($item['day_num']>0)
		{
			$todaytime=strtotime(date("Y-m-d",time()));
			//获取这个人为对应的人的砍价记录。
			$count=$this->activity_friend->where("aid=".$aid." and uid=".$uid." and from_user='".$user_info['openid']."' and createtime>=".$todaytime)->count();
			if($count>=$item['day_num'])
			{
				$result=array('status'=>0,
							  'msg'=>"你今天已经帮TA砍过了！");
				$this->ajaxReturn($result);
			}
		}
		//获取被砍价用户的信息。
		$user=$this->activity_user->where(array('aid'=>$aid,'id'=>$uid))->find();
		if(!$user)
		{
			$result=array('status'=>0,
						  'msg'=>"分享人信息不存在！");
			$this->ajaxReturn($result);
		}
		//当前价=总价减去个人砍掉价。
		$dqprice=$item['params']['p_y_price']-$user['price'];
		//如果当前价小于等于最低价。
		if($dqprice<=$item['params']['p_low_price'])
		{
			$result=array('status'=>0,
						  'msg'=>"已经是最低价了，不能再砍了！");
			$this->ajaxReturn($result);
		}
		//获取所有砍价规则范围。
		$fanweils=$this->weiprice_rule->where(array("aid"=>$aid))->select();
		$fanweiqi=0;
		$fanweizhi=0;
		//判断价格属于哪个砍价范围内。
		for($i=0;$i<count($fanweils);$i++)
		{
			//拆分砍价范围。
			$fanwei=explode("、",$fanweils[$i]['pice']);
			if(count($fanwei)==1&&$fanwei[0]=="0")
			{
				$fanweiqi=$fanweils[$i]['start']>$fanweils[$i]['end']?$fanweils[$i]['end']:$fanweils[$i]['start'];
				$fanweizhi=$fanweils[$i]['start']>$fanweils[$i]['end']?$fanweils[$i]['start']:$fanweils[$i]['end'];
			}
			else
			{
				if(count($fanwei)==1&&$dqprice<=intval($fanwei[0]))
				{
					$fanweiqi=$fanweils[$i]['start']>$fanweils[$i]['end']?$fanweils[$i]['end']:$fanweils[$i]['start'];
					$fanweizhi=$fanweils[$i]['start']>$fanweils[$i]['end']?$fanweils[$i]['start']:$fanweils[$i]['end'];
				}
				else if(count($fanwei)>=2)
				{
					//范围顺序调整。
					$linshi=$fanwei[0];
					if($fanwei[0]>$fanwei[1])
					{
						$fanwei[0]=$fanwei[1];
						$fanwei[1]=$linshi;
					}
					//范围判断。
					if($dqprice>=intval($fanwei[0])&&$dqprice<=intval($fanwei[1]))
					{
						$fanweiqi=$fanweils[$i]['start']>$fanweils[$i]['end']?$fanweils[$i]['end']:$fanweils[$i]['start'];
						$fanweizhi=$fanweils[$i]['start']>$fanweils[$i]['end']?$fanweils[$i]['start']:$fanweils[$i]['end'];
					}
				}
			}
		}
		//生成随机数价格。
		$fanwei=rand(intval($fanweiqi*100),intval($fanweizhi*100))/100;
		//判断当前价格减去生成的范围后是否小于最低价。
		$fanwei=$dqprice-$fanwei<$item['params']['p_low_price']?$dqprice-$item['params']['p_low_price']:$fanwei;
		//更改被砍价人的价格。
		$update_time = time();
		$sql="update cmf_activity_user set update_time=".$update_time." ,price=price+".$fanwei." where aid=".$aid." and id=".$uid;
		M()->execute($sql);
		//生成砍价记录。
		$kandata=array('uid'=>$uid,
					   'aid'=>$aid,
					   'from_user'=>$user_info['openid'],
					   'nickname'=>$user_info['nickname'],
					   'avatar'=>$user_info['headimgurl'],
					   'price'=>$fanwei,
					   'createtime'=>time());
		$res=$this->activity_friend->add($kandata);
		if($res)
		{
			//进度=被砍掉的价格除以（总价-最低价）。
			$jindu=intval(($user['price']+$fanwei)/($item['params']['p_y_price']-$item['params']['p_low_price'])*100);
			//当前剩余价格等于总价减去用户价格减去生成的价格。
			$jiage=$item['params']['p_y_price']-($user['price']+$fanwei);
			$result=array('status'=>1,
						  'fanwei'=>$fanwei,
						  'jindu'=>$jindu,
						  'jiage'=>$jiage);
		}
		else
		{
			$result=array('status'=>0,
						  'msg'=>'砍价失败');
		}
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
		
		//获取活动信息。
		$item = sp_get_activity($aid);
		//获取活动附加信息。
		$params = sp_get_activity_params($aid);
		$item['params'] = array_column($params, 'value', 'field');
		//判断次数限制。
		if($item['total_num']>0)
		{
			//获取这个人为对应的人的砍价记录。
			$count=$this->activity_friend->where(array('aid'=>$aid,'uid'=>$uid,'from_user'=>$userInfo['openid']))->sum('total_num');
			if($count>=$item['total_num'])
			{
				$result=array('status'=>0,
							  'msg'=>"你已经帮TA砍过了！");
				$this->ajaxReturn($result);
			}
		}
		if($item['day_num']>0)
		{
			$todaytime=strtotime(date("Y-m-d",time()));
			//获取这个人为对应的人的砍价记录。
			$count=$this->activity_friend->where(array('aid'=>$aid,'uid'=>$uid,'from_user'=>$userInfo['openid']))->sum('day_num');
			if($count>=$item['day_num'])
			{
				$result=array('status'=>0,
							  'msg'=>"你今天已经帮TA砍过了！");
				$this->ajaxReturn($result);
			}
		}
		//获取被砍价用户的信息。
		$user=$this->activity_user->where(array('aid'=>$aid,'id'=>$uid))->find();
		if(!$user)
		{
			$result=array('status'=>0,
						  'msg'=>"分享人信息不存在！");
			$this->ajaxReturn($result);
		}
		//当前价=总价减去个人砍掉价。
		$dqprice=$item['params']['p_y_price']-$user['price'];
		//如果当前价小于等于最低价。
		if($dqprice<=$item['params']['p_low_price'])
		{
			$result=array('status'=>0,
						  'msg'=>"已经是最低价了，不能再砍了！");
			$this->ajaxReturn($result);
		}
		//获取所有砍价规则范围。
		$fanweils=$this->weiprice_rule->where(array("aid"=>$aid))->select();
		$fanweiqi=0;
		$fanweizhi=0;
		//判断价格属于哪个砍价范围内。
		for($i=0;$i<count($fanweils);$i++)
		{
			//拆分砍价范围。
			$fanwei=explode("、",$fanweils[$i]['pice']);
			if(count($fanwei)==1&&$fanwei[0]=="0")
			{
				$fanweiqi=$fanweils[$i]['start']>$fanweils[$i]['end']?$fanweils[$i]['end']:$fanweils[$i]['start'];
				$fanweizhi=$fanweils[$i]['start']>$fanweils[$i]['end']?$fanweils[$i]['start']:$fanweils[$i]['end'];
			}
			else
			{
				if(count($fanwei)==1&&$dqprice<=intval($fanwei[0]))
				{
					$fanweiqi=$fanweils[$i]['start']>$fanweils[$i]['end']?$fanweils[$i]['end']:$fanweils[$i]['start'];
					$fanweizhi=$fanweils[$i]['start']>$fanweils[$i]['end']?$fanweils[$i]['start']:$fanweils[$i]['end'];
				}
				else if(count($fanwei)>=2)
				{
					//范围顺序调整。
					$linshi=$fanwei[0];
					if($fanwei[0]>$fanwei[1])
					{
						$fanwei[0]=$fanwei[1];
						$fanwei[1]=$linshi;
					}
					//范围判断。
					if($dqprice>=intval($fanwei[0])&&$dqprice<=intval($fanwei[1]))
					{
						$fanweiqi=$fanweils[$i]['start']>$fanweils[$i]['end']?$fanweils[$i]['end']:$fanweils[$i]['start'];
						$fanweizhi=$fanweils[$i]['start']>$fanweils[$i]['end']?$fanweils[$i]['start']:$fanweils[$i]['end'];
					}
				}
			}
		}
		//生成随机数价格。
		$fanwei=rand(intval($fanweiqi*100),intval($fanweizhi*100))/100;
		//判断当前价格减去生成的范围后是否小于最低价。
		$fanwei=$dqprice-$fanwei<$item['params']['p_low_price']?$dqprice-$item['params']['p_low_price']:$fanwei;
		//更改被砍价人的价格。
		$this->activity_user->where(array('id'=>$uid))->setInc('price',$fanwei);
		$userdata['update_time']=time();
		$this->activity_user->where(array('id'=>$uid))->save($userdata);		
		//生成砍价记录。		
		$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->setInc('day_num');
		$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->setInc('total_num');
		$friend=$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->find();
		$frienddata['price']=intval($friend['price'])+$fanwei;
		$frienddata['update_time']=time();
		$res=$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid,"from_user"=>$userInfo['openid']))->save($frienddata);
		if($res)
		{
			//进度=被砍掉的价格除以（总价-最低价）。
			$jindu=intval(($user['price']+$fanwei)/($item['params']['p_y_price']-$item['params']['p_low_price'])*100);
			//当前剩余价格等于总价减去用户价格减去生成的价格。
			$jiage=$item['params']['p_y_price']-($user['price']+$fanwei);
			$result=array('status'=>1,
						  'fanwei'=>$fanwei,
						  'jindu'=>$jindu,
						  'jiage'=>$jiage);
		}
		else
		{
			$result=array('status'=>0,
						  'msg'=>'砍价失败');
		}
		$this->ajaxReturn($result);
	}
	function jilu()
	{
		$aid = I("get.id",0,"intval");
		$uid = I("get.uid",0,"intval");
		$page = I("post.page",0,"intval");
		$size=15;
		if($page<=1)
		{
			//获取数据总条数。
			$count=$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid))->count();
			//获取总页数。
			$pagecount=$count>0?ceil($count/$size):1;
		}
		//获取指定页码中的数据。
		$list=$this->activity_friend->where(array("aid"=>$aid,"uid"=>$uid))->order("id Desc")->limit((($page-1)*$size).",".$size)->select();
		$result=array('status'=>1,
					  'pagecount'=>$pagecount,
					  'list'=>$list);
		$this->ajaxReturn($result);
	}
	//获取排行信息。
	function paihang()
	{
		$aid = I("get.id",0,"intval");
		$page = I("post.page",0,"intval");
		$size=15;
		if($page<=1)
		{
			//获取数据总条数。
			$count=$this->activity_user->where("aid=".$aid." and mobile is not null")->count();
			//获取总页数。
			$pagecount=$count>0?ceil($count/$size):1;
		}
		//获取指定页码中的数据。
		$list=$this->activity_user->where("aid=".$aid." and mobile is not null")->order("price Desc,update_time asc")->limit((($page-1)*$size).",".$size)->select();
		//更改用户的手机号，防止在前台更改被其他人获取。
		for($i=0;$i<count($list);$i++)
		{
			$list[$i]['mobile']=$list[$i]['mobile']!=""?substr($list[$i]['mobile'],0,3)."****".substr($list[$i]['mobile'],-4):"";
		}
		$result=array('status'=>1,
					  'pagecount'=>$pagecount,
					  'list'=>$list);
		$this->ajaxReturn($result);
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
	
	
	//显示用户
	public function showuser(){
		$result=$this->activity_user->where('id=950474')->select();
		print_r($result);
	}
	public function showfriend(){
		$result=$this->activity_friend->where('uid=950474')->select();
		print_r($result);
	}
	
	
	
	
}

