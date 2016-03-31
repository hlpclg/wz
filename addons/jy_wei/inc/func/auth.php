<?php

if (!empty($_W['openid']) && intval($_W['account']['level']) >= 3) {
	$accObj = WeiXinAccount::create($_W['account']);
	$userinfo = $accObj->fansQueryInfo($_W['openid']);			
}

$state = 'weihezisid-'.$_W['session_id'];

$_SESSION['dest_url'] = base64_encode($_SERVER['QUERY_STRING']);

$code = $_GET['code'];
$from_user=$_W['openid'];

if(empty($code)){
	if($userinfo['subscribe']==0){
		$url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('auth');
		$callback = urlencode($url);
		$forward = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$_W['oauth_account']['key'].'&redirect_uri='.$callback.'&response_type=code&scope=snsapi_userinfo&state='.$state.'#wechat_redirect';
		header("Location: ".$forward);									
	}else{
		//用户已经关注改公众号了
		$weid=$_W['uniacid'];
		$fan_temp=pdo_fetch("SELECT * FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);
		if(!empty($userinfo) && !empty($userinfo['headimgurl']) && !empty($userinfo['nickname'])){
			$userinfo['avatar'] = $userinfo['headimgurl'];
			unset($userinfo['headimgurl']);

			//开启了强制注册，自定义注册
			$default_groupid = pdo_fetchcolumn('SELECT groupid FROM ' .tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(':uniacid' => $_W['uniacid']));

			$data = array(
				'uniacid' => $_W['uniacid'],
				'email' => md5($_W['openid']).'@9yetech.com'.$op,
				'salt' => random(8),
				'groupid' => $default_groupid, 
				'createtime' => TIMESTAMP,
				'nickname' 		=> $userinfo['nickname'],
				'avatar' 		=> $userinfo['avatar'],
				'gender' 		=> $userinfo['sex'],
				'nationality' 	=> $userinfo['country'],
				'resideprovince'=> $userinfo['province'] . '省',
				'residecity' 	=> $userinfo['city'] . '市',
			);
			$data['password'] = md5($_W['openid'] . $data['salt'] . $_W['config']['setting']['authkey']);						

			if(empty($fan_temp)){
				pdo_insert('mc_members', $data);
				$uid = pdo_insertid();
			}else{
				pdo_update('mc_members' ,$data ,array('uid'=>$fan_temp['uid']));
				$uid=$fan_temp['uid'];
			}

			$record = array(
				'openid' 		=> $_W['openid'],
				'uid' 			=> $uid,
				'acid' 			=> $_W['acid'],
				'uniacid' 		=> $_W['uniacid'],
				'salt' 			=> random(8),
				'updatetime' 	=> TIMESTAMP,
				'nickname' 		=> $userinfo['nickname'],
				'follow' 		=> $userinfo['subscribe'],
				'followtime' 	=> $userinfo['subscribe_time'],
				'unfollowtime' 	=> 0,
				'tag' 			=> base64_encode(iserializer($userinfo))
			);
			$record['uid'] = $uid;
			if(empty($fan_temp)){
				pdo_insert('mc_mapping_fans', $record);
			}else{
				pdo_update('mc_mapping_fans' ,$record ,array('fanid'=>$fan_temp['fanid']));
			}
		}
	}
}else{ 
	//未关注，通过网页授权
	$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$_W['oauth_account']['key']."&secret=".$_W['oauth_account']['secret']."&code=".$code."&grant_type=authorization_code";
	$response = ihttp_get($url);
	$oauth = @json_decode($response['content'], true);

	$url = "https://api.weixin.qq.com/sns/userinfo?access_token={$oauth['access_token']}&openid={$oauth['openid']}&lang=zh_CN";
	$response = ihttp_get($url);

	if (!is_error($response)) {

		$userinfo = array();
		$userinfo = @json_decode($response['content'], true);

		$userinfo['avatar'] = $userinfo['headimgurl'];
		unset($userinfo['headimgurl']);

		$_SESSION['userinfo'] = base64_encode(iserializer($userinfo));
		
		if(!empty($userinfo) && !empty($userinfo['avatar']) && !empty($userinfo['nickname'])){
			$weid=$_W['uniacid'];
			$fan_temp=pdo_fetch("SELECT * FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);
			//开启了强制注册，自定义注册
			$default_groupid = pdo_fetchcolumn('SELECT groupid FROM ' .tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(':uniacid' => $_W['uniacid']));
			$data = array(
				'uniacid' => $_W['uniacid'],
				'email' => md5($_W['openid']).'@9yetech.com'.$op,
				'salt' => random(8),
				'groupid' => $default_groupid, 
				'createtime' => TIMESTAMP,
				'nickname' 		=> $userinfo['nickname'],
				'avatar' 		=> rtrim($userinfo['avatar'], '0') . 132,
				'gender' 		=> $userinfo['sex'],
				'nationality' 	=> $userinfo['country'],
				'resideprovince'=> $userinfo['province'] . '省',
				'residecity' 	=> $userinfo['city'] . '市',
			);
			$data['password'] = md5($_W['openid'] . $data['salt'] . $_W['config']['setting']['authkey']);

			if(empty($fan_temp)){
				pdo_insert('mc_members', $data);
				$uid = pdo_insertid();
			}else{
				pdo_update('mc_members' ,$data ,array('uid'=>$fan_temp['uid']));
				$uid=$fan_temp['uid'];
			}

			$record = array(
				'openid' 		=> $_W['openid'],
				'uid' 			=> $uid,
				'acid' 			=> $_W['acid'],
				'uniacid' 		=> $_W['uniacid'],
				'salt' 			=> random(8),
				'updatetime' 	=> TIMESTAMP,
				'nickname' 		=> $userinfo['nickname'],
				'follow' 		=> $userinfo['subscribe'],
				'followtime' 	=> $userinfo['subscribe_time'],
				'unfollowtime' 	=> 0,
				'tag' 			=> base64_encode(iserializer($userinfo))
			);
			$record['uid'] = $uid;

			if(empty($fan_temp)){
				pdo_insert('mc_mapping_fans', $record);
			}else{
				$temp=pdo_update('mc_mapping_fans' ,$record ,array('fanid'=>$fan_temp['fanid']));
			}
		}
	} else {
		message('微信授权获取用户信息失败,请重新尝试: ' . $response['message']);
	}
}

$url = $_SESSION['authurl'];
if(!$url){
	exit('网络错误');
}
echo "<script>window.location.href = '".$url."';</script>";
?>