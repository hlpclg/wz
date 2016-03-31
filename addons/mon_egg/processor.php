<?php

defined('IN_IA') or exit('Access Denied');
define("MON_EGG", "mon_egg");
require_once IA_ROOT . "/addons/" . MON_EGG . "/dbutil.class.php";
require_once IA_ROOT . "/addons/" . MON_EGG . "/monUtil.class.php";


class Mon_EggModuleProcessor extends WeModuleProcessor {

    public function respond()
    {
        $rid = $this->rule;

        $egg = pdo_fetch("select * from " . tablename(DBUtil::$TABLE_EGG) . " where rid=:rid", array(":rid" => $rid));

        if (!empty($egg)) {
            if (TIMESTAMP < $egg['starttime']) {
                return $this->respText("砸金蛋活动未开始!");
            }
            $news = array();
            $news [] = array('title' => $egg['new_title'], 'description' => $egg['new_content'], 'picurl' => MonUtil::getpicurl($egg ['new_icon']), 'url' => $this->createMobileUrl('Index', array('egid' => $egg['id'])));
            return $this->respNews($news);
        } else {
            return $this->respText("砸金蛋活动未开始");
        }

        return null;


    }


}
