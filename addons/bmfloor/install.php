<?php
/**
 * 抢楼活动模块定义
 *
 * @author 美丽心情
 * @qq 513316788
 */
$sql =<<<EOF
CREATE TABLE IF NOT EXISTS `ims_bmfloor_award` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `floor` VARCHAR(100) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `from_user` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_bmfloor_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `from_user` varchar(50) NOT NULL DEFAULT '',
  `share_point` int(10) unsigned NOT NULL DEFAULT '0',  
  `share_used` int(10) unsigned NOT NULL DEFAULT '0', 
  `weid` int(10) NOT NULL,   
  `createtime` int(10) NOT NULL,    
  `IPaddress` char(15) NOT NULL DEFAULT '',  
  `rid` int(10) unsigned NOT NULL DEFAULT '0',  
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_bmfloor_winner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `from_user` varchar(32) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` char(15) NOT NULL DEFAULT '',
  `awardid` int(10) unsigned NOT NULL DEFAULT '0',
  `awardname` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `floor` int(10) NOT NULL DEFAULT '0',
  `realname` varchar(32) NOT NULL DEFAULT '',  
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_bmfloor` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `awardprompt` text NOT NULL DEFAULT '',
  `currentprompt` text NOT NULL DEFAULT '',
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `url` varchar(255) NOT NULL DEFAULT '', 
  `total` int(10) unsigned NOT NULL DEFAULT '0',  
  `memo` text NOT NULL DEFAULT '',  
  `picture` varchar(255) NOT NULL DEFAULT '',    
  `password` varchar(20) NOT NULL DEFAULT '',      
  `url1` varchar(255) NOT NULL DEFAULT '', 
  `starttime` DATETIME NOT NULL,
  `endtime` DATETIME NOT NULL,    
  `memo1` text NOT NULL DEFAULT '',  
  `memo2` text NOT NULL DEFAULT '',
  `share_keyword` varchar(100) NOT NULL DEFAULT '',
  `share_logo` varchar(255) NOT NULL DEFAULT '',
  `share_memo` text NOT NULL DEFAULT '',
  `share_statement` text NOT NULL DEFAULT '',
  `share_url` varchar(255) NOT NULL DEFAULT '',
  `share_point` int(10) unsigned NOT NULL DEFAULT '0',          
  `adv_url` varchar(255) NOT NULL DEFAULT '',  
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
EOF;
pdo_run($sql);
