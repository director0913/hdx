<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace Portal\Controller;
use Common\Controller\HomebaseController; 
 
/**
 * 首页
 */
class JoinController extends HomebaseController {
	/**
	*创建D模型
	**/
	protected $form_model;
	protected $formitem_model;
	protected $formdata_model;
	protected $formrow_model;

	function _initialize() {
		parent::_initialize();
		$this->form_model = D("Common/Form");
		$this->formitem_model = D("Common/FormItem");
		$this->formdata_model = D("Common/FormData");
		$this->formrow_model = D("Common/FormRows");
	}
	public function index() {
		//1表单名称，备注
		$id=$_GET['id'];
		if(empty($id))
		{
			$this->display(":index");
		}
		$form_info=$this->form_model->field('id,name,remark')->where('id='.$id)->find();
		$form_item_info=$this->formitem_model->where('form_id='.$id)->order('id asc')->select();
		$this->assign('form_info',$form_info);
		$this->assign('form_item_info',$form_item_info);
		//展示已报名信息
    	$this->display(":join");
    }
	/**
	*保存参加活动
	**/
	public function add_post() {
		if(IS_POST){
			//拼接
			$total=$_POST['key'];
			$tmp=array();
			$data=array();
			$sum=0;
			$row = array(
				'from_user' => I('post.from_user'),
				'form_id' => I('post.form_id'),
				'createtime'=>time()
			);
			$this->formrow_model->add($row);
			for($i=0;$i<=$total;$i++)
			{
				$data[]=array(
				  'form_id'=>$_POST['form_id'],
				  'from_user'=>$_POST['from_user'],
				  'nickname'=>$_POST['nickname'],
				  'avatar'=>$_POST['avatar'],
				  'item_id'=>$_POST['h'.$i],
				  'value'=>$_POST['t'.$i]
				);
			}
			if($this->formdata_model->addAll($data)==true){
				$this->success(L('ADD_SUCCESS'), U("activity/index"));
			}else{
				$this->error($form_data->getError());
			}
		}
    }
}


