<?php
namespace Collage\Controller;
use Think\Controller;
/**
* zxl 此为分销移动端接口
*/
class DistributionController extends Controller {
	//采用单独连接的方式，主要是因为前缀不一样，又不想因为如此单独改动配置文件
	public $db = "mysql://root:w!s@s#db@127.0.0.1:3306/sjd#utf8";
	//$User = M('User','fx_', $db);
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
	public function test()
	{
		echo "<pre>";var_dump(M('activity', "fx_"));exit;
	}
    //创建活动
	public function createActivity()
	{
		//echo "<pre>";var_dump(I("post."));exit;
		//获取到post过来的所有数据，存进去，不存在的字段会自动被过滤
		$data = I("post.");
		$data["create_time"] = date("Y-m-d H:i:s");
		$id = M('activity', "fx_")->add($data);
		
		//店家优惠
		foreach ($data["discount"] as $value) {
			M('discount', "fx_")->add([
				"create_time" => date("Y-m-d H:i:s"), "entry" => $value, "aid" => $id
			]);
		}
		
		//处理门店信息，犹豫联系电话可能有多个，so，新增以电话为主，只有电话，没有其他的信息化，电话仍旧作为新的一条数据
		foreach ($data["contact"] as $key => $value) {
			if ($value)
				M('store', "fx_")->add([
					"store_name" => @$data["store_name"][$key],
					"store_address" => @$data["store_address"][$key],
					"building" => @$data["building"][$key],
					"contact" => @$value,
					"aid" => $id
				]);
		}

		//信息收集设置，至少2条，至多5条数据，其中2条是固定的，
		//3条是自定义的，这3个问题，只能是文本、单选，只管把问题存进来就好
		//在此，固定好3个未知问题的name，便于取值
		M('infos', "fx_")->add([
			"user_name" => $data["user_name"],//固定问题
			"phone" => $data["phone"],//固定问题
			"unknown_one" => @$data["unknown_one"],//未知1
			"unknown_two" => @$data["unknown_two"],//未知2
			"unknown_three" => @$data["unknown_three"],//未知3
			"aid" => $id
		]);
		
		//然后再单独解析出上传的图片等信息
		$uploads_dir = APP_PATH . "Uploads/Fx/";
		if (!file_exists($uploads_dir)) {
			@mkdir($uploads_dir, 0777, true);
		}

		$logo = $_FILES["logo"];
		if ($logo["error"] == UPLOAD_ERR_OK) {
	         $tmp_name = $logo["tmp_name"];
	         $name = $logo["name"];
	         move_uploaded_file($tmp_name, $uploads_dir . $name);
	         M('activity', "fx_")->where("id = {$id}")->save(array("logo" => $uploads_dir . $name));
	    }

		//活动顶部展示图片，name为tops
		$res = $this->buildInfo($_FILES["tops"]);
        if ($res) {
            foreach ($res as $one) {
            	if ($one["error"] == UPLOAD_ERR_OK) {
			         $tmp_name = $one["tmp_name"];
			         $name = $one["name"];
			         $bool = move_uploaded_file($tmp_name, $uploads_dir . $name);
			         if ($bool) {
			         	M('enc', "fx_")->add(
		                    ["url" => $uploads_dir . $name, "name" => $name, "enc_type" => 2, "enc_group" => 1, "aid" => $id]
		                );
			         }
			    }
            }
        }
		
        //商品描述图片,name为produts
        $res = $this->buildInfo($_FILES["produts"]);
        if ($res) {
            foreach ($res as $one) {
            	if ($one["error"] == UPLOAD_ERR_OK) {
			         $tmp_name = $one["tmp_name"];
			         $name = $one["name"];
			         $bool = move_uploaded_file($tmp_name, $uploads_dir . $name);
			         if ($bool) {
			         	M('enc', "fx_")->add(
		                    ["url" => $uploads_dir . $name, "name" => $name, "enc_type" => 2, "enc_group" => 2, "aid" => $id]
		                );
			         }
			    }
            }
        }

        //活动详情，可能有文本、图片、详情，需要逐一划分开来
        //活动详情的文本，name为activity_notes
        if ($data["activity_notes"]) {
            foreach ($data["activity_notes"] as $one) {
                M('enc', "fx_")->add(
                    ["content" => $one, "enc_type" => 1, "enc_group" => 3, "aid" => $id]
                );
            }
        }
        //活动详情的图片，name为activity_imgs
        $res = $this->buildInfo($_FILES["activity_imgs"]);
        if ($res) {
            foreach ($res as $one) {
            	if ($one["error"] == UPLOAD_ERR_OK) {
			         $tmp_name = $one["tmp_name"];
			         $name = $one["name"];
			         $bool = move_uploaded_file($tmp_name, $uploads_dir . $name);
			         if ($bool) {
			         	M('enc', "fx_")->add(
		                    ["url" => $uploads_dir . $name, "name" => $name, "enc_type" => 2, "enc_group" => 3, "aid" => $id]
		                );
			         }
			    }
            }
        }
        //活动详情的视频，name为activity_videos
        $res = $this->buildInfo($_FILES["activity_videos"]);
        if ($res) {
            foreach ($res as $one) {
            	if ($one["error"] == UPLOAD_ERR_OK) {
			         $tmp_name = $one["tmp_name"];
			         $name = $one["name"];
			         $bool = move_uploaded_file($tmp_name, $uploads_dir . $name);
			         if ($bool) {
			         	M('enc', "fx_")->add(
		                    ["url" => $uploads_dir . $name, "name" => $name, "enc_type" => 3, "enc_group" => 3, "aid" => $id]
		                );
			         }
			    }
            }
        }
        
        //机构介绍，可能有文本、图片、详情，需要逐一划分开来
        //机构介绍的文本，name为jg_notes
        if ($data["jg_notes"]) {
            foreach ($data["jg_notes"] as $one) {
                M('enc', "fx_")->add(
                    ["content" => $one, "enc_type" => 1, "enc_group" => 4, "aid" => $id]
                );
            }
        }
        //机构介绍的图片，name为jg_imgs
        $res = $this->buildInfo($_FILES["jg_imgs"]);
        if ($res) {
            foreach ($res as $one) {
            	if ($one["error"] == UPLOAD_ERR_OK) {
			         $tmp_name = $one["tmp_name"];
			         $name = $one["name"];
			         $bool = move_uploaded_file($tmp_name, $uploads_dir . $name);
			         if ($bool) {
			         	M('enc', "fx_")->add(
		                    ["url" => $uploads_dir . $name, "name" => $name, "enc_type" => 2, "enc_group" => 4, "aid" => $id]
		                );
			         }
			    }
            }
        }
        //机构介绍的视频，name为jg_videos
        $res = $this->buildInfo($_FILES["jg_videos"]);
        if ($res) {
            foreach ($res as $one) {
            	if ($one["error"] == UPLOAD_ERR_OK) {
			         $tmp_name = $one["tmp_name"];
			         $name = $one["name"];
			         $bool = move_uploaded_file($tmp_name, $uploads_dir . $name);
			         if ($bool) {
			         	M('enc', "fx_")->add(
		                    ["url" => $uploads_dir . $name, "name" => $name, "enc_type" => 3, "enc_group" => 4, "aid" => $id]
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

	//查询活动信息getActivity
	public function index()
	{
		$user_info = session('wx_userInfo');
                if (!$user_info) {
                        A('Home/WeChat')->start(get_host_name().(U('Home/Sprout/index',array('sprout_id'=>I('get.sprout_id','0')))));
                }       
                M("wx", "col_")->add($user_info);

		$id = I("get.id");
//echo "<pre>";var_dump(M('activity', C("DB_PREFIX1"))->find(5));exit;
		//$id = 5;
		$ac = M('activity', "fx_")->find($id);
		//有效期倒计时
		$ac["times"] = strtotime($ac["end_time"]) - time();

		//查出当前砍价的发起人，以及有几人以及帮忙再砍
		$faqi = M('cuts', "fx_")->where("aid = {$id} and user_type = 1")->find();
		$helps = M('cuts', "fx_")->where("aid = {$id} and user_type = 2 and parentid = {$faqi['id']}")->select();
		$faqi["help_person"] = $helps;

		//查询出店家优惠信息
		$discounts = M("discount", "fx_")->where("aid = {$id}")->select();
		$ac["discounts"] = $discounts;

		//处理一下详情，将每一项拆分出来，做成一个数组
		$arr = explode("\n", $ac["explain"]);
		$ac["explain"] = $arr;

		//查出该活动下，所有的图片、文本、视频等信息，然后逐一划分开来
		$encs = M("enc", "fx_")->where("aid = {$id}")->order("id")->select();
		$tops = array();//活动顶部展示图片
		$produts = array();//商品描述
		$acts = array();//活动详情描述
		$jgs = array();//机构描述
		$fcs = array();//我们的风采
		foreach ($encs as $one) {
			switch ($one["enc_group"]) {
				case 1:
					$tops[] = $one;
					break;
				case 2:
					$produts[] = $one;
					break;
				case 3:
					$acts[] = $one;
					break;
				case 4:
					$jgs[] = $one;
					break;
				case 5:
					$fcs[] = $one;
					break;
				default:
					break;
			}
		}
		$ac["tops"] = $tops;
		$ac["produts"] = $produts;
		$ac["acts"] = $acts;
		$ac["jgs"] = $jgs;
		$ac["fcs"] = $fcs;

		//查出砍价榜、邀请榜的发起人排行
		//砍价榜就是砍后的现价，倒序
		//邀请榜就是发起人邀请的人数，倒序
		$kj = M('cuts', "fx_")->where("aid = {$id} and user_type = 1")->order("cur_price desc")->select();
		foreach ($kj as &$value) {
			$count = M('cuts', "fx_")->where("aid = {$id} and user_type = 2 and parentid = {$value['id']}")->count();
			$value["count"] = $count;
		}
		//邀请榜，直接用sql，暂时没起来怎么干，那就先全查出来，再排序吧
		$fqs = M('cuts', "fx_")->where("aid = {$id} and user_type = 1")->select();
		foreach ($fqs as &$value) {
			$count = M('cuts', "fx_")->where("aid = {$id} and user_type = 2 and parentid = {$value['id']}")->count();
			$value["count"] = $count;
		}
		$len = count($fqs);
		for($i = 1;$i < $len;$i++) {
			for($k = 0;$k < $len - $i;$k++) {
				if($fqs[$k]["count"] < $fqs[$k+1]["count"]) {
					$tmp = $fqs[$k+1];
					$fqs[$k+1] = $fqs[$k];
					$fqs[$k] = $tmp;
				}
			}
		}

		//echo "<pre>";var_dump($kj);exit;
		$this->assign(array("ac" => $ac, "user" => $faqi, "kj" => $kj, "fqs" => $fqs));
		$this->display("index");
	}

	//我要参加
	public function joinIn()
	{
		$aid = I("post.aid");//活动id
		$userid = I("post.userid");//发起人id
		$username = I("post.username");//活动id
		$phone = I("post.phone");//活动id
		$insertId = M('cuts', "fx_")->add(array("user_name" => $username, "phone" => $phone, "user_type" => 2, "parentid" => $userid, "aid" => $aid));
		echo $insertId ? true : false;
	}

	//投诉
	//分享
	//联系商家
	//邀请规则

}
