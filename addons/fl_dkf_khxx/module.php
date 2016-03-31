<?php
defined('IN_IA') or exit('Access Denied');
class Fl_dkf_khxxModule extends WeModule
{
    public function settingsDisplay($settings)
    {
        global $_W, $_GPC;
        if (checksubmit('submit')) {
            $this->editSetting($_GPC);
            message('配置参数更新成功！', referer(), 'success');
        }
        $member_table = str_replace("`", "", tablename("mc_members"));
        $sql          = "SELECT
			COLUMN_NAME,
			column_comment
		FROM
			INFORMATION_SCHEMA. COLUMNS
		WHERE
			table_name = '$member_table'";
        $column       = pdo_fetchall($sql);
        $sql          = "select * from " . tablename("fl_dkf_column") . " where weid={$_W['weid']}";
        $wcolumn      = pdo_fetchall($sql);
        if (!$wcolumn) {
            $sql     = "select * from " . tablename("fl_dkf_column") . " where weid=0";
            $wcolumn = pdo_fetchall($sql);
        }
        foreach ($wcolumn as $key => $value) {
            $wcolumn_keys[$value['column_name']] = $value;
        }
        foreach ($column as $key => $value) {
            $column[$key]['column_show_name'] = $wcolumn_keys[$value['COLUMN_NAME']]['column_show_name'];
            $column[$key]['is_show']          = $wcolumn_keys[$value['COLUMN_NAME']]['is_show'];
            $column[$key]['is_edit']          = $wcolumn_keys[$value['COLUMN_NAME']]['is_edit'];
        }
        include $this->template('setting');
    }
    public function editSetting($data)
    {
        global $_W, $_GPC;
        $sql    = "select * from " . tablename("fl_dkf_column") . " where weid={$_W['weid']}";
        $column = pdo_fetchall($sql);
        foreach ($column as $key => $value) {
            $column_keys[$value['column_name']] = $value;
        }
        foreach ($data['column_show'] as $key => $value) {
            if ($column_keys[$key]) {
                $editData  = Array(
                    "column_show_name" => $value,
                    "is_show" => $data['is_show'][$key],
                    "is_edit" => $data['is_edit'][$key],
                    "weid" => $_W['weid']
                );
                $editWhere = Array(
                    "id" => $column_keys[$key]['id']
                );
                pdo_update('fl_dkf_column', $editData, $editWhere);
            } else {
                $insertData = Array(
                    "column_show_name" => $value,
                    "is_show" => $data['is_show'][$key],
                    "is_edit" => $data['is_edit'][$key],
                    "weid" => $_W['weid'],
                    "column_name" => $key
                );
                pdo_insert('fl_dkf_column', $insertData);
            }
        }
    }
}