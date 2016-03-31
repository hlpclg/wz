<?php /*折翼天使资源社区 www.zheyitianshi.com*/
function send_template($uid,$code,$other = array()){
	global $_W,$_GPC;
	load()->model('mc');
	if(is_numeric($uid)){
		$user = mc_fetch($uid);
		$sql = "SELECT openid FROM ".tablename('mc_mapping_fans')." WHERE uid = :uid AND uniacid = :uniacid ";
		$params = array(':uid'=>$uid,':uniacid'=>$_W['uniacid']);
		$openid = pdo_fetchcolumn($sql,$params);
	}else{
		$user = fans_search($uid);
		$openid = $uid;
	}
	if(empty($openid)){
		message('openid为空，发送消息失败!',referer(),error);
	}
	$sql = "SELECT openid,acid FROM ".tablename('mc_mapping_fans')." WHERE uniacid = :uniacid AND openid = :openid";
	$params = array(':uniacid'=>$_W['uniacid'],':openid'=>$openid);
	$fans = pdo_fetch($sql,$params);
	$send = array();
	
	if(empty($_W['acid'])){
		$_W['acid'] = $row['acid'];
	}
	if(empty($_W['acid'])){
		$_W['acid'] = $fans['acid'];
	}
	if(empty($_W['acid'])){
		message('请选择公众账号',referer(),error);
	}
	
	
	$send['touser'] = trim($openid);
	$send['acid'] = intval($_W['acid']);
	
	$sql = "SELECT * FROM ".tablename('meepo_bbs_msg_template')." WHERE uniacid = :uniacid AND type = :type ";
	$params = array(':uniacid'=>$_W['uniacid'],':type'=>$code);
	$template = pdo_fetch($sql,$params);
	
	$send['tpl_id'] = $template['tpl_id'];
	$tags = unserialize($template['tags']);
	$set = unserialize($template['set']);
	$data = array();
	foreach ($tags as $tag){
		$data[$tag] = array('value'=>$set['content'][$tag],'color'=>$set['color'][$tag]);
	}
	$replace = array();
	$replace[] = array('name'=>'ntime','replace'=>'#有效期#');
	$replace[] = array('name'=>'address','replace'=>'#地址#');
	$replace[] = array('name'=>'realname','replace'=>'#姓名#');
	$replace[] = array('name'=>'mobile','replace'=>'#手机#');
	$replace[] = array('name'=>'credit1','replace'=>'#积分#');
	$replace[] = array('name'=>'credit2','replace'=>'#余额#');
	$replace[] = array('name'=>'nickname','replace'=>'#昵称#');
	$replace[] = array('name'=>'password','replace'=>'#o2o密码#');
	$replace[] = array('name'=>'company','replace'=>'#公司#');
	$replace[] = array('name'=>'uid','replace'=>'#会员编号#');
	$replace[] = array('name'=>'time','replace'=>'#当前时间#');
	$replace[] = array('name'=>'clerkname','replace'=>'#核销员姓名#');
	$replace[] = array('name'=>'clerkcompany','replace'=>'#核销员公司#');
	$replace[] = array('name'=>'clerkmobile','replace'=>'#核销员电话#');
	$replace[] = array('name'=>'clerktitle','replace'=>'#核销卡券标题#');
	$replace[] = array('name'=>'clerksn','replace'=>'#核销卡券SN#');
	$replace[] = array('name'=>'clerkmoney','replace'=>'#核销卡券抵扣金额#');
	
	
	$database = array(
			'time'=>date('Y-m-d',time()),
			'ntime'=>date('Y-m-d',time()+60*60*24*12),
			'address'=>$user['address'],
			'realname'=>$user['realname'],
			'mobile'=>$user['mobile'],
			'nickname'=>$user['nickname'],
			'password'=>$other['password'],
			'uid'=>$user['uid'],
			'company'=>$user['company'],
			'clerkname' =>$other['clerkname'],
			'clerkcompany' =>$other['clerkcompany'],
			'clerkmobile' =>$other['clerkmobile'],
			'clerktitle' =>$other['clerktitle'],
			'clerksn'=>$other['clerksn'],
        'clerkmoney' => $other['clerkmoney']
	);
	
	$send['data'] = formot_content2($data,$database,$replace);
	$send['url'] = $set['url'];
	
	$return = send_template_message($send);
	
	return $return;
}

