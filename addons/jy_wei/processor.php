<?php
defined('IN_IA') or exit('Access Denied');

class Jy_weiModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$rid = $this->rule;
		$sql = "SELECT * FROM " . tablename('jy_wei_rule') . " WHERE ruleid = :id ";
		$reply = pdo_fetch($sql, array(':id'=>$rid));
		$sql = "SELECT * FROM " . tablename('jy_wei_company') . " WHERE id = :id ";
		$company = pdo_fetch($sql, array(':id'=>$reply['companyid']));
		$news[] = array(
				'title' => $company['sharetitle'],
				'description' => $company['sharedescription'],
				'picurl' => $company['shareimage'],
				'url' => $this->createMobileUrl('index', array('id' => $company['id'])),
			);
		return $this->respNews($news);
	
	}
}