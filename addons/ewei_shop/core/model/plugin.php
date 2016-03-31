<?php
//捌零网络科技有限公司QQ2316571101
if (!defined('IN_IA')) {
    exit('Access Denied');
}
class Ewei_DShop_Plugin
{
    public function getSet($plugin = '', $key = '', $uniacid = 0)
    {
        global $_W, $_GPC;
        if (empty($uniacid)) {
            $uniacid = $_W['uniacid'];
        }
        $set = m('cache')->getArray('sysset', $uniacid);
        if (empty($set)) {
            $set = pdo_fetch("select * from " . tablename('ewei_shop_sysset') . ' where uniacid=:uniacid limit 1', array(
                ':uniacid' => $uniacid
            ));
        }
        if (empty($set)) {
            return array();
        }
        $allset = unserialize($set['sets']);
        if (empty($key)) {
            return $allset;
        }
        return $allset[$key];
    }
    public function exists($pluginName = '')
    {
        $dbplugin = pdo_fetchall('select * from ' . tablename('ewei_shop_plugin') . ' where identity=:identyty limit  1', array(
            ':identity' => $pluginName
        ));
        if (empty($dbplugin)) {
            return false;
        }
        return true;
    }
    /*public function getAll()
    {
        global $_W;
		$path = IA_ROOT . "/addons/ewei_shop/data/perm";
		if (!is_dir($path)) {
			load()->func('file');
			@mkdirs($path);
		}
		$cachefile = $path . "/plugins";
		$plugins = iunserializer(@file_get_contents($cachefile));
		if (!is_array($plugins)) {
			$plugins = pdo_fetchall('select * from ' . tablename('ewei_shop_plugin') . ' order by displayorder asc');
			file_put_contents($cachefile, iserializer($plugins));
        }
        return $plugins;
    }*/
	public function getAll()
	{
		global $_W;
		$plugins = m('cache')->getArray('plugins', 'global');
		if (empty($plugins)) {
			$plugins = pdo_fetchall('select * from ' . tablename('ewei_shop_plugin') . ' order by displayorder asc');
			m('cache')->set('plugins', $plugins, 'global');
		}
		return $plugins;
	}
	public function getCategory()
	{
		return array('biz' => array('name' => '业务类'), 'sale' => array('name' => '营销类'), 'tool' => array('name' => '工具类'), 'help' => array('name' => '辅助类'));
	}
}