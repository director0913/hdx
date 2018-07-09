<?php 
namespace Home\Controller;
use Think\Controller;
class SproutController extends Controller{
    //萌宝 投票
    /**手机创建活动
     */
    public function index(){
        $sprout_id = I('get.sprout_id','0');
        $user_info=session('wx_userInfo');
        if (!$user_info) {
            A('WeChat')->start(get_host_name().(U('Home/Sprout/index',array('sprout_id'=>I('get.sprout_id','0')))));
        }
        $res = M('sprout')->where(array('sprout_id'=>$sprout_id))->find();
        //报名人数
        $count = M('sign')->where(array('sprout_id'=>$sprout_id))->count();
        //累计票数
        $vote = M('sign')->where(array('sprout_id'=>$sprout_id))->sum('vote_num');
        $parm = ['sprout_id'=>$sprout_id,'order'=>'vote_num','sort'=>'desc'];
        $rankList = D("Sprout")->rankList($parm);
        $res['sort_rule'] = json_decode($res['sort_rule']);
        //微信分享api
        $wx_share = A('WeChat')->shareAPi(sp_get_url());
        $this->assign('res',$res);
        $this->assign('count',$count);
        $this->assign('vote',$vote);
        $this->assign('rankList',$rankList);
        $this->assign('wx_share',$wx_share);
        $this->display();
    }
    //列表rank
    public function ranking(){
        $sprout_id = I('get.sprout_id','0');
        $res = M('sprout')->where(array('sprout_id'=>$sprout_id))->find();
        $parm = ['sprout_id'=>$sprout_id,'order'=>'vote_num','sort'=>'desc'];
        $vote = D('Sprout')->rankList($parm);
        $this->assign('res',$res);
        $this->assign('vote',$vote);
        $this->display();
    }
    //说明
    public function win(){
        $sprout_id = I('get.sprout_id','0');
        $res = M('Sprout')->where(array('sprout_id'=>$sprout_id))->find();
        $this->assign('res',$res);
        $this->display();
    }
    //报名
    public function reports(){
        $sprout_id = I('get.sprout_id','0');
        //手机端不允许报名
        $sprout = M('sprout')->where(array('sprout_id'=>$sprout_id))->find();
        if (isMobile()) {
            if (!$sprout['phone_sign']) {
                echo '<script>alert("手机端不允许报名！");history.back(-1);</script>';
            }
        }
        //先查询是否 还可以报名，人数限制
        $sign_count = M('Sign')->where(['sprout_id'=>$sprout_id])->count();
        if ($sprout['sign_is_limit']==1) {
            if ($sign_count >= $sprout['sign_num']) {
                echo '<script>alert("报名人数已达到最大限度，不能继续报名！");history.back(-1);</script>';
            }
        }
        $sprout['link_info'] = json_decode($sprout['link_info']);
        $sprout_id = I('get.sprout_id','0');
        $res = M('Sprout')->where(array('sprout_id'=>$sprout_id))->find();
        $this->assign('res',$res);
        $this->assign('sprout',$sprout);
        $this->display();
    }
    //详情页
    public function detail(){
        $sign_id = I('get.sign_id','0');
        $sprout_id = I('get.sprout_id','0');
        $sprout = M('sprout')->where(array('sprout_id'=>$sprout_id))->find();
        $res = M('Sign')->join('LEFT JOIN sj_customer ON sj_customer.user_id = sj_sign.user_id')->where(array('sign_id'=>$sign_id))->find();
        $res['img'] = json_decode($res['img']);
        $this->assign('res',$res);
        $this->assign('sprout',$sprout);
        $this->display();
    }
    //报名
    public function sign(){
        //先查询是否 还可以报名，人数限制
        $sprout = M('sprout')->where(array('sprout_id'=>$sprout_id))->find();
        $sign_count = M('Sign')->where(['sprout_id'=>I('post.sprout_id')])->count();
        if ($sprout['sign_is_limit']==1) {
            if ($sign_count >= $sprout['sign_num']) {
                echo '<script>alert("报名人数已达到最大限度，不能继续报名！");history.back(-1);</script>';
            }
        }
        
        $add['title'] = I('post.title');
        $add['description'] = I('post.description');
        $add['name'] = I('post.name');
        $add['phone'] = I('post.phone');
        $add['info'] = I('post.info');
        $add['create_at'] = time();
        $add['sprout_id'] = I('post.sprout_id');
        if(!empty($_FILES)){
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  = './Uploads/'; // 设置附件上传根目录
            $upload->savePath  = 'Sprout/'; // 设置附件上传（子）目录
            $info=array();
            foreach ($_FILES['img']['name'] as $key=>$value){
                $file1=array();
                $file1["img"]['name']=$value;
                $file1["img"]['type']=$_FILES['img']["type"][$key];
                $file1["img"]['tmp_name']=$_FILES['img']["tmp_name"][$key];
                $file1["img"]['error']=$_FILES['img']["error"][$key];
                $file1["img"]['size']=$_FILES['img']["size"][$key];
                $info   =   $upload->upload();
                $imgs[] = '/Uploads/'.$info[0]['savepath'].$info[0]['savename'];
            }
            $add['img'] = json_encode($imgs);
        }
        $res = M('sign')->add($add);
        $this->redirect('Home/Sprout/detail', array('sprout_id' => $add['sprout_id'],'sign_id' => $res), 0);
        $this->assign('res',$res);
        $this->display();
    }
    //创建活动
    public function add(){
        $user_info=session('wx_userInfo');
        if (!$user_info) {
            A('WeChat')->start(get_host_name().(U('Home/Sprout/index',array('sprout_id'=>I('get.sprout_id','0')))));
        }
        // if (!sp_is_user_login()) {
        //     $service = urlencode(U('Home/Sprout/add'));
        //     redirect('https://login.xiaoyingtong.net/login?service='.$service);
        // }
        if (IS_POST) {
            $add['title'] = I('post.title');
            $add['start_at'] = strtotime(I('post.start_at'));
            $add['end_at'] = strtotime(I('post.end_at'));
            $add['is_code'] = intval(I('post.is_code'));
            $add['hours_num'] = intval(I('post.hours_num'));
            $add['is_code'] = intval(I('post.is_code'));
            $add['limit_num'] = intval(I('post.limit_num'));
            $add['hours_num'] = intval(I('post.hours_num'));
            $add['description'] = I('post.description');
            $add['city'] = I('post.city');
            $add['wenben'] = I('post.wenben');
            if(!empty($_FILES['images'])){
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize   =     3145728 ;// 设置附件上传大小
                $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->rootPath  = './Uploads/'; // 设置附件上传根目录
                $upload->savePath  = 'Sprout/'; // 设置附件上传（子）目录
                $info=array();
                foreach ($_FILES['images']['name'] as $key=>$value){
                    $file1=array();
                    $file1["images"]['name']=$value;
                    $info   =   $upload->upload();
                    $imgs[] = '/Uploads/'.$info[0]['savepath'].$info[0]['savename'];
                }
                $add['images'] = json_encode($imgs);
            }
            if(!empty($_FILES['videos'])){
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize   =     3145728 ;// 设置附件上传大小
                $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->rootPath  = './Uploads/'; // 设置附件上传根目录
                $upload->savePath  = 'Sprout/'; // 设置附件上传（子）目录
                $info=array();
                foreach ($_FILES['videos']['name'] as $key=>$value){
                    $file1=array();
                    $file1["videos"]['name']=$value;
                    $info   =   $upload->upload();
                    $video[] = '/Uploads/'.$info[0]['savepath'].$info[0]['savename'];
                }
                $add['videos'] = json_encode($video);
            }
           
            $add['store_title'] = I('post.store_title');
            $add['store_addr'] = I('post.store_addr');
            $add['store_buliding_num'] = I('post.store_buliding_num');
            $add['store_tel'] = I('post.store_tel');
            $add['create_at'] = time();
            $add['sprout_id'] = I('post.sprout_id');
            $add['sprout_id'] = $_SESSION['user_id'];
            $res = M('sprout')->add($add);
            if ($res) {
                //添加到项目表
                $project['create_id'] = sp_get_current_userid();
                $project['table_name'] = 'Sprout';
                $project['table_id'] = $res;
                $project['table_id_name'] = 'sprout_id';
                $project['create_time'] = $add['create_at'];
                M('Project')->add($project);
                $this->redirect('Home/Sprout/index', array('sprout_id' => $res),0);
            }
        }else{
            $this->display();
        }
    }
        //获取排序列表
    public function getRankList(){
        if(!empty($_GET)){
            $sort = I('get.sort') == 'sort'?'asc':'desc';
            $order = I('get.sort')== 'sort'?'sign_id':I('get.sort');;
            $parm = ['sprout_id'=>I('get.sprout_id'),'order'=>$order,'sort'=>$sort];
            $data = D('Sprout')->rankList($parm);
            $this->ajaxReturn($data);
        }
    }
    //投票
    public function toVote(){
        if(!empty($_GET)){
            $sign_id = I('get.sign_id') ;
            $sprout_id = I('get.sprout_id') ;
            $parm['sign_id'] = $sign_id;
            $parm['sprout_id'] = $sprout_id;
            //$model = new Home\Model\SproutModel;;
            $res = D('Sprout')->toVote($parm);
            $this->ajaxReturn($res);            
        }
    }
	
}