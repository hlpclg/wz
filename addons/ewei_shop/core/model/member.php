<?php
//捌零网络科技有限公司QQ2316571101
if (!defined('IN_IA')) {
    exit('Access Denied');
}
class Ewei_DShop_Member
{
    public function getInfo($openid = '')
    {
        global $_W;
        $uid = intval($openid);
        if ($uid == 0) {
            $info = pdo_fetch('select * from ' . tablename('ewei_shop_member') . ' where openid=:openid and uniacid=:uniacid limit 1', array(
                ':uniacid' => $_W['uniacid'],
                ':openid' => $openid
            ));
        } else {
            $info = pdo_fetch('select * from ' . tablename('ewei_shop_member') . ' where id=:id  and uniacid=:uniacid limit 1', array(
                ':uniacid' => $_W['uniacid'],
                ':id' => $uid
            ));
        }
        if (!empty($info['uid'])) {
            load()->model('mc');
            $uid                = mc_openid2uid($info['openid']);
            $fans               = mc_fetch($uid, array(
                'credit1',
                'credit2',
                'birthyear',
                'birthmonth',
                'birthday',
                'gender',
                'avatar',
                'resideprovince',
                'residecity',
                'nickname'
            ));
            $info['credit1']    = $fans['credit1'];
            $info['credit2']    = $fans['credit2'];
            $info['birthyear']  = empty($info['birthyear']) ? $fans['birthyear'] : $info['birthyear'];
            $info['birthmonth'] = empty($info['birthmonth']) ? $fans['birthmonth'] : $info['birthmonth'];
            $info['birthday']   = empty($info['birthday']) ? $fans['birthday'] : $info['birthday'];
            $info['nickname']   = empty($info['nickname']) ? $fans['nickname'] : $info['nickname'];
            $info['gender']     = empty($info['gender']) ? $fans['gender'] : $info['gender'];
            $info['sex']        = $info['gender'];
            $info['avatar']     = empty($info['avatar']) ? $fans['avatar'] : $info['avatar'];
            $info['headimgurl'] = $info['avatar'];
            $info['province']   = empty($info['province']) ? $fans['resideprovince'] : $info['province'];
            $info['city']       = empty($info['city']) ? $fans['residecity'] : $info['city'];
        }
        if (!empty($info['birthyear']) && !empty($info['birthmonth']) && !empty($info['birthday'])) {
            $info['birthday'] = $info['birthyear'] . '-' . (strlen($info['birthmonth']) <= 1 ? '0' . $info['birthmonth'] : $info['birthmonth']) . '-' . (strlen($info['birthday']) <= 1 ? '0' . $info['birthday'] : $info['birthday']);
        }
        if (empty($info['birthday'])) {
            $info['birthday'] = '';
        }
        return $info;
    }
    public function getMember($openid = '')
    {
        global $_W;
        $uid = intval($openid);
        if (empty($uid)) {
            $info = pdo_fetch('select * from ' . tablename('ewei_shop_member') . ' where  openid=:openid and uniacid=:uniacid limit 1', array(
                ':uniacid' => $_W['uniacid'],
                ':openid' => $openid
            ));
        } else {
            $info = pdo_fetch('select * from ' . tablename('ewei_shop_member') . ' where id=:id and uniacid=:uniacid limit 1', array(
                ':uniacid' => $_W['uniacid'],
                ':id' => $uid
            ));
        }
	if (!empty($_var_2)) {
		$openid = $info['openid'];
		if (empty($info['uid'])) {
			$_var_4 = m('user')->followed($openid);
			if ($_var_4) {
				load()->model('mc');
				$uid = mc_openid2uid($openid);
				if (!empty($uid)) {
					$info['uid'] = $uid;
					$_var_5 = array('uid' => $uid);
					if ($info['credit1'] > 0) {
						mc_credit_update($uid, 'credit1', $info['credit1']);
							$_var_5['credit1'] = 0;
					}
					if ($info['credit2'] > 0) {
						mc_credit_update($uid, 'credit2', $info['credit2']);
						$_var_5['credit2'] = 0;
					}
					if (!empty($_var_5)) {
						pdo_update('ewei_shop_member', $_var_5, array('id' => $info['id']));
					}
					}
			}
		}
		$_var_6 = $this->getCredits($openid);
		$info['credit1'] = $_var_6['credit1'];
		$info['credit2'] = $_var_6['credit2'];
	}
        return $info;
    }
    public function getMid()
    {
        global $_W;
        $openid = m('user')->getOpenid();
        $member = $this->getMember($openid);
        return $member['id'];
    }
    public function setCredit($openid = '', $credittype = 'credit1', $credits = 0, $log = array())
    {
        global $_W;
        load()->model('mc');
        $uid = mc_openid2uid($openid);
        if (!empty($uid)) {
            $value     = pdo_fetchcolumn("SELECT {$credittype} FROM " . tablename('mc_members') . " WHERE `uid` = :uid", array(
                ':uid' => $uid
            ));
            $newcredit = $credits + $value;
            if ($newcredit <= 0) {
                $newcredit = 0;
            }
            pdo_update('mc_members', array(
                $credittype => $newcredit
            ), array(
                'uid' => $uid
            ));
            if (empty($log) || !is_array($log)) {
                $log = array(
                    $uid,
                    '未记录'
                );
            }
            $data = array(
                'uid' => $uid,
                'credittype' => $credittype,
                'uniacid' => $_W['uniacid'],
                'num' => $credits,
                'createtime' => TIMESTAMP,
                'operator' => intval($log[0]),
                'remark' => $log[1]
            );
            pdo_insert('mc_credits_record', $data);
        } else {
            $value     = pdo_fetchcolumn("SELECT {$credittype} FROM " . tablename('ewei_shop_member') . " WHERE  uniacid=:uniacid and openid=:openid limit 1", array(
                ':uniacid' => $_W['uniacid'],
                ':openid' => $openid
            ));
            $newcredit = $credits + $value;
            if ($newcredit <= 0) {
                $newcredit = 0;
            }
            pdo_update('ewei_shop_member', array(
                $credittype => $newcredit
            ), array(
                'uniacid' => $_W['uniacid'],
                'openid' => $openid
            ));
        }
    }
    public function getCredit($openid = '', $credittype = 'credit1')
    {
        global $_W;
        load()->model('mc');
        $uid = mc_openid2uid($openid);
        if (!empty($uid)) {
            return pdo_fetchcolumn("SELECT {$credittype} FROM " . tablename('mc_members') . " WHERE `uid` = :uid", array(
                ':uid' => $uid
            ));
        } else {
            return pdo_fetchcolumn("SELECT {$credittype} FROM " . tablename('ewei_shop_member') . " WHERE  openid=:openid and uniacid=:uniacid limit 1", array(
                ':uniacid' => $_W['uniacid'],
                ':openid' => $openid
            ));
		}
	}
	public function getCredits($_var_0 = '', $_var_13 = array('credit1', 'credit2'))
	{
		global $_W;
		load()->model('mc');
		$_var_1 = mc_openid2uid($_var_0);
		$_var_14 = implode(',', $_var_13);
		if (!empty($_var_1)) {
			return pdo_fetch("SELECT {$_var_14} FROM " . tablename('mc_members') . ' WHERE `uid` = :uid limit 1', array(':uid' => $_var_1));
		} else {
			return pdo_fetch("SELECT {$_var_14} FROM " . tablename('ewei_shop_member') . ' WHERE  openid=:openid and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $_var_0));
		}

    }
    public function checkMember($openid = '')
    {
        global $_W, $_GPC;
        if (strexists($_SERVER['REQUEST_URI'], '/web/')) {
            return;
        }
        if (empty($openid)) {
            $openid = m('user')->getOpenid();
        }
        if (empty($openid)) {
            return;
        }
        $member   = m('member')->getMember($openid);
        $userinfo = m('user')->getInfo();
        $followed = m('user')->followed($openid);
        $uid      = 0;
        $mc       = array();
		load()->model('mc');
		if ($followed) {
			$uid = mc_openid2uid($openid);
			$mc = mc_fetch($uid, array('realname', 'mobile', 'avatar', 'resideprovince', 'residecity', 'residedist'));
		}
        if (empty($member)) {
            if ($followed) {
                $uid = mc_openid2uid($openid);
                $mc  = mc_fetch($uid, array(
                    'realname',
                    'mobile',
                    'avatar',
                    'resideprovince',
                    'residecity',
                    'residedist'
                ));
            }
            $member = array(
                'uniacid' => $_W['uniacid'],
                'uid' => $uid,
                'openid' => $openid,
                'realname' => !empty($mc['realname']) ? $mc['realname'] : '',
                'mobile' => !empty($mc['mobile']) ? $mc['mobile'] : '',
                'nickname' => !empty($mc['nickname']) ? $mc['nickname'] : $userinfo['nickname'],
                'avatar' => !empty($mc['avatar']) ? $mc['avatar'] : $userinfo['avatar'],
                'gender' => !empty($mc['gender']) ? $mc['gender'] : $userinfo['sex'],
                'province' => !empty($mc['residecity']) ? $mc['resideprovince'] : $userinfo['province'],
                'city' => !empty($mc['residecity']) ? $mc['residecity'] : $userinfo['city'],
                'area' => !empty($mc['residedist']) ? $mc['residedist'] : '',
                'createtime' => time(),
                'status' => 0
            );
            pdo_insert('ewei_shop_member', $member);
        } else {
            $upgrade = array();
            if ($userinfo['nickname'] != $member['nickname']) {
                $upgrade['nickname'] = $userinfo['nickname'];
            }
            if ($userinfo['avatar'] != $member['avatar']) {
                $upgrade['avatar'] = $userinfo['avatar'];
            }
            if (!empty($upgrade)) {
                pdo_update('ewei_shop_member', $upgrade, array(
                    'id' => $member['id']
                ));
            }
        }
        if (p('commission')) {
            p('commission')->checkAgent();
        }
        if (p('poster')) {
            p('poster')->checkScan();
        }
    }
    function getLevels()
    {
        global $_W;
        return pdo_fetchall('select * from ' . tablename('ewei_shop_member_level') . ' where uniacid=:uniacid order by level asc', array(
            ':uniacid' => $_W['uniacid']
        ));
    }
    function getLevel($openid)
    {
        global $_W;
        if (empty($openid)) {
            return false;
        }
        $member = m('member')->getMember($openid);
        if (empty($member['level'])) {
            return array(
                'discount' => 10
            );
        }
        $level = pdo_fetch('select * from ' . tablename('ewei_shop_member_level') . ' where id=:id and uniacid=:uniacid order by level asc', array(
            ':uniacid' => $_W['uniacid'],
            ':id' => $member['level']
        ));
        if (empty($level)) {
            return array(
                'discount' => 10
            );
        }
        return $level;
    }
    function upgradeLevel($openid)
    {
        global $_W;
        if (empty($openid)) {
            return;
        }
        $_var_18 = m('common')->getSysset('shop');
		$_var_19 = intval($_var_18['leveltype']);
		$member = m('member')->getMember($openid);
        if (empty($member)) {
			return;
		}
		$level = false;
		if (empty($_var_19)) {
			$_var_20 = pdo_fetchcolumn('select ifnull( sum(og.realprice),0) from ' . tablename('ewei_shop_order_goods') . ' og ' . ' left join ' . tablename('ewei_shop_order') . ' o on o.id=og.orderid ' . ' where o.openid=:openid and o.status=3 and o.uniacid=:uniacid ', array(':uniacid' => $_W['uniacid'], ':openid' => $member['openid']));
			$level = pdo_fetch('select * from ' . tablename('ewei_shop_member_level') . " where uniacid=:uniacid  and {$_var_20} >= ordermoney and ordermoney>0  order by level desc limit 1", array(':uniacid' => $_W['uniacid']));
		} else if ($_var_19 == 1) {
			$_var_21 = pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_order') . ' where openid=:openid and status=3 and uniacid=:uniacid ', array(':uniacid' => $_W['uniacid'], ':openid' => $member['openid']));
			$level = pdo_fetch('select * from ' . tablename('ewei_shop_member_level') . " where uniacid=:uniacid  and {$_var_21} >= ordercount and ordercount>0  order by level desc limit 1", array(':uniacid' => $_W['uniacid']));
		}
		if (empty($level)) {
			return;
		}
		if ($level['id'] == $member['level']) {
			return;
		}
		$_var_22 = $this->getLevel($openid);
		$_var_23 = false;
		if (empty($_var_22['id'])) {
			$_var_23 = true;
		} else {
			if ($level['level'] > $_var_22['level']) {
				$_var_23 = true;
			}
		}
		if ($_var_23) {
			pdo_update('ewei_shop_member', array('level' => $level['id']), array('id' => $member['id']));
			m('notice')->sendMemberUpgradeMessage($openid, $_var_22, $level);
		}
    }
    function getGroups()
    {
        global $_W;
        return pdo_fetchall('select * from ' . tablename('ewei_shop_member_group') . ' where uniacid=:uniacid order by id asc', array(
            ':uniacid' => $_W['uniacid']
        ));
    }
    function getGroup($openid)
    {
        if (empty($openid)) {
            return false;
        }
        $member = m('member')->getMember($openid);
        return $member['groupid'];
    }
    function setRechargeCredit($openid = '', $money = 0)
    {
        if (empty($openid)) {
            return;
        }
        global $_W;
        $credit = 0;
        $set    = m('common')->getSysset(array(
            'trade',
            'shop'
        ));
        if ($set['trade']) {
            $tmoney  = floatval($set['trade']['money']);
            $tcredit = intval($set['trade']['credit']);
            if ($tmoney > 0) {
                if ($money % $tmoney == 0) {
                    $credit = intval($money / $tmoney) * $tcredit;
                } else {
                    $credit = (intval($money / $tmoney) + 1) * $tcredit;
                }
            }
        }
        if ($credit > 0) {
            $this->setCredit($openid, 'credit1', $credit, array(
                0,
                $set['shop']['name'] . '会员充值积分:credit2:' . $credit
            ));
        }
    }
}