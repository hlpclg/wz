<?php

//微赞科技 by QQ:800083075 http://www.012wz.com/
if (!defined('IN_IA')) {
	die('Access Denied');
}
class QiniuWeb extends Plugin
{
	public function __construct()
	{
		parent::__construct('qiniu');
	}
	public function check($config)
	{
		return m('qiniu')->save('http://www.baidu.com/img/bdlogo.png', $config);
	}
	public function index()
	{
		global $_W, $_GPC;
		$set = $this->getSet();
		if (checksubmit('submit')) {
			$set['user'] = is_array($_GPC['user']) ? $_GPC['user'] : array();
			$this->updateSet($set);
			if (!empty($set['user']['upload'])) {
				$ret = $this->check($set['user']);
				if (empty($ret)) {
					message('配置有误，请仔细检查参数设置!', '', 'error');
				}
			}
			message('设置保存成功!', referer(), 'success');
		}
		if (checksubmit('submit_admin')) {
			$set['admin'] = is_array($_GPC['admin']) ? $_GPC['admin'] : array();
			$this->updateSet($set);
			if (!empty($set['admin']['upload'])) {
				$ret = $this->check($set['admin']);
				if (empty($ret)) {
					message('配置有误，请仔细检查参数设置!', '', 'error');
				}
			}
			message('设置保存成功!', referer(), 'success');
		}
		include $this->template('set');
	}
}