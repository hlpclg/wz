<?php
/**
 * meepo 超级微现场
 *
 * http://www.012wz.com 作者QQ 800083075
 */
defined('IN_IA') or exit('Access Denied');

class meepo_bigwallModuleProcessor extends WeModuleProcessor {
	
	
	public function respond() {
		if ($this->inContext) {
			return $this->post();
		} else { 
			$res = $this->register();
			return $this->respText($res);
		}
	}
	
	private function register() { 
		global $_W;
		$rid = $this->rule;
		$from_user = $this->message['from'];
		$uid = $_W['fans']['uid'];
		$wall = pdo_fetch("SELECT * FROM ".tablename('weixin_wall_reply')." WHERE rid = :rid LIMIT 1", array(':rid'=>$rid));	
		if (empty($wall)) {
			return array();
		}
		$member = $this->getMember();
		$newmember = $this->getflag();
		if($member=='1'){
			           
			            $followtime = pdo_fetchcolumn("SELECT followtime FROM ".tablename('mc_mapping_fans')." WHERE openid = '{$from_user}'  AND uniacid = '{$_W['uniacid']}'");
						if($followtime!=$this->message['time']){
							if(empty($wall['enter_tips'])){
								     $message = '您已经录入基本信息！直接回复内容即可上墙';	
							}else{
								     $message = $wall['enter_tips'];	
							}
							if($wall['lurumobile'] == '1'){
								     
									  if(empty($newmember['mobile']) || empty($newmember['realname'])){
									       $message  .= "请输入您的真实姓名以及联系方式，验证您的真实身份！\n";
									       $message  .= '<a target="_blank" href="'.$this->buildSiteUrl($this->createMobileUrl('getmobilerealname',array('rid'=>$rid))).'">点击进入</a>';
									  }
							}
							 
						}else{
							$data = array(
								'rid' => $rid,
								'openid' => $from_user,
								'type' =>'text',
								'createtime' => TIMESTAMP,
								'weid'=>$_W['uniacid'],
								'isshow'=>1,
							);
							
							$data['content'] = '欢迎'.$newmember['nickname'].'关注'.$_W['account']['name'];
							$data['image'] = '';
							$data['avatar'] = $_W['attachurl'].$newmember['avatar'];
							$data['nickname'] = $newmember['nickname'];
							pdo_insert('weixin_wall', $data);
							$maxid = pdo_insertid();	 
							
							$checknum = pdo_fetchcolumn("SELECT num FROM ".tablename('weixin_wall_num')." WHERE weid=:weid AND rid=:rid",array(':weid'=>$_W['uniacid'],':rid'=>$rid));

							if(!empty($checknum)){
                                pdo_update('weixin_wall',array('num'=>intval($checknum)),array('id'=>$maxid,'rid'=>$rid,'weid'=>$_W['uniacid']));
                                pdo_update('weixin_wall_num',array('num'=>intval($checknum)+1),array('weid'=>$_W['uniacid'],'rid'=>$rid)); 
								
							}else{
								pdo_insert('weixin_wall_num',array('num'=>2,'weid'=>$_W['uniacid'],'rid'=>$rid));
								pdo_update('weixin_wall',array('num'=>1),array('id'=>$maxid,'rid'=>$rid,'weid'=>$_W['uniacid']));
								   
							}
							if(empty($wall['subit_tips'])){
								 $message = '欢迎关注，您已经录入基本信息！直接回复内容即可上墙';
							}else{
								 $message = $wall['subit_tips'];	
							}
							if($wall['lurumobile'] == '1'){
									  if(empty($newmember['mobile']) || empty($newmember['realname'])){
									       $message .= "请输入您的真实姓名以及联系方式，验证您的真实身份！\n";
									       $message  .= '<a target="_blank" href="'.$this->buildSiteUrl($this->createMobileUrl('getmobilerealname',array('rid'=>$rid))).'">点击进入</a>';
									  }
							}					
							   
							
						}
			 
			 
			 
	        
		}elseif($member=='2'){
			               if(empty($wall['enter_tips'])){
								     $message = '您已经录入基本信息！直接回复内容即可上墙';	
						   }else{
								     $message = $wall['enter_tips'];	
						   }
			               if($wall['lurumobile'] == '1'){
								      
									  if(empty($newmember['mobile']) || empty($newmember['realname'])){
									       $message  .= "请输入您的真实姓名以及联系方式，验证您的真实身份！\n";
									       $message  .= '<a target="_blank" href="'.$this->buildSiteUrl($this->createMobileUrl('getmobilerealname',array('rid'=>$rid))).'">点击进入</a>';
									  } 
						   }
							
	    }elseif($member=='3'){
		     $sql = 'SELECT verify FROM '.tablename('weixin_flag').' WHERE openid=:openid AND rid=:rid';
			$para = array(':openid'=>$from_user,':rid'=>$rid);
			$resmember = pdo_fetch($sql,$para);
			$message = '欢迎进入微信墙，亲回复验证码来录入您的头像，昵称等基本信息！'."\n验证码为：".$resmember['verify']."\n要一模一样的才能通过哦！！";
		}elseif($member=='4'){
			$sql = 'SELECT verify FROM '.tablename('weixin_flag').' WHERE openid=:openid AND rid=:rid';
			$para = array(':openid'=>$from_user,':rid'=>$rid);
			$resmember = pdo_fetch($sql,$para);
			
			$message = '欢迎进入微信墙，亲回复验证码来录入您的头像，昵称等基本信息！'."\n验证码为：".$resmember['verify']."\n要一模一样的才能通过哦！！";
		        
		    
		}elseif($member=='5'){
		    $message = '网络超时或者未设置好‘基本设置’，请重新回复'.$this->message['content'];	
		    
		}else{
		    $message = '管理员后台参数配置有误，请呼叫管理员！';
		}
		      
		         
		$this->beginContext();
		return $message;
	}
	
