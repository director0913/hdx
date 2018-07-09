<?php
namespace Collage\Controller;
use Think\Controller;
/**
* 此为拼团的pc端，有前端展示页
* zxl
*/
class PcCollageController extends Controller {
	public function index()
	{
        $id = I("get.id");
		$ac = $this->getOne($id);
		$this->assign('ac', $ac);
		$this->display("index");
	}
    //创建活动
	public function createActivity()
	{
		$data = $_POST;
		$id = M('collage_activity', "col_")->add(["title" => $data["title"], "start_time" => $data["start_time"], "end_time" => $data["end_time"], "explain" => $data["explain"], "price" => $data["price"], "advance" => $data["advance"], "building" => $data["building"], "contact" => $data["contact"], "store_name" => $data["store_name"], "store_address" => $data["store_address"], "introductions" => $data["introductions"], "mobile" => $data["mobile"], "address" => $data["address"], "cashing_time" => $data["cashing_time"], "create_time" => date("Y-m-d H:i:s")]);

        $collage = explode(",,", $data["collage"]);
        foreach ($collage as $value) {
            $one = explode(":", $value);
            M('collage', "col_")->add(["aid" => $id, "persons" => $one[0],"price" => $one[1]]);
        }

		$uploads_dir = APP_PATH . "Uploads/Collage/";
		if (!file_exists($uploads_dir)) {
			@mkdir($uploads_dir, 0777, true);
		}
		
        //活动图片
        $res = $this->buildInfo($_FILES["files"]);
        if ($res) {
            foreach ($res as $one) {
            	if ($one["error"] == UPLOAD_ERR_OK) {
			         $tmp_name = $one["tmp_name"];
			         $name = $one["name"];
			         $bool = move_uploaded_file($tmp_name, $uploads_dir . $name);
			         if ($bool) {
			         	M('activity_enc', "col_")->add(
		                    ["url" => $uploads_dir . $name, "name" => $name, "enc_type" => 2, "enc_group" => 1, "aid" => $id]
		                );
			         }
			    }
            }
        }
        //return;
        //领取信息的文本
        if ($data["notes"]) {
            foreach ($data["notes"] as $one) {
                M('activity_enc', "col_")->add(
                    ["url" => $one, "enc_type" => 1, "enc_group" => 2, "aid" => $id]
                );
            }
        }
        
        //领取信息的图片
        $res = $this->buildInfo($_FILES["pics"]);
        if ($res) {
        	foreach ($res as $one) {
            	if ($one["error"] == UPLOAD_ERR_OK) {
			         $tmp_name = $one["tmp_name"];
			         $name = $one["name"];
			         $bool = move_uploaded_file($tmp_name, $uploads_dir . $name);
			         if ($bool) {
			         	M('activity_enc', "col_")->add(
		                    ["url" => $uploads_dir . $name, "name" => $name, "enc_type" => 2, "enc_group" => 2, "aid" => $id]
		                );
			         }
			    }
            }
        }
        //领取信息的视频
        $res = $this->buildInfo($_FILES["videos"]);
        if ($res) {
            foreach ($res as $one) {
            	if ($one["error"] == UPLOAD_ERR_OK) {
			         $tmp_name = $one["tmp_name"];
			         $name = $one["name"];
			         $bool = move_uploaded_file($tmp_name, $uploads_dir . $name);
			         if ($bool) {
			         	M('activity_enc', "col_")->add(
		                    ["url" => $uploads_dir . $name, "name" => $name, "enc_type" => 3, "enc_group" => 2, "aid" => $id]
		                );
			         }
			    }
            }
        }

        //机构介绍的图片
        $res = $this->buildInfo($_FILES["imgs"]);
        if ($res) {
            foreach ($res as $one) {
            	if ($one["error"] == UPLOAD_ERR_OK) {
			         $tmp_name = $one["tmp_name"];
			         $name = $one["name"];
			         $bool = move_uploaded_file($tmp_name, $uploads_dir . $name);
			         if ($bool) {
			         	M('activity_enc', "col_")->add(
		                    ["url" => $uploads_dir . $name, "name" => $name, "enc_type" => 2, "enc_group" => 3, "aid" => $id]
		                );
			         }
			    }
            }
        }
        
        echo $this->out(0, ['status' => 1]);
	}
	//处理多文件上传，纯自定义的手写方法，将每个文件的信息拆出来组合在一起
	private function buildInfo($names){
	    $i = 0;
	    $info = array();
	    foreach ($names as $k => $v){
	    	$i = 0;
	        if(is_string($v['name'])){
	            $info[$i] = $v;
	            $i++;
	        }else{
	        	foreach ($v as $one) {
	        		$info[$i][$k] = $one;
	        		$i++;
	        	}
	        }
	    }
	    return $info;
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
		foreach ($cs as $key => &$value) {
			if ($value) $value = preg_replace("/^\d、/i", "", $value);
			else unset($cs[$key]);
		}
		$activity["collage_content"] = $cs;

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
		//echo "<pre>";var_dump($imgs);exit;
		return $activity;
    }

