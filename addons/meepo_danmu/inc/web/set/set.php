<?php
global $_W,$_GPC;
include_once INC_PATH.'web/set/navs.php';
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'base';


if($op == 'base'){
	load()->func('tpl');
	$panel_heading = '基本设置';
	
	$settings = pdo_fetch("SELECT * FROM".tablename('meepo_danmu_set')." WHERE uniacid='{$_W['uniacid']}'");
	if($_W['ispost']){
		$data = array(
			'share_title'=>$_GPC['share_title'],
			'share_content'=>$_GPC['share_content'],
			'share_img'=>$_GPC['share_logo'],
			'title'=>$_GPC['title'],
			'logo'=>$_GPC['logo'],
			'wx_name'=>$_GPC['wx_name'],
			'wx_num'=>$_GPC['wx_num'],
			'uniacid'=>$_W['uniacid'],
		);
		
		if(!$settings){
			pdo_insert('meepo_danmu_set',$data);
		}else{
			unset($data['uniacid']);
			pdo_update('meepo_danmu_set',$data,array('uniacid'=>$_W['uniacid']));
		}
		message('提交成功',referer(),'success');
	}
	if(!$settings){
		$settings = array(
			'share_title'=>'联盟弹幕，丰富展现精彩内容',
			'share_content'=>'联盟弹幕，丰富展现精彩内容',
			'share_img'=>'',
			'title'=>'联盟弹幕，丰富展现精彩内容',
			'logo'=>'',
			'wx_name'=>'Meepo创业联盟',
			'wx_num'=>'imeepos',
			'uniacid'=>$_GPC['uniacid'],
		);
	}
	include $this->template('settings');
}