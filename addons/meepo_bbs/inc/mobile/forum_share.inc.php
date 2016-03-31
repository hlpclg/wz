<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;
checkauth();
$openid = $_W['openid'];
$tid = intval($_GPC['id']);
if(empty($tid)){
	die('error');
}
$sql = "SELECT * FROM ".tablename('meepo_bbs_topic_share')." WHERE openid = :openid AND tid = :tid limit 1";
$params = array(':openid'=>$_W['openid'],':tid'=>$tid);
$share = pdo_fetch($sql,$params);

if(!empty($share)){
	pdo_update('meepo_bbs_topic_share',array('num'=>$share['num'] + 1),array('openid'=>$_W['openid'],'tid'=>$tid));
}else{
	$like = array();
	$like['tid']=$tid;
	$like['openid'] = $_W['openid'];
	$like['num'] = 1;
	$like['time']=time();
	pdo_insert('meepo_bbs_topic_share',$like);

	//点赞 类型为1
	$sql = "SELECT * FROM ".tablename('meepo_bbs_topics')." WHERE id = :tid";
	$params = array(':tid'=>$tid);
	$topic = pdo_fetch($sql,$params);
	insert_home_message($_W['member']['uid'],$topic['uid'],$tid,1,$_W['member']['nickname'].'分享了您的帖子'.$topic['title']);
	update_credit_share($tid);

	$date = '分享成功';
}

die($date);