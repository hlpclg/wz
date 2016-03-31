<?php
defined('IN_IA') or exit('Access Denied');
require_once(IA_ROOT . '/addons/quick_center/define.php');
require_once(IA_ROOT . '/addons/quick_center/loader.php');
require_once(IA_ROOT . '/addons/quick_center/data_template.php');

class quick_centerModuleSite extends WeModuleSite
{
	protected static $t_bind = "quick_center_module_bindings";
	protected $center;
	protected $user_config = array(array('title' => '排序', 'name' => 'displayorder', 'type' => 'text', 'required' => true, 'size' => 50), array('title' => '缩略图', 'name' => 'thumb', 'type' => 'image', 'size' => 60, 'required' => true), array('title' => '显示名称', 'name' => 'title', 'type' => 'text', 'required' => true, 'size' => 200), array('title' => '组号', 'name' => 'groupid', 'type' => 'option', 'required' => true, 'size' => 150, 'value' => array('group01' => '第一组', 'group02' => '第二组', 'group03' => '第三组', 'group04' => '第四组', 'group05' => '第五组', 'group06' => '第六组', 'group07' => '第七组', 'group08' => '第八组', 'group09' => '第九组')), array('title' => '标示符', 'name' => 'identifier', 'type' => 'text', 'required' => true, 'size' => 150), array('title' => '上一级标示符', 'name' => 'pidentifier', 'type' => 'text', 'required' => true, 'size' => 150), array('title' => '链接地址', 'name' => 'url', 'type' => 'text', 'required' => true, 'list' => false), array('title' => '模块', 'name' => 'module', 'type' => 'text', 'required' => true, 'size' => 120), array('title' => '动态内容回调', 'name' => 'callback', 'type' => 'text', 'required' => true, 'size' => 20, 'list' => false, 'size' => 100), array('title' => '多行动态内容回调', 'name' => 'rich_callback_enable', 'type' => 'option', 'required' => true, 'value' => array('0' => '不启用', '1' => '启用'), 'help' => '1为回调内容为多行，0为普通.', 'list' => false), array('title' => '是否启用', 'name' => 'enable', 'type' => 'option', 'required' => true, 'value' => array('1' => '在菜单中显示', '0' => '不在菜单中显示'), 'help' => '1为在菜单中显示，0为在菜单中隐藏.', 'size' => 20),);

	function __construct()
	{
		yload()->classs('quick_center', 'center');
		$this->center = new CenterManager();
	}

	private function tryLink()
	{
		global $_GPC, $_W, $_COOKIE;
		yload()->classs('quick_link', 'translink');
		$_link = new TransLink();
		WeUtility::logging("shareby", array('GPC' => $_GPC['shareby'], 'cookie' => $_COOKIE['shareby' . $_W['weid']], 'fans' => $_W['fans']['from_user']));
		if ($_GPC['shareby'] != $_W['fans']['from_user']) {
			$_link->link($_W['weid'], $_W['fans']);
		}
	}

	public function doMobileCenter()
	{
		global $_W, $_GPC;
		if (empty($_GPC['openid'])) {
			$from_user = $_W['fans']['from_user'];
		} else {
			$from_user = $_GPC['openid'];
		}
		$this->tryLink();
		yload()->classs('quick_center', 'fans');
		$_fans = new Fans();
		$fans = $_fans->refresh($_W['weid'], $from_user);
		$uplevelfans = $_fans->getUplevelFans($_W['weid'], $from_user);
		$groupid = empty($_GPC['groupid']) ? 1 : intval($_GPC['groupid']);
		$list = pdo_fetchall("SELECT * FROM " . tablename(self::$t_bind) . " WHERE weid=:weid AND enable=1 ORDER BY groupid, displayorder", array(':weid' => $_W['weid']));
		$list = $this->buildCallbackParam($list);
		yload()->classs('quick_center', 'menubuilder');
		$menus = MenuBuilder::build($list);
		$title = empty($this->module['config']['title']) ? $_W['account']['name'] : $this->module['config']['title'];
		$share = array();
		$share['disable'] = true;
		$vip_kv = unserialize($this->module['config']['vip']);
		$fans['vipname'] = $vip_kv[$fans['vip']];
		yload()->classs('quick_center', 'template');
		$_template = new Template($this->module['name']);
		$_W['account']['template'] = $this->getTemplateName();
		include $_template->template('center');
	}

