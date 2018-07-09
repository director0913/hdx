<?php
namespace Common\Model;
use Common\Model\CommonModel;
class FormModel extends CommonModel{
	//自动验证
	protected $_validate = array(
			//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)			
			array('name', 'require', '表单名称不能为空！', 1, 'regex', 3),
			//array('contact', 'require', '联系人不能为空！', 1, 'regex', 3),
			//array('phone', 'require', '电话不能为空！', 1, 'regex', 3),
			//array('remark', 'require', '备注不能为空！', 1, 'regex', 3),
	);
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
	}
}