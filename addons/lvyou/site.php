<?php
/**
 * 微旅游模块微站定义
 *
 * @author Freedom QQ:350826748
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class LvyouModuleSite extends WeModuleSite {
	
	public function __web($f_name){
		global $_W,$_GPC;
		checklogin();
		@set_magic_quotes_runtime(0);
		#$MQG = get_magic_quotes_gpc();var_dump($MQG);
		foreach(array('_POST', '_GET', '_COOKIE') as $__R) {
			if($$__R) { 
				foreach($$__R as $__k => $__v) { 
					if(isset($$__k) && $$__k == $__v) 
					unset($$__k); 
				} 
			}
		}
		if(!$MQG) {
			if($_POST) $_POST = $this->daddslashes($_POST);
			if($_GET) $_GET = $this->daddslashes($_GET);
			if($_COOKIE) $_COOKIE = $this->daddslashes($_COOKIE);
		}
		define(IS_POST, isset($_POST['submit'])&& $_SERVER['REQUEST_METHOD']=='POST');
		if($_POST) extract($_POST, EXTR_SKIP);
		if($_GET) extract($_GET, EXTR_SKIP);
		if($id)$id = intval($id);
		if($page ==0) $page = 1; 
		$pagesize = 20;
		$offset = ($page-1)*$pagesize;
		$file = substr($f_name,5);
		$M = $this->module;
		$weid=$_W['weid'];
		$timestamp = $_W['timestamp'];
		$post = $_POST;
		$op = $_GPC['op']?$_GPC['op']:'list';
		$curr_url = create_url('site/module',array('do'=>$file,'name'=>$M['name'],'weid'=>$weid));
		include_once  'web/lp_'.$file.'.php';
	}

	public function __mobile($f_name){
		//微信端控制
		global $_W,$_GPC;
		if($page ==0) $page = 1; 
		$pagesize = 20;
		$offset = ($page-1)*$pagesize;
		$file = substr($f_name,5);
		$M = $this->module;
		$weid=$_W['weid'];
		$timestamp = $_W['timestamp'];
		
		$curr_url = create_url('mobile/entry',array('eid'=>$_GET['eid'],'name'=>$M['name'],'weid'=>$weid));
		define('ROOT', './source/modules/lvyou');
		define('CSS', ROOT.'/template/mobile/css/');
		define('JS', ROOT.'/template/mobile/js/');
		define('IMG', ROOT.'/template/mobile/images/');
		$do = trim($_GET['do']);
		include_once  'mobile/lp_Index.php';
	}		

	public function doMobileIndex(){
		$this->__mobile(__FUNCTION__);
	}

	public function doWebJianjie() {
		//这个操作被定义用来呈现 管理中心导航菜单
		$this->__web(__FUNCTION__);
	}

	public function doWebHaibao() {
		//这个操作被定义用来呈现 管理中心导航菜单
		$this->__web(__FUNCTION__);
	}

	public function doWebJingqu() {
		//这个操作被定义用来呈现 管理中心导航菜单
		$this->__web(__FUNCTION__);
	}

	public function doWebJingdian() {
		//这个操作被定义用来呈现 管理中心导航菜单
		$this->__web(__FUNCTION__);
	}

	public function doWebXiangce() {
		//这个操作被定义用来呈现 管理中心导航菜单
		$this->__web(__FUNCTION__);
	}

	public function doWebYinxiang() {
		//这个操作被定义用来呈现 管理中心导航菜单
		$this->__web(__FUNCTION__);
	}

	public function doWebDianping() {
		//这个操作被定义用来呈现 管理中心导航菜单
		$this->__web(__FUNCTION__);
	}

	function sql_desc($table){
		$desc = pdo_fetchall("desc {$table}");
		foreach ($desc as $key => $value) {
			$str .= $value['Field'].",";
		}
		return trim($str,",");
	}
	
	function sql_ext($table='',$post,$fields){
		global $_W;
		if(empty($fields)){
			$desc = $this->sql_desc($table);
			$_fields = explode(',', $desc);
			foreach ($_fields as $key => $value) {
				$fields[$value]=$value;
			}
		}
		if(!is_array($fields)){
			return -1;
		}

		if($fields['weid'] && empty($post['weid'])){
			$post['weid'] = $_W['weid'];
		}

		if($fields['dateline'] && empty($post['dateline'])){
			$post['dateline'] = $_W['timestamp'];
		}

		if($post['id']){
			foreach($post as $k=>$v) {
				if(in_array($k, $fields)) $sql .= ",$k='$v'";
			}
			$sql = substr($sql, 1);
		}else{
			foreach($post as $k=>$v) {
				if(in_array($k, $fields)) { $sqlk .= ','.$k; $sqlv .= ",'$v'"; }
			}
			$sqlk = substr($sqlk, 1);
	   		$sqlv = substr($sqlv, 1);
		}
	    if($table){
	    	if($post['id']){
				$sql = "UPDATE {$table} SET $sql WHERE id = {$post['id']}";
	    	}else{
				$sql  = "INSERT INTO {$table} ($sqlk) VALUES ($sqlv)";
	    	}
	    	//echo $sql;
	    	$result = pdo_query($sql);
	    	if(!$post['id']){
	    		return pdo_insertid();
	    	}
	    	return $result;
	    }else{
	    	return array($sqlk,$sqlv);
	    }
	    
	}

	function daddslashes($string) {
		if(!is_array($string)) return addslashes($string);
		foreach($string as $key => $val) $string[$key] = $this->daddslashes($val);
		return $string;
	}

}