<?php

class CreditLog
{
	static $t_credit_log = 'quick_credit_log';

	public function get($weid, $from_user)
	{
		$ret = pdo_fetchall('SELECT * FROM ' . tablename(self::$t_credit_log) . " WHERE weid=:weid AND from_user=:from_user", array(':weid' => $weid, ':from_user' => $from_user));
		return $ret;
	}

	public function getCreditTypeName($type)
	{
		if (1 == $type) {
			return '积分';
		} else if (2 == $type) {
			return '余额';
		}
		return '';
	}

	public function logCredit($_fans, $weid, $from_user, $credittype, $delta, $tag)
	{
		$fans = $_fans->get($weid, $from_user);
		$data = array('weid' => $weid, 'from_user' => $fans['from_user'], 'nickname' => $fans['nickname'], 'credit' => $fans['credit' . $credittype], 'delta' => $delta, 'tag' => $this->getCreditTypeName($credittype) . '-' . $tag, 'createtime' => TIMESTAMP);
		$ret = pdo_insert(self::$t_credit_log, $data);
		return $ret;
	}
}