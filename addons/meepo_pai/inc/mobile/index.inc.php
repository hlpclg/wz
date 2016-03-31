<?php 
global $_W,$_GPC;
session_start();
$act = !empty($_GPC['act'])?$_GPC['act']:'index';
$title = '全民自拍';
$setting = pdo_fetch("SELECT * FROM ".tablename('meepo_pai_set')." WHERE uniacid='{$_W['uniacid']}'");
$copy = $setting;
$copy = array(
	'main' => array('title'=>'Meepo校园联盟','url'=>'','des'=>''),
	'kaifa' => array('title'=>'Meepo校园联盟','url'=>'','des'=>''),
	'activity' => array('title'=>'Meepo校园联盟','url'=>'','des'=>''),
	'zanzhu1' => array('title'=>'Meepo校园联盟','url'=>'','des'=>''),
	'zanzhu2' => array('title'=>'Meepo校园联盟','url'=>'','des'=>''),
	'zanzhu3' => array('title'=>'Meepo校园联盟','url'=>'','des'=>''),
	'yindao'=>array('title'=>'我也要参加','url'=>$setting['share_url']),
);
if($act == 'index'){
	$sex = !empty($_GPC['sex'])?intval($_GPC['sex']):0;
	$newvote = get_newvote();
	
	if(empty($newvote)){
		$value = array(
			'status'=>8199,
			'content'=>$newvote
		);
		
		
	}
	$value = array(
		'status'=>8000,
		'content'=>$newvote
	);
	$ip = getip();
	$new_vote = pdo_fetch("SELECT * FROM ".tablename('meepo_newvote')." WHERE ip='{$ip}'");
		
	if(empty($new_vote)){
		pdo_insert('meepo_newvote',array('ip'=>$ip,'left_uid'=>$newvote[0]['uid'],'right_uid'=>$newvote[1]['uid'],'time'=>time()));
	}else{
		pdo_update('meepo_newvote',array('left_uid'=>$newvote[0]['uid'],'right_uid'=>$newvote[1]['uid'],'time'=>time()),array('ip'=>$ip));
	}
	include $this->template('index');
}

if($act == 'list'){
	$sex = !empty($_GPC['sex'])?intval($_GPC['sex']):0;
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$condition = "AND sex = '{$sex}' AND uniacid = '{$_W['uniacid']}'";
	if($_W['uniacid']==19){
		$condition = "AND sex = '{$sex}'";
	}
	$userss = pdo_fetchall("SELECT * FROM ".tablename('meepo_pai')." WHERE 1 {$condition} ORDER BY num DESC LIMIT " . ($pindex - 1) * $psize . "," . $psize);
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('meepo_pai')." WHERE 1 {$condition}");
	
	$pager = pagination($total, $pindex, $psize);
	include $this->template('list');
}

if($act == 'report'){
	
	global $_W,$_GPC;
	$uid = intval($_GPC['id']);
	if($uid == 0){
		//举报左边
		$ip = getip();//举报人ip
		$new_vote = pdo_fetch("SELECT * FROM ".tablename('meepo_newvote')." WHERE ip='{$ip}' limit 1");
		$user_id = $new_vote['left_uid'];//举报的人
		$contact = $_GPC['contact'];
		$repoer_reason = $_GPC['repoer_reason'];
		$report_content = $_GPC['report_content'];
		pdo_insert('meepo_pai_report',array('repoer_reason'=>$repoer_reason,'contact'=>$contact,'report_content'=>$report_content,'ip'=>$ip,'time'=>time(),'uid'=>$user_id,'uniacid'=>$_W['uniacid']));
		$value = array(
			'status'=>8300
		);
	}elseif($uid == 1){
		//举报右边
		$ip = getip();//举报人ip
		$new_vote = pdo_fetch("SELECT * FROM ".tablename('meepo_newvote')." WHERE ip='{$ip}' limit 1");
		$user_id = $new_vote['right_uid'];//举报的人
		$contact = $_GPC['contact'];
		$repoer_reason = $_GPC['repoer_reason'];
		$report_content = $_GPC['report_content'];
		pdo_insert('meepo_pai_report',array('repoer_reason'=>$repoer_reason,'contact'=>$contact,'report_content'=>$report_content,'ip'=>$ip,'time'=>time(),'uid'=>$user_id,'uniacid'=>$_W['uniacid']));
		$value = array(
			'status'=>8300
		);
	}else{
		$value = array(
			'status'=>8999
		);
	}
	
	die(json_encode($value));

}

