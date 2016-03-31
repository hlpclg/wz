<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;

$foo = $_GPC['foo'];
$url = $this->createWebUrl('threadclass');
$params = array(':uniacid'=>$_W['uniacid'],':fid'=>0);
$sql = "SELECT * FROM ".tablename('meepo_bbs_threadclass')." WHERE uniacid = :uniacid AND fid = :fid ORDER BY displayorder DESC";
$lists = pdo_fetchall($sql,$params);
foreach ($lists as $li) {
	$li['icon'] = tomedia($li['icon']);
	$cats[] = $li;
}
$urls = array(
		array(
				'url'=>array(
						array('url'=>'./index.php?c=activity&a=coupon&','title'=>'折扣券兑换','icon'=>'fa fa-bars'),
						array('url'=>'./index.php?c=activity&a=token&','title'=>'代金券兑换','icon'=>'fa fa-bars'),
						array('url'=>'./index.php?c=activity&a=goods&','title'=>'实体物品兑换','icon'=>'fa fa-bars'),
				),
				'head'=>' 兑换管理',
				'icon'=>'fa fa-plane'
		),

		array(
				'url'=>array(
						array('url'=>'./index.php?c=activity&a=coupon&do=post&','title'=>'添加折扣券','icon'=>'fa fa-plus-square-o'),
						array('url'=>'./index.php?c=activity&a=token&do=post&','title'=>'添加代金券','icon'=>'fa fa-plus-square-o'),
						array('url'=>'./index.php?c=activity&a=goods&do=post&','title'=>'添加实体物品','icon'=>'fa fa-plus-square-o'),
				),
				'head'=>' 添加兑换管理',
				'icon'=>'fa fa-plane'
		),
		array(
				'url'=>array(
						array('url'=>'./index.php?c=activity&a=coupon&do=record&','title'=>'折扣券记录','icon'=>'fa fa-book'),
						array('url'=>'./index.php?c=activity&a=token&do=record&','title'=>'代金券记录','icon'=>'fa fa-book'),
						array('url'=>'./index.php?c=activity&a=goods&do=record&','title'=>'实体物品记录','icon'=>'fa fa-book'),
						array('url'=>'./index.php?c=activity&a=goods&do=deliver&','title'=>'实体发货记录','icon'=>'fa fa-book'),
				),
				'head'=>' 兑换记录管理',
				'icon'=>'fa fa-plane'
		),
		array(
				'url'=>array(
						array('url'=>$this->createWebUrl('adv'),'title'=>'广告管理','icon'=>'fa fa-cog'),
						array('url'=>$this->createWebUrl('threadclass'),'title'=>'板块管理','icon'=>'fa fa-cog'),
						array('url'=>$this->createWebUrl('set'),'title'=>'系统设置','icon'=>'fa fa-cog'),
						array('url'=>$this->createWebUrl('task'),'title'=>'任务大厅','icon'=>'fa fa-cog'),
						array('url'=>$this->createWebUrl('qiniu'),'title'=>'七牛云存储','icon'=>'fa fa-cog'),
				),
				'head'=>' 其他快捷操作',
				'icon'=>'fa fa-plane'
		),


);

if($foo == 'delete'){
	$id = $_GPC['id'];
	if(empty($id)){
		message('所选分类不存在或已删除',$url,'success');
	}
	
	pdo_delete('meepo_bbs_threadclass',array('typeid'=>$id));
	message('删除成功',$url,success);
}


if($foo == 'isgood'){
	$id = $_GPC['id'];
	pdo_update('meepo_bbs_threadclass',array('isgood'=>1),array('typeid'=>$id));
	message('更新成功',$url,success);
}

