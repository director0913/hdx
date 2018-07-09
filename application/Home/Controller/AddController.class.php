<?php 
namespace Home\Controller;
use Think\Controller;
class AddController extends Controller{
    /**手机创建活动
     *
     */
	public function add_activity(){//创建活动，之后的添加数据均为修改
        if(!empty($_POST)){
            $data['title'] = $_POST['title'];
            $data['explain'] = $_POST['explain'];
            $data['if_participants'] = $_POST['compulsory'];
            $data['create_id'] = $_POST['create_id'];
            $data['indx'] = 1;
            $data['ac_thumb'] = 'https://8171176.h40.faiusr.com/2/140/ACgIABACGAAgoKyM0gUop4XL3gUwgAU4-AI.jpg';
            $data['ac_create_time'] = time();

            $res = M('activity')->add($data);
            if($res){
                $sp['com_name'] = $_POST['spName'];
                $sp['com_num'] = $_POST['spNum'];
                $sp['original_price'] = $_POST['spYj'];
                $sp['cut_price'] = $_POST['cutp'];
                $sp['cut_num'] = $_POST['cutNum'];
                $sp['thumb'] = "https://hdg.faisys.com/image/dspkj/theGift.jpg?v=201504091029";
                $sp['stab'] = 1;
                $sp['ac_id'] = $res;
                //添加到项目表
                $project['create_id'] = $data['create_id'];
                $project['table_name'] = 'Activity';
                $project['table_id'] = $res;
                $project['table_id_name'] = 'ac_id';
                $project['create_time'] = $data['ac_create_time'];
                M('Project')->add($project);
                if(M('commodity')->add($sp)){
                        echo 1;
                }else{
                    echo 0;
                }
            }
        }

	}
    /**
     *活动修改  查出内容
     */
	public function edit_activity(){
		if(!empty($_POST)){
		    $id=$_POST['ac_id'];
		    $res['activity'] = M('activity')->where(array('ac_id'=>$id))->find();
		    $res['commodity'] = M('commodity')->where(array('ac_id'=>$id))->select();
		    $this->ajaxReturn($res);
        }
	}

