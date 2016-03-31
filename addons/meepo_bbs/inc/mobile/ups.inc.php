<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;
checkauth();
$uid = $_W['member']['uid'];
$res = array();
if(empty($uid)){
	$res['success'] = false;
	die(json_encode($res));
}


$input = $_GPC['__input'];
$data = array();
$data['rid'] = $input['id'];
$data['uid'] = $uid;
$data['caretetime'] = time();
$data['uniacid'] = $_W['uniacid'];

$sql = "SELECT rid FROM ".tablename('meepo_bbs_reply_ups')." WHERE rid = :rid AND uid = :uid";
$params = array(':rid'=>$data['rid'],':uid'=>$uid);
$up = pdo_fetch($sql,$params);
if(!empty($up)){
	//已经点赞，不能重复点赞
	$res['success'] = false;
	die(json_encode($res));
}
pdo_insert('meepo_bbs_reply_ups',$data);

$res['success'] = true;

//更新点赞次数

die(json_encode($res));

function __init(){
	if(!pdo_tableexists('meepo_bbs_reply_ups')){
		$sql = "CREATE TABLE `ims_meepo_bbs_reply_ups` (
			`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			`rid` int(11) UNSIGNED NOT NULL DEFAULT 0,
			`uid` int(11) UNSIGNED NOT NULL DEFAULT 0,
			`caretetime` int(11) UNSIGNED NOT NULL DEFAULT 0,
			`uniacid` int(11) UNSIGNED NOT NULL DEFAULT 0,
			PRIMARY KEY (`id`)
		) ENGINE=MyISAM
		CHECKSUM=0
		DELAY_KEY_WRITE=0;
		";

		pdo_query($sql);
	}
}