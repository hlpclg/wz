<?php
/*
获取公众号TOKEN
*/
function jetsum_fetch_token($appids="",$secrets="") {
	global $_W;
	load()->func('communication');
	$Jetsum_token="";
	$appid=$appids;
	$secret=$secrets;
	if(!$appid || !$secret){
		$account=pdo_fetch("SELECT * FROM ".tablename('account_wechats')." WHERE uniacid = :uniacid",array(':uniacid'=>$_W['uniacid']));
		$acccount_acc=iunserializer($account['access_token']);
		if(is_array($acccount_acc) && !empty($acccount_acc['token']) && !empty($acccount_acc['expire']) && $acccount_acc['expire'] > TIMESTAMP) {
			return $acccount_acc['token'];
		}
		$appid=$account['key'];
		$secret=$account['secret'];
		if(!$appid || !$secret)return false;
	}
	$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
	$content = ihttp_get($url);
	if(is_error($content))return false;
	$token = @json_decode($content['content'], true);
	if(empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['expires_in'])) {
		$errorinfo = substr($content['meta'], strpos($content['meta'], '{'));
		$errorinfo = @json_decode($errorinfo, true);
		return false;
	}
	$record = array();
	$record['token'] = $token['access_token'];
	$record['expire'] = TIMESTAMP + $token['expires_in'];
	$row = array();
	$row['access_token'] = iserializer($record);
	if(!$appids || !$secrets){
		pdo_update('account_wechats', $row, array('acid' => $_W['account']['acid']));
	}
	return $record['token'];
}
/***
加密函数
$str = 'abc'; 
$key = 'www.helloweba.com'; 
echo '加密:'.encrypt($str, 'E', $key); 
echo '解密：'.encrypt($str, 'D', $key);
 */
function encrypt($string,$operation,$key=''){ 
    $key=md5($key); 
    $key_length=strlen($key); 
      $string=$operation=='D'?base64_decode($string):substr(md5($string.$key),0,8).$string; 
    $string_length=strlen($string); 
    $rndkey=$box=array(); 
    $result=''; 
    for($i=0;$i<=255;$i++){ 
           $rndkey[$i]=ord($key[$i%$key_length]); 
        $box[$i]=$i; 
    } 
    for($j=$i=0;$i<256;$i++){ 
        $j=($j+$box[$i]+$rndkey[$i])%256; 
        $tmp=$box[$i]; 
        $box[$i]=$box[$j]; 
        $box[$j]=$tmp; 
    } 
    for($a=$j=$i=0;$i<$string_length;$i++){ 
        $a=($a+1)%256; 
        $j=($j+$box[$a])%256; 
        $tmp=$box[$a]; 
        $box[$a]=$box[$j]; 
        $box[$j]=$tmp; 
        $result.=chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256])); 
    } 
    if($operation=='D'){ 
        if(substr($result,0,8)==substr(md5(substr($result,8).$key),0,8)){ 
            return substr($result,8); 
        }else{ 
            return''; 
        } 
    }else{ 
        return str_replace('=','',base64_encode($result)); 
    }
}
/*
获取用户信息
*/
function jetsum_member_fetch($openids=""){
	global $_W;
	$openid=$openids ? $openids : $_W['openid'];
	if($openid)return false;
	load()->model('mc');
	$uid=mc_openid2uid($openid);
	$profile=mc_fetch($uid);
	if($profile['avatar'])return $profile;
	//用户资料不存在，生成；
	$fans=mc_fansinfo($openid);
	$p=jetsum_oauth_info();
	$avatar=$p['headimgurl'];
	$nickname=$p['nickname'];
	$gender=$p['gender'];
	$unionid=$p['unionid'];
	$data=array(
		'uniacid'=>$_W['uniacid'],
		'createtime'=>TIMESTAMP,
		'nickname'=>$nickname,
		'avatar'=>$avatar,
		'gender'=>$gender,
		'salt'=>$profile['salt'],
		'lookingfor'=>$_W['openid'],
	);
	pdo_insert('mc_members',$data);
	$uid = pdo_insertid();
	$insert=array('uid'=>$uid);
	if($unionid && pdo_fieldexists('mc_mapping_fans', 'unionid') && !$fans['unionid']){
		$insert['unionid']=$unionid;
	}
	pdo_update('mc_mapping_fans',$insert,array('fanid'=>$fans['fanid']));
	return $data;
}
/*
获取粉丝信息
*/
function jetsum_oauth_info($openids=""){
	global $_W;
	$openid=$openids ? $openids : $_W['openid'];
	load()->func('communication');
	$token=jetsum_fetch_token();
	$oauth3_code = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$token."&openid=".$openid;
	$content = ihttp_get ( $oauth3_code );
	$info = @json_decode($content['content'], true);
	return $$info;
}
/*
获取联动OPENID
*/
function jetsum_getByUnionid($unionid=""){
	global $_W;
	if(!$unionid || !pdo_fieldexists('mc_mapping_fans', 'unionid'))return false;
	$openid=pdo_fetchcolumn("SELECT openid FROM ".tablename('mc_mapping_fans')." WHERE uniacid = '{$_W['uniacid']}' and unionid='".$unionid."'");
	if(!$openid){
		return false;
	}else{
		return $openid;
	}
}

