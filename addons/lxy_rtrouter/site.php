<?php
/**
 * 微路由
 *
 * @author 大路货 QQ:792454007
 * @url 
 * [WeiZan System] Copyright (c) 2013 012wz.com
 */
defined('IN_IA') or exit('Access Denied');

class Lxy_rtrouterModuleSite extends WeModuleSite {
	public $routertable='lxy_rtrouter_info';
	public $replytable='lxy_rtrouter_reply';
	public $table_authlist = 'lxy_rtrouter_authentication';
	public $jk_array=array(
		'CRE'=>'https://api.authcat.org/node_api/create_node',
		'UPD'=>'https://api.authcat.org/node_api/update_node',
		'DEL'=>'https://api.authcat.org/node_api/delete_node',
		'QRY'=>'https://api.authcat.org/node_api/retrieve_node',
		'QRYL'=>'https://api.authcat.org/node_api/retrieve_node_list',		
	);
	
	public $errorinfo=array(
			'-1'=>array(
					'msg'=>'Bad request! Please validate the JSON format of your request.',
					'des'=>'用户请求的格式错误导致服务器不能识别用户的请求，例如传入了非JSON格式的数据或者JSON格式错误等。'
					),
			'-2'=>array(
					'msg'=>'One or more mandatory fields are missing from the request: <List of missing parameters>.',
					'des'=>'用户的输入参数中缺少必选的参数。'
			),
			'-3'=>array(
					'msg'=>"Parameter <parameter-name>'s value<parameter-value> is in erroneous format because it is <error-reason>.",
					'des'=>'用户的输入参数格式没有满足参数列表中对参数的要求，例如字符长度限制或者数字范围限制等。'
			),
			'-4'=>array(
					'msg'=>"An internal error has occurred, please have a try in a moment.",
					'des'=>'服务器内部错误。'
			),
			'-5'=>array(
					'msg'=>"The node <node-id> that you have requested cannot be found.",
					'des'=>'用户请求修改，查询或者删除的节点并不存在。'
			),
			
	);
	
	
	
	public function getProfileTiles() {

	}

	public function getHomeTiles() {
	}
	
	
	public function doWebRouteradd() {
		global $_GPC, $_W;
		$id=$_GPC['id'];
		$weid = $_W['uniacid'];
		//drop list
		$seldata=array(
				'api_id'=>$this->module['config']['appid'],
				'api_key'=>$this->module['config']['appkey'],
		);
		$arrdat=$this->jkvisit('QRYL', $seldata);
		$clist=$arrdat['node_list'];
		if(!empty($id))
		{
			$item = pdo_fetch("SELECT * FROM ".tablename($this->routertable)." WHERE weid=:weid and id=:id", array(':weid' => $weid,':id'=>$id));
			if(empty($item))
			{
				message('抱歉,您编辑的路由器信息不存在或已删除');
			}
		}
		
		if (checksubmit('submit')) {
			if (empty($_GPC['rname'])) {
				message('请输入路由器名称！');
			}
			$data = array(
					'rname'=>$_GPC['rname'],					
					'weid' => $weid,
					'appid'=>$this->module['config']['authid'],
					'appkey'=>$this->module['config']['authkey'],
					'iurl'=> $_GPC['iurl'],
					'nodeid'=> $_GPC['nodeid'],					
					'status'=>$_GPC['status'],
			);
			if (empty($id))
			{
				pdo_insert($this->routertable, $data);
			}
			else
			{
				pdo_update($this->routertable, $data, array('id' => $id));
			}
			message('路由器信息更新成功！', $this->createWebUrl('routerlist'), 'success');
		}
		load()->func('tpl');		
		include $this->template('routeradd');
	}
	
