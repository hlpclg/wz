<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;

load()->model('mc');
$op = $_GPC['op'];
$tid = $_GPC['tid'];

$openid = $_W['openid'];

$sql = "SELECT * FROM ".tablename('meepo_bbs_topics')." WHERE id = :id";
$params = array(':id'=>$tid);
$topic = pdo_fetch($sql,$params);

$to_uid = $topic['uid'];
$fid = $topic['fid'];

$credit = getCredit($fid);

if($op == 'read'){
	$sql = "SELECT num FROM ".tablename('meepo_bbs_topic_read')." WHERE openid = :openid AND tid = :tid";
	$params = array(':openid'=>$openid,':tid'=>$tid);
	$num = pdo_fetchcolumn($sql,$params);
	
	if(empty($num)){
		//第一次阅读
		$data = array();
		$data['tid'] = $tid;
		$data['openid'] = $openid;
		$data['time'] = time();
		$data['num'] = 0;
		pdo_insert('meepo_bbs_topic_read',$data);
		if(!empty($to_uid)){
			mc_credit_update($to_uid, 'credit1',$credit['bread'],array($to_uid,'帖子'.$topic['title'].'被阅读奖励积分'));
		}
		
		if(!empty($_W['member']['uid'])){
			mc_credit_update($_W['member']['uid'], 'credit1',$credit['read'],array($to_uid,'阅读帖子'.$topic['title'].'奖励积分'));
		}
		
	}else{
		$data = array();
		$data['time'] = time();
		$data['num'] = $num + 1;
		pdo_update('meepo_bbs_topic_read',$data,array('openid'=>$openid,'tid'=>$tid));
	}
}

if($op == 'share'){
	$sql = "SELECT num FROM ".tablename('meepo_bbs_topic_share')." WHERE openid = :openid AND tid = :tid";
	$params = array(':openid'=>$openid,':tid'=>$tid);
	$num = pdo_fetchcolumn($sql,$params);

	if(empty($num)){
		//第一次分享
		$data = array();
		$data['tid'] = $tid;
		$data['openid'] = $openid;
		$data['time'] = time();
		$data['num'] = 0;
		pdo_insert('meepo_bbs_topic_read',$data);
		if(!empty($to_uid)){
			mc_credit_update($to_uid, 'credit1',$credit['bshare'],array($to_uid,'帖子'.$topic['title'].'被分享奖励积分'));
		}

		if(!empty($_W['member']['uid'])){
			mc_credit_update($_W['member']['uid'], 'credit1',$credit['share'],array($to_uid,'分享帖子'.$topic['title'].'奖励积分'));
		}

	}else{
		$data = array();
		$data['time'] = time();
		$data['num'] = $num + 1;
		pdo_update('meepo_bbs_topic_read',$data,array('openid'=>$openid,'tid'=>$tid));
	}
}

if($op == 'like'){
	$sql = "SELECT num FROM ".tablename('meepo_bbs_topic_like')." WHERE openid = :openid AND tid = :tid";
	$params = array(':openid'=>$openid,':tid'=>$tid);
	$num = pdo_fetchcolumn($sql,$params);

	if(empty($num)){
		//第一次分享
		$data = array();
		$data['tid'] = $tid;
		$data['openid'] = $openid;
		$data['time'] = time();
		$data['num'] = 0;
		pdo_insert('meepo_bbs_topic_read',$data);
		if(!empty($to_uid)){
			mc_credit_update($to_uid, 'credit1',$credit['bgoods'],array($to_uid,'帖子'.$topic['title'].'被赞奖励积分'));
		}

		if(!empty($_W['member']['uid'])){
			mc_credit_update($_W['member']['uid'], 'credit1',$credit['goods'],array($to_uid,'赞帖子'.$topic['title'].'奖励积分'));
		}

	}else{
		$data = array();
		$data['time'] = time();
		$data['num'] = $num + 1;
		pdo_update('meepo_bbs_topic_read',$data,array('openid'=>$openid,'tid'=>$tid));
	}
}

if($op == 'reply'){
	$sql = "SELECT num FROM ".tablename('meepo_bbs_topic_like')." WHERE openid = :openid AND tid = :tid";
	$params = array(':openid'=>$openid,':tid'=>$tid);
	$num = pdo_fetchcolumn($sql,$params);
	
	if(empty($num)){
		//第一次分享
		$data = array();
		$data['tid'] = $tid;
		$data['openid'] = $openid;
		$data['time'] = time();
		$data['num'] = 0;
		pdo_insert('meepo_bbs_topic_read',$data);
		if(!empty($to_uid)){
			mc_credit_update($to_uid, 'credit1',$credit['bgoods'],array($to_uid,'帖子'.$topic['title'].'被赞奖励积分'));
		}
	
		if(!empty($_W['member']['uid'])){
			mc_credit_update($_W['member']['uid'], 'credit1',$credit['goods'],array($to_uid,'赞帖子'.$topic['title'].'奖励积分'));
		}
	
	}else{
		$data = array();
		$data['time'] = time();
		$data['num'] = $num + 1;
		pdo_update('meepo_bbs_topic_read',$data,array('openid'=>$openid,'tid'=>$tid));
	}
}


