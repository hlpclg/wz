<?php

defined('IN_IA') or exit('Access Denied');

class Wkf_messageModule extends WeModule {
	public $name = 'Message';
	public $tablename = 'message_reply';	
	public function fieldsFormDisplay($rid = 0) {

	}

	public function fieldsFormValidate($rid = 0) {

		return '';
	}

    public function settingsDisplay($settings) {
        global $_GPC, $_W;
        if (checksubmit()) {
            $cfg = array(
                'isshow' => $_GPC['isshow'],
            );
            if ($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
        }
        load()->func('tpl');
        include $this->template('setting');
    }
}