<?php /*折翼天使资源社区 www.zheyitianshi.com*/
//乞讨活动后台管理
global $_W,$_GPC;
load()->model('mc');
$fo = $_GPC['fo'];
if($_W['ispost']){
	if(!empty($_GPC['delete'])){
		$select = $_GPC['select'];
		foreach ($select as $key) {
			pdo_delete('meepo_bbs_topics',array('id'=>$key));
		}
		message('删除数据成功',referer(),success);
	}
}

if(empty($fo)){
	$typeid = $_GPC['typeid'];
	$condition = " AND tab = 'begging' ";
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$sql = "SELECT * FROM ".tablename('meepo_bbs_topics')."  WHERE uniacid = :uniacid {$condition} ORDER BY last_reply_at DESC ". "LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
	$params = array(':uniacid'=>$_W['uniacid']);
	$lists = pdo_fetchall($sql,$params);
	
	foreach ($lists as $li) {
		
		$li['detail'] = $this->createWebUrl('index',array('doo'=>'begging','op'=>'post','tid'=>$li['id']));
		$li['delete'] = $this->createWebUrl('manage',array('fo'=>'delete','id'=>$li['id']));
		
		$user = mc_fetch($li['uid'],array('avatar','nickname'));
		$li['avatar'] = tomedia($user['avatar']);
		$li['nickname'] = $user['nickname'];
		
		$sql = "SELECT SUM(fee) FROM ".tablename('meepo_bbs_begging')." WHERE ttid = :ttid";
		$params = array(':ttid'=>$li['id']);
		$begging_money = pdo_fetchcolumn($sql,$params);
		$li['last_reply_at'] = date('Y-m-d',$li['last_reply_at']);
		$li['begging_money'] = $begging_money;
		$sql = "SELECT * FROM ".tablename('meepo_bbs_threadclass')." WHERE typeid = :id";
		$params = array(':id'=>$li['fid']);
		$threadclass = pdo_fetch($sql,$params);
		$li['threadclass'] = $threadclass['name'];
		$li['title'] = $li['title']?$li['title']:'已被删除';
		$li['href'] = $_W['siteroot'].'app/'.$this->createMobileUrl('forum_topic',array('id'=>$li['ttid']));
		$li['delete'] = $this->createWebUrl('index',array('doo'=>'begging','op'=>'list','fo'=>'delete','id'=>$li['id']));
		$list[] = $li;
	}
	
	$params = array(':uniacid'=>$_W['uniacid']);
	$total = pdo_fetchcolumn(
			'SELECT COUNT(*) FROM ' . tablename('meepo_bbs_topics') . " WHERE uniacid = :uniacid {$condition} ", $params);
	$pager = pagination($total, $pindex, $psize);
	
}

if($fo == 'delete'){
	$id = $_GPC['id'];
	if(is_array($id)){
		foreach ($id as $i){
			pdo_delete('meepo_bbs_topics',array('id'=>$i));
		}
	}else{
		pdo_delete('meepo_bbs_topics',array('id'=>$id));
	}
	message('操作成功',referer(),success);
}
