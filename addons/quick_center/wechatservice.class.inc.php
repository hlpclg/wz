<?php

class WechatService
{
	private $cookiename = "xiaochu_openid";
	private $mn;

	function __construct($mn)
	{
		$this->mn = $mn;
	}

	public function getUserOpenDetail()
	{
		global $_W, $_GPC;
		if (isset($_GPC[$this->cookiename]) and !empty($_GPC[$this->cookiename])) {
			$info = unserialize(base64_decode($_GPC[$this->cookiename]));
			return $info;
		}
		if (empty($_GPC[$this->cookiename])) {
			$account = $this->getAccount();
			if (empty($account['key']) or empty($account)) {
				return array('openid' => $_W['fans']['from_user'], 'nickname' => '路人甲');
			}
			$callback = urlencode($_W['siteroot'] . $this->createMobileUrl('oauth') . '&query=' . base64_encode($_SERVER['QUERY_STRING']));
			$state = md5(base64_encode($_SERVER['QUERY_STRING']));
			$forward = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$account['key']}&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state={$state}#wechat_redirect";
			header('location: ' . $forward);
			exit();
		}
		return null;
	}

	public function getUserOpenID()
	{
		global $_W, $_GPC;
		if (isset($_GPC[$this->cookiename]) and !empty($_GPC[$this->cookiename])) {
			$info = unserialize(base64_decode($_GPC[$this->cookiename]));
			return $info['openid'];
		}
		if (empty($_GPC[$this->cookiename])) {
			$account = $this->getAccount();
			if (empty($account['key']) or empty($account)) {
				return $_W['fans']['from_user'];
			}
			$callback = urlencode($_W['siteroot'] . $this->createMobileUrl('oauth') . '&query=' . base64_encode($_SERVER['QUERY_STRING']));
			$state = md5(base64_encode($_SERVER['QUERY_STRING']));
			$forward = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$account['key']}&redirect_uri={$callback}&response_type=code&scope=snsapi_base&state={$state}#wechat_redirect";
			header('location: ' . $forward);
			exit();
		}
		return null;
	}

	public function forceOpenInWechat($callback)
	{
		global $_W, $_GPC;
		return 0;
		if (empty($_GPC['code']) or empty($_GPC['state']) or $_GPC['state'] != $this->cookiename) {
			if (isset($_GPC[$this->cookiename]) and !empty($_GPC[$this->cookiename])) {
				$info = unserialize(base64_decode($_GPC[$this->cookiename]));
				return;
			}
			if (empty($_GPC[$this->cookiename])) {
				$account = $this->getAccount();
				if (empty($account['key']) or empty($account)) {
					message('invalid account key', '', 'error');
				}
				$state = $this->cookiename;
				$callback = urlencode($callback);
				$forward = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$account['key']}&redirect_uri={$callback}&response_type=code&scope=snsapi_base&state={$state}#wechat_redirect";
				header('location: ' . $forward);
				exit();
			}
		}
	}

	private function getAccount()
	{
		global $_W;
		$account = $_W['account'];
		if (empty($account)) {
			$wechat = pdo_fetch("SELECT * FROM " . tablename('wechats') . " WHERE weid = '{$_W['weid']}'");
			if (!empty($wechat)) {
				if (empty($account['key']) || empty($account['secret'])) {
					$account['key'] = trim($wechat['key']);
					$account['secret'] = trim($wechat['secret']);
				}
			}
		}
		if ($account['level'] != 2) {
			$cfg = $this->module['config'];
			$account['key'] = trim($cfg['key']);
			$account['secret'] = trim($cfg['secret']);
		}
		if ($account['key'] && $account['secret']) {
			return $account;
		}
		return null;
	}

	public function doMobileOAuth()
	{
		global $_W, $_GPC;
		$account = $this->getAccount();
		$code = trim($_GPC['code']);
		if (!empty($code)) {
			$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$account['key']}&secret={$account['secret']}&code={$code}&grant_type=authorization_code";
			WeUtility::logging('trace', 'url:' . $url);
			load()->func('communication');
			$ret = ihttp_get($url);
			if (!is_error($ret)) {
				$auth = @json_decode($ret['content'], true);
				if (is_array($auth) && !empty($auth['openid'])) {
					$row = array();
					$row['weid'] = $_W['weid'];
					$row['openid'] = $auth['openid'];
					$row['from_user'] = $auth['openid'];
					if ($auth['scope'] == 'snsapi_userinfo') {
						$user = $this->getFansInfo($auth['access_token'], $auth['openid']);
						$row['nickname'] = $user['nickname'];
						$row['avatar'] = $user['headimgurl'];
					}
					WeUtility::logging('trace', 'user:' . json_encode($row));
					isetcookie($this->cookiename, base64_encode(serialize($row)), 86400 * 7 * 7);
					$forward = base64_decode($_GPC['query']);
					header('location: ' . $_W['siteroot'] . 'mobile.php?' . $forward . '&' . $this->cookiename . '=' . $auth['openid'] . '&wxref=mp.weixin.qq.com#wechat_redirect');
					exit;
				} else {
					message($ret['content']);
				}
			}
		}
		message('微信授权失败!');
	}

	private function getFansInfo($access_token, $openid)
	{
		$userinfo_url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
		load()->func('communication');
		$user = ihttp_get($userinfo_url);
		$user = @json_decode($user['content'], true);
		if ($user['errcode']) {
			$filename = IA_ROOT . '/data/logs/getfansinfo.log';
			mkdirs(dirname($filename));
			$content = date('Y-m-d H:i:s') . "-------------------\n";
			$content .= "errcode:" . $user['errcode'] . "\n";
			$content .= "errmsg:" . $user['errmsg'] . "\n";
			$content .= "\n";
			$fp = fopen($filename, 'a+');
			fwrite($fp, $content);
			fclose($fp);
			echo '<h2>' . $user['errmsg'] . '</h2>';
			exit();
		}
		return $user;
	}

	private function createMobileUrl($m)
	{
		return murl('entry/module/' . $m, array('m' => $this->mn));
	}
} ?>