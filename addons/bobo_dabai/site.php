<?php
/**
 * 大白治疗师
 *
 * 作者:bobo
 *
 * qq:717321528
 */
defined('IN_IA') or exit('Access Denied');
define('RES', '../addons/bobo_dabai/template/');

class bobo_dabaiModuleSite extends WeModuleSite
{
    public $title = '大白治疗师';

    function __construct()
    {
        global $_W, $_GPC;

    }

    public function doMobileIndex()
    {
        global $_W, $_GPC;
        include $this->template('dabai');
    }
    public function doMobileShowmsg(){
    
    $ch = curl_init();
    $url = 'http://apis.baidu.com/turing/turing/turing?key=879a6cb3afb84dbf4fc84a1df2ab7319&info='.$_POST['msg'].'&userid=eb2edb736';
    $header = array(
        'apikey: bd42b80f6b2c44a75563f5770ecaa081',
    );
    // 添加apikey到header
    curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // 执行HTTP请求
    curl_setopt($ch , CURLOPT_URL , $url);
    $res = curl_exec($ch);

    $res=json_decode($res);
	   if($res->code==100000){
	   		$arr=array('code'=>$res->code,'text'=>$res->text);
	   }else{
	   		$arr=array('code'=>1,'text'=>'系统错误');
	   }
	   echo json_encode($arr);
    }
  
}