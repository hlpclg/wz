<?php
//客服
//114.215.85.35  8080

//kfai
//115.28.225.82
//60808888  x2Vt247f

define('IP','115.28.225.82');
define('PORT','80');
define('HOSTNAME','/FeieServer/');
include 'HttpClient.class.php';

class wprint {
	public $client;
	function __construct() {
		$this->client = new HttpClient(IP, PORT);
	}

	function StrPrint($printer_sn, $key, $orderinfo, $times = 1){
		$content = array(
			'sn' => $printer_sn,  
			'printContent' => $orderinfo,
			//'apitype' => 'php',
			'key' => $key,
			'times' => $times
		);
		if(!$this->client->post(HOSTNAME . 'printOrderAction', $content)){
			return error(-1, '链接服务器失败');
		}
		else{
			$result = $this->client->getContent();
			$result = @json_decode($result, true);
			$error = array(
					'服务器接收订单成功',
					'打印机编号错误',
					'服务器处理订单失败',
					'打印内容太长',
					'请求参数错误'
				);
			if($result['responseCode'] == 0) {
				return $result['orderindex'];
			} else {
				return error(-1, $error[$result['responseCode']]);
			}
		}
	}

	function QueryOrderState($printer_sn, $key, $index){
		$msgInfo = array(
			'sn' => $printer_sn,  
			'key' => $key,
			'index' => $index
		);
	
		if(!$this->client->post(HOSTNAME . 'queryOrderStateAction', $msgInfo)){
			return error(-1, '链接服务器失败');
		} else {
			$result = $this->client->getContent();
			$result = @json_decode($result, true);
			$error = array(
					'已打印/未打印',
					'请求参数错误',
					'服务器处理订单失败',
					'没有找到该索引的订单',
				);
			if($result['responseCode'] == 0) {
				$status = ($result['msg'] == '已打印' ? 1 : 2);
				return $status;
			} else {
				return error(-1, $error[$result['responseCode']]);
			}
		}
	}

	function QueryPrinterStatus($printer_sn, $key){
		$msgInfo = array(
			'sn' => $printer_sn,  
			'key' => $key,
		);

		if(!$this->client->post(HOSTNAME . 'queryPrinterStatusAction', $msgInfo)){
			return error(-1, '链接服务器失败');
		} else {
			$result = $this->client->getContent();
			$result = @json_decode($result, true);
			return $result['msg'];
		}
	}
}
?>