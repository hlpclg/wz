<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;

$forum = getSet();

$act = trim($_GPC['act']);

if(empty($act)){
	//列表
	$sql = "SELECT * FROM ".tablename('meepo_bbs_rss')." WHERE uniacid = :uniacid ";
	$params = array(':uniacid'=>$_W['uniacid']);
	$lists = pdo_fetchall($sql,$params);
	
	foreach ($lists as $li){
		$li['time'] = date('Y-m-d',$li['time']);
		if($li['status'] == 0){
			$li['status'] = '不自动执行';
			$li['auto'] = $this->createWebUrl('index',array('doo'=>'index','op'=>'rss','act'=>'update','status'=>1,'id'=>$li['id']));
			$li['autotitle'] = '设置为自动'; 
		}
		if($li['status'] == 1){
			$li['status'] = '自动执行';
			$li['auto'] = $this->createWebUrl('index',array('doo'=>'index','op'=>'rss','act'=>'update','status'=>0,'id'=>$li['id']));
			$li['autotitle'] = '设置为手动';
		}
		
		$li['delete'] = $this->createWebUrl('index',array('doo'=>'index','op'=>'rss','act'=>'delete','id'=>$li['id']));
		$sql = "SELECT name FROM ".tablename('meepo_bbs_threadclass')." WHERE typeid = :typeid";
		$params = array(':typeid'=>$li['fid']);
		$li['threadclass'] = pdo_fetchcolumn($sql,$params);
		
		$li['post'] = $this->createWebUrl('index',array('doo'=>'index','op'=>'rss','act'=>'post','id'=>$li['id']));
		$li['rss'] = $this->createWebUrl('index',array('doo'=>'index','op'=>'rss','act'=>'rss','id'=>$li['id']));
		$list[] = $li;
	}
}

if($act == 'update'){
	$id = intval($_GPC['id']);
	if(empty($id)){
		message('参数错误',referer(),error);
	}
	$status = intval($_GPC['status']);
	pdo_update('meepo_bbs_rss',array('status'=>$status),array('id'=>$id));
	message('操作成功',$this->createWebUrl('index',array('doo'=>'index','op'=>'rss')),success);
}

if($act == 'post'){
	//添加
	$id = intval($_GPC['id']);
	$sql = "SELECT * FROM ".tablename('meepo_bbs_rss')." WHERE id = :id";
	$params = array(':id'=>$id);
	$setting = pdo_fetch($sql,$params);
	$cats = getCat();
	
	if($_W['ispost']){
		$data = array();
		$data['url'] = trim($_GPC['url']);
		$data['title'] = trim($_GPC['title']);
		$data['uniacid'] = $_W['uniacid'];
		$data['status'] = 0;
		$data['fid'] = intval($_GPC['fid']);
		
		if(empty($id)){
			pdo_insert('meepo_bbs_rss',$data);
		}else{
			pdo_update('meepo_bbs_rss',$data,array('id'=>$id));
		}
		message('操作成功',$this->createWebUrl('index',array('doo'=>'index','op'=>'rss')),success);
	}
}

if($act == 'delete'){
	//删除
	$id = intval($_GPC['id']);
	if(empty($id)){
		message('参数错误',referer(),error);
	}
	pdo_delete('meepo_bbs_rss',array('id'=>$id));
	message('操作成功',$this->createWebUrl('index',array('doo'=>'index','op'=>'rss')),success);
}

