<?php
defined('IN_IA') or exit('Access Denied');

if(!pdo_fieldexists('xc_article_article', 'redirect_url')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `redirect_url` varchar(500) NOT NULL DEFAULT '' AFTER `displayorder`;");
}
if(!pdo_fieldexists('xc_article_article_category', 'thumb')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article_category')." ADD `thumb` varchar(1024) NOT NULL DEFAULT '' AFTER `icon`;");
}
if(!pdo_fieldexists('xc_article_article', 'sharethumb')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `sharethumb` varchar(1024) NOT NULL DEFAULT '' AFTER `thumb`;");
}
if(!pdo_fieldexists('xc_article_article', 'praise_count')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `praise_count` varchar(500) NOT NULL DEFAULT '' AFTER `max_credit`;");
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `read_count` varchar(500) NOT NULL DEFAULT '' AFTER `max_credit`;");
}
if(!pdo_fieldexists('xc_article_article', 'templatefile')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `templatefile` varchar(500) NOT NULL DEFAULT '' AFTER `template`;");
}

if(!pdo_fieldexists('xc_article_article', 'recommendation')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `recommendation` varchar(1024) NOT NULL COMMENT '推荐ID列表' AFTER `author`;");
}

if(!pdo_fieldexists('xc_article_article', 'recommendation_source')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `recommendation_source` varchar(1024) NOT NULL COMMENT '推荐来源user自定义rand随机' AFTER `recommendation`;");
  pdo_query("UPDATE " .tablename('xc_article_article') . " SET `recommendation_source`='rand'");
}

if(!pdo_fieldexists('xc_article_article_category', 'templatefile')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article_category')." ADD `templatefile` varchar(500) NOT NULL DEFAULT '' AFTER `template`;");
}

if(!pdo_fieldexists('xc_article_share_track', 'ip')) {
  pdo_query("ALTER TABLE ".tablename('xc_article_share_track')." ADD `ip` varchar(64) NOT NULL DEFAULT '' AFTER `access_time`;");
}


if(!pdo_fieldexists('xc_article_share_track', 'clicker_id')) {
  pdo_query("ALTER TABLE ".tablename('xc_article_share_track')." ADD `clicker_id` varchar(100) NOT NULL DEFAULT '' AFTER `access_time`;");
}

if(!pdo_fieldexists('xc_article_article', 'adv_top')) {
  pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `adv_on_off` varchar(10) NOT NULL DEFAULT 'off', ADD `adv_top` TEXT(10240) NOT NULL DEFAULT '',  ADD `adv_status` TEXT(10240) NOT NULL DEFAULT '', ADD `adv_bottom` TEXT(10240) NOT NULL DEFAULT '' ");
}

pdo_query("CREATE TABLE IF NOT EXISTS " . tablename('xc_article_adv_cache') . "(
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `adv_on_off` varchar(10) NOT NULL DEFAULT 'off',
  `adv_top` TEXT(10240) NOT NULL DEFAULT '',
  `adv_status` TEXT(10240) NOT NULL DEFAULT '',
  `adv_bottom` TEXT(10240) NOT NULL DEFAULT ''
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

pdo_query("UPDATE " . tablename('xc_article_article') . " SET template='jupai' WHERE template='round_box' or template='thumb_plain' or template='plain'");
pdo_query("UPDATE " . tablename('xc_article_article_category') . " SET template='jupai' WHERE template='round_box' or template='thumb_plain' or template='plain'");

if(!pdo_fieldexists('xc_article_article', 'per_user_credit')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `per_user_credit` int(10) NOT NULL DEFAULT '0' AFTER `max_credit`;");
}

if(pdo_fieldexists('xc_article_article', 'max_credit')) {
  pdo_query("ALTER TABLE  " . tablename("xc_article_article") . " CHANGE  `max_credit`  `max_credit` INT( 10 ) NOT NULL DEFAULT  '0' COMMENT  '积分奖励上限';");
}

