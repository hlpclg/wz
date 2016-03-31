<?php

/**
 * 拍大白
 * @author ewei qq:22185157
 */
defined('IN_IA') or exit('Access Denied');

class Ewei_takephotoaModuleSite extends WeModuleSite {
  
    public function doWebManage() {
        global $_GPC, $_W;

        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $sql = "uniacid = :uniacid AND `module` = :module";
        $params = array();
        $params[':uniacid'] = $_W['uniacid'];
        $params[':module'] = 'ewei_takephotoa';

        if (isset($_GPC['keywords'])) {
            $sql .= ' AND `name` LIKE :keywords';
            $params[':keywords'] = "%{$_GPC['keywords']}%";
        }
        load()->model('reply');
        $list = array();
        $list = reply_search($sql, $params, $pindex, $psize, $total);
        $pager = pagination($total, $pindex, $psize);

        if (!empty($list)) {
            foreach ($list as &$item) {
                $condition = "`rid`={$item['id']}";
                $item['keywords'] = reply_keywords_search($condition);
                $reply = pdo_fetch("SELECT rid,title, viewnum,starttime,endtime,status FROM " . tablename('ewei_takephotoa_reply') . " WHERE rid = :rid ", array(':rid' => $item['id']));
                $item['rid'] = $reply['rid'];
                $item['title'] = $reply['title'];
                $item['fansnum'] = pdo_fetchcolumn("select count(*) from " . tablename('ewei_takephotoa_fans') . " where rid=:rid ", array(":rid" => $item['id']));
                $item['viewnum'] = $reply['viewnum'];
                $item['starttime'] = date('Y-m-d H:i', $reply['starttime']);
                $endtime = $reply['endtime'];
                $item['endtime'] = date('Y-m-d H:i', $endtime);
                $nowtime = time();
                if ($reply['starttime'] > $nowtime) {
                    $item['statusstr'] = "<span class=\"label label-warning\">未开始</span>";
                } elseif ($endtime < $nowtime) {
                    $item['statusstr'] = "<span class=\"label label-default\">已结束</span>";
                } else {
                    if ($reply['status'] == 1) {
                        $item['statusstr'] = "<span class=\"label label-success\">已开始</span>";
                    } else {
                        $item['statusstr'] = "<span class=\"label \">已暂停</span>";
                    }
                }
                $item['status'] = $reply['status'];
            }
            unset($item);
        }
        include $this->template('manage');
    }
    public function doWebTpl() {
        global $_GPC, $_W;
        load()->func('tpl');
		
        include $this->template($_GPC['t']);
    }
    public function doWebSysset() {
        global $_W, $_GPC;
        $set = pdo_fetch("select * from ".tablename('ewei_takephotoa_sysset')." where uniacid=:uniacid limit  1",array(':uniacid'=>$_W['uniacid']));
        if (checksubmit('submit')) {

            $data = array(
                'uniacid' => $_W['uniacid'],
                'oauth2' => intval($_GPC['oauth2']),
                'appid' => $_GPC['appid'],
                'appsecret' => $_GPC['appsecret']
            );
            if (!empty($set)) {
                pdo_update('ewei_takephotoa_sysset', $data, array('id' => $set['id']));
            } else {
                pdo_insert('ewei_takephotoa_sysset', $data);
            }
            message('更新授权接口成功！', 'refresh');
        }
        include $this->template('sysset');
    }
    public function doWebDelete() {
   
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $rule = pdo_fetch("SELECT id, module FROM " . tablename('rule') . " WHERE id = :id and uniacid=:uniacid", array(':id' => $rid, ':uniacid' => $_W['uniacid']));
        if (empty($rule)) {
            message('抱歉，要修改的规则不存在或是已经被删除！');
        }
        if (pdo_delete('rule', array('id' => $rid))) {
            pdo_delete('rule_keyword', array('rid' => $rid));
            //删除统计相关数据
            pdo_delete('stat_rule', array('rid' => $rid));
            pdo_delete('stat_keyword', array('rid' => $rid));
            //调用模块中的删除
            $module = WeUtility::createModule($rule['module']);
            if (method_exists($module, 'ruleDeleted')) {
                $module->ruleDeleted($rid);
            }
        }


        message('规则操作成功！', $this->createWebUrl('manage',array('name'=>'ewei_takephotoa')), 'success');
    
    }
    public function doWebStatus() {
   
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
       $status = intval($_GPC['status']);
        pdo_update("ewei_takephotoa_reply",array("status"=>$status),array("rid"=>$rid));
        message('操作成功！', $this->createWebUrl('manage',array('name'=>'ewei_takephotoa')), 'success');
    }
    
