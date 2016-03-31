<?php
/**
 * meepo微相亲模块微站定义
 *
 * @author meepo_zam
 * @url
 */
defined('IN_IA') or exit('Access Denied');
define(EARTH_RADIUS, 6371);//地球半径，平均半径为6371km
define('RES', '../addons/meepo_weixiangqin/template');
define('MEEPORES', '../addons/meepo_weixiangqin/template/mobile/tpl');
define('RES2', '../addons/meepo_weixiangqin/template/style/');
class Meepo_weixiangqinModuleSite extends WeModuleSite {
	public $modulename = 'meepo_weixiangqin';
	public function getusers($weid,$openid) {
        $tablename = tablename("hnfans");
        $sql = 'SELECT * FROM ' . $tablename . ' WHERE from_user=:from_user AND weid=:weid';
        $arr = array(
            ":from_user" =>$openid,
			":weid" => $weid
        );
        $res = pdo_fetch($sql, $arr);
        return $res;
    }
	public function insertit() {
		 global $_W;
		 $openid = $_W['openid'];
		 $weid = $_W['uniacid'];
		 $cfg = $this->module['config'];
		 load()->classs('weixin.account');
         $accObj= WeixinAccount::create($_W['account']['acid']);
         $access_token = $accObj->fetch_token();
		 $token2 =  $access_token;
		 if(empty($token2)){
			 die('管理员配置的参数有误'); 
		 }else{
			    load()->func('communication');
				$url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$token2.'&openid='.$openid.'&lang=zh_CN';
				$content2 = ihttp_request($url);
				$info = @json_decode($content2['content'], true);
				if(empty($info['nickname'])){
					die('管理员配置的参数有误');
				}else{
				   $row = array();
				   $onoff = pdo_fetchcolumn('SELECT status FROM ' .tablename('meepo_hongniangonoff') . ' WHERE weid=:weid',array(':weid' => $weid));
				   $row = array(
						'nickname'=> $info["nickname"],
						'realname'=> $info["nickname"],
						'avatar' => $info["headimgurl"],
						'gender'  => $info['sex'],
						'time'=>time()
					);
					if($onoff!='0'){
						   $row['isshow'] =0;
					}else{
						   $row['isshow'] = 1;
					}  
					if($cfg['yingcang'] == '2'){
						   $row['yingcang'] =2;
					}else{
						   $row['yingcang'] =1;
					} 
					if(!empty($info["country"])){
						$row['nationality']=$info["country"];
					}
					if(!empty($info["province"])){
						$row['resideprovincecity']=$info["province"].$info["city"];
					}
					
				   
					$res = $this->getusers($weid,$openid);
					if(!empty($res)){
						pdo_update('hnfans',array('avatar'=>$info["headimgurl"],'nickname'=>$info["nickname"]), array('from_user' =>$openid,'weid'=>$weid));
					}else{
						$row['weid'] = $weid;
						$row['from_user'] = $openid;
						pdo_insert('hnfans',$row);
					}
					
				}
		}           
		
	}
	//新增推荐自己到首页
	public function doMobiletjself(){
	    global $_W,$_GPC;
        $weid = $_W['uniacid'];
		$openid = $_W['openid'];
		$uid = $_W['member']['uid'];
		$cfg = $this->module['config'];
		$tuijiannum = empty($cfg['tuijiannum']) ? 10 : intval($cfg['tuijiannum']);
		if($_W['isajax']){
		    $payment = intval($cfg['tjjifen']);
            load()->model('mc');
			$member = mc_fetch($uid);
			 if($member['credit1'] >= $payment){
				           $res = $this->getusers($weid,$openid);
						   if($res['tuijian']=='2'){
						      die('已经显示在首页。无需再次推荐！');
						   }
						   if($res['yingcang']=='2'){
						      die('你已隐藏个人信息、不可推荐！');
						   }
						   if($res['gender']=='0'){
						      die('你的性别设置为保密，不可推荐！');
						   }
						   $allnum = pdo_fetchcolumn("SELECT count(*)  FROM " .tablename('hnfans'). " WHERE  weid='{$weid}' AND nickname!='' AND isshow='1'  AND gender='{$res['gender']}' AND yingcang='1' AND tuijian='2'"); //分页
						   if($allnum >=$tuijiannum){
							  die('推荐人数已经满，请联系管理员');
						   }
						   $check = pdo_fetchcolumn("SELECT id,status FROM ".tablename('meepohn_tuijian')." WHERE openid=:openid AND weid=:weid",array(':openid'=>$openid,':weid'=>$weid));
						   if(empty($check['id'])){
							   if($cfg['tjstatus']=='1'){//need confirm
							       $data['status']=0;
							   }else{
							       $data['status']=1;
							   }
							   $data['openid'] = $openid;
							   $data['payment'] = $payment;
							   $data['createtime'] = time();
							   $data['weid'] = $weid;
							   pdo_insert('meepohn_tuijian',$data);
							   pdo_query("UPDATE ".tablename('mc_members')." SET credit1 = credit1 - '{$payment}' WHERE uid = '{$uid}' AND uniacid='{$weid}' ");
							   if($data['status']==1){
							        pdo_update("hnfans", array("tuijian" =>2,'tjtype'=>1), array("from_user" =>$openid,"weid"=>$weid));
                                    die('你已推荐成功!');
							   }else{
							        pdo_update("hnfans", array('tjtype'=>1), array("from_user" =>$openid,"weid"=>$weid));
									die('推荐成功，请等待管理员审核');
							   }
                               
						   }else{
						       if($check['status']=='0'){
							        die('你已推荐成功，请等待管理员审核');
							   }else{
							       die('你已推荐成功，无需再次推荐');
							   }
						   }
			 }else{
				die('low');
			 }
            

		}
	}
	//管理员自主点击推荐
	public function doWebtuijian(){
		global $_W,$_GPC;
        $weid = $_W['uniacid'];
		$openid = $_W['openid'];
		$cfg = $this->module['config'];
		$tuijiannum = empty($cfg['tuijiannum']) ? 10 : intval($cfg['tuijiannum']);
			if(!empty($_GPC['id'])){
				  $id = intval($_GPC['id']);
				  $tuijian = pdo_fetch("SELECT tuijian,gender FROM".tablename('hnfans')." WHERE weid=:weid AND id=:id",array(':weid'=>$weid,':id'=>$id));
				  if($tuijian['tuijian'] == '1'){
						  if($tuijian['gender'] == '0'){
							   message('此人性别保密，不可推荐','referer','error');
						  }
				  }
				  $allnum = pdo_fetchcolumn("SELECT count(*)  FROM " .tablename('hnfans'). " WHERE  weid='{$weid}' AND nickname!='' AND isshow='1'  AND gender='{$tuijian['gender']}' AND yingcang='1' AND tuijian='2'"); //分页
				  if($allnum >=$tuijiannum){
					  if($tuijian['gender'] == '1'){
					     message('推荐男性数最多为'.$tuijiannum.'人！','referer','error');
					  }else{
					     message('推荐女性数最多为'.$tuijiannum.'人！','referer','error');
					  }
				  }
				  if($tuijian['tuijian'] == '1'){
					  pdo_update('hnfans',array('tuijian'=>2),array('weid'=>$weid,'id'=>$id));
					  message('推荐到首页成功','referer','success');
				  }else{
					  pdo_update('hnfans',array('tuijian'=>1),array('weid'=>$weid,'id'=>$id));
					   message('取消推荐成功','referer','success');
				  }
			}else{
			  message('参数错误','referer','error');
			}
	  
	}
	//推荐申请
	public function doWebtjapply(){
        global $_GPC, $_W;
		$weid = $_W['uniacid'];
        checklogin();
        if (checksubmit('verify') && !empty($_GPC['select'])) {
            pdo_update('meepohn_tuijian', array('status' => 1) , " id  IN  ('" . implode("','", $_GPC['select']) . "')");
			foreach($_GPC['select'] as $row){
			   $openid = pdo_fetchcolumn("SELECT openid FROM ".tablename('meepohn_tuijian')." WHERE weid=:weid AND id=:id",array(":weid"=>$weid,':id'=>intval($row)));
			   pdo_update("hnfans",array('tuijian'=>2),array('from_user'=>$openid,'weid'=>$weid));
			}
            message('审核成功！', $this->createWebUrl('tjapply', array('page' => $_GPC['page'])),'success');
        }
        if (checksubmit('delete') && !empty($_GPC['select'])) {
            
			foreach($_GPC['select'] as $row){
			   $openid = pdo_fetchcolumn("SELECT openid FROM ".tablename('meepohn_tuijian')." WHERE weid=:weid AND id=:id",array(":weid"=>$weid,':id'=>intval($row)));
			   pdo_update("hnfans",array('tuijian'=>1,'tjtype'=>0),array('from_user'=>$openid,'weid'=>$weid));
			}
			pdo_delete('meepohn_tuijian', " id  IN  ('" . implode("','", $_GPC['select']) . "')");
            message('删除成功！', $this->createWebUrl('tjapply', array('page' => $_GPC['page'] )),'success');
        }
		load()->func('tpl');
		$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $status = isset($_GPC['status']) ? intval($_GPC['status']) : 0;
		if($op=='display'){
				$pindex = max(1, intval($_GPC['page']));
				$psize = 20;
				$condition .= "  o.status='{$status}'";
				if (!empty($_GPC['keyword'])) {
				    $condition .= " AND a.nickname LIKE '%{$_GPC['keyword']}%'";
			    }	
			$sql = "select o.* , a.nickname,a.avatar,a.yingcang  from ".tablename('meepohn_tuijian')." o"
					." left join ".tablename('hnfans')." a on o.openid = a.from_user where $condition ORDER BY a.time DESC"
					. " LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
			$list = pdo_fetchall($sql);
			$total = pdo_fetchcolumn("select count(*)  from ".tablename('meepohn_tuijian')." o"
					." left join ".tablename('hnfans')." a on o.openid = a.from_user where $condition ORDER BY a.time DESC");
            $pager = pagination($total, $pindex, $psize);
		 }
		  include $this->template('apply');
	}
    /***********检验是否已经注册*************/
    public function doMobileRegisterajax() {
        global $_W,$_GPC;
        $weid = $_W['weid'];
        $openid = $_W['openid']; 
		$cfg = $this->module['config'];
        $data = array();
        if (empty($_GPC['openid'])) {
            $data['msg'] = '登录失效';
            $data['res'] = false;
        } else {
            $res = $this->getusers($weid,$_GPC['openid']);
            if (empty($res)) {
				    $data['msg'] = '未注册';
                    $data['res'] = false;
                
            } else {
                    $data['msg'] = '1';
				    $data['res'] = true;
            }
        }
        die(json_encode($data));
    }
    /********编辑头像********/
	public function doMobileUploadImage() {
		global $_W;
		$openid = $_W['openid'];
		$weid = $_W['weid'];
		$result = array();	
		if (empty($_FILES['header_img_id']['name'])) {
			$result['message'] = '请选择要上传的文件！';
			$result['result'] = 0;
			exit(json_encode($result));
		}
        $back = $this->fileUpload2($_FILES['header_img_id'], $type = 'image');//{$_W['attachment']}
		
		if ($back == '-1') {
			$result['message'] = '不支持此类文件！';
			$result['result'] = 0;
		}elseif($back == '-2'){
		    $result['message'] = '文件最大为2兆！';
			$result['result'] = 0;
		}elseif($back == '-3'){
		    $result['message'] = '网络超时，保存失败！';
			$result['result'] = 0;
		}else{
			$result['imgurl'] =  $back['path'];
			$result['result'] = 1;
			//$result['filename'] = $file['filename'];
			load()->func('file');
			$headerimg = $this->getusers($weid,$openid);
			if(!strpos($headerimg['avatar'],'qlogo')){
			   file_delete($headerimg['avatar']);
		    }
			pdo_update('hnfans',array('avatar'=>$back['path']),array('from_user'=>$openid,'weid'=>$weid));
		}
		exit(json_encode($result));
	}
	/********上传照片********/
	public function doMobileUploadImage2() {
		global $_W,$_GPC;
		$openid = $_W['openid'];
		$weid = $_W['weid'];
		$photocfg = $this->module['config'];
		$result = array();
		if(empty($openid) || empty($_POST['id'])){
		   die('0');
		}
         load()->func('communication');
		 load()->classs('weixin.account');
         $accObj= WeixinAccount::create($_W['account']['acid']);
         $access_token = $accObj->fetch_token();
	     $token2 =  $access_token;
			 $url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$_POST['id'];
			 $pic_data = ihttp_request($url);
			 $path = "images/meepoxiangqin/";
			 load()->func('file');
			 $picurl = $path.random(30) .".jpg";
			 file_write($picurl,$pic_data['content']);
			 $data = array(
							'from_user' => $openid,
							'weid' => $weid,
							'url' => $picurl,
							'description' => '暂无描述',
							'time' => time(),
						);
			if($photocfg['isstatus']==0){
			 $data['status'] = 1;
			}else{
			   $data['status'] = 0;
			}
			pdo_insert('meepohongniangphotos',$data);
			
			die($picurl);
	}
    public function fileUpload2($file, $type = 'image', $name = '') {
			if(empty($file)) {
				return '-1';
			}
			global $_W;
			if(empty($cfg['size'])){
			   $defsize = 2;
			}
		    $deftype = array('jpg','png','jpeg');
			if (empty($_W['uploadsetting'])) {
				$_W['uploadsetting'] = array();
				$_W['uploadsetting'][$type]['folder'] = 'images';
				$_W['uploadsetting'][$type]['extentions'] = $deftype;
				$_W['uploadsetting'][$type]['limit'] = 1024*$defsize;
			}
			$settings = $_W['uploadsetting'];
			if(!array_key_exists($type, $settings)) {
				return '-1';
			}
			$extention = pathinfo($file['name'], PATHINFO_EXTENSION);
			if(!in_array(strtolower($extention), $settings[$type]['extentions'])) {
				return '-1';
			}
			if(!empty($settings[$type]['limit']) && $settings[$type]['limit'] * 1024 < $file['size']) {
				return '-2';
			}
			$result = array();
			load()->func('file');
			if(empty($name) || $name == 'auto') {
				$result['path'] = "{$settings[$type]['folder']}/" . date('Y/m/');
				mkdirs(ATTACHMENT_ROOT . '/' . $result['path']);
				do {
					
					$filename = random(30) . ".{$extention}";
				   
				} while(file_exists(ATTACHMENT_ROOT . '/' . $result['path'] . $filename));
				$result['path'] .= $filename;
			} else {
				$result['path'] = $name . '.' .$extention;
			}
			if(!file_move($file['tmp_name'], ATTACHMENT_ROOT . '/' . $result['path'])) {
				return '-3';
			}
			return $result; 
     }
      /*
   * 首次进入我的直接推荐人 从数据库获取推荐人的回复消息以及发送的消息 最后10条 按时间排序
   **/
   
