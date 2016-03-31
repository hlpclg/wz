<?php
/**
 * [WeiZan System] Copyright (c) 2014 012wz.com
 * WeiZan is  a free software, it under the license terms, visited http://www.012wz.com/ for more details.
 */

error_reporting(1);
define('IN_MOBILE', true);
define("MON_ORDER", "mon_orderform");
require '../../framework/bootstrap.inc.php';
require_once  "WxPayPubHelper/WxPayPubHelper.php";
require_once "monUtil.class.php";
require_once "dbutil.class.php";

$input = file_get_contents('php://input');
WeUtility::logging('info',"通用订单异步通知数据".$input);

//WeUtility::logging('info',"商户key数据".$kjsetting);
$notify=new Notify_pub();
$notify->saveData($input);
$data=$notify->getData();
$ordersetting=DBUtil::findUnique(DBUtil::$TABLE_ORDER_SETTING,array(":appid"=>$data['appid']));
if(empty($data)){
	$notify->setReturnParameter("return_code","FAIL");
	$notify->setReturnParameter("return_msg","通用订单参数格式校验错误");
	WeUtility::logging('info',"通用订单回复参数格式校验错误");
	exit($notify->createXml());
}

if($data['result_code'] !='SUCCESS' || $data['return_code'] !='SUCCESS') {
	$notify->setReturnParameter("return_code","FAIL");
	$notify->setReturnParameter("return_msg","通用订单参数格式校验错误");
	WeUtility::logging('info',"通用订单回复参数格式校验错误");
	exit($notify->createXml());
}
//更新表订单信息

WeUtility::logging('info',"通知订单更新");
if($notify->checkSign($ordersetting['shkey'])) {
	DBUtil::update(DBUtil::$TABLE_ORDER_ORDER,array("status"=>3,'paytime'=>TIMESTAMP),array("outno"=>$data['out_trade_no']));
	$order = DBUtil::findUnique(DBUtil::$TABLE_ORDER_ORDER, array(":outno"=>$data['out_trade_no']));
	$notify->setReturnParameter("return_code","SUCCESS");
	$notify->setReturnParameter("return_msg","OK");


	exit($notify->createXml());
} else {
	$notify->setReturnParameter("return_code","FAIL");
	$notify->setReturnParameter("return_msg","通用订单签名校验错误");
	WeUtility::logging('info',"通用订单签名校验错误");
	exit($notify->createXml());
}

WeUtility::logging('info',"通用订单更新回复数据".$data);





