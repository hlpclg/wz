<?php
/* meepo_pai
 * 
 * pid
 * nickname
 * sex
 * src_img
 * uid
 * vid
 * num
 * school
 * 
 * meepo_pai_images
 * 
 * id
 * url
 * time
 * uid
 * 
 * meepo_pai_log
 * 
 * id
 * uid
 * ip
 * time
 * 
 * */
global $_W,$_GPC;
if($_W['isajax']){
	$uid = intval($_GPC['id']);
	$ip = getip(); 
	
	$user = pdo_fetch("SELECT uid FROM ".tablename('meepo_pai')." WHERE uid='{$uid}' limit 1");
	if(empty($user)){
		//用户不存在
		$value = array(
			'status'=>8999
		);
	}
	
	$vote = pdo_fetch("SELECT ip FROM ".tablename('meepo_pai_log')." WHERE uid = '{$uid}' AND ip = '{$ip}' limit 1");
	if(!empty($vote)){
		//已经投票成功
		$value = array(
			'status'=>8104
		);
	}else{
		//投票成功
		pdo_query("UPDATE ".tablename('meepo_pai')." SET num = num +1 WHERE uid ='{$uid}' AND uniacid='{$_W['uniacid']}'");
		pdo_insert('meepo_vote_log',array('uid'=>$uid,'ip'=>$ip,'time'=>time()));
		$value = array(
			'status'=>8110
		);
		
	}
	if($uid == -1){
		$value = array(
			'status'=>8999
		);
	}
	die(json_encode($value));
}