    /**
     * web端创建活动
     */
    public function web_create_activity(){
        if(!empty($_POST)){
            $arr = $_POST['mhd'][0]['params'];
            $data['title'] = $_POST['mhd'][0]['hdjc']['title'];
            $data['start_time'] = $_POST['mhd'][0]['hdjc']['startime'];
            $data['end_time'] = $_POST['mhd'][0]['hdjc']['endtime'];
            $data['participants'] = $_POST['mhd'][0]['rsidnb'];//虚拟的参与人数
            $data['if_participants'] = $_POST['mhd'][0]['rsid'];//是否展示虚拟的参与人数
            $data['explain'] = $_POST['mhd'][0]['hdjc']['cont'];//说明
            $data['limit'] = $_POST['mhd'][0]['kjrsidnb'];
            $data['if_limit'] = $_POST['mhd'][0]['kjrsid'];
            $data['indx'] = $_POST['mhd'][0]['indx'];
            $data['function_button'] = $_POST['mhd'][0]['gjconf']['cndbtn'];
//            $data['contact'] = $_POST[''];
//            $data['compulsory'] = $_POST[''];
//            $data['wx_share_icon'] = $_POST[''];
//            $data['wx_share_text'] = $_POST[''];
            $data['button_content'] = $_POST['mhd'][0]['gjconf']['cndbtnlj'];
            $data['button_name'] = $_POST['mhd'][0]['gjconf']['cndbtnname'];
            $data['button_logo_img'] = $_POST['mhd'][0]['gjconf']['cndbtnimg'];
            $data['create_id'] = $_POST['mhd'][0]['userid'];
            $data['ac_create_time'] = time();
            $data['partic'] = $_POST['mhd'][0]['xzrsidnb'];//创建砍价人数
            $data['if_partic'] = $_POST['mhd'][0]['xzrsid'];//是否对创建砍价人数限制
            $data['ac_thumb'] = 'https://8171176.h40.faiusr.com/2/140/ACgIABACGAAgoKyM0gUop4XL3gUwgAU4-AI.jpg';
//            $data['wx_share_title'] = $_POST[''];
            $data['if_send'] = 0;
            $cc = M('activity')->add($data);
            if($cc){
                //添加到项目表
                $project['create_id'] = $data['create_id'];
                $project['table_name'] = 'Activity';
                $project['table_id'] = $cc;
                $project['table_id_name'] = 'ac_id';
                $project['create_time'] = $data['ac_create_time'];
                M('Project')->add($project);
                for($i=0;$i<$data['indx'];$i++){
                    $ck[$i]['com_name'] = $arr[$i]['cont'];
                    $ck[$i]['stab'] = $arr[$i]['stab'];
                    $ck[$i]['com_num'] = $arr[$i]['spnbr'];
                    $ck[$i]['original_price'] = $arr[$i]['spyj'];
                    $ck[$i]['cut_price'] = $arr[$i]['spkjmb'];
                    $ck[$i]['cut_num'] = $arr[$i]['spxknb'];
                    $ck[$i]['thumb'] = $arr[$i]['spimgs'];
                    $ck[$i]['change_way'] = $arr[$i]['spdjfs'];
                    $ck[$i]['do_notice'] = $arr[$i]['spti'];
                    $ck[$i]['do_address'] = $arr[$i]['spdiz'];
                    $ck[$i]['do_link'] = $arr[$i]['splj'];
                    $ck[$i]['do_time'] = $arr[$i]['spqx'];
                    if($ck[$i]['do_time'] == 2){
                        $stat = strtotime("+".$arr[$i]['spsxday']." day");
                        $end = strtotime("+".$arr[$i]['spsxday']+$arr[$i]['spyxday']." day");
                        $ck[$i]['do_start_time'] = date('Y-m-d H:i', $stat);
                        $ck[$i]['do_end_time'] = date('Y-m-d H:i', $end);
                    }else{
                        $ck[$i]['do_start_time'] = $arr[$i]['spstartime'];
                        $ck[$i]['do_end_time'] = $arr[$i]['spendtime'];
                    }
                    $ck[$i]['do_start_gd'] = $arr[$i]['spsxday'];
                    $ck[$i]['do_end_gd'] = $arr[$i]['spyxday'];
                    $ck[$i]['subtitle'] = $arr[$i]['sptitle'];
                    $ck[$i]['service_phone'] = $arr[$i]['sptel'];
                    $ck[$i]['shoud_know'] = $arr[$i]['spjxz'];
                    $ck[$i]['self_button'] = $arr[$i]['spbutton'];
                    $ck[$i]['button_name'] = $arr[$i]['spbuttonname'];
                    $ck[$i]['button_link'] = $arr[$i]['spbuttonhtt'];
                    $ck[$i]['button_img'] = $arr[$i]['spgcodimg'];
                    $ck[$i]['ac_id'] = $cc;
                   $like = M('commodity')->add($ck[$i]);
                   if($data['if_partic'] == 'true'){//生成兑奖码
                        for($a=0;$a<$ck[$i]['com_num'];$a++){
                            $vouch['code'] = getRandCode(8);
                            $vouch['com_id'] = $like;
                            M('VoucherModel:voucher','sj_','connection')->add($vouch);
                            //M('voucher')->add($vouch);
                        }
                   }
                }
                echo 1;

            }else{
               echo '活动规则入库失败';
            }
        }else{
            echo 0;
        }
    }
    /**
     * 手机端活动创建
     */
    public function modil_create_activity(){
        if(!empty($_POST)){
            $data['start_time'] = $_POST['start_time'];
            $data['end_time'] = $_POST['end_time'];
            $data['ac_thumb'] = $_POST['ac_thumb'];
            $data['explain'] = $_POST['explain'];
            $data['if_participants'] = 'false';
            $data['if_limit'] = 'true';
            $data['limit'] = '3';
            $data['if_partic'] = 'true';
            $data['partic'] = $_POST['com_num'];
            $data['if_send'] = 0;
            $data['ac_create_time'] = time();
            $data['bgm_id'] = $_POST['bgm_id'];
            //暂时演示注释，正式要打开
            //$data['create_id'] = sp_get_current_user();
            $data['create_id'] = 12;
            $data['title'] = $_POST['title'];
            $cc = M('activity')->add($data);
            if($cc){
                $ck['com_name'] = $_POST['com_name'];
                $ck['com_num'] = $_POST['com_num'];
                $ck['original_price'] = $_POST['original_price'];
                $ck['cut_price'] = $_POST['cut_price'];
                $ck['do_start_time'] = $_POST['start_time'];
                $ck['do_end_time'] = $_POST['end_time'];
                $ck['cut_num'] = $_POST['cut_num'];
                $ck['thumb'] = $_POST['thumb'];
                $ck['do_address'] = $_POST['do_address'];
                $ck['service_phone'] = $_POST['service_phone'];
                $ck['shoud_know'] = $_POST['shoud_know'];
                $ck['ac_id'] = $cc;
                $like = M('commodity')->add($ck);
                for($a=0;$a<$ck['com_num'];$a++){
                    $vouch['code'] = rand_string(8);
                    $vouch['com_id'] = $like;
                    M('voucher')->add($vouch);
                }
                //添加到项目表
                // $project['create_id'] = $data['create_id'];
                // $project['table_name'] = 'Activity';
                // $project['table_id'] = $cc;
                // $project['table_id_name'] = 'ac_id';
                // $project['create_time'] = $data['ac_create_time'];
                // $res = M('Project')->add($project);
                echo 'ac_id='.$cc.'&user_id='.$data['create_id'].'&com_id='.$like;
                //echo $cc;
            }else{
                echo "活动规则入库失败";
            }
        }else{
            echo 0;
        }
    }

//    public function css(){
//        $url="http://www.bargain.com/excel.php";
//       $row=file_get_contents($url);
//       var_dump($row);
//    }
    /**
     * 商品信息的添加
     */
    public function add_commoddity(){
        if(!empty($_POST)){
            $k=count($_POST);
            for($i=0;$i<$k;$i++){
                $result = M('commodity')->add($_POST[$k]);
            }
            if($result){
                $this->ajaxReturn($result);
            }

        }
    }


