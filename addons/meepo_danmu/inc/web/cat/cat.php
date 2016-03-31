<?php
global $_W,$_GPC;
include_once INC_PATH.'web/cat/navs.php';
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'manage';


if($op == 'manage'){
	load()->func('tpl');
	$panel_heading = '链接管理';
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$condition = " AND uniacid = '{$_W['uniacid']}'";
	$list = pdo_fetchall("SELECT * FROM ".tablename('meepo_danmu_data')."  WHERE uniacid = '{$_W['uniacid']}' ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . "," . $psize);
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('meepo_danmu_data')." WHERE uniacid = '{$_W['uniacid']}'");
		
	$pager = pagination($total, $pindex, $psize);
	include $this->template('manage');
}



elseif($op == 'post'){
	$panel_heading = '添加链接';
	$id = intval($_GPC['id']);
	$cat = pdo_fetch("SELECT * FROM".tablename('meepo_danmu_data')." WHERE uniacid='{$_W['uniacid']}' AND id='{$id}'");
	if($_W['ispost']){
		$data = array(
			'uniacid'=>$_W['uniacid'],
			'title'=>$_GPC['title'],
			'url'=>$_GPC['url'],
			'status'=>$_GPC['status'],
		);
		
		if(empty($id)){
			pdo_insert('meepo_danmu_data',$data);
		}else{
			unset($data['uniacid']);
			pdo_update('meepo_danmu_data', $data, array('id'=>$id));
		}
		
		message('添加成功',referer(),'success');
	}
	include $this->template('cat_post');
}


elseif($op == 'delete'){
	 $id = intval($_GPC['id']);
	 if(empty($id)){
	 	
	 	message('您操作的分类不存在或已删除',referer(),'error');
	 }	
	 pdo_delete('meepo_danmu_data',array('id'=>$id));
}