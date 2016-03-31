<?php

//微赞科技 by QQ:800083075 http://www.012wz.com/
if (!defined('IN_IA')) {
	die('Access Denied');
}
class Core extends WeModuleSite
{
	public $footer = array();
	public $header = null;
	public function __construct()
	{
		check_shop_auth();
		if (is_weixin()) {
			m('member')->checkMember();
		}
	}
	public function setHeader()
	{
		global $_W, $_GPC;
		$_var_0 = m('user')->getOpenid();
		$_var_1 = m('user')->followed($_var_0);
		@session_start();
		if (!$_var_1) {
			$_var_2 = intval($_GPC['mid']);
			$_var_3 = m('common')->getSysset();
			$this->header = array('url' => $_var_3['share']['followurl']);
			$_var_4 = false;
			if (!empty($_var_2)) {
				if (!empty($_SESSION[EWEI_SHOP_PREFIX . '_shareid']) && $_SESSION[EWEI_SHOP_PREFIX . '_shareid'] == $_var_2) {
					$_var_2 = $_SESSION[EWEI_SHOP_PREFIX . '_shareid'];
				}
				$_var_5 = m('member')->getMember($_var_2);
				if (!empty($_var_5)) {
					$_SESSION[EWEI_SHOP_PREFIX . '_shareid'] = $_var_2;
					$_var_4 = true;
					$this->header['icon'] = $_var_5['avatar'];
					$this->header['text'] = '来自好友 <span>' . $_var_5['nickname'] . '</span> 的推荐';
				}
			}
			if (!$_var_4) {
				$this->header['icon'] = tomedia($_var_3['shop']['logo']);
				$this->header['text'] = '欢迎进入 <span>' . $_var_3['shop']['name'] . '</span>';
			}
		}
	}
	public function setFooter()
	{
		global $_GPC;
		$_var_2 = intval($_GPC['mid']);
		$this->footer['first'] = array('text' => '首页', 'ico' => 'home', 'url' => $this->createMobileUrl('shop'));
		$this->footer['second'] = array('text' => '分类', 'ico' => 'list', 'url' => $this->createMobileUrl('shop/category'));
		$_var_0 = m('user')->getOpenid();
		if (p('commission')) {
			$_var_3 = p('commission')->getSet();
			if (empty($_var_3['level'])) {
				return;
			}
			$_var_5 = m('member')->getMember($_var_0);
			$_var_6 = $_var_5['isagent'] == 1 && $_var_5['status'] == 1;
			if ($_GPC['do'] == 'plugin') {
				$this->footer['first'] = array('text' => '小店', 'ico' => 'home', 'url' => $this->createPluginMobileUrl('commission/myshop', array('mid' => $_var_5['id'])));
				if ($_GPC['method'] == '') {
					$this->footer['first']['text'] = '我的小店';
				}
				$this->footer['second'] = array('text' => '分销中心', 'ico' => 'sitemap', 'url' => $this->createPluginMobileUrl('commission'));
			} else {
				if (!$_var_6) {
					$this->footer['second'] = array('text' => '成为分销商', 'ico' => 'sitemap', 'url' => $this->createPluginMobileUrl('commission/register'));
				} else {
					$this->footer['second'] = array('text' => '小店', 'ico' => 'heart', 'url' => $this->createPluginMobileUrl('commission/myshop', array('mid' => $_var_5['mid'])));
				}
			}
		}
	}
	public function createMobileUrl($_var_7, $_var_8 = array(), $_var_9 = true)
	{
		global $_W, $_GPC;
		$_var_7 = explode('/', $_var_7);
		if (isset($_var_7[1])) {
			$_var_8 = array_merge(array('p' => $_var_7[1]), $_var_8);
		}
		if (empty($_var_8['mid'])) {
			$_var_2 = intval($_GPC['mid']);
			if (!empty($_var_2)) {
				$_var_8['mid'] = $_var_2;
			}
		}
		return $_W['siteroot'] . 'app/' . substr(parent::createMobileUrl($_var_7[0], $_var_8, true), 2);
	}
	public function createWebUrl($_var_7, $_var_8 = array())
	{
		global $_W;
		$_var_7 = explode('/', $_var_7);
		if (count($_var_7) > 1 && isset($_var_7[1])) {
			$_var_8 = array_merge(array('p' => $_var_7[1]), $_var_8);
		}
		return $_W['siteroot'] . 'web/' . substr(parent::createWebUrl($_var_7[0], $_var_8, true), 2);
	}
	public function createPluginMobileUrl($_var_7, $_var_8 = array())
	{
		global $_W, $_GPC;
		$_var_7 = explode('/', $_var_7);
		$_var_8 = array_merge(array('p' => $_var_7[0]), $_var_8);
		$_var_8['m'] = 'ewei_shop';
		if (isset($_var_7[1])) {
			$_var_8 = array_merge(array('method' => $_var_7[1]), $_var_8);
		}
		if (isset($_var_7[2])) {
			$_var_8 = array_merge(array('op' => $_var_7[2]), $_var_8);
		}
		if (empty($_var_8['mid'])) {
			$_var_2 = intval($_GPC['mid']);
			if (!empty($_var_2)) {
				$_var_8['mid'] = $_var_2;
			}
		}
		return $_W['siteroot'] . 'app/' . substr(parent::createMobileUrl('plugin', $_var_8, true), 2);
	}
	public function createPluginWebUrl($_var_7, $_var_8 = array())
	{
		global $_W;
		$_var_7 = explode('/', $_var_7);
		$_var_8 = array_merge(array('p' => $_var_7[0]), $_var_8);
		if (isset($_var_7[1])) {
			$_var_8 = array_merge(array('method' => $_var_7[1]), $_var_8);
		}
		if (isset($_var_7[2])) {
			$_var_8 = array_merge(array('op' => $_var_7[2]), $_var_8);
		}
		return $_W['siteroot'] . 'web/' . substr(parent::createWebUrl('plugin', $_var_8, true), 2);
	}
	public function _exec($_var_7, $_var_10 = '', $_var_11 = true)
	{
		global $_GPC;
		$_var_7 = strtolower(substr($_var_7, $_var_11 ? 5 : 8));
		$_var_12 = trim($_GPC['p']);
		empty($_var_12) && ($_var_12 = $_var_10);
		if ($_var_11) {
			$_var_13 = IA_ROOT . '/addons/ewei_shop/core/web/' . $_var_7 . '/' . $_var_12 . '.php';
		} else {
			$this->setFooter();
			$_var_13 = IA_ROOT . '/addons/ewei_shop/core/mobile/' . $_var_7 . '/' . $_var_12 . '.php';
		}
		if (!is_file($_var_13)) {
			message("未找到 控制器文件 {$_var_7}::{$_var_12} : {$_var_13}");
		}
		include $_var_13;
		die;
	}
	public function template($_var_14, $_var_15 = TEMPLATE_INCLUDEPATH)
	{
		global $_W;
		$_var_16 = strtolower($this->modulename);
		if (defined('IN_SYS')) {
			$_var_17 = IA_ROOT . "/web/themes/{$_W['template']}/{$_var_16}/{$_var_14}.html";
			$_var_18 = IA_ROOT . "/data/tpl/web/{$_W['template']}/{$_var_16}/{$_var_14}.tpl.php";
			if (!is_file($_var_17)) {
				$_var_17 = IA_ROOT . "/web/themes/default/{$_var_16}/{$_var_14}.html";
			}
			if (!is_file($_var_17)) {
				$_var_17 = IA_ROOT . "/addons/{$_var_16}/template/{$_var_14}.html";
			}
			if (!is_file($_var_17)) {
				$_var_17 = IA_ROOT . "/web/themes/{$_W['template']}/{$_var_14}.html";
			}
			if (!is_file($_var_17)) {
				$_var_17 = IA_ROOT . "/web/themes/default/{$_var_14}.html";
			}
			if (!is_file($_var_17)) {
				$_var_19 = explode('/', $_var_14);
				$_var_20 = array_slice($_var_19, 1);
				$_var_17 = IA_ROOT . "/addons/{$_var_16}/plugin/" . $_var_19[0] . '/template/' . implode('/', $_var_20) . '.html';
			}
		} else {
			$_var_21 = 'default';
			$_var_13 = IA_ROOT . '/addons/ewei_shop/data/template/shop_' . $_W['uniacid'];
			if (is_file($_var_13)) {
				$_var_21 = file_get_contents($_var_13);
				if (!is_dir(IA_ROOT . '/addons/ewei_shop/template/mobile/' . $_var_21)) {
					$_var_21 = 'default';
				}
			}
			$_var_18 = IA_ROOT . "/data/tpl/app/ewei_shop/{$_var_21}/mobile/{$_var_14}.tpl.php";
			$_var_17 = IA_ROOT . "/addons/{$_var_16}/template/mobile/{$_var_21}/{$_var_14}.html";
			if (!is_file($_var_17)) {
				$_var_17 = IA_ROOT . "/addons/{$_var_16}/template/mobile/default/{$_var_14}.html";
			}
			if (!is_file($_var_17)) {
				$_var_17 = IA_ROOT . "/app/themes/{$_W['template']}/{$_var_14}.html";
			}
			if (!is_file($_var_17)) {
				$_var_17 = IA_ROOT . "/app/themes/default/{$_var_14}.html";
			}
		}
		if (!is_file($_var_17)) {
			die("Error: template source '{$_var_14}' is not exist!");
		}
		if (DEVELOPMENT || !is_file($_var_18) || filemtime($_var_17) > filemtime($_var_18)) {
			template_compile($_var_17, $_var_18, true);
		}
		return $_var_18;
	}
	public function getUrl()
	{
		if (p('commission')) {
			$_var_3 = p('commission')->getSet();
			if (!empty($_var_3['level'])) {
				return $this->createPluginMobileUrl('commission/myshop');
			}
		}
		return $this->createMobileUrl('shop');
	}
}