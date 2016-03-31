<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;
$op = $_GPC['op'];

//配置分类 样板

//导入计划任务


if($op == 'nav'){
	//配置首页导航
	$navs = array();
	$navs['forum'] = array();
	$navs['forum']['uniacid'] = $_W['uniacid'];
	$navs['forum']['name'] = '首页';
	$navs['forum']['icon'] = $_W['siteroot'].'/addons/meepo_bbs/template/mobile/default/img/home.png';
	$navs['forum']['link'] = $this->createMobileUrl('forum');
	$navs['forum']['time'] = time();
	$navs['forum']['displayorder'] = 5;
	$navs['forum']['enabled'] = 1;
	
	$navs['home'] = array();
	$navs['home']['uniacid'] = $_W['uniacid'];
	$navs['home']['name'] = '个人中心';
	$navs['home']['icon'] = $_W['siteroot'].'/addons/meepo_bbs/template/mobile/default/img/avatar.png';
	$navs['home']['link'] = $this->createMobileUrl('home');
	$navs['home']['time'] = time();
	$navs['home']['displayorder'] = 4;
	$navs['home']['enabled'] = 1;
	
	$navs['activity_coupon'] = array();
	$navs['activity_coupon']['uniacid'] = $_W['uniacid'];
	$navs['activity_coupon']['name'] = '积分兑换';
	$navs['activity_coupon']['icon'] = $_W['siteroot'].'/addons/meepo_bbs/template/mobile/default/img/2.png';
	$navs['activity_coupon']['link'] = $this->createMobileUrl('activity_coupon');
	$navs['activity_coupon']['time'] = time();
	$navs['activity_coupon']['displayorder'] = 3;
	$navs['activity_coupon']['enabled'] = 1;
	
	$navs['task'] = array();
	$navs['task']['uniacid'] = $_W['uniacid'];
	$navs['task']['name'] = '任务大厅';
	$navs['task']['icon'] = $_W['siteroot'].'/addons/meepo_bbs/template/mobile/default/img/13.png';
	$navs['task']['link'] = $this->createMobileUrl('task');
	$navs['task']['time'] = time();
	$navs['task']['displayorder'] = 2;
	$navs['task']['enabled'] = 1;
	
	$navs['forum_cat'] = array();
	$navs['forum_cat']['uniacid'] = $_W['uniacid'];
	$navs['forum_cat']['name'] = '版块分类';
	$navs['forum_cat']['icon'] = $_W['siteroot'].'/addons/meepo_bbs/template/mobile/default/img/1.png';
	$navs['forum_cat']['link'] = $this->createMobileUrl('forum_cat');
	$navs['forum_cat']['time'] = time();
	$navs['forum_cat']['displayorder'] = 1;
	$navs['forum_cat']['enabled'] = 1;
	
	pdo_delete('meepo_bbs_navs',array('uniacid'=>$_W['uniacid']));
	foreach ($navs as $nav){
		pdo_insert('meepo_bbs_navs',$nav);
	}
	
	message('配置成功',$this->createWebUrl('nav'),success);
}


//配置系统设置

message('配置成功',$this->createWebUrl('nav'),success);