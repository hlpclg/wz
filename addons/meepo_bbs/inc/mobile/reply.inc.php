<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;

checkauth();
$uid = $_W['member']['uid'];
if(empty($uid)){
	$res = array();
	$res['success'] = false;
	die(json_encode($res));
}
$input = $_GPC['__input'];
$id = $input['reply_id'];
$data = array();
$data['content'] = trim($input['content']);
$data['tid'] = intval($input['reply_id']);
$data['uniacid'] = $_W['uniacid'];
$data['uid'] = $uid;
$data['create_at'] = time();
pdo_insert('meepo_bbs_topic_replie',$data);

$res = array();
$res['success'] = true;
die(json_encode($res));