   public function doMobilegetfatherback10(){
      global $_W, $_GPC;
	  $weid = $_W['uniacid'];
	  $sender = $_W['fans']['from_user'];
      if(empty($_GPC['sender']) || empty($_GPC['geter'])){
	      $back = array();
		 
	  }else{
		 $geter = $_GPC['geter'];
		 $pindex = intval($_GPC['page']);
		 $psize = 4;
		 $back = pdo_fetchall("SELECT * FROM ".tablename('hnmessage')." WHERE (sender='{$sender}' or sender='{$geter}') AND (geter='{$geter}' or geter='{$sender}')  AND weid='{$weid}'  ORDER BY stime DESC LIMIT " . $pindex . ",{$psize}");
		 
		 if(is_array($back) && !empty($back)){
			 foreach($back as &$row){
			   $row['stime'] = date('Y-m-d H:i:s', $row['stime']);
			 }
			 unset($row);
			 
		 }else{
			$back = array();
		 }	
	  }
	  die(json_encode($back));
   }
   /*
   *获取接收方的及时消息
   **/
   public function doMobilegetmes(){
      global $_W, $_GPC;
	  $weid = $_W['uniacid'];
			if(empty($_POST['sender'])){exit();}
			$sender = $_POST['sender'];
			$geter = $_POST['geter'];
			$sql = "SELECT * FROM ".tablename('hnmessage')." WHERE sender=:sender AND geter=:geter AND mloop=:mloop AND weid=:weid ORDER BY stime ASC";
			$all = pdo_fetchall($sql,array(':sender'=>$geter,':geter'=>$sender,':mloop'=>0,':weid'=>$weid));
			$mNums = count($all);
			if($mNums<1){
				echo "nomessage";
				exit();
			}else{
				 if(is_array($all) && !empty($all)){
					 foreach($all as &$row){
					   $row['stime'] = date('Y-m-d H:i:s', $row['stime']);
					 }
					 unset($row);
					 
				 }
				echo json_encode($all);
			}
			
			if($mNums>0){
				pdo_update('hnmessage',array('mloop'=>1),array('sender'=>$geter,'geter'=>$sender,'mloop'=>0,'weid'=>$weid));
			}
   }
   /*
	*
	* 向推荐人发送消息 并且插入数据库 发送的文本内容以及发送的时间 以及发送者 以及其直接推荐人
	*/
    public function doMobilechatfatherajax(){
      global $_W, $_GPC;
	  $weid = $_W['uniacid'];
      $back = array();
	  $data = array();
	  $cfgs = $this->module['config'];				
	  if(!empty($_GPC['content'])){
			if(empty($_GPC['sender']) || empty($_GPC['geter'])){
			   $back = array(
				   'succ'=>'0',
				   'message'=>'参数错误'
			   ); 
			}else{
			   $result = pdo_fetchcolumn("SELECT id FROM ".tablename('hnblacklist')." WHERE wantblack = :wantblack AND blackwho = :blackwho AND weid=:weid",array(':wantblack'=>$_GPC['sender'],':blackwho'=>$_GPC['geter'],':weid'=>$weid));
			   if(!empty($result)){
				   $back = array(
				   'succ'=>'0',
				   'message'=>'你已将对方拉入黑名单'
			       );
				   
			   }else{ 
                   $uresult = pdo_fetchcolumn("SELECT id FROM ".tablename('hnblacklist')." WHERE wantblack = :wantblack AND blackwho = :blackwho AND weid=:weid",array(':wantblack'=>$_GPC['geter'],':blackwho'=>$_GPC['sender'],':weid'=>$weid));
				   if(!empty($uresult)){
					    $back = array(
						   'succ'=>'0',
						   'message'=>'对方已将你拉入黑名单'
						   );
				   }else{
					   //回复正常 那么先检查是否开通包月功能
					   $baoyue = pdo_fetchcolumn("SELECT endtime FROM ".tablename('meepohn_baoyue')." WHERE openid=:openid AND weid=:weid ORDER BY endtime DESC",array(':weid'=>$weid,':openid'=>$_GPC['sender']));
					   if(empty($baoyue) || TIMESTAMP > $baoyue){//未开通包月或者包月过期
							 $payment = !empty($cfgs['chatpay']) ? intval($cfgs['chatpay']) : 0 ;
							 load()->model('mc');
							 $member = mc_fetch($_W['member']['uid']);
							 if($member['credit1'] >= $payment){
										   $senderuid = pdo_fetch("SELECT * FROM ".tablename('hnfans')." WHERE from_user = '{$_GPC['sender']}' AND weid = '{$weid}'");
										   $senderavatar = $senderuid['avatar'];
										   $sendernickname = $senderuid['nickname'];
										   $data['sender'] = $_GPC['sender'];
										   $data['geter'] = $_GPC['geter'];
										   $data['content'] = $_GPC['content'];
										   $data['msgtype'] = $_GPC['msgtype'];
										   $data['stime'] = time();
										   $data['weid'] = $weid;
										   if(preg_match('/http:(.*)/',$senderavatar)){
											$data['senderavatar'] =  $senderavatar;
										   }elseif(preg_match('/images(.*)/',$senderavatar)){
											$data['senderavatar'] = $_W['attachurl'].$senderavatar;
										   }else{   
											$data['senderavatar'] = MEEPORES."/static/friend/images/cdhn80.jpg";
										   }
										   $data['sendernickname'] = $sendernickname;
											pdo_insert('hnmessage',$data);
											pdo_update("hnfans", array("mails" =>$senderuid['mails']+1), array("from_user" =>$_GPC['geter'],"weid"=>$weid));
											$btime = date('Y-m-d'.'00:00:00',time());
											$btimestr = strtotime($btime);
											
											$max = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('hnmessage')." WHERE sender=:sender AND geter=:geter  AND weid=:weid AND stime>:stime",array(':sender'=>$_GPC['geter'],':geter'=>$_GPC['sender'],':weid'=>$weid,':stime'=>$btimestr));
											$cfgnum = intval($cfgs['maxnum']);
											if($cfgnum > 0){
												 if($max < $cfgnum){
													   $this->sendmessage($_GPC['content'],$_GPC['geter']);
												 }
											}else{
												$this->sendmessage($_GPC['content'],$_GPC['geter']);
											}
											$time = date('Y-m-d H:i:s', time());
											 $back = array(
												  'succ'=>'1',
												  'message'=>$time
											  );
											 $touid = $_W['member']['uid'];
											 pdo_query("UPDATE ".tablename('mc_members')." SET credit1 = credit1 - '{$payment}' WHERE uid = '{$touid}' AND uniacid='{$weid}' ");
							 }else{
											 $back = array(
											  'succ'=>'2',
											  'message'=>'积分余额不足'
											 );
							 }
					   }else{//开通了包月
					                       $senderuid = pdo_fetch("SELECT avatar,nickname FROM ".tablename('hnfans')." WHERE from_user = '{$_GPC['sender']}' AND weid = '{$weid}'");
										   $senderavatar = $senderuid['avatar'];
										   $sendernickname = $senderuid['nickname'];
										   $data['sender'] = $_GPC['sender'];
										   $data['geter'] = $_GPC['geter'];
										   $data['content'] = $_GPC['content'];
										   $data['msgtype'] = $_GPC['msgtype'];
										   $data['stime'] = time();
										   $data['weid'] = $weid;
										   if(preg_match('/http:(.*)/',$senderavatar)){
											   $data['senderavatar'] =  $senderavatar;
										   }elseif(preg_match('/images(.*)/',$senderavatar)){
											   $data['senderavatar'] = $_W['attachurl'].$senderavatar;
										   }else{   
											   $data['senderavatar'] = MEEPORES."/static/friend/images/cdhn80.jpg";
										   }
										         $data['sendernickname'] = $sendernickname;
											     pdo_insert('hnmessage',$data);
											     pdo_update("hnfans", array("mails" =>$senderuid['mails']+1), array("from_user" =>$_GPC['geter'],"weid"=>$weid));
											     $btime = date('Y-m-d'.'00:00:00',time());
											     $btimestr = strtotime($btime);
											     $max = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('hnmessage')." WHERE sender=:sender AND geter=:geter  AND weid=:weid AND stime>:stime",array(':sender'=>$_GPC['geter'],':geter'=>$_GPC['sender'],':weid'=>$weid,':stime'=>$btimestr));
											     $cfgnum = intval($cfgs['maxnum']);
												 if($cfgnum > 0){
													 if($max < $cfgnum){
														   $this->sendmessage($_GPC['content'],$_GPC['geter']);
													 }
												 }else{
													$this->sendmessage($_GPC['content'],$_GPC['geter']);
												 }
												 $time = date('Y-m-d H:i:s', time());
												 $back = array(
													  'succ'=>'1',
													  'message'=>$time
												  );
					   }
				   }
			   }
						
			}
	  }else{
	          $back = array(
				 'succ'=>'0',
				   'message'=>'发送内容不能为空哦！'
			   ); 
	  }
      die(json_encode($back));
   }
   public function doMobileUploadImage3() {
		global $_W,$_GPC;
		$openid = $_W['openid'];
		$weid = $_W['weid'];
		$cfgs = $this->module['config'];
		$result = array();
		if(empty($openid) || empty($_POST['id']) || empty($_GPC['geter'])){
		   $back = array(
				   'succ'=>'0',
				   'message'=>'参数错误'
			   ); 
		}else{
              $sql = "SELECT id FROM ".tablename('hnblacklist')." WHERE wantblack = :wantblack AND blackwho = :blackwho AND weid=:weid";
			   $paras = array(':wantblack'=>$openid,':blackwho'=>$_GPC['geter'],':weid'=>$weid);
			   $result = pdo_fetchcolumn($sql,$paras);
			   if(!empty($result)){
				   $back = array(
				   'succ'=>'0',
				   'message'=>'你已将对方拉入黑名单'
			       );
			   }else{
				   $sql2 = "SELECT id FROM ".tablename('hnblacklist')." WHERE wantblack = :wantblack AND blackwho = :blackwho AND weid=:weid";
			       $paras2 = array(':wantblack'=>$_GPC['geter'],':blackwho'=>$openid,':weid'=>$weid);
                   $uresult = pdo_fetchcolumn($sql2,$paras2);
				   if(!empty($uresult)){
					    $back = array(
						   'succ'=>'0',
						   'message'=>'对方已将你拉入黑名单'
						   );
				   }else{
					 $baoyue = pdo_fetchcolumn("SELECT endtime FROM ".tablename('meepohn_baoyue')." WHERE openid=:openid AND weid=:weid ORDER BY endtime DESC",array(':weid'=>$weid,':openid'=>$_GPC['sender']));
					 if(empty($baoyue) || TIMESTAMP > $baoyue){//未开通包月或者包月过期
                                $payment = !empty($cfgs['chatpay']) ? intval($cfgs['chatpay']) : 0 ;
					            load()->model('mc');
					            $member = mc_fetch($_W['member']['uid']);
					            if($member['credit1'] >= $payment){
										 load()->func('communication');
										 load()->classs('weixin.account');
										 $accObj= WeixinAccount::create($_W['account']['acid']);
										 $access_token = $accObj->fetch_token();
										 $token2 =  $access_token;
											 $url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$_POST['id'];
											 $pic_data = ihttp_request($url);
											 $path = "images/meepoxiangqin/";
											 $path2 = "images/meepoxiangqinthumb/";
											 load()->func('file');
											 $picurl = $path.random(30) .".jpg";
											 $thumbimg = $path2.random(30) .".jpg";
											 file_write($picurl,$pic_data['content']);
											 $thumb = file_image_thumb(IA_ROOT . '/attachment/' .$picurl,IA_ROOT . '/attachment/' .$thumbimg, $width = 70);
											 if(!is_array($thumb)){
												  $thumburl = $thumb;
											 }else{
												  $thumburl = $picurl;
											 }
											$sender = $_W['fans']['from_user'];
											$senderuid = pdo_fetch("SELECT * FROM ".tablename('hnfans')." WHERE from_user = '{$sender}' AND weid = '{$weid}'");
											$senderavatar = $senderuid['avatar'];
											$sendernickname = $senderuid['nickname'];
											$data = array(
															'sender' => $sender,
															'geter' => $_GPC['geter'],
															'content' => $picurl,
															'msgtype' => 'images',
															'thumburl'=>$thumburl,
															'stime' => time(),
															'weid' => $weid,
															'sendernickname'=> $sendernickname,
													 
														);
											
											if(preg_match('/http:(.*)/',$senderavatar)){
												$data['senderavatar'] =  $senderavatar;
											}elseif(preg_match('/images(.*)/',$senderavatar)){
												$data['senderavatar'] = $_W['attachurl'].$senderavatar;
											}else{   
												$data['senderavatar'] = MEEPORES."/static/friend/images/cdhn80.jpg";
											}
											$res = pdo_insert('hnmessage',$data);
											pdo_update("hnfans", array("mails" =>$senderuid['mails']+1), array("from_user" =>$_GPC['geter'],"weid"=>$weid));
													$btime = date('Y-m-d'.'00:00:00',time());
													$btimestr = strtotime($btime);
													$max = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('hnmessage')." WHERE sender=:sender AND geter=:geter  AND weid=:weid AND stime>:stime",array(':sender'=>$sender,':geter'=>$_GPC['geter'],':weid'=>$weid,':stime'=>$btimestr));
													
													$cfgnum = intval($cfgs['maxnum']);
														if($cfgnum){
															 if($max < $cfgnum){
																   $this->sendmessage($_GPC['content'],$_GPC['geter']);
															 }
														}else{
															$this->sendmessage($_GPC['content'],$_GPC['geter']);
														}
													$back['succ'] = '1';		
													$back['picurl'] = $picurl;
													$back['thumburl'] = $thumburl;
													$touid = $_W['member']['uid'];
											        pdo_query("UPDATE ".tablename('mc_members')." SET credit1 = credit1 - '{$payment}' WHERE uid = '{$touid}' AND uniacid='{$weid}' ");
									}else{
										  $back = array(
													  'succ'=>'2',
													  'message'=>'积分余额不足'
													 );
									}   
					 }else{
					                     load()->func('communication');
										 load()->classs('weixin.account');
										 $accObj= WeixinAccount::create($_W['account']['acid']);
										 $access_token = $accObj->fetch_token();
										 $token2 =  $access_token;
											 $url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$token2.'&media_id='.$_POST['id'];
											 $pic_data = ihttp_request($url);
											 $path = "images/meepoxiangqin/";
											 $path2 = "images/meepoxiangqinthumb/";
											 load()->func('file');
											 $picurl = $path.random(30) .".jpg";
											 $thumbimg = $path2.random(30) .".jpg";
											 file_write($picurl,$pic_data['content']);
											 $thumb = file_image_thumb(IA_ROOT . '/attachment/' .$picurl,IA_ROOT . '/attachment/' .$thumbimg, $width = 70);
											 if(!is_array($thumb)){
												  $thumburl = $thumb;
											 }else{
												  $thumburl = $picurl;
											 }
											$sender = $_W['fans']['from_user'];
											$senderuid = pdo_fetch("SELECT * FROM ".tablename('hnfans')." WHERE from_user = '{$sender}' AND weid = '{$weid}'");
											$senderavatar = $senderuid['avatar'];
											$sendernickname = $senderuid['nickname'];
											$data = array(
															'sender' => $sender,
															'geter' => $_GPC['geter'],
															'content' => $picurl,
															'msgtype' => 'images',
															'thumburl'=>$thumburl,
															'stime' => time(),
															'weid' => $weid,
															'sendernickname'=> $sendernickname,
													 
														);
											
											if(preg_match('/http:(.*)/',$senderavatar)){
												$data['senderavatar'] =  $senderavatar;
											}elseif(preg_match('/images(.*)/',$senderavatar)){
												$data['senderavatar'] = $_W['attachurl'].$senderavatar;
											}else{   
												$data['senderavatar'] = MEEPORES."/static/friend/images/cdhn80.jpg";
											}
											$res = pdo_insert('hnmessage',$data);
											pdo_update("hnfans", array("mails" =>$senderuid['mails']+1), array("from_user" =>$_GPC['geter'],"weid"=>$weid));
													$btime = date('Y-m-d'.'00:00:00',time());
													$btimestr = strtotime($btime);
													$max = pdo_fetchcolumn("SELECT count(*) FROM ".tablename('hnmessage')." WHERE sender=:sender AND geter=:geter  AND weid=:weid AND stime>:stime",array(':sender'=>$sender,':geter'=>$_GPC['geter'],':weid'=>$weid,':stime'=>$btimestr));
													
													$cfgnum = intval($cfgs['maxnum']);
														if($cfgnum){
															 if($max < $cfgnum){
																   $this->sendmessage($_GPC['content'],$_GPC['geter']);
															 }
														}else{
															$this->sendmessage($_GPC['content'],$_GPC['geter']);
														}
													$back['succ'] = '1';		
													$back['picurl'] = $picurl;
													$back['thumburl'] = $thumburl;
					 
					 }	
					}
			}
		}
		die(json_encode($back));
	}
	//拉黑ajax
	public function doMobiledropblackajax(){
	    global $_W,$_GPC;
        $weid = $_W['uniacid'];
        $openid = $_W['openid'];
	    if($_W['isajax']){
			$to = $_GPC['toname'];
			$toopenid = $_GPC['toopenid'];
			if (empty($openid)) {
				        $back = array(
						   'succ'=>0,
						   'message'=>'失败、请重新从微信进入！'
						   );
						   die(json_encode($back));
			}
			if (empty($to)) {
				 $back = array(
						   'succ'=>0,
						   'message'=>'失败、请重新从微信进入！'
						   );
						   die(json_encode($back));
			} else {
				if ($openid == $toopenid) {
					 $back = array(
						   'succ'=>0,
						   'message'=>'错误！'
						   );
						   die(json_encode($back));
				}
				$sql = "SELECT * FROM ".tablename('hnblacklist')." WHERE wantblack = :wantblack AND blackwho = :blackwho AND weid=:weid";
				$paras = array(':wantblack'=>$openid,':blackwho'=>$toopenid,':weid'=>$weid);
				$result = pdo_fetch($sql,$paras);
				if(empty($result)){
					$data = array('wantblack'=>$openid,'blackwho'=>$toopenid,'time'=>time(),'weid'=>$weid);
					pdo_insert('hnblacklist',$data);
					 $back = array(
						   'succ'=>1,
						   'message'=>'拉黑成功！'
						   );
					die(json_encode($back));
					
				}else{
					pdo_delete('hnblacklist',array('wantblack'=>$openid,'blackwho'=>$toopenid,'weid'=>$weid));
					$back = array(
						   'succ'=>2,
						   'message'=>'取消拉黑成功！'
						   );
					die(json_encode($back));
				}  
			}
		}
	}
	//默认走周榜单 榜单的前20名男性
    public function doMobilebangdanajax() {
        global $_W,$_GPC;
        $weid = $_W['weid'];
		$suijinum = rand();
		$settings = pdo_fetch("SELECT * FROM ".tablename('meepo_hongniangset')." WHERE weid=:weid",array(':weid'=>$_W['weid']));
		$openid2 = $_W['openid'];
        //先取得当事人的位置  
		$julires = $this->getusers($weid,$openid);
		$tablename = tablename("hnfans");
		$psize = 20;
        $pindex =1;
		$isshow =1;
		$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));//里面的数字分别是整型（int）的时、分、秒、月、日、年
		$endToday=mktime(0,0,0,date('m'),date('d')+7,date('Y'))-1;
		if($_POST['time']=="week" && $_POST['type']=="men"){
             $gender=1;
              $list = pdo_fetchall("SELECT *  FROM " . $tablename . " WHERE yingcang=1 AND weid='{$weid}' AND nickname!='' AND isshow='{$isshow}' AND gender='{$gender}' ORDER BY rand() LIMIT " . ($pindex - 1) * $psize . ',' . $psize); //分页

            if(!empty($list) && is_array($list)){
			     foreach($list as $rand){
			       $list2[] = pdo_fetch("SELECT *  FROM " . $tablename . " WHERE yingcang=1 AND weid='{$weid}' AND from_user='{$rand['from_user']}' AND gender='{$gender}'"); //分页  这里没意义后期改进吧
			     }
            }
		}elseif($_POST['time']=="week" && $_POST['type']=="women"){
		      $gender=2;
			    
                   $list = pdo_fetchall("SELECT *  FROM " . $tablename . " WHERE yingcang=1 AND weid='{$weid}' AND nickname!='' AND isshow='{$isshow}' AND gender='{$gender}' ORDER BY rand() LIMIT " . ($pindex - 1) * $psize . ',' . $psize); //分页
			   

               if(!empty($list) && is_array($list)){
			      foreach($list as $rand){
			         $list2[] = pdo_fetch("SELECT *  FROM " . $tablename . " WHERE yingcang=1 AND weid='{$weid}' AND from_user='{$rand['from_user']}'"); //分页  这里没意义后期改进吧
			       }
		       }
		}elseif($_POST['time']=="all" && $_POST['type']=="women"){
		        $gender=2;
		        $list2 = pdo_fetchall("SELECT *  FROM " . $tablename . " WHERE yingcang=1 AND weid='{$weid}' AND nickname!='' AND isshow='{$isshow}' AND gender='{$gender}' ORDER BY love DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize); //分页
		}else{
		         $gender=1;
		        $list2 = pdo_fetchall("SELECT *  FROM " . $tablename . " WHERE yingcang=1 AND weid='{$weid}' AND nickname!='' AND isshow='{$isshow}' AND gender='{$gender}' ORDER BY love DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize); //分页
		
		}
			if(!empty($list2) && is_array($list2)){
					foreach($list2 as $row){
						if(!empty($row['lat']) && !empty($row['lng'])){
							if(!empty($julires['lat']) && !empty($julires['lng'])){
							   $juli[$row['id']]= "相距: ".$this->getDistance($julires['lat'],$julires['lng'],$row['lat'],$row['lng'])."km";
							}else{
							    $juli[$row['id']]= ""; 
							}
						}else{
							 $juli[$row['id']]= ""; 
						}
						$photoss = $this->getphotos($row['from_user']);
						if(count($photoss)>3){
						  $photos[$row['id']] = array($photoss[0],$photoss[1],$photoss[2]);
						}else{
						  $photos[$row['id']] =$photoss;
						}
						
					}
							$result_str = '';
								foreach($list2 as $row) {
									$result_str .= '<div class="search_list"><article>
										<div class="list_info"><p>';
															   if(preg_match('/http:(.*)/',$row['avatar'])){
																	  $result_str .='<img src="'.$row['avatar'].'" alt="用户头像" height="30" width="30">';
																  }elseif(preg_match('/images(.*)/',$row['avatar'])){
																	  $result_str .='<img src="'.$_W['attachurl'].$row['avatar'].'" alt="用户头像" height="30" width="30">';
																  }else{
																	 $result_str .='<img src="./addons/meepo_weixiangqin/template/mobile/tpl/static/friend/images/cdhn80.jpg" alt="用户头像" height="30" width="30">'; 
																  }
						                           $result_str .='</p><dl><dt><a href="'.$this->createMobileUrl('others',array('openid'=>$row['from_user'])).'">'.cutstr($row['realname'],5,true).'</a><span>'.$row['age'].' | '.$row['resideprovincecity'].'</span>
										</dt>
										</dl>
										<input type="hidden" class="toopenid'.$row['id'].'" value="'.$row['from_user'].'"/>
										<a class="search_hi" id="hitlike" date="'.$row['id'].'" title="'.$row['openid'].'">赞&nbsp;&nbsp;'.$row['love'].'</a>
										</div>
										<ul>';

										foreach($photos[$row['id']] as $ph){
										$result_str .='<li><img src="'.$_W['attachurl'].$ph['url'].'" height="120" width="90" date="'.$row['from_user'].'" id="btn"></li>';

										}
										$result_str .='</ul>
										</article></div>';

								}
								if ($result_str == '') {
									echo json_encode(0);
								} else {
									echo json_encode($result_str);
								}
			}else{
			   echo json_encode(0);
			}
	
    }
	/*******选择显示与影藏******/
	public function doMobileshow(){
		global $_W,$_GPC;
        $weid = $_W['weid'];
		$openid = $_W['openid'];
		if(!empty($openid) && $_POST['choose']){
			  $yingcang = intval($_POST['choose']);
		      $res = pdo_update('hnfans',array('yingcang'=>$yingcang),array('weid'=>$weid,'from_user'=>$openid));
			  if($res){
			     die('1');
			  }else{
			    die('0');
			  }
		}else{
		  die('0');
		}
	
	}
    /*********点击喜欢ajax********/
    public function doMobilelikeajax() {
        global $_W,$_GPC;
        $weid = $_W['weid'];
		$openid = $_W['openid'];
        if (empty($openid) || empty($_GPC['uid']) || empty($_GPC['toopenid'])) {
           die('error');
        } else {
            $res = $this->getusers($weid,$openid);
            if (empty($res['constellation'])) {
               die('nfull');
            } else {
				//uid 是被喜欢的粉丝hnfans表内的id
				$toopenid = $_GPC['toopenid'];
				$uid = intval($_GPC['uid']);
                if ($openid == $toopenid) {
                       die('no way');
				}else{
                    $hadlike = pdo_fetchcolumn("SELECT id FROM ".tablename('meepo_hongnianglikes')." WHERE toopenid = :toopenid AND openid = :openid AND weid =:weid",array(':toopenid'=>$toopenid,':openid'=>$openid,':weid'=>$weid));
                    if (!empty($hadlike)) {
                       
						 pdo_delete("meepo_hongnianglikes", array('id'=>$hadlike));
						 pdo_query("UPDATE ".tablename('hnfans')." SET love = love - '1' WHERE from_user = :from_user AND weid=:weid ",array(':from_user'=>$toopenid,':weid'=>$weid));
				        $user = $this->getusers($weid,$toopenid);
					    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $user['love'];
					    exit;
                    } else {
					   
						$data = array(
							'uid' =>$uid,
							'openid' =>$openid,
							'toopenid' => $toopenid,
							'status' => 1,
							'createtime' => TIMESTAMP,
							'weid'=>$weid,
						);
						pdo_insert("meepo_hongnianglikes", $data);
						pdo_query("UPDATE ".tablename('hnfans')." SET love = love + '1' WHERE from_user = :from_user AND weid=:weid ",array(':from_user'=>$toopenid,':weid'=>$weid));
						
				        $user = $this->getusers($weid,$toopenid);
					    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $user['love'];
					    exit;
                    }
                } 
            }
        }
    }
	//榜单页点击赞
	 /*********点击喜欢ajax********/
    public function doMobilelikeajax2() {
        global $_W,$_GPC;
        $weid = $_W['weid'];
		$openid = $_W['openid'];
        if (empty($openid) || empty($_GPC['uid']) || empty($_GPC['toopenid'])) {
           die('error');
        } else {
            $res = $this->getusers($weid,$openid);
            if (empty($res['constellation'])) {
               die('nfull');
            } else {
				//uid 是被喜欢的粉丝hnfans表内的id
				$toopenid = $_GPC['toopenid'];
				$uid = intval($_GPC['uid']);
                if ($openid == $toopenid) {
                       die('no way');
				}else{
                    $hadlike = pdo_fetchcolumn("SELECT id FROM ".tablename('meepo_hongnianglikes')." WHERE toopenid = :toopenid AND openid = :openid AND weid =:weid",array(':toopenid'=>$toopenid,':openid'=>$openid,':weid'=>$weid));
                    if (!empty($hadlike)) {
                       
						 pdo_delete("meepo_hongnianglikes", array('id'=>$hadlike));
						 pdo_query("UPDATE ".tablename('hnfans')." SET love = love - '1' WHERE from_user = :from_user AND weid=:weid ",array(':from_user'=>$toopenid,':weid'=>$weid));
				        $user = $this->getusers($weid,$toopenid);
					    echo "赞&nbsp;&nbsp;" . $user['love'];
					    exit;
                    } else {
					   
						$data = array(
							'uid' =>$uid,
							'openid' =>$openid,
							'toopenid' => $toopenid,
							'status' => 1,
							'createtime' => TIMESTAMP,
							'weid'=>$weid,
						);
						pdo_insert("meepo_hongnianglikes", $data);
						pdo_query("UPDATE ".tablename('hnfans')." SET love = love + '1' WHERE from_user = :from_user AND weid=:weid ",array(':from_user'=>$toopenid,':weid'=>$weid));
						
				        $user = $this->getusers($weid,$toopenid);
					    echo "赞&nbsp;&nbsp;" . $user['love'];
					    exit;
                    }
                } 
            }
        }
    }
	
	
    /*******错误的方式********/
    public function doMobileErrorjoin() {
		  global $_W,$_GPC;
        include $this->template('error');
    }
    /*******获取当前fans的个人信息********/
    public function getexchange($openid,$toopenid,$which) {
		global $_GPC, $_W;
		$weid = $_W['weid'];
        $tablename = tablename("hongniangexchangelog");
        $sql = 'SELECT * FROM ' . $tablename . ' WHERE openid=:openid AND weid=:weid AND twhichone=:twhichone AND toopenid=:toopenid';
        $arr = array(
            ":openid" => $openid,
			":weid" => $_W['weid'],
			":twhichone" =>$which,
			":toopenid"=>$toopenid,
        );
        $res = pdo_fetch($sql, $arr);
        return $res;
    }
	 /*******获取所有已经兑换的类目********/
    public function getexchangetitle($openid,$toopenid) {
		global $_GPC, $_W;
		$weid = $_W['weid'];
        $tablename = tablename("hongniangexchangelog");
        $sql = 'SELECT * FROM ' . $tablename . ' WHERE openid=:openid AND weid=:weid AND toopenid=:toopenid';
        $arr = array(
            ":openid" => $openid,
			":weid" => $_W['weid'],
			":toopenid"=>$toopenid
        );
        $res = pdo_fetchall($sql, $arr);
        return $res;
    }
	
	//取得访问对象的全部照片
	 public function doMobilegetsomephotosajax() {
		global $_GPC, $_W;
		$weid = $_W['weid'];
		$status = 1;
		$openid = $_POST['toopenid'];
		if(!empty($openid)){
				$tablename = tablename("meepohongniangphotos");
				$sql = 'SELECT url FROM ' . $tablename . ' WHERE from_user=:from_user AND weid=:weid  AND status=:status ORDER BY time DESC';
				$arr = array(
					":from_user" => $openid,
					":weid" => $_W['weid'],
					":status"=>$status
				);
				$res = pdo_fetchall($sql, $arr);
				if(!empty($res)){
					foreach($res as $row){
					   $newres[] = $_W['attachurl'].$row['url'];
					}
				  echo json_encode($newres);
				}else{
				   echo '0';
				}
		}else{
		  echo '0';
		}
    }
	/*******获取当前fans的上传的个人照片********/
    public function getphotos($openid) {
		global $_GPC, $_W;
		$weid = $_W['uniacid'];
		$status = 1;
        $tablename = tablename("meepohongniangphotos");
        $sql = 'SELECT * FROM ' . $tablename . ' WHERE from_user=:from_user AND weid=:weid  AND status=:status ORDER BY time DESC';
        $arr = array(
            ":from_user" => $openid,
			":weid" => $_W['weid'],
			":status"=>$status
        );
        $res = pdo_fetchall($sql, $arr);
        return $res;
    }
	public function getallphotos($openid) {
		global $_GPC, $_W;
		$weid = $_W['uniacid'];
        $tablename = tablename("meepohongniangphotos");
        $sql = 'SELECT * FROM ' . $tablename . ' WHERE from_user=:from_user AND weid=:weid  ORDER BY time DESC';
        $arr = array(
            ":from_user" => $openid,
			":weid" => $_W['weid'],
			":status"=>$status
        );
        $res = pdo_fetchall($sql, $arr);
        return $res;
    }
	public function getarea($openid) {
		global $_GPC, $_W;
		$weid = $_W['weid'];
        $tablename = tablename("meepo_hongniangarea");
        $sql = 'SELECT * FROM ' . $tablename . ' WHERE openid=:openid AND weid=:weid';
        $arr = array(
            ":openid" => $openid,
			":weid" => $_W['weid']
        );
        $res = pdo_fetch($sql, $arr);
        return $res;
    }
    /*********获取当前粉丝的所有情书以及发送者信息*********/
    public function getallmails($openid) {
		global $_GPC, $_W;
		$weid = $_W['weid'];
        $tablename = tablename("meepo_hongniangmails");
        $sql = 'SELECT * FROM ' . $tablename . ' WHERE toopenid=:toopenid AND weid=:weid ORDER BY time DESC';
        $arr = array(
            ":toopenid" => $openid,
			":weid"=>$weid
        );
        $res = pdo_fetchall($sql, $arr);
        return $res;
    }
    /***********获取我喜欢的所有对象************/
    public function getallmylike($openid) {
		 global $_GPC, $_W;
		$weid = $_W['weid'];
        $tablename = tablename("meepo_hongnianglikes");
        $sql = 'SELECT * FROM ' . $tablename . ' WHERE openid=:openid AND weid=:weid  ORDER BY createtime DESC';
        $arr = array(
            ":openid" => $openid,
			":weid"=>$weid
        );
        $res = pdo_fetchall($sql, $arr);
        return $res;
    }
    /********获取所有喜欢我的*************/
    public function getalllikeme($toopenid) {
         global $_GPC, $_W;
		$weid = $_W['weid'];
        $tablename = tablename("meepo_hongnianglikes");
        $sql = 'SELECT * FROM ' . $tablename . ' WHERE toopenid=:toopenid AND weid=:weid ORDER BY createtime DESC';
        $arr = array(
            ":toopenid" => $toopenid,
			":weid"=>$weid
        );
        $res = pdo_fetchall($sql, $arr);
        return $res;
    } 
    //后台只有一个操作那就是审核机制
    public function doWebList() {
        //这个操作被定义用来呈现 管理中心导航菜单
        global $_GPC, $_W;
		$weid = $_W['weid'];
        checklogin();
        if (checksubmit('verify') && !empty($_GPC['select'])) {
            pdo_update('hnfans', array('isshow' => 1) , " id  IN  ('" . implode("','", $_GPC['select']) . "')");
            message('审核成功！', $this->createWebUrl('list', array('page' => $_GPC['page'])),'sucess');
        }
        if (checksubmit('delete') && !empty($_GPC['select'])) {
            pdo_delete('hnfans', " id  IN  ('" . implode("','", $_GPC['select']) . "')");
            message('删除成功！', $this->createWebUrl('list', array('page' => $_GPC['page'] )),'sucess');
        }
		load()->func('tpl');
		$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $isshow = isset($_GPC['isshow']) ? intval($_GPC['isshow']) : 0;
		if($op=='display'){
				$pindex = max(1, intval($_GPC['page']));
				$psize = 20;
				$condition = '';
				if(!empty($_GPC['type']) && $_GPC['type']=='update'){
					 $updatelist = pdo_fetchall("SELECT * FROM " . tablename('hnfans') . " WHERE telephone!=:telephone and weid=:weid",array(':telephone'=>'',':weid'=>$weid));
					 
					 if(!empty($updatelist) && is_array($updatelist)){
						  load()->model('mc');
						  foreach($updatelist as $row){
							 $data = array(
							   'realname'=>$row['realname'],
								'telephone'=>$row['telephone'],
								'gender'=>$row['gender'],
								'constellation'=>$row['constellation'],
								'height'=>$row['height'],
								'weight'=>$row['weight'],
								'education'=>$row['education'],
								'revenue'=>$row['revenue'],
								'affectivestatus'=>$row['affectivestatus'],
								'occupation'=>$row['occupation'],
								'lookingfor'=>$row['lookingfor'],
							
							);
							$uid = pdo_fetchcolumn("SELECT uid FROM ".tablename('mc_mapping_fans')." WHERE openid=:openid AND uniacid=:uniacid",array(':openid'=>$row['from_user'],':uniacid'=>$weid));
							if(!empty($uid)){
							  mc_update($uid,$data);

							}
						  }
						  message('同步成功',$this->createWebUrl('list'),'success');
					 }

				}
				if (!empty($_GPC['keyword'])) {
						$condition .= " AND nickname LIKE '%{$_GPC['keyword']}%'";
				}
				if (!empty($_GPC['yingcang'])) {
						$yingcang = intval($_GPC['yingcang']);
						$condition .= " AND yingcang = '{$yingcang}'";
				}
				if (!empty($_GPC['tuijian'])) {
						$tuijian = intval($_GPC['tuijian']);
						$condition .= " AND tuijian = '{$tuijian}'";
				}
				if(!empty($_GPC['telephone'])){
					 $telephone = trim($_GPC['telephone']);
					 $condition .= " AND telephone LIKE '%{$_GPC['telephone']}%'";
				} 
				if(!empty($_GPC['datelimit'])){  
				   if($_GPC['datelimit']['start']!='1970-01-01' && $_GPC['datelimit']['end']!='1970-01-01'){
					   $starttime = strtotime($_GPC['datelimit']['start']);
					   $endtime = strtotime($_GPC['datelimit']['end']);
					   $_GPC['starttime'] = $starttime;
					   $_GPC['endtime'] = $endtime;
					   $condition .= " AND time >= {$starttime} AND time <= {$endtime}";
				  
				   }
				}
				$list = pdo_fetchall("SELECT * FROM " . tablename('hnfans') . " WHERE nickname!='' and isshow='{$isshow}'  and weid='{$weid}' {$condition} ORDER BY CASE  WHEN telephone !='' THEN  null  ELSE 1  END,time DESC LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
				if (!empty($list)) {
					$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('hnfans') . " WHERE nickname!='' AND  isshow={$isshow} AND weid={$weid}");
					$pager = pagination($total, $pindex, $psize);
				}
		}elseif($op == 'post'){
		     $id = intval($_GPC['id']);
			 if(empty($id)){
			    message('参数错误');
			 }
			 $user = pdo_fetch("SELECT * FROM " . tablename('hnfans') . " WHERE weid=:weid AND id=:id",array(':weid'=>$weid,':id'=>$id));
			 $sql = "SELECT uid FROM ".tablename('mc_mapping_fans')." WHERE openid = :openid AND uniacid = :uniacid";
			 $paras = array(':uniacid'=>$weid,':openid'=>$user['from_user']);
		     $uid = pdo_fetchcolumn($sql,$paras);
			 load()->model('mc');
             $member = mc_fetch($uid);
			
			 if(!empty($_GPC['userid'])){
				 $data = array(
				    'nickname'=>$_GPC['nickname'],
					 'realname'=>$_GPC['realname'],
					 'gender'=>intval($_GPC['gender']),
					 'telephone'=>$_GPC['telephone'],
					 'qq'=>$_GPC['qq'],
					 'wechat'=>$_GPC['wechat'],
				 );
			    pdo_update('hnfans',$data,array('id'=>intval($_GPC['userid']),'weid'=>$weid));
				pdo_update('mc_members',array('credit1'=>$_GPC['credit1']),array('uid'=>$uid,'uniacid'=>$weid));
				message('保存成功！',$this->createWebUrl('list',array('op'=>'post','id'=>$user['id'])),'success');
			 }
		}else{
		  message('未知操作');
		}
        include $this->template('list');
    }
	//查询某个人的
	//照片列表
	public function doWebPhotolist() {
        global $_GPC, $_W;
		$weid = $_W['uniacid'];
        checklogin();
        if (checksubmit('verify') && !empty($_GPC['select'])) {
            pdo_update('meepohongniangphotos', array('status' => 1) , " id  IN  ('" . implode("','", $_GPC['select']) . "')");
            message('审核成功！', $this->createWebUrl('Photolist', array('page' => $_GPC['page'])),'sucess');
        }
        if (checksubmit('delete') && !empty($_GPC['select'])) {
            pdo_delete('meepohongniangphotos', " id  IN  ('" . implode("','", $_GPC['select']) . "')");
			if(is_array($_GPC['select'])){
			   foreach($_GPC['select'] as $row){
			         $imgurl = pdo_fetch("SELECT * FROM ". tablename('meepohongniangphotos') ."WHERE weid={$weid} AND id=:id",array(":id"=>$row));
					 load()->func('file');
					 file_delete($imgurl['url']);
			   }
			}
            message('删除成功！', $this->createWebUrl('Photolist', array('page' => $_GPC['page'] )),'sucess');
        }
		$condition = '';
		if(!empty($_GPC['nickname'])){
		   $nickname = $_GPC['nickname'];
		   $from_user = pdo_fetchcolumn("SELECT from_user FROM ".tablename('hnfans')." WHERE weid='{$weid}' AND nickname LIKE '%{$_GPC['nickname']}%'");
		   $condition .= " AND from_user = '{$from_user}'";
		}
		if(!empty($_GPC['datelimit'])){  
				  
					   $starttime = strtotime($_GPC['datelimit']['start']);
					   $endtime = strtotime($_GPC['datelimit']['end']);
					   //$_GPC['starttime'] = $starttime;
					   //$_GPC['endtime'] = $endtime;
					   $condition .= " AND time >= {$starttime} AND time <= {$endtime}";
				  
				   
			}
			
			
        $status = isset($_GPC['status']) ? intval($_GPC['status']) : 0;
        $pindex = max(1, intval($_GPC['page']));
        $psize = 5;
        $list = pdo_fetchall("SELECT * FROM " . tablename('meepohongniangphotos') . " WHERE  status='{$status}'  and weid='{$weid}' $conditon ORDER BY time DESC LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
		$total = pdo_fetchcolumn("SELECT * FROM " . tablename('meepohongniangphotos') . " WHERE  status='{$status}'  and weid='{$weid}' $conditon ORDER BY time DESC");
        $pager = pagination($total, $pindex, $psize);
        if (!empty($list)) {
			if(!empty($list)){
					foreach($list as $arr){
						  $userinfo[$arr['from_user']] = pdo_fetch("SELECT * FROM ". tablename('hnfans') ."WHERE weid='{$weid}' AND from_user=:from_user",array(":from_user"=>$arr['from_user']));
					}
	        }
			$a = "SELECT COUNT(*) FROM " . tablename('meepohongniangphotos') . " WHERE   status='{$status}' AND weid='{$weid}' $conditon";
			
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('meepohongniangphotos') . " WHERE   status='{$status}' AND weid='{$weid}' $conditon");
            $pager = pagination($total, $pindex, $psize);
        }
		include $this->template('photolist');
	}
	//聊天记录 chatcontent
    public function doWebchatcontent() {
        global $_GPC, $_W;
		$weid = $_W['weid'];
        checklogin();
        if (checksubmit('delete') && !empty($_GPC['select'])) {
            pdo_delete('hnmessage', " id  IN  ('" . implode("','", $_GPC['select']) . "')");
			
            message('删除成功！', $this->createWebUrl('chatcontent', array('page' => $_GPC['page'] )),'success');
        }
		$condition = '';
		if(!empty($_GPC['nickname'])){
		   $nickname = $_GPC['nickname'];
		   $condition .= " AND sendernickname LIKE '%{$_GPC['nickname']}%'";
		}
		if(!empty($_GPC['tonickname'])){
		   $tonickname = $_GPC['tonickname'];
		   $geter = pdo_fetchcolumn("SELECT from_user FROM ".tablename('hnfans')." WHERE weid='{$weid}' AND nickname LIKE '%{$_GPC['tonickname']}%'");
		   
		     $condition .= " AND geter = '{$geter}'";
		   
		}
        $status = isset($_GPC['status']) ? intval($_GPC['status']) : 0;
        $pindex = max(1, intval($_GPC['page']));
        $psize = 10;
		
        $list = pdo_fetchall("SELECT * FROM " . tablename('hnmessage') . " WHERE  weid={$weid} $condition ORDER BY stime DESC LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
        if (!empty($list)) {
			if(!empty($list)){
					foreach($list as $arr){
						  $userinfo[$arr['geter']] = pdo_fetch("SELECT * FROM ". tablename('hnfans') ."WHERE weid={$weid} AND from_user=:from_user",array(":from_user"=>$arr['geter']));
					}
	        }
			
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('hnmessage') . " WHERE   weid={$weid} $condition");
            $pager = pagination($total, $pindex, $psize);
        }
		include $this->template('chatmessage');
	}
    public function doWebonoff() {
        global $_GPC, $_W;
        $weid = $_W['weid'];
        $tablename = tablename('meepo_hongniangonoff');
        $sql = 'SELECT * FROM ' . $tablename . ' WHERE weid=:weid';
        $arr = array(
            ':weid' => $weid
        );
        $res = pdo_fetch($sql, $arr);
		if (!empty($_POST)) {
            $data = array(
                "status" => intval($_POST['status']) ,
                "weid" => $weid
            );
            if (empty($res)) {
                pdo_insert("meepo_hongniangonoff", $data);
				    if ($_POST['status'] == '1') {
                        message("设置成功,您已经开启了审核",$this->createWebUrl('onoff'),'success');
                    } else {
                        message("设置成功,您已经关闭了审核",$this->createWebUrl('onoff'),'success');
                    }
            } else {
                      pdo_update("meepo_hongniangonoff",array('status'=>$data['status']), array(
                            'id' => $res['id'],'weid' => $weid
                        ));
                    if ($_POST['status'] == '1') {
                        
                        message("设置成功,您已经开启了审核",$this->createWebUrl('onoff'),'success');
                    } else {
                        
                        message("设置成功,您已经关闭了审核",$this->createWebUrl('onoff'),'success');
                    }
                
            }
        }
        if(empty($res['id'])){
		    $res['status']=0;
		}
        include $this->template("onoff");
    }
    public function doWebpay_set(){
    	global $_W,$_GPC;
    	$weid = $_W['weid'];
		
    	$settings = pdo_fetch("SELECT * FROM ".tablename('meepo_hongniangset')." WHERE weid='{$weid}'");
    	if (!empty($_POST)) {
			$id = intval($_GPC['id']);
    		$data = array(
    			'pay_telephone'=>intval($_GPC['pay_telephone']),
    			'pay_height'=>intval($_GPC['pay_height']),
    			'pay_weight'=>intval($_GPC['pay_weight']),
    			'pay_carhouse'=>intval($_GPC['pay_carhouse']),
    			'pay_uheight'=>intval($_GPC['pay_uheight']),
    			'pay_uage'=>intval($_GPC['pay_uage']),
    			'pay_Descrip'=>intval($_GPC['pay_Descrip']),
				'pay_uitsOthers'=>intval($_GPC['pay_uitsOthers']),
				'pay_occupation'=>intval($_GPC['pay_occupation']),
				'pay_revenue'=>intval($_GPC['pay_revenue']),
				'pay_affectivestatus'=>intval($_GPC['pay_affectivestatus']),
				'pay_lxxingzuo'=>intval($_GPC['pay_lxxingzuo']),
				'share_jifen'=>intval($_GPC['share_jifen']),
    			'pay_all'=>intval($_GPC['pay_all']),
				'pay_qq'=>intval($_GPC['pay_qq']),
				'pay_wechat'=>intval($_GPC['pay_wechat']),
				'weid'=>$_W['weid'],
    		);
    		if (empty($id) || $id==0) {
				pdo_insert("meepo_hongniangset", $data);
				
				message('保存成功',referer());
			}else{
				 pdo_update("meepo_hongniangset", $data,array('id' =>$id));
				 message('更新成功',referer());
			}
    	}
    	include $this->template('set2');
    }

	public function doWebSet(){
		global $_GPC, $_W;
		$weid = $_W['weid'];
		//$check = $this->docheckurl();			 
		if($check){
		  die('No pass');  
		}
		$tablename = tablename('meepo_hongniangset');
		
		$sql = "SELECT * FROM " . $tablename . "WHERE weid='{$_W['weid']}'";
        $settings = pdo_fetch($sql);
		load()->func('tpl');
		if (!empty($_POST)) {
			$id = intval($_GPC['id']);
			$data = array(
				'share_title'=>$_GPC['share_title'],
				'share_logo'=>$_GPC['share_logo'],
				'share_link'=>$_GPC['share_link'],
				'share_content'=>$_GPC['share_content'],
				'title'=>$_GPC['title'],
				'headtitle'=>$_GPC['headtitle'],
				'header_ads'=>$_GPC['header_ads'],
				'header_adsurl'=>$_GPC['header_adsurl'],
				'logo'=>$_GPC['logo'],
				'url'=>$_GPC['url'],
				'hnages'=>trim($_GPC['hnages']),
				'weid'=>$_W['weid'],

			);
			if (empty($id) || $id==0) {
				pdo_insert("meepo_hongniangset", $data);
				
				message('保存成功',$this->createWebUrl('set'),'success');
			}else{
				 pdo_update("meepo_hongniangset", $data, array('id' =>$id));
				 message('更新成功',$this->createWebUrl('set'),'success');
			}
			
		}
		if(empty($settings)){
			$settings['title'] ='相亲、交友';
			$settings['headtitle'] ='亲们，为了更好的找到属于自己的TA,一定要先去完善自己的资料';
			$settings['share_title'] = '';
			$settings['share_content'] = '';
			$settings['share_link'] = str_replace('./','',$_W['siteroot'].'app/'.$this->createMobileUrl('Alllist'));
			$settings['url'] = "";
			$settings['hnages'] = "21,22,23,24,25,26";
			$settings['header_adsurl'] = "";
		}
		include $this->template("set");
	}
	public function getset(){
	     global $_GPC, $_W;
		$tablename = tablename("meepo_hongniangset");
		$sql = "SELECT * FROM " . $tablename . "WHERE weid='{$_W['uniacid']}'";
		$settings = pdo_fetch($sql);
		return $settings;
	}
	//分享链接奖励积分
	public function doMobileshareajax(){
	     global $_W, $_GPC;
		 $weid = $_W['weid'];
		 $settings = pdo_fetch("SELECT * FROM ".tablename('meepo_hongniangset')." WHERE weid=:weid",array(':weid'=>$_W['weid']));
		 $cfg = $this->module['config'];
		 $share_num = isset($cfg['share_num']) ? $cfg['share_num'] : 3 ;//取得配置总数 默认三次
		 $openid = $_W['openid'];
			   if(!empty($settings['share_jifen']) && $settings['share_jifen']!='0'){
				      $todaytimestamp = strtotime(date('Y-m-d'));
			          $all = pdo_fetchall("SELECT * FROM ".tablename('hongniangsharelogs')." WHERE weid=".$weid."  AND openid='".$openid."' AND sharetime >= '".$todaytimestamp."'");
					   $sharenum = count($all);//今日分享次数
					   if($sharenum<$share_num){
				              
						       $touid = $_W['member']['uid'];
					          $share_jifen = intval($settings['share_jifen']);
					           $result = pdo_query("UPDATE ".tablename('mc_members')." SET credit1 = credit1 + '{$share_jifen}' WHERE uid = '{$touid}' AND uniacid='{$weid}' ");
					          
                              if($result){
								  $data = array('openid'=>$openid,'weid'=>$weid,'openid'=>$openid,'jljifen'=>$settings['share_jifen'],'sharetime'=>time());
					               pdo_insert("hongniangsharelogs",$data);
								    $all2 = pdo_fetchall("SELECT * FROM ".tablename('hongniangsharelogs')." WHERE weid=".$weid."  AND openid='".$openid."' AND sharetime >= '".$todaytimestamp."'");
					                $sharenum2 = count($all2);//今日分享次数
									$othernum = $share_num-$sharenum2;//今日还有多少次机会
								  echo $othernum;
					          }else{
						          echo 'no';
					          }
					   }else{
					       echo 'over'; 
					   }  
			   }
	   
	}
	public function doMobilepay(){
		global $_W, $_GPC;
		$weid = $_W['weid'];
		checkAuth();
		$openid = $_W['openid'];
		load()->model('mc');
		if (!empty($_W['member']['uid'])) {
			$member = mc_fetch($_W['member']['uid']);
		}else{
		   die('false');
		}
		$to = trim($_GPC['to']);//付积分给谁
		if(!empty($to)){
			$tsql = "SELECT uid FROM ".tablename('mc_mapping_fans')." WHERE openid = :openid AND uniacid = :uniacid";
			$tparas = array(':uniacid'=>$weid,':openid'=>$to);
		    $touids = pdo_fetch($tsql,$tparas);
			$touid = $touids['uid'];
		}else{
		  die('false');
		}
		if($to==$openid){
		  die("no");
		}
		$payment = !empty($_GPC['payment'])?intval($_GPC['payment']):'0';
		$type = $_GPC['type'];//1为积分，2为余额
		$option = $_GPC['option'];//1为增加，2为减少
		$whichone = $_GPC['whichone'];//兑换哪个
		if($whichone=="carhouse"){
            $whichone = 'carstatus';
		}
		if($whichone=="uheight"){
		   $whichone ='uheightL';
		}
		//查询对方是否完善了资料
		$userinfo  = $this->getusers($weid,$to);
		if(empty($userinfo[$whichone]) || $userinfo[$whichone]=='0'){
		    die("sorry");
		}
		if(empty($option)){
		   $option = '1';
		}
		if(empty($type)){
		   $type = '1';
		}
        //防止重复兑换扣积分
        $exchangeres2 = $this->getexchange($openid,$to,$whichone);
		if(!empty($exchangeres2)){
		  die("over");
		}
		//$fans = $this->getusers($weid,$openid);;
		if($option == '1'){
			//增加
			if($type == '1'){
				//积分credit1
				$credit1 = $member['credit1'];
				if($credit1 < $payment){
					die('low');
				}
				if(pdo_query("UPDATE ".tablename('mc_members')." SET credit1 = credit1 - '{$payment}' WHERE uid = '{$_W['member']['uid']}' AND uniacid='{$weid}'")){
					//增加积分
					if(pdo_query("UPDATE ".tablename('mc_members')." SET credit1 = credit1 + '{$payment}' WHERE uid = '{$touid}' AND uniacid='{$weid}' ")){
							 //表名hongniangexchangelog
						    //插入数据
							//先查询是否已经兑换 
							$exchangeres = $this->getexchange($openid,$to,$whichone);
                             if(empty($exchangeres)){
								$data = array(
								  'openid'=>$openid,
								  'toopenid'=>$to,
								  'twhichone'=>$whichone,
									'credit'=>intval($payment),
									'weid' =>intval($weid),
									'createtime'=>time(),
								);
						        pdo_insert("hongniangexchangelog",$data);//插入数据库兑换的距离内容【项目，积分，当事人 对方  时间】
							 }
							   if($whichone=="carstatus"){
							      $userinfo[$whichone] = $userinfo['carstatus'].'、'.$userinfo['housestatus'];
								  die($userinfo[$whichone]);
							   }
							   elseif($whichone=="height"){
							     die($userinfo[$whichone]."cm");
							   }
							   elseif($whichone=="weight"){
								    if($userinfo[$whichone] == '401'){
									    die('40kg以下');
									  }elseif($userinfo[$whichone] == '701'){
									    die('70kg以上');
									  }else{
									   die($userinfo[$whichone]."kg");
							          }
							     
							   }
							   elseif($whichone=="uheight"){
							     die($userinfo['uheightL']."cm~~".$userinfo['uheightH']);
							   }
							   elseif($whichone=="uage"){
								   if(strlen($userinfo[$whichone])==2){
							        die($userinfo[$whichone]."岁");
								   }else{
								      if($userinfo[$whichone] == '1825'){
									    die('18-25岁');
									  }elseif($userinfo[$whichone] == '2635'){
									    die('26-35岁');
									  }elseif($userinfo[$whichone] == '3645'){
									   die('36-45岁');
							          }elseif($userinfo[$whichone] == '4655'){
									    die('46-55岁');
				                      }else{
									    die('55岁以上');
								      }
								   }
							   }
							   else{
							     die($userinfo[$whichone]);
							   }
						          
						
					}else{
						die('false');
					}
				}else{
					die('false');
				}
				
			}elseif($type=='2'){
				$credit2 = intval($fans['credit2']);
				if($credit2 < $payment){
					die('余额不足，账户余额为'.$credit1.'元!');
				}
				if(pdo_query("UPDATE ".tablename('hnfans')." SET credit2 = credit2 - '{$payment}' WHERE from_user = '{$openid}'")){
					//增加积分
					if(pdo_query("UPDATE ".tablename('hnfans')." SET credit2 = credit2 + '{$payment}' WHERE from_user = '{$to}'")){
						die('success');
					}else{
						die('false');
					}
				}else{
					die('false');
				}
			
			}
		}elseif($option == '2'){
			//更新奖励积分
			if($type == '1'){
				if(pdo_query("UPDATE ".tablename('hnfans')." SET credit1 = credit1 + '{$payment}' WHERE from_user = '{$openid}'")){
					die('success');
				}else{
					die('false');
				}
			}elseif($type == '2'){
			if(pdo_query("UPDATE ".tablename('hnfans')." SET credit2 = credit2 + '{$payment}' WHERE from_user = '{$openid}'")){
					die('success');
				}else{
					die('false');
				}
			}
		}
		
	}
	public function checkAuth() {
		global $_W;
		checkauth();
	}
	public function doMobilePayjifen() {
		global $_W, $_GPC;
		if (empty($_W['member']['uid'])) {
			checkauth();
		}
		$weid = $_W['uniacid'];
		$openid = $_W['openid'];
		if(empty($openid)){
		   die('请重新从微信进入！');
		}
		$settings = pdo_fetch("SELECT * FROM ".tablename('meepo_hongniangset')." WHERE weid=:weid",array(':weid'=>$_W['weid']));
		$cfg = $this->module['config'];
		$username = $this->getusers($weid,$openid);
	    include $this->template('recharge');
		
	}
	/******包月服务*****/
	public function doMobilebaoyue() {
		global $_W, $_GPC;
		if (empty($_W['member']['uid'])) {
			checkauth();
		}
		$weid = $_W['uniacid'];
		$openid = $_W['openid'];
		if(empty($openid)){
		   message('请重新从微信进入！');
		}
		$baoyue = pdo_fetchcolumn("SELECT endtime FROM ".tablename('meepohn_baoyue')." WHERE openid=:openid AND weid=:weid ORDER BY endtime DESC",array(':weid'=>$weid,':openid'=>$openid));
	    if(empty($baoyue) || TIMESTAMP > $baoyue){
		}else{
		  message('你的包月服务未过期、无需再次开通！','referer','info');
		}
		$cfg = $this->module['config'];
		$money = !empty($cfg['baoyue']) ? intval($cfg['baoyue']) : 100;
		$username = $this->getusers($weid,$openid);
	    include $this->template('baoyue');
		
	}
    public function doMobilePayjifen2() {
		global $_W, $_GPC;
		$this->checkAuth();
		$openid = $_W['openid'];
		$weid = $_W['uniacid'];
		$cfg = $this->module['config'];
		$money = intval($_GPC['money']);
		$num = $money/$cfg['bilv'];
		if($money >= $cfg['bilv'] && is_int($num)){
			$params['tid'] = date('YmdHi').random(10, 1);
		    $params['user'] = $_W['fans']['from_user'];
		    $params['fee'] = $money/$cfg['bilv'];
			$params['title'] = $_W['account']['name'];
			$params['ordersn'] = time();
			$params['virtual'] =  true;
			
			include $this->template('pay');
		}else{
		     message('你输入的积分数量有误，请核实！', url('entry', array('m' => 'meepo_weixiangqin', 'do' => 'Payjifen')), 'error');
		}
	}
	public function doMobilebaoyue2() {
		global $_W, $_GPC;
		$this->checkAuth();
		$openid = $_W['openid'];
		if(empty($openid)){
		   message('请重新从微信进入！');
		}
		$weid = $_W['uniacid'];
		$cfg = $this->module['config'];
		$money = !empty($cfg['baoyue']) ? intval($cfg['baoyue']) : 100;
		$params['tid'] = '||'.date('YmdHi').random(10, 1);
		$params['user'] = $openid;
		$params['fee'] = $money;
		$params['title'] = $_W['account']['name'];
		$params['ordersn'] = time();
		$params['virtual'] =  true;
		include $this->template('pay');
	}
	public function payResult($params) {
		global $_W, $_GPC;
		//$this->checkAuth();
		$weid = $_W['uniacid'];
		$uid = $_W['member']['uid'];
		$openid = $_W['openid'];
		if(empty($openid)){
		    $tsql = "SELECT openid FROM ".tablename('mc_mapping_fans')." WHERE uid = :uid AND uniacid = :uniacid";
			$tparas = array(':uniacid'=>$weid,':uid'=>$uid);
		    $topenid = pdo_fetch($tsql,$tparas);
			$openid = $topenid['openid'];
		}
		$cfg = $this->module['config'];
		if(empty($openid)){
		   message('身份失效，请重新进入！');
		}else{
		   $res = $this->getusers($weid,$openid);
		}
		$fee = intval($params['fee']);
		$data = array('status' => $params['result'] == 'success' ? 1 : 0);
		$paytype = array('credit' => '1', 'wechat' => '2', 'alipay' => '2', 'delivery' => '3');
		$data['paytype'] = $paytype[$params['type']];
		if ($params['type'] == 'wechat') {
			$data['transid'] = $params['tag']['transaction_id'];
		}
		if ($params['type'] == 'delivery') {
			$data['status'] = 1;
		}
		$data['fee'] = $fee;//已经兑换成RMB
		$data['openid'] = $openid;
		$data['time'] = time();
		$data['avatar'] = $res['avatar'];
		$data['weid'] = $weid;
		//也需将订单编号 插入数据库
		$data['tid'] = $params['tid'];
		$addcredit = $fee*$cfg['bilv'];
				if ($params['from'] == 'return') {
					  if(!strexists($params['tid'],"||")){
					   pdo_insert('hnpayjifen',$data);
					   load()->model('mc');
					   mc_credit_update($uid,'credit1',$addcredit, $log = array());
					  }else{
						 $data['starttime'] = time();
						 $data['endtime'] = time()+3600*720;
						 pdo_insert('meepohn_baoyue',$data);
					  }
						$setting = uni_setting($_W['uniacid'], array('creditbehaviors'));
						$credit = $setting['creditbehaviors']['currency'];
						if ($params['type'] == $credit) {
							message('支付成功！', $this->createMobileUrl('homecenter'), 'success');
						} else {
							message('支付成功！', '../../app/' . $this->createMobileUrl('homecenter'), 'success');
						}
				}
		
	}
	/*****************20150407增加活动选项**********************************************************************/
	public function getHomeTiles() {
        global $_W;
        $urls = array();
        $list = pdo_fetchall("SELECT title, reid FROM " . tablename('hnresearch') . " WHERE weid = '{$_W['uniacid']}'");
        if (!empty($list)) {
            foreach ($list as $row) {
                $urls[] = array('title' => $row['title'], 'url' => $_W['siteroot']."app/".$this->createMobileUrl('research', array('id' => $row['reid'])));
            }
        }
        return $urls;
    }

    public function doWebQuery() {
        global $_W, $_GPC;
        $kwd = $_GPC['keyword'];
        $sql = 'SELECT * FROM ' . tablename('hnresearch') . ' WHERE `weid`=:weid AND `title` LIKE :title ORDER BY reid DESC LIMIT 0,8';
        $params = array();
        $params[':weid'] = $_W['uniacid'];
        $params[':title'] = "%{$kwd}%";
        $ds = pdo_fetchall($sql, $params);
        foreach ($ds as &$row) {
            $r = array();
            $r['title'] = $row['title'];
            $r['description'] = cutstr(strip_tags($row['description']), 50);
            $r['thumb'] = $row['thumb'];
            $r['reid'] = $row['reid'];
            $row['entry'] = $r;
        }
        include $this->template('hnquery');
    }

    public function doWebDetail() {
        global $_W, $_GPC;
        $rerid = intval($_GPC['id']);
        $sql = 'SELECT * FROM ' . tablename('hnresearch_rows') . " WHERE `rerid`=:rerid";
        $params = array();
        $params[':rerid'] = $rerid;
        $row = pdo_fetch($sql, $params);
        if (empty($row)) {
            message('访问非法.');
        }
        $sql = 'SELECT * FROM ' . tablename('hnresearch') . ' WHERE `weid`=:weid AND `reid`=:reid';
        $params = array();
        $params[':weid'] = $_W['uniacid'];
        $params[':reid'] = $row['reid'];
        $activity = pdo_fetch($sql, $params);
        if (empty($activity)) {
            message('非法访问.');
        }
        $sql = 'SELECT * FROM ' . tablename('hnresearch_fields') . ' WHERE `reid`=:reid ORDER BY `refid`';
        $params = array();
        $params[':reid'] = $row['reid'];
        $fields = pdo_fetchall($sql, $params);
        if (empty($fields)) {
            message('非法访问.');
        }
        $ds = $fids = array();
        foreach ($fields as $f) {
            $ds[$f['refid']]['fid'] = $f['title'];
            $ds[$f['refid']]['type'] = $f['type'];
            $ds[$f['refid']]['refid'] = $f['refid'];
            $fids[] = $f['refid'];
        }
        $fids = implode(',', $fids);
        $row['fields'] = array();
        $sql = 'SELECT * FROM ' . tablename('hnresearch_data') . " WHERE `reid`=:reid AND `rerid`='{$row['rerid']}' AND `refid` IN ({$fids})";
        $fdatas = pdo_fetchall($sql, $params);

        foreach ($fdatas as $fd) {
            $row['fields'][$fd['refid']] = $fd['data'];
        }
        // 兼容会员居住地字段
        foreach ($ds as $value) {
            if ($value['type'] == 'reside') {
                $row['fields'][$value['refid']] = '';
                foreach ($fdatas as $fdata) {
                    if ($fdata['refid'] == $value['refid']) {
                        $row['fields'][$value['refid']] .= $fdata['data'];
                    }
                }
                break;
            }
        }
        include $this->template('hndetail');
    }

    public function doWebManage() {
        global $_W, $_GPC;
        $reid = intval($_GPC['id']);
					 
		if($check){
		die('NO pass');  
		}
        $sql = 'SELECT * FROM ' . tablename('hnresearch') . ' WHERE `weid`=:weid AND `reid`=:reid';
        $params = array();
        $params[':weid'] = $_W['uniacid'];
        $params[':reid'] = $reid;
        $activity = pdo_fetch($sql, $params);
        if (empty($activity)) {
            message('非法访问.');
        }
        $sql = 'SELECT * FROM ' . tablename('hnresearch_fields') . ' WHERE `reid`=:reid ORDER BY `refid`';
        $params = array();
        $params[':reid'] = $reid;
        $fields = pdo_fetchall($sql, $params);
        if (empty($fields)) {
            message('非法访问.');
        }
        $ds = array();
        foreach ($fields as $f) {
            $ds[$f['refid']] = $f['title'];
        }
        $starttime = empty($_GPC['daterange']['start']) ? strtotime('-1 month') : strtotime($_GPC['daterange']['start']);
        $endtime = empty($_GPC['daterange']['end']) ? TIMESTAMP : strtotime($_GPC['daterange']['end']) + 86399;
        $select = array();
        if (!empty($_GPC['select'])) {
            foreach ($_GPC['select'] as $field) {
                if (isset($ds[$field])) {
                    $select[] = $field;
                }
            }
        }
        $pindex = max(1, intval($_GPC['page']));
        $psize = 50;
        $sql = 'SELECT * FROM ' . tablename('hnresearch_rows') . " WHERE `reid`=:reid AND `createtime` > {$starttime} AND `createtime` < {$endtime} ORDER BY `createtime` DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
        $params = array();
        $params[':reid'] = $reid;
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('hnresearch_rows') . " WHERE `reid`=:reid AND `createtime` > {$starttime} AND `createtime` < {$endtime}", $params);
        $pager = pagination($total, $pindex, $psize);
        $list = pdo_fetchall($sql, $params);
		//得到当前活动名称
		 $sql2 = 'SELECT title FROM ' . tablename('hnresearch') . " WHERE `reid`=:reid";
	     $Thuodong = pdo_fetch($sql2, $params);
		if(is_array($list) && !empty($list)){
		   foreach($list as &$row){
		       $user = pdo_fetch("SELECT nickname,avatar FROM".tablename('hnfans')." WHERE from_user=:from_user AND     weid=:weid",array(':from_user'=>$row['openid'],':weid'=>$_W['uniacid']));
				 $row['nickname'] = $user['nickname'];
				 $row['avatar'] = $user['avatar'];
		   }
		}
        if ($select) {
            $fids = implode(',', $select);
            foreach ($list as &$r) {
                $r['fields'] = array();
                $sql = 'SELECT data, refid FROM ' . tablename('hnresearch_data') . " WHERE `reid`=:reid AND `rerid`='{$r['rerid']}' AND `refid` IN ({$fids})";
                $fdatas = pdo_fetchall($sql, $params);
                foreach ($fdatas as $fd) {
                    if (false == array_key_exists($fd['refid'], $r['fields'])) {
                        $r['fields'][$fd['refid']] = $fd['data'];
                    } else {
                        $r['fields'][$fd['refid']] .= '--' . $fd['data'];
                    }
                }
            }
        }

        /* 如果调查项目类型为图片，处理fields字段信息 */
        foreach ($list as $key => &$value) {
            if(is_array($value['fields'])){
            foreach ($value['fields'] as &$v) {
                $img = '<div align="center"><img src="';
                if (substr($v, 0, 6) == 'images') {
                    $v = $img . $_W['attachurl'] . $v . '" style="width:50px;height:50px;"/></div>';
                }
            }
            unset($v);
            }
        }
        if (checksubmit('export', 1)) {
            $sql = 'SELECT title FROM ' . tablename('hnresearch_fields') . " AS f JOIN " . tablename('hnresearch_rows') . " AS r ON f.reid='{$params[':reid']}' GROUP BY title ORDER BY refid DESC";
            $tableheader = pdo_fetchall($sql, $params);
            $tablelength = count($tableheader);
            $tableheader[] = array('title' => '报名时间');
			$tableheader[] = array('title' => '粉丝微信标识');
			$tableheader[] = array('title' => '粉丝微信昵称');
			$tableheader[] = array('title' => '本次活动名称');
            /* 获取预约数据 */
            $sql = 'SELECT * FROM ' . tablename('hnresearch_rows') . " WHERE `reid`=:reid AND `createtime` > {$starttime} AND `createtime` < {$endtime} ORDER BY `createtime` DESC";
            $params = array();
            $params[':reid'] = $reid;
            $list = pdo_fetchall($sql, $params);
			$sql2 = 'SELECT title FROM ' . tablename('hnresearch') . " WHERE `reid`=:reid";
			$huodongtitle = pdo_fetch($sql2, $params);
            if (empty($list)) {
                message('暂时没有数据');
            }
			if(is_array($list) && !empty($list)){
			   foreach($list as &$row){
			      $user = pdo_fetch("SELECT nickname FROM".tablename('hnfans')." WHERE from_user=:from_user AND weid=:weid",array(':from_user'=>$row['openid'],':weid'=>$_W['uniacid']));
				  $row['nickname'] = $user['nickname'];
			   }
			}
            foreach ($list as &$r) {
                $r['fields'] = array();
                $sql = 'SELECT data, refid FROM ' . tablename('hnresearch_data') . " WHERE `reid`=:reid AND `rerid`='{$r['rerid']}'";
                $fdatas = pdo_fetchall($sql, $params);
                foreach ($fdatas as $fd) {
                    $r['fields'][$fd['refid']] .= $fd['data'];
                }
            }
           
            /* 处理预约数据 */
            $data = array();
            foreach ($list as $key => $value) {
                if (!empty($value['fields'])) {
                    foreach ($value['fields'] as $field) {
                        $data[$key][] = str_replace(array("\n", "\r", "\t"), '', $field);
                    }
                }
                $data[$key]['createtime'] = date('Y-m-d H:i:s', $value['createtime']);
				$data[$key]['openid'] =  $value['openid'];
				$data[$key]['nickname'] =  $value['nickname'];
               
            }
            /* 输入到CSV文件 */
            $html = "\xEF\xBB\xBF";
            /* 输出表头 */
			$num = count($tableheader)-1;
            for($j=$num;$j >= 0;$j--) {
                $html .= $tableheader[$j]['title'] . "\t ,";
            }
            $html .= "\n";
            /* 输出内容 */
            foreach ($data as $value) {
				$html .= $huodongtitle['title'] . "\t ,";
				$html .= $value['nickname'] . "\t ,";
				$html .= $value['openid'] . "\t ,";
				$html .= $value['createtime'] . "\t ,";
                for ($i = 0; $i < $tablelength; $i++) {
                    $html .= $value[$i] . "\t ,";
                }	
                $html .= "\n";
            }
            /* 输出CSV文件 */
            header("Content-type:text/csv");
            header("Content-Disposition:attachment; filename=".$huodongtitle['title']."活动全部数据.csv");
            echo $html;
            exit();
        }
        include $this->template('hnmanage');
    }
