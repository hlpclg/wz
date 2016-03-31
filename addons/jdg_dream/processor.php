<?php
/**
 * 为梦想干杯模块处理程序
 *
 * @author GaoLi
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Jdg_dreamModuleProcessor extends WeModuleProcessor {
	public function respond() {
		
		//这里定义此模块进行消息处理时的具体过程, 请查看www.zheyitianShi.Com文档来编写你的代码
		global $_W;
        $rid = $this->rule;
	
        $sql = "SELECT * FROM " . tablename('dream_reply') . " WHERE `rid`=:rid LIMIT 1";
		$row = pdo_fetch($sql, array(':rid' => $rid));
	
		if ($row == false) {
            return $this->respText("活动已取消...");
        }
        if ($row['isshow'] == 0) {
            return $this->respText("活动未开始，请等待...");
        }
	    if ($row['endtime'] > time()) {
			
            return $this->respNews(array(
                        'Title' => $row['title'],
                        'PicUrl' => tomedia($row['picurl']),
                        'Url' =>$this->createMobileUrl('index', array('id' => $rid)),
            ));
        } else{
			
			return $this->respText("活动已结束，下次再来吧！");
		}
		
	}
}