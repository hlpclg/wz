<?php
global $_W,$_GPC;
if(empty($_W['isfounder'])) {
	message('您没有相应操作权限', '', 'error');
}

init();
$ip =gethostbyname($_SERVER['SERVER_ADDR']);
$domain =$_SERVER['HTTP_HOST'];
$setting =setting_load('site');
$id =isset($setting['site']['key'])? $setting['site']['key'] : '1';

$auth = getAuthSet($this->modulename);
load()->func('communication');

if(empty($auth['code'])){
	$resp =ihttp_post('http://meepo.com.cn/meepo/module/oauth.php',array('ip'=>$ip,'id'=>$id,'domain'=>$domain,'module'=>$this->modulename));
}

if(checksubmit('submit')){
	if (empty($_GPC['domain'])){
		message('域名不能为空!', '', 'error');
	}
	if (empty($_GPC['code'])){
		message('请填写授权码!', '', 'error');
	}
	if (empty($_GPC['id'])){
		message('您还没未注册站点!', '', 'error');
	}
	//发送请求，验证授权
	$resp =ihttp_post('http://meepo.com.cn/meepo/module/oauth.php',array('ip'=>$ip,'id'=>$id,'code'=>$_GPC['code'],'domain'=>$domain,'module'=>$this->modulename));
	$content = json_decode($resp['content']);
	$status = intval($content->status);
	$message = trim($content->message);
	if ($status == 1){
		$set =pdo_fetch('SELECT * FROM ' . tablename('meepo_module'). ' WHERE module = :module limit 1', array(':module' => $this->modulename));
		$sets =iunserializer($set['set']);
		if (!is_array($sets)){
			$sets =array();
		}
		$sets['auth'] =array('ip' => $ip, 'id' => $id, 'code' => $_GPC['code'], 'domain'=>$_GPC['domain'] );
		if (empty($set)){
			pdo_insert('meepo_module', array('set' => iserializer($sets), 'module' => $this->modulename,'time'=>time()));
		}else{
			pdo_update('meepo_module', array('set' => iserializer($sets),'time'=>time()),array('module'=>$this->modulename));
		}
		message('系统授权成功！', referer(), 'success');
	}
	message('授权失败，请联系客服! 错误信息:'.$message);
}

$status =0;
if (!empty($ip)&& !empty($id) && !empty($auth['code'])){
	load()->func('communication');
	//发送请求，验证授权
	$resp =ihttp_post('http://meepo.com.cn/meepo/module/oauth.php',array('ip'=>$ip,'id'=>$id,'code'=>$auth['code'],'domain'=>$domain,'module'=>$this->modulename));
	$content = json_decode($resp['content']);
	$status = intval($content->status);
	$message = trim($content->message);
	if ($status == 1){
		$status =1;
		/* $url = $this->createWebUrl('download');
		header("location:$url");
		exit(); */
	}
}
include $this->template('upgate');