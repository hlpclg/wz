<?php
/**
 * 微小区模块
 *
 * [晓锋] Copyright (c) 2013 qfinfo.cn
 */
/**
 * 后台小区公告信息
 */
defined('IN_IA') or exit('Access Denied');
	global $_GPC,$_W;
	$regionid = $_GPC['regionid'];
	$op = !empty($_GPC['op'])?$_GPC['op']:'display';
	$id = intval($_GPC['id']);
	if($op == 'display'){
		//公告搜索
		$condition = '';
		if (!empty($_GPC['title'])) {
			$condition .= " AND title LIKE '%{$_GPC['title']}%'";
		}
		//管理公告reason
		$pindex = max(1, intval($_GPC['page']));
		$psize  = 10;
		$sql    = "select * from ".tablename("xcommunity_announcement")."where regionid='{$regionid}' $condition and weid = {$_W['weid']} LIMIT ".($pindex - 1) * $psize.','.$psize;
		
		$list   = pdo_fetchall($sql);
		$total  = pdo_fetchcolumn('select count(*) from'.tablename("xcommunity_announcement")."where  regionid=:regionid  $condition and weid = {$_W['weid']}",array(':regionid' => $regionid));
		$pager  = pagination($total, $pindex, $psize);
	}
	if($op == 'post'){
		if(!empty($id)){
			$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_announcement')."WHERE id=:id",array(':id' =>$id));
		}
		//添加公告
		if(checksubmit('submit')){
			
			$insert = array(
					'weid'       => $_W['uniacid'],
					'regionid'   =>$regionid,
					'title'      =>$_GPC['title'],
					'createtime' =>$_W['timestamp'],
					'status'     =>$_GPC['status'],
					'enable'     =>$_GPC['enable'],
					'datetime'   =>$_GPC['datetime'],
					'location'   =>$_GPC['location'],
					'reason'     =>$_GPC['reason'],
					'remark'     =>$_GPC['remark'],
				);
			if(empty($id)){
				pdo_insert("xcommunity_announcement",$insert);
				$id = pdo_insertid();
			}else{

				pdo_update("xcommunity_announcement",$insert,array('id'=>$id));
			}
			//是否启用模板消息
			if ($_GPC['status'] == 2) {

				load()->classs('weixin.account');
				load()->func('communication');
				$obj = new WeiXinAccount();
				$access_token = $obj->fetch_available_token();
				$templates =pdo_fetch("SELECT * FROM".tablename('xcommunity_notice_setting')."WHERE uniacid='{$_W['uniacid']}'");
				$key = 'template_id_'.$_GPC['enable'];
				$template_id = $templates[$key];
				$openids = pdo_fetchall("SELECT openid FROM".tablename('xcommunity_member')."WHERE weid='{$_W['uniacid']}' AND regionid='{$regionid}'");
				$url = $_W['siteroot']."app/index.php?i={$_W['uniacid']}&c=entry&id={$id}&op=detail&do=announcement&m=xfeng_community";
				foreach ($openids as $key => $value) {
					$data = array(
							'touser' => $value['openid'],
							'template_id' => $template_id,
							'url' => $url,
							'topcolor' => "#FF0000",
							'data' => array(
									'first' => array(
											'value' => $_GPC['title'],
										),
									'time' => array(
											'value' => $_GPC['datetime'],
										),
									'location'	=> array(
											'value' => $_GPC['location'],
										),
									'reason'    => array(
											'value' => $_GPC['reason'],
										),
									'remark'    => array(
											'value' => $_GPC['remark'],
										),	
								)
						);
					$json = json_encode($data);
					$url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token;
					$ret = ihttp_post($url,$json);
				}
			}
			message('提交成功',referer(), 'success');
		}
	}
	if($op == 'delete'){
		//删除公告
		pdo_delete("xcommunity_announcement",array('id'=>$id));
		message('删除成功',referer(), 'success');
	}
	if ($op == 'setting') {
		if ($_GPC['regionid']) {
			$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_notice_setting')."WHERE regionid =:regionid",array(":regionid" => $_GPC['regionid']));
		}
		//print_r($item);exit();
		$data = array(
				'uniacid' => $_W['uniacid'],
				'regionid' => $_GPC['regionid'],
				'template_id_1' => $_GPC['template_id_1'],
				'template_id_2' => $_GPC['template_id_2'],
				'template_id_3' => $_GPC['template_id_3'],
				'template_id_4' => $_GPC['template_id_4'],
				'template_id_5' => $_GPC['template_id_5'],
				'template_id_6' => $_GPC['template_id_6'],
			);
		if ($_W['ispost']) {
			if ($id) {
				pdo_update('xcommunity_notice_setting',$data,array('id' => $id));
			}else{
				pdo_insert('xcommunity_notice_setting',$data);
			}
			message('提交成功',referer(),'success');
		}
		
		
		include $this->template('notice_setting');exit();
	}

















	include $this->template('announcement');