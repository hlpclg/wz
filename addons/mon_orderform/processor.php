<?php

defined('IN_IA') or exit('Access Denied');
define("MON_ORDER", "mon_orderform");
require_once IA_ROOT . "/addons/" . MON_ORDER . "/dbutil.class.php";
require_once IA_ROOT . "/addons/" . MON_ORDER . "/monUtil.class.php";
require_once IA_ROOT . "/addons/" . MON_ORDER . "/value.class.php";

class Mon_OrderformModuleProcessor extends WeModuleProcessor
{
	public function respond()
	{
		$rid = $this->rule;
		$order = pdo_fetch("select * from " . tablename(DBUtil::$TABLE_ORDER_FORM) . " where rid=:rid", array(":rid" => $rid));
		if (!empty($order)) {

			$news = array();
			$news [] = array('title' => $order['new_title'], 'description' => $order['new_content'], 'picurl' => MonUtil::getpicurl($order ['new_icon']), 'url' => $this->createMobileUrl('Index', array('fid' => $order['id'])));
			return $this->respNews($news);
		} else {
			return $this->respText("订单不不存在");
		}
		return null;
	}


}