	public function doWebRouterlist() {
		global $_W,$_GPC;
		$weid=$_W['uniacid'];	
		$pindex = max(1, intval($_GPC['page']));
		$psize = 15;
		$condition = '';
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND rname LIKE '%{$_GPC['keyword']}%'";
		}
		$list = pdo_fetchall("SELECT * FROM ".tablename($this->routertable)." WHERE weid = '{$weid}' $condition ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->routertable) . " WHERE weid = '{$weid}' $condition");
		$pager = pagination($total, $pindex, $psize);
		load()->func('tpl');	
		include $this->template('routerlist');
	}
	//通过接口取得节点列表
	public function doWebNodelist() {
		$data=array(
				'api_id'=>$this->module['config']['appid'],
				'api_key'=>$this->module['config']['appkey'],
		);
		
		$arrdat=$this->jkvisit('QRYL', $data);
		$list=$arrdat['node_list'];

		include $this->template('nodelist');
	}
	
	//通过接口新增节点
	public function doWebNodeadd() {
		global $_GPC,$_W;
	

		if(checksubmit('submit'))
		{
			if(empty($_GPC['nodename']))
			{
				message('节点名称必填！');
			}
			if(empty($_GPC['login_url']))
			{
				message('认证登陆页面地址必填！');
			}
			if(empty($_GPC['success_url']))
			{
				message('登陆认证成功页面地址必填！');
			}
			

				
			$add_data=array(
					'api_id'=>$this->module['config']['appid'],
					'api_key'=>$this->module['config']['appkey'],
					'description'=>$_GPC['description'],
					'name'=>($_GPC['nodename']),
					'login_url'=>$_GPC['login_url'],
					'success_url'=>$_GPC['success_url'],
					'probation_url'=>$_GPC['probation_url'],
					'login_timeout'=>intval($_GPC['login_timeout'])<=0||intval($_GPC['login_timeout'])>1440?1440:intval($_GPC['login_timeout']),
					'probation_timeout'=>intval($_GPC['probation_timeout']),
					'is_portal'=>$_GPC['is_portal']==1?true:false,
					'qq_login'=>$_GPC['qq_login']==1?true:false,
					'weibo_login'=>$_GPC['weibo_login']==1?true:false,
					'weixin_login'=>$_GPC['weixin_login']==1?true:false,
					'wx_id'=>$_GPC['wx_id'],
					'wx_name'=>$_GPC['wx_name'],
					'wx_phone_only'=>$_GPC['wx_phone_only']==1?true:false,
					'wx_unauth_timeout'=>intval($_GPC['wx_unauth_timeout']),
					'wx_reject_timeout'=>intval($_GPC['wx_reject_timeout'])<=0?3:intval($_GPC['wx_reject_timeout']),
					'white_list'=>$_GPC['white_list'],
					'hide_cp'=>$_GPC['hide_cp']==1?true:false,
					'auth2nd'=>intval($_GPC['auth2nd']),
						
			);
			$i=0;
			$arrnumber=count($add_data);
			$arrNew=array();
			while($i<$arrnumber)
			{
				$param =current($add_data);
				if (!empty($param)) 
				{
					$arrNew[key($add_data)]=$param;
						
				}
				next($add_data);
				$i++;
			}
			
			
			$arrdat=$this->jkvisit('CRE', $arrNew);
			message('新增节点号为：['.$arrdat['node'].']节点成功',referer(),'success');
	
		}
	
		include $this->template('nodeadd');
	}
	
	
	//通过接口取得节点信息
	public function doWebNodeview() {
		global $_GPC,$_W;
		$nodeid=intval($_GPC['node']);
		$data=array(
				'api_id'=>$this->module['config']['appid'],
				'api_key'=>$this->module['config']['appkey'],
				'node'=>intval($_GPC['node'])
		);
		if(checksubmit('submit'))
		{
			
			$update_data=array(
					'api_id'=>$this->module['config']['appid'],
					'api_key'=>$this->module['config']['appkey'],
					'node'=>intval($_GPC['node']),
					'description'=>$_GPC['description'],
					'login_url'=>$_GPC['login_url'],
					'success_url'=>$_GPC['success_url'],
					'probation_url'=>$_GPC['probation_url'],
					'login_timeout'=>intval($_GPC['login_timeout'])<=0||intval($_GPC['login_timeout'])>1440?1440:intval($_GPC['login_timeout']),
					'probation_timeout'=>intval($_GPC['probation_timeout']),
					'is_portal'=>$_GPC['is_portal']==1?true:false,
					'qq_login'=>$_GPC['qq_login']==1?true:false,
					'weibo_login'=>$_GPC['weibo_login']==1?true:false,
					'weixin_login'=>$_GPC['weixin_login']==1?true:false,
					'wx_id'=>$_GPC['wx_id'],
					'wx_name'=>$_GPC['wx_name'],
					'wx_phone_only'=>$_GPC['wx_phone_only']==1?true:false,
					'wx_unauth_timeout'=>intval($_GPC['wx_unauth_timeout']),
					'wx_reject_timeout'=>intval($_GPC['wx_reject_timeout'])<=0?3:intval($_GPC['wx_reject_timeout']),
					'white_list'=>$_GPC['white_list'],
					'hide_cp'=>$_GPC['hide_cp']==1?true:false,
					'auth2nd'=>intval($_GPC['auth2nd']),
			
			);
			$arrdat=$this->jkvisit('UPD', $update_data);
			message('更新节点号为：['.$arrdat['node'].']节点成功',referer(),'success');
				
		}
		
		$item=$this->jkvisit('QRY', $data);	
		//print_r($item);
		//return ;
		include $this->template('nodeview');
	}
	