    public function doWebfanslist() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        if (empty($rid)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $where = '';
        $params = array(':rid' => $rid);
    
        if (!empty($_GPC['keywords'])) {
            $where.=' and nickname like :nickname';
            $params[':nickname'] = "%{$_GPC['keywords']}%";
        }
        $total = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('ewei_takephotoa_fans') . " WHERE rid = :rid " . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("SELECT * FROM " . tablename('ewei_takephotoa_fans') . " WHERE rid = :rid " . $where . " ORDER BY score DESC " . $limit, $params);
        include $this->template('fanslist');
    }
    
    public function webmessage($error, $url = '', $errno = -1) {
        $data = array();
        $data['errno'] = $errno;
        if (!empty($url)) {
            $data['url'] = $url;
        }
        $data['error'] = $error;
        echo json_encode($data);
        exit;
    }
 
    
    public function doMobileIndex(){
        global $_W,$_GPC;
        $rid = intval($_GPC['rid']);
        $reply = pdo_fetch("select * from ".tablename('ewei_takephotoa_reply')." where rid=:rid limit 1",array(':rid'=>$rid));
        if(empty($reply)){
            message('活动未找到!');
        }
        if ($reply['status'] == 0) {
            message('活动暂停 ,请稍后再来哦!');
        }
        if ($reply['starttime'] > time()) {
            message('活动还未开始，还不能参加哦!');
        }
        if ($reply['endtime'] < time()) {
            message('活动已经结束，不能参加啦，请等待下次活动哦!');
        }
        //是否关注
        $followed = !empty($_W['openid']);
        if ($followed) {
            $mf = pdo_fetch("select follow from " . tablename('mc_mapping_fans') . " where openid=:openid limit 1", array(":openid" => $_W['openid']));
            $followed = $mf['follow'] == 1;
        }
        
        $set =  pdo_fetch("select * from " . tablename('ewei_takephotoa_sysset') . " where uniacid=:uniacid limit 1", array(":uniacid" => $_W['uniacid']));
        if (empty($set)) {
            $set['uniacid'] = $_W['uniacid'];
            pdo_insert("ewei_takephotoa_sysset", $set);
        }
        
        load()->model('account');
        $account = account_fetch($_W['uniacid']);
        $appId = $account['key'];
        $appSecret = $account['secret'];
             
        if($account['level']!=4){
              //不是认证服务号
              if (!empty($set['appid']) && !empty($set['appsecret'])) {
                 $appId = $set['appid'];
                 $appSecret = $set['appsecret'];
              }
              else{
                 message('请使用高级认证号，或借用高级认证号使用!');
              }
        }
       if (empty($appId) || empty($appSecret)) {
              message('请到管理后台设置完整的 AppID 和AppSecret !');
        }
     
      
                $cookieid = "__cookie_ewei_takephotoa_20150417001__{$rid}_{$_W['uniacid']}";
                $cookie = json_decode(base64_decode($_COOKIE[$cookieid]), true);
                $openid = is_array($cookie)?$cookie['openid']:"";
                $nickname= is_array($cookie)?$cookie['nickname']:"";
                $headimgurl = is_array($cookie)?$cookie['headimgurl']:"";
                $access_token="";
                $snsapi_type = "snsapi_userinfo";
                load()->func('communication');
                //获取openid
                if(empty($openid)){
                        $code = $_GPC['code'];
                        $access_token = "";
                        if (empty($code)) {
                            $url = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . "&c=entry&m=ewei_takephotoa&do=index&rid={$rid}";
                            $authurl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $appId . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope={$snsapi_type}&state=123#wechat_redirect";
                            header("location: " . $authurl);
                            exit();
                        } else {
                            $tokenurl = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appId . "&secret=" . $appSecret . "&code=" . $code . "&grant_type=authorization_code";
                            $resp = ihttp_get($tokenurl);
                            $token = @json_decode($resp['content'], true);
                            if (!empty($token) && is_array($token) && $token['errmsg'] == 'invalid code') {
                                $url = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . "&c=entry&m=ewei_takephotoa&do=index&rid={$rid}";
                                $authurl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $appId . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope={$snsapi_type}&state=123#wechat_redirect";
                                header("location: " . $authurl);
                                exit();
                            }
                            if (empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
                            } else {
                                $access_token = $token['access_token'];
                                $openid = $token['openid'];
                                 $f = array(
                                    "uniacid" => $_W['uniacid'],
                                    "openid" => $openid,
                                    "rid" => $rid,
                                    "appid" => $appId,
                                    "appsecret" => $appSecret
                                );
                                setcookie($cookieid, base64_encode(json_encode($f)), time() + 3600 * 24 * 365);
                            }
                        }
                    }

                //获取用户资料
                if(empty($nickname)){
                       $f = array(
                            "uniacid" => $_W['uniacid'],
                            "openid" => $openid,
                            "rid" => $rid,
                            "appid" => $appId,
                            "appsecret" => $appSecret
                        );
                        //如果未获取过用户信息，则获取粉丝信息
                        $infourl = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $access_token . "&openid=" . $openid . "&lang=zh_CN";
                        $resp = ihttp_get($infourl);
                        $fans_info = @json_decode($resp['content'], true);

                        if (isset($fans_info['nickname'])) {
                            $nickname = $f['nickname'] = $fans_info['nickname'];
                            $headimgurl = $f['headimgurl'] = $fans_info['headimgurl'];
                            setcookie($cookieid, base64_encode(json_encode($f)), time() + 3600 * 24 * 365);
                        }
               }
       
        //用户
        $fans = pdo_fetch("select * from ".tablename('ewei_takephotoa_fans')." where rid=:rid and openid=:openid limit 1",array(':rid'=>$rid,":openid"=>$openid));
        if(empty($fans)){
            $dd =array(
                "openid"=>$openid,
                "rid"=>intval($_GPC['rid']),
                "nickname"=>$nickname,
                "headimgurl"=>$headimgurl,
                "createtime"=>time(),
                "score"=>0,
            );
            pdo_insert("ewei_takephotoa_fans",$dd);
        }
        
        $reply['share_desc'] = str_replace("[SCORE]", "' + score + '", $reply['share_desc']);
        $reply['share_title'] = str_replace("[SCORE]", "' + score + '", $reply['share_title']);
        $items = iunserializer($reply['items']);
        //访问量
        pdo_update("ewei_takephotoa_reply",array("viewnum"=>$reply['viewnum']+1),array("rid"=>$rid));
        
        include $this->template('index');
    }
    
