<?php
/**
 * 龙舟大赛
 *
 * 作者:迷失卍国度
 *
 * qq : 15595755
 */
defined('IN_IA') or exit('Access Denied');
include "model.php";

class weisrc_dragonboatModule extends WeModule {

    public $tablename = 'weisrc_dragonboat_reply';

    public function fieldsFormDisplay($rid = 0) {
        global $_W;
        load()->func('tpl');
        if (!empty($rid)) {
            $reply = pdo_fetch("SELECT * FROM " . tablename($this->tablename) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
        }
        if (!$reply) {
            $now = TIMESTAMP;
            $reply = array(
                "title" => "龙舟大赛开始了!",
                "start_picurl" => "../addons/weisrc_dragonboat/template/style/game.jpg",
                "description" => "欢迎参加龙舟大赛",
                "rule" => '<p><br />吃到粽子随机奖励0-3秒时间，<br /><br />比一比谁划的远!<br /><br />小心石头，别挂掉哦</p>',
                "award" => '',
                "starttime" => $now,
                "endtime" => strtotime(date("Y-m-d H:i", $now + 7 * 24 * 3600)),
                "end_picurl" => "../addons/weisrc_dragonboat/template/style/game.jpg",
                "share_image" => "../addons/weisrc_dragonboat/icon.jpg",
                "end_theme" => "活动结束了",
                "end_instruction" => "活动已经结束了",
                "number_times" => 0,
                "gametime" => 15,
                "gamelevel" => 3,
                "showusernum" => 20,
                "cover" => "../addons/weisrc_dragonboat/template/mobile/boat/App_Content/Game/Boats/style/images/cover.jpg",
                "most_num_times" => 1,
                "daysharenum" => 1,
                "mode" => 0,
                "sharelotterynum" =>1,
                'copyright' => '',
                'isneedfollow' => 1,
                'copyrighturl' => '#',
                "share_title" => "欢迎参加龙舟大赛",
                "share_desc" => "亲，欢迎参加龙舟大赛活动，祝您好运哦！！ ",
            );
        }

        include $this->template('form');
    }

    public function fieldsFormValidate($rid = 0) {
        //规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
        return '';
    }

    public function fieldsFormSubmit($rid) {
        global $_GPC, $_W;
        load()->func('tpl');
        $id = intval($_GPC['reply_id']);

        $insert = array(
            'rid' => $rid,
            'weid' => $_W['uniacid'],
            'title' => trim($_GPC['title']),
            'content' => trim($_GPC['content']),
            'description' => trim($_GPC['description']),
            'rule' => trim($_GPC['rule']),
            'award' => trim($_GPC['award']),
            'end_theme' => $_GPC['end_theme'],
            'end_instruction' => $_GPC['end_instruction'],
            'number_times' => intval($_GPC['number_times']),
            'most_num_times' => intval($_GPC['most_num_times']),
            'daysharenum' => intval($_GPC['daysharenum']),
            'starttime' => strtotime($_GPC['datelimit']['start']),
//            'endtime' => strtotime($_GPC['datelimit']['end']) + 86400 - 1,
            'endtime' => strtotime($_GPC['datelimit']['end']),
            'dateline' => TIMESTAMP,
            'copyright' => $_GPC['copyright'],
            'copyrighturl' => $_GPC['copyrighturl'],
            "gametime" => intval($_GPC['gametime']),
            "gamelevel" => intval($_GPC['gamelevel']),
            "number_times" => intval($_GPC['number_times']),
            "showusernum" => intval($_GPC['showusernum']),
            "most_num_times" => intval($_GPC['most_num_times']),
            "daysharenum" => intval($_GPC['daysharenum']),
            "mode" => intval($_GPC['mode']),
            "isneedfollow" => intval($_GPC['isneedfollow']),
            "sharelotterynum" => intval($_GPC['sharelotterynum']),
            'share_title' => $_GPC['share_title'],
            'share_desc' => $_GPC['share_desc'],
            'share_url' => $_GPC['share_url'],
            'follow_url' => $_GPC['follow_url'],
        );

        if (!empty($_GPC['start_picurl'])) {
            $insert['start_picurl'] = $_GPC['start_picurl'];
        }

        if (!empty($_GPC['end_picurl'])) {
            $insert['end_picurl'] = $_GPC['end_picurl'];
        }

        if (!empty($_GPC['share_image'])) {
            $insert['share_image'] = $_GPC['share_image'];
        }

        if (!empty($_GPC['cover'])) {
            $insert['cover'] = $_GPC['cover'];
        }

        if (empty($id)) {
            if ($insert['starttime'] <= time()) {
                $insert['status'] = 1;
            } else {
                $insert['status'] = 0;
            }
            $id = pdo_insert($this->tablename, $insert);
        } else {
            unset($insert['dateline']);
            pdo_update($this->tablename, $insert, array('id' => $id));
        }
        return true;
    }

    public function ruleDeleted($rid) {
        pdo_delete('weisrc_dragonboat_reply', array('rid' => $rid));
        pdo_delete('weisrc_dragonboat_fans', array('rid' => $rid));
        pdo_delete('weisrc_dragonboat_record', array('rid' => $rid));
    }

    public function settingsDisplay($settings) {
        global $_GPC, $_W;
    }
}
