<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;
load()->func('tpl');

$operation = $_GPC['op'];

$table = 'meepo_bbs_navs';
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
	$sql = "SELECT * FROM ".tablename($table)." WHERE uniacid = :uniacid ORDER BY displayorder DESC";
	$params = array(':uniacid'=>$_W['uniacid']);
	$list = pdo_fetchall($sql,$params);
} elseif ($operation == 'post') {
	$id = intval($_GPC['id']);
	if (checksubmit('submit')) {
		$data = array(
				'uniacid' => $_W['uniacid'],
				'name' => $_GPC['name'],
				'link' => $_GPC['link'],
				'enabled' => intval($_GPC['enabled']),
				'displayorder' => intval($_GPC['displayorder']),
				'icon'=>$_GPC['icon']
		);
		if (!empty($id)) {
			pdo_update($table, $data, array('id' => $id));
		} else {
			pdo_insert($table, $data);
			$id = pdo_insertid();
		}
		message('更新导航成功！', $this->createWebUrl('nav', array('op' => 'display')), 'success');
	}
	$adv = pdo_fetch("select * from " . tablename($table) . " where id=:id and uniacid=:weid limit 1", array(":id" => $id, ":weid" => $_W['uniacid']));
} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);
	pdo_delete($table, array('id' => $id));
	message('导航删除成功！', $this->createWebUrl('nav', array('op' => 'display')), 'success');
} else {
	message('请求方式不存在');
}
include $this->template('navs');

