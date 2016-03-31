<?php
/**
 * 微官网模块微站定义
 *
 * @author 史中营 qq:800083075
 * @url http://w.mamani.cn
 */
defined('IN_IA') or exit('Access Denied');
include_once IA_ROOT . '/addons/amouse_article/model.php';
define("AMOUSE_ARTICLE", "amouse_article");
define("RES", "../addons/".AMOUSE_ARTICLE."/style/");

function get_timelineauction($pubtime){
    $time=time();
    /** 如果不是同一年 */
    if(idate('Y', $time) != idate('Y', $pubtime)){
        return date('Y-m-d', $pubtime);
    }
    /** 以下操作同一年的日期 */
    $seconds=$time-$pubtime;
    $days=idate('z', $time)-idate('z', $pubtime);
    /** 如果是同一天 */
    if($days == 0){
        /** 如果是一小时内 */
        if($seconds < 3600){
            /** 如果是一分钟内 */
            if($seconds < 60){
                if(3 > $seconds){
                    return '刚刚';
                } else {
                    return $seconds.'秒前';
                }
            }
            return intval($seconds / 60).'分钟前';
        }
        return idate('H', $time)-idate('H', $pubtime).'小时前';
    }
    /** 如果是昨天 */
    if($days == 1){
        return '昨天 '.date('H:i', $pubtime);
    }
    /** 如果是前天 */
    if($days == 2){
        return '前天 '.date('H:i', $pubtime);
    }
    /** 如果是7天内 */
    if($days < 7){
        return $days.'天前';
    }
    /** 超过7天 */
    return date('n-j H:i', $pubtime);
}

class Amouse_articleModuleSite extends WeModuleSite {

    public $_appid='';
    public $_appsecret='';


    function __construct(){
        global $_W;
        $_weid=$_W['uniacid'];

        $set=  pdo_fetch("SELECT * FROM ".tablename('fineness_sysset')." WHERE weid=:weid limit 1", array(':weid' =>$_weid));
        if($set && $set['isget']==1){
            load()->model('account');
            $_W['account'] = account_fetch($_W['uniacid']);
            $this->_appid = trim($_W['account']['key']);
            $this->_appsecret =  trim($_W['account']['secret']);
            if ($_W['account']['level'] != 4) {
                //不是认证服务号
                if (!empty($set['appid']) && !empty($set['appsecret'])) {
                    $this->_appid = trim($set['appid']);
                    $this->_appsecret = trim($set['appsecret']);
                }
            }
        }

    }



    public function __app($f_name){
        global $_W, $_GPC;
        $weid=$_W['uniacid'];
        $set=  pdo_fetch("SELECT * FROM ".tablename('fineness_sysset')." WHERE weid=:weid limit 1", array(':weid' => $weid));
        include_once 'inc/app/'.strtolower(substr($f_name, 8)).'.inc.php';
    }

    public function doMobileIndex() {
        global $_GPC, $_W;
        $weid=$_W['uniacid'];
        $this->__app(__FUNCTION__);
    }

    public function doMobileSecond() {
        global $_GPC, $_W;
        $weid=$_W['uniacid'];
        $this->__app(__FUNCTION__);
    }

