<?php
        global $_W, $_GPC;
		$this->checkAuth();
		//$id = intval($_GPC['orderid']);
		$openid = $_W['openid'];
		$weid = $_W['uniacid'];
		$settings = pdo_fetch("SELECT * FROM ".tablename('meepo_hongniangset')." WHERE weid=:weid",array(':weid'=>$weid));
		$cfg = $this->module['config'];
	    $sql = "SELECT * FROM ".tablename('meepohn_baoyue')." WHERE weid=:weid AND openid=:openid ORDER BY time DESC";
		$paras = array(":openid"=>$openid,":weid"=>$weid);
		$res = pdo_fetchall($sql,$paras);
		include $this->template('baoyueres');