<?php
global $_W,$_GPC;
include_once INC_PATH.'web/rank/navs.php';
$op = !empty($_GPC['op'])?trim($_GPC['op']):'list_boy';

if($op == 'list_boy'){
	$panel_heading = '男生排名';
	$sex = 1;
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$condition = "AND sex = '{$sex}' AND uniacid = '{$_W['uniacid']}'";
	$list = pdo_fetchall("SELECT * FROM ".tablename('meepo_pai')." WHERE sex = '{$sex}' AND uniacid = '{$_W['uniacid']}' ORDER BY num DESC LIMIT " . ($pindex - 1) * $psize . "," . $psize);
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('meepo_pai')." WHERE sex = '{$sex}' AND uniacid = '{$_W['uniacid']}'");
	
	$pager = pagination($total, $pindex, $psize);
	include $this->template('rank_list');
}

if($op == 'list_girl'){
	$panel_heading = '女生排名';
	$sex = 0;
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$condition = "AND sex = '{$sex}' AND uniacid = '{$_W['uniacid']}'";
	$list = pdo_fetchall("SELECT * FROM ".tablename('meepo_pai')." WHERE sex = '{$sex}' AND uniacid = '{$_W['uniacid']}' ORDER BY num DESC LIMIT " . ($pindex - 1) * $psize . "," . $psize);
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('meepo_pai')." WHERE sex = '{$sex}' AND uniacid = '{$_W['uniacid']}'");
		
	$pager = pagination($total, $pindex, $psize);
	include $this->template('rank_list');
}

if($op == 'edit'){
	$pid = intval($_GPC['pid']);
	if(empty($pid)){
		message('您操作的用户不存在或已删除',referer(),'error');
	}
	$item = pdo_fetch("SELECT * FROM ".tablename('meepo_pai')." WHERE pid='{$pid}' AND uniacid='{$_W['uniacid']}' limit 1");
	$item['time'] = date('Y-m-d',$item['time']);
	$panel_heading = $item['nickname'].'的信息';
	include $this->template('rank_detail');
}

if($op == 'delete'){
	$pid = intval($_GPC['pid']);
	if(empty($pid)){
		message('您操作的用户不存在或已删除',referer(),'error');
	}
	pdo_delete('meepo_pai',array('pid'=>$pid));
	message('删除成功',referer(),'success');
	
}

