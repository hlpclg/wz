<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;

$openid = $_W['openid'];
if(empty($openid)){
	die();
}
$tid = intval($_GPC['tid']);
$fid = intval($_GPC['fid']);

if(empty($tid)){
	message('',referer(),success);
}

if(empty($fid)){
	//帖子点赞
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
}


//回复点赞
$sql = "SELECT * FROM ".tablename('meepo_bbs_topic_like')." WHERE openid = :openid AND tid = :tid AND fid = :fid";
$params = array(':openid'=>$_W['openid'],':tid'=>$tid,':fid'=>$fid);
$like = pdo_fetch($sql,$params);

if(!empty($like)){
	pdo_update('meepo_bbs_topic_like',array('num'=>$like['num'] + 1),array('openid'=>$_W['openid'],'tid'=>$tid,'fid'=>$fid));
	message('',$this->createMobileUrl('forum_topic',array('id'=>$tid)));
}else{
	$like = array();
	$like['tid']=$tid;
	$like['openid'] = $_W['openid'];
	$like['num'] = 1;
	$like['time']=time();
	$like['fid'] = $fid;
	pdo_insert('meepo_bbs_topic_like',$like);
	message('',$this->createMobileUrl('forum_topic',array('id'=>$tid)));
}

die($date);