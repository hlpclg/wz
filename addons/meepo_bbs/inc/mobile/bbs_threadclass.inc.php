<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;

__init();
load()->model('mc');

$params = array(':uniacid'=>$_W['uniacid']);

$where = "";

$sql = "SELECT * FROM ".tablename('meepo_bbs_threadclass')." WHERE uniacid = :uniacid $where ORDER BY displayorder DESC";

$list = pdo_fetchall($sql,$params);

foreach ($list as $li) {
	$li['icon'] = tomedia($li['icon']);
	$res['data'][] = $li;
}


die(json_encode($res));

function __init(){
	/*
	 * meepo_bbs_threadclass
	 * 
	 * typeid
	 * fid
	 * name
	 * displayorder
	 * icon
	 * moderators
	 * 
	 * */
		if(!pdo_tableexists('meepo_bbs_threadclass')){
		 	$sql = "
		 	CREATE TABLE `ims_meepo_bbs_threadclass` (
				`typeid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
				`fid` int(11) UNSIGNED NOT NULL DEFAULT 0,
				`name` varchar(255) NOT NULL DEFAULT '',
				`displayorder` int(11) UNSIGNED NOT NULL DEFAULT 0,
				`icon` varchar(255) NOT NULL DEFAULT '',
				`moderators` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
				PRIMARY KEY (`typeid`)
			) ENGINE=MyISAM
			CHECKSUM=0
			DELAY_KEY_WRITE=0;
		 	";
			pdo_query($sql);
		 }
		
		if(!pdo_fieldexists('meepo_bbs_threadclass','uniacid')){
			$sql = "ALTER TABLE `ims_meepo_bbs_threadclass` 
				ADD COLUMN `uniacid` int(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `fid`;";
			pdo_query($sql);
		}
}
