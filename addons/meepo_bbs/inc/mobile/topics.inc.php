<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;
__init();
load()->model('mc');
$id = $_GPC['id'];
$params = array(':uniacid'=>$_W['uniacid']);
if($id){
	$where = " AND fid = :fid ";
	$params[':fid'] = $id;
}
$sql = "SELECT * FROM ".tablename('meepo_bbs_topics')." WHERE uniacid = :uniacid $where ORDER BY last_reply_at DESC";

$topics = pdo_fetchall($sql,$params);
foreach ($topics as $data) {
	$user = mc_fetch($data['uid'],array('avatar'));
	$data['author']['avatar'] = tomedia($user['avatar']);
	$data['last_reply_at'] = date('Y-m-d h:i:sa',$data['last_reply_at']);
	$res['data'][] = $data;
}

die(json_encode($res));

function __init(){
	/*
	meepo_bbs_topics
	id
	uid
	title
	tab
	last_reply_at
	createtime

	 */
	if(!pdo_tableexists('meepo_bbs_topics')){
		$sql = "CREATE TABLE `ims_meepo_bbs_topics` (
				`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
				`uid` int(11) UNSIGNED NOT NULL DEFAULT 0,
				`uniacid` int(11) UNSIGNED NOT NULL DEFAULT 0,
				`title` varchar(320) NULL,
				`tab` varchar(32) NULL,
				`last_reply_at` int(11) UNSIGNED NOT NULL DEFAULT 0,
				`createtime` int(11) UNSIGNED NOT NULL DEFAULT 0,
				PRIMARY KEY (`id`)
			) ENGINE=MyISAM
		CHECKSUM=0
		DELAY_KEY_WRITE=0;";
		pdo_query($sql);
	}

	if(!pdo_fieldexists('meepo_bbs_topics','content')){
		$sql = "ALTER TABLE `ims_meepo_bbs_topics` 
			ADD COLUMN `content` text NULL AFTER `createtime`;";
		pdo_query($sql);
	}
	//帖子 板块版块ID
	if(!pdo_fieldexists('meepo_bbs_topics','fid')){
		$sql = "ALTER TABLE `ims_meepo_bbs_topics` 
			ADD COLUMN `fid` int(11) NULL AFTER `createtime`;";
		pdo_query($sql);
	}
	//帖子 主题ID
	if(!pdo_fieldexists('meepo_bbs_topics','tid')){
		$sql = "ALTER TABLE `ims_meepo_bbs_topics` 
			ADD COLUMN `tid` int(11) NULL AFTER `createtime`;";
		pdo_query($sql);
	}
	//帖子审核状态 -5=回收站;-3=已忽略;-2=待审核;-1=主题帖在回收站中;0=已审核通过
	if(!pdo_fieldexists('meepo_bbs_topics','invisible')){
		$sql = "ALTER TABLE `ims_meepo_bbs_topics` 
			ADD COLUMN `invisible` tinyint(1) NULL AFTER `createtime`;";
		pdo_query($sql);
	}
	
	if(!pdo_fieldexists('meepo_bbs_topics','rate')){
		$sql = "ALTER TABLE `ims_meepo_bbs_topics` 
			ADD COLUMN `rate` int(11) NULL AFTER `createtime`;";
		pdo_query($sql);
	}
	
	if(!pdo_fieldexists('meepo_bbs_topics','ratetimes')){
		$sql = "ALTER TABLE `ims_meepo_bbs_topics` 
			ADD COLUMN `ratetimes` int(11) NULL AFTER `createtime`;";
		pdo_query($sql);
	}
	//帖子标签
	if(!pdo_fieldexists('meepo_bbs_topics','tags')){
		$sql = "ALTER TABLE `ims_meepo_bbs_topics` 
			ADD COLUMN `tags` varchar(150) NULL AFTER `createtime`;";
		pdo_query($sql);
	}
	//回帖积分
	if(!pdo_fieldexists('meepo_bbs_topics','replycredit')){
		$sql = "ALTER TABLE `ims_meepo_bbs_topics` 
			ADD COLUMN `replycredit` int(11) NULL AFTER `createtime`,
			KEY uid (uid);";
		pdo_query($sql);
	}
}
