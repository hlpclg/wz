<?php
/**
 * 拆红包模块处理程序
 *
 * @author 冯齐跃
 * @url http://fengqiyue.com/
 */
defined('IN_IA') or exit('Access Denied');

class Qiyue_luckymoneyModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$item = pdo_fetch("SELECT rid, title, description, picurl FROM ".tablename('qiyue_luckymoney')." WHERE rid=:rid", array(':rid'=>$this->rule));
        if($item){
	        return $this->respNews(array(
	            'title' => $item['title'],
	            'description' => $item['description'],
	            'picurl' => tomedia($item['picurl']),
	            'url' => $this->createMobileUrl('detail', array('rid' => $item['rid']))
	        ));
        }
	}
}