if($foo == 'nogood'){
	$id = $_GPC['id'];
	pdo_update('meepo_bbs_threadclass',array('isgood'=>0),array('typeid'=>$id));
	message('更新成功',$url,success);
}
$ids = array();
if(empty($foo)){
	$sql = "SELECT * FROM ".tablename('meepo_bbs_threadclass')." WHERE uniacid = :uniacid AND fid = :fid";
	$params = array(':uniacid'=>$_W['uniacid'],':fid'=>0);
	
	$dsss = pdo_fetchall($sql,$params); 
	foreach ($dsss as $d){
		$sql = "SELECT * FROM ".tablename('meepo_bbs_threadclass')." WHERE uniacid = :uniacid AND fid = :fid";
		$params = array(':uniacid'=>$_W['uniacid'],':fid'=>$d['typeid']);
		
		$url = $this->createMobileUrl('forum', array('id' => $d['typeid']));
		$d['surl'] = $url;
		$url = substr($url, 2);
		$url = $_W['siteroot'] . 'app/' . $url;
		$d['url'] = $url;
		
		$ds = pdo_fetchall($sql,$params);
		foreach ($ds as $dss){
			
			$url = $this->createMobileUrl('forum', array('id' => $dss['typeid']));
			$dss['surl'] = $url;
			$url = substr($url, 2);
			$url = $_W['siteroot'] . 'app/' . $url;
			$dss['url'] = $url;
			$ids[] = $dss['typeid'];
			$d['ch'][] = $dss;
		}
		$list[] = $d;
		$ids[] = $d['typeid'];
	}
	if($_GPC['th'] == 'clearall'){
		$in = db_create_not_in($ids,'typeid');
		$sql = "DELETE FROM ".tablename('meepo_bbs_threadclass')." WHERE uniacid = '{$_W['uniacid']}' AND $in ";
		pdo_query($sql);
	}
	
	include $this->template('threadclass');
}

if($foo == 'create'){
	$id = $_GPC['id'];
	$fid = $_GPC['fid'];
	$sql = "SELECT * FROM ".tablename('meepo_bbs_threadclass')." WHERE typeid = :typeid";
	$params = array(':typeid'=>$id);
	$setting = pdo_fetch($sql,$params);
	$setting['look_group'] = unserialize($setting['look_group']);
	$setting['post_group'] = unserialize($setting['post_group']);
	
	if(!empty($fid)){
		$setting['fid'] = $fid;
	}
	
	$group = pdo_fetchall('SELECT groupid,title FROM ' . tablename('mc_groups') . " WHERE uniacid = '{$_W['uniacid']}' ");
	
	$sql = "SELECT * FROM ".tablename('meepo_bbs_threadclass')." WHERE typeid = :typeid";
	$params = array(':typeid'=>$fid);
	$class = pdo_fetch($sql,$params);
	
	if($_W['ispost']){
		$data = array();
		$data['fid'] = $_GPC['fid'];
		$data['name'] = $_GPC['name'];
		$data['displayorder'] = $_GPC['displayorder'];
		$data['icon'] = $_GPC['icon'];
		$data['content'] = trim($_GPC['content']);
		$data['look_group'] = serialize($_GPC['look_group']);
		$data['post_group'] = serialize($_GPC['post_group']);
		
		if (!empty($id)) {
			pdo_update('meepo_bbs_threadclass', $data, array('typeid'=>$id));
		} else {
			$data['uniacid'] = $_W['uniacid'];
			pdo_insert('meepo_bbs_threadclass', $data);
		}
		message('提交成功',$url,'success');
	}
	load()->func('tpl');
	include $this->template('threadclass_post');
}

function db_create_not_in($item_list, $field_name = '') {
	if (empty($item_list)) {
		return $field_name . " NOT IN ('') ";
	} else {
		if (!is_array($item_list)) {
			$item_list = explode(',', $item_list);
		}
		$item_list = array_unique($item_list);
		$item_list_tmp = '';
		foreach ($item_list AS $item) {
			if ($item !== '') {
				$item_list_tmp.= $item_list_tmp ? ",'$item'" : "'$item'";
			}
		}
		if (empty($item_list_tmp)) {
			return $field_name . " NOT IN ('') ";
		} else {
			return $field_name . ' NOT IN (' . $item_list_tmp . ') ';
		}
	}
}