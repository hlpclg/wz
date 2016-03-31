<?php
//捌零网络科技有限公司QQ2316571101
global $_W, $_GPC;
$set = $this->getSet();
if (checksubmit('submit')) {
	$set['user'] = is_array($_GPC['user']) ? $_GPC['user'] : array();
	$this->updateSet($set);
	message('设置保存成功!', referer(), 'success');
}
include $this->template('set');