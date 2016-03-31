<?php
/**
 *
 *
 * @author  weizan012
 * qq:800083075
 * @url
 */
defined('IN_IA') or exit('Access Denied');

define("MON_EGG", "mon_egg");
define("MON_EGG_RES", "../addons/" . MON_EGG . "/");
require_once IA_ROOT . "/addons/" . MON_EGG . "/dbutil.class.php";
require_once IA_ROOT . "/addons/" . MON_EGG . "/monUtil.class.php";

class Mon_EggModule extends WeModule
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
			$reply = DBUtil::findUnique(DBUtil::$TABLE_EGG, array(":rid" => $rid));

			$reply['starttime'] = date("Y-m-d  H:i", $reply['starttime']);
			$reply['endtime'] = date("Y-m-d  H:i", $reply['endtime']);
		}
		$prizes=pdo_fetchall("select * from ".tablename(DBUtil::$TABLE_EGG_PRIZE)." where egid=:egid order by display_order asc ",array(":egid"=>$reply['id']));
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


		$egid = $_GPC['egid'];
		$data = array(
			'rid' => $rid,
			'weid' => $this->weid,
			'title' => $_GPC['title'],
			'starttime' => strtotime($_GPC['starttime']),
			'endtime' => strtotime($_GPC['endtime']),
			'follow_url' => $_GPC['follow_url'],
			'copyright' => $_GPC['copyright'],
			'follow_btn_name' => $_GPC['follow_btn_name'],
			'follow_dlg_tip' =>$_GPC['follow_dlg_tip'],
			'new_title' => $_GPC['new_title'],
			'new_icon' => $_GPC['new_icon'],
			'new_content' => $_GPC['new_content'],
			'share_title' => $_GPC['share_title'],
			'share_icon' => $_GPC['share_icon'],
			'share_content' => $_GPC['share_content'],
			'intro' => htmlspecialchars_decode($_GPC['intro']),
			'banner_bg' => $_GPC['banner_bg'],
			'bg_img' => $_GPC['bg_img'],
			'share_bg' => $_GPC['share_bg'],
			'day_count' => $_GPC['day_count'],
			'prize_limit' => $_GPC['prize_limit'],
			'dpassword' =>$_GPC['dpassword'],
			'share_enable' =>$_GPC['share_enable'],
			'share_times' => $_GPC['share_times'],
			'share_award_count' =>$_GPC['share_award_count'],
			'music' => $_GPC['music'],
			'updatetime' => TIMESTAMP
		);

		if (empty($egid)) {
			$data['createtime'] = TIMESTAMP;
			DBUtil::create(DBUtil::$TABLE_EGG, $data);
			$egid = pdo_insertid();
		} else {
			DBUtil::updateById(DBUtil::$TABLE_EGG, $data, $egid);
		}

		$prizids = array();
		$pids = $_GPC['pids'];
		$display_orders = $_GPC['display_orders'];
		$plevels = $_GPC['plevels'];
		$pnames = $_GPC['pnames'];
		$pimgs = $_GPC['pimgs'];
		$ptypes = $_GPC['ptypes'];
		$jfs = $_GPC['jfs'];
		$pcounts = $_GPC['pcounts'];
		$pbs = $_GPC['pbs'];
		$pimgs = $_GPC['pimgs'];

		if (is_array($pids)) {
			foreach ($pids as $key => $value) {
				$value = intval($value);
				$d = array(
					"egid" => $egid,
					"plevel" => $plevels[$key],
					'display_order' => $display_orders[$key],
					'pname' => $pnames[$key],
					'pimg' => $pimgs[$key],
					'pcount' => $pcounts[$key],
					'ptype' => $ptypes[$key],
					'pb' => $pbs[$key],
					'jf' => $jfs[$key],
					"createtime" => TIMESTAMP
				);

				if (empty($value)) {
					DBUtil::create(DBUtil::$TABLE_EGG_PRIZE, $d);
					$prizids[] = pdo_insertid();
				} else {
					DBUtil::updateById(DBUtil::$TABLE_EGG_PRIZE, $d, $value);
					$prizids[] = $value;
				}

			}

			if (count($prizids) > 0) {
				pdo_query("delete from " . tablename(DBUtil::$TABLE_EGG_PRIZE) . " where egid='{$egid}' and id not in (" . implode(",", $prizids) . ")");
			} else {
				pdo_query("delete from " . tablename(DBUtil::$TABLE_EGG_PRIZE) . " where egid='{$egid}'");
			}
		}
		return true;
	}

	public function ruleDeleted($rid)
	{
		$egg = DBUtil::findUnique(DBUtil::$TABLE_EGG, array(":rid" => $rid));
		pdo_delete(DBUtil::$TABLE_EGG_USER, array("egid" => $egg['id']));
		pdo_delete(DBUtil::$TABLE_EGG_PRIZE, array("egid" => $egg['id']));
		pdo_delete(DBUtil::$TABLE_EGG_RECORD, array("egid" => $egg['id']));
		pdo_delete(DBUtil::$TABLE_EGG_SHARE, array("egid" => $egg['id']));
	}


}