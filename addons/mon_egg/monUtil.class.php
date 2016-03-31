<?php

/**
 * Class MonUtil
 * 工具类
 */
class MonUtil
{

	public static $DEBUG = false;

	public static $IMG_BANNER_BG = 1;
	public static $IMG_BG = 2;
	public static $IMG_SHARE_BG = 3;


	/**
	 * author: www.zheyitianShi.Com科技
	 * @param $url
	 * @return string
	 */
	public static function str_murl($url)
	{
		global $_W;

		return $_W['siteroot'] . 'app' . str_replace('./', '/', $url);

	}


	/**
	 * author: weizan012 QQ:800083075
	 * 检查手机
	 */
	public static function  checkmobile()
	{

		if (!MonUtil::$DEBUG) {
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			if (strpos($user_agent, 'MicroMessenger') === false) {
				echo "本页面仅支持微信访问!非微信浏览器禁止浏览!";
				exit();
			}
		}


	}

	/**
	 * author:weizan012 QQ 800083075
	 * 获取哟规划信息
	 * @return array|mixed|stdClass
	 */
	public static function  getClientCookieUserInfo($cookieKey)
	{
		global $_GPC;
		$session = json_decode(base64_decode($_GPC[$cookieKey]), true);
		return $session;

	}


	/**
	 * author: weizan012 QQ:800083075
	 * @param $openid
	 * @param $accessToken
	 * @return unknown
	 * cookie保存用户信息
	 */
	public static function setClientCookieUserInfo($userInfo = array(), $cookieKey)
	{

		if (!empty($userInfo) && !empty($userInfo['openid'])) {
			$cookie = array();
			foreach ($userInfo as $key => $value)
				$cookie[$key] = $value;
			$session = base64_encode(json_encode($cookie));

			isetcookie($cookieKey, $session, 1 * 3600 * 1);

		} else {

			message("获取用户信息错误");
		}


	}


	public static function getpicurl($url)
	{
		global $_W;
		return $_W ['attachurl'] . $url;

	}


	public static function  emtpyMsg($obj, $msg) {
		if (empty($obj)) {
			message($msg);
		}
	}

	public static function defaultImg($img_type,$egg ='') {
		switch ($img_type) {
			//首页
			case MonUtil::$IMG_BANNER_BG:
				if (!empty($egg)&&!empty($egg['banner_bg'])) {
					return MonUtil::getpicurl($egg['banner_bg']);
				}
				$img_name = "banner.jpg";
				break;
			case MonUtil::$IMG_BG:
				if (!empty($egg)&&!empty($egg['bg_img'])) {
					return MonUtil::getpicurl($egg['bg_img']);
				}
				$img_name = "bg.jpg";
				break;
			case MonUtil::$IMG_SHARE_BG:
				if (!empty($egg)&&!empty($egg['share_bg'])) {
					return MonUtil::getpicurl($egg['share_bg']);
				}
				$img_name = "guide.png";
				break;
		}
		return "../addons/mon_egg/images/" . $img_name;

	}

	public static function exportexcel($data = array(), $title = array(), $filename = 'report')
	{
		header("Content-type:application/octet-stream");
		header("Accept-Ranges:bytes");
		header("Content-type:application/vnd.ms-excel");
		header("Content-Disposition:attachment;filename=" . $filename . ".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		//导出xls 开始
		if (!empty($title)) {
			foreach ($title as $k => $v) {
				$title[$k] = iconv("UTF-8", "GB2312", $v);
			}
			$title = implode("\t,", $title);
			echo "$title\n";
		}

		if (!empty($data)) {
			foreach ($data as $key => $val) {
				foreach ($val as $ck => $cv) {
					$data[$key][$ck] = iconv("UTF-8", "GB2312", $cv);
				}
				$data[$key] = implode("\t,", $data[$key]);

			}
			echo implode("\n", $data);
		}
	}

}