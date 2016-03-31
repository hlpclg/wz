<?php
/**
 * 超级防伪码模块定义
 *
 * @author 超级防伪码
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
class Super_securitycodeModule extends WeModule
{
    public function fieldsFormDisplay($rid = 0)
    {
        global $_W;
        if ($rid == 0) {
            $reply = array(
                'tnumber' => 3
            );
        } else {
            $reply = pdo_fetch("SELECT * FROM " . tablename('super_securitycode_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(
                ':rid' => $rid
            ));
        }
        include $this->template('form');
    }
    public function fieldsFormValidate($rid = 0)
    {
        return '';
    }
    public function fieldsFormSubmit($rid)
    {
        global $_GPC, $_W;
        $id     = intval($_GPC['reply_id']);
        $insert = array(
            'rid' => $rid,
            'weid' => $_W['weid'],
            'tnumber' => $_GPC['tnumber'],
            'Reply' => $_GPC['Reply'],
            'Failure' => $_GPC['Failure']
        );
        if (empty($id)) {
            pdo_insert('super_securitycode_reply', $insert);
        } else {
            pdo_update('super_securitycode_reply', $insert, array(
                'id' => $id
            ));
        }
    }
    public function ruleDeleted($rid)
    {
        global $_W;
        $replies  = pdo_fetchall("SELECT id,rid FROM " . tablename('super_securitycode_reply') . " WHERE rid = '$rid'");
        $deleteid = array();
        if (!empty($replies)) {
            foreach ($replies as $index => $row) {
                $deleteid[] = $row['id'];
                $ridid[]    = $row['rid'];
            }
        }
        pdo_delete('super_securitycode_reply', "id IN ('" . implode("','", $deleteid) . "')");
        return true;
    }
    public function settingsDisplay($settings)
    {
        global $_W, $_GPC;
        if (checksubmit()) {
            $this->saveSettings($dat);
        }
        include $this->template('settings');
    }
}