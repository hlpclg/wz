<?php
/**
 * 抢楼活动模块微站定义
 *
 * @author 美丽心情
 * @qq 513316788
 */
defined('IN_IA') or exit('Access Denied');

class bm_floorModuleSite extends WeModuleSite {
	public function doWebAwardlist() {
		global $_GPC, $_W;
		checklogin();
		$id = intval($_GPC['id']);
		
		if (!empty($_GPC['wid'])) {
			$wid = intval($_GPC['wid']);
			pdo_update('bm_floor_winner', array('status' => intval($_GPC['status'])), array('id' => $wid));
			message('操作成功！', $this->createWebUrl('awardlist', array('do' => 'awardlist', 'name' => 'bm_floor', 'id' => $id, 'page' => $_GPC['page'], 'state' => '')));
		}
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('bm_floor_winner')." AS w WHERE w.rid='$id'");
		if ($total > 0) {
			$list = pdo_fetchall("SELECT w.* FROM ".tablename('bm_floor_winner')." AS w WHERE w.rid='$id' ORDER BY w.id ASC");
			$pager = pagination($total, $pindex, $psize);
		}
		include $this->template('awardlist');
	}

	public function doMobileAwardsubmit() {
		global $_W, $_GPC;
		$from_user = trim($_GPC['from_user']);
		$rid = trim($_GPC['rid']);
		//print_r($from_user);print_r($rid);exit;
		$success = 0;
		//print_r($from_user);
		if ($from_user != '') {
			//include 'common.inc.php';
			$from_user = $from_user;
			$user = fans_search($from_user);
			//print_r('<pre>');print_r($user);exit;
		}
		//print_r($_GPC['btnsubmit']);
        if (!empty($_GPC['realname'])) {
			$user = array(
				'realname' => trim($_GPC['realname']),
				'mobile' => trim($_GPC['mobile']),
				'qq' => trim($_GPC['qq']),
			);
			//print_r('<pre>');print_r($user);exit;
			fans_update($from_user, $user);
			//$user = fans_search($from_user);
			//print_r('<pre>');print_r($user);			
			pdo_update('bm_floor_winner', array('realname' => trim($_GPC['realname']),'mobile' => trim($_GPC['mobile']),'qq' => trim($_GPC['qq'])), array('from_user' => $from_user , 'rid' => $rid));
			$success = 1;
			$sql = "select * from ".tablename('bm_floor')." where rid = " . $rid;
			//print_r($this->rule);print_r($rid);exit;
			$it    = pdo_fetch($sql);
			$url = $it['url'];
			//print_r($url);exit;
		}
		//print_r('ok');exit;
		include $this->template('awardsubmit');
	}
	
	public function doMobileAwardlist() {
		global $_W, $_GPC;
		//include_once IA_ROOT . '/source/modules/oauth2/model.php';
		//include_once IA_ROOT . '/source/modules/oauth2/emoji.php';		
		//$user = oauth2::fetch_userinfo();
		//print_r('<pre>');print_r($user);exit;
		$rid = trim($_GPC['rid']);
		$from_user = trim($_GPC['from_user']);
		//$winner = pdo_fetchall("SELECT * FROM ".tablename('bm_floor_winner')." WHERE rid = :rid ORDER BY `floor` DESC", array(':rid' => $rid));
		$winnertotal = pdo_fetch("SELECT count(*) as sum FROM ".tablename('bm_floor_winner')." WHERE rid = :rid", array(':rid' => $rid));
		$awardtotal = pdo_fetch("SELECT count(*) as sum FROM ".tablename('bm_floor_award')." WHERE rid = :rid", array(':rid' => $rid));
		$mytotal = pdo_fetch("SELECT count(*) as sum FROM ".tablename('bm_floor_winner')." WHERE from_user = :from_user and rid = :rid", array(':from_user' => $from_user,':rid' => $rid));		
		//print_r('<pre>');print_r($winner);exit;
		$award = pdo_fetchall("SELECT * FROM ".tablename('bm_floor_award')." WHERE rid = :rid ORDER BY `floor` DESC", array(':rid' => $rid));
		$floor = pdo_fetch("SELECT * FROM ".tablename('bm_floor')." WHERE rid = :rid", array(':rid' => $rid));
		//print_r('<pre>');print_r($award);exit;
		include $this->template('awardlist');
	}	
	
	public function doMobileWinnerlist() {
		global $_W, $_GPC;
		$rid = trim($_GPC['rid']);
		$from_user = trim($_GPC['from_user']);
		$winner = pdo_fetchall("SELECT * FROM ".tablename('bm_floor_winner')." WHERE rid = :rid ORDER BY `floor` DESC", array(':rid' => $rid));
		$winnertotal = pdo_fetch("SELECT count(*) as sum FROM ".tablename('bm_floor_winner')." WHERE rid = :rid", array(':rid' => $rid));
		$awardtotal = pdo_fetch("SELECT count(*) as sum FROM ".tablename('bm_floor_award')." WHERE rid = :rid", array(':rid' => $rid));
		$mytotal = pdo_fetch("SELECT count(*) as sum FROM ".tablename('bm_floor_winner')." WHERE from_user = :from_user and rid = :rid", array(':from_user' => $from_user,':rid' => $rid));		
		//print_r('<pre>');print_r($winner);exit;
		//$award = pdo_fetchall("SELECT * FROM ".tablename('bm_floor_award')." WHERE rid = :rid ORDER BY `floor` DESC", array(':rid' => $rid));
		$floor = pdo_fetch("SELECT * FROM ".tablename('bm_floor')." WHERE rid = :rid", array(':rid' => $rid));
		//print_r('<pre>');print_r($award);exit;
		include $this->template('winnerlist');
	}		

	public function doMobileMyaward() {
		global $_W, $_GPC;
		$rid = trim($_GPC['rid']);
		$from_user = trim($_GPC['from_user']);
		//$winner = pdo_fetchall("SELECT * FROM ".tablename('bm_floor_winner')." WHERE rid = :rid ORDER BY `floor` DESC", array(':rid' => $rid));
		$floor = pdo_fetch("SELECT * FROM ".tablename('bm_floor')." WHERE rid = :rid", array(':rid' => $rid));
		$winnertotal = pdo_fetch("SELECT count(*) as sum FROM ".tablename('bm_floor_winner')." WHERE rid = :rid", array(':rid' => $rid));
		$awardtotal = pdo_fetch("SELECT count(*) as sum FROM ".tablename('bm_floor_award')." WHERE rid = :rid", array(':rid' => $rid));
		$mytotal = pdo_fetch("SELECT count(*) as sum FROM ".tablename('bm_floor_winner')." WHERE from_user = :from_user and rid = :rid", array(':from_user' => $from_user,':rid' => $rid));	
		$myaward = pdo_fetchall("SELECT * FROM ".tablename('bm_floor_winner')." WHERE from_user = :from_user and rid = :rid ORDER BY `floor` DESC", array(':from_user' => $from_user,':rid' => $rid));		
		//print_r('<pre>');print_r($mylist);exit;
		include $this->template('myaward');
	}	
	
	public function doMobileGetAward() {
		global $_W, $_GPC;
		$id = trim($_GPC['id']);
		$rid = trim($_GPC['rid']);		
		$from_user = trim($_GPC['from_user']);
		//print_r($from_user);exit;
		if (checksubmit('submit')) {
			if (empty($_GPC['password'])) {
				message('请输入验证密码！');
			}
			$password = $_GPC['password'];
			$sql="SELECT b.password,b.rid from ".tablename('bm_floor_winner')." AS a INNER JOIN ".tablename('bm_floor')." AS b ON a.rid = b.rid WHERE a.id='{$id}' and a.status=0 and password='{$password}'";
			//print_r($sql);exit;
			$row = pdo_fetch($sql);		
			if (!empty($row)) {
				pdo_update('bm_floor_winner', array(
					'status' => 1,
				), array('from_user' => $from_user, 'id' => $id));
				message('您已成功兑奖！', $this->createMobileUrl('myaward', array('from_user' => $from_user , 'rid' => $rid ,'weid' => $_W['weid'])), 'success');
			} else {
				message('兑奖密码验证失败，请重试！', $this->createMobileUrl('myaward', array('from_user' => $from_user , 'rid' => $rid ,'weid' => $_W['weid'])), 'error');
			}
		}
		$sql="SELECT * from ".tablename('bm_floor_winner')." WHERE id='{$id}'";
		$item = pdo_fetch($sql);
		//print_r('<pre>');print_r($item);exit;		
		include $this->template('getaward');
	}
	
	//分享展示
	public function doMobileshow(){
		global $_GPC, $_W;
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		if(!strpos($userAgent,'MicroMessenger')){
			//include $this->template('s404');
			//exit();
		}
		$fromuser = authcode(base64_decode($_GPC['from_user']), 'DECODE');
		if (empty($fromuser)) {
			exit('非法参数！');
		}
		$rid = intval($_GPC['rid']);
		$pan = pdo_fetch("SELECT * FROM " . tablename('bm_floor') . " WHERE rid = '$rid'  LIMIT 1");
		if (empty($pan)) {
			exit('非法参数！');
		}
		$weid= $_GPC['weid'];
		$rid = $_GPC['rid'];
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('bm_floor_member') . " WHERE rid = '{$rid}' and weid='{$weid}'");
		$member = pdo_fetch("SELECT * FROM " . tablename('bm_floor_member') . " WHERE from_user = '{$fromuser}' and rid='{$rid}' and weid='{$weid}' LIMIT 1");
		$list =  pdo_fetchall("select realname,mobile,share_point from " . tablename('mc_mapping_fans') . " a inner join " . tablename('bm_floor_member') . " b on a.openid=b.from_user inner join " . tablename('mc_members') . " c on a.uid=c.uid where b.rid='{$rid}' and c.realname<>'' and c.mobile<>'' ORDER BY share_point DESC LIMIT 100");
		$orderNum =  pdo_fetchcolumn("select COUNT(*) from ".tablename('bm_floor_member')." where rid = '{$rid}' and weid='{$weid}' and  share_point>(SELECT share_point from ".tablename('bm_floor_member')." where from_user='".$fromuser."' and rid = '{$rid}' and weid='{$weid}') order by share_point desc LIMIT 1");
		
		$orderNum = $orderNum + 1;
		if(!empty($member)){
			$state = 1;	
		}else{
			$state = 0;		
		}
		//print_r($pan['share_url']);print_r('||');print_r($pan['picture']);exit;
		
		$staturl=$_W['siteroot'].'app/'.$this->createMobileUrl('tongji', array('rid' => $rid, 'weid' => $weid, 'url' => urlencode($pan['share_url']), 'from_user' => $_GPC['from_user']));
		//print_r($staturl);exit;
		$imgurl=$_W['attachurl'].$pan['picture'];
		//print_r($imgurl);exit;
		include $this->template('show');
	}	
	//提交用户姓名和手机号码
	public function doMobileajaxGetUser(){
		global $_GPC, $_W;
		$fromuser = authcode(base64_decode($_GPC['from_user']), 'DECODE');
		$weid= $_GPC['weid'];
		$data = array(
					'realname' => $_GPC['username'],
					'mobile' => $_GPC['mobile'],
		);
		if (empty($data['realname'])) {
				die('<script>alert("请填写您的真实姓名！");history.go(-1);</script>');		
			}
		if (empty($data['mobile'])) {
				die('<script>alert("请填写您的手机号码！");history.go(-1);</script>');	
		}
		$fromuser = authcode(base64_decode($_GPC['from_user']), 'DECODE');
		// 判断fans表里面有没有记录	
		//pdo_update('fans', $data, array('from_user' => $fromuser));
		fans_update($from_user, $data);		
		$insert = array(
				'weid' => $_GPC['weid'],
				'rid' => $_GPC['rid'],
				'from_user' => $fromuser,
				'IPaddress' => $_W['clientip'],
				'createtime' => TIMESTAMP
		);
		$pan = pdo_fetch("SELECT * FROM ".tablename('bm_floor_member')." WHERE rid = '{$rid}' and weid='{$weid}' and from_user='{$fromuser}'");
		if(empty($pan)){
			pdo_insert('bm_floor_member', $insert);
			message('提交成功！', referer(), 'success');
		}else{
			message('已经存在此用户', referer(), 'success');
		}	
	}
	//屏蔽电话号码中间的四位数字
	public function hidtel($phone)
	{
		 $IsWhat = preg_match('/(0[0-9]{2,3}[\-]?[2-9][0-9]{6,7}[\-]?[0-9]?)/i',$phone); //固定电话
		 if($IsWhat == 1)
		 {
		  return preg_replace('/(0[0-9]{2,3}[\-]?[2-9])[0-9]{3,4}([0-9]{3}[\-]?[0-9]?)/i','$1****$2',$phone);
		
		 }
		 else
		 {
		  return  preg_replace('/(1[358]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$phone);
		 }
	}	
	// 点击量统计
	public function doMobileTongji(){
		global $_GPC, $_W;
		$IPaddress = CLIENT_IP;
		$URL = urldecode($_GPC['url']);
		//print_r($URL);print_r('abc');exit;
		$fromuser = authcode(base64_decode($_GPC['from_user']), 'DECODE');
		
		if (empty($fromuser)) {
			exit('非法参数！');
		}
		$member = pdo_fetch("SELECT IPaddress,share_point FROM ".tablename('bm_floor_member')." WHERE from_user = '{$fromuser}' and rid='".$_GPC['rid']."' LIMIT 1");
		$share = pdo_fetch("SELECT share_point FROM ".tablename('bm_floor')." WHERE rid='".$_GPC['rid']."' LIMIT 1");		
		//if($IPaddress != $member['IPaddress']){
		//echo $fromuser;
		if($IPaddress != '1'){		
			if(!isset($_COOKIE["tangel"])){ 
			//if($IPaddress != '1'){
				//cookies不存在
				setcookie('tangel','flag',time()+86400);
				$data = array(
					'IPaddress' => $IPaddress,
					'share_point' => $member['share_point'] + $share['share_point']
				);
				pdo_update('bm_floor_member', $data,array('from_user' => $fromuser));	
			}
			//echo $member['share_point'];exit();
		}
		//echo $fromuser; echo $member['IPaddress'];echo $IPaddress; exit();
		//print_r($URL);exit;
		header("Location: ".$URL ); //跳转
		if (empty($URL)) {
		//include $this->template('tongji');
		}
		
	}	
}
