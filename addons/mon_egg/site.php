<?php
/**
 * www.zheyitianShi.Com科技
 */
defined('IN_IA') or exit('Access Denied');
define("MON_EGG", "mon_egg");
define("MON_EGG_RES", "../addons/" . MON_EGG . "/");
require_once IA_ROOT . "/addons/" . MON_EGG . "/dbutil.class.php";
require IA_ROOT . "/addons/" . MON_EGG . "/oauth2.class.php";
require_once IA_ROOT . "/addons/" . MON_EGG . "/monUtil.class.php";

/**
 * Class Mon_BatonModuleSite
 */
class Mon_EggModuleSite extends WeModuleSite
{
	public $weid;
	public $acid;
	public $oauth;
	public static $USER_COOKIE_KEY = "__shakeuserv8";
	public static $USER_CB_PAGE_SIZE = 10;
	public static $STATUS_UNKNOW = 0;
	public static $STATUS_ZJ = 1;
	public static $STATUS_DH = 2;


	function __construct()
	{
		global $_W;
		$this->weid = $_W['uniacid'];
		$this->oauth = new Oauth2('', '');
	}

	public function doWebEggManage() {
		global $_W, $_GPC;
		$where = '';
		$params = array();
		$params[':weid'] = $this->weid;
		if (isset($_GPC['keyword'])) {
			$where .= ' AND `title` LIKE :keywords';
			$params[':keywords'] = "%{$_GPC['keyword']}%";
		}
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_EGG) . " WHERE weid =:weid " . $where . " ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_EGG) . " WHERE weid =:weid " . $where, $params);
			$pager = pagination($total, $pindex, $psize);
		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_EGG_SHARE, array("egid" => $id));
			pdo_delete(DBUtil::$TABLE_EGG_RECORD, array("egid" => $id));
			pdo_delete(DBUtil::$TABLE_EGG_PRIZE, array("egid" => $id));
			pdo_delete(DBUtil::$TABLE_EGG_USER, array("egid" => $id));
			pdo_delete(DBUtil::$TABLE_EGG, array("id" => $id));
			message('删除成功！', referer(), 'success');
		}
		include $this->template("egg_manage");
	}


	/**
	 * 接力用户
	 */
	public function  doWebuserList() {
		global $_W, $_GPC;
		$egid = $_GPC['egid'];
		$egg = DBUtil::findById(DBUtil::$TABLE_EGG, $egid);
		$where='';
		$params = array();
		$params[':egid'] =$egid;
		if (isset($_GPC['keyword'])) {
			$where .= ' AND (`tel` LIKE :keywords or nickname Like :keywords)';
			$params[':keywords'] = "%{$_GPC['keyword']}%";
		}
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_EGG_USER). " WHERE egid =:egid ".$where." ORDER BY createtime desc  LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_EGG_USER) . " WHERE egid =:egid ".$where, $params);
			$pager = pagination($total, $pindex, $psize);
		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_EGG_RECORD, array("uid" => $id));
			pdo_delete(DBUtil::$TABLE_EGG_SHARE, array("uid" => $id));
			pdo_delete(DBUtil::$TABLE_EGG_USER, array("id" => $id));
			message('删除成功！', referer(), 'success');
		}

		include $this->template("user_list");

	}

	/**
	 * author: weizan012 QQ:800083075
	 * 删除用户
	 */
	public function doWebDeleteUser() {
		global $_GPC, $_W;
		foreach ($_GPC['idArr'] as $k => $uid) {
			$id = intval($uid);
			if ($id == 0)
				continue;
			pdo_delete(DBUtil::$TABLE_EGG_RECORD, array("uid" => $id));
			pdo_delete(DBUtil::$TABLE_EGG_SHARE, array("uid" => $id));
			pdo_delete(DBUtil::$TABLE_EGG_USER, array("id" => $id));
		}

		echo json_encode(array('code'=>200));
	}

	/**
	 * author: weizan012 QQ:800083075
	 * 记录
	 */
	public function  doWebRecord_list() {
		global $_W, $_GPC;
		$egid = $_GPC['egid'];
		$egg = DBUtil::findById(DBUtil::$TABLE_EGG, $egid);
		$pid = $_GPC['pid'];
		$prizes = pdo_fetchall("select * from ".tablename(DBUtil::$TABLE_EGG_PRIZE)." where egid=:egid",array(":egid"=>$egid));
		$where = '';
		$params = array();
		$params[':egid'] = $egid;
		$status = $_GPC['status'];
		$ptype = $_GPC['ptype'];
		if ($_GPC['uid']!='') {
			$where .= ' AND r.uid =:uid';
			$params[':uid'] = $_GPC['uid'];

		}
		if ($pid != 0 && !empty($pid)) {
			$where .= ' AND r.pid =:pid';
			$params[':pid'] = $pid;

		}
        if ($status == '') {
			$status = -1;
		} 
        if ($status != -1) {
			$where .= ' AND r.status =:status';
			$params[':status'] = $status;
		}

		if ($ptype != 0 && !empty($ptype)) {
			$where .= ' AND p.ptype =:ptype';
			$params[':ptype'] = $ptype;
		}

        
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT r.*,r.id as rid, p.ptype,p.jf , p.pname as ppname, (select u.uname  from ".tablename(DBUtil::$TABLE_EGG_USER)." u where u.id= r.uid) as uname, (select u2.tel  from ".tablename(DBUtil::$TABLE_EGG_USER)." u2 where u2.id= r.uid) as tel,(select u3.nickname  from ".tablename(DBUtil::$TABLE_EGG_USER)." u3 where u3.id= r.uid) as nickname FROM " . tablename(DBUtil::$TABLE_EGG_RECORD) . " r left join ".tablename(DBUtil::$TABLE_EGG_PRIZE)." p on p.id = r.pid WHERE r.egid =:egid" . $where . " ORDER BY r.createtime DESC, r.id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn("SELECT count(*)  FROM " . tablename(DBUtil::$TABLE_EGG_RECORD) . " r left join ".tablename(DBUtil::$TABLE_EGG_PRIZE)." p on p.id = r.pid WHERE r.egid =:egid " . $where , $params);
			
			$pager = pagination($total, $pindex, $psize);
		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_EGG_RECORD, array("id" => $id));
			message('删除成功！', referer(), 'success');
		} else if ($operation == 'dh') {
			$id = $_GPC['id'];
			$record = DBUtil::findById(DBUtil::$TABLE_EGG_RECORD, $id);

			if ($record['status'] == self::$STATUS_ZJ) {
				$prize = DBUtil::findById(DBUtil::$TABLE_EGG_PRIZE, $record['pid']);
				if ($prize['ptype'] == 2) { //积分类型
					load()->model('mc');
					$uid = mc_openid2uid($record['openid']);
					$result = mc_credit_update($uid, 'credit1', $prize['jf'], array($_W['uid'],'砸金蛋后台管理兑换积分'));
					if ($result) {
						DBUtil::updateById(DBUtil::$TABLE_EGG_RECORD,array("status"=>self::$STATUS_DH,'dhtime'=>TIMESTAMP),$id);
						message('兑换成功！', referer(), 'success');
					} else {
						message("兑换失败!");
					}
				} else {
					DBUtil::updateById(DBUtil::$TABLE_EGG_RECORD,array("status"=>self::$STATUS_DH,'dhtime'=>TIMESTAMP),$id);
					message('兑换成功！', referer(), 'success');
				}


			}

		}

		include $this->template("record_list");

	}


	/**
	 * author: www.zheyitianShi.Com科技
	 * 记录
	 */
	public function  doWebShareList() {
		global $_W, $_GPC;
		$egid = $_GPC['egid'];
		$egg = DBUtil::findById(DBUtil::$TABLE_EGG, $egid);
		$where = '';
		$params = array();
		$params[':egid'] = $egid;

		if ($_GPC['openid']!='')
		{
			$where .= ' AND s.openid =:openid';
			$params[':openid'] = $_GPC['openid'];
		}

		if (isset($_GPC['keyword'])) {
			$where .= ' AND (u.`tel` LIKE :keywords or u.nickname Like :keywords)';
			$params[':keywords'] = "%{$_GPC['keyword']}%";
		}

		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("SELECT s.*,u.nickname nickname,u.tel as tel  FROM " . tablename(DBUtil::$TABLE_EGG_SHARE) . " s left join " . tablename(DBUtil::$TABLE_EGG_USER) . " u  on s.openid=u.openid WHERE s.egid =:egid  and u.egid=:egid " . $where . " ORDER BY s.createtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_EGG_SHARE) . " s left join".tablename(DBUtil::$TABLE_EGG_USER)." u on s.openid=u.openid WHERE s.egid =:egid and u.egid=:egid " . $where, $params);
			$pager = pagination($total, $pindex, $psize);
		} else if ($operation == 'delete') {
			$id = $_GPC['id'];
			pdo_delete(DBUtil::$TABLE_EGG_SHARE, array("id" => $id));
			message('删除成功！', referer(), 'success');
		}

		include $this->template("share_list");

	}



	/**
	 * author: weizan012 QQ:800083075
	 * 删除砸金蛋
	 */
	public function doWebDeleteEgg() {
		global $_GPC, $_W;

		foreach ($_GPC['idArr'] as $k => $bid) {
			$id = intval($bid);
			if ($id == 0)
				continue;
			pdo_delete(DBUtil::$TABLE_EGG_SHARE, array("egid" => $id));
			pdo_delete(DBUtil::$TABLE_EGG_RECORD, array("egid" => $id));
			pdo_delete(DBUtil::$TABLE_EGG_PRIZE, array("egid" => $id));
			pdo_delete(DBUtil::$TABLE_EGG_USER, array("egid" => $id));
			pdo_delete(DBUtil::$TABLE_EGG, array("id" => $id));
		}
		echo json_encode(array('code' => 200));
	}

	public function doWebDeleteRecord() {
		global $_GPC, $_W;

		foreach ($_GPC['idArr'] as $k => $bid) {
			$id = intval($bid);
			if ($id == 0)
				continue;
			pdo_delete(DBUtil::$TABLE_EGG_RECORD, array("id" => $id));

		}

		echo json_encode(array('code' => 200));
	}


	/**
	 * author: www.zheyitianShi.Com科技
	 * 删除奖品
	 */
	public function  doWebDeletePrize() {
		global $_GPC;
		$pid = $_GPC['pid'];
		$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_EGG_RECORD) . " WHERE  pid=:pid", array(':pid' => $pid));
		$res = array();
		if ($count > 0) {

			$res['code'] = 500;
		} else {
			$res['code'] = 200;
		}
		echo json_encode($res);

	}

	/**
	 * author: weizan012 QQ:246361982
	 * 删除分享
	 */
	public function  doWebDeleteShare() {
		global $_GPC;
		foreach ($_GPC['idArr'] as $k => $bid) {
			$id = intval($bid);
			if ($id == 0)
				continue;
			pdo_delete(DBUtil::$TABLE_EGG_SHARE, array("id" => $id));

		}

		echo json_encode(array('code' => 200));
	}



	/**
	 * author: weizan012 QQ:800083075
	 * 用户信息导出
	 */
	public function  doWebUDownload() {
		global $_W, $_GPC;
		$egid = $_GPC['egid'];
		$egg = DBUtil::findById(DBUtil::$TABLE_EGG, $egid);
		$where='';
		$params = array();
		$params[':egid'] =$egid;

		$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_EGG_USER). " WHERE egid =:egid ".$where." ORDER BY createtime desc  ", $params);
        $i = 0;
		foreach ($list as $key => $value) {
			$arr[$i]['openid'] = $value['openid'];
			$arr[$i]['nickname'] = $value['nickname'];
			$arr[$i]['uname'] = $value['uname'];
			$arr[$i]['tel'] = $value['tel'];
			$arr[$i]['createtime'] = date('Y-m-d H:i:s', $value['createtime']);
			$i++;
		}
        MonUtil::exportexcel($arr, array('openID', '昵称', '姓名', '手机', '参加时间'), '参加用户');
	}

	/**
	 * author: www.zheyitianShi.Com科技
	 * 中奖记录导出
	 */
	public function  doWebRDownload() {
		global $_W, $_GPC;
		$egid = $_GPC['egid'];
		$pid = $_GPC['pid'];
		$where = '';
		$params = array();
		$params[':egid'] = $egid;
		$status = $_GPC['status'];
		$ptype = $_GPC['ptype'];
		if ($_GPC['uid']!='') {
			$where .= ' AND r.uid =:uid';
			$params[':uid'] = $_GPC['uid'];

		}
		if ($pid != 0 && !empty($pid)) {
			$where .= ' AND r.pid =:pid';
			$params[':pid'] = $pid;

		}
        if ($status == '') {
			$status = -1;
		} 
        if ($status != -1) {
			$where .= ' AND r.status =:status';
			$params[':status'] = $status;
		}

		if ($ptype != 0 && !empty($ptype)) {
			$where .= ' AND p.ptype =:ptype';
			$params[':ptype'] = $ptype;
		}


		$list = pdo_fetchall("SELECT r.*,r.id as rid, p.ptype,p.jf , p.pname as ppname, (select u.uname  from ".tablename(DBUtil::$TABLE_EGG_USER)." u where u.id= r.uid) as uname, (select u2.tel  from ".tablename(DBUtil::$TABLE_EGG_USER)." u2 where u2.id= r.uid) as tel,(select u3.nickname  from ".tablename(DBUtil::$TABLE_EGG_USER)." u3 where u3.id= r.uid) as nickname FROM " . tablename(DBUtil::$TABLE_EGG_RECORD) . " r left join ".tablename(DBUtil::$TABLE_EGG_PRIZE)." p on p.id = r.pid WHERE r.egid =:egid" . $where . " ORDER BY r.createtime DESC, r.id DESC ", $params);
		
		
		$i = 0;

		foreach ($list as $key => $value) {
			$arr[$i]['openid'] = $value['openid'];
			$arr[$i]['nickname'] = $value['nickname'];
			$arr[$i]['uname'] = $value['uname'];
			$arr[$i]['tel'] = $value['tel'];
			if ($value['ptype'] == 1 ) {
				$arr[$i]['ptype'] = '实物';
			}

			if ($value['ptype'] == 2 ) {
				$arr[$i]['ptype'] = '积分';
			}

			if ($value['ptype'] == 1 ) {
				$arr[$i]['jf'] = '-';
			} else if($value['ptype'] == 2) {
				$arr[$i]['jf'] = $value['jf'];
			}



            if ($value['status'] == self::$STATUS_UNKNOW) {
				$arr[$i]['status'] = '未中奖';
			}

			if ($value['status'] == self::$STATUS_ZJ) {
				$arr[$i]['status'] = '已中奖';
			}

			if ($value['status'] == self::$STATUS_DH) {
				$arr[$i]['status'] = '已兑换';
			}

			$arr[$i]['createtime'] = date('Y-m-d H:i:s', $value['createtime']);
			$arr[$i]['dhtime'] = date('Y-m-d H:i:s', $value['dhtime']);
			$i++;
		}

		MonUtil::exportexcel($arr, array('openID', '昵称', '姓名', '手机', '物品类型', '积分', '状态', '中奖时间', '兑换时间'), '中奖记录');
	}


	/**********************************************************
	手机
	 * author: www.zheyitianShi.Com科技
	 */

	public function doMobileIndex() {
		global $_W, $_GPC;
		MonUtil::checkmobile();
        $egid = $_GPC['egid'];
		$egg = DBUtil::findById(DBUtil::$TABLE_EGG,  $egid);
		MonUtil::emtpyMsg($egg, "砸金蛋活动删除或不存在！");
		$prizes = pdo_fetchall("select * from ".tablename(DBUtil::$TABLE_EGG_PRIZE)." where egid=:egid order by display_order asc ",array(":egid"=>$egid));
		$openid = $this->getOpenId();
		$dbUser = DBUtil::findUnique(DBUtil::$TABLE_EGG_USER, array(":egid" => $egid, ":openid" => $openid));
		if (!empty($dbUser)) {
			$myawards = pdo_fetchall("select p.pname as ppname, r.* from " . tablename(DBUtil::$TABLE_EGG_RECORD). " r left join  "
				.tablename(DBUtil::$TABLE_EGG_PRIZE)." p on r.pid = p.id " . "
			where r.egid=:egid and r.uid =:uid and r.status <>:status
			order by  createtime desc  ",array(":egid" => $egid, ":uid" => $dbUser['id'], ":status" => self::$STATUS_UNKNOW));
		}

		$recordCount = $this->getLeftCountAndShare($egg, $openid);
		$leftCount = $recordCount[0];
		$shareTip = $recordCount[1];

        $userCredit = $this->getUserSystemCredit();



		$follow = 1;
		if (!empty($_W['fans']['follow'])){
			$follow=2;
		}
		//$follow = 1;
		include $this->template("index");
	}

	function getUserSystemCredit() {
		load()->model('mc');
		$uid = mc_openid2uid($this->getOpenId());
		$result = mc_credit_fetch($uid);
		return $result['credit1'];
	}

	/**
	 * author: www.zheyitianShi.Com科技
	 * 砸蛋
	 */
	public function doMobileAjaxSmashing() {
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$egid = $_GPC['egid'];
		$egg = DBUtil::findById(DBUtil::$TABLE_EGG, $egid);
		$res = array();
		if (empty($egg)) {
			$res['code'] = 500;
			$res['msg'] = "活动删除或不存在";
            die(json_encode($res));
		}

		if (TIMESTAMP < $egg['starttime']) {
			$res['code'] = 501;
			$res['msg'] = "活动还未开始";
			die(json_encode($res));
		}

		if (TIMESTAMP > $egg['endtime']) {
			$res['code'] = 503;
			$res['msg'] = "活动已结束开始";
			die(json_encode($res));
		}

		$openid = $this->getOpenId();

		if (empty($openid)) {
			$res['code'] = 504;
			$res['msg'] = "请授权登录后再砸蛋哦";
			die(json_encode($res));
		}

		$leftCount = $this->getLeftCountAndShare($egg, $openid);

		if ($leftCount <= 0) {
			$res['code'] = 506;
			$res['msg'] = "您今天的机会已用完，下次再来参加吧!";
			die(json_encode($res));
		}

		$dbUser = DBUtil::findUnique(DBUtil::$TABLE_EGG_USER, array(":egid" => $egid, ":openid" => $openid));

		$uid = '';
		if (empty($dbUser)) {
			$userInfo = $this->getClientUserInfo($openid);
			$userData = array(
				'egid' => $egid,
				'openid' => $openid,
				'nickname' => $userInfo['nickname'],
				'headimgurl' => $userInfo['headimgurl'],
				'createtime' => TIMESTAMP
			);
			DBUtil::create(DBUtil::$TABLE_EGG_USER, $userData);
			$uid = pdo_insertid();
		} else {
			$uid = $dbUser['id'];
		}


		$recordCount = $this->getLeftCountAndShare($egg, $openid);

		if ($recordCount[0] == 0) {
			$res['code'] = 200;
			$recordCount = $this->getLeftCountAndShare($egg, $openid);
			$res['left'] = $recordCount[0];
			$res['shareTip'] = $recordCount[1];
			die(json_encode($res));

		}


		//用户中奖次数
		$userPrizeCount = $this->findUserPrizeCount($egg['id'], $openid);

       //	createRecord($uid, $egid, $pid, $status, $openid,$pname)
		if ($userPrizeCount >= $egg ['prize_limit']) {  //已超过中奖次数
			$this->createRecord($uid, $egid, 0, self::$STATUS_UNKNOW, $openid, '');
			$res['code'] = 200;
			$recordCount = $this->getLeftCountAndShare($egg, $openid);
			$res['left'] = $recordCount[0];
			$res['shareTip'] = $recordCount[1];
			die(json_encode($res));

		}


		$prizes = pdo_fetchall("select * from " . tablename(DBUtil::$TABLE_EGG_PRIZE) . " where egid=:egid  order by pb asc ", array(":egid" => $egid));
		$arrayRand = array();
		$totalRand = 0;
		for ($index = 0; $index < count($prizes); $index++) {
			$arrayRand[$index] = $prizes[$index]['pb'];
			$totalRand += $arrayRand[$index];
		}

		$arrayRand[count($prizes)] = 10000 - $totalRand;//不中奖概率计算

		$pIndex = $this->get_rand($arrayRand);//随机

		if ($pIndex == count($prizes)) { //没有中奖
			$this->createRecord($uid, $egid, 0, self::$STATUS_UNKNOW, $openid, '');
			$res['code'] = 200;
			$recordCount = $this->getLeftCountAndShare($egg, $openid);
			$res['left'] = $recordCount[0];
			$res['shareTip'] = $recordCount[1];
			die(json_encode($res));
		} else {//中奖
			$prize = $prizes[$pIndex];
			$przie_count = $this->findPrizeAwardCount($egid,$prize['id']);

			if ($przie_count >= $prize['pcount'] ) { //超过数量了
				$this->createRecord($uid, $egid, 0, self::$STATUS_UNKNOW, $openid, '');
				$res['code'] = 200;
				$recordCount = $this->getLeftCountAndShare($egg, $openid);
				$res['left'] = $recordCount[0];
				$res['shareTip'] = $recordCount[1];
				die(json_encode($res));
			} else {
				$this->createRecord($uid, $egid, $prize['id'], self::$STATUS_ZJ, $openid,$prize['pname']);
				$res['code'] = 250;
				$recordCount = $this->getLeftCountAndShare($egg, $openid);
				$res['left'] = $recordCount[0];
				$res['shareTip'] = $recordCount[1];
				$muserInfo = DBUtil::findById(DBUtil::$TABLE_EGG_USER, $uid);
				$res['plevel'] = $prize['plevel'];
				$res['pimg'] = $prize['pimg'];
				$res['pname'] = $prize['pname'];
				if (empty($muserInfo['uname'])) {
					$res['uname'] = '';
				} else {
					$res['uname'] = $muserInfo['uname'];
				}

				if (empty($muserInfo['tel'])) {
					$res['tel'] = '';
				} else {
					$res['tel'] = $muserInfo['tel'];
				}

				die(json_encode($res));
			}
		}

		$res['code'] = 1000;
		$res['msg'] = '未知错误';
		echo json_encode($res);
	}

	public function doMobileAjaxUserAwards() {
		global $_W, $_GPC;
		$egid = $_GPC['egid'];
		$page = max(1, intval($_GPC['page']));
		$page_size = 50;
		$start = 0+ ($page - 1) * $page_size;
		$userwards = pdo_fetchall("select p.pname as ppname, r.*, (select tel  from ".tablename(DBUtil::$TABLE_EGG_USER)." u where u.id = r.uid ) as utel  from " . tablename(DBUtil::$TABLE_EGG_RECORD). " r left join  "
			.tablename(DBUtil::$TABLE_EGG_PRIZE)." p on r.pid = p.id " . "
			where r.egid=:egid  and r.status <>:status
			order by  createtime desc  limit ".$start . "," . $page_size ,array(":egid" => $egid, ":status" => self::$STATUS_UNKNOW));
		include $this->template("award_list");
	}

	/**
	 * author: www.zheyitianShi.Com科技
	 * 兑换
	 */
	public function doMobileDH() {
		global $_W, $_GPC;
		MonUtil::checkmobile();
		$rid = $_GPC['rid'];
		$egid = $_GPC['egid'];
		$dpassword = $_GPC['dpassword'];
		$record = DBUtil::findById(DBUtil::$TABLE_EGG_RECORD,$rid);
		$res =array();
		if (empty($record)) {
			$res['code'] = 500;
			$res['msg'] = "记录删除或不存在";
			die(json_encode($res));
		}
		$egg = DBUtil::findById(DBUtil::$TABLE_EGG, $egid);
		if ($record['status'] == self::$STATUS_DH) {
			$res['code'] = 500;
			$res['msg'] = "奖品已经兑换过";
			die(json_encode($res));
		}

		if ($egg['dpassword'] == $dpassword) {//密码正确

			$prize = DBUtil::findById(DBUtil::$TABLE_EGG_PRIZE, $record['pid']);
			if ($prize['ptype'] == 2) { //积分类型
				load()->model('mc');
				$uid = mc_openid2uid($this->getOpenId());
				$result = mc_credit_update($uid, 'credit1', $prize['jf'], array($uid,'砸金蛋手机端兑换积分'));
				if ($result) {
					DBUtil::updateById(DBUtil::$TABLE_EGG_RECORD,array("status"=>self::$STATUS_DH,'dhtime'=>TIMESTAMP), $rid);
					$res['code'] = 200;
					die(json_encode($res));
				} else {
					$res['code'] = 500;
					$res['msg'] = "兑换失败";
					die(json_encode($res));
				}
			} else {
				DBUtil::updateById(DBUtil::$TABLE_EGG_RECORD,array("status"=> self::$STATUS_DH,'dhtime'=>TIMESTAMP), $rid);
				$res['code'] = 200;
				die(json_encode($res));
			}

		} else {
			$res['code'] = 500;
			$res['msg'] = "密码错误";
			die(json_encode($res));
		}

	}

	public function  getOpenId() {
		global $_W;

		//return  "o_-Hajq-MxgT-pvJX7gRMswH8_eM";
		return $_W['fans']['from_user'];
	}

	public function createRecord($uid, $egid, $pid, $status, $openid,$pname)
	{
		$recordData = array(
			'egid' => $egid,
			'pid' => $pid,
			'uid' => $uid,
			'openid' => $openid,
			'status' => $status,
			'pname' => $pname,
			'createtime' => TIMESTAMP
		);
		DBUtil::create(DBUtil::$TABLE_EGG_RECORD, $recordData);

	}

	public function getClientUserInfo($openid)
	{
		global $_W;
		if (!empty($openid) && ($_W['account']['level'] == 3 || $_W['account']['level'] == 4)) {
			load()->classs('weixin.account');
			$accObj = WeixinAccount::create($_W['acid']);
			$access_token = $accObj->fetch_token();

			if (empty($access_token)) {
				message("获取accessToken失败");
			}
			$userInfo = $this->oauth->getUserInfo($access_token, $openid);
			MonUtil::setClientCookieUserInfo($userInfo, $this::$USER_COOKIE_KEY);
			return $userInfo;
		}
	}

	/**
	 * 概率计算
	 *
	 * @param unknown $proArr
	 * @return Ambigous <string, unknown>
	 */
	function get_rand($proArr)
	{
		$result = '';
		// 概率数组的总概率精度
		$proSum = array_sum($proArr);
		// 概率数组循环
		foreach ($proArr as $key => $proCur) {
			$randNum = mt_rand(1, $proSum); // 抽取随机数
			if ($randNum <= $proCur) {
				$result = $key; // 得出结果
				break;
			} else {
				$proSum -= $proCur;
			}
		}
		unset($proArr);
		return $result;
	}

	/**
	 * author: weizan012 QQ:800083075
	 * @param $sid
	 * @param $openid
	 * @return bool 查找分享次数
	 */
	public function  findUserDayShareCount($egid, $openid) {
		$today_beginTime = strtotime(date('Y-m-d' . '00:00:00', TIMESTAMP));
		$today_endTime = strtotime(date('Y-m-d' . '23:59:59', TIMESTAMP));
		$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_EGG_SHARE) . " WHERE  egid=:egid
		and openid=:openid and createtime<=:endtime and  createtime>=:starttime ", array(':egid' => $egid, ":openid" => $openid, ":endtime" => $today_endTime, ":starttime" => $today_beginTime));
		return $count;
	}

	/**
	 * author: weizan012 QQ:800083075
	 * @param $sid
	 * @param $openid
	 * @return bool
	 */
	public function  findUserDayAward($sid, $openid) {
		$today_beginTime = strtotime(date('Y-m-d' . '00:00:00', TIMESTAMP));
		$today_endTime = strtotime(date('Y-m-d' . '23:59:59', TIMESTAMP));
		$count = pdo_fetchcolumn('SELECT sum(award_count) FROM ' . tablename(DBUtil::$TABLE_QMSHAKE_SHARE) . " WHERE  sid=:sid and openid=:openid and createtime<=:endtime and  createtime>=:starttime ", array(':sid' => $sid, ":openid" => $openid, ":endtime" => $today_endTime, ":starttime" => $today_beginTime));
		return $count;
	}

	/**
	 * author: weizan012 QQ:800083075
	 * @param $sid
	 * @param $pid
	 * @return bool中奖次数
	 */
	public function  findPrizeAwardCount($egid, $pid) {
		$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_EGG_RECORD) . " WHERE  egid=:egid and pid=:pid ", array(':egid' => $egid, ":pid" => $pid));
		return $count;
	}

	/**
	 * author: weizan012 QQ:800083075
	 * @param $sid
	 * @param $openid
	 * @return bool
	 * 中奖次数
	 */
	public function  findUserPrizeCount($egid, $openid) {
		$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_EGG_RECORD) . " WHERE  egid=:egid and openid=:openid and (status=2 or status=1)", array(':egid' => $egid, ":openid" => $openid));
		return $count;
	}

	/**
	 * author: weizan012 QQ:800083075
	 * @param $sid
	 * @param $openid
	 * @return bool每天次数
	 */
	public function  findUserDayRecordCount($egid, $openid) {
		$today_beginTime = strtotime(date('Y-m-d' . '00:00:00', TIMESTAMP));
		$today_endTime = strtotime(date('Y-m-d' . '23:59:59', TIMESTAMP));
		$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_EGG_RECORD) . " WHERE  egid=:egid and openid=:openid and createtime<=:endtime and  createtime>=:starttime ", array(':egid' => $egid, ":openid" => $openid, ":endtime" => $today_endTime, ":starttime" => $today_beginTime));
		return $count;
	}


	/**
	 * author: www.zheyitianShi.Com科技
	 * 用户分享
	 */
	public  function doMobileUserShare() {
		global $_W, $_GPC;
		$egid = $_GPC['egid'];
		$egg = DBUtil::findById(DBUtil::$TABLE_EGG, $egid);

		$openid = $this->getOpenId();
		if (!empty($egg) && $egg['share_enable'] == 1 && !empty($openid)) {

			$user =DBUtil::findUnique(DBUtil::$TABLE_EGG_USER,array(":egid"=>$egid,":openid" => $openid)) ;

			if (!empty($user)) {
				$user_DayShare = $this->findUserDayShareCount($egg['id'],$openid);
				$userDayCount = $this->findUserDayRecordCount($egg['id'], $openid);//查找用户今天的次数
				//分享奖励次数还有，并且  用户抽奖次数大于等于 每天限制基本次数
				if (($user_DayShare < $egg['share_times']) && $userDayCount >= $egg['day_count']) {

						$shareData = array(
							'egid' =>$egid,
							'uid' =>$user['id'],
							'openid' =>$openid,
							'createtime' => TIMESTAMP
						);

					DBUtil::create(DBUtil::$TABLE_EGG_SHARE, $shareData);

					die(json_encode(array('code'=>250,'leftShare'=>$egg['share_times']-1-$user_DayShare,'awardCount'=>$egg['share_award_count'])));
				}
			}
		}

		die(json_encode(array('code'=>200)));
	}
	/**
	 * author: weizan012 QQ:800083075
	 * 绑定用户
	 */
	public function doMobileBindUser() {
		global $_W, $_GPC;
		$egid = $_GPC['egid'];
		$tel = $_GPC['tel'];
		$uname = $_GPC['uname'] ;
		$openid = $this->getOpenId();
		$user = DBUtil::findUnique(DBUtil::$TABLE_EGG_USER, array(":egid" => $egid, ":openid" => $openid));
		$res =array();
		if (empty($user)) {
			$res['code'] = 500;
			$res['msg'] = "用户不存在";
			die(json_encode($res));
		}
		DBUtil::updateById(DBUtil::$TABLE_EGG_USER, array('tel'=>$tel, 'uname' => $uname), $user['id']);
		$res['code'] = 200;
		die(json_encode($res));
	}
	/***************************函数********************************/
	/**
	 * author: weizan012 QQ:800083075
	 * @param $kid
	 * @param $status
	 * @return bool数量
	 */

	function  encode($value) {
		return $value;
		return iconv("utf-8", "gb2312", $value);

	}

	/**
	 * author: www.zheyitianShi.Com科技
	 * @param $egg
	 * @param $openid
	 * @return array
	 * 计算获取剩余次数及分享提示
	 */
	public  function getLeftCountAndShare($egg, $openid) {
		$userDayCount = $this->findUserDayRecordCount($egg['id'], $openid);//查找用户今天的次数
		$shareTip = '';
		if ($egg['share_enable'] ==1) {//开启了分享
			$shareCount = $this->findUserDayShareCount($egg['id'], $openid);
			$leftShare = $egg['share_times'] - $shareCount;
			//用户基本次数已经用完
			if ($userDayCount >= $egg['day_count'] && $leftShare > 0) {
				$shareTip ="您还有".$leftShare."次分享奖励机会，每次分享奖励".$egg['share_award_count']."次机会";
			}

			if ($shareCount > 0) {//已经分享了
				$totalCount = $shareCount * $egg['share_award_count'] + $egg['day_count'];//总机会
				$leftCount = $totalCount - $userDayCount;
			} else {//还没分享
				$leftCount = $egg['day_count'] - $userDayCount;
			}

		} else {
			$leftCount = $egg['day_count'] - $userDayCount;
		}

		if ($leftCount < 0) {
			$leftCount = 0;
		}

		return array($leftCount, $shareTip);

	}

}