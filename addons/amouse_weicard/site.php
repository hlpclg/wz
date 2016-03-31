
<?php
/**
 * 模块 0.6
 *
 * @url
 */
defined('IN_IA') or exit('Access Denied');
require_once "jssdk.php";
define('AW_ROOT', IA_ROOT . '/addons/amouse_weicard');
class Amouse_WeicardModuleSite extends WeModuleSite
{

    //后台管理程序 web文件夹下
    public function __web($f_name)
    {
        global $_W, $_GPC;
        checklogin();
        $weid = $_W['uniacid'];
        //每个页面都要用的公共信息，今后可以考虑是否要运用到缓存
        include_once 'web/' . strtolower(substr($f_name, 5)) . '.php';
    }

    public function doMobileIndex()
    { //首页
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $id = intval($_GPC['id']);
        if (empty($id)) {
            message('抱歉，参数错误！', '', 'error');
        }
        $reply = $this->get_reply($id);
        if ($reply == false) {
            message('抱歉，活动已经结束，下次再来吧！', '', 'error');
        }
        if ($reply['status'] == 0) {
            message('抱歉，名片创建已禁用，请稍后进入!', '', 'error');
        }
        //是否关注
        /*$followed = !empty($_W['openid']);
        if ($followed) {
            $mf = pdo_fetch("select follow from " . tablename('mc_mapping_fans') . " where openid=:openid limit 1", array(":openid" => $_W['openid']));
            $followed = $mf['follow'] == 1;
        }*/
     //   $fromuser=$_GPC['openid'];
        $setting = $this->get_sysset($weid);
        $oauth_openid = "amouse_weicard_201504012101_001_".$id.'_'.$weid;
       $fromuser= $_COOKIE[$oauth_openid];

        if (empty($fromuser)) {
            if (!empty($setting) && !empty($setting['appid']) && !empty($setting['appsecret'])) {
              $this->checkCookie($id);
            }
        }

        $fans = pdo_fetch("select * from " . tablename('amouse_weicard2_fans') . " where rid=:rid and openid=:openid limit 1", array(":rid" => $id, ":openid" => $fromuser));
        /* $joincount=pdo_fetchcolumn("select count(*) from ".tablename('amouse_weicard_fans')." where rid=:rid",array(":rid"=>$id));
         $joincount += $reply['joincount'];
         $joincount = number_format($joincount, 0);
         //浏览次数
         pdo_query("update " . tablename('amouse_weicard_reply') . " set viewnum=viewnum+1 where rid=:rid", array(":rid"=>$id));*/

      if (!empty($fans['name'])) {
            $detailUrl = $_W['siteroot'] . 'app/' . $this->createMobileUrl('detail', array('cardid' => $fans['id'], 'wxid' => $fans['openid'], 'rid' => $id));
            header("location:$detailUrl");
            exit;
        }

        include $this->template('index');
    }


    function write_cache($filename, $data)
    {
        global $_W;
        $path = "/addons/amouse_weicard";
        $filename = IA_ROOT . $path . "/data/" . $filename . ".txt";
        load()->func('file');
        mkdirs(dirname(__FILE__));
        file_put_contents($filename, base64_encode(json_encode($data)));
        @chmod($filename, $_W['config']['setting']['filemode']);
        return is_file($filename);
    }

    /**
     * @return boolean
     */
    public function get_reply($rid)
    {
        $path = "/addons/amouse_weicard";
        $filename = IA_ROOT . $path . "/data/" . $rid . ".txt";
        if (is_file($filename)) {
            $content = file_get_contents($filename);
            if (empty($content)) {
                return false;
            }
            return json_decode(base64_decode($content), true);
        }
        return pdo_fetch("SELECT * FROM " . tablename('amouse_weicard2_reply') . " WHERE rid = :rid limit 1", array(':rid' => $rid));
    }


    //我的名片
    public function doMobileDetail(){
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $cardid = $_GPC['cardid'];
        $wxid = $_GPC['wxid'];
        $rid = $_GPC['rid'];
        $reply = $this->get_reply($rid);
        $setting = $this->get_sysset($weid);
        $weicard = pdo_fetch("select * from " . tablename('amouse_weicard2_fans') . " where id=:cardid and openid=:openid limit 1", array(":cardid" => $cardid, ":openid" => $wxid));

        //分享信息
        $sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('detail', array('cardid' => $cardid, 'wxid' => $openid, 'rid' => $rid));
        if (!empty($weicard['headimg'])) {
            $shareimg = toimage($weicard['headimg']);
        } else {
            $shareimg = toimage($reply['thumb']);
        }

        if(!empty($weicard['template'])) {
            include $this->template($weicard['templatefile']);
            exit;
        }

        include $this->template('mycard');
    }


    private function checkCookie($rid)
    {
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $setting = $this->get_sysset($weid);
        $oauth_openid = "amouse_weicard_201504012101_001_" . $rid . '_' . $weid;
        if (empty($_COOKIE[$oauth_openid])) {
            if (!empty($setting) && !empty($setting['appid']) && !empty($setting['appsecret'])) { // 判断是否是借用设置
                $appid = $setting['appid'];
                $secret = $setting['appsecret'];
            }
            $url = $_W['siteroot'] . "app/" . substr($this->createMobileUrl('userinfo', array('rid' => $rid), true), 2);
            $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $appid . "&redirect_uri=" . urlencode($url) . "&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect";
            header("location:$oauth2_code");
            exit;
        }
    }


