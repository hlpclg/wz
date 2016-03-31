<?php

//微赞科技 by QQ:800083075 http://www.012wz.com/
if (!defined('IN_IA')) {
	die('Access Denied');
}
require_once 'model.php';
class TaobaoWeb extends Plugin
{
	public function __construct()
	{
		parent::__construct('taobao');
	}
	public function index()
	{
		global $_W, $_GPC;
		$category = pdo_fetchall("SELECT * FROM " . tablename('ewei_dshop_category') . " WHERE uniacid=:uniacid  ORDER BY parentid ASC, displayorder DESC", array(':uniacid' => $_W['uniacid']), 'id');
		if (!empty($category)) {
			$children = '';
			foreach ($category as $cid => $cate) {
				if (!empty($cate['parentid'])) {
					$children[$cate['parentid']][$cate['id']] = array($cate['id'], $cate['name']);
				}
			}
		}
		include $this->template('index');
	}
	public function fetch()
	{
		global $_GPC;
		set_time_limit(0);
		$ret = array();
		$url = $_GPC['url'];
		$pcate = intval($_GPC['pcate']);
		$ccate = intval($_GPC['ccate']);
		if (is_numeric($url)) {
			$itemid = $url;
		} else {
			preg_match('/id\\=(\\d+)/i', $url, $matches);
			if (isset($matches[1])) {
				$itemid = $matches[1];
			}
		}
		if (empty($itemid)) {
			die(json_encode(array("result" => 0, "error" => "未获取到 itemid!")));
		}
		$ret = $this->model->get_item_taobao($itemid, $_GPC['url'], $pcate, $ccate);
		die(json_encode($ret));
	}
}