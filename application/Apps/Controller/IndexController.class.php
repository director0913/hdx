<?php

/**
 * 营销活动定义
 */
namespace Apps\Controller;
use Common\Controller\MemberbaseController;
class IndexController extends MemberbaseController  {
	protected $activity;
	protected $activity_params;

	function _initialize(){
		parent::_initialize();
		$this->activity = D("Activity");
		$this->activity_params = D("ActivityParams");
	}
    // public function index() {
    //   $this->display();
    // }
	/**
	* 活动左右同步左侧模板。
	*/
    public function thimes(){
		$module = I("get.module");
		$page = I("get.page");
		$this->display("/Thimes/".$module."/".$page);
	}
	function test(){
		//echo "test";
		$a = array(
		  array(
			'id' => 5698,
			'first_name' => 'Bill',
			'last_name' => 'Gates',
		  ),
		  array(
			'id' => 4767,
			'first_name' => 'Steve',
			'last_name' => 'Jobs',
		  ),
		  array(
			'id' => 3809,
			'first_name' => 'Mark',
			'last_name' => 'Zuckerberg',
		  )
		);

		$last_names = array_column($a, 'last_name', 'id');
		var_dump($last_names);
		die();
	}
	/**
	* 分析用户身份
	*/
	public function analyze_role(){
//		$curr_user = sp_get_current_user();
//		if($curr_user['valid_time'] < time()){
//			$this->error("很抱歉，您的账号已过期，请联系客服人员续费后，方可使用！");
//		}
	}
	/**
	* 添加/编辑活动
	**/
	public function edit()
	{
		$this->analyze_role();
		$id = I("get.id",0,"intval");
		$module = I("get.module");
		$activity = $this->activity->where("id = $id")->find();
		// 查询活动的自定义参数
		$activity['params'] = $this->activity_params->where("aid = $id")->select();
		$activity['params'] = array_column($activity['params'], 'value', 'field');
		// 如果是新建活动，就初始化需要初始化的数据。
		if ($id == 0) {
			$activity['begintime'] = time();
			$activity['endtime'] = time();
		}
		$begin_fen = date("H:i",!empty($activity['begintime']) ? $activity['begintime'] : strtotime('2017-06-01 08:00:00'));
		$end_fen = date("H:i",!empty($activity['endtime']) ? $activity['endtime'] : strtotime('2017-12-31 18:00:00'));
		$time_array = [];
		for ($shi = 0,$i = 0; $i < 24; $i++) {
			if ($i < 10) {
				$shi = "0".$i;
			}
			else $shi = $i;
			$time_array[] = $shi.":00";
			$time_array[] = $shi.":30";
		}
		if (in_array($module, ['weiprice','weiprices'])) {
			$activity['pricerule'] = D("WeipriceRule")->where("aid = $id")->select();
		}
		if ($module == "questionnaire") {
			$activity['questionnaire'] = D("QuestionnaireProblem")->where("aid = $id")->select();
		}
		if ($module == "groupbuy" || $module == "ngroupbuy") {
			$groupbuy_option = D("ActivityGroupbuy")->where("aid = $id")->select();
			$this->assign("groupbuy_option", $groupbuy_option);
		}
		if ($module == "weidiom") {
			$weidiom_option = D("WeidiomsChengyu")->where("aid = $id")->order('id')->select();
			$this->assign("weidiom_option", $weidiom_option);
		}
		if ($module == "classjoin") {
			$classjoin_option = D("ClassJoin")->where("aid = $id")->select();
			$this->assign("classjoin_option", $classjoin_option);
		}
		if ($module == "assessment") {
			$problem_list = 0;
			if ($id != 0) {
				$problem_list = D("QuestionnaireProblem")->where("aid = $id")->order('id')->select();
				$this->assign("problem_list", $problem_list);
			}
			
		}
		if (in_array($module, ['laborday','hammer','weiprices'])) {
			if (empty($activity['params']['activityprize'])) {
				$this->assign("prizes", '');
			} else {
				$activityprize = explode(',', $activity['params']['activityprize']);
				$this->assign("prizes", $activityprize);
			}			
		}
		if($module == "weiprices"){
			file_put_contents("user.txt", print_r($activity,true));
			file_put_contents("report.txt", print_r($_POST,true));
		}
		$activity['begintime'] = !empty($activity['begintime']) ? date('Y-m-d', $activity['begintime']) : date('Y-m-d', strtotime('2017-06-01'));
		$activity['endtime'] = !empty($activity['endtime']) ? date('Y-m-d', $activity['endtime']) : date('Y-m-d', strtotime('2017-12-31'));

		$this->assign("id", $id);
		$this->assign("module", $module);
		$this->assign("activity", $activity);
		$this->assign($activity);
		$this->assign($activity['params']);
		$this->assign("begin_fen", $begin_fen);
		$this->assign("end_fen", $end_fen);
		$this->assign("time_array", $time_array);
		$ce = I("get.ce", 0, "intval");
		if ($ce == 1) {
			$this->display("/Index/" . $module . "_edit1");
		} else {
			$this->display("/Index/" . $module . "_edit");
		}
	}
	// 提交添加/编辑活动。
	public function edit_post(){
		$this->analyze_role();
		if (IS_POST) {
			$yuanid = $id = I("post.id", 0, "intval");
			$module = I("post.type");
			$_POST['rule'] = htmlspecialchars_decode($_POST['rule']);
			$_POST['activityremark'] = htmlspecialchars_decode($_POST['activityremark']);
			$_POST['activityprize'] = htmlspecialchars_decode($_POST['activityprize']);
			$_POST['school_desc'] = htmlspecialchars_decode($_POST['school_desc']);
			$type = I("post.type");
			if ($type == 'groupbuy' || $type == 'weigreet' || $type == 'flower' || $type == 'nyyvote' || $type == 'voteprize') {
				$school = I("post.school");
				$slogan = I("post.slogan");
				$_POST['title'] = $school.$slogan;
			}
			// 获取当前登陆用户id。
			$_POST['uid'] = get_current_userid();
			$_POST['begintime'] = strtotime($_POST['begintime'].$_POST['begintime_fen']);
			$_POST['endtime'] = strtotime($_POST['endtime'].$_POST['endtime_fen']);
			$_POST['createtime'] = time();
			if ($id > 0) {
				$activity = $this->activity->where("id = $id")->find();
				if ($activity['uid'] != $_POST['uid']) {
					$_POST['id'] = 0;
				}
			}
			if ($this->activity->create()) {
				if ($id > 0 && $activity['uid'] == $_POST['uid']) {
					$res = $this->activity->save();
				} else {
					$res = $this->activity->add();
					$id = $res;
				}
				if ($res !== false) {
					switch ($module) {
						case "olympicrefuel":
						case "cheerchina":
						case "flychicken":
						case "bigwheel": $this->save_jiang($id); break;
						case "weishare": $this->save_weishare($id); break;
						case "invitegift": $this->save_invitegift($id); break;
						case "weiprice": $this->save_weiprice($id,$yuanid); break;
						case "weiprices": R("Apps/weiprices/save_weiprices",array($id,$yuanid)); break;
						case "groupbuy": R("Apps/groupbuy/save_groupbuy",array($id)); break;
						case "ngroupbuy": R("Apps/ngroupbuy/save_groupbuy",array($id)); break;
						case "weicheer":  $this->save_weicheer($id); break;
						case "weimeet": $this->save_weimeet($id); break;
						case "questionnaire": $this->save_jiang($id); $this->save_questionnaire($id,$yuanid);break;
						case "weidiom": $this->save_weidiom($id,$yuanid);break;
						case "weigift": $this->save_weigift($id);break;
						case "scratch": $this->save_scratch($id);break;
						case "weigreet": $this->save_weigreet($id);break;
						case "challenge": $this->save_jiang($id); $this->save_challenge($id);break;
						case "yyvote": $this->save_yyvote($id);break;
						case "yyvotes": R("Apps/yyvotes/save_yyvotes",array($id));break;
						case "flower": $this->save_flower($id);break;
						case "midautumn": $this->midautumn_save($id);$this->save_jiang($id);break;
						case "nationday": $this->save($id);break;
						case "giftbox": R("Apps/giftbox/save",array($id));break;
						case "newquestionnaire": R("Apps/newquestionnaire/save",array($id));break;
						case "nyyvote": R("Apps/nyyvote/save_nyyvote",array($id));break;
						case "shareprize": $this->save_shareprize($id);break;
						case "voteprize": R("Apps/voteprize/save_voteprize",array($id));break;
						case "nenvelope": R("Apps/nenvelope/save_nenvelope",array($id));break;
						case "classjoin": R("Apps/classjoin/save_classjoin",array($id,$yuanid));break;
						case "nweishare": R("Apps/nweishare/save_nweishare",array($id));break;
						case "envelope": $this->save_envelope($id);break;
					 	case "shakeprize": R("Apps/shakeprize/save_shakeprize",array($id));break;
						case "weilight": $this->save_weilight($id);break;
						case "weiflower": R("Apps/weiflower/save_weiflower",array($id));break;
						case "adventure": $this->save_jiang($id); R("Apps/adventure/save_adventure",array($id));break;
						case "assessment": R("Apps/assessment/save_assessment",array($id,$yuanid));break;
						case "womenday": R("Apps/womenday/save_womenday",array($id));break;
						case "turnover": R("Apps/turnover/save_turnover",array($id));break;
						case "sharpeyes": R("Apps/sharpeyes/save_sharpeyes",array($id));break;
						case "anniversary": R("Apps/anniversary/save_anniversary",array($id));break;
						case "hammer": R("Apps/hammer/save_hammer",array($id));break;
						case "laborday": R("Apps/laborday/save_laborday",array($id));break;
						case "goddess": R("Apps/goddess/save_goddess",array($id));break;
						case "weicircle": R("Apps/weicircle/save_weicircle",array($id,$yuanid));break;
					}
					$this->success("保存成功！", U("apps/index/addresult",array("id"=>$id)));
				}else{
					$this->error($this->activity->getError());
				}
			}else{
				$this->error($this->activity->getError());
			}
		}
	}

