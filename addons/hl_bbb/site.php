<?php
/**
 * 摇骰子吧抽奖模块
 *
 * [皓蓝] www.weixiamen.cn 5517286
 */
defined('IN_IA') or exit('Access Denied');
class Hl_bbbModuleSite extends WeModuleSite {
	public function doWebFormDisplay() {
		global $_W, $_GPC;
		$result = array('error' => 0, 'message' => '', 'content' => '');
		$result['content']['id'] = $GLOBALS['id'] = 'add-row-news-'.$_W['timestamp'];
		$result['content']['html'] = $this->template('item', TEMPLATE_FETCH);
		exit(json_encode($result));
	}

	public function doWebAwardlist() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);

		if (checksubmit('delete')) {
			if (empty($_GPC['select'])) {
				message('请选择需要删除的数据', referer(), 'error');
			}
			pdo_delete('bbb_user', ' id IN (' . implode(',', $_GPC['select']) . ')');
			message('删除成功！', $this->createWebUrl('awardlist', array('id' => $id, 'page' => $_GPC['page'])));
		}

		$where = ' WHERE `a`.`rid` = :rid AND `f`.`uniacid` = :uniacid';
		$params = array(':rid' => intval($_GPC['id']), ':uniacid' => $_W['uniacid']);
		$join = ' FROM ' . tablename('bbb_user') . ' AS `a` JOIN ' . tablename('mc_mapping_fans') . ' AS `f` ON
				`a`.`from_user` = `f`.`openid`';

		if (!empty($_GPC['nickname'])) {
			$where .= ' AND `f`.`nickname` LIKE :nickname';
			$params[':nickname'] = '%' . $_GPC['nickname'] . '%';
		}

		$sql = 'SELECT COUNT(*) ' . $join . $where;
		$total = pdo_fetchcolumn($sql, $params);

		if ($total > 0) {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;

			$sql = 'SELECT `a`.`id`, `a`.`points`, `a`.`createtime`, `f`.`nickname` ' . $join . $where . ' ORDER BY
					`a`.`points` DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
			$list = pdo_fetchall($sql, $params);

			$pager = pagination($total, $pindex, $psize);
		}

		if (!empty($_GPC['export'])) {
			/* 输入到CSV文件 */
			$html = "\xEF\xBB\xBF";

			/* 输出表头 */
			$filter = array(
				'nickname' => '昵称',
				'points' => '分数',
				'createtime' => '时间',
			);

			foreach ($filter as $key => $value) {
				$html .= $value . "\t,";
			}
			$html .= "\n";

			foreach ($list as $key => $value) {
				foreach ($filter as $index => $title) {
					if ($index != 'createtime') {
						$html .= $value[$index] . "\t, ";
					} else {
						$html .= date('Y-m-d H:i:s', $value[$index]) . "\t, ";
					}
				}
				$html .= "\n";
			}

			/* 输出CSV文件 */
			header("Content-type:text/csv");
			header("Content-Disposition:attachment; filename=全部数据.csv");
			echo $html;
			exit();

		}

		include $this->template('awardlist');
	}

	public function getCovers() {
		return array(
			array('title' => '第一期摇骰子', 'url' => $this->createWebUrl('first')),
		);
	}

	public function getHomeTiles() {
		global $_W;
		$urls = array();

		$sql = 'SELECT `id`, `rid`, `title` FROM ' . tablename('bbb_reply') . ' WHERE `uniacid` = :uniacid';
		$replies = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
		if (!empty($replies)) {
			foreach ($replies as $reply) {
				$urls[] = array('title' => $reply['title'], 'url' => $this->createMobileUrl('lottery', array('id' => $reply['rid'])));
			}
		}

		return $urls;
	}

	public function doMobileLottery() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		if (empty($_W['fans']['openid'])) {
			message('必须关注公众号才可以进入游戏', $this->createMobileUrl('info', array('id' => $id)), 'error');
		}
		$fromuser = $_W['fans']['openid'];
		$sql = 'SELECT COUNT(*) FROM ' . tablename('bbb_user') . ' WHERE `from_user` = :fromuser AND `rid` = :rid';
		$params = array(':fromuser' => $fromuser, ':rid' => $id);
		$isuser = pdo_fetchcolumn($sql, $params);

		//用户不存在，就插入
		if (!$isuser) {
			$bbb_user = array(
				'rid' => $id,
				'count' => 0,
				'points' => 0,
				'from_user' => $fromuser,
				'createtime' => TIMESTAMP,
			);
			pdo_insert('bbb_user', $bbb_user);
		}
		$bbb = pdo_fetch("SELECT * FROM " . tablename('bbb_reply') . " WHERE rid = '$id'");

		if ($bbb['start_time'] > TIMESTAMP) {
			$str = "活动于" . date('Y-m-d H:i') . " 开始!";
			message('活动没开始', $this->createMobileUrl('info', array('id' => $id)));
		}
		if ($bbb['end_time'] < TIMESTAMP) {
			message('活动已结束,稍等带你去看排名..', $this->createMobileUrl('rank', array('id' => $id)));
		}
		if (empty($bbb)) {
			message('非法访问，请重新发送消息进入摇骰子页面！');
		}

		$sql = 'SELECT `id`, `points`, `count` FROM ' . tablename('bbb_user') . ' WHERE `from_user` = :fromuser AND `rid` = :rid';
		$myuser = pdo_fetch($sql, $params);

		// 获取分享点数
		$shareUid = intval($_GPC['uid']);

		if (!empty($shareUid) && $myuser['id'] != $shareUid) {
			$sql = 'SELECT COUNT(*) FROM ' . tablename('bbb_share') . ' WHERE `rid` = :rid AND `uid` = :uid AND `share_uid`
			 		= :share_uid AND `createtime` = :createtime';
			$shareParams = array(':rid' => $id, ':uid' => $myuser['id'], ':share_uid' => $shareUid, ':createtime' => date('Ymd'));
			$shareTotal = pdo_fetchcolumn($sql, $shareParams);

			if (empty($shareTotal)) {
				$shareData = array('rid' => $id, 'uid' => $myuser['id'], 'share_uid' => $shareUid, 'createtime' => date('Ymd'));
				if (pdo_insert('bbb_share', $shareData)) {
					$sql = 'UPDATE ' . tablename('bbb_user') . ' SET `count` = `count` + 1 WHERE `id` = :id';
					pdo_query($sql, array(':id' => $shareUid));
				}
			}
		}

		$sql = 'SELECT COUNT(*) FROM ' . tablename('bbb_winner') . ' WHERE `createtime` > :createtime AND `from_user` = :fromuser
				AND `rid` = :rid';
		$params[':createtime'] = strtotime(date('Y-m-d'));
		$total = pdo_fetchcolumn($sql, $params);

		$arr_times = $this->get_today_times($total, $bbb['maxlottery'], $bbb['prace_times'], $myuser['count']);

		include $this->template('bbb');
	}

	public function doMobileInfo() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$sql = 'SELECT * FROM ' . tablename('bbb_reply') . ' WHERE `rid` = :rid';
		$bbb = pdo_fetch($sql, array(':rid' => $id));
		if (empty($bbb)) {
			message('非法访问，请重新发送消息进入摇骰子页面！');
		}

		load()->model('mc');
		mc_oauth_userinfo($_W['acid']);

		$bbb['descriptions'] = str_replace(array("\r\n"), "", $bbb['description']);
		$bbb['rule'] = preg_replace('/color:\s+\#\w+;/i', '', $bbb['rule']);
		$followInfo = empty($_W['fans']['openid']) ? '提示：必须关注公众号才可以进入游戏' : '';
		$sql = 'SELECT * FROM ' . tablename('bbb_user') . ' WHERE `rid` = :rid AND `from_user` = :from_user';
		$params = array(':rid' => $id, ':from_user' => $_W['fans']['openid']);
		$user = pdo_fetch($sql, $params);
		include $this->template('info');
	}

	public function doMobileRank() {
		global $_GPC, $_W;
		$fromuser = $_W['fans']['from_user'];
		$id = intval($_GPC['id']);
		$bbb = pdo_fetch("SELECT * FROM ".tablename('bbb_reply')." WHERE rid = '$id' LIMIT 1");
		$bbb['descriptions']=str_replace("\r","",$bbb['description']);
		$bbb['descriptions']=str_replace("\n","",$bbb['descriptions']);
		$showurl=1;
		if(!empty($fromuser)){
			$showurl=0;
			$sql="SELECT * FROM ".tablename('bbb_user')." WHERE  from_user = '$fromuser' AND rid = '$id' ";
			$myuser = pdo_fetch($sql);
			if($myuser){
				$sql="SELECT count(*) FROM ".tablename('bbb_user')." WHERE  rid = ".$id." and points >".$myuser['points'];
				$ph=pdo_fetchcolumn($sql);
				$myph=intval($ph)+1;
				if ($myph<12){
					$str=$myuser['points'].'点';
				}else{
					$str=$myph."名";
				}
			}
		}else{
			$str="";
		}
		if(empty($bbb['guzhuurl'])){
			$showurl=0;
		}
		$sql="select u.points,b.nickname from ".tablename('bbb_user')." as u
                                  LEFT JOIN " . tablename('mc_mapping_fans')." f on f.openid = u.from_user
                  LEFT JOIN " . tablename('mc_members') . " b ON b.uid = f.uid

                                                   where u.rid = '$id' order by u.points DESC ,u.id ASC limit 10";
		$allph=pdo_fetchall($sql);
		include $this->template('rank');
	}

	// 点击量统计
	public function doMobileUcount(){
		global $_GPC, $_W;
		$effective= true ;
		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
		if(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false ){
			$effective = false ;
		}

		$id = intval($_GPC['id']);
		$uid = intval($_GPC['uid']);
		if (!$uid) {
			$effective = false ;
		}
		$url=$this->createMobileUrl('rank', array('id' => $id));
		$replay = pdo_fetch("SELECT * FROM ".tablename('bbb_reply')." WHERE rid = '{$id}' LIMIT 1");
		$user = pdo_fetch("SELECT * FROM ".tablename('bbb_user')." WHERE id = '{$uid}' and rid = '{$id}'  LIMIT 1");
		if($uid && $effective){
			//cookies不存在
			if(!isset($_COOKIE["hlbbb"])){

				setcookie('hlbbb',1,time()+86400);
				$data = array(
					'count' => $user['count'] +1,
					'friendcount'=> $user['friendcount'] +1,
				);
				pdo_update('bbb_user', $data,array('id' => $uid));
			}

		}
		if(!empty($replay['guzhuurl'])){
			$url=$replay['guzhuurl'];
		}
		die('<script>location.href = "'.$url.'";</script>');

	}

	public function doMobileGetAward() {
		global $_GPC, $_W;
		$fromuser = $_W['fans']['from_user'];

		if (empty($fromuser)) {
			exit('非法参数1！');
		}
		$id = intval($_GPC['id']);
		$bbb = pdo_fetch("SELECT * FROM ".tablename('bbb_reply')." WHERE rid = '$id' LIMIT 1");

		if (empty($bbb)) {
			exit('非法参数2！');
		}
		$sql="SELECT COUNT(*) FROM ".tablename('bbb_winner')." WHERE createtime > '".strtotime(date('Y-m-d'))."' AND from_user = '$fromuser' and rid = '$id' ";
		$totals = pdo_fetchcolumn($sql);
		$myuser=pdo_fetch("SELECT id,points,count FROM ".tablename('bbb_user')." WHERE  from_user = '{$fromuser}' AND rid=".$id);

		$arr_times=$this->get_today_times($totals,$bbb['maxlottery'],$bbb['prace_times'],$myuser['count']);

		if ($arr_times['today_has'] <=0 ) {
			echo json_encode(array('level'=>1,'errmessage'=>'今天你的抽奖次数用完了,明天再来吧!'));
			exit;
		}

		//点数概率
		$level=array();
		$level['a']=rand(1,6);
		$level['b']=rand(1,6);
		$level['c']=rand(1,6);
		$level['d']=rand(1,6);
		$level['e']=rand(1,6);
		$level['f']=rand(1,6);
		$level['title']='bbb';
		$level['key']= $level['a'] + $level['b'] + $level['c'] + $level['d'] + $level['e'] + $level['f'] ;

		$user=array();
		$user['name']='ss';
		$user['num']=$arr_times['today_has']-1;
		$user['usercont']=$arr_times['todayalltimes'];
		$data=array(
			'rid'=>$id,
			'point'=>$level['key'],
			'from_user'=>$fromuser,
			'createtime'=>TIMESTAMP,
		);
		pdo_insert('bbb_winner', $data);

		if ($totals>=$bbb['maxlottery']){
			pdo_query("UPDATE  ".tablename('bbb_user')." SET count=count-1 , points=points+".$level['key']." WHERE from_user = '{$fromuser}' AND rid=".$id);
			$user['usercont']=$user['usercont']-1;

		}else{
			pdo_query("UPDATE  ".tablename('bbb_user')." SET points=points+".$level['key']." WHERE from_user = '{$fromuser}' AND rid=".$id);
		}

		$user['mytotal'] = $myuser['points']+ $level['key'];
		echo json_encode(array('user'=>$user,'level'=>$level,'errmessage'=>''));
		exit;

	}


	/*
	* $userhad 用户今天已使用
	* $maxlottery 每天系统送
	* $prace_times 每天最多奖励次数
	* $friedsend 朋友送
	* return array
	* today_has 今天还可以摇的次数
	* todayalltimes 剩余的次数
	*/
	public function get_today_times($userhad, $maxlottery, $prace_times, $friedsend){
		$arr = array(
			'today_has' => 0,
			'todayalltimes' => $friedsend,
		);
		if ($userhad >= ($maxlottery + $prace_times)) {
			$arr['today_has'] = 0;
			return $arr;
		}
		if (($userhad >= $maxlottery) && !$friedsend) {
			$arr['today_has'] = 0;
			return $arr;
		}
		if (($userhad + $friedsend) >= ($prace_times + $maxlottery)) {
			$arr['today_has'] = $prace_times + $maxlottery - $userhad;
			return $arr;
		}
		if ($userhad < $maxlottery) {
			if ($friedsend < $prace_times) {
				$arr['today_has'] = $maxlottery + $friedsend - $userhad;
			} else {
				$arr['today_has'] = $maxlottery + $prace_times - $userhad;

			}
		} else {
			if ($friedsend + $userhad > $maxlottery + $prace_times) {
				$arr['today_has'] = $maxlottery + $prace_times - $userhad;
			} else {
				$arr['today_has'] = $friedsend;
			}

		}
		return $arr;
	}

}