	private function post() {
		global $_W, $engine;
		$weid = $_W['weid'];
		$openid = $this->message['from'];
		
		if (!in_array($this->message['msgtype'], array('text', 'image','event'))) {
			return false;
		}
		$rid = $this->rule;
	    $member = $this->getflag();
		
		$wall = pdo_fetch("SELECT * FROM ".tablename('weixin_wall_reply')." WHERE rid = :rid  LIMIT 1", array(':rid'=>$rid));
		if(!empty($member['nickname']) && !empty($member['fakeid'])){	
			       
					if ((!empty($wall['timeout']) && $wall['timeout'] > 0 && TIMESTAMP - $member['lastupdate'] >= $wall['timeout'])) {
						$this->endContext();
						return $this->respText('由于您长时间未操作，请重新进入微信墙！');
					}
					$this->refreshContext();
					if ((empty($wall['quit_command']) && $this->message['content'] == '退出') ||
						(!empty($wall['quit_command']) && $this->message['content'] == $wall['quit_command'])) {
						$this->endContext();
						return $this->respText($wall['quit_tips']);
					}
					
					if ($member['isblacklist']!=0) {
						$content = '你已被列入黑名单，不准许上墙！';
						return $this->respText($content);
					} 
					if($wall['lurumobile'] == '1'){
							  
							  if(empty($member['mobile']) || empty($member['realname'])){
								   $content  = "请输入您的真实姓名以及联系方式，验证您的真实身份！\n";
								   $content  .= '<a target="_blank" href="'.$this->buildSiteUrl($this->createMobileUrl('getmobilerealname',array('rid'=>$rid))).'">点击进入</a>';
								   return $this->respText($content);
							  }
					}
					                   $data = array(
											'rid' => $rid,
											'openid' => $openid,
											'type' => $this->message['type'],
											'createtime' => TIMESTAMP,
						                    'weid'=>$weid,
										);
										if ($wall['isshow']=='0') {
											$data['isshow'] = 1;
										} else {
											$data['isshow'] = 0;
										}

										if ($this->message['msgtype'] == 'event' && $this->message['event'] == 'CLICK') {
											
											if($this->message['content']=='投票'){
													$content = '快速进入投票通道！<a target="_blank" href="'.$this->buildSiteUrl($this->createMobileUrl('votehtml',array('rid'=>$rid))).'">点击进入投票</a>';
													return $this->respText($content);
											}elseif($this->message['content']=='摇一摇'){
												
												   $res = pdo_fetch("SELECT * FROM ".tablename('weixin_shake_toshake')." WHERE openid=:openid AND weid=:weid AND rid=:rid",array(':openid'=>$openid,':weid'=>$weid,':rid'=>$rid));
												   //return $this->respText($res['rid']."rrr");
												   if(empty($res)){
 pdo_insert('weixin_shake_toshake',array('openid'=>$openid,'phone'=>$member['nickname'],'point'=>0,'avatar'=>$_W['attachurl'].$member['avatar'],'weid'=>$weid,'rid'=>$rid));
											       }
													$content = '进入摇一摇后等待游戏开始，主持人点击开始游戏，倒计时后用您吃奶的劲尽情狂欢吧！<a target="_blank" href="'.$this->buildSiteUrl($this->createMobileUrl('shakehands',array('rid'=>$rid))).'">点击进入摇一摇</a>';
													
											       return $this->respText($content);
											}
										}
										if ($this->message['type'] == 'text') {
											if($this->message['content']=='投票'){
													$content = '快速进入投票通道！<a target="_blank" href="'.$this->buildSiteUrl($this->createMobileUrl('votehtml',array('rid'=>$rid))).'">点击进入投票</a>';
													return $this->respText($content);
											}elseif($this->message['content']=='摇一摇'){
												   $res = pdo_fetch("SELECT * FROM ".tablename('weixin_shake_toshake')." WHERE openid=:openid AND weid=:weid AND rid=:rid",array(':openid'=>$openid,':weid'=>$weid,':rid'=>$rid));
												   if(empty($res)){
                                                       pdo_insert('weixin_shake_toshake',array('openid'=>$openid,'phone'=>$member['nickname'],'point'=>'0','avatar'=>$_W['siteroot'] . 'attachment/'.$member['avatar'],'weid'=>$weid,'rid'=>$rid));
											       }
													$content = '进入摇一摇后等待游戏开始，主持人点击开始游戏，倒计时后用您吃奶的劲尽情狂欢吧！<a target="_blank" href="'.$this->buildSiteUrl($this->createMobileUrl('shakehands',array('rid'=>$rid))).'">点击进入摇一摇</a>';
													
											       return $this->respText($content);
											}else{
											   $data['content'] = $this->message['content'];
											   $data['image'] = '';
											}
										}

										if ($this->message['type'] == 'image') {
																   load()->func('communication');
																   $picurl = "photos/" . random(30) .".jpg";
																   $pic_data2 = ihttp_get($this->message['picurl']);
																   load()->func('file');
																   $upload = file_write($picurl,$pic_data2['content']);				
											                       $data['content'] = 'meepo图片消息';
											                       $data['image'] = $_W['attachurl'] .$picurl;
										}
										
										
										$data['avatar'] = $_W['attachurl'].$member['avatar'];
										$data['nickname'] = $member['nickname'];
										pdo_insert('weixin_wall', $data);
										$maxid = pdo_insertid();
										if($wall['isshow']=='0'){		 
											$checknum = pdo_fetchcolumn("SELECT num FROM ".tablename('weixin_wall_num')." WHERE weid=:weid AND rid=:rid",array(':weid'=>$_W['uniacid'],':rid'=>$rid));
											if(!empty($checknum)){
												pdo_update('weixin_wall',array('num'=>intval($checknum)),array('id'=>$maxid,'rid'=>$rid,'weid'=>$_W['uniacid']));
												pdo_update('weixin_wall_num',array('num'=>intval($checknum)+1),array('weid'=>$_W['uniacid'],'rid'=>$rid));
												
											}else{
												pdo_insert('weixin_wall_num',array('num'=>2,'weid'=>$_W['uniacid'],'rid'=>$rid));
												pdo_update('weixin_wall',array('num'=>1),array('id'=>$maxid,'rid'=>$rid,'weid'=>$_W['uniacid']));
											
											}
											if(!empty($wall['send_tips'])) {
											    $content = $wall['send_tips'];
										    } else {
											    $content = '上墙成功，请多多关注大屏幕！';
										    }
											 
										}else{
										
											if(empty($wall['send_tips'])){
												 $content = '发送消息成功，请等待管理员审核';
											 }else{
												 $content = $wall['send_tips'].'请等待管理员审核';	
											 }
										
										}
										if(preg_match("/^签到(.*)/",$this->message['content'])){
											     if(!$member['sign']){
													$up = array('sign'=>1,'signtime'=>time());
                                                    pdo_update('weixin_flag',$up,array('openid'=>$this->message['from'],'rid'=>$rid,'weid'=>$weid));
												    $content = '签到成功，请多多关注大屏幕！';
												 }
											        
										}
					                   return $this->respText($content);
		}else{
			if ((!empty($wall['timeout']) && $wall['timeout'] > 0 && TIMESTAMP - $member['lastupdate'] >= $wall['timeout'])) {
						$this->endContext();
						return $this->respText('由于您长时间未操作，请重新进入微信墙！');
			}elseif($this->message['content'] == $member['verify']){
							 $res = $this->isluru();	
							 if($res=='1'){
							    return $this->respText($wall['enter_tips']);
							 }else{
							    return $this->respText('网络超时，录入失败，请重新输入验证码'.$member['verify']);
							 }
			}else{
		       $res = $this->register();
			   return $this->respText($res);
			}
		}
	}
	