function getTopicById($id){
	$sql = "SELECT * FROM ".tablename('meepo_bbs_topics')." WHERE id = :id";
	$params = array(':id'=>$id);
	$topic = pdo_fetch($sql,$params);
	return $topic;
}
function activity_coupon_use1($uid, $couponid, $operator, $recid = '', $module = 'system') {
	global $_W;
	$coupon = pdo_fetch("SELECT * FROM " . tablename('activity_coupon') . " WHERE `type` = 1 AND `couponid` = :couponid LIMIT 1", array(':couponid' => $couponid));
	if (empty($coupon)) {
		return error(-1, '没有指定的折扣券信息');
	} elseif ($coupon['starttime'] > TIMESTAMP) {
		return error(-1, '折扣活动尚未开始');
	} elseif ($coupon['endtime'] < TIMESTAMP) {
		return error(-1, '折扣活动已经结束');
	}
	$params = array();
	$params[':couponid'] = $couponid;
	$params[':uid'] = $uid;
	$where = ' ORDER BY granttime ';
	if (!empty($recid)) {
		$where = ' AND `recid` = :recid';
		$params[':recid'] = $recid;
	}
	$precord = pdo_fetch("SELECT * FROM " . tablename('activity_coupon_record') . " WHERE `uid` = :uid AND `couponid` = :couponid AND `status` = 1 $where", $params);
	if (empty($precord)) {
		return error(-1, '没有可使用的折扣券');
	}
	if($precord['status'] == 2){
		return error(-1, '对不起，此折扣券已经被核销');
	}
	$update = array(
			'status' => 2,
			'usemodule' => $module,
			'usetime' => TIMESTAMP,
			'operator' => $operator
	);
	pdo_update('activity_coupon_record', $update, array('recid' => $precord['recid']));
	return true;
}
function activity_token_use1($uid, $couponid, $operator, $recid = '', $module = 'system') {
	global $_W;
	$coupon = pdo_fetch("SELECT * FROM " . tablename('activity_coupon') . " WHERE `type` = 2 AND `couponid` = :couponid LIMIT 1", array(':couponid' => $couponid));
	if (empty($coupon)) {
		return error(-1, '没有指定的代金券信息');
	} elseif ($coupon['starttime'] > TIMESTAMP) {
		return error(-1, '代金券活动尚未开始');
	} elseif ($coupon['endtime'] < TIMESTAMP) {
		return error(-1, '代金券活动已经结束');
	}
	$params = array();
	$params[':uid'] = $uid;
	$params[':couponid'] = $couponid;
	$where = 'ORDER BY granttime';
	if (!empty($recid)) {
		$where = ' AND `recid` = :recid';
		$params[':recid'] = $recid;
	}
	$precord = pdo_fetch("SELECT * FROM " . tablename('activity_coupon_record') . " WHERE `uid` = :uid AND `couponid` = :couponid $where ", $params);
	if (empty($precord)) {
		return error(-1, '没有可使用的代金券');
	}
	if($precord['status'] == 2){
		return error(-1, '对不起，此代金券已经被核销');
	}
	$update = array(
			'status' => 2,
			'usemodule' => $module,
			'usetime' => TIMESTAMP,
			'operator' => $operator
	);
	pdo_update('activity_coupon_record', $update, array('recid' => $precord['recid']));
	return true;
}

