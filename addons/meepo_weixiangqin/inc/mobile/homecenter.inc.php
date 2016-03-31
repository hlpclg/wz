<?php
global $_W,$_GPC;
        $weid = $_W['uniacid'];
		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {	
	    }else{
		    $url=$this->createMobileUrl('Errorjoin');			
			header("location:$url");
			exit;										
		}	
		$settings = pdo_fetch("SELECT * FROM ".tablename('meepo_hongniangset')." WHERE weid=:weid",array(':weid'=>$_W['weid']));
		$openid = $_W['openid'];
        load()->model('mc');
		$cfg = $this->module['config'];
		if (!empty($_W['member']['uid'])) {
			$member = mc_fetch($_W['member']['uid']);	
		}else{
		   die('请重新从微信进入！');
		}
        $res = $this->getusers($weid,$openid);
        include $this->template('gerenhome');