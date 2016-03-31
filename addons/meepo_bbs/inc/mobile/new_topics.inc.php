<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;

$input = $_GPC['__input'];

checkauth();

$uid = $_W['member']['uid'];

if(is_numeric($input['accesstoken'])){
	$input['accesstoken'] = intval($input['accesstoken']);
}else{
	$input['accesstoken'] = $uid;
}

if(empty($input['accesstoken'])){
	$res['success'] = false;
	die(json_encode($res));
}

$data = array();
$data['title'] = $input['title'];
$data['tab'] = $input['tab'];
$data['content'] = $input['content'];
$data['uid'] = $input['accesstoken'];
$data['uniacid'] = $_W['uniacid'];
$data['createtime'] = time();
$data['last_reply_at'] = time();
$data['fid'] = $input['typeid'];

pdo_insert('meepo_bbs_topics',$data);
$res['success'] = true;
$res['topic_id'] = pdo_insertid();

die(json_encode($res));