function activity_coupon_owned1($uid, $filter = array(), $pindex = 10, $psize = 0) {
	$condition = '';
	if (!empty($filter['used'])) {
		$condition .= ' AND r.`status` = ' . $filter['used'];
	}
	if (!empty($filter['couponid'])) {
		$condition .= ' AND r.`couponid` = ' . $filter['couponid'];
	}
	if (!empty($filter['grantmodule'])) {
		$condition .= " AND r.`grantmodule`= '{$filter['grantmodule']}' ";
	}
	if (!empty($filter['usemodule'])) {
		$condition .= " AND r.`usemodule`= '{$filter['usemodule']}' ";
	}
	$limit_sql = '';
	if ($psize > 0) {
		$limit_sql = ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
	}
	$total = pdo_fetchall("SELECT COUNT(*) AS cototal, r.couponid, r.status FROM " . tablename('activity_coupon_record') . " AS r LEFT JOIN " . tablename('activity_coupon') . " AS c ON r.couponid = c.couponid WHERE c.type = 1 AND r.uid = :uid " . $condition . ' GROUP BY r.couponid', array(':uid' => $uid));
	$data = pdo_fetchall("SELECT COUNT(*) AS cototal, r.couponid, r.status,r.recid FROM " . tablename('activity_coupon_record') . " AS r LEFT JOIN " . tablename('activity_coupon') . " AS c ON r.couponid = c.couponid WHERE c.type = 1 AND r.uid = :uid " . $condition . ' GROUP BY r.couponid ORDER BY r.couponid DESC' . $limit_sql, array(':uid' => $uid), 'couponid');
	if(!empty($data)) {
		$couponids = implode(', ', array_keys($data));
		$tokens = pdo_fetchall("SELECT couponid,thumb,couponsn,`condition`,title,discount,type,starttime,endtime FROM " . tablename('activity_coupon') . " WHERE couponid IN ({$couponids})", array(), 'couponid');
		foreach($tokens as &$token) {
			$token['status'] = $data[$token['couponid']]['status'];
			$token['cototal'] = $data[$token['couponid']]['cototal'];
			$token['thumb'] = tomedia($token['thumb']);
			$token['recid'] = $data[$token['couponid']]['recid'];
			$token['description'] = htmlspecialchars_decode($token['description']);
			
			$tokenss[$token['couponid']] = $token;
		}
	}
	unset($data);
	return array('total' => count($total), 'data' => $tokenss);
}

function activity_token_owned1($uid, $filter = array(), $pindex = 10, $psize = 0) {
	$condition = '';
	if (!empty($filter['used'])) {
		$condition .= ' AND r.`status` = ' . $filter['used'];
	}
	if (!empty($filter['couponid'])) {
		$condition .= ' AND r.`couponid` = ' . $filter['couponid'];
	}
	if (!empty($filter['grantmodule'])) {
		$condition .= " AND r.`grantmodule`= '{$filter['grantmodule']}' ";
	}
	if (!empty($filter['usemodule'])) {
		$condition .= " AND r.`usemodule`= '{$filter['usemodule']}' ";
	}
	$limit_sql = '';
	if ($psize > 0) {
		$limit_sql = ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
	}
	$total = pdo_fetchall("SELECT COUNT(*) AS cototal, r.couponid, r.status FROM " . tablename('activity_coupon_record') . " AS r LEFT JOIN " . tablename('activity_coupon') . " AS c ON r.couponid = c.couponid WHERE c.type = 2 AND r.uid = :uid " . $condition . ' GROUP BY r.couponid', array(':uid' => $uid));
	$data = pdo_fetchall("SELECT COUNT(*) AS cototal, r.couponid, r.status,r.recid FROM " . tablename('activity_coupon_record') . " AS r LEFT JOIN " . tablename('activity_coupon') . " AS c ON r.couponid = c.couponid WHERE c.type = 2 AND r.uid = :uid " . $condition . ' GROUP BY r.couponid ORDER BY r.couponid DESC' . $limit_sql, array(':uid' => $uid), 'couponid');
	if(!empty($data)) {
		$couponids = implode(', ', array_keys($data));
		$tokens = pdo_fetchall("SELECT couponid,thumb,couponsn,`condition`,title,discount,type,starttime,endtime FROM " . tablename('activity_coupon') . " WHERE couponid IN ({$couponids})", array(), 'couponid');
		foreach($tokens as &$token) {
			$token['status'] = $data[$token['couponid']]['status'];
			$token['cototal'] = $data[$token['couponid']]['cototal'];
			$token['thumb'] = tomedia($token['thumb']);
			$token['recid'] = $data[$token['couponid']]['recid'];
			$token['description'] = htmlspecialchars_decode($token['description']);
				
			$tokenss[$token['couponid']] = $token;
		}
	}
	unset($data);
	return array('total' => count($total), 'data' => $tokenss);
}
function replace_em($str){
	$str = preg_replace('/\[em_(.*?)\]/', '<img src="../addons/meepo_bbs/template/mobile/default/lib/qqface/arclist/$1.gif">', $str);
	return $str;
}
function send_uid($send = array(),$uid,$acid){
	global $_W;
	load()->classs('account');
	if(empty($acid)){
		message('请选择公众号',referer(),error);
	}
	$acc = WeAccount::create($acid);
	$data = $acc->sendCustomNotice($send);
	return $data;
}

