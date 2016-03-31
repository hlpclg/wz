<?php
        global $_W,$_GPC;
		$cfg = $this->module['config'];
        $weid = $_W['uniacid'];
		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
		if(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false ){
			 $url=$this->createMobileUrl('Errorjoin');			
				header("location:$url");
				exit;
		}
		if(strpos($useragent, 'WindowsWechat')){
		    $url=$this->createMobileUrl('Errorjoin');			
				header("location:$url");
				exit;
		}
		$settings = pdo_fetch("SELECT * FROM ".tablename('meepo_hongniangset')." WHERE weid=:weid",array(':weid'=>$_W['weid']));
        $openid = $_W['openid'];
		if(empty($openid)){
		   $openid = $_GPC['openid'];
		}
        $to = $_GPC['toname'];
        $toopenid = $_GPC['toopenid'];
        if (empty($openid)) {
            message("登录身份无效，请重新从微信进入！");
        }
        if (empty($to)) {
            message("参数错误，请重新从微信进入！");
        } else {
          
            if ($openid == $toopenid) {
                message("自己不能和自己聊天哦！", $this->createMobileUrl('alllist') , 'error');
            }

			
			$res2 = $this->getusers($weid,$openid);
			if(empty($res2['Descrip'])){
			     message('请先完善资料！',$this->createMobileUrl('userinfo'),'info');
			}
		    $result = pdo_fetchcolumn("SELECT id FROM ".tablename('hnblacklist')." WHERE wantblack = :wantblack AND blackwho = :blackwho AND weid=:weid",array(':wantblack'=>$openid,':blackwho'=>$toopenid,':weid'=>$weid));
			if(preg_match('/http:(.*)/',$res2['avatar'])){
				$avatar =  $res2['avatar'];
			}elseif(preg_match('/images(.*)/',$res2['avatar'])){
				$avatar = $_W['attachurl'].$res2['avatar'];
			}else{   
				 $avatar = MEEPORES."/static/friend/images/cdhn80.jpg";
			}
			$member['nickname'] = $res2['nickname'];
			$geter = $toopenid;
			$sender = $openid;
            include $this->template('chat2');
        }