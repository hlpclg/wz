<?php
/**
 * 入口文件
 * 
 * @version     $Id: zqgame.inc.php 1 9:01 2015年09月16日Z lions $
 * @copyright   Copyright (c) 2013 - 2020, haobama.net, Inc.
 * @link        http://www.haobama.net
 */

global $_W,$_GPC;
$setting = pdo_fetchcolumn("SELECT `setting_value` FROM " . tablename('lions_zq_settings') . " WHERE `uniacid`=:uniacid AND `setting_key`='require_subscribe'",array(':uniacid'=>$_W['uniacid']));
$judge['require'] = false;
if($setting && $setting == 1){
    $judge['require'] = true;
    $judge['subscribe'] = requreSubscribe();
    $judge['thumb'] = pdo_fetchcolumn("SELECT `setting_value` FROM " . tablename('lions_zq_settings') . " WHERE `uniacid`=:uniacid AND `setting_key`='thumb'",array(':uniacid'=>$_W['uniacid']));
}

function requreSubscribe(){
    global $_W;
    $hasSubscribe = 1;
    if(isset($_W['fans']['from_user']) && !empty($_W['fans']['from_user'])){
        $openid = $_W['fans']['from_user'];
        $userinfo = mc_fansinfo($openid);
        if(!$userinfo || ($userinfo['follow']==0)){
            $hasSubscribe = 0;
        }
    }else{
        $oauthAccount = $_W['oauth_account'];
        if(empty($oauthAccount)){
            message('未指定网页授权公众号, 无法获取用户信息.','','error');
        }
        $userinfo = mc_oauth_userinfo();
        $level = pdo_fetchcolumn("SELECT `level` FROM ".tablename('account_wechats')." WHERE `uniacid`=:uniacid",array(':uniacid'=>$_W['uniacid']));

        if($level == 4){
            if($userinfo['subscribe'] == 0){
                $hasSubscribe = 0;
            }
        }else{
            $unionid = isset($userinfo['unionid']) ? $userinfo['unionid'] : '';

            if(empty($unionid)){
                message('获取unionid失败,请确认公众号已接入微信开放平台','','error');
            }
            $fieldsExist = pdo_fieldexists('mc_mapping_fans','unionid');
            if(!$fieldsExist){
                pdo_query("ALTER TABLE ".tablename('mc_mapping_fans')." ADD column unionid varchar(255) default null");
            }
            $openid = pdo_fetchcolumn("SELECT `openid` FROM ".tablename('mc_mapping_fans'). " WHERE `unionid`=:unionid AND `uniacid`=:uniacid ", array(':unionid'=>$unionid, ':uniacid'=>$_W['account']['uniacid']));
            if(empty($openid)){
                $hasSubscribe = 0;
            }else{
                $userinfo = mc_fansinfo($openid);
                if(!$userinfo || ($userinfo['follow']==0)){
                    $hasSubscribe = 0;
                }
            }

        }
    }


    return array('openid'=>$openid,'subscribe'=>$hasSubscribe);
}

include $this->template('index');

