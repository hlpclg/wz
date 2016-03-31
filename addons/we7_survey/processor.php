<?php

/**
 * 调研模块处理程序
 *
 * @author WeiZan System
 * @url http://bbs.012wz.com
 */
defined('IN_IA') or exit('Access Denied');

class We7_surveyModuleProcessor extends WeModuleProcessor {

    public function respond() {
        global $_W;
        $rid = $this->rule;
        if ($rid) {
            $reply = pdo_fetch("SELECT * FROM " . tablename('survey_reply') . " WHERE rid = :rid", array(':rid' => $rid));
            if ($reply) {
                $sql = 'SELECT * FROM ' . tablename('survey') . ' WHERE `weid`=:weid AND `sid`=:sid';
                $activity = pdo_fetch($sql, array(':weid' => $_W['uniacid'], ':sid' => $reply['sid']));
                $news = array();
                $news[] = array(
                    'title' => $activity['title'],
                    'description' => strip_tags($activity['description']),
                    'picurl' => $_W['attachurl'] . $activity['thumb'],
                    'url' => $this->createMobileUrl('survey', array('id' => $activity['sid'], 'weid' => $_W['uniacid']))
                );
                return $this->respNews($news);
            }
        }
        return null;
    }

}
