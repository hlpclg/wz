<?php
/**
 * 新版图文回复模块定义
 *
 * @author meepo
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Abc_newsModule extends WeModule {
	public $tablename = 'news_reply';
	public $replies = array();

	public function fieldsFormDisplay($rid = 0) {
		global $_W;
		load()->func('tpl');
		$replies = pdo_fetchall("SELECT * FROM ".tablename($this->tablename)." WHERE rid = :rid ORDER BY `displayorder` DESC", array(':rid' => $rid));
		foreach($replies as &$reply) {
			if(!empty($reply['thumb'])) {
				$reply['src'] = tomedia($reply['thumb']);
			}
		}
		include $this->template('display');
	}
	
	public function fieldsFormValidate($rid = 0) {
		global $_GPC;
		if(empty($_GPC['titles'])) {
			return '必须填写有效的回复内容.';
		}
		foreach($_GPC['titles'] as $k => $v) {
			$row = array();
			if(empty($v)) {
				continue;
			}
			$row['title'] = $v;
			$row['id'] = $_GPC['id'][$k];
			$row['author'] = $_GPC['authors'][$k];
			$row['displayorder'] = $_GPC['displayorder'][$k];
			$row['thumb'] = $_GPC['thumbs'][$k];
			$row['description'] = $_GPC['descriptions'][$k];
			$row['content'] = $_GPC['contents'][$k];
			$row['url'] = $_GPC['urls'][$k];
			$row['incontent'] = intval($_GPC['incontent'][$k]);
			$row['createtime'] = time();
			$this->replies[] = $row;
		}
		if(empty($this->replies)) {
			return '必须填写有效的回复内容.';
		}
		foreach($this->replies as &$r) {
			if(trim($r['title']) == '') {
				return '必须填写有效的标题.';
			}
			if (trim($r['author']) == '') {
				return '必须填写有效的作者名称.';
			}
			if (trim($r['thumb']) == '') {
				return '必须填写有效的封面链接地址.';
			}
			if (trim($r['description']) == '') {
				return '必须填写有效的图文描述.';
			}
			$r['content'] = htmlspecialchars_decode($r['content']);
		}
		return '';
	}
	
	public function fieldsFormSubmit($rid = 0) {
		$sql = 'SELECT `id` FROM ' . tablename($this->tablename) . " WHERE `rid` = :rid";
		$replies = pdo_fetchall($sql, array(':rid' => $rid), 'id');
		$replyids = array_keys($replies);
		foreach($this->replies as $reply) {
			if (in_array($reply['id'], $replyids)) {
				pdo_update($this->tablename, $reply, array('id' => $reply['id']));
			} else {
				$reply['rid'] = $rid;
				pdo_insert($this->tablename, $reply);
			}
			unset($replies[$reply['id']]);
		}
		if (!empty($replies)) {
			$replies = array_keys($replies);
			$replies = implode(',', $replies);
			$sql = 'DELETE FROM '. tablename($this->tablename) . " WHERE `id` IN ({$replies})";
			pdo_query($sql);
		}
		return true;
	}
	
	public function ruleDeleted($rid = 0) {
		pdo_delete($this->tablename, array('rid' => $rid));
		return true;
	}

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		load()->func('tpl');
		//这里来展示设置项表单
		$list = pdo_fetchall("SELECT * FROM ".tablename('abc_replace')." WHERE uniacid = :uniacid",array(':uniacid'=>$_W['uniacid']));
		include $this->template('setting');
	}

}