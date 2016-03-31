<?php
//捌零网络科技有限公司QQ2316571101
if (!defined('IN_IA')) {
    exit('Access Denied');
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