<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;
load()->func('tpl');
$type = $_GPC['type']?trim($_GPC['type']):'text';
$sql = "SELECT * FROM ".tablename('meepo_bbs_set')." WHERE uniacid = :uniacid";
$params = array(':uniacid'=>$_W['uniacid']);
$row = pdo_fetch($sql,$params);
$set = unserialize($row['set']);

$tid = $_GPC['tid'];

$cats = getCat();

$sql = "SELECT * FROM ".tablename('meepo_bbs_topics')." WHERE id = :id";
$params = array(':id'=>$tid);
$setting = pdo_fetch($sql,$params);
$setting['thumb'] = unserialize($setting['thumb']);

if(empty($tid)){
	
	$tab = trim($_GPC['tab']);
	$uid = $set['sysuid'];
	
	if(empty($uid)){
		message('系统会员为空，请前往设置',$this->createWebUrl('set'),error);
	}
	
	$id = intval($_GPC['fid']);
	$sql = "SELECT name FROM ".tablename('meepo_bbs_threadclass')." WHERE typeid = :typeid ";
	$params = array(':typeid'=>$id);
	$fname = pdo_fetchcolumn($sql,$params);
}else{
	$tab = $setting['tab'];
	$uid = $setting['uid'];
	$id = $setting['fid'];
	
	$sql = "SELECT name FROM ".tablename('meepo_bbs_threadclass')." WHERE typeid = :typeid ";
	$params = array(':typeid'=>$id);
	$fname = pdo_fetchcolumn($sql,$params);
}

$types = array(
		array('code'=>'text','title'=>'普通帖'),
);

$tabs = array(
		array('code'=>'','title'=>'普通'),
		array('code'=>'jing','title'=>'精华'),
		array('code'=>'new','title'=>'最新'),
		array('code'=>'hot','title'=>'最热'),
		array('code'=>'top','title'=>'置顶'),
);

if($_W['ispost']){
	$data = array();
	
	$data['uid'] = $uid;
	$data['uniacid'] = $_W['uniacid'];
	$data['title'] = trim($_GPC['title']);
	$data['content'] = htmlspecialchars_decode($_GPC['content']);
	$data['tab'] = trim($_GPC['tab']);
	$data['last_reply_at'] = time();
	$data['createtime'] = time();
	$data['fid'] = intval($_GPC['fid']);
	if(!empty($_GPC['thumb'])){
		foreach ($_GPC['thumb'] as $thumb){
			$th[] = save_media(tomedia($thumb));
		}
	}
	
	$data['thumb'] = iserializer($th);
	
	if(empty($tid)){
		pdo_insert('meepo_bbs_topics',$data);
		$tid = pdo_insertid();
	}else{
		pdo_update('meepo_bbs_topics',$data,array('id'=>$tid));
	}
	
	message('提交成功',$this->createWebUrl('manage'),success);
}

include $this->template('forum_post_type');