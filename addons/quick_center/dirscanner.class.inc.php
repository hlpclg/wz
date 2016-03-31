<?php

class DirScanner
{
	public function scan($prefix_dir)
	{
		$templates = array();
		$path = IA_ROOT . "/addons/" . $prefix_dir;
		if (is_dir($path)) {
			if ($handle = opendir($path)) {
				while (false !== ($dir = readdir($handle))) {
					if ($dir != '.' && $dir != '..') {
						$templates[] = $dir;
					}
				}
			}
		}
		return $templates;
	}
}