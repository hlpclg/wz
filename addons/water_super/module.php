<?php
defined('IN_IA') or exit('Access Denied');

class Water_superModule extends WeModule
{
	public function settingsDisplay($settings)
	{
		global $_W, $_GPC;
		if (checksubmit()) {
			$this->saveSettings($dat);
		}
		include $this->template('setting');
	}
}