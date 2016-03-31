<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;
$tempalte = $this->module['config']['name']?$this->module['config']['name']:'default';

$catid = $_GPC['catid'];

$list = getCat();

foreach ($list as $li){
	$sql = "SELECT COUNT(*) FROM ".tablename('meepo_bbs_topics')." WHERE fid = :fid ";
	$params = array(':fid'=>$li['typeid']);
	$li['total_num'] = pdo_fetchcolumn($sql,$params);
	$cats[] = $li;
}


if(empty($catid)){
	$sql = "SELECT * FROM ".tablename('meepo_bbs_threadclass')." WHERE uniacid = :uniacid AND isgood = :isgood";
	$params = array(':uniacid'=>$_W['uniacid'],':isgood'=>1);
	$list = pdo_fetchall($sql,$params);
	
	foreach ($list as $li){
		$sql = "SELECT COUNT(*) FROM ".tablename('meepo_bbs_topics')." WHERE fid = :fid ";
		$params = array(':fid'=>$li['typeid']);
		$li['total_num'] = pdo_fetchcolumn($sql,$params);
		$good_cats[] = $li;
	}
}else{
	$params = array(':uniacid'=>$_W['uniacid'],':fid'=>$catid);
	$sql = "SELECT * FROM ".tablename('meepo_bbs_threadclass')." WHERE uniacid = :uniacid AND fid = :fid ORDER BY displayorder DESC";
	$list = pdo_fetchall($sql,$params);
	
	
	foreach ($list as $li){
		$sql = "SELECT COUNT(*) FROM ".tablename('meepo_bbs_topics')." WHERE fid = :fid ";
		$params = array(':fid'=>$li['typeid']);
		$li['total_num'] = pdo_fetchcolumn($sql,$params);
		$good_cats[] = $li;
	}
}

include $this->template($tempalte.'/templates/forum/cat');