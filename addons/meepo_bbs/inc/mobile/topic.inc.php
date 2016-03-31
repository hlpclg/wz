<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;
__init();

$uid = $_W['member']['uid'];
$id = intval($_GPC['id']);
if(empty($id)){
	$res = array();
	$res['error_msg'] = '主题不存在或已删除';
}

$sql = "SELECT * FROM ".tablename('meepo_bbs_topics')." WHERE id = :id";
$params = array(':id'=>$id);
$data = pdo_fetchall($sql,$params);

foreach ($data as $da) {
	$user = mc_fetch($da['uid'],array('nickname','avatar'));
	$da['author']['avatar'] = tomedia($user['avatar']);
	$da['author']['nickname'] = $user['nickname'];
	$da['create_at'] = date('Y-m-d h:i:sa',$da['create_at']);
	$sql = "SELECT * FROM ".tablename('meepo_bbs_topic_replie')." WHERE tid = :tid";
	$params = array(':tid'=>$da['id']);
	$replies = pdo_fetchall($sql,$params);
	foreach ($replies as $re) {
		$user = mc_fetch($re['uid'],array('avatar','nickname'));
		$re['author']['avatar'] = tomedia($user['avatar']);
		$re['author']['nickname'] = $user['nickname'];
		$re['create_at'] = date('Y-m-d h:i:sa',$re['create_at']);
		$sql = "SELECT uid FROM ".tablename('meepo_bbs_reply_ups')." WHERE rid = :rid";
		$params = array(':rid'=>$re['id']);
		$re['ups'] = pdo_fetchall($sql,$params);
		$reply[] = $re;
	}
	$da['replies'] = $reply;
	$res['data'] = $da;
}

