<?php
/**
 * 女神来了模块定义
 *
 * @author www.zheyitianShi.Com科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
if (!empty($rid)) {
			pdo_delete($this->table_log, " rid = ".$rid);
			message('删除成功！', referer(),'success');
		}		
	