	private function getMember() {
		global $_W;
		$rid = $this->rule;
		$weid = $_W['weid'];
		$openid = $this->message['from'];
		$member = $this->getflag();
		if (empty($member)) {
			$cfg = $this->module['config'];				   
		    if($cfg['isshow']=='1'){    
									 load()->func('communication');
									 load()->classs('weixin.account');
                                     $accObj= WeixinAccount::create($_W['account']['acid']);
                                     $access_token = $accObj->fetch_token();
									 $token2 = $access_token;
									 if(empty($token2)){
										 $message  = '0';	 
									 }else{
											$url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$token2.'&openid='.$openid.'&lang=zh_CN';
											$content2 = ihttp_request($url);		
											$info = @json_decode($content2['content'], true);
											if(empty($info['nickname'])){
												$message  = '0';
												
											
											}else{
											   $data = array(
														'openid' =>$openid,
														'rid' => $this->rule,
														'isjoin' => 1,
														'lastupdate' => TIMESTAMP,
														'isblacklist' => 0,
												        'status' =>2,//代表处于未中奖状态
											            'othid' =>0,//代表处于对对碰初始化状态 即为 对对碰未中状态
														'vote'=>0,//代表未投票
														'verify'=>'1',
														'weid'=>$weid,
													);
											   $imgurl = $info['headimgurl'];
											   if(empty($imgurl)){
											       $picurl = 'images/cdhn80.jpg';
											   }else{
												   load()->func('communication');
													   $picurl = "photos/{$from_user}".time().".jpg";
													   $pic_data = ihttp_get($imgurl);
													   load()->func('file');
													   $upload = file_write($picurl,$pic_data['content']);
													   
											   }
											           $data['avatar'] = $picurl;
													   $data['nickname'] = $info['nickname'];
													   $data['sex'] = $info['sex'];
													   if(empty($data['sex'])){
													      $data['sex'] = '0';
													   }
											           $data['fakeid'] = random(10,$numeric = true);
													   $data['msgid'] = time();
			                                           pdo_insert('weixin_flag', $data);
			                                  
			                                  $message  = '1';
											}
									        
						               }
				}else{
										$verify = random(5,$numeric = true);
										$data = array(
											'openid' =>$openid,
											'rid' => $this->rule,
											'isjoin' => 1,
											'lastupdate' => TIMESTAMP,
											'isblacklist' => 0,
											'status' =>2,
											'othid' =>0,
											'vote'=>0,
											'verify'=>$verify,
											'weid'=>$weid,
										);
										$insertsome = pdo_insert('weixin_flag',$data);                   
										if($insertsome){
										   $message  = '4';
										}else{
											$message  = '5';
										}
			    }			
					
			
		} else {    
			$data = array('lastupdate' => TIMESTAMP);
			$parm = array('openid' => $openid,'rid' => $this->rule,'weid'=>$weid);
			pdo_update('weixin_flag', $data, $parm);
			if(!empty($member['fakeid'])){
			    $message  = '2';
			}else{
			    $message  = '3';
			}
		}
        return $message;
	}
	public function getflag(){
	   global $_W, $_GPC;
	   $rid = $this->rule;
	   $openid = $this->message['from'];
	   $sql = "SELECT * FROM ".tablename('weixin_flag')." WHERE openid = :openid AND rid = :rid  AND weid=:weid";
	   $param = array(':openid' => $openid, ':rid' => $rid,':weid' =>$_W['uniacid']);
	   $flag =  pdo_fetch($sql,$param);
	   return $flag;
	}
	public function hookBefore() {
		global $_W, $engine;
	}
	protected function GetRandStr($len) 
	{ 
		$chars = array( 
			"a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",  
			"l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",  
			"w", "x", "y", "z", "0", "1", "2",  
			"3", "4", "5", "6", "7", "8", "9" 
		); 
		$charsLen = count($chars) - 1; 
		shuffle($chars);   
		$output = ""; 
		for ($i=0; $i<$len; $i++) 
		{ 
			$output .= $chars[mt_rand(0, $charsLen)]; 
		}  
		return $output;  
	} 
	public function lurushake(){
		global $_W;
		$rid = $this->rule;
	    $openid = $this->message['from'];
		$weid = $_W['weid'];
	    $member = $this->getflag();
	    $nicheng = $member['nickname'];
	    $avatar = $member['avatar'];
	    pdo_insert('weixin_shake_toshake',array('openid'=>$openid,'phone'=>$nicheng,'point'=>'0','avatar'=>$avatar,'weid'=>$weid,'rid'=>$rid));
    }
	
