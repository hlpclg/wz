<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W, $_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display_new';
if ($operation == 'delete') { //删除兑换请求
	$id = intval($_GPC['id']);
	$row = pdo_fetch("SELECT * FROM ".tablename('meepo_bbs_credit_request')." WHERE id = :id", array(':id' => $id));
	if (empty($row)) {
		message('抱歉，编号为'.$id.'的兑换请求不存在或是已经被删除！');
	} else if ($row['status'] = 'new') {
		message('未兑换商品无法删除。请兑换后删除！', referer(), 'error');
	}
	pdo_delete('meepo_bbs_credit_request', array('id' => $id));
	message('删除成功！', referer(), 'success');
} else if ($operation == 'do_goods') { // 完成兑换
	$data = array(
		'status' => 'done'
	);
	$id = intval($_GPC['id']);
	$row = pdo_fetch("SELECT id FROM ".tablename('meepo_bbs_credit_request')." WHERE id = :id", array(':id' => $id));
	if (empty($row)) {
		message('抱歉，编号为'.$id.'的兑换请求不存在或是已经被删除！');
	}
	pdo_update('meepo_bbs_credit_request', $data, array('id' => $id));
	message('已经移入“已兑换请求”栏！', referer(), 'success');
} else if ($operation == 'display_new' || empty($operation)) {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$sql = "SELECT * FROM ".tablename('meepo_bbs_credit_request')." WHERE uniacid = :uniacid ";
	$ssql = "SELECT COUNT(*) FROM ".tablename('meepo_bbs_credit_request')." WHERE uniacid = :uniacid ";
	$params = array(':uniacid'=>$_W['uniacid']);
	
	if(!empty($_GPC['status'])){
		$sql .= " AND status = :status ";
		$ssql .= " AND status = :status ";
		$params[':status'] = trim($_GPC['status']);
	}
	
	if(!empty($_GPC['uid'])){
		$sql .= " AND uid = :uid ";
		$ssql .= " AND uid = :uid ";
		$params[':uid'] = intval($_GPC['uid']);
	}
	
	$sql .= " ORDER BY createtime DESC ". "LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
	
	$lists = pdo_fetchall($sql,$params);
	foreach ($lists as $li){
		$user = pdo_fetch("SELECT * FROM ".tablename('mc_members')." WHERE uid = '{$li['uid']}' limit 1");
		$li['nickname'] = $user['nickname'];
		$li['createtime'] = date('Y-m-d',$li['createtime']);
		$sql = "SELECT * FROM ".tablename('meepo_bbs_credit_goods')." WHERE id = :id";
		$params = array(':id'=>$li['goods_id']);
		$goods = pdo_fetch($sql,$params);
		$li['title'] = $goods['title'];
		$li['price'] = $goods['price'];
		$li['cost'] = $goods['cost'];
		$list[] = $li;
	}
	$total = pdo_fetchcolumn($ssql, $params);
	$pager = pagination($total, $pindex, $psize);
	
	include $this->template('credit_request');
	
}