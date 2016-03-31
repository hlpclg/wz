<?php
global $_W,$_GPC;
include_once INC_PATH.'web/vote/navs.php';
$op = !empty($_GPC['op'])?trim($_GPC['op']):'list';

if($op == 'list'){
	$panel_heading = '投票列表';
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$condition = "AND uniacid = '{$_W['uniacid']}'";
	$list = pdo_fetchall("SELECT re.*,pai.nickname FROM ".tablename('meepo_pai_log')." AS re LEFT JOIN ".tablename('meepo_pai')." AS pai ON re.uid=pai.uid WHERE  re.uniacid = '{$_W['uniacid']}' ORDER BY re.time DESC LIMIT " . ($pindex - 1) * $psize . "," . $psize);
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('meepo_pai_log')." WHERE uniacid = '{$_W['uniacid']}'");
		
	$pager = pagination($total, $pindex, $psize);
	include $this->template('vote_list');

}

if($op == 'delete'){
	$id = intval($_GPC['id']);
	if(empty($pid)){
		message('您操作的用户不存在或已删除',referer(),'error');
	}
	pdo_delete('meepo_pai_log',array('id'=>$id));
	message('删除成功',referer(),'success');
	
}

