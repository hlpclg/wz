<?php
/**
 * 抢楼活动模块处理程序
 *
 * @author 美丽心情
 * @qq 513316788
 */
defined('IN_IA') or exit('Access Denied');

include 'common.inc.php';

class bm_floorModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W,$_GPC;
		$config = $this->module['config'];
		$isdes = isset($config['isdes']) ? $config['isdes'] : 1;
		$isstatus = isset($config['isstatus']) ? $config['isstatus'] : 1;
		$content = $this->message['content'];		
		//return $this->respText($this->message['content']);
		$sql = "SELECT * FROM " . tablename('bm_floor') . " WHERE `rid`=:rid";
		$floor = pdo_fetch($sql, array(':rid'=>$this->rule));
		//return $this->respText($floor['starttime']);
		if (date('Y-m-d H:i:s',time()) < $floor['starttime']) {
			if ($floor['memo1'] <> '') {
				return $this->respText($floor['memo1']);
			} else {
				return $this->respText('活动尚未开始！');
			}
		}
		if (date('Y-m-d H:i:s',time()) > $floor['endtime']) {
			if ($floor['memo2'] <> '') {
				return $this->respText($floor['memo2']);
			} else {
				return $this->respText('活动已经结束！');
			}		
		}
		//return $this->respText($floor['share_keyword']);
		//发送分享关键词获取分享链接
		if ($content == $floor['share_keyword']) {
			$url = $this->buildSiteUrl($this->createMobileUrl('show', array('do' => 'show', 'name' => 'bm_floor', 'rid' => $this->rule, 'weid' => $_W['weid'], 'from_user' => base64_encode(authcode($this->message['from'], 'ENCODE')))));
			//return $this->respText($url);
			$title = '微信抢楼送礼活动';
			$response['FromUserName'] = $this->message['to'];
			$response['ToUserName'] = $this->message['from'];
			$response['MsgType'] = 'news';
			$response['ArticleCount'] = 1;
			$response['Articles'] = array();
			$response['Articles'][] = array(
				'Title' => $title,
				'Description' => $floor['share_memo'],
				'PicUrl' => $_W['attachurl'] . $floor['share_logo'],
				'Url' => $url,
				'TagName' => 'item',
			);
			return $response;			
		}
		
		$sql = "SELECT count(id) as id FROM " . tablename('bm_floor_'.$this->rule) . " where from_user='" . $this->message['from'] . "' and date_format(FROM_UNIXTIME(dateline),'%Y-%m-%d')=CURDATE()";
		//return $this->respText($sql);
		$maxid = pdo_fetch($sql);
		$allowtotal = $floor['total'];
		$used = $maxid['id'] + 1;
		$usertotal = $allowtotal - $used;
		$sql = "SELECT share_point,share_used FROM " . tablename('bm_floor_member') . " where from_user='" . $this->message['from'] . "' and rid = '{$this->rule}'";
		$share = pdo_fetch($sql);
		//return $this->respText($sql);		
		if (!empty($share)) {
			$share_point = $share['share_point'];
			$usertotal = $usertotal + $share['share_point'] - $share['share_used'];	
		} else {
			$share_point = 0 ;
		}
		if ($usertotal < 0) {
			$usertotal = 0;
		}
		//return $this->respText($usertotal);
		if ($maxid['id'] >= $floor['total']) {
			if ($share['share_point'] > $share['share_used']) {
				$data = array(
					'IPaddress' => $IPaddress,
					'share_used' => $share['share_used'] + 1
				);
				pdo_update('bm_floor_member', $data,array('from_user' => $this->message['from']));
			} else {
				//次数用完了
				$sql = "SELECT memo FROM " . tablename('bm_floor') . " WHERE `rid`=:rid";
				$prompt = pdo_fetch($sql, array(':rid'=>$this->rule));		
				//return $this->respText($prompt['memo']);
				$url = $this->buildSiteUrl($this->createMobileUrl('awardlist', array('from_user' => $this->message['from'] ,'rid' => $this->rule ,'do' => 'awardlist', 'name' => 'bm_floor', 'weid' => $_W['weid'])));
				$des = $prompt['memo'];
				$sql = "SELECT picture FROM " . tablename('bm_floor') . " WHERE `rid`=:rid";	
				$row = pdo_fetch($sql, array(':rid' => $this->rule));					
				$response['FromUserName'] = $this->message['to'];
				$response['ToUserName'] = $this->message['from'];
				$response['MsgType'] = 'news';
				$response['ArticleCount'] = 1;
				$response['Articles'] = array();
				$response['Articles'][] = array(
					'Title' => $des,
					'Description' => '欢迎参微信抢楼送礼活动！点击看看奖品清单吧！',
					'PicUrl' => !strexists($row['picture'], 'http://') ? $_W['attachurl'] . $row['picture'] : $row['picture'],
					'Url' => $url,
					'TagName' => 'item',
				);
				return $response;
			}			
		}		
		
		$sql = "SELECT floor,from_user,id,title FROM " . tablename('bm_floor_award') . " WHERE from_user <> '' and `rid`=:rid";
		//return $this->respText($sql);
		$floors = pdo_fetchall($sql, array(':rid'=>$this->rule));		
		$sql = "SELECT max(id) as id FROM " . tablename('bm_floor_'.$this->rule);
		$maxid = pdo_fetch($sql);	
		$cur = $maxid['id'] +1;
		$flag = 0 ;
		foreach ($floors as $f) {
			if (($f['floor'] == $cur) && ($flag == 0)) {
				$insert = array(
					'dateline' => $_W['timestamp'],
					'from_user' => $f['from_user'],
				);
				pdo_insert("bm_floor_".$this->rule, $insert, false);
				$insert = array(
					'from_user' => $f['from_user'],
					'dateline' => $_W['timestamp'],
					'ip' => $_W['clientip'],
					'rid' => $this->rule,
					'awardid' => $f['id'],
					'awardname' => $f['title'],
					'floor' => $f['floor'],
					'realname' => $f['from_user'],				
				);
				pdo_insert('bm_floor_winner', $insert);	
				$flag = 1 ;
			}
		}

		$insert = array(
			'dateline' => $_W['timestamp'],
			'from_user' => $this->message['from'],
		);
		pdo_insert("bm_floor_".$this->rule, $insert, false);
		$id = pdo_insertid();
		if ($id <= 0) {
			return $this->respText('系统异常，请稍后重试！');
		}
		
		$award = pdo_fetchall("SELECT * FROM ".tablename('bm_floor_award')." WHERE rid = :rid ORDER BY `floor` ASC", array(':rid' => $this->rule));
		$sql = "SELECT awardprompt,currentprompt FROM " . tablename('bm_floor') . " WHERE `rid`=:rid";
		$prompt = pdo_fetch($sql, array(':rid'=>$this->rule));
		$awardprompt = $prompt['awardprompt']!=''?$prompt['awardprompt']:'当前楼层是{FLOOR}楼,恭喜你，获得{AWARD}{DESCRIPTION}';
		$currentprompt = $prompt['currentprompt']!=''?$prompt['currentprompt']:'当前楼层是{FLOOR}楼';
		$award_name = "";
		//中奖了
		if ($award) {
			foreach ($award as $item) {
				$award_name .= '奖品名称：' . $item['title'] . '　奖品描述：' . $item['description'] . "　获奖楼层：" . $item['floor'] . "楼\n";
				$floor = explode(',', $item['floor']);
				if (in_array($id, $floor)) {
					$insert = array(
						'from_user' => $this->message['from'],
						'dateline' => $_W['timestamp'],
						'ip' => $_W['clientip'],
						'rid' => $this->rule,
						'awardid' => $item['id'],
						'awardname' => $item['title'],
						'floor' => $id,
					);
					$from_user = $this->message['from'];
					$user = fans_search($from_user);
					//return $this->respText($user['credit1']);
					$sql_member = "SELECT a.uid FROM " . tablename('mc_mapping_fans') . " a inner join " . tablename('mc_members') . " b on a.uid=b.uid WHERE a.openid='{$from_user}'";
					//return $this->respText($sql_member);
					$uid = pdo_fetchcolumn($sql_member);
					if ($item['title'] == '会员积分') {
						$insert['status'] = 3;
						$credit1 = $user['credit1'] + intval($item['description']);
						mc_credit_update($uid , 'credit1' , $credit1 , array( 0 => 'system', 1 => '抢楼送积分' ));						
					};
					
					if ($item['title'] == '会员余额') {
						$insert['status'] = 3;
						$credit2 = $user['credit2'] + intval($item['description']);
						mc_credit_update($uid , 'credit2' , $credit2 , array( 0 => 'system', 1 => '抢楼送积分' ));
					};		
					
					pdo_insert('bm_floor_winner', $insert);
					$result = str_replace('{FLOOR}', $id, $awardprompt);
					$result = str_replace('{AWARD}', $item['title'], $result);
					$result = str_replace('{DESCRIPTION}', $item['description'], $result);
					$sql = "SELECT picture FROM " . tablename('bm_floor') . " WHERE `rid`=:rid";	
					$row = pdo_fetch($sql, array(':rid' => $this->rule));						
					//$url = $_W['siteroot'] . create_url('mobile/module', array('rid' => $this->rule ,'do' => 'awardsubmit', 'name' => 'bm_floor', 'weid' => $_W['weid'], 'from_user' => $this->message['from']));
					$url = $this->buildSiteUrl($this->createMobileUrl('awardsubmit', array('rid' => $this->rule ,'do' => 'awardsubmit', 'name' => 'bm_floor', 'weid' => $_W['weid'], 'from_user' => $this->message['from'])));
					//return $this->respText($url);
					$des = '欢迎参加抢楼活动，点击查看奖品清单、中奖名单、您的奖品、领取奖品。每人每天可抢楼' . $allowtotal . '次，您通过分享点击增加了' . $share_point . '次，您今天已经抢楼' . $used . '次，您今天还可以抢楼' . $usertotal . '次';
					$response['FromUserName'] = $this->message['to'];
					$response['ToUserName'] = $this->message['from'];
					$response['MsgType'] = 'news';
					$response['ArticleCount'] = 1;
					$response['Articles'] = array();
					$response['Articles'][] = array(
						'Title' => $result,
						'Description' => $des,
						'PicUrl' => !strexists($row['picture'], 'http://') ? $_W['attachurl'] . $row['picture'] : $row['picture'],
						'Url' => $url,
						'TagName' => 'item',
					);
					return $response;					
					
					//return $this->respText($result);
				}
			}		
		}
		//没中奖
		$result = str_replace('{FLOOR}', $id, $currentprompt);
		
		$sql = "SELECT picture FROM " . tablename('bm_floor') . " WHERE `rid`=:rid";	
		$row = pdo_fetch($sql, array(':rid' => $this->rule));			
        //$url = $_W['siteroot'] . create_url('mobile/module', array('from_user' => $this->message['from'] ,'rid' => $this->rule ,'do' => 'awardlist', 'name' => 'bm_floor', 'weid' => $_W['weid']));
		//$url = $this->createMobileUrl('awardlist', array('from_user' => $this->message['from'] ,'rid' => $this->rule ,'do' => 'awardlist', 'name' => 'bm_floor', 'weid' => $_W['weid']));
		$url =$this->buildSiteUrl($this->createMobileUrl('awardlist', array('from_user' => $this->message['from'] ,'rid' => $this->rule ,'do' => 'awardlist', 'name' => 'bm_floor', 'weid' => $_W['weid'])));
		//return $this->respText($url);
		$des = '欢迎参加抢楼活动，点击查看奖品清单、中奖名单、您的奖品、领取奖品。每人每天可抢楼' . $allowtotal . '次，您通过分享点击增加了' . $share_point . '次，您今天已经抢楼' . $used . '次，您今天还可以抢楼' . $usertotal . '次';
        $response['FromUserName'] = $this->message['to'];
        $response['ToUserName'] = $this->message['from'];
        $response['MsgType'] = 'news';
        $response['ArticleCount'] = 1;
        $response['Articles'] = array();
        $response['Articles'][] = array(
            'Title' => $result,
            'Description' => $des,
            'PicUrl' => !strexists($row['picture'], 'http://') ? $_W['attachurl'] . $row['picture'] : $row['picture'],
            'Url' => $url,
            'TagName' => 'item',
        );
		return $response;
	}
}