	//对于未认证的采取全部录入
	public function isluru(){
						global $_W;
						$weid = $_W['weid'];
						$sql = "SELECT * FROM ".tablename('account_wechats')." WHERE uniacid=:uniacid";
		                        $parasss = array(":uniacid"=>$weid);
								$wechatss = pdo_fetch($sql,$parasss);
								$ress=$this->login($wechatss['username'],$wechatss['password']);
								pdo_update('weixin_cookie',array('cookie'=>$ress[0],'cookies'=>$ress[1],'token'=>$ress[2]),array('weid'=>$weid));
						load()->func('communication');
						$openid = $this->message['from'];					
	                    $sql_flg="SELECT * FROM  ".tablename('weixin_flag')." WHERE openid =:openid AND weid=:weid";
	                    $checkres = pdo_fetch($sql_flg,array(':weid'=>$weid,':openid'=>$openid));
						if(!empty($checkres['nickname']) && !empty($checkres['fakeid'])){
						    return '1';
						}
						$rid = $this->rule;
						$weid = $_W['weid'];						
						$cfg = pdo_fetch("SELECT * FROM ".tablename('weixin_cookie')." WHERE weid=:weid",array(':weid'=>$weid));
						$token = $cfg['token'];
						$cookie = iunserializer($cfg['cookie']);
						$xiaobai=$this->getmessage($token,$cookie,$cookies);
						$date_time=$xiaobai[0]["date_time"];
						for ($i=0;$i<=19;$i++){
							if($xiaobai[$i]["content"] == $this->message['content']){
							    break;
							}
						}
						
						if($i == 20){
						        $sql = "SELECT * FROM ".tablename('account_wechats')." WHERE uniacid=:uniacid";
		                        $paras = array(":uniacid"=>$weid);
								$wechat = pdo_fetch($sql,$paras);
								$res=$this->login($wechat['username'],$wechat['password']);
								pdo_update('weixin_cookie',array('cookie'=>$res[0],'cookies'=>$res[1],'token'=>$res[2]),array('weid'=>$weid));
								return '0';
						}
						
							if(!empty($xiaobai[$i]["fakeid"])){		
								$fakeid=$xiaobai[$i]["fakeid"];
								$msgid = $xiaobai[$i]["id"];
								$nickname=$xiaobai[$i]["nick_name"];
                                $gender = $this->sixi($token,$fakeid,$cookie,$cookies);
								
								parse_str($gender);
						        $sex=$gender;
						        $data = array();
								$img = $this->gethead($token,$fakeid,$cookie);
								if(!empty($img)){
								load()->func('file');
								$picurl = 'images/'.rand().'.jpg';
								$imgurl = file_write($picurl,$img);
								$data['avatar'] = $picurl;
								}else{
								   $data['avatar'] =  'images/cdhn80.jpg';
								
								}
								 
								                      
													   $data['nickname'] = $nickname;
													   $data['sex'] = $sex;
													   $data['fakeid'] = $fakeid;
													   $data['msgid'] = $msgid;
													   if(empty($data['sex'])){
													      $data['sex'] = '0';
													   }
								$allinsert = pdo_update('weixin_flag',$data,array('openid'=>$openid,'rid'=>$rid,'weid'=>$weid));
								if($allinsert){
								  return '1'; 
								}else{
								   return '0';
								}
								  
							}else{
							  return '0';
							
							}
					
	}
	public function sixi($token,$fakeid,$cookie,$cookies){
		load()->func('communication');
		$url = "https://mp.weixin.qq.com/cgi-bin/getcontactinfo";
		$refer = "https://mp.weixin.qq.com/cgi-bin/message?t=message/list&count=20&day=7&token=".$token."&lang=zh_CN";
						$post = array(
						     'token'=>$token,
							 'lang'=>'zn_CN',
							 't'=>'ajax-getcontactinfo',
							 'fakeid'=>$fakeid,
						);
                $outputs = ihttp_request($url, $post, array('CURLOPT_REFERER' => $refer, 'CURLOPT_COOKIE' => $cookie));  
			    $output = $outputs['content'];
                $deng= preg_replace('/[\{]+/i','',$output);
                $deng= preg_replace('/[\}]+/i','',$deng);
                $deng= preg_replace('/[\[]+/i','',$deng);
                $deng= preg_replace('/[\]]+/i','',$deng);
                $aaa=preg_replace('/["]+/i','',$deng);
                $aaaq=str_replace(',','&',$aaa);
                $aaaq =str_replace(':','=',$aaaq);
                $aaaq="?$aaaq";
                $ab=trim($aaaq);
                $bb=str_replace(" ","",$ab);
                $bb=str_replace("\r\n","",$bb);
                $bb=str_replace("\n","",$bb);  
  
	            return $bb;
	}
	public function gethead($token,$fakeid,$cookie){  
             load()->func('communication');
             $url = "https://mp.weixin.qq.com/misc/getheadimg?token=".$token."&fakeid=".$fakeid;
						$refer = "https://mp.weixin.qq.com/cgi-bin/getmessage?t=wxm-message&token=".$token."&lang=zh_CN&count=50";
             $outputs = ihttp_request($url, '', array('CURLOPT_REFERER' => $refer, 'CURLOPT_COOKIE' => $cookie));     
			 $output = $outputs['content'];
             $img=$output;
             return $img;
	}
	  
