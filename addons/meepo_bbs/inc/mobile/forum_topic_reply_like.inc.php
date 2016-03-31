<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;
$openid = $_W['openid'];
if(empty($openid)){
	die('点赞成功');
}
$tid = intval($_GPC['tid']);
$sql = "SELECT * FROM ".tablename('meepo_bbs_topic_like')." WHERE openid = :openid AND tid = :tid";
$params = array(':openid'=>$_W['openid'],':tid'=>$tid);
$like = pdo_fetch($sql,$params);

if(!empty($like)){
	pdo_update('meepo_bbs_topic_like',array('num'=>$like['num'] + 1),array('openid'=>$_W['openid'],'tid'=>$tid));
}else{
	$like = array();
	$like['tid']=$tid;
	$like['openid'] = $_W['openid'];
	$like['num'] = 1;
	$like['time']=time();
	pdo_insert('meepo_bbs_topic_like',$like);
	
	$insert = array();
	//点赞 类型为1
	$sql = "SELECT * FROM ".tablename('meepo_bbs_topics')." WHERE id = :tid";
	$params = array(':tid'=>$tid);
	$topic = pdo_fetch($sql,$params);
	insert_home_message($_W['member']['uid'],$topic['uid'],$tid,1,$_W['member']['nickname'].'点赞了您的帖子'.$topic['title']);
	update_credit_like($tid);
	
	$date = '点赞成功';
}

die($date);

