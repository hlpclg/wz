<?php
/**
 * 提取录音模块微站定义
 *
 * @author null
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

require_once "jssdk.php";
require_once "qiniu/io.php";
require_once "qiniu/rs.php";


class Null_getvoiceModuleSite extends WeModuleSite {

	private $jssdk;

	public function __construct(){
		global $_W;

		$appId = $_W['account']['key'];
		$appSecret = $_W['account']['secret'];
		if(empty($appId) && empty($appSecret)){
			echo '<script>alert("appId或appSecret为空\n请检查公众账号是否有使用js-sdk的权限")</script>';
			exit();
		}
		$this->jssdk = new JSSDK($appId, $appSecret);
	}

	public function doMobileSet() {
		//这个操作被定义用来呈现 功能封面
		$signPackage = $this->jssdk->GetSignPackage();

		include $this->template('index');
	}

	public function doMobileUpload(){
		require_once "qiniu.config.php";
		global $_GPC;

		$serverId = $_GPC['serverId'];

		//获取音频链接
		$fileName = substr($serverId, -10).'.amr';
		$url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->jssdk->getAccessToken().'&media_id='.$serverId;
		$body = $this->jssdk->HttpGet($url);

		// 上传至七牛
		Qiniu_SetKeys($qiniu['accessKey'], $qiniu['secretKey']);
		$putPolicy = new Qiniu_RS_PutPolicy($qiniu['bucket']);
		$upToken = $putPolicy->Token(null);
		list($ret, $err) = Qiniu_Put($upToken, $fileName, $body, null);
		if ($err !== null) {
		    var_dump($err);
		} else {
		    // var_dump($ret);
		    echo 'http://'.$qiniu['domainName'].'/'.$fileName;
		}		
		
	}

}