<?php
defined('IN_IA') or exit('Access Denied');
class fl_dkf_khxxModuleSite extends WeModuleSite
{
    public function doWebHomeSetting()
    {
        global $_W;
        $root   = $_W['siteroot'];
        $weid   = $_W['weid'];
        $url    = "{$root}app/index.php?i={$weid}&c=entry&do=member&m=fl_dkf_khxx";
        $images = MODULE_URL . "images/";
        include $this->template('homeSetting');
    }
    public function doMobileMember()
    {
        global $_GPC, $_W;
        $sql = "select * from " . tablename('fl_dkf_column') . " where weid={$_W['weid']}";
        if (pdo_fetchall($sql)) {
            $sql = "select * from " . tablename('fl_dkf_column') . " where weid={$_W['weid']} and is_show=1";
        } else {
            $sql = "select * from " . tablename('fl_dkf_column') . " where weid=0 and is_show=1";
        }
        $column = pdo_fetchall($sql);
        include $this->template('member');
    }
    public function doMobileAjaxGetMember()
    {
        global $_GPC, $_W;
        $openid                         = $_GPC['openid'];
        $sql                            = "select m.*,mf.openid from " . tablename('mc_members') . " m 
			left join " . tablename('mc_mapping_fans') . " mf on mf.uid=m.uid
			where mf.openid='{$openid}'";
        $return['member']               = pdo_fetch($sql);
        $return['member']['createtime'] = date("Y-m-d H:i:s", $return['member']['createtime']);
        $return['member']['followtime'] = date("Y-m-d H:i:s", $return['member']['followtime']);
        $sql                            = "select * from " . tablename('fl_dkf_column') . " where weid={$_W['weid']}";
        if (pdo_fetchall($sql)) {
            $sql = "select * from " . tablename('fl_dkf_column') . " where weid={$_W['weid']}";
        } else {
            $sql = "select * from " . tablename('fl_dkf_column') . " where weid=0";
        }
        $column = pdo_fetchall($sql);
        foreach ($column as $value) {
            if ($value['is_show']) {
                $returns['member'][$value['column_name']] = $return['member'][$value['column_name']];
            }
        }
        echo json_encode($returns);
    }
    public function doMobileSetKefu()
    {
        global $_GPC;
        $_SESSION['kefu']        = $_GPC['workeraccount'];
        $return['workeraccount'] = $_SESSION['kefu'];
        echo json_encode($return);
    }
    public function doMobileEditMember()
    {
        global $_GPC, $_W;
        $sql         = "select uid from " . tablename('mc_mapping_fans') . " where openid='{$_GPC['openid']}'";
        $_GPC['uid'] = pdo_fetchcolumn($sql);
        $sql         = "select * from " . tablename('fl_dkf_column') . " where weid={$_W['weid']}";
        if (pdo_fetchall($sql)) {
            $sql = "select * from " . tablename('fl_dkf_column') . " where weid={$_W['weid']} and is_show=1 and is_edit=1";
        } else {
            $sql = "select * from " . tablename('fl_dkf_column') . " where weid=0 and is_show=1 and is_edit=1";
        }
        $column = pdo_fetchall($sql);
        foreach ($column as $key => $value) {
            if (isset($_GPC[$value['column_name']])) {
                $data[$value['column_name']] = $_GPC[$value['column_name']];
            }
        }
        pdo_begin();
        pdo_update('mc_members', $data, array(
            "uid" => $_GPC['uid']
        ));
        $sql = "select * from " . tablename('fl_dkf_member_log') . " where uid={$_GPC['uid']} limit 1";
        pdo_fetch($sql);
        $inserArr['create_time'] = time();
        $inserArr['uid']         = $_GPC['uid'];
        $inserArr['kefu_name']   = 'system';
        if (!pdo_fetch($sql)) {
            $sql        = "select * from " . tablename('mc_members') . " where uid={$_GPC['uid']} limit 1";
            $old_member = pdo_fetch($sql);
            foreach ($old_member as $key => $value) {
                $inserArr[$key] = $value;
            }
            pdo_insert('fl_dkf_member_log', $inserArr);
        }
        foreach ($data as $key => $value) {
            $inserArr[$key] = $value;
        }
        $inserArr['kefu_name'] = $_SESSION['kefu'];
        pdo_insert('fl_dkf_member_log', $inserArr);
        pdo_commit();
        message('更新资料成功！', $this->createMobileUrl('member'), 'success');
    }
    public function doMobileRefreshKefu()
    {
        $_SESSION['kefu'] = $_SESSION['kefu'];
    }
    public function doMobileGetKefuByOpenid()
    {
        global $_GPC, $_W;
        $openid           = $_GPC['openid'];
        $token            = $_W['account']['access_token']['token'];
        $url              = "http://api.weixin.qq.com/customservice/kfsession/getsession?access_token={$token}&openid={$openid}";
        $back             = file_get_contents($url);
        $data             = json_decode($back, true);
        $_SESSION['kefu'] = $data['kf_account'];
        echo $data['kf_account'];
    }
}