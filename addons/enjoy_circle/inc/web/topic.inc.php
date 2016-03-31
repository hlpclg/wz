<?php
global $_W,$_GPC;
load()->func('tpl');
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$pindex = max(1, intval($_GPC['page']));
$psize = 9;
if ($op == 'display') {
	$list = pdo_fetchall("SELECT * FROM " . tablename('enjoy_circle_topic') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY createtime LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	$countadd=pdo_fetchcolumn("select count(*) from " . tablename('enjoy_circle_topic') . " WHERE uniacid = '{$_W['uniacid']}'");
	$pager = pagination($countadd, $pindex, $psize);	
} elseif ($op == 'post') {
	$id = intval($_GPC['tid']);
	if (checksubmit('submit')) {
		$data = array(
				'uniacid' => $_W['uniacid'],
				'title'=>$_GPC['title'],
				'nickname'=>$_GPC['nickname'],
				'avatar'=>$_GPC['avatar'],
				'hot'=>$_GPC['hot'],
				'pic'=>$_GPC['pic'],
				'joinnum'=>$_GPC['joinnum'],
				'zan'=>$_GPC['zan'],
				'createtime'=>TIMESTAMP
				//'etime'=>strtotime($_GPC['etime'])+59
		);
		//$count=intval($_GPC['count']);
		if (!empty($id)) {
			pdo_update('enjoy_circle_topic', $data, array('tid' => $id));

			$message="更新话题成功！";
		} else {
			pdo_insert('enjoy_circle_topic', $data);
			$id = pdo_insertid();

			$message="新增话题成功！";
		
		
	}
	message($message, $this->createWebUrl('topic', array('op' => 'display')), 'success');
	}
	//修改
	$topic = pdo_fetch("SELECT * FROM " . tablename('enjoy_circle_topic') . " WHERE tid = '$id' and uniacid = '{$_W['uniacid']}'");
// 	$red['stime']=$red['stime']!=''?date('Y-m-d H:i',$red['stime']):date('Y-m-d H:i',TIMESTAMP);
// 	$red['etime']=$red['etime']!=''?date('Y-m-d H:i',$red['etime']):date('Y-m-d H:i',TIMESTAMP+86400);
} elseif ($op == 'delete') {
	$id = intval($_GPC['tid']);
	$topic = pdo_fetch("SELECT tid FROM " . tablename('enjoy_circle_topic') . " WHERE tid = '$id' AND uniacid=" . $_W['uniacid'] . "");
	if (empty($topic)) {
		message('抱歉，话题不存在或是已经被删除！', $this->createWebUrl('topic', array('op' => 'display')), 'error');
	}
	pdo_delete('enjoy_circle_topic', array('tid' => $id));
	message('话题删除成功！', $this->createWebUrl('topic', array('op' => 'display')), 'success');
} else {
	message('请求方式不存在');
}


include $this->template('topic');