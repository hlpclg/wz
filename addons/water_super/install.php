<?php
global $_W;

$sql = "

DROP TABLE IF EXISTS `ims_water_super_address`;
CREATE TABLE `ims_water_super_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `customername` varchar(100) NOT NULL,
  `tel` varchar(100) NOT NULL,
  `customercity` varchar(100) NOT NULL,
  `customerarea` varchar(100) NOT NULL,
  `xiangxdz` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=167 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_water_super_areas`;
CREATE TABLE `ims_water_super_areas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `cityid` int(11) NOT NULL,
  `areaname` varchar(50) NOT NULL COMMENT '名称',
  `areaunicode` varchar(200) NOT NULL COMMENT '名称的Unicode码',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=124 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_water_super_cardnumber`;
CREATE TABLE `ims_water_super_cardnumber` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `themecode` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=612 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_water_super_citys`;
CREATE TABLE `ims_water_super_citys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `cityname` varchar(20) NOT NULL,
  `cityinfo` varchar(500) NOT NULL,
  `cityphoto` varchar(200) NOT NULL,
  `cityunicode` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_water_super_coupon`;
CREATE TABLE `ims_water_super_coupon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL COMMENT '标题',
  `description` varchar(200) NOT NULL COMMENT '描述',
  `money` int(10) NOT NULL,
  `start_date` date NOT NULL COMMENT '启用日期',
  `end_date` date NOT NULL COMMENT '截止日期',
  `status` int(2) NOT NULL COMMENT '状态',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_water_super_coupon_record`;
CREATE TABLE `ims_water_super_coupon_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `user_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `coupon_id` int(10) unsigned NOT NULL COMMENT '优惠券id',
  `status` int(2) NOT NULL COMMENT '状态',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_water_super_employees`;
CREATE TABLE `ims_water_super_employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `employeename` varchar(100) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `employeestate` int(2) NOT NULL,
  `sumorders` int(11) NOT NULL,
  `workstate` int(2) NOT NULL,
  `cityid` int(11) NOT NULL,
  `areaid` int(11) NOT NULL,
  `city` varchar(50) NOT NULL,
  `area` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_water_super_express`;
CREATE TABLE `ims_water_super_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `orderid` int(11) NOT NULL,
  `orderstate` varchar(10) NOT NULL,
  `expresstime` datetime NOT NULL,
  `employeename` varchar(100) NOT NULL,
  `employeeid` int(11) NOT NULL,
  `employeetel` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=395 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_water_super_goods`;
CREATE TABLE `ims_water_super_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `goodsname` varchar(50) NOT NULL,
  `goodsprice` float(10,2) NOT NULL,
  `goodsinfo` varchar(100) NOT NULL,
  `goodsphoto` varchar(100) NOT NULL,
  `isjj` int(11) NOT NULL DEFAULT '1',
  `danwei` varchar(10) NOT NULL DEFAULT '件',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=190 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_water_super_members`;