if($act == 'vote'){
	global $_W,$_GPC;
	$uid = intval($_GPC['id']);
	if($uid == 0){
		//投的左边
		$ip = getip();
		$new_vote = pdo_fetch("SELECT * FROM ".tablename('meepo_newvote')." WHERE ip='{$ip}' limit 1");
		$user_id = $new_vote['left_uid'];
		$log = pdo_fetch("SELECT uid FROM ".tablename('meepo_pai_log')." WHERE ip='{$ip}' AND uid='{$user_id}'");
		if(empty($log)){
			pdo_insert('meepo_pai_log',array('uid'=>$user_id,'ip'=>$ip,'time'=>time(),'uniacid'=>$_W['uniacid']));
			pdo_query("UPDATE ".tablename('meepo_pai')." SET num = num + 1 WHERE uniacid='{$_W['uniacid']}' AND uid='{$user_id}'");
			$value = array(
				'status'=>8100
			);
		}else{
			//已经投过票了
			if($setting['chongfu']){
				pdo_query("UPDATE ".tablename('meepo_pai')." SET num = num + 1 WHERE uniacid='{$_W['uniacid']}' AND uid='{$user_id}'");
				pdo_insert('meepo_pai_log',array('uid'=>$user_id,'ip'=>$ip,'time'=>time(),'uniacid'=>$_W['uniacid']));
			}
			$value = array(
				'status'=>8104
			);
		}
		
		
	}elseif($uid == 1){
		//投的右边
		$ip = getip();
		$new_vote = pdo_fetch("SELECT * FROM ".tablename('meepo_newvote')." WHERE ip='{$ip}' limit 1");
		$user_id = $new_vote['right_uid'];
		
		$log = pdo_fetch("SELECT uid FROM ".tablename('meepo_pai_log')." WHERE ip='{$ip}' AND uid='{$user_id}'");
		if(empty($log)){
			pdo_insert('meepo_pai_log',array('uid'=>$user_id,'ip'=>$ip,'time'=>time(),'uniacid'=>$_W['uniacid']));
			pdo_query("UPDATE ".tablename('meepo_pai')."SET num = num + 1 WHERE uniacid='{$_W['uniacid']}' AND uid='{$user_id}'");
			$value = array(
				'status'=>8100
			);
		}else{
		//已经投过票了
			if($setting['chongfu']){
				pdo_query("UPDATE ".tablename('meepo_pai')." SET num = num + 1 WHERE uniacid='{$_W['uniacid']}' AND uid='{$user_id}'");
				pdo_insert('meepo_pai_log',array('uid'=>$user_id,'ip'=>$ip,'time'=>time(),'uniacid'=>$_W['uniacid']));
			}
			$value = array(
				'status'=>8104
			);
		}
	}else{
		$value = array(
			'status'=>8999
		);
	}
	
	die(json_encode($value));
}

if($act == 'newvote'){
	$newvote = get_newvote();
	
	if(empty($newvote)){
		$value = array(
			'status'=>8199,
			'content'=>$newvote
		);
		
		
	}
	$value = array(
		'status'=>8000,
		'content'=>$newvote
	);
	$ip = getip();
	$new_vote = pdo_fetch("SELECT * FROM ".tablename('meepo_newvote')." WHERE ip='{$ip}'");
		
	if(empty($new_vote)){
		pdo_insert('meepo_newvote',array('ip'=>$ip,'left_uid'=>$newvote[0]['uid'],'right_uid'=>$newvote[1]['uid'],'time'=>time()));
	}else{
		pdo_update('meepo_newvote',array('left_uid'=>$newvote[0]['uid'],'right_uid'=>$newvote[1]['uid'],'time'=>time()),array('ip'=>$ip));
	}
	die(json_encode($value));
}




function get_newvote(){
	global $_W,$_GPC;
	$sex = !empty($_GPC['sex'])?intval($_GPC['sex']):0;
	$condition = "AND sex = '{$sex}' AND uniacid = '{$_W['uniacid']}'";
	if($_W['uniacid']==19){
		$condition = "AND sex = '{$sex}'";
	}
	
	$pais = pdo_fetchall("SELECT * FROM " . tablename('meepo_pai') . " WHERE 1 {$condition} ORDER BY rand() DESC limit 2");
	
	return $pais;
}