<?php

/**
 * 砸蛋抽奖模块
 *
 * [WeiZan System] Copyright (c) 2013 012wz.com
 */
defined('IN_IA') or exit('Access Denied');

class We7_eggModuleSite extends WeModuleSite {

	public function doWebFormDisplay() {
		global $_W, $_GPC;
		$result = array('error' => 0, 'message' => '', 'content' => '');
		$result['content']['id'] = $GLOBALS['id'] = 'add-row-news-' . $_W['timestamp'];
		$result['content']['html'] = $this->template('item', TEMPLATE_FETCH);
		exit(json_encode($result));
	}

	public function doWebAwardlist() {
		global $_GPC, $_W;
		load()->func('tpl');
		$id = intval($_GPC['id']);

		if (checksubmit('delete')) {
			if (is_array($_GPC['select'])) {
				pdo_delete('egg_winner', " id  IN  ('" . implode("','", $_GPC['select']) . "')");
				message('删除成功！', $this->createWebUrl('awardlist', array('id' => $id, 'page' => $_GPC['page'])));
			}
			message('未选择任何记录，无法删除!','','error');
		}
		if (!empty($_GPC['wid'])) {
			$wid = intval($_GPC['wid']);
			pdo_update('egg_winner', array('status' => intval($_GPC['status'])), array('id' => $wid));
			message('操作成功！', $this->createWebUrl('awardlist', array('id' => $id, 'page' => $_GPC['page'])));
		}

		$pindex = max(1, intval($_GPC['page']));
		$psize = 15;
		$where = '';
		if (empty($starttime) || empty($endtime)) {
			$starttime =  strtotime('-1 month');
			$endtime = TIMESTAMP + 86399;
		}
		if (!empty($_GPC['daterange'])) {
			$starttime = strtotime($_GPC['daterange']['start']);
			$endtime = strtotime($_GPC['daterange']['end']) + 86399;
		}
		$where = ' WHERE a.uniacid = :uniacid AND a.rid = :rid';
		$params[':uniacid'] = $_W['uniacid'];
		$params[':rid'] = $id;
		$isaward = intval($_GPC['isaward']);
		if($isaward == 1) {
			$where .= ' AND a.isaward = :isaward';
			$params[':isaward'] = 1;
		} elseif($isaward == 2) {
			$where .= ' AND a.isaward = :isaward';
			$params[':isaward'] = 0;
		}

		$profile = trim($_GPC['profile']);
		if(!empty($profile)) {
			$where .= " AND a.uid IN (SELECT uid FROM " . tablename('mc_members') . "  WHERE uniacid = {$_W['uniacid']} AND (realname LIKE '%{$profile}%' OR mobile LIKE '%{$profile}%'))";
		}

		$award = trim($_GPC['award']);
		$awardvalue = trim($_GPC['awardvalue']);
		if(!empty($award)) {
			if($award == 'title') {
				$where .= " AND a.award LIKE '%{$awardvalue}%'";
			} else {
				$where .= " AND a.description LIKE '%{$awardvalue}%'";
			}
		}
		$where .= ' AND a.createtime > :start AND a.createtime < :end';
		$params[':start'] = $starttime;
		$params[':end'] = $endtime;

		$sql = 'SELECT a.*,m.realname,m.mobile FROM ' . tablename('egg_winner') . ' AS `a` LEFT JOIN ' . tablename('mc_members') .
				' AS `m` ON `m`.`uid` = `a`.`uid`' . $where . ' ORDER BY `a`.`id` DESC';

		$sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
		$list = pdo_fetchall($sql, $params);

		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('egg_winner') . ' AS a ' . $where, $params);
		$pager = pagination($total, $pindex, $psize);