/******************后台显示活动列表**************/
    public function doWebDisplay() {
        global $_W, $_GPC;
        if ($_W['ispost']) {
            $reid = intval($_GPC['reid']);
            $switch = intval($_GPC['switch']);
            $sql = 'UPDATE ' . tablename('hnresearch') . ' SET `status`=:status WHERE `reid`=:reid';
            $params = array();
            $params[':status'] = $switch;
            $params[':reid'] = $reid;
            pdo_query($sql, $params);
            exit();
        }
        $sql = 'SELECT * FROM ' . tablename('hnresearch') . ' WHERE `weid`=:weid';
        $status =$_GPC['status'];
        if($status!=''){
            $sql.=" and status=".intval($status);
        }
        $ds = pdo_fetchall($sql, array(':weid' => $_W['uniacid']));
        foreach ($ds as &$item) {
            $item['isstart'] = $item['starttime'] > 0;
            $item['switch'] = $item['status'];
            $item['link'] =$_W['siteroot']."app/".$this->createMobileUrl('research', array('id' => $item['reid']));
			$item['link'] = str_replace('./','',$item['link']);
        }
        include $this->template('hndisplay');
    }
/**************增加前台报名显示列表****************/
    public function doMobilehuodongindex() {
        global $_W, $_GPC;
		$cfg = $this->module['config'];
        $sql = 'SELECT * FROM ' . tablename('hnresearch') . ' WHERE `weid`=:weid ORDER BY reid desc';
        $ds = pdo_fetchall($sql, array(':weid' => $_W['uniacid']));
		$settings = pdo_fetch("SELECT * FROM ".tablename('meepo_hongniangset')." WHERE weid=:weid",array(':weid'=>$_W['weid']));
        foreach ($ds as &$item) {
            $item['isstart'] = $item['starttime'] > 0;
            $item['switch'] = $item['status'];
            $item['link'] =$_W['siteroot']."app/".$this->createMobileUrl('huodongcontent', array('id' => $item['reid']));
        }
        include $this->template('huodongindex');
    }
