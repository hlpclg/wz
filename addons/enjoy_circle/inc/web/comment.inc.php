<?php
global $_W,$_GPC;

$tid=$_GPC['tid'];

load()->func('tpl');
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$pindex = max(1, intval($_GPC['page']));
$psize = 9;
if ($op == 'display') {
	$list = pdo_fetchall("SELECT * FROM " . tablename('enjoy_circle_comment') . " WHERE uniacid = '{$_W['uniacid']}' and tid=".$tid." ORDER BY createtime LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	$countadd=pdo_fetchcolumn("select count(*) from " . tablename('enjoy_circle_comment') . " WHERE uniacid = '{$_W['uniacid']}' and tid=".$tid."");
	$pager = pagination($countadd, $pindex, $psize);
} elseif ($op == 'post') {
	$id = intval($_GPC['cid']);
	if (checksubmit('submit')) {
			$data = array(
				'uniacid' => $_W['uniacid'],
				'comment'=>$_GPC['comment'],
				'nickname'=>$_GPC['nickname'],
				'avatar'=>$_GPC['avatar'],
				'hot'=>$_GPC['hot'],
				'tid'=>$_GPC['tid'],
				'createtime'=>TIMESTAMP
				//'etime'=>strtotime($_GPC['etime'])+59
		);
		//$count=intval($_GPC['count']);
		if (!empty($id)) {
			pdo_update('enjoy_circle_comment', $data, array('cid' => $id));

			$message="更新评论成功！";
		} else {
			pdo_insert('enjoy_circle_comment', $data);
			$id = pdo_insertid();

			$message="新增评论成功！";


		}
		message($message, $this->createWebUrl('comment', array('op' => 'display','tid'=>$tid)), 'success');
	}
	//修改
	$comment = pdo_fetch("SELECT * FROM " . tablename('enjoy_circle_comment') . " WHERE cid = '$id' and uniacid = '{$_W['uniacid']}'");
	// 	$red['stime']=$red['stime']!=''?date('Y-m-d H:i',$red['stime']):date('Y-m-d H:i',TIMESTAMP);
	// 	$red['etime']=$red['etime']!=''?date('Y-m-d H:i',$red['etime']):date('Y-m-d H:i',TIMESTAMP+86400);
} elseif ($op == 'delete') {
	$id = intval($_GPC['cid']);
	$comment = pdo_fetch("SELECT cid FROM " . tablename('enjoy_circle_comment') . " WHERE cid = '$id' AND uniacid=" . $_W['uniacid'] . "");
	if (empty($comment)) {
		message('抱歉，评论不存在或是已经被删除！', $this->createWebUrl('comment', array('op' => 'display','tid'=>$tid)), 'error');
	}
	pdo_delete('enjoy_circle_comment', array('cid' => $id));
	message('评论删除成功！', $this->createWebUrl('comment', array('op' => 'display','tid'=>$tid)), 'success');
} else {
	message('请求方式不存在');
}
include $this->template('comment');