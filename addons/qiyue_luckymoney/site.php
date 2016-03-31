<?php
/**
 * 拆红包模块微站定义
 *
 * @author 冯齐跃
 * @url http://fengqiyue.com/
 */
defined('IN_IA') or exit('Access Denied');

class Qiyue_luckymoneyModuleSite extends WeModuleSite {

	public function doWebList(){
		global $_W, $_GPC;
		$state = intval($_GPC['state']);
		$rid = intval($_GPC['id']);
		if(empty($rid)){
			message('缺少参数', '', 'error');
		}
		$item = pdo_fetch("SELECT * FROM ".tablename('qiyue_luckymoney')." WHERE rid=:rid", array(':rid'=>$rid));
		if (empty($item)){
			// message('该活动不存在', '', 'error');
		}
		$result=array();
		$pindex = max(1, intval($_GPC['page']));
		$psize = 50;
		$where = "WHERE `rid` = :rid";
		$paras = array();
		$paras[':rid'] = $rid;
		if($state==1){
			$where.=' AND opennum >= 4';
		}

		$sql = "SELECT * FROM ".tablename('qiyue_luckymoney_fans'). $where. ' ORDER BY id DESC';
		$result['list'] = pdo_fetchall($sql . " LIMIT " . ($pindex - 1) * $psize .',' .$psize, $paras);
		$result['total'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('qiyue_luckymoney_fans') . $where, $paras);
		$result['pager'] = pagination($result['total'], $pindex, $psize);
		include $this->template('list');
	}

	public function doMobileDetail() {
		global $_W, $_GPC, $do;
		$rid = intval($_GPC['rid']);
		if(empty($rid)){
			message('缺少参数', '', 'error');
		}
		$item = pdo_fetch("SELECT * FROM ".tablename('qiyue_luckymoney')." WHERE rid=:rid", array(':rid'=>$rid));
		if (empty($item)){
			message('该活动不存在', '', 'error');
		}

		// oauth 授权
		load()->model('mc');
		$member = mc_fetch($_W['member']['uid'], array('uid', 'avatar'));
		if (empty($member['avatar'])) {
			mc_oauth_userinfo();
		}

		$row = pdo_fetch("SELECT * FROM ".tablename('qiyue_luckymoney_fans')." WHERE uid=:uid AND rid=:rid", array(':uid'=>$_W['member']['uid'], ':rid'=>$rid));
		if (empty($row)) {
			pdo_insert('qiyue_luckymoney_fans', array('uid'=>$_W['member']['uid'], 'rid'=>$rid, 'prize'=>'新春对联'));
			$row['opennum'] = 0;
		}

		$isreceive = 0;
		if($row['opennum']>=4 && $row['nickname'] && $row['mobile']){
			$isreceive = 1;
		}

		// 还要多少人拆
		$row['opennum'] = abs($row['opennum'] - 4);
		// 助力的好友
		$friend_list = array();
		if($row['friends']){
			$friends = explode(',', $row['friends']);
			$friend_list = pdo_fetchall("SELECT nickname,avatar FROM ".tablename('mc_members')." WHERE uid IN ('".implode("','", array_filter($friends))."')", array(), 'uid');
			// $friends = explode(',', $row['friends']);
			// $friend_list = mc_fetch(array_filter($friends), array('nickname', 'avatar'));
		}
		$jsconfig = $_W['account']['jssdkconfig'];
		$_share=array(
			'title' => $item['share_title'],
			'desc' => $item['share_desc'],
			'imgurl' => $item['share_imgurl'],
			'link' => $item['share_link'],
		);
		include $this->template('detail');
	}

	public function doMobileShare() {
		global $_W, $_GPC, $do;
		$rid = intval($_GPC['rid']);
		if(empty($rid)){
			message('缺少参数', '', 'error');
		}
		$item = pdo_fetch("SELECT * FROM ".tablename('qiyue_luckymoney')." WHERE rid=:rid", array(':rid'=>$rid));
		if (empty($item)){
			message('该活动不存在', '', 'error');
		}

		$uid = intval($_GPC['uid']);
		if(empty($uid)){
			header("Location: ".$this->createMobileUrl('detail', array('rid'=>$rid), true));
			exit;
		}
		// 被助力粉丝信息
		$fans = pdo_fetch("SELECT * FROM ".tablename('qiyue_luckymoney_fans')." WHERE uid=:uid AND rid=:rid", array(':uid'=>$uid, ':rid'=>$rid));
		if (empty($fans)){
			message('该好友没有参加活动', '', 'error');
		}
		$fans = array_merge($fans, mc_fetch($uid, array('uid', 'avatar')));
		$jsconfig = $_W['account']['jssdkconfig'];
		$_share=array(
			'title' => $item['share_title'],
			'desc' => $item['share_desc'],
			'imgurl' => $item['share_imgurl'],
			'link' => $item['share_link'],
		);
		include $this->template('detail');
	}

