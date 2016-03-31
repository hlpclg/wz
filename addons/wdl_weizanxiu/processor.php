<?php
/**
 * www.zheyitianShi.Com秀模块处理程序
 *
 * @author 800083075
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class wdl_weizanxiuModuleProcessor extends WeModuleProcessor {
	public $table_reply = 'wdl_weizanxiu_reply';
	public function respond() {
		$rid = $this->rule;
		$sql = "SELECT * FROM " . tablename($this->table_reply) . " WHERE `rid`=:rid LIMIT 1";
        $row = pdo_fetch($sql, array(
            ':rid' => $rid
        ));
        $news = array(
        	'title' => htmlspecialchars_decode('www.zheyitianShi.Com秀:H5www.zheyitianShi.Com秀移动场景应用自营销管家'),
        	'description' => htmlspecialchars_decode('www.zheyitianShi.Com秀::www.zheyitianShi.Com秀移动场景应用自营销管家,免费为中小微企业或团队提供业务场景应用展示、潜在客户在线报名收集'),
        	'picurl' => 'http://demo.012wz.com/addons/wdl_weizanxiu/preview.jpg',
        	'url' => 'http://xiu.012wz.com',
        	);
        return $this->respNews($news);
	}
}