<?php

class DependencyChecker
{
	public function requireModule($all_granted_modules, $modulename)
	{
		return 0;
		if (is_array($modulename)) {
			message('参数错误，请调用正确的方法！');
		}
		foreach ($all_granted_modules as $m) {
			if (strtolower($m['name']) == strtolower($modulename)) {
				$exist = true;
				break;
			}
		}
		if (!$exist) {
			message("本功能依赖{$modulename}模块，请确认已经安装本模块，并确认该模块已经授权给当前公众号！");
		}
	}

	public function requireModules($all_granted_modules, $modulenames)
	{
		if (!is_array($modulenames)) {
			message('参数错误，请调用正确的方法！');
		}
		foreach ($modulenames as $modulename) {
			$this->requireModule($all_granted_modules, $modulename);
		}
	}

	public function requireMessage($allMsg, $type, $expect, $onErr)
	{
		if (strtolower($allMsg[$type]) != strtolower($expect)) {
			message($onErr . '<br>期望设置模块为:' . $expect . ' <br>当前实际模块为:' . $allMsg[$type]);
		}
	}
}