<?php
/**
 * 抢楼活动模块定义
 *
 * @author 美丽心情
 * @qq 513316788
 */
defined('IN_IA') or exit('Access Denied');

class bmfloorModule extends WeModule {
	public $tablename = 'bmfloor_award';
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_W;
		if (!empty($rid)) {
			$award = pdo_fetchall("SELECT * FROM ".tablename($this->tablename)." WHERE rid = :rid ORDER BY `floor` ASC", array(':rid' => $rid));
			$prompt = pdo_fetch("SELECT * FROM ".tablename('bmfloor')." WHERE rid = :rid", array(':rid' => $rid));
		}
		include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_GPC, $_W;

		$table = tablename("bmfloor_$rid");
		if (!$this->_table_exist($table)) {
			$sql =<<<EOF
CREATE TABLE IF NOT EXISTS $table (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `from_user` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
EOF;
			pdo_run($sql);
		}

		if (isset($_GPC['awardprompt'])) {
			$sql = "SELECT id FROM " . tablename('bmfloor') . " WHERE `rid`=:rid";
			$row = pdo_fetch($sql, array(':rid'=>$rid));
			if ($row) {
				$update = array(
					'awardprompt' => trim($_GPC['awardprompt']),
				);
				
				pdo_update('bmfloor', $update, array('id' => $row['id']));
			} else {
				$insert = array(
					'rid' => $rid,
					'awardprompt' => trim($_GPC['awardprompt']),
				);

				pdo_insert('bmfloor', $insert);
			}
		}

		if (isset($_GPC['currentprompt'])) {
			$sql = "SELECT id FROM " . tablename('bmfloor') . " WHERE `rid`=:rid";
			$row = pdo_fetch($sql, array(':rid'=>$rid));
			if ($row) {
				$update = array(
					'currentprompt' => trim($_GPC['currentprompt']),
				);
				
				pdo_update('bmfloor', $update, array('id' => $row['id']));
			} else {
				$insert = array(
					'rid' => $rid,
					'currentprompt' => trim($_GPC['currentprompt']),
				);
				pdo_insert('bmfloor', $insert);
			}
		}
		
		if (isset($_GPC['starttime'])) {
			$sql = "SELECT id FROM " . tablename('bmfloor') . " WHERE `rid`=:rid";
			$row = pdo_fetch($sql, array(':rid'=>$rid));
			if ($row) {
				$update = array(
					'starttime' => $_GPC['starttime'],
				);
				
				pdo_update('bmfloor', $update, array('id' => $row['id']));
			} else {
				$insert = array(
					'rid' => $rid,
					'starttime' => $_GPC['starttime'],
				);
				pdo_insert('bmfloor', $insert);
			}
		}
		
		if (isset($_GPC['endtime'])) {
			$sql = "SELECT id FROM " . tablename('bmfloor') . " WHERE `rid`=:rid";
			$row = pdo_fetch($sql, array(':rid'=>$rid));
			if ($row) {
				$update = array(
					'endtime' => $_GPC['endtime'],
				);
				
				pdo_update('bmfloor', $update, array('id' => $row['id']));
			} else {
				$insert = array(
					'rid' => $rid,
					'endtime' => $_GPC['endtime'],
				);
				pdo_insert('bmfloor', $insert);
			}
		}				

		if (isset($_GPC['url'])) {
			$sql = "SELECT id FROM " . tablename('bmfloor') . " WHERE `rid`=:rid";
			$row = pdo_fetch($sql, array(':rid'=>$rid));
			if ($row) {
				$update = array(
					'url' => trim($_GPC['url']),
				);
				pdo_update('bmfloor', $update, array('id' => $row['id']));
			} else {
				$insert = array(
					'rid' => $rid,
					'url' => trim($_GPC['url']),					
				);
				pdo_insert('bmfloor', $insert);
			}
		}

		if (isset($_GPC['url1'])) {
			$sql = "SELECT id FROM " . tablename('bmfloor') . " WHERE `rid`=:rid";
			$row = pdo_fetch($sql, array(':rid'=>$rid));
			if ($row) {
				$update = array(
					'url1' => trim($_GPC['url1']),
				);
				pdo_update('bmfloor', $update, array('id' => $row['id']));
			} else {
				$insert = array(
					'rid' => $rid,
					'url1' => trim($_GPC['url1']),					
				);
				pdo_insert('bmfloor', $insert);
			}
		}		
		
		if (isset($_GPC['picture'])) {
			$sql = "SELECT id FROM " . tablename('bmfloor') . " WHERE `rid`=:rid";
			$row = pdo_fetch($sql, array(':rid'=>$rid));
			if ($row) {
				$update = array(
					'picture' => trim($_GPC['picture']),
				);
				pdo_update('bmfloor', $update, array('id' => $row['id']));
			} else {
				$insert = array(
					'rid' => $rid,
					'picture' => trim($_GPC['picture']),					
				);
				pdo_insert('bmfloor', $insert);
			}
		}	
				
		if (isset($_GPC['total'])) {
			$sql = "SELECT id FROM " . tablename('bmfloor') . " WHERE `rid`=:rid";
			$row = pdo_fetch($sql, array(':rid'=>$rid));
			if ($row) {
				$update = array(
					'total' => trim($_GPC['total']),
				);
				pdo_update('bmfloor', $update, array('id' => $row['id']));
			} else {
				$insert = array(
					'rid' => $rid,
					'total' => trim($_GPC['total']),					
				);
				pdo_insert('bmfloor', $insert);
			}
		}	
		
		if (isset($_GPC['memo'])) {
			$sql = "SELECT id FROM " . tablename('bmfloor') . " WHERE `rid`=:rid";
			$row = pdo_fetch($sql, array(':rid'=>$rid));
			if ($row) {
				$update = array(
					'memo' => trim($_GPC['memo']),
				);
				pdo_update('bmfloor', $update, array('id' => $row['id']));
			} else {
				$insert = array(
					'rid' => $rid,
					'memo' => trim($_GPC['memo']),					
				);
				pdo_insert('bmfloor', $insert);
			}
		}
		
		if (isset($_GPC['memo1'])) {
			$sql = "SELECT id FROM " . tablename('bmfloor') . " WHERE `rid`=:rid";
			$row = pdo_fetch($sql, array(':rid'=>$rid));
			if ($row) {
				$update = array(
					'memo1' => trim($_GPC['memo1']),
				);
				pdo_update('bmfloor', $update, array('id' => $row['id']));
			} else {
				$insert = array(
					'rid' => $rid,
					'memo1' => trim($_GPC['memo1']),					
				);
				pdo_insert('bmfloor', $insert);
			}
		}
		
		if (isset($_GPC['memo2'])) {
			$sql = "SELECT id FROM " . tablename('bmfloor') . " WHERE `rid`=:rid";
			$row = pdo_fetch($sql, array(':rid'=>$rid));
			if ($row) {
				$update = array(
					'memo2' => trim($_GPC['memo2']),
				);
				pdo_update('bmfloor', $update, array('id' => $row['id']));
			} else {
				$insert = array(
					'rid' => $rid,
					'memo2' => trim($_GPC['memo2']),					
				);
				pdo_insert('bmfloor', $insert);
			}
		}				
		
		if (isset($_GPC['password'])) {
			$sql = "SELECT id FROM " . tablename('bmfloor') . " WHERE `rid`=:rid";
			$row = pdo_fetch($sql, array(':rid'=>$rid));
			if ($row) {
				$update = array(
					'password' => trim($_GPC['password']),
				);
				pdo_update('bmfloor', $update, array('id' => $row['id']));
			} else {
				$insert = array(
					'rid' => $rid,
					'password' => trim($_GPC['password']),					
				);
				pdo_insert('bmfloor', $insert);
			}
		}

		if (isset($_GPC['share_keyword'])) {
			$sql = "SELECT id FROM " . tablename('bmfloor') . " WHERE `rid`=:rid";
			$row = pdo_fetch($sql, array(':rid'=>$rid));
			if ($row) {
				$update = array(
					'share_keyword' => trim($_GPC['share_keyword']),
				);
				pdo_update('bmfloor', $update, array('id' => $row['id']));
			} else {
				$insert = array(
					'rid' => $rid,
					'share_keyword' => trim($_GPC['share_keyword']),					
				);
				pdo_insert('bmfloor', $insert);
			}
		}	
		
		if (isset($_GPC['share_logo'])) {
			$sql = "SELECT id FROM " . tablename('bmfloor') . " WHERE `rid`=:rid";
			$row = pdo_fetch($sql, array(':rid'=>$rid));
			if ($row) {
				$update = array(
					'share_logo' => trim($_GPC['share_logo']),
				);
				pdo_update('bmfloor', $update, array('id' => $row['id']));
			} else {
				$insert = array(
					'rid' => $rid,
					'share_logo' => trim($_GPC['share_logo']),					
				);
				pdo_insert('bmfloor', $insert);
			}
		}

		if (isset($_GPC['share_memo'])) {
			$sql = "SELECT id FROM " . tablename('bmfloor') . " WHERE `rid`=:rid";
			$row = pdo_fetch($sql, array(':rid'=>$rid));
			if ($row) {
				$update = array(
					'share_memo' => trim($_GPC['share_memo']),
				);
				pdo_update('bmfloor', $update, array('id' => $row['id']));
			} else {
				$insert = array(
					'rid' => $rid,
					'share_memo' => trim($_GPC['share_memo']),					
				);
				pdo_insert('bmfloor', $insert);
			}
		}
		
		if (isset($_GPC['share_statement'])) {
			$sql = "SELECT id FROM " . tablename('bmfloor') . " WHERE `rid`=:rid";
			$row = pdo_fetch($sql, array(':rid'=>$rid));
			if ($row) {
				$update = array(
					'share_statement' => trim($_GPC['share_statement']),
				);
				pdo_update('bmfloor', $update, array('id' => $row['id']));
			} else {
				$insert = array(
					'rid' => $rid,
					'share_statement' => trim($_GPC['share_statement']),					
				);
				pdo_insert('bmfloor', $insert);
			}
		}	
		
		if (isset($_GPC['share_url'])) {
			$sql = "SELECT id FROM " . tablename('bmfloor') . " WHERE `rid`=:rid";
			$row = pdo_fetch($sql, array(':rid'=>$rid));
			if ($row) {
				$update = array(
					'share_url' => trim($_GPC['share_url']),
				);
				pdo_update('bmfloor', $update, array('id' => $row['id']));
			} else {
				$insert = array(
					'rid' => $rid,
					'share_url' => trim($_GPC['share_url']),					
				);
				pdo_insert('bmfloor', $insert);
			}
		}							

		if (isset($_GPC['share_point'])) {
			$sql = "SELECT id FROM " . tablename('bmfloor') . " WHERE `rid`=:rid";
			$row = pdo_fetch($sql, array(':rid'=>$rid));
			if ($row) {
				$update = array(
					'share_point' => intval($_GPC['share_point']),
				);
				pdo_update('bmfloor', $update, array('id' => $row['id']));
			} else {
				$insert = array(
					'rid' => $rid,
					'share_point' => intval($_GPC['share_point']),					
				);
				pdo_insert('bmfloor', $insert);
			}
		}	

		if (isset($_GPC['adv_url'])) {
			$sql = "SELECT id FROM " . tablename('bmfloor') . " WHERE `rid`=:rid";
			$row = pdo_fetch($sql, array(':rid'=>$rid));
			if ($row) {
				$update = array(
					'adv_url' => trim($_GPC['adv_url']),
				);
				pdo_update('bmfloor', $update, array('id' => $row['id']));
			} else {
				$insert = array(
					'rid' => $rid,
					'adv_url' => trim($_GPC['adv_url']),					
				);
				pdo_insert('bmfloor', $insert);
			}
		}						

		if (!empty($_GPC['award-title'])) {
			foreach ($_GPC['award-title'] as $index => $title) {
				if (empty($title)) {
					continue;
				}
				$update = array(
					'title' => $title,
					'description' => $_GPC['award-description'][$index],
					'floor' => $_GPC['award-floor'][$index],
					'from_user' => $_GPC['award-from_user'][$index],					
				);
				
				pdo_update($this->tablename, $update, array('id' => $index));
			}
		}

		if (!empty($_GPC['award-title-new'])) {
			foreach ($_GPC['award-title-new'] as $index => $title) {
				if (empty($title)) {
					continue;
				}
				$insert = array(
					'rid' => $rid,
					'title' => $title,
					'description' => $_GPC['award-description-new'][$index],
					'floor' => $_GPC['award-floor-new'][$index],
					'dateline' => $_W['timestamp'],
					'from_user' => $_GPC['award-from_user-new'][$index],					
				);

				pdo_insert($this->tablename, $insert);
			}
		}
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		return true;
	}

	public function doDelete() {
		global $_W,$_GPC;
		$id = intval($_GPC['id']);
		$sql = "SELECT id FROM " . tablename($this->tablename) . " WHERE `id`=:id";
		$row = pdo_fetch($sql, array(':id'=>$id));
		if (empty($row)) {
			message('抱歉，奖品不存在或是已经被删除！', '', 'error');
		}
		if (pdo_delete($this->tablename, array('id' => $id))) {
			message('删除奖品成功', '', 'success');
		}
	}

	private function _table_exist($tablename) {
		$exist = false;
		$tables = pdo_fetchall('SHOW TABLES');
		if ($tables) {
			foreach ($tables as $table) {
				$table = array_values($table);
				if ($table[0] == $tablename) {
					$exist = true;	
					break;
				}
			}	
		}

		return $exist;
	}
}
