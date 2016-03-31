<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;
$op = $_GPC['op'];

/* if($op == 'goods' || empty($op)){
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	
	$sql = "SELECT * FROM ".tablename('meepo_bbs_credit_goods')." WHERE uniacid = :uniacid ORDER BY createtime DESC ". "LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
	$params = array(':uniacid'=>$_W['uniacid']);
	$lists = pdo_fetchall($sql,$params);
	
	foreach ($lists as $li){
		$li['edit'] = $this->createWebUrl('credit_goods',array('id'=>$li['id']));
		$li['delete'] = $this->createWebUrl('credit_goods',array('op'=>'delete','id'=>$li['id']));
		$li['yu'] = $this->createMobileUrl('credit_goods',array('id'=>$li['id']));
		$list[] = $li;
	}
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('meepo_bbs_credit_goods')." WHERE uniacid = :uniacid ", $params);
	$pager = pagination($total, $pindex, $psize);
	
	include $this->template('credit');
}
 */

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
		
		
);

include $this->template('credit_cat');
