<?php
        global $_W,$_GPC;
        $weid = $_W['uniacid'];
		$settings = pdo_fetch("SELECT * FROM ".tablename('meepo_hongniangset')." WHERE weid=:weid",array(':weid'=>$_W['weid']));
        $openid = $_W['openid'];	
        $to = $_GPC['toname'];
        $toopenid = $_GPC['toopenid'];
        if (empty($openid)) {
            message("登录身份无效，请重新从微信进入！");
        }
        if (empty($to)) {
            message("参数错误，请重新从微信进入！");
        } else {
            if ($openid == $toopenid) {
                message("自己不能拉黑自己哦！", $this->createMobileUrl('alllist') , 'error');
            }
			$sql = "SELECT * FROM ".tablename('hnblacklist')." WHERE wantblack = :wantblack AND blackwho = :blackwho AND weid=:weid";
			$paras = array(':wantblack'=>$openid,':blackwho'=>$toopenid,':weid'=>$weid);
			$result = pdo_fetch($sql,$paras);
			if(empty($result)){
				$data = array('wantblack'=>$openid,'blackwho'=>$toopenid,'time'=>time(),'weid'=>$weid);
			    pdo_insert('hnblacklist',$data);
				message('你已经成功将'.$to.'拉入黑名单！',$this->createMobileUrl('mynews'),'sucess');
			}else{
			    pdo_delete('hnblacklist',array('wantblack'=>$openid,'blackwho'=>$toopenid,'weid'=>$weid));
				message('你已经取消将'.$to.'拉入黑名单！',$this->createMobileUrl('mynews'),'sucess');
			}  
        }