<?php
class ShowApiSdk{
	static $showapi_appid = '2562';
	static $showapi_sign = 'a2b24d2ad58f4480af0b5ae048e0a9c2';
	static function createSign ($paramArr) {
		$sign = "";
		ksort($paramArr);
		foreach ($paramArr as $key => $val) {
			if ($key != '' && $val != '') {
				$sign .= $key.$val;
			}
		}
		$sign.=ShowApiSdk::$showapi_sign;
		$sign = strtoupper(md5($sign));
		return $sign;
	}
	
	static function createStrParam ($paramArr) {
		$strParam = '';
		foreach ($paramArr as $key => $val) {
			if ($key != '' && $val != '') {
				$strParam .= $key.'='.urlencode($val).'&';
			}
		}
		return $strParam;
	}
	
	static function getContent(){
		$paramArr = array(
				'showapi_appid'=> ShowApiSdk::$showapi_appid,
				'time' => date('Y-m-d') ,
				'page' => '' ,
				'maxResult' => '50' ,
				'showapi_timestamp' => date('YmdHis')
				// other parameter
		);
		$sign = ShowApiSdk::createSign($paramArr);
		$strParam = ShowApiSdk::createStrParam($paramArr);
		$strParam .= 'showapi_sign='.$sign;
		$url = 'http://route.showapi.com/341-1?'.$strParam;
		$result = file_get_contents($url);
		$result = json_decode($result);
		if(intval($result->showapi_res_code)==0){
			var_dump(count($result->showapi_res_body->contentlist));
			echo $result->showapi_res_body->contentlist[0]->text;
		}else{
			var_dump("失败了");
		}
	}
	
	static function getIDInfo($id){
		$paramArr = array(
				'showapi_appid'=> ShowApiSdk::$showapi_appid,
				'id'=>$id,
				'showapi_timestamp' => date('YmdHis')
				// other parameter
		);
		$sign = ShowApiSdk::createSign($paramArr);
		$strParam = ShowApiSdk::createStrParam($paramArr);
		$strParam .= 'showapi_sign='.$sign;
		$url = 'http://route.showapi.com/25-3?'.$strParam;
		$result = file_get_contents($url);
		$result = json_decode($result);
		if(intval($result->showapi_res_code)==0){
			if($result->showapi_res_body->retMsg=="success"){
				return $result->showapi_res_body->retData;
			}
		}else{
			return '';
		}
	}
}
?>