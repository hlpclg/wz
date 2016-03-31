<?php
/**
 * 微小区模块
 *
 * [晓锋] Copyright (c) 2013 qfinfo.cn
 */
/**
 * 后台菜单设置
 */
defined('IN_IA') or exit('Access Denied');
	global $_W,$_GPC;
	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
	$navs = pdo_fetchAll("SELECT * FROM".tablename('xcommunity_nav')."WHERE uniacid= '{$_W['uniacid']}'");
	if(empty($navs)){
		$data1 =array('displayorder' => 0,'pcate' => 0 ,'title' => '物业服务','url' => '','status' => 1,'uniacid' => $_W['uniacid']);
		$data2 =array('displayorder' => 0,'pcate' => 0 ,'title' => '小区互动','url' => '','status' => 1,'uniacid' => $_W['uniacid']);
		$data3 =array('displayorder' => 0,'pcate' => 0 ,'title' => '生活服务','url' => '','status' => 1,'uniacid' => $_W['uniacid']);
		if ($data1) {
			pdo_insert('xcommunity_nav',$data1);
			$nid1 = pdo_insertid();
			$menu1 = array(
					array('displayorder' => 0,'pcate' => $nid1,'title' => '物业介绍','icon' => 'glyphicon glyphicon-user','bgcolor' => '#ffb81c','url' => $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=property&m=xfeng_community','status' => 1,'uniacid' => $_W['uniacid']),
					array('displayorder' => 0,'pcate' => $nid1,'title' => '公告信息','icon' => 'glyphicon glyphicon-bullhorn','bgcolor' => '#95bd38','url' => $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=announcement&m=xfeng_community','status' => 1,'uniacid' => $_W['uniacid']),
					array('displayorder' => 0,'pcate' => $nid1,'title' => '小区报修','icon' => 'glyphicon glyphicon-wrench','bgcolor' => '#3c87c8','url' => $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=repair&m=xfeng_community','status' => 1,'uniacid' => $_W['uniacid']),
					array('displayorder' => 0,'pcate' => $nid1,'title' => '投诉建议','icon' => 'fa fa-legal','bgcolor' => '#dd4b2b','url' => $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=report&m=xfeng_community','status' => 1,'uniacid' => $_W['uniacid']),
					array('displayorder' => 0,'pcate' => $nid1,'title' => '便民号码','icon' => 'glyphicon glyphicon-earphone','bgcolor' => '#ab5e90','url' =>$_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=phone&m=xfeng_community','status' => 1,'uniacid' => $_W['uniacid']),
					array('displayorder' => 0,'pcate' => $nid1,'title' => '查物业费','icon' => 'fa fa-money','bgcolor' => '#660000','url' => $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=propertyfree&m=xfeng_community','status' => 1,'uniacid' => $_W['uniacid']),
			);
			foreach ($menu1 as $key => $value1) {
				pdo_insert('xcommunity_nav',$value1);
			}
		}
		if ($data2) {
			pdo_insert('xcommunity_nav',$data2);
			$nid2 = pdo_insertid();
			$menu2 = array(
					array('displayorder' => 0,'pcate' => $nid2,'title' => '小区活动','icon' => 'glyphicon glyphicon-tasks','bgcolor' => '#65944e','url' => $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=activity&m=xfeng_community','status' => 1,'uniacid' => $_W['uniacid']),
					array('displayorder' => 0,'pcate' => $nid2,'title' => '家政服务','icon' => 'glyphicon glyphicon-leaf','bgcolor' => '#95bd38','url' => $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=homemaking&m=xfeng_community','status' => 1,'uniacid' => $_W['uniacid']),
					array('displayorder' => 0,'pcate' => $nid2,'title' => '房屋租赁','icon' => 'fa fa-info','bgcolor' => '#38bfc8','url' => $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=houselease&m=xfeng_community','status' => 1,'uniacid' => $_W['uniacid']),
					array('displayorder' => 0,'pcate' => $nid2,'title' => '常用查询','icon' => 'glyphicon glyphicon-search','bgcolor' => '#ec9510','url' => $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=search&m=xfeng_community','status' => 1,'uniacid' => $_W['uniacid']),
					array('displayorder' => 0,'pcate' => $nid2,'title' => '二手市场','icon' => 'fa fa-exchange','bgcolor' => '#666699','url' => $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=fled&m=xfeng_community','status' => 1,'uniacid' => $_W['uniacid']),
					array('displayorder' => 0,'pcate' => $nid2,'title' => '小区拼车','icon' => 'fa fa-truck','bgcolor' => '#7f6000','url' => $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=car&m=xfeng_community','status' => 1,'uniacid' => $_W['uniacid']),
			);
			foreach ($menu2 as $key => $value2) {
				pdo_insert('xcommunity_nav',$value2);
			}
		}		
		if ($data3) {
			pdo_insert('xcommunity_nav',$data3);
			$nid3 = pdo_insertid();
			$menu3 = array(
					array('displayorder' => 0,'pcate' => $nid3,'title' => '小区商家','icon' => 'glyphicon glyphicon-shopping-cart','bgcolor' => '#65944e','url' => $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=business&op=app&m=xfeng_community','status' => 1,'uniacid' => $_W['uniacid']),
					array('displayorder' => 0,'pcate' => $nid3,'title' => '小区超市','icon' => 'glyphicon glyphicon-shopping-cart','bgcolor' => '#65944e','url' => $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&do=shopping&op=list&m=xfeng_community','status' => 1,'uniacid' => $_W['uniacid']),

			);
			foreach ($menu3 as $key => $value3) {
				pdo_insert('xcommunity_nav',$value3);
			}
		}	
	}

	$pindex = max(1, intval($_GPC['page']));
	$psize  = 10;
	$list = pdo_fetchall("SELECT * FROM".tablename('xcommunity_nav')."WHERE  uniacid='{$_W['uniacid']}' AND pcate = 0 order by displayorder asc LIMIT ".($pindex - 1) * $psize.','.$psize);
	$children = array();
	foreach ($list as $key => $value) {
		$sql  = "select *from".tablename("xcommunity_nav")."where uniacid='{$_W['uniacid']}' and  pcate='{$value['id']}' order by displayorder asc";
		$li = pdo_fetchall($sql);

		$children[$value['id']] = $li;
	}
	//print_r($children);
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_nav')."WHERE uniacid='{$_W['uniacid']}' AND pcate = 0 ");
	$pager  = pagination($total, $pindex, $psize);

	// AJAX是否显示
	if($_W['isajax'] && $_GPC['id']){
		$data = array();
		$data['status'] = intval($_GPC['status']);
		if(pdo_update('xcommunity_nav', $data, array('id' => $_GPC['id'])) !== false) {
				exit('success');
		}
		
	}
	if ($op == 'post') {
		if ($_GPC['id']) {
			$category = pdo_fetch("SELECT * FROM".tablename('xcommunity_nav')."WHERE id=:id",array(":id" => $_GPC['id']));
		}
		if ($_W['ispost']) {
			$insert = array(
				'uniacid' => $_W['uniacid'],
				'displayorder' => $_GPC['displayorder'],
				'title' => $_GPC['title'],
				'url' => $_GPC['url'], 
				'status' => 1,
				'icon' => $_GPC['icon'],
				'bgcolor' => $_GPC['bgcolor'],
			);
			if ($_GPC['id']) {
				$insert['pcate'] = $category['pcate'];
				pdo_update('xcommunity_nav',$insert,array('id' => $_GPC['id']));
			}else{
				$insert['pcate'] = $_GPC['pcate'];
				pdo_insert('xcommunity_nav',$insert);
			}
			message('操作成功',referer(),'success');
		}
	}elseif ($op == 'display') {
		//print_r($_GPC['displayorder']);exit();
		if (!empty($_GPC['displayorder'])) {
			foreach ($_GPC['displayorder'] as $id => $displayorder) {
				pdo_update('xcommunity_nav', array('displayorder' => $displayorder), array('id' => $id));
			}
			message('排序更新成功！', 'refresh', 'success');
		}
	}elseif ($op == 'style') {

		$data = array(
				'uniacid' => $_W['uniacid'],
				'styleid' => $_GPC['styleid'],
			);
		$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
		if ($_W['ispost']) {
				if (empty($item)) {
					pdo_insert('xcommunity_template',$data);
				}else{
					$row = pdo_query("UPDATE ".tablename('xcommunity_template')."SET styleid = '{$_GPC['styleid']}' WHERE uniacid='{$_W['uniacid']}'");
					if ($row) {
						message('操作成功',refresh,'success');
					}
				}
		}


	}

	include $this->template('category');