		if (checksubmit('export', true)) {
			$tableHeader = array(
					'realname' => '姓名',
					'mobile' => '手机',
					'award' => '奖品',
					'description' => '描述',
					'createtime' => '获奖时间'
			);
			$sql = 'SELECT a.*,m.realname,m.mobile FROM ' . tablename('egg_winner') . ' AS `a` LEFT JOIN ' . tablename('mc_members') .
					' AS `m` ON `m`.`uid` = `a`.`uid`' . $where . ' ORDER BY `a`.`id` DESC';
			$data = pdo_fetchall($sql, $params);
			// 输入到CSV文件
			$html = "\xEF\xBB\xBF";
			// 输出表头
			foreach ($tableHeader as $value) {
				$html .= $value . "\t ,";
			}
			$html .= "\n";
			// 输出内容
			foreach ($data as $value) {
				foreach ($tableHeader as $key => $header) {
					if ($key == 'createtime') {
						$value[$key] = date('Y-m-d H:i', $value[$key]);
					}
					if ($key == 'award' && empty($value[$key])) {
						$value[$key] = '未中奖';
					}
					if ($key == 'description') {
						$value[$key] = strip_tags($value[$key]);
					}
					$html .= $value[$key] . "\t ,";
				}
				$html .= "\n";
			}

			// 输出CSV文件
			header("Content-type:text/csv");
			header("Content-Disposition:attachment; filename=全部数据.csv");
			echo $html;
			exit();
		}
		include $this->template('awardlist');
	}

	public function doWebDelete() {
		global $_W, $_GPC;
		$id = intval($_GPC['id']);
		$sql = "SELECT id FROM " . tablename('egg_award') . " WHERE `id`=:id";
		$row = pdo_fetch($sql, array(':id' => $id));
		if (empty($row)) {
			message('抱歉，奖品不存在或是已经被删除！', '', 'error');
		}
		if (pdo_delete('egg_award', array('id' => $id))) {
			message('删除奖品成功', referer(), 'success');
		}
	}

	public function getCovers() {
		return array(
			array('title' => '第一期砸蛋', 'url' => $this->createWebUrl('first')),
		);
	}

	public function getHomeTiles() {
		global $_W;
		$urls = array();
		$list = pdo_fetchall("SELECT name, id FROM " . tablename('rule') . " WHERE uniacid = '{$_W['uniacid']}' AND module = 'we7_egg'");
		if (!empty($list)) {
			foreach ($list as $row) {
				$urls[] = array('title' => $row['name'], 'url' => $this->createMobileUrl('lottery', array('id' => $row['id'])));
			}
		}
		return $urls;
	}

	public function doMobileLottery() {
		global $_GPC, $_W;

		checkauth();
		load()->model('mc');
		mc_require($_W['member']['uid'], array('realname', 'mobile') , '需要完善资料后才能砸蛋.');

		$where = ' WHERE `rid` = :rid';
		$params = array(':rid' => intval($_GPC['id']));
		$sql = 'SELECT * FROM ' . tablename('egg_reply') . $where;
		$egg = pdo_fetch($sql, $params);

		if (empty($egg)) {
			message('非法访问，请重新发送消息进入砸蛋页面！');
		}
		if (TIMESTAMP < $egg['starttime']) {
			message('活动还没有开始！');
		}
		if (TIMESTAMP > $egg['endtime']) {
			message('活动已经结束啦！');
		}

		$where .= ' AND `uniacid` = :uniacid AND `uid` = :uid';
		$params[':uniacid'] = $_W['uniacid'];
		$params[':uid'] = $_W['member']['uid'];
		$params[':createtime'] = strtotime(date('Y-m-d'));

		// 当日砸蛋次数
		$sql = 'SELECT COUNT(*) FROM ' . tablename('egg_winner') . $where . ' AND `createtime` > :createtime';
		$total = pdo_fetchcolumn($sql, $params);

		// 会员信息
		$member = mc_fetch($_W['member']['uid'], array('realname', 'mobile'));

		// 我的奖品
		$sql = 'SELECT `award`, `description` FROM ' . tablename('egg_winner') . $where . ' ORDER BY `createtime` DESC';
		unset($params[':createtime']);
		$myAward = pdo_fetchall($sql, $params);

		// 中奖名单
		$sql = 'SELECT `award`, `realname` FROM ' . tablename('egg_winner') . ' AS `w` JOIN ' . tablename('mc_members')
				. ' AS `m` ON `w`.`uid` = `m`.`uid` WHERE `rid` = :rid ORDER BY `w`.`id` DESC LIMIT 20';
		$otherAward = pdo_fetchall($sql, array(':rid' => $params[':rid']));

		// 分享信息
		$shareTitle = empty($egg['title']) ? '砸蛋抽奖' : $egg['title'];
		$shareDesc = $egg['description'];
		$shareImage = tomedia($egg['picture']);
		include $this->template('lottery');
	}

	public function doMobileGetAward() {
		global $_GPC, $_W;
		if (empty($_W['fans']['from_user']) || empty($_W['member']['uid'])) {
			message('非法访问，请重新发送消息进入砸蛋页面！');
		}
		checkauth();
		$fromuser = $_W['fans']['from_user'];
		$uid = intval($_W['member']['uid']);
		$id = intval($_GPC['id']);

		$sql = 'SELECT `id`, `periodlottery`, `maxlottery`, `default_tips`, `misscredit`, `hitcredit` FROM '
				. tablename('egg_reply') . ' WHERE `rid` = :rid';
		$params = array(':rid' => $id);
		$egg = pdo_fetch($sql, $params);
		if (empty($egg)) {
			message('非法访问，请重新发送消息进入砸蛋页面！');
		}

		$result = array('status' => -1, 'message' => '');
		$sql = 'SELECT COUNT(*) FROM ' . tablename('egg_winner') . ' WHERE `rid` = :rid AND `uid` = :uid';
		$params[':uid'] = $uid;

		if (empty($egg['periodlottery'])) {
			$total = pdo_fetchcolumn($sql, $params);
			if ($total >= $egg['maxlottery']) {
				$result['message'] = '您已经超过最大砸蛋次数';
				message($result, '', 'ajax');
			}
		} else {
			$sql .= ' AND `createtime` > :createtime';
			$params[':createtime'] = strtotime(date('Y-m-d')) - 86400 * $egg['periodlottery'];
			$total = pdo_fetchcolumn($sql, $params);
			if ($total >= $egg['maxlottery']) {
				$sql = 'SELECT `createtime` FROM ' . tablename('egg_winner') . ' WHERE `rid` = :rid AND `uid` = :uid
						ORDER BY `createtime` DESC';
				$lastdate = pdo_fetchcolumn($sql, array(':rid' => $id, ':uid' => $uid));
				$result['message'] = '您还未到达可以再次砸蛋的时间。下次可砸时间为' . date('Y-m-d', strtotime(date('Y-m-d', $lastdate)) + $egg['periodlottery'] * 86400);
				message($result, '', 'ajax');
			}
		}

		$sql = 'SELECT * FROM ' . tablename('egg_award') . ' WHERE `rid` = :rid ORDER BY `probalilty`';
		$gifts = pdo_fetchall($sql, array(':rid' => $id));

		$awards = array();
		foreach ($gifts as $key => $gift) {
			if (empty($gift['total']) || empty($gift['probalilty'])) {
				unset($gifts[$key]);
				continue;
			}
			$gifts[$key]['random'] = mt_rand(1, 100 / $gift['probalilty']);
			if (mt_rand(1, 100 / $gift['probalilty']) == mt_rand(1, 100 / $gift['probalilty'])) {
				$awards[] = $gift;
			}
		}
		if (count($awards)>0){
			$randid = mt_rand(0, count($awards) - 1);
			$awardid = $awards[$randid];
		}

		$result['message'] = empty($egg['default_tips']) ? '很遗憾,您没能中奖！' : $egg['default_tips'];
		$credit = array(
			'rid' => $id,
			'uniacid' => $_W['uniacid'],
			'uid' => $uid,
			'from_user' => $fromuser,
			'isaward' => empty($awardid) ? 0 : 1,
			'award' => (empty($awardid) ? '未中' : '中') . '奖奖励积分',
			'description' => (empty($awardid) ? $egg['misscredit'] : $egg['hitcredit']),
			'credit' => (empty($awardid) ? $egg['misscredit'] : $egg['hitcredit']),
			'createtime' => strtotime(date('Y-m-d H:i:s')),
			'status' => 2
		);

		if (!empty($awardid)) {
			$sql = 'SELECT * FROM ' . tablename('egg_award') . ' WHERE `rid` = :rid AND `id` = :id';
			$params = array(':rid' => $id, ':id' => $awardid['id']);
			$gift = pdo_fetch($sql, $params);
			if ($gift['total'] > 0) {
				$credit['status'] = 0;
				$credit['award'] = $gift['title'];

				if (!empty($gift['inkind'])) {
					$credit['description'] = $gift['description'];
					$sql = 'UPDATE ' . tablename('egg_award') . ' SET `total` = `total` - 1 WHERE `rid` = :rid AND `id` = :id';
					pdo_query($sql, $params);
				} else {
					$gift['activation_code'] = (array)iunserializer($gift['activation_code']);
					$code = array_pop($gift['activation_code']);
					$activation_code = iserializer($gift['activation_code']);
					$sql = 'UPDATE ' . tablename('egg_award') . " SET `total` = `total` - 1, `activation_code` = '" . $activation_code .
							"' WHERE `rid` = :rid AND `id` = :id";
					pdo_query($sql, $params);
					$credit['description'] = '兑换码：' . $code . '<br /> 兑换地址：' . $gift['activation_url'];
				}

				$result['message'] = '恭喜您，得到“' . $gift['title'] . '”！';
				$result['status'] = 0;
			} else {
				$credit['description'] = $egg['misscredit'];
				$credit['award'] = '未中奖奖励积分';
			}
		}
		!empty($credit['description']) && $result['message'] .= '<br />' . $credit['award'] . '：' . $credit['description'];
		$credit['aid'] = $gift['id'];

		if(empty($awardid)) {
			$value = intval($egg['misscredit']);
			$uid = mc_openid2uid($fromuser);
			mc_credit_update($uid, 'credit1', $value, array(0, '使用砸蛋模块未中奖,赠送'.$value.'积分'));
		} else {
			$value = intval($egg['hitcredit']);
			$uid = mc_openid2uid($fromuser);
			mc_credit_update($uid, 'credit1', $value, array(0, '使用砸蛋模块中奖,赠送'.$value.'积分'));
		}

		pdo_insert('egg_winner', $credit);
		$result['myaward'] = pdo_fetchall("SELECT award, description FROM " . tablename('egg_winner') . " WHERE uid = '{$uid}' AND award <> '' AND rid = '$id' ORDER BY createtime DESC");
		message($result, '', 'ajax');
	}

	public function doMobileRegister() {
		global $_GPC, $_W;
		$title = '砸蛋领奖登记个人信息';
		if (!empty($_GPC['submit'])) {
			if (empty($_W['fans']['from_user'])) {
				message('非法访问，请重新发送消息进入砸蛋页面！');
			}
			$data = array(
				'realname' => $_GPC['realname'],
				'mobile' => $_GPC['mobile'],
				'qq' => $_GPC['qq'],
			);
			if (empty($data['realname'])) {
				die('<script>alert("请填写您的真实姓名！");location.reload();</script>');
			}
			if (empty($data['mobile'])) {
				die('<script>alert("请填写您的手机号码！");location.reload();</script>');
			}
			fans_update($_W['fans']['from_user'], $data);
			die('<script>alert("登记成功！");location.href = "' . $this->createMobileUrl('lottery', array('id' => $_GPC['id'])) . '";</script>');
		}
		include $this->template('register');
	}

}
