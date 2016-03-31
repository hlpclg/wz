<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;

$op =  $_GPC['op'];
$id = intval($_GPC['id']);

if(empty($op)){
	$sql = "SELECT * FROM ".tablename('meepo_bbs_share')." WHERE uniacid = :uniacid AND tid = :tid";
	$params = array(':uniacid'=>$_W['uniacid'],':tid'=>$id);
	$ds = pdo_fetch($sql,$params);
	$set = iunserializer($ds['set']);
	if(empty($set)){
		$set['post'] = '0';
		$set['reply'] = '0';
		$set['breply'] = '0';
		$set['goods'] = '0';
		$set['bgoods'] = '0';
		$set['share'] = '0';
		$set['bshare'] = '0';
		$set['profile'] = '0';
		$set['read'] = '0';
		$set['bread'] = '0';
		$set['top'] = '0';
		$set['jing'] = '0';
		$set['delete'] = '0';
	}
	
	if($_W['ispost']){
		$data = array();
		$data['uniacid'] = $_W['uniacid'];
		$data['tid'] = $id;
		$data['set'] = iserializer($_GPC['credit']);
		$data['createtime'] = time();
		
		if(empty($ds)){
			pdo_insert('meepo_bbs_share',$data);
		}else{
			pdo_update('meepo_bbs_share',$data,array('tid'=>$id,'uniacid'=>$_W['uniacid']));
		}
		
		message('更新成功',referer(),success);
	}
	include $this->template('share');
}

if($op == 'share_log'){
	$sql = "SELECT * FROM ".tablename('meepo_bbs_share_log')." WHERE uniacid = :uniacid AND tid = :tid";
	$params = array(':uniacid'=>$_W['uniacid'],':tid'=>$id);
	$ds = pdo_fetchall($sql,$params);
	include $this->template('share_log');
}