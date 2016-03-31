<?php

//微赞科技 by QQ:800083075 http://www.012wz.com/
if (!defined('IN_IA')) {
	die('Access Denied');
}
require_once EWEI_DSHOP_PLUGIN . 'qiniu/qiniu/io.php';
require_once EWEI_DSHOP_PLUGIN . 'qiniu/qiniu/rs.php';
class QiniuModel extends PluginModel
{
	private function check_remote_file_exists($url)
	{
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		$result = curl_exec($curl);
		$found = false;
		if ($result !== false) {
			$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			if ($statusCode == 200) {
				$found = true;
			}
		}
		curl_close($curl);
		return $found;
	}
	public function save($url, $config)
	{
		set_time_limit(0);
		if (empty($url)) {
			return '';
		}
		$ext = strrchr($url, ".");
		if ($ext != ".jpeg" && $ext != ".gif" && $ext != ".jpg" && $ext != ".png") {
			return "";
		}
		$filename = random(30) . $ext;
		if (!$this->check_remote_file_exists($url)) {
			return "";
		}
		$contents = @file_get_contents($url);
		$storename = $filename;
		$bu = $config['bucket'] . ":" . $storename;
		$accessKey = $config['access_key'];
		$secretKey = $config['secret_key'];
		Qiniu_SetKeys($accessKey, $secretKey);
		$putPolicy = new Qiniu_RS_PutPolicy($bu);
		$upToken = $putPolicy->Token(null);
		$putExtra = new Qiniu_PutExtra();
		$putExtra->Crc32 = 1;
		list($ret, $err) = Qiniu_Put($upToken, $storename, $contents, $putExtra);
		if (!empty($err)) {
			return "";
		}
		return 'http://' . trim($config['url']) . "/" . $ret['key'];
	}
	function getConfig()
	{
		$config = array();
		$set = $this->getSet();
		if ($set['admin']['allow'] == 1) {
			if (isset($set['user']) && is_array($set['user'])) {
				$config = $set['user'];
			}
		} else {
			if (isset($set['admin']) && is_array($set['admin'])) {
				$config = $set['admin'];
			}
		}
		if (!empty($config['access_key']) && !empty($config['secret_key']) && !empty($config['bucket']) && !empty($config['url'])) {
			return $config;
		}
		return false;
	}
}