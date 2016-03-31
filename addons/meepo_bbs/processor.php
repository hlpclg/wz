<?php /*折翼天使资源社区 www.zheyitianshi.com*/
/**
 * 微论坛模块处理程序
 *
 * @author meepo
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
define('ROOT_PATH', str_replace('processor.php', '', str_replace('\\', '/', __FILE__)));
define('INC_PATH',ROOT_PATH.'inc/');
if(file_exists(INC_PATH.'core/function/forum.func.php')){
	include INC_PATH.'core/function/forum.func.php';
}
class Meepo_bbsModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		$set = getSet();
		$content = $this->message['content'];
		$sql = "SELECT * FROM ".tablename('meepo_bbs_topics')." WHERE uniacid = '{$_W['uniacid']}' AND title LIKE '%$content%' ORDER BY rand() limit 9";
		$list = pdo_fetchall($sql);
		
		$news = array();
		foreach ($list as $li){
			$row = array();
			$row['title'] = $li['title'];
			$row['description'] = cutstr(strip_tags($li['content']), 35);
			$thumb = iunserializer($li['thumb']);
			$user = mc_fetch($li['uid']);
			$row['picurl'] = $thumb[0]?tomedia($thumb[0]):tomedia($user['avatar']);
			$row['url'] = $this->createMobileUrl('forum_topic',array('id'=>$li['id']));
			
			$news[] = $row;
		}
		if(empty($news)){
			return $this->respText(isset($set['set']['wechat'])?$set['set']['wechat']:'暂时没有找到相关帖子，换个关键词试试吧！');
		}
		return $this->respNews($news);
		
	}
}