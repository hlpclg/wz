<?php
defined('IN_IA') or exit('Access Denied');
define('MUSIC', 1);
define('IMAGE', 2);
class Francin_lovehelperModuleSite extends WeModuleSite
{
    private $tb_lovehelper_msg = 'lovehelper_msg';
    private $tb_lovehelper_ip = 'lovehelper_ip';
    private $tb_lovehelper_res = 'lovehelper_res';
    public $res_path = '../addons/francin_lovehelper/template/resource';
    public function doMobileSaylove()
    {
        global $_W, $_GPC;
        $res_path = $this->res_path;
        $ops      = array(
            'submit',
            'saylove'
        );
        $op       = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'saylove';
        if ($op == 'saylove') {
            $openid   = $_SESSION['openid'];
            $sql      = 'SELECT count(openid) FROM ' . tablename('mc_mapping_fans') . ' WHERE openid=:openid AND uniacid=:uniacid AND follow = 1';
            $params   = array(
                ':openid' => $openid,
                ':uniacid' => $_W['uniacid']
            );
            $isfollow = pdo_fetchcolumn($sql, $params);
            $dayBegin = mktime(0, 0, 0);
            $dayEnd   = mktime(23, 59, 59);
            $sql      = 'SELECT count(openid) FROM ' . tablename($this->tb_lovehelper_msg) . " WHERE openid=:openid AND uniacid=:uniacid AND createtime<$dayEnd AND createtime>$dayBegin";
            $params   = array(
                ':openid' => $openid,
                ':uniacid' => $_W['uniacid']
            );
            $count    = pdo_fetchcolumn($sql, $params);
            $sql      = 'SELECT * FROM ' . tablename($this->tb_lovehelper_res) . ' WHERE type=:type AND uniacid=:uniacid';
            $params   = array(
                ':type' => IMAGE,
                ':uniacid' => $_W['uniacid']
            );
            $bgimages = pdo_fetchall($sql, $params);
            include $this->template('saylove');
        }
        if ($op == 'submit') {
            $content  = $_GPC["content"];
            $fromuser = $_GPC["fromuser"];
            $bgimage  = $_GPC["bgimage"];
            $love     = array(
                'content' => $content,
                'fromuser' => $fromuser,
                'bgimage' => $bgimage,
                'uniacid' => $_W['uniacid'],
                'openid' => $_SESSION['openid'],
                'createtime' => TIMESTAMP
            );
            pdo_insert($this->tb_lovehelper_msg, $love);
            echo pdo_insertid();
        }
    }
    public function doMobileShowlove()
    {
        global $_W, $_GPC;
        $res_path   = $this->res_path;
        $shareimg   = $_W['siteroot'] . substr($res_path, 3) . "/images/icon.jpg";
        $sharelink  = $_W['siteurl'];
        $sharedesc  = "帮TA传情，你有" . random(2, true) . "个好友也在玩这个哦";
        $sharetitle = "帮TA传情，你有" . random(2, true) . "个好友也在玩这个哦";
        $id         = intval($_GPC['id']);
        $sql        = 'SELECT * FROM ' . tablename($this->tb_lovehelper_msg) . ' WHERE id=:id AND uniacid=:uniacid LIMIT 1';
        $params     = array(
            ':id' => $id,
            ':uniacid' => $_W['uniacid']
        );
        $msg        = pdo_fetch($sql, $params);
        $content    = $msg["content"] . "<br>from:" . $msg["fromuser"];
        $bgimage    = $msg["bgimage"];
        $viewcount  = $this->number($msg["viewcount"]);
        $forward    = $this->number($msg["forward"]);
        $praise     = $this->number($msg["praise"]);
        $ip         = array(
            'clientip' => $_W['clientip'],
            'id' => $id,
            'uniacid' => $_W['uniacid'],
            'createtime' => TIMESTAMP
        );
        $existIp    = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->tb_lovehelper_ip) . ' WHERE clientip=:clientip AND id=:id AND uniacid=:uniacid ', array(
            ':clientip' => $_W['clientip'],
            ':id' => $id,
            ':uniacid' => $_W['uniacid']
        ));
        if ($existIp == 0) {
            pdo_insert($this->tb_lovehelper_ip, $ip);
            pdo_query('update ' . tablename($this->tb_lovehelper_msg) . " set viewcount=viewcount+1 where id=:id and uniacid=:uniacid ", array(
                ':id' => $id,
                ':uniacid' => $_W['uniacid']
            ));
        }
        include $this->template('showlove');
    }
    public function doMobileForwardlove()
    {
        global $_W, $_GPC;
        $re  = "";
        $ops = array(
            'forward',
            'praise'
        );
        $op  = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'forward';
        if ($op == 'forward') {
            $id = intval($_GPC['id']);
            $re = pdo_query("update " . tablename($this->tb_lovehelper_msg) . " set forward=forward+1 where id=:id and uniacid=:uniacid ", array(
                ":id" => $id,
                ':uniacid' => $_W['uniacid']
            ));
            if ($re) {
                echo "0";
            } else {
                echo '-1';
            }
        }
        if ($op == 'praise') {
            $id = intval($_GPC['id']);
            $re = pdo_query("update " . tablename($this->tb_lovehelper_msg) . " set praise=praise+1 where id=:id and uniacid=:uniacid ", array(
                ":id" => $id,
                ':uniacid' => $_W['uniacid']
            ));
            if ($re) {
                $praise = pdo_fetchcolumn('SELECT praise FROM ' . tablename($this->tb_lovehelper_msg) . 'where id=:id and uniacid=:uniacid', array(
                    ":id" => $id,
                    ':uniacid' => $_W['uniacid']
                ));
                echo $praise;
            } else {
                echo '-1';
            }
        }
    }
    public function doMobileRank()
    {
        global $_W, $_GPC;
        $res_path = $this->res_path;
        $sql      = 'SELECT * FROM ' . tablename($this->tb_lovehelper_msg) . ' WHERE uniacid=:uniacid ORDER BY forward DESC LIMIT 10';
        $params   = array(
            ':uniacid' => $_W['uniacid']
        );
        $rank     = pdo_fetchall($sql, $params);
        include $this->template('rank');
    }
    public function doWebMusic()
    {
        global $_W, $_GPC;
        $ops = array(
            'display',
            'create',
            'delete'
        );
        $op  = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'display';
        if ($op == 'display') {
            $sql    = 'SELECT * FROM ' . tablename($this->tb_lovehelper_res) . ' WHERE type=:type and uniacid=:uniacid';
            $params = array(
                ':type' => MUSIC,
                ':uniacid' => $_W['uniacid']
            );
            $musics = pdo_fetchall($sql, $params);
            include $this->template('music');
        }
        if ($op == 'create') {
            if (checksubmit()) {
                $music = $_GPC['music'];
                pdo_insert($this->tb_lovehelper_res, array(
                    'name' => $music['name'],
                    'filename' => $music['filename'],
                    'type' => MUSIC,
                    'uniacid' => $_W['uniacid']
                ));
                message('添加背景音乐文件成功', $this->createWebUrl('music', array(
                    'op' => 'create'
                )), 'success');
            }
            include $this->template('music');
        }
        if ($op == 'delete') {
            $id = intval($_GPC['id']);
            if (empty($id)) {
                message('未找到指定背景音乐文件');
            }
            $sql      = 'SELECT filename FROM ' . tablename($this->tb_lovehelper_res) . ' WHERE id=:id AND type=:type AND uniacid=:uniacid LIMIT 1';
            $params   = array(
                ':id' => $id,
                ':type' => MUSIC,
                ':uniacid' => $_W['uniacid']
            );
            $filename = pdo_fetchcolumn($sql, $params);
            load()->func('file');
            file_delete($filename);
            $result = pdo_delete($this->tb_lovehelper_res, array(
                'id' => $id,
                'uniacid' => $_W['uniacid']
            ));
            if (intval($result) == 1) {
                message('删除背景音乐文件成功.', $this->createWebUrl('music'), 'success');
            } else {
                message('删除背景音乐文件失败.');
            }
        }
    }
    public function doWebBgimage()
    {
        global $_W, $_GPC;
        $ops = array(
            'display',
            'create',
            'delete'
        );
        $op  = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'display';
        if ($op == 'display') {
            $sql      = 'SELECT * FROM ' . tablename($this->tb_lovehelper_res) . ' WHERE type=:type and uniacid=:uniacid';
            $params   = array(
                ':type' => IMAGE,
                ':uniacid' => $_W['uniacid']
            );
            $bgimages = pdo_fetchall($sql, $params);
            include $this->template('bgimage');
        }
        if ($op == 'create') {
            if (checksubmit()) {
                $bgimage = $_GPC['bgimage'];
                pdo_insert($this->tb_lovehelper_res, array(
                    'name' => $bgimage['name'],
                    'filename' => $bgimage['filename'],
                    'type' => IMAGE,
                    'uniacid' => $_W['uniacid']
                ));
                message('添加背景图片文件成功', $this->createWebUrl('bgimage', array(
                    'op' => 'create'
                )), 'success');
            }
            include $this->template('bgimage');
        }
        if ($op == 'delete') {
            $id = intval($_GPC['id']);
            if (empty($id)) {
                message('未找到指定背景图片文件');
            }
            $sql      = 'SELECT filename FROM ' . tablename($this->tb_lovehelper_res) . ' WHERE id=:id AND type=:type AND uniacid=:uniacid LIMIT 1';
            $params   = array(
                ':id' => $id,
                ':type' => IMAGE,
                ':uniacid' => $_W['uniacid']
            );
            $filename = pdo_fetchcolumn($sql, $params);
            load()->func('file');
            file_delete($filename);
            $result = pdo_delete($this->tb_lovehelper_res, array(
                'id' => $id,
                'uniacid' => $_W['uniacid']
            ));
            if (intval($result) == 1) {
                message('删除背景图片文件成功.', $this->createWebUrl('bgimage'), 'success');
            } else {
                message('删除背景图片文件失败.');
            }
        }
    }
    private function number($n)
    {
        if ($n < 10000) {
            return $n;
        } else if ($n < 100000) {
            return round($n / 10000, 1) . "万";
        } else {
            return '10万+';
        }
    }
    private function randomBgres($type)
    {
        global $_W;
        $sql      = 'SELECT filename FROM ' . tablename($this->tb_lovehelper_res) . ' WHERE type=:type AND uniacid=:uniacid';
        $params   = array(
            ':type' => $type,
            ':uniacid' => $_W['uniacid']
        );
        $filename = pdo_fetchall($sql, $params);
        $ra       = rand(0, count($filename) - 1);
        return $filename[$ra]['filename'];
    }
}