<?php
namespace Home\Controller;
use Think\Controller;
class CutdownController extends Controller{
    /**
     * 用户查看所有的砍价商品
     */
    public function look_goods(){

        $id = $_POST['id'];
        $cus_id = $_POST['cus_id'];
        $akm = M('activity')->where(array('ac_id'=>$id))->getField('end_time');
        $uzi = strtotime($akm);
        $now = time();
        if($uzi<$now){
            $if_send['if_send'] = 2;
            M('activity')->where(array('ac_id'=>$id))->save($if_send);
        }
        $ump = M('order')->where(array('user_id'=>$cus_id))->select();
        foreach ($ump as $k=>$v){
            $rng = M('commodity')->where(array('com_id'=>$v['com_id']))->getField('do_end_time');
            $c = strtotime($rng);
            $now = time();
            if($c<$now){
                M('order')->where(array('or_id'=>$v['or_id']))->save(array('if_end'=>2));
            }

        }
        $resc = M('customer')->where(array('cus_id'=>$cus_id))->find();
        if($resc){
            if($resc['openid'] == null || $resc['openid'] == ""){
                $result['if_zc'] = 0;//未授权
            }
        }else{
            $result['if_zc'] = 0;//未注册
        }
        $res = M('activity')->where(array('ac_id'=>$id))->find();
        $res['bgm'] = M('bgm')->where(array('id'=>$res['bgm_id']))->getField('bgm');
        $times = $res['end_time'];
        $res['start_time'] = date('Y年m月d H:i',$res['start_time']);
        $res['end_time'] = date('Y年m月d H:i',$res['end_time']);
        $row = M('commodity')->where(array('ac_id'=>$id))->select();
        $actor = M('actor')->where(array('ac_id'=>$id))->count();
        $order = M('order')->where(array('ac_id'=>$id))->count();
        $orders = M('order')->join(C('DB_PREFIX')."commodity on ".C('DB_PREFIX')."commodity.com_id=".C('DB_PREFIX')."order.com_id")->where(array(C('DB_PREFIX').'order.user_id'=>$cus_id,'if_end'=>1))->select();
        $result['end_times'] =strtotime($times);
        $result['if_end'] = $orders;
        $result['tol'] =$actor+$order+$res['participants'];
        $result['huodong'] =$res;
        $result['shangp'] =$row;
        $result['order_num'] =$order;
        $result['if_author'] = M('customer')->where(array('user_id'=>$cus_id))->getField('if_author');
        $this->ajaxReturn($result);
    }
    /**
     * 砍价已完成的详细内容
     */
    public function cut_end(){
        if(!empty($_POST)){
            $id = $_POST['or_id'];
            $row = M('order')->join(C('DB_PREFIX')."commodity on ".C('DB_PREFIX')."commodity.com_id=".C('DB_PREFIX')."order.com_id")->where(array('or_id'=>$id,'if_end'=>1))->find();
            $this->ajaxReturn($row);
        }else{
            echo 0;
        }
    }
    /**
     * 创建砍价
     *  1.生成砍价随机金额
     *  2.数据存放
     */
    public function create_cut(){
        if(!empty($_GET)){
            $data['com_id'] = $_GET['com_id'];
            $data['user_id'] = $_GET['user_id'];
            $res['if_author'] = M('customer')->where(array('user_id'=>$data['user_id']))->getField('if_author');
            $orid = $_GET['or_id'];
            $orids['or_id'] = $_GET['or_id'];
            $awm = M('commodity')->where(array('com_id'=>$data['com_id']))->find();

            $bgm_id['id'] = M('activity')->where(array('ac_id'=>$awm['ac_id']))->getField('bgm_id');
            $res['bgm'] = M('bgm')->where($bgm_id)->getField('bgm');//活动音乐

            $akm = M('activity')->where(array('ac_id'=>$awm['ac_id']))->getField('end_time');
            $uzi = strtotime($akm);
            $now = time();
            if($uzi<$now){
                $if_send['if_send'] = 2;
                M('activity')->where(array('ac_id'=>$awm['ac_id']))->save($if_send);
            }
            if($orid!=null){//判断是否帮砍
            	$ck = M('order')->where($orids)->find();
            }else{
            	$ck = M('order')->where($data)->find();
            }
            if($ck){
                $res['commodity'] = M('commodity')->where(array('com_id'=>$data['com_id']))->find();//商品信息
                $res['end_times'] = M('activity')->where(array('ac_id'=>$res['commodity']['ac_id']))->getField('end_time');//活动结束时间

                //砍价人
                $actor = M('actor')->where(array('or_id'=>$ck['or_id']))->select();
                foreach($actor as $k=>$v){
                    $ress +=$v['cut_money'];//已砍了多少元
                    $us['user_id'] = $v['cus_id'];
                    $user = M('customer')->where($us)->find();
                    $actor[$k]['user_name'] = $user['user_name'];
                    $actor[$k]['user_img'] = $user['user_img'];
                    $actor[$k]['create_time'] = date('Y-m-d H:i',$v['create_time']);
                }
                $res['actors'] = $actor;
                $res['actors_num'] = count($actor);
                //是否已完成砍价
                if($res['commodity']['cut_num']==count($actor)){
                	$res['yiwanc'] = 1;
                }else{
                	$res['yiwanc'] = 0;
                }

                //砍价进度
                $res['haiyou'] = $ck['original_price']-$ck['cut_price']-$ress;//离目标还有多少元
                $res['baifenb'] = ($ress/($ck['original_price']-$ck['cut_price']))*100;//百分比
                $res['or_id'] = $ck['or_id'];//order表的id
                $res['yikan'] = $ress;
                $res['userids'] = $ck['user_id'];//order表user_id
                $res['ac_id'] = $res['commodity']['ac_id'];//活动id
                //是否自砍
                if(M('actor')->where(array('or_id'=>$ck['or_id'],'user_id'=>$data['user_id']))->find()){
                    $res['if_i_cut'] = 0;
                }else{
                    $res['if_i_cut'] = 1;
                }
                $this->ajaxReturn($res);
            }else{
            	//砍价金额
                $result = M('commodity')->where(array('com_id'=> $data['com_id']))->find();
                $total=$result['original_price']-$result['cut_price'];//砍价总额
                $num=$result['cut_num'];// 最多砍价人数
                $min=0.01;//能砍价的最低金额
                $arr = array();
                for ($i=1;$i<$num;$i++)
                {
                    $safe_total=($total-($num-$i)*$min)/($num-$i);//随机安全上限
                    $money=mt_rand($min*100,$safe_total*100)/100;
                    $total=$total-$money;
                    array_push($arr,$money);
                }
                array_push($arr,$total);
                $cut_arr = json_encode($arr);
                $data['com_name'] = $result['com_name'];//商品名称
                $data['original_price'] = $result['original_price'];//商品原价
                $data['cut_price'] = $result['cut_price'];//砍价目标
                $data['thumb'] = $result['thumb'];//商品图片
                $data['subtitle'] = $result['subtitle'];//商品副标题
                $data['ac_id'] = $result['ac_id'];//活动id
                $data['if_end'] = 0;//是否砍价完成
                $data['if_prize'] = 0;//是否已兑奖
                $data['cut_arr'] = $cut_arr;
                $data['times'] = time();//创建时间

                    $ress = M('order')->add($data);


                if($ress){
                    $res['end_times'] = M('activity')->where(array('ac_id'=>$result['ac_id']))->getField('end_time');//活动结束时间
                    $res['commodity'] = M('commodity')->where(array('com_id'=>$data['com_id']))->find();//商品信息
                    $res['haiyou'] = $result['original_price']-$result['cut_price'];//离目标还有多少元
                    $res['yikan'] = 0;//已砍了多少元
                    $res['or_id'] = $ress;//创建的order
                    $res['baifenb'] = 0;//百分比
                    $res['if_i_cut'] = 1; //是否自砍
                    $res['ac_id'] = $result['ac_id']; //活动id
                    $this->ajaxReturn($res);
                }
            }

        }
    }