function formot_content2($content = '',$data = array(),$replace = array()){
	global $_W;
	if(empty($content)){
		return $content;
	}
	if(is_array($content)){
		foreach ($content as $key => &$con) {
			$cont[$key] = formot_content2($con,$data,$replace);
		}
		
		return $cont;
	}else{
		
		foreach ($replace as $re){
			$content = str_replace($re['replace'], $data[$re['name']], $content);
		}
		
		return $content;
	}
}

function send_template_message($send = array(), $topcolor = '#FF683F'){
	global $_W;
	if(empty($send)){
		message('发送消息不能为空',referer(),error);
	}
	$touser = $send['touser'];
	$template_id = $send['tpl_id'];
	$postdata = $send['data'];
	load()->classs('account');
	$acid = $send['acid'];
	$url = $send['url'];
	if(empty($acid)){
		message('请选择公众号',referer(),error);
	}
	$acc = WeAccount::create($acid);
	
	$data = $acc->sendTplNotice($touser, $template_id, $postdata, $url, $topcolor);
	return $data;
}

function randompassword(){
	global $_W;
	$password = random(8);
	$sql = "SELECT id FROM ".tablename('activity_coupon_password')." WHERE password = :password AND uniacid = :uniacid ";
	$params = array(':password'=>$password,':uniacid'=>$_W['uniacid']);
	$isexit = pdo_fetchcolumn($sql,$params);
	if(!empty($isexit)){
		$password = randompassword();
	}

	return $password;
}

function getnavs(){
	global $_W;
	$table = 'meepo_bbs_navs';
	$sql = "SELECT * FROM ".tablename($table)." WHERE uniacid = :uniacid AND enabled = :enabled ORDER BY displayorder DESC";
	$params = array(':uniacid'=>$_W['uniacid'],':enabled'=>1);
	$list = pdo_fetchall($sql,$params);
	
	return $list;
}

if(!function_exists('db_create_in')){
	function db_create_in($item_list, $field_name = '') {
		if (empty($item_list)) {
			return $field_name . " IN ('') ";
		} else {
			if (!is_array($item_list)) {
				$item_list = explode(',', $item_list);
			}
			$item_list = array_unique($item_list);
			$item_list_tmp = '';
			foreach ($item_list AS $item) {
				if ($item !== '') {
					$item_list_tmp.= $item_list_tmp ? ",'$item'" : "'$item'";
				}
			}
			if (empty($item_list_tmp)) {
				return $field_name . " IN ('') ";
			} else {
				return $field_name . ' IN (' . $item_list_tmp . ') ';
			}
		}
	}
}


function update_credit_delete($tid){
	global $_W,$_GPC;
	$tid = $tid?$tid:$_GPC['tid'];
	$openid = $_W['openid'];

	$sql = "SELECT * FROM ".tablename('meepo_bbs_topics')." WHERE id = :id";
	$params = array(':id'=>$tid);
	$topic = pdo_fetch($sql,$params);

	$to_uid = $topic['uid'];
	$fid = $topic['fid'];

	$credit = getCredit($fid);

	if(!empty($to_uid)){
		mc_credit_update($to_uid, 'credit1',-$credit['delete'],array($to_uid,'帖子'.$topic['title'].'被删帖扣除积分'));
		insert_home_message($_W['member']['uid'],$to_uid,$tid,0,'帖子'.$topic['title'].'被删帖扣除积分');
	}
}
function update_credit_jing($tid){
	global $_W,$_GPC;
	$tid = $tid?$tid:$_GPC['tid'];
	$openid = $_W['openid'];

	$sql = "SELECT * FROM ".tablename('meepo_bbs_topics')." WHERE id = :id";
	$params = array(':id'=>$tid);
	$topic = pdo_fetch($sql,$params);

	$to_uid = $topic['uid'];
	$fid = $topic['fid'];

	$credit = getCredit($fid);

	if(!empty($to_uid)){
		mc_credit_update($to_uid, 'credit1',$credit['jing'],array($to_uid,'帖子'.$topic['title'].'被加精奖励积分'));
		insert_home_message($_W['member']['uid'],$to_uid,$tid,0,'帖子'.$topic['title'].'被加精奖励积分');
	}
}

