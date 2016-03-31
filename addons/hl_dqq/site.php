<?php
/**
 * 打气球抽奖模块
 *
 * [皓蓝] www.weixiamen.cn 5517286
 */
defined('IN_IA') or exit('Access Denied');

class Hl_dqqModuleSite extends WeModuleSite {

	public function doWebAwardlist() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);

		if (checksubmit('delete')) {
			if (empty($_GPC['select'])) {
				message('请先选择要删除的记录', referer(), 'error');
			}
			pdo_delete('dqq_winner', " id  IN  ('".implode("','", $_GPC['select'])."')");
			message('奖品记录删除成功！', referer(), 'success');
		}
		if (!empty($_GPC['wid'])) {
			$wid = intval($_GPC['wid']);
			pdo_update('dqq_winner', array('status' => intval($_GPC['status'])), array('id' => $wid));
			message('奖品状态更改成功！', referer(), 'success');
		}

		$where = ' WHERE `a`.`rid` = :rid AND `f`.`uniacid` = :uniacid';
		$params = array(':rid' => intval($_GPC['id']), ':uniacid' => $_W['uniacid']);

		if (empty($starttime) || empty($endtime)) {
			$starttime =  strtotime('-1 month');
			$endtime = time();
		}
		if (!empty($_GPC['daterange'])) {
			$starttime = strtotime($_GPC['daterange']['start']);
			$endtime = strtotime($_GPC['daterange']['end']) + 86399;
		}

		$condition = array(
			'isregister' => array(
				'',
				" AND m.realname <> ''",
				" AND m.realname = ''",
			),
			'isaward' => array(
				'',
				" AND a.award <> ''",
				" AND a.award = ''",
			),
			'award' => array(
				'title' => " AND a.award LIKE '%{$_GPC['awardvalue']}%'",
				'description' => " AND a.description LIKE '%{$_GPC['awardvalue']}%'",
			),
			'qq' => " AND m.qq LIKE '%{$_GPC['profilevalue']}%'",
			'mobile' => " AND m.mobile LIKE '%{$_GPC['profilevalue']}%'",
			'realname' => " AND m.realname LIKE '%{$_GPC['profilevalue']}%'",
			'starttime' => " AND a.createtime >= '$starttime'",
			'endtime' => " AND a.createtime <= '$endtime'",
		);

		$where .= $condition['isregister'][$_GPC['isregister']];
		$where .= $condition['isaward'][$_GPC['isaward']];
		$where .= $condition['award'][$_GPC['award']];
		if (!empty($_GPC['profile'])) {
			$where .= $condition[$_GPC['profile']];
		}
		if (!empty($_GPC['award'])) {
			$where .= $condition[$_GPC['award']];
		}
		if (!empty($starttime)) {
			$where .= $condition['starttime'];
		}
		if (!empty($endtime)) {
			$where .= $condition['endtime'];
		}


		$join = ' FROM ' . tablename('dqq_winner') . ' AS `a` JOIN ' . tablename('mc_mapping_fans') . ' AS `f` ON
				`a`.`from_user` = `f`.`openid` JOIN ' . tablename('mc_members') . ' AS `m` ON `f`.`uid` = `m`.`uid`';

		$sql = 'SELECT COUNT(*) ' . $join . $where;
		$total = pdo_fetchcolumn($sql, $params);
		if ($total > 0) {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;

			$sql = 'SELECT `a`.*, `m`.`realname`, `m`.`mobile`, `m`.`qq` ' . $join . $where;
			$list = pdo_fetchall($sql, $params);

			$pager = pagination($total, $pindex, $psize);
		}

		if (!empty($_GPC['export'])) {
			/* 输入到CSV文件 */
			$html = "\xEF\xBB\xBF";

			/* 输出表头 */
			$filter = array(
				'realname' => '姓名',
				'mobile' => '手机',
				'qq' => 'QQ',
				'award' => '奖品',
				'createtime' => '获取时间',
			);


			foreach ($filter as $key => $value) {
				$html .= $value . "\t,";
			}
			$html .= "\n";

			foreach ($list as $key => $value) {
				foreach ($filter as $index => $title) {
					if ($index != 'createtime') {
						$html .= $value[$index] . "\t, ";
					} else {
						$html .= date('Y-m-d H:i:s', $value[$index]);
					}
				}
				$html .= "\n";
			}

			/* 输出CSV文件 */
			header("Content-type:text/csv");
			header("Content-Disposition:attachment; filename=全部数据.csv");
			echo $html;
			exit();

		}

		include $this->template('awardlist');
	}

	public function getProfileTiles() {

	}
	
	public function getHomeTiles($keyword = '') {
        global $_W;
		$urls = array();
		$list = pdo_fetchall("SELECT name, id FROM ".tablename('rule')." WHERE uniacid = '{$_W['weid']}' AND module = 'hl_dqq'".(!empty($keyword) ? " AND name LIKE '%{$keyword}%'" : ''));
		if (!empty($list)) {
			foreach ($list as $row) {
				$urls[] = array('title'=>$row['name'], 'url'=> $this->createMobileUrl('lottery', array('id' => $row['id'])));
			}
		}
		return $urls;
	}

	public function doMobileLottery() {
		global $_GPC, $_W;
		$title = '打气球送积分';
		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
		if(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false ){
		     //message('请使用微信客户端进入打气球吧!');
		}
		checkauth();
		load()->model('mc');
		mc_require($_W['member']['uid'], array('realname', 'mobile') , '需要完善资料后才能打气球.');
		$fromuser = $_W['fans']['from_user'];
		//$profile = fans_require($fromuser, array('realname', 'mobile', 'qq'), '需要完善资料后才能打气球.');
		$id = intval($_GPC['id']);
		$dqq = pdo_fetch("SELECT id, maxlottery, default_tips, rule FROM ".tablename('dqq_reply')." WHERE rid = '$id' LIMIT 1");
		if (empty($dqq)) {
			message('非法访问，请重新发送消息进入打气球页面！');
		}
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('dqq_winner')." WHERE createtime > '".strtotime(date('Y-m-d'))."' AND from_user = '$fromuser' ");
		$member = fans_search($fromuser);
		$myaward = pdo_fetchall("SELECT award, description FROM ".tablename('dqq_winner')." WHERE from_user = '{$fromuser}'  AND rid = '$id' ORDER BY createtime DESC");

		$sql = "SELECT a.award, b.realname FROM ".tablename('dqq_winner')." AS a
				  LEFT JOIN " . tablename('mc_mapping_fans')." f on f.openid = a.from_user
                  LEFT JOIN " . tablename('mc_members') . " b ON b.uid = f.uid WHERE b.mobile <> '' AND b.realname <> ''  AND a.rid = '$id' ORDER BY a.createtime DESC LIMIT 20";
		$otheraward = pdo_fetchall($sql);
		include $this->template('lottery');
	}

	public function doMobileGetAward() {
		global $_GPC, $_W;
		checkauth();
		$fromuser = $_W['fans']['from_user'];
		$id = intval($_GPC['id']);
		$dqq = pdo_fetch("SELECT id, periodlottery, maxlottery, default_tips, misscredit, hitcredit FROM ".tablename('dqq_reply')." WHERE rid = '$id' LIMIT 1");
		if (empty($dqq)) {
			message('非法访问，请重新发送消息进入打气球页面！4');
		}
		$result = array('status' => -1, 'message' => '');
		if (!empty($dqq['periodlottery'])) {
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('dqq_winner')." WHERE createtime > '".strtotime(date('Y-m-d'))."' AND from_user = '$fromuser'  AND rid = '$id'");
			$lastdate = pdo_fetchcolumn("SELECT createtime FROM ".tablename('dqq_winner')." WHERE from_user = '$fromuser'  ORDER BY createtime DESC");
			if (($total >= intval($dqq['maxlottery'])) && strtotime(date('Y-m-d')) < strtotime(date('Y-m-d', $lastdate)) + $dqq['periodlottery'] * 86400) {
				$result['message'] = '没箭啦';
				message($result, '', 'ajax');
			}
		} else {
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('dqq_winner')." WHERE createtime > '".strtotime(date('Y-m-d'))."' AND from_user = '$fromuser' ");
			if (!empty($dqq['maxlottery']) && $total >= $dqq['maxlottery']) {
				$result['message'] = '今天没箭了';
				message($result, '', 'ajax');
			}
		}
		
		$gifts = pdo_fetchall("SELECT id, probalilty FROM ".tablename('dqq_award')." WHERE rid = '$id' ORDER BY probalilty ASC");
		//计算每个礼物的概率
		$probability = 0;
		$rate = 1;
		$award = array();
		foreach ($gifts as $name => $gift){
			if (empty($gift['probalilty'])) {
				continue;
			}
			if ($gift['probalilty'] < 1) {
				$temp = explode('.', $gift['probalilty']);
				$temp = pow(10, strlen($temp[1]));
				$rate = $temp < $rate ? $rate : $temp;
			}
			$probability = $probability + $gift['probalilty'] * $rate;
			$award[] = array('id' => $gift['id'], 'probalilty' => $probability);
		}
		$all = 100 * $rate;
		if($probability < $all){
			$award[] = array('title' => '','probalilty' => $all);
		}
		mt_srand((double) microtime()*1000000);
		$rand = mt_rand(1, $all);
		foreach ($award as $key => $gift) {
			if (isset($award[$key - 1])) {
				if ($rand > $award[$key - 1]['probalilty'] && $rand <= $gift['probalilty']) {
					$awardid = $gift['id'];
					break;
				}
			} else {
				if ($rand > 0 && $rand <= $gift['probalilty']) {
					$awardid = $gift['id'];
					break;
				}
			}
		}
		
		$result['message'] = '唉，没中';
		$data = array(
			'rid' => $id,
			'from_user' => $fromuser,
			'status' => empty($gift['inkind']) ? 1 : 0,
			'createtime' => TIMESTAMP,
		);
		$credit = array(
			'rid' => $id,
			'award' => (empty($awardid) ? '未中' : '中') . '奖励积分',
			'from_user' => $fromuser,
			'status' => 3,
			'description' => (empty($awardid) ? $dqq['misscredit'] : $dqq['hitcredit']),
			'createtime' => TIMESTAMP,
		);

		if (!empty($awardid)) {
			$sql = 'SELECT * FROM ' . tablename('dqq_award') . ' WHERE `rid` = :rid AND `id` = :id';
			$params = array(':rid' => $id, ':id' => $awardid);
			$gift = pdo_fetch($sql, $params);
			if ($gift['total'] > 0) {
				$data['award'] = $gift['title'];
				$credit1 = intval($gift['get_jf']);
				load()->model('mc');
				mc_credit_update($_W['member']['uid'], "credit1", $credit1, null);
				$data['description'] = $gift['description'];
				$result['message'] = '' . $data['award'] . '！';
				$result['status'] = 0;
				pdo_update('dqq_award', array('total' => --$gift['total']), array('id' => $gift['id']));
			} else {
				$credit['description'] = $dqq['misscredit'];
				$credit['award'] = '未中奖励积分';
			}
		}
		!empty($credit['description']) && $result['message'] .= '<br />' . $credit['award'] . '：'. $credit['description'];
		$data['aid'] = $gift['id'];
		if (!empty($credit['description'])) {
			pdo_insert('dqq_winner', $credit);
		}
		pdo_insert('dqq_winner', $data);
		message($result, '', 'ajax');
	}

	public function doMobileRegister() {
		global $_GPC, $_W;
		$title = '打气球领奖登记个人信息';
		checkauth();
		if (!empty($_GPC['submit'])) {
			$data = array(
				'realname' => $_GPC['realname'],
				'mobile' => $_GPC['mobile'],
				
			);
			if (empty($data['realname'])) {
				die('<script>alert("请填写您的真实姓名！");location.reload();</script>');
			}
			if (empty($data['mobile'])) {
				die('<script>alert("请填写您的手机号码！");location.reload();</script>');
			}
			fans_update($_W['fans']['from_user'], $data);
			die('<script>alert("登记成功！");location.href = "'.$this->createMobileUrl('lottery', array('id' => $_GPC['id'])).'";</script>');
		}
		include $this->template('register');
	}
	
	public function doMobileGetMyAward() {
		global $_GPC, $_W;
		$params = $awards = array();
		$sql = 'SELECT `award`, `createtime`, `description` FROM ' . tablename('dqq_winner') . " WHERE `rid` = :rid AND `from_user` = :fromuser AND `award` <> :award
				ORDER BY `createtime` DESC";
		$params[':rid'] = intval($_GPC['id']);
		$params[':fromuser'] = $_W['fans']['from_user'];
		$params[':award'] = '';
		$awards = pdo_fetchall($sql, $params);
		if (!empty($awards)) {
			foreach ($awards as &$award) {
				$award['createtime'] = date('Y-m-d H:i', $award['createtime']);
			}
		}
		exit(json_encode($awards));
	}
	
	public function doMobileGetScore() {
		global $_GPC, $_W;
		$params = $scores = array();
		$sql = 'SELECT COUNT(*) AS `count`, `from_user` FROM ' . tablename('dqq_winner') . " WHERE `rid` = :rid AND `award` <> :award GROUP BY `from_user` 
				ORDER BY `count` DESC LIMIT 0, 10";
		$params[':rid'] = intval($_GPC['id']);
		$params[':award'] = '';
		$scores = pdo_fetchall($sql, $params, 'from_user');
		if (!empty($scores)) {
			load()->model('mc');
			$fromusers = array_keys($scores);
			$members = mc_fetch($fromusers, array('realname'));
			foreach ($scores as &$score) {
				$member = mc_fetch($score['from_user'], array('realname'));
				$score['member'] = $member['realname'];
			}
		}
		exit(json_encode($scores));
	}
}
