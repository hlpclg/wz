<?php
/**
 * 微WiFi模块处理程序
 *
 * @author Jeff Huang
 * @url http://www.vbiz.cc
 */
defined('IN_IA') or exit('Access Denied');

class jeffh_vwifiModuleProcessor extends WeModuleProcessor {	

	function http_post_data($url, $data_string) {  
	  
			$ch = curl_init();  
			curl_setopt($ch, CURLOPT_POST, 1);  
			curl_setopt($ch, CURLOPT_URL, $url);  
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);  
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(  
				'Content-Type: application/json; charset=utf-8',  
				'Content-Length: ' . strlen($data_string))  
			);  
			ob_start();  
			curl_exec($ch);  
			$return_content = ob_get_contents();  
			ob_end_clean();  
	  
			$return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
			return array($return_code, $return_content);  
	}  

	public function respond() {
		$content = $this->message['content'];
		$openid = $this->message['from'];
		$api_id = $this->module['config']['api_id'];
		$api_key = $this->module['config']['api_key'];
		$node = (int)$this->module['config']['node'];
		$url = "http://wx.rippletek.com/Portal/Wx/get_auth_url";
		$data = json_encode(array('api_id'=>$api_id, 'api_key'=>$api_key, 'node'=>$node, 'openid'=>$openid)); 	
		$result = $this->http_post_data($url, $data); 
		$info = json_decode($result[1],true);

		if($info['status'] == "0"){
			return $this->respText("哦啦!请<a href=\"".$info['auth_url']."\">直接点击</a>联网");
		}else{
			//var_dump($result);
			return $this->respText("登录失败，请重试。");
		}
	}
}