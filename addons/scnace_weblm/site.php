<?php
/**
 * Web_Lastmile模块微站定义
 *
 * @author scnace
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Scnace_weblmModuleSite extends WeModuleSite {

	public function doMobileWeblm() {
		//这个操作被定义用来呈现 功能封面
		//这个操作被定义用来呈现 功能封面
		//此处global 全局变量
		global  $_W,$_GPC;
		// openid获取测试
		//echo $_W['openid'];
		
		//openid init
		$openid=$_W['openid'];
		//给前台
		//检查权限
		$check=$this->CheckAuth($openid);
		
		$owner=$_GPC['Weblm'];
		$ownername=$owner['Name'];
		$commonid=substr(md5(time()),0,12);
		//	$expressname=$_GPC[];
		$addressrm=$owner['addressrm'];
		$ownerphone=$owner['Phone'];
		$addressap=$owner['addressap'];
		$expressname=$owner['expressname'];
		$type=$owner['type'];
		 
		var_dump($owner);
		//var_dump($_GPC['submitbutton']);
		if(($addressap=='none'||$type=='none'||$expressname=='none'||$ownername==''||$addressrm==''||$ownerphone=='')&& $_GPC['submitbutton']){
			$databutton=false;
		}
		else{
			$databutton=true;
		}
		//	var_dump($owner) ;
		if($owner&&$databutton){
			$this->PutOwnerinfo($openid, $commonid, $expressname, $addressap, $ownername, $addressrm, $ownerphone,$type);
		}
		include $this->template('index');
		
	}
	/**
	 * Attention
	 */
	public function doMobileAttention(){
		//全局变量 获取tpl
		global  $_W;
		//openid init
		$openid=$_W['openid'];
		//给前台
		$check=$this->CheckAuth($openid);
	
		include $this->template('Attention');
	
	}
	/**
	 * Help
	 */
	public function doMobileHelp(){
		//获取tpl
		global  $_W,$_GPC;
		//openid init
		$openid=$_W['openid'];
		//检查权限
		//给前台
		$check=$this->CheckAuth($openid);
	
		$box=$this->SearchBoxInfo();
	
		$button=$_GPC['button'];
		if($button){
			$this->PickTheExpress($_GPC['workerid'],$_GPC['commonid']);
		}
	
		include $this->template('Help');
	}
	
	
	
	public function doMobileMyExpress(){
		//获取tpl
		global  $_W,$_GPC;
		//openid init
		$openid=$_W['openid'];
		//检查权限
		//给前台
		$check=$this->CheckAuth($openid);
		//获得我的快递信息
		$worker=$this->SearchMyInfo($openid);
		//var_dump($worker);
		if($_GPC['button']){
			$this->ConfirmDeal($_GPC['commonid']);
		}
		include $this->template('MyExpress');
	}
	
	
	
	public function doMobileWorkerRank(){
		//获取tpl
		global  $_W,$_GPC;
		//echo $_W['openid'];
		//openid init
		$openid=$_W['openid'];
	
		//给前台
		$check=$this->CheckAuth($openid);
		//$button=true;
		$worker=$_GPC['WorkerRank'];
		//worker姓名  初始化
		$workername=$worker['Wname'];
		//workerphone 初始化
		$workerphone=$worker['Wphone'];
		//var_dump($_GPC['sbbutton']);
		if(($workername=='' OR $workerphone=='') AND $_GPC['sbbutton']){
			$button=false;
		}
		else{
			$button=true;
		}
		//var_dump($button);
		//pdo 操作
		if($worker&&$button){
			$worker=$this->Putworkerinfo($openid, $workername, $workerphone);
		}
	
		include $this->template('WorkerRank');
	}
	public function doWebRule() {
		//这个操作被定义用来呈现 规则列表
	}
	public function doWebAdmin() {
		//这个操作被定义用来呈现 管理中心导航菜单
	}
	public function doMobileGuide() {
		//这个操作被定义用来呈现 微站首页导航图标
	}
	public function doMobileMyinfo() {
		//这个操作被定义用来呈现 微站个人中心导航
	}
	public function doMobileShortcut() {
		//这个操作被定义用来呈现 微站快捷功能导航
	}

	/**
	 * Worker信息输入
	 * @param unknown $openid
	 * @param unknown $workername
	 * @param unknown $phone
	 * @return Ambigous <boolean, unknown>
	 */
	
	private function  Putworkerinfo($openid,$workername,$phone){
		//pdo_insert
		$t=pdo_insert('worker',array('openid'=>$openid,'workername'=>$workername,'phone'=>$phone));
		return $t;
	}
	
	/**
	 * 权限检查 基于openid
	 * @param unknown $openid
	 * @return boolean
	 */
	private  function  CheckAuth($openid){
		//select pdo
		$t=pdo_fetch("SELECT * FROM".tablename('worker')."WHERE openid=:oid",array("oid"=>$openid));
		return $t;
	}
	
	/**
	 * Owner信息输入
	 * @param unknown $openid
	 * @param unknown $commonid
	 * @param unknown $expressname
	 * @param unknown $addressap
	 * @param unknown $ownername
	 * @param unknown $addressrm
	 * @param unknown $phone
	 * @param unknown $type
	 * @return Ambigous <boolean, unknown>
	 */
	private function PutOwnerinfo($openid,$commonid,$expressname,$addressap,$ownername,$addressrm,$phone,$type){
		$t=pdo_insert('owner',
				array(
						'openid'=>$openid,
						'commonid'=>$commonid,
						'expressname'=>$expressname,
						'addressap'=>$addressap,
						'ownername'=>$ownername,
						'addressrm'=>$addressrm,
						'phone'=>$phone,
						'type'=>$type
				)
		);
		$b=pdo_insert('box',array('commonid'=>$commonid,'status'=>'你的快递还在盒子里睡觉呢','visible'=>1,'checked'=>0));
		return $t&&$b;
	}
	
	/**
	 * 获取有用信息
	 * @return multitype:
	 */
	
	private  function SearchBoxInfo(){
	
		//初始化数据数组
		$storage=array();
		$allownerarr=pdo_fetchall("SELECT * FROM".tablename('box')."WHERE visible=:v",array(':v'=>'1'),'');
		//重构数组
		foreach ($allownerarr as $k=>$v){
			//获取owner表数据
			$commonid=$v['commonid'];
			$ownerinfo=pdo_fetch("SELECT * FROM ".tablename('owner')."WHERE commonid=:cid",array(':cid'=>$commonid));
			$ownername=$ownerinfo['ownername'];
			$phone=$ownerinfo['phone'];
			$expressname=$ownerinfo['expressname'];
			$address=$ownerinfo['addressap'].'-'.$ownerinfo['addressrm'];
			$type=$ownerinfo['type'];
			//重组数组
			$arr=array(
					'ownername'=>$ownername,
					'commonid'=>$commonid,
					'phone'=>$phone,
					'address'=>$address,
					'type'=>$type,
					'expressname'=>$expressname
			);
			array_push($storage, $arr);
		}
	
	
		return $storage;
	}
	/**
	 *
	 * @param unknown $openid
	 * @return multitype:
	 */
	private  function  SearchMyInfo($openid){
	
		$tarr=array();
		//根据Openid查找对应的owner
		$owner=pdo_fetchall("SELECT * FROM".tablename('owner')."WHERE openid=:oid",array(':oid'=>$openid),'');
		//commonid查找BOX表
		foreach ($owner as $k=>$v){
			$commonid=$v['commonid'];
			$where='WHERE commonid=:cid AND checked=:ck';
			$zero=0;
			$box=pdo_fetchall('SELECT * FROM'.tablename('box')."{$where}",array(':cid'=>$commonid,':ck'=> $zero),'');
			foreach ($box as $key=>$value){
				$worker=pdo_fetch("SELECT * FROM".tablename('worker')."WHERE workerid=:wid",array(':wid'=>$value['workerid']));
				$workername=$worker['workername'];
				$wphone=$worker['phone'];
				$workers=array(
						'workername'=>$workername,
						'phone'=>$wphone,
						'workerid'=>$value['workerid'],
						'commonid'=>$commonid,
						'status'=>$value['status']
				);
				array_push($tarr, $workers);
			}
		}
		return $tarr;
	}
	/**
	 *
	 * @param unknown $workerid
	 * @param unknown $commonid
	 */
	private function  PickTheExpress($workerid,$commonid){
		//update  box table
		$box=pdo_update('box',array('workerid'=>$workerid,'visible'=>0,'status'=>'你的快递已经再回来的路上了，再等等哦!'),array('commonid'=>$commonid));
	
	}
	
	/**
	 *
	 * @param unknown $commonid
	 */
	private function ConfirmDeal($commonid){
		//update box table
		$box=pdo_update('box',array('checked'=>1),array('commonid'=>$commonid));
	
		$boxinfo=pdo_fetch("SELECT * FROM".tablename('box')."WHERE commonid=:cid",array(':cid'=>$commonid));
		$workerid=$boxinfo['workerid'];
		$solved=pdo_fetch("SELECT solved FROM".tablename('worker')."WHERE workerid=:wid",array(':wid'=>$workerid));
		$addsolved=$solved['solved']+1;
		//update worker table
		$worker=pdo_update('worker',array('solved'=>$addsolved),array('workerid'=>$workerid));
	}
	
	
	
}
