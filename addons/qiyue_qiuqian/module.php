<?php
/**
 * 祈福签模块定义
 *
 * @author 冯齐跃
 * @url http://www.admin9.com/
 */
defined('IN_IA') or exit('Access Denied');

class qiyue_qiuqianModule extends WeModule
{
    public function settingsDisplay($settings)
    {
        global $_W, $_GPC;
        load()->func('file');
        $_W['page']['title'] = '签文参数设置';
        if ($_W['isajax'] && $_GPC['op'] == 'delete' && $_GPC['filename']) {
            file_delete($_GPC['filename']);
            exit('ok');
        }
        if (checksubmit()) {
            // 配置
            $dat = $_GPC['add'];
            $dat['imgUrl'] = $_GPC['imgUrl'];
            $this->saveSettings($dat);
            // 签文
            $qian = $_GPC['qian'];
            $f_exp = "::::::";
            $r_exp = PHP_EOL;
            $morepic = "";
            for ($i = 0; $i < count($qian['filename']); $i++) {
                //替换非法字符
                $name = str_replace($f_exp, "", $qian['title'][$i]);
                $name = str_replace($r_exp, "", $name);
                $pic = str_replace($f_exp, "", $qian['filename'][$i]);
                $pic = str_replace($r_exp, "", $pic);
                if ($pic) {
                    $morepic .= $pic . $f_exp . $name . $r_exp;
                }
            }
            // 去掉最后的字符
            // $morepic = substr($morepic,0,strlen($morepic)-2);
            $morepic = trim($morepic);
            $check = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('qiyue_qiuqian') . " WHERE uniacid=:uniacid", array(':uniacid' => $_W['uniacid']));
            if ($check) {
                pdo_update('qiyue_qiuqian', array('morepic' => $morepic), array('uniacid' => $_W['uniacid']));
            }
            else {
                $add['uniacid'] = $_W['uniacid'];
                $add['morepic'] = $morepic;
                pdo_insert('qiyue_qiuqian', $add);
            }
            message('设置成功', 'referer', 'success');
        }
        if (empty($settings)) {
            $settings = array(
                'title' => '新年祈福签',
                'desc' => '我在' . $_W['account']['name'] . '求了一支新年签，你也来吧！',
                'imgUrl' => tomedia('./addons/qiuqian/icon.jpg'),
            );
        }
        $qian_r = pdo_fetch("SELECT * FROM " . tablename('qiyue_qiuqian') . " WHERE uniacid=:uniacid", array(':uniacid' => $_W['uniacid']));
        $morepic = array();
        if ($qian_r['morepic']) {
            $f_exp = "::::::";
            $r_exp = PHP_EOL;
            $rr = explode($r_exp, $qian_r['morepic']);
            for ($i = 0; $i < count($rr); $i++) {
                $fr = explode($f_exp, $rr[$i]);
                $morepic[] = array(
                    'title' => $fr['1'],
                    'filename' => $fr['0']
                );
            }
            unset($qian_r['morepic']);
        }
        include $this->template('setting');
    }
}