    //默认设置
    public function doMobileWap() {
        global $_GPC, $_W;
        $weid=$_W['uniacid'];
        $set=  pdo_fetch("SELECT * FROM ".tablename('fineness_sysset')." WHERE weid=:weid limit 1", array(':weid' => $weid));
        $cid = intval($_GPC['cid']);
        $advlist= pdo_fetchall('SELECT * FROM '.tablename('fineness_adv')." WHERE weid=$weid and pid =$cid ");
        //独立选择分类模板
        $title = $category['name'];
        $result = pdo_fetchall("SELECT * FROM ".tablename('fineness_article_category')." WHERE uniacid =$weid AND parentid = $cid ORDER BY displayorder ASC, id ASC ");
        if($cid>0){
            $sql="SELECT id FROM " . tablename('fineness_article_category') . " WHERE uniacid =$weid AND parentid = $cid ORDER BY createtime ASC limit 1";
            $defaultid = pdo_fetchcolumn($sql);
            if($defaultid){
                $list = pdo_fetchall("SELECT * FROM ".tablename('fineness_article')." WHERE weid={$weid} AND pcate={$cid} AND ccate=$defaultid ORDER BY displayorder ASC ") ;
            }else{
                $list = pdo_fetchall("SELECT * FROM ".tablename('fineness_article')." WHERE weid={$weid} AND pcate={$cid}  ORDER BY displayorder ASC ") ;
            }
        }else{
            $list = pdo_fetchall("SELECT * FROM ".tablename('fineness_article')." WHERE weid=:weid ORDER BY displayorder ASC ", array(':weid' => $weid)) ;
        }

        include $this->template('themes/list14');
    }


    //List
    public function doMobileList() {
        global $_GPC, $_W;
        $weid=$_W['uniacid'];
        $set=  pdo_fetch("SELECT * FROM ".tablename('fineness_sysset')." WHERE weid=:weid limit 1", array(':weid' => $weid));
        $cid = intval($_GPC['cid']);
        $category = pdo_fetch("SELECT * FROM " . tablename('fineness_article_category') . " WHERE id = '{$cid}'");

        $advlist= pdo_fetchall('SELECT * FROM '.tablename('fineness_adv')." WHERE weid=:weid and pid ='{$cid}' ", array(':weid' =>$weid));
        $result = pdo_fetchall("SELECT * FROM " . tablename('fineness_article_category') . " WHERE uniacid =$weid AND parentid = $cid ORDER BY displayorder ASC, id ASC ");
        //独立选择分类模板
        $title = $category['name'];
        $op=$_GPC['op'];
        if(!empty($category['thumb'])) {
            $shareimg = toimage($category['thumb']);
        }else{
            $shareimg=IA_ROOT.'/addons/amouse_article/icon.jpg';
        }

        $childid=$_GPC['childid'];
        $list = pdo_fetchall("SELECT * FROM ".tablename('fineness_article')." WHERE weid={$weid} AND pcate={$cid} AND ccate={$childid} ORDER BY displayorder ASC ") ;
        $url=$_W['siteroot']."app/".substr($this->createMobileUrl('Index',array('cid'=>$cid,'uniacid'=>$weid),true),2);

        include $this->template('themes/list14');
    }


    public function doMobileDetail() {
        $this->__app(__FUNCTION__);
        // include $this->template('detail');
    }

    public function doMobileJubao() {
        include $this->template('jubao');
    }

    //评论
    public function doMobileComment() {
        $this->__app(__FUNCTION__);
    }

    public  function doMobileLike(){
        global $_W, $_GPC;
        $weid=$_W['uniacid'];
        $record_id=$_GPC['articleid'];
        $record=pdo_fetch("SELECT * FROM ".tablename('fineness_article')." WHERE id= $record_id ");
        if(empty($record)){
            $res['ret']=501;
            return json_encode($res);
        }
        if(pdo_update('fineness_article',array('zanNum'=>$record['zanNum']+1), array('id'=>$record_id))){
            $res['ret']=0;
            return json_encode($res);
        }
    }

