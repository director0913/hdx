<?php

/**
 * 大转盘助力活动
 */
namespace Apps\Controller;
use Common\Controller\ActivitybaseController; 
class ChallengeController extends ActivitybaseController {
	protected $activity;
	protected $activity_params;
	protected $activity_user;
	protected $activity_user_data;
	protected $activity_user_prize;
	protected $answer_type;
	protected $questionnaire_problem;
	protected $questionnaire_user_data;
	protected $share_data;//定义分享数据
	protected $ctivity_user_answer;
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
		$this->ctivity_user_answer=D('ActivityUserAnswer');
	}
	//个性化字段处理。
	function save_challenge($aid){
		$data = array(
			array("aid"=>$aid,"name"=>"答题时间(秒)","field"=>"game_time","value"=>I('post.game_time')),
			array("aid"=>$aid,"name"=>"题目分值","field"=>"amount","value"=>I('post.amount')),
			array("aid"=>$aid,"name"=>"题目数量","field"=>"question_total","value"=>I('post.question_total')),
			array("aid"=>$aid,"name"=>"挑战成功所需的分数","field"=>"success_num","value"=>I('post.success_num')),
			array("aid"=>$aid,"name"=>"结束提示","field"=>"prompt","value"=>I('post.prompt'))
		);
		sp_save_activity_params($data);
	}
    public function index() {
		set_wechat_info();
	  $aid = I("get.id",0,"intval");
	  $url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/challenge/index',array('id'=>$aid));
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('challenge', 'index', $aid);
		} else {
			$this->entry1($url);
		}
