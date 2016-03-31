<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W, $_GPC;
load()->func('tpl');
$set = getSet();

if (checksubmit('submit')){
	$set['user'] = is_array($_GPC['user']) ? $_GPC['user'] : array();
	updateSet($set);
	if (!empty($set['user']['upload'])){
		$check = qiniu_check($set['user']);
		if (empty($check)){
			message('配置有误，请仔细检查参数设置!', '', 'error');
		}
	}
	message('设置保存成功!', referer(), 'success');
}
if (checksubmit('submit_admin'))
{
	$set['admin'] = is_array($_GPC['admin']) ? $_GPC['admin'] : array();
	$return = updateSet($set);
	if (!empty($set['admin']['upload'])){
		$check = qiniu_check($set['admin']);
		if (empty($check)){
			message('配置有误，请仔细检查参数设置!', '', 'error');
		}
	}
	message('设置保存成功!', referer(), 'success');
}
include $this->template('qiniu');

function qiniu_check($user){
	return save('http://www.baidu.com/img/bdlogo.png', $user);
}

