<?php
/**
 * meepo找老乡模块处理程序
 *
 * @author meepo_zam
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Zam_findlxModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W,$_GPC;
		$weid = $_W['uniacid'];
		$openid = $this->message['from'];
		$res = $this->getuserinfo();
        $content = $this->message['content'];
		$wcfg = $this->module['config'];
		if($content=='找老乡'){
			            if($openid=='fromUser'){
						    $news = array(
									   
									   'title'=>'测试',
								        'description'=>'测试列表',
									   'picurl'=>'',
									   'url'=>$this->buildSiteUrl($this->createMobileUrl('list')),
								   );
						return $this->respNews($news);
						}
			            load()->func('communication');
						$cfgsql = "SELECT * FROM ".tablename('zam_cookie')." WHERE weid=:weid";
						$cfg = pdo_fetch($cfgsql,array(':weid'=>$weid));
						$token = $cfg['token'];
						$cookie = iunserializer($cfg['cookie']);
						/*此代码用来处理用户详细信息并输出*/
						$xiaobai=$this->getmessage($token,$cookie,$cookies);//将用户消息转换成变量 在此得到了粉丝的最新消息大概20条
						 
						$date_time=$xiaobai[0]["date_time"];
						
						$i=0;
						for (;$i<=19;$i++){
						if($xiaobai[$i]["content"] = $content){
							break;
							}
						}
						if($i == 20 && $date_time){
						    $this->monilogin();
							return $this->respText('噢，网络超时！，请重新回复'.$content);
						  }
						
						if($date_time){//最新消息结构
									
								$fakeid=$xiaobai[$i]["fakeid"];
								$msgid = $xiaobai[$i]["id"];
								$nickname=$xiaobai[$i]["nick_name"];
                                $gender = $this->sixi($token,$fakeid,$cookie,$cookies);
								parse_str($gender);
						        $sex=$gender;
								$img = $this->gethead($token,$fakeid,$cookie);
								// $img = 'https://mp.weixin.qq.com/misc/getheadimg?token='.$token.'&fakeid='.$fakeid;
								if(!empty($img)){
								load()->func('file');
								$picurl = 'images/'.rand().'.jpg';
								//include_once('../source/function/file.func.php');
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
							   $data['weid'] = $weid;
							   $data['openid'] = $openid;
							   $data['jointime'] = time();
							   if(!empty($res)){
							        pdo_update('zam_userinfo',$data,array('weid'=>$weid,'openid'=>$openid));
							   }else{
							        pdo_insert('zam_userinfo',$data);
							   }
								if(empty($res['username'])){
									//你个叼毛还未注册，注册了点击头像就可以给老乡发信息了哈，也可以发送 @+对方微信昵称+word+任意内容  就可以聊天了哈
								   $news[] = array(
									   
									   'title'=>$wcfg['title'],
								        'description'=>'',
									   'picurl'=>$_W['attachurl'].$wcfg['logo'],
									   'url'=>$this->buildSiteUrl($this->createMobileUrl('register')),
								   );
									   $news[] = array(
									   
									   'title'=>'系统检测到你还未注册，请点击我去注册',
								        'description'=>'',
									   'picurl'=>'',
									   'url'=>$this->buildSiteUrl($this->createMobileUrl('register')),
								   );
									   $news[] = array(
									   
									   'title'=>'注册后，点击头像或者发送【@老乡姓名@任意内容】，即可和任意老乡聊天了哦！',
								        'description'=>'',
									   'picurl'=>'',
									   'url'=>$this->buildSiteUrl($this->createMobileUrl('register')),
								   );
									 
								}else{
									
								   $news[] = array(
									   
									  'title'=>$wcfg['title'],
								        'description'=>'',
									   'picurl'=>$_W['attachurl'].$wcfg['logo'],
									   'url'=>$this->buildSiteUrl($this->createMobileUrl('list')),
								   );
									   $news[] = array(
									   
									   'title'=>'点击进入找老乡主页',
								        'description'=>'',
									   'picurl'=>'',
									   'url'=>$this->buildSiteUrl($this->createMobileUrl('list')),
								   );
									   $news[] = array(
									   
									   'title'=>'点击头像或者发送【@老乡姓名@任意内容】，即可和任意老乡聊天了哦！',
								        'description'=>'',
									   'picurl'=>'',
									   'url'=>$this->buildSiteUrl($this->createMobileUrl('list')),
								   );
								}
								return $this->respNews($news);
						}else{ 
								
								$this->monilogin();
							  return $this->respText('噢，网络超时！，请重新回复'.$content);
					   }
		}elseif(preg_match("/^@(.*)@(.*)/",$content,$mat)){
		    $username = $mat[1];
			$content2 = $mat[2];
			
            if(empty($res['username']) || empty($res['banji'])){
			    return $this->respText('sorry，你还录入基本资料，不能和与其他人聊天！，请先回复找老乡');
			}else{
			   
                    $sql = "SELECT * FROM ".tablename('zam_userinfo')." WHERE  weid=:weid AND  username LIKE '%{$username}%'";
		            $someone = pdo_fetch($sql,array(':weid'=>$weid));
					
					if(empty($someone['fakeid']) || empty($someone['avatar'])){
					       return $this->respText('sorry，'.$username.'还录入基本资料，还不能和与其聊天！');
					}else{
						     if($res['isblacklist']==1 ){
							    return $this->respText('发送失败，你已被管理员拉入黑名单');
							 }
							 if($someone['isblacklist']==1 ){
								  return $this->respText('发送失败，对方已被管理员拉入黑名单');
							   
							 }
					                      load()->func('communication');
										  $cfg = pdo_fetch("SELECT * FROM ".tablename('zam_cookie')." WHERE weid=:weid",array(':weid'=>$weid));
																$token = $cfg['token'];
																$cookie = iunserializer($cfg['cookie']);
										 $fakeid2 = $someone['fakeid'];
										 $quickreplyid  = $someone['msgid'];  
										  $loginurl = 'https://mp.weixin.qq.com/cgi-bin/singlesend?t=ajax-response&f=json&token='.$token.'&lang=zh_CN';
										  $refer = 'https://mp.weixin.qq.com/cgi-bin/message?t=message/list&count=20&day=7&token='.$token.'&lang=zh_CN';
										  $post = array(
											'token' => $token,
											'lang' =>'zh_CN',
											'f' => 'json',
											'ajax' => '1',	
											'random' => '0.08272588928230107',
											'mask' => 'false',
											  'tofakeid' =>$fakeid2,
											'imgcode' => '',
											   'type' =>'1',
											'content' => $res['username']."发来消息说: \n".$content2."\n温馨提示：\n直接回复【@老乡姓名@任意内容】即可回复老乡的信息！",
											  'quickreplyid'=>$quickreplyid
										  );
					
										 $response = ihttp_request($loginurl, $post, array('CURLOPT_REFERER' =>$refer, 'CURLOPT_COOKIE' =>$cookie));	
										 $data = json_decode($response['content'], true);
										 if ($data['base_resp']['ret'] == 0 && $data['base_resp']['err_msg']=='ok') {
											 pdo_insert('zam_chatlog',array('openid'=>$openid,'toopenid'=>$openid2,'content'=>$content2,'username'=>$res['username'],'tousername'=>$someone['username'],'createtime'=>time(),'weid'=>$weid));

											 pdo_update('zam_userinfo',array('chattime'=>$item['chattime']+1),array('openid'=>$openid,'weid'=>$weid));
												 $news[] = array(
													   
													 'title'=>$wcfg['title'],
								                     'description'=>'',
									                 'picurl'=>$_W['attachurl'].$wcfg['logo'],
													   'url'=>$this->buildSiteUrl($this->createMobileUrl('list')),
												   );
													   $news[] = array(
													   
													   'title'=>'恭喜恭喜！对方已经收到你的信息',
														'description'=>'',
													   'picurl'=>'',
													   'url'=>$this->buildSiteUrl($this->createMobileUrl('list')),
												   );
													   $news[] = array(
													   
													   'title'=>'点击头像或者发送【@老乡姓名@任意内容】，即可和任意老乡聊天了哦！',
														'description'=>'',
													   'picurl'=>'',
													   'url'=>$this->buildSiteUrl($this->createMobileUrl('list')),
												   );
													   $update =  $this->updateit($content,$this->message['createtime']);
					
										            return $this->respNews($news);
										 }else{
                                            $this->monilogin();
										    return $this->respText('噢，网络超时！，请重新发送');
										 
										 }
					
					}
			}
		
		}
		
	}