//	  $this->entry1($url);

	  $item = sp_get_activity($aid);
	  if(!$item)
	  {
		  $this->error('抱歉，活动被删除或不存在！');
	  }
		$astatus=1;
		//判断活动是否开始或结束。
		if($item['begintime']>time())
		{
			$astatus=-1;
			$axinxi="活动还未开始。";
		}
		else if($item['endtime']<time())
		{
			$astatus=-1;
			$axinxi="活动已结束。";
		}
	  $params = sp_get_activity_params($aid);
	  $params = array_column($params, 'value', 'field');
	  //获取当前活动下所有题目类型。
	  $typels=$this->answer_type->where(array('aid'=>$aid))->order("listorder Desc")->select();
	  $this->assign("typels",$typels);

	  $this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' => C('DOMAIN_NAME').U('apps/challenge/index',array('id'=>$aid)),
			'imgUrl' =>$item['share_img']
	  );
	  $this->add_userinfo($aid);
	  $user_info=session('userInfo');
	  //获取个人信息。
	  $user=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
		$tody=strtotime(date("Y-m-d",time()));
		//如果不是当天参与的，更新当天参与次数为0。
		if($user['update_time']<$tody)
		{
			$user["day_num"]=0;
			$this->activity_user->where(array('id'=>$user['id']))->save(array('day_num'=>0,'update_time'=>time()));
		}
		//获取会员的参与次数是否有效。
		if($item['per_num']>0&&$user["total_num"]>=$item['per_num'])
		{
			$astatus=-1;
			$axinxi="您已经参加过了，不能再参加了！";
		}
		if($item['day_num']>0&&$user["day_num"]>=$item['day_num'])
		{
			$astatus=-1;
			$axinxi="您今天的参与次数已用完，改天再来吧！";
		}
	  $this->assign("user",$user);
	  $this->assign("astatus",$astatus);
	  $this->assign("axinxi",$axinxi);
	  $this->assign("share",$this->share_data);
	  $this->assign($params);
	  $this->assign($item);
	  $this->assign("aid",$aid);
	  $prizels=$this->activity_user_prize->where(array("aid"=>$aid,'uid'=>$user['id']))->select();
	  $this->assign("prizels",$prizels);
      $this->display();
    }
	
	//开始答题
	public function run(){
		//获取活动id。
        $aid = I("get.aid",0,"intval");
		//获取题目类型id。
        $titype = I("get.titype",0,"intval");
		$url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/challenge/run',array('aid'=>$aid,'titype'=>$titype));
		$this->entry1($url);
		$item = sp_get_activity($aid);
		if (!$item) {
			$this->error('抱歉，活动被删除或不存在！');
		}
		//判断活动是否开始或结束。
		if($item['begintime']>time())
		{
			$this->error('活动还未开始',U('apps/challenge/index',array('id'=>$aid)));
		}
		else if($item['endtime']<time())
		{
			$this->error('活动已结束',U('apps/challenge/index',array('id'=>$aid)));
		}
		$params = sp_get_activity_params($aid);	
		$item['params'] = array_column($params, 'value', 'field');
		$this->add_userinfo($aid);
		//分享内容定义区
		/**
		$this->share_data = array(
			'title'	=>$item['share_title'],
			'desc'  =>$item['share_des'],
			'url' => 'http://'.$_SERVER['HTTP_HOST'].U('apps/challenge/index',array('id'=>$aid)),
			'imgUrl' =>sp_get_asset_upload_path($item['share_img'],true)
		);**/

		$this->share_data = array(
				'title'	=>$item['share_title'],
				'desc'  =>$item['share_des'],
				'url' => C('DOMAIN_NAME').U('apps/challenge/index',array('id'=>$aid)),
				'imgUrl' =>$item['share_img']
		);
		$this->assign("share",$this->share_data);
		$this->assign("item",$item);
		$this->add_userinfo($aid);
		$user_info=session('userInfo');
		//获取个人信息。
		$user=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
		$tody=strtotime(date("Y-m-d",time()));
		//如果不是当天参与的，更新当天参与次数为0。
		if($user['update_time']<$tody)
		{
			$user["day_num"]=0;
			$this->activity_user->where(array('id'=>$user['id']))->save(array('day_num'=>0,'update_time'=>time()));
		}
		//获取会员的参与次数是否有效。
		if($item['per_num']>0&&$user["total_num"]>=$item['per_num'])
		{
			$this->error('您已经参加过了，不能再参加了！',U('apps/challenge/index',array('id'=>$aid)));
		}
		if($item['day_num']>0&&$user["day_num"]>=$item['day_num'])
		{
			$this->error('您今天的参与次数已用完，改天再来吧！',U('apps/challenge/index',array('id'=>$aid)));
		}
		if($user['id']>0)
		{
			//会员当天参与次数和总参与次数+1.
			$sql="update cmf_activity_user set total_num=total_num+1,day_num=day_num+1 where aid=".$aid." and id=".$user['id'];
		 	M()->execute($sql);
			//插入一条答题记录。
			$datidata=array('aid'=>$aid,
							'uid'=>$user['id'],
							'iid'=>$titype,
							'createtime'=>time());
			$this->questionnaire_user_data->add($datidata);
		}
		$this->assign("user",$user);
		//获取当前分类下的所有题目。
		$answerls=$this->questionnaire_problem->where(array("aid"=>$aid,'type_id'=>$titype))->select();
		$countti=intval($item['params']['question_total'])>count($answerls)?count($answerls):intval($item['params']['question_total']);
		//打乱数组顺序，随机选取
		shuffle($answerls);
		$timuls=array();
		if($countti<count($answerls))
		{
			for($i=0;$i<$countti;$i++)
				$timuls[]=$answerls[$i];
		}
		else $timuls=$answerls;
		//真实答案
		foreach($timuls as &$vo){
			$vo['daan_str']=1;
			if($vo['right']==1){
				$vo['daan_str']=$vo['item1'];	
			}elseif($vo['right']==2){
				$vo['daan_str']=$vo['item2'];	
			}elseif($vo['right']==3){
				$vo['daan_str']=$vo['item3'];	
			}elseif($vo['right']==4){
				$vo['daan_str']=$vo['item4'];	
			}
			
		}
		$this->assign("timuls",$timuls);
		$this->assign("aid",$aid);
		$prizels=$this->activity_user_prize->where(array("aid"=>$aid,'uid'=>$user['id']))->select();
		$this->assign("prizels",$prizels);
		$this->display("answer");
	}
	//在奖品表插入答题记录
	public function dati_put(){
		$aid = I("get.aid",0,"intval");
		$round = I("post.round",0,"intval");
		$type = I("post.type",0,"intval");
		$pid=I("post.pid",0,"intval");
		$answer = I("post.answer");
		$is_right = I("post.is_right",0,"intval");
		
		$user_info=session('userInfo');
		$user=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
		
		//往奖品表写数据
		$data['aid']=$aid;
		$data['uid']=$user['id'];
		$data['username']=$user['username'];
		$data['round']=$round;
		$data['type']=$type;
		$data['problem_id']=$pid;
		$data['answer']=$answer;
		$data['is_right']=$is_right;
		$data['createtime']=time();
		$this->ctivity_user_answer->add($data);
		//$this->activity_user_prize
		$res['status']=1;
		$res['info']='aid'.$data['aid'].'uid'.$data['uid'].'username'.$data['username'].'round'.$data['round'].'type'.$data['type'].'problem_id'.$data['problem_id'].'answer'.$data['answer'].'is_right'.$data['is_right'].'time'.$data['createtime'];
		$this->ajaxReturn($res);
	}
	public function show_p(){
		$result=$this->activity_user_prize->limit(3)->select();
		var_dump($result);
	}
	//更改个人答题记录，只修改最后插入的那一条。
	public function editdata()
    {
        $aid = I("get.aid",0,"intval");
        $uid = I("get.uid",0,"intval");
		//记录得分信息。
        $defen = I("post.defen",0,"intval");
		$res=$uid>0?$this->questionnaire_user_data->where(array('uid'=>$uid,'aid'=>$aid))->order("id Desc")->find():false;
		$result['status']=$res?1:0;
		if($result['status'])
		{
			//更改指定的记录。
			$this->questionnaire_user_data->where(array("id"=>$res['id']))->save(array("qid"=>$defen));
		}
		$this->ajaxReturn($result);
	}
	//更改个人的最高分。
	public function editmax()
    {
        $aid = I("get.aid",0,"intval");
        $uid = I("get.uid",0,"intval");
        $maxfen = I("post.maxfen",0,"intval");
		$res=$uid>0?$this->activity_user->where(array('id'=>$uid,'aid'=>$aid))->save(array('per_num'=>$maxfen)):false;
		$result['status']=$res?1:0;
		if($result['status'])
		{
			//获取总参与人数
			$zongcanjia=$this->activity_user->where(array('aid'=>$aid))->count();
			//获取低于当前分的人数。
			$difen=$this->activity_user->where('aid='.$aid." and per_num<".$maxfen)->count();
			$result['zongge']=$zongcanjia;
			$result['dige']=$difen;
			$result['jibai']=round(100/$zongcanjia*($zongcanjia-$difen),2);
		}
		$this->ajaxReturn($result);
	}
	//计算个人打败比例。
	public function calculated()
    {
        $aid = I("get.aid",0,"intval");
        $fen = I("post.fen",0,"intval");
		//获取总参与人数
		$zongcanjia=$this->activity_user->where(array('aid'=>$aid))->count();
		//获取低于当前分的人数。
		$difen=$this->activity_user->where('aid='.$aid." and per_num<".$fen)->count();
		$result['jibai']=round(100/$zongcanjia*($zongcanjia-$difen),2);
		$result['status']=1;
		$this->ajaxReturn($result);
	}
	//获取应得的奖品信息。
	public function getjiang()
    {
		$id=I('get.id',0,'intval');
		$item = sp_get_activity($id);
		//获取当前活动奖品信息。
		$params = $this->activity_params->where("aid=$id")->select();
		$params=array_column($params, 'value', 'field');
		//$params = sp_get_activity_params($id);
		if(!empty(cookie('challenge_jiang')))
		{
			cookie('challenge_jiang',null);
		}
		//获取当前用户信息。
		$user_info=session('userInfo');
		$user=$this->activity_user->where(array('aid'=>$id,'from_user'=>$user_info['openid']))->find();
		$zhi=-1;
		if($user['award_num']>=$item['total_num'])
		{
			$zhi=0;
		}
		if($zhi==-1)
		{
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
				cookie('challenge_jiang',$zhi,3600*7*24);
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
							cookie('challenge_jiang',$zhi,3600*7*24);
							break;
						}
					}
				}
				else     //如果用户已经注册
				{
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
								cookie('challenge_jiang',($zhi),3600*7*24);
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
				//更改个人中奖次数。
				$sql="update cmf_activity_user set award_num=award_num+1 where aid=".$id." and id=".$user['id'];
				M()->execute($sql);
			}
			$jiang=array('type'=>$params['prize'.$zhi.'_type'],'name'=>$params['prize'.$zhi.'_name'],'thumb'=>$params['prize'.$zhi.'_thumb']);
			$result['jiang']=$jiang;
		}
		$result['status']=$zhi;
		$this->ajaxReturn($result);
	}
	/*记录中奖用户信息*/
	public function getPrize()
	{
		$aid=I('get.aid',0,'intval');
		$data['username']=I('post.name');
		$data['mobile']=I('post.mobile');
		$zhi=cookie('challenge_jiang');
		//根据奖值获取奖品。
		$params = $this->activity_params->where("aid=$aid and (field='prize".$zhi."_name' or field='prize".$zhi."_type' or field='prize".$zhi."_thumb')")->select();
		$jiang=array_column($params, 'value', 'field');
		$user_info=session('userInfo');
		$user=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
		if($user)
		{
			if(empty($user['mobile']))
			{
				//更改个人信息。
				$this->activity_user->where(array('id'=>$user['id']))->save($data);
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
		}
		else
		{
			$res['status']=-1;
			$res['msg']="添加中奖用户失败";
		}
		$this->ajaxReturn($res);
	}
	//获取排行信息。
	function getsort()
	{
		$aid = I("get.id",0,"intval");
		$page = I("post.page",0,"intval");
		$size=15;
		if($page<=1)
		{
			//获取数据总条数。
			$count=$this->activity_user->where("aid=".$aid." and total_num>0")->count();
			//获取总页数。
			$pagecount=$count>0?ceil($count/$size):1;
		}
		//获取指定页码中的数据。
		$list=$this->activity_user->where("aid=".$aid." and total_num>0")->order("per_num Desc")->limit((($page-1)*$size).",".$size)->select();
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
	
	function test(){
		echo "111";
		$result=D('ActivityUserAnswer')->select();
		var_dump($result);
	}
	
	
	
}

