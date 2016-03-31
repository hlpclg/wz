<?php
global $_GPC, $_W;
$id = intval($_GPC['id']);
$weid=$_W['uniacid'];
$acid=$_W['acid'];
load()->model('account');
$account = account_fetch($acid);
if($set && $set['isget']==1){
    $is_follow = false;
    $userInfo = $this->getClientUserInfo("__article_Cookies_201520151012".$id);// 从cookie中取
    if (empty($userInfo)) {//授权
        $redirect_uri =$_W['siteroot'] . 'app' . str_replace('./', '/', $this->createMobileUrl('auth',array('id'=>$id),true));
        $this->authorization_code($redirect_uri, "snsapi_userinfo", 1);//进行授权
    }
    if (!empty($userInfo) && !empty($userInfo['nickname'])) {//已关注过
        $is_follow = true;
    }
    $openid=$userInfo['openid'];
}else{
    $openid='weixin_openid';
}
$cList = pdo_fetchall("SELECT * FROM " . tablename('fineness_comment') . " WHERE `aid`=:aid and weid=:weid and status=1 ", array(':aid'=>$id,':weid' => $weid));
$detail = pdo_fetch("SELECT * FROM " . tablename('fineness_article') . " WHERE `id`=:id and weid=:weid", array(':id'=>$id,':weid' => $weid));

if (!empty($detail)) {
    pdo_update('fineness_article', array('clickNum' => $detail['clickNum'] + 1), array('id' => $detail['id']));
}
$shareimg = toimage($detail['thumb']);
$url=$_W['siteroot']."app/".substr($this->createMobileUrl('detail',array('id'=>$id,'uniacid'=>$weid),true),2);
if($detail['bg_music_switch']==1){
    if (strexists($detail['musicurl'], 'http://')||strexists($detail['musicurl'], 'https://')) {
        $detail['musicurl'] = $detail['musicurl'];
    } else {
        $detail['musicurl'] = $_W['attachurl'] . $detail['musicurl'];
    }
}
if (!empty($detail['outLink'])) {
    if(strtolower(substr($detail['outLink'], 0, 4)) != 'tel:' && !strexists($detail['outLink'], 'http://') && !strexists($detail['outLink'], 'https://')) {
        $detail['outLink'] = $_W['siteroot'] . 'app/' . $detail['outLink'];
    }
    header('Location: '. $detail['outLink']);
    exit;
}

$sql = "SELECT * FROM `ims_fineness_adv_er` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `ims_fineness_adv_er`)-(SELECT MIN(id) FROM `ims_fineness_adv_er`))+(SELECT MIN(id) FROM `ims_fineness_adv_er`)) AS id) AS t2 WHERE t1.id >= t2.id ORDER BY t1.id LIMIT 1";
$randAdv = pdo_fetch($sql);
if($randAdv){
    $randAdv['thumb'] = (strpos($randAdv['thumb'], 'http://') === FALSE) ? $_W['attachurl'] . $randAdv['thumb'] : $randAdv['thumb'];
}
$wechat=  pdo_fetch("SELECT * FROM ".tablename('account_wechats')." WHERE acid=:acid AND uniacid=:uniacid limit 1", array(':acid' => $weid,':uniacid' => $weid));

if(!empty($detail['template'])) {
    include $this->template($detail['templatefile']);
    exit;
}
include $this->template('themes/detail5');
exit;