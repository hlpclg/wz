<?php
/**
 * 微路由
 *
 * @author 大路货 QQ:792454007
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class Lxy_rtrouterModuleProcessor extends WeModuleProcessor {    
	
	public $table_router = 'lxy_rtrouter_info';
	public $table_reply = 'lxy_rtrouter_reply';
	public $table_authlist = 'lxy_rtrouter_authentication';
	public $error_info=array(
			'100003'=>'节点不存在',
			'100004'=>'新节点已存在',
			'100005'=>'节点id不存在',
			'100006'=>'节点id和节点名不符',
			'100007'=>'节点id无效',
			'200001'=>'accessID错误',
			'200002'=>'accessKey错误',
			'200003'=>'该用户无该节点操作权限',
			'200010'=>'Json请求消息错误',
			'200011'=>'微信接口传入参数错误',
			'300006'=>'登陆验证码错误',
			'300007'=>'登陆验证码失效',
			'300008'=>'三方app或者随机验证码地址错误',
			'300009'=>'三方app获取随机数参数错误',
			'310002'=>'Json参数accessID为空',
			'310003'=>'Json参数accessKey为空',
			'310004'=>'Json参数node_name为空',
			'310005'=>'Json参数new_node_name格式不合法',
			'310006'=>'Json参数description为空',
			'310007'=>'Json参数email格式不合法',
			'310008'=>'Json参数login_page为空',
			'310009'=>'Json参数portal_page为空',
			'310010'=>'Json参数not_dev_url为空',
			'310011'=>'Json参数welogin不合法',
			'310012'=>'Json参数wereject不合法',
			'310013'=>'Json参数probation_time不合法',
			'310014'=>'Json参数logintimeout不合法',
			'310015'=>'Json参数isportal不合法',
			'310016'=>'Json参数notwww不合法',
			'310017'=>'Json参数logintype不合法',
			'310018'=>'Json参数phone不合法',
			'310019'=>'Json参数whiltlist不合法',
			'310020'=>'Json参数type错误',
			'310021'=>'Json参数probation_url不合法',			
			'400001'=>'Json微信参数openid为空',
			'400002'=>'Json微信参数openid无法在公众号中获取',
				
				
	);
	
	public $jk_url=array(
			'WXONLY'=>'http://wx.rippletek.com/Portal/Wx/get_auth_url',
			'WXCODE'=>'http://wx.rippletek.com/Portal/Wx/get_auth_token',
			'CANCELAUTH'=>'http://wx.rippletek.com/Portal/Wx/unauth_user',
			'GETOPENID'=>'http://wx.rippletek.com/Portal/Wx/get_openid_by_mac',
	);
	
	public $jk_ret_par=array(
			'WXONLY'=>array('status','auth_url','err_msg'),
			'WXCODE'=>array('status','auth_token','err_msg'),
			'CANCELAUTH'=>array('status','msg','err_msg'),
			'GETOPENID'=>array('status','openid','err_msg'),
				
	);
	
	public $jk_return=array(
		'-1'=>'用户请求的格式错误导致服务器不能识别用户的请求，例如传入了非 JSON 格式的数据或者 JSON 格式错误等。'	,
		'-2'=>'用户的输入参数中缺少必选的参数。',
		'-3'=>'用户的输入参数格式没有满足参数列表中对参数的要求，例如参数类型或字符长度限制或者数字范围限制等。',
		'-4'=>'服务器内部错误。',
		'-5'=>'用户请求的节点并不存在。',
		'-6'=>'用户提供的 <api_id, api_key>不存在，不匹配或者并无权限进行操作。',
		'-7'=>'使用获取 OPENID 接口时候，对应的 mac 地址不存在或者无对应的 OPENID。',
	);
	public function respond() {   	
    	
    	global $_W;
    	$rid = $this->rule;
    	$weid=$_W['uniacid'];
    	$sql = "SELECT * FROM " . tablename($this->table_reply) . " WHERE `rid`=:rid LIMIT 1";
    	$row = pdo_fetch($sql, array(':rid' => $rid));
    	$routerid=$row['routerid'];
    	if (empty($routerid)) {
    		return $this->respText("请确认您操作的路由器已经维护") ;
    	}
    	$rowrouter=pdo_fetch("SELECT * FROM " . tablename($this->table_router) ." WHERE `id`={$routerid} ");
    	if (empty($rowrouter)) {
    		return $this->respText("指定关联路由器已删除！") ;
    	}
    	if($rowrouter['status']==0)
    	{
    		return $this->respText("该该路由器未启用，请后台设置为启用！") ;
    	}
    	
    	$appid=$rowrouter['appid'];
    	$appkey=$rowrouter['appkey'];
    	$nodeid=$rowrouter['nodeid'];
    	$openid=$this->message['from'];
    	
    	$postarr=array(
    			'api_id'=>$appid,
    			'api_key'=>$appkey,
    			'node'=>intval($nodeid),
    			'openid'=>$openid
    	);
    	//return $this->respText(serialize($postarr));
    	if(empty($appid)||empty($appkey)||empty($nodeid))
    	{
    		return $this->respText("路由器配置的接口参数为空请设置后重试！") ;
    	}
    	
    	$interfaceinfo=$this->GetRouterInter('WXONLY',$postarr);
    	$interfacetokeninfo=$this->GetRouterInter('WXCODE',$postarr);
    	$authdata=array(
    			'routerid'=>$routerid,
    			'fromuser'=>$openid,
    			'createtime'=>time(),
    			'weid'=>$weid,
    	);
    	if($interfaceinfo['flag']==1)//接受认证
    	{
    		
    		$urlText="<a href='{$interfaceinfo['result']}' >直接点击</a>";
    		$row['oktip']=str_replace('{url}',$urlText , $row['oktip']);
    		$row['oktip']=str_replace('{key}',$interfacetokeninfo['result'] , $row['oktip']);
    		$authdata['result']=1;
    		$authdata['resultmemo']='认证链接:'.$interfaceinfo['result'].' 验证码:'.$interfacetokeninfo['result'];
    		pdo_insert($this->table_authlist,$authdata);
    		return $this->respText($row['oktip']);
    	}
    	else 
    	{
    		$authdata['result']=0;
    		$authdata['resultmemo']=$interfaceinfo['result'];    		
    		pdo_insert($this->table_authlist,$authdata);
    		return $this->respText($interfaceinfo['result'].$interfaceinfo['debug']);
    	}
    	
    	
   }

   
   
   private function GetRouterInter($jktype,$postarr)
   {
   	$arrResult=array(
   			'flag'=>0,
   			'result'=>'',
   			'token'=>'',
   			'debug'=>'',
   	);  	
   	
   	$url=$this->jk_url[$jktype];  
    $jsnpostarr=json_encode($postarr);
    
	load()->func('communication');
   	$rsp = ihttp_post($url,$jsnpostarr);
   	$dat=$rsp['content'];
   	
   	//获取接口情况
   	if(!empty($dat))
   	{
   		$result=json_decode($dat,true) ;
   		if(!is_array($result))
   		{
   			$arrResult['flag']=0;
   			$arrResult['result']='接口返回参数不是JSON格式';
   			$arrResult['debug']=$dat;
   		
   		}
   		else
   		{
   			if($result[$this->jk_ret_par[$jktype][0]]==0)//连接成功
   			{
   				$arrResult['flag']=1;
   				$arrResult['result']=$result[$this->jk_ret_par[$jktype][1]];
   			}
   			elseif ($result[$this->jk_ret_par[$jktype][0]]<0)//捕捉到失败
   			{
   				$arrResult['flag']=-1;
   				$arrResult['result']=$this->errcodetrans($result[$this->jk_ret_par[$jktype][2]]);
   				$arrResult['debug']=$jsnpostarr;
   					
   			}
   			else
   			{
   				$arrResult['flag']=0;
   				$arrResult['result']='返回状态异常，请联系路由器接口提供方';
   			}
   		}
   	}
 	else 
   	{
   		$arrResult['flag']=0;
   		$arrResult['result']='接口无响应';   	
   		$arrResult['debug']=$url;
   	}

   	return $arrResult;
   	
   }
   
   private function  errcodetrans($errcode)
   {
   	if(array_key_exists("$errcode", $this->jk_return)) 
   	{
   		$rsp="错误代码[{$errcode}] 原因:".$this->jk_return[$errcode];
   		
   	}
   	else
   	{
   		$rsp="错误代码[{$errcode}],接口返回的错误代码在预期定义以外，请联系路由器厂家确认原因";
   	}
   	return $rsp;
   }
 
}

