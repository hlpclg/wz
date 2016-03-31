<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W, $_GPC;
$act = empty($_GPC['act'])? 'list' : $_GPC['act'];
$table = "meepo_bbs_msg_template";
$tabledata = "meepo_bbs_msg_template_data";
if ($_W['uniacid'] == "") {
    die("acount id error");
}
$tid = max(0, intval($_GPC['tid']));

$tabs = array(
		array('code'=>'web_o2o_user','title'=>'o2o审核通过提醒'),
		array('code'=>'web_o2o_user_false','title'=>'o2o审核失败提醒'),
		array('code'=>'mobile_use_token','title'=>'代金券核销成功'),
		array('code'=>'mobile_use_token_false','title'=>'代金券核销失败'),
		array('code'=>'mobile_use_coupon','title'=>'折扣券核销成功'),
		array('code'=>'mobile_use_coupon_false','title'=>'折扣券核销失败'),
);
$values = array('web_o2o_user'=>'o2o审核通过提醒','web_o2o_user_false'=>'o2o审核失败提醒','mobile_use_token_false'=>'代金券核销失败','mobile_use_token'=>'代金券核销成功','mobile_use_coupon'=>'折扣券核销成功','mobile_use_coupon_false'=>'折扣券核销失败');
if ($act == 'edit') {
    if (checksubmit('submit')) {
    	
        $saveData = array(
            'uniacid' => $_W['uniacid'],
            'title' => trim($_GPC['title']),
            'tpl_id' => trim($_GPC['tpl_id']),
            'template' => trim(htmlspecialchars_decode($_GPC['template'])),
        	'type'=>trim($_GPC['type']),
        );
        $tags = getTplTags($saveData['template']);
        $saveData['tags'] = serialize($tags);
        if (empty($tags)) {
            message("模板信息填写错误，请重新从公众平处复制", "", 'error');
        }
        if ($tid > 0) {
            pdo_update($table, $saveData, array('id' => $tid));
        } else {
        	$sql = "SELECT * FROM " . tablename($table) . " WHERE uniacid=:uniacid AND type = :type";
        	$params = array(':uniacid' => $_W['uniacid'],':type'=>$_GPC['type']);
        	$templateInfo = pdo_fetch($sql,$params);
        	if (!empty($templateInfo)) {
        		message("已存在次魔板消息", "", 'error');
        	}
            pdo_insert($table, $saveData);
        }
        message("模板信息设置成功!", $this->createWebUrl('index', array('doo'=>'template','op'=>'template','act' => 'list')), 'success');
    }
    if ($tid > 0) {
        $templateInfo = pdo_fetch("select * from " . tablename($table) . " where uniacid=:uniacid and id=:tid", array(':uniacid' => $_W['uniacid'], ":tid" => $tid));
    }
}
if ($act == 'list') {
    $pageindex = max(intval($_GPC['page']), 1); // 当前页码
    $pagesize = 10; // 设置分页大小
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($table) . " where uniacid=:uniacid", array(':uniacid' => $_W['uniacid']));
    $templatess = pdo_fetchall("select * from " . tablename($table) . " where uniacid=:uniacid order by id desc LIMIT " . ($pageindex - 1) * $pagesize . ",$pagesize", array(':uniacid' => $_W['uniacid']));
    foreach ($templatess as $t){
    	$t['type'] = $values[$t['type']];
    	
    	$templates[] = $t;
    }
    $pager = pagination($total, $pageindex, $pagesize);
}
if ($act == 'delete') {
    $tid = intval($_GPC['tid']);
    if (empty($tid)) {
        message('抱歉，消息模板不存在或是已经被删除！', $this->createWebUrl('index', array('doo'=>'template','op'=>'template','act' => 'list')), 'error');
    }
    pdo_delete($table, array('id' => $tid));
    message('消息模板删除成功！', $this->createWebUrl('index', array('doo'=>'template','op'=>'template','act' => 'list')), 'success');
}

if($act == 'set'){
	load()->func('tpl');
	
	$tid = intval($_GPC['tid']);
    if (empty($tid)) {
        message('抱歉，消息模板不存在或是已经被删除！', $this->createWebUrl('index', array('doo'=>'template','op'=>'template','act' => 'list')), 'error');
    }
    
    $templateInfo = pdo_fetch("select * from " . tablename($table) . " where uniacid=:uniacid and id=:tid", array(':uniacid' => $_W['uniacid'], ":tid" => $tid));
    $tags = unserialize($templateInfo['tags']);
    $set = unserialize($templateInfo['set']);
    $help = '#会员编号#,#地址#,#姓名#,#手机#,#当前时间#,#积分#,#余额#,#昵称#
    	<br>#o2o密码#,#帖子标题#,#评论内容#,#评论者昵称#,#公司#,#有效期#
    	<br>#核销员姓名#,#核销员公司#,#核销员电话#,#核销卡券标题#,#核销卡券SN#,#核销卡券抵扣金额#';
    
    if($_W['ispost']){
   		$data = array();
   		$set['color'] = $_GPC['color'];
   		$set['content'] = $_GPC['content'];
   		$set['url'] = $_GPC['url'];
   		$data['set'] = serialize($set);
   		
   		pdo_update($table,$data,array('id'=>$tid));
   		message('保存成功！', $this->createWebUrl('index', array('doo'=>'template','op'=>'template','act' => 'list')), 'success');
    }
}

function getTplTags($tpl) {
	$returnArray = array();
	$pattern = '/\w+(?=\.DATA)/i';
	preg_match_all($pattern, urldecode($tpl), $returnArray);
	if (empty($returnArray[0])) {
		return false;
	} else {
		return $returnArray[0];
	}
}