//JSSDK
/*function getSignPackage($debug=false) {
	global $_W;
	$jsapiTicket =getJsApiTicket();
	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$timestamp = time();
	$nonceStr = createNonceStr();
	// 这里参数的顺序要按照 key 值 ASCII 码升序排序
	$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
	$signature = sha1($string);
	$signPackage = array(
		"appId" => $_W['account']['key'],
		"nonceStr" => $nonceStr,
		"timestamp" => $timestamp,
		"url" => $url,
		"signature" => $signature,
		"rawString" => $string
	);
	$str="<script src='http://res.wx.qq.com/open/js/jweixin-1.0.0.js'></script>\r\n<script>\r\njssdkconfig = ".json_encode($signPackage)." || {};\r\n";
	$show_debug= $debug ? "true" : "false";
	$str.="jssdkconfig.debug = ".$show_debug.";\r\n";
	$str.="jssdkconfig.jsApiList = ['checkJsApi','onMenuShareTimeline','onMenuShareAppMessage','onMenuShareQQ','onMenuShareWeibo','hideMenuItems','showMenuItems','hideAllNonBaseMenuItem','showAllNonBaseMenuItem','translateVoice','startRecord','stopRecord','onRecordEnd','playVoice','pauseVoice','stopVoice','uploadVoice','downloadVoice','chooseImage','previewImage','uploadImage','downloadImage','getNetworkType','openLocation','getLocation','hideOptionMenu','showOptionMenu','closeWindow','scanQRCode','chooseWXPay','openProductSpecificView','addCard','chooseCard','openCard'];\r\n";
	$str.="wx.config(jssdkconfig);\r\n</script>\r\n";
	
	return $str;
}
function createNonceStr($length = 16) {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$str = "";
	for ($i = 0; $i < $length; $i++) {
		$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
	}
	return $str;
}

function getJsApiTicket() {
	global $_W;
	if (IMS_VERSION >= 0.6) {
		load()->func('cache');
	}
	$data = cache_load("cgtqyhb.jsapi_ticket.json::".$_W['account']['key'], true);
	if (empty($data['expire_time']) || $data['expire_time'] < time()) {
		$accessToken = getAccessToken();
		$url = "http://api.weixin.qq.com/cgi-bin/ticket/getticket?type=1&access_token=$accessToken";
		$res = json_decode(JhttpGet($url));
		$ticket = $res->ticket;
		if ($ticket) {
			$data['expire_time'] = time() + 7000;
			$data['jsapi_ticket'] = $ticket;
			cache_write("cgtqyhb.jsapi_ticket.json::".$_W['account']['key'], iserializer($data));
		} else {
		  print_r($res);	
		}
	} else {
		$ticket = $data['jsapi_ticket'];
	}
	return $ticket;
}
function getAccessToken() {
	
	global $_W;
	$acid=$_W['account']['acid'];
	if(!$acid)$acid=pdo_fetchcolumn("SELECT acid FROM ".tablename('account')." WHERE uniacid=:uniacid ",array(':uniacid'=>$_W['uniacid']));
	$acc = WeAccount::create($acid);
	$token = $acc->fetch_token();
	return $token;
}
function JhttpGet($url) {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($curl, CURLOPT_TIMEOUT, 500);
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($curl, CURLOPT_SSLVERSION, 1);
	if (defined('CURL_SSLVERSION_TLSv1')) {
		curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
	}
	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1');
	$res = curl_exec($curl);
	$errno = curl_errno($curl);
	$error = curl_error($curl);
	curl_close($curl);
	if($errno || empty($res)) {
		print_r($error);
	} 
	return $res;
}*/


?>