	function save_envelope($aid){
		$data = array(
			array("aid"=>$aid,"name"=>"助力积分方式","field"=>"steptype","value"=>I('post.steptype')),
			array("aid"=>$aid,"name"=>"固定助力积分","field"=>"step","value"=>I('post.step'),0,"intval"),
			array("aid"=>$aid,"name"=>"助力随机积分范围","field"=>"steprandom","value"=>I('post.steprandom')),
			array("aid"=>$aid,"name"=>"报名需填信息","field"=>"other_remark","value"=>I('post.other_remark')),
			array("aid"=>$aid,"name"=>"校区","field"=>"other_school","value"=>I('post.other_school')),
			array("aid"=>$aid,"name"=>"年龄","field"=>"age","value"=>I('post.age')),
			array("aid"=>$aid,"name"=>"班级","field"=>"cless","value"=>I('post.cless')),
			array("aid"=>$aid,"name"=>"学校","field"=>"school","value"=>I('post.school')),
			array("aid"=>$aid,"name"=>"其他","field"=>"qita","value"=>I('post.qita')),
		);
		sp_save_activity_params($data);
	}

	function save_flower($aid){
		$data = array(
			array("aid"=>$aid,"name"=>"显示前多少人","field"=>"show_total","value"=>I('post.show_total')),
			array("aid"=>$aid,"name"=>"每页显示条数","field"=>"page_total","value"=>I('post.page_total')),
			array("aid"=>$aid,"name"=>"学校","field"=>"school_name","value"=>I('post.school_name')),
			array("aid"=>$aid,"name"=>"报名需填信息","field"=>"other_remark","value"=>I('post.other_remark')),
			array("aid"=>$aid,"name"=>"校区","field"=>"other_school","value"=>I('post.other_school')),
			array("aid"=>$aid,"name"=>"年龄","field"=>"age","value"=>I('post.age')),
			array("aid"=>$aid,"name"=>"班级","field"=>"cless","value"=>I('post.cless')),
			array("aid"=>$aid,"name"=>"学校","field"=>"school","value"=>I('post.school')),
			array("aid"=>$aid,"name"=>"其他","field"=>"qita","value"=>I('post.qita')),
			array("aid"=>$aid,"name"=>"是否开启前台报名","field"=>"is_front_join","value"=>I('post.is_front_join')),
			array("aid"=>$aid,"name"=>"是否开启报名审核","field"=>"is_check","value"=>I('post.is_check')),
			array("aid"=>$aid,"name"=>"宣传语","field"=>"slogan","value"=>I('post.slogan')),
			//array("aid"=>$aid,"name"=>"报名者献花次数","field"=>"join_total","value"=>I('post.join_total'))
		);
		sp_save_activity_params($data);
	}

