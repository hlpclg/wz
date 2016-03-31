<?php
/**
 * 微信多平台接入模块处理程序
 *
 * @author 
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Czt_zhuanfaModuleProcessor extends WeModuleProcessor {
	public function respond2() {
		$content = $this->message['content'];
		$from=$this->message['from'];
		$to=$this->message['to'];
		$type=$this->message['type'];
		$time=$this->message['time'];
		$msgid=$this->message['msgid'];

		$sql = "SELECT * FROM " . tablename('czt_zhuanfa_reply') . " WHERE `rid`=:rid";
		$r = pdo_fetch($sql, array(':rid'=>$this->rule));
		if ($r) {
			$xml=<<<eof
			<xml>
			<ToUserName><![CDATA[{$to}]]></ToUserName>
			<FromUserName><![CDATA[{$from}]]></FromUserName>
			<CreateTime>{$time}</CreateTime>
			<MsgType><![CDATA[text]]></MsgType>
			<Content><![CDATA[{$content}]]></Content>
			<MsgId>{$msgid}</MsgId>
			</xml>
eof;

			$timestamp=time();
			$nonce=mt_rand(0,10);
			$token=$r['token'];
			$tmpArr = array($token, $timestamp, $nonce);
			sort($tmpArr, SORT_STRING);
			$tmpStr = implode( $tmpArr );
			$signature = sha1( $tmpStr );
			$data=array('signature'=>$signature,'timestamp'=>$timestamp,'nonce'=>$nonce,'token'=>$token);
			$params=http_build_query($data);
			if (strripos($r['url'], '?')) {
				$params='&'.$params;
			}else{
				$params='?&'.$params;
			}
			
			$r=http_post_data($r['url'].$params,$xml);
			$packet=parse($r);
			if ($packet['type'] == 'text') {
				return $this->respText($packet['content']);
			}
			if ($packet['type'] == 'news') {
				$news = array();
				$news[] = array(
					'title' => $packet['title'],
					'description' =>$packet['description'],
					'picurl' =>$packet['picurl'],
					'url' => $packet['url'],
				);
				return $this->respNews($news);
			}
			
		}
	}

	public function respond() {
		global $_W;

		$sql = "SELECT * FROM " . tablename('czt_zhuanfa_reply') . " WHERE `rid`=:rid";
		$item = pdo_fetch($sql, array(':rid'=>$this->rule));

		$result = array();
		if (!strexists($item['url'], 'http://') && !strexists($item['url'], 'https://')) {
			//$result = $this->procLocal($item);
			$result = $this->respText('error');
		} else {
			$result = $this->procRemote($item);
		}
		// if(empty($result) && !empty($item['default_text'])) {
		// 	$result = $this->respText($item['default_text']);
		// }
		if (!empty($result) && is_array($result)) {
			$result['FromUserName'] = $this->message['to'];
			$result['ToUserName'] = $this->message['from'];

		}
		return $result;
	}
	private function procRemote($item) {
		load()->func('communication');
		if (!strexists($item['url'], '?')) {
			$item['url'] .= '?';
		} else {
			$item['url'] .= '&';
		}
		
		$sign = array(
			'timestamp' => TIMESTAMP,
			'nonce' => random(10, 1),
		);
		$signkey = array($item['token'], $sign['timestamp'], $sign['nonce']);
		sort($signkey, SORT_STRING);
		$sign['signature'] = sha1(implode($signkey));
		$item['url'] .= http_build_query($sign, '', '&');

		$body = "<xml>" . PHP_EOL .
			"<ToUserName><![CDATA[{$this->message['to']}]]></ToUserName>" . PHP_EOL .
			"<FromUserName><![CDATA[{$this->message['from']}]]></FromUserName>" . PHP_EOL .
			"<CreateTime>{$this->message['time']}</CreateTime>" . PHP_EOL .
			"<MsgType><![CDATA[text]]></MsgType>" . PHP_EOL .
			"<Content><![CDATA[{$this->message['content']}]]></Content>" . PHP_EOL .
			"<MsgId>".TIMESTAMP."</MsgId>" . PHP_EOL .
			"</xml>";
		$response = ihttp_request($item['url'], $body, array('CURLOPT_HTTPHEADER' => array('Content-Type: text/xml; charset=utf-8')));
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
}
function http_post_data($url, $data) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    //$data = http_build_query($data);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml; charset=UTF-8','Content-Length: ' . strlen($data)));
    ob_start();
    curl_exec($ch);
    $return_content = ob_get_contents();
    ob_end_clean();
    return $return_content;
}

function parse($message) {
	$packet = array();
	if (!empty($message)){
		$obj = simplexml_load_string($message, 'SimpleXMLElement', LIBXML_NOCDATA);
		if($obj instanceof SimpleXMLElement) {
			$packet['from'] = strval($obj->FromUserName);
			$packet['to'] = strval($obj->ToUserName);
			$packet['time'] = strval($obj->CreateTime);
			$packet['type'] = strval($obj->MsgType);
			$packet['event'] = strval($obj->Event);
			
			foreach ($obj as $variable => $property) {
				$packet[strtolower($variable)] = (string)$property;
			}
			
			if($packet['type'] == 'text') {
				$packet['content'] = strval($obj->Content);
				$packet['redirection'] = false;
				$packet['source'] = null;
			}	
			if($packet['type'] == 'news') {
				$packet['title'] = strval($obj->Articles->item->Title);
				$packet['url'] = strval($obj->Articles->item->Url);
				$packet['picUrl'] = strval($obj->Articles->item->PicUrl);
				$packet['description'] = strval($obj->Articles->item->Description);
			}
		}
	}
	return $packet;
}