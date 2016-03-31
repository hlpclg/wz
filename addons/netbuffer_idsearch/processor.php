<?php
defined ( 'IN_IA' ) or exit ( 'Access Denied' );
require_once IA_ROOT.'/addons/netbuffer_idsearch/ShowApi.class.php';
class netbuffer_idsearchModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W, $_GPC;
		if(!$this->inContext) {
			$this->beginContext();
			return $this->respText('请输入要查询的身份证号码:');
		} else {
			$id= trim($this->message['content']);
			$info=ShowApiSdk::getIDInfo($id);
			$str="";
			if($info!=""&&is_object($info)){
				$str="地址:".$info->address."\r\n"."生日:".$info->birthday."\r\n"."性别:".($info->sex=="F"?"男":"女");
			}
			if($str!=""&&strlen($str)>6){
				$this->endContext();
				return $this->respText("查询到的信息\r\n".$str);
			}else{
				return $this->respText("请确认您输入的号码是否正确，检查后重试吧");
			}
		}
	}
}
?>