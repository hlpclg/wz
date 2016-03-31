<?php /*折翼天使资源社区 www.zheyitianshi.com*/
//乞讨活动后台管理
global $_W,$_GPC;
load()->model('mc');
$fo = $_GPC['fo'];
if($_W['ispost']){
	if(!empty($_GPC['delete'])){
		$select = $_GPC['select'];
		foreach ($select as $key) {
			pdo_delete('meepo_bbs_begging',array('id'=>$key));
		}
		message('删除数据成功',referer(),success);
	}
}

if(empty($fo)){
	$typeid = $_GPC['typeid'];
	
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$sql = "SELECT * FROM ".tablename('meepo_bbs_begging')."  WHERE uniacid = :uniacid {$condition} ORDER BY time DESC ". "LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
	$params = array(':uniacid'=>$_W['uniacid']);
	$lists = pdo_fetchall($sql,$params);
	
	foreach ($lists as $li) {
		$li['time'] = date('Y-m-d',$li['time']);
		$user = mc_fetch($li['uid'],array('avatar','nickname'));
		$li['bavatar'] = tomedia($user['avatar']);
		$li['bnickname'] = $user['nickname'];
		$fuid = mc_openid2uid($li['fopenid']);
		$fuser = mc_fetch($fuid,array('nickname','avatar'));
		$li['avatar'] = tomedia($fuser['avatar']);
		$li['nickname'] = $fuser['nickname'];
		$topic = getTopicById($li['ttid']);
		$li['title'] = $topic['title']?$topic['title']:'已被删除';
		$li['href'] = $_W['siteroot'].'app/'.$this->createMobileUrl('forum_topic',array('id'=>$li['ttid']));
		$li['delete'] = $this->createWebUrl('index',array('doo'=>'begging','op'=>'manage','fo'=>'delete','id'=>$li['id']));
		$list[] = $li;
	}
	
	$params = array(':uniacid'=>$_W['uniacid']);
	$total = pdo_fetchcolumn(
			'SELECT COUNT(*) FROM ' . tablename('meepo_bbs_begging') . " WHERE uniacid = :uniacid {$condition} ", $params);
	$pager = pagination($total, $pindex, $psize);
	
}

if($fo == 'delete'){
	$id = $_GPC['id'];
	if(is_array($id)){
		foreach ($id as $i){
			pdo_delete('meepo_bbs_begging',array('id'=>$i));
		}
	}else{
		pdo_delete('meepo_bbs_begging',array('id'=>$id));
	}
	message('操作成功',referer(),success);
}
