<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;
load()->model('mc');
$op = $_GPC['op'];
checkauth();
$id = $_GPC['id'];
$sql = "SELECT * FROM ".tablename('meepo_bbs_topics')." WHERE id = :id";
$params = array(':id'=>$id);
$topic = pdo_fetch($sql,$params);

$to_uid = $topic['uid'];
$fid = $topic['fid'];

$credit = getCredit($fid);

if(empty($_W['openid'])){
	die('请从微信端浏览');
}

if($op == 'delete_reply'){
	if(empty($id)){
		message('',referer(),success);
	}
	pdo_delete('meepo_bbs_topic_replie',array('id'=>$id));
	message('',referer(),success);
}

if($op == 'delete'){
	pdo_delete('meepo_bbs_topics',array('id'=>$id));
	update_credit_delete($tid);
	insert_home_message($_W['member']['uid'],$topic['uid'],$tid,0,'您的帖子'.$topic['title'].'被删除');
	message('操作成功',$this->createMobileUrl('forum'),'success');
}

if($op = 'tab'){
	$tab = trim($_GPC['tab']);
	
	pdo_update('meepo_bbs_topics',array('tab'=>$tab),array('id'=>$id));
	
	if($tab == 'top'){
		update_credit_top($tid);
		insert_home_message($_W['member']['uid'],$topic['uid'],$tid,0,'您的帖子'.$topic['title'].'被置顶');
	}
	
	if($tab == 'jing'){
		update_credit_jing($tid);
		insert_home_message($_W['member']['uid'],$topic['uid'],$tid,0,'您的帖子'.$topic['title'].'被加精');
	}
	
	message('操作成功',$this->createMobileUrl('forum_topic',array('id'=>$id)),'success');
}