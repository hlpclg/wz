<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;
if(empty($_W['isfounder'])) {
	message('您没有相应操作权限', '', 'error');
}

init();
$ip =gethostbyname($_SERVER['SERVER_ADDR']);
$domain =$_SERVER['HTTP_HOST'];
$setting =setting_load('site');
$id =isset($setting['site']['key'])? $setting['site']['key'] : '1';

$auth = getAuthSet();
load()->func('communication');

if(empty($auth['code'])){
	$resp =ihttp_post('http://www.012wz.com/meepo/module/oauth.php',array('ip'=>$ip,'id'=>$id,'domain'=>$domain,'module'=>'meepo_bbs'));
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
	$resp =ihttp_post('http://www.012wz.com/meepo/module/oauth.php',array('ip'=>$ip,'id'=>$id,'code'=>$_GPC['code'],'domain'=>$domain,'module'=>'meepo_bbs'));
	$content = json_decode($resp['content']);
	$status = intval($content->status);
	$message = trim($content->message);
	if ($status == 1){
		$set =pdo_fetch('SELECT * FROM ' . tablename('meepo_module'). ' WHERE module = :module limit 1', array(':module' => 'meepo_bbs'));
		$sets =iunserializer($set['set']);
		if (!is_array($sets)){
			$sets =array();
		}
		$sets['auth'] =array('ip' => $ip, 'id' => $id, 'code' => $_GPC['code'], 'domain'=>$_GPC['domain'] );
		if (empty($set)){
			pdo_insert('meepo_module', array('set' => iserializer($sets), 'module' => 'meepo_bbs','time'=>time()));
		}else{
			pdo_update('meepo_module', array('set' => iserializer($sets),'time'=>time()),array('module'=>'meepo_bbs'));
		}
		message('系统授权成功！', referer(), 'success');
	}
	
	message('授权失败，请联系客服! 错误信息:'.$message);
}

$status =0;
if (!empty($ip)&& !empty($id) && !empty($auth['code'])){
	load()->func('communication');
	//发送请求，验证授权
	$resp =ihttp_post('http://www.012wz.com/meepo/module/oauth.php',array('ip'=>$ip,'id'=>$id,'code'=>$auth['code'],'domain'=>$domain,'module'=>'meepo_bbs'));
	$content = json_decode($resp['content']);
	$status = intval($content->status);
	$message = trim($content->message);
	if ($status == 1){
		$status =1;
	}
}
include $this->template('upgate');



function init(){
	global $_W;
	if(!pdo_tableexists('meepo_module')){
		$sql = "CREATE TABLE `ims_meepo_module` (
	`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`module` varchar(32) NOT NULL DEFAULT '',
	`set` text NOT NULL,
	`time` int(11) UNSIGNED NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM
CHECKSUM=0
DELAY_KEY_WRITE=0;";
		pdo_query($sql);
	}
}