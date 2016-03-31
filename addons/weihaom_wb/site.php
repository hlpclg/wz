<?php

defined('IN_IA') or exit('Access Denied');

class Weihaom_wbModuleSite extends WeModuleSite {

    public function doMobileIndex() {
        global $_W, $_GPC;

        if (empty($_W['fans']['openid'])) {
            message('请先关注公众号再来参加活动吧！');
        }

        $rid = intval($_GPC['rid']);
        $sql = 'SELECT * FROM ' . tablename('weihaom_wb_reply') .' WHERE `rid` = :rid';
        $params = array(':rid' => $rid);
        $set = pdo_fetch($sql, $params);
        if (empty($set)) {
            message('活动不存在或已经被删除');
        }

        $sql = 'SELECT * FROM ' . tablename('weihaom_wb_user') . ' WHERE `weid` = :weid AND `rid` = :rid AND
                `from_user` = :openid';
        $params[':weid'] = $_W['uniacid'];
        $params[':openid'] = $_W['fans']['openid'];
        $user = pdo_fetch($sql, $params);

        if (intval($_GPC['id'])) {
            $score = intval($_GPC['score']);
            if ($user['score'] < $score) {
                $user['score'] = $score;
                $update = array('score' => $score);
                pdo_update('weihaom_wb_user', $update, array('id' => intval($_GPC['id'])));
            }
            message($user['score'], '', 'ajax');
        }

        if (empty($user)) {
        	$result = mc_fetch($_W['member']['uid'], array('nickname'));
            $insert = array(
                'weid' => $_W['uniacid'],
                'rid' => $params[':rid'],
                'from_user' => $_W['fans']['openid'],
                'realname' => $result['nickname'], // $_W['fans']['nickname']
                'score' => 0
            );
            pdo_insert('weihaom_wb_user', $insert);
            $user = array('id' => pdo_insertid());
        }

        $realname = $_W['fans']['nickname'];
        $set['description'] = str_replace("\r\n", '', $set['description']);

        include $this->template('index');
    }

    public function doMobilePhb() {
        global $_W, $_GPC;
        $rid = intval($_GPC['rid']);
        $sql = 'SELECT * FROM ' . tablename('weihaom_wb_reply') . ' WHERE `rid` = :rid';
        $params = array(':rid' => $rid);
        $set = pdo_fetch($sql, $params);
        $set['description'] = str_replace("\r\n", '', $set['description']);
        $sql = 'SELECT * FROM ' . tablename('weihaom_wb_user') . ' WHERE `rid` = :rid ORDER BY `score` DESC LIMIT 10';
        $users = pdo_fetchall($sql, $params);
        include $this->template('phb');
    }

    public function doWebuserlist(){
        global $_W,$_GPC;
        $rid = intval($_GPC['id']);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $sql = 'SELECT * FROM ' . tablename('weihaom_wb_user') . " WHERE `rid`=:rid order by score DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
        $params = array();
        $params[':rid'] = $rid;
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('weihaom_wb_user') . " WHERE  `rid`=:rid", $params);
        $pager = pagination($total, $pindex, $psize);

        $list = pdo_fetchall($sql, $params);
        $n = ($pindex -1)  * $psize;
        foreach($list as &$row){
            $row['rank'] = ++$n;
        }
        unset($row);
        include $this->template('userlist');
    }

    public function getHomeTiles() {
        global $_W;
        $urls = array();
        $sql = 'SELECT `id`, `rid`, `title` FROM ' . tablename('weihaom_wb_reply') . ' WHERE `uniacid` = :uniacid';
        $replies = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
        if (!empty($replies)) {
            foreach ($replies as $reply) {
                $urls[] = array('title' => $reply['title'], 'url' => $this->createMobileUrl('index', array('rid' => $reply['rid'])));
            }
        }
        return $urls;
    }

}
