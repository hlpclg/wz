<?php
defined('IN_IA') or exit('Access Denied');

class WechatUtil
{
	public static function curl_file_get_contents($durl, $timeout = 20)
	{
		$header = -1;
		return self::curl_file_get_contents_with_header($durl, $header, $timeout);
	}

	public static function curl_file_get_contents_with_header($durl, &$header, $timeout = 20)
	{
		$r = null;
		$timeout = (($timeout < 4) ? 4 : $timeout);
		if (function_exists('curl_init') && function_exists('curl_exec')) {
			WeUtility::logging("using curl");
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $durl);
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			if ($header >= 0) curl_setopt($ch, CURLOPT_HEADER, true);
			$r = curl_exec($ch);
			if ($header >= 0) {
				$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
				$header = substr($r, 0, $headerSize);
				$body = substr($r, $headerSize);
			} else {
				$header = null;
				$body = $r;
			}
			curl_close($ch);
		}
		return $body;
	}

	public static function is_image_expired($header)
	{
		$expired = false;
		if (strpos($header, "X-ErrNo") !== false) {
			$expired = true;
		}
		return $expired;
	}

	public static function fsock_http_request($url, $timeout = 30)
	{
		$parsed = parse_url($url);
		$host = $parsed['host'];
		$path = $parsed['path'] . '?' . $parsed['query'];
		$cookie = '';
		$fp = fsockopen($host, 80, $errno, $errstr, $timeout);
		WeUtility::logging('fsockopen', array($url, $errno, $errstr, $fp));
		if (!$fp) {
			return -1;
		}
		$out = "GET " . $path . " HTTP/1.1\r\n";
		$out .= "Host: " . $host . "\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cookie: " . $cookie . "\r\n\r\n";
		fwrite($fp, $out);
		fclose($fp);
	}

	public static function http_request($url, $post = '', $extra = array(), $timeout = 60000)
	{
		$timeout = intval($timeout / 1000);
		$timeout = (0 == $timeout) ? 1 : $timeout;
		$urlset = parse_url($url);
		if (empty($urlset['path'])) {
			$urlset['path'] = '/';
		}
		if (!empty($urlset['query'])) {
			$urlset['query'] = "?{$urlset['query']}";
		}
		if (empty($urlset['port'])) {
			$urlset['port'] = $urlset['scheme'] == 'https' ? '443' : '80';
		}
		if (strexists($url, 'https://') && !extension_loaded('openssl')) {
			if (!extension_loaded("openssl")) {
				message('请开启您PHP环境的openssl');
			}
		}
		if (function_exists('curl_init') && function_exists('curl_exec')) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $urlset['scheme'] . '://' . $urlset['host'] . ($urlset['port'] == '80' ? '' : ':' . $urlset['port']) . $urlset['path'] . $urlset['query']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			if ($post) {
				curl_setopt($ch, CURLOPT_POST, 1);
				if (is_array($post)) {
					$filepost = false;
					foreach ($post as $name => $value) {
						if (substr($value, 0, 1) == '@') {
							$filepost = true;
							$post[$name] = class_exists('CURLFile', false) ? new CURLFile(substr($value, 1)) : $value;
							break;
						}
					}
					if (!$filepost) {
						$post = http_build_query($post);
					}
				}
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			}
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSLVERSION, 1);
			if (defined('CURL_SSLVERSION_TLSv1')) {
				curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
			}
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1');
			if (!empty($extra) && is_array($extra)) {
				$headers = array();
				foreach ($extra as $opt => $value) {
					if (strexists($opt, 'CURLOPT_')) {
						curl_setopt($ch, constant($opt), $value);
					} elseif (is_numeric($opt)) {
						curl_setopt($ch, $opt, $value);
					} else {
						$headers[] = "{$opt}: {$value}";
					}
				}
				if (!empty($headers)) {
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				}
			}
			$data = curl_exec($ch);
			$status = curl_getinfo($ch);
			$errno = curl_errno($ch);
			$error = curl_error($ch);
			curl_close($ch);
			if ($errno || empty($data)) {
				return error(1, $error);
			} else {
				load()->func('communication');
				return ihttp_response_parse($data);
			}
		}
		$method = empty($post) ? 'GET' : 'POST';
		$fdata = "{$method} {$urlset['path']}{$urlset['query']} HTTP/1.1\r\n";
		$fdata .= "Host: {$urlset['host']}\r\n";
		if (function_exists('gzdecode')) {
			$fdata .= "Accept-Encoding: gzip, deflate\r\n";
		}
		$fdata .= "Connection: close\r\n";
		if (!empty($extra) && is_array($extra)) {
			foreach ($extra as $opt => $value) {
				if (!strexists($opt, 'CURLOPT_')) {
					$fdata .= "{$opt}: {$value}\r\n";
				}
			}
		}
		$body = '';
		if ($post) {
			if (is_array($post)) {
				$body = http_build_query($post);
			} else {
				$body = urlencode($post);
			}
			$fdata .= 'Content-Length: ' . strlen($body) . "\r\n\r\n{$body}";
		} else {
			$fdata .= "\r\n";
		}
		if ($urlset['scheme'] == 'https') {
			$fp = fsockopen('ssl://' . $urlset['host'], $urlset['port'], $errno, $error);
		} else {
			$fp = fsockopen($urlset['host'], $urlset['port'], $errno, $error);
		}
		stream_set_blocking($fp, true);
		stream_set_timeout($fp, $timeout);
		if (!$fp) {
			return error(1, $error);
		} else {
			fwrite($fp, $fdata);
			$content = '';
			while (!feof($fp)) $content .= fgets($fp, 512);
			fclose($fp);
			load()->func('communication');
			return ihttp_response_parse($content, true);
		}
	}

	private static function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
	{
		static $recursive_counter = 0;
		if (++$recursive_counter > 1000) {
			die('possible deep recursion attack');
		}
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				self::arrayRecursive($array[$key], $function, $apply_to_keys_also);
			} else {
				$array[$key] = $function($value);
			}
			if ($apply_to_keys_also && is_string($key)) {
				$new_key = $function($key);
				if ($new_key != $key) {
					$array[$new_key] = $array[$key];
					unset($array[$key]);
				}
			}
		}
		$recursive_counter--;
	}

	public static function json_encode($array)
	{
		self::arrayRecursive($array, 'urlencode', true);
		$json = json_encode($array);
		return urldecode($json);
	}

	public static function saveFile($file_url, $save_as_filename)
	{
		$data = file_get_contents($file_url);
		$fp = @fopen($save_as_filename, "w");
		@fwrite($fp, $data);
		fclose($fp);
		return $save_as_filename;
	}

	public static function createMobileUrl($do, $modulename, $query = array(), $noredirect = false)
	{
		global $_W;
		$query['m'] = strtolower($modulename);
		return self::murl('entry/module/' . $do, $query, $noredirect);
	}

	public static function murl($segment, $params = array(), $noredirect = false)
	{
		global $_W;
		list($controller, $action, $do) = explode('/', $segment);
		$url = './app/index.php?i=' . $_W['uniacid'] . '&';
		if (!empty($controller)) {
			$url .= "c={$controller}&";
		}
		if (!empty($action)) {
			$url .= "a={$action}&";
		}
		if (!empty($do)) {
			$url .= "do={$do}&";
		}
		if (!empty($params)) {
			$queryString = http_build_query($params, '', '&');
			$url .= $queryString;
			if ($noredirect === false) {
				$url .= '&wxref=mp.weixin.qq.com#wechat_redirect';
			}
		}
		return $_W['siteroot'] . $url;
	}

	public static function format_date($time)
	{
		$t = time() - $time;
		$f = array('31536000' => '年', '2592000' => '个月', '604800' => '星期', '86400' => '天', '3600' => '小时', '60' => '分钟', '1' => '秒');
		if ($time <= 0) {
			return "无";
		} else if ($t < 0) {
			return "未来";
		}
		foreach ($f as $k => $v) {
			if (0 != $c = floor($t / (int)$k)) {
				return $c . $v . '前';
			}
		}
	}
}