    //拼团说明
    public function xq()
    {
    	$id = @$id ? $id : $_REQUEST["id"];
    	$activity = M('collage_activity', "col_")->field("collage_note")->find($id);
    	$arr = explode("\n", $activity["collage_note"]);
    	foreach ($arr as $key => &$one) {
    		if ($one) $one = preg_replace("/^\d、/i", "", $one);
    		else unset($arr[$key]);
    	}
    	//echo "<pre>";var_dump($arr);exit;
    	$this->assign('notes', $arr);
    	$this->display("xiangqing");
    }

    //开团
    public function regiment()
    {
    	$aid = $_REQUEST["aid"];
    	$user_no = @$_REQUEST["user_no"];
    	$name = $_REQUEST["username"];
    	$phone = $_REQUEST["phone"];
    	$id = M('regiment', "col_")->add(
            ["user_no" => $user_no, "user_name" => $name, "phone" => $phone, "user_type" => 1, "create_time" => date("Y-m-d H:i:s"), "aid" => $aid]
        );
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
    	$this->assign(array("ac" => $ac, "user" => $user));
    	$this->display("cantuan");
    }

    //查看更多
    public function more()
    {
    	$userid = I("get.userid");
    	$aid = I("get.aid");
    	$user = M('regiment', "col_")->field("id, user_name, create_time")->where("aid = {$aid}")->find($userid);
    	$persons = M('regiment', "col_")->field("id, user_name, create_time")->where("user_type = 2 and parentid = {$userid} and aid = {$aid}")->select();
    	$user["persons"] = $persons;
    	$user["count"] = count($persons) + 1;

    	$this->assign(array("user" => $user, "aid" => $aid));
    	$this->display("more");
    }

    //参加此团
    public function joinIn()
    {
    	$aid = $_REQUEST["aid"];
    	$gid = $_REQUEST["gid"];//团长id
    	$name = $_REQUEST["uname"];//用户名字
    	$phone = $_REQUEST["phone"];//手机号
    	//var_dump($_REQUEST);exit;
    	M('regiment', "col_")->add(
            ["user_name" => $name, "phone" => $phone, "user_type" => 2, "create_time" => date("Y-m-d H:i:s"), "parentid" => $gid, "aid" => $aid]
        );
    }

    //咨询，根据传过来的活动id，查出活动的咨询电话
    public function consultation()
    {
    	$aid = $_REQUEST["aid"];//活动id
    	$activity = M('collage_activity', "col_")->field("id, contact")->find($aid);
    	//echo $this->out(0, ['status' => $activity]);
    }

    //投诉第一步
    public function complaintOne()
    {
    	$aid = I("get.aid");
    	$this->assign('aid', $aid);
    	$this->display("tsindex");
    }
    //流量劫持
    public function lljc()
    {
    	$this->display("lljc");
    }
    //投诉第二步
    public function complaintTwo()
    {
    	$aid = I("get.aid");
    	$status = I("get.status");
    	$this->assign(array('aid' => $aid, "status" => $status));
    	$this->display("tsms");
    }
    //投诉
    public function complaint()
    {
    	$post = I("post.");
    	M('complaint', "col_")->add(
            ["aid" => $post["aid"], "cid" => $post["cid"], "content" => $post["content"], "phone" => $post["phone"], "create_time" => date("Y-m-d H:i:s")]
        );
        echo true;
    }
    //投诉完毕
    public function complaintOff()
    {
    	$this->display("tswc");
    }

}