	public function doMobileOpen() {
		global $_W, $_GPC;
		if(!$_W['isajax']){
			exit('来路非法');
		}

		$rid = intval($_GPC['rid']);
		if(empty($rid)){
			exit('缺少参数');
		}
		$item = pdo_fetch("SELECT * FROM ".tablename('qiyue_luckymoney')." WHERE rid=:rid", array(':rid'=>$rid));
		if (empty($item)){
			exit('该活动不存在');
		}

		$uid = intval($_GPC['uid']);
		if(empty($uid)){
			exit('会员ID为空');
		}

		// 被助力粉丝信息
		$fans = pdo_fetch("SELECT * FROM ".tablename('qiyue_luckymoney_fans')." WHERE uid=:uid AND rid=:rid", array(':uid'=>$uid, ':rid'=>$rid));
		if (empty($fans)){
			exit('该好友没有参加活动');
		}

		// 非本人进来时，进行助力
		if($_W['member']['uid']!=$uid){
			if( $fans['opennum'] < 4 && !strstr($fans['friends'],','.$_W['member']['uid'].',') )
			{
				if(empty($fans['friends'])){
					$newuids=','.$_W['member']['uid'].',';
				}
				else{
					$newuids=$fans['friends'].$_W['member']['uid'].',';
				}
				$usql=pdo_query("UPDATE ".tablename('qiyue_luckymoney_fans')." SET opennum=opennum+1, friends='".$newuids."' WHERE id=:id", array(':id'=>$fans['id']));
			}
			exit('ok');
		}

		// 本人拆开 抽产逻辑
		echo $rand = 1;
		exit();
	}

	public function doMobileRegister(){
		global $_W, $_GPC;
		$rid = intval($_GPC['rid']);
		$uid = $_W['member']['uid'];
		if(empty($rid)){
			message('缺少参数', '', 'error');
		}
		$item = pdo_fetch("SELECT * FROM ".tablename('qiyue_luckymoney')." WHERE rid=:rid", array(':rid'=>$rid));
		if (empty($item)){
			message('该活动不存在', '', 'error');
		} 
		if($_W['isajax']){
			if(empty($_W['member']['uid'])){
				message(array('error_code'=>-1,'error_message'=>'未登陆'), '', 'ajax');
			}
			// 被助力粉丝信息
			$fans = pdo_fetch("SELECT * FROM ".tablename('qiyue_luckymoney_fans')." WHERE uid=:uid AND rid=:rid", array(':uid'=>$uid, ':rid'=>$rid));
			if (empty($fans)){
				message(array('error_code'=>-1,'error_message'=>'你没有参加活动'), '', 'ajax');
			}
			if(empty($fans['truename']) || empty($fans['mobile'])){
				$update = array(
					'nickname' => $_GPC['truename'],
					'mobile' => $_GPC['mobile']
				);
				pdo_update('qiyue_luckymoney_fans', $update, array('uid'=>$uid));

				// 电话、姓名同步至粉丝资料
				load()->model('mc');
				$profile = mc_fetch($uid, array('realname', 'mobile'));
				$fans_up = array(); 
				if (empty($profile['realname'])) {
					$fans_up['realname'] = $update['nickname'];
				}
				if (empty($profile['mobile'])) {
					$fans_up['mobile'] = $update['mobile'];
				}
				if (!empty($fans_up)) {
					$uid = mc_update($uid, $fans_up);
				}
			}
			message(array('error_code'=>0), '', 'ajax');
		}
		include $this->template('register');
	}

	// 浏览、分享、参与
	public function doMobileTools(){
		global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$op = $_GPC['op'] ? $_GPC['op'] : 'viewnum';
		if(!in_array($op, array('viewnum','sharenum','fansnum')) || empty($rid)){
			message('来路非法！');
		}
		if($_W['isajax']){
			if($op=='sharenum'){
				pdo_query("UPDATE ".tablename('qiyue_luckymoney')." SET sharenum=sharenum+1 WHERE rid=".$rid);
			}
			elseif($op=='fansnum'){
				pdo_query("UPDATE ".tablename('qiyue_luckymoney')." SET fansnum=fansnum+1 WHERE rid=".$rid);
			}
			else{
				pdo_query("UPDATE ".tablename('qiyue_luckymoney')." SET viewnum=viewnum+1 WHERE rid=".$rid);
				// 粉丝的页面被点击次数
				if(intval($_GPC['uid'])){
					pdo_query("UPDATE ".tablename('qiyue_luckymoney_fans')." SET viewnum=viewnum+1 WHERE uid=".$_GPC['uid']);
				}
			}
			message('操作成功！','','ajax');
		}
	}
}