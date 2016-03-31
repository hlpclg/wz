
<?php
defined('IN_IA') or exit('Access Denied');
class Fl_wsq_bmfwModule extends WeModule
{
    public function fieldsFormDisplay($rid = 0)
    {
    }
    public function fieldsFormValidate($rid = 0)
    {
        return '';
    }
    public function fieldsFormSubmit($rid)
    {
    }
    public function ruleDeleted($rid)
    {
    }
    public function settingsDisplay($settings)
    {
        global $_W, $_GPC;
        if (checksubmit()) {
            $this->saveSetting($_GPC);
            message('保存成功');
            die;
        }
        $sql  = "select * from " . tablename("fl_wsq_config") . " where weid={$_W['weid']}";
        $data = pdo_fetch($sql);
        include $this->template('setting');
    }
    public function saveSetting($settings)
    {
        global $_W;
        $sql  = "select * from " . tablename("fl_wsq_config") . " where weid={$_W['weid']}";
        $old  = pdo_fetch($sql);
        $data = Array(
            "show_title" => $settings['show_title']
        );
        if ($old) {
            $where = Array(
                "id" => $old['id']
            );
            $rs    = pdo_update("fl_wsq_config", $data, $where);
        } else {
            $data['weid'] = $_W['weid'];
            $rs           = pdo_insert("fl_wsq_config", $data);
        }
        return $rs;
    }
}