<?php

load()->func('file');
load()->model('cloud');
load()->func('communication');
if ($do == 'check') {
	$filetree = file_tree(IA_ROOT);
	$modify = array();
	$unknown = array();
	$lose = array();
	$pars = _cloud_build_params();
	$pars['method'] = 'application.build';
	$pars['extra'] = cloud_extra_data();
	$dat = cloud_request('http://addons.weizancms.com/gateway.php', $pars);
	$file = IA_ROOT . '/data/application.build';
	$ret = _cloud_shipping_parse($dat, $file);
	foreach ($ret['files'] as $value) {
		$clouds[$value['path']]['path'] = $value['path'];
		$clouds[$value['path']]['checksum'] = $value['checksum'];
	}

	foreach ($filetree as $filename) {
		$file = str_replace(IA_ROOT, '', $filename);
		if (!empty($clouds[$file])) {
			if (!is_file($filename) || md5_file($filename) != $clouds[$file]['checksum']) {
				$modify[] = $filename;
			}
		} else {
			if (!preg_match('/^\/addons/', $file) && !preg_match('/^\/data\/logs/', $file) && !preg_match('/^\/data\/tpl/', $file)) {
				$unknown[] = $filename;
			}
		}
	}
	foreach ($clouds as $value) {
		$cloud = IA_ROOT.$value['path'];
		if (!in_array($cloud, $filetree)) {
			$lose[] = $cloud;
		}
	}
	$count_unknown = count($unknown);
	$count_lose = count($lose);
	$count_modify = count($modify);
}
template('system/filecheck');
