<?php
load()->func('pdo');
/*
升级更新
*/
pdo_query("
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS  `ims_meepo_newvote`;
CREATE TABLE `ims_meepo_newvote` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `left_uid` int(11) unsigned NOT NULL,
  `right_uid` int(11) unsigned NOT NULL,
  `ip` varchar(80) NOT NULL,
  `time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1042 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS  `ims_meepo_pai`;
CREATE TABLE `ims_meepo_pai` (
  `pid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL,
  `vid` int(11) unsigned NOT NULL,
  `num` int(11) unsigned NOT NULL,
  `school` varchar(200) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `src_img` varchar(250) NOT NULL,
  `time` int(11) unsigned NOT NULL,
  `sex` tinyint(2) unsigned NOT NULL DEFAULT '3',
  `uniacid` int(11) NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS  `ims_meepo_pai_log`;
CREATE TABLE `ims_meepo_pai_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL,
  `ip` varchar(80) NOT NULL,
  `time` int(11) unsigned NOT NULL,
  `uniacid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1622 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS  `ims_meepo_pai_report`;
CREATE TABLE `ims_meepo_pai_report` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL,
  `ip` varchar(80) NOT NULL,
  `repoer_reason` tinyint(3) NOT NULL,
  `report_content` text NOT NULL,
  `contact` varchar(20) NOT NULL,
  `time` int(11) unsigned NOT NULL,
  `uniacid` int(11) NOT NULL,
  `status` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS  `ims_meepo_pai_set`;
CREATE TABLE `ims_meepo_pai_set` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `title` varchar(180) NOT NULL,
  `chongfu` tinyint(3) NOT NULL,
  `share_url` varchar(200) NOT NULL,
  `bao_name` varchar(150) NOT NULL,
  `shen_name` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;
");

if(pdo_fieldexists('meepo_pai_set','bao_name')){
	pdo_query('ALTER TABLE `ims_meepo_pai_set` ADD `bao_name` varchar(150) NULL AFTER `share_url`;');
}
if(pdo_fieldexists('meepo_pai_set','shen_name')){
	pdo_query('ALTER TABLE `ims_meepo_pai_set` ADD `shen_name` varchar(150) NULL AFTER `share_url`;');
}