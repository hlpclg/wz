<?php /*折翼天使资源社区 www.zheyitianshi.com*/ 
if(file_exists(INC_PATH . 'core/qiniu/io.php')){
	require_once INC_PATH . 'core/qiniu/io.php';
}
if(file_exists(INC_PATH . 'core/qiniu/rs.php')){
	require_once INC_PATH . 'core/qiniu/rs.php';
}

function save_media($file) {
	$set = array(
			'qiniu' => false
	);
	$set = getSet();
	if ($set['admin']['allow'] == 1) {
		$set = $set['user'];
	} else {
		$set = $set['admin'];
	}
	if (!empty($set['upload'])) {
		$set['qiniu'] = true;
		if (strexists($file, $set['url'])) {
			return $file;
		}
		$url = save(tomedia($file) , $set);
		if (empty($url)) {
			return $file;
		}
		return $url;
	}
	return $file;
}

function check_remote_file_exists($url){
	$h = curl_init($url);
	curl_setopt($h, CURLOPT_NOBODY, true);
	$re = curl_exec($h);
	$return = false;
	if ($re !== false)
	{
		$info = curl_getinfo($h, CURLINFO_HTTP_CODE);
		if ($info == 200)
		{
			$return = true;
		}
	}
	curl_close($h);
	return $return;
}

function save($url, $user){
	set_time_limit(0);
	if (empty($url)){
		return '';
	}
	$ex = strrchr($url, '.');
	if ($ex != '.jpeg' && $ex != '.gif' && $ex != '.jpg' && $ex != '.png'){
		return "";
	}
	$rand = random(30) . $ex;
	if (!check_remote_file_exists($url)){
		return "";
	}
	$file = @file_get_contents($url);
	$bucket = $user['bucket'] . ':' . $rand;
	$access_key = $user['access_key'];
	$secret_key = $user['secret_key'];
	Qiniu_SetKeys($access_key, $secret_key);
	$qiniu = new Qiniu_RS_PutPolicy($bucket);
	$token = $qiniu->Token(null);
	$putextra = new Qiniu_PutExtra();
	$putextra->Crc32 = 1;
	list($list1, $list2) = Qiniu_Put($token, $rand, $file, $putextra);
	if (!empty($list2)){
		return "";
	}
	return 'http://' . trim($user['url']) . '/' . $list1['key'];
}

function getConfig()
{
	$user = array();
	$set = getSet();
	if ($set['admin']['allow'] == 1){
		if (isset($set['user']) && is_array($set['user'])){
			$user = $set['user'];
		}
	}else{
		if (isset($set['admin']) && is_array($set['admin'])){
			$user = $set['admin'];
		}
	}
	if (!empty($user['access_key']) && !empty($user['secret_key']) && !empty($user['bucket']) && !empty($user['url'])){
		return $user;
	}
	return false;
}