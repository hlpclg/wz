<?php
/**
 * 祈福签模块微站定义
 *
 * @author 冯齐跃
 * @url http://www.admin9.com/
 */
defined('IN_IA') or exit('Access Denied');
class Qiyue_qiuqianModuleSite extends WeModuleSite {

	public function doMobileIndex() {
		global $_W, $_GPC;
		
		// 分享量
		if($_W['isajax'] && $_GPC['op']=='share'){
			pdo_query("UPDATE ".tablename('qiyue_qiuqian')." SET sharenum=sharenum+1 WHERE uniacid=:uniacid", array(':uniacid'=>$_W['uniacid']));
			message(array('error_code'=>0), '', 'ajax');
		}

		$qian_r = pdo_fetch("SELECT * FROM ".tablename('qiyue_qiuqian')." WHERE uniacid=:uniacid", array(':uniacid'=>$_W['uniacid']));
		$morepic = array();
		$imgcount = 0;
		if($qian_r['morepic']){
			$f_exp = "::::::";
			$r_exp = PHP_EOL;
			$rr = explode($r_exp, $qian_r['morepic']);
			$imgcount = count($rr);
			for ($i=0; $i < $imgcount; $i++) { 
				$fr = explode($f_exp, $rr[$i]);
				$morepic[] = array(
					'title' => $fr['1'],
					'url' => tomedia($fr['0'])
				);
			}
		}
		
		$_share = $this->module['config'];
		unset($_share['cnzzid']); // 去掉CNZZ
		$cnzzid = $this->module['config']['cnzzid'];

		// 增加浏览量
		pdo_query("UPDATE ".tablename('qiyue_qiuqian')." SET viewnum=viewnum+1 WHERE uniacid=:uniacid", array(':uniacid'=>$_W['uniacid']));
		include $this->template('index');
	}

}