    public function doMobileUserinfo() {
        global $_GPC, $_W;
        $weid = $_W['uniacid']; //当前公众号ID
        load()->func('communication');
        $rid= $_GPC['rid'];
        //用户不授权返回提示说明
        if ($_GPC['code'] == "authdeny") {
            $url = $this->createMobileUrl('index', array('id' => $rid), true);
            $url2 = $_W['siteroot'] . "app/" . substr($url, 2);
            header("location:$url2");
            exit('authdeny');
        }
        //高级接口取未关注用户Openid
        if (isset($_GPC['code'])) {
            //第二步：获得到了OpenID
            $serverapp = $_W['account']['level'];
            $setting = $this->get_sysset($weid);
            if (!empty($setting) && !empty($setting['appid']) && !empty($setting['appsecret'])) { // 判断是否是借用设置
                $appid = $setting['appid'];
                $secret = $setting['appsecret'];
            }
            $state = $_GPC['state'];
            //1为关注用户, 0为未关注用户
            $code = $_GPC['code'];
            $oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&code=".$code."&grant_type=authorization_code";
            $content =ihttp_get($oauth2_code);
            $token = @json_decode($content['content'], true);
            if (empty($token) || !is_array($token)
                || empty($token['access_token']) || empty($token['openid'])
            ) {
                echo '<h1>获取微信公众号授权' . $code . '失败[无法取得token以及openid], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';
                exit;
            }
            $from_user = $token['openid'];
            //未关注用户和关注用户取全局access_token值的方式不一样
            if ($state == 1) {
                $oauth2_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $secret . "";
                $content = ihttp_get($oauth2_url);
                $token_all = @ json_decode($content['content'], true);
                if (empty($token_all) || !is_array($token_all) || empty($token_all['access_token'])) {
                    echo '<h1>获取微信公众号授权失败[无法取得access_token], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';
                    exit;
                }
                $access_token = $token_all['access_token'];
                $oauth2_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $access_token . "&openid=" . $from_user . "&lang=zh_CN";
            } else {
                $access_token = $token['access_token'];
                $oauth2_url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$from_user . "&lang=zh_CN";
            }

            //使用全局ACCESS_TOKEN获取OpenID的详细信息
            $content=ihttp_get($oauth2_url);
            $info= @json_decode($content['content'], true);
            if (empty($info) || !is_array($info) || empty($info['openid']) || empty($info['nickname'])) {
                echo '<h1>获取微信公众号授权失败[无法取得info], 请稍后重试！<h1>';
                exit;
            }

            $row = array('nickname' => $info["nickname"], 'realname' => $info["nickname"], 'gender' => $info['sex']);
            if (!empty($info["country"])) {
                $row['nationality'] = $info["country"];
            }
            if (!empty($info["province"])) {
                $row['resideprovince'] = $info["province"];
            }
            if (!empty($info["city"])) {
                $row['residecity'] = $info["city"];
            }
            if (!empty($info["headimgurl"])) {
                $row['avatar'] = $info["headimgurl"];
            }
            fans_update($info['openid'], $row);
            $oauth_openid = "amouse_weicard_201504012101_001_".$rid.'_'.$_W['uniacid'];
            setcookie($oauth_openid, $info['openid'], time() + 3600 * 240);

            $newfans = false;
            $fans = pdo_fetch("select * from " . tablename('amouse_weicard2_fans') . " where rid=:rid and openid=:openid limit 1", array(":rid" => $rid, ":openid" => $info['openid']));
            if (!empty($fans)) {
                pdo_update("amouse_weicard2_fans", array("headimg" => $info["headimgurl"]), array("openid" => $info['openid']));
            } else {
                $fans = array(
                    "rid" => $rid,
                    "weid"=>$weid,
                    "openid" => $info['openid'],
                    "headimg" => $info["headimgurl"],
                    "createtime" => time()
                );
                pdo_insert("amouse_weicard2_fans", $fans);
                $newfans = true;
            }
            $url = $_W['siteroot']."app/".substr($this->createMobileUrl('index', array('id' => $rid,'openid'=>$fans['openid'])), 2);
            header("location:$url");
            exit;
        } else {
            echo '<h1>网页授权域名设置出错!</h1>';
            exit;
        }
    }