CREATE TABLE `ims_water_super_members` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `vipnumber` varchar(100) DEFAULT NULL,
  `cardnumber` varchar(100) NOT NULL,
  `membername` varchar(50) NOT NULL COMMENT '用户姓名',
  `sex` varchar(50) NOT NULL COMMENT '性别',
  `tel` varchar(20) NOT NULL COMMENT '电话',
  `balance` float(10,2) NOT NULL COMMENT '余额',
  `real_cost` float(10,2) NOT NULL COMMENT '实际消费累计',
  `coupon_cost` float(10,2) NOT NULL COMMENT '优惠券消费金额累计',
  `createtime` datetime NOT NULL,
  `coupons` varchar(50) NOT NULL COMMENT '优惠券',
  `memberstate` int(2) NOT NULL,
  `jifen` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=612 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_water_super_orders`;
CREATE TABLE `ims_water_super_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `openid` varchar(100) NOT NULL,
  `workopenid` varchar(100) NOT NULL,
  `ordercode` varchar(200) NOT NULL,
  `ordertime` datetime NOT NULL,
  `fuwuriqi` varchar(100) NOT NULL,
  `fuwushijian` varchar(100) NOT NULL,
  `addressid` int(11) NOT NULL,
  `customername` varchar(200) NOT NULL,
  `customertel` varchar(100) NOT NULL,
  `customercity` varchar(100) NOT NULL,
  `customerarea` varchar(100) NOT NULL,
  `xiangxdz` varchar(1000) NOT NULL,
  `ordertype` varchar(20) NOT NULL,
  `orderstate` int(2) NOT NULL,
  `ordercost` decimal(10,2) NOT NULL,
  `paytype` int(2) NOT NULL,
  `paystate` int(2) NOT NULL,
  `paytime` datetime NOT NULL,
  `transid` varchar(100) DEFAULT NULL,
  `detail` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=261 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_water_super_rnumber`;
CREATE TABLE `ims_water_super_rnumber` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=261 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_water_super_shop`;
CREATE TABLE `ims_water_super_shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `shopname` varchar(200) NOT NULL,
  `indexad` varchar(200) NOT NULL,
  `xc3` varchar(200) DEFAULT NULL,
  `sltts` varchar(500) NOT NULL,
  `sltxc` varchar(200) NOT NULL,
  `xc2` varchar(200) DEFAULT NULL,
  `xc1` varchar(200) DEFAULT NULL,
  `kefutel` varchar(100) NOT NULL,
  `creater` varchar(100) NOT NULL,
  `cardlogo` varchar(512) NOT NULL,
  `czlogo` varchar(512) NOT NULL,
  `fanwei` varchar(500) NOT NULL,
  `fanuniacidesc` varchar(1000) NOT NULL,
  `fuwupic` varchar(512) NOT NULL,
  `jiamupic` varchar(512) NOT NULL,
  `cityunicode` varchar(1000) NOT NULL,
  `areaunicode` varchar(1000) NOT NULL,
  `addemployeepwd` varchar(100) NOT NULL,
  `needaudit` int(2) NOT NULL,
  `dangmf` int(2) NOT NULL,
  `weixf` int(2) NOT NULL,
  `zhifb` int(2) NOT NULL,
  `kefuwx` varchar(50) NOT NULL,
  `goodsinfourl` varchar(500) NOT NULL,
  `smsdx` int(2) NOT NULL,
  `mbxx` int(2) NOT NULL,
  `smsuid` varchar(50) NOT NULL,
  `smspwd` varchar(50) NOT NULL,
  `unewordermid` varchar(200) NOT NULL,
  `utopayordermid` varchar(200) NOT NULL,
  `upayordermid` varchar(200) NOT NULL,
  `wnewordermid` varchar(200) NOT NULL,
  `ddzt0` varchar(50) NOT NULL,
  `ddzt1` varchar(50) NOT NULL,
  `ddzt2` varchar(50) NOT NULL,
  `ddzt3` varchar(50) NOT NULL,
  `ddzt4` varchar(50) NOT NULL,
  `ddzt5` varchar(50) NOT NULL,
  `fuwuname` varchar(50) NOT NULL,
  `isygdj` int(11) NOT NULL DEFAULT '0',
  `recharge` varchar(100) NOT NULL,
  `yjfkurl` varchar(100) NOT NULL,
  `smsyzmb` varchar(100) NOT NULL,
  `imglb1` varchar(200) NOT NULL,
  `imglb2` varchar(200) NOT NULL,
  `imglb3` varchar(200) NOT NULL,
  `imgurl1` varchar(500) NOT NULL,
  `imgurl2` varchar(500) NOT NULL,
  `imgurl3` varchar(500) NOT NULL,
  `template` varchar(50) NOT NULL DEFAULT 'index',
  `pczjs` varchar(500) NOT NULL,
  `pfwfw` varchar(500) NOT NULL,
  `pxctp1` varchar(500) NOT NULL,
  `pxctp2` varchar(500) NOT NULL,
  `iswww` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
";
pdo_query($sql);