	public function doMobileCenter2()
	{
		global $_W, $_GPC;
		$groupid = empty($_GPC['groupid']) ? 1 : intval($_GPC['groupid']);
		$list = pdo_fetchall("SELECT * FROM " . tablename(self::$t_bind) . " WHERE weid=:weid AND pidentifier = '' ORDER BY groupid, displayorder", array(':weid' => $_W['weid']));
		$list = $this->buildCallbackParam($list);
		$list = $this->buildUrlParam($list);
		$groups = $this->buildGroup($list);
		include $this->template('org.center');
	}

	public function doMobileShowSubMenu()
	{
		global $_GPC, $_W;
		$pidentifier = $_GPC['identifier'];
		$list = pdo_fetchall("SELECT * FROM " . tablename(self::$t_bind) . " WHERE weid=:weid AND pidentifier=:pid ORDER BY groupid, displayorder", array(':weid' => $_W['weid'], ':pid' => $pidentifier));
		$list = $this->buildCallbackParam($list);
		$list = $this->buildUrlParam($list);
		$groups = $this->buildGroup($list);
		$isSubMenu = true;
		include $this->template('org.center');
	}

	private function buildGroup($list)
	{
		$groups = array();
		$hash = array();
		foreach ($list as $_item) {
			$groupid = $_item['groupid'];
			if (!in_array($groupid, $hash)) {
				$hash[] = $groupid;
				$groups[$groupid] = array();
			}
			$groups[$groupid][] = $_item;
		}
		return $groups;
	}

	private function buildCallbackParam($list)
	{
		foreach ($list as &$_item) {
			$canUseInternal = true;
			$callback = htmlspecialchars_decode($_item['callback']);
			$module = $_item['module'];
			if (!empty($callback) and !empty($module)) {
				$m = WeUtility::createModuleSite($module);
				if (!empty($m)) {
					$func = "{$callback}";
					if (method_exists($m, $func)) {
						$_item['callback_str'] = $m->$func();
						$canUseInternal = false;
					}
				}
			}
			if ($canUseInternal) {
				$_item['callback_str'] = htmlspecialchars_decode($_item['callback']);
			}
		}
		return $list;
	}

	private function buildUrlParam($list)
	{
		foreach ($list as &$_item) {
			$url = $_item['url'];
			if (empty($url)) {
				$url = $this->createMobileUrl('ShowSubMenu', array('identifier' => $_item['identifier']));
				$_item['url'] = $url;
			}
		}
		return $list;
	}

	public function doWebCenter()
	{
		global $_W, $_GPC;
		$operation = empty($_GPC['op']) ? 'display' : ($_GPC['op']);
		if ($operation == 'display') {
			$level = empty($_GPC['level']) ? 1 : intval($_GPC['level']);
			$groupid = empty($_GPC['groupid']) ? 1 : intval($_GPC['groupid']);
			$list = pdo_fetchall("SELECT * FROM " . tablename(self::$t_bind) . " WHERE weid=:weid ORDER BY enable DESC, groupid, displayorder", array(':weid' => $_W['weid']));
		} else if ($operation == 'post') {
			load()->func('tpl');
			yload()->classs('quick_center', 'FormTpl');
			$id = intval($_GPC['id']);
			if (checksubmit('submit')) {
				if (empty($id)) {
					$this->center->AddItem($_GPC);
					message('新增菜单项成功', $this->createWebUrl('Center'), 'success');
				} else {
					$this->center->UpdateItem($id, $_GPC);
					message('更新成功', $this->createWebUrl('Center', array('op' => 'display')), 'success');
				}
			}
			$item = $this->center->GetItem($id);
		} else if ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$this->center->DeleteItem($id);
			message('删除成功', referer(), 'success');
		}
		$data_config = $this->user_config;
		include $this->template('center');
	}

	public function doWebEdit()
	{
		include $this->template('edit');
	}

	private function res($url)
	{
		global $_W;
		if (!preg_match('/^(http|https)/', $url)) {
			$r = $_W['attachurl'] . $url;
		} else {
			$r = $url;
		}
		return $r;
	}

	private function getTemplateName()
	{
		if (empty($this->module['config']['template'])) {
			return 'pink';
		}
		return $this->module['config']['template'];
	}
}