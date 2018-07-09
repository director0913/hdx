<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Tuolaji <479923197@qq.com>
// +----------------------------------------------------------------------
/**
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class BannerController extends AdminbaseController {

    public function index()
	{
		$list = M('banner')->order('id DESC')->select();
		$this->assign('list',$list);
		$this->display();
	}

	public function add()
	{
		$this->display();
	}

	public function add_post()
	{
		$name = I('post.name');
		$url = I('post.url');
		$order = I('post.order');

		M('banner')->data([
			'name' => $name,
			'url' => $url,
			'order' => $order
		])->add();

		$this->success("添加成功！", U("banner/index"));
	}

	public function edit()
	{
		$id = I('get.id');
		$data = M('banner')->where('id = '.$id)->find();

		$this->assign('data',$data);
		$this->display();
	}

	public function edit_post()
	{
		$id = I('post.id');
		$name = I('post.name');
		$url = I('post.url');
		$order = I('post.order');

		M('banner')->where('id = '.$id)->save([
			'name' => $name,
			'url' => $url,
			'order' => $order
		]);

		$this->success("编辑成功！", U("banner/index"));
	}

	public function del()
	{
		$id = I('get.id');
		M('banner')->where('id = '.$id)->delete();
		$this->success("删除成功！", U("banner/index"));
	}

}