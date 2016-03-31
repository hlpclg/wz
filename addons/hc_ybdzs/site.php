
<?php
defined('IN_IA') or exit('Access Denied');
class Hc_ybdzsModuleSite extends WeModuleSite
{
    public function doWebsetting()
    {
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        load()->func('tpl');
        $subject                = pdo_fetch("SELECT * FROM " . tablename(hc_ybdzs_setting) . " WHERE weid = '{$weid}' ORDER BY id DESC LIMIT 1");
        $item['hc_ybdzs_title'] = empty($item['hc_ybdzs_title']) ? '月饼大战' : $item['hc_ybdzs_title'];
        $item['share_desc']     = empty($item['share_desc']) ? '我只用了一层功力，已经无人可及了！' : $item['share_desc'];
        $item['wechat']         = empty($item['wechat']) ? '导流标题' : $item['wechat'];
        $item['hc_ybdzs_url']   = empty($item['hc_ybdzs_url']) ? 'http://bbs.012wz.com' : $item['hc_ybdzs_url'];
        if (checksubmit()) {
            $data = array(
                'hc_ybdzs_title' => $_GPC['hc_ybdzs_title'],
                'hc_ybdzs_url' => $_GPC['hc_ybdzs_url'],
                'share_desc' => $_GPC['share_desc'],
                'wechat' => $_GPC['wechat'],
                'counts' => $_GPC['counts'],
                'photo' => $_GPC['photo']
            );
            if (empty($subject)) {
                $data['weid'] = $weid;
                pdo_insert(hc_ybdzs_setting, $data);
            } else {
                pdo_update(hc_ybdzs_setting, $data, array(
                    'weid' => $weid
                ));
            }
            message('欧了！欧了！更新完毕！', referer(), 'success');
        }
        if (!$subject['photo']) {
            $subject = array(
                "photo" => "../addons/hc_ybdzs/template/mobile/b.gif"
            );
        }
        include $this->template('setting');
    }
    public function doMobilewesites()
    {
        global $_W, $_GPC;
        load()->func('tpl');
        $sql            = "SELECT * FROM " . tablename(hc_ybdzs_setting) . " WHERE weid = '{$_W['uniacid']}'";
        $arr            = pdo_fetchall($sql);
        $hc_ybdzs_title = $arr['0']['hc_ybdzs_title'];
        $hc_ybdzs_url   = $arr['0']['hc_ybdzs_url'];
        $share_desc     = $arr['0']['share_desc'];
        $wechat         = $arr['0']['wechat'];
        $photo          = $arr['0']['photo'];
        $counts         = $arr['0']['counts'];
        $weid           = $_W['uniacid'];
        $homeurl        = empty($reply['homeurl']) ? $_W['siteroot'] . 'app/' . $this->createMobileUrl('wesites', array(
            'id' => $id
        ), true) : $reply['homeurl'];
        include $this->template('index');
    }
}