	//通过接口删除节点信息
	public function doWebDelnode() {
		global $_GPC;
	
		$data=array(
				'api_id'=>$this->module['config']['appid'],
				'api_key'=>$this->module['config']['appkey'],
				'node'=>intval($_GPC['node']),
		);
	
		$arrdat=$this->jkvisit('DEL', $data);
		message('删除节点号为：['.$arrdat['node'].']节点成功',referer(),'success');		

	}
	
	//通过接口更新节点信息
	public function doWebUpdatenode() {
		global $_GPC;
	

	
		$arrdat=$this->jkvisit('DEL', $data);
		message('删除节点号为：['.$arrdat['node'].']节点成功',referer(),'success');
	
	}

	
	
	//接口返回参数
	public function jkvisit($type,$data)
	{
		$url=$this->jk_array[$type];
		if(empty($url))
		{
			message($type.'接口地址未设置');
		}
		
		if(empty($data))
		{
			message($type.'接口参数未设置,请到设置菜单设置！');
				
		}
		load()->func('communication');
		$jsondata=json_encode($data);
		$rsp=ihttp_post($url, $jsondata);
		$dat=$rsp['content'];
		$arrdat=json_decode($dat,true) ;	
	
		$status=$arrdat['status'];
		if($status<=-1&&$status>=-7)
		{
			message('错误代码['.$status.'];错误信息'.$arrdat['err_msg'].$this->errorinfo[$status]['des']);
		}
		return $arrdat;		
	}
	
	public function doWebDelrouter() {
		global $_GPC,$_W;
		$id = intval($_GPC['id']);
		$item = pdo_fetch("SELECT * FROM ".tablename($this->routertable)." WHERE id = :id and weid=:weid" , array(':id' => $id,':weid'=>$_W['uniacid']));
		if (empty($item)) {
			message('抱歉，指定路由器不存在或是已经删除！', '', 'error');
		}
		pdo_delete($this->routertable, array('id' => $item['id']));
		message('删除成功！', referer(), 'success');
	}
	
	public function doWebAuthlist() {
		global $_W,$_GPC;
		$weid=$_W['uniacid'];
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$condition = '';
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND b.rname LIKE '%{$_GPC['keyword']}%'";
		}
		$list = pdo_fetchall("SELECT a.*,b.rname FROM ".tablename($this->table_authlist)." a left join ".tablename($this->routertable)." b on a.routerid=b.id  WHERE a.weid = '{$weid}' $condition ORDER BY createtime DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
		$total = pdo_fetchcolumn("SELECT count(*) FROM ".tablename($this->table_authlist)." a left join ".tablename($this->routertable)." b on a.routerid=b.id  WHERE a.weid = '{$weid}'  $condition");
		$pager = pagination($total, $pindex, $psize);
		include $this->template('authlist');
	}
	
	
}
