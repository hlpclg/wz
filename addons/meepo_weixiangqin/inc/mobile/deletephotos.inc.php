<?php
        global $_W,$_GPC;
        $weid = $_W['weid'];
		$openid = $_W['openid'];
		if(empty($openid) || empty($_GPC['id'])){
		   message('请重新从微信进入');
		}else{
			$tablename = tablename("meepohongniangphotos");
			$sql = 'SELECT * FROM ' . $tablename . ' WHERE from_user=:from_user AND weid=:weid';
			$arr = array(
				":from_user" => $openid,
				":weid" => $weid,
			);
			$res = pdo_fetch($sql, $arr);
			  load()->func('file');
			  file_delete($res['url']);
			  pdo_delete('meepohongniangphotos',array('weid'=>$weid,'id'=>$_GPC['id']));
			  message('删除成功',$this->createMobileUrl('myphotos'),'sucess');
		}