    public function getmessage($token,$cookie,$cookies=''){    
		load()->func('communication');
	    $url = "https://mp.weixin.qq.com/cgi-bin/message?t=message/list&count=20&day=7&token=".$token."&lang=zh_CN";
		$refer = "https://mp.weixin.qq.com/cgi-bin/contactmanage?t=user/index&token=".$token."&lang=zh_CN&pagesize=10&pageidx=0&type=0&groupid=0";
        $outputs = ihttp_request($url, '', array('CURLOPT_REFERER' => $refer, 'CURLOPT_COOKIE' => $cookie));
        $output = $outputs['content'];
		$u_msg=substr($output,(strpos($output,"{\"msg_item\":")+14));
		$abc=substr($u_msg,(strpos($u_msg,"{\"msg_item\":[\":")+1));
		$b=array();
		$i = 0;
		foreach (explode('},{',$u_msg) as $u_msg){
		$u_msg=preg_replace('/["]+/i','',$u_msg);
			foreach (explode(',',$u_msg) as $u_msg){
				list($k,$v)=explode(':',$u_msg);
				$b[$i][$k]=$v;
			}
		$i++;
		}

	     return $b;
	}
	public function login($username,$pwd,$verify='',$codecookie=''){
	     $loginurl = 'https://mp.weixin.qq.com/cgi-bin/login?lang=zh_CN';
	     $post = array(
		'username' => $username,
		'pwd' => $pwd,
		'imgcode' => $imgcode,
		'f' => 'json',	
	     );
	    load()->func('communication');
		$response = ihttp_request($loginurl, $post, array('CURLOPT_REFERER' => 'https://mp.weixin.qq.com/'));
		if (is_error($response)) {
			return false;
		}
		$data = json_decode($response['content'], true);
		if ($data['base_resp']['ret'] == 0) {
			preg_match('/token=([0-9]+)/', $data['redirect_url'], $match);
			$token = trim($match[1]);
			$cookienew =  implode('; ', $response['headers']['Set-Cookie']);      
			$cookienew = iserializer($cookienew);
			$cookienews = 'meepo';
			$back = array($cookienew,$cookienews,$token);	 
			return $back;
		}else{
		   return false;
		}
   }
}
