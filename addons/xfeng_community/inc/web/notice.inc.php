<?php
/**
 * 微小区模块
 *
 * [晓锋] Copyright (c) 2013 qfinfo.cn
 */
/**
 * 后台小区通知设置
 */
defined('IN_IA') or exit('Access Denied');
	global $_GPC,$_W;
	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
	if ($op == 'display') {
		$list = pdo_fetchAll("SELECT * FROM".tablename('xcommunity_wechat_notice')."WHERE uniacid = '{$_W['uniacid']}' AND regionid='{$_GPC['regionid']}'");
	}elseif ($op == 'post') {
		if ($_W['ispost']) {
			$data = array(
					'uniacid' => $_W['uniacid'],
					'regionid' => $_GPC['regionid'],
					'fansopenid' => $_GPC['fansopenid'],
					'repair_status' => $_GPC['repair_status'],
					'report_status' => $_GPC['report_status'],
				);
			if ($_GPC['id']) {
				if(pdo_update('xcommunity_wechat_notice',$data,array('id' => $_GPC['id']))){
					message('提交成功',referer(),'success');
				}
			}else{
				if(pdo_insert('xcommunity_wechat_notice',$data)){
					message('提交成功',referer(),'success');
				}
			}
		}
	}elseif($op == 'delete'){
		if (pdo_delete('xcommunity_wechat_notice',array('id' => $_GPC['id']))) {
			$result = array(
					'status' => 1,
				);
			echo json_encode($result);exit();
		}
	}





	include $this->template('notice');