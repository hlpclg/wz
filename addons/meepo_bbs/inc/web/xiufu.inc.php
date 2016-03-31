<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;

$sql = "SELECT * FROM ".tablename('meepo_bbs_topics')." WHERE uniacid = :uniacid GROUP BY fid";
$params = array(':uniacid'=>$_W['uniacid']);
$list = pdo_fetchall($sql,$params);


foreach ($list as $li){
	if(!empty($li['fid'])){
		$sql = "INSERT INTO `ims_meepo_bbs_threadclass` (`typeid`, `fid`, `uniacid`, `name`, `displayorder`, `icon`, `moderators`, `content`, `group`, `look_group`, `post_group`, `isgood`) VALUES
(".$li['fid'].", 0,".$li['uniacid'].", '数据异常恢复', 0, '', 0, '', NULL, '', '', 0);";
		var_dump(pdo_query($sql));
	}
}