    //评价
    public  function doMobileAjaxcomment(){
        global $_W, $_GPC;
        $weid=$_W['uniacid'];
        $aid=$_GPC['articleid'];
        $set=  pdo_fetch("SELECT * FROM ".tablename('fineness_sysset')." WHERE weid=:weid limit 1", array(':weid' => $weid));
        $follow_url = $set['guanzhuUrl'];
        $is_follow = false;
        $record=pdo_fetch("SELECT * FROM ".tablename('fineness_article')." WHERE id= $aid ");
        if(empty($record)){
            $res['code']=501;
            $res['msg']="文章不存在或者已经被删除。";
            return json_encode($res);
        }
        if($set && $set['isget']==1){
            $userInfo = $this->getClientUserInfo("__article_Cookies_201520151012".$aid);// 从cookie中取
            if (empty($userInfo) && empty($userInfo['nickname'])) {//已关注过
                $res['code']=202;
                $res['msg']="您还没有关注，请关注后参与。";
                return json_encode($res);
            }
            $data = array(
                'weid' => $weid,
                'js_cmt_input' => $_GPC['js_cmt_input'],
                'status' =>0,
                'aid' => $aid,
                'author' => $userInfo['nickname'],
                'thumb' => $userInfo['headimgurl'],
                'openid' => $userInfo['openid'],
                'createtime' => time()
            );
        }else{
            $data = array(
                'weid' => $weid,
                'js_cmt_input' => $_GPC['js_cmt_input'],
                'status' =>0,
                'aid' => $aid,
                'author' => '匿名',
                'thumb' => '',
                'openid' => 'weixin_openid',
                'createtime' => time()
            );
        }
        pdo_insert('fineness_comment', $data);
        $res['code']=200;
        $res['msg']="评论成功，由公众帐号筛选后显示！";

        return json_encode($res);
    }
    //删除评价
    public  function doMobileDelComment(){
        global $_W, $_GPC;
        $commentid=$_GPC['commentid'];
        $record=pdo_fetch("SELECT * FROM ".tablename('fineness_comment')." WHERE id= $commentid ");
        if(empty($record)){
            $res['code']=501;
            $res['msg']="记录不存在或者已经被删除。";
            return json_encode($res);
        }
        $temp= pdo_delete("fineness_comment", array('id' => $commentid));
        $res['code']=200;
        $res['msg']='删除成功';
        return json_encode($res);
    }

    public  function doMobileAjaxpraise(){
        global $_W, $_GPC;
        $commentid=$_GPC['commentid'];
        $record=pdo_fetch("SELECT * FROM ".tablename('fineness_comment')." WHERE id= $commentid ");
        if(empty($record)){
            $res['code']=501;
            $res['msg']="记录不存在或者已经被删除。";
            return json_encode($res);
        }
        $temp= pdo_update("fineness_comment",array('praise_num'=>$record['praise_num']+1), array('id' => $commentid));
        $res['code']=200;
        return json_encode($res);
    }


    public function  getClientUserInfo($cookieKey){
        global $_GPC;
        $session = json_decode(base64_decode($_GPC[$cookieKey]), true);
        return $session;
    }

    public function setClientCookieUserInfo($userInfo = array(), $cookieKey) {
        if (!empty($userInfo) && !empty($userInfo['openid'])) {
            $cookie = array();
            foreach ($userInfo as $key => $value)
                $cookie[$key] = $value;
            $session = base64_encode(json_encode($cookie));
            isetcookie($cookieKey, $session, 1 * 3600 * 1);

        } else {
            message("获取用户信息错误");
        }
    }

    public function setClientUserInfo($openid,$aid) {
        global $_W;
        load()->func('communication');
        if (!empty($openid)) {
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->_appid."&secret=".$this->_appsecret;
            $content = ihttp_get($url);

            if(is_error($content)) {
                message('获取微信公众号授权失败, 请稍后重试！错误详情: ' . $content['message']);
            }
            $token = @json_decode($content['content'], true);
            if(empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['expires_in'])) {
                $errorinfo = substr($content['meta'], strpos($content['meta'], '{'));
                $errorinfo = @json_decode($errorinfo, true);
                message('获取微信公众号授权失败, 请稍后重试！ 公众平台返回原始数据为: 错误代码-' . $errorinfo['errcode'] . '，错误信息-' . $errorinfo['errmsg']);
            }
            $access_token= $token['access_token'];

            if (empty($access_token)) {
                message("获取accessToken失败");
            }

            $api_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $access_token . "&openid=" . $openid . "&lang=zh_CN";
            $info = ihttp_get($api_url);
            $userInfo = @json_decode($info['content'], true);
            var_dump($userInfo);
            if (!empty($userInfo)) {
                $subscribe = $userInfo['subscribe'];
                var_dump($subscribe);
                if($subscribe==1){//已经关注
                    $cookie['openid'] = $userInfo['openid'];
                    $cookie['nickname'] = $userInfo['nickname'];
                    $cookie['headimgurl'] = $userInfo['headimgurl'];
                }else{
                    $cookie['openid'] = $openid;
                }
                $session = base64_encode(json_encode($cookie));
                isetcookie('__articleuser'.$aid, $session, 24 * 3600 * 365);
            }
            return $userInfo;
        }
    }

    public function authorization_code($redirect_uri, $scope, $state){
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->_appid. "&redirect_uri=" . urlencode($redirect_uri) . "&response_type=code&scope=" . $scope . "&state=" . $state . "#wechat_redirect";
        //var_dump($url);
        header("location: $url");
    }

