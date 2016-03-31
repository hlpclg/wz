<?php
global $_W,$_GPC;
include_once INC_PATH.'web/report/navs.php';
$op = !empty($_GPC['op'])?trim($_GPC['op']):'list';

if($op == 'list_no'){
	$panel_heading = '未查看举报';
	$sex = 1;
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$condition = "AND sex = '{$sex}' AND uniacid = '{$_W['uniacid']}'";
	$list = pdo_fetchall("SELECT re.*,pai.nickname FROM ".tablename('meepo_pai_report')." AS re LEFT JOIN ".tablename('meepo_pai')." AS pai ON re.uid=pai.uid WHERE re.status=0 AND re.uniacid = '{$_W['uniacid']}' ORDER BY re.time DESC LIMIT " . ($pindex - 1) * $psize . "," . $psize);
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('meepo_pai_report')." WHERE status=0 AND uniacid = '{$_W['uniacid']}'");
		
	$pager = pagination($total, $pindex, $psize);
	include $this->template('report_list');

}

if($op == 'list_yes'){
	$panel_heading = '已确认举报';
	$sex = 1;
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$condition = "AND sex = '{$sex}' AND uniacid = '{$_W['uniacid']}'";
	$list = pdo_fetchall("SELECT re.*,pai.nickname FROM ".tablename('meepo_pai_report')." AS re LEFT JOIN ".tablename('meepo_pai')." AS pai ON re.uid=pai.uid WHERE re.status=1 AND re.uniacid = '{$_W['uniacid']}' ORDER BY re.time DESC LIMIT " . ($pindex - 1) * $psize . "," . $psize);
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('meepo_pai_report')." WHERE status=1 AND uniacid = '{$_W['uniacid']}'");
		
	$pager = pagination($total, $pindex, $psize);
	include $this->template('report_list');

}



if($op == 'list_done'){
	$panel_heading = '已处理举报';
	$sex = 1;
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$condition = "AND sex = '{$sex}' AND uniacid = '{$_W['uniacid']}'";
	$list = pdo_fetchall("SELECT re.*,pai.nickname FROM ".tablename('meepo_pai_report')." AS re LEFT JOIN ".tablename('meepo_pai')." AS pai ON re.uid=pai.uid WHERE re.status=2 AND re.uniacid = '{$_W['uniacid']}' ORDER BY re.time DESC LIMIT " . ($pindex - 1) * $psize . "," . $psize);
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('meepo_pai_report')." WHERE status=2 AND uniacid = '{$_W['uniacid']}'");
		
	$pager = pagination($total, $pindex, $psize);
	include $this->template('report_list');

}
if($op == 'list_san'){
	$panel_heading = '已忽略举报';
	$sex = 1;
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$condition = "AND sex = '{$sex}' AND uniacid = '{$_W['uniacid']}'";
	$list = pdo_fetchall("SELECT re.*,pai.nickname FROM ".tablename('meepo_pai_report')." AS re LEFT JOIN ".tablename('meepo_pai')." AS pai ON re.uid=pai.uid WHERE re.status=3 AND re.uniacid = '{$_W['uniacid']}' ORDER BY re.time DESC LIMIT " . ($pindex - 1) * $psize . "," . $psize);
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('meepo_pai_report')." WHERE status=3 AND uniacid = '{$_W['uniacid']}'");
		
	$pager = pagination($total, $pindex, $psize);
	include $this->template('report_list');

}

if($op == 'edit'){
	$id = intval($_GPC['id']);
	if(empty($id)){
		message('您操作的记录不存在或已删除',referer(),'error');
	}
	$item = pdo_fetch("SELECT re.*,pai.nickname,pai.src_img FROM ".tablename('meepo_pai_report')." AS re LEFT JOIN ".tablename('meepo_pai')." AS pai ON re.uid = pai.uid WHERE re.id='{$id}' AND re.uniacid='{$_W['uniacid']}' limit 1");
	$item['time'] = date('Y-m-d',$item['time']);
	$panel_heading = '举报'.$item['nickname'].'的信息';
	if($item['repoer_reason'] == 0){$item['repoer_reason_str'] = '虚假';}
	if($item['repoer_reason'] == 1){$item['repoer_reason_str'] = '色情';}
	if($item['repoer_reason'] == 2){$item['repoer_reason_str'] = '侵犯肖像权';}
	include $this->template('report_detail');
}

if($op == 'send'){
	$id = intval($_GPC['id']);
	$status = intval($_GPC['status']);
	if(empty($id)){
		message('您操作的记录不存在或已删除',referer(),'error');
	}
	pdo_update('meepo_pai_report',array('status'=>$status),array('id'=>$id));
	message('操作成功',referer(),'success');
	
}

if($op == 'delete'){
	$id = intval($_GPC['id']);
	if(empty($id)){
		message('您操作的记录不存在或已删除',referer(),'error');
	}
	pdo_delete('meepo_pai_report',array('id'=>$id));
	message('删除成功',referer(),'success');
	
}