if($act == 'getdetail'){
	
	$resp = ihttp_get($_GPC['url']);
	$content = $resp['content'];
	
	//标题、作者、内容、出处
	require INC_PATH.'core/class/simple_html_dom.php';
	$html = new simple_html_dom();
	$html->load($content);
	
	$main = $html->find('.main');
	
	$titles = $html->find('h1');
	foreach($titles as $element){
		$title = $element->innertext;
	}
	
	$contents = $html->find('#Cnt-Main-Article-QQ');
	foreach($contents as $element){
		$content = $element->innertext;
	}
	$fid = $_GPC['fid'];
	$data = array();
	$data['title'] = iconv("GBK", "UTF-8", $title); 
	$data['content'] = htmlspecialchars(iconv("GBK", "UTF-8", $content));
	$data['fid'] = $fid;
	$data['uid'] = $forum['sysuid'];
	$data['uniacid'] = $_W['uniacid'];
	$data['tab'] = 'new';
	$data['last_reply_at'] = time();
	$data['createtime'] = time();
	
	if(empty($data['content']) || empty($data['content'])){
		message('抓取失败',referer(),success);
	}
	
	pdo_insert('meepo_bbs_topics',$data);
	message('抓取成功',$this->createWebUrl('forum_post',array('tid'=>pdo_insertid())),success);
}

if($act == 'rss'){
	//自动抓取
	$id = intval($_GPC['id']);
	if(empty($id)){
		message('参数错误',referer(),error);
	}
	
	if(empty($forum['sysuid'])){
		message('系统会员uid错误，请前往添加',$this->createWebUrl('set'),error);
	}
	$sql = "SELECT * FROM ".tablename('meepo_bbs_rss')." WHERE id = :id";
	$params = array(':id'=>$id);
	$rss = pdo_fetch($sql,$params);
	$url = $rss['url'];
	$items = rss_process($url);
	
	$fid = $rss['fid'];//版块
	
	$data = array();
	$data['fid'] = $fid;
	$data['uid'] = $forum['sysuid'];
	$data['uniacid'] = $_W['uniacid'];
	$data['tab'] = 'new';
	$data['last_reply_at'] = time();
	$data['createtime'] = time();
	
	foreach ($items as $item){
		$data['title'] = $item['title'];
		$data['content'] = htmlspecialchars_decode($item['description']);
		$sql = "SELECT * FROM ".tablename('meepo_bbs_topics')." WHERE title = :title AND fid = :fid";
		$params = array(':title'=>$item['title'],':fid'=>$fid);
		$exit = pdo_fetch($sql,$params);
		if(empty($exit)){
			$data['url'] = $item['link'];
			
			$data['post'] = $this->createWebUrl('index',array('doo'=>'index','op'=>'rss','act'=>'getdetail','fid'=>$fid,'url'=>$data['url']));
			$list[] = $data;
		}
	}
}

if(checksubmit('delete')){
	//删除
	$ids = $_GPC['select'];
	foreach ($ids as $id){
		pdo_delete('meepo_bbs_rss',array('id'=>intval($id)));
	}
	message('操作成功',$this->createWebUrl('index',array('doo'=>'index','op'=>'rss')),success);
}

if(checksubmit('rssall')){
	//抓取所有
	$ids = $_GPC['select'];
}

if(checksubmit('rss')){
	//抓取选取
	$sql = "SELECT * FROM ".tablename('meepo_bbs_rss')." WHERE uniacid = :uniacid ";
	$params = array(':uniacid'=>$_W['uniacid']);
	$lists = pdo_fetchall($sql,$params);
	
	foreach ($lists as $li){
		
	}
}


function object_to_array($obj)
{
	$_arr= is_object($obj) ? get_object_vars($obj) : $obj;
	foreach((array)$_arr as $key=> $val)
	{
		$val= (is_array($val) || is_object($val)) ? object_to_array($val) : $val;
		$arr[$key] = $val;
	}
	return$arr;
}

function rss_process($url){
	global $_W;
	load()->func('communication');
	
	$resp = ihttp_get($url);
	
	if($resp['code'] == 200){
		//获取成功
		//item = title / description / author / 
		$content = $resp['content'];
		
		$packet = array();
		if (!empty($content)){
			$obj = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA);
			
			if($obj instanceof SimpleXMLElement) {
				$channel = object_to_array($obj->channel);
				$items = $channel['item'];
			}
		}
		
		return $items;
	}else{
		return false;
	}
}
