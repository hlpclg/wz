<?php
global $_W,$_GPC;
include_once INC_PATH.'web/setting/navs.php';
$op = !empty($_GPC['op'])?trim($_GPC['op']):'base';

if($op == 'base'){
	$panel_heading = '基本设置';
	$setting = pdo_fetch("SELECT * FROM ".tablename('meepo_pai_set')." WHERE uniacid='{$_W['uniacid']}'");
	if($_W['ispost']){
		$date = array(
			'title'=>$_GPC['title'],
			'chongfu'=>intval($_GPC['chongfu']),
			'share_url'=>$_GPC['share_url'],
			'bao_name'=>$_GPC['bao_name'],
			'shen_name'=>$_GPC['shen_name'],
			'uniacid'=>$_W['uniacid'],
			'zhuban_url'=>$_GPC['zhuban_url'],
			'zhuban_title'=>$_GPC['zhuban_title'],
			'tuandui_url'=>$_GPC['tuandui_url'],
			'tuandui_title'=>$_GPC['tuandui_title'],
			'zanzhu_url'=>$_GPC['zanzhu_url'],
			'zanzhu_title'=>$_GPC['zanzhu_title'],	
		);
		if(!empty($setting)){
			unset($date['uniacid']);
			pdo_update('meepo_pai_set',$date,array('uniacid'=>$_W['uniacid']));
		}else{
			pdo_insert('meepo_pai_set',$date);
		}
		message('提交成功',referer(),'success');
	}
	if(empty($setting)){
		$setting['bao_name']='请输入学校名称';
		$setting['bao_name']='请输入昵称';
		$setting['title'] = '【创业联盟】-全民自拍，正在进行时！美丽就要秀出来';
		$setting['share_url'] = 'http://mp.weixin.qq.com/s?__biz=MzA4OTg3ODMzOQ==&mid=200863468&idx=1&sn=74366e1a965dbf1598258ed20ff0c374#rd';
		$setting['zhuban_url'] = 'http://www.meepo.com.cn';
		$setting['zhuban_title'] = 'Meepo创业联盟';
		$setting['tuandui_url'] = 'http://www.meepo.com.cn';
		$setting['tuandui_title'] = 'Meepo创业联盟';
		$setting['zanzhu_url'] = 'http://www.meepo.com.cn';
		$setting['zanzhu_title'] = 'Meepo创业联盟';
	}
	include $this->template('setting_base');
}

if($op == 'share'){
	$panel_heading = '分享设置';
	
	
	include $this->template('setting_share');
}

if($op == 'affiliate'){
	$panel_heading = '与分销系统融合设置';
	
	
	include $this->template('setting_affiliate');
}