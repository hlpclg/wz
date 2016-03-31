<?php
/**
 * 抢楼活动模块处理程序
 *
 * @author 美丽心情
 * @qq 513316788
 */
defined('IN_IA') or exit('Access Denied');

include 'common.inc.php';

class bmfloorModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W,$_GPC;
		$config = $this->module['config'];
		$isdes = isset($config['isdes']) ? $config['isdes'] : 1;
		$isstatus = isset($config['isstatus']) ? $config['isstatus'] : 1;
		$content = $this->message['content'];		
		//return $this->respText($this->message['content']);
		$sql = "SELECT * FROM " . tablename('bmfloor') . " WHERE `rid`=:rid";
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
		if ($content == $floor['share_keyword']) {
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
				'Url' => $_W['siteroot'] . create_url('Mobile/module', array('do' => 'show', 'name' => 'bmfloor', 'rid' => $this->rule, 'weid' => $_W['weid'], 'from_user' => base64_encode(authcode($this->message['from'], 'ENCODE')))),
				'TagName' => 'item',
			);
			return $response;			
		}
		
		$sql = "SELECT count(id) as id FROM " . tablename('bmfloor_'.$this->rule) . " where from_user='" . $this->message['from'] . "' and date_format(FROM_UNIXTIME(dateline),'%Y-%m-%d')=CURDATE()";
		//return $this->respText($sql);
		$maxid = pdo_fetch($sql);
		$allowtotal = $floor['total'];
		$used = $maxid['id'] + 1;
		$usertotal = $allowtotal - $used;
		$sql = "SELECT share_point,share_used FROM " . tablename('bmfloor_member') . " where from_user='" . $this->message['from'] . "' and rid = '{$this->rule}'";
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
				pdo_update('bmfloor_member', $data,array('from_user' => $this->message['from']));
			} else {
				$sql = "SELECT memo FROM " . tablename('bmfloor') . " WHERE `rid`=:rid";
				$prompt = pdo_fetch($sql, array(':rid'=>$this->rule));		
				//return $this->respText($prompt['memo']);
				$url = $_W['siteroot'] . create_url('mobile/module', array('from_user' => $this->message['from'] ,'rid' => $this->rule ,'do' => 'awardlist', 'name' => 'bmfloor', 'weid' => $_W['weid']));
				$des = $prompt['memo'];
				$sql = "SELECT picture FROM " . tablename('bmfloor', array(':rid' => $this->rule));	
				$row = pdo_fetch($sql);				
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
		
		$sql = "SELECT floor,from_user,id,title FROM " . tablename('bmfloor_award') . " WHERE from_user <> '' and `rid`=:rid";
		//return $this->respText($sql);
		$floors = pdo_fetchall($sql, array(':rid'=>$this->rule));		
		$sql = "SELECT max(id) as id FROM " . tablename('bmfloor_'.$this->rule);
		$maxid = pdo_fetch($sql);	
		$cur = $maxid['id'] +1;
		$flag = 0 ;
		foreach ($floors as $f) {
			if (($f['floor'] == $cur) && ($flag == 0)) {
				$insert = array(
					'dateline' => $_W['timestamp'],
					'from_user' => $f['from_user'],
				);
				pdo_insert("bmfloor_".$this->rule, $insert, false);
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
				pdo_insert('bmfloor_winner', $insert);	
				$flag = 1 ;
			}
		}

		$insert = array(
			'dateline' => $_W['timestamp'],
			'from_user' => $this->message['from'],
		);
		pdo_insert("bmfloor_".$this->rule, $insert, false);
		$id = pdo_insertid();
		if ($id <= 0) {
			return $this->respText('系统异常，请稍后重试！');
		}
		
		$award = pdo_fetchall("SELECT * FROM ".tablename('bmfloor_award')." WHERE rid = :rid ORDER BY `floor` ASC", array(':rid' => $this->rule));
		$sql = "SELECT awardprompt,currentprompt FROM " . tablename('bmfloor') . " WHERE `rid`=:rid";
		$prompt = pdo_fetch($sql, array(':rid'=>$this->rule));
		$awardprompt = $prompt['awardprompt']!=''?$prompt['awardprompt']:'当前楼层是{FLOOR}楼,恭喜你，获得{AWARD}{DESCRIPTION}';
		$currentprompt = $prompt['currentprompt']!=''?$prompt['currentprompt']:'当前楼层是{FLOOR}楼';
		$award_name = "";
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
					pdo_insert('bmfloor_winner', $insert);
					$result = str_replace('{FLOOR}', $id, $awardprompt);
					$result = str_replace('{AWARD}', $item['title'], $result);
					$result = str_replace('{DESCRIPTION}', $item['description'], $result);
					//$url = $this->createMobileUrl('awardsubmit', array('from' => superman_authcode($this->message['from'], 'ENCODE')));
					$url = $this->createMobileUrl('awardsubmit', array('from' => $this->message['from'], 'rid' => $this->rule));
					
					$sql = "SELECT picture FROM " . tablename('bmfloor', array(':rid' => $this->rule));	
					$row = pdo_fetch($sql);			
					$url = $_W['siteroot'] . create_url('mobile/module', array('rid' => $this->rule ,'do' => 'awardsubmit', 'name' => 'bmfloor', 'weid' => $_W['weid'], 'from_user' => $this->message['from']));
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
		$result = str_replace('{FLOOR}', $id, $currentprompt);
		
		$sql = "SELECT picture FROM " . tablename('bmfloor', array(':rid' => $this->rule));	
		$row = pdo_fetch($sql);			
        $url = $_W['siteroot'] . create_url('mobile/module', array('from_user' => $this->message['from'] ,'rid' => $this->rule ,'do' => 'awardlist', 'name' => 'bmfloor', 'weid' => $_W['weid']));
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