	function midautumn_save($aid){
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

	function save_shareprize($aid){
		$data = array(
			array("aid"=>$aid,"name"=>"最高得分限制","field"=>"max","value"=>I('post.max'),0,"intval"),
			array("aid"=>$aid,"name"=>"助力积分方式","field"=>"steptype","value"=>I('post.steptype')),
			array("aid"=>$aid,"name"=>"固定助力积分","field"=>"step","value"=>I('post.step'),0,"intval"),
			array("aid"=>$aid,"name"=>"助力随机积分范围","field"=>"steprandom","value"=>I('post.steprandom')),
			array("aid"=>$aid,"name"=>"助力积分方式","field"=>"steptype","value"=>I('post.steptype')),
			array("aid"=>$aid,"name"=>"最多中奖次数","field"=>"zhongjiang_max","value"=>I('post.zhongjiang_max'),0,"intval"),
			array("aid"=>$aid,"name"=>"报名需填信息","field"=>"other_remark","value"=>I('post.other_remark')),
			array("aid"=>$aid,"name"=>"校区","field"=>"other_school","value"=>I('post.other_school')),
			array("aid"=>$aid,"name"=>"年龄","field"=>"age","value"=>I('post.age')),
			array("aid"=>$aid,"name"=>"班级","field"=>"cless","value"=>I('post.cless')),
			array("aid"=>$aid,"name"=>"学校","field"=>"school","value"=>I('post.school')),
			array("aid"=>$aid,"name"=>"其他","field"=>"qita","value"=>I('post.qita')),


			array("aid"=>$aid,"name"=>"奖品1图片","field"=>"prize1_thumb","value"=>I('post.prize1_thumb')),
			array("aid"=>$aid,"name"=>"奖品1名称","field"=>"prize1_name","value"=>I('post.prize1_name')),
			array("aid"=>$aid,"name"=>"奖品1数量","field"=>"prize1_total","value"=>I('post.prize1_total')),
			array("aid"=>$aid,"name"=>"奖品1概率","field"=>"prize1_rate","value"=>I('post.prize1_rate')),
			array("aid"=>$aid,"name"=>"奖品1类型","field"=>"prize1_type","value"=>I('post.prize1_type')),

			array("aid"=>$aid,"name"=>"奖品2图片","field"=>"prize2_thumb","value"=>I('post.prize2_thumb')),
			array("aid"=>$aid,"name"=>"奖品2名称","field"=>"prize2_name","value"=>I('post.prize2_name')),
			array("aid"=>$aid,"name"=>"奖品2数量","field"=>"prize2_total","value"=>I('post.prize2_total')),
			array("aid"=>$aid,"name"=>"奖品2概率","field"=>"prize2_rate","value"=>I('post.prize2_rate')),
			array("aid"=>$aid,"name"=>"奖品2类型","field"=>"prize2_type","value"=>I('post.prize2_type')),

			array("aid"=>$aid,"name"=>"奖品3图片","field"=>"prize3_thumb","value"=>I('post.prize3_thumb')),
			array("aid"=>$aid,"name"=>"奖品3名称","field"=>"prize3_name","value"=>I('post.prize3_name')),
			array("aid"=>$aid,"name"=>"奖品3数量","field"=>"prize3_total","value"=>I('post.prize3_total')),
			array("aid"=>$aid,"name"=>"奖品3概率","field"=>"prize3_rate","value"=>I('post.prize3_rate')),
			array("aid"=>$aid,"name"=>"奖品3类型","field"=>"prize3_type","value"=>I('post.prize3_type')),
		);
		sp_save_activity_params($data);

	}

	function save_weilight($aid){
		$data = array(
			array("aid"=>$aid,"name"=>"助力积分方式","field"=>"steptype","value"=>I('post.steptype')),
			array("aid"=>$aid,"name"=>"固定助力积分","field"=>"step","value"=>I('post.step'),0,"intval"),
			array("aid"=>$aid,"name"=>"助力随机积分范围","field"=>"steprandom","value"=>I('post.steprandom')),
			array("aid"=>$aid,"name"=>"最多中奖次数","field"=>"prize_max","value"=>I('post.prize_max'),0,"intval"),
			array("aid"=>$aid,"name"=>"报名需填信息","field"=>"other_remark","value"=>I('post.other_remark')),
			array("aid"=>$aid,"name"=>"校区","field"=>"other_school","value"=>I('post.other_school')),
			array("aid"=>$aid,"name"=>"年龄","field"=>"age","value"=>I('post.age')),
			array("aid"=>$aid,"name"=>"班级","field"=>"cless","value"=>I('post.cless')),
			array("aid"=>$aid,"name"=>"学校","field"=>"school","value"=>I('post.school')),
			array("aid"=>$aid,"name"=>"其他","field"=>"qita","value"=>I('post.qita')),

			array("aid"=>$aid,"name"=>"第一个字","field"=>"myfont1","value"=>I('post.myfont1')),
			array("aid"=>$aid,"name"=>"第二个字","field"=>"myfont2","value"=>I('post.myfont2')),
			array("aid"=>$aid,"name"=>"第三个字","field"=>"myfont3","value"=>I('post.myfont3')),
			array("aid"=>$aid,"name"=>"第四个字","field"=>"myfont4","value"=>I('post.myfont4')),
			array("aid"=>$aid,"name"=>"第五个字","field"=>"myfont5","value"=>I('post.myfont5')),

			array("aid"=>$aid,"name"=>"奖品1图片","field"=>"prize1_thumb","value"=>I('post.prize1_thumb')),
			array("aid"=>$aid,"name"=>"奖品1名称","field"=>"prize1_name","value"=>I('post.prize1_name')),
			array("aid"=>$aid,"name"=>"奖品1数量","field"=>"prize1_total","value"=>I('post.prize1_total')),
			array("aid"=>$aid,"name"=>"奖品1概率","field"=>"prize1_rate","value"=>I('post.prize1_rate')),
			array("aid"=>$aid,"name"=>"奖品1类型","field"=>"prize1_type","value"=>I('post.prize1_type')),

			array("aid"=>$aid,"name"=>"奖品2图片","field"=>"prize2_thumb","value"=>I('post.prize2_thumb')),
			array("aid"=>$aid,"name"=>"奖品2名称","field"=>"prize2_name","value"=>I('post.prize2_name')),
			array("aid"=>$aid,"name"=>"奖品2数量","field"=>"prize2_total","value"=>I('post.prize2_total')),
			array("aid"=>$aid,"name"=>"奖品2概率","field"=>"prize2_rate","value"=>I('post.prize2_rate')),
			array("aid"=>$aid,"name"=>"奖品2类型","field"=>"prize2_type","value"=>I('post.prize2_type')),

			array("aid"=>$aid,"name"=>"奖品3图片","field"=>"prize3_thumb","value"=>I('post.prize3_thumb')),
			array("aid"=>$aid,"name"=>"奖品3名称","field"=>"prize3_name","value"=>I('post.prize3_name')),
			array("aid"=>$aid,"name"=>"奖品3数量","field"=>"prize3_total","value"=>I('post.prize3_total')),
			array("aid"=>$aid,"name"=>"奖品3概率","field"=>"prize3_rate","value"=>I('post.prize3_rate')),
			array("aid"=>$aid,"name"=>"奖品3类型","field"=>"prize3_type","value"=>I('post.prize3_type')),

		);
		sp_save_activity_params($data);
	}

	function save_weiprice($aid,$yuanaid){
		//保存扩展字段。
		$data = [
			["aid" => $aid, "name" => "背景音乐", "field" => "bg", "value" => I('post.bg')],
			["aid" => $aid, "name" => "关注引导链接", "field" => "follow_url", "value" => I('post.follow_url')],
			["aid" => $aid, "name" => " 商品名称", "field" => "p_name", "value" => I('post.p_name')],
			["aid" => $aid, "name" => "商品描述", "field"=> "p_dec", "value" => I('post.p_dec')],
			["aid" => $aid, "name" => "商品缩略图", "field" => "p_preview_pic", "value" => I('post.p_preview_pic')],
			["aid" => $aid, "name" => "商品库存", "field" => "p_kc", "value" => I('post.p_kc')],
			["aid" => $aid, "name" => "商品原价", "field" => "p_y_price", "value" => I('post.p_y_price')],
			["aid" => $aid, "name" => "商品最低价", "field" => "p_low_price", "value" => I('post.p_low_price')],
			["aid" => $aid, "name" => "报名需填信息", "field" => "other_remark", "value" => I('post.other_remark')],
			["aid" => $aid, "name" => "校区", "field" => "other_school", "value" =>I('post.other_school')],
			["aid" => $aid, "name" => "年龄", "field" => "age", "value" => I('post.age')],
			["aid" => $aid, "name" => "班级", "field" => "cless", "value" => I('post.cless')],
			["aid" => $aid, "name" => "学校", "field" => "school", "value" => I('post.school')],
			["aid" => $aid, "name" => "其他", "field" => "qita", "value" => I('post.qita')],
			["aid" => $aid, "name" => "是否需要支付", "field" => "is_pay", "value" => I('post.is_pay')],
			["aid" => $aid, "name" => "支付二维码", "field" => "pay_thumb", "value" => I('post.pay_thumb')],
		];
		sp_save_activity_params($data);
		//获取所有填写的信息。
		$rule_id = I('post.rule_id');
		$rule_pice = [];
		$rule_piceshi = I('post.rule_piceshi');
		$rule_picezhi = I('post.rule_picezhi');
		for ($i = 0; $i < count($rule_id); $i++) {
			$rule_piceshi[$i] = $rule_piceshi[$i] == "" ? 0 : $rule_piceshi[$i];
			$rule_picezhi[$i] = $rule_picezhi[$i] == "" ? 0 : $rule_picezhi[$i];
			if ($rule_piceshi[$i] > $rule_picezhi[$i]) {
				$linshi = $rule_piceshi[$i];
				$rule_piceshi[$i] = $rule_picezhi[$i];
				$rule_picezhi[$i] = $linshi;
			}
			$rule_pice[] = $rule_piceshi[$i] == 0 && $rule_picezhi[$i] == 0 ? 0 : $rule_piceshi[$i] . "、" . $rule_picezhi[$i];
		}
		$rule_start = I('post.rule_start');
		$rule_end = I('post.rule_end');
		if ($yuanaid == $aid) {
			//将所有的id拼接起来
			$newrule_id = "0";
			for ($i = 0; $i < count($rule_id); $i++) {
				if ($rule_id[$i] > 0) {
					$newrule_id .= "," . $rule_id[$i];
				}
			}
			//删除不存在的id。
			D("WeipriceRule")->where('aid='.$aid.' and id not in('.$newrule_id.')')->delete();
		}
		//遍历数据，如果有id就更新数据，没有id就插入数据。
		for ($i = 0; $i < count($rule_id); $i++) {
			$data = [
				'pice' => $rule_pice[$i],
				'start' => $rule_start[$i],
				'end' => $rule_end[$i]
			];
			if ($rule_id[$i] > 0 && $yuanaid == $aid) {
				D("WeipriceRule")->where('id = '.$rule_id[$i])->save($data);
			} else {
				$data['aid'] = $aid;
				D("WeipriceRule")->add($data);
			}
		}
		return true;
	}

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

	function save_jiang($aid){
		$data = array(
			array("aid"=>$aid,"name"=>"奖品1图片","field"=>"prize1_thumb","value"=>I('post.prize1_thumb')),
			array("aid"=>$aid,"name"=>"奖品1名称","field"=>"prize1_name","value"=>I('post.prize1_name')),
			array("aid"=>$aid,"name"=>"奖品1数量","field"=>"prize1_total","value"=>I('post.prize1_total')),
			array("aid"=>$aid,"name"=>"奖品1概率","field"=>"prize1_rate","value"=>I('post.prize1_rate')),
			array("aid"=>$aid,"name"=>"奖品1类型","field"=>"prize1_type","value"=>I('post.prize1_type')),
			array("aid"=>$aid,"name"=>"奖品1是否必中","field"=>"prize1_only","value"=>I('post.prize1_only',0,"intval")),

			array("aid"=>$aid,"name"=>"奖品2图片","field"=>"prize2_thumb","value"=>I('post.prize2_thumb')),
			array("aid"=>$aid,"name"=>"奖品2名称","field"=>"prize2_name","value"=>I('post.prize2_name')),
			array("aid"=>$aid,"name"=>"奖品2数量","field"=>"prize2_total","value"=>I('post.prize2_total')),
			array("aid"=>$aid,"name"=>"奖品2概率","field"=>"prize2_rate","value"=>I('post.prize2_rate')),
			array("aid"=>$aid,"name"=>"奖品2类型","field"=>"prize2_type","value"=>I('post.prize2_type')),
			array("aid"=>$aid,"name"=>"奖品2是否必中","field"=>"prize2_only","value"=>I('post.prize2_only',0,"intval")),

			array("aid"=>$aid,"name"=>"奖品3图片","field"=>"prize3_thumb","value"=>I('post.prize3_thumb')),
			array("aid"=>$aid,"name"=>"奖品3名称","field"=>"prize3_name","value"=>I('post.prize3_name')),
			array("aid"=>$aid,"name"=>"奖品3数量","field"=>"prize3_total","value"=>I('post.prize3_total')),
			array("aid"=>$aid,"name"=>"奖品3概率","field"=>"prize3_rate","value"=>I('post.prize3_rate')),
			array("aid"=>$aid,"name"=>"奖品3类型","field"=>"prize3_type","value"=>I('post.prize3_type')),
			array("aid"=>$aid,"name"=>"奖品3是否必中","field"=>"prize3_only","value"=>I('post.prize3_only',0,"intval")),
			
			array("aid"=>$aid,"name"=>"奖品4图片","field"=>"prize4_thumb","value"=>I('post.prize4_thumb')),
			array("aid"=>$aid,"name"=>"奖品4名称","field"=>"prize4_name","value"=>I('post.prize4_name')),
			array("aid"=>$aid,"name"=>"奖品4数量","field"=>"prize4_total","value"=>I('post.prize4_total')),
			array("aid"=>$aid,"name"=>"奖品4概率","field"=>"prize4_rate","value"=>I('post.prize4_rate')),
			array("aid"=>$aid,"name"=>"奖品4类型","field"=>"prize4_type","value"=>I('post.prize4_type')),
			array("aid"=>$aid,"name"=>"奖品4是否必中","field"=>"prize4_only","value"=>I('post.prize4_only',0,"intval")),

			array("aid"=>$aid,"name"=>"奖品5图片","field"=>"prize5_thumb","value"=>I('post.prize5_thumb')),
			array("aid"=>$aid,"name"=>"奖品5名称","field"=>"prize5_name","value"=>I('post.prize5_name')),
			array("aid"=>$aid,"name"=>"奖品5数量","field"=>"prize5_total","value"=>I('post.prize5_total')),
			array("aid"=>$aid,"name"=>"奖品5概率","field"=>"prize5_rate","value"=>I('post.prize5_rate')),
			array("aid"=>$aid,"name"=>"奖品5类型","field"=>"prize5_type","value"=>I('post.prize5_type')),
			array("aid"=>$aid,"name"=>"奖品5是否必中","field"=>"prize5_only","value"=>I('post.prize5_only',0,"intval")),

			array("aid"=>$aid,"name"=>"奖品6图片","field"=>"prize6_thumb","value"=>I('post.prize6_thumb')),
			array("aid"=>$aid,"name"=>"奖品6名称","field"=>"prize6_name","value"=>I('post.prize6_name')),
			array("aid"=>$aid,"name"=>"奖品6数量","field"=>"prize6_total","value"=>I('post.prize6_total')),
			array("aid"=>$aid,"name"=>"奖品6概率","field"=>"prize6_rate","value"=>I('post.prize6_rate')),
			array("aid"=>$aid,"name"=>"奖品6类型","field"=>"prize6_type","value"=>I('post.prize6_type')),
			array("aid"=>$aid,"name"=>"奖品6是否必中","field"=>"prize6_only","value"=>I('post.prize6_only',0,"intval")),
			
		);
		sp_save_activity_params($data);
		return true;
	}
	function save_weishare($aid){
		$data = [
			["aid" => $aid, "name" => "是否显示倒计时", "field" => "iscountdown", "value" => I('post.iscountdown',1)],
			["aid" => $aid, "name" => "卡片数量", "field" => "count", "value" => I('post.count',0,"intval")],
			["aid" => $aid, "name" => "最高得分限制", "field" => "max", "value" => I('post.max',0,"intval")],
			["aid" => $aid, "name" => "初始分值", "field" => "start", "value" => I('post.start',0,"intval")],
			["aid" => $aid, "name" => "积分单位","field" => "unit", "value" => I('post.unit','分')],
			["aid" => $aid, "name" => "固定助力积分", "field" => "step", "value" => I('post.step',10,"intval")],
			["aid" => $aid, "name" => "助力随机积分范围", "field" => "steprandom", "value" => I('post.steprandom','10、50')],
			["aid" => $aid, "name" => "助力积分方式", "field" => "steptype", "value" => I('post.steptype',1)],
		];
		sp_save_activity_params($data);
		return true;
	}
	function save_invitegift($aid){
		$data = array(
			array("aid"=>$aid,"name"=>"单位LOGO","field"=>"logo","value"=>I('post.logo')),
			array("aid"=>$aid,"name"=>"活动日期","field"=>"timestr","value"=>I('post.timestr')),
			array("aid"=>$aid,"name"=>"单位名称","field"=>"school","value"=>I('post.school')),
			array("aid"=>$aid,"name"=>"联系电话","field"=>"tel","value"=>I('post.tel')),
		);
		sp_save_activity_params($data);
		return true;
	}
	
	function save_weicheer($aid){
		$data = array(
			array("aid"=>$aid,"name"=>"访问量","field"=>"hits","value"=>0),
			array("aid"=>$aid,"name"=>"人气值","field"=>"pvalue","value"=>I('post.pvalue'),0,"intval"),
			array("aid"=>$aid,"name"=>"最大报名人数限制","field"=>"maxjoin","value"=>I('post.maxjoin'),0,"intval"),
			array("aid"=>$aid,"name"=>"奖品1图片","field"=>"prize1_thumb","value"=>I('post.prize1_thumb')),
			array("aid"=>$aid,"name"=>"奖品1名称","field"=>"prize1_name","value"=>I('post.prize1_name')),
			array("aid"=>$aid,"name"=>"奖品1数量","field"=>"prize1_total","value"=>I('post.prize1_total')),
			array("aid"=>$aid,"name"=>"奖品1概率","field"=>"prize1_rate","value"=>I('post.prize1_rate')),
			array("aid"=>$aid,"name"=>"奖品1类型","field"=>"prize1_type","value"=>I('post.prize1_type')),
			
			array("aid"=>$aid,"name"=>"奖品2图片","field"=>"prize2_thumb","value"=>I('post.prize2_thumb')),
			array("aid"=>$aid,"name"=>"奖品2名称","field"=>"prize2_name","value"=>I('post.prize2_name')),
			array("aid"=>$aid,"name"=>"奖品2数量","field"=>"prize2_total","value"=>I('post.prize2_total')),
			array("aid"=>$aid,"name"=>"奖品2概率","field"=>"prize2_rate","value"=>I('post.prize2_rate')),
			array("aid"=>$aid,"name"=>"奖品2类型","field"=>"prize2_type","value"=>I('post.prize2_type')),
			
			array("aid"=>$aid,"name"=>"奖品3图片","field"=>"prize3_thumb","value"=>I('post.prize3_thumb')),
			array("aid"=>$aid,"name"=>"奖品3名称","field"=>"prize3_name","value"=>I('post.prize3_name')),
			array("aid"=>$aid,"name"=>"奖品3数量","field"=>"prize3_total","value"=>I('post.prize3_total')),
			array("aid"=>$aid,"name"=>"奖品3概率","field"=>"prize3_rate","value"=>I('post.prize3_rate')),
			array("aid"=>$aid,"name"=>"奖品3类型","field"=>"prize3_type","value"=>I('post.prize3_type')),	
			
			array("aid"=>$aid,"name"=>"奖品4图片","field"=>"prize4_thumb","value"=>I('post.prize4_thumb')),
			array("aid"=>$aid,"name"=>"奖品4名称","field"=>"prize4_name","value"=>I('post.prize4_name')),
			array("aid"=>$aid,"name"=>"奖品4数量","field"=>"prize4_total","value"=>I('post.prize4_total')),
			array("aid"=>$aid,"name"=>"奖品4概率","field"=>"prize4_rate","value"=>I('post.prize4_rate')),
			array("aid"=>$aid,"name"=>"奖品4类型","field"=>"prize4_type","value"=>I('post.prize4_type')),

			array("aid"=>$aid,"name"=>"奖品5图片","field"=>"prize5_thumb","value"=>I('post.prize5_thumb')),
			array("aid"=>$aid,"name"=>"奖品5名称","field"=>"prize5_name","value"=>I('post.prize5_name')),
			array("aid"=>$aid,"name"=>"奖品5数量","field"=>"prize5_total","value"=>I('post.prize5_total')),
			array("aid"=>$aid,"name"=>"奖品5概率","field"=>"prize5_rate","value"=>I('post.prize5_rate')),
			array("aid"=>$aid,"name"=>"奖品5类型","field"=>"prize5_type","value"=>I('post.prize5_type')),

			array("aid"=>$aid,"name"=>"奖品6图片","field"=>"prize6_thumb","value"=>I('post.prize6_thumb')),
			array("aid"=>$aid,"name"=>"奖品6名称","field"=>"prize6_name","value"=>I('post.prize6_name')),
			array("aid"=>$aid,"name"=>"奖品6数量","field"=>"prize6_total","value"=>I('post.prize6_total')),
			array("aid"=>$aid,"name"=>"奖品6概率","field"=>"prize6_rate","value"=>I('post.prize6_rate')),
			array("aid"=>$aid,"name"=>"奖品6类型","field"=>"prize6_type","value"=>I('post.prize6_type')),
			
		);
		sp_save_activity_params($data);
		return true;
	}
	/**
	* 保存会议邀请函
	*/
	function save_weimeet($aid){
		$data = array(
			array("aid"=>$aid,"name"=>"单位名称","field"=>"school","value"=>I('post.school')),
			array("aid"=>$aid,"name"=>"活动价格","field"=>"price","value"=>I('post.price')),
			array("aid"=>$aid,"name"=>"活动日期","field"=>"timestr","value"=>I('post.timestr')),
			array("aid"=>$aid,"name"=>"单位logo","field"=>"logo","value"=>I('post.logo')),
			array("aid"=>$aid,"name"=>"活动类型","field"=>"activity_type","value"=>I('post.activity_type'))
		);
		sp_save_activity_params($data);
	}
	
	function save_weidiom($aid,$yuanaid){
		//保存扩展字段。
		$data = array(
			array("aid"=>$aid,"name"=>"设置积分值","field"=>"pvalue","value"=>I('post.pvalue'),0,"intval"),
		);
		sp_save_activity_params($data);
		$itemid = I("post.course_id");
		$itemtitle = I("post.course_name");
		$item1 = I("post.course_yprice");
		if($yuanaid==$aid)
		{
			//将所有的id拼接起来
			$newitemid="0";
			for($i=0;$i<count($itemid);$i++)
			{
				if($itemid[$i]>0) $newitemid.=",".$itemid[$i];
			}
			//删除不存在的id。
			D("WeidiomsChengyu")->where('aid='.$aid.' and id not in('.$newitemid.')')->delete();
		}
		//遍历数据，如果有id就更新数据，没有id就插入数据。
		for($i=0;$i<count($itemid);$i++)
		{
			$data=array('idioms'=>$itemtitle[$i],
						'idioms_des'=>$item1[$i]);
			if($itemid[$i]>0&&$yuanaid==$aid)
			{
				D("WeidiomsChengyu")->where('id='.$itemid[$i])->save($data);
			}
			else
			{
				$data['aid']=$aid;
				D("WeidiomsChengyu")->add($data);
			}
		}
	}
	/**
	* 开学大礼包个性化属性保存
	*/
	function save_weigift($aid){
		$data = array(
			array("aid"=>$aid,"name"=>"学校名称","field"=>"school","value"=>I('post.school')),
			array("aid"=>$aid,"name"=>"优惠金额","field"=>"price","value"=>I('post.price')),
			array("aid"=>$aid,"name"=>"优惠说明","field"=>"coupon_des","value"=>I('post.coupon_des')),
			array("aid"=>$aid,"name"=>"优惠比例","field"=>"rate","value"=>I('post.rate')),
			array("aid"=>$aid,"name"=>"每天次数限制","field"=>"limit_day","value"=>I('post.limit_day')),
			array("aid"=>$aid,"name"=>"总次数限制","field"=>"limit_total","value"=>I('post.limit_total'))
		);
		sp_save_activity_params($data);
	}
	/**
	* 刮刮乐个性化属性保存
	*/
	function save_scratch($aid)
	{
		$data = [
			["aid" => $aid, "name" => "奖品1图片", "field" => "prize1_thumb", "value" => I('post.prize1_thumb')],
			["aid" => $aid, "name" => "奖品1名称", "field" => "prize1_name", "value" => I('post.prize1_name')],
			["aid" => $aid, "name" => "奖品1数量", "field" => "prize1_total", "value" => I('post.prize1_total')],
			["aid" => $aid, "name" => "奖品1概率", "field" => "prize1_rate", "value" => I('post.prize1_rate')],
			["aid" => $aid, "name" => "奖品1类型", "field" => "prize1_type", "value" => I('post.prize1_type')],
			["aid" => $aid, "name" => "奖品1是否必中", "field" => "prize1_only", "value" => I('post.prize1_only',0,"intval")],

			["aid" => $aid, "name" => "奖品2图片", "field" => "prize2_thumb", "value" => I('post.prize2_thumb')],
			["aid" => $aid, "name" => "奖品2名称", "field" => "prize2_name", "value" => I('post.prize2_name')],
			["aid" => $aid, "name" => "奖品2数量","field" => "prize2_total", "value" => I('post.prize2_total')],
			["aid" => $aid, "name" => "奖品2概率","field" => "prize2_rate", "value" => I('post.prize2_rate')],
			["aid" => $aid, "name" => "奖品2类型","field" => "prize2_type", "value" => I('post.prize2_type')],
			["aid" => $aid, "name" => "奖品2是否必中", "field" => "prize2_only", "value" => I('post.prize2_only',0,"intval")],

			["aid" => $aid, "name" => "奖品3图片", "field" => "prize3_thumb", "value" => I('post.prize3_thumb')],
			["aid" => $aid, "name"=> "奖品3名称", "field" => "prize3_name", "value" => I('post.prize3_name')],
			["aid" => $aid, "name"=> "奖品3数量", "field" => "prize3_total", "value"=>I('post.prize3_total')],
			["aid" => $aid, "name" => "奖品3概率", "field" => "prize3_rate", "value" => I('post.prize3_rate')],
			["aid" => $aid, "name" => "奖品3类型", "field" => "prize3_type", "value" => I('post.prize3_type')],
			["aid" => $aid, "name" => "奖品3是否必中", "field" => "prize3_only", "value" => I('post.prize3_only',0,"intval")],

			["aid" => $aid, "name" => "奖品4图片", "field" => "prize4_thumb", "value" => I('post.prize4_thumb')],
			["aid" => $aid, "name" => "奖品4名称", "field" => "prize4_name", "value" => I('post.prize4_name')],
			["aid" => $aid, "name" => "奖品4数量", "field" => "prize4_total", "value" => I('post.prize4_total')],
			["aid" => $aid, "name" => "奖品4概率", "field" => "prize4_rate", "value" => I('post.prize4_rate')],
			["aid" => $aid, "name" => "奖品4类型", "field" => "prize4_type", "value" => I('post.prize4_type')],
			["aid" => $aid, "name" => "奖品4是否必中", "field" => "prize4_only", "value" => I('post.prize4_only',0,"intval")],

			["aid" => $aid, "name" => "奖品5图片", "field" => "prize5_thumb", "value" => I('post.prize5_thumb')],
			["aid" => $aid, "name" => "奖品5名称", "field" => "prize5_name", "value" => I('post.prize5_name')],
			["aid" => $aid, "name" => "奖品5数量", "field" => "prize5_total", "value" => I('post.prize5_total')],
			["aid" => $aid, "name" => "奖品5概率", "field" => "prize5_rate", "value" => I('post.prize5_rate')],
			["aid" => $aid, "name" => "奖品5类型", "field" => "prize5_type", "value" => I('post.prize5_type')],
			["aid" => $aid, "name" => "奖品5是否必中", "field" => "prize5_only", "value" => I('post.prize5_only',0,"intval")],

			["aid" => $aid, "name" => "奖品6图片", "field" => "prize6_thumb", "value" => I('post.prize6_thumb')],
			["aid" => $aid, "name" => "奖品6名称", "field" => "prize6_name", "value" => I('post.prize6_name')],
			["aid" => $aid, "name" => "奖品6数量", "field" => "prize6_total", "value" => I('post.prize6_total')],
			["aid" => $aid, "name" => "奖品6概率", "field" => "prize6_rate", "value" => I('post.prize6_rate')],
			["aid" => $aid, "name" => "奖品6类型", "field" => "prize6_type", "value" => I('post.prize6_type')],
			["aid" => $aid, "name" => "奖品6是否必中", "field" => "prize6_only", "value" => I('post.prize6_only',0,"intval")]
		];
		sp_save_activity_params($data);
		return true;
	}
	//画板祝福个性化字段保存
	function save_weigreet($aid){
		$data = array(
			array("aid"=>$aid,"name"=>"学校名称","field"=>"school","value"=>I('post.school')),
			array("aid"=>$aid,"name"=>"宣传语","field"=>"slogan","value"=>I('post.slogan'))
		);
		sp_save_activity_params($data);
	}
	function save_challenge($aid)
	{
		$data = [
			["aid" =>$aid, "name" => "答题时间(秒)", "field" => "game_time", "value" => I('post.game_time')],
			["aid" => $aid, "name" => "题目分值", "field" => "amount", "value" => I('post.amount')],
			["aid" => $aid, "name" => "题目数量", "field" => "question_total", "value" => I('post.question_total')],
			["aid" => $aid, "name" => "挑战成功所需的分数", "field" => "success_num", "value" => I('post.success_num')],
			["aid" => $aid, "name" => "结束提示", "field" => "prompt", "value" => I('post.prompt')]
		];
		sp_save_activity_params($data);
	}
	//投票个性化字段保存
	function save_yyvote($aid){
		$data = array(
//			array("aid"=>$aid,"name"=>"显示前多少人","field"=>"show_total","value"=>I('post.show_total')),
//			array("aid"=>$aid,"name"=>"每页显示条数","field"=>"page_total","value"=>I('post.page_total')),
			array("aid"=>$aid,"name"=>"是否开启前台报名","field"=>"is_front_join","value"=>I('post.is_front_join')),
//			array("aid"=>$aid,"name"=>"是否开启报名审核","field"=>"is_check","value"=>I('post.is_check'))

		);
		sp_save_activity_params($data);
	}
	/**
	* 删除活动
	**/
	public function delete(){
		$res = $this->activity->where("id=$id")->save(array("valid"=>0));
		if($res){
			$this->success("删除成功！");
		}else{
			$this->error($this->activity->getError());
		}
	}
	/*
	*添加应用成功
	*/
	public function addresult()
	{
		$id=I('get.id',0,'intval');
		$info=$this->activity->where("id=$id")->find();
		$this->assign('info',$info);
		$this->display("/Index/addresult");
		//"/Thimes/".$module."/".$page
		//"/Index/addresult"
	}
}

