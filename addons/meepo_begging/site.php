<?php
defined('IN_IA') or exit('Access Denied');
define('ROOT_PATH', str_replace('site.php', '', str_replace('\\', '/', __FILE__)));
define('INC_PATH', ROOT_PATH . 'inc/');
define('TEMPLATE_PATH', '../../addons/meepo_begging/template/mobile/release/');
load()->model('mc');
class Meepo_beggingModuleSite extends WeModuleSite
{
    protected function sendHong($record, $user)
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $api     = $this->module['config'];
        if (empty($api)) {
            return error(-2, '系统还未开放');
        }
        $fee                   = floatval($record['fee']) * 100;
        $url                   = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
        $pars                  = array();
        $pars['nonce_str']     = random(32);
        $pars['mch_billno']    = $api['mchid'] . date('Ymd') . sprintf('%d', time());
        $pars['mch_id']        = $api['mchid'];
        $pars['wxappid']       = $api['appid'];
        $pars['nick_name']     = $_W['account']['name'];
        $pars['send_name']     = $_W['account']['name'];
        $pars['re_openid']     = $record['openid'];
        $pars['total_amount']  = $fee;
        $pars['min_value']     = $pars['total_amount'];
        $pars['max_value']     = $pars['total_amount'];
        $pars['total_num']     = 1;
        $pars['wishing']       = '感谢您' . $_W['account']['name'] . '东家，你的体现金额已发放，注意查收！' . $record['fee'] . '元';
        $pars['client_ip']     = $api['ip'];
        $pars['act_name']      = '吴迪生物东家佣金发放';
        $pars['remark']        = '尊敬的东家：' . $user['nickname'] . '您的佣金已通过红包发放，请注意查收';
        $pars['logo_imgurl']   = tomedia($api['logo']);
        $pars['share_content'] = '哇,发财了！我在' . $_W['account']['name'] . '赚了' . $record['fee'] . '元佣金，已经到账啦！赶紧来玩吧！';
        $pars['share_imgurl']  = tomedia($api['logo']);
        $pars['share_url']     = 'www.baidu.com';
        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach ($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key={$api['password']}";
        $pars['sign']              = strtoupper(md5($string1));
        $xml                       = array2xml($pars);
        $extras                    = array();
        $extras['CURLOPT_CAINFO']  = ROOT_PATH . '/cert/rootca.pem.' . $uniacid;
        $extras['CURLOPT_SSLCERT'] = ROOT_PATH . '/cert/apiclient_cert.pem.' . $uniacid;
        $extras['CURLOPT_SSLKEY']  = ROOT_PATH . '/cert/apiclient_key.pem.' . $uniacid;
        load()->func('communication');
        $procResult = null;
        $resp       = ihttp_request($url, $xml, $extras);
        if (is_error($resp)) {
            $procResult = $resp;
        } else {
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
            $dom = new DOMDocument();
            if ($dom->loadXML($xml)) {
                $xpath = new DOMXPath($dom);
                $code  = $xpath->evaluate('string(//xml/return_code)');
                $ret   = $xpath->evaluate('string(//xml/result_code)');
                if (strtolower($code) == 'success' && strtolower($ret) == 'success') {
                    $procResult = true;
                } else {
                    $error      = $xpath->evaluate('string(//xml/err_code_des)');
                    $procResult = error(-2, $error);
                }
            } else {
                $procResult = error(-1, 'error response');
            }
        }
        if (is_error($procResult)) {
            print_r($procResult);
            return false;
        } else {
            return true;
        }
    }
    public function __init()
    {
        global $_W;
        if (!pdo_tableexists('meepo_begging')) {
            $sql = "CREATE TABLE `ims_meepo_begging` (
					`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
					`uniacid` int(11) UNSIGNED NOT NULL DEFAULT 0,
					`uid` int(11) UNSIGNED NOT NULL DEFAULT 0,
					`money` float UNSIGNED NOT NULL DEFAULT 0.00,
					`createtime` int(11) UNSIGNED NOT NULL DEFAULT 0,
					PRIMARY KEY (`id`)
				) ENGINE=MyISAM
				CHECKSUM=0
				DELAY_KEY_WRITE=0;";
            pdo_query($sql);
        }
        if (!pdo_tableexists('meepo_begging_user')) {
            $sql = "CREATE TABLE `ims_meepo_begging_user` (
					`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
					`uid` int(11) UNSIGNED NOT NULL DEFAULT 0,
					`uniacid` int(11) UNSIGNED NOT NULL DEFAULT 0,
					`fopenid` varchar(40) NULL,
					`money` float NOT NULL DEFAULT 0.00,
					`message` text NULL,
					`createtime` int(11) UNSIGNED NOT NULL DEFAULT 0,
					`avatar` varchar(132) NULL,
					`nickname` varchar(32) NULL,
					`status` tinyint(2) NOT NULL DEFAULT 0,
					PRIMARY KEY (`id`)
				) ENGINE=MyISAM
				CHECKSUM=0
				DELAY_KEY_WRITE=0;";
            pdo_query($sql);
        }
        if (!pdo_tableexists('meepo_begging_set')) {
            $sql = "CREATE TABLE `ims_meepo_begging_set` (
					`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
					`uniacid` int(11) UNSIGNED NOT NULL DEFAULT 0,
					`set` text NULL,
					`createtime` int(11) UNSIGNED NOT NULL DEFAULT 0,
					PRIMARY KEY (`id`)
				) ENGINE=MyISAM
				CHECKSUM=0
				DELAY_KEY_WRITE=0;";
            pdo_query($sql);
        }
        if (!pdo_tableexists('meepo_begging_log')) {
            $sql = "CREATE TABLE `ims_meepo_begging_log` (
					`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
					`uid` int(11) UNSIGNED NOT NULL DEFAULT 0,
					`uniacid` int(11) UNSIGNED NOT NULL DEFAULT 0,
					`openid` varchar(40) NULL,
					`apply` varchar(40) NULL,
					`type` tinyint(2) NOT NULL DEFAULT 0,
					`createtime` int(11) UNSIGNED NOT NULL DEFAULT 0,
					`status` tinyint(2) NOT NULL DEFAULT 0,
					PRIMARY KEY (`id`)
				) ENGINE=MyISAM
				CHECKSUM=0
				DELAY_KEY_WRITE=0;";
            pdo_query($sql);
        }
        if (!pdo_fieldexists('meepo_begging', 'cash')) {
            pdo_query('ALTER TABLE ' . tablename('meepo_begging') . " ADD COLUMN cash float(5) DEFAULT '0'");
        }
        if (!pdo_fieldexists('meepo_begging_log', 'money')) {
            pdo_query('ALTER TABLE ' . tablename('meepo_begging_log') . " ADD COLUMN money float(5) DEFAULT '0'");
        }
    }
    public function doMobilecheckFollow()
    {
        global $_W;
        if ($_W['fans']['follow']) {
            $uid = $this->checkauth();
            if (empty($uid)) {
                checkauth();
            } else {
                $url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('index', array(
                    'uid' => $uid
                ));
                header("Location:$url");
                exit();
            }
        } else {
            $sql          = 'SELECT `subscribeurl` FROM ' . tablename('account_wechats') . " WHERE `acid` = :acid";
            $subscribeurl = pdo_fetchcolumn($sql, array(
                ':acid' => intval($_W['acid'])
            ));
            message('正在跳转关注页面，说明：参加活动必须关注' . $_W['account']['name'] . ',然后再打开当前链接，进行活动参加！', $subscribeurl, success);
            exit();
        }
    }
    public function doMobilePay()
    {
        global $_W, $_GPC;
        $uid               = $this->checkauth();
        $begid             = $_GPC['begid'];
        $sql               = "SELECT * FROM " . tablename('meepo_begging_user') . " WHERE id = :id limit 1";
        $params            = array(
            ':id' => $begid
        );
        $begging           = pdo_fetch($sql, $params);
        $parmas            = array();
        $params['tid']     = $begid;
        $params['user']    = $_W['openid'];
        $params['fee']     = floatval($begging['money']);
        $params['title']   = '一分也是爱，不要嫌少';
        $params['ordersn'] = 'MEEPO_BEGGING_' . random(5, 1);
        $params['virtual'] = true;
        $this->pay($params);
    }
    public function sendMessage($send = array())
    {
        global $_W;
        load()->classs('account');
        $from_user = $send['touser'];
        $acid      = $_W['acid'];
        if (empty($acid)) {
            $acid = pdo_fetchcolumn("SELECT acid FROM " . tablename('mc_mapping_fans') . " WHERE openid = '{$from_user}'");
        }
        $acc  = WeAccount::create($acid);
        $data = $acc->sendCustomNotice($send);
    }
    public function payResult($params)
    {
        global $_W;
        $mid  = $this->checkauth();
        $fee  = floatval($params['fee']);
        $data = array(
            'status' => $params['result'] == 'success' ? 1 : 0
        );
        pdo_update('meepo_begging_user', $data, array(
            'id' => $params['tid']
        ));
        if ($params['from'] == 'return') {
            $sql     = "SELECT * FROM " . tablename('meepo_begging_user') . " WHERE id = :id limit 1";
            $parms   = array(
                ':id' => $params['tid']
            );
            $order   = pdo_fetch($sql, $parms);
            $uid     = $order['uid'];
            $sql     = "SELECT * FROM " . tablename('meepo_begging') . " WHERE uid = :uid limit 1";
            $parms   = array(
                ':uid' => $uid
            );
            $begging = pdo_fetch($sql, $parms);
            $money   = floatval($begging['money']) + floatval($fee);
            pdo_update('meepo_begging', array(
                'money' => $money
            ), array(
                'uid' => $uid,
                'uniacid' => $_W['uniacid']
            ));
            if (intval($_W['account']['level']) == 4) {
                $sql             = "SELECT openid FROM " . tablename('mc_mapping_fans') . " WHERE uniacid = :uniacid AND uid = :uid";
                $params          = array(
                    ':uniacid' => $_W['uniacid'],
                    ':uid' => $begging['uid']
                );
                $touser          = pdo_fetchcolumn($sql, $params);
                $send['touser']  = trim($touser);
                $send['msgtype'] = 'news';
                $sql             = "SELECT * FROM " . tablename('meepo_begging_user') . " WHERE uid = :uid ORDER BY createtime DESC limit 8";
                $params          = array(
                    ':uid' => $begging['uid']
                );
                $list            = pdo_fetchall($sql, $params);
                foreach ($list as $li) {
                    $row                = array();
                    $fuser              = fans_search($li['fopenid']);
                    $row['title']       = urlencode(date('Y-m-d', $li['createtime']) . '收到一笔打赏' . $li['money']);
                    $row['description'] = urlencode("收到一笔来自" . $fuser['nickname'] . "的打赏，打赏金额为" . $params['fee'] . "元");
                    $row['url']         = $_W['siteroot'] . '/app/' . $this->createMobileUrl('index', array(
                        'uid' => $begging['uid']
                    ));
                    !empty($fuser['thumb']) && $row['picurl'] = tomedia($fuser['nickname']);
                    $news[] = $row;
                }
                $send['news']['articles'] = $news;
                $this->sendMessage($send);
            }
        }
        $setting = uni_setting($_W['uniacid'], array(
            'creditbehaviors'
        ));
        $credit  = $setting['creditbehaviors']['currency'];
        if ($params['type'] == $credit) {
            message('您已经向好友支付成功，正在生成自己的链接，生成完成后，请收藏网页!', $this->createMobileUrl('index', array(
                'uid' => $_W['member']['uid']
            )) . '#/event/home/' . $mid, 'success');
        } else {
            message('您已经向好友支付成功，正在生成自己的链接，生成完成后，请收藏网页!！', '../../app/' . $this->createMobileUrl('index', array(
                'uid' => $_W['member']['uid']
            )) . '#/event/home' . $mid, 'success');
        }
    }
    public function checkauth()
    {
        global $_W;
        load()->model('mc');
        if (empty($_W['member']['uid'])) {
            if (!empty($_W['openid'])) {
                $fan = mc_fansinfo($_W['openid'], $_W['acid'], $_W['uniacid']);
                _mc_login(array(
                    'uid' => intval($fan['uid'])
                ));
            }
        }
        if (empty($_W['member']['uid'])) {
            $sql    = "SELECT * FROM " . tablename('mc_mapping_fans') . " WHERE uniacid = :uniacid AND openid = :openid";
            $params = array(
                ':uniacid' => $_W['uniacid'],
                ':openid' => $_W['openid']
            );
            $fans   = pdo_fetch($sql, $params);
            if (!empty($fans)) {
                if (!empty($fans['uid'])) {
                    _mc_login(array(
                        'uid' => intval($fans['uid'])
                    ));
                    if (empty($_W['member']['uid'])) {
                        return false;
                    } else {
                        return $_W['member']['uid'];
                    }
                } else {
                    return $this->doMobileRegistMember();
                }
            } else {
                return $this->doMobileRegistFans();
            }
        } else {
            load()->func('communication');
            if (empty($_W['acid'])) {
                $_W['acid'] = pdo_fetchcolumn("SELECT acid FROM " . tablename('mc_mapping_fans') . " WHERE uniacid='{$_W['uniacid']}' AND openid = '{$_W['openid']}'");
            }
            $account = account_fetch($_W['acid']);
            load()->classs('weixin.account');
            $accObj                           = WeixinAccount::create($_W['account']['acid']);
            $account['access_token']['token'] = $accObj->fetch_token();
            if (empty($account['access_token']['token'])) {
                return false;
            }
            $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $account['access_token']['token'] . "&openid=" . $_W['openid'] . "&lang=zh_CN";
            $re  = ihttp_get($url);
            if ($re['code'] == 200) {
                $userinfo = json_decode($re['content'], true);
                if ($userinfo['errcode'] == '41001') {
                }
            }
            $data = array(
                'nickname' => stripslashes($userinfo['nickname']),
                'avatar' => trim($userinfo['headimgurl']),
                'gender' => $userinfo['sex'],
                'nationality' => $userinfo['country'],
                'resideprovince' => $userinfo['province'] . '省',
                'residecity' => $userinfo['city'] . '市'
            );
            pdo_update('mc_members', $data, array(
                'uid' => $_W['member']['uid']
            ));
            return $_W['member']['uid'];
        }
    }
    public function doMobileRegistFans()
    {
        global $_W;
        $rec                 = array();
        $rec['acid']         = $_W['acid'];
        $rec['uniacid']      = $_W['uniacid'];
        $rec['uid']          = 0;
        $rec['openid']       = $_W['openid'];
        $rec['salt']         = random(8);
        $rec['follow']       = 0;
        $rec['followtime']   = 0;
        $rec['unfollowtime'] = 0;
        $rec['unfollowtime'] = 0;
        $rec['uid']          = $this->doMobileRegistMember();
        pdo_insert('mc_mapping_fans', $rec);
        _mc_login(array(
            'uid' => intval($rec['uid'])
        ));
        if (empty($_W['member']['uid'])) {
            return false;
        } else {
            return $_W['member']['uid'];
        }
    }
    public function doMobileRegistMember()
    {
        global $_W;
        load()->func('communication');
        if (empty($_W['acid'])) {
            $_W['acid'] = pdo_fetchcolumn("SELECT acid FROM " . tablename('mc_mapping_fans') . " WHERE uniacid='{$_W['uniacid']}' AND openid = '{$_W['openid']}'");
        }
        $account = account_fetch($_W['acid']);
        load()->classs('weixin.account');
        $accObj                           = WeixinAccount::create($_W['account']['acid']);
        $account['access_token']['token'] = $accObj->fetch_token();
        if (empty($account['access_token']['token'])) {
            return false;
        }
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $account['access_token']['token'] . "&openid=" . $_W['openid'] . "&lang=zh_CN";
        $re  = ihttp_get($url);
        if ($re['code'] == 200) {
            $userinfo = json_decode($re['content'], true);
            if ($userinfo['errcode'] == '41001') {
            }
        }
        $rec['tag']       = base64_encode($userinfo);
        $default_groupid  = pdo_fetchcolumn('SELECT groupid FROM ' . tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(
            ':uniacid' => $_W['uniacid']
        ));
        $data             = array(
            'uniacid' => $_W['uniacid'],
            'email' => md5($_W['openid']) . '@we7.cc',
            'salt' => random(8),
            'groupid' => $default_groupid,
            'createtime' => TIMESTAMP,
            'nickname' => stripslashes($userinfo['nickname']),
            'avatar' => trim($userinfo['headimgurl']),
            'gender' => $userinfo['sex'],
            'nationality' => $userinfo['country'],
            'resideprovince' => $userinfo['province'] . '省',
            'residecity' => $userinfo['city'] . '市'
        );
        $data['password'] = md5($message['from'] . $data['salt'] . $_W['config']['setting']['authkey']);
        pdo_insert('mc_members', $data);
        $rec['uid'] = pdo_insertid();
        pdo_update('mc_mapping_fans', $rec, array(
            'uniacid' => $_W['uniacid'],
            'openid' => $_W['openid']
        ));
        _mc_login(array(
            'uid' => intval($rec['uid'])
        ));
        if (empty($_W['member']['uid'])) {
            return false;
        } else {
            return $_W['member']['uid'];
        }
    }
}
function get_timef($begin_time, $end_time)
{
    if ($begin_time < $end_time) {
        $starttime = $begin_time;
        $endtime   = $end_time;
    } else {
        $starttime = $end_time;
        $endtime   = $begin_time;
    }
    $timediff = $endtime - $starttime;
    $days     = intval($timediff / 86400);
    $remain   = $timediff % 86400;
    $hours    = intval($remain / 3600);
    $remain   = $remain % 3600;
    $mins     = intval($remain / 60);
    $secs     = $remain % 60;
    $res      = array(
        "day" => $days,
        "hour" => $hours,
        "min" => $mins,
        "sec" => $secs
    );
    return $res;
}