    /**
     * 自砍一刀，帮忙砍价，进入请传get
     */
    public function cut_help(){
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            if (!empty($_POST)) {
                $data['or_id'] = $_POST['or_id'];//传入的砍价的id
                $com_id = $_POST['com_id'];//商品Id
                $rs = M('order')->where(array('or_id' => $data['or_id']))->find();//查询砍价金额
                $re = M('actor')->where(array('or_id' => $data['or_id']))->select();//查询需砍价商品的参与人数
                $ro = M('commodity')->where(array('com_id' => $com_id))->find();//查询需砍价商品的限制人数
                $rt = M('activity')->where(array('ac_id' => $ro['ac_id']))->find();
                $num = count($re);
                if ($num < $ro['cut_num']) {

                    $data['cus_id'] = $_POST['cus_id'];//砍价用户的ID
                    $data['ac_id'] = $ro['ac_id'];//砍价活动ID
                    $act['or_id'] = $_POST['or_id'];
                    $act['cus_id'] = $_POST['cus_id'];
                    $yikan = M('actor')->where($act)->find();
                    if($yikan){
                    	$this->ajaxReturn(4);//已砍过
                    }else{
                        if($rt['if_limit']==true){
                            $xz['ac_id'] = $ro['ac_id'];
                            $xz['cus_id'] = $_POST['cus_id'];
                            $yikanss = M('actor')->where($xz)->count();
                            if($yikanss==$rt['limit']){
                                $this->ajaxReturn(5);//次数已到上限
                            }else{
                                $data['create_time'] = time();
                                $cut = json_decode($rs['cut_arr']);
                                $cut_money = $cut[$num];
                                $data['cut_money'] = $cut_money;
                                if (M('actor')->add($data)) {
                                    //砍价已完成，添加兑奖码
                                    $acnum = M('actor')->where(array('or_id' => $data['or_id']))->count();
                                    if($acnum==$ro['cut_num']){
                                        $vou = M('voucher')->where(array('com_id' => $com_id))->find();
                                        $da['or_id'] = $_POST['or_id'];
                                        $code['get_code'] = $vou['code'];
                                        $code['if_end'] = 1;
                                        $code['success_time'] = date("Y-m-d H:i:s",time());
                                        if(M('order')->where($da)->save($code)){
                                            $vo_id['id'] = $vou['id'];
                                            M('commodity')->where(array('com_id'=>$com_id))->setDec('com_num');
                                            M('voucher')->where($vo_id)->delete();
                                        }
                                    }
                                    $this->ajaxReturn(1);//成功入库
                                } else {
                                    $this->ajaxReturn(0);//入库失败
                                }
                            }
                        }



                    }
                    
                } else {
                    $this->ajaxReturn(3);//砍价已完成
                }
            } else {
                $id = $_GET['or_id'];
                $res = M('order')->join(C('DB_PREFIX')."actor on ".C('DB_PREFIX')."actor.or_id = ".C('DB_PREFIX')."order.or_id")->where(array(C('DB_PREFIX').'order.or_id' => $id))->find();
                $this->ajaxReturn($res);
            }
        }else{
            $this->ajaxReturn(array('res'=>'请在微信客户端打开'));
        }
    }

    /**
     * “我”的活动列表
     */
    public function my_activity_list(){
        if(!empty($_GET)){
            $id = I('get.user_id');
            $num = I('get.num');//查询条数
            $page = I('get.page',1);//页码
            $offset = $num*($page-1);
            $project = M('Project')->where(array("create_id"=>$id))->limit($offset,$num)->select();
            if ($project) {
                foreach ($project as $k => $v) {
                    if ($v['table_name'] && $v['table_id_name']) {
                        $res[] = M($v['table_name'])->where(array($v['table_id_name']=>$id))->find();
                    }
                }
            }
            $k = count($res);
            for($i=0;$i<$k;$i++){
                $res[$i]['ac_create_time'] =  date("Y-m-d H:i",$res[$i]['ac_create_time']);
            }
            $ck['res'] = $res;
            $ck['num'] = $k;
           $this->ajaxReturn($ck);
        }
    }
	/**
	 *我的砍价商品
	 *
	 *  */
	public function my_kanjia(){
		$user_id = I('user_id');
		$ac_id = I('ac_id');
		$or['end_time'] = M('activity')->where("ac_id=$ac_id")->getField('end_time');
		$or = M('order')->where("user_id=$user_id")->select();
		foreach($or as $k=>$v){
			$data['com_id'] = $v['com_id'];
			$or[$k]['num_kuc'] = M('commodity')->where($data)->getField('com_num');
		}
		$this->ajaxReturn($or);
	}


    /**
     * 发布活动
     */
	public function fb(){
        if(!empty($_POST)){
            $ac_id = $_POST['ac_id'];
            $data['if_send'] = $_POST['if_send'];//活动状态
            if(M('activity')->where(array('ac_id'=>$ac_id))->save($data)){
                echo 1;
            }else{
                echo 0;
            }
        }
    }

    /**
     * 删除活动
     */
    public function hd_del(){
        if(!empty($_POST)){
            $ac_id = $_POST['ac_id'];
           $activity =  M('activity')->where(array('ac_id'=>$ac_id))->delete();
           $commodity = M('commodity')->where(array('ac_id'=>$ac_id))->delete();
            if($activity || $commodity){
                echo 1;
            }else{
                echo 0;
            }
        }
    }
    /**
     * 查询兑奖信息
     */
    public function write_off(){
     if(!empty($_POST)){
            $user_id =$_POST['userid'];
            $get_code =$_POST['code'];
            $s = M('activity')->where(array('create_id'=>$user_id))->select();
            $order = array();
            for($i=0;$i<count($s);$i++){
                $order['order'] = M('order')->where(array('ac_id'=>$s[$i]['ac_id'],'if_end'=>1,'get_code'=>$get_code))->find();
                $order['customer'] = M('customer')->where(array('user_id'=>$order['order']['user_id']))->find();

            }
           $this->ajaxReturn($order);
       }
    }

    /**
     * 核销
     */
    public function do_write_off(){
        if(!empty($_POST)){
            $or_id = $_POST['or_id'];
            $data['if_prize'] =  1;
            if(M('order')->where(array('or_id'=>$or_id))->save($data)){
                echo 1;
            }else{
                echo 0;
            }
        }
    }

    /**
     *
     * web预览--二维码
     */
    public function preview_ercode(){
        if(!empty($_GET)){
            $user_id = $_GET['user_id'];
            $ac_id = $_GET['ac_id'];
           $url = "/fankes/index.html?ac_id=".$ac_id."&user_id=".$user_id;
           scerweima2($url);
        }
    }

    /**
     * web预览--链接
     */
    public function preview_link(){
        if(!empty($_GET)){
            $user_id = $_GET['user_id'];
            $ac_id = $_GET['ac_id'];
            $url = "/fankes/index.html?ac_id=".$ac_id."&user_id=".$user_id;
            $this->ajaxReturn($url);
        }
    }
    public function shouquan(){
        $user_id['user_id'] = I('user_id');
        $data['if_author'] =1;
        $sa = M('customer')->where($user_id)->save($data);
        if($sa){
            $this->ajaxReturn(1);
        }else{
            $this->ajaxReturn(0);
        }
    }
}
?>