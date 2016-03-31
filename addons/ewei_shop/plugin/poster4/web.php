<?php

//微赞科技 by QQ:800083075 http://www.012wz.com/
if (!defined('IN_IA')) {
	die('Access Denied');
}
require_once 'model.php';
class PosterWeb extends Plugin
{
	public function __construct()
	{
		parent::__construct('poster');
	}
	public function index()
	{
		$this->_exec_plugin(__FUNCTION__);
	}
	public function manage()
	{
		$this->_exec_plugin(__FUNCTION__);
	}
	public function log()
	{
		$this->_exec_plugin(__FUNCTION__);
	}
	public function scan()
	{
		$this->_exec_plugin(__FUNCTION__);
	}
	public function set()
	{
		$this->_exec_plugin(__FUNCTION__);
	}
}