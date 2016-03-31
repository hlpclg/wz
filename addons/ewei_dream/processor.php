<?php

/**
 * 梦想契约
 * @author 狸小狐 QQ:22185157
 */
defined('IN_IA') or exit('Access Denied');

class Ewei_dreamModuleProcessor extends WeModuleProcessor {
    public function respond() {
        global $_W;
        $rid = $this->rule;
        if ($rid) {
            $reply = pdo_fetch("SELECT * FROM " . tablename('ewei_dream_reply') . " WHERE rid = :rid", array(':rid' => $rid));
            if ($reply) {
                $news = array( 
                    array(
                    'title' => $reply['title'],
                    'description' => $reply['description'],
                    'picurl' => $_W['attachurl'] . $reply['thumb'],
                    'url' => $this->createMobileUrl('dream', array('rid' => $reply['rid']))
                ));
                return $this->respNews($news);
            }
        }
    }

}
