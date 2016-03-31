<?php
/**
 * 特殊消息接口转发模块处理程序
 *
 * @author 华轩科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Hx_othermsgModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		load()->func('communication');
		$msgtype = $this->message['msgtype'];
		if ($msgtype == 'image') {
			$url = $this->module['config']['picurl'];
			$token = $this->module['config']['pictoken'];
			if ($this->module['config']['pic'] == 1 && !empty($url) && !empty($token)) {
				$url = $this->setsign($url,$token);
				$body = "<xml>" . PHP_EOL .
					"<ToUserName><![CDATA[{$this->message['to']}]]></ToUserName>" . PHP_EOL .
					"<FromUserName><![CDATA[{$this->message['from']}]]></FromUserName>" . PHP_EOL .
					"<CreateTime>{$this->message['time']}</CreateTime>" . PHP_EOL .
					"<MsgType><![CDATA[image]]></MsgType>" . PHP_EOL .
					"<PicUrl><![CDATA[{$this->message['picurl']}]]></PicUrl>" . PHP_EOL .
					"<MsgId>".TIMESTAMP."</MsgId>" . PHP_EOL .
					"</xml>";
			}else{
				return $this->respText('图片消息接口配置错误，进在特殊消息处理模块进行正确的配置');
			}
		}elseif ($msgtype == 'voice') {
			$url = $this->module['config']['voiceurl'];
			$token = $this->module['config']['voicetoken'];
			if ($this->module['config']['voice'] == 1 && !empty($url) && !empty($token)) {
				$url = $this->setsign($url,$token);
				$body = "<xml>" . PHP_EOL .
					"<ToUserName><![CDATA[{$this->message['to']}]]></ToUserName>" . PHP_EOL .
					"<FromUserName><![CDATA[{$this->message['from']}]]></FromUserName>" . PHP_EOL .
					"<CreateTime>{$this->message['time']}</CreateTime>" . PHP_EOL .
					"<MsgType><![CDATA[voice]]></MsgType>" . PHP_EOL .
					"<MediaId><![CDATA[{$this->message['mediaid']}]]></MediaId>" . PHP_EOL .
					"<Format><![CDATA[{$this->message['format']}]]></Format>" . PHP_EOL .
					"<MsgId>".TIMESTAMP."</MsgId>" . PHP_EOL .
					"</xml>";
			}else{
				return $this->respText('语音消息接口配置错误，进在特殊消息处理模块进行正确的配置');
			}
		}elseif ($msgtype == 'video') {
			$url = $this->module['config']['videourl'];
			$token = $this->module['config']['videotoken'];
			if ($this->module['config']['video'] == 1 && !empty($url) && !empty($token)) {
				$url = $this->setsign($url,$token);
				$body = "<xml>" . PHP_EOL .
					"<ToUserName><![CDATA[{$this->message['to']}]]></ToUserName>" . PHP_EOL .
					"<FromUserName><![CDATA[{$this->message['from']}]]></FromUserName>" . PHP_EOL .
					"<CreateTime>{$this->message['time']}</CreateTime>" . PHP_EOL .
					"<MsgType><![CDATA[video]]></MsgType>" . PHP_EOL .
					"<MediaId><![CDATA[{$this->message['mediaid']}]]></MediaId>" . PHP_EOL .
					"<ThumbMediaId><![CDATA[{$this->message['thumbmediaid']}]]></ThumbMediaId>" . PHP_EOL .
					"<MsgId>".TIMESTAMP."</MsgId>" . PHP_EOL .
					"</xml>";
			}else{
				return $this->respText('视频消息接口配置错误，进在特殊消息处理模块进行正确的配置');
			}
		}elseif ($msgtype == 'location') {
			$url = $this->module['config']['locationurl'];
			$token = $this->module['config']['locationtoken'];
			if ($this->module['config']['location'] == 1 && !empty($url) && !empty($token)) {
				$url = $this->setsign($url,$token);
				$body = "<xml>" . PHP_EOL .
					"<ToUserName><![CDATA[{$this->message['to']}]]></ToUserName>" . PHP_EOL .
					"<FromUserName><![CDATA[{$this->message['from']}]]></FromUserName>" . PHP_EOL .
					"<CreateTime>{$this->message['time']}</CreateTime>" . PHP_EOL .
					"<MsgType><![CDATA[location]]></MsgType>" . PHP_EOL .
					"<Location_X><![CDATA[{$this->message['location_x']}]]></Location_X>" . PHP_EOL .
					"<Location_Y><![CDATA[{$this->message['location_y']}]]></Location_Y>" . PHP_EOL .
					"<Scale><![CDATA[{$this->message['scale']}]]></Scale>" . PHP_EOL .
					"<Label><![CDATA[{$this->message['label']}]]></Label>" . PHP_EOL .
					"<MsgId>".TIMESTAMP."</MsgId>" . PHP_EOL .
					"</xml>";
			}else{
				return $this->respText('位置消息接口配置错误，进在特殊消息处理模块进行正确的配置');
			}
		}elseif ($msgtype == 'link') {
			$url = $this->module['config']['linkurl'];
			$token = $this->module['config']['linktoken'];
			if ($this->module['config']['link'] == 1 && !empty($url) && !empty($token)) {
				$url = $this->setsign($url,$token);
				$body = "<xml>" . PHP_EOL .
					"<ToUserName><![CDATA[{$this->message['to']}]]></ToUserName>" . PHP_EOL .
					"<FromUserName><![CDATA[{$this->message['from']}]]></FromUserName>" . PHP_EOL .
					"<CreateTime>{$this->message['time']}</CreateTime>" . PHP_EOL .
					"<MsgType><![CDATA[link]]></MsgType>" . PHP_EOL .
					"<Title><![CDATA[{$this->message['title']}]]></Title>" . PHP_EOL .
					"<Description><![CDATA[{$this->message['description']}]]></Description>" . PHP_EOL .
					"<Url><![CDATA[{$this->message['url']}]]></Url>" . PHP_EOL .
					"<MsgId>".TIMESTAMP."</MsgId>" . PHP_EOL .
					"</xml>";
			}else{
				return $this->respText('链接消息接口配置错误，进在特殊消息处理模块进行正确的配置');
			}
		}elseif ($msgtype == 'event' && $this->message['event'] == 'LOCATION') {
			$url = $this->module['config']['traceurl'];
			$token = $this->module['config']['tracetoken'];
			if ($this->module['config']['trace'] == 1 && !empty($url) && !empty($token)) {
				$url = $this->setsign($url,$token);
				$body = "<xml>" . PHP_EOL .
					"<ToUserName><![CDATA[{$this->message['to']}]]></ToUserName>" . PHP_EOL .
					"<FromUserName><![CDATA[{$this->message['from']}]]></FromUserName>" . PHP_EOL .
					"<CreateTime>{$this->message['time']}</CreateTime>" . PHP_EOL .
					"<MsgType><![CDATA[event]]></MsgType>" . PHP_EOL .
					"<Event><![CDATA[LOCATION]]></Event>" . PHP_EOL .
					"<Latitude><![CDATA[{$this->message['latitude']}]]></Latitude>" . PHP_EOL .
					"<Longitude><![CDATA[{$this->message['longitude']}]]></Longitude>" . PHP_EOL .
					"<Precision><![CDATA[{$this->message['precision']}]]></Precision>" . PHP_EOL .
					"<MsgId>".TIMESTAMP."</MsgId>" . PHP_EOL .
					"</xml>";
			}else{
				return $this->respText('上传位置消息接口配置错误，进在特殊消息处理模块进行正确的配置');
			}
		}
		$response = ihttp_request($url, $body, array('CURLOPT_HTTPHEADER' => array('Content-Type: text/xml; charset=utf-8')));
		$result = array();
		if (!is_error($response)) {
			$temp = @json_decode($response['content'], true);
			if (is_array($temp)) {
				$result = $this->buildResponse($temp);
			} else {
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
			}
			if(@stristr($result, '{begin-context}') !== false) {
				$this->beginContext(0);
				$result = str_ireplace('{begin-context}', '', $result);
			}
			if(@stristr($result, '{end-context}') !== false) {
				$this->endContext();
				$result = str_ireplace('{end-context}', '', $result);
			}
			return $result;
		} else {
			return array();
		}
	}

	private function buildResponse($data = array()) {
		$result = array();
		$result['MsgType'] = $data['type'];
		$data = $data['content'];
		
		if ($result['MsgType'] == 'text') {
			$result['Content'] = $data;
		} elseif ($result['MsgType'] == 'news') {
			$result['ArticleCount'] = $data['ArticleCount'];
			$result['Articles'] = array();
			if (!isset($data[0])) {
				$temp[0] = $data;
				$data = $temp;
			}
			foreach ($data as $row) {
				$result['Articles'][] = array(
					'Title' => $row['Title'],
					'Description' => $row['Description'],
					'PicUrl' => $row['PicUrl'],
					'Url' => $row['Url'],
					'TagName' => 'item',
				);
			}
		} elseif ($result['MsgType'] == 'music') {
			$result['Music'] = array(
				'Title'	=> $data['Title'],
				'Description' => $data['Description'],
				'MusicUrl' => $data['MusicUrl'],
				'HQMusicUrl' => $data['HQMusicUrl'],
			);
		}
		return $result;
	}

	protected function setsign($url,$token) {
		if (!strexists($url, '?')) {
			$url .= '?';
		} else {
			$url .= '&';
		}
		$sign = array(
			'timestamp' => TIMESTAMP,
			'nonce' => random(10, 1),
		);
		$signkey = array($token, $sign['timestamp'], $sign['nonce']);
		sort($signkey, SORT_STRING);
		$sign['signature'] = sha1(implode($signkey));
		$url .= http_build_query($sign, '', '&');
		return $url;
	}
}