    //拍照
    public function doMobileTake(){
        global $_W,$_GPC;
        
        $data = $_GPC['data'];
        $rid = intval($_GPC['rid']);
        
        $cookieid = "__cookie_ewei_takephotoa_20150417001__{$rid}_{$_W['uniacid']}";
        $cookie = json_decode(base64_decode($_COOKIE[$cookieid]), true);
        $openid = is_array($cookie)?$cookie['openid']:"";
                
        $score = floatval($_GPC['score']);
        $body = substr(strstr($data,','),1);
        $imgdata= base64_decode($body );
        load()->func('file');
        $path = IA_ROOT."/addons/ewei_takephotoa/photos";
        @mkdirs($path);
        $f = $openid.".png";
        $filename = $path."/".$f;
        file_put_contents($filename,$imgdata);
        
        $fans = pdo_fetch("select * from ".tablename('ewei_takephotoa_fans')." where rid=:rid and openid=:openid limit 1",array(':rid'=>$rid,":openid"=>$openid));
        if(!empty($fans)){
            $mscore = $fans['score'];
            $img = $fans['img'];
            if($score>$mscore){
                $mscore = $score;
                $img = "../addons/ewei_takephotoa/photos/".$f;
            }
            pdo_update("ewei_takephotoa_fans",array("score"=>$mscore,'img'=>$img),array('rid'=>$rid,"openid"=>$openid));
        }
          
        die('');
    }
    public function doMobileRank(){
        global $_W,$_GPC;
         $rid = intval($_GPC['rid']);
         $pindex = max(1, intval($_GPC['page']));
         $psize = 15;
           $sql = "select id,nickname,headimgurl,score from " . tablename('ewei_takephotoa_fans') . " where rid={$rid} order by score desc limit 0,{$psize}";
                
         $list = pdo_fetchall($sql);
                
        include $this->template('rank');
    }
    public function doMobileRankMore() {
		global $_GPC, $_W;
                $rid = intval($_GPC['rid']);
		$pindex = max(1, intval($_GPC['page']));
		$psize = 15;
                  $sql = "select id,nickname,headimgurl,score from " . tablename('ewei_takephotoa_fans') . " where rid={$rid} order by score desc  LIMIT " . ($pindex - 1) * $psize . ',' . $psize."";
                 $list = pdo_fetchall($sql);
	include $this->template('rank_more');
    }
    public function doWebDownload(){
        require 'download.php';
    }
}
