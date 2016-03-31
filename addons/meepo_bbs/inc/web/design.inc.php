<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;
$urls = array(
		array(
				'url'=>array(
						array('url'=>$this->createWebUrl('nav'),'title'=>'社区首页导航菜单管理','icon'=>'fa fa-bars'),
						array('url'=>$this->createWebUrl('detail_footer'),'title'=>'帖子详情页底部菜单管理','icon'=>'fa fa-bars'),
				),
				'head'=>' 前台页面设计',
				'icon'=>'fa fa-plane'
		),
);
include $this->template('credit_cat');