<?php
/**
 * 超强防伪码模块处理程序
 *
 * @author 超强防伪码
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
class Super_securitycodeModuleProcessor extends WeModuleProcessor
{
    public function __construct()
    {
        global $_W;
        $this->data  = 'super_securitycode_data_' . $_W['uniacid'];
        $this->moban = 'super_securitycode_data_moban';
        $this->reply = 'super_securitycode_reply';
        $sql         = "CREATE TABLE IF NOT EXISTS " . tablename($this->data) . " LIKE " . tablename($this->moban);
        pdo_query($sql);
    }
    public function respond()
    {
        global $_W;
        load()->model('mc');
        $openid         = $this->message['from'];
        $logs['openid'] = $openid;
        $logs['weid']   = $_W['uniacid'];
        $fans           = pdo_fetch("SELECT fanid,uid FROM " . tablename('mc_mapping_fans') . " WHERE `openid`='$openid' LIMIT 1");
        $uid            = '0';
        if ($fans['uid'] != '0') {
            $uid = $fans['uid'];
        } else {
            $uid = mc_update($uid, array(
                'email' => md5($_W['openid']) . '@012wz.com'
            ));
            if (!empty($fans['fanid']) && !empty($uid)) {
                pdo_update('mc_mapping_fans', array(
                    'uid' => $uid
                ), array(
                    'fanid' => $fans['fanid']
                ));
            }
        }
        $rid  = $this->rule;
        $sql  = "SELECT * FROM " . tablename('rule_keyword') . " WHERE `rid`=:rid LIMIT 1";
        $row  = pdo_fetch($sql, array(
            ':rid' => $rid
        ));
        $sqls = "SELECT * FROM " . tablename($this->reply) . " WHERE `rid`=:rid LIMIT 1";
        $rows = pdo_fetch($sqls, array(
            ':rid' => $rid
        ));
        if (empty($rows['id'])) {
            return array();
        }
        if (empty($row['id'])) {
            return array();
        }
        $wd_code   = $this->message['scancodeinfo']['scanresult'];
        $wd_qrcode = substr($wd_code, strpos($wd_code, "wd_code=") + 8);
        if ($this->message['event'] == 'scancode_waitmsg') {
            $qrtype       = $this->message['scancodeinfo']['scantype'];
            $SecurityCode = $wd_qrcode;
        } else {
            $keywords = $wd_qrcode;
            preg_match('/' . $keywords . '(.*)/', $this->message['content'], $match);
            $SecurityCode = $match[1];
        }
        $logs['code'] = $SecurityCode;
        $sql          = "SELECT * FROM " . tablename($this->data) . " WHERE code='{$SecurityCode}' LIMIT 1";
        $member       = pdo_fetch($sql);
        $states       = 0;
        if (!empty($member)) {
            if ($member['status'] == '0') {
                $states = 0;
            } elseif ($member['num'] >= $rows['tnumber']) {
                $set    = pdo_update($this->data, array(
                    'status' => '0'
                ), array(
                    'id' => $member['id']
                ));
                $states = 0;
            } elseif ($member['stime'] <= TIME()) {
                $logs['status'] = '0';
                $reply          = '您查询的防伪码已过期! ';
            } else {
                $data = array(
                    'num' => $member['num'] + 1
                );
                pdo_update($this->data, $data, array(
                    'id' => $member['id']
                ));
                $states = 1;
            }
            if ($states == 0) {
                $reply = str_replace('[SecurityCode]', $SecurityCode, $rows['Failure']);
            } else {
                $number   = $member['num'] + 1;
                $Factory  = $member['factory'];
                $Effedate = date('Y-m-d', $member['stime']);
                $Products = $member['type'];
                $Brand    = $member['brand'];
                $Spec     = $member['spec'];
                $Weight   = $member['weight'];
                $Remarks  = $member['remarks'];
                $reply    = str_replace('[number]', $number, $rows['Reply']);
                $reply    = str_replace('[Factory]', $Factory, $reply);
                $reply    = str_replace('[Brand]', $Brand, $reply);
                $reply    = str_replace('[Spec]', $Spec, $reply);
                $reply    = str_replace('[Weight]', $Weight, $reply);
                $reply    = str_replace('[Remarks]', $Remarks, $reply);
                $reply    = str_replace('[Effedate]', $Effedate, $reply);
                $reply    = str_replace('[Products]', $Products, $reply);
                $reply    = str_replace('[SecurityCode]', $SecurityCode, $reply);
                $reply    = str_replace('[CreditName]', $member['creditname'], $reply);
                $reply    = str_replace('[CreditNum]', $member['creditnum'], $reply);
                if ($member['creditstatus'] == '0') {
                    mc_credit_update($uid, 'credit1', $member['creditnum'], array(
                        '1',
                        '防伪码自动增加积分，积分名称：' . $member['creditname']
                    ));
                    pdo_update($this->data, array(
                        'creditstatus' => '1'
                    ), array(
                        'id' => $member['id']
                    ));
                }
                $logs['status'] = '1';
            }
        } else {
            $logs['status'] = '0';
            $reply          = '您查询的防伪码不存在，请核对后重试！';
        }
        $logs['createtime'] = time();
        pdo_insert('super_securitycode_logs', $logs);
        return $this->respText($reply);
    }
}