<?php

class WechatSetting
{
	private static $t_wechat = 'wechats_modules';
	private static $t_module = 'uni_account_modules';

	public function get($weid, $modulename)
	{
		$settings = null;
		$result = pdo_fetchcolumn("SELECT settings FROM " . tablename(self::$t_module) . " WHERE uniacid= :weid AND module= :name LIMIT 1", array(':name' => $modulename, ':weid' => $weid));
		if (!empty($result)) {
			$settings = iunserializer($result);
		}
		return $settings;
	}
}