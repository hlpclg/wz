<?php
/**
 * 凡筞打印机模块定义
 * @author www.zheyitianShi.Com团队
 */
defined('IN_IA') or exit('Access Denied');

class We7_fanceModule extends WeModule {

	public $tablename = 'fance';

	public function fieldsFormDisplay($rid = 0) {
      	if (!empty($rid)) {
			$sql = 'SELECT * FROM ' . tablename($this->tablename) . ' WHERE `rid` = :rid';
			$reply = pdo_fetch($sql, array(':rid' => $rid));
 		}
		include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		global $_GPC;
		if (empty($_GPC['forward_url'])) {
			return '转发URL地址不能为空';
		}
		if (empty($_GPC['forward_token'])) {
			return '转发地址Token值不能为空';
		}
		if (empty($_GPC['start_keyword'])) {
			return '开始打印关键字不能为空';
		}
		if (empty($_GPC['end_keyword'])) {
			return '结束打印关键字不能为空';
		}
		return true;
	}

	public function fieldsFormSubmit($rid) {
		global $_GPC, $_W;
		$id = intval($_GPC['reply_id']);
		$data = array(
			'rid' => $rid,
			'url' => $_GPC['forward_url'],
			'token' => $_GPC['forward_token'],
			'status' => intval($_GPC['is_status']),
			'start_keyword' => $_GPC['start_keyword'],
			'end_keyword' => $_GPC['end_keyword']
		);

		if (empty($id)) {
			pdo_insert($this->tablename, $data);
		} else {
			pdo_update($this->tablename, $data, array('id' => $id));
		}

      	return true;
	}

	public function ruleDeleted($rid) {
		$sql = 'SELECT `id`, `pic` FROM ' . tablename('fance_reply') . ' WHERE `rid` = :rid';
		$replies = pdo_fetchall($sql, array(':rid' => $rid));
		load()->func('file');
		if (!empty($replies)) {
			foreach ($replies as $index => $row) {
				file_delete($row['pic']);
			}
		}
		$params = array('rid' => $rid);
		pdo_delete('fance_reply', $params);
		pdo_delete($this->tablename, $params);
		return true;
	}


}