    public function getQRImage($id, $openid)
    {
        global $_W;
        $weicard = pdo_fetch("select * from " . tablename('amouse_weicard2_fans') . " where id=:cardid and openid=:openid limit 1", array(":cardid" => $id, ":openid" => $openid));

        include AW_ROOT . '/source/phpqrcode.php';
        $path = "/addons/amouse_weicard";
        $filename = IA_ROOT . $path . "/data/weicard_" . $id . ".png";
        load()->func('file');
        mkdirs(dirname(__FILE__));
        @chmod($filename, $_W['config']['setting']['filemode']);
        $chl = "BEGIN:VCARD\nVERSION:3.0" . //vcard头信息
            "\nFN:" . $weicard['name'] .
            "\nTEL:" . $weicard['mobile'] .
            "\nEMAIL:" . $weicard['email'] .
            "\nTITLE:" . $weicard['job'] .
            "\nORG:" . $weicard['company'] .
            "\nROLE:" . $weicard['department'] .
            "\nX-QQ:" . $weicard['qq'] .
            "\nADR;WORK;POSTAL:" . $weicard['address'] .
            "\nEND:VCARD"; //vcard尾信息

        $filename2 = ".." . $path . "/data/weicard_" . $id . ".png";
        QRcode::png($chl, $filename, QR_ECLEVEL_L, 100);
        echo  '<img src="' . $filename2 . '" style="height: 90%; width:90%;" class="img-responsive"/>';
    }

    public function getQRImage2($id, $openid)
    {
        global $_W;
        $weicard = pdo_fetch("select * from " . tablename('amouse_weicard2_fans') . " where id=:cardid and openid=:openid limit 1", array(":cardid" => $id, ":openid" => $openid));

        include AW_ROOT . '/source/phpqrcode.php';
        $path = "/addons/amouse_weicard";
        $filename = IA_ROOT . $path . "/data/weicard_" . $id . ".png";
        load()->func('file');
        mkdirs(dirname(__FILE__));
        @chmod($filename, $_W['config']['setting']['filemode']);
        $chl = "BEGIN:VCARD\nVERSION:3.0" . //vcard头信息
            "\nFN:" . $weicard['name'] .
            "\nTEL:" . $weicard['mobile'] .
            "\nEMAIL:" . $weicard['email'] .
            "\nTITLE:" . $weicard['job'] .
            "\nORG:" . $weicard['company'] .
            "\nROLE:" . $weicard['department'] .
            "\nX-QQ:" . $weicard['qq'] .
            "\nADR;WORK;POSTAL:" . $weicard['address'] .
            "\nEND:VCARD"; //vcard尾信息

        $filename2 = "..".$path."/data/weicard_" . $id . ".png";
        QRcode::png($chl, $filename, QR_ECLEVEL_L, 100);
        echo  $filename2;
    }


    //创建名片
    public function doMobileProcessData(){
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $rid = intval($_GPC['rid']);
        $weid = $_W['uniacid'];
        //$this->checkIsWeixin();
        $fromuser = $_GPC['openId'];
        if(empty($fromuser)){
            $fromuser = $_W['fans']['from_user'];
        }
        $weicard=pdo_fetch("select * from ".tablename('amouse_weicard2_fans')." where openid=:openid limit 1", array(":openid" => $fromuser));

        $insert1 = array(
            'weid' => $weid,
            'name' => $_GPC['name'],
            'mobile' => $_GPC['mobile'],
            'email' => $_GPC['email'],
            'qq' => $_GPC['qq'],
            'weixin' => $_GPC['weixin'],
            'job' => $_GPC['job'],
            'department' => $_GPC['department'],
            'company' => $_GPC['Company'],
            'address' => $_GPC['address'],
            'template' => $_GPC['template'],
            'templatefile' => "themes/style".$_GPC['template'].'mycard',
        );

        if (!empty($weicard)) {
            $cid = $weicard['id'];
            pdo_update('amouse_weicard2_fans',$insert1,array('id' => $weicard['id'],'openid'=>$fromuser));
        } else {
            $insert1['openid']=$fromuser;
            $insert1['createtime'] =time();
            pdo_insert('amouse_weicard2_fans', $insert1);
            $cid = pdo_insertid();
        }
        return $this->heixiuJson(1, $cid, '');
        exit;
    }

    public function heixiuJson($resultCode, $resultData, $resultMsg)
    {
        $jsonArray = array(
            'resultCode' => $resultCode,
            'resultData' => $resultData,
            'resultMsg' => $resultMsg
        );
        $jsonStr = json_encode($jsonArray);
        return $jsonStr;
    }


    private function checkIsWeixin()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {
            echo "本页面仅支持微信访问!非微信浏览器禁止浏览!";
            exit;
        }
    }

    //名片管理
    public function doWebWeicard()
    {
        $this->__web(__FUNCTION__);
    }

    //订单导出
    public function doWebExport()
    {
        $this->__web(__FUNCTION__);
    }

    //单条订单导出
    public function doWebExport2()
    {
        $this->__web(__FUNCTION__);
    }

    public function get_sysset($weid = 0)
    {
        global $_W;
        $path = "/addons/amouse_weicard";
        $filename = IA_ROOT . $path . "/data/sysset_" . $_W['uniacid'] . ".txt";
        if (is_file($filename)) {
            $content = file_get_contents($filename);
            if (empty($content)) {
                return false;
            }
            return json_decode(base64_decode($content), true);
        }
        return pdo_fetch("SELECT * FROM " . tablename('amouse_weicard2_sysset') . " WHERE weid=:weid limit 1", array(':weid' => $weid));
    }

    //参数设置
    public function doWebSysset()
    {
        $this->__web(__FUNCTION__);
    }


}
?>