function update_credit_top($tid){
	global $_W,$_GPC;
	$tid = $tid?$tid:$_GPC['tid'];
	$openid = $_W['openid'];

	$sql = "SELECT * FROM ".tablename('meepo_bbs_topics')." WHERE id = :id";
	$params = array(':id'=>$tid);
	$topic = pdo_fetch($sql,$params);

	$to_uid = $topic['uid'];
	$fid = $topic['fid'];

	$credit = getCredit($fid);

	if(!empty($to_uid)){
		mc_credit_update($to_uid, 'credit1',$credit['top'],array($to_uid,'帖子'.$topic['title'].'被置顶奖励积分'));
		insert_home_message($_W['member']['uid'],$to_uid,$tid,0,'帖子'.$topic['title'].'被置顶奖励积分');
	}
}

function update_credit_post($tid){
	global $_W,$_GPC;
	$tid = $tid?$tid:$_GPC['tid'];
	$openid = $_W['openid'];

	$sql = "SELECT * FROM ".tablename('meepo_bbs_topics')." WHERE id = :id";
	$params = array(':id'=>$tid);
	$topic = pdo_fetch($sql,$params);

	$to_uid = $topic['uid'];
	$fid = $topic['fid'];

	$credit = getCredit($fid);

	if(!empty($to_uid)){
		mc_credit_update($to_uid, 'credit1',$credit['post'],array($to_uid,'发布帖子'.$topic['title'].'奖励积分'));
		insert_home_message($_W['member']['uid'],$to_uid,$tid,0,'发布帖子'.$topic['title'].'奖励积分');
	}
}

function update_credit_read($tid){
	global $_W,$_GPC;
	$tid = $tid?$tid:$_GPC['tid'];
	$openid = $_W['openid'];

	$sql = "SELECT * FROM ".tablename('meepo_bbs_topics')." WHERE id = :id";
	$params = array(':id'=>$tid);
	$topic = pdo_fetch($sql,$params);

	$to_uid = $topic['uid'];
	$fid = $topic['fid'];

	$credit = getCredit($fid);

	if(!empty($to_uid)){
		mc_credit_update($to_uid, 'credit1',$credit['bread'],array($to_uid,'帖子'.$topic['title'].'被阅读奖励积分'));
		insert_home_message($_W['member']['uid'],$to_uid,$tid,0,'帖子'.$topic['title'].'被阅读励积分');
	}

	if(!empty($_W['member']['uid'])){
		mc_credit_update($_W['member']['uid'], 'credit1',$credit['read'],array($to_uid,'阅读帖子'.$topic['title'].'奖励积分'));
		insert_home_message($_W['member']['uid'],$_W['member']['uid'],$tid,0,'阅读帖子'.$topic['goods'].'奖励积分');
	}
}

function update_credit_share($tid){
	global $_W,$_GPC;
	$tid = $tid?$tid:$_GPC['tid'];
	$openid = $_W['openid'];

	$sql = "SELECT * FROM ".tablename('meepo_bbs_topics')." WHERE id = :id";
	$params = array(':id'=>$tid);
	$topic = pdo_fetch($sql,$params);

	$to_uid = $topic['uid'];
	$fid = $topic['fid'];

	$credit = getCredit($fid);

	if(!empty($to_uid)){
		mc_credit_update($to_uid, 'credit1',$credit['bshare'],array($to_uid,'帖子'.$topic['title'].'被分享奖励积分'));
		insert_home_message($_W['member']['uid'],$to_uid,$tid,0,'帖子'.$topic['title'].'被分享奖励积分');
	}

	if(!empty($_W['member']['uid'])){
		mc_credit_update($_W['member']['uid'], 'credit1',$credit['share'],array($to_uid,'分享帖子'.$topic['title'].'奖励积分'));
		insert_home_message($_W['member']['uid'],$_W['member']['uid'],$tid,0,'分享帖子'.$topic['goods'].'奖励积分');
	}
}

