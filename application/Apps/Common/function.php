<?php
// +----------------------------------------------------------------------
// | 营销中心公共函数定义区
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Tuolaji <479923197@qq.com>
// +----------------------------------------------------------------------
function sp_get_activity($aid)
{
	$activity = D("Activity")->where(array("id"=>$aid))->find();
	return $activity;
}
/**
* 保存活动的配置参数 -- array(array('id'=>1,'name'=>'prize_name1','value'=>'奖品1'),array('id'=>2,'name'=>'prize_name2','value'=>'奖品2'));
*/
function sp_save_activity_params($params){
	$params_obj = D("ActivityParams");
	foreach($params as $v){
		$res = $params_obj->where(array("aid"=>$v['aid'],"field"=>$v['field']))->find();
		if($res){
			$v['id'] = $res['id'];
			$params_obj->save($v);
		}else{
			$params_obj->add($v);
		}
	}
}
/**
* 保存微官网的配置参数 -- array(array('id'=>1,'name'=>'prize_name1','value'=>'奖品1'),array('id'=>2,'name'=>'prize_name2','value'=>'奖品2'));
*/
function sp_save_weisite_params($params){
	$params_obj = D("WesiteParams");
	foreach($params as $v){
		$res = $params_obj->where(array("site_id"=>$v['site_id'],"field"=>$v['field']))->find();
		if($res){
			$v['id'] = $res['id'];
			$params_obj->save($v);
		}else{
			$params_obj->add($v);
		}
	}
}

/**
* 获取相应对应活动的指定字段的值
**/
function sp_get_activity_params($aid){
	$params_obj = D("ActivityParams");
	return $params_obj->where(array("aid"=>$aid))->select();
}
function sp_get_activity_params_field($aid,$field){
	$params_obj = D("ActivityParams");
	return $params_obj->where(array("aid"=>$aid,"field"=>$field))->getField("value");
}
/**
* 分析活动的状态
* $aid 活动id
*/
function analyze_activitys($aid){
	exit(json_encode(array('status'=>0,'info'=>'活动不存在')));
	//$this->success("该活动不存在！");
	/**
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
	}**/
}
/**
* 分析用户的状态--分析用户的三个指标
*/
function sp_analyze_activity_user($user_info,$aid=0){
	//$user_info = session("userInfo");
	$from_user = $user_info['openid'];
	$activity = D("Activity")->where(array("id"=>$aid,"valid"=>1))->find();
	$user = D("ActivityUser")->where(array("aid"=>$aid,"from_user"=>$from_user))->find();
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