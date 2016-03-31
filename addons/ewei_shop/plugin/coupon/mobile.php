<?php
//捌零网络科技有限公司QQ2316571101
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class CouponMobile extends Plugin
{
	public function __construct()
	{
		parent::__construct('coupon');
	}

	public function index()
	{
		$this->_exec_plugin(__FUNCTION__, false);
	}

	public function detail()
	{
		$this->_exec_plugin(__FUNCTION__, false);
	}

	public function my()
	{
		$this->_exec_plugin(__FUNCTION__, false);
	}

	public function mydetail()
	{
		$this->_exec_plugin(__FUNCTION__, false);
	}

	public function util()
	{
		$this->_exec_plugin(__FUNCTION__, false);
	}
}