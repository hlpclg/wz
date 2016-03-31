<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;

if($_W['ispost']){
	$min = intval($_GPC['min']);
	$max = intval($_GPC['max']);
	
	
	$sql = "SELECT id FROM ".tablename('meepo_bbs_topics')." WHERE uniacid = :uniacid";
	$params = array(':uniacid'=>$_W['uniacid']);
	$topics = pdo_fetchall($sql,$params);

	foreach ($topics as $topic) {
		$random = rand($min,$max);
		$sql = "SELECT lnum FROM ".tablename('meepo_bbs_topics')." WHERE uniacid = :uniacid AND id = :id";
		$params = array(':uniacid'=>$_W['uniacid'],':id'=>$topic['id']);
		$num = pdo_fetchcolumn($sql,$params);
		
		pdo_update('meepo_bbs_topics',array('lnum'=>$num + $random),array('id'=>$topic['id']));
	}
	message('添加虚假浏览数据成功',referer(),'success');
}

include $this->template('random');