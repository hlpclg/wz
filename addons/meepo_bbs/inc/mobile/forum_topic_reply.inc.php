<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;

$forum = getSet();
load()->model('mc');

$tid = intval($_GPC['tid']);
if(empty($tid)){
	message('梯子不存在或已删除',$this->createMobileUrl('forum'),error);
}
$fid = intval($_GPC['fid']);
$uid = $_W['member']['uid'];
if(empty($uid)){
	$fan = mc_fansinfo($_W['openid'], $_W['acid'], $_W['uniacid']);
	_mc_login(array('uid' => intval($fan['uid'])));
	$uid = $_W['member']['uid'];
}

$sql = "SELECT * FROM ".tablename('meepo_bbs_topics')." WHERE id = :id";
$params = array(':id'=>$tid);
$topic = pdo_fetch($sql,$params);

if($_W['ispost']){
	
	if($_GPC['tab'] == 'begging'){
		$fee = floatval($_GPC['begging_money']);
		if(empty($fee)){
			message('请输入打赏金额',referer(),error);
		}
		if($fee <= 0) {
			message('支付错误, 金额小于0',referer(),error);
		}
		$uid = $topic['uid'];
		
		$user = mc_fetch($uid,array('nickname'));
		$data = array();
		$data['tid'] = date('YmdHi').random(10, 1);
		$data['fee'] = $fee;
		$data['type'] = 'begging';
		$data['time'] = time();
		$data['uniacid'] = $_W['uniacid'];
		$data['fopenid'] = $_W['openid'];
		$data['uid'] = $uid;
		$data['fid'] = $fid;
		$data['ttid'] = $tid;
		$data['status'] = 0;
		$data['content'] = trim($_GPC['content']);
		if(!empty($_GPC['thumb'])){
			foreach ($_GPC['thumb'] as $thumb){
				$th[] = save_media(tomedia($thumb));
			}
			$data['thumb'] = iserializer($th);
		}
		
		if(!pdo_insert('meepo_bbs_begging',$data)){
			message('创建打赏订单失败，请重试！', $this->createMobileUrl('forum_topic',array('id'=>$tid)), 'error');
		}
		$params = array(
				'tid' => $data['tid'],
				'ordersn' => $data['tid'],
				'title' => '给'.$user['nickname'].'打赏'.$fee.'元',
				'fee' => $data['fee'],
				'user' => $_W['member']['uid'],
		);
		
		$this->pay($params);
		exit();
	}
	$sql = "SELECT create_at FROM ".tablename('meepo_bbs_topic_replie')." WHERE uid = :uid ORDER BY create_at DESC limit 1";
	$params = array(':uid'=>$_W['member']['uid']);
	$lasttime = pdo_fetchcolumn($sql,$params);
	if(empty($forum['reply_time'])){
		$forum['reply_time'] = 0;
	}
	if(empty($lasttime)){
		$lasttime = time()-$forum['reply_time'];
	}
	if(time()-$lasttime < $forum['reply_time']){
		message($forum['reply_time'].'秒内不能重复发帖',referer(),error);
	}
	
	if(empty($_GPC['content']) && empty($_GPC['thumb'])){
		message('说点什么吧',referer(),error);
	}
	$data = array();
	$data['content'] = trim($_GPC['content']);
	$data['tid'] = intval($_GPC['tid']);
	$data['uniacid'] = $_W['uniacid'];
	$data['uid'] = $uid;
	$data['create_at'] = time();
	$data['fid'] = intval($_GPC['fid']);
	
	if(!empty($_GPC['thumb'])){
		foreach ($_GPC['thumb'] as $thumb){
			$th[] = save_media(tomedia($thumb));
		}
		$data['thumb'] = iserializer($th);
	}
	
	$openid = $_W['openid'];
	
	pdo_insert('meepo_bbs_topic_replie',$data);
	pdo_update('meepo_bbs_topics',array('last_reply_at'=>time()),array('id'=>$tid));
	
	if(!empty($_GPC['fid'])){
		message('',$this->createMobileUrl('forum_topic',array('id'=>$tid)),success);
	}
	$user = mc_fetch($_W['member']['uid'],array('nickname'));
	if(empty($user['nickname'])){
		$user['nickname'] = '游客';
	}
	
	$sql = "SELECT openid FROM ".tablename('mc_mapping_fans')." WHERE uid = :uid AND uniacid = :uniacid AND openid != :openid";
	$params = array(':uid'=>$topic['uid'],':uniacid'=>$_W['uniacid'],':openid'=>'fromUser');
	$send['touser'] = pdo_fetchcolumn($sql,$params);
	$send['msgtype'] = 'news';
	
	$articles = array();
	$article = array();
	$article['title'] = urlencode('【'.$topic['title'].'】有新的回复！');
	$article['url'] = $_W['siteroot'].'app/'.$this->createMobileUrl('forum_topic',array('id'=>$topic['id']));
	$articles[] = $article;
	
	$article = array();
	$article['title'] = urlencode($user['nickname'].'回复了您的帖子');
	$article['url'] = $_W['siteroot'].'app/'.$this->createMobileUrl('forum_topic',array('id'=>$topic['id']));
	$articles[] = $article;
	
	$send['news']['articles'] = $articles;
	$acid = $_W['acid'];
	
	$uniacccount = WeAccount::create($acid);
	print($forum);
	if($forum['issend']){
		$data = $uniacccount->sendCustomNotice($send);
	}
	
	
	
	$user = mc_fansinfo($_W['openid'],$_W['acid'],$_W['uniacid']);
	
	update_credit_reply($tid);
	insert_home_message($_W['member']['uid'],$topic['uid'],$tid,2,$user['nickname'].'回复了您的帖子'.$topic['title']);
}


$res = array();
$res['success'] = true;

message('',$this->createMobileUrl('forum_topic',array('id'=>$tid)),success);