    /**
     *用户的注册
     */
	public function add_user(){
        header('Access-Control-Allow-Origin:*');
        if(!empty($_POST)){
            $wx_info = $_SESSION['user_info'];
            $data['phone'] = $_POST['phone'];
            $data['password'] = sha1($_POST['password']);
            $data['create_time'] = time();
            $cc = M('customer')->where(array('openid'=>$wx_info['openid']))->find();
            if($cc){
                if(M('customer')->where(array('user_id'=>$cc['user_id']))->save($data)){
                    echo 1;//注册成功
                }else{
                    echo 0;//注册失败
                }
            }else{
                $data['user_name'] = $wx_info['nickname'];
                $data['openid'] = $wx_info['openid'];
                $data['user_img'] = $wx_info['headimgurl'];
                $data['city'] = $wx_info['city'];
                if(M('customer')->add($data)){
                    echo 1;
                }else{
                    echo 0;
                }
            }

        }
    }
    /**
     *
     * 用户注册前获取微信基本信息
     */
        public function wx_info(){
            $r_code = new UserinfoController();
            $r_code->s_code();
        }

    /**
     * 验证用户是否存在
     */
    public function ifcz(){

        if(M('customer')->where(array('phone'=>$_POST['phone']))->find()){
            echo 0;
            exit;
        }else{
            echo 1;
        }
    }
    /**
     * 获取注册验证码
     */
    public function register(){//注册发送验证码
        if(!empty($_POST)){
            if(M('customer')->where(array('phone'=>$_POST['phone']))->find()){
               echo 0;
               exit;
            }else{
                $res = sendMsg($_POST['phone']);
                echo $res;
			}
		}else{
			 exit(json_encode(array('msg'=>'未接受到数据')));
		}
	}

    /**
     * 获取找回密码的验证码
     */
    public function back_password(){
        if(!empty($_POST)){
            $row = M('customer')->where(array('phone'=>$_POST['phone']))->find();
            if(!$row){
                echo 0;//未找到该号码，改账号无法修改密码
                exit;
            }else{
                $res = sendMsg($_POST['phone']);
                echo $res;
            }
        }else{
            exit(json_encode(array('msg'=>'未接受到数据')));
        }
    }

	/**
     * 找回密码
     */
    public function edit_password(){
        if(!empty($_POST)){
            $data['phone']=$_POST['phone'];
            $data['password'] = sha1($_POST['password']);
            if(M('customer')->where(array('phone'=>$data['phone']))->find()){
                M('customer')->where(array('phone'=>$data['phone']))->save($data);
                echo 1;//修改成功
            }else{
                echo 0;//未找到该用户的数据
            }
        }
    }

    /**
     *用户的登录
     */
    public function user_login(){
        if(!empty($_POST)){
            $name = $_POST['phone'];
            $pass = sha1($_POST['password']);
            $res = M('customer')->where(array('phone'=>$name,'password'=>$pass))->find();
            if($res){
                 $_SESSION['phone'] = $name;
                 $_SESSION['user_id'] = $res['user_id'];
                 $_SESSION['city'] = $res['city'];
                 $_SESSION['sex'] = $res['sex']==1?'男':'女';
                 $this->ajaxReturn($res);//成功
            }else{
                echo 0;//失败
            }
        }
    }

    /**
     * 图片上传，返回路径
     */
    public function up_imgs(){
        if(!empty($_FILES)){
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  = './Uploads/'; // 设置附件上传根目录
            $upload->savePath  = 'Product/'; // 设置附件上传（子）目录
            // 上传文件
            $info   =   $upload->upload();
            $imgs = 'Uploads/'.$info['file']['savepath'].$info['file']['savename'];
            exit(json_encode(array("code"=>0,"msg"=>'上传成功','src'=>'/'.$imgs)));//返回到JS中进行处理
        }else{
            echo '0';
        }

    }
    public function bgm(){
        $bgm = M('bgm')->select();
        if($bgm){
            $this->ajaxReturn($bgm);
        }
    }
}

?>