<?php
namespace Collage\Controller;
use Think\Controller;
/**
* 此为拼团的移动端接口
* zxl
*/
class CollageController extends Controller {
	/*
	* 返回并退出
	*/
	public function out($code = 0, $data = [], $message = 'ok', $sign = null)
	{
	    if (empty($data)) {
	        return json_encode([
	            'code' => $code,
	            'msg' => $message,
	            'result' => (object)$data,
	            'sign' => null
	        ]);
	    }
	    return json_encode([
	        'code' => $code,
	        'msg' => $message,
	        'result' => $data,
	        'sign' => null
	    ]);
	}
	/*  base64格式编码转换为图片并保存对应文件夹 */
	public function base64_image_content($base64_image_content,$path,$fname){
	    //匹配出图片的格式
	    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
	        $type = $result[2];
	        $new_file = $path."/";
	        if(!file_exists($new_file)){
	            //检查是否有该文件夹，如果没有就创建，并给予最高权限
	            mkdir($new_file, 0700);
	        }
	        $new_file = $new_file.$fname.".{$type}";
	        if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
	            return '/'.$new_file;
	        }else{
	            return false;
	        }
	    }else{
	        return false;
	    }
	}

	public function index()
	{
		//session_start();
		$user_info = session('wx_userInfo');
        	// if (!$user_info) {
         //    		A('Home/WeChat')->start(get_host_name().(U('Home/Sprout/index',array('sprout_id'=>I('get.sprout_id','0')))));
        	// }
		M("wx", "col_")->add($user_info);	
//echo "<pre>";var_dump($user_info);exit;
		$id = @I("get.id") ? I("get.id") : 10;
		$ac = $this->getOne($id, 2);

		//微信分享api
        	$wx_share = A('Home/WeChat')->shareAPi(sp_get_url());
		//echo "<pre>";var_dump($wx_share);exit;
		$str = "http://wx.vsaishi.com/index.php/Collage/Collage/index";
		$wx_share["imgurl"] = "__PUBLIC__/PhoneCollage/img/img1.jpg";
		$wx_share["desc"] = "分享11";
		$wx_share["title"] = "分享11";
		$wx_share["link"] = $str;
		$res = ["title" => "拼团111", "wx_share_default" => "__PUBLIC__/PhoneCollage/img/img1.jpg", "wx_share_logo" => "__PUBLIC__/PhoneCollage/img/img1.jpg"];
		$this->assign(["ac" => $ac, "wx_share" => $wx_share, "res" => $res]);
		$this->display("index");
	}
    //创建活动
	public function createActivity()
	{
		$data = I("post.");
		$id = M('collage_activity', "col_")->add(["title" => $data["title"], "start_time" => $data["start_time"], "end_time" => $data["end_time"], "explain" => $data["explain"], "price" => $data["price"], "advance" => $data["advance"], "building" => $data["building"], "contact" => $data["contact"], "store_name" => $data["store_name"], "store_address" => $data["store_address"], "introductions" => $data["introductions"], "mobile" => $data["mobile"], "address" => $data["address"], "cashing_time" => $data["cashing_time"], "create_time" => date("Y-m-d H:i:s")]);

        $collage = explode(",,", $data["collage"]);
        foreach ($collage as $value) {
            $one = explode(":", $value);
            M('collage', "col_")->add(["aid" => $id, "persons" => $one[0],"price" => $one[1]]);
        }

		$uploads_dir = APP_PATH . "Uploads/PhoneCollage/";
		if (!file_exists($uploads_dir)) {
			@mkdir($uploads_dir, 0777, true);
		}
		
        //活动描述图片
        preg_match_all("/{.*?}/i", $data["enc_group1"], $arr);
		foreach ($arr[0] as $key => $value) {
			$one = str_replace(array("{id:{$key}, name:", "}"), "", $value);
			$fname = md5(uniqid(microtime() . mt_rand()));
			$res = $this->base64_image_content($one, $uploads_dir, $fname);
			if ($res) {
				$res = str_replace("./sjd", "shangjd/sjd", $res);
	         	M('activity_enc', "col_")->add(
	                ["url" => $res, "name" => $res, "enc_type" => 2, "enc_group" => 1, "aid" => $id]
	            );
	         }
		}

        //领取信息的文本
        if ($data["enc_group2_text"]) {
        	$texts = explode(",", $data["enc_group2_text"]);
            foreach ($texts as $one) {
            	if ($one)
	                M('activity_enc', "col_")->add(
	                    ["url" => $one, "enc_type" => 1, "enc_group" => 2, "aid" => $id]
	                );
            }
        }
        //领取信息的视频
        if ($data["enc_group2_video"]) {
        	$videos = explode(",", $data["enc_group2_video"]);
            foreach ($videos as $one) {
            	if ($one)
	                M('activity_enc', "col_")->add(
	                    ["url" => $one, "enc_type" => 3, "enc_group" => 2, "aid" => $id]
	                );
            }
        }
        //领取信息的图片
        preg_match_all("/{.*?}/i", $data["enc_group2"], $arr);
		foreach ($arr[0] as $key => $value) {
			$one = str_replace(array("{id:{$key}, name:", "}"), "", $value);
			$fname = md5(uniqid(microtime() . mt_rand()));
			$res = $this->base64_image_content($one, $uploads_dir, $fname);
			if ($res) {
				$res = str_replace("./sjd", "shangjd/sjd", $res);
	         	M('activity_enc', "col_")->add(
	                ["url" => $res, "name" => $res, "enc_type" => 2, "enc_group" => 2, "aid" => $id]
	            );
	         }
		}

        //机构介绍的图片
        preg_match_all("/{.*?}/i", $data["enc_group3"], $arr);
		foreach ($arr[0] as $key => $value) {
			$one = str_replace(array("{id:{$key}, name:", "}"), "", $value);
			$fname = md5(uniqid(microtime() . mt_rand()));
			$res = $this->base64_image_content($one, $uploads_dir, $fname);
			if ($res) {
				$res = str_replace("./sjd", "shangjd/sjd", $res);
	         	M('activity_enc', "col_")->add(
	                ["url" => $res, "name" => $res, "enc_type" => 2, "enc_group" => 3, "aid" => $id]
	            );
	         }
		}
        
        echo true;
	}

	//获取活动信息
    public function getOne($id = null, $t = 1)
    {
    	$id = @$id ? $id : $_REQUEST["id"];
    	$activity = M('collage_activity', "col_")->find($id);
    	$collage = M('collage', "col_")->where("aid = {$id}")->order("persons")->select();
		$activity["collage"] = $collage;

		//查出已经开团的信息，开团时间再活动的开始、结束日期之间
		$regiments = M('regiment', "col_")->where("(create_time >= '{$activity['start_time']}' and create_time <= '{$activity['end_time']}') and (user_type = 1) and (aid = {$id})")->select();
		foreach ($regiments as &$value) {
			//查出团长下面，有几人已经参团，算上团长自己
			$persons = M('regiment', "col_")->where("user_type = 2 and parentid = {$value['id']}")->count();
			$value["persons"] = $persons + 1;
			//1人团，388/人；3人团，288/人；如果人数是2人，这个时候就需要判断，2人，仍旧是1人团的价钱。团的价目信息是正序排列的，也就是1、3、4人，我们需要判断离2最近且比我小的数，循环整个团信息，最后一个比我小的，就是我们要的信息
			$coll;//存储当前团长开的团实行的价目信息
			foreach ($collage as $key => $one) {
				if ($value["persons"] > $one["persons"]) {
					$coll = $one;
				}
			}
			$value["coll"] = $coll;
		}
		$activity["regiments"] = $regiments;

		$activity["times"] = strtotime($activity["end_time"]) - time();
		$cs = explode("\n", $activity["collage_content"]);
		$contents = array();
		foreach ($cs as $key => &$value) {
			if ($value) {
				$value = preg_replace("/^\d、/i", "", $value);
				$contents[] = $value;
			} else {
				unset($cs[$key]);
			}
		}
		$activity["collage_content"] = $contents;

		$ins = explode("\n", $activity["introductions"]);
		$indexs = array();
		foreach ($ins as $key => &$value) {
			if ($value) {
				$value = preg_replace("/^\d、/i", "", $value);
				$indexs[] = $value;
			} else {
				unset($ins[$key]);
			}
		}
		$activity["introductions"] = $indexs;

		$imgs = M("activity_enc", "col_")->where("aid = {$id}")->select();
		$activity["imgs"] = $imgs;

		$arr = explode("\n", $activity["collage_note"]);
		$notes = array();
    	foreach ($arr as $key => &$one) {
    		if ($one) {
    			$one = preg_replace("/^\d、/i", "", $one);
    			$notes[] = $one;
    		} else {
    			unset($arr[$key]);
    		}
    	}
    	$activity["collage_note"] = $notes;
		//echo "<pre>";var_dump($imgs);exit;
		if ($t == 1) echo $this->out(0, ['status' => $activity]);
		else return $activity;
    }

    //拼团说明
    public function xq()
    {
    	$id = @$id ? $id : $_REQUEST["id"];
    	$activity = M('collage_activity', "col_")->field("collage_note")->find($id);
    	$arr = explode("\n", $activity["collage_note"]);
    	foreach ($arr as &$one) {
    		$one = preg_replace("/^\d、/i", "", $one);
    	}
    	echo $this->out(0, ['status' => $arr]);
    }

    //开团
    public function regiment()
    {
    	$aid = I("post.aid");
    	$name = I("post.username");
    	$phone = I("post.phone");
    	$id = M('regiment', "col_")->add(
            ["user_no" => @$user_no, "user_name" => $name, "phone" => $phone, "user_type" => 1, "create_time" => date("Y-m-d H:i:s"), "aid" => $aid]
        );
        echo  $id ? true : false;
    }

    //我要参团
    public function join()
    {

    	$userid = $_REQUEST["userid"];
    	$aid = $_REQUEST["aid"];
    	$ac = $this->getOne($aid, 2);
    	//判断，如果ac中，有当前团长的信息，就删除
    	foreach ($ac["regiments"] as $key => &$value) {
    		if ($value["id"] == $userid) {
    			unset($ac["regiments"][$key]);
    		}
    	}
    	//再查出当前团长，及下属的参团成员
    	$user = M('regiment', "col_")->field("id, user_name, create_time")->where("aid = {$aid}")->find($userid);
    	$persons = M('regiment', "col_")->field("id, user_name, create_time")->where("user_type = 2 and parentid = {$userid} and aid = {$aid}")->select();
    	$user["persons"] = $persons;
    	$user["count"] = count($persons) + 1;
    	//echo "<pre>";var_dump($user);exit;
    	$this->assign(array("ac" => $ac, "user" => $user));
    	$this->display("index2");
    }

    //参加此团
    public function joinIn()
    {
    	$aid = I("post.aid");
    	$gid = I("post.gid");//团长id
    	$name = I("post.uname");//用户名字
    	$phone = I("post.phone");//手机号
    	//var_dump($_REQUEST);exit;
    	M('regiment', "col_")->add(
            ["user_name" => $name, "phone" => $phone, "user_type" => 2, "create_time" => date("Y-m-d H:i:s"), "parentid" => $gid, "aid" => $aid]
        );
        echo $this->out(0, ['status' => 1]);
    }

    //咨询，根据传过来的活动id，查出活动的咨询电话
    public function consultation()
    {
    	$aid = $_REQUEST["aid"];//活动id
    	$activity = M('collage_activity', "col_")->field("id, contact")->find($aid);
    	echo $this->out(0, ['status' => $activity]);
    }

    //投诉
    public function complaint()
    {
    	M('complaint', "col_")->add(
            ["aid" => $_REQUEST["aid"], "cid" => $_REQUEST["cid"], "content" => $_REQUEST["content"], "phone" => $_REQUEST["phone"], "create_time" => date("Y-m-d H:i:s")]
        );
        echo $this->out(0, ['status' => 1]);
    }

    //马上制作
    public function make()
    {
	//var_dump(T("Collage/index1"));exit;
    	$this->display("create");
    }

}
