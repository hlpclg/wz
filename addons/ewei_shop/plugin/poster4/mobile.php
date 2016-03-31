<?php

//微赞科技 by QQ:800083075 http://www.012wz.com/
if (!defined('IN_IA')) {
	die('Access Denied');
}
class PosterMobile extends Plugin
{
	public function __construct()
	{
		parent::__construct('poster');
	}
	public function build()
	{
		$this->_exec_plugin(__FUNCTION__, false);
	}
}