function update_credit_reply($tid){
	global $_W,$_GPC;
	$tid = $tid?$tid:$_GPC['tid'];
	$openid = $_W['openid'];
	
	$sql = "SELECT * FROM ".tablename('meepo_bbs_topics')." WHERE id = :id";
	$params = array(':id'=>$tid);
	$topic = pdo_fetch($sql,$params);
	
	$to_uid = $topic['uid'];
	$fid = $topic['fid'];
	
	$credit = getCredit($fid);
	
	if(!empty($to_uid)){
		mc_credit_update($to_uid, 'credit1',$credit['bgoods'],array($to_uid,'帖子'.$topic['title'].'被回复奖励积分'));
		insert_home_message($_W['member']['uid'],$to_uid,$tid,0,'帖子'.$topic['title'].'被回复奖励积分');
	}
	
	if(!empty($_W['member']['uid'])){
		mc_credit_update($_W['member']['uid'], 'credit1',$credit['like'],array($to_uid,'回复帖子'.$topic['title'].'奖励积分'));
		insert_home_message($_W['member']['uid'],$_W['member']['uid'],$tid,0,'回复帖子'.$topic['goods'].'奖励积分');
	}
	
}

function update_credit_like($tid){
	global $_W,$_GPC;
	$tid = $tid?$tid:$_GPC['tid'];
	$openid = $_W['openid'];
	
	$sql = "SELECT * FROM ".tablename('meepo_bbs_topics')." WHERE id = :id";
	$params = array(':id'=>$tid);
	$topic = pdo_fetch($sql,$params);
	
	$to_uid = $topic['uid'];
	$fid = $topic['fid'];
	
	$credit = getCredit($fid);
	
	if(!empty($to_uid)){
		mc_credit_update($to_uid, 'credit1',$credit['bgoods'],array($to_uid,'帖子'.$topic['title'].'被点赞奖励积分'));
		insert_home_message($_W['member']['uid'],$topic['uid'],$tid,0,'帖子'.$topic['title'].'被点赞奖励积分');
	}
	
	if(!empty($_W['member']['uid'])){
		mc_credit_update($_W['member']['uid'], 'credit1',$credit['like'],array($to_uid,'点赞帖子'.$topic['title'].'奖励积分'));
		insert_home_message($_W['member']['uid'],$_W['member']['uid'],$tid,0,'点赞帖子'.$topic['goods'].'奖励积分');
	}
}

function insert_home_message($fromopenid,$toopenid,$tid , $type , $log){
	global $_W,$_GPC;
	$insert = array();
	//点赞 类型为1
	$insert['toopenid'] = $toopenid;
	$insert['fromopenid'] = $fromopenid;
	$insert['type'] = $type;
	$insert['time'] = time();
	$insert['status'] = 0;
	$insert['tid'] = $tid;
	$insert['content'] = $log;
	pdo_insert('meepo_bbs_home_message',$insert);
	
	
}
function my_scandir($dir) {
	global $my_scenfiles;
	if ($handle = opendir($dir)) {
		while (($file = readdir($handle)) !== false) {
			if ($file != ".." && $file != ".") {
				if (is_dir($dir . "/" . $file)) {
					my_scandir($dir . "/" . $file);
				} else {
					$my_scenfiles[] = $dir . "/" . $file;
				}
			}
		}
		closedir($handle);
	}
}
function getAuthSet(){
	global $_W;
	$module = 'meepo_bbs';
	$set =pdo_fetch("SELECT * FROM " . tablename('meepo_module'). " WHERE `module` = '{$module}' limit 1");
	$sets =iunserializer($set['set']);
	if (is_array($sets)){
		return is_array($sets['auth'])? $sets['auth'] : array();
	}
	return array();
}
function getCredit($tid){
	global $_W;
	$sql = "SELECT * FROM ".tablename('meepo_bbs_share')." WHERE tid = :tid AND uniacid = :uniacid";
	$params = array(':tid'=>$tid,':uniacid'=>$_W['uniacid']);
	$set = pdo_fetch($sql,$params);
	$set = iunserializer($set['set']);
	if(empty($set)){
		$set = array();
		$set['post'] = '0';
		$set['reply'] = '0';
		$set['breply'] = '0';
		$set['goods'] = '0';
		$set['bgoods'] = '0';
		$set['share'] = '0';
		$set['bshare'] = '0';
		$set['profile'] = '0';
		$set['read'] = '0';
		$set['bread'] = '0';
		$set['top'] = '0';
		$set['jing'] = '0';
		$set['delete'] = '0';
	}
	return $set;
}


