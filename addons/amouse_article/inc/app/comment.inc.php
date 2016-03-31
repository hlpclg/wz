<?php
global $_GPC, $_W;
$artid = intval($_GPC['artid']);

if(empty($artid)){
    $artid = intval($_GPC['id']);
}

$weid=$_W['uniacid'];
$detail = pdo_fetch("SELECT * FROM " . tablename('fineness_article') . " WHERE `id`=:id and weid=:weid", array(':id'=>$artid,':weid' => $weid));
$set=  pdo_fetch("SELECT * FROM ".tablename('fineness_sysset')." WHERE weid=:weid limit 1", array(':weid' => $weid));
$follow_url = $set['guanzhuUrl'];

$is_follow = false;
if($set && $set['isget']==1){
    $userInfo = $this->getClientUserInfo("__article_Cookies_201520151012".$artid);// 从cookie中取
    /**$userInfo['openid']="oSWoes0T1YM4Uq83FZOdKv9q8ud0";
    $userInfo['nickname']="AA-SHIZHONGYING(w.mamani.cn)";$userInfo['headimgurl']="http://wx.qlogo.cn/mmopen/qGusKyb0IEdXvIJKqkQ1H7DwHVJORbtvZbXkOyFoWkpQppC3eIT5FAPe4kXJCjVdEQsJZDCWUWseuhkicXHPshQ/0";
     **/
    if (empty($userInfo)) {//授权
        $redirect_uri =$_W['siteroot'] . 'app' . str_replace('./', '/', $this->createMobileUrl('auth',array('aid'=>$artid),true));
        $this->authorization_code($redirect_uri, "snsapi_userinfo", 1);//进行授权
    }

    if (!empty($userInfo) && !empty($userInfo['nickname'])) {//已关注过
        $is_follow = true;
        $openid=$userInfo['openid'];
    }
}else{
    $openid='weixin_openid';
}
$mycomments=pdo_fetchall("SELECT * FROM ".tablename('fineness_comment')." WHERE `aid`=:aid and weid=:weid AND openid=:openid order by createtime desc ", array(':aid'=>$artid, ':weid'
=>$weid,':openid'=>$openid));

include $this->template('comment');