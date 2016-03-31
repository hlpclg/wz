<?php
        global $_W,$_GPC;
        $weid = $_W['uniacid'];
		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
	    }else{
		    $url=$this->createMobileUrl('Errorjoin');			
			header("location:$url");
			exit;
													
		}
		$settings = pdo_fetch("SELECT * FROM ".tablename('meepo_hongniangset')." WHERE weid=:weid",array(':weid'=>$weid));
		$openid2 = $_W['openid'];
		$cfg = $this->module['config'];
        $openid = $_GPC['openid'];
		$res2 = $this->getusers($weid,$openid);
		if(empty($res2['telephone'])){
		    message("该会员还未完善个人资料！",referer());
		}
		 $photoss = $this->getphotos($openid);//取得所有照片
		 if(count($photoss)>8){
		   $photos = array($photoss[0],$photoss[1],$photoss[2],$photoss[3],$photoss[4],$photoss[5],$photoss[6],$photoss[7],$photoss[8]);
		}else{
		   $photos = $photoss;
		}
        if (empty($openid)) {
            message("参数错误，请重新从微信进入！");
        } 
        include $this->template('gerenshow');