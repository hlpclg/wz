<?php
/**
 * 凡筞打印机处理程序
 * @author www.zheyitianShi.Com团队
 */
defined('IN_IA') or exit('Access Denied');

class We7_fanceModuleProcessor extends WeModuleProcessor {

	public $tablename = 'fance';

	public function respond() {
		$sql = 'SELECT * FROM ' . tablename($this->tablename) . ' WHERE `rid` = :rid';
		$params = array(':rid' => $this->rule);
		$reply = pdo_fetch($sql, $params);
		$response = $this->procRemote(array('apiurl' => $reply['url'], 'token' => $reply['token']));

		if ($this->message['content'] == $reply['end_keyword']) {
			$this->endContext();
			return $this->respText('您已退出打印');
		}
		if (strexists($response['Content'], $reply['start_keyword'])) {
			$this->beginContext();
		}
		if (strexists($response['Content'], $reply['end_keyword'])) {
			$this->endContext();
		}
		return $this->respText($response['Content']);
	}

	private function procRemote($item) {
		load()->func('communication');
		if (!strexists($item['apiurl'], '?')) {
			$item['apiurl'] .= '?';
		} else {
			$item['apiurl'] .= '&';
		}

		$sign = array(
			'timestamp' => TIMESTAMP,
			'nonce' => random(10, 1),
		);
		$signkey = array($item['token'], $sign['timestamp'], $sign['nonce']);
		sort($signkey, SORT_STRING);
		$sign['signature'] = sha1(implode($signkey));
		$item['apiurl'] .= http_build_query($sign, '', '&');

		if ($this->message['msgtype'] == 'text') {
			$body = "<xml>" . PHP_EOL .
				"<ToUserName><![CDATA[{$this->message['to']}]]></ToUserName>" . PHP_EOL .
				"<FromUserName><![CDATA[{$this->message['from']}]]></FromUserName>" . PHP_EOL .
				"<CreateTime>{$this->message['time']}</CreateTime>" . PHP_EOL .
				"<MsgType><![CDATA[{$this->message['msgtype']}]]></MsgType>" . PHP_EOL .
				"<Content><![CDATA[{$this->message['content']}]]></Content>" . PHP_EOL .
				"<MsgId>".TIMESTAMP."</MsgId>" . PHP_EOL .
				"</xml>";
		} elseif ($this->message['msgtype'] == 'image') {
			$body = "<xml>" . PHP_EOL .
				"<ToUserName><![CDATA[{$this->message['to']}]]></ToUserName>" . PHP_EOL .
				"<FromUserName><![CDATA[{$this->message['from']}]]></FromUserName>" . PHP_EOL .
				"<CreateTime>{$this->message['time']}</CreateTime>" . PHP_EOL .
				"<MsgType><![CDATA[{$this->message['msgtype']}]]></MsgType>" . PHP_EOL .
				"<PicUrl><![CDATA[{$this->message['picurl']}]]></PicUrl>" . PHP_EOL .
				"<MsgId>".TIMESTAMP."</MsgId>" . PHP_EOL .
				"</xml>";
		} elseif ($this->message['msgtype'] == 'voice') {
			$body = "<xml>" . PHP_EOL .
				"<ToUserName><![CDATA[{$this->message['to']}]]></ToUserName>" . PHP_EOL .
				"<FromUserName><![CDATA[{$this->message['from']}]]></FromUserName>" . PHP_EOL .
				"<CreateTime>{$this->message['time']}</CreateTime>" . PHP_EOL .
				"<MsgType><![CDATA[{$this->message['msgtype']}]]></MsgType>" . PHP_EOL .
				"<MediaId><![CDATA[{$this->message['media_id']}]]></MediaId>" . PHP_EOL .
				"<MsgId>".TIMESTAMP."</MsgId>" . PHP_EOL .
				"</xml>";
		}

		$response = ihttp_request($item['apiurl'], $body, array('CURLOPT_HTTPHEADER' => array('Content-Type: text/xml; charset=utf-8')));
		$result = array();
		if (!is_error($response)) {
			if (!empty($response['content'])){
				$obj = @simplexml_load_string(trim($response['content']), 'SimpleXMLElement', LIBXML_NOCDATA);
				if($obj instanceof SimpleXMLElement) {
					$type = strtolower(strval($obj->MsgType));
					if($type == 'text') {
						$result = $this->respText(strval($obj->Content));
					}
					if($type == 'image') {
						$imid = strval($obj->Image->MediaId);
						$result = $this->respImage($imid);
					}
					if($type == 'voice') {
						$imid = strval($obj->Voice->MediaId);
						$result = $this->respVoice($imid);
					}
					if($type == 'video') {
						$video = array();
						$video['video'] = strval($obj->Video->MediaId);
						$video['thumb'] = strval($obj->Video->ThumbMediaId);
						$result = $this->respVideo($video);
					}
					if($type == 'music') {
						$music = array();
						$music['title'] = strval($obj->Music->Title);
						$music['description'] = strval($obj->Music->Description);
						$music['musicurl'] = strval($obj->Music->MusicUrl);
						$music['hqmusicurl'] = strval($obj->Music->HQMusicUrl);
						$result = $this->respMusic($music);
					}
					if($type == 'news') {
						$news = array();
						foreach($obj->Articles->item as $item) {
							$news[] = array(
								'title' => strval($item->Title),
								'description' => strval($item->Description),
								'picurl' => strval($item->PicUrl),
								'url' => strval($item->Url)
							);
						}
						$result = $this->respNews($news);
					}
				}
			}
			return $result;
		} else {
			return array();
		}
	}


}