/************活动详情*************/
    public function doMobilehuodongcontent() {
        global $_W, $_GPC;
		$cfg = $this->module['config'];
		$id = $_GPC['id'];
        $sql = 'SELECT * FROM ' . tablename('hnresearch') . ' WHERE weid=:weid AND reid=:reid';
        $row = pdo_fetch($sql, array(':weid' => $_W['uniacid'],':reid'=>$id));
        $settings = pdo_fetch("SELECT * FROM ".tablename('meepo_hongniangset')." WHERE weid=:weid",array(':weid'=>$_W['weid']));
        include $this->template('huodongcontent');
    }
    public function doWebDelete() {
        global $_W, $_GPC;
        $reid = intval($_GPC['id']);
        if ($reid > 0) {
            $params = array();
            $params[':reid'] = $reid;
            $sql = 'DELETE FROM ' . tablename('hnresearch') . ' WHERE `reid`=:reid';
            pdo_query($sql, $params);
            $sql = 'DELETE FROM ' . tablename('hnresearch_rows') . ' WHERE `reid`=:reid';
            pdo_query($sql, $params);
            $sql = 'DELETE FROM ' . tablename('hnresearch_fields') . ' WHERE `reid`=:reid';
            pdo_query($sql, $params);
            $sql = 'DELETE FROM ' . tablename('hnresearch_data') . ' WHERE `reid`=:reid';
            pdo_query($sql, $params);
            message('操作成功.', referer());
        }
        message('非法访问.');
    }

    public function doWebResearchDelete() {
        global $_W, $_GPC;
        $id = intval($_GPC['id']);
        if (!empty($id)) {
            pdo_delete('hnresearch_rows', array('rerid' => $id));
        }
        message('操作成功.', referer());
    }

    public function doWebPost() {
        global $_W, $_GPC;
        $reid = intval($_GPC['id']);
        $hasData = false;
        if ($reid) {
            $sql = 'SELECT COUNT(*) FROM ' . tablename('hnresearch_rows') . ' WHERE `reid`=' . $reid;
            if (pdo_fetchcolumn($sql) > 0) {
                $hasData = true;
            }
        }
        if (checksubmit()) {
            $record = array();
            $record['title'] = trim($_GPC['activity']);
            $record['weid'] = $_W['uniacid'];
            $record['description'] = trim($_GPC['description']);
            $record['content'] = trim($_GPC['content']);
            $record['information'] = trim($_GPC['information']);
            if (!empty($_GPC['thumb'])) {
                $record['thumb'] = $_GPC['thumb'];
                load()->func('file');
                file_delete($_GPC['thumb-old']);
            }
            $record['status'] = intval($_GPC['status']);
            $record['inhome'] = intval($_GPC['inhome']);
            $record['pretotal'] = intval($_GPC['pretotal']);
            $record['starttime'] = strtotime($_GPC['starttime']);
            $record['endtime'] = strtotime($_GPC['endtime']);
            $record['noticeemail'] = trim($_GPC['noticeemail']);
            if (empty($reid)) {
                $record['status'] = 1;
                $record['createtime'] = TIMESTAMP;
                pdo_insert('hnresearch', $record);
                $reid = pdo_insertid();
                if (!$reid) {
                    message('保存失败, 请稍后重试.');
                }
            } else {
                if (pdo_update('hnresearch', $record, array('reid' => $reid)) === false) {
                    message('保存失败, 请稍后重试.');
                }
            } 
            if (!$hasData) {
                $sql = 'DELETE FROM ' . tablename('hnresearch_fields') . ' WHERE `reid`=:reid';
                $params = array();
                $params[':reid'] = $reid;
                pdo_query($sql, $params);
                foreach ($_GPC['title'] as $k => $v) {
                    $field = array();
                    $field['reid'] = $reid;
                    $field['title'] = trim($v);
                    $field['displayorder'] = range_limit($_GPC['displayorder'][$k], 0, 254);
                    $field['type'] = $_GPC['type'][$k];
                    $field['essential'] = $_GPC['essentialvalue'][$k] == 'true' ? 1 : 0;
                    $field['bind'] = $_GPC['bind'][$k];
                    $field['value'] = $_GPC['value'][$k];
                    $field['value'] = urldecode($field['value']);
                    $field['description'] = $_GPC['desc'][$k];
                    pdo_insert('hnresearch_fields', $field);
                }
            }
            if (!empty($record['noticeemail'])) {
            	load()->func('communication');
            	ihttp_email($record['noticeemail'], $record['title'] . '的报名提醒', $record['description']);
            }
            message('保存成功.', 'refresh');
        }
        $types = array();
        $types['number'] = '数字(number)';
        $types['text'] = '字串(text)';
        $types['textarea'] = '文本(textarea)';
        $types['radio'] = '单选(radio)';
        $types['checkbox'] = '多选(checkbox)';
        $types['select'] = '选择(select)';
        $types['calendar'] = '日历(calendar)';
        $types['email'] = '电子邮件(email)';
        $types['image'] = '上传图片(image)';
        $types['range'] = '日期范围(range)';
        $types['reside'] = '居住地(reside)';
        $fields = fans_fields();
        if ($reid) {
            $sql = 'SELECT * FROM ' . tablename('hnresearch') . ' WHERE `weid`=:weid AND `reid`=:reid';
            $params = array();
            $params[':weid'] = $_W['uniacid'];
            $params[':reid'] = $reid;
            $activity = pdo_fetch($sql, $params);
            $activity['starttime'] && $activity['starttime'] = date('Y-m-d H:i:s', $activity['starttime']);
            $activity['endtime'] && $activity['endtime'] = date('Y-m-d H:i:s', $activity['endtime']);
            if ($activity) {
                $sql = 'SELECT * FROM ' . tablename('hnresearch_fields') . ' WHERE `reid`=:reid ORDER BY `refid`';
                $params = array();
                $params[':reid'] = $reid;
                $ds = pdo_fetchall($sql, $params);
            }
        }
        if (empty($activity['endtime'])) {
            $activity['endtime'] = date('Y-m-d', strtotime('+1 day'));
        }
        include $this->template('hnpost');
    }

    public function doMobileResearch() {
        global $_W, $_GPC;
		$weid = $_W['weid'];
        $reid = intval($_GPC['id']);
        $sql = 'SELECT * FROM ' . tablename('hnresearch') . ' WHERE `weid`=:weid AND `reid`=:reid';
        $params = array();
        $params[':weid'] = $_W['uniacid'];
        $params[':reid'] = $reid;
        $activity = pdo_fetch($sql, $params);
        $title = $activity['title'];
        if ($activity['status'] != '1') {
            message('当前活动已经停止.');
        }
        if (!$activity) {
            message('非法访问.');
        }
        if ($activity['starttime'] > TIMESTAMP) {
            message('当前活动还未开始！');
        }
        if ($activity['endtime'] < TIMESTAMP) {
            message('当前活动已经结束！');
        }
        $sql = 'SELECT * FROM ' . tablename('hnresearch_fields') . ' WHERE `reid` = :reid';
        $params = array();
        $params[':reid'] = $reid;
        $ds = pdo_fetchall($sql, $params);
        if (!$ds) {
            message('非法访问.');
        }
        $initRange = $initCalendar = false;
        $binds = array();
        foreach ($ds as &$r) {
            if ($r['type'] == 'range') {
                $initRange = true;
            }
            if ($r['type'] == 'calendar') {
                $initCalendar = true;
            }
            if ($r['value']) {
                $r['options'] = explode(',', $r['value']);
            }
            if ($r['bind']) {
                $binds[$r['type']] = $r['bind'];
            }
            if ($r['type'] == 'reside') {
                $reside = $r;
            }
        }
        if (checksubmit('submit')) {
            $pretotal = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('hnresearch_rows') . " WHERE reid = :reid AND openid = :openid", array(':reid' => $reid, ':openid' => $_W['fans']['from_user']));
            if ($pretotal >= $activity['pretotal']) {
                message('抱歉,每人只能报名' . $activity['pretotal'] . "次！",$this->createMobileUrl('huodongindex'), 'error');
            }
			if(empty($_W['fans']['from_user'])){
			    message('非法进去，请从公众号进入！');
			}else{
				     $checksql = "SELECT * FROM ".tablename('hnfans')." WHERE from_user=:from_user AND weid=:weid";
					 $checkit = pdo_fetch($checksql,array(':from_user'=>$_W['fans']['from_user'],':weid'=>$weid));
			         if(empty($checkit)){
					        message('对不起，本活动只准许交友系统会员报名！');
					 }
			}
            $row = array();
            $row['reid'] = $reid;
            $row['openid'] = $_W['fans']['from_user'];
            $row['createtime'] = TIMESTAMP;
            $datas = $fields = $update = array();
            foreach ($ds as $value) {
                $fields[$value['refid']] = $value;
            }
            foreach ($_GPC as $key => $value) {
                if (strexists($key, 'field_')) {
                    $bindFiled = substr(strrchr($key, '_'), 1);
                    if (!empty($bindFiled)) {
                        $update[$bindFiled] = $value;
                    }
                    $refid = intval(str_replace('field_', '', $key));
                    $field = $fields[$refid];
                    if ($refid && $field) {
                        $entry = array();
                        $entry['reid'] = $reid;
                        $entry['rerid'] = 0;
                        $entry['refid'] = $refid;
                        if (in_array($field['type'], array('number', 'text', 'calendar', 'email', 'textarea', 'radio', 'range', 'select'))) {
                            $entry['data'] = strval($value);
                        }
                        if (in_array($field['type'], array('checkbox'))) {
                            if (!is_array($value))
                                continue;
                            $entry['data'] = implode(';', $value);
                        }
                        $datas[] = $entry;
                    }
                }
            }
            if ($_FILES) {
                load()->func('file');
                foreach ($_FILES as $key => $file) {
                    if (strexists($key, 'field_')) {
                        $refid = intval(str_replace('field_', '', $key));
                        $field = $fields[$refid];
                        if ($refid && $field && $file['name'] && $field['type'] == 'image') {
                            $entry = array();
                            $entry['reid'] = $reid;
                            $entry['rerid'] = 0;
                            $entry['refid'] = $refid;
                            $ret = file_upload($file);
                            if (!$ret['success']) {
                                message('上传图片失败, 请稍后重试.');
                            }
                            $entry['data'] = trim($ret['path']);
                            $datas[] = $entry;
                        }
                    }
                }
            }

            // 兼容会员居住地字段
            if (!empty($_GPC['reside'])) {
                if (in_array('reside', $binds)) {
                    $update['resideprovince'] = $_GPC['reside']['province'];
                    $update['residecity'] = $_GPC['reside']['city'];
                    $update['residedist'] = $_GPC['reside']['district'];
                }
                foreach ($_GPC['reside'] as $key => $value) {
                    $resideData = array('reid' => $reside['reid']);
                    $resideData['rerid'] = 0;
                    $resideData['refid'] = $reside['refid'];
                    $resideData['data'] = $value;
                    $datas[] = $resideData;
                }
            }

            // 更新关联会员资料
            if (!empty($update)) {
                load()->model('mc');
                mc_update($_W['member']['uid'], $update);
            }

            if (empty($datas)) {
                message('非法访问.', '', 'error');
            }

            if (pdo_insert('hnresearch_rows', $row) != 1) {
                message('保存失败.');
            }
            $rerid = pdo_insertid();
            if (empty($rerid)) {
                message('保存失败.');
            }
            foreach ($datas as &$r) {
                $r['rerid'] = $rerid;
                pdo_insert('hnresearch_data', $r);
            }
            if (empty($activity['starttime'])) {
                $record = array();
                $record['starttime'] = TIMESTAMP;
                pdo_update('hnresearch', $record, array('reid' => $reid));
            }
            //发送预约
            if (!empty($datas) && !empty($activity['noticeemail'])) {
                foreach ($datas as $row) {
                    $img = "<img src='";
                    /* 如果预约项目类型是上传图片 */
                    if (substr($row['data'], 0, 6) == 'images') {
                        $body = $fields[$row['refid']]['title'] . ':' . $img . tomedia($row['data']) . ' />';
                    }
                    $body .= $fields[$row['refid']]['title'] . ':' . $row['data'];
                }
                load()->func('communication');
                ihttp_email($activity['noticeemail'], $activity['title'] . '的报名提醒', $body);
            }
            message($activity['information'],$this->createMobileUrl('huodongindex'),'sucess');//可能需要改成跳转到活动首页
        }
        foreach ($binds as $key => $value) {
            if ($value == 'reside') {
                unset($binds[$key]);
                $binds[] = 'resideprovince';
                $binds[] = 'residecity';
                $binds[] = 'residedist';
                break;
            }
        }
        if (!empty($_W['fans']['from_user']) && !empty($binds)) {
            $profile = fans_search($_W['fans']['from_user'], $binds);
            if ($profile['gender']) {
                if ($profile['gender'] == '0')
                    $profile['gender'] = '保密';
                if ($profile['gender'] == '1')
                    $profile['gender'] = '男';
                if ($profile['gender'] == '2')
                    $profile['gender'] = '女';
            }
            foreach ($ds as &$r) {
                if ($profile[$r['bind']]) {
                    $r['default'] = $profile[$r['bind']];
                }
            }
        }
		$settings = pdo_fetch("SELECT * FROM ".tablename('meepo_hongniangset')." WHERE weid=:weid",array(':weid'=>$_W['weid']));
        load()->func('tpl');
        include $this->template('hnsubmit');
    }

    public function doMobileMyResearch() {
        global $_W, $_GPC;
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'display') {
            $rows = pdo_fetchall("SELECT * FROM " . tablename('hnresearch_rows') . " WHERE openid = :openid", array(':openid' => $_W['fans']['from_user']));
            if (!empty($rows)) {
                foreach ($rows as $row) {
                    $reids[$row['reid']] = $row['reid'];
                }
                $research = pdo_fetchall("SELECT * FROM " . tablename('hnresearch') . " WHERE reid IN (" . implode(',', $reids) . ")", array(), 'reid');
            }
        } elseif ($operation == 'detail') {
            $id = intval($_GPC['id']);
            $row = pdo_fetch("SELECT * FROM " . tablename('hnresearch_rows') . " WHERE openid = :openid AND rerid = :rerid", array(':openid' => $_W['fans']['from_user'], ':rerid' => $id));
            if (empty($row)) {
                message('我的预约不存在或是已经被删除！');
            }
            $research = pdo_fetch("SELECT * FROM " . tablename('hnresearch') . " WHERE reid = :reid", array(':reid' => $row['reid']));
            $research['fields'] = pdo_fetchall("SELECT a.title, a.type, b.data FROM " . tablename('hnresearch_fields') . " AS a LEFT JOIN " . tablename('hnresearch_data') . " AS b ON a.refid = b.refid WHERE a.reid = :reid AND b.rerid = :rerid", array(':reid' => $row['reid'], ':rerid' => $id));
        }
		$settings = pdo_fetch("SELECT * FROM ".tablename('meepo_hongniangset')." WHERE weid=:weid",array(':weid'=>$_W['weid']));
        include $this->template('hnresearch');
    }
	 
	//幻灯片管理
	public function doWebslide() {
		global $_W, $_GPC;
		
		 if($check){
		   die('NO pass');  
		 }
			load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$list = pdo_fetchall("SELECT * FROM " . tablename('meepoweixiangqin_slide') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			if (checksubmit('submit')) {
				$data = array(
					'weid' => $_W['uniacid'],
					'title' => $_GPC['title'],
					'url' => $_GPC['url'],
					'status' => intval($_GPC['status']),
					'displayorder' => intval($_GPC['displayorder']),
					'attachment'=>$_GPC['attachment']
				);
				if (!empty($id)) {
					pdo_update('meepoweixiangqin_slide', $data, array('id' => $id));
				} else {
					pdo_insert('meepoweixiangqin_slide', $data);
					$id = pdo_insertid();
				}
				message('更新幻灯片成功！', $this->createWebUrl('slide', array('op' => 'display')), 'success');
			}
			$adv = pdo_fetch("select * from " . tablename('meepoweixiangqin_slide') . " where id=:id and weid=:weid limit 1", array(":id" => $id, ":weid" => $_W['uniacid']));
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$adv = pdo_fetch("SELECT id  FROM " . tablename('meepoweixiangqin_slide') . " WHERE id = '$id' AND weid=" . $_W['uniacid'] . "");
			if (empty($adv)) {
				message('抱歉，幻灯片不存在或是已经被删除！', $this->createWebUrl('slide', array('op' => 'display')), 'error');
			}
			pdo_delete('meepoweixiangqin_slide', array('id' => $id));
			message('幻灯片删除成功！', $this->createWebUrl('slide', array('op' => 'display')), 'success');
		} else {
			message('请求方式不存在');
		}
		include $this->template('adv');
	}
	//充值记录
	public function doWebPayrecord() {
		global $_W, $_GPC;
			 
		 if($check){
		   die('NO pass');  
		 }
		$weid = $_W['uniacid'];
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$params = array();
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;				
			$condition = " o.weid = '{$weid}'";		
			if (!empty($_GPC['nickname'])) {
				$condition .= " AND a.nickname LIKE '%{$_GPC['nickname']}%'";
			}	
			if(!empty($_GPC['datelimit'])){  
				  
					   $starttime = strtotime($_GPC['datelimit']['start']);
					   $endtime = strtotime($_GPC['datelimit']['end']);
					   //$_GPC['starttime'] = $starttime;
					   //$_GPC['endtime'] = $endtime;
					   $condition .= " AND o.time >= {$starttime} AND o.time <= {$endtime}";
				  
				   
			}
			$sql = "select o.* , a.nickname  from ".tablename('hnpayjifen')." o"
					." left join ".tablename('hnfans')." a on o.openid = a.from_user where $condition ORDER BY a.time DESC"
					. " LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
			$list = pdo_fetchall($sql);
			$total = pdo_fetchcolumn("select count(*)  from ".tablename('hnpayjifen')." o"
					." left join ".tablename('hnfans')." a on o.openid = a.from_user where $condition ORDER BY a.time DESC");
            $pager = pagination($total, $pindex, $psize);
		}elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$adv = pdo_fetch("SELECT id  FROM " . tablename('hnpayjifen') . " WHERE id = '$id' AND weid=" . $_W['uniacid'] . "");
			
			pdo_delete('hnpayjifen', array('weid'=>$weid,'id' => $id));
			message('删除成功！', $this->createWebUrl('payrecord', array('page'=>$_GPC['page'],'op' => 'display')), 'success');
		} else {
			message('请求方式不存在');
		}
		include $this->template('payrecord');
	}
	public function doWebBaoyuerecord() {
		global $_W, $_GPC;
		 
		 if($check){
		   die('NO pass');  
		 }
		$weid = $_W['uniacid'];
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$params = array();
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;				
			$condition = " o.weid = '{$weid}'";		
			if (!empty($_GPC['nickname'])) {
				$condition .= " AND a.nickname LIKE '%{$_GPC['nickname']}%'";
			}
			if(!empty($_GPC['datelimit'])){  
				  
					   $starttime = strtotime($_GPC['datelimit']['start']);
					   $endtime = strtotime($_GPC['datelimit']['end']);
					   //$_GPC['starttime'] = $starttime;
					   //$_GPC['endtime'] = $endtime;
					   $condition .= " AND o.time >= {$starttime} AND o.time <= {$endtime}";
				  
				   
			}
			$total = pdo_fetchcolumn("select count(*)  from ".tablename('meepohn_baoyue')." o"
					." left join ".tablename('hnfans')." a on o.openid = a.from_user where $condition ORDER BY a.time DESC");
            $pager = pagination($total, $pindex, $psize);
			$sql = "select o.* , a.nickname  from ".tablename('meepohn_baoyue')." o"
					." left join ".tablename('hnfans')." a on o.openid = a.from_user where $condition ORDER BY a.time DESC"
					. " LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
			$list = pdo_fetchall($sql);
		}elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$adv = pdo_fetch("SELECT id  FROM " . tablename('meepohn_baoyue') . " WHERE id = '$id' AND weid=" . $_W['uniacid'] . "");
			if(empty($adv)){
			  message('此条包月记录不存在或者已经被删除！');
			}
			pdo_delete('meepohn_baoyue', array('weid'=>$weid,'id' => $id));
			message('删除成功！', $this->createWebUrl('baoyuerecord', array('page'=>$_GPC['page'],'op' => 'display')), 'success');
		} else {
			message('请求方式不存在');
		}
		include $this->template('baoyuerecord');
	}
	//充值记录
	public function doWebExchangerecord() {
		global $_W, $_GPC;
		 
		 if($check){
		   die('NO');  
		 }
		$weid = $_W['uniacid'];
	    
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$condition = '';
			if (!empty($_GPC['nickname'])) {
				$openid = pdo_fetchcolumn("SELECT from_user FROM".tablename('hnfans')." WHERE  nickname  LIKE '%{$_GPC['nickname']}%' AND weid = '{$weid}'");
				if($openid){
			      $condition .= " AND openid = '{$openid}'";
				}
			}
			if(!empty($_GPC['datelimit'])){  
				  
					   $starttime = strtotime($_GPC['datelimit']['start']);
					   $endtime = strtotime($_GPC['datelimit']['end']);
					   //$_GPC['starttime'] = $starttime;
					   //$_GPC['endtime'] = $endtime;
					   $condition .= " AND createtime >= {$starttime} AND createtime <= {$endtime}";
				  
				   
			}
			
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;				
			$page = intval($_GPC['truepage']);//页码	
		    $tablename = tablename("hongniangexchangelog");
            $list = pdo_fetchall("SELECT * FROM " . $tablename . " WHERE weid = :weid   {$condition} ORDER BY createtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':weid' =>$weid));
			$total = pdo_fetchcolumn("SELECT count(*) FROM " . $tablename . " WHERE weid = :weid   {$condition} ORDER BY createtime DESC",array(':weid' =>$weid));
            $pager = pagination($total, $pindex, $psize);
			if(!empty($list) && is_array($list)){
			   foreach($list as &$row){
			         $openidusers = pdo_fetch("SELECT nickname,avatar FROM".tablename('hnfans')." WHERE from_user=:from_user AND weid=:weid",array(":weid"=>$weid,':from_user'=>$row['openid']));
					  $toopenidusers = pdo_fetch("SELECT nickname,avatar FROM".tablename('hnfans')." WHERE from_user=:from_user AND weid=:weid",array(":weid"=>$weid,':from_user'=>$row['toopenid']));
					 $row['nickname'] = $openidusers['nickname'];
					 $row['avatar'] = $openidusers['avatar'];
					 $row['tonickname'] = $toopenidusers['nickname'];
					 $row['toavatar'] = $toopenidusers['avatar'];
			   }
			   unset($row);
			}
			
		}elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			
			pdo_delete('hongniangexchangelog', array('weid'=>$weid,'id' => $id));
			message('删除成功！', $this->createWebUrl('exchangerecord', array('page'=>$_GPC['page'],'op' => 'display')), 'success');
		} else {
			message('请求方式不存在');
		}
		include $this->template('exchangerecord');
	}
	public function sendmessage($content,$openid){
		 global $_W, $_GPC;
		 $weid = $_W['weid'];
		 $cfg = $this->module['config'];
		 $appid = $cfg['appid'];
		 $secret = $cfg['secret'];
		 $img = $_W['attachurl'].$cfg['kefuimg'];
		 $id = $_W['openid'];//当前粉丝$openid为发送者
         $res = $this->getusers($weid,$id);
		 load()->classs('weixin.account');
         $accObj= WeixinAccount::create($_W['account']['acid']);
         $access_token = $accObj->fetch_token();
						    $token2 =  $access_token;
					        $title =  $res['nickname'].'给你发来新消息了！';
                            $fans = pdo_fetch('SELECT salt,acid,openid FROM ' . tablename('mc_mapping_fans') . ' WHERE uniacid = :uniacid AND openid = :openid', array(':uniacid' => $weid, ':openid' => $openid));
                            $pass['time'] = TIMESTAMP;
							$pass['acid'] = $fans['acid'];
							$pass['openid'] = $fans['openid'];
							$pass['hash'] = md5("{$fans['openid']}{$pass['time']}{$fans['salt']}{$_W['config']['setting']['authkey']}");
							$auth = base64_encode(json_encode($pass));
							$vars = array();
							$vars['__auth'] = $auth;
							$vars['forward'] = base64_encode($this->createMobileUrl('hitmail',array('toname'=>$res['nickname'],'toopenid'=>$id)));
							$url2 =  $_W['siteroot'] . 'app/' . murl('auth/forward', $vars);
									$data = '{
										"touser":"'.$openid.'",
										"msgtype":"news",
										"news":{
											"articles": [
											 {
												 "title":"'.$title.'",
												 "description":"'.$title.'",
												 "url":"'.$url2.'",
												 "picurl":"'.$img.'",
											 }
											 ]
										}
									}';
							$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token2;
							load()->func('communication');
							$it = ihttp_post($url, $data);
	
	}
}