function getMyTopicsAll(){
	global $_W;
	$sql = "SELECT * FROM ".tablename('meepo_bbs_topics')." WHERE uniacid = :uniacid AND uid = :uid ORDER BY createtime DESC";
	$params = array(':uniacid'=>$_W['uniacid'],':uid'=>$_W['member']['uid']);
	$topics = pdo_fetchall($sql,$params);
	foreach ($topics as $topic){
		$topic['thumb'] = iunserializer($topic['thumb']);
		$topic['pic'] = $topic['thumb'][0];
		
		$sql = "SELECT name FROM ".tablename('meepo_bbs_threadclass')." WHERE typeid = :typeid";
		$params = array(':typeid'=>$topic['fid']);
		$topic['class'] = pdo_fetchcolumn($sql,$params);
		
		$sql = "SELECT COUNT(*) FROM ".tablename('meepo_bbs_topic_share')." WHERE tid = :tid";
		$params = array(':tid'=>$topic['id']);
		$sharenum = pdo_fetchcolumn($sql,$params);
		
		$sql = "SELECT COUNT(*) FROM ".tablename('meepo_bbs_topic_replie')." WHERE tid = :tid";
		$params = array(':tid'=>$topic['id']);
		$replynum = pdo_fetchcolumn($sql,$params);
		
		$sql = "SELECT COUNT(*) FROM ".tablename('meepo_bbs_topic_like')." WHERE tid = :tid";
		$params = array(':tid'=>$topic['id']);
		$likenum = pdo_fetchcolumn($sql,$params);
		
		$sql = "SELECT SUM(fee) FROM ".tablename('meepo_bbs_begging')." WHERE ttid = :ttid AND status = 1";
		$params = array(':ttid'=>$topic['id']);
		$begging_money = pdo_fetchcolumn($sql,$params);
		
		$topic['sharenum'] = $sharenum;
		$topic['replynum'] = $replynum;
		$topic['likenum'] = $likenum;
		$topic['begging_money'] = $begging_money;
		
		$list[] = $topic;
	}
	return $list;
}
function getMyTopics(){
	global $_W;
	$sql = "SELECT * FROM ".tablename('meepo_bbs_topics')." WHERE uniacid = :uniacid AND uid = :uid ORDER BY rand() DESC limit 0,5";
	$params = array(':uniacid'=>$_W['uniacid'],':uid'=>$_W['member']['uid']);
	$topics = pdo_fetchall($sql,$params);
	foreach ($topics as $topic){
		$topic['thumb'] = iunserializer($topic['thumb']);
		$topic['pic'] = $topic['thumb'][0];
		$list[] = $topic;
	}
	return $list;
}

function getTotalMyTopics(){
	global $_W;
	$sql = "SELECT COUNT(*) FROM ".tablename('meepo_bbs_topics')." WHERE uniacid = :uniacid AND uid = :uid";
	$params = array(':uniacid'=>$_W['uniacid'],':uid'=>$_W['member']['uid']);
	$topics = pdo_fetchcolumn($sql,$params);
	
	return $topics;
}


function check_look($id){
	global $_W;
	$manage = check_manager();
	if(!is_error($manage)){
		return $message;
	}
	load()->model('mc');
	if(empty($_W['openid'])){
		$user['groupid'] = -1;
	}
	$sql = "SELECT * FROM ".tablename('meepo_bbs_threadclass')." WHERE typeid = :id limit 1";
	$params = array(':id'=>$id);
	$group = pdo_fetch($sql,$params);
	$group = unserialize($group['look_group']);
	
	if(empty($_W['member']['uid'])){
		$user['groupid'] = -1;
	}else{
		$user = mc_fetch($_W['member']['uid'],array('groupid'));
	}
	
	if(in_array($user['groupid'], (array)$group)){
		return true;
	}else{
		return error('-3','您所在会员组没有访问权限');
	}
}

function check_post($id){
	global $_W;
	$manage = check_manager();
	if(!is_error($manage)){
		return $message;
	}
	load()->model('mc');
	if(empty($_W['openid'])){
		$user['groupid'] = -1;
	}
	$sql = "SELECT * FROM ".tablename('meepo_bbs_threadclass')." WHERE typeid = :id limit 1";
	$params = array(':id'=>$id);
	$group = pdo_fetch($sql,$params);
	$group = unserialize($group['post_group']);
	
	if(empty($_W['member']['uid'])){
		$user['groupid'] = -1;
	}else{
		$user = mc_fetch($_W['member']['uid'],array('groupid'));
	}
	if(in_array($user['groupid'], (array)$group)){
		return true;
	}else{
		return error('-3','您所在会员组没有发帖权限');
	}
}

