<?php

/**
 * 打气球送积分模块
 *
 * [皓蓝] www.weixiamen.cn 5517286
 */
defined('IN_IA') or exit('Access Denied');

class Hl_dqqModule extends WeModule {

    public $tablename = 'dqq_reply';

    public function fieldsFormDisplay($rid = 0) {
        global $_W;
        if (!empty($rid)) {
            $reply = pdo_fetch("SELECT * FROM " . tablename($this->tablename) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
            $award = pdo_fetchall("SELECT * FROM " . tablename('dqq_award') . " WHERE rid = :rid ORDER BY `id` ASC", array(':rid' => $rid));
        } else {
            $reply = array(
                'periodlottery' => 1,
                'maxlottery' => 1,
            );
        }
        include $this->template('form');
    }

    public function fieldsFormValidate($rid = 0) {
        return true;
    }

    public function fieldsFormSubmit($rid = 0) {
        global $_GPC, $_W;
        $id = intval($_GPC['reply_id']);
        $insert = array(
            'rid' => $rid,
            'picture' => $_GPC['picture'],
            'description' => $_GPC['description'],
            'periodlottery' => intval($_GPC['periodlottery']),
            'maxlottery' => intval($_GPC['maxlottery']),
            'rule' => htmlspecialchars_decode($_GPC['rule']),
            'default_tips' => $_GPC['default_tips'],
            'hitcredit' => intval($_GPC['hitcredit']),
            'misscredit' => intval($_GPC['misscredit']),
        );
        if (empty($id)) {
            pdo_insert($this->tablename, $insert);
        } else {
            if (!empty($_GPC['picture'])) {
                load()->func('file');
                file_delete($_GPC['picture-old']);
            } else {
                unset($insert['picture']);
            }
            pdo_update($this->tablename, $insert, array('id' => $id));
        }
        if (!empty($_GPC['award-title'])) {
            foreach ($_GPC['award-title'] as $index => $title) {
                if (empty($title)) {
                    continue;
                }
                $update = array(
                    'title' => $title,
                    'description' => $_GPC['award-description'][$index],
                    'probalilty' => $_GPC['award-probalilty'][$index],
                    'total' => intval($_GPC['total'][$index]),
                    'get_jf' => $_GPC['get_jf'][$index],
                );

                pdo_update('dqq_award', $update, array('id' => $index));
            }
        }
        //处理添加
        if (!empty($_GPC['award-title-new'])) {
            foreach ($_GPC['award-title-new'] as $index => $title) {
                if (empty($title)) {
                    continue;
                }
                $insert = array(
                    'rid' => $rid,
                    'title' => $title,
                    'description' => $_GPC['award-description-new'][$index],
                    'probalilty' => $_GPC['award-probalilty-new'][$index],
                    'inkind' => intval($_GPC['award-inkind-new'][$index]),
                    'total' => 1,
                    'get_jf' => intval($_GPC['get_jf-new'][$index]),
                );


                pdo_insert('dqq_award', $insert);
            }
        }
    }

    public function ruleDeleted($rid = 0) {
        global $_W;
        $replies = pdo_fetchall("SELECT id, picture FROM " . tablename($this->tablename) . " WHERE rid = '$rid'");
        $deleteid = array();
        if (!empty($replies)) {
            load()->func('file');
            foreach ($replies as $index => $row) {
                file_delete($row['picture']);
                $deleteid[] = $row['id'];
            }
        }
        pdo_delete($this->tablename, "id IN ('" . implode("','", $deleteid) . "')");
        return true;
    }

    public function doFormDisplay() {
        global $_W, $_GPC;
        $result = array('error' => 0, 'message' => '', 'content' => '');
        $result['content']['id'] = $GLOBALS['id'] = 'add-row-news-' . $_W['timestamp'];
        $result['content']['html'] = $this->template('dqq/item', TEMPLATE_FETCH);
        exit(json_encode($result));
    }

    public function doDelete() {
        global $_W, $_GPC;
        $id = intval($_GPC['id']);
        $sql = "SELECT id FROM " . tablename('dqq_award') . " WHERE `id`=:id";
        $row = pdo_fetch($sql, array(':id' => $id));
        if (empty($row)) {
            message('抱歉，奖品不存在或是已经被删除！', '', 'error');
        }
        if (pdo_delete('dqq_award', array('id' => $id))) {
            message('删除奖品成功', '', 'success');
        }
    }

}