//授权认证
    public function  doMobileAuth(){
        global $_GPC,$_W;
        $code = $_GPC ['code'];
        $aid = $_GPC['id'];
        $tokenInfo = $this->getOauthAccessToken($code);
        $userInfo = $this->getOauthUserInfo($tokenInfo['openid'], $tokenInfo['access_token']);
        $this->setClientCookieUserInfo($userInfo,"__article_Cookies_201520151012".$aid);
        $params = array();
        $params['id'] = $aid;
        $params['openid'] = $userInfo['openid'];
        $redirect_uri =$_W['siteroot'] . 'app' . str_replace('./', '/', $this->createMobileUrl('detail',$params,true));
        header("location: $redirect_uri");
    }

    public function  getOauthAccessToken($code)
    {
        load()->func('communication');
        $oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $this->_appid . "&secret=" . $this->_appsecret . "&code=" . $code .
            "&grant_type=authorization_code";
        $content = ihttp_get($oauth2_code);
        $token = @json_decode($content['content'], true);
        if (empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
            echo '<h1>获取微信公众号授权' . $code . '失败[无法取得token以及openid], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';
            exit();
        }
        return $token;
    }

    public function getOauthUserInfo($openid, $accessToken){
        load()->func('communication');
        $tokenUrl = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $accessToken . "&openid=" . $openid . "&lang=zh_CN";
        $content = ihttp_get($tokenUrl);
        $userInfo = @json_decode($content['content'], true);
        return $userInfo;
    }


    public function  getUserInfo($access_token, $openid){
        load()->func('communication');

        return $userInfo;
    }

    //一键关注
    public function doMobileTuijian() {
        global $_GPC, $_W;
        $weid=$_W['uniacid'];
        $cfg = $this->module['config'];
        $list = pdo_fetchall("SELECT * FROM ".tablename('wx_tuijian')." WHERE weid=:weid ORDER BY createtime DESC ", array(':weid' => $weid)) ;
        include $this->template('tuijian');
    }

    //后台程序 inc/web文件夹下
    public function __web($f_name){
        global $_W, $_GPC;
        $weid=$_W['uniacid'];
        include_once 'inc/web/'.strtolower(substr($f_name, 5)).'.inc.php';
    }

    //分类关联
    public function doWebCategory() {
        $this->__web(__FUNCTION__);
    }

    //文章关联
    public function doWebPaper() {
        $this->__web(__FUNCTION__);
    }

    public function doWebComment() {
        $this->__web(__FUNCTION__);
    }

    //系统设置
    public function doWebSysset() {
        $this->__web(__FUNCTION__);
    }

    //一键关注设置
    public function doWebHutui() {
        $this->__web(__FUNCTION__);
    }

    //幻灯片管理
    public function doWebSlide(){
        $this->__web(__FUNCTION__);
    }


    //广告管理
    public function doWebAdv() {
        $this->__web(__FUNCTION__);
    }

    public function doWebjiaocheng() {
        include $this->template('help');
    }


}