function check_manager(){
	global $_W;
	load()->model('mc');
	if(empty($_W['openid'])){
		$user['groupid'] = -1;
	}
	$set = getSet();
	$group = $set['manager_group'];
	
	
	/* if(empty($_W['fans']['follow'])){
		return error('-2','没有关注');
	}
 */
	
	if(empty($_W['member']['uid'])){
		$user['groupid'] = -1;
	}else{
		$user = mc_fetch($_W['member']['uid'],array('groupid'));
	}
	
	if(in_array($user['groupid'], (array)$group)){
		return $user['groupid'];
	}else{
		return error('-3','您所在会员组没有发帖权限');
	}
}


function getSet(){
	global $_W;
	$sql = "SELECT * FROM ".tablename('meepo_bbs_set')." WHERE uniacid = :uniacid limit 1";
	$params = array(':uniacid'=>$_W['uniacid']);
	$row = pdo_fetch($sql,$params);
	$forum = unserialize($row['set']);
	return $forum;
}

function updateSet($set){
	global $_W;
	$data = serialize($set);
	pdo_update('meepo_bbs_set',array('set'=>$data),array('uniacid'=>$_W['uniacid']));
	return true;
}


function getAdv(){
	global $_W;
	$sql = "SELECT * FROM ".tablename('meepo_bbs_adv')." WHERE uniacid = :uniacid AND enabled = :enabled ORDER BY RAND() LIMIT 1";
	$params = array(':uniacid'=>$_W['uniacid'],':enabled'=>1);
	$advs = pdo_fetch($sql,$params);
	return $advs;
}


function getCat(){
	global $_W;
	$params = array(':uniacid'=>$_W['uniacid'],':fid'=>0);
	$sql = "SELECT * FROM ".tablename('meepo_bbs_threadclass')." WHERE uniacid = :uniacid AND fid = :fid ORDER BY displayorder DESC";
	$list = pdo_fetchall($sql,$params);
	foreach ($list as $li) {
		$li['icon'] = tomedia($li['icon']);
		$params = array(':uniacid'=>$_W['uniacid'],':fid'=>$li['typeid']);
		$sql = "SELECT * FROM ".tablename('meepo_bbs_threadclass')." WHERE uniacid = :uniacid AND fid = :fid ORDER BY displayorder DESC";
		$li['ch'] = pdo_fetchall($sql,$params);
		$cats[] = $li;
	}
	unset($list);
	return $cats;
}

function getTop(){
	global $_W;
	$sql = "SELECT * FROM ".tablename('meepo_bbs_topics')." WHERE uniacid = :uniacid AND tab = :tab ORDER BY rand() DESC limit 0,5";
	$params = array(':uniacid'=>$_W['uniacid'],':tab'=>'top');
	$topics = pdo_fetchall($sql,$params);
	
	return $topics;
}

function getJing($id){
	global $_W;

	$sql = "SELECT * FROM ".tablename('meepo_bbs_topics')." WHERE uniacid = :uniacid AND tab = :tab ORDER BY rand() DESC limit 0,3";
	$params = array(':uniacid'=>$_W['uniacid'],':tab'=>'jing');
	if(!empty($id)){
		$sql = "SELECT * FROM ".tablename('meepo_bbs_topics')." WHERE uniacid = :uniacid AND tab = :tab AND fid = :fid ORDER BY rand() DESC limit 0,3";
		$params = array(':uniacid'=>$_W['uniacid'],':tab'=>'jing',':fid'=>$id);
	}
	$topics = pdo_fetchall($sql,$params);
	foreach ($topics as $topic){
		$topic['thumb'] = iunserializer($topic['thumb']);
		$list[] = $topic;
	}
	return $list;
}

function getGroupTitle($groupid){
	global $_W;
	$sql = "SELECT title FROM ".tablename('mc_groups')." WHERE groupid = :groupid AND uniacid = :uniacid";
	$params = array(':groupid'=>$groupid,':uniacid'=>$_W['uniacid']);
	$title = pdo_fetchcolumn($sql,$params);
	return $title;
}