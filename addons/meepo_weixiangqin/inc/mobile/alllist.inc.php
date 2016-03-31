<?php
        include_once(MODULE_ROOT.'/func.php');
        global $_W,$_GPC;
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
		//幻灯片
		$slide = pdo_fetchall("SELECT * FROM " . tablename('meepoweixiangqin_slide') . " WHERE weid = :weid AND status=1 ORDER BY displayorder DESC,id DESC LIMIT 6", array(':weid' => $weid));
		$sujinum = rand();
		$openid = $_W['openid'];

		/*$baoyue = pdo_fetch("SELECT * FROM ".tablename('meepohn_baoyue')." WHERE openid=:openid AND weid=:weid ORDER BY endtime DESC",array(':weid'=>$weid,':openid'=>$openid));
		var_dump($baoyue);*/
		$cfg = $this->module['config'];		
		$settings = pdo_fetch("SELECT * FROM ".tablename('meepo_hongniangset')." WHERE weid=:weid",array(':weid'=>$_W['weid']));
		$res = $this->getusers($weid,$openid);
		if (!empty($openid)) {
					$sql = 'SELECT `follow`,`openid`,`uid` FROM ' . tablename('mc_mapping_fans') . ' WHERE `uniacid`=:uniacid AND `openid`=:openid';
					$pars = array();
					$pars[':uniacid'] = $weid;
					$pars[':openid'] = $openid;
					$fan = pdo_fetch($sql, $pars);
			        
			        if($fan['follow'] != '1'){
					   $url =  empty($settings['url']) ? 'http://baidu.com' : $settings['url'];
				       header("location:$url");
				       exit;
					}else{	
						if(empty($res['nickname'])){
							 $this->insertit();//录入
						}
						$tablename = tablename("hnfans");
						$gender = pdo_fetchcolumn("SELECT gender FROM ".  $tablename ." WHERE  weid=:weid AND from_user = :from_user",array(':weid'=>$weid,':from_user'=>$openid));
						$isshow =1;
						$tuijiannum = empty($cfg['tuijiannum']) ? 10 : intval($cfg['tuijiannum']);
						if($gender=='2'){
								$tuijian = pdo_fetchcolumn("SELECT count(*)  FROM " . $tablename . " WHERE  weid='{$weid}' AND nickname!='' AND isshow='{$isshow}'  AND yingcang='1' AND gender='1' AND tuijian='2' "); 
								if($tuijian < $tuijiannum){
									$list1 = pdo_fetchall("SELECT * FROM " . $tablename . " WHERE  weid='{$weid}' AND nickname!='' AND isshow='{$isshow}'  AND yingcang='1' AND gender='1' AND tuijian='2' ORDER BY love DESC,time DESC");
									$NUM = $tuijiannum - $tuijian;
									$list2 = pdo_fetchall("SELECT * FROM " . $tablename . " WHERE  weid='{$weid}' AND nickname!='' AND isshow='{$isshow}'  AND yingcang='1' AND gender='1' AND tuijian='1' ORDER BY RAND()  LIMIT ".$NUM."");
									$list = array_merge_recursive($list1,$list2);
								}else{
									$list = pdo_fetchall("SELECT * FROM " . $tablename . " WHERE  weid='{$weid}' AND nickname!='' AND isshow='{$isshow}'  AND yingcang='1' AND gender='1' AND tuijian='2' ORDER BY love DESC,time DESC LIMIT 0,".$tuijiannum);
								}
						}else{
						        $tuijian = pdo_fetchcolumn("SELECT count(*)  FROM " . $tablename . " WHERE  weid='{$weid}' AND nickname!='' AND isshow='{$isshow}'  AND yingcang='1' AND gender='2' AND tuijian='2' "); 
								if($tuijian < $tuijiannum){
									$list1 = pdo_fetchall("SELECT * FROM " . $tablename . " WHERE  weid='{$weid}' AND nickname!='' AND isshow='{$isshow}'  AND yingcang='1' AND gender='2' AND tuijian='2' ORDER BY love DESC,time DESC");
									$NUM = $tuijiannum - $tuijian;
									$list2 = pdo_fetchall("SELECT * FROM " . $tablename . " WHERE  weid='{$weid}' AND nickname!='' AND isshow='{$isshow}'  AND yingcang='1' AND gender='2' AND tuijian='1' ORDER BY RAND()  LIMIT ".$NUM."");
									$list = array_merge_recursive($list1,$list2);
								}else{
									$list = pdo_fetchall("SELECT * FROM " . $tablename . " WHERE  weid='{$weid}' AND nickname!='' AND isshow='{$isshow}'  AND yingcang='1' AND gender='2' AND tuijian='2' ORDER BY love DESC,time DESC LIMIT 0,".$tuijiannum);
								}
						}
						if(!empty($list) && is_array($list)){
								foreach($list as $row){
									if(!empty($row['lat']) && !empty($row['lng'])){
										if(!empty($res['lat']) && !empty($res['lng'])){
										   $juli[$row['id']]= "相距: ".getDistance($res['lat'],$res['lng'],$row['lat'],$row['lng'])."km";
										}else{
											$juli[$row['id']]= ""; 
										}
									}else{
										 $juli[$row['id']]= ""; 
									}
								}
							
						}
					}
		}else{
		   $url =  empty($settings['url']) ? 'http://baidu.com' : $settings['url'];
		   header("location:$url");
		   exit;
		}
		if($cfg['telephoneconfirm'] == '1'){
		    if($res['telephoneconfirm'] == '0'){
			   $smsurl=$this->createMobileUrl('sms');			
				header("location:$smsurl");
				exit;
			} 
		}
		include $this->template('alllist');
?>