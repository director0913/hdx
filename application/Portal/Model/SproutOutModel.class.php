<?php
namespace Portal\Model;
use Common\Model\CommonModel;
/**
 * 基础model
 */
class SproutOutModel extends CommonModel{
    Protected $autoCheckFields = false;
    /**
     * 排行列表
     * @param    array    $data    数据 
     * @return   integer           新增数据的id 
     */
    public function rankList($parm){
        $data = M('sign')
                ->join('LEFT JOIN '.C('DB_PREFIX').'customer ON '.C('DB_PREFIX').'customer.user_id = '.C('DB_PREFIX').'sign.user_id')
                // ->join('LEFT JOIN sj_sign ON sj_sign.user_id = sj_customer.user_id')
                ->where(array('sprout_id'=>$parm['sprout_id']))
                ->order($parm['order']." ".$parm['sort'])->select();      
        return $data;
    }
    
   //投票
    public function toVote($parm){
        $res = M('sprout')->where(array('sprout_id'=>$parm['sprout_id']))->find();
        //查看投票时间段
        if ($res['vote_start_at']) {
            //判断是否有投票限制时间
            if ($res['vote_start_at']>time()) {
                return ['msg'=>'投票尚未开始！','status'=>false];
            }elseif ($res['vote_end_at']<time()) {
                return ['msg'=>'投票已经结束！','status'=>false];
            }
        }else{
            //判断是不是在活动时间内
            if ($res['start_at']>time()) {
                return ['msg'=>'活动尚未开始！','status'=>false];
            }elseif ($res['end_at']<time()) {
                return ['msg'=>'活动已经结束！','status'=>false];
            }
            //判断每个小时是否有票数限制
            if ($res['limit_num']) {
                $start_at = strtotime(date('Y-m-d'))+date('H')*3600;
                $end_at = $start_at+3600;
                $hoursWhere = ['user_id'=>$_SESSION['if_user_id'],'sign_id'=>$parm['sign_id'],'create_at'=> array(array(array('egt',$start_at),array('elt',$end_at)),'AND')];
                $hoursCount = M('vote')->where($hoursWhere)->count();
                if (intval($res['hours_num']) == $hoursCount) {
                    return ['msg'=>'投票失败，当前选手每个小时获得票数已经到达上限！','status'=>false];
                }   
            }
        }
        if ($res['voting_form']==1) {
            $start_at = strtotime(date('Ymd'));
            $end_at = strtotime(date('Ymd'))+86400;
            $countWhere = ['user_id'=>$_SESSION['if_user_id'],'create_at'=> array(array(array('egt',$start_at),array('elt',$end_at)),'AND')];
        }else{
            $countWhere = ['user_id'=>$_SESSION['if_user_id']];
        }
        //判断每天最大投票数
        $dayCount = M('vote')->where($countWhere)->count();
       // echo M('vote')->getLastSql();
       //   var_dump($dayCount);die;die;
        if (intval($res['player_day_num']) > $dayCount) {
            $voteCount = M('sign')->where(['sign_id'=>$parm['sign_id']])->setInc('vote_num');
            $add['user_id'] = 1;
            $add['sign_id'] = $parm['sign_id'];
            $add['create_at'] = time();    
            $data = M('vote')->add($add);       
            return ['msg'=>'投票成功！','status'=>true];
        }
        return ['msg'=>'您的当天的投票数已用完！','status'=>false]; 
    }
}