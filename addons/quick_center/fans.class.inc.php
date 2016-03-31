<?php

class Fans
{
	static $t_sys_fans = 'mc_mapping_fans';
	static $t_sys_member = 'mc_members';
	static $t_follow = 'quickspread_follow';
	static $select_fields = 'a.fanid, a.acid, a.uniacid, a.uid, a.openid from_user, a.openid, a.follow, a.followtime createtime, a.unfollowtime, a.tag, a.updatetime, a.groupid, b.mobile, b.groupid, b.credit1, b.credit2, b.credit3, b.credit4, b.credit5, b.createtime, b.realname, b.nickname, b.avatar, b.qq, b.vip, b.gender, b.birthyear, b.birthmonth, b.birthday, b.address, b.zipcode, b.nationality, b.resideprovince, b.residecity, b.residedist';

	function __construct()
	{
	}

	public function batchGet($weid, $conds, $key, $pindex, $psize, $fields = array('openid as from_user', 'b.nickname', 'avatar', 'credit1', 'follow', 'vip', 'credit2', 'createtime'))
	{
		$fans = array();
		$fields_str = self::$select_fields;
		$condition = '';
		if (isset($conds['nickname']) and !empty($conds['nickname'])) {
			$condition .= "OR b.nickname LIKE '%{$conds['nickname']}%' ";
		}
		if (isset($conds['mobile']) and !empty($conds['mobile'])) {
			$condition .= " OR mobile = '{$conds['mobile']}' ";
		}
		if (isset($conds['from_user']) and !empty($conds['from_user'])) {
			$condition .= " OR openid = '{$conds['from_user']}' ";
		}
		if (isset($conds['vip'])) {
			$condition .= " OR vip = " . intval($conds['vip']);
		}
		if (isset($conds['credit1'])) {
			$condition .= " OR credit1 > " . intval($conds['credit1']);
		}
		if (isset($conds['credit2'])) {
			$condition .= " OR credit2 > " . floatval($conds['credit2']);
		}
		if (isset($conds['follow'])) {
			$condition .= " OR follow = " . intval($conds['follow']);
		}
		if (!empty($condition)) {
			$condition = " AND (0 " . $condition . ")";
		} else {
			$condition = '';
		}
		$list = pdo_fetchall("SELECT {$fields_str} FROM " . tablename(self::$t_sys_fans) . " a, " . tablename('mc_members') . " b WHERE a.uniacid = :uniacid AND a.uid = b.uid {$condition} ORDER BY a.uid DESC " . " LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':uniacid' => $weid), $key);
		$total = pdo_fetchcolumn("SELECT count(*) FROM " . tablename(self::$t_sys_fans) . " a, " . tablename('mc_members') . " b WHERE a.uniacid = :uniacid AND a.uid = b.uid {$condition} ", array(':uniacid' => $weid));
		return array($list, $total);
	}

	public function batchGetByOpenids($weid, $openids, $key = null, $fields = array('from_user', 'nickname', 'avatar', 'credit1', 'follow', 'vip', 'credit2', 'createtime'))
	{
		$fans = array();
		if (!empty($openids)) {
			$openids_str = "'" . join("','", $openids) . "'";
			$fields_str = self::$select_fields;
			$fans = pdo_fetchall("SELECT {$fields_str} FROM " . tablename(self::$t_sys_fans) . " a LEFT JOIN " . tablename(self::$t_sys_member) . " b ON a.uid = b.uid WHERE a.openid IN ($openids_str) AND a.uniacid = :uniacid", array(':uniacid' => $weid), $key);
		}
		return $fans;
	}

	public function get($weid, $openid, $fields = array('nickname', 'avatar', 'credit1', 'follow', 'vip', 'credit2', 'createtime'))
	{
		return $this->fans_search_by_openid($weid, $openid, $fields);
	}

	public function fans_search_by_openid($weid, $openid, $fields = array('nickname', 'avatar', 'credit1', 'follow', 'vip', 'credit2', 'createtime'))
	{
		global $_W;
		$fans = $this->fans_search($openid, $fields);
		if (!empty($fans) and empty($fans['avatar'])) {
			$fans['avatar'] = $_W['siteroot'] . 'addons/quick_fans/images/default_head.png';
		}
		return $fans;
	}

	private function fans_search($user, $fields = array())
	{
		global $_W;
		$fields_str = self::$select_fields;
		$sql = "SELECT {$fields_str} FROM " . tablename(self::$t_sys_fans) . " a, " . tablename(self::$t_sys_member) . " b WHERE a.openid = :openid AND a.uniacid= :uniacid AND a.uid = b.uid";
		$fans = pdo_fetch($sql, array(':openid' => $user, ':uniacid' => $_W['uniacid']));
		WeUtility::logging('sql', $fans);
		return $fans;
	}

	public function update($weid, $openid, $data)
	{
		return $this->fans_update_by_openid($weid, $openid, $data);
	}

	public function fans_update_by_openid($weid, $openid, $data)
	{
		$enable_emoji_filter = true;
		if ($enable_emoji_filter and isset($data['nickname'])) {
			yload()->classs('quick_center', 'emojiutil');
			$data['nickname'] = EmojiUtil::removeEmoji($data['nickname']);
		}
		unset($data['from_user']);
		unset($data['openid']);
		$data['uniacid'] = $weid;
		load()->model('mc');
		$uid = mc_openid2uid($openid);
		$ret = pdo_update('mc_members', $data, array('uid' => $uid));
		return $ret;
	}

	public function fans_search_by_uid($weid, $uid)
	{
	}

	public function getUplevelFans($weid, $follower)
	{
		global $_W;
		$from_user = pdo_fetchcolumn("SELECT leader FROM " . tablename(self::$t_follow) . " WHERE follower=:follower AND weid=:weid LIMIT 1", array(':follower' => $follower, ':weid' => $weid));
		$fans = array('nickname' => '系统');
		if (!empty($from_user)) {
			$fans = $this->get($weid, $from_user, array('from_user', 'nickname'));
		}
		return $fans;
	}

	public function refresh($weid, $from_user, $force = false)
	{
		$userInfo = $this->fans_search_by_openid($weid, $from_user);
		if ($userInfo['follow'] == 1) {
			if ($force == true or empty($userInfo['nickname']) or empty($userInfo['avatar'])) {
				yload()->classs('quick_center', 'wechatapi');
				$_weapi = new WechatAPI();
				$info = $_weapi->getUserInfo($from_user);
				$weInfo = array('nickname' => $info['nickname'], 'gender' => $info['sex'], 'residecity' => $info['city'], 'resideprovince' => $info['province'], 'nationality' => $info['country'], 'avatar' => $info['headimgurl']);
				$this->fans_update_by_openid($weid, $from_user, $weInfo);
				$userInfo = $this->fans_search_by_openid($weid, $from_user);
			}
		}
		return $userInfo;
	}

	public function setVIP($weid, $from_user, $vip = 1)
	{
		$info = array('vip' => $vip);
		$ret = $this->fans_update_by_openid($weid, $from_user, $info);
		return $ret;
	}

	private function sysAddCredit($from_user, $credit_value, $credittype, $tag)
	{
		global $_GPC;
		load()->model('mc');
		$uid = mc_openid2uid($from_user);
		mc_credit_update($uid, 'credit' . $credittype, $credit_value, array($uid, $tag));
	}

	public function addCredit($weid, $from_user, $credit, $credittype = 1, $tag = '')
	{
		yload()->classs('quick_center', 'creditlog');
		$_creditlog = new CreditLog();
		$_creditlog->logCredit($this, $weid, $from_user, $credittype, $credit, $tag . '前');
		$ret = $this->sysAddCredit($from_user, floatval($credit), $credittype, $tag);
		$_creditlog->logCredit($this, $weid, $from_user, $credittype, $credit, $tag . '后');
		return $ret;
	}

	public function getActiveUserByTime($weid, $status, $seconds)
	{
		$now = TIMESTAMP;
		$condition = "createtime > {$now} - {$seconds} AND follow={$status} AND a.uniacid={$weid}";
		$sql = "SELECT COUNT(*) FROM " . tablename(self::$t_sys_fans) . " a, " . tablename('mc_members') . " b WHERE a.uid = b.uid  AND {$condition}";
		$total = pdo_fetchcolumn($sql);
		return $total;
	}

	public function disappear($weid, $from_user)
	{
		load()->model('mc');
		$uid = mc_openid2uid($from_user);
		pdo_delete(self::$t_sys_fans, array('uniacid' => $weid, 'openid' => $from_user));
		pdo_delete(self::$t_sys_member, array('uniacid' => $weid, 'uid' => $uid));
	}
}