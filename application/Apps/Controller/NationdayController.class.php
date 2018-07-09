<?php

/**
 * 国庆拆礼盒
 */
namespace Apps\Controller;
use Common\Controller\ActivitybaseController; 
class NationdayController extends ActivitybaseController {
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
	}
	//对个性化字段的处理。
	function save($aid){
		$data = array(
			array("aid"=>$aid,"name"=>"礼盒一名称","field"=>"prize_name1","value"=>I('post.prize_name1')),
			array("aid"=>$aid,"name"=>"礼盒一数量","field"=>"prize_num1","value"=>I('post.prize_num1',0,'intval')),
			array("aid"=>$aid,"name"=>"礼盒二名称","field"=>"prize_name2","value"=>I('post.prize_name2')),
			array("aid"=>$aid,"name"=>"礼盒二数量","field"=>"prize_num2","value"=>I('post.prize_num2',0,'intval')),
			array("aid"=>$aid,"name"=>"礼盒三名称","field"=>"prize_name3","value"=>I('post.prize_name3')),
			array("aid"=>$aid,"name"=>"礼盒三数量","field"=>"prize_num3","value"=>I('post.prize_num3',0,'intval')),
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
		$this->assign("aid",$aid);
		//看是否是分享过来的页面。
		$uid=I('get.uid',0,'intval');
		//定义分享链接。
		$url = 'http://'.$_SERVER['HTTP_HOST'].U('apps/nationday/index',array('id'=>$aid,'uid'=>$uid));
		//检查浏览器及获取公众号信息。
		if (!session('userInfo') && C('DOMAIN_NAME') != 'http://xiu.xiaoyingtong.net') {
			get_wechat_info('nationday', 'index', $aid, $uid);
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
			$status=-2;
			$xinximsg="活动已结束";
		}
		$params = sp_get_activity_params($aid);	
		$activity['params'] = array_column($params, 'value', 'field');
		$this->assign("params",$activity['params']);
		//$this->add_userinfo($aid);
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
			        $his_info=$this->activity_user->where(array('aid'=>$aid,'id'=>$uid))->find();
					$this->assign('his_info',$his_info);
				}
			}
			else
			{
				$this->error('分享人信息不存在！',U("apps/nationday/index",array("id"=>$aid,"sui"=>time())));
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
		if($isme==1){
			//是自己把奖品情况
			$mylibao_list=D('ActivityUserPrize')->order('createtime asc')->where(array('aid'=>$aid,'uid'=>$user['id']))->select();
			$this->assign('mylibao_list',$mylibao_list);
		}
		$this->assign("chuanuid",$chuanuid);
		$friend_list=array();
		//如果有分享人信息就改变分享链接。
		//$random_url = time();
		if($shareuid!=0)
		{
			$share_url='http://'.$_SERVER['HTTP_HOST'].U('apps/nationday/index',array('id'=>$aid,'uid'=>$shareuid));
			//$url='http://'.$random_url.'.qihuangongfang.com'.U('apps/nationday/index',array('id'=>$aid,'uid'=>$shareuid));
			$where = " aid=$aid and uid=$shareuid and from_user is not null";
			$friend_list=$this->activity_friend->order('createtime asc')->where($where)->select();
			$friend_list1=array();
			$friend_list2=array();
			$friend_list3=array();
			$fnum1=0;
			$fnum2=0;
			$fnum3=0;
			for($i=0;$i<7;$i++){
				$friend_list1[]=$friend_list[$i];
				if($friend_list[$i]['from_user']){$fnum1++;}
			}
			for($i=7;$i<14;$i++){
				$friend_list2[]=$friend_list[$i];
				if($friend_list[$i]['from_user']){$fnum2++;}
			}
			for($i=14;$i<21;$i++){
				$friend_list3[]=$friend_list[$i];
				if($friend_list[$i]['from_user']){$fnum3++;}
			}		
		}
		//否则，分享链接是自己的。
		else
		{
			$share_url='http://'.$_SERVER['HTTP_HOST'].U('apps/nationday/index',array('id'=>$aid));
			$uuid = intval($user['id']);
			$where = " aid=$aid and uid=$uuid and from_user is not null";
			$friend_list=$this->activity_friend->order('createtime asc')->where($where)->select();
			$friend_list1=array();
			$friend_list2=array();
			$friend_list3=array();
			$fnum1=0;
			$fnum2=0;
			$fnum3=0;
			for($i=0;$i<7;$i++){
				$friend_list1[]=$friend_list[$i];
				if($friend_list[$i]['from_user']){$fnum1++;}
			}
			for($i=7;$i<14;$i++){
				$friend_list2[]=$friend_list[$i];
				if($friend_list[$i]['from_user']){$fnum2++;}
			}
			for($i=14;$i<21;$i++){
				$friend_list3[]=$friend_list[$i];
				if($friend_list[$i]['from_user']){$fnum3++;}
			}
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
		$this->assign("friend_list",$friend_list);
		$this->assign("friend_list1",$friend_list1);
		$this->assign("friend_list2",$friend_list2);
		$this->assign("friend_list3",$friend_list3);
		$this->assign('fnum1',$fnum1);
		$this->assign('fnum2',$fnum2);
		$this->assign('fnum3',$fnum3);
		//print_r($fnum1.">>".$fnum2.">>".$fnum3);
		$this->assign("share",$this->share_data);
		$this->assign("activity",$activity);
		$this->assign("share_info",$share_info);
		$this->assign("user_info",$user_info);
		//如果是自己，判断自己的一次加油是否被使用了，如果total_num==0可以给自己加油。
		//如果是给别人加油，判断自己的加油总次数。
		$this->assign("cando",$cando);
		$this->assign("xinximsg",$xinximsg);
		$this->assign("friend_list",$friend_list);
//		$this->assign("bing_list",$bing_list);
		//统计参与人员的数量。
		$canyucount=20+$this->activity_user->where('aid='.$aid.' and mobile is not null')->count();
		$this->assign("canyucount",$canyucount);
		$this->display();
    }
	/*注册+ 生成选择礼盒*/
	public function register()
	{
		$user_info=session('userInfo');
		$aid=I('get.id',0,'intval');
		//day_num记录选择几个礼盒
		$data=array(
			'from_user'=>$user_info['openid'],
			'nickname'=>$user_info['nickname'],
			'avatar'=>$user_info['headimgurl'],
			'createtime'=>time(),
			'update_time'=>time(),
			'aid'=>$aid,
			'username'=>I("post.name"),
			'mobile'=>I("post.mobile"),
			'age'=>I("post.age"),
			'class'=>I("post.cless"),
			'school'=>I("post.school"),
			'remark'=>I("post.remark"),
			'day_num'=>I('post.lihe_num',0,'intval'),
			'course'=>'',
		);
		//获取之前保存的礼盒
		$lihe_old=array();
		$info=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
		if($info['course']){
			$data['course']=$info['course'];
			//原来的礼盒
			$lihe_old=$info['course'];
			$lihe_old=str_split($lihe_old,1);
		}
		//对应的图片
		$lihe_select=I('post.lihe_select',0,'intval');
		$arr=str_split($lihe_select,1);

		for ($i = 0; $i <= 2; $i++) {
			if ($i == 0) {
				$data['course'].= $arr[$i];
			} else {
				$data['course'].="、".$arr[$i];
			}
		}
//		foreach($arr as $tmp){
//			//判断是否是旧礼盒
//			$is_old=0;
////			foreach($lihe_old as $old){
////				if($tmp==$old){
////					$is_old=1;
////				}
////			}
//			if($is_old==0){
//
//			}else{
//				$data['course'].="、".$tmp;
//			}
//			$is_old++;
//		}
//		$data['course']=substr($data['course'],0,strlen($data['course'])-1);
		//courese存放选择的图片
		//礼盒的排列组合
		$lihe_array=array('1、2、3','1、3、2','2、1、3','2、3、1','3、1、2','3、2、1');
		$flag=rand (0,5);
		$lihe=$lihe_array[$flag];
		$data['parent_msg']=$lihe;    //parent_msg 用来记录用户的随机礼盒顺序
		$result = $this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
		if($result){
			$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->save($data);
		}else{
			$this->activity_user->add($data);
		}
		$res['status']=1;
		$res['msg']="注册成功";
		//防止缓存
		$res['url']=U('apps/nationday/index',array('id'=>$aid,'ss'=>1));
		$this->ajaxReturn($res);
	}
	public function zhuli(){
		$aid=I('get.id',0,'intval');
		$uid=I('get.uid',0,'intval');
		$user_info=session('userInfo');
		$activity = sp_get_activity($aid);
		
		if($aid!=901&&$aid!=962){
			if(empty($user_info['openid'])){
				$res['status']=-1;
				$res['msg']="您的信息获取失败，请重新打开！";
				$this->ajaxReturn($res);
				die();
			}
			if($aid!=1198&&$aid!=2354){
				//先查是否添加过 A对B只能助力一次
				$result=$this->activity_friend->where(array('aid'=>$aid,'uid'=>$uid,'from_user'=>$user_info['openid']))->find();
				if($result){
					$res['status']=-1;
					$res['msg']="你已经帮TA拆过礼盒了";
					$this->ajaxReturn($res);
				}
			}
			
			//检查A（是注册用户）总共在此活动下，助力次数
			$where = array(
				'aid' => $aid,
				'from_user' => $user_info['openid']
			);
			$where['_string'] = "  username<>''";
			$result2=$this->activity_user->where($where)->find();
			if($result2){
				//是注册用户统计此活动下助力次数
				$canjia_num=$this->activity_friend->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->count();
				//测测测
				if($aid==1191){
						$res['test']=1;
						$res['msg'].='参加数量'.$canjia_num."参与者限制".$activity['per_num'];
						$this->ajaxReturn($res);
					}
				//测测测
				if($canjia_num>=$activity['per_num']){
					$res['status']=-1;
					$res['msg']="你的帮忙拆礼盒次数已用完";
					$this->ajaxReturn($res);
				}
			}
			//检查7人，14人，21人限制开始
			$his=$this->activity_user->where('id='.$uid)->find();
			$friend_num=$this->activity_friend->where(array('aid'=>$aid,'uid'=>$uid))->count();
			if($his['day_num']==1){
				if($friend_num>=7){
					$res['status']=-1;
					$res['msg']="该好友礼盒已拆完";
					$this->ajaxReturn($res);
				}
			}
			if($his['day_num']==2){
				if($friend_num>=14){
					$res['status']=-1;
					$res['msg']="该好友礼盒已拆完";
					$this->ajaxReturn($res);
				}
			}
			if($his['day_num']==3){
				if($friend_num>=21){
					$res['status']=-1;
					$res['msg']="该好友礼盒已拆完";
					$this->ajaxReturn($res);
				}
			}
			//检查7人，14人，21人限制结束
		
		
		}
		
		//插入到friend信息
		$friend_data = array(
							'aid' => $aid,
							'uid' => $uid,
							'from_user' => $user_info['openid'],
							'avatar' => $user_info['headimgurl'],
							'nickname' => $user_info['nickname'],
							'createtime' => time(),
							'update_time' => time()
						);
		$this->activity_friend->add($friend_data);
		$res['status']=1;
		$res['msg']="助力成功";
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
			$groupdata=$this->activity_friend->where(array('aid'=>$aid,'uid'=>$uid))->group('per_num')->select();
			$c=0;
			for($i=0;$i<count($groupdata);$i++)
			{
				if($groupdata[$i]['per_num']!=0)
					$c++;
			}
			if($c>=5)
			{
				$result['status']=0;
				$result['msg']="他的月饼已经集齐了，去帮别人吧！";
				$this->ajaxReturn($result);
			}
			//获取应该得到的月饼。
			//$result['msg'].="；被帮助次数：".$helpcount."；随机数：".$suiji;
			$jilv1=array(40,28,17,10,5);
			$jilv2=array(40,5,17,10,28);
			if($helpcount<=$dizhi-5)
			{
				//$result['msg'].="；随便出，低于低值-5";
				//除了最后之外的随便出，第四个几率低一点。
				$suiji=rand(1,100);
				//$result['msg'].="；随机值：".$suiji;
				$zong=0;
				for($i=0;$i<count($jilv1);$i++)
				{
					$zong+=$jilv1[$i];
					if($suiji<=$zong)
					{
						$result['bingnum']=$i;break;
					}
				}
				//$result['msg'].="；得值：".$result['bingnum'];
				//如果是0就为空。
				$result['bingnum']=$result['bingnum']==0?6:$result['bingnum'];
				//如果生成的1-4中包含不应该中的将，奖值为5.
				$result['bingnum']=$result['bingnum']==$halpuser['day_num']?5:$result['bingnum'];
				//$result['msg'].="；转换值：".$result['bingnum'];
			}
			else if($helpcount>$dizhi-5&&$helpcount<=$dizhi)
			{
				//$result['msg'].="；接近范围值了，第四个几率变高。";
				$a=array(1,2,3);
				//判断前三个是否已经有了
				$buzaizhi=$this->getnonum($a,$groupdata);
				//如果没出就挨着出。
				if($buzaizhi!=-1)
				{
					$result['bingnum']=$buzaizhi;
					//$result['msg'].="；前三个没出完，开始出值".$result['bingnum'];
				}
				else
				{
					//$result['msg'].="；前三个出完了，第四几率高";
					//$result['msg'].="；";
					//使用几率2获取结果。
					$suiji=rand(1,100);
					$zong=0;
					for($i=0;$i<count($jilv2);$i++)
					{
						$zong+=$jilv2[$i];
						if($suiji<=$zong)
						{
							$result['bingnum']=$i;break;
						}
					}
					//$result['msg'].="；随机值：".$suiji."；获得的值是".$result['bingnum'];
				}
				//如果是0就为空。
				$result['bingnum']=$result['bingnum']==0?6:$result['bingnum'];
				//如果生成的1-4中包含不应该中的将，奖值为5.
				$result['bingnum']=$result['bingnum']==$halpuser['day_num']?5:$result['bingnum'];
				//$result['msg'].="；转换后的值是".$result['bingnum'];
			}
			else if($helpcount>$dizhi&&$helpcount<=$gaozhi-5)
			{
				//$result['msg'].="；在范围前五的位置";
				//$result['msg'].="；";
				$buzaizhi=$this->getnonum(array(4),$groupdata);
				//如果第四个已出，第五个几率为40，其它的其它随便分。
				if($buzaizhi==-1)
				{
					//$result['msg'].="；前四个已经集齐。";
					$suiji=rand(1,100);
					//$result['msg'].="；产生的随机数是".$suiji;
					if($suiji<=40)
					{
						$result['bingnum']=$halpuser['day_num'];
						//$result['msg'].="；获得最后一个".$result['bingnum'];
					}
					else
					{
						$result['bingnum']=$suiji%5;
						$result['bingnum']=$result['bingnum']==$halpuser['day_num']?5:$result['bingnum'];
						//$result['msg'].="；获得的值是".$result['bingnum'];
					}
				}
				else      //如果第四个没出，几率增加到70，第五个不能中。
				{
					$suiji=rand(1,100);
					//$result['msg'].="；第四个未出，几率增加；随机值是".$suiji;
					if($suiji<=70)
					{
						$result['bingnum']=4;
						//$result['msg'].="；中第四个";
					}
					else
					{
						$result['bingnum']=$suiji%4;
					}
					$result['bingnum']=$result['bingnum']==$halpuser['day_num']?5:$result['bingnum'];
					//$result['msg'].="；获得的值是：".$result['bingnum'];
				}
				$result['bingnum']=$result['bingnum']==0?6:$result['bingnum'];
				//$result['msg'].="；转换后的值：".$result['bingnum'];
			}
			else if($helpcount>$gaozhi-5&&$helpcount<=$gaozhi-1)
			{
				//$result['msg'].="；在接近最后的范围内";
				//如果第四个没出，出现第四个。
				$buzaizhi=$this->getnonum(array(4),$groupdata);
				if($buzaizhi!=-1)
				{
					$result['bingnum']=4;
					//$result['msg'].="；第四个还没有出现，这里出现";
				}
				else   //如果已经出了，就判断有没有出第五个。
				{
					//$result['msg'].="；第四个已经出现；看第五个有没有出现";
					$buzaizhi5=$this->getnonum(array(5),$groupdata);
					$suiji=rand(1,100);
					//没出就有70%的几率出。
					if($buzaizhi5!=-1)
					{
						//$result['msg'].="；第五个没出，几率增加。";
						if($suiji<=70)
						{
							$result['bingnum']=$halpuser['day_num'];
							//$result['msg'].="；得到的为第五个".$halpuser['day_num'];
						}
						else
						{
							$result['bingnum']=$suiji%5;
							//$result['msg'].="；得到的值是".$result['bingnum'];
							$result['bingnum']=$result['bingnum']==$halpuser['day_num']?5:$result['bingnum'];
							//$result['msg'].="；转换过的值是".$result['bingnum'];
						}
					}
					else
					{
						$result['bingnum']=$suiji%5;
						//$result['msg'].="；第五个已出，得到的值是".$result['bingnum'];
						$result['bingnum']=$result['bingnum']==0?6:$result['bingnum'];
						//$result['msg'].="；转换过的值是".$result['bingnum'];
					}
				}
			}
			else
			{
				//$result['msg'].="；次数超过范围";
				//如果第五个没出，出第五个。
				$buzaizhi=$this->getnonum(array(5),$groupdata);
				if($buzaizhi!=-1)
				{
					//$result['msg'].="；第五个没出，出第五个";
					$result['bingnum']=5;
				}
				else
				{
					$result['bingnum']=rand(1,100)%6;
					//$result['msg'].="；第五个出了，得到的值是".$result['bingnum'];
					$result['bingnum']=$result['bingnum']==0?6:$result['bingnum'];
					//$result['msg'].="；转换后的值是".$result['bingnum'];
				}
				//如果已经出了，几率大家随便分。
			}
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
	/*注册*/
	/*public function register()
	{
		$aid=I('get.id',0,'intval');
		$data['username']=I('post.name');
		$data['mobile']=I('post.mobile');
		$data['per_num']=1;
		$user_info=session('userInfo');
		$result=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
		if($result)
		{
			$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->save($data);
			//添加一条获奖记录
			$jilu=array('aid'=>$aid,
						'uid'=>$result['id'],
						'from_user'=>$user_info['openid'],
						'nickname'=>$result['nickname'],
						'avatar'=>$result['avatar'],
						'createtime'=>time(),
						'per_num'=>I('post.num'));
			$this->activity_friend->add($jilu);
			$res['status']=1;
			$res['msg']="注册成功";
			//$res['url']='http://'.$_SERVER['HTTP_HOST'].U('apps/midautumn/index',array('id'=>$aid,'uid'=>$result['id'],'ss'=>1));
			$res['url']='http://'.$_SERVER['HTTP_HOST'].U('apps/midautumn/index',array('id'=>$aid,'ss'=>1));
		}
		else
		{
			$res['status']=-1;
			$res['msg']="注册失败";
		}
		$this->ajaxReturn($res);
	}*/
	
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
	/*//老拆礼盒领奖，原版
	public function getGift(){
		$aid=I('get.id',0,'intval');
		//$activity=sp_get_activity($aid);
		$data['has_gift'] = 0;
		//$data['has_gift'] = 1;
		$activity = sp_get_activity($aid);
		$params = sp_get_activity_params($aid);	
		$activity['params'] = array_column($params, 'value', 'field');
		$user_info=session('userInfo');
		//user
		$user=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
		//prize_name3 prize_num3
		$flag=I('post.gift_key',0,'intval');
		$lihe_list=explode("、",$user['parent_msg']);
		
		if($flag==1){
			$index=$lihe_list[0];
		}
		if($flag==2){
			$index=$lihe_list[1];
		}
		if($flag==3){
			$index=$lihe_list[2];
		}
		$data['msg']="a".$lihe_list[0]."b".$lihe_list[1]."c".$lihe_list[2];
		$prize_name=$activity['params']['prize_name'.$index];
		$prize_num=intval($activity['params']['prize_num'.$index]);
		//查自己是否有这个奖品开始
		$same_prize=D('ActivityUserPrize')->where(array('aid'=>$aid,'uid'=>$user['id'],'name'=>$prize_name))->find();
		if($same_prize){
			$prize_num=0;
		}
		//查自己是否有这个奖品结束
		if($prize_num>0){
				$data['has_gift'] = 1;
				$data['gift_name'] = $prize_name;
				//减减数量
				$this->activity_params->where(array('aid'=>$aid,'field'=>'prize_num'.$index))->setDec('value');
				//userprize
				D('ActivityUserPrize')->add(array('aid'=>$aid,'uid'=>$user['id'],'from_user'=>$user['from_user'],'username'=>$user['username'],'mobile'=>$user['mobile'],'name'=>$prize_name,'type'=>$index,'thumb'=>$index,'createtime'=>time()));
		}else{
			$data['has_gift'] = 0;
			$prize_name = "空奖";
			$data['gift_name'] = $prize_name;
				//userprize
				D('ActivityUserPrize')->add(array('aid'=>$aid,'uid'=>$user['id'],'from_user'=>$user['from_user'],'username'=>$user['username'],'mobile'=>$user['mobile'],'name'=>$prize_name,'type'=>4,'thumb'=>$index,'createtime'=>time()));
		}
		$this->ajaxReturn($data);
	}*/
	//老拆礼盒领奖，原版
	public function getGift(){
		$aid=I('get.id',0,'intval');
		//$activity=sp_get_activity($aid);
		$data['has_gift'] = 0;
		//$data['has_gift'] = 1;
		$activity = sp_get_activity($aid);
		$params = sp_get_activity_params($aid);	
		$activity['params'] = array_column($params, 'value', 'field');
		$user_info=session('userInfo');
		//user
		$user=$this->activity_user->where(array('aid'=>$aid,'from_user'=>$user_info['openid']))->find();
		//prize_name3 prize_num3
		$index=I('post.gift_key',0,'intval');
		$prize_name=$activity['params']['prize_name'.$index];
		$prize_num=intval($activity['params']['prize_num'.$index]);
		//查自己是否有这个奖品开始
		/*$same_prize=D('ActivityUserPrize')->where(array('aid'=>$aid,'uid'=>$user['id'],'name'=>$prize_name))->find();
		if($same_prize){
			$prize_num=0;
		}*/
		$same_prize="";
		$flag=$index;
		do{
			$prize_name=$activity['params']['prize_name'.$flag];
			$prize_num=intval($activity['params']['prize_num'.$flag]);
			$same_prize=D('ActivityUserPrize')->where(array('aid'=>$aid,'uid'=>$user['id'],'name'=>$prize_name))->find();
			$flag++;
			if($flag>=4){
				$flag=1;
			}
		}
		while($same_prize);
		
		if($flag==1){
			$flag=4;
		}
		$index=$flag-1;
		
		
		//查自己是否有这个奖品结束
		if($prize_num>0){
				$data['has_gift'] = 1;
				$data['gift_name'] = $prize_name;
				//减减数量
				$this->activity_params->where(array('aid'=>$aid,'field'=>'prize_num'.$index))->setDec('value');
				//userprize
				D('ActivityUserPrize')->add(array('aid'=>$aid,'uid'=>$user['id'],'from_user'=>$user['from_user'],'username'=>$user['username'],'mobile'=>$user['mobile'],'name'=>$prize_name,'type'=>$index,'thumb'=>$index,'createtime'=>time()));
		}else{
			$data['has_gift'] = 0;
			$prize_name = "空奖";
			$data['gift_name'] = $prize_name;
				//userprize
				D('ActivityUserPrize')->add(array('aid'=>$aid,'uid'=>$user['id'],'from_user'=>$user['from_user'],'username'=>$user['username'],'mobile'=>$user['mobile'],'name'=>$prize_name,'type'=>4,'thumb'=>$index,'createtime'=>time()));
		}
		$this->ajaxReturn($data);
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
	public function testadd(){
		//adduser
		$data['aid']=892;
		$data['form_user']='oqd6JxIy7Pyg_AD3AezfbveF6IaU';
		$data['nickname']='DNA';
		$data['username']='丁扬';
		$data['mobile']='18037998505';
		$data['createtime']=time();
		$data['update_time']=time();
		$data['avatar']='http://wx.qlogo.cn/mmopen/Y3WgNLFjO0fgMIHKzEibVZjGT83vtgIdv0dL9UN79nmpwcGk7YnnCooXnmicbsuqSKHwvGmP8VxMr47O5MWRxQB5r5cqlse4vO/0';
		D("ActivityUser")->add($data);
	}
	public function testf(){
		//adduser
		//$this->activity_friend
		$data['uid']=125374;
		$data['aid']=892;
		$data['form_user']='oqd6JxIy7Pyg_AD3AezfbveF6IaU';
		$data['nickname']='DNA';
		//$data['day_num']=1;
		$data['createtime']=time();
		$data['update_time']=time();
		$data['avatar']='http://wx.qlogo.cn/mmopen/Y3WgNLFjO0fgMIHKzEibVZjGT83vtgIdv0dL9UN79nmpwcGk7YnnCooXnmicbsuqSKHwvGmP8VxMr47O5MWRxQB5r5cqlse4vO/0';
		for($i=0;$i<20;$i++){
			$data['day_num']=$i;
			$this->activity_friend->add($data);
		}
	}
	public function testshow(){
		$user=D("ActivityUser")->where("aid=901")->select();
		//$user=D("ActivityUser")->where("id=126937")->select();
		print_r($user);
	}
	public function testdelete(){
		$user=D('ActivityUserPrize')->where("aid=901")->delete();
	}
	public function testshowf(){
		$f=$this->activity_friend->where("aid=901")->select();
		//$user=D("ActivityUser")->where("id=126937")->select();
		print_r($f);
	}
	//加昵称为空
	public function addf(){
		/*$data['']=
		$f=$this->activity_friend->where("aid=901")->select();
		print_r($f);*/
	}
	
	
	public function testshowp(){
		$p=D('ActivityUserPrize')->where("aid=901")->select();
		//$user=D("ActivityUser")->where("id=126937")->select();
		print_r($p);
	}
	
	public function addu(){
		$data['aid']=983;
		$data['form_user']='oqd6JxIy7Pyg_AD3AezfbveF6IaU';
		$data['nickname']='DNA';
		//$data['username']='丁扬';
		$data['mobile']='18037998505';
		$data['createtime']=time();
		$data['update_time']=time();
		$data['avatar']='http://wx.qlogo.cn/mmopen/Y3WgNLFjO0fgMIHKzEibVZjGT83vtgIdv0dL9UN79nmpwcGk7YnnCooXnmicbsuqSKHwvGmP8VxMr47O5MWRxQB5r5cqlse4vO/0';
		for($i=0;$i<50;$i++){
			$data['username']=$i;
			D("ActivityUser")->add($data);
		}
		$user=D("ActivityUser")->where("aid=983")->select();
		//$user=D("ActivityUser")->where("id=126937")->select();
		print_r($user);
	}
	public function changeu(){
		$data['aid']=984;
		D("ActivityUser")->where("aid=983")->save($data);
	}
	public function deleteu(){
		D("ActivityUser")->where("aid=984")->delete();
	}
	
}

