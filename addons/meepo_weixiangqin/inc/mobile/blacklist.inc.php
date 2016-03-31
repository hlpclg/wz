<?php
global $_W,$_GPC;
        $weid = $_W['weid'];
		$settings = pdo_fetch("SELECT * FROM ".tablename('meepo_hongniangset')." WHERE weid=:weid",array(':weid'=>$_W['weid']));
        $openid = $_W['openid'];
        $to = $_GPC['toname'];
        $toopenid = $_GPC['toopenid'];
        if (empty($openid)) {
            message("登录身份无效，请重新从微信进入！");
        }
			$sql = "SELECT * FROM ".tablename('hnblacklist')." WHERE wantblack = :wantblack  AND weid=:weid";
			$paras = array(':wantblack'=>$openid,':weid'=>$weid);
			$result = pdo_fetchall($sql,$paras);
			if(!empty($result) && is_array($result)){
				foreach($result as $row){
				   $sql2 = "SELECT * FROM ".tablename('hnfans')." WHERE from_user=:from_user   AND weid=:weid";
				   $itsblack[] = pdo_fetch($sql2,array(':from_user'=>$row['blackwho'],':weid'=>$weid));
				   
				}
			}
			unset($result);
            include $this->template('blacklist');