<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;
$op = $_GPC['op'];

if($_W['role'] == 'manager' || $_W['isfounder']){
	
	if($op == 'all'){
		
		if($_W['ispost']){
			clearall();
			message('操作成功',referer(),success);
		}
		
	}
	
	if($op == 'topics'){
		if($_W['ispost']){
			cleartopics();
			message('操作成功',referer(),success);
		}
	}
	
	if($op == 'threadclass'){
		if($_W['ispost']){
			clearthreadclass();
			message('操作成功',referer(),success);
		}
	}
	
	if($op == 'adv'){
		if($_W['ispost']){
			clearadv();
			message('操作成功',referer(),success);
		}
	}
	
	if($op == 'task'){
		if($_W['ispost']){
			cleartask();
			message('操作成功',referer(),success);
		}
	}
	
	if($op == 'other'){
		if($_W['ispost']){
			clearother();
			message('操作成功',referer(),success);
		}
	}
	
	
}else{
	message('您不是次公众号管理员，没有清理权限',referer(),success);
}

function clearall(){
	global $_W;
	clearthreadclass();
	cleartask();
	clearadv();
	clearother();
}

function clearother(){
	global $_W;
	pdo_delete('meepo_bbs_credit_goods',array('uniacid'=>$_W['uniacid']));
	pdo_delete('meepo_bbs_credit_request',array('uniacid'=>$_W['uniacid']));
}
function clearadv(){
	global $_W;
	pdo_delete('meepo_bbs_adv',array('uniacid'=>$_W['uniacid']));
}

function cleartask(){
	global $_W;
	$sql = "SELECT taskid FROM ".tablename('meepo_bbs_task')." WHERE uniacid = :uniacid";
	$params = array(':uniacid'=>$_W['uniacid']);
	$taskid = pdo_fetchall($sql,$params);
	$in = db_create_in($topicid,'taskid');
	pdo_query("DELETE FROM ".tablename('meepo_bbs_task_user')." WHERE $in");
	pdo_delete('meepo_bbs_task',array('uniacid'=>$_W['uniacid']));
}

function clearthreadclass(){
	global $_W;
	cleartopics();
	pdo_delete('meepo_bbs_threadclass',array('uniacid'=>$_W['uniacid']));
}

function cleartopics(){
	global $_W;
	$sql = "SELECT typeid FROM ".tablename('meepo_bbs_threadclass')." WHERE uniacid = :uniacid";
	$params = array(':uniacid'=>$_W['uniacid']);
	$typeid = pdo_fetchall($sql,$params);
	
	$in = db_create_in($typeid,'fid');
	$sql = "SELECT id FROM ".tablename('meepo_bbs_topics')." WHERE $in ";
	$topicid = pdo_fetchall($sql,$params);
	
	$in = db_create_in($topicid,'tid');
	
	pdo_query("DELETE FROM ".tablename('meepo_bbs_share')." WHERE $in");
	pdo_query("DELETE FROM ".tablename('meepo_bbs_topic_like')." WHERE $in");
	pdo_query("DELETE FROM ".tablename('meepo_bbs_topic_read')." WHERE $in");
	pdo_query("DELETE FROM ".tablename('meepo_bbs_topic_replie')." WHERE $in");
	pdo_query("DELETE FROM ".tablename('meepo_bbs_topic_share')." WHERE $in");
	pdo_delete('meepo_bbs_share',array('uniacid'=>$_W['uniacid']));
	pdo_delete('meepo_bbs_reply_ups',array('uniacid'=>$_W['uniacid']));
	pdo_delete('meepo_bbs_topics',array('uniacid'=>$_W['uniacid']));
}
