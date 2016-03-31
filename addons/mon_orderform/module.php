<?php
/**
 *
 *
 * @author  codeMonkey
 * qq:631872807
 * @url
 */
defined('IN_IA') or exit('Access Denied');

define("MON_ORDER", "mon_orderform");
define("MON_ORDER_RES", "../addons/" . MON_ORDER . "/");
require_once IA_ROOT . "/addons/" . MON_ORDER . "/dbutil.class.php";
require_once IA_ROOT . "/addons/" . MON_ORDER . "/monUtil.class.php";

class Mon_OrderformModule extends WeModule
{

	public $weid;

	public function __construct()
	{
		global $_W;
		$this->weid = IMS_VERSION < 0.6 ? $_W['weid'] : $_W['uniacid'];
	}

	public function fieldsFormDisplay($rid = 0)
	{
		global $_W;

		if (!empty($rid)) {
			$reply = DBUtil::findUnique(DBUtil::$TABLE_ORDER_FORM, array(":rid" => $rid));
		}

		load()->func('tpl');
		include $this->template('form');


	}

	public function fieldsFormValidate($rid = 0)
	{
		global $_GPC, $_W;


		return '';
	}

	public function fieldsFormSubmit($rid)
	{
		global $_GPC;
		$fid = $_GPC['fid'];

		$data = array(
			'rid' => $rid,
			'weid' => $this->weid,
			'oname' => $_GPC['oname'],
			'pname' => $_GPC['pname'],
			'odesc' => htmlspecialchars_decode($_GPC['odesc']),
			'p_desc' => htmlspecialchars_decode($_GPC['p_desc']),
			'p_tel' => $_GPC['p_tel'],
            'lng' => $_GPC['lng'],
			'lat' => $_GPC['lat'],
			'p_title_pg' => $_GPC['p_title_pg'],
			'p_titile_url' => $_GPC['p_titile_url'],
			'location_p' => $_GPC['location_p'],
			'location_c' => $_GPC['location_c'],
			'location_a' => $_GPC['location_a'],
		    'address' => $_GPC['address'],
			'follow_url' => $_GPC['follow_url'],
			'copyright' => $_GPC['copyright'],
			'new_title' => $_GPC['new_title'],
			'new_icon' => $_GPC['new_icon'],
			'new_content' => $_GPC['new_content'],
			'share_title' => $_GPC['share_title'],
			'share_icon' => $_GPC['share_icon'],
			'share_content' => $_GPC['share_content'],
			'emailenable' => $_GPC['emailenable'],
			'email' => $_GPC['email'],
			'updatetime' => TIMESTAMP

		);

		if (empty($fid)) {
			$data['createtime'] = TIMESTAMP;
			DBUtil::create(DBUtil::$TABLE_ORDER_FORM, $data);
		} else {
			DBUtil::updateById(DBUtil::$TABLE_ORDER_FORM, $data, $fid);
		}



		return true;
	}

	public function ruleDeleted($rid) {
		$form = DBUtil::findUnique(DBUtil::$TABLE_ORDER_FORM, array(":rid" => $rid));
		pdo_delete(DBUtil::$TABLE_ORDER_ITEM, array("fid" => $form['id']));
		pdo_delete(DBUtil::$TABLE_ORDER_ORDER, array("fid" => $form['id']));
		pdo_delete(DBUtil::$TABLE_ORDER_FORM, array('id' => $form['id']));
	}


}