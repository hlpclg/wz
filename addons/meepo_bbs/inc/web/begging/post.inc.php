<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;
load()->func('tpl');
$tid = $_GPC['tid'];
$set = getSet();
$type = $_GPC['type']?trim($_GPC['type']):'text';
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
		array('code'=>'sound','title'=>'语音帖'),
		array('code'=>'note','title'=>'投票帖'),
		array('code'=>'goods','title'=>'商品帖'),
		array('code'=>'credit_goods','title'=>'积分兑换帖'),
		array('code'=>'sys','title'=>'系统公告帖'),
);

$tabs = array(
	array('code'=>'begging','title'=>'打赏帖子'),
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
	$data['fid'] = $id;
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
	message('操作成功',referer(),success);
}