private function getuserinfo(){
        global $_W,$_GPC;
		$weid = $_W['uniacid'];
		$openid = $_W['fans']['from_user'];
		$sql = "SELECT * FROM ".tablename('zam_userinfo')." WHERE openid=:openid AND weid=:weid";
		$res = pdo_fetch($sql,array(":openid"=>$openid,':weid'=>$weid));
		return $res;

}
public function sixi($token,$fakeid,$cookie,$cookies)
	{
     $url = "https://mp.weixin.qq.com/cgi-bin/getcontactinfo";
	$post= "token=$token&lang=zh_CN&t=ajax-getcontactinfo&fakeid=$fakeid";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//curl_setopt($ch, CURLOPT_HEADER,$cookies);  //设置头信息的地方 
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1521.3 Safari/537.36");

    curl_setopt($ch, CURLOPT_HEADER, 0);   
    curl_setopt($ch, CURLOPT_COOKIE, $cookie); 
	curl_setopt($ch, CURLOPT_REFERER, "https://mp.weixin.qq.com/cgi-bin/message?t=message/list&count=20&day=7&token=$token&lang=zh_CN");
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);	
	$output = curl_exec($ch);
	//var_dump($output);
   	curl_close($ch);
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
      return $img;//storge中的头像地址
	}
	  
    public function getmessage($token,$cookie,$cookies='')//从拿到的cookie我们获取最新的回复消息
	{    
		load()->func('communication');
   $url = "https://mp.weixin.qq.com/cgi-bin/message?t=message/list&count=20&day=7&token=".$token."&lang=zh_CN";
						$refer = "https://mp.weixin.qq.com/cgi-bin/contactmanage?t=user/index&token=".$token."&lang=zh_CN&pagesize=10&pageidx=0&type=0&groupid=0";
             $outputs = ihttp_request($url, '', array('CURLOPT_REFERER' => $refer, 'CURLOPT_COOKIE' => $cookie));
            
		    
			 $output = $outputs['content'];
    $u_msg=substr($output,(strpos($output,"{\"msg_item\":")+14));
    $abc=substr($u_msg,(strpos($u_msg,"{\"msg_item\":[\":")+1));
	//var_dump($u_msg);
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
		'imgcode' => $verify,
		'f' => 'json',	
	);
	load()->func('communication');
	$response = ihttp_request($loginurl, $post, array('CURLOPT_REFERER' => 'https://mp.weixin.qq.com/'));
	if (is_error($response)) {
		return false;
	}
	$data = json_decode($response['content'], true);
	if ($data['base_resp']['ret'] == 0) {
		preg_match('/token=([0-9]+)/', $data['redirect_url'], $match);//token=82628120
		
		$token = trim($match[1]);//取得token
        $cookienew =  implode('; ', $response['headers']['Set-Cookie']);      
	
                $cookienew = iserializer($cookienew);
		$cookienews = 'Meepo';
		$back = array($cookienew,$cookienews,$token);
         
		return $back;
	}else{
	   return false;
	}
   }
public function monilogin(){
	    global $_W,$_GPC;
		$wechat = $this->module['config']; 
		$pass  = md5($wechat['pass']);
		$res=$this->login($wechat['user'],$pass);
		
		pdo_update('zam_cookie',array('cookie'=>$res[0],'cookies'=>$res[1],'token'=>$res[2]),array('weid'=>$weid));
        

 }
 public function updateit($content,$createtime){
	  global $_W,$_GPC;
		$weid = $_W['uniacid'];
		$openid = $_W['fans']['from_user'];
         load()->func('communication');
						$cfgsql = "SELECT * FROM ".tablename('zam_cookie')." WHERE weid=:weid";
						$cfg = pdo_fetch($cfgsql,array(':weid'=>$weid));
						$token = $cfg['token'];
						$cookie = iunserializer($cfg['cookie']);
						/*此代码用来处理用户详细信息并输出*/
						$xiaobai=$this->getmessage($token,$cookie,$cookies);//将用户消息转换成变量 在此得到了粉丝的最新消息大概20条
						 
						$date_time=$xiaobai[0]["date_time"];
						
						$i=0;
						for (;$i<=19;$i++){
						if($xiaobai[$i]["content"] = $content){
							break;
							}
						}
					
						if($i == 20 && $date_time){
						    $this->monilogin();
							return '0';
						  }
						
						if($date_time){//最新消息结构
									
								$fakeid=$xiaobai[$i]["fakeid"];
								//print_r($fakeid);
								$msgid = $xiaobai[$i]["id"];
							   $data['fakeid'] = $fakeid;
							   $data['msgid'] = $msgid;
							   $data['jointime'] = time();
							   //$res = $this->getuserinfo();
							   //if(!empty($res)){
							        pdo_update('zam_userinfo',$data,array('weid'=>$weid,'openid'=>$openid));
							   //}else{
							       // pdo_insert('zam_userinfo',$data);
							   //}
							    return '1';
						}else{ 
								
								$this->monilogin();
							  return '0';
					   }
 }
}