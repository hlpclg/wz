/*
MySQL Backup
Source Server Version: 5.1.57
Source Database: yc500
Date: 2015-12-12 01:53:22
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
--  Table structure for `ims_abc_replace`
-- ----------------------------
DROP TABLE IF EXISTS `ims_abc_replace`;
CREATE TABLE `ims_abc_replace` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `replace` varchar(32) DEFAULT NULL,
  `name` varchar(32) DEFAULT NULL,
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `id` (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_account`
-- ----------------------------
DROP TABLE IF EXISTS `ims_account`;
CREATE TABLE `ims_account` (
  `acid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `hash` varchar(8) NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `isconnect` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`acid`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_account_wechats`
-- ----------------------------
DROP TABLE IF EXISTS `ims_account_wechats`;
CREATE TABLE `ims_account_wechats` (
  `acid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `token` varchar(32) NOT NULL,
  `encodingaeskey` varchar(255) NOT NULL,
  `level` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `name` varchar(30) NOT NULL,
  `account` varchar(30) NOT NULL,
  `original` varchar(50) NOT NULL,
  `signature` varchar(100) NOT NULL,
  `country` varchar(10) NOT NULL,
  `province` varchar(3) NOT NULL,
  `city` varchar(15) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(32) NOT NULL,
  `lastupdate` int(10) unsigned NOT NULL DEFAULT '0',
  `key` varchar(50) NOT NULL,
  `secret` varchar(50) NOT NULL,
  `styleid` int(10) unsigned NOT NULL DEFAULT '1',
  `subscribeurl` varchar(120) NOT NULL,
  `topad` varchar(225) NOT NULL,
  `footad` varchar(225) NOT NULL,
  `auth_refresh_token` varchar(255) NOT NULL,
  PRIMARY KEY (`acid`),
  KEY `idx_key` (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_account_yixin`
-- ----------------------------
DROP TABLE IF EXISTS `ims_account_yixin`;
CREATE TABLE `ims_account_yixin` (
  `acid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `token` varchar(32) NOT NULL,
  `access_token` varchar(1000) NOT NULL DEFAULT '',
  `level` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `name` varchar(30) NOT NULL,
  `account` varchar(30) NOT NULL,
  `signature` varchar(100) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(32) NOT NULL,
  `key` varchar(50) NOT NULL,
  `secret` varchar(50) NOT NULL,
  `styleid` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`acid`),
  KEY `idx_key` (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_activity`
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity`;
CREATE TABLE `ims_activity` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) unsigned DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `ac_pic` varchar(100) NOT NULL,
  `begintime` int(11) unsigned DEFAULT NULL,
  `endtime` int(11) unsigned DEFAULT NULL,
  `createtime` int(11) unsigned DEFAULT NULL,
  `countlimit` int(5) NOT NULL,
  `countvirtual` int(5) DEFAULT '0',
  `visitsCount` int(11) DEFAULT '0',
  `ppt1` varchar(100) DEFAULT NULL,
  `ppt2` varchar(100) DEFAULT NULL,
  `ppt3` varchar(100) DEFAULT NULL,
  `acdes` varchar(500) NOT NULL DEFAULT '',
  `address` varchar(200) NOT NULL,
  `location_p` varchar(100) NOT NULL COMMENT '所在地区_省',
  `location_c` varchar(100) NOT NULL COMMENT '所在地区_市',
  `location_a` varchar(100) NOT NULL COMMENT '所在地区_区',
  `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000',
  `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000',
  `tel` varchar(20) DEFAULT NULL,
  `email` varchar(20) DEFAULT NULL,
  `zb` varchar(50) DEFAULT NULL,
  `cb` varchar(50) DEFAULT NULL,
  `xb` varchar(50) DEFAULT NULL,
  `cjdx` varchar(50) DEFAULT NULL,
  `hoteldesc` varchar(500) DEFAULT NULL,
  `costdes` varchar(500) DEFAULT NULL,
  `isrepeat` int(1) DEFAULT '0',
  `istip` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_activity_coupon`
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_coupon`;
CREATE TABLE `ims_activity_coupon` (
  `couponid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `type` tinyint(4) NOT NULL,
  `title` varchar(30) NOT NULL DEFAULT '',
  `couponsn` varchar(50) NOT NULL,
  `description` text,
  `discount` decimal(10,2) NOT NULL,
  `condition` decimal(10,2) NOT NULL,
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  `limit` int(11) NOT NULL DEFAULT '0',
  `dosage` int(11) unsigned NOT NULL DEFAULT '0',
  `amount` int(11) unsigned NOT NULL,
  `thumb` varchar(500) NOT NULL,
  `credit` int(10) unsigned NOT NULL,
  `credittype` varchar(20) NOT NULL,
  `use_module` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`couponid`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_activity_coupon_allocation`
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_coupon_allocation`;
CREATE TABLE `ims_activity_coupon_allocation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `couponid` int(10) unsigned NOT NULL,
  `groupid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`couponid`,`groupid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_activity_coupon_modules`
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_coupon_modules`;
CREATE TABLE `ims_activity_coupon_modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `couponid` int(10) unsigned NOT NULL,
  `module` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `couponid` (`couponid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_activity_coupon_password`
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_coupon_password`;
CREATE TABLE `ims_activity_coupon_password` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(200) NOT NULL DEFAULT '',
  `mobile` varchar(20) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `nickname` varchar(30) NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_activity_coupon_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_coupon_record`;
CREATE TABLE `ims_activity_coupon_record` (
  `recid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `grantmodule` varchar(50) NOT NULL DEFAULT '',
  `granttime` int(10) unsigned NOT NULL DEFAULT '0',
  `usemodule` varchar(50) NOT NULL DEFAULT '',
  `usetime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `remark` varchar(300) NOT NULL DEFAULT '',
  `couponid` int(10) unsigned NOT NULL,
  `operator` varchar(30) NOT NULL,
  `clerk_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`recid`),
  KEY `couponid` (`uid`,`grantmodule`,`usemodule`,`status`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_activity_day`
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_day`;
CREATE TABLE `ims_activity_day` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `aid` int(11) unsigned DEFAULT NULL,
  `daytime` int(11) unsigned DEFAULT NULL,
  `dname` varchar(20) DEFAULT NULL,
  `ddes` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_activity_exchange`
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_exchange`;
CREATE TABLE `ims_activity_exchange` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `thumb` varchar(500) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `extra` varchar(3000) NOT NULL DEFAULT '',
  `credit` int(10) unsigned NOT NULL,
  `credittype` varchar(10) NOT NULL,
  `pretotal` int(11) NOT NULL,
  `num` int(11) NOT NULL,
  `total` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_activity_exchange_trades`
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_exchange_trades`;
CREATE TABLE `ims_activity_exchange_trades` (
  `tid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `exid` int(10) unsigned NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`tid`),
  KEY `uniacid` (`uniacid`,`uid`,`exid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_activity_exchange_trades_shipping`
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_exchange_trades_shipping`;
CREATE TABLE `ims_activity_exchange_trades_shipping` (
  `tid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `exid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  `province` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `district` varchar(30) NOT NULL,
  `address` varchar(255) NOT NULL,
  `zipcode` varchar(6) NOT NULL,
  `mobile` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`tid`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_activity_guest`
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_guest`;
CREATE TABLE `ims_activity_guest` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `aid` int(11) unsigned DEFAULT NULL,
  `gname` varchar(20) NOT NULL,
  `jobtitle` varchar(20) NOT NULL,
  `gdesc` varchar(500) NOT NULL,
  `sig` varchar(20) NOT NULL,
  `headimage` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='嘉宾';

-- ----------------------------
--  Table structure for `ims_activity_mail`
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_mail`;
CREATE TABLE `ims_activity_mail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `weid` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_activity_modules`
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_modules`;
CREATE TABLE `ims_activity_modules` (
  `mid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `exid` int(10) unsigned NOT NULL,
  `module` varchar(50) NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `available` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`mid`),
  KEY `uniacid` (`uniacid`),
  KEY `module` (`module`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_activity_modules_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_modules_record`;
CREATE TABLE `ims_activity_modules_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned NOT NULL,
  `num` tinyint(3) NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_activity_note`
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_note`;
CREATE TABLE `ims_activity_note` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `aid` int(11) unsigned DEFAULT NULL,
  `title` varchar(50) NOT NULL,
  `ndesc` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_activity_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_reply`;
CREATE TABLE `ims_activity_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `aid` int(10) unsigned NOT NULL,
  `new_pic` varchar(200) NOT NULL,
  `news_content` varchar(500) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_activity_stores`
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_stores`;
CREATE TABLE `ims_activity_stores` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `business_name` varchar(50) NOT NULL,
  `branch_name` varchar(50) NOT NULL,
  `category` varchar(255) NOT NULL,
  `province` varchar(15) NOT NULL,
  `city` varchar(15) NOT NULL,
  `district` varchar(15) NOT NULL,
  `address` varchar(50) NOT NULL,
  `longitude` varchar(15) NOT NULL,
  `latitude` varchar(15) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `photo_list` varchar(10000) NOT NULL,
  `avg_price` int(10) unsigned NOT NULL,
  `opentime` varchar(50) NOT NULL,
  `recommend` varchar(255) NOT NULL,
  `special` varchar(255) NOT NULL,
  `introduction` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_activity_user`
-- ----------------------------
DROP TABLE IF EXISTS `ims_activity_user`;
CREATE TABLE `ims_activity_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `aid` int(11) unsigned DEFAULT NULL,
  `createtime` int(11) unsigned DEFAULT NULL,
  `uname` varchar(20) DEFAULT NULL,
  `sex` varchar(10) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `email` varchar(20) NOT NULL,
  `company` varchar(20) NOT NULL,
  `jobtitle` varchar(20) NOT NULL,
  `acname` varchar(50) DEFAULT NULL,
  `openid` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_album`
-- ----------------------------
DROP TABLE IF EXISTS `ims_album`;
CREATE TABLE `ims_album` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `banner` varchar(255) NOT NULL DEFAULT '',
  `content` varchar(1000) NOT NULL DEFAULT '',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `pcate` int(11) unsigned NOT NULL DEFAULT '0',
  `ccate` int(11) unsigned NOT NULL DEFAULT '0',
  `isview` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_album_category`
-- ----------------------------
DROP TABLE IF EXISTS `ims_album_category`;
CREATE TABLE `ims_album_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `thumb` varchar(255) NOT NULL COMMENT '分类图片',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `description` varchar(500) NOT NULL COMMENT '分类介绍',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_album_photo`
-- ----------------------------
DROP TABLE IF EXISTS `ims_album_photo`;
CREATE TABLE `ims_album_photo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `albumid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `attachment` varchar(255) NOT NULL DEFAULT '',
  `ispreview` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_albumid` (`albumid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_album_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_album_reply`;
CREATE TABLE `ims_album_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `albumid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_amouse_house`
-- ----------------------------
DROP TABLE IF EXISTS `ims_amouse_house`;
CREATE TABLE `ims_amouse_house` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `title` varchar(25) NOT NULL COMMENT '标题',
  `price` varchar(100) NOT NULL COMMENT '租金总价',
  `square_price` varchar(100) NOT NULL COMMENT '每平方价格',
  `area` varchar(100) NOT NULL COMMENT '面积',
  `house_type` varchar(100) NOT NULL COMMENT '户型',
  `floor` varchar(100) NOT NULL COMMENT '楼层',
  `orientation` varchar(100) NOT NULL COMMENT '朝向',
  `type` varchar(2) NOT NULL COMMENT '0：出租；1：求租；2：出售/3：求购',
  `status` varchar(2) NOT NULL COMMENT '是否显示/审核',
  `recommed` int(1) NOT NULL COMMENT '推荐 0未推荐 1推荐',
  `contacts` varchar(100) NOT NULL COMMENT '联系人',
  `phone` varchar(13) NOT NULL COMMENT '联系电话',
  `introduction` text NOT NULL COMMENT '详细描述',
  `openid` varchar(25) NOT NULL COMMENT '微信OPENID',
  `createtime` int(10) NOT NULL,
  `thumb3` varchar(1000) NOT NULL DEFAULT '',
  `thumb4` varchar(1000) NOT NULL DEFAULT '',
  `thumb1` varchar(1000) NOT NULL DEFAULT '',
  `thumb2` varchar(1000) NOT NULL DEFAULT '',
  `place` varchar(1000) NOT NULL DEFAULT '',
  `lat` varchar(1000) NOT NULL DEFAULT '0.0000000000',
  `lng` varchar(1000) NOT NULL DEFAULT '0.0000000000',
  `location_p` varchar(1000) NOT NULL DEFAULT '',
  `location_c` varchar(1000) NOT NULL DEFAULT '',
  `location_a` varchar(1000) NOT NULL DEFAULT '',
  `brokerage` varchar(1000) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='租房出售';

-- ----------------------------
--  Table structure for `ims_amouse_house_slide`
-- ----------------------------
DROP TABLE IF EXISTS `ims_amouse_house_slide`;
CREATE TABLE `ims_amouse_house_slide` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `url` varchar(200) NOT NULL DEFAULT '',
  `slide` varchar(200) NOT NULL DEFAULT '',
  `listorder` int(10) unsigned NOT NULL DEFAULT '0',
  `isshow` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_amouse_newflats`
-- ----------------------------
DROP TABLE IF EXISTS `ims_amouse_newflats`;
CREATE TABLE `ims_amouse_newflats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL COMMENT '姓名',
  `thumb` varchar(255) NOT NULL COMMENT '图片',
  `price` varchar(100) NOT NULL COMMENT '价格',
  `type` varchar(200) NOT NULL COMMENT '建筑类型',
  `years` varchar(100) NOT NULL COMMENT '产权年限',
  `wytype` varchar(100) NOT NULL COMMENT '物业类别',
  `cqtype` varchar(100) NOT NULL COMMENT '产权类型',
  `jzarea` varchar(100) NOT NULL COMMENT '建筑面积',
  `ratio` varchar(100) NOT NULL COMMENT '容积率',
  `floor_area` varchar(100) NOT NULL COMMENT '房屋面积',
  `afforestation` varchar(100) NOT NULL COMMENT '绿化率',
  `total` varchar(100) NOT NULL COMMENT '总户型',
  `door_area` varchar(100) NOT NULL COMMENT '户型面积',
  `road_transport` varchar(100) NOT NULL COMMENT '道路交通',
  `investors` varchar(100) NOT NULL COMMENT '投资商',
  `developers` varchar(100) NOT NULL COMMENT '开发商',
  `property_compay` varchar(100) NOT NULL COMMENT '物业公司',
  `propertypay` varchar(100) NOT NULL COMMENT '物业费',
  `features` varchar(100) NOT NULL COMMENT '楼盘特色',
  `sales_addres` varchar(100) NOT NULL COMMENT '售楼地址',
  `checkin_time` varchar(100) NOT NULL COMMENT '入住时间',
  `sales_status` varchar(100) NOT NULL COMMENT '销售状况',
  `average_price` varchar(100) NOT NULL COMMENT '均价',
  `discounted_costs` varchar(100) NOT NULL COMMENT '折扣价格',
  `payment` varchar(100) NOT NULL COMMENT '付款方式',
  `business` varchar(100) NOT NULL COMMENT '商业配套',
  `banks` varchar(100) NOT NULL COMMENT '银行',
  `trading_area` varchar(100) NOT NULL COMMENT '商圈',
  `park` varchar(100) NOT NULL COMMENT '公园',
  `hotel` varchar(100) NOT NULL COMMENT '酒店',
  `supermarket` varchar(100) NOT NULL COMMENT '超市',
  `humanities` varchar(100) NOT NULL COMMENT '人文自然景观',
  `supporting` varchar(100) NOT NULL COMMENT '社区内配套',
  `internal` varchar(100) NOT NULL COMMENT '内部配套',
  `parking_number` varchar(100) NOT NULL COMMENT '车位数',
  `base` varchar(100) NOT NULL COMMENT '基本参数',
  `equally` varchar(100) NOT NULL COMMENT '公摊系数',
  `surrounding` varchar(100) NOT NULL COMMENT '周边商业',
  `landscape` varchar(100) NOT NULL COMMENT '周边景观',
  `hospitals` varchar(100) NOT NULL COMMENT '周边医院',
  `school` varchar(100) NOT NULL COMMENT '周边学校',
  `traffic` varchar(100) NOT NULL COMMENT '交通',
  `construction` varchar(100) NOT NULL COMMENT '建筑施工单位',
  `design` varchar(100) NOT NULL COMMENT '规划设计单位',
  `salecom` varchar(100) NOT NULL COMMENT '销售公司',
  `address` varchar(255) NOT NULL COMMENT '销售公司所在位置图片',
  `introduction` text NOT NULL COMMENT '详细描述',
  `readcount` int(11) DEFAULT '0' COMMENT '阅读量',
  `openid` varchar(25) NOT NULL COMMENT '微信OPENID',
  `like` int(11) DEFAULT '0' COMMENT '点赞',
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_amouse_sysset`
-- ----------------------------
DROP TABLE IF EXISTS `ims_amouse_sysset`;
CREATE TABLE `ims_amouse_sysset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `jjrmobile` varchar(13) NOT NULL COMMENT '手机',
  `broker` varchar(200) NOT NULL COMMENT '经纪人',
  `guanzhuUrl` varchar(255) DEFAULT '1' COMMENT '引导关注',
  `copyright` varchar(255) DEFAULT '' COMMENT '版权',
  `newflat_images` varchar(255) DEFAULT '' COMMENT '楼盘图片设置',
  `isoauth` int(10) DEFAULT '1' COMMENT '是否开启高级权限',
  `isshow` int(10) DEFAULT '1' COMMENT '是否只显示经纪人信息',
  `cnzz` varchar(255) DEFAULT '' COMMENT '统计',
  `appid` varchar(255) DEFAULT '',
  `appsecret` varchar(255) DEFAULT '',
  `appid_share` varchar(255) DEFAULT '',
  `appsecret_share` varchar(255) DEFAULT '',
  `defcity` varchar(1000) DEFAULT '中国',
  `nickname` varchar(500) DEFAULT NULL COMMENT '昵称',
  `openid` varchar(500) DEFAULT NULL COMMENT 'openid',
  `isadjuest` varchar(1) DEFAULT '1' COMMENT '是否审核',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_amouse_weicard2_fans`
-- ----------------------------
DROP TABLE IF EXISTS `ims_amouse_weicard2_fans`;
CREATE TABLE `ims_amouse_weicard2_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(100) DEFAULT '' COMMENT '用户ID',
  `mobile` varchar(20) DEFAULT '' COMMENT '手机',
  `name` varchar(20) DEFAULT ' ',
  `email` varchar(200) DEFAULT '',
  `createtime` int(10) DEFAULT '0',
  `qq` varchar(255) DEFAULT '',
  `job` varchar(255) DEFAULT '',
  `department` varchar(255) DEFAULT '',
  `company` varchar(255) DEFAULT '',
  `address` varchar(255) DEFAULT '',
  `area` varchar(255) DEFAULT '',
  `weixin` varchar(255) DEFAULT '',
  `joincount` int(11) DEFAULT '0',
  `template` varchar(300) NOT NULL DEFAULT '' COMMENT '模板',
  `templatefile` varchar(300) NOT NULL DEFAULT '' COMMENT '模板名称',
  `headimg` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_amouse_weicard2_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_amouse_weicard2_reply`;
CREATE TABLE `ims_amouse_weicard2_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL,
  `tpl` int(10) unsigned NOT NULL DEFAULT '0',
  `status` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(20) NOT NULL COMMENT '活动标题',
  `description` longtext NOT NULL COMMENT '活动介绍',
  `thumb` varchar(200) DEFAULT '',
  `isshow` tinyint(1) DEFAULT '0',
  `bj` varchar(100) NOT NULL COMMENT '名片图片',
  `viewnum` int(11) DEFAULT '0',
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_amouse_weicard2_sysset`
-- ----------------------------
DROP TABLE IF EXISTS `ims_amouse_weicard2_sysset`;
CREATE TABLE `ims_amouse_weicard2_sysset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `guanzhuUrl` varchar(255) DEFAULT '1' COMMENT '引导关注',
  `copyright` varchar(255) DEFAULT '' COMMENT '版权',
  `appid` varchar(255) DEFAULT '',
  `appsecret` varchar(255) DEFAULT '',
  `appid_share` varchar(255) DEFAULT '',
  `appsecret_share` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_amouse_weicard_bg`
-- ----------------------------
DROP TABLE IF EXISTS `ims_amouse_weicard_bg`;
CREATE TABLE `ims_amouse_weicard_bg` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `displayorder` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_amouse_weicard_bjyy`
-- ----------------------------
DROP TABLE IF EXISTS `ims_amouse_weicard_bjyy`;
CREATE TABLE `ims_amouse_weicard_bjyy` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `mid` int(10) NOT NULL,
  `musicid` int(10) NOT NULL,
  `from_user` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_amouse_weicard_card`
-- ----------------------------
DROP TABLE IF EXISTS `ims_amouse_weicard_card`;
CREATE TABLE `ims_amouse_weicard_card` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `from_user` varchar(255) NOT NULL,
  `mid` int(10) NOT NULL COMMENT '会员表id',
  `mobile` tinyint(1) DEFAULT '0' COMMENT 'type=1;0代表全部可见，1代表互相收藏可见，2代表自己可见',
  `email` tinyint(1) DEFAULT '0' COMMENT 'type=2;0代表全部可见，1代表互相收藏可见，2代表自己可见',
  `weixin` tinyint(1) DEFAULT '0' COMMENT 'type=3;0代表全部可见，1代表互相收藏可见，2代表自己可见',
  `address` tinyint(1) DEFAULT '0' COMMENT 'type=4;0代表全部可见，1代表互相收藏可见，2代表自己可见',
  `bgimg` varchar(255) DEFAULT NULL,
  `shopName` varchar(255) DEFAULT NULL,
  `templateFile` varchar(300) DEFAULT 'qianx_index',
  `shopIcon` varchar(255) DEFAULT NULL,
  `shopUrl` varchar(255) DEFAULT NULL,
  `zan` int(10) DEFAULT '0',
  `view` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_amouse_weicard_companyinfo`
-- ----------------------------
DROP TABLE IF EXISTS `ims_amouse_weicard_companyinfo`;
CREATE TABLE `ims_amouse_weicard_companyinfo` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `mid` int(10) NOT NULL,
  `cid` int(10) NOT NULL,
  `from_user` varchar(255) NOT NULL,
  `img` text,
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_amouse_weicard_industry`
-- ----------------------------
DROP TABLE IF EXISTS `ims_amouse_weicard_industry`;
CREATE TABLE `ims_amouse_weicard_industry` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `displayorder` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_amouse_weicard_member`
-- ----------------------------
DROP TABLE IF EXISTS `ims_amouse_weicard_member`;
CREATE TABLE `ims_amouse_weicard_member` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) DEFAULT NULL,
  `realname` varchar(50) DEFAULT NULL,
  `mobile` varchar(11) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `job` varchar(50) DEFAULT NULL,
  `qq` varchar(50) DEFAULT NULL,
  `industry` varchar(50) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `weixin` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `headimg` varchar(255) DEFAULT NULL,
  `openid` varchar(255) DEFAULT NULL,
  `myattention` varchar(255) DEFAULT NULL,
  `myfocus` varchar(255) DEFAULT NULL,
  `createtime` int(11) DEFAULT NULL,
  `companyAddress` varchar(255) DEFAULT NULL,
  `lat` decimal(18,10) DEFAULT '0.0000000000',
  `lng` decimal(18,10) DEFAULT '0.0000000000',
  `status` tinyint(1) DEFAULT NULL COMMENT '0表示已审核，1表示未审核，2表示禁用',
  `qianming` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_amouse_weicard_music`
-- ----------------------------
DROP TABLE IF EXISTS `ims_amouse_weicard_music`;
CREATE TABLE `ims_amouse_weicard_music` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) DEFAULT NULL,
  `musicName` varchar(255) DEFAULT NULL,
  `musicSinger` varchar(255) DEFAULT NULL,
  `musicImg` varchar(255) DEFAULT NULL,
  `musicUrl` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_amouse_weicard_mycollect`
-- ----------------------------
DROP TABLE IF EXISTS `ims_amouse_weicard_mycollect`;
CREATE TABLE `ims_amouse_weicard_mycollect` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `mid` int(10) NOT NULL,
  `cid` int(10) NOT NULL,
  `from_user` varchar(255) NOT NULL,
  `collect_mid` int(10) NOT NULL,
  `collect_cid` int(10) NOT NULL,
  `collect_from_user` varchar(255) NOT NULL,
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_amouse_weicard_photo`
-- ----------------------------
DROP TABLE IF EXISTS `ims_amouse_weicard_photo`;
CREATE TABLE `ims_amouse_weicard_photo` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `mid` int(10) NOT NULL COMMENT '会员表id',
  `cid` int(10) NOT NULL COMMENT '名片表id',
  `from_user` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL COMMENT '栏目名称',
  `icon` varchar(255) DEFAULT NULL COMMENT '栏目图标',
  `thumb` text COMMENT '图片',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_amouse_weicard_presence`
-- ----------------------------
DROP TABLE IF EXISTS `ims_amouse_weicard_presence`;
CREATE TABLE `ims_amouse_weicard_presence` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `mid` int(10) NOT NULL,
  `cid` int(10) NOT NULL,
  `from_user` varchar(255) DEFAULT NULL,
  `img` text,
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_amouse_weicard_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_amouse_weicard_reply`;
CREATE TABLE `ims_amouse_weicard_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(20) NOT NULL COMMENT '活动标题',
  `description` longtext NOT NULL COMMENT '活动介绍',
  `thumb` varchar(200) DEFAULT '',
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_amouse_weicard_sysset`
-- ----------------------------
DROP TABLE IF EXISTS `ims_amouse_weicard_sysset`;
CREATE TABLE `ims_amouse_weicard_sysset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `guanzhuUrl` varchar(255) DEFAULT '1' COMMENT '引导关注',
  `copyright` varchar(255) DEFAULT '' COMMENT '版权',
  `cnzz` varchar(800) DEFAULT '' COMMENT '第三方统计',
  `appid` varchar(255) DEFAULT '',
  `isoauth` int(2) unsigned NOT NULL DEFAULT '1',
  `appsecret` varchar(255) DEFAULT '',
  `appid_share` varchar(255) DEFAULT '',
  `appsecret_share` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_amouse_weicard_zan`
-- ----------------------------
DROP TABLE IF EXISTS `ims_amouse_weicard_zan`;
CREATE TABLE `ims_amouse_weicard_zan` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `mid` int(10) NOT NULL,
  `cid` int(10) NOT NULL,
  `from_user` varchar(255) NOT NULL,
  `zan_mid` int(10) NOT NULL,
  `zan_cid` int(10) NOT NULL,
  `zan_from_user` varchar(255) NOT NULL,
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_apidaquan`
-- ----------------------------
DROP TABLE IF EXISTS `ims_apidaquan`;
CREATE TABLE `ims_apidaquan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `status` int(3) NOT NULL,
  `city` varchar(255) NOT NULL,
  `company` varchar(20) NOT NULL,
  `create_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  `openid` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_apitype`
-- ----------------------------
DROP TABLE IF EXISTS `ims_apitype`;
CREATE TABLE `ims_apitype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `express_id` varchar(20) NOT NULL,
  `status` int(3) NOT NULL,
  `company` varchar(20) NOT NULL,
  `create_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  `openid` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_article_case`
-- ----------------------------
DROP TABLE IF EXISTS `ims_article_case`;
CREATE TABLE `ims_article_case` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cateid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `source` varchar(255) NOT NULL,
  `author` varchar(50) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `is_display` tinyint(3) unsigned NOT NULL,
  `is_show_home` tinyint(3) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `click` int(10) unsigned NOT NULL,
  `weixinh` varchar(50) NOT NULL,
  `weixintag` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `cateid` (`cateid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_article_catecase`
-- ----------------------------
DROP TABLE IF EXISTS `ims_article_catecase`;
CREATE TABLE `ims_article_catecase` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `type` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_article_category`
-- ----------------------------
DROP TABLE IF EXISTS `ims_article_category`;
CREATE TABLE `ims_article_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `type` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_article_news`
-- ----------------------------
DROP TABLE IF EXISTS `ims_article_news`;
CREATE TABLE `ims_article_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cateid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `source` varchar(255) NOT NULL,
  `author` varchar(50) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `is_display` tinyint(3) unsigned NOT NULL,
  `is_show_home` tinyint(3) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `click` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `cateid` (`cateid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_article_notice`
-- ----------------------------
DROP TABLE IF EXISTS `ims_article_notice`;
CREATE TABLE `ims_article_notice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cateid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `is_display` tinyint(3) unsigned NOT NULL,
  `is_show_home` tinyint(3) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `click` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `cateid` (`cateid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_article_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_article_reply`;
CREATE TABLE `ims_article_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `articleid` int(11) NOT NULL,
  `isfill` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_article_unread_notice`
-- ----------------------------
DROP TABLE IF EXISTS `ims_article_unread_notice`;
CREATE TABLE `ims_article_unread_notice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `notice_id` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `is_new` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `notice_id` (`notice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_auction_adv`
-- ----------------------------
DROP TABLE IF EXISTS `ims_auction_adv`;
CREATE TABLE `ims_auction_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_auction_goodslist`
-- ----------------------------
DROP TABLE IF EXISTS `ims_auction_goodslist`;
CREATE TABLE `ims_auction_goodslist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号',
  `title` varchar(100) DEFAULT NULL COMMENT '商品标题',
  `sh_price` int(10) DEFAULT '0' COMMENT '起拍金额',
  `add_price` int(10) DEFAULT '0' COMMENT '默认加价金额',
  `st_price` int(10) DEFAULT '0' COMMENT '成交金额',
  `bond` int(10) DEFAULT '0' COMMENT '保证金',
  `picarr` text COMMENT '商品图片',
  `content` mediumtext COMMENT '商品详情',
  `start_time` int(10) unsigned DEFAULT NULL COMMENT '开始时间',
  `end_time` int(10) unsigned DEFAULT NULL COMMENT '结束时间',
  `createtime` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `pos` tinyint(4) unsigned DEFAULT '0' COMMENT '出价次数',
  `status` int(11) NOT NULL COMMENT '1:已付余款',
  `g_status` int(11) NOT NULL COMMENT '2:上架；1：下架',
  `q_uid` varchar(10) DEFAULT NULL COMMENT '成交人昵称',
  `q_user` varchar(50) DEFAULT NULL COMMENT '成交人from_user',
  `send_state` int(4) unsigned NOT NULL COMMENT '1为已发货',
  `send` int(4) unsigned NOT NULL COMMENT '是否需要快递1为需要',
  `express` varchar(20) DEFAULT NULL COMMENT '快递公司',
  `expressn` char(20) DEFAULT NULL COMMENT '快递单',
  `send_time` char(20) DEFAULT NULL COMMENT '发货时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `send_state` (`send_state`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_auction_member`
-- ----------------------------
DROP TABLE IF EXISTS `ims_auction_member`;
CREATE TABLE `ims_auction_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号id',
  `balance` int(10) unsigned NOT NULL COMMENT '会员余额',
  `from_user` varchar(50) NOT NULL COMMENT '微信会员openID',
  `realname` varchar(10) NOT NULL COMMENT '真实姓名',
  `nickname` varchar(20) NOT NULL COMMENT '昵称',
  `avatar` varchar(255) NOT NULL COMMENT '头像',
  `mobile` varchar(11) NOT NULL COMMENT '手机号码',
  `address` varchar(255) NOT NULL COMMENT '邮寄地址',
  `bankcard` varchar(20) NOT NULL,
  `bankname` varchar(10) NOT NULL,
  `alipay` varchar(30) NOT NULL,
  `aliname` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_auction_recharge`
-- ----------------------------
DROP TABLE IF EXISTS `ims_auction_recharge`;
CREATE TABLE `ims_auction_recharge` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号',
  `from_user` varchar(50) NOT NULL COMMENT '微信会员openID',
  `nickname` varchar(20) NOT NULL COMMENT '用户昵称',
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `ordersn` varchar(20) NOT NULL COMMENT '订单编号',
  `status` smallint(4) NOT NULL DEFAULT '0' COMMENT '0未支付,1为已付款',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额支付,2为支付宝,3为微信支付,4为定价返还',
  `transid` varchar(30) NOT NULL COMMENT '微信订单号',
  `price` int(10) unsigned NOT NULL COMMENT '充值金额',
  `createtime` int(10) unsigned NOT NULL COMMENT '充值时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `from_user` (`from_user`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_auction_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_auction_record`;
CREATE TABLE `ims_auction_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号',
  `from_user` varchar(50) NOT NULL COMMENT '微信会员openID',
  `nickname` varchar(20) NOT NULL COMMENT '用户昵称',
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `sid` int(10) unsigned NOT NULL COMMENT '商品编号',
  `ordersn` varchar(20) NOT NULL COMMENT '订单编号',
  `price` int(10) unsigned NOT NULL COMMENT '交易价格',
  `bond` int(10) unsigned NOT NULL COMMENT '保证金',
  `createtime` int(10) unsigned NOT NULL COMMENT '购买时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_auction_withdrawals`
-- ----------------------------
DROP TABLE IF EXISTS `ims_auction_withdrawals`;
CREATE TABLE `ims_auction_withdrawals` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号',
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `ordersn` varchar(20) NOT NULL COMMENT '订单编号',
  `status` smallint(4) NOT NULL COMMENT '0为提现中,1为提现成功，2提现失败',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为银行卡,2为支付宝',
  `price` int(10) unsigned NOT NULL COMMENT '提现金额',
  `createtime` int(10) unsigned NOT NULL COMMENT '申请时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_basic_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_basic_reply`;
CREATE TABLE `ims_basic_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_bbb_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_bbb_reply`;
CREATE TABLE `ims_bbb_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `uniacid` int(10) unsigned NOT NULL,
  `picture` varchar(100) NOT NULL COMMENT '活动图片',
  `description` varchar(500) NOT NULL COMMENT '活动描述',
  `rule` text NOT NULL COMMENT '活动描述',
  `periodlottery` smallint(10) unsigned NOT NULL DEFAULT '1' COMMENT '0为无周期',
  `maxlottery` tinyint(3) unsigned NOT NULL COMMENT '最大抽奖数',
  `headpic` varchar(100) NOT NULL COMMENT '默认提示信息',
  `headurl` varchar(255) NOT NULL DEFAULT '',
  `panzi` varchar(100) NOT NULL DEFAULT '',
  `guzhuurl` varchar(255) NOT NULL DEFAULT '',
  `prace_times` int(10) NOT NULL DEFAULT '100',
  `title` varchar(100) NOT NULL DEFAULT '',
  `start_time` int(10) NOT NULL DEFAULT '0',
  `end_time` int(10) NOT NULL DEFAULT '1600000000',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_bbb_share`
-- ----------------------------
DROP TABLE IF EXISTS `ims_bbb_share`;
CREATE TABLE `ims_bbb_share` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `share_uid` int(10) unsigned NOT NULL COMMENT '分享者ID',
  `createtime` char(8) NOT NULL DEFAULT '' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_bbb_user`
-- ----------------------------
DROP TABLE IF EXISTS `ims_bbb_user`;
CREATE TABLE `ims_bbb_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL DEFAULT '0',
  `from_user` varchar(50) NOT NULL COMMENT '用户唯一身份ID',
  `count` int(10) NOT NULL DEFAULT '0',
  `points` int(10) NOT NULL DEFAULT '0',
  `friendcount` int(10) NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL COMMENT '日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_bbb_winner`
-- ----------------------------
DROP TABLE IF EXISTS `ims_bbb_winner`;
CREATE TABLE `ims_bbb_winner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `point` int(10) unsigned NOT NULL COMMENT '点数',
  `from_user` varchar(50) NOT NULL COMMENT '用户唯一身份ID',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未领奖，1不需要领奖，2已领奖',
  `createtime` int(10) unsigned NOT NULL COMMENT '获奖日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_bigwheel_award`
-- ----------------------------
DROP TABLE IF EXISTS `ims_bigwheel_award`;
CREATE TABLE `ims_bigwheel_award` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `fansID` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '0' COMMENT '用户ID',
  `name` varchar(50) DEFAULT '' COMMENT '名称',
  `description` varchar(200) DEFAULT '' COMMENT '描述',
  `prizetype` varchar(10) DEFAULT '' COMMENT '类型',
  `award_sn` varchar(50) DEFAULT '' COMMENT 'SN',
  `createtime` int(10) DEFAULT '0',
  `consumetime` int(10) DEFAULT '0',
  `status` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_bigwheel_fans`
-- ----------------------------
DROP TABLE IF EXISTS `ims_bigwheel_fans`;
CREATE TABLE `ims_bigwheel_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `fansID` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '' COMMENT '用户ID',
  `tel` varchar(20) DEFAULT '' COMMENT '登记信息(手机等)',
  `todaynum` int(11) DEFAULT '0',
  `totalnum` int(11) DEFAULT '0',
  `awardnum` int(11) DEFAULT '0',
  `last_time` int(10) DEFAULT '0',
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_bigwheel_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_bigwheel_reply`;
CREATE TABLE `ims_bigwheel_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT '0',
  `weid` int(11) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `content` varchar(200) DEFAULT '',
  `start_picurl` varchar(200) DEFAULT '',
  `isshow` tinyint(1) DEFAULT '0',
  `ticket_information` varchar(200) DEFAULT '',
  `starttime` int(10) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  `repeat_lottery_reply` varchar(50) DEFAULT '',
  `end_theme` varchar(50) DEFAULT '',
  `end_instruction` varchar(200) DEFAULT '',
  `end_picurl` varchar(200) DEFAULT '',
  `c_type_one` varchar(20) DEFAULT '',
  `c_name_one` varchar(50) DEFAULT '',
  `c_num_one` int(11) DEFAULT '0',
  `c_draw_one` int(11) DEFAULT '0',
  `c_rate_one` double DEFAULT '0',
  `c_type_two` varchar(20) DEFAULT '',
  `c_name_two` varchar(50) DEFAULT '',
  `c_num_two` int(11) DEFAULT '0',
  `c_draw_two` int(11) DEFAULT '0',
  `c_rate_two` double DEFAULT '0',
  `c_type_three` varchar(20) DEFAULT '',
  `c_name_three` varchar(50) DEFAULT '',
  `c_num_three` int(11) DEFAULT '0',
  `c_draw_three` int(11) DEFAULT '0',
  `c_rate_three` double DEFAULT '0',
  `c_type_four` varchar(20) DEFAULT '',
  `c_name_four` varchar(50) DEFAULT '',
  `c_num_four` int(11) DEFAULT '0',
  `c_draw_four` int(11) DEFAULT '0',
  `c_rate_four` double DEFAULT '0',
  `c_type_five` varchar(20) DEFAULT '',
  `c_name_five` varchar(50) DEFAULT '',
  `c_num_five` int(11) DEFAULT '0',
  `c_draw_five` int(11) DEFAULT '0',
  `c_rate_five` double DEFAULT '0',
  `c_type_six` varchar(20) DEFAULT '',
  `c_name_six` varchar(50) DEFAULT '',
  `c_num_six` int(11) DEFAULT '0',
  `c_draw_six` int(10) DEFAULT '0',
  `c_rate_six` double DEFAULT '0',
  `total_num` int(11) DEFAULT '0' COMMENT '总获奖人数(自动加)',
  `probability` double DEFAULT '0',
  `award_times` int(11) DEFAULT '0',
  `number_times` int(11) DEFAULT '0',
  `most_num_times` int(11) DEFAULT '0',
  `sn_code` tinyint(4) DEFAULT '0',
  `sn_rename` varchar(20) DEFAULT '',
  `tel_rename` varchar(20) DEFAULT '',
  `copyright` varchar(20) DEFAULT '',
  `show_num` tinyint(2) DEFAULT '0',
  `viewnum` int(11) DEFAULT '0',
  `fansnum` int(11) DEFAULT '0',
  `createtime` int(10) DEFAULT '0',
  `share_title` varchar(200) DEFAULT '',
  `share_desc` varchar(300) DEFAULT '',
  `share_url` varchar(100) DEFAULT '',
  `share_txt` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_bmfloor`
-- ----------------------------
DROP TABLE IF EXISTS `ims_bmfloor`;
CREATE TABLE `ims_bmfloor` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `awardprompt` text NOT NULL,
  `currentprompt` text NOT NULL,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `url` varchar(255) NOT NULL DEFAULT '',
  `total` int(10) unsigned NOT NULL DEFAULT '0',
  `memo` text NOT NULL,
  `picture` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(20) NOT NULL DEFAULT '',
  `url1` varchar(255) NOT NULL DEFAULT '',
  `starttime` datetime NOT NULL,
  `endtime` datetime NOT NULL,
  `memo1` text NOT NULL,
  `memo2` text NOT NULL,
  `share_keyword` varchar(100) NOT NULL DEFAULT '',
  `share_logo` varchar(255) NOT NULL DEFAULT '',
  `share_memo` text NOT NULL,
  `share_statement` text NOT NULL,
  `share_url` varchar(255) NOT NULL DEFAULT '',
  `share_point` int(10) unsigned NOT NULL DEFAULT '0',
  `adv_url` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_bmfloor_award`
-- ----------------------------
DROP TABLE IF EXISTS `ims_bmfloor_award`;
CREATE TABLE `ims_bmfloor_award` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `floor` varchar(100) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `from_user` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_bmfloor_member`
-- ----------------------------
DROP TABLE IF EXISTS `ims_bmfloor_member`;
CREATE TABLE `ims_bmfloor_member` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_bmfloor_winner`
-- ----------------------------
DROP TABLE IF EXISTS `ims_bmfloor_winner`;
CREATE TABLE `ims_bmfloor_winner` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_brand`
-- ----------------------------
DROP TABLE IF EXISTS `ims_brand`;
CREATE TABLE `ims_brand` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) unsigned DEFAULT NULL,
  `bname` varchar(50) NOT NULL,
  `intro` varchar(1000) NOT NULL,
  `intro2` varchar(1000) NOT NULL,
  `video_name` varchar(100) DEFAULT NULL,
  `video_url` varchar(100) DEFAULT NULL,
  `createtime` int(11) unsigned DEFAULT NULL,
  `pptname` varchar(100) DEFAULT NULL,
  `ppt1` varchar(100) DEFAULT NULL,
  `ppt2` varchar(100) DEFAULT NULL,
  `ppt3` varchar(100) DEFAULT NULL,
  `pic` varchar(100) NOT NULL,
  `visitsCount` int(11) DEFAULT '0',
  `btnName` varchar(20) DEFAULT NULL,
  `btnUrl` varchar(100) DEFAULT NULL,
  `btnName2` varchar(20) DEFAULT NULL,
  `btnUrl2` varchar(100) DEFAULT NULL,
  `btnName3` varchar(20) DEFAULT NULL,
  `btnUrl3` varchar(100) DEFAULT NULL,
  `showMsg` int(1) DEFAULT '0',
  `tel` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_brand_image`
-- ----------------------------
DROP TABLE IF EXISTS `ims_brand_image`;
CREATE TABLE `ims_brand_image` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bid` int(11) unsigned DEFAULT NULL,
  `title` varchar(50) NOT NULL,
  `url` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_brand_message`
-- ----------------------------
DROP TABLE IF EXISTS `ims_brand_message`;
CREATE TABLE `ims_brand_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `createtime` int(11) unsigned DEFAULT NULL,
  `bid` int(11) unsigned DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `tel` varchar(100) NOT NULL,
  `content` varchar(1000) NOT NULL,
  `address` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_brand_note`
-- ----------------------------
DROP TABLE IF EXISTS `ims_brand_note`;
CREATE TABLE `ims_brand_note` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bid` int(11) unsigned DEFAULT NULL,
  `title` varchar(50) NOT NULL,
  `note` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_brand_product`
-- ----------------------------
DROP TABLE IF EXISTS `ims_brand_product`;
CREATE TABLE `ims_brand_product` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bid` int(11) unsigned DEFAULT NULL,
  `pname` varchar(200) NOT NULL,
  `image` varchar(200) NOT NULL,
  `summary` varchar(200) NOT NULL,
  `intro` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_brand_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_brand_reply`;
CREATE TABLE `ims_brand_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `bid` int(10) unsigned NOT NULL,
  `new_pic` varchar(200) NOT NULL,
  `news_content` varchar(500) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_broke_acmanager`
-- ----------------------------
DROP TABLE IF EXISTS `ims_broke_acmanager`;
CREATE TABLE `ims_broke_acmanager` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `code` varchar(20) NOT NULL,
  `listorder` int(5) NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL,
  `content` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `loupan` int(10) NOT NULL,
  `loupanid` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_broke_assistant`
-- ----------------------------
DROP TABLE IF EXISTS `ims_broke_assistant`;
CREATE TABLE `ims_broke_assistant` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `realname` varchar(50) NOT NULL,
  `mobile` varchar(11) NOT NULL COMMENT '手机号码',
  `company` varchar(50) DEFAULT NULL,
  `code` varchar(20) NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `flag` tinyint(1) DEFAULT '0' COMMENT '0为销售员，1为经理',
  `content` text,
  `createtime` int(10) NOT NULL,
  `loupan` int(10) NOT NULL DEFAULT '0',
  `pwd` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_broke_commission`
-- ----------------------------
DROP TABLE IF EXISTS `ims_broke_commission`;
CREATE TABLE `ims_broke_commission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `mid` int(10) unsigned NOT NULL COMMENT '经纪人ID',
  `cid` int(10) unsigned DEFAULT NULL COMMENT '客户ID',
  `commission` int(10) unsigned NOT NULL COMMENT '佣金',
  `content` text,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL,
  `flag` tinyint(1) DEFAULT '0',
  `opid` int(10) unsigned DEFAULT '0' COMMENT '操作员ID经理或销售或管理员',
  `opname` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_broke_counselor`
-- ----------------------------
DROP TABLE IF EXISTS `ims_broke_counselor`;
CREATE TABLE `ims_broke_counselor` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `code` varchar(20) NOT NULL,
  `loupan` int(10) unsigned NOT NULL DEFAULT '0',
  `listorder` int(5) NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL,
  `content` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_broke_customer`
-- ----------------------------
DROP TABLE IF EXISTS `ims_broke_customer`;
CREATE TABLE `ims_broke_customer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `mobile` varchar(11) NOT NULL COMMENT '手机号码',
  `realname` varchar(50) NOT NULL,
  `loupan` int(10) unsigned NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL,
  `updatetime` int(10) DEFAULT NULL,
  `flag` tinyint(1) DEFAULT '0',
  `cid` int(10) unsigned DEFAULT '0' COMMENT '该客户从属于某销售员',
  `allottime` int(10) DEFAULT NULL COMMENT '分配时间',
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_broke_identity`
-- ----------------------------
DROP TABLE IF EXISTS `ims_broke_identity`;
CREATE TABLE `ims_broke_identity` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `identity_name` varchar(20) NOT NULL,
  `iscompany` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否需要填写公司名称，1要，默认不要',
  `listorder` int(5) NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_broke_item`
-- ----------------------------
DROP TABLE IF EXISTS `ims_broke_item`;
CREATE TABLE `ims_broke_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `lpid` int(10) unsigned NOT NULL,
  `photoid` int(10) unsigned NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `item` varchar(1000) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL DEFAULT '',
  `x` int(3) NOT NULL DEFAULT '0',
  `y` int(3) NOT NULL DEFAULT '0',
  `animation` varchar(20) NOT NULL DEFAULT '',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_photoid` (`photoid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_broke_log`
-- ----------------------------
DROP TABLE IF EXISTS `ims_broke_log`;
CREATE TABLE `ims_broke_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `share_from_user` varchar(50) DEFAULT NULL,
  `loupan` int(10) unsigned NOT NULL,
  `browser` varchar(200) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `createtime` int(10) NOT NULL COMMENT '时间戳格式',
  `createtime1` varchar(20) NOT NULL COMMENT '日期格式',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_broke_logloupan`
-- ----------------------------
DROP TABLE IF EXISTS `ims_broke_logloupan`;
CREATE TABLE `ims_broke_logloupan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `lid` int(10) unsigned NOT NULL,
  `createtime` int(10) NOT NULL COMMENT '时间戳格式',
  `createtime1` varchar(20) NOT NULL COMMENT '日期格式',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_broke_loupan`
-- ----------------------------
DROP TABLE IF EXISTS `ims_broke_loupan`;
CREATE TABLE `ims_broke_loupan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `icon` varchar(100) NOT NULL DEFAULT '',
  `share` varchar(100) NOT NULL DEFAULT '',
  `open` varchar(100) NOT NULL DEFAULT '',
  `ostyle` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `music` varchar(100) NOT NULL DEFAULT '',
  `mauto` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `mloop` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `thumb` varchar(100) NOT NULL DEFAULT '',
  `content` varchar(1000) NOT NULL DEFAULT '',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `isloop` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isview` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dist` varchar(20) DEFAULT '',
  `city` varchar(20) DEFAULT '',
  `province` varchar(20) DEFAULT '',
  `address` varchar(255) DEFAULT '',
  `lng` varchar(12) DEFAULT '116.403694',
  `lat` varchar(12) DEFAULT '39.916042',
  `addr` varchar(255) DEFAULT NULL,
  `commission` varchar(20) DEFAULT NULL,
  `tel` varchar(50) DEFAULT NULL,
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `jw_addr` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_broke_member`
-- ----------------------------
DROP TABLE IF EXISTS `ims_broke_member`;
CREATE TABLE `ims_broke_member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `tjmid` int(10) NOT NULL DEFAULT '0',
  `from_user` varchar(50) NOT NULL,
  `realname` varchar(50) NOT NULL,
  `mobile` varchar(11) NOT NULL COMMENT '手机号码',
  `bankcard` varchar(20) DEFAULT NULL,
  `banktype` varchar(20) DEFAULT NULL,
  `identity` int(10) unsigned NOT NULL,
  `company` varchar(50) DEFAULT NULL,
  `createtime` int(10) NOT NULL,
  `status` tinyint(1) DEFAULT '0',
  `commission` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_broke_photo`
-- ----------------------------
DROP TABLE IF EXISTS `ims_broke_photo`;
CREATE TABLE `ims_broke_photo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `lpid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL DEFAULT '',
  `attachment` varchar(100) NOT NULL DEFAULT '',
  `ispreview` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_lpid` (`lpid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_broke_protect`
-- ----------------------------
DROP TABLE IF EXISTS `ims_broke_protect`;
CREATE TABLE `ims_broke_protect` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cname` varchar(50) NOT NULL DEFAULT '',
  `mobile` varchar(50) NOT NULL,
  `createtime` int(10) NOT NULL DEFAULT '0',
  `weid` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_broke_rule`
-- ----------------------------
DROP TABLE IF EXISTS `ims_broke_rule`;
CREATE TABLE `ims_broke_rule` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT '',
  `rule` text,
  `terms` text,
  `createtime` int(10) NOT NULL,
  `gzurl` varchar(255) NOT NULL,
  `teamfy` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_business`
-- ----------------------------
DROP TABLE IF EXISTS `ims_business`;
CREATE TABLE `ims_business` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL,
  `content` varchar(1000) NOT NULL DEFAULT '',
  `phone` varchar(15) NOT NULL DEFAULT '',
  `qq` varchar(15) NOT NULL DEFAULT '',
  `province` varchar(50) NOT NULL DEFAULT '',
  `city` varchar(50) NOT NULL DEFAULT '',
  `dist` varchar(50) NOT NULL DEFAULT '',
  `address` varchar(500) NOT NULL DEFAULT '',
  `lng` varchar(10) NOT NULL DEFAULT '',
  `lat` varchar(10) NOT NULL DEFAULT '',
  `industry1` varchar(10) NOT NULL DEFAULT '',
  `industry2` varchar(10) NOT NULL DEFAULT '',
  `createtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_lat_lng` (`lng`,`lat`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_core_attachment`
-- ----------------------------
DROP TABLE IF EXISTS `ims_core_attachment`;
CREATE TABLE `ims_core_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `filename` varchar(255) NOT NULL,
  `attachment` varchar(255) NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_core_cache`
-- ----------------------------
DROP TABLE IF EXISTS `ims_core_cache`;
CREATE TABLE `ims_core_cache` (
  `key` varchar(50) NOT NULL,
  `value` mediumtext NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_core_cron`
-- ----------------------------
DROP TABLE IF EXISTS `ims_core_cron`;
CREATE TABLE `ims_core_cron` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cloudid` int(10) unsigned NOT NULL,
  `module` varchar(50) NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `filename` varchar(50) NOT NULL,
  `lastruntime` int(10) unsigned NOT NULL,
  `nextruntime` int(10) unsigned NOT NULL,
  `weekday` tinyint(3) NOT NULL,
  `day` tinyint(3) NOT NULL,
  `hour` tinyint(3) NOT NULL,
  `minute` varchar(255) NOT NULL,
  `extra` varchar(5000) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `createtime` (`createtime`),
  KEY `nextruntime` (`nextruntime`),
  KEY `uniacid` (`uniacid`),
  KEY `cloudid` (`cloudid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_core_cron_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_core_cron_record`;
CREATE TABLE `ims_core_cron_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `module` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `tid` int(10) unsigned NOT NULL,
  `note` varchar(500) NOT NULL,
  `tag` varchar(5000) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `tid` (`tid`),
  KEY `module` (`module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_core_menu`
-- ----------------------------
DROP TABLE IF EXISTS `ims_core_menu`;
CREATE TABLE `ims_core_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` tinyint(3) unsigned NOT NULL,
  `title` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `url` varchar(60) NOT NULL,
  `append_title` varchar(30) NOT NULL,
  `append_url` varchar(60) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `type` varchar(15) NOT NULL,
  `is_display` tinyint(3) unsigned NOT NULL,
  `is_system` tinyint(3) unsigned NOT NULL,
  `permission_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_core_paylog`
-- ----------------------------
DROP TABLE IF EXISTS `ims_core_paylog`;
CREATE TABLE `ims_core_paylog` (
  `plid` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL DEFAULT '',
  `uniacid` int(11) NOT NULL,
  `openid` varchar(40) NOT NULL DEFAULT '',
  `tid` varchar(64) NOT NULL,
  `fee` decimal(10,2) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `module` varchar(50) NOT NULL DEFAULT '',
  `tag` varchar(2000) NOT NULL DEFAULT '',
  `acid` int(10) unsigned NOT NULL,
  `is_usecard` tinyint(3) unsigned NOT NULL,
  `card_type` tinyint(3) unsigned NOT NULL,
  `card_id` varchar(50) NOT NULL,
  `card_fee` decimal(10,2) unsigned NOT NULL,
  `encrypt_code` varchar(100) NOT NULL,
  `uniontid` varchar(50) NOT NULL,
  PRIMARY KEY (`plid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_tid` (`tid`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `uniontid` (`uniontid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_core_performance`
-- ----------------------------
DROP TABLE IF EXISTS `ims_core_performance`;
CREATE TABLE `ims_core_performance` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL,
  `runtime` varchar(10) NOT NULL,
  `runurl` varchar(512) NOT NULL,
  `runsql` varchar(512) NOT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_core_queue`
-- ----------------------------
DROP TABLE IF EXISTS `ims_core_queue`;
CREATE TABLE `ims_core_queue` (
  `qid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `message` varchar(2000) NOT NULL DEFAULT '',
  `params` varchar(1000) NOT NULL DEFAULT '',
  `keyword` varchar(1000) NOT NULL DEFAULT '',
  `response` varchar(2000) NOT NULL DEFAULT '',
  `module` varchar(50) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`qid`),
  KEY `uniacid` (`uniacid`,`acid`),
  KEY `module` (`module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_core_resource`
-- ----------------------------
DROP TABLE IF EXISTS `ims_core_resource`;
CREATE TABLE `ims_core_resource` (
  `mid` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `media_id` varchar(100) NOT NULL,
  `trunk` int(10) unsigned NOT NULL DEFAULT '0',
  `type` varchar(10) NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`mid`),
  KEY `acid` (`uniacid`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_core_sessions`
-- ----------------------------
DROP TABLE IF EXISTS `ims_core_sessions`;
CREATE TABLE `ims_core_sessions` (
  `sid` char(32) NOT NULL DEFAULT '',
  `uniacid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `data` varchar(5000) NOT NULL,
  `expiretime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_core_settings`
-- ----------------------------
DROP TABLE IF EXISTS `ims_core_settings`;
CREATE TABLE `ims_core_settings` (
  `key` varchar(200) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_core_wechats_attachment`
-- ----------------------------
DROP TABLE IF EXISTS `ims_core_wechats_attachment`;
CREATE TABLE `ims_core_wechats_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `filename` varchar(255) NOT NULL,
  `attachment` varchar(255) NOT NULL,
  `media_id` varchar(255) NOT NULL,
  `type` varchar(15) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `width` int(10) unsigned NOT NULL,
  `height` int(10) unsigned NOT NULL,
  `model` varchar(25) NOT NULL,
  `tag` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `media_id` (`media_id`),
  KEY `acid` (`acid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_coupon`
-- ----------------------------
DROP TABLE IF EXISTS `ims_coupon`;
CREATE TABLE `ims_coupon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `card_id` varchar(50) NOT NULL,
  `type` varchar(15) NOT NULL,
  `logo_url` varchar(150) NOT NULL,
  `code_type` tinyint(3) unsigned NOT NULL,
  `brand_name` varchar(15) NOT NULL,
  `title` varchar(15) NOT NULL,
  `sub_title` varchar(20) NOT NULL,
  `color` varchar(15) NOT NULL,
  `notice` varchar(15) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `date_info` varchar(200) NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  `location_id_list` varchar(1000) NOT NULL,
  `use_custom_code` tinyint(3) NOT NULL,
  `bind_openid` tinyint(3) unsigned NOT NULL,
  `can_share` tinyint(3) unsigned NOT NULL,
  `can_give_friend` tinyint(3) unsigned NOT NULL,
  `get_limit` tinyint(3) unsigned NOT NULL,
  `service_phone` varchar(20) NOT NULL,
  `extra` varchar(1000) NOT NULL,
  `source` varchar(20) NOT NULL,
  `url_name_type` varchar(20) NOT NULL,
  `custom_url` varchar(100) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `is_display` tinyint(3) unsigned NOT NULL,
  `promotion_url_name` varchar(10) NOT NULL,
  `promotion_url` varchar(100) NOT NULL,
  `promotion_url_sub_title` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`acid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_coupon_location`
-- ----------------------------
DROP TABLE IF EXISTS `ims_coupon_location`;
CREATE TABLE `ims_coupon_location` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `location_id` int(10) unsigned NOT NULL,
  `business_name` varchar(50) NOT NULL,
  `branch_name` varchar(50) NOT NULL,
  `category` varchar(255) NOT NULL,
  `province` varchar(15) NOT NULL,
  `city` varchar(15) NOT NULL,
  `district` varchar(15) NOT NULL,
  `address` varchar(50) NOT NULL,
  `longitude` varchar(15) NOT NULL,
  `latitude` varchar(15) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `photo_list` varchar(10000) NOT NULL,
  `avg_price` int(10) unsigned NOT NULL,
  `open_time` varchar(50) NOT NULL,
  `recommend` varchar(255) NOT NULL,
  `special` varchar(255) NOT NULL,
  `introduction` varchar(255) NOT NULL,
  `offset_type` tinyint(3) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `message` varchar(255) NOT NULL,
  `sid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`acid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_coupon_modules`
-- ----------------------------
DROP TABLE IF EXISTS `ims_coupon_modules`;
CREATE TABLE `ims_coupon_modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `card_id` varchar(50) NOT NULL,
  `module` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`),
  KEY `card_id` (`card_id`),
  KEY `uniacid` (`uniacid`,`acid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_coupon_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_coupon_record`;
CREATE TABLE `ims_coupon_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `card_id` varchar(50) NOT NULL,
  `outer_id` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `friend_openid` varchar(50) NOT NULL,
  `givebyfriend` tinyint(3) unsigned NOT NULL,
  `code` varchar(50) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `usetime` int(10) unsigned NOT NULL,
  `status` tinyint(3) NOT NULL,
  `clerk_name` varchar(15) NOT NULL,
  `clerk_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`acid`),
  KEY `outer_id` (`outer_id`),
  KEY `card_id` (`card_id`),
  KEY `hash` (`hash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_coupon_setting`
-- ----------------------------
DROP TABLE IF EXISTS `ims_coupon_setting`;
CREATE TABLE `ims_coupon_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) NOT NULL,
  `logourl` varchar(150) NOT NULL,
  `whitelist` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`acid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_cover_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_cover_reply`;
CREATE TABLE `ims_cover_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `multiid` int(10) unsigned NOT NULL DEFAULT '0',
  `rid` int(10) unsigned NOT NULL,
  `module` varchar(30) NOT NULL DEFAULT '',
  `do` varchar(30) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_custom_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_custom_reply`;
CREATE TABLE `ims_custom_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `start1` int(10) NOT NULL DEFAULT '-1',
  `end1` int(10) NOT NULL DEFAULT '-1',
  `start2` int(10) NOT NULL DEFAULT '-1',
  `end2` int(10) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_cut_zongzi_billboard`
-- ----------------------------
DROP TABLE IF EXISTS `ims_cut_zongzi_billboard`;
CREATE TABLE `ims_cut_zongzi_billboard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `openid` varchar(255) DEFAULT NULL,
  `score` varchar(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_cut_zongzi_settings`
-- ----------------------------
DROP TABLE IF EXISTS `ims_cut_zongzi_settings`;
CREATE TABLE `ims_cut_zongzi_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `setting_key` varchar(255) NOT NULL,
  `setting_value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_czt_subscribe_redpack_records`
-- ----------------------------
DROP TABLE IF EXISTS `ims_czt_subscribe_redpack_records`;
CREATE TABLE `ims_czt_subscribe_redpack_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `openid` varchar(40) NOT NULL DEFAULT '',
  `fee` varchar(20) NOT NULL DEFAULT '',
  `log` varchar(500) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `create_t` int(10) unsigned NOT NULL,
  `success_t` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`),
  KEY `log` (`log`(333)),
  KEY `uniacid` (`uniacid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_czt_zhuanfa_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_czt_zhuanfa_reply`;
CREATE TABLE `ims_czt_zhuanfa_reply` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `url` varchar(500) NOT NULL,
  `token` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_dqq_award`
-- ----------------------------
DROP TABLE IF EXISTS `ims_dqq_award`;
CREATE TABLE `ims_dqq_award` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `title` varchar(50) NOT NULL COMMENT '奖品名称',
  `total` int(11) NOT NULL COMMENT '数量',
  `probalilty` varchar(5) NOT NULL COMMENT '概率单位%',
  `description` varchar(100) NOT NULL DEFAULT '' COMMENT '描述',
  `inkind` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否是实物',
  `get_jf` int(11) NOT NULL COMMENT '获取的积分',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_dqq_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_dqq_reply`;
CREATE TABLE `ims_dqq_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `picture` varchar(100) NOT NULL COMMENT '活动图片',
  `description` varchar(100) NOT NULL COMMENT '活动描述',
  `rule` varchar(1000) NOT NULL COMMENT '规则',
  `periodlottery` smallint(10) unsigned NOT NULL DEFAULT '1' COMMENT '0为无周期',
  `maxlottery` tinyint(3) unsigned NOT NULL COMMENT '最大抽奖数',
  `default_tips` varchar(100) NOT NULL COMMENT '默认提示信息',
  `hitcredit` int(11) NOT NULL COMMENT '中奖奖励积分',
  `misscredit` int(11) NOT NULL COMMENT '未中奖奖励积分',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_dqq_winner`
-- ----------------------------
DROP TABLE IF EXISTS `ims_dqq_winner`;
CREATE TABLE `ims_dqq_winner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `aid` int(10) unsigned NOT NULL COMMENT '奖品ID',
  `award` varchar(100) NOT NULL DEFAULT '' COMMENT '奖品名称',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '中奖信息描述',
  `from_user` varchar(50) NOT NULL COMMENT '用户唯一身份ID',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未领奖，1不需要领奖，2已领奖',
  `createtime` int(10) unsigned NOT NULL COMMENT '获奖日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_dream_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_dream_reply`;
CREATE TABLE `ims_dream_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `weid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `picurl` varchar(200) DEFAULT '',
  `starttime` int(10) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  `share_title` varchar(50) DEFAULT '',
  `share_content` varchar(255) DEFAULT '',
  `isshow` tinyint(1) DEFAULT '0',
  `viewnum` int(11) DEFAULT '0',
  `dreamnum` int(11) DEFAULT '0',
  `logo` varchar(200) DEFAULT NULL,
  `gzurl` varchar(255) DEFAULT NULL,
  `slogans` varchar(28) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_dream_wish`
-- ----------------------------
DROP TABLE IF EXISTS `ims_dream_wish`;
CREATE TABLE `ims_dream_wish` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `fansID` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '0',
  `drea_mname` varchar(50) DEFAULT '' COMMENT '自己名字',
  `to_name` varchar(200) DEFAULT '' COMMENT '好友名字',
  `dream` varchar(50) DEFAULT '' COMMENT '梦想',
  `createtime` int(10) DEFAULT '0',
  `consumetime` int(10) DEFAULT '0',
  `status` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_egg_award`
-- ----------------------------
DROP TABLE IF EXISTS `ims_egg_award`;
CREATE TABLE `ims_egg_award` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `title` varchar(50) NOT NULL COMMENT '奖品名称',
  `total` int(11) NOT NULL COMMENT '数量',
  `probalilty` varchar(5) NOT NULL COMMENT '概率单位%',
  `description` varchar(100) NOT NULL DEFAULT '' COMMENT '描述',
  `inkind` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否是实物',
  `activation_code` text,
  `activation_url` varchar(200) NOT NULL COMMENT '激活地址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_egg_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_egg_reply`;
CREATE TABLE `ims_egg_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '活动标题',
  `picture` varchar(100) NOT NULL COMMENT '活动图片',
  `description` varchar(100) NOT NULL COMMENT '活动描述',
  `rule` text NOT NULL COMMENT '规则',
  `periodlottery` smallint(10) unsigned NOT NULL DEFAULT '1' COMMENT '0为无周期',
  `maxlottery` tinyint(3) unsigned NOT NULL COMMENT '最大抽奖数',
  `default_tips` varchar(100) NOT NULL COMMENT '默认提示信息',
  `hitcredit` int(11) NOT NULL COMMENT '中奖奖励积分',
  `misscredit` int(11) NOT NULL COMMENT '未中奖奖励积分',
  `starttime` int(10) unsigned NOT NULL COMMENT '开始时间',
  `endtime` int(10) unsigned NOT NULL COMMENT '结束时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_egg_winner`
-- ----------------------------
DROP TABLE IF EXISTS `ims_egg_winner`;
CREATE TABLE `ims_egg_winner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `aid` int(10) unsigned NOT NULL COMMENT '奖品ID',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `isaward` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `award` varchar(100) NOT NULL DEFAULT '' COMMENT '奖品名称',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '中奖信息描述',
  `credit` int(10) unsigned NOT NULL DEFAULT '0',
  `from_user` varchar(50) NOT NULL COMMENT '用户唯一身份ID',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未领奖，1不需要领奖，2已领奖',
  `createtime` int(10) unsigned NOT NULL COMMENT '获奖日期',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_enjoy_circle_comment`
-- ----------------------------
DROP TABLE IF EXISTS `ims_enjoy_circle_comment`;
CREATE TABLE `ims_enjoy_circle_comment` (
  `cid` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(50) DEFAULT NULL,
  `tid` int(255) DEFAULT NULL,
  `comment` varchar(10000) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `avatar` varchar(200) DEFAULT NULL,
  `cuid` int(200) DEFAULT '0',
  `hot` int(50) DEFAULT '0',
  `createtime` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_enjoy_circle_fans`
-- ----------------------------
DROP TABLE IF EXISTS `ims_enjoy_circle_fans`;
CREATE TABLE `ims_enjoy_circle_fans` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `openid` varchar(40) NOT NULL DEFAULT '',
  `proxy` varchar(40) NOT NULL DEFAULT '',
  `unionid` varchar(40) NOT NULL DEFAULT '',
  `nickname` varchar(20) NOT NULL DEFAULT '',
  `gender` varchar(2) DEFAULT '',
  `state` varchar(20) NOT NULL DEFAULT '',
  `city` varchar(20) NOT NULL DEFAULT '',
  `country` varchar(20) NOT NULL DEFAULT '',
  `avatar` varchar(500) NOT NULL DEFAULT '',
  `puid` int(20) DEFAULT NULL,
  `black` int(2) NOT NULL DEFAULT '0',
  `ip` varchar(50) DEFAULT NULL,
  `subscribe` int(2) DEFAULT NULL,
  `subscribe_time` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `uniacid` (`uniacid`),
  KEY `openid` (`openid`),
  KEY `proxy` (`proxy`),
  KEY `nickname` (`nickname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_enjoy_circle_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_enjoy_circle_reply`;
CREATE TABLE `ims_enjoy_circle_reply` (
  `id` int(50) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(20) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `sucai` varchar(200) DEFAULT NULL,
  `exurl` varchar(500) DEFAULT NULL,
  `expic` varchar(200) DEFAULT NULL,
  `extitle` varchar(200) DEFAULT NULL,
  `share_icon` varchar(200) DEFAULT NULL,
  `share_title` varchar(200) DEFAULT NULL,
  `share_content` varchar(200) DEFAULT NULL,
  `ewm` varchar(200) DEFAULT NULL,
  `bgpic` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_enjoy_circle_topic`
-- ----------------------------
DROP TABLE IF EXISTS `ims_enjoy_circle_topic`;
CREATE TABLE `ims_enjoy_circle_topic` (
  `tid` int(255) NOT NULL AUTO_INCREMENT,
  `uniacid` int(50) NOT NULL,
  `title` varchar(1000) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `avatar` varchar(200) DEFAULT NULL,
  `pic` varchar(200) DEFAULT NULL,
  `hot` int(100) DEFAULT NULL,
  `zan` varchar(500) DEFAULT NULL,
  `cuid` int(200) DEFAULT '0',
  `joinnum` int(200) DEFAULT NULL,
  `createtime` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_eso_runman_plus`
-- ----------------------------
DROP TABLE IF EXISTS `ims_eso_runman_plus`;
CREATE TABLE `ims_eso_runman_plus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT NULL,
  `val` varchar(255) DEFAULT NULL COMMENT '加分-被加分',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='奔跑兄弟 - 加温记录';

-- ----------------------------
--  Table structure for `ims_eso_runman_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_eso_runman_reply`;
CREATE TABLE `ims_eso_runman_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT '',
  `content` text,
  `background` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `description` text,
  `share_title` varchar(255) DEFAULT '',
  `share_desc` varchar(255) DEFAULT '',
  `share_url` varchar(255) DEFAULT '',
  `mp3` varchar(255) DEFAULT '',
  `join` int(10) unsigned DEFAULT '0',
  `view` int(10) unsigned DEFAULT '0',
  `share_txt` text,
  `regular` text,
  `setting` text,
  `starttime` bigint(18) unsigned DEFAULT '0',
  `endtime` bigint(18) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='奔跑兄弟 - 回复规则';

-- ----------------------------
--  Table structure for `ims_eso_runman_submit`
-- ----------------------------
DROP TABLE IF EXISTS `ims_eso_runman_submit`;
CREATE TABLE `ims_eso_runman_submit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(20) DEFAULT '',
  `rid` int(11) DEFAULT NULL,
  `openid` varchar(255) DEFAULT '',
  `title` varchar(255) DEFAULT '0',
  `did` int(10) unsigned DEFAULT '0',
  `indate` bigint(18) unsigned DEFAULT '0',
  `update` bigint(18) unsigned DEFAULT '0',
  `money` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '提现金额',
  `exchange` tinyint(3) unsigned DEFAULT '0' COMMENT '1为已处理',
  `setting` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='奔跑兄弟 - 领取记录';

-- ----------------------------
--  Table structure for `ims_eso_runman_users`
-- ----------------------------
DROP TABLE IF EXISTS `ims_eso_runman_users`;
CREATE TABLE `ims_eso_runman_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT NULL,
  `ruid` int(11) DEFAULT NULL COMMENT '来源会员ID',
  `rnum` int(10) unsigned DEFAULT '0' COMMENT '被访问次数',
  `title` varchar(255) DEFAULT '',
  `sex` varchar(10) DEFAULT '',
  `tag` varchar(255) DEFAULT '',
  `mobile` varchar(255) DEFAULT '',
  `img` varchar(255) DEFAULT '',
  `openid` varchar(255) DEFAULT '',
  `indate` bigint(18) unsigned DEFAULT '0' COMMENT '入住时间',
  `ladate` varchar(20) DEFAULT '',
  `defaultval` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '初始暖值',
  `ruidval` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '别人加的暖值',
  `val` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '当前暖值（暖值总）',
  `one` tinyint(3) unsigned DEFAULT '0' COMMENT '第一次进入',
  `setting` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='奔跑兄弟 - 会员';

-- ----------------------------
--  Table structure for `ims_eso_sale_address`
-- ----------------------------
DROP TABLE IF EXISTS `ims_eso_sale_address`;
CREATE TABLE `ims_eso_sale_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `realname` varchar(20) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `province` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `area` varchar(30) NOT NULL,
  `address` varchar(300) NOT NULL,
  `isdefault` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_eso_sale_adv`
-- ----------------------------
DROP TABLE IF EXISTS `ims_eso_sale_adv`;
CREATE TABLE `ims_eso_sale_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_eso_sale_cart`
-- ----------------------------
DROP TABLE IF EXISTS `ims_eso_sale_cart`;
CREATE TABLE `ims_eso_sale_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `goodsid` int(11) NOT NULL,
  `goodstype` tinyint(1) NOT NULL DEFAULT '1',
  `from_user` varchar(50) NOT NULL,
  `total` int(10) unsigned NOT NULL,
  `optionid` int(10) DEFAULT '0',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_eso_sale_category`
-- ----------------------------
DROP TABLE IF EXISTS `ims_eso_sale_category`;
CREATE TABLE `ims_eso_sale_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `commission` int(10) unsigned DEFAULT '0' COMMENT '推荐该类商品所能获得的佣金',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `thumb` varchar(255) NOT NULL COMMENT '分类图片',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0',
  `description` varchar(500) NOT NULL COMMENT '分类介绍',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_eso_sale_commission`
-- ----------------------------
DROP TABLE IF EXISTS `ims_eso_sale_commission`;
CREATE TABLE `ims_eso_sale_commission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `mid` int(10) unsigned NOT NULL COMMENT '粉丝ID',
  `ogid` int(10) unsigned DEFAULT NULL COMMENT '订单商品ID',
  `commission` decimal(10,2) unsigned NOT NULL COMMENT '佣金',
  `content` text,
  `flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为账户充值记录，1为提现记录',
  `isout` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为未导出，1为已导出',
  `isshare` int(11) DEFAULT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_eso_sale_credit_award`
-- ----------------------------
DROP TABLE IF EXISTS `ims_eso_sale_credit_award`;
CREATE TABLE `ims_eso_sale_credit_award` (
  `award_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT '0',
  `deadline` datetime NOT NULL,
  `credit_cost` int(11) NOT NULL DEFAULT '0',
  `price` int(11) NOT NULL DEFAULT '100',
  `content` text NOT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`award_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_eso_sale_credit_request`
-- ----------------------------
DROP TABLE IF EXISTS `ims_eso_sale_credit_request`;
CREATE TABLE `ims_eso_sale_credit_request` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `award_id` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_eso_sale_dispatch`
-- ----------------------------
DROP TABLE IF EXISTS `ims_eso_sale_dispatch`;
CREATE TABLE `ims_eso_sale_dispatch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `dispatchname` varchar(50) DEFAULT '',
  `dispatchtype` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `firstprice` decimal(10,2) DEFAULT '0.00',
  `secondprice` decimal(10,2) DEFAULT '0.00',
  `firstweight` int(11) DEFAULT '0',
  `secondweight` int(11) DEFAULT '0',
  `express` int(11) DEFAULT '0',
  `description` text,
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_eso_sale_express`
-- ----------------------------
DROP TABLE IF EXISTS `ims_eso_sale_express`;
CREATE TABLE `ims_eso_sale_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `express_name` varchar(50) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `express_price` varchar(10) DEFAULT '',
  `express_area` varchar(100) DEFAULT '',
  `express_url` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_eso_sale_feedback`
-- ----------------------------
DROP TABLE IF EXISTS `ims_eso_sale_feedback`;
CREATE TABLE `ims_eso_sale_feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为维权，2为告擎',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态0未解决，1用户同意，2用户拒绝',
  `feedbackid` varchar(30) NOT NULL COMMENT '投诉单号',
  `transid` varchar(30) NOT NULL COMMENT '订单号',
  `reason` varchar(1000) NOT NULL COMMENT '理由',
  `solution` varchar(1000) NOT NULL COMMENT '期待解决方案',
  `remark` varchar(1000) NOT NULL COMMENT '备注',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_feedbackid` (`feedbackid`),
  KEY `idx_createtime` (`createtime`),
  KEY `idx_transid` (`transid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_eso_sale_goods`
-- ----------------------------
DROP TABLE IF EXISTS `ims_eso_sale_goods`;
CREATE TABLE `ims_eso_sale_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `pcate` int(10) unsigned NOT NULL DEFAULT '0',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为实体，2为虚拟',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `xsthumb` varchar(255) DEFAULT '',
  `unit` varchar(5) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `goodssn` varchar(50) NOT NULL DEFAULT '',
  `productsn` varchar(50) NOT NULL DEFAULT '',
  `marketprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `productprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `costprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` int(10) NOT NULL DEFAULT '0',
  `totalcnf` int(11) DEFAULT '0' COMMENT '0 拍下减库存 1 付款减库存 2 永久不减',
  `sales` int(10) unsigned NOT NULL DEFAULT '0',
  `spec` varchar(5000) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `weight` decimal(10,2) NOT NULL DEFAULT '0.00',
  `credit` int(11) DEFAULT '0',
  `maxbuy` int(11) DEFAULT '0',
  `hasoption` int(11) DEFAULT '0',
  `dispatch` int(11) DEFAULT '0',
  `thumb_url` text,
  `isnew` int(11) DEFAULT '0',
  `ishot` int(11) DEFAULT '0',
  `isdiscount` int(11) DEFAULT '0',
  `isrecommand` int(11) DEFAULT '0',
  `istime` int(11) DEFAULT '0',
  `timestart` int(11) DEFAULT '0',
  `timeend` int(11) DEFAULT '0',
  `viewcount` int(11) DEFAULT '0',
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `commission2` int(3) DEFAULT NULL,
  `commission3` int(3) DEFAULT NULL,
  `commission` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_eso_sale_goods_option`
-- ----------------------------
DROP TABLE IF EXISTS `ims_eso_sale_goods_option`;
CREATE TABLE `ims_eso_sale_goods_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `thumb` varchar(60) DEFAULT '',
  `productprice` decimal(10,2) DEFAULT '0.00',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  `costprice` decimal(10,2) DEFAULT '0.00',
  `stock` int(11) DEFAULT '0',
  `weight` decimal(10,2) DEFAULT '0.00',
  `displayorder` int(11) DEFAULT '0',
  `specs` text,
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`goodsid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_eso_sale_goods_param`
-- ----------------------------
DROP TABLE IF EXISTS `ims_eso_sale_goods_param`;
CREATE TABLE `ims_eso_sale_goods_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `value` text,
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`goodsid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_eso_sale_member`
-- ----------------------------
DROP TABLE IF EXISTS `ims_eso_sale_member`;
CREATE TABLE `ims_eso_sale_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `shareid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `from_user` varchar(50) NOT NULL,
  `realname` varchar(20) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `pwd` varchar(20) NOT NULL,
  `bankcard` varchar(20) DEFAULT NULL,
  `banktype` varchar(20) DEFAULT NULL,
  `alipay` varchar(100) DEFAULT NULL,
  `wxhao` varchar(100) DEFAULT NULL,
  `commission` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '已结佣佣金',
  `zhifu` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '已打款佣金',
  `content` text,
  `createtime` int(10) NOT NULL,
  `flagtime` int(10) DEFAULT NULL COMMENT '为成推广人的时间',
  `status` tinyint(1) DEFAULT '1' COMMENT '0为禁用，1为可用',
  `flag` tinyint(1) DEFAULT '0' COMMENT '0为会推广人，1为推广人',
  `clickcount` int(11) NOT NULL DEFAULT '0' COMMENT '点击次数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_eso_sale_order`
-- ----------------------------
DROP TABLE IF EXISTS `ims_eso_sale_order`;
CREATE TABLE `ims_eso_sale_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `shareid` int(10) unsigned DEFAULT '0' COMMENT '推荐人ID',
  `ordersn` varchar(20) NOT NULL,
  `price` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功',
  `sendtype` tinyint(1) unsigned NOT NULL COMMENT '1为快递，2为自提',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付',
  `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号',
  `goodstype` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `remark` varchar(1000) NOT NULL DEFAULT '',
  `addressid` int(10) unsigned NOT NULL,
  `expresscom` varchar(30) NOT NULL DEFAULT '',
  `expresssn` varchar(50) NOT NULL DEFAULT '',
  `express` varchar(200) NOT NULL DEFAULT '',
  `goodsprice` decimal(10,2) DEFAULT '0.00',
  `dispatchprice` decimal(10,2) DEFAULT '0.00',
  `dispatch` int(10) DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_eso_sale_order_goods`
-- ----------------------------
DROP TABLE IF EXISTS `ims_eso_sale_order_goods`;
CREATE TABLE `ims_eso_sale_order_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `goodsid` int(10) unsigned NOT NULL,
  `commission` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '该订单的推荐佣金',
  `commission2` decimal(10,2) unsigned DEFAULT '0.00',
  `commission3` decimal(10,2) unsigned DEFAULT '0.00',
  `applytime` int(10) unsigned DEFAULT NULL COMMENT '申请时间',
  `checktime` int(10) unsigned DEFAULT NULL COMMENT '审核时间',
  `status` tinyint(3) DEFAULT '0' COMMENT '申请状态，-2为标志删除，-1为审核无效，0为未申请，1为正在申请，2为审核通过',
  `content` text,
  `price` decimal(10,2) DEFAULT '0.00',
  `total` int(10) unsigned NOT NULL DEFAULT '1',
  `optionid` int(10) DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  `optionname` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_eso_sale_product`
-- ----------------------------
DROP TABLE IF EXISTS `ims_eso_sale_product`;
CREATE TABLE `ims_eso_sale_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goodsid` int(11) NOT NULL,
  `productsn` varchar(50) NOT NULL,
  `title` varchar(1000) NOT NULL,
  `marketprice` decimal(10,0) unsigned NOT NULL,
  `productprice` decimal(10,0) unsigned NOT NULL,
  `total` int(11) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `spec` varchar(5000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_goodsid` (`goodsid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_eso_sale_rule`
-- ----------------------------
DROP TABLE IF EXISTS `ims_eso_sale_rule`;
CREATE TABLE `ims_eso_sale_rule` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT '',
  `rule` text,
  `terms` text,
  `createtime` int(10) NOT NULL,
  `gzurl` varchar(255) NOT NULL,
  `teamfy` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_eso_sale_rules`
-- ----------------------------
DROP TABLE IF EXISTS `ims_eso_sale_rules`;
CREATE TABLE `ims_eso_sale_rules` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `rule` text,
  `terms` text,
  `createtime` int(10) NOT NULL,
  `commtime` int(5) NOT NULL DEFAULT '15' COMMENT '默认15天',
  `promotertimes` int(10) NOT NULL DEFAULT '1' COMMENT '默认成交一次才能成为推广员',
  `ischeck` tinyint(1) DEFAULT '1' COMMENT '0为未审核，1为审核',
  `clickcredit` int(10) NOT NULL DEFAULT '0' COMMENT '点击获取积分',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_eso_sale_share_history`
-- ----------------------------
DROP TABLE IF EXISTS `ims_eso_sale_share_history`;
CREATE TABLE `ims_eso_sale_share_history` (
  `sharemid` int(11) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  `from_user` varchar(50) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_eso_sale_spec`
-- ----------------------------
DROP TABLE IF EXISTS `ims_eso_sale_spec`;
CREATE TABLE `ims_eso_sale_spec` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `displaytype` tinyint(3) unsigned NOT NULL,
  `content` text NOT NULL,
  `goodsid` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_eso_sale_spec_item`
-- ----------------------------
DROP TABLE IF EXISTS `ims_eso_sale_spec_item`;
CREATE TABLE `ims_eso_sale_spec_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `specid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `show` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_specid` (`specid`),
  KEY `indx_show` (`show`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_eso_share_data`
-- ----------------------------
DROP TABLE IF EXISTS `ims_eso_share_data`;
CREATE TABLE `ims_eso_share_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则id',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户uid',
  `eso_shareip` varchar(15) NOT NULL DEFAULT '' COMMENT '分享达人IP',
  `eso_sharetime` int(10) unsigned NOT NULL COMMENT '分享时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分享达人';

-- ----------------------------
--  Table structure for `ims_eso_share_list`
-- ----------------------------
DROP TABLE IF EXISTS `ims_eso_share_list`;
CREATE TABLE `ims_eso_share_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则id',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户uid',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '姓名',
  `tel` varchar(50) NOT NULL DEFAULT '' COMMENT '电话',
  `eso_sharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享量',
  `eso_sharetime` int(10) unsigned NOT NULL COMMENT '最后分享时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否禁止',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分享达人';

-- ----------------------------
--  Table structure for `ims_eso_share_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_eso_share_reply`;
CREATE TABLE `ims_eso_share_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `isname` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要绑定个人信息',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL COMMENT '规则标题',
  `checkkeyword` varchar(50) NOT NULL COMMENT '查询关键词',
  `picture` varchar(100) NOT NULL COMMENT '图片',
  `start_time` int(10) unsigned NOT NULL COMMENT '开始时间',
  `end_time` int(10) unsigned NOT NULL COMMENT '结束时间',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `content` text NOT NULL COMMENT '内容',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '开关状态',
  `r` int(10) unsigned NOT NULL DEFAULT '0',
  `z` int(10) unsigned NOT NULL DEFAULT '0',
  `u` varchar(255) DEFAULT NULL,
  `share_url` text,
  `share_txt` text,
  `share_desc` text,
  `share_title` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分享达人';

-- ----------------------------
--  Table structure for `ims_ewei_bonus_fans`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_bonus_fans`;
CREATE TABLE `ims_ewei_bonus_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `openid` varchar(100) DEFAULT '' COMMENT '用户ID',
  `nickname` varchar(255) DEFAULT '' COMMENT '昵称',
  `headurl` varchar(255) DEFAULT '' COMMENT '头像',
  `area` varchar(255) DEFAULT '' COMMENT '地区',
  `realname` varchar(255) DEFAULT '' COMMENT '姓名',
  `mobile` varchar(255) DEFAULT '' COMMENT '手机',
  `paytype` tinyint(1) DEFAULT '0',
  `account` varchar(255) DEFAULT '',
  `bank` varchar(255) DEFAULT '',
  `points_start` decimal(10,2) DEFAULT '0.00' COMMENT '初始钱数',
  `points_current` decimal(10,2) DEFAULT '0.00' COMMENT '当前钱数',
  `points_help` decimal(10,2) DEFAULT '0.00' COMMENT '合体钱数',
  `points_withdraw` decimal(10,2) DEFAULT '0.00' COMMENT '提取钱数',
  `points_total` decimal(10,2) DEFAULT '0.00' COMMENT '钱数总数',
  `helps` int(11) DEFAULT '0' COMMENT '被帮助数',
  `helpothers` int(11) DEFAULT '0' COMMENT '帮助数',
  `joincount` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0' COMMENT '0 未提现 1 已提现',
  `createtime` int(10) DEFAULT '0' COMMENT '参与时间',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_bonus_fans_help`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_bonus_fans_help`;
CREATE TABLE `ims_ewei_bonus_fans_help` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `fansopenid` varchar(100) DEFAULT '',
  `openid` varchar(100) DEFAULT '',
  `nickname` varchar(255) DEFAULT '',
  `headurl` varchar(255) DEFAULT '',
  `points` decimal(10,2) DEFAULT '0.00',
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_bonus_fans_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_bonus_fans_record`;
CREATE TABLE `ims_ewei_bonus_fans_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `openid` varchar(100) DEFAULT '',
  `nickname` varchar(255) DEFAULT '',
  `points` decimal(10,2) DEFAULT '0.00' COMMENT '钱数',
  `status` int(11) DEFAULT '0' COMMENT '状态 0 申请 1 已提现',
  `sim` int(11) DEFAULT '0' COMMENT '状态 0 用户 1 模拟',
  `createtime` int(10) DEFAULT '0' COMMENT '申请时间',
  `consumetime` int(10) DEFAULT '0' COMMENT '提现时间',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_bonus_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_bonus_reply`;
CREATE TABLE `ims_ewei_bonus_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `thumb` varchar(200) DEFAULT '',
  `isshow` tinyint(1) DEFAULT '0',
  `viewnum` int(11) DEFAULT '0',
  `start` decimal(10,2) DEFAULT '0.00',
  `end` decimal(10,2) DEFAULT '0.00',
  `detail` text,
  `rules` text,
  `copyright` varchar(200) DEFAULT '',
  `followurl` varchar(1000) DEFAULT '',
  `starttime` int(10) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  `joincount` int(11) DEFAULT '0',
  `createtime` int(10) DEFAULT '0',
  `points` decimal(10,2) DEFAULT '100.00' COMMENT '多少可以申请提现',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_bonus_sysset`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_bonus_sysset`;
CREATE TABLE `ims_ewei_bonus_sysset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `appid` varchar(255) DEFAULT '',
  `appsecret` varchar(255) DEFAULT '',
  `appid_share` varchar(255) DEFAULT '',
  `appsecret_share` varchar(255) DEFAULT '',
  `resroot` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_comeon_award`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_comeon_award`;
CREATE TABLE `ims_ewei_comeon_award` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `point` decimal(10,2) DEFAULT '0.00',
  `name` varchar(255) DEFAULT '',
  `num` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_comeon_fans`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_comeon_fans`;
CREATE TABLE `ims_ewei_comeon_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `from_user` varchar(100) DEFAULT '' COMMENT '用户ID',
  `mobile` varchar(20) DEFAULT '' COMMENT '登记信息(手机等)',
  `points` decimal(10,2) DEFAULT '0.00' COMMENT '点数',
  `helps` int(11) DEFAULT '0' COMMENT '被助力次数',
  `createtime` int(10) DEFAULT '0',
  `status` int(10) DEFAULT '0',
  `awardid` int(10) DEFAULT '0',
  `awardtime` int(10) DEFAULT '0',
  `finger` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_comeon_fans_help`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_comeon_fans_help`;
CREATE TABLE `ims_ewei_comeon_fans_help` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `from_user` varchar(100) DEFAULT '' COMMENT '助力的',
  `fansid` int(11) DEFAULT '0' COMMENT '被助力的',
  `date` varchar(20) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_comeon_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_comeon_reply`;
CREATE TABLE `ims_ewei_comeon_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT '0',
  `weid` int(11) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `thumb` varchar(200) DEFAULT '',
  `isshow` tinyint(1) DEFAULT '0',
  `fansnum` int(11) DEFAULT '0',
  `viewnum` int(11) DEFAULT '0',
  `toppic` varchar(255) DEFAULT '',
  `bgcolor` varchar(255) DEFAULT '',
  `fontcolor` varchar(255) DEFAULT '',
  `btncolor` varchar(255) DEFAULT '',
  `btnfontcolor` varchar(255) DEFAULT '',
  `start` decimal(10,2) DEFAULT '0.00',
  `end` decimal(10,2) DEFAULT '0.00',
  `tips` varchar(200) DEFAULT '',
  `info_tips` varchar(200) DEFAULT '' COMMENT '例如 您已经获得 [P] [U]',
  `help_tips` varchar(200) DEFAULT '' COMMENT '例如 给TA助力',
  `join_tips` varchar(200) DEFAULT '' COMMENT '例如 我也来领取加油卡',
  `invite_tips` varchar(200) DEFAULT '' COMMENT '例如 邀请好友助力',
  `rank_tips` varchar(200) DEFAULT '' COMMENT '例如 显示排名',
  `rank_num` int(11) DEFAULT '0' COMMENT '多少名之前的排名',
  `unit` varchar(200) DEFAULT '' COMMENT '单位',
  `ticket_information` varchar(200) DEFAULT '',
  `tel_rename` varchar(200) DEFAULT '',
  `content` text,
  `copyright` varchar(200) DEFAULT '',
  `joincontent` text,
  `overcontent` text,
  `self_times` int(11) DEFAULT '0' COMMENT '活动期间可以被助力几次',
  `self_day_times` int(11) DEFAULT '0' COMMENT '每天可以被助力几次',
  `other_times` int(11) DEFAULT '0' COMMENT '活动期间可给别人助力多少次',
  `other_day_times` int(11) DEFAULT '0' COMMENT '每天可给别人助力多少次',
  `other_one_times` int(11) DEFAULT '0' COMMENT '活动期间可给相同助力多少次',
  `other_one_day_times` int(11) DEFAULT '0' COMMENT '每天可给相同用户助力多少次',
  `type` tinyint(1) DEFAULT '0' COMMENT '规则类型 0 集分 1 集分',
  `show_rank` tinyint(1) DEFAULT '0' COMMENT '显示排名 0 不显示 1 显示',
  `show_num` tinyint(1) DEFAULT '0' COMMENT '是否显示奖品数量',
  `show_helps` tinyint(1) DEFAULT '0' COMMENT '是否显示助力数',
  `awardtype` tinyint(1) DEFAULT '0' COMMENT '奖品类型 0 一次性 1 阶梯性',
  `awards` text COMMENT '奖品',
  `rules` text COMMENT '规则',
  `starttime` int(10) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  `createtime` int(10) DEFAULT '0',
  `share_title` varchar(200) DEFAULT '',
  `share_desc` varchar(300) DEFAULT '',
  `share_url` varchar(100) DEFAULT '',
  `share_txt` varchar(500) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_comeon_rule`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_comeon_rule`;
CREATE TABLE `ims_ewei_comeon_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `point` decimal(10,2) DEFAULT '0.00',
  `start` decimal(10,2) DEFAULT '0.00',
  `end` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_comeon_sysset`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_comeon_sysset`;
CREATE TABLE `ims_ewei_comeon_sysset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `appid` varchar(255) DEFAULT '',
  `appsecret` varchar(255) DEFAULT '',
  `appid_share` varchar(255) DEFAULT '',
  `appsecret_share` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_couplet_fans`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_couplet_fans`;
CREATE TABLE `ims_ewei_couplet_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `openid` varchar(100) DEFAULT '' COMMENT '用户ID',
  `nickname` varchar(255) DEFAULT '' COMMENT '昵称',
  `headurl` varchar(255) DEFAULT '' COMMENT '头像',
  `area` varchar(255) DEFAULT '' COMMENT '地区',
  `realname` varchar(255) DEFAULT '' COMMENT '姓名',
  `mobile` varchar(255) DEFAULT '' COMMENT '手机',
  `uptext` text COMMENT '上联',
  `downtext` text COMMENT '下联',
  `rule` text COMMENT '规则',
  `helps` int(11) DEFAULT '0' COMMENT '被帮助数',
  `status` tinyint(1) DEFAULT '0' COMMENT '0 未中奖 1 已中奖 2 已兑奖',
  `num` int(11) DEFAULT '0' COMMENT '抽中个数',
  `log` tinyint(1) DEFAULT '0',
  `sim` tinyint(1) DEFAULT '0',
  `createtime` int(10) DEFAULT '0' COMMENT '参与时间',
  `consumetime` int(10) DEFAULT '0' COMMENT '兑奖时间',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_couplet_fans_help`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_couplet_fans_help`;
CREATE TABLE `ims_ewei_couplet_fans_help` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `fansopenid` varchar(100) DEFAULT '',
  `openid` varchar(100) DEFAULT '',
  `nickname` varchar(255) DEFAULT '',
  `headurl` varchar(255) DEFAULT '',
  `desc` text,
  `status` tinyint(1) DEFAULT '0' COMMENT '0 错误 1 正确',
  `createtime` int(10) DEFAULT '0' COMMENT '帮助时间',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_couplet_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_couplet_reply`;
CREATE TABLE `ims_ewei_couplet_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `thumb` varchar(200) DEFAULT '',
  `isshow` tinyint(1) DEFAULT '0',
  `viewnum` int(11) DEFAULT '0',
  `start` decimal(10,2) DEFAULT '0.00',
  `end` decimal(10,2) DEFAULT '0.00',
  `detail` text,
  `rules` text,
  `couplets` text,
  `award_name` varchar(255) DEFAULT '0',
  `award_total` int(11) DEFAULT '0',
  `award_last` int(11) DEFAULT '0',
  `friendcount` int(11) DEFAULT '0',
  `copyright` varchar(200) DEFAULT '',
  `toptext` varchar(200) DEFAULT '',
  `followurl` varchar(1000) DEFAULT '',
  `starttime` int(10) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  `joincount` int(11) DEFAULT '0',
  `bgcolor` varchar(255) DEFAULT '',
  `res_img1` varchar(255) DEFAULT '',
  `res_img2` varchar(255) DEFAULT '',
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_couplet_sysset`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_couplet_sysset`;
CREATE TABLE `ims_ewei_couplet_sysset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `appid` varchar(255) DEFAULT '',
  `appsecret` varchar(255) DEFAULT '',
  `appid_share` varchar(255) DEFAULT '',
  `appsecret_share` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_dream_fans`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_dream_fans`;
CREATE TABLE `ims_ewei_dream_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `fansid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `nickname` varchar(255) DEFAULT '',
  `headurl` varchar(255) DEFAULT '',
  `dream` varchar(255) DEFAULT '',
  `punishment` varchar(255) DEFAULT '',
  `views` int(11) DEFAULT '0',
  `oversees` text,
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_dream_oversee`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_dream_oversee`;
CREATE TABLE `ims_ewei_dream_oversee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `fansid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `nickname` varchar(255) DEFAULT '',
  `headurl` varchar(255) DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_dream_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_dream_reply`;
CREATE TABLE `ims_ewei_dream_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `dreams` text,
  `punishments` text,
  `views` int(11) DEFAULT '0',
  `shares` int(11) DEFAULT '0',
  `follow_url` varchar(255) DEFAULT '',
  `follow_need` int(11) DEFAULT '0',
  `diy_bgcolor` varchar(255) DEFAULT '',
  `diy_fontcolor` varchar(255) DEFAULT '',
  `diy_topimg` varchar(255) DEFAULT '',
  `diy_btncolor` varchar(255) DEFAULT '',
  `diy_btnfontcolor` varchar(255) DEFAULT '',
  `diy_btntext` varchar(255) DEFAULT '',
  `diy_title1` varchar(255) DEFAULT '',
  `diy_title2` varchar(255) DEFAULT '',
  `diy_title3` varchar(255) DEFAULT '',
  `diy_title4` varchar(255) DEFAULT '',
  `diy_title5` varchar(255) DEFAULT '',
  `diy_audio` varchar(255) DEFAULT '',
  `diy_topimgshare` varchar(255) DEFAULT '',
  `diy_inputcolor` varchar(255) DEFAULT '',
  `diy_inputtextcolor` varchar(255) DEFAULT '',
  `diy_paperimg` varchar(255) DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  `copyright` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_exam_course`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_exam_course`;
CREATE TABLE `ims_ewei_exam_course` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0' COMMENT '所属帐号',
  `pcate` int(11) DEFAULT '0',
  `ccate` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '' COMMENT '课程标题',
  `ctype` int(11) DEFAULT '0' COMMENT '0 时间限制 1 人数限制',
  `starttime` int(11) DEFAULT '0' COMMENT '报名开始时间',
  `endtime` int(11) DEFAULT '0' COMMENT '报名截止时间',
  `ctotal` int(11) DEFAULT '0' COMMENT '报名人数限制',
  `description` text,
  `content` text,
  `thumb` varchar(255) DEFAULT '',
  `viewnum` int(11) DEFAULT '0' COMMENT '访问人数',
  `fansnum` int(11) DEFAULT '0' COMMENT '报名人数',
  `teachers` text COMMENT '授课讲师',
  `coursetime` int(11) DEFAULT '0' COMMENT '开始时间',
  `times` int(11) DEFAULT '0' COMMENT '授课时长',
  `week` int(11) DEFAULT '0' COMMENT '第几期',
  `address` text,
  `location_p` varchar(255) DEFAULT NULL,
  `location_c` varchar(255) DEFAULT NULL,
  `location_a` varchar(255) DEFAULT NULL,
  `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000',
  `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000',
  `createtime` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0' COMMENT '题目排序',
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`),
  KEY `idx_pcate` (`ccate`),
  KEY `idx_ccate` (`ccate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_exam_course_category`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_exam_course_category`;
CREATE TABLE `ims_ewei_exam_course_category` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0' COMMENT '所属帐号',
  `parentid` int(11) DEFAULT '0',
  `cname` varchar(255) DEFAULT '',
  `description` text COMMENT '描述',
  `displayorder` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`),
  KEY `idx_parentid` (`parentid`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_exam_course_reserve`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_exam_course_reserve`;
CREATE TABLE `ims_ewei_exam_course_reserve` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `courseid` int(11) DEFAULT '0',
  `memberid` int(11) DEFAULT '0',
  `times` int(11) DEFAULT '0' COMMENT '用时',
  `createtime` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `username` varchar(255) DEFAULT '',
  `mobile` varchar(255) DEFAULT '',
  `email` varchar(255) DEFAULT '',
  `ordersn` varchar(255) DEFAULT '',
  `msg` text,
  `mngtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_memberid` (`memberid`),
  KEY `idx_weid` (`weid`),
  KEY `idx_paperid` (`courseid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- ----------------------------
--  Table structure for `ims_ewei_exam_member`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_exam_member`;
CREATE TABLE `ims_ewei_exam_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '',
  `userid` varchar(255) DEFAULT '',
  `username` varchar(255) DEFAULT '',
  `mobile` varchar(255) DEFAULT '',
  `email` varchar(255) DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_exam_paper`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_exam_paper`;
CREATE TABLE `ims_ewei_exam_paper` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0' COMMENT '所属帐号',
  `pcate` int(11) DEFAULT '0',
  `ccate` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '' COMMENT '试卷标题',
  `level` int(11) DEFAULT '0' COMMENT '难度',
  `score` int(11) DEFAULT '0' COMMENT '分值',
  `description` text,
  `thumb` varchar(255) DEFAULT '',
  `year` int(11) DEFAULT '0' COMMENT '年份',
  `viewnum` int(11) DEFAULT '0' COMMENT '访问人数',
  `fansnum` int(11) DEFAULT '0' COMMENT '考试人数',
  `times` int(11) DEFAULT '0' COMMENT '时间限制 0不限制',
  `types` varchar(5) DEFAULT NULL COMMENT '考题类型选择 例如 11111 包含5种题型',
  `avscore` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '平均分',
  `avtimes` int(11) NOT NULL DEFAULT '0' COMMENT '平均用时',
  `createtime` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `tid` int(11) NOT NULL DEFAULT '0' COMMENT '考题类型id',
  `status` tinyint(1) DEFAULT '0',
  `isfull` tinyint(1) NOT NULL DEFAULT '0' COMMENT '试题是否完整1完整0不完整',
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`),
  KEY `idx_pcate` (`ccate`),
  KEY `idx_ccate` (`ccate`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_exam_paper_category`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_exam_paper_category`;
CREATE TABLE `ims_ewei_exam_paper_category` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0' COMMENT '所属帐号',
  `parentid` int(11) DEFAULT '0',
  `cname` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `description` text COMMENT '描述',
  `status` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`),
  KEY `idx_parentid` (`parentid`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_exam_paper_member_data`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_exam_paper_member_data`;
CREATE TABLE `ims_ewei_exam_paper_member_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `paperid` int(11) DEFAULT '0',
  `memberid` int(11) DEFAULT '0',
  `recordid` int(11) DEFAULT '0' COMMENT '学员考试记录id',
  `questionid` int(11) NOT NULL DEFAULT '0',
  `answer` text,
  `times` int(11) DEFAULT '0' COMMENT '单题用时',
  `createtime` int(11) DEFAULT '0',
  `isright` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否回答正确',
  `type` int(11) DEFAULT '0' COMMENT '1 判断 2单选 3多选 4 填空  5 解答',
  `pageid` int(11) NOT NULL DEFAULT '0' COMMENT '顺序id',
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`),
  KEY `idx_memberid` (`memberid`),
  KEY `idx_paperid` (`paperid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_exam_paper_member_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_exam_paper_member_record`;
CREATE TABLE `ims_ewei_exam_paper_member_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `paperid` int(11) DEFAULT '0',
  `memberid` int(11) DEFAULT '0',
  `times` int(11) DEFAULT '0' COMMENT '用时',
  `countdown` int(11) DEFAULT '0' COMMENT '倒计时',
  `score` decimal(10,2) DEFAULT '0.00' COMMENT '得分',
  `did` int(11) DEFAULT '0' COMMENT '是否完成考试',
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_memberid` (`memberid`),
  KEY `idx_weid` (`weid`),
  KEY `idx_paperid` (`paperid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_exam_paper_question`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_exam_paper_question`;
CREATE TABLE `ims_ewei_exam_paper_question` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `questionid` int(11) DEFAULT '0' COMMENT '题ID',
  `displayorder` int(11) DEFAULT '0' COMMENT '题目排序',
  `paperid` bigint(20) NOT NULL DEFAULT '0' COMMENT '试卷ID',
  PRIMARY KEY (`id`),
  KEY `idx_questionid` (`questionid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_exam_paper_type`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_exam_paper_type`;
CREATE TABLE `ims_ewei_exam_paper_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0' COMMENT '所属帐号',
  `title` varchar(255) DEFAULT '' COMMENT '试卷标题',
  `score` decimal(10,2) DEFAULT '0.00' COMMENT '分值',
  `types` text COMMENT '试题类型设置 包含试题类型 试题分数',
  `times` int(11) NOT NULL DEFAULT '0' COMMENT '考试时间',
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_exam_pool`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_exam_pool`;
CREATE TABLE `ims_ewei_exam_pool` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '' COMMENT '标题',
  `description` text COMMENT '描述',
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_exam_question`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_exam_question`;
CREATE TABLE `ims_ewei_exam_question` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `poolid` int(11) DEFAULT '0' COMMENT '题库ID',
  `paperid1` int(11) DEFAULT '0' COMMENT '题库ID',
  `type` int(11) DEFAULT '0' COMMENT '1 判断 2单选 3多选 4 填空  5 解答',
  `level` int(11) DEFAULT '0' COMMENT '难度',
  `question` text COMMENT '问题',
  `thumb` varchar(255) DEFAULT '' COMMENT '问题图片',
  `answer` text COMMENT '答案',
  `isimg` tinyint(1) DEFAULT '0' COMMENT '答案是否包含图片',
  `explain` text COMMENT '讲解',
  `fansnum` int(11) DEFAULT '0' COMMENT '多少人做过',
  `correctnum` int(11) DEFAULT '0' COMMENT '多少人正确',
  `items` text,
  `img_items` text,
  PRIMARY KEY (`id`),
  KEY `idx_poolid` (`poolid`),
  KEY `idx_type` (`type`),
  KEY `idx_weid` (`weid`),
  KEY `idx_paperid` (`paperid1`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_exam_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_exam_reply`;
CREATE TABLE `ims_ewei_exam_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `weid` int(11) DEFAULT '0' COMMENT '所属帐号',
  `paperid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`),
  KEY `idx_weid` (`weid`),
  KEY `idx_paperid` (`paperid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_exam_sysset`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_exam_sysset`;
CREATE TABLE `ims_ewei_exam_sysset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `classopen` int(11) DEFAULT '1',
  `login_flag` tinyint(1) DEFAULT '0' COMMENT '是否开启登录',
  `about` text COMMENT '帮助',
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_money_award`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_money_award`;
CREATE TABLE `ims_ewei_money_award` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `uid` int(11) DEFAULT '0',
  `sum` float DEFAULT NULL,
  `info` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '0' COMMENT '用户ID',
  `name` varchar(50) DEFAULT '' COMMENT '名称',
  `description` varchar(200) DEFAULT '' COMMENT '描述',
  `prizetype` varchar(10) DEFAULT '' COMMENT '类型',
  `award_sn` varchar(50) DEFAULT '' COMMENT 'SN',
  `createtime` int(10) DEFAULT '0',
  `consumetime` int(10) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `exchange` double DEFAULT '0',
  `useable` double DEFAULT '0',
  `shopUrl` varchar(300) NOT NULL COMMENT '购物链接地址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_money_fans`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_money_fans`;
CREATE TABLE `ims_ewei_money_fans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `isplay` tinyint(1) DEFAULT '0',
  `info` tinyint(1) DEFAULT '0',
  `from_user` varchar(50) NOT NULL,
  `nickname` varchar(20) NOT NULL DEFAULT '' COMMENT '昵称',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号码',
  `sum` float DEFAULT NULL,
  `remain` int(11) NOT NULL,
  `max_score` float NOT NULL,
  `alltimes` int(11) NOT NULL COMMENT '总剩余次数',
  `daytimes` int(11) NOT NULL COMMENT '当天剩余次数',
  `lasttime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_money_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_money_reply`;
CREATE TABLE `ims_ewei_money_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `isfollow` tinyint(1) NOT NULL COMMENT '是否关注',
  `isshow` tinyint(1) DEFAULT '0',
  `info` int(11) DEFAULT '0',
  `c_rate_one` tinyint(1) DEFAULT '0',
  `c_rate_two` tinyint(1) DEFAULT '0',
  `c_rate_three` tinyint(1) DEFAULT '0',
  `c_rate_four` tinyint(1) DEFAULT '0',
  `c_rate_five` tinyint(1) DEFAULT '0',
  `c_rate_six` tinyint(1) DEFAULT '0',
  `c_rate_seven` tinyint(1) DEFAULT '0',
  `c_rate_eight` tinyint(1) DEFAULT '0',
  `c_rate_nine` tinyint(1) DEFAULT '0',
  `game_time` int(11) NOT NULL,
  `title` varchar(200) DEFAULT '',
  `start_picurl` varchar(200) DEFAULT '',
  `reg_first` tinyint(1) NOT NULL COMMENT '游戏前后注册',
  `max_sum` int(11) NOT NULL,
  `min_sum` int(11) NOT NULL,
  `total_remain` int(11) NOT NULL,
  `remain` int(11) NOT NULL,
  `remain_stime` int(11) NOT NULL,
  `remain_etime` int(11) NOT NULL,
  `remain_name` varchar(50) NOT NULL COMMENT '现金劵名称',
  `remain_sm` varchar(15) NOT NULL COMMENT '兑奖密码',
  `valid_time` varchar(100) NOT NULL COMMENT '现金劵有效时间',
  `remain_rule` varchar(100) NOT NULL COMMENT '现金劵规则',
  `rule` text NOT NULL COMMENT '规则',
  `description` text NOT NULL COMMENT '活动简介',
  `alltimes` int(3) unsigned NOT NULL COMMENT '最大抽奖数',
  `daytimes` int(11) NOT NULL COMMENT '每天最大抽奖数',
  `homeurl` varchar(300) NOT NULL COMMENT '微站链接地址',
  `homepicurl` varchar(200) DEFAULT '',
  `followurl` varchar(300) NOT NULL COMMENT '提示关注网址',
  `homename` varchar(50) NOT NULL COMMENT '微站名称',
  `starttime` int(11) NOT NULL,
  `endtime` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `view_times` int(11) NOT NULL,
  `play_times` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_money_sysset`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_money_sysset`;
CREATE TABLE `ims_ewei_money_sysset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `appid` varchar(255) DEFAULT '',
  `appsecret` varchar(255) DEFAULT '',
  `appid_share` varchar(255) DEFAULT '',
  `appsecret_share` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_adv`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_adv`;
CREATE TABLE `ims_ewei_shop_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_enabled` (`enabled`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_carrier`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_carrier`;
CREATE TABLE `ims_ewei_shop_carrier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `realname` varchar(50) DEFAULT '',
  `mobile` varchar(50) DEFAULT '',
  `address` varchar(255) DEFAULT '',
  `deleted` tinyint(1) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_deleted` (`deleted`),
  KEY `idx_createtime` (`createtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_category`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_category`;
CREATE TABLE `ims_ewei_shop_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) DEFAULT NULL COMMENT '分类名称',
  `thumb` varchar(255) DEFAULT NULL COMMENT '分类图片',
  `parentid` int(11) DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0',
  `description` varchar(500) DEFAULT NULL COMMENT '分类介绍',
  `displayorder` tinyint(3) unsigned DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) DEFAULT '1' COMMENT '是否开启',
  `ishome` tinyint(3) DEFAULT '0',
  `advimg` varchar(255) DEFAULT '',
  `advurl` varchar(500) DEFAULT '',
  `level` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_displayorder` (`displayorder`),
  KEY `idx_enabled` (`enabled`),
  KEY `idx_parentid` (`parentid`),
  KEY `idx_isrecommand` (`isrecommand`),
  KEY `idx_ishome` (`ishome`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_commission_apply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_commission_apply`;
CREATE TABLE `ims_ewei_shop_commission_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `applyno` varchar(255) DEFAULT '',
  `mid` int(11) DEFAULT '0' COMMENT '会员ID',
  `type` tinyint(3) DEFAULT '0' COMMENT '0 余额 1 微信',
  `orderids` text,
  `commission` decimal(10,2) DEFAULT '0.00',
  `commission_pay` decimal(10,2) DEFAULT '0.00',
  `content` text,
  `status` tinyint(3) DEFAULT '0' COMMENT '-1 无效 0 未知 1 正在申请 2 审核通过 3 已经打款',
  `applytime` int(11) DEFAULT '0',
  `checktime` int(11) DEFAULT '0',
  `paytime` int(11) DEFAULT '0',
  `invalidtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_mid` (`mid`),
  KEY `idx_checktime` (`checktime`),
  KEY `idx_paytime` (`paytime`),
  KEY `idx_applytime` (`applytime`),
  KEY `idx_status` (`status`),
  KEY `idx_invalidtime` (`invalidtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_commission_clickcount`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_commission_clickcount`;
CREATE TABLE `ims_ewei_shop_commission_clickcount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `from_openid` varchar(255) DEFAULT '',
  `clicktime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_from_openid` (`from_openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_commission_level`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_commission_level`;
CREATE TABLE `ims_ewei_shop_commission_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `levelname` varchar(50) DEFAULT '',
  `commission1` decimal(10,2) DEFAULT '0.00',
  `commission2` decimal(10,2) DEFAULT '0.00',
  `commission3` decimal(10,2) DEFAULT '0.00',
  `commissionmoney` decimal(10,2) DEFAULT '0.00',
  `ordermoney` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_commission_log`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_commission_log`;
CREATE TABLE `ims_ewei_shop_commission_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `applyid` int(11) DEFAULT '0',
  `mid` int(11) DEFAULT '0',
  `commission` decimal(10,2) DEFAULT '0.00',
  `createtime` int(11) DEFAULT '0',
  `commission_pay` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_applyid` (`applyid`),
  KEY `idx_mid` (`mid`),
  KEY `idx_createtime` (`createtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_commission_shop`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_commission_shop`;
CREATE TABLE `ims_ewei_shop_commission_shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `mid` int(11) DEFAULT '0',
  `name` varchar(255) DEFAULT '',
  `logo` varchar(255) DEFAULT '',
  `img` varchar(255) DEFAULT NULL,
  `desc` varchar(255) DEFAULT '',
  `selectgoods` tinyint(3) DEFAULT '0',
  `selectcategory` tinyint(3) DEFAULT '0',
  `goodsids` text,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_mid` (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_creditshop_adv`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_creditshop_adv`;
CREATE TABLE `ims_ewei_shop_creditshop_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_enabled` (`enabled`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_creditshop_category`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_creditshop_category`;
CREATE TABLE `ims_ewei_shop_creditshop_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) DEFAULT NULL COMMENT '分类名称',
  `thumb` varchar(255) DEFAULT NULL COMMENT '分类图片',
  `displayorder` tinyint(3) unsigned DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) DEFAULT '1' COMMENT '是否开启',
  `advimg` varchar(255) DEFAULT '',
  `advurl` varchar(500) DEFAULT '',
  `isrecommand` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_displayorder` (`displayorder`),
  KEY `idx_enabled` (`enabled`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_creditshop_goods`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_creditshop_goods`;
CREATE TABLE `ims_ewei_shop_creditshop_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `cate` int(11) DEFAULT '0',
  `thumb` varchar(255) DEFAULT '',
  `price` decimal(10,2) DEFAULT '0.00',
  `type` tinyint(3) DEFAULT '0',
  `credit` int(11) DEFAULT '0',
  `money` decimal(10,2) DEFAULT '0.00',
  `total` int(11) DEFAULT '0',
  `totalday` int(11) DEFAULT '0',
  `chance` int(11) DEFAULT '0',
  `chanceday` int(11) DEFAULT '0',
  `detail` text,
  `rate1` int(11) DEFAULT '0',
  `rate2` int(11) DEFAULT '0',
  `endtime` int(11) DEFAULT '0',
  `joins` int(11) DEFAULT '0',
  `views` int(11) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `status` tinyint(3) DEFAULT '0',
  `deleted` tinyint(3) DEFAULT '0',
  `showlevels` text,
  `buylevels` text,
  `showgroups` text,
  `buygroups` text,
  `vip` tinyint(3) DEFAULT '0',
  `istop` tinyint(3) DEFAULT '0',
  `isrecommand` tinyint(3) DEFAULT '0',
  `istime` tinyint(3) DEFAULT '0',
  `timestart` int(11) DEFAULT '0',
  `timeend` int(11) DEFAULT '0',
  `share_title` varchar(255) DEFAULT '',
  `share_icon` varchar(255) DEFAULT '',
  `share_desc` varchar(500) DEFAULT '',
  `followneed` tinyint(3) DEFAULT '0',
  `followtext` varchar(255) DEFAULT '',
  `subtitle` varchar(255) DEFAULT '',
  `subdetail` text,
  `noticedetail` text,
  `usedetail` varchar(255) DEFAULT '',
  `goodsdetail` text,
  `isendtime` tinyint(3) DEFAULT '0',
  `usecredit2` tinyint(3) DEFAULT '0',
  `area` varchar(255) DEFAULT '',
  `dispatch` decimal(10,2) DEFAULT '0.00',
  `storeids` text,
  `noticeopenid` varchar(255) DEFAULT '',
  `noticetype` tinyint(3) DEFAULT '0',
  `isverify` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_type` (`type`),
  KEY `idx_endtime` (`endtime`),
  KEY `idx_createtime` (`createtime`),
  KEY `idx_status` (`status`),
  KEY `idx_displayorder` (`displayorder`),
  KEY `idx_deleted` (`deleted`),
  KEY `idx_istop` (`istop`),
  KEY `idx_isrecommand` (`isrecommand`),
  KEY `idx_istime` (`istime`),
  KEY `idx_timestart` (`timestart`),
  KEY `idx_timeend` (`timeend`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_creditshop_log`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_creditshop_log`;
CREATE TABLE `ims_ewei_shop_creditshop_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `logno` varchar(255) DEFAULT '',
  `eno` varchar(255) DEFAULT '' COMMENT '兑换码',
  `openid` varchar(255) DEFAULT '',
  `goodsid` int(11) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `status` tinyint(3) DEFAULT '0' COMMENT '0 只生成记录未参加 1 未中奖 2 已中奖 3 已发奖',
  `paystatus` tinyint(3) DEFAULT '0' COMMENT '支付状态 -1 不需要支付 0 未支付 1 已支付',
  `paytype` tinyint(3) DEFAULT '-1' COMMENT '支付类型 -1 不需要支付 0 余额 1 微信',
  `dispatchstatus` tinyint(3) DEFAULT '0' COMMENT '运费状态 -1 不需要运费 0 未支付 1 已支付',
  `creditpay` tinyint(3) DEFAULT '0' COMMENT '积分支付 0 未支付 1 已支付',
  `addressid` int(11) DEFAULT '0' COMMENT '收货地址',
  `dispatchno` varchar(255) DEFAULT '' COMMENT '运费支付单号',
  `usetime` int(11) DEFAULT '0',
  `express` varchar(255) DEFAULT '',
  `expresssn` varchar(255) DEFAULT '',
  `expresscom` varchar(255) DEFAULT '',
  `verifyopenid` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_designer`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_designer`;
CREATE TABLE `ims_ewei_shop_designer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0' COMMENT '公众号',
  `pagename` varchar(255) NOT NULL DEFAULT '' COMMENT '页面名称',
  `pagetype` tinyint(3) NOT NULL DEFAULT '0' COMMENT '页面类型',
  `pageinfo` text NOT NULL,
  `createtime` varchar(255) NOT NULL DEFAULT '' COMMENT '页面创建时间',
  `keyword` varchar(255) DEFAULT '',
  `savetime` varchar(255) NOT NULL DEFAULT '' COMMENT '页面最后保存时间',
  `setdefault` tinyint(3) NOT NULL DEFAULT '0' COMMENT '默认页面',
  `datas` text NOT NULL COMMENT '数据',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_pagetype` (`pagetype`),
  FULLTEXT KEY `idx_keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_designer_menu`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_designer_menu`;
CREATE TABLE `ims_ewei_shop_designer_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `menuname` varchar(255) DEFAULT '',
  `isdefault` tinyint(3) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `menus` text,
  `params` text,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_isdefault` (`isdefault`),
  KEY `idx_createtime` (`createtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
--  Table structure for `ims_ewei_shop_dispatch`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_dispatch`;
CREATE TABLE `ims_ewei_shop_dispatch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `dispatchname` varchar(50) DEFAULT '',
  `dispatchtype` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `firstprice` decimal(10,2) DEFAULT '0.00',
  `secondprice` decimal(10,2) DEFAULT '0.00',
  `firstweight` int(11) DEFAULT '0',
  `secondweight` int(11) DEFAULT '0',
  `express` varchar(250) DEFAULT '',
  `areas` text,
  `carriers` text,
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_express`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_express`;
CREATE TABLE `ims_ewei_shop_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `express_name` varchar(50) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `express_price` varchar(10) DEFAULT '',
  `express_area` varchar(100) DEFAULT '',
  `express_url` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_feedback`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_feedback`;
CREATE TABLE `ims_ewei_shop_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '0',
  `type` tinyint(1) DEFAULT '1' COMMENT '1为维权，2为投诉',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态 0 未解决，1用户同意，2用户拒绝',
  `feedbackid` varchar(100) DEFAULT '' COMMENT '投诉单号',
  `transid` varchar(100) DEFAULT '' COMMENT '订单号',
  `reason` varchar(1000) DEFAULT '' COMMENT '理由',
  `solution` varchar(1000) DEFAULT '' COMMENT '期待解决方案',
  `remark` varchar(1000) DEFAULT '' COMMENT '备注',
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_feedbackid` (`feedbackid`),
  KEY `idx_createtime` (`createtime`),
  KEY `idx_transid` (`transid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_goods`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_goods`;
CREATE TABLE `ims_ewei_shop_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `pcate` int(11) DEFAULT '0',
  `ccate` int(11) DEFAULT '0',
  `type` tinyint(1) DEFAULT '1' COMMENT '1为实体，2为虚拟',
  `status` tinyint(1) DEFAULT '1',
  `displayorder` int(11) DEFAULT '0',
  `title` varchar(100) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `unit` varchar(5) DEFAULT '',
  `description` varchar(1000) DEFAULT '',
  `content` text,
  `goodssn` varchar(50) DEFAULT '',
  `productsn` varchar(50) DEFAULT '',
  `productprice` decimal(10,2) DEFAULT '0.00',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  `costprice` decimal(10,2) DEFAULT '0.00',
  `originalprice` decimal(10,2) DEFAULT '0.00' COMMENT '原价',
  `total` int(10) DEFAULT '0',
  `totalcnf` int(11) DEFAULT '0' COMMENT '0 拍下减库存 1 付款减库存 2 永久不减',
  `sales` int(11) DEFAULT '0',
  `salesreal` int(11) DEFAULT '0',
  `spec` varchar(5000) DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  `weight` decimal(10,2) DEFAULT '0.00',
  `credit` int(11) DEFAULT '0',
  `maxbuy` int(11) DEFAULT '0',
  `usermaxbuy` int(11) DEFAULT '0',
  `hasoption` int(11) DEFAULT '0',
  `dispatch` int(11) DEFAULT '0',
  `thumb_url` text,
  `isnew` tinyint(1) DEFAULT '0',
  `ishot` tinyint(1) DEFAULT '0',
  `isdiscount` tinyint(1) DEFAULT '0',
  `isrecommand` tinyint(1) DEFAULT '0',
  `issendfree` tinyint(1) DEFAULT '0',
  `istime` tinyint(1) DEFAULT '0',
  `iscomment` tinyint(1) DEFAULT '0',
  `timestart` int(11) DEFAULT '0',
  `timeend` int(11) DEFAULT '0',
  `viewcount` int(11) DEFAULT '0',
  `deleted` tinyint(3) DEFAULT '0',
  `hascommission` tinyint(3) DEFAULT '0',
  `commission1_rate` decimal(10,2) DEFAULT '0.00',
  `commission1_pay` decimal(10,2) DEFAULT '0.00',
  `commission2_rate` decimal(10,2) DEFAULT '0.00',
  `commission2_pay` decimal(10,2) DEFAULT '0.00',
  `commission3_rate` decimal(10,2) DEFAULT '0.00',
  `commission3_pay` decimal(10,2) DEFAULT '0.00',
  `score` decimal(10,2) DEFAULT '0.00',
  `taobaoid` varchar(255) DEFAULT '',
  `taotaoid` varchar(255) DEFAULT '',
  `taobaourl` varchar(255) DEFAULT '',
  `updatetime` int(11) DEFAULT '0',
  `share_title` varchar(255) DEFAULT '',
  `share_icon` varchar(255) DEFAULT '',
  `cash` tinyint(3) DEFAULT '0',
  `commission_thumb` varchar(255) DEFAULT '',
  `isnodiscount` tinyint(3) DEFAULT '0',
  `showlevels` text,
  `buylevels` text,
  `showgroups` text,
  `buygroups` text,
  `isverify` tinyint(3) DEFAULT '0',
  `storeids` text,
  `noticeopenid` text,
  `tcate` int(11) DEFAULT '0',
  `noticetype` tinyint(3) DEFAULT '0',
  `needfollow` tinyint(3) DEFAULT '0',
  `followtip` varchar(255) DEFAULT '',
  `followurl` varchar(255) DEFAULT '',
  `deduct` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_pcate` (`pcate`),
  KEY `idx_ccate` (`ccate`),
  KEY `idx_isnew` (`isnew`),
  KEY `idx_ishot` (`ishot`),
  KEY `idx_isdiscount` (`isdiscount`),
  KEY `idx_isrecommand` (`isrecommand`),
  KEY `idx_iscomment` (`iscomment`),
  KEY `idx_issendfree` (`issendfree`),
  KEY `idx_istime` (`istime`),
  KEY `idx_deleted` (`deleted`),
  KEY `idx_tcate` (`tcate`),
  FULLTEXT KEY `idx_buylevels` (`buylevels`),
  FULLTEXT KEY `idx_showgroups` (`showgroups`),
  FULLTEXT KEY `idx_buygroups` (`buygroups`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_goods_comment`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_goods_comment`;
CREATE TABLE `ims_ewei_shop_goods_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `goodsid` int(10) DEFAULT '0',
  `openid` varchar(50) DEFAULT '',
  `nickname` varchar(50) DEFAULT '',
  `headimgurl` varchar(255) DEFAULT '',
  `content` varchar(255) DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_goodsid` (`goodsid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_createtime` (`createtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_goods_option`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_goods_option`;
CREATE TABLE `ims_ewei_shop_goods_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `thumb` varchar(60) DEFAULT '',
  `productprice` decimal(10,2) DEFAULT '0.00',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  `costprice` decimal(10,2) DEFAULT '0.00',
  `stock` int(11) DEFAULT '0',
  `weight` decimal(10,2) DEFAULT '0.00',
  `displayorder` int(11) DEFAULT '0',
  `specs` text,
  `skuId` varchar(255) DEFAULT '',
  `goodssn` varchar(255) DEFAULT '',
  `productsn` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_goodsid` (`goodsid`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_goods_param`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_goods_param`;
CREATE TABLE `ims_ewei_shop_goods_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `value` text,
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_goodsid` (`goodsid`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_goods_spec`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_goods_spec`;
CREATE TABLE `ims_ewei_shop_goods_spec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `goodsid` int(11) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `description` varchar(1000) DEFAULT '',
  `displaytype` tinyint(3) DEFAULT '0',
  `content` text,
  `displayorder` int(11) DEFAULT '0',
  `propId` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_goodsid` (`goodsid`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_goods_spec_item`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_goods_spec_item`;
CREATE TABLE `ims_ewei_shop_goods_spec_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `specid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `show` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `valueId` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_specid` (`specid`),
  KEY `idx_show` (`show`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_member`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_member`;
CREATE TABLE `ims_ewei_shop_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `uid` int(11) DEFAULT '0',
  `groupid` int(11) DEFAULT '0',
  `level` int(11) DEFAULT '0',
  `agentid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '',
  `realname` varchar(20) DEFAULT '',
  `mobile` varchar(11) DEFAULT '',
  `pwd` varchar(20) DEFAULT '',
  `weixin` varchar(100) DEFAULT '',
  `commission` decimal(10,2) DEFAULT '0.00',
  `commission_pay` decimal(10,2) DEFAULT '0.00',
  `content` text,
  `createtime` int(10) DEFAULT '0',
  `agenttime` int(10) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `isagent` tinyint(1) DEFAULT '0',
  `clickcount` int(11) DEFAULT '0',
  `agentlevel` int(11) DEFAULT '0',
  `noticeset` text,
  `nickname` varchar(255) DEFAULT '',
  `credit1` int(11) DEFAULT '0',
  `credit2` decimal(10,2) DEFAULT '0.00',
  `birthyear` varchar(255) DEFAULT '',
  `birthmonth` varchar(255) DEFAULT '',
  `birthday` varchar(255) DEFAULT '',
  `gender` tinyint(3) DEFAULT '0',
  `avatar` varchar(255) DEFAULT '',
  `province` varchar(255) DEFAULT '',
  `city` varchar(255) DEFAULT '',
  `area` varchar(255) DEFAULT '',
  `childtime` int(11) DEFAULT '0',
  `inviter` int(11) DEFAULT '0',
  `agentnotupgrade` tinyint(3) DEFAULT '0',
  `agentselectgoods` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_shareid` (`agentid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_status` (`status`),
  KEY `idx_agenttime` (`agenttime`),
  KEY `idx_isagent` (`isagent`),
  KEY `idx_uid` (`uid`),
  KEY `idx_groupid` (`groupid`),
  KEY `idx_level` (`level`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_member_address`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_member_address`;
CREATE TABLE `ims_ewei_shop_member_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '0',
  `realname` varchar(20) DEFAULT '',
  `mobile` varchar(11) DEFAULT '',
  `province` varchar(30) DEFAULT '',
  `city` varchar(30) DEFAULT '',
  `area` varchar(30) DEFAULT '',
  `address` varchar(300) DEFAULT '',
  `isdefault` tinyint(1) DEFAULT '0',
  `zipcode` varchar(255) DEFAULT '',
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_isdefault` (`isdefault`),
  KEY `idx_deleted` (`deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_member_cart`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_member_cart`;
CREATE TABLE `ims_ewei_shop_member_cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(100) DEFAULT '',
  `goodsid` int(11) DEFAULT '0',
  `total` int(11) DEFAULT '0',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  `deleted` tinyint(1) DEFAULT '0',
  `optionid` int(11) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_goodsid` (`goodsid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_deleted` (`deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_member_favorite`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_member_favorite`;
CREATE TABLE `ims_ewei_shop_member_favorite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `goodsid` int(10) DEFAULT '0',
  `openid` varchar(50) DEFAULT '',
  `deleted` tinyint(1) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_goodsid` (`goodsid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_deleted` (`deleted`),
  KEY `idx_createtime` (`createtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_member_group`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_member_group`;
CREATE TABLE `ims_ewei_shop_member_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `groupname` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_member_history`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_member_history`;
CREATE TABLE `ims_ewei_shop_member_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `goodsid` int(10) DEFAULT '0',
  `openid` varchar(50) DEFAULT '',
  `deleted` tinyint(1) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_goodsid` (`goodsid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_deleted` (`deleted`),
  KEY `idx_createtime` (`createtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_member_level`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_member_level`;
CREATE TABLE `ims_ewei_shop_member_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `level` int(11) DEFAULT '0',
  `levelname` varchar(50) DEFAULT '',
  `ordermoney` decimal(10,2) DEFAULT '0.00',
  `ordercount` int(10) DEFAULT '0',
  `discount` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_member_log`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_member_log`;
CREATE TABLE `ims_ewei_shop_member_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `type` tinyint(3) DEFAULT NULL COMMENT '0 充值 1 提现',
  `logno` varchar(255) DEFAULT '',
  `title` varchar(255) DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0' COMMENT '0 生成 1 成功 2 失败',
  `money` decimal(10,2) DEFAULT '0.00',
  `rechargetype` varchar(255) DEFAULT '' COMMENT '充值类型',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_type` (`type`),
  KEY `idx_createtime` (`createtime`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_member_message_template`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_member_message_template`;
CREATE TABLE `ims_ewei_shop_member_message_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `template_id` varchar(255) DEFAULT '',
  `first` text NOT NULL COMMENT '键名',
  `firstcolor` varchar(255) DEFAULT '',
  `data` text NOT NULL COMMENT '颜色',
  `remark` text NOT NULL COMMENT '键值',
  `remarkcolor` varchar(255) DEFAULT '',
  `url` varchar(255) NOT NULL,
  `createtime` int(11) DEFAULT '0',
  `sendtimes` int(11) DEFAULT '0',
  `sendcount` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_createtime` (`createtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_notice`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_notice`;
CREATE TABLE `ims_ewei_shop_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `detail` text,
  `status` tinyint(3) DEFAULT '0',
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_order`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_order`;
CREATE TABLE `ims_ewei_shop_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '',
  `agentid` int(11) DEFAULT '0',
  `ordersn` varchar(20) DEFAULT '',
  `price` decimal(10,2) DEFAULT '0.00',
  `goodsprice` decimal(10,2) DEFAULT '0.00',
  `discountprice` decimal(10,2) DEFAULT '0.00',
  `status` tinyint(4) DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功',
  `paytype` tinyint(1) DEFAULT '0' COMMENT '1为余额，2为在线，3为到付',
  `transid` varchar(30) DEFAULT '0' COMMENT '微信支付单号',
  `remark` varchar(1000) DEFAULT '',
  `addressid` int(11) DEFAULT '0',
  `dispatchprice` decimal(10,2) DEFAULT '0.00',
  `dispatchid` int(10) DEFAULT '0',
  `createtime` int(10) DEFAULT NULL,
  `dispatchtype` tinyint(3) DEFAULT '0',
  `carrier` text,
  `refundid` int(11) DEFAULT '0',
  `iscomment` tinyint(3) DEFAULT '0',
  `creditadd` tinyint(3) DEFAULT '0',
  `deleted` tinyint(3) DEFAULT '0',
  `userdeleted` tinyint(3) DEFAULT '0',
  `finishtime` int(11) DEFAULT '0',
  `paytime` int(11) DEFAULT '0',
  `expresscom` varchar(30) NOT NULL DEFAULT '',
  `expresssn` varchar(50) NOT NULL DEFAULT '',
  `express` varchar(255) DEFAULT '',
  `sendtime` int(11) DEFAULT '0',
  `fetchtime` int(11) DEFAULT '0',
  `cash` tinyint(3) DEFAULT '0',
  `canceltime` int(11) DEFAULT NULL,
  `cancelpaytime` int(11) DEFAULT '0',
  `refundtime` int(11) DEFAULT '0',
  `isverify` tinyint(3) DEFAULT '0',
  `verified` tinyint(3) DEFAULT '0',
  `verifyopenid` varchar(255) DEFAULT '',
  `verifycode` text,
  `verifytime` int(11) DEFAULT '0',
  `verifystoreid` int(11) DEFAULT '0',
  `deductprice` decimal(10,2) DEFAULT '0.00',
  `deductcredit` int(11) DEFAULT '0',
  `deductcredit2` decimal(10,2) DEFAULT '0.00',
  `deductenough` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_shareid` (`agentid`),
  KEY `idx_status` (`status`),
  KEY `idx_createtime` (`createtime`),
  KEY `idx_refundid` (`refundid`),
  KEY `idx_paytime` (`paytime`),
  KEY `idx_finishtime` (`finishtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_order_comment`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_order_comment`;
CREATE TABLE `ims_ewei_shop_order_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `orderid` int(11) DEFAULT '0',
  `goodsid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '',
  `nickname` varchar(50) DEFAULT '',
  `headimgurl` varchar(255) DEFAULT '',
  `level` tinyint(3) DEFAULT '0',
  `content` varchar(255) DEFAULT '',
  `images` text,
  `createtime` int(11) DEFAULT '0',
  `deleted` tinyint(3) DEFAULT '0',
  `append_content` varchar(255) DEFAULT '',
  `append_images` text,
  `reply_content` varchar(255) DEFAULT '',
  `reply_images` text,
  `append_reply_content` varchar(255) DEFAULT '',
  `append_reply_images` text,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_goodsid` (`goodsid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_createtime` (`createtime`),
  KEY `idx_orderid` (`orderid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_order_goods`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_order_goods`;
CREATE TABLE `ims_ewei_shop_order_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `orderid` int(11) DEFAULT '0',
  `goodsid` int(11) DEFAULT '0',
  `price` decimal(10,2) DEFAULT '0.00',
  `total` int(11) DEFAULT '1',
  `optionid` int(10) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `optionname` text,
  `commission1` text COMMENT '0',
  `applytime1` int(11) DEFAULT '0',
  `checktime1` int(10) DEFAULT '0',
  `paytime1` int(11) DEFAULT '0',
  `invalidtime1` int(11) DEFAULT '0',
  `deletetime1` int(11) DEFAULT '0',
  `status1` tinyint(3) DEFAULT '0' COMMENT '申请状态，-2删除，-1无效，0未申请，1申请，2审核通过 3已打款',
  `content1` text,
  `commission2` text,
  `applytime2` int(11) DEFAULT '0',
  `checktime2` int(10) DEFAULT '0',
  `paytime2` int(11) DEFAULT '0',
  `invalidtime2` int(11) DEFAULT '0',
  `deletetime2` int(11) DEFAULT '0',
  `status2` tinyint(3) DEFAULT '0' COMMENT '申请状态，-2删除，-1无效，0未申请，1申请，2审核通过 3已打款',
  `content2` text,
  `commission3` text,
  `applytime3` int(11) DEFAULT '0',
  `checktime3` int(10) DEFAULT '0',
  `paytime3` int(11) DEFAULT '0',
  `invalidtime3` int(11) DEFAULT '0',
  `deletetime3` int(11) DEFAULT '0',
  `status3` tinyint(3) DEFAULT '0' COMMENT '申请状态，-2删除，-1无效，0未申请，1申请，2审核通过 3已打款',
  `content3` text,
  `realprice` decimal(10,2) DEFAULT '0.00',
  `goodssn` varchar(255) DEFAULT '',
  `productsn` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_orderid` (`orderid`),
  KEY `idx_goodsid` (`goodsid`),
  KEY `idx_createtime` (`createtime`),
  KEY `idx_applytime1` (`applytime1`),
  KEY `idx_checktime1` (`checktime1`),
  KEY `idx_status1` (`status1`),
  KEY `idx_applytime2` (`applytime2`),
  KEY `idx_checktime2` (`checktime2`),
  KEY `idx_status2` (`status2`),
  KEY `idx_applytime3` (`applytime3`),
  KEY `idx_invalidtime1` (`invalidtime1`),
  KEY `idx_checktime3` (`checktime3`),
  KEY `idx_invalidtime2` (`invalidtime2`),
  KEY `idx_invalidtime3` (`invalidtime3`),
  KEY `idx_status3` (`status3`),
  KEY `idx_paytime1` (`paytime1`),
  KEY `idx_paytime2` (`paytime2`),
  KEY `idx_paytime3` (`paytime3`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_order_refund`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_order_refund`;
CREATE TABLE `ims_ewei_shop_order_refund` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `orderid` int(11) DEFAULT '0',
  `refundno` varchar(255) DEFAULT '',
  `price` varchar(255) DEFAULT '',
  `reason` varchar(255) DEFAULT '',
  `images` text,
  `content` text,
  `createtime` int(11) DEFAULT '0',
  `status` tinyint(3) DEFAULT '0' COMMENT '0申请 1 通过 2 驳回',
  `reply` text,
  `refundtype` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_createtime` (`createtime`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_perm_log`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_perm_log`;
CREATE TABLE `ims_ewei_shop_perm_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `name` varchar(255) DEFAULT '',
  `type` varchar(255) DEFAULT '',
  `op` text,
  `createtime` int(11) DEFAULT '0',
  `ip` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_createtime` (`createtime`),
  KEY `idx_uniacid` (`uniacid`),
  FULLTEXT KEY `idx_type` (`type`),
  FULLTEXT KEY `idx_op` (`op`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_perm_plugin`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_perm_plugin`;
CREATE TABLE `ims_ewei_shop_perm_plugin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acid` int(11) DEFAULT '0',
  `uid` int(11) DEFAULT '0',
  `type` tinyint(3) DEFAULT '0',
  `plugins` text,
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_acid` (`acid`),
  KEY `idx_type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_perm_role`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_perm_role`;
CREATE TABLE `ims_ewei_shop_perm_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rolename` varchar(255) DEFAULT '',
  `status` tinyint(3) DEFAULT '0',
  `perms` text,
  `deleted` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_status` (`status`),
  KEY `idx_deleted` (`deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_perm_user`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_perm_user`;
CREATE TABLE `ims_ewei_shop_perm_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `uid` int(11) DEFAULT '0',
  `username` varchar(255) DEFAULT '',
  `password` varchar(255) DEFAULT '',
  `roleid` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `perms` text,
  `deleted` tinyint(3) DEFAULT '0',
  `realname` varchar(255) DEFAULT '',
  `mobile` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_uid` (`uid`),
  KEY `idx_roleid` (`roleid`),
  KEY `idx_status` (`status`),
  KEY `idx_deleted` (`deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_plugin`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_plugin`;
CREATE TABLE `ims_ewei_shop_plugin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `displayorder` int(11) DEFAULT '0',
  `identity` varchar(50) DEFAULT '',
  `name` varchar(50) DEFAULT '',
  `version` varchar(10) DEFAULT '',
  `author` varchar(20) DEFAULT '',
  `status` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_displayorder` (`displayorder`),
  FULLTEXT KEY `idx_identity` (`identity`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_poster`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_poster`;
CREATE TABLE `ims_ewei_shop_poster` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `type` tinyint(3) DEFAULT '0' COMMENT '1 首页 2 小店 3 商城 4 自定义',
  `title` varchar(255) DEFAULT '',
  `bg` varchar(255) DEFAULT '',
  `data` text,
  `keyword` varchar(255) DEFAULT '',
  `times` int(11) DEFAULT '0',
  `follows` int(11) DEFAULT '0',
  `isdefault` tinyint(3) DEFAULT '0',
  `resptitle` varchar(255) DEFAULT '',
  `respthumb` varchar(255) DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  `respdesc` varchar(255) DEFAULT '',
  `respurl` varchar(255) DEFAULT '',
  `waittext` varchar(255) DEFAULT '',
  `oktext` varchar(255) DEFAULT '',
  `subcredit` int(11) DEFAULT '0',
  `submoney` decimal(10,2) DEFAULT '0.00',
  `reccredit` int(11) DEFAULT '0',
  `recmoney` decimal(10,2) DEFAULT '0.00',
  `paytype` tinyint(1) DEFAULT '0',
  `scantext` varchar(255) DEFAULT '',
  `subtext` varchar(255) DEFAULT '',
  `beagent` tinyint(3) DEFAULT '0',
  `bedown` tinyint(3) DEFAULT '0',
  `isopen` tinyint(3) DEFAULT '0',
  `opentext` varchar(255) DEFAULT '',
  `openurl` varchar(255) DEFAULT '',
  `templateid` varchar(255) DEFAULT '',
  `subpaycontent` text,
  `recpaycontent` text,
  `entrytext` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_type` (`type`),
  KEY `idx_times` (`times`),
  KEY `idx_isdefault` (`isdefault`),
  KEY `idx_createtime` (`createtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_poster_log`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_poster_log`;
CREATE TABLE `ims_ewei_shop_poster_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `posterid` int(11) DEFAULT '0',
  `from_openid` varchar(255) DEFAULT '',
  `subcredit` int(11) DEFAULT '0',
  `submoney` decimal(10,2) DEFAULT '0.00',
  `reccredit` int(11) DEFAULT '0',
  `recmoney` decimal(10,2) DEFAULT '0.00',
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_createtime` (`createtime`),
  KEY `idx_posterid` (`posterid`),
  FULLTEXT KEY `idx_from_openid` (`from_openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_poster_qr`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_poster_qr`;
CREATE TABLE `ims_ewei_shop_poster_qr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acid` int(10) unsigned NOT NULL,
  `openid` varchar(100) NOT NULL DEFAULT '',
  `type` tinyint(3) DEFAULT '0',
  `sceneid` int(11) DEFAULT '0',
  `mediaid` varchar(255) DEFAULT '',
  `ticket` varchar(250) NOT NULL,
  `url` varchar(80) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `goodsid` int(11) DEFAULT '0',
  `qrimg` varchar(1000) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_acid` (`acid`),
  KEY `idx_sceneid` (`sceneid`),
  KEY `idx_type` (`type`),
  FULLTEXT KEY `idx_openid` (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_poster_scan`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_poster_scan`;
CREATE TABLE `ims_ewei_shop_poster_scan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `posterid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `from_openid` varchar(255) DEFAULT '',
  `scantime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_posterid` (`posterid`),
  KEY `idx_scantime` (`scantime`),
  FULLTEXT KEY `idx_openid` (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_saler`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_saler`;
CREATE TABLE `ims_ewei_shop_saler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `storeid` int(11) DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `status` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_storeid` (`storeid`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_store`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_store`;
CREATE TABLE `ims_ewei_shop_store` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `storename` varchar(255) DEFAULT '',
  `address` varchar(255) DEFAULT '',
  `tel` varchar(255) DEFAULT '',
  `lat` varchar(255) DEFAULT '',
  `lng` varchar(255) DEFAULT '',
  `status` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_sysset`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_sysset`;
CREATE TABLE `ims_ewei_shop_sysset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `sets` text,
  `plugins` text,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_virtual_category`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_virtual_category`;
CREATE TABLE `ims_ewei_shop_virtual_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) DEFAULT NULL COMMENT '分类名称',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_virtual_data`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_virtual_data`;
CREATE TABLE `ims_ewei_shop_virtual_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `typeid` int(11) NOT NULL DEFAULT '0' COMMENT '类型id',
  `pvalue` varchar(255) DEFAULT '' COMMENT '主键键值',
  `fields` text NOT NULL COMMENT '字符集',
  `openid` varchar(255) NOT NULL DEFAULT '' COMMENT '使用者openid',
  `usetime` int(11) NOT NULL DEFAULT '0' COMMENT '使用时间',
  `orderid` int(11) DEFAULT '0',
  `ordersn` varchar(255) DEFAULT '',
  `price` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_typeid` (`typeid`),
  KEY `idx_usetime` (`usetime`),
  KEY `idx_orderid` (`orderid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_shop_virtual_type`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_virtual_type`;
CREATE TABLE `ims_ewei_shop_virtual_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `cate` int(11) DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '分类名称',
  `fields` text NOT NULL COMMENT '字段集',
  `usedata` int(11) NOT NULL DEFAULT '0' COMMENT '已用数据',
  `alldata` int(11) NOT NULL DEFAULT '0' COMMENT '全部数据',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_cate` (`cate`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_takephotoa_fans`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_takephotoa_fans`;
CREATE TABLE `ims_ewei_takephotoa_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '' COMMENT '用户openid',
  `nickname` varchar(255) DEFAULT '' COMMENT '用户昵称',
  `headimgurl` varchar(255) DEFAULT '' COMMENT '用户头像',
  `score` decimal(10,2) DEFAULT '0.00' COMMENT '平均',
  `img` varchar(255) DEFAULT '' COMMENT '成绩截图',
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_takephotoa_fans_score`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_takephotoa_fans_score`;
CREATE TABLE `ims_ewei_takephotoa_fans_score` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '' COMMENT '用户openid',
  `score` decimal(10,2) DEFAULT '0.00' COMMENT '平均',
  `createtime` int(10) DEFAULT '0',
  `img` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_takephotoa_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_takephotoa_reply`;
CREATE TABLE `ims_ewei_takephotoa_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `starttime` int(11) DEFAULT '0',
  `endtime` int(11) DEFAULT '0',
  `bgimg` varchar(255) DEFAULT '',
  `helpimg` varchar(255) DEFAULT '',
  `shareimg` varchar(255) DEFAULT '',
  `titleimg` varchar(255) DEFAULT '',
  `cameraimg` varchar(255) DEFAULT '',
  `numberimg` varchar(255) DEFAULT '',
  `items` text COMMENT '物品',
  `follow_url` varchar(1000) DEFAULT '',
  `share_url` varchar(1000) DEFAULT '',
  `viewnum` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `share_desc` varchar(500) DEFAULT '',
  `share_icon` varchar(255) DEFAULT '',
  `share_title` varchar(255) DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_rid` (`rid`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ewei_takephotoa_sysset`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_takephotoa_sysset`;
CREATE TABLE `ims_ewei_takephotoa_sysset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `oauth2` tinyint(1) DEFAULT '0',
  `appid` varchar(255) DEFAULT '',
  `appsecret` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_feng_goodscodes`
-- ----------------------------
DROP TABLE IF EXISTS `ims_feng_goodscodes`;
CREATE TABLE `ims_feng_goodscodes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众账号',
  `s_id` int(10) unsigned NOT NULL COMMENT '商品ID',
  `s_cid` smallint(5) unsigned NOT NULL,
  `s_len` smallint(5) DEFAULT NULL COMMENT '长度',
  `s_codes` longtext COMMENT '商品码',
  `s_codes_tmp` longtext COMMENT '商品码备份',
  PRIMARY KEY (`id`),
  KEY `s_id` (`s_id`),
  KEY `uniacid` (`uniacid`),
  KEY `s_len` (`s_len`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_feng_goodslist`
-- ----------------------------
DROP TABLE IF EXISTS `ims_feng_goodslist`;
CREATE TABLE `ims_feng_goodslist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号',
  `sid` int(10) unsigned NOT NULL COMMENT '同一个商品id',
  `title` varchar(100) DEFAULT NULL COMMENT '商品标题',
  `price` int(10) DEFAULT '0' COMMENT '金额',
  `zongrenshu` int(10) unsigned DEFAULT '0' COMMENT '总需人数',
  `canyurenshu` int(10) unsigned DEFAULT '0' COMMENT '已参与人数',
  `shengyurenshu` int(10) unsigned DEFAULT NULL COMMENT '剩余人数',
  `periods` smallint(6) unsigned DEFAULT '0' COMMENT '期数',
  `maxperiods` smallint(5) unsigned DEFAULT '1' COMMENT ' 最大期数',
  `picarr` text COMMENT '商品图片',
  `content` mediumtext COMMENT '商品详情',
  `createtime` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `pos` tinyint(4) unsigned DEFAULT NULL COMMENT '是否推荐',
  `status` int(11) NOT NULL COMMENT '1:下架, 2: 上架',
  `scale` int(10) unsigned DEFAULT NULL COMMENT '比例',
  `q_uid` varchar(10) DEFAULT NULL COMMENT '中奖人昵称',
  `q_user` varchar(50) DEFAULT NULL COMMENT '中奖人from_user',
  `q_user_code` char(20) DEFAULT NULL COMMENT '中奖码',
  `q_end_time` char(20) DEFAULT NULL COMMENT '揭晓时间',
  `send_state` int(4) unsigned NOT NULL COMMENT '1为已发货',
  `send` int(4) unsigned NOT NULL COMMENT '是否需要快递1为需要',
  `express` varchar(20) DEFAULT NULL COMMENT '快递公司',
  `expressn` char(20) DEFAULT NULL COMMENT '快递单',
  `send_time` char(20) DEFAULT NULL COMMENT '发货时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `sid` (`sid`),
  KEY `status` (`status`),
  KEY `shenyurenshu` (`shengyurenshu`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_feng_member`
-- ----------------------------
DROP TABLE IF EXISTS `ims_feng_member`;
CREATE TABLE `ims_feng_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号id',
  `from_user` varchar(50) NOT NULL COMMENT '微信会员openID',
  `realname` varchar(10) NOT NULL COMMENT '真实姓名',
  `nickname` varchar(20) NOT NULL COMMENT '昵称',
  `avatar` varchar(255) NOT NULL COMMENT '头像',
  `mobile` varchar(11) NOT NULL COMMENT '手机号码',
  `address` varchar(255) NOT NULL COMMENT '邮寄地址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_feng_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_feng_record`;
CREATE TABLE `ims_feng_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from_user` varchar(50) NOT NULL COMMENT '微信会员ID',
  `nickname` varchar(20) NOT NULL COMMENT '用户昵称',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号',
  `sid` int(10) unsigned NOT NULL COMMENT '商品编号',
  `ordersn` varchar(20) NOT NULL COMMENT '订单编号',
  `status` smallint(4) NOT NULL DEFAULT '0' COMMENT '0未支付，1为已付款',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额支付，2为支付宝，3为微信支付',
  `transid` varchar(30) NOT NULL COMMENT '微信订单号',
  `count` int(10) unsigned NOT NULL COMMENT '商品数量',
  `s_codes` longtext COMMENT '商品码',
  `createtime` int(10) unsigned NOT NULL COMMENT '购买时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `status` (`status`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_feng_wechat`
-- ----------------------------
DROP TABLE IF EXISTS `ims_feng_wechat`;
CREATE TABLE `ims_feng_wechat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `appid` varchar(100) DEFAULT NULL,
  `appsecret` varchar(200) DEFAULT NULL,
  `access_token` text,
  `lasttime` char(20) DEFAULT NULL,
  `share_title` varchar(200) DEFAULT NULL,
  `share_image` varchar(500) DEFAULT NULL,
  `share_desc` varchar(300) DEFAULT NULL,
  `win_mess` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_fighting`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fighting`;
CREATE TABLE `ims_fighting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `fid` int(10) unsigned NOT NULL,
  `answerNum` int(11) unsigned NOT NULL COMMENT '已经答题数量',
  `from_user` varchar(30) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `lasttime` int(10) unsigned NOT NULL,
  `lastcredit` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_fighting_dept`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fighting_dept`;
CREATE TABLE `ims_fighting_dept` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `deptName` varchar(100) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_fighting_level`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fighting_level`;
CREATE TABLE `ims_fighting_level` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL DEFAULT '0',
  `levelname` varchar(100) NOT NULL DEFAULT '' COMMENT '等级名称',
  `min` int(10) NOT NULL DEFAULT '0' COMMENT '开始积分',
  `max` int(10) NOT NULL DEFAULT '0' COMMENT '结束积分',
  `dateline` int(10) NOT NULL DEFAULT '0' COMMENT '发布日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_fighting_question_bank`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fighting_question_bank`;
CREATE TABLE `ims_fighting_question_bank` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `figure` int(30) NOT NULL,
  `question` varchar(500) NOT NULL,
  `option_num` int(10) unsigned NOT NULL,
  `optionA` varchar(100) NOT NULL,
  `optionB` varchar(100) NOT NULL,
  `optionC` varchar(100) NOT NULL,
  `optionD` varchar(100) NOT NULL,
  `optionE` varchar(100) NOT NULL,
  `optionF` varchar(100) NOT NULL,
  `answer` varchar(100) NOT NULL,
  `sid` int(10) unsigned NOT NULL COMMENT '广告URL',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_fighting_question_worng`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fighting_question_worng`;
CREATE TABLE `ims_fighting_question_worng` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `fightingid` int(10) unsigned NOT NULL,
  `qname` varchar(100) NOT NULL,
  `answer` varchar(100) NOT NULL,
  `optionA` varchar(100) NOT NULL,
  `optionB` varchar(100) NOT NULL,
  `optionC` varchar(100) NOT NULL,
  `optionD` varchar(100) NOT NULL,
  `optionE` varchar(100) NOT NULL,
  `optionF` varchar(100) NOT NULL,
  `wornganswer` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_fighting_setting`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fighting_setting`;
CREATE TABLE `ims_fighting_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL,
  `parentid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(20) NOT NULL COMMENT '活动标题',
  `description` longtext NOT NULL COMMENT '活动介绍',
  `tiao` tinyint(1) unsigned NOT NULL COMMENT '1允许跳过0不允许',
  `status_fighting` tinyint(1) unsigned NOT NULL COMMENT '0正常1暂停2结束',
  `qnum` int(11) unsigned NOT NULL COMMENT '题目数量',
  `answertime` int(10) unsigned NOT NULL COMMENT '答题时间',
  `start` int(10) unsigned NOT NULL DEFAULT '1383235200' COMMENT '开始时间',
  `end` int(10) unsigned NOT NULL DEFAULT '1383235200' COMMENT '结束时间',
  `most_num_times` int(11) DEFAULT '0',
  `picture` varchar(100) NOT NULL COMMENT '活动图片',
  `followurl` varchar(1000) DEFAULT '',
  `thumb` varchar(100) NOT NULL COMMENT '广告',
  `thumb_url` varchar(100) NOT NULL COMMENT '广告URL',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_fighting_user`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fighting_user`;
CREATE TABLE `ims_fighting_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `deptid` int(10) NOT NULL COMMENT '部门ID',
  `fid` int(10) unsigned NOT NULL COMMENT '活动ID',
  `nickname` varchar(100) NOT NULL COMMENT '活动ID',
  `mobile` varchar(100) NOT NULL COMMENT '手机号码',
  `openid` varchar(255) NOT NULL COMMENT '手机号码',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_fineness_adv`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fineness_adv`;
CREATE TABLE `ims_fineness_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `pcateid` int(11) DEFAULT '0',
  `link` varchar(255) DEFAULT '',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `pid` int(10) unsigned DEFAULT '0' COMMENT '父ID',
  `zanNum` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='幻灯片';

-- ----------------------------
--  Table structure for `ims_fineness_adv_er`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fineness_adv_er`;
CREATE TABLE `ims_fineness_adv_er` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '广告标题',
  `thumb` varchar(500) NOT NULL COMMENT '广告图片',
  `link` varchar(500) NOT NULL COMMENT '广告外链',
  `type` tinyint(1) unsigned NOT NULL COMMENT '0商品推广1推荐公众',
  `description` varchar(500) NOT NULL COMMENT '广告外链',
  `status` varchar(2) NOT NULL COMMENT '是否显示',
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='随机广告';

-- ----------------------------
--  Table structure for `ims_fineness_article`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fineness_article`;
CREATE TABLE `ims_fineness_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `musicurl` varchar(100) NOT NULL DEFAULT '' COMMENT '上传音乐',
  `content` mediumtext NOT NULL,
  `credit` varchar(255) DEFAULT '0',
  `pcate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '一级分类',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二级分类',
  `template` varchar(300) NOT NULL DEFAULT '' COMMENT '内容模板目录',
  `templatefile` varchar(300) NOT NULL DEFAULT '' COMMENT '分类模板名称',
  `bg_music_switch` varchar(1) NOT NULL DEFAULT '1',
  `clickNum` int(10) unsigned NOT NULL DEFAULT '0',
  `zanNum` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(500) NOT NULL DEFAULT '' COMMENT '缩略图',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '简介',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `outLink` varchar(500) DEFAULT '0' COMMENT '外链',
  `author` varchar(100) DEFAULT '' COMMENT '作者',
  `type` varchar(10) NOT NULL,
  `kid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `tel` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_fineness_article_category`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fineness_article_category`;
CREATE TABLE `ims_fineness_article_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `thumb` varchar(1024) NOT NULL DEFAULT '' COMMENT '分类图片',
  `kid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `type` varchar(10) NOT NULL,
  `description` varchar(100) NOT NULL DEFAULT '' COMMENT '分类描述',
  `template` varchar(300) NOT NULL DEFAULT '' COMMENT '分类模板目录',
  `templatefile` varchar(300) NOT NULL DEFAULT '' COMMENT '分类模板名称',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_fineness_comment`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fineness_comment`;
CREATE TABLE `ims_fineness_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `aid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章ID',
  `author` varchar(255) NOT NULL COMMENT '昵称',
  `openid` varchar(255) NOT NULL COMMENT '昵称',
  `thumb` varchar(500) NOT NULL COMMENT '头像',
  `js_cmt_input` varchar(500) NOT NULL COMMENT '留言内容',
  `js_cmt_reply` varchar(500) NOT NULL COMMENT '回复内容',
  `status` varchar(2) NOT NULL COMMENT '是否显示',
  `praise_num` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL,
  `updatetime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章评价';

-- ----------------------------
--  Table structure for `ims_fineness_sysset`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fineness_sysset`;
CREATE TABLE `ims_fineness_sysset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `guanzhuUrl` varchar(255) DEFAULT '' COMMENT '引导关注',
  `guanzhutitle` varchar(255) DEFAULT '' COMMENT '引导关注名称',
  `historyUrl` varchar(255) DEFAULT '' COMMENT '历史记录外链',
  `copyright` varchar(255) DEFAULT '' COMMENT '版权',
  `cnzz` varchar(800) DEFAULT '' COMMENT '统计',
  `appid` varchar(255) DEFAULT '',
  `logo` varchar(255) DEFAULT '',
  `footlogo` varchar(255) DEFAULT '',
  `appsecret` varchar(255) DEFAULT '',
  `appid_share` varchar(255) DEFAULT '',
  `isopen` varchar(1) DEFAULT '1',
  `title` varchar(255) DEFAULT '',
  `tjgzh` varchar(255) DEFAULT '1' COMMENT '推荐公众号图片',
  `tjgzhUrl` varchar(255) DEFAULT '1' COMMENT '推荐公众号引导关注',
  `appsecret_share` varchar(255) DEFAULT '',
  `iscomment` varchar(1) DEFAULT '1',
  `isget` varchar(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_fl_wsq_area`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fl_wsq_area`;
CREATE TABLE `ims_fl_wsq_area` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `fid` int(11) NOT NULL DEFAULT '0' COMMENT '父id为0为顶级',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '地区名称',
  `status` int(1) NOT NULL COMMENT '状态1显示0隐藏',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `weid` int(11) NOT NULL DEFAULT '0' COMMENT '公众号id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='微社区地区表';

-- ----------------------------
--  Table structure for `ims_fl_wsq_config`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fl_wsq_config`;
CREATE TABLE `ims_fl_wsq_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `weid` int(11) NOT NULL COMMENT '公众号id',
  `show_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '显示名称',
  `use_area` int(1) NOT NULL DEFAULT '0' COMMENT '使用地区分类',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='微社区配置文件';

-- ----------------------------
--  Table structure for `ims_fl_wsq_search_log`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fl_wsq_search_log`;
CREATE TABLE `ims_fl_wsq_search_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '搜索词',
  `create_time` int(11) NOT NULL COMMENT '搜索时间',
  `openid` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '搜索用户',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='微社区搜索记录';

-- ----------------------------
--  Table structure for `ims_fl_wsq_shoping`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fl_wsq_shoping`;
CREATE TABLE `ims_fl_wsq_shoping` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(255) NOT NULL COMMENT '商户名称',
  `tel` varchar(255) NOT NULL COMMENT '电话',
  `address` varchar(255) NOT NULL COMMENT '地址',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `status` int(1) NOT NULL COMMENT '状态1正常0关闭',
  `orders` int(11) NOT NULL DEFAULT '0' COMMENT '排序越大越前',
  `weid` int(11) NOT NULL DEFAULT '0' COMMENT '所属公众号id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='社区商户列表';

-- ----------------------------
--  Table structure for `ims_fl_wsq_shoping_reg`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fl_wsq_shoping_reg`;
CREATE TABLE `ims_fl_wsq_shoping_reg` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '商户名',
  `tel` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '店铺电话',
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '店铺地址',
  `create_time` int(11) NOT NULL COMMENT '申请时间',
  `openid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '申请者openid',
  `status` int(1) NOT NULL COMMENT '申请状态0未处理1审核通过2拒绝',
  `contact_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '联系人姓名',
  `contact_tel` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '联系人电话',
  `content` text COLLATE utf8_unicode_ci NOT NULL COMMENT '申请理由',
  `weid` int(11) NOT NULL COMMENT '公众号id',
  `tid` int(11) DEFAULT NULL COMMENT '分类id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='商户申请表';

-- ----------------------------
--  Table structure for `ims_fl_wsq_shoping_type`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fl_wsq_shoping_type`;
CREATE TABLE `ims_fl_wsq_shoping_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '分类名称',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `images` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '分类封面',
  `weid` int(11) NOT NULL COMMENT '公众号id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='商户分类表';

-- ----------------------------
--  Table structure for `ims_fl_wsq_shoping_type_bind`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fl_wsq_shoping_type_bind`;
CREATE TABLE `ims_fl_wsq_shoping_type_bind` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `tid` int(11) NOT NULL COMMENT '分类id',
  `sid` int(11) NOT NULL COMMENT '商铺id',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商铺分类绑定表';

-- ----------------------------
--  Table structure for `ims_fl_wsq_slide`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fl_wsq_slide`;
CREATE TABLE `ims_fl_wsq_slide` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '幻灯片标题',
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '幻灯片图片',
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '链接地址',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `status` int(1) NOT NULL COMMENT '状态1正常0关闭',
  `weid` int(11) NOT NULL COMMENT '公众号id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='幻灯片管理';

-- ----------------------------
--  Table structure for `ims_fm_photosvote_advs`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fm_photosvote_advs`;
CREATE TABLE `ims_fm_photosvote_advs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `uniacid` int(10) unsigned NOT NULL,
  `rid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `description` varchar(350) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `times` int(11) DEFAULT '0',
  `ismiaoxian` int(2) DEFAULT '0',
  `issuiji` int(2) DEFAULT '0',
  `nexttime` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_fm_photosvote_announce`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fm_photosvote_announce`;
CREATE TABLE `ims_fm_photosvote_announce` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `content` varchar(150) NOT NULL DEFAULT '' COMMENT '公告',
  `nickname` varchar(100) NOT NULL DEFAULT '' COMMENT '公告',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '公告链接',
  `createtime` int(10) unsigned NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_createtime` (`createtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_fm_photosvote_awarding`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fm_photosvote_awarding`;
CREATE TABLE `ims_fm_photosvote_awarding` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `uniacid` int(10) unsigned NOT NULL,
  `typeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '区域ID',
  `shoptitle` varchar(50) NOT NULL DEFAULT '' COMMENT '兑奖店面名称',
  `address` varchar(200) NOT NULL DEFAULT '' COMMENT '兑奖地址',
  `tel` varchar(50) NOT NULL DEFAULT '' COMMENT '联系电话',
  `pass` varchar(20) NOT NULL DEFAULT '' COMMENT '兑奖密码',
  `images` varchar(200) NOT NULL DEFAULT '' COMMENT '广告或店面图',
  `carmap` varchar(50) NOT NULL COMMENT '地图导航',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_fm_photosvote_awardingtype`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fm_photosvote_awardingtype`;
CREATE TABLE `ims_fm_photosvote_awardingtype` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `uniacid` int(10) unsigned NOT NULL,
  `quyutitle` varchar(50) NOT NULL DEFAULT '' COMMENT '分类名称',
  `orderid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_fm_photosvote_banners`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fm_photosvote_banners`;
CREATE TABLE `ims_fm_photosvote_banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `uniacid` int(10) unsigned NOT NULL,
  `rid` int(11) DEFAULT '0',
  `bannername` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_fm_photosvote_bbsreply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fm_photosvote_bbsreply`;
CREATE TABLE `ims_fm_photosvote_bbsreply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `uniacid` int(10) unsigned NOT NULL,
  `avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `tid` varchar(125) NOT NULL COMMENT '帖子的ID',
  `tfrom_user` varchar(255) NOT NULL DEFAULT '' COMMENT '帖子作者的openid',
  `reply_id` varchar(125) NOT NULL COMMENT '回复评论帖子的ID',
  `from_user` varchar(255) NOT NULL DEFAULT '' COMMENT '回复评论帖子的openid',
  `to_reply_id` int(11) NOT NULL DEFAULT '0' COMMENT '回复评论的id',
  `rfrom_user` varchar(255) NOT NULL DEFAULT '' COMMENT '被回复的评论的作者的openid',
  `content` text NOT NULL COMMENT '评论回复内容',
  `is_del` tinyint(2) DEFAULT '0' COMMENT '是否已删除 0-否 1-是',
  `status` tinyint(2) DEFAULT '0' COMMENT '是否审核 0-否 1-是',
  `storey` int(11) NOT NULL DEFAULT '0' COMMENT '绝对楼层',
  `ip` varchar(255) NOT NULL DEFAULT '' COMMENT '回复IP',
  `iparr` varchar(200) NOT NULL DEFAULT '' COMMENT 'IP区域',
  `createtime` int(11) NOT NULL COMMENT '回复时间',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_rid` (`rid`),
  KEY `indx_createtime` (`createtime`),
  KEY `indx_from_user` (`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_fm_photosvote_data`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fm_photosvote_data`;
CREATE TABLE `ims_fm_photosvote_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `uniacid` int(10) unsigned NOT NULL,
  `fromuser` varchar(150) NOT NULL DEFAULT '' COMMENT '分享用户openid',
  `from_user` varchar(150) NOT NULL DEFAULT '' COMMENT '当前用户openid',
  `tfrom_user` varchar(150) NOT NULL DEFAULT '' COMMENT '被分享用户openid',
  `avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享人UID',
  `isin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否参与',
  `visitorsip` varchar(15) NOT NULL DEFAULT '' COMMENT '访问IP',
  `visitorstime` int(10) unsigned NOT NULL COMMENT '访问时间',
  `viewnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看次数',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uid` (`uid`),
  KEY `indx_from_user` (`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_fm_photosvote_gift`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fm_photosvote_gift`;
CREATE TABLE `ims_fm_photosvote_gift` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL COMMENT '奖品名称',
  `total` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '数量',
  `total_winning` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '中奖数量',
  `lingjiangtype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '奖品库存减少方式0为有资格1为提交2为兑奖',
  `description` text NOT NULL COMMENT '描述',
  `inkind` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否是实物',
  `activation_code` varchar(50) NOT NULL COMMENT '激活码',
  `activation_url` varchar(215) NOT NULL COMMENT '激活地址',
  `break` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '需要朋友人数',
  `awardpic` varchar(200) NOT NULL COMMENT '奖品图片',
  `awardpass` varchar(20) NOT NULL COMMENT '兑奖密码',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_fm_photosvote_giftmika`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fm_photosvote_giftmika`;
CREATE TABLE `ims_fm_photosvote_giftmika` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL,
  `giftid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '礼盒ID',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid',
  `mika` varchar(50) NOT NULL COMMENT '密卡字符串',
  `activationurl` varchar(200) NOT NULL COMMENT '激活地址',
  `typename` varchar(20) NOT NULL DEFAULT '' COMMENT '类型说明',
  `description` varchar(50) NOT NULL DEFAULT '' COMMENT '描述',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否领取1为领取过',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_fm_photosvote_iplist`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fm_photosvote_iplist`;
CREATE TABLE `ims_fm_photosvote_iplist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `iparr` varchar(200) NOT NULL DEFAULT '' COMMENT 'IP区域',
  `ipadd` varchar(100) NOT NULL DEFAULT '' COMMENT 'IP区域',
  `createtime` int(10) unsigned NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_rid` (`rid`),
  KEY `indx_createtime` (`createtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_fm_photosvote_iplistlog`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fm_photosvote_iplistlog`;
CREATE TABLE `ims_fm_photosvote_iplistlog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `from_user` varchar(255) NOT NULL DEFAULT '' COMMENT 'openid',
  `ip` varchar(255) NOT NULL DEFAULT '' COMMENT 'IP',
  `hitym` varchar(255) NOT NULL DEFAULT '' COMMENT '点击页面',
  `createtime` int(11) NOT NULL COMMENT '初始时间',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_rid` (`rid`),
  KEY `indx_createtime` (`createtime`),
  KEY `indx_from_user` (`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_fm_photosvote_provevote`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fm_photosvote_provevote`;
CREATE TABLE `ims_fm_photosvote_provevote` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `uniacid` int(10) unsigned NOT NULL,
  `from_user` varchar(255) NOT NULL DEFAULT '' COMMENT '用户openid',
  `tfrom_user` varchar(255) NOT NULL DEFAULT '' COMMENT '被投票用户openid',
  `avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '微信头像',
  `photo` varchar(200) NOT NULL DEFAULT '' COMMENT '照片',
  `music` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `mediaid` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐id',
  `timelength` varchar(200) NOT NULL DEFAULT '' COMMENT '时间轴',
  `voice` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `vedio` varchar(200) NOT NULL DEFAULT '' COMMENT '视频',
  `youkuurl` varchar(200) NOT NULL DEFAULT '' COMMENT '视频',
  `fmmid` varchar(200) NOT NULL DEFAULT '' COMMENT '识别',
  `picarr` varchar(2000) DEFAULT '',
  `picarr_1` varchar(200) NOT NULL DEFAULT '' COMMENT '照片组',
  `picarr_2` varchar(200) NOT NULL DEFAULT '' COMMENT '照片组',
  `picarr_3` varchar(200) NOT NULL DEFAULT '' COMMENT '照片组',
  `picarr_4` varchar(200) NOT NULL DEFAULT '' COMMENT '照片组',
  `picarr_5` varchar(200) NOT NULL DEFAULT '' COMMENT '照片组',
  `picarr_6` varchar(200) NOT NULL DEFAULT '' COMMENT '照片组',
  `picarr_7` varchar(200) NOT NULL DEFAULT '' COMMENT '照片组',
  `picarr_8` varchar(200) NOT NULL DEFAULT '' COMMENT '照片组',
  `description` varchar(512) NOT NULL DEFAULT '' COMMENT '简介，描述',
  `photoname` varchar(50) NOT NULL DEFAULT '' COMMENT '照片名字',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `realname` varchar(20) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `job` varchar(20) NOT NULL DEFAULT '' COMMENT '职业',
  `xingqu` varchar(20) NOT NULL DEFAULT '' COMMENT '兴趣',
  `weixin` varchar(255) NOT NULL DEFAULT '' COMMENT '联系微信号',
  `qqhao` varchar(20) NOT NULL DEFAULT '' COMMENT '联系QQ号码',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT '联系邮箱',
  `address` varchar(100) NOT NULL DEFAULT '' COMMENT '联系地址',
  `photosnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '票数',
  `xnphotosnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '虚拟票数',
  `hits` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '人气',
  `xnhits` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '虚拟人气',
  `yaoqingnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '邀请量',
  `ewm` varchar(200) NOT NULL DEFAULT '' COMMENT '二维码地址',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '审核状态',
  `createip` varchar(50) NOT NULL DEFAULT '' COMMENT '创建IP',
  `lastip` varchar(50) NOT NULL DEFAULT '' COMMENT '编辑IP',
  `iparr` varchar(200) NOT NULL DEFAULT '' COMMENT 'ip地区',
  `lasttime` int(10) unsigned NOT NULL COMMENT '最后编辑时间',
  `sharetime` int(10) unsigned NOT NULL COMMENT '最后分享时间',
  `sharenum` int(10) unsigned NOT NULL COMMENT '最后分享',
  `createtime` int(10) unsigned NOT NULL COMMENT '注册时间',
  `ysid` int(10) unsigned NOT NULL COMMENT 'ysid',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '性别，1、男 2、女 0 、未知',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_createtime` (`createtime`),
  KEY `indx_from_user` (`from_user`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_fm_photosvote_provevote_name`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fm_photosvote_provevote_name`;
CREATE TABLE `ims_fm_photosvote_provevote_name` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL,
  `from_user` varchar(255) NOT NULL DEFAULT '' COMMENT '用户openid',
  `musicname` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `photoname` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `picarr_1_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `picarr_2_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `picarr_3_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `picarr_4_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `picarr_5_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `picarr_6_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `picarr_7_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `picarr_8_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `musicnamefop` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `voicename` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `voicenamefop` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `vedioname` varchar(200) NOT NULL DEFAULT '' COMMENT '视频',
  `vedionamefop` varchar(200) NOT NULL DEFAULT '' COMMENT '视频',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_rid` (`rid`),
  KEY `indx_from_user` (`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_fm_photosvote_provevote_voice`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fm_photosvote_provevote_voice`;
CREATE TABLE `ims_fm_photosvote_provevote_voice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `from_user` varchar(555) NOT NULL DEFAULT '' COMMENT 'openid',
  `mediaid` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐id',
  `timelength` varchar(200) NOT NULL DEFAULT '' COMMENT '时间轴',
  `voice` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `fmmid` varchar(200) NOT NULL DEFAULT '' COMMENT '识别',
  `ip` varchar(255) NOT NULL DEFAULT '' COMMENT 'IP',
  `createtime` int(11) NOT NULL COMMENT '初始时间',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_createtime` (`createtime`),
  KEY `indx_from_user` (`from_user`(333)),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_fm_photosvote_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fm_photosvote_reply`;
CREATE TABLE `ims_fm_photosvote_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL COMMENT '规则标题',
  `shareurl` varchar(255) NOT NULL COMMENT '活动网址',
  `sharetitle` varchar(50) NOT NULL COMMENT '分享标题',
  `sharecontent` varchar(100) NOT NULL COMMENT '分享简介',
  `picture` varchar(225) NOT NULL COMMENT '规则图片',
  `sharephoto` varchar(225) NOT NULL COMMENT 'fx图片',
  `stopping` varchar(225) NOT NULL COMMENT 'fx图片',
  `nostart` varchar(225) NOT NULL COMMENT 'fx图片',
  `end` varchar(225) NOT NULL COMMENT 'fx图片',
  `start_time` int(10) unsigned NOT NULL COMMENT '开始时间',
  `end_time` int(10) unsigned NOT NULL COMMENT '结束时间',
  `tstart_time` int(10) unsigned NOT NULL COMMENT '投票开始时间',
  `tend_time` int(10) unsigned NOT NULL COMMENT '投票结束时间',
  `bstart_time` int(10) unsigned NOT NULL COMMENT '报名开始时间',
  `bend_time` int(10) unsigned NOT NULL COMMENT '报名结束时间',
  `ttipstart` varchar(255) NOT NULL COMMENT '投票开始时间',
  `ttipend` varchar(255) NOT NULL COMMENT '投票结束时间',
  `btipstart` varchar(255) NOT NULL COMMENT '报名开始时间',
  `btipend` varchar(255) NOT NULL COMMENT '报名结束时间',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `command` varchar(10) NOT NULL COMMENT '报名命令',
  `ckcommand` varchar(255) NOT NULL COMMENT '命令',
  `content` text NOT NULL COMMENT '内容',
  `tj` text NOT NULL COMMENT '站长统计代码',
  `moshi` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '展示模式： 1 相册模式  2 详情模式',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '开关状态',
  `addpv` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启添加投稿',
  `isbbsreply` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启评论',
  `cqtp` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否可重复投票',
  `tpsh` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '投稿是否需审核',
  `indexpx` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '活动首页显示,0 按最新排序 1 按人气排序 3 按投票数排序',
  `tpxz` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '投稿照片数限制',
  `daytpxz` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '每日投票数限制',
  `dayonetp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '同一选手投票数限制',
  `allonetp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '同一选手最高投票数',
  `fansmostvote` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户最高投票数',
  `indextpxz` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '首页列表显示数',
  `phbtpxz` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '人气榜显示个数',
  `autolitpic` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '裁剪大小',
  `autozl` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '裁剪质量',
  `hits` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击量',
  `xuninum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '虚拟人数',
  `xuninumtime` int(10) unsigned NOT NULL DEFAULT '86400' COMMENT '虚拟间隔时间',
  `xuninuminitial` int(10) unsigned NOT NULL DEFAULT '10' COMMENT '虚拟随机数值1',
  `xuninumending` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '虚拟随机数值2',
  `xuninum_time` int(10) unsigned NOT NULL COMMENT '虚拟更新时间',
  `isvisits` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否互访0为不可以1为可以',
  `subscribe` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否强制需要关注公众号才能参与',
  `opensubscribe` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要参与活动才算人气0为不需要1为需要',
  `share_shownum` int(3) unsigned NOT NULL DEFAULT '10' COMMENT '加载一次显示多少参与者',
  `userinfo` varchar(200) NOT NULL COMMENT '输入姓名或手机时的提示词',
  `isindex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否首页显示0为不需要1为需要',
  `isvotexq` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否详情页显示0为不需要1为需要',
  `ispaihang` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否排行页显示0为不需要1为需要',
  `isreg` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否报名页显示0为不需要1为需要',
  `isdes` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否描述页显示0为不需要1为需要',
  `isrealname` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入姓名0为不需要1为需要',
  `ismobile` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入手机号0为不需要1为需要',
  `isweixin` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入微信号0为不需要1为需要',
  `isqqhao` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入QQ号0为不需要1为需要',
  `isemail` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入邮箱0为不需要1为需要',
  `isaddress` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入地址0为不需要1为需要',
  `isjob` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职业0为不需要1为需要',
  `isxingqu` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入兴趣0为不需要1为需要',
  `isfans` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0只保存本模块下1同步更新至官方FANS表',
  `iscopyright` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0显示公众号版权1为显示自定义版权',
  `copyrighturl` varchar(255) NOT NULL COMMENT '版权链接',
  `copyright` varchar(50) NOT NULL COMMENT '版权',
  `zbgcolor` varchar(50) NOT NULL COMMENT '背景色',
  `zbg` varchar(255) NOT NULL COMMENT '背景图',
  `zbgtj` varchar(255) NOT NULL COMMENT '背景图',
  `lapiao` varchar(5) NOT NULL COMMENT '拉票',
  `sharename` varchar(2) NOT NULL COMMENT '分享',
  `ishuodong` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0',
  `mtemplates` varchar(500) NOT NULL COMMENT '模板ID',
  `huodong` varchar(500) NOT NULL COMMENT '活动',
  `hhhdpicture` varchar(255) NOT NULL COMMENT '活动图片',
  `messagetemplate` varchar(255) NOT NULL COMMENT '投票消息模板id 微信的模板id',
  `regmessagetemplate` varchar(255) NOT NULL COMMENT '报名消息模板id 微信的模板id',
  `shmessagetemplate` varchar(255) NOT NULL COMMENT '报名消息模板id 微信的模板id',
  `addpvapp` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '前端是否允许用户报名',
  `iscode` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启投票验证码',
  `codekey` varchar(255) NOT NULL COMMENT '验证码key',
  `isedes` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启首页显示说明',
  `tmreply` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '弹幕评论是否同步到数据库',
  `tmyushe` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '弹幕评论是否同步到数据库',
  `isipv` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启IP作弊限制',
  `ipturl` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '存在作弊ip后是否继续允许查看，投票，评论等',
  `ipstopvote` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '存在作弊ip后是否继续允许查看，投票，评论等',
  `ipannounce` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启公告',
  `tmoshi` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '首页显示模式',
  `mediatype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '上传模式',
  `mediatypem` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '上传模式',
  `mediatypev` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '上传模式',
  `bgarr` varchar(1000) NOT NULL COMMENT '颜色及背景配置',
  `tpname` varchar(100) NOT NULL COMMENT '投票名称',
  `rqname` varchar(100) NOT NULL COMMENT '人气名称',
  `tpsname` varchar(100) NOT NULL COMMENT '投票数名称',
  `votesuccess` varchar(200) NOT NULL COMMENT '投票成功提示语',
  `subscribedes` varchar(200) NOT NULL COMMENT '分享提示语',
  `csrs` varchar(10) NOT NULL COMMENT '参赛作品',
  `ljtp` varchar(10) NOT NULL COMMENT '累计投票',
  `cyrs` varchar(10) NOT NULL COMMENT '参与人数',
  `voicebg` varchar(200) NOT NULL COMMENT '录音室背景',
  `qiniu` varchar(600) NOT NULL COMMENT '七牛',
  `voicemoshi` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '语音室模式',
  `isdaojishi` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '倒计时',
  `votetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户投票时间',
  `limitip` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '投票ip每天限制数',
  `indexorder` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '首页排序',
  `ttipvote` varchar(100) NOT NULL COMMENT '用户投票时间结束提示语',
  `webinfo` text NOT NULL COMMENT '内容',
  `istopheader` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '最上方',
  `zanzhums` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '赞助商显示',
  `istop` varchar(300) NOT NULL COMMENT '顶部设置',
  `isid` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'isid',
  `iplocallimit` varchar(100) NOT NULL COMMENT '地区限制',
  `iplocaldes` varchar(100) NOT NULL COMMENT '地区限制',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_fm_photosvote_votelog`
-- ----------------------------
DROP TABLE IF EXISTS `ims_fm_photosvote_votelog`;
CREATE TABLE `ims_fm_photosvote_votelog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `uniacid` int(10) unsigned NOT NULL,
  `tptype` int(10) unsigned NOT NULL COMMENT '投票类型 1 微信页面投票  2 微信会话界面',
  `from_user` varchar(255) NOT NULL DEFAULT '' COMMENT '用户openid',
  `tfrom_user` varchar(255) NOT NULL DEFAULT '' COMMENT '被投票用户openid',
  `afrom_user` varchar(255) NOT NULL DEFAULT '' COMMENT '分享用户openid',
  `avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `ip` varchar(50) NOT NULL DEFAULT '' COMMENT '投票IP',
  `iparr` varchar(200) NOT NULL DEFAULT '' COMMENT 'ip地区',
  `photosnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '票数',
  `createtime` int(10) unsigned NOT NULL COMMENT '投票时间',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_rid` (`rid`),
  KEY `indx_createtime` (`createtime`),
  KEY `indx_from_user` (`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hc_ybdzs_setting`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hc_ybdzs_setting`;
CREATE TABLE `ims_hc_ybdzs_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `hc_ybdzs_title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `hc_ybdzs_url` varchar(200) CHARACTER SET utf8 NOT NULL,
  `share_title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `share_desc` varchar(100) CHARACTER SET utf8 NOT NULL,
  `wechat` varchar(100) CHARACTER SET utf8 NOT NULL,
  `photo` varchar(100) CHARACTER SET utf8 NOT NULL,
  `counts` varchar(500) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `ims_hc_zqttt_setting`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hc_zqttt_setting`;
CREATE TABLE `ims_hc_zqttt_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `hc_zqttt_title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `hc_zqttt_url` varchar(200) CHARACTER SET utf8 NOT NULL,
  `share_title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `share_desc` varchar(100) CHARACTER SET utf8 NOT NULL,
  `photo` varchar(100) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `ims_heka_list`
-- ----------------------------
DROP TABLE IF EXISTS `ims_heka_list`;
CREATE TABLE `ims_heka_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `title` varchar(50) DEFAULT NULL,
  `card` varchar(20) NOT NULL COMMENT '活动图片',
  `author` varchar(20) DEFAULT NULL,
  `content` varchar(500) NOT NULL COMMENT '活动描述',
  `cardName` varchar(50) DEFAULT NULL,
  `from_user` varchar(50) DEFAULT NULL,
  `hits` int(11) DEFAULT NULL,
  `share` int(11) DEFAULT NULL,
  `create_time` int(10) NOT NULL COMMENT '规则',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_heka_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_heka_reply`;
CREATE TABLE `ims_heka_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `title` varchar(50) DEFAULT NULL,
  `picture` varchar(100) NOT NULL COMMENT '活动图片',
  `description` varchar(200) NOT NULL COMMENT '活动描述',
  `create_time` int(10) NOT NULL COMMENT '规则',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hlzonyu_data`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hlzonyu_data`;
CREATE TABLE `ims_hlzonyu_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid',
  `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '用户头像',
  `realname` varchar(50) NOT NULL DEFAULT '' COMMENT '点赞人姓名',
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享人UID',
  `zonyuip` varchar(15) NOT NULL DEFAULT '' COMMENT '集赞人IP',
  `zonyutime` int(10) unsigned NOT NULL COMMENT '集赞时间',
  `viewnum` int(10) NOT NULL DEFAULT '1' COMMENT '查看次数',
  `content` varchar(512) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hlzonyu_list`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hlzonyu_list`;
CREATE TABLE `ims_hlzonyu_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid',
  `zonyunum` int(10) NOT NULL DEFAULT '0' COMMENT '分享量',
  `ndrank` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '内定排名',
  `ndranknum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '内定分享量',
  `ndranknums` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '内定增加量',
  `zhongjiang` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否中奖',
  `zonyutime` int(10) unsigned NOT NULL COMMENT '最后分享时间',
  `createtime` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否禁止',
  `btype` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '类型',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hlzonyu_log`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hlzonyu_log`;
CREATE TABLE `ims_hlzonyu_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1积分，2金额',
  `credit` int(10) NOT NULL DEFAULT '0' COMMENT '分值或金额',
  `nametype` varchar(50) NOT NULL COMMENT '类型',
  `name` varchar(50) NOT NULL COMMENT '类型名称',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `createtime` int(10) unsigned NOT NULL,
  `ip` varchar(20) NOT NULL DEFAULT '' COMMENT 'IP地址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hlzonyu_order`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hlzonyu_order`;
CREATE TABLE `ims_hlzonyu_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `ordersn` varchar(20) NOT NULL,
  `price` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功',
  `sendtype` tinyint(1) unsigned NOT NULL COMMENT '1为快递，2为自提',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付',
  `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号',
  `goodstype` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `remark` varchar(1000) NOT NULL DEFAULT '',
  `rid` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `address` varchar(1000) NOT NULL DEFAULT '',
  `goodsprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `goodsname` varchar(100) NOT NULL DEFAULT '',
  `tjname` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hlzonyu_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hlzonyu_reply`;
CREATE TABLE `ims_hlzonyu_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `title` varchar(50) NOT NULL COMMENT '规则标题',
  `zonyuurl` varchar(255) NOT NULL COMMENT '活动网址',
  `picture` varchar(100) NOT NULL COMMENT '图片',
  `start_time` int(10) unsigned NOT NULL COMMENT '开始时间',
  `end_time` int(10) unsigned NOT NULL COMMENT '结束时间',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `content` text NOT NULL COMMENT '内容',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '开关状态',
  `credit` int(10) NOT NULL DEFAULT '0' COMMENT '奖励最小积分',
  `creditx` int(10) NOT NULL DEFAULT '0' COMMENT '奖励最大积分',
  `zonyunum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '集多少赞奖励',
  `email` varchar(100) NOT NULL COMMENT '通知邮箱',
  `zhongjiang` varchar(200) NOT NULL COMMENT '中奖提醒词',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '活动类型0为分享、1为集赞',
  `dingpic` varchar(512) NOT NULL COMMENT '顶部图片',
  `zanpic` varchar(512) NOT NULL COMMENT '点赞图片',
  `shangjia` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示赞助商',
  `shangjianame` varchar(50) NOT NULL COMMENT '赞助商名称',
  `shangjiapic` varchar(512) NOT NULL COMMENT '赞助商图片展示',
  `shangjiatel` varchar(50) NOT NULL COMMENT '赞助商联系电话',
  `shangjiaaddress` varchar(90) NOT NULL COMMENT '赞助商联系地址',
  `shangjiamap` varchar(50) NOT NULL COMMENT '赞助商地图导航',
  `shangjialink` varchar(250) NOT NULL COMMENT '赞助商链接',
  `ndrankstatus` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启内定排名',
  `ndrankstatusnum` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '内定前几排名',
  `zonyu_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '点赞方式',
  `zonyu_show` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '显示方式投赞者',
  `zonyu_imgtext` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '显示头像或昵称',
  `zonyu_rankshow` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '显示方式排名',
  `zonyu_shownum` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '显示多少投赞者',
  `zonyu_ranknum` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排名显示多少位',
  `zonyu_numtype` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否可以重复累计',
  `zonyu_num` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '点赞周期',
  `btype` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `sharetitle` varchar(200) NOT NULL COMMENT '分享标题',
  `productprice` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hl_periarthritis`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hl_periarthritis`;
CREATE TABLE `ims_hl_periarthritis` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `weid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL DEFAULT '',
  `shaketimes` int(10) unsigned NOT NULL,
  `content` varchar(1000) NOT NULL DEFAULT '',
  `picture` varchar(255) NOT NULL COMMENT '活动图片',
  `gzurl` varchar(255) NOT NULL COMMENT '关注URL',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hnblacklist`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hnblacklist`;
CREATE TABLE `ims_hnblacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `wantblack` varchar(255) NOT NULL,
  `blackwho` varchar(255) NOT NULL,
  `time` int(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hnfans`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hnfans`;
CREATE TABLE `ims_hnfans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `from_user` varchar(50) NOT NULL COMMENT '用户的唯一身份ID',
  `createtime` int(10) unsigned NOT NULL COMMENT '加入时间',
  `realname` varchar(10) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `nickname` varchar(20) NOT NULL DEFAULT '' COMMENT '昵称',
  `avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '头像',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号码',
  `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别(0:保密 1:男 2:女)',
  `constellation` varchar(10) NOT NULL DEFAULT '' COMMENT '星座',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '固定电话',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '邮寄地址',
  `nationality` varchar(30) NOT NULL DEFAULT '' COMMENT '国籍',
  `resideprovincecity` varchar(30) NOT NULL DEFAULT '' COMMENT '居住省市',
  `residedist` varchar(30) NOT NULL DEFAULT '' COMMENT '居住行政区/县',
  `education` varchar(10) NOT NULL DEFAULT '' COMMENT '学历',
  `occupation` varchar(30) NOT NULL DEFAULT '' COMMENT '职业',
  `position` varchar(30) NOT NULL DEFAULT '' COMMENT '职位',
  `revenue` varchar(10) NOT NULL DEFAULT '' COMMENT '年收入',
  `affectivestatus` varchar(30) NOT NULL DEFAULT '' COMMENT '情感状态',
  `lookingfor` varchar(255) NOT NULL DEFAULT '' COMMENT ' 交友目的',
  `height` varchar(5) NOT NULL DEFAULT '' COMMENT '身高',
  `weight` varchar(5) NOT NULL DEFAULT '' COMMENT '体重',
  `interest` text NOT NULL COMMENT '兴趣爱好',
  `lxxingzuo` varchar(200) NOT NULL DEFAULT '' COMMENT '理想星座',
  `housestatus` varchar(20) NOT NULL DEFAULT '' COMMENT '是否有房',
  `carstatus` varchar(20) NOT NULL DEFAULT '' COMMENT '是否有车',
  `lat` varchar(20) NOT NULL DEFAULT '' COMMENT '经度',
  `lng` varchar(20) NOT NULL DEFAULT '' COMMENT '纬度',
  `ueducation` varchar(30) NOT NULL DEFAULT '' COMMENT 'TA的学历',
  `urevenue` varchar(30) NOT NULL DEFAULT '' COMMENT '他的月薪',
  `love` int(10) NOT NULL DEFAULT '0' COMMENT '被喜欢次数',
  `mails` int(10) NOT NULL DEFAULT '0' COMMENT '收信次数',
  `uheightL` int(10) NOT NULL DEFAULT '0' COMMENT 'Ta的最小身高',
  `uheightH` int(10) NOT NULL DEFAULT '0' COMMENT 'Ta的最大身高',
  `uweight` int(10) NOT NULL DEFAULT '0' COMMENT 'Ta的体重',
  `uage` int(10) NOT NULL DEFAULT '0' COMMENT 'Ta的年龄',
  `Descrip` varchar(200) NOT NULL DEFAULT '' COMMENT '一句话描述',
  `uitsCharacter` varchar(300) NOT NULL DEFAULT '' COMMENT 'Ta的性格',
  `uitsOthers` varchar(300) NOT NULL DEFAULT '' COMMENT 'Ta的其他要求',
  `age` int(10) NOT NULL DEFAULT '0' COMMENT '自己的年龄',
  `isshow` int(2) NOT NULL DEFAULT '0' COMMENT '注册审核机制',
  `time` int(12) NOT NULL DEFAULT '0' COMMENT '进入平台获取资料时间',
  `yingcang` int(2) NOT NULL DEFAULT '1' COMMENT '隐藏显示',
  `qq` varchar(20) NOT NULL COMMENT '会员QQ',
  `wechat` varchar(25) NOT NULL COMMENT '会员微信',
  `telephoneconfirm` int(2) NOT NULL DEFAULT '0' COMMENT '是否手机验证',
  `tuijian` int(2) NOT NULL DEFAULT '1' COMMENT '推荐',
  `tjtype` int(2) NOT NULL DEFAULT '0' COMMENT '推荐类型',
  PRIMARY KEY (`id`),
  KEY `weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hnmessage`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hnmessage`;
CREATE TABLE `ims_hnmessage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `content` varchar(300) NOT NULL,
  `sender` varchar(255) NOT NULL,
  `sendernickname` varchar(200) NOT NULL,
  `senderavatar` varchar(255) NOT NULL,
  `geter` varchar(255) NOT NULL,
  `stime` int(12) NOT NULL,
  `mloop` tinyint(1) NOT NULL DEFAULT '0',
  `msgtype` varchar(20) NOT NULL DEFAULT 'text' COMMENT 'leixing',
  `thumburl` varchar(100) NOT NULL DEFAULT '0' COMMENT 'thumb',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hnpayjifen`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hnpayjifen`;
CREATE TABLE `ims_hnpayjifen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `tid` varchar(20) NOT NULL DEFAULT '0' COMMENT '订单编号',
  `openid` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `fee` int(10) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `paytype` varchar(20) NOT NULL,
  `transid` varchar(30) NOT NULL DEFAULT '0',
  `time` int(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hnresearch`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hnresearch`;
CREATE TABLE `ims_hnresearch` (
  `reid` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL,
  `content` text NOT NULL,
  `information` varchar(500) NOT NULL DEFAULT '',
  `thumb` varchar(200) NOT NULL DEFAULT '',
  `inhome` tinyint(4) NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL DEFAULT '0',
  `starttime` int(10) NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `pretotal` int(10) unsigned NOT NULL DEFAULT '1',
  `noticeemail` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`reid`),
  KEY `weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hnresearch_data`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hnresearch_data`;
CREATE TABLE `ims_hnresearch_data` (
  `redid` bigint(20) NOT NULL AUTO_INCREMENT,
  `reid` int(11) NOT NULL,
  `rerid` int(11) NOT NULL,
  `refid` int(11) NOT NULL,
  `data` varchar(800) NOT NULL,
  PRIMARY KEY (`redid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hnresearch_fields`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hnresearch_fields`;
CREATE TABLE `ims_hnresearch_fields` (
  `refid` int(11) NOT NULL AUTO_INCREMENT,
  `reid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `essential` tinyint(4) NOT NULL DEFAULT '0',
  `bind` varchar(30) NOT NULL DEFAULT '',
  `value` varchar(300) NOT NULL DEFAULT '',
  `description` varchar(500) NOT NULL DEFAULT '',
  `displayorder` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`refid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hnresearch_rows`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hnresearch_rows`;
CREATE TABLE `ims_hnresearch_rows` (
  `rerid` int(11) NOT NULL AUTO_INCREMENT,
  `reid` int(11) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `createtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rerid`),
  KEY `reid` (`reid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hongapis`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hongapis`;
CREATE TABLE `ims_hongapis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `status` int(3) NOT NULL,
  `city` varchar(255) NOT NULL,
  `company` varchar(20) NOT NULL,
  `create_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  `openid` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hongapitype`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hongapitype`;
CREATE TABLE `ims_hongapitype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `express_id` varchar(20) NOT NULL,
  `status` int(3) NOT NULL,
  `company` varchar(20) NOT NULL,
  `create_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  `openid` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hongniangexchangelog`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hongniangexchangelog`;
CREATE TABLE `ims_hongniangexchangelog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(100) NOT NULL,
  `toopenid` varchar(100) NOT NULL,
  `twhichone` varchar(100) NOT NULL,
  `credit` tinyint(1) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hongniangsharelogs`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hongniangsharelogs`;
CREATE TABLE `ims_hongniangsharelogs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(100) NOT NULL,
  `weid` int(10) unsigned NOT NULL,
  `jljifen` varchar(10) NOT NULL,
  `sharetime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hotel2`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hotel2`;
CREATE TABLE `ims_hotel2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `lng` decimal(10,2) DEFAULT '0.00',
  `lat` decimal(10,2) DEFAULT '0.00',
  `ordermax` int(11) DEFAULT '0',
  `numsmax` int(11) DEFAULT '0',
  `daymax` int(11) DEFAULT '0',
  `address` varchar(255) DEFAULT '',
  `location_p` varchar(50) DEFAULT '',
  `location_c` varchar(50) DEFAULT '',
  `location_a` varchar(50) DEFAULT '',
  `roomcount` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `phone` varchar(255) DEFAULT '',
  `mail` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `thumborder` varchar(255) DEFAULT '',
  `description` text,
  `content` text,
  `traffic` text,
  `thumbs` text,
  `sales` text,
  `displayorder` int(11) DEFAULT '0',
  `level` int(11) DEFAULT '0',
  `device` text,
  `brandid` int(11) DEFAULT '0',
  `businessid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hotel2_brand`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hotel2_brand`;
CREATE TABLE `ims_hotel2_brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hotel2_business`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hotel2_business`;
CREATE TABLE `ims_hotel2_business` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `location_p` varchar(255) DEFAULT '',
  `location_c` varchar(255) DEFAULT '',
  `location_a` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hotel2_member`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hotel2_member`;
CREATE TABLE `ims_hotel2_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `userid` varchar(50) DEFAULT '',
  `from_user` varchar(50) DEFAULT '',
  `realname` varchar(255) DEFAULT '',
  `mobile` varchar(255) DEFAULT '',
  `score` int(11) DEFAULT '0' COMMENT '积分',
  `createtime` int(11) DEFAULT '0',
  `userbind` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `username` varchar(30) DEFAULT '' COMMENT '用户名',
  `password` varchar(200) DEFAULT '' COMMENT '密码',
  `salt` varchar(8) NOT NULL DEFAULT '' COMMENT '加密盐',
  `islogin` tinyint(3) NOT NULL DEFAULT '0',
  `isauto` tinyint(1) NOT NULL DEFAULT '0' COMMENT '自动添加，0否，1是',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hotel2_order`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hotel2_order`;
CREATE TABLE `ims_hotel2_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `hotelid` int(11) DEFAULT '0',
  `roomid` int(11) DEFAULT '0',
  `memberid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `name` varchar(255) DEFAULT '',
  `mobile` varchar(255) DEFAULT '',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `btime` int(11) DEFAULT '0',
  `etime` int(11) DEFAULT '0',
  `style` varchar(255) DEFAULT '',
  `nums` int(11) DEFAULT '0',
  `oprice` decimal(10,2) DEFAULT '0.00' COMMENT '原价',
  `cprice` decimal(10,2) DEFAULT '0.00' COMMENT '现价',
  `mprice` decimal(10,2) DEFAULT '0.00' COMMENT '会员价',
  `info` text,
  `time` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `paytype` int(11) DEFAULT '0',
  `paystatus` int(11) DEFAULT '0',
  `msg` text,
  `mngtime` int(11) DEFAULT '0',
  `contact_name` varchar(30) NOT NULL DEFAULT '' COMMENT '联系人',
  `day` tinyint(2) NOT NULL DEFAULT '0' COMMENT '住几晚',
  `sum_price` decimal(10,2) DEFAULT '0.00' COMMENT '总价',
  `ordersn` varchar(30) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_hotelid` (`hotelid`),
  KEY `indx_weid` (`weid`),
  KEY `indx_roomid` (`roomid`),
  KEY `indx_memberid` (`memberid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hotel2_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hotel2_reply`;
CREATE TABLE `ims_hotel2_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `hotelid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hotel2_room`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hotel2_room`;
CREATE TABLE `ims_hotel2_room` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hotelid` int(11) DEFAULT '0',
  `weid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `oprice` decimal(10,2) DEFAULT '0.00' COMMENT '原价',
  `cprice` decimal(10,2) DEFAULT '0.00' COMMENT '现价',
  `mprice` decimal(10,2) DEFAULT '0.00' COMMENT '会员价',
  `thumbs` text,
  `device` text,
  `area` varchar(255) DEFAULT '',
  `floor` varchar(255) DEFAULT '',
  `smoke` varchar(255) DEFAULT '',
  `bed` varchar(255) DEFAULT '',
  `persons` int(11) DEFAULT '0',
  `bedadd` varchar(30) DEFAULT '',
  `status` int(11) DEFAULT '0',
  `isshow` int(11) DEFAULT '0',
  `sales` text,
  `displayorder` int(11) DEFAULT '0',
  `area_show` int(11) DEFAULT '0',
  `floor_show` int(11) DEFAULT '0',
  `smoke_show` int(11) DEFAULT '0',
  `bed_show` int(11) DEFAULT '0',
  `persons_show` int(11) DEFAULT '0',
  `bedadd_show` int(11) DEFAULT '0',
  `score` int(11) DEFAULT '0' COMMENT '订房积分',
  `breakfast` tinyint(3) DEFAULT '0' COMMENT '0无早 1单早 2双早',
  `sortid` int(11) NOT NULL DEFAULT '0' COMMENT '房间id，排序时使用',
  PRIMARY KEY (`id`),
  KEY `indx_hotelid` (`hotelid`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hotel2_room_price`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hotel2_room_price`;
CREATE TABLE `ims_hotel2_room_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `hotelid` int(11) DEFAULT '0',
  `roomid` int(11) DEFAULT '0',
  `roomdate` int(11) DEFAULT '0',
  `thisdate` varchar(255) NOT NULL DEFAULT '' COMMENT '当天日期',
  `oprice` decimal(10,2) DEFAULT '0.00' COMMENT '原价',
  `cprice` decimal(10,2) DEFAULT '0.00' COMMENT '现价',
  `mprice` decimal(10,2) DEFAULT '0.00' COMMENT '会员价',
  `num` varchar(255) DEFAULT '-1',
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_hotelid` (`hotelid`),
  KEY `indx_roomid` (`roomid`),
  KEY `indx_roomdate` (`roomdate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hotel2_set`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hotel2_set`;
CREATE TABLE `ims_hotel2_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `user` tinyint(1) DEFAULT '0' COMMENT '用户类型0微信用户1独立用户',
  `reg` tinyint(1) DEFAULT '0' COMMENT '是否允许注册0禁止注册1允许注册',
  `bind` tinyint(1) DEFAULT '0' COMMENT '是否绑定',
  `regcontent` text COMMENT '注册提示',
  `ordertype` tinyint(1) DEFAULT '0' COMMENT '预定类型0电话预定1电话和网络预订',
  `is_unify` tinyint(1) DEFAULT '0' COMMENT '0使用各分店电话,1使用统一电话',
  `tel` varchar(20) DEFAULT '' COMMENT '统一电话',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT '提醒接受邮箱',
  `mobile` varchar(32) NOT NULL DEFAULT '' COMMENT '提醒接受手机',
  `template` varchar(32) NOT NULL DEFAULT '' COMMENT '发送模板消息',
  `templateid` varchar(255) NOT NULL DEFAULT '' COMMENT '模板ID',
  `paytype1` tinyint(1) DEFAULT '0',
  `paytype2` tinyint(1) DEFAULT '0',
  `paytype3` tinyint(1) DEFAULT '0',
  `version` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0单酒店版1多酒店版',
  `location_p` varchar(50) DEFAULT '',
  `location_c` varchar(50) DEFAULT '',
  `location_a` varchar(50) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
--  Table structure for `ims_hx_alert_list`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_alert_list`;
CREATE TABLE `ims_hx_alert_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `title` varchar(50) DEFAULT NULL,
  `loops` int(10) unsigned NOT NULL COMMENT '循环次数',
  `items` text NOT NULL COMMENT '弹出文字',
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_alert_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_alert_reply`;
CREATE TABLE `ims_hx_alert_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `title` varchar(50) DEFAULT NULL,
  `picture` varchar(100) NOT NULL COMMENT '活动图片',
  `description` varchar(200) NOT NULL COMMENT '活动描述',
  `gzurl` varchar(255) NOT NULL,
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_cards_award`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_cards_award`;
CREATE TABLE `ims_hx_cards_award` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `reply_id` int(10) DEFAULT '0',
  `uid` int(10) DEFAULT '0',
  `name` varchar(50) DEFAULT '' COMMENT '名称',
  `prizetype` varchar(10) DEFAULT '' COMMENT '类型',
  `level` int(10) unsigned NOT NULL,
  `createtime` int(10) DEFAULT '0',
  `consumetime` int(10) DEFAULT '0',
  `status` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`reply_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_cards_fans`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_cards_fans`;
CREATE TABLE `ims_hx_cards_fans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `reply_id` int(10) unsigned NOT NULL DEFAULT '0',
  `from_user` varchar(50) NOT NULL DEFAULT '',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `todaynum` int(10) unsigned NOT NULL DEFAULT '0',
  `totalnum` int(10) unsigned NOT NULL DEFAULT '0',
  `awardnum` int(10) unsigned NOT NULL DEFAULT '0',
  `last_time` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_cards_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_cards_reply`;
CREATE TABLE `ims_hx_cards_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(200) NOT NULL,
  `groupid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '可参与的用户组',
  `thumb` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  `need_type` varchar(10) NOT NULL,
  `need_num` int(10) unsigned NOT NULL,
  `give_type` varchar(10) NOT NULL,
  `give_num` int(10) unsigned NOT NULL DEFAULT '0',
  `onlynone` tinyint(1) NOT NULL DEFAULT '0',
  `awardnum` int(10) unsigned NOT NULL,
  `playnum` int(10) unsigned NOT NULL,
  `dayplaynum` int(10) unsigned NOT NULL,
  `zfcs` int(10) unsigned NOT NULL COMMENT '转发次数',
  `zjcs` int(10) unsigned NOT NULL,
  `tips` varchar(255) NOT NULL,
  `noprize` text NOT NULL,
  `remark` varchar(255) NOT NULL,
  `share_title` varchar(100) NOT NULL,
  `share_img` varchar(255) NOT NULL,
  `share_url` varchar(255) NOT NULL,
  `share_content` varchar(255) NOT NULL,
  `rate` int(10) unsigned NOT NULL,
  `prizes` text NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0结束1正常2暂停',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_cards_share`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_cards_share`;
CREATE TABLE `ims_hx_cards_share` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uinacid` int(10) unsigned NOT NULL,
  `reply_id` int(10) unsigned NOT NULL,
  `share_from` varchar(50) NOT NULL,
  `share_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_dialect_questions`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_dialect_questions`;
CREATE TABLE `ims_hx_dialect_questions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `title` varchar(50) NOT NULL,
  `audio` varchar(200) NOT NULL,
  `a` varchar(50) NOT NULL,
  `b` varchar(50) NOT NULL,
  `c` varchar(50) NOT NULL,
  `d` varchar(50) NOT NULL,
  `answer` varchar(2) NOT NULL,
  `mark` int(10) NOT NULL,
  `hard` varchar(5) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `remark` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_dialect_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_dialect_reply`;
CREATE TABLE `ims_hx_dialect_reply` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `uniacid` int(10) NOT NULL,
  `r_name` varchar(200) NOT NULL,
  `r_title` varchar(200) NOT NULL,
  `thumb` varchar(1000) NOT NULL,
  `num` int(10) unsigned NOT NULL,
  `s_title` varchar(200) NOT NULL,
  `s_icon` varchar(1000) NOT NULL,
  `s_des` varchar(1000) NOT NULL,
  `s_cancel` varchar(200) NOT NULL,
  `s_share` varchar(2000) NOT NULL,
  `s_sucai` varchar(2000) NOT NULL,
  `py_1` varchar(200) NOT NULL,
  `py_2` varchar(200) NOT NULL,
  `py_3` varchar(200) NOT NULL,
  `py_4` varchar(200) NOT NULL,
  `py_5` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_lottery_award`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_lottery_award`;
CREATE TABLE `ims_hx_lottery_award` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `reply_id` int(10) DEFAULT '0',
  `uid` int(10) DEFAULT '0',
  `name` varchar(50) DEFAULT '' COMMENT '名称',
  `prizetype` varchar(10) DEFAULT '' COMMENT '类型',
  `level` int(10) unsigned NOT NULL,
  `createtime` int(10) DEFAULT '0',
  `consumetime` int(10) DEFAULT '0',
  `status` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`reply_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_lottery_fans`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_lottery_fans`;
CREATE TABLE `ims_hx_lottery_fans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `reply_id` int(10) unsigned NOT NULL DEFAULT '0',
  `from_user` varchar(50) NOT NULL DEFAULT '',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `todaynum` int(10) unsigned NOT NULL DEFAULT '0',
  `totalnum` int(10) unsigned NOT NULL DEFAULT '0',
  `awardnum` int(10) unsigned NOT NULL DEFAULT '0',
  `last_time` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_lottery_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_lottery_reply`;
CREATE TABLE `ims_hx_lottery_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(200) NOT NULL,
  `groupid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '可参与的用户组',
  `thumb` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  `need_type` varchar(10) NOT NULL,
  `need_num` int(10) unsigned NOT NULL,
  `give_type` varchar(10) NOT NULL,
  `give_num` int(10) unsigned NOT NULL DEFAULT '0',
  `onlynone` tinyint(1) NOT NULL DEFAULT '0',
  `awardnum` int(10) unsigned NOT NULL,
  `playnum` int(10) unsigned NOT NULL,
  `dayplaynum` int(10) unsigned NOT NULL,
  `zfcs` int(10) unsigned NOT NULL,
  `zjcs` int(10) unsigned NOT NULL,
  `tips` varchar(255) NOT NULL,
  `prizeinfo` varchar(255) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `share_title` varchar(100) NOT NULL,
  `share_img` varchar(255) NOT NULL,
  `share_url` varchar(255) NOT NULL,
  `share_content` varchar(255) NOT NULL,
  `rate` int(10) unsigned NOT NULL,
  `prizes` text NOT NULL,
  `reg` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0结束1正常2暂停',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_lottery_share`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_lottery_share`;
CREATE TABLE `ims_hx_lottery_share` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uinacid` int(10) unsigned NOT NULL,
  `reply_id` int(10) unsigned NOT NULL,
  `share_from` varchar(50) NOT NULL,
  `share_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_pictorial`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_pictorial`;
CREATE TABLE `ims_hx_pictorial` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `icon` varchar(100) NOT NULL DEFAULT '',
  `share` varchar(250) NOT NULL DEFAULT '',
  `open` varchar(100) NOT NULL DEFAULT '',
  `ostyle` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `music` varchar(100) NOT NULL DEFAULT '',
  `mauto` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `mloop` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `thumb` varchar(100) NOT NULL DEFAULT '',
  `content` varchar(1000) NOT NULL DEFAULT '',
  `loading` varchar(100) NOT NULL DEFAULT '',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `isloop` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isview` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  `moban` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_pictorial_item`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_pictorial_item`;
CREATE TABLE `ims_hx_pictorial_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `pictorialid` int(10) unsigned NOT NULL,
  `photoid` int(10) unsigned NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `item` varchar(1000) NOT NULL DEFAULT '',
  `url` varchar(250) NOT NULL DEFAULT '',
  `x` int(3) NOT NULL DEFAULT '0',
  `y` int(3) NOT NULL DEFAULT '0',
  `bigimg` varchar(1000) NOT NULL DEFAULT '',
  `animation` varchar(20) NOT NULL DEFAULT '',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_photoid` (`photoid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_pictorial_photo`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_pictorial_photo`;
CREATE TABLE `ims_hx_pictorial_photo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `pictorialid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `url` varchar(250) NOT NULL DEFAULT '',
  `attachment` varchar(100) NOT NULL DEFAULT '',
  `ispreview` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_huabaoid` (`pictorialid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_pictorial_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_pictorial_reply`;
CREATE TABLE `ims_hx_pictorial_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `huabaoid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_securityspro_data_56`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_securityspro_data_56`;
CREATE TABLE `ims_hx_securityspro_data_56` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `factory` varchar(500) NOT NULL,
  `creditname` varchar(20) NOT NULL,
  `creditnum` int(10) unsigned NOT NULL,
  `creditstatus` tinyint(1) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `stime` int(10) unsigned NOT NULL,
  `createtime` decimal(11,0) NOT NULL,
  `num` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_securityspro_data_67`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_securityspro_data_67`;
CREATE TABLE `ims_hx_securityspro_data_67` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `factory` varchar(500) NOT NULL,
  `creditname` varchar(20) NOT NULL,
  `creditnum` int(10) unsigned NOT NULL,
  `creditstatus` tinyint(1) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `stime` int(10) unsigned NOT NULL,
  `createtime` decimal(11,0) NOT NULL,
  `num` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_securityspro_data_moban`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_securityspro_data_moban`;
CREATE TABLE `ims_hx_securityspro_data_moban` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `factory` varchar(500) NOT NULL,
  `creditname` varchar(20) NOT NULL,
  `creditnum` int(10) unsigned NOT NULL,
  `creditstatus` tinyint(1) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `stime` int(10) unsigned NOT NULL,
  `createtime` decimal(11,0) NOT NULL,
  `num` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_securityspro_logs`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_securityspro_logs`;
CREATE TABLE `ims_hx_securityspro_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `code` varchar(50) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_securityspro_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_securityspro_reply`;
CREATE TABLE `ims_hx_securityspro_reply` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `weid` int(10) NOT NULL,
  `Reply` varchar(1000) NOT NULL,
  `Integral` int(10) NOT NULL,
  `tnumber` int(10) NOT NULL,
  `Failure` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_subscribe_apply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_subscribe_apply`;
CREATE TABLE `ims_hx_subscribe_apply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `realname` varchar(20) NOT NULL,
  `qq` varchar(50) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `alipay` varchar(50) NOT NULL,
  `cardid` varchar(50) NOT NULL,
  `cardfrom` varchar(255) NOT NULL,
  `cardname` varchar(10) NOT NULL,
  `credit2` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `mobile` varchar(12) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `remark` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_subscribe_article`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_subscribe_article`;
CREATE TABLE `ims_hx_subscribe_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(100) NOT NULL DEFAULT '',
  `content` mediumtext NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `source` varchar(255) NOT NULL,
  `author` varchar(50) NOT NULL,
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `linkurl` varchar(500) NOT NULL DEFAULT '',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `credit` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_subscribe_data`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_subscribe_data`;
CREATE TABLE `ims_hx_subscribe_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `from_uid` int(10) unsigned NOT NULL,
  `sn` int(10) unsigned NOT NULL,
  `follow` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `article_id` int(10) unsigned NOT NULL,
  `shouyi` int(10) unsigned NOT NULL DEFAULT '0',
  `zjrs` int(10) unsigned NOT NULL DEFAULT '0',
  `jjrs` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_zhongchou_address`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_zhongchou_address`;
CREATE TABLE `ims_hx_zhongchou_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `realname` varchar(20) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `province` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `area` varchar(30) NOT NULL,
  `address` varchar(300) NOT NULL,
  `isdefault` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_zhongchou_adv`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_zhongchou_adv`;
CREATE TABLE `ims_hx_zhongchou_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_zhongchou_cart`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_zhongchou_cart`;
CREATE TABLE `ims_hx_zhongchou_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `projectid` int(11) NOT NULL,
  `from_user` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_zhongchou_category`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_zhongchou_category`;
CREATE TABLE `ims_hx_zhongchou_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `thumb` varchar(255) NOT NULL COMMENT '分类图片',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0',
  `description` varchar(500) NOT NULL COMMENT '分类介绍',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_zhongchou_dispatch`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_zhongchou_dispatch`;
CREATE TABLE `ims_hx_zhongchou_dispatch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `dispatchname` varchar(50) DEFAULT '',
  `dispatchtype` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `firstprice` decimal(10,2) DEFAULT '0.00',
  `secondprice` decimal(10,2) DEFAULT '0.00',
  `firstweight` int(11) DEFAULT '0',
  `secondweight` int(11) DEFAULT '0',
  `express` int(11) DEFAULT '0',
  `description` text,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_zhongchou_express`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_zhongchou_express`;
CREATE TABLE `ims_hx_zhongchou_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `express_name` varchar(50) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `express_price` varchar(10) DEFAULT '',
  `express_area` varchar(100) DEFAULT '',
  `express_url` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_zhongchou_feedback`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_zhongchou_feedback`;
CREATE TABLE `ims_hx_zhongchou_feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为维权，2为告擎',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态0未解决，1用户同意，2用户拒绝',
  `feedbackid` varchar(30) NOT NULL COMMENT '投诉单号',
  `transid` varchar(30) NOT NULL COMMENT '订单号',
  `reason` varchar(1000) NOT NULL COMMENT '理由',
  `solution` varchar(1000) NOT NULL COMMENT '期待解决方案',
  `remark` varchar(1000) NOT NULL COMMENT '备注',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`),
  KEY `idx_feedbackid` (`feedbackid`),
  KEY `idx_createtime` (`createtime`),
  KEY `idx_transid` (`transid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_zhongchou_order`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_zhongchou_order`;
CREATE TABLE `ims_hx_zhongchou_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `ordersn` varchar(20) NOT NULL,
  `pid` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `price` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功',
  `sendtype` tinyint(1) unsigned NOT NULL COMMENT '1为快递，2为自提',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付',
  `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号',
  `return_type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `remark` varchar(1000) NOT NULL DEFAULT '',
  `addressid` int(10) unsigned NOT NULL,
  `expresscom` varchar(30) NOT NULL DEFAULT '',
  `expresssn` varchar(50) NOT NULL DEFAULT '',
  `express` varchar(200) NOT NULL DEFAULT '',
  `item_price` decimal(10,2) DEFAULT '0.00',
  `dispatchprice` decimal(10,2) DEFAULT '0.00',
  `dispatch` int(10) DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_zhongchou_project`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_zhongchou_project`;
CREATE TABLE `ims_hx_zhongchou_project` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `limit_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `donenum` int(10) unsigned NOT NULL DEFAULT '0',
  `finish_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `deal_days` int(10) unsigned NOT NULL,
  `ishot` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isrecommand` tinyint(1) unsigned DEFAULT '0',
  `pcate` int(10) unsigned NOT NULL DEFAULT '0',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) DEFAULT '',
  `brief` varchar(1000) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `nosubuser` tinyint(1) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0暂停1正常2停止',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_hx_zhongchou_project_item`
-- ----------------------------
DROP TABLE IF EXISTS `ims_hx_zhongchou_project_item`;
CREATE TABLE `ims_hx_zhongchou_project_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `pid` int(10) unsigned NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `description` varchar(2000) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `limit_num` int(10) unsigned NOT NULL,
  `donenum` int(10) unsigned NOT NULL DEFAULT '0',
  `repaid_day` int(10) unsigned NOT NULL,
  `return_type` tinyint(1) unsigned NOT NULL,
  `dispatch` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_images_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_images_reply`;
CREATE TABLE `ims_images_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `mediaid` varchar(255) NOT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_iwobanji_xcpage_adma`
-- ----------------------------
DROP TABLE IF EXISTS `ims_iwobanji_xcpage_adma`;
CREATE TABLE `ims_iwobanji_xcpage_adma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `url` varchar(100) NOT NULL,
  `copyright` varchar(50) NOT NULL,
  `info` varchar(120) NOT NULL,
  `title` varchar(60) NOT NULL,
  `class` varchar(60) NOT NULL,
  `classkouling` varchar(60) NOT NULL,
  `classslogan` varchar(60) NOT NULL,
  `background_img` varchar(60) NOT NULL,
  `group_photo` varchar(60) NOT NULL,
  `wxh` varchar(60) NOT NULL,
  `wxm` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_iwobanji_xcpage_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_iwobanji_xcpage_reply`;
CREATE TABLE `ims_iwobanji_xcpage_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_jufeng_wcy_cart`
-- ----------------------------
DROP TABLE IF EXISTS `ims_jufeng_wcy_cart`;
CREATE TABLE `ims_jufeng_wcy_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `foodsid` int(11) NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `total` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_jufeng_wcy_category`
-- ----------------------------
DROP TABLE IF EXISTS `ims_jufeng_wcy_category`;
CREATE TABLE `ims_jufeng_wcy_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '名称',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级ID,0为店铺',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  `sendprice` int(10) unsigned NOT NULL DEFAULT '0',
  `total` int(10) unsigned NOT NULL DEFAULT '0',
  `shouji` bigint(50) NOT NULL COMMENT '店家手机',
  `email` varchar(50) NOT NULL DEFAULT '',
  `typeid` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `time1` varchar(10) NOT NULL DEFAULT '0',
  `time2` varchar(10) NOT NULL DEFAULT '0',
  `time3` varchar(10) NOT NULL DEFAULT '0',
  `time4` varchar(10) NOT NULL DEFAULT '0',
  `address` varchar(100) NOT NULL,
  `loc_x` varchar(20) NOT NULL,
  `loc_y` varchar(20) NOT NULL,
  `mbgroup` int(10) unsigned NOT NULL,
  `count1` varchar(20) NOT NULL,
  `count2` varchar(20) NOT NULL,
  `count3` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_jufeng_wcy_foods`
-- ----------------------------
DROP TABLE IF EXISTS `ims_jufeng_wcy_foods`;
CREATE TABLE `ims_jufeng_wcy_foods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `pcate` int(10) unsigned NOT NULL DEFAULT '0',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `ishot` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `thumb` varchar(100) NOT NULL DEFAULT '',
  `unit` varchar(5) NOT NULL DEFAULT '',
  `preprice` varchar(10) NOT NULL DEFAULT '',
  `oriprice` varchar(10) NOT NULL DEFAULT '',
  `hits` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_jufeng_wcy_loc`
-- ----------------------------
DROP TABLE IF EXISTS `ims_jufeng_wcy_loc`;
CREATE TABLE `ims_jufeng_wcy_loc` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `loc_x` varchar(20) NOT NULL,
  `loc_y` varchar(20) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_jufeng_wcy_order`
-- ----------------------------
DROP TABLE IF EXISTS `ims_jufeng_wcy_order`;
CREATE TABLE `ims_jufeng_wcy_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `pcate` int(10) unsigned NOT NULL,
  `mobile` bigint(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `ordersn` varchar(20) NOT NULL,
  `price` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '-2已删除，-1已取消，0已完成，1等待支付，2已下单，3已确认',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为在线付款，2为餐到付款',
  `other` varchar(100) NOT NULL DEFAULT '',
  `time` varchar(20) NOT NULL DEFAULT '',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_jufeng_wcy_order_foods`
-- ----------------------------
DROP TABLE IF EXISTS `ims_jufeng_wcy_order_foods`;
CREATE TABLE `ims_jufeng_wcy_order_foods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `foodsid` int(10) unsigned NOT NULL,
  `total` int(10) unsigned NOT NULL DEFAULT '1',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_jufeng_wcy_print`
-- ----------------------------
DROP TABLE IF EXISTS `ims_jufeng_wcy_print`;
CREATE TABLE `ims_jufeng_wcy_print` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `cateid` int(10) NOT NULL,
  `deviceno` varchar(20) NOT NULL,
  `key` varchar(20) NOT NULL,
  `printtime` int(10) unsigned NOT NULL,
  `qr` varchar(200) NOT NULL DEFAULT '',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_jufeng_wcy_shoptype`
-- ----------------------------
DROP TABLE IF EXISTS `ims_jufeng_wcy_shoptype`;
CREATE TABLE `ims_jufeng_wcy_shoptype` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `displayorder` int(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_jufeng_wcy_sms`
-- ----------------------------
DROP TABLE IF EXISTS `ims_jufeng_wcy_sms`;
CREATE TABLE `ims_jufeng_wcy_sms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `email` varchar(50) NOT NULL,
  `emailpsw` varchar(100) NOT NULL,
  `smtp` varchar(50) NOT NULL,
  `smsnum` varchar(50) NOT NULL,
  `smspsw` varchar(50) NOT NULL,
  `smstest` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_junsion_qixiqueqiao_player`
-- ----------------------------
DROP TABLE IF EXISTS `ims_junsion_qixiqueqiao_player`;
CREATE TABLE `ims_junsion_qixiqueqiao_player` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `avatar` varchar(200) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `realname` varchar(50) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `qq` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `myname` varchar(50) NOT NULL,
  `hname` varchar(50) NOT NULL,
  `status` int(1) NOT NULL,
  `createtime` varchar(11) NOT NULL,
  `successtime` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_junsion_qixiqueqiao_prize`
-- ----------------------------
DROP TABLE IF EXISTS `ims_junsion_qixiqueqiao_prize`;
CREATE TABLE `ims_junsion_qixiqueqiao_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `thumb` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `level` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_junsion_qixiqueqiao_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_junsion_qixiqueqiao_record`;
CREATE TABLE `ims_junsion_qixiqueqiao_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(50) NOT NULL,
  `pid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_junsion_qixiqueqiao_rule`
-- ----------------------------
DROP TABLE IF EXISTS `ims_junsion_qixiqueqiao_rule`;
CREATE TABLE `ims_junsion_qixiqueqiao_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `stitle` varchar(50) NOT NULL,
  `sthumb` varchar(200) NOT NULL,
  `sdesc` text NOT NULL,
  `niulang` varchar(200) NOT NULL,
  `zhinv` varchar(200) NOT NULL,
  `bg` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `starttime` varchar(11) NOT NULL,
  `endtime` varchar(11) NOT NULL,
  `prize_mode` int(1) NOT NULL,
  `describe_limit` int(1) NOT NULL,
  `describe_limit2` int(1) DEFAULT '0',
  `prize_limit` int(11) NOT NULL,
  `birds_success` int(11) NOT NULL,
  `birds_limit` varchar(10) NOT NULL,
  `sharetitle` varchar(200) NOT NULL,
  `sharethumb` varchar(200) NOT NULL,
  `sharedesc` text NOT NULL,
  `isinfo` int(1) NOT NULL,
  `awardtips` varchar(200) NOT NULL,
  `isrealname` int(1) NOT NULL,
  `ismobile` int(1) NOT NULL,
  `isqq` int(1) NOT NULL,
  `isemail` int(1) NOT NULL,
  `isaddress` int(1) NOT NULL,
  `isfans` int(1) NOT NULL,
  `rank` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_junsion_qixiqueqiao_share`
-- ----------------------------
DROP TABLE IF EXISTS `ims_junsion_qixiqueqiao_share`;
CREATE TABLE `ims_junsion_qixiqueqiao_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `avatar` varchar(200) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `pid` int(11) NOT NULL,
  `birds_num` int(11) NOT NULL,
  `createtime` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ju_credit_log`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ju_credit_log`;
CREATE TABLE `ims_ju_credit_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned DEFAULT NULL,
  `openid` varchar(40) DEFAULT NULL,
  `subscribetime` int(10) unsigned DEFAULT NULL,
  `unsubscribetime` int(10) unsigned DEFAULT NULL,
  `follow` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ju_read_data`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ju_read_data`;
CREATE TABLE `ims_ju_read_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `reply_id` int(10) unsigned NOT NULL,
  `openid` varchar(30) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `sn` varchar(20) NOT NULL,
  `prizeid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ju_read_log`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ju_read_log`;
CREATE TABLE `ims_ju_read_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `reply_id` int(10) unsigned NOT NULL,
  `parentopenid` varchar(30) NOT NULL,
  `readopenid` varchar(30) NOT NULL,
  `ceratetime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_ju_read_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ju_read_reply`;
CREATE TABLE `ims_ju_read_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `topimg` varchar(255) NOT NULL,
  `bgcolor` varchar(10) NOT NULL,
  `pagestyle` longtext NOT NULL,
  `address` text NOT NULL,
  `tips` varchar(500) NOT NULL,
  `linkurl` varchar(200) NOT NULL,
  `adimg` varchar(255) NOT NULL,
  `tel` varchar(11) NOT NULL,
  `copyright` varchar(20) NOT NULL,
  `prizes` longtext NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_jy_wei_company`
-- ----------------------------
DROP TABLE IF EXISTS `ims_jy_wei_company`;
CREATE TABLE `ims_jy_wei_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `url` varchar(255) NOT NULL COMMENT '公司URL',
  `title` varchar(255) NOT NULL COMMENT '网站title',
  `name` varchar(255) NOT NULL COMMENT '公司名称',
  `shortname` varchar(255) NOT NULL COMMENT '公司名称简写',
  `banner` varchar(255) NOT NULL COMMENT 'Banner',
  `logo` varchar(255) NOT NULL COMMENT 'Logo',
  `propagenda` varchar(255) NOT NULL COMMENT '一句话公司宣传语',
  `description` varchar(255) NOT NULL COMMENT '简介',
  `shareimage` varchar(255) NOT NULL COMMENT '分享图片',
  `sharetitle` varchar(255) NOT NULL COMMENT '分享标题',
  `sharedescription` varchar(255) NOT NULL COMMENT '分享描述',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_jy_wei_invitation`
-- ----------------------------
DROP TABLE IF EXISTS `ims_jy_wei_invitation`;
CREATE TABLE `ims_jy_wei_invitation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL,
  `companyid` int(11) unsigned NOT NULL,
  `positionid` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL COMMENT '姓名',
  `phone` varchar(255) NOT NULL COMMENT '手机',
  `payment` varchar(255) NOT NULL COMMENT '薪酬',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_jy_wei_keyword`
-- ----------------------------
DROP TABLE IF EXISTS `ims_jy_wei_keyword`;
CREATE TABLE `ims_jy_wei_keyword` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `companyid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL COMMENT '关键字',
  `unicode` varchar(255) NOT NULL COMMENT '唯一码MD5(name)',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_jy_wei_label`
-- ----------------------------
DROP TABLE IF EXISTS `ims_jy_wei_label`;
CREATE TABLE `ims_jy_wei_label` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `companyid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL COMMENT '标签',
  `unicode` varchar(255) NOT NULL COMMENT '唯一码MD5(name)',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_jy_wei_log`
-- ----------------------------
DROP TABLE IF EXISTS `ims_jy_wei_log`;
CREATE TABLE `ims_jy_wei_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL,
  `companyid` int(11) unsigned NOT NULL,
  `positionid` int(11) unsigned NOT NULL,
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_jy_wei_position`
-- ----------------------------
DROP TABLE IF EXISTS `ims_jy_wei_position`;
CREATE TABLE `ims_jy_wei_position` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `companyid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL COMMENT '职位',
  `payment` varchar(255) NOT NULL COMMENT '薪酬',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `count` int(11) NOT NULL DEFAULT '0',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_jy_wei_rule`
-- ----------------------------
DROP TABLE IF EXISTS `ims_jy_wei_rule`;
CREATE TABLE `ims_jy_wei_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `companyid` int(11) NOT NULL,
  `ruleid` int(11) NOT NULL,
  `uniacid` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_lee_tlvoice_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lee_tlvoice_record`;
CREATE TABLE `ims_lee_tlvoice_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `keyid` varchar(40) NOT NULL DEFAULT '',
  `timelength` varchar(20) NOT NULL DEFAULT '',
  `serverid` varchar(500) NOT NULL DEFAULT '',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_lions_zq_billboard`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lions_zq_billboard`;
CREATE TABLE `ims_lions_zq_billboard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `openid` varchar(255) DEFAULT NULL,
  `score` varchar(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='月球来的月饼';

-- ----------------------------
--  Table structure for `ims_lions_zq_settings`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lions_zq_settings`;
CREATE TABLE `ims_lions_zq_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `setting_key` varchar(255) NOT NULL,
  `setting_value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='月球来的月饼';

-- ----------------------------
--  Table structure for `ims_lovehelper_ip`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lovehelper_ip`;
CREATE TABLE `ims_lovehelper_ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clientip` varchar(30) NOT NULL,
  `identity` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_lovehelper_msg`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lovehelper_msg`;
CREATE TABLE `ims_lovehelper_msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `fromuser` varchar(50) NOT NULL,
  `bgimage` varchar(200) NOT NULL,
  `viewcount` int(11) NOT NULL DEFAULT '1',
  `forward` int(11) NOT NULL,
  `praise` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_lovehelper_res`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lovehelper_res`;
CREATE TABLE `ims_lovehelper_res` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `filename` varchar(200) NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '1:music 2:image',
  `uniacid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_lw_comments`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lw_comments`;
CREATE TABLE `ims_lw_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `parentid` int(10) unsigned NOT NULL DEFAULT '0',
  `openid` varchar(50) NOT NULL,
  `toUser` varchar(50) NOT NULL,
  `content` varchar(300) NOT NULL DEFAULT '',
  `createtime` varchar(100) NOT NULL,
  `nowColor` varchar(50) NOT NULL,
  `limit` tinyint(2) NOT NULL DEFAULT '0',
  `isok` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_lw_commentslike`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lw_commentslike`;
CREATE TABLE `ims_lw_commentslike` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `swnoId` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `createtime` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_lw_fans`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lw_fans`;
CREATE TABLE `ims_lw_fans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `openid` varchar(100) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `headimgurl` varchar(300) NOT NULL DEFAULT '',
  `createtime` varchar(100) NOT NULL,
  `isblack` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_lw_report`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lw_report`;
CREATE TABLE `ims_lw_report` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `swnoId` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `reporter` varchar(50) NOT NULL,
  `createtime` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_lxy_buildpro_album`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lxy_buildpro_album`;
CREATE TABLE `ims_lxy_buildpro_album` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL COMMENT '相册名称',
  `subtitle` varchar(255) DEFAULT NULL,
  `hid` int(11) DEFAULT NULL COMMENT '楼盘id ims_lxy_buildpro_set table id',
  `sort` tinyint(4) unsigned DEFAULT '0' COMMENT '排序',
  `jianjie` text,
  `pic` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='楼盘相册';

-- ----------------------------
--  Table structure for `ims_lxy_buildpro_bill`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lxy_buildpro_bill`;
CREATE TABLE `ims_lxy_buildpro_bill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT NULL,
  `weid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `pic1` varchar(255) DEFAULT NULL,
  `pic2` varchar(255) DEFAULT NULL,
  `pic3` varchar(255) DEFAULT NULL,
  `pic4` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='楼盘海报';

-- ----------------------------
--  Table structure for `ims_lxy_buildpro_expert_comment`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lxy_buildpro_expert_comment`;
CREATE TABLE `ims_lxy_buildpro_expert_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT NULL,
  `weid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `expert_name` varchar(20) DEFAULT NULL,
  `zhiwei` varchar(255) DEFAULT NULL COMMENT '专家职位',
  `sort` tinyint(4) unsigned DEFAULT NULL COMMENT '排序',
  `jianjie` text,
  `content` text COMMENT '点评内容',
  `thumb` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='楼盘-专家点评';

-- ----------------------------
--  Table structure for `ims_lxy_buildpro_fell`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lxy_buildpro_fell`;
CREATE TABLE `ims_lxy_buildpro_fell` (
  `yid` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `hid` int(11) DEFAULT NULL COMMENT '楼盘id',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `sort` tinyint(4) unsigned DEFAULT '0' COMMENT '排序',
  `yinxiang_number` int(11) unsigned DEFAULT '0' COMMENT '印象数',
  `isshow` tinyint(1) DEFAULT '1',
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`yid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='房友印象';

-- ----------------------------
--  Table structure for `ims_lxy_buildpro_fell_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lxy_buildpro_fell_record`;
CREATE TABLE `ims_lxy_buildpro_fell_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT NULL,
  `weid` int(11) DEFAULT NULL,
  `fromuser` varchar(255) DEFAULT NULL COMMENT '楼盘id',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='房友印象';

-- ----------------------------
--  Table structure for `ims_lxy_buildpro_full_view`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lxy_buildpro_full_view`;
CREATE TABLE `ims_lxy_buildpro_full_view` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `hsid` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `quanjinglink` varchar(500) DEFAULT NULL COMMENT '全景外链',
  `pic_qian` varchar(1023) DEFAULT NULL,
  `pic_hou` varchar(1023) DEFAULT NULL,
  `pic_zuo` varchar(1023) DEFAULT NULL,
  `pic_you` varchar(1023) DEFAULT NULL,
  `pic_shang` varchar(1023) DEFAULT NULL,
  `pic_xia` varchar(1023) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='楼盘户型全景';

-- ----------------------------
--  Table structure for `ims_lxy_buildpro_head`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lxy_buildpro_head`;
CREATE TABLE `ims_lxy_buildpro_head` (
  `hid` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL,
  `xcname` varchar(255) DEFAULT NULL,
  `headpic` varchar(255) DEFAULT NULL,
  `apartpic` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `dist` varchar(20) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `province` varchar(20) DEFAULT NULL,
  `jw_addr` varchar(255) DEFAULT NULL,
  `lng` varchar(12) DEFAULT '116.403694',
  `lat` varchar(12) DEFAULT '39.916042',
  `jianjie` text,
  `xiangmu` text,
  `jiaotong` text,
  `addr` varchar(255) DEFAULT NULL,
  `yyurl` varchar(500) DEFAULT NULL,
  `xwurl` varchar(500) DEFAULT NULL,
  `hyurl` varchar(500) DEFAULT NULL,
  `tel` varchar(50) DEFAULT NULL,
  `lxname` varchar(50) DEFAULT NULL,
  `hyname` varchar(50) DEFAULT NULL,
  `yyname` varchar(50) DEFAULT NULL,
  `xwname` varchar(50) DEFAULT NULL,
  `yxname` varchar(50) DEFAULT NULL,
  `hxname` varchar(50) DEFAULT NULL,
  `jjname` varchar(50) DEFAULT NULL,
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`hid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='楼盘简介';

-- ----------------------------
--  Table structure for `ims_lxy_buildpro_house`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lxy_buildpro_house`;
CREATE TABLE `ims_lxy_buildpro_house` (
  `hsid` int(11) NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT NULL,
  `weid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL COMMENT '户型名称',
  `sid` int(11) DEFAULT NULL COMMENT '子楼盘 ims_lxy_buildpro_set id',
  `louceng` smallint(1) DEFAULT NULL COMMENT '楼层',
  `mianji` varchar(255) DEFAULT NULL COMMENT '建筑面积',
  `fang` tinyint(4) DEFAULT NULL,
  `ting` tinyint(4) DEFAULT NULL,
  `sort` tinyint(4) unsigned DEFAULT NULL COMMENT '排序',
  `jianjie` text,
  `pic` text,
  `picjson` text,
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`hsid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='楼盘户型';

-- ----------------------------
--  Table structure for `ims_lxy_buildpro_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lxy_buildpro_reply`;
CREATE TABLE `ims_lxy_buildpro_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `hid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_lxy_buildpro_sub`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lxy_buildpro_sub`;
CREATE TABLE `ims_lxy_buildpro_sub` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `hid` int(11) DEFAULT NULL COMMENT '楼盘id',
  `title` varchar(255) DEFAULT NULL COMMENT '子楼盘名称',
  `sort` tinyint(4) unsigned DEFAULT '0' COMMENT '排序',
  `jianjie` text,
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='子楼盘';

-- ----------------------------
--  Table structure for `ims_lxy_marry_info`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lxy_marry_info`;
CREATE TABLE `ims_lxy_marry_info` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `weid` bigint(20) unsigned DEFAULT NULL,
  `fromuser` varchar(32) DEFAULT NULL,
  `sid` bigint(20) unsigned DEFAULT NULL COMMENT 'micro_xitie_set id',
  `name` varchar(25) DEFAULT NULL,
  `tel` varchar(25) DEFAULT NULL,
  `rs` smallint(1) DEFAULT NULL COMMENT '赴宴人数',
  `zhufu` varchar(255) DEFAULT NULL COMMENT '收到祝福',
  `ctime` datetime DEFAULT NULL,
  `type` tinyint(1) DEFAULT '1' COMMENT '1:赴宴 2：祝福',
  PRIMARY KEY (`id`),
  KEY `idx_sid_openid` (`sid`,`fromuser`),
  KEY `idx_sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微喜帖信息列表';

-- ----------------------------
--  Table structure for `ims_lxy_marry_list`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lxy_marry_list`;
CREATE TABLE `ims_lxy_marry_list` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) unsigned DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `art_pic` varchar(255) DEFAULT NULL,
  `bg_pic` varchar(255) NOT NULL COMMENT '背景图片',
  `donghua_pic` varchar(255) DEFAULT NULL,
  `suolue_pic` varchar(255) DEFAULT NULL COMMENT '缩略图',
  `xl_name` varchar(255) DEFAULT NULL,
  `xn_name` varchar(255) DEFAULT NULL,
  `is_front` varchar(255) DEFAULT '1' COMMENT '1:新郎名字在前 2:新娘名字在前',
  `tel` varchar(25) DEFAULT NULL,
  `hy_time` datetime DEFAULT NULL COMMENT '婚宴日期',
  `dist` varchar(20) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `province` varchar(20) DEFAULT NULL,
  `hy_addr` varchar(255) DEFAULT NULL COMMENT '婚宴地址',
  `jw_addr` varchar(255) DEFAULT NULL COMMENT '经纬地址',
  `lng` varchar(12) DEFAULT '116.403694',
  `lat` varchar(12) DEFAULT '39.916042',
  `video` varchar(255) DEFAULT '/res/weiXiTie/mp4.mp4',
  `music` varchar(255) DEFAULT '/res/weiXiTie/youGotMe.mp3',
  `hs_pic` text COMMENT '婚纱图片',
  `pwd` varchar(255) DEFAULT NULL,
  `word` varchar(500) DEFAULT NULL,
  `erweima_pic` varchar(255) DEFAULT NULL COMMENT '二维码图片',
  `copyright` varchar(512) DEFAULT NULL COMMENT '版权',
  `createtime` int(11) unsigned DEFAULT NULL,
  `sendtitle` varchar(255) NOT NULL DEFAULT '',
  `senddescription` varchar(500) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微喜帖设置';

-- ----------------------------
--  Table structure for `ims_lxy_marry_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lxy_marry_reply`;
CREATE TABLE `ims_lxy_marry_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `marryid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_lxy_rtrouter_authentication`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lxy_rtrouter_authentication`;
CREATE TABLE `ims_lxy_rtrouter_authentication` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `routerid` int(11) DEFAULT NULL,
  `fromuser` varchar(100) DEFAULT NULL,
  `createtime` int(11) DEFAULT NULL,
  `result` int(11) DEFAULT NULL,
  `resultmemo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_lxy_rtrouter_info`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lxy_rtrouter_info`;
CREATE TABLE `ims_lxy_rtrouter_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `iurl` varchar(255) DEFAULT NULL,
  `rname` varchar(100) DEFAULT NULL,
  `appid` varchar(100) DEFAULT NULL,
  `appkey` varchar(100) DEFAULT NULL,
  `nodeid` varchar(100) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_lxy_rtrouter_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_lxy_rtrouter_reply`;
CREATE TABLE `ims_lxy_rtrouter_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `oktip` varchar(255) NOT NULL COMMENT '规则标题',
  `routerid` int(10) unsigned NOT NULL COMMENT '名片id',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '开关状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mbrp_activities`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mbrp_activities`;
CREATE TABLE `ims_mbrp_activities` (
  `actid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '活动编号',
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(20) NOT NULL COMMENT '活动名称',
  `start` int(10) unsigned NOT NULL COMMENT '开始时间',
  `end` int(10) unsigned NOT NULL COMMENT '结束时间',
  `rules` text NOT NULL COMMENT '活动规则介绍',
  `guide` varchar(255) NOT NULL COMMENT '活动指南(图文素材地址)',
  `banner` varchar(500) NOT NULL COMMENT '背景图片',
  `type` varchar(10) NOT NULL COMMENT '活动类型(direct, shared)',
  `limit` varchar(1000) NOT NULL DEFAULT '',
  `share` varchar(1000) NOT NULL DEFAULT '',
  `tag` text NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`actid`),
  KEY `type` (`type`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mbrp_activity_gifts`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mbrp_activity_gifts`;
CREATE TABLE `ims_mbrp_activity_gifts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `activity` int(10) unsigned NOT NULL,
  `gift` int(10) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  `rate` decimal(6,2) NOT NULL COMMENT '中奖百分比率',
  PRIMARY KEY (`id`),
  KEY `activity` (`activity`),
  KEY `gift` (`gift`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mbrp_fans`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mbrp_fans`;
CREATE TABLE `ims_mbrp_fans` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `openid` varchar(40) NOT NULL DEFAULT '',
  `proxy` varchar(40) NOT NULL DEFAULT '',
  `unionid` varchar(40) NOT NULL DEFAULT '',
  `nickname` varchar(20) NOT NULL DEFAULT '',
  `gender` varchar(2) DEFAULT '',
  `state` varchar(20) NOT NULL DEFAULT '',
  `city` varchar(20) NOT NULL DEFAULT '',
  `country` varchar(20) NOT NULL DEFAULT '',
  `avatar` varchar(500) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  KEY `uniacid` (`uniacid`),
  KEY `openid` (`openid`),
  KEY `proxy` (`proxy`),
  KEY `nickname` (`nickname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mbrp_gifts`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mbrp_gifts`;
CREATE TABLE `ims_mbrp_gifts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(20) NOT NULL,
  `type` varchar(20) NOT NULL COMMENT 'cash - 现金红包, coupon - 券类',
  `tag` text NOT NULL,
  `remark` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mbrp_helps`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mbrp_helps`;
CREATE TABLE `ims_mbrp_helps` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `activity` int(10) unsigned NOT NULL,
  `owner` int(10) unsigned NOT NULL,
  `helper` int(10) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `activity` (`activity`),
  KEY `uniacid` (`uniacid`),
  KEY `owner` (`owner`),
  KEY `helper` (`helper`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mbrp_profiles`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mbrp_profiles`;
CREATE TABLE `ims_mbrp_profiles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `name` varchar(10) NOT NULL,
  `title` varchar(20) NOT NULL,
  `value` varchar(500) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mbrp_records`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mbrp_records`;
CREATE TABLE `ims_mbrp_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `activity` int(10) unsigned NOT NULL,
  `gift` int(10) unsigned NOT NULL,
  `fee` varchar(20) NOT NULL DEFAULT '',
  `log` varchar(500) NOT NULL DEFAULT '',
  `status` varchar(20) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `completed` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `activity` (`activity`),
  KEY `gift` (`gift`),
  KEY `log` (`log`(333)),
  KEY `uniacid` (`uniacid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mbrp_trades`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mbrp_trades`;
CREATE TABLE `ims_mbrp_trades` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `activity` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `item` varchar(20) NOT NULL,
  `status` varchar(10) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `paid` int(10) unsigned NOT NULL,
  `completed` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `activity` (`activity`),
  KEY `uid` (`uid`),
  KEY `item` (`item`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mbsk_activities`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mbsk_activities`;
CREATE TABLE `ims_mbsk_activities` (
  `actid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '活动编号',
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(20) NOT NULL COMMENT '活动名称',
  `start` int(10) unsigned NOT NULL COMMENT '开始时间',
  `end` int(10) unsigned NOT NULL COMMENT '结束时间',
  `rules` text NOT NULL COMMENT '活动规则介绍',
  `guide` varchar(255) NOT NULL COMMENT '活动指南(图文素材地址)',
  `sorry` varchar(500) NOT NULL DEFAULT '',
  `banner` varchar(500) NOT NULL COMMENT '背景图片',
  `type` varchar(10) NOT NULL COMMENT '活动类型(direct, shared)',
  `tag` text NOT NULL,
  `limit` varchar(1000) NOT NULL DEFAULT '',
  `share` varchar(1000) NOT NULL DEFAULT '',
  `shake` varchar(1000) NOT NULL,
  `page` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`actid`),
  KEY `type` (`type`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mbsk_activity_gifts`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mbsk_activity_gifts`;
CREATE TABLE `ims_mbsk_activity_gifts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `activity` int(10) unsigned NOT NULL,
  `gift` int(10) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  `rate` decimal(6,2) NOT NULL COMMENT '中奖百分比率',
  PRIMARY KEY (`id`),
  KEY `activity` (`activity`),
  KEY `gift` (`gift`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mbsk_devices`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mbsk_devices`;
CREATE TABLE `ims_mbsk_devices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `activity` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(30) NOT NULL,
  `device_id` int(10) unsigned NOT NULL,
  `uuid` varchar(50) NOT NULL,
  `major` int(10) unsigned NOT NULL,
  `minor` int(10) unsigned NOT NULL,
  `audit_status` int(11) NOT NULL,
  `audit_comment` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0: 未激活, 1:已激不活跃, 2: 活跃',
  PRIMARY KEY (`id`),
  KEY `uuid` (`uuid`,`major`,`minor`),
  KEY `uniacid` (`uniacid`),
  KEY `device_id` (`device_id`),
  KEY `activity` (`activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mbsk_fans`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mbsk_fans`;
CREATE TABLE `ims_mbsk_fans` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `openid` varchar(40) NOT NULL DEFAULT '',
  `proxy` varchar(40) NOT NULL DEFAULT '',
  `unionid` varchar(40) NOT NULL DEFAULT '',
  `nickname` varchar(20) NOT NULL DEFAULT '',
  `gender` varchar(2) DEFAULT '',
  `state` varchar(20) NOT NULL DEFAULT '',
  `city` varchar(20) NOT NULL DEFAULT '',
  `country` varchar(20) NOT NULL DEFAULT '',
  `avatar` varchar(500) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  KEY `uniacid` (`uniacid`),
  KEY `openid` (`openid`),
  KEY `proxy` (`proxy`),
  KEY `nickname` (`nickname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mbsk_gifts`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mbsk_gifts`;
CREATE TABLE `ims_mbsk_gifts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(20) NOT NULL,
  `type` varchar(20) NOT NULL COMMENT 'cash - 现金红包, coupon - 券类',
  `tag` text NOT NULL,
  `remark` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mbsk_profiles`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mbsk_profiles`;
CREATE TABLE `ims_mbsk_profiles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `name` varchar(10) NOT NULL,
  `title` varchar(20) NOT NULL,
  `value` varchar(500) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mbsk_records`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mbsk_records`;
CREATE TABLE `ims_mbsk_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `activity` int(10) unsigned NOT NULL,
  `gift` int(10) unsigned NOT NULL,
  `fee` varchar(20) NOT NULL DEFAULT '',
  `log` varchar(500) NOT NULL DEFAULT '',
  `status` varchar(20) NOT NULL,
  `device` int(10) unsigned NOT NULL DEFAULT '0',
  `distance` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `created` int(10) unsigned NOT NULL,
  `completed` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `activity` (`activity`),
  KEY `gift` (`gift`),
  KEY `log` (`log`(333)),
  KEY `uniacid` (`uniacid`),
  KEY `status` (`status`),
  KEY `device` (`device`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mc_card`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_card`;
CREATE TABLE `ims_mc_card` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `color` varchar(255) NOT NULL DEFAULT '',
  `background` varchar(255) NOT NULL DEFAULT '',
  `logo` varchar(255) NOT NULL DEFAULT '',
  `format` varchar(50) NOT NULL DEFAULT '',
  `fields` varchar(1000) NOT NULL DEFAULT '',
  `snpos` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `business` text NOT NULL,
  `description` varchar(512) NOT NULL,
  `discount_type` tinyint(3) unsigned NOT NULL,
  `discount` varchar(3000) NOT NULL,
  `grant` varchar(200) NOT NULL,
  `grant_rate` int(10) unsigned NOT NULL,
  `nums_status` tinyint(3) unsigned NOT NULL,
  `nums_text` varchar(15) NOT NULL,
  `nums` varchar(1000) NOT NULL,
  `times_status` tinyint(3) unsigned NOT NULL,
  `times_text` varchar(15) NOT NULL,
  `times` varchar(1000) NOT NULL,
  `recharge` varchar(500) NOT NULL,
  `offset_rate` int(10) unsigned NOT NULL,
  `offset_max` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mc_card_care`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_card_care`;
CREATE TABLE `ims_mc_card_care` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(30) NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `groupid` int(10) unsigned NOT NULL,
  `credit1` int(10) unsigned NOT NULL,
  `credit2` int(10) unsigned NOT NULL,
  `couponid` int(10) unsigned NOT NULL,
  `granttime` int(10) unsigned NOT NULL,
  `days` int(10) unsigned NOT NULL,
  `time` tinyint(3) unsigned NOT NULL,
  `show_in_card` tinyint(3) unsigned NOT NULL,
  `content` varchar(1000) NOT NULL,
  `sms_notice` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mc_card_credit_set`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_card_credit_set`;
CREATE TABLE `ims_mc_card_credit_set` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `sign` varchar(1000) NOT NULL,
  `share` varchar(500) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mc_card_members`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_card_members`;
CREATE TABLE `ims_mc_card_members` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) DEFAULT NULL,
  `cid` int(10) NOT NULL DEFAULT '0',
  `cardsn` varchar(20) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `nums` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mc_card_notices`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_card_notices`;
CREATE TABLE `ims_mc_card_notices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `title` varchar(30) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `groupid` int(10) unsigned NOT NULL,
  `content` text NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mc_card_notices_unread`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_card_notices_unread`;
CREATE TABLE `ims_mc_card_notices_unread` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `notice_id` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `is_new` tinyint(3) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`),
  KEY `notice_id` (`notice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mc_card_recommend`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_card_recommend`;
CREATE TABLE `ims_mc_card_recommend` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(30) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mc_card_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_card_record`;
CREATE TABLE `ims_mc_card_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `type` varchar(15) NOT NULL,
  `model` tinyint(3) unsigned NOT NULL,
  `fee` decimal(10,2) unsigned NOT NULL,
  `tag` varchar(10) NOT NULL,
  `note` varchar(255) NOT NULL,
  `remark` varchar(200) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mc_card_sign_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_card_sign_record`;
CREATE TABLE `ims_mc_card_sign_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `credit` int(10) unsigned NOT NULL,
  `is_grant` tinyint(3) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mc_cash_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_cash_record`;
CREATE TABLE `ims_mc_cash_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `clerk_id` int(10) unsigned NOT NULL,
  `store_id` int(10) unsigned NOT NULL,
  `fee` decimal(10,2) unsigned NOT NULL,
  `final_fee` decimal(10,2) unsigned NOT NULL,
  `credit1` int(10) unsigned NOT NULL,
  `credit1_fee` decimal(10,2) unsigned NOT NULL,
  `credit2` decimal(10,2) unsigned NOT NULL,
  `cash` decimal(10,2) unsigned NOT NULL,
  `return_cash` decimal(10,2) unsigned NOT NULL,
  `final_cash` decimal(10,2) unsigned NOT NULL,
  `remark` varchar(255) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mc_chats_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_chats_record`;
CREATE TABLE `ims_mc_chats_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `flag` tinyint(3) unsigned NOT NULL,
  `openid` varchar(32) NOT NULL,
  `msgtype` varchar(15) NOT NULL,
  `content` varchar(10000) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`acid`),
  KEY `openid` (`openid`),
  KEY `createtime` (`createtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mc_credits_recharge`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_credits_recharge`;
CREATE TABLE `ims_mc_credits_recharge` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `tid` varchar(64) NOT NULL,
  `transid` varchar(30) NOT NULL,
  `fee` varchar(10) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `type` varchar(15) NOT NULL,
  `tag` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid_uid` (`uniacid`,`uid`),
  KEY `idx_tid` (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mc_credits_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_credits_record`;
CREATE TABLE `ims_mc_credits_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `uniacid` int(11) NOT NULL,
  `credittype` varchar(10) NOT NULL DEFAULT '',
  `num` decimal(10,2) NOT NULL,
  `operator` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `remark` varchar(200) NOT NULL DEFAULT '',
  `module` varchar(30) NOT NULL,
  `clerk_id` int(10) unsigned NOT NULL,
  `store_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mc_fans_groups`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_fans_groups`;
CREATE TABLE `ims_mc_fans_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `groups` varchar(10000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mc_groups`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_groups`;
CREATE TABLE `ims_mc_groups` (
  `groupid` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(20) NOT NULL DEFAULT '',
  `orderlist` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `isdefault` tinyint(4) NOT NULL DEFAULT '0',
  `credit` int(10) unsigned NOT NULL,
  PRIMARY KEY (`groupid`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mc_handsel`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_handsel`;
CREATE TABLE `ims_mc_handsel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `touid` int(10) unsigned NOT NULL,
  `fromuid` varchar(32) NOT NULL,
  `module` varchar(30) NOT NULL,
  `sign` varchar(100) NOT NULL,
  `action` varchar(20) NOT NULL,
  `credit_value` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`touid`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mc_mapping_fans`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_mapping_fans`;
CREATE TABLE `ims_mc_mapping_fans` (
  `fanid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `acid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `openid` varchar(50) NOT NULL,
  `salt` char(8) NOT NULL DEFAULT '',
  `follow` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `followtime` int(10) unsigned NOT NULL,
  `unfollowtime` int(10) unsigned NOT NULL,
  `tag` varchar(1000) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `groupid` int(10) unsigned NOT NULL,
  `updatetime` int(10) unsigned DEFAULT NULL,
  `unionid` varchar(64) NOT NULL,
  PRIMARY KEY (`fanid`),
  KEY `acid` (`acid`),
  KEY `uniacid` (`uniacid`),
  KEY `openid` (`openid`),
  KEY `updatetime` (`updatetime`),
  KEY `nickname` (`nickname`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mc_mapping_ucenter`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_mapping_ucenter`;
CREATE TABLE `ims_mc_mapping_ucenter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `centeruid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mc_mass_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_mass_record`;
CREATE TABLE `ims_mc_mass_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `groupname` varchar(50) NOT NULL,
  `fansnum` int(10) unsigned NOT NULL,
  `msgtype` varchar(10) NOT NULL,
  `content` varchar(10000) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `group` int(10) NOT NULL,
  `attach_id` int(10) unsigned NOT NULL,
  `media_id` varchar(100) NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `cron_id` int(10) unsigned NOT NULL,
  `sendtime` int(10) unsigned NOT NULL,
  `finalsendtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`acid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mc_members`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_members`;
CREATE TABLE `ims_mc_members` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `mobile` varchar(11) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `salt` varchar(8) NOT NULL DEFAULT '',
  `groupid` int(11) NOT NULL DEFAULT '0',
  `credit1` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `credit2` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `credit3` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `credit4` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `credit5` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `createtime` int(10) unsigned NOT NULL,
  `realname` varchar(10) NOT NULL DEFAULT '',
  `nickname` varchar(20) NOT NULL DEFAULT '',
  `avatar` varchar(255) NOT NULL DEFAULT '',
  `qq` varchar(15) NOT NULL DEFAULT '',
  `vip` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `gender` tinyint(1) NOT NULL DEFAULT '0',
  `birthyear` smallint(6) unsigned NOT NULL DEFAULT '0',
  `birthmonth` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `birthday` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `constellation` varchar(10) NOT NULL DEFAULT '',
  `zodiac` varchar(5) NOT NULL DEFAULT '',
  `telephone` varchar(15) NOT NULL DEFAULT '',
  `idcard` varchar(30) NOT NULL DEFAULT '',
  `studentid` varchar(50) NOT NULL DEFAULT '',
  `grade` varchar(10) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `zipcode` varchar(10) NOT NULL DEFAULT '',
  `nationality` varchar(30) NOT NULL DEFAULT '',
  `resideprovince` varchar(30) NOT NULL DEFAULT '',
  `residecity` varchar(30) NOT NULL DEFAULT '',
  `residedist` varchar(30) NOT NULL DEFAULT '',
  `graduateschool` varchar(50) NOT NULL DEFAULT '',
  `company` varchar(50) NOT NULL DEFAULT '',
  `education` varchar(10) NOT NULL DEFAULT '',
  `occupation` varchar(30) NOT NULL DEFAULT '',
  `position` varchar(30) NOT NULL DEFAULT '',
  `revenue` varchar(10) NOT NULL DEFAULT '',
  `affectivestatus` varchar(30) NOT NULL DEFAULT '',
  `lookingfor` varchar(255) NOT NULL DEFAULT '',
  `bloodtype` varchar(5) NOT NULL DEFAULT '',
  `height` varchar(5) NOT NULL DEFAULT '',
  `weight` varchar(5) NOT NULL DEFAULT '',
  `alipay` varchar(30) NOT NULL DEFAULT '',
  `msn` varchar(30) NOT NULL DEFAULT '',
  `taobao` varchar(30) NOT NULL DEFAULT '',
  `site` varchar(30) NOT NULL DEFAULT '',
  `bio` text NOT NULL,
  `interest` text NOT NULL,
  `credit6` decimal(10,2) NOT NULL,
  PRIMARY KEY (`uid`),
  KEY `groupid` (`groupid`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mc_member_address`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_member_address`;
CREATE TABLE `ims_mc_member_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(50) unsigned NOT NULL,
  `username` varchar(20) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `zipcode` varchar(6) NOT NULL,
  `province` varchar(32) NOT NULL,
  `city` varchar(32) NOT NULL,
  `district` varchar(32) NOT NULL,
  `address` varchar(512) NOT NULL,
  `isdefault` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uinacid` (`uniacid`),
  KEY `idx_uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mc_member_fields`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_member_fields`;
CREATE TABLE `ims_mc_member_fields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `fieldid` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `available` tinyint(1) NOT NULL,
  `displayorder` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_fieldid` (`fieldid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mc_oauth_fans`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mc_oauth_fans`;
CREATE TABLE `ims_mc_oauth_fans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `oauth_openid` varchar(50) NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_oauthopenid_acid` (`oauth_openid`,`acid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepohn_baoyue`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepohn_baoyue`;
CREATE TABLE `ims_meepohn_baoyue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `tid` varchar(20) NOT NULL DEFAULT '0' COMMENT '֩եҠۅ',
  `openid` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `fee` int(10) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `paytype` varchar(20) NOT NULL,
  `transid` varchar(30) NOT NULL DEFAULT '0',
  `time` int(12) NOT NULL,
  `starttime` int(10) NOT NULL DEFAULT '0',
  `endtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepohn_tuijian`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepohn_tuijian`;
CREATE TABLE `ims_meepohn_tuijian` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '0',
  `openid` varchar(200) NOT NULL DEFAULT '',
  `payment` int(10) NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepohongniangphotos`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepohongniangphotos`;
CREATE TABLE `ims_meepohongniangphotos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `url` varchar(200) NOT NULL,
  `description` varchar(200) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepomailattachment`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepomailattachment`;
CREATE TABLE `ims_meepomailattachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `attachmentname` varchar(50) NOT NULL COMMENT '附件名称',
  `thumb` varchar(255) NOT NULL COMMENT '附件路径',
  `description` varchar(500) NOT NULL COMMENT '附件描述',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '附加排序',
  `isshow` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepoweixiangqin_slide`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepoweixiangqin_slide`;
CREATE TABLE `ims_meepoweixiangqin_slide` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(200) NOT NULL DEFAULT '',
  `attachment` varchar(100) NOT NULL DEFAULT '',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_bbs_adv`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_bbs_adv`;
CREATE TABLE `ims_meepo_bbs_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  `typeid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`),
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_bbs_api`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_bbs_api`;
CREATE TABLE `ims_meepo_bbs_api` (
  `id` int(11) NOT NULL,
  `title` varchar(32) NOT NULL DEFAULT '',
  `description` varchar(132) NOT NULL DEFAULT '',
  `file` varchar(132) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_bbs_begging`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_bbs_begging`;
CREATE TABLE `ims_meepo_bbs_begging` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tid` varchar(64) NOT NULL DEFAULT '',
  `fid` int(11) unsigned NOT NULL DEFAULT '0',
  `type` varchar(32) NOT NULL DEFAULT '',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `fopenid` varchar(42) NOT NULL DEFAULT '',
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `fee` float(6,2) unsigned NOT NULL DEFAULT '0.00',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `transid` varchar(32) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `thumb` text NOT NULL,
  `ttid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_bbs_blacklist`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_bbs_blacklist`;
CREATE TABLE `ims_meepo_bbs_blacklist` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_bbs_credit_goods`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_bbs_credit_goods`;
CREATE TABLE `ims_meepo_bbs_credit_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT '0',
  `deadline` datetime NOT NULL,
  `per_user_limit` int(11) NOT NULL DEFAULT '0',
  `cost` int(11) NOT NULL DEFAULT '0',
  `cost_type` int(11) NOT NULL DEFAULT '1' COMMENT '1系统积分 2会员积分 4,8等留作扩展',
  `price` int(11) NOT NULL DEFAULT '100',
  `content` text NOT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_bbs_credit_request`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_bbs_credit_request`;
CREATE TABLE `ims_meepo_bbs_credit_request` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `realname` varchar(200) NOT NULL,
  `mobile` varchar(200) NOT NULL,
  `residedist` varchar(200) NOT NULL,
  `note` varchar(200) NOT NULL,
  `goods_id` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_bbs_ec_chong_log`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_bbs_ec_chong_log`;
CREATE TABLE `ims_meepo_bbs_ec_chong_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tid` varchar(50) NOT NULL DEFAULT '',
  `type` varchar(32) NOT NULL DEFAULT '',
  `fee` float unsigned NOT NULL DEFAULT '0',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `num` float unsigned NOT NULL DEFAULT '0',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `transid` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_bbs_home_message`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_bbs_home_message`;
CREATE TABLE `ims_meepo_bbs_home_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fromopenid` varchar(50) NOT NULL DEFAULT '',
  `toopenid` varchar(50) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `tid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fromopenid` (`fromopenid`) USING BTREE,
  KEY `toopenid` (`toopenid`) USING BTREE,
  KEY `tid` (`tid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_bbs_log`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_bbs_log`;
CREATE TABLE `ims_meepo_bbs_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(42) NOT NULL DEFAULT '',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `log` text NOT NULL,
  `type` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_bbs_msg_template`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_bbs_msg_template`;
CREATE TABLE `ims_meepo_bbs_msg_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `title` varchar(500) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '模板标题',
  `tpl_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '模板id',
  `template` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '模板内容',
  `tags` varchar(1000) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '模板标签',
  `set` text NOT NULL,
  `type` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_bbs_msg_template_data`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_bbs_msg_template_data`;
CREATE TABLE `ims_meepo_bbs_msg_template_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(52) NOT NULL DEFAULT '',
  `set` text NOT NULL,
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `tpl_id` varchar(124) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_bbs_navs`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_bbs_navs`;
CREATE TABLE `ims_meepo_bbs_navs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `icon` varchar(132) NOT NULL DEFAULT '',
  `name` varchar(32) NOT NULL DEFAULT '',
  `link` varchar(132) NOT NULL DEFAULT '',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `displayorder` int(11) unsigned NOT NULL DEFAULT '0',
  `enabled` tinyint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_bbs_o2o_user`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_bbs_o2o_user`;
CREATE TABLE `ims_meepo_bbs_o2o_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `shopid` int(11) unsigned NOT NULL DEFAULT '0',
  `openid` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `set` text NOT NULL,
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `acid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_bbs_o2o_user_log`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_bbs_o2o_user_log`;
CREATE TABLE `ims_meepo_bbs_o2o_user_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `type` varchar(32) NOT NULL DEFAULT '',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `cid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_bbs_reply_ups`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_bbs_reply_ups`;
CREATE TABLE `ims_meepo_bbs_reply_ups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(11) unsigned NOT NULL DEFAULT '0',
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `caretetime` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_bbs_rss`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_bbs_rss`;
CREATE TABLE `ims_meepo_bbs_rss` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `title` varchar(32) NOT NULL DEFAULT '',
  `url` varchar(132) NOT NULL DEFAULT '',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `fid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_bbs_set`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_bbs_set`;
CREATE TABLE `ims_meepo_bbs_set` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `set` text,
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_bbs_share`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_bbs_share`;
CREATE TABLE `ims_meepo_bbs_share` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `set` text NOT NULL,
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  `tid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_bbs_task`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_bbs_task`;
CREATE TABLE `ims_meepo_bbs_task` (
  `taskid` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `available` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  `note` text NOT NULL,
  `num` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `maxnum` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `image` varchar(150) NOT NULL DEFAULT '',
  `filename` varchar(50) NOT NULL DEFAULT '',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `nexttime` int(10) unsigned NOT NULL DEFAULT '0',
  `nexttype` varchar(20) NOT NULL DEFAULT '',
  `credit` smallint(6) NOT NULL DEFAULT '0',
  `displayorder` smallint(6) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`taskid`),
  KEY `displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_bbs_task_user`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_bbs_task_user`;
CREATE TABLE `ims_meepo_bbs_task_user` (
  `uid` mediumint(8) unsigned NOT NULL,
  `username` char(15) NOT NULL DEFAULT '',
  `taskid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `credit` smallint(6) NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `isignore` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`taskid`),
  KEY `isignore` (`isignore`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_bbs_threadclass`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_bbs_threadclass`;
CREATE TABLE `ims_meepo_bbs_threadclass` (
  `typeid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fid` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `displayorder` int(11) unsigned NOT NULL DEFAULT '0',
  `icon` varchar(255) NOT NULL DEFAULT '',
  `moderators` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `content` text,
  `group` varchar(132) DEFAULT NULL,
  `look_group` varchar(232) DEFAULT NULL,
  `post_group` varchar(232) DEFAULT NULL,
  `isgood` tinyint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`typeid`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `fid` (`fid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_bbs_topic`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_bbs_topic`;
CREATE TABLE `ims_meepo_bbs_topic` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `tid` int(11) unsigned NOT NULL DEFAULT '0',
  `title` varchar(132) DEFAULT NULL,
  `content` text,
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `tid` (`tid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_bbs_topics`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_bbs_topics`;
CREATE TABLE `ims_meepo_bbs_topics` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `title` varchar(320) DEFAULT NULL,
  `tab` varchar(32) DEFAULT NULL,
  `last_reply_at` int(11) unsigned NOT NULL DEFAULT '0',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  `replycredit` int(11) unsigned NOT NULL DEFAULT '0',
  `tags` varchar(150) DEFAULT NULL,
  `ratetimes` int(11) unsigned NOT NULL DEFAULT '0',
  `rate` int(11) unsigned NOT NULL DEFAULT '0',
  `invisible` tinyint(1) DEFAULT NULL,
  `tid` int(11) DEFAULT NULL,
  `fid` int(11) DEFAULT NULL,
  `content` text,
  `rnum` int(11) unsigned NOT NULL DEFAULT '0',
  `lnum` int(11) unsigned NOT NULL DEFAULT '0',
  `thumb` text,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `tid` (`tid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_bbs_topic_like`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_bbs_topic_like`;
CREATE TABLE `ims_meepo_bbs_topic_like` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(40) NOT NULL DEFAULT '',
  `tid` int(11) unsigned NOT NULL DEFAULT '0',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `num` int(11) unsigned NOT NULL DEFAULT '0',
  `fid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`) USING BTREE,
  KEY `fid` (`fid`) USING BTREE,
  KEY `openid` (`openid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
--  Table structure for `ims_meepo_bbs_topic_read`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_bbs_topic_read`;
CREATE TABLE `ims_meepo_bbs_topic_read` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(40) NOT NULL DEFAULT '',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `tid` int(11) unsigned NOT NULL DEFAULT '0',
  `num` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`) USING BTREE,
  KEY `tid` (`tid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_bbs_topic_replie`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_bbs_topic_replie`;
CREATE TABLE `ims_meepo_bbs_topic_replie` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `content` text,
  `create_at` int(11) unsigned NOT NULL DEFAULT '0',
  `tid` int(11) unsigned NOT NULL DEFAULT '0',
  `thumb` text NOT NULL,
  `fid` int(11) unsigned NOT NULL DEFAULT '0',
  `beggingid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `tid` (`tid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_bbs_topic_share`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_bbs_topic_share`;
CREATE TABLE `ims_meepo_bbs_topic_share` (
  `id` int(11) NOT NULL,
  `openid` varchar(40) NOT NULL DEFAULT '',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `tid` int(11) unsigned NOT NULL DEFAULT '0',
  `num` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`) USING BTREE,
  KEY `tid` (`tid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_bbs_user`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_bbs_user`;
CREATE TABLE `ims_meepo_bbs_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(42) NOT NULL DEFAULT '',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `online` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(32) NOT NULL DEFAULT '',
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `acid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_danmu_data`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_danmu_data`;
CREATE TABLE `ims_meepo_danmu_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `url` varchar(250) NOT NULL,
  `click` int(11) DEFAULT '0',
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_danmu_set`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_danmu_set`;
CREATE TABLE `ims_meepo_danmu_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `title` varchar(120) NOT NULL,
  `logo` varchar(120) NOT NULL,
  `wx_name` varchar(80) NOT NULL,
  `wx_num` varchar(100) NOT NULL,
  `share_title` varchar(200) NOT NULL,
  `share_content` text NOT NULL,
  `share_img` varchar(420) NOT NULL,
  `num` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_fen_basic`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_fen_basic`;
CREATE TABLE `ims_meepo_fen_basic` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_fen_click`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_fen_click`;
CREATE TABLE `ims_meepo_fen_click` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` mediumint(8) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `openid` varchar(20) NOT NULL,
  `time` int(11) NOT NULL,
  `num` int(11) NOT NULL DEFAULT '0',
  `success` tinyint(2) NOT NULL,
  `father` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `father` (`father`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_fen_ip_log`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_fen_ip_log`;
CREATE TABLE `ims_meepo_fen_ip_log` (
  `log_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uniacid` mediumint(8) NOT NULL,
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`log_id`),
  KEY `uid` (`uid`),
  KEY `time` (`time`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_fen_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_fen_reply`;
CREATE TABLE `ims_meepo_fen_reply` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `old_basic_id` int(11) NOT NULL,
  `new_basic_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_fen_set`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_fen_set`;
CREATE TABLE `ims_meepo_fen_set` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `pan_name` varchar(150) NOT NULL,
  `fans_num` int(11) NOT NULL,
  `couponid` int(5) NOT NULL,
  `set` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_fen_user`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_fen_user`;
CREATE TABLE `ims_meepo_fen_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL,
  `ecs_userid` int(11) unsigned NOT NULL,
  `father` int(11) unsigned NOT NULL,
  `couponid` int(11) NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `father` (`father`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_hongnianglikes`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_hongnianglikes`;
CREATE TABLE `ims_meepo_hongnianglikes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `openid` varchar(100) NOT NULL,
  `toopenid` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `createtime` int(12) NOT NULL,
  `weid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_hongniangonoff`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_hongniangonoff`;
CREATE TABLE `ims_meepo_hongniangonoff` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` int(2) NOT NULL DEFAULT '1',
  `weid` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_hongniangset`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_hongniangset`;
CREATE TABLE `ims_meepo_hongniangset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `share_title` varchar(100) NOT NULL,
  `share_link` varchar(300) NOT NULL,
  `share_content` varchar(300) NOT NULL,
  `share_logo` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `headtitle` varchar(200) NOT NULL,
  `logo` varchar(60) NOT NULL,
  `weid` int(11) NOT NULL,
  `url` varchar(200) NOT NULL,
  `hnages` varchar(200) NOT NULL,
  `pay_height` varchar(12) NOT NULL DEFAULT '0' COMMENT '查看身高消费积分',
  `pay_weight` varchar(12) NOT NULL DEFAULT '0' COMMENT '查看体重消费积分',
  `pay_telephone` varchar(12) NOT NULL DEFAULT '0' COMMENT '查看手机号码消费积分',
  `pay_carhouse` varchar(12) NOT NULL DEFAULT '0' COMMENT '查看车房状态',
  `pay_Descrip` varchar(12) NOT NULL DEFAULT '0' COMMENT '查看自我介绍',
  `pay_uitsOthers` varchar(12) NOT NULL DEFAULT '0' COMMENT '查看理想的另一半',
  `pay_uheight` varchar(12) NOT NULL DEFAULT '0' COMMENT '查看对象的身高',
  `pay_uage` varchar(12) NOT NULL DEFAULT '0' COMMENT '查看对象的年龄',
  `pay_all` varchar(12) NOT NULL DEFAULT '0' COMMENT '查看所有',
  `pay_occupation` varchar(10) NOT NULL DEFAULT '0' COMMENT '查看职业',
  `pay_revenue` varchar(10) NOT NULL DEFAULT '0' COMMENT '查看月收入',
  `pay_qq` varchar(10) NOT NULL DEFAULT '0' COMMENT '查看qq',
  `pay_wechat` varchar(10) NOT NULL DEFAULT '0' COMMENT '查看微信',
  `pay_affectivestatus` varchar(10) NOT NULL DEFAULT '0' COMMENT '查看他的情感状态',
  `pay_lxxingzuo` varchar(10) NOT NULL DEFAULT '0' COMMENT '查看理想星座',
  `share_jifen` varchar(10) NOT NULL DEFAULT '0' COMMENT '分享奖励积分',
  `header_ads` varchar(100) NOT NULL COMMENT '前台广告',
  `header_adsurl` varchar(200) NOT NULL COMMENT '首页图片链接',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_module`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_module`;
CREATE TABLE `ims_meepo_module` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(32) NOT NULL DEFAULT '',
  `set` text NOT NULL,
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_newvote`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_newvote`;
CREATE TABLE `ims_meepo_newvote` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `left_uid` int(11) unsigned NOT NULL,
  `right_uid` int(11) unsigned NOT NULL,
  `ip` varchar(80) NOT NULL,
  `time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_pai`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_pai`;
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_pai_log`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_pai_log`;
CREATE TABLE `ims_meepo_pai_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL,
  `ip` varchar(80) NOT NULL,
  `time` int(11) unsigned NOT NULL,
  `uniacid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_pai_report`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_pai_report`;
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_pai_set`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_pai_set`;
CREATE TABLE `ims_meepo_pai_set` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `title` varchar(180) NOT NULL,
  `chongfu` tinyint(3) NOT NULL,
  `share_url` varchar(200) NOT NULL,
  `bao_name` varchar(150) NOT NULL,
  `shen_name` varchar(150) NOT NULL,
  `zhuban_url` varchar(100) DEFAULT NULL,
  `zhuban_title` varchar(32) DEFAULT NULL,
  `tuandui_url` varchar(100) DEFAULT NULL,
  `tuandui_title` varchar(32) DEFAULT NULL,
  `zanzhu_url` varchar(100) DEFAULT NULL,
  `zanzhu_title` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_sexy_set`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_sexy_set`;
CREATE TABLE `ims_meepo_sexy_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `url` varchar(100) NOT NULL,
  `num` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_meepo_sms_news`
-- ----------------------------
DROP TABLE IF EXISTS `ims_meepo_sms_news`;
CREATE TABLE `ims_meepo_sms_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `news` varchar(10) NOT NULL DEFAULT '',
  `openid` varchar(200) NOT NULL DEFAULT '',
  `createtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_menu_event`
-- ----------------------------
DROP TABLE IF EXISTS `ims_menu_event`;
CREATE TABLE `ims_menu_event` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `keyword` varchar(30) NOT NULL,
  `type` varchar(30) NOT NULL,
  `picmd5` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `picmd5` (`picmd5`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_message_list`
-- ----------------------------
DROP TABLE IF EXISTS `ims_message_list`;
CREATE TABLE `ims_message_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `weid` int(11) NOT NULL,
  `nickname` varchar(30) DEFAULT NULL,
  `info` varchar(200) DEFAULT NULL,
  `fid` int(11) DEFAULT '0',
  `isshow` tinyint(1) DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `from_user` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mobilenumber`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mobilenumber`;
CREATE TABLE `ims_mobilenumber` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `dateline` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_modules`
-- ----------------------------
DROP TABLE IF EXISTS `ims_modules`;
CREATE TABLE `ims_modules` (
  `mid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL,
  `version` varchar(10) NOT NULL DEFAULT '',
  `ability` varchar(500) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `author` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `settings` tinyint(1) NOT NULL DEFAULT '0',
  `subscribes` varchar(500) NOT NULL DEFAULT '',
  `handles` varchar(500) NOT NULL DEFAULT '',
  `isrulefields` tinyint(1) NOT NULL DEFAULT '0',
  `issystem` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `target` int(10) unsigned NOT NULL DEFAULT '0',
  `iscard` tinyint(3) unsigned NOT NULL,
  `permissions` varchar(5000) NOT NULL,
  PRIMARY KEY (`mid`),
  KEY `idx_name` (`name`),
  KEY `idx_issystem` (`issystem`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_modules_bindings`
-- ----------------------------
DROP TABLE IF EXISTS `ims_modules_bindings`;
CREATE TABLE `ims_modules_bindings` (
  `eid` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(30) NOT NULL DEFAULT '',
  `entry` varchar(10) NOT NULL DEFAULT '',
  `call` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL,
  `do` varchar(30) NOT NULL,
  `state` varchar(200) NOT NULL,
  `direct` int(11) NOT NULL DEFAULT '0',
  `url` varchar(100) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `displayorder` tinyint(255) unsigned NOT NULL,
  PRIMARY KEY (`eid`),
  KEY `idx_module` (`module`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_moncom_neighbors`
-- ----------------------------
DROP TABLE IF EXISTS `ims_moncom_neighbors`;
CREATE TABLE `ims_moncom_neighbors` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `nname` varchar(200) NOT NULL,
  `banner_pic` varchar(200) NOT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_egg`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_egg`;
CREATE TABLE `ims_mon_egg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `weid` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `starttime` int(10) DEFAULT NULL,
  `endtime` int(10) DEFAULT NULL,
  `intro` text,
  `music` varchar(500) DEFAULT NULL,
  `banner_bg` varchar(1000) DEFAULT NULL,
  `bg_img` varchar(1000) DEFAULT NULL,
  `share_bg` varchar(1000) DEFAULT NULL,
  `day_count` int(10) DEFAULT NULL,
  `prize_limit` int(10) DEFAULT NULL,
  `dpassword` varchar(20) DEFAULT NULL,
  `follow_url` varchar(1000) DEFAULT NULL,
  `copyright` varchar(100) NOT NULL,
  `follow_dlg_tip` varchar(500) DEFAULT NULL,
  `follow_btn_name` varchar(20) DEFAULT NULL,
  `share_enable` int(1) DEFAULT '0',
  `share_times` int(10) DEFAULT '0',
  `share_award_count` int(10) DEFAULT '0',
  `new_icon` varchar(200) DEFAULT NULL,
  `new_content` varchar(200) DEFAULT NULL,
  `new_title` varchar(200) DEFAULT NULL,
  `share_title` varchar(200) DEFAULT NULL,
  `share_icon` varchar(200) DEFAULT NULL,
  `share_content` varchar(200) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  `updatetime` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_egg_prize`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_egg_prize`;
CREATE TABLE `ims_mon_egg_prize` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sn` varchar(100) DEFAULT NULL,
  `egid` int(10) DEFAULT NULL,
  `plevel` varchar(50) DEFAULT NULL,
  `pname` varchar(50) DEFAULT NULL,
  `pimg` varchar(500) DEFAULT NULL,
  `ptype` int(1) DEFAULT NULL,
  `pb` int(10) DEFAULT '0',
  `jf` int(10) DEFAULT '0',
  `pcount` int(10) DEFAULT NULL,
  `display_order` int(3) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_egg_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_egg_record`;
CREATE TABLE `ims_mon_egg_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `egid` int(10) NOT NULL,
  `pid` int(10) DEFAULT NULL,
  `pname` varchar(200) DEFAULT NULL,
  `uid` int(10) DEFAULT NULL,
  `openid` varchar(200) NOT NULL,
  `status` int(1) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  `dhtime` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_egg_share`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_egg_share`;
CREATE TABLE `ims_mon_egg_share` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `egid` int(10) NOT NULL,
  `uid` int(10) DEFAULT NULL,
  `openid` varchar(300) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_egg_user`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_egg_user`;
CREATE TABLE `ims_mon_egg_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `egid` int(10) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `headimgurl` varchar(200) NOT NULL,
  `createtime` int(10) DEFAULT '0',
  `uname` varchar(100) DEFAULT NULL,
  `tel` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_fool`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_fool`;
CREATE TABLE `ims_mon_fool` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `follow_url` varchar(200) NOT NULL,
  `new_title` varchar(200) NOT NULL,
  `new_icon` varchar(200) NOT NULL,
  `new_content` varchar(200) NOT NULL,
  `share_title` varchar(200) NOT NULL,
  `share_icon` varchar(200) NOT NULL,
  `share_content` varchar(200) NOT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_house`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_house`;
CREATE TABLE `ims_mon_house` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `news_title` varchar(255) NOT NULL,
  `lpaddress` varchar(255) NOT NULL,
  `price` int(10) NOT NULL,
  `sltel` varchar(25) NOT NULL,
  `zxtel` varchar(25) NOT NULL,
  `news_icon` varchar(255) NOT NULL,
  `news_content` varchar(500) NOT NULL,
  `title` varchar(100) NOT NULL,
  `kptime` int(10) DEFAULT '0',
  `rztime` int(10) DEFAULT '0',
  `kfs` varchar(100) NOT NULL,
  `cover_img` varchar(200) NOT NULL,
  `overview_img` varchar(200) NOT NULL,
  `intro_img` varchar(200) NOT NULL,
  `intro` varchar(2000) DEFAULT NULL,
  `order_title` varchar(50) NOT NULL,
  `order_remark` varchar(100) NOT NULL,
  `share_icon` varchar(200) NOT NULL,
  `share_title` varchar(200) NOT NULL,
  `share_content` varchar(500) NOT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_house_agent`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_house_agent`;
CREATE TABLE `ims_mon_house_agent` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT '0',
  `gname` varchar(255) NOT NULL,
  `headimgurl` varchar(255) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `workyear` int(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_hid` (`hid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_house_item`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_house_item`;
CREATE TABLE `ims_mon_house_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `iname` varchar(255) NOT NULL,
  `icontent` varchar(255) NOT NULL,
  `sort` int(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_hid` (`hid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_house_order`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_house_order`;
CREATE TABLE `ims_mon_house_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT '0',
  `uname` varchar(20) NOT NULL,
  `createtime` int(10) DEFAULT '0',
  `tel` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_hid` (`hid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_house_timage`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_house_timage`;
CREATE TABLE `ims_mon_house_timage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT '0',
  `tid` int(11) DEFAULT '0',
  `pre_img` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_hid` (`hid`),
  KEY `tid` (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_house_type`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_house_type`;
CREATE TABLE `ims_mon_house_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `tname` varchar(255) NOT NULL,
  `sort` int(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_hid` (`hid`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_orderform`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_orderform`;
CREATE TABLE `ims_mon_orderform` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `weid` int(11) NOT NULL,
  `oname` varchar(200) DEFAULT NULL,
  `pname` varchar(200) DEFAULT NULL,
  `odesc` text,
  `address` varchar(200) DEFAULT NULL,
  `p_tel` varchar(50) DEFAULT NULL,
  `p_desc` text,
  `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000',
  `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000',
  `location_p` varchar(100) NOT NULL,
  `location_c` varchar(100) NOT NULL,
  `location_a` varchar(100) NOT NULL,
  `p_title_pg` varchar(500) DEFAULT NULL,
  `p_titile_url` varchar(500) DEFAULT NULL,
  `copyright` varchar(50) DEFAULT NULL,
  `follow_url` varchar(200) DEFAULT NULL,
  `new_title` varchar(200) DEFAULT NULL,
  `new_icon` varchar(200) DEFAULT NULL,
  `new_content` varchar(200) DEFAULT NULL,
  `share_title` varchar(200) DEFAULT NULL,
  `share_icon` varchar(200) DEFAULT NULL,
  `share_content` varchar(200) DEFAULT NULL,
  `createtime` int(10) DEFAULT NULL,
  `updatetime` int(10) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `emailenable` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_orderform_item`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_orderform_item`;
CREATE TABLE `ims_mon_orderform_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fid` int(10) DEFAULT NULL,
  `iname` varchar(200) DEFAULT NULL,
  `ititle` varchar(500) DEFAULT NULL,
  `ititle_pg` varchar(500) DEFAULT NULL,
  `ititle_url` varchar(500) DEFAULT NULL,
  `y_price` float(6,2) DEFAULT NULL,
  `x_price` float(6,2) DEFAULT NULL,
  `i_desc` text,
  `i_summary` varchar(50) DEFAULT NULL,
  `o_tel` varchar(50) DEFAULT NULL,
  `pay_type` int(1) DEFAULT NULL,
  `o_num` int(3) DEFAULT NULL,
  `displayorder` int(3) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_orderform_order`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_orderform_order`;
CREATE TABLE `ims_mon_orderform_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderno` varchar(100) DEFAULT NULL,
  `outno` varchar(300) DEFAULT NULL,
  `acid` varchar(100) DEFAULT NULL,
  `fid` int(10) DEFAULT NULL,
  `iid` int(10) DEFAULT NULL,
  `openid` varchar(200) NOT NULL,
  `nickname` varchar(300) DEFAULT NULL,
  `headimgurl` varchar(300) DEFAULT NULL,
  `uname` varchar(300) DEFAULT NULL,
  `utel` varchar(50) DEFAULT NULL,
  `ordertime` int(10) DEFAULT NULL,
  `paytime` int(10) DEFAULT NULL,
  `ordernum` int(3) DEFAULT NULL,
  `o_yprice` int(3) DEFAULT NULL,
  `o_xprice` int(3) DEFAULT NULL,
  `zf_price` int(3) DEFAULT NULL,
  `js_price` int(3) DEFAULT NULL,
  `pay_type` int(3) DEFAULT NULL,
  `remark` varchar(2000) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_orderform_setting`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_orderform_setting`;
CREATE TABLE `ims_mon_orderform_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `appid` varchar(200) DEFAULT NULL,
  `appsecret` varchar(200) DEFAULT NULL,
  `mchid` varchar(100) DEFAULT NULL,
  `shkey` varchar(100) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_orderform_template`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_orderform_template`;
CREATE TABLE `ims_mon_orderform_template` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `ordertid` varchar(500) DEFAULT NULL,
  `orderenable` int(1) DEFAULT NULL,
  `payenable` int(1) DEFAULT NULL,
  `paytid` varchar(500) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_sign`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_sign`;
CREATE TABLE `ims_mon_sign` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `follow_credit` int(10) NOT NULL,
  `follow_credit_allow` int(1) DEFAULT '0',
  `leave_credit_clear` int(1) DEFAULT '0',
  `sign_credit` int(11) DEFAULT '0',
  `sync_credit` int(1) DEFAULT '0',
  `rule` varchar(2000) DEFAULT NULL,
  `starttime` int(10) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  `sin_suc_msg` varchar(200) DEFAULT NULL,
  `sin_suc_fail` varchar(200) DEFAULT NULL,
  `new_title` varchar(200) DEFAULT NULL,
  `new_icon` varchar(200) DEFAULT NULL,
  `new_content` varchar(200) DEFAULT NULL,
  `copyright` varchar(200) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_sign_award`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_sign_award`;
CREATE TABLE `ims_mon_sign_award` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(10) unsigned DEFAULT NULL,
  `uid` int(10) NOT NULL,
  `sign_type` int(2) NOT NULL,
  `serial_start_time` int(10) DEFAULT NULL,
  `serial_end_time` int(10) DEFAULT NULL,
  `serial_day` int(10) DEFAULT NULL,
  `credit` int(10) NOT NULL,
  `createtime` int(10) unsigned NOT NULL COMMENT '日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_sign_link`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_sign_link`;
CREATE TABLE `ims_mon_sign_link` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(11) unsigned DEFAULT NULL,
  `sort` int(2) DEFAULT '0',
  `link_name` varchar(50) NOT NULL,
  `link_url` varchar(50) NOT NULL,
  `createtime` int(10) unsigned NOT NULL COMMENT '日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_sign_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_sign_record`;
CREATE TABLE `ims_mon_sign_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `sid` int(10) DEFAULT '0',
  `sin_time` int(10) DEFAULT '0',
  `credit` int(10) NOT NULL,
  `sign_type` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_sign_serial`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_sign_serial`;
CREATE TABLE `ims_mon_sign_serial` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(11) unsigned DEFAULT NULL,
  `day` int(4) NOT NULL,
  `credit` int(10) DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL COMMENT '日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_sign_token`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_sign_token`;
CREATE TABLE `ims_mon_sign_token` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) unsigned DEFAULT NULL,
  `access_token` varchar(1000) NOT NULL,
  `expires_in` int(11) DEFAULT NULL,
  `createtime` int(10) unsigned NOT NULL COMMENT '日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_sign_user`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_sign_user`;
CREATE TABLE `ims_mon_sign_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(10) NOT NULL,
  `begin_sign_time` int(10) DEFAULT NULL,
  `end_sign_time` int(10) DEFAULT NULL,
  `openid` varchar(200) NOT NULL,
  `nickname` varchar(20) NOT NULL,
  `headimgurl` varchar(200) DEFAULT NULL,
  `serial_id` int(10) DEFAULT NULL,
  `credit` int(10) DEFAULT '0',
  `sin_count` int(10) DEFAULT '0',
  `sin_serial` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_wkj`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_wkj`;
CREATE TABLE `ims_mon_wkj` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `weid` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `starttime` int(10) DEFAULT NULL,
  `endtime` int(10) DEFAULT NULL,
  `p_name` varchar(100) DEFAULT NULL,
  `p_kc` int(10) DEFAULT '0',
  `p_y_price` float(10,2) DEFAULT NULL,
  `p_low_price` float(10,2) DEFAULT NULL,
  `yf_price` float(10,2) DEFAULT '0.00',
  `p_pic` varchar(200) DEFAULT NULL,
  `p_preview_pic` varchar(200) DEFAULT NULL,
  `follow_url` varchar(200) DEFAULT NULL,
  `copyright` varchar(100) NOT NULL,
  `new_title` varchar(200) DEFAULT NULL,
  `new_icon` varchar(200) DEFAULT NULL,
  `new_content` varchar(200) DEFAULT NULL,
  `share_title` varchar(200) DEFAULT NULL,
  `share_icon` varchar(200) DEFAULT NULL,
  `share_content` varchar(200) DEFAULT NULL,
  `p_url` varchar(500) DEFAULT NULL,
  `copyright_url` varchar(500) DEFAULT NULL,
  `hot_tel` varchar(50) DEFAULT NULL,
  `p_intro` varchar(1000) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  `kj_dialog_tip` varchar(1000) DEFAULT NULL,
  `rank_tip` varchar(1000) DEFAULT NULL,
  `u_fist_tip` varchar(1000) DEFAULT NULL,
  `u_already_tip` varchar(1000) DEFAULT NULL,
  `fk_fist_tip` varchar(1000) DEFAULT NULL,
  `fk_already_tip` varchar(1000) DEFAULT NULL,
  `kj_rule` varchar(1000) DEFAULT NULL,
  `pay_type` int(2) DEFAULT NULL,
  `p_model` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_wkj_firend`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_wkj_firend`;
CREATE TABLE `ims_mon_wkj_firend` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kid` int(10) NOT NULL,
  `uid` int(10) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `headimgurl` varchar(200) NOT NULL,
  `k_price` float(10,2) DEFAULT NULL,
  `kh_price` float(10,2) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  `ip` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_wkj_order`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_wkj_order`;
CREATE TABLE `ims_mon_wkj_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kid` int(10) NOT NULL,
  `uid` int(10) NOT NULL,
  `uname` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `tel` varchar(50) DEFAULT NULL,
  `openid` varchar(200) DEFAULT NULL,
  `order_no` varchar(100) DEFAULT NULL,
  `wxorder_no` varchar(100) DEFAULT NULL,
  `y_price` float(10,2) DEFAULT NULL,
  `kh_price` float(10,2) DEFAULT NULL,
  `yf_price` float(10,2) DEFAULT NULL,
  `total_price` float(10,2) DEFAULT NULL,
  `pay_type` int(2) DEFAULT NULL,
  `p_model` varchar(1000) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `wxnotify` varchar(200) DEFAULT NULL,
  `notifytime` int(10) DEFAULT '0',
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_wkj_setting`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_wkj_setting`;
CREATE TABLE `ims_mon_wkj_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `appid` varchar(200) DEFAULT NULL,
  `appsecret` varchar(200) DEFAULT NULL,
  `mchid` varchar(100) DEFAULT NULL,
  `shkey` varchar(100) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_wkj_user`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_wkj_user`;
CREATE TABLE `ims_mon_wkj_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kid` int(10) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `headimgurl` varchar(200) NOT NULL,
  `price` float(10,2) DEFAULT NULL,
  `ip` varchar(30) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_yjgz`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_yjgz`;
CREATE TABLE `ims_mon_yjgz` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) unsigned DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `banner_pic` varchar(300) NOT NULL,
  `banner_desc` varchar(1000) NOT NULL,
  `share_title` varchar(200) DEFAULT NULL,
  `share_icon` varchar(200) DEFAULT NULL,
  `share_content` varchar(200) DEFAULT NULL,
  `createtime` int(10) unsigned NOT NULL COMMENT '日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_mon_yjgz_item`
-- ----------------------------
DROP TABLE IF EXISTS `ims_mon_yjgz_item`;
CREATE TABLE `ims_mon_yjgz_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `yid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `i_desc` varchar(500) NOT NULL,
  `i_url` varchar(300) NOT NULL,
  `sort` int(3) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_music_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_music_reply`;
CREATE TABLE `ims_music_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(300) NOT NULL DEFAULT '',
  `hqurl` varchar(300) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_news_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_news_reply`;
CREATE TABLE `ims_news_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `url` varchar(255) NOT NULL,
  `displayorder` int(10) NOT NULL,
  `incontent` tinyint(1) NOT NULL DEFAULT '0',
  `author` varchar(64) NOT NULL,
  `createtime` int(10) NOT NULL,
  `parent_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_nsign_add`
-- ----------------------------
DROP TABLE IF EXISTS `ims_nsign_add`;
CREATE TABLE `ims_nsign_add` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `shop` text NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `thumb` text NOT NULL,
  `content` text NOT NULL,
  `type` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_nsign_prize`
-- ----------------------------
DROP TABLE IF EXISTS `ims_nsign_prize`;
CREATE TABLE `ims_nsign_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `name` text NOT NULL,
  `type` text NOT NULL,
  `award` text NOT NULL,
  `time` int(11) NOT NULL,
  `num` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_nsign_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_nsign_record`;
CREATE TABLE `ims_nsign_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `username` text NOT NULL,
  `today_rank` int(11) NOT NULL,
  `sign_time` int(11) NOT NULL,
  `last_sign_time` int(11) NOT NULL,
  `continue_sign_days` int(11) NOT NULL,
  `maxcontinue_sign_days` int(11) NOT NULL,
  `total_sign_num` int(11) NOT NULL,
  `maxtotal_sign_num` int(11) NOT NULL,
  `first_sign_days` int(11) NOT NULL,
  `maxfirst_sign_days` int(11) NOT NULL,
  `credit` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_nsign_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_nsign_reply`;
CREATE TABLE `ims_nsign_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `title` text NOT NULL,
  `picture` text NOT NULL,
  `description` text NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_profile_fields`
-- ----------------------------
DROP TABLE IF EXISTS `ims_profile_fields`;
CREATE TABLE `ims_profile_fields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `field` varchar(255) NOT NULL,
  `available` tinyint(1) NOT NULL DEFAULT '1',
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `displayorder` smallint(6) NOT NULL DEFAULT '0',
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `unchangeable` tinyint(1) NOT NULL DEFAULT '0',
  `showinregister` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_qiyue_canvas`
-- ----------------------------
DROP TABLE IF EXISTS `ims_qiyue_canvas`;
CREATE TABLE `ims_qiyue_canvas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `openid` varchar(50) NOT NULL DEFAULT '',
  `attach` varchar(200) NOT NULL DEFAULT '',
  `diggtop` int(10) NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `ischeck` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `viewnum` int(10) NOT NULL DEFAULT '0',
  `sharenum` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_qiyue_luckymoney`
-- ----------------------------
DROP TABLE IF EXISTS `ims_qiyue_luckymoney`;
CREATE TABLE `ims_qiyue_luckymoney` (
  `rid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(20) NOT NULL DEFAULT '',
  `description` varchar(200) NOT NULL DEFAULT '',
  `picurl` varchar(200) NOT NULL DEFAULT '',
  `starttime` int(10) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  `logourl` varchar(200) NOT NULL DEFAULT '',
  `musicurl` varchar(200) NOT NULL DEFAULT '',
  `ruletxt` varchar(300) NOT NULL DEFAULT '',
  `viewnum` int(11) DEFAULT '0',
  `fansnum` int(11) DEFAULT '0',
  `sharenum` int(11) DEFAULT '0',
  `share_imgurl` varchar(100) DEFAULT '',
  `share_title` varchar(100) DEFAULT '',
  `share_desc` varchar(200) DEFAULT '',
  `share_link` varchar(200) DEFAULT '',
  PRIMARY KEY (`rid`),
  KEY `uniacid` (`rid`,`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_qiyue_luckymoney_fans`
-- ----------------------------
DROP TABLE IF EXISTS `ims_qiyue_luckymoney_fans`;
CREATE TABLE `ims_qiyue_luckymoney_fans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT '0',
  `uid` int(10) unsigned DEFAULT '0',
  `nickname` varchar(30) DEFAULT '',
  `avatar` varchar(250) DEFAULT '',
  `mobile` char(11) DEFAULT '',
  `viewnum` int(11) DEFAULT '0',
  `opennum` tinyint(5) NOT NULL DEFAULT '0',
  `friends` text NOT NULL,
  `prize` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_qiyue_qiuqian`
-- ----------------------------
DROP TABLE IF EXISTS `ims_qiyue_qiuqian`;
CREATE TABLE `ims_qiyue_qiuqian` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(20) NOT NULL DEFAULT '',
  `filename` varchar(200) NOT NULL DEFAULT '',
  `myorder` tinyint(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`id`,`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_qrcode`
-- ----------------------------
DROP TABLE IF EXISTS `ims_qrcode`;
CREATE TABLE `ims_qrcode` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL DEFAULT '0',
  `qrcid` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  `keyword` varchar(100) NOT NULL,
  `model` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ticket` varchar(250) NOT NULL DEFAULT '',
  `expire` int(10) unsigned NOT NULL DEFAULT '0',
  `subnum` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `type` varchar(10) NOT NULL,
  `extra` int(10) unsigned NOT NULL,
  `url` varchar(80) NOT NULL,
  `scene_str` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_qrcid` (`qrcid`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_qrcode_stat`
-- ----------------------------
DROP TABLE IF EXISTS `ims_qrcode_stat`;
CREATE TABLE `ims_qrcode_stat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `acid` int(10) unsigned NOT NULL,
  `qid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL DEFAULT '',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `qrcid` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `scene_str` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_quick_center_module_bindings`
-- ----------------------------
DROP TABLE IF EXISTS `ims_quick_center_module_bindings`;
CREATE TABLE `ims_quick_center_module_bindings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `groupid` varchar(50) NOT NULL COMMENT '分组名称',
  `identifier` varchar(50) NOT NULL COMMENT '菜单标示符',
  `pidentifier` varchar(50) NOT NULL COMMENT '上级菜单标示符',
  `displayorder` int(11) NOT NULL COMMENT '显示顺序',
  `title` varchar(50) NOT NULL,
  `url` varchar(1000) NOT NULL,
  `thumb` varchar(1000) NOT NULL,
  `module` varchar(1000) NOT NULL,
  `do` varchar(100) NOT NULL COMMENT '打开按钮的跳转链接',
  `callback` varchar(10240) NOT NULL,
  `rich_callback_enable` int(11) NOT NULL DEFAULT '0',
  `enable` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_qyhb_user`
-- ----------------------------
DROP TABLE IF EXISTS `ims_qyhb_user`;
CREATE TABLE `ims_qyhb_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `user_id` varchar(100) NOT NULL COMMENT '用户id',
  `user_name` varchar(100) DEFAULT NULL COMMENT '用户昵称',
  `user_image` varchar(200) DEFAULT NULL COMMENT '用户头像',
  `ipaddr` varchar(30) DEFAULT NULL COMMENT '用户ip地址',
  `status` varchar(1) DEFAULT NULL COMMENT '是否发放红包',
  `num` int(11) DEFAULT '0',
  `referee` varchar(100) DEFAULT NULL COMMENT '推荐人id',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_recharge_adv`
-- ----------------------------
DROP TABLE IF EXISTS `ims_recharge_adv`;
CREATE TABLE `ims_recharge_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_recharge_order`
-- ----------------------------
DROP TABLE IF EXISTS `ims_recharge_order`;
CREATE TABLE `ims_recharge_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `ordersn` varchar(20) NOT NULL,
  `price` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线',
  `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号',
  `remark` varchar(1000) NOT NULL DEFAULT '',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_research`
-- ----------------------------
DROP TABLE IF EXISTS `ims_research`;
CREATE TABLE `ims_research` (
  `reid` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL,
  `content` text NOT NULL,
  `information` varchar(500) NOT NULL DEFAULT '',
  `thumb` varchar(200) NOT NULL DEFAULT '',
  `inhome` tinyint(4) NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL DEFAULT '0',
  `starttime` int(10) NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `pretotal` int(10) unsigned NOT NULL DEFAULT '1',
  `noticeemail` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`reid`),
  KEY `weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_research_data`
-- ----------------------------
DROP TABLE IF EXISTS `ims_research_data`;
CREATE TABLE `ims_research_data` (
  `redid` bigint(20) NOT NULL AUTO_INCREMENT,
  `reid` int(11) NOT NULL,
  `rerid` int(11) NOT NULL,
  `refid` int(11) NOT NULL,
  `data` varchar(800) NOT NULL,
  PRIMARY KEY (`redid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_research_fields`
-- ----------------------------
DROP TABLE IF EXISTS `ims_research_fields`;
CREATE TABLE `ims_research_fields` (
  `refid` int(11) NOT NULL AUTO_INCREMENT,
  `reid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `essential` tinyint(4) NOT NULL DEFAULT '0',
  `bind` varchar(30) NOT NULL DEFAULT '',
  `value` varchar(300) NOT NULL DEFAULT '',
  `description` varchar(500) NOT NULL DEFAULT '',
  `displayorder` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`refid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_research_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_research_reply`;
CREATE TABLE `ims_research_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `reid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_research_rows`
-- ----------------------------
DROP TABLE IF EXISTS `ims_research_rows`;
CREATE TABLE `ims_research_rows` (
  `rerid` int(11) NOT NULL AUTO_INCREMENT,
  `reid` int(11) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `createtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rerid`),
  KEY `reid` (`reid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_rule`
-- ----------------------------
DROP TABLE IF EXISTS `ims_rule`;
CREATE TABLE `ims_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  `module` varchar(50) NOT NULL,
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_rule_keyword`
-- ----------------------------
DROP TABLE IF EXISTS `ims_rule_keyword`;
CREATE TABLE `ims_rule_keyword` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `module` varchar(50) NOT NULL,
  `content` varchar(255) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_content` (`content`),
  KEY `idx_rid` (`rid`),
  KEY `idx_uniacid_type_content` (`uniacid`,`type`,`content`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_scene_cube_app`
-- ----------------------------
DROP TABLE IF EXISTS `ims_scene_cube_app`;
CREATE TABLE `ims_scene_cube_app` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `listorder` int(11) NOT NULL,
  `iden` varchar(50) NOT NULL,
  `price` int(11) NOT NULL,
  `title` varchar(300) NOT NULL DEFAULT '',
  `thumb` varchar(300) NOT NULL,
  `qrcode` varchar(300) NOT NULL,
  `author` varchar(300) NOT NULL,
  `series` varchar(50) NOT NULL,
  `isshow` tinyint(2) NOT NULL,
  `create_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_scene_cube_book`
-- ----------------------------
DROP TABLE IF EXISTS `ims_scene_cube_book`;
CREATE TABLE `ims_scene_cube_book` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0',
  `list_id` int(11) NOT NULL,
  `from_user` varchar(50) NOT NULL DEFAULT '',
  `str1` varchar(200) NOT NULL DEFAULT '',
  `str2` varchar(200) NOT NULL DEFAULT '',
  `str3` varchar(200) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL,
  `create_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_scene_cube_comment`
-- ----------------------------
DROP TABLE IF EXISTS `ims_scene_cube_comment`;
CREATE TABLE `ims_scene_cube_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `list_id` int(11) NOT NULL,
  `from` varchar(10) NOT NULL,
  `content` varchar(255) NOT NULL,
  `create_time` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `from_user` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_scene_cube_list`
-- ----------------------------
DROP TABLE IF EXISTS `ims_scene_cube_list`;
CREATE TABLE `ims_scene_cube_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `s_id` int(11) NOT NULL,
  `iden` varchar(50) NOT NULL,
  `cover` varchar(300) NOT NULL,
  `cover_title` varchar(50) NOT NULL,
  `cover_subtitle` varchar(50) DEFAULT NULL,
  `cover1` varchar(300) NOT NULL,
  `cover2` varchar(300) NOT NULL,
  `thumb` varchar(300) NOT NULL,
  `share_title` varchar(200) NOT NULL DEFAULT '',
  `share_thumb` varchar(300) NOT NULL DEFAULT '',
  `share_content` varchar(1000) NOT NULL,
  `share_cb_url` varchar(500) NOT NULL,
  `share_cb_tel` varchar(20) NOT NULL,
  `diyurl` varchar(100) NOT NULL DEFAULT '',
  `share_cover` varchar(300) NOT NULL DEFAULT '',
  `share_url` varchar(300) NOT NULL DEFAULT '',
  `share_txt` varchar(500) NOT NULL DEFAULT '',
  `share_button` varchar(300) NOT NULL,
  `share_tips` varchar(300) NOT NULL,
  `reply_title` varchar(50) NOT NULL,
  `reply_thumb` varchar(300) NOT NULL,
  `reply_description` varchar(1000) NOT NULL,
  `isadvanced` int(3) NOT NULL DEFAULT '0',
  `advanced_thumb` varchar(300) NOT NULL,
  `email` varchar(300) NOT NULL DEFAULT '',
  `emailtitle` varchar(100) NOT NULL,
  `first_type` tinyint(2) NOT NULL,
  `first_btn_select` varchar(10) NOT NULL,
  `first_btn_value` varchar(500) NOT NULL,
  `bg_music_switch` tinyint(4) NOT NULL,
  `bg_music_icon` tinyint(4) NOT NULL,
  `bg_music_url` varchar(300) NOT NULL,
  `start_time` int(10) NOT NULL,
  `end_time` int(10) NOT NULL,
  `hits` int(10) NOT NULL,
  `shares` int(10) NOT NULL,
  `tongji` varchar(1000) NOT NULL,
  `isyuyue` tinyint(1) NOT NULL DEFAULT '0',
  `iscomment` tinyint(1) NOT NULL DEFAULT '0',
  `isdemo` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_scene_cube_manage`
-- ----------------------------
DROP TABLE IF EXISTS `ims_scene_cube_manage`;
CREATE TABLE `ims_scene_cube_manage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `appid` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `appnums` int(11) NOT NULL,
  `start_time` int(10) NOT NULL,
  `end_time` int(10) NOT NULL,
  `create_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_scene_cube_page`
-- ----------------------------
DROP TABLE IF EXISTS `ims_scene_cube_page`;
CREATE TABLE `ims_scene_cube_page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `list_id` varchar(50) NOT NULL DEFAULT '',
  `listorder` int(11) NOT NULL,
  `m_type` tinyint(4) NOT NULL,
  `thumb` varchar(300) NOT NULL,
  `param` text NOT NULL,
  `create_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_scene_cube_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_scene_cube_reply`;
CREATE TABLE `ims_scene_cube_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `list_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_shake_member`
-- ----------------------------
DROP TABLE IF EXISTS `ims_shake_member`;
CREATE TABLE `ims_shake_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `shakecount` int(10) unsigned NOT NULL DEFAULT '0',
  `remark` varchar(500) NOT NULL DEFAULT '',
  `lastupdate` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0为不可摇奖，1为可摇奖',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_openid_replyid` (`openid`,`rid`),
  KEY `idx_replyid` (`rid`),
  KEY `idx_shakecount` (`rid`,`shakecount`),
  KEY `createtime` (`createtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_shake_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_shake_reply`;
CREATE TABLE `ims_shake_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `cover` varchar(255) NOT NULL DEFAULT '',
  `qrcode` varchar(255) NOT NULL,
  `background` varchar(255) NOT NULL DEFAULT '',
  `logo` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `rule` text NOT NULL,
  `speed` int(10) unsigned NOT NULL DEFAULT '3000',
  `speedandroid` int(10) unsigned NOT NULL DEFAULT '8000',
  `interval` int(10) unsigned NOT NULL DEFAULT '100',
  `countdown` tinyint(1) unsigned NOT NULL DEFAULT '10',
  `maxshake` int(10) unsigned NOT NULL DEFAULT '100',
  `maxwinner` int(10) unsigned NOT NULL DEFAULT '1',
  `maxjoin` int(10) unsigned NOT NULL,
  `joinprobability` int(10) unsigned NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为未开始，1为进行中，2为已结束',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_shopping_adv`
-- ----------------------------
DROP TABLE IF EXISTS `ims_shopping_adv`;
CREATE TABLE `ims_shopping_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_shopping_cart`
-- ----------------------------
DROP TABLE IF EXISTS `ims_shopping_cart`;
CREATE TABLE `ims_shopping_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `goodsid` int(11) NOT NULL,
  `goodstype` tinyint(1) NOT NULL DEFAULT '1',
  `from_user` varchar(50) NOT NULL,
  `total` int(10) unsigned NOT NULL,
  `optionid` int(10) DEFAULT '0',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_shopping_category`
-- ----------------------------
DROP TABLE IF EXISTS `ims_shopping_category`;
CREATE TABLE `ims_shopping_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `thumb` varchar(255) NOT NULL COMMENT '分类图片',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0',
  `description` varchar(500) NOT NULL COMMENT '分类介绍',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_shopping_dispatch`
-- ----------------------------
DROP TABLE IF EXISTS `ims_shopping_dispatch`;
CREATE TABLE `ims_shopping_dispatch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `dispatchname` varchar(50) DEFAULT '',
  `dispatchtype` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `firstprice` decimal(10,2) DEFAULT '0.00',
  `secondprice` decimal(10,2) DEFAULT '0.00',
  `firstweight` int(11) DEFAULT '0',
  `secondweight` int(11) DEFAULT '0',
  `express` int(11) DEFAULT '0',
  `description` text,
  `enabled` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_shopping_express`
-- ----------------------------
DROP TABLE IF EXISTS `ims_shopping_express`;
CREATE TABLE `ims_shopping_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `express_name` varchar(50) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `express_price` varchar(10) DEFAULT '',
  `express_area` varchar(100) DEFAULT '',
  `express_url` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_shopping_feedback`
-- ----------------------------
DROP TABLE IF EXISTS `ims_shopping_feedback`;
CREATE TABLE `ims_shopping_feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为维权，2为告擎',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态0未解决，1用户同意，2用户拒绝',
  `feedbackid` varchar(30) NOT NULL COMMENT '投诉单号',
  `transid` varchar(30) NOT NULL COMMENT '订单号',
  `reason` varchar(1000) NOT NULL COMMENT '理由',
  `solution` varchar(1000) NOT NULL COMMENT '期待解决方案',
  `remark` varchar(1000) NOT NULL COMMENT '备注',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`),
  KEY `idx_feedbackid` (`feedbackid`),
  KEY `idx_createtime` (`createtime`),
  KEY `idx_transid` (`transid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_shopping_goods`
-- ----------------------------
DROP TABLE IF EXISTS `ims_shopping_goods`;
CREATE TABLE `ims_shopping_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `pcate` int(10) unsigned NOT NULL DEFAULT '0',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为实体，2为虚拟',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `unit` varchar(5) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `goodssn` varchar(50) NOT NULL DEFAULT '',
  `productsn` varchar(50) NOT NULL DEFAULT '',
  `marketprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `productprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `costprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `originalprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价',
  `total` int(10) unsigned NOT NULL DEFAULT '0',
  `totalcnf` int(11) DEFAULT '0' COMMENT '0 拍下减库存 1 付款减库存 2 永久不减',
  `sales` int(10) unsigned NOT NULL DEFAULT '0',
  `spec` varchar(5000) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `weight` decimal(10,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(10,2) NOT NULL DEFAULT '0.00',
  `maxbuy` int(11) DEFAULT '0',
  `usermaxbuy` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户最多购买数量',
  `hasoption` int(11) DEFAULT '0',
  `dispatch` int(11) DEFAULT '0',
  `thumb_url` text,
  `isnew` int(11) DEFAULT '0',
  `ishot` int(11) DEFAULT '0',
  `isdiscount` int(11) DEFAULT '0',
  `isrecommand` int(11) DEFAULT '0',
  `istime` int(11) DEFAULT '0',
  `timestart` int(11) DEFAULT '0',
  `timeend` int(11) DEFAULT '0',
  `viewcount` int(11) DEFAULT '0',
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_shopping_goods_option`
-- ----------------------------
DROP TABLE IF EXISTS `ims_shopping_goods_option`;
CREATE TABLE `ims_shopping_goods_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `thumb` varchar(60) DEFAULT '',
  `productprice` decimal(10,2) DEFAULT '0.00',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  `costprice` decimal(10,2) DEFAULT '0.00',
  `stock` int(11) DEFAULT '0',
  `weight` decimal(10,2) DEFAULT '0.00',
  `displayorder` int(11) DEFAULT '0',
  `specs` text,
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`goodsid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_shopping_goods_param`
-- ----------------------------
DROP TABLE IF EXISTS `ims_shopping_goods_param`;
CREATE TABLE `ims_shopping_goods_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `value` text,
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`goodsid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_shopping_order`
-- ----------------------------
DROP TABLE IF EXISTS `ims_shopping_order`;
CREATE TABLE `ims_shopping_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `ordersn` varchar(20) NOT NULL,
  `price` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功',
  `sendtype` tinyint(1) unsigned NOT NULL COMMENT '1为快递，2为自提',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付',
  `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号',
  `goodstype` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `remark` varchar(1000) NOT NULL DEFAULT '',
  `addressid` int(10) unsigned NOT NULL,
  `address` varchar(1024) NOT NULL DEFAULT '' COMMENT '收货地址信息',
  `expresscom` varchar(30) NOT NULL DEFAULT '',
  `expresssn` varchar(50) NOT NULL DEFAULT '',
  `express` varchar(200) NOT NULL DEFAULT '',
  `goodsprice` decimal(10,2) DEFAULT '0.00',
  `dispatchprice` decimal(10,2) DEFAULT '0.00',
  `dispatch` int(10) DEFAULT '0',
  `paydetail` varchar(255) NOT NULL COMMENT '支付详情',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_shopping_order_goods`
-- ----------------------------
DROP TABLE IF EXISTS `ims_shopping_order_goods`;
CREATE TABLE `ims_shopping_order_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `goodsid` int(10) unsigned NOT NULL,
  `price` decimal(10,2) DEFAULT '0.00',
  `total` int(10) unsigned NOT NULL DEFAULT '1',
  `optionid` int(10) DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  `optionname` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_shopping_product`
-- ----------------------------
DROP TABLE IF EXISTS `ims_shopping_product`;
CREATE TABLE `ims_shopping_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goodsid` int(11) NOT NULL,
  `productsn` varchar(50) NOT NULL,
  `title` varchar(1000) NOT NULL,
  `marketprice` decimal(10,0) unsigned NOT NULL,
  `productprice` decimal(10,0) unsigned NOT NULL,
  `total` int(11) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `spec` varchar(5000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_goodsid` (`goodsid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_shopping_spec`
-- ----------------------------
DROP TABLE IF EXISTS `ims_shopping_spec`;
CREATE TABLE `ims_shopping_spec` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `displaytype` tinyint(3) unsigned NOT NULL,
  `content` text NOT NULL,
  `goodsid` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_shopping_spec_item`
-- ----------------------------
DROP TABLE IF EXISTS `ims_shopping_spec_item`;
CREATE TABLE `ims_shopping_spec_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `specid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `show` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_specid` (`specid`),
  KEY `indx_show` (`show`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_sinrch_dataosha_setting`
-- ----------------------------
DROP TABLE IF EXISTS `ims_sinrch_dataosha_setting`;
CREATE TABLE `ims_sinrch_dataosha_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `subscribe_num` varchar(255) CHARACTER SET utf8 NOT NULL,
  `subscribe_skill` varchar(255) CHARACTER SET utf8 NOT NULL,
  `subscribe_url` varchar(255) CHARACTER SET utf8 NOT NULL,
  `subscribe_game` varchar(255) CHARACTER SET utf8 NOT NULL,
  `share_title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `share_desc` varchar(255) CHARACTER SET utf8 NOT NULL,
  `photo` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `ims_site_article`
-- ----------------------------
DROP TABLE IF EXISTS `ims_site_article`;
CREATE TABLE `ims_site_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `kid` int(10) unsigned NOT NULL,
  `iscommend` tinyint(1) NOT NULL DEFAULT '0',
  `ishot` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pcate` int(10) unsigned NOT NULL DEFAULT '0',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0',
  `template` varchar(300) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(100) NOT NULL DEFAULT '',
  `content` mediumtext NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `source` varchar(255) NOT NULL,
  `author` varchar(50) NOT NULL,
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `linkurl` varchar(500) NOT NULL DEFAULT '',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `type` varchar(10) NOT NULL DEFAULT '',
  `credit` varchar(255) NOT NULL DEFAULT '',
  `incontent` tinyint(1) NOT NULL,
  `click` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_iscommend` (`iscommend`),
  KEY `idx_ishot` (`ishot`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_site_category`
-- ----------------------------
DROP TABLE IF EXISTS `ims_site_category`;
CREATE TABLE `ims_site_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `nid` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL,
  `parentid` int(10) unsigned NOT NULL DEFAULT '0',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `icon` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(100) NOT NULL DEFAULT '',
  `styleid` int(10) unsigned NOT NULL,
  `linkurl` varchar(500) NOT NULL DEFAULT '',
  `ishomepage` tinyint(1) NOT NULL DEFAULT '0',
  `icontype` tinyint(1) unsigned NOT NULL,
  `css` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_site_multi`
-- ----------------------------
DROP TABLE IF EXISTS `ims_site_multi`;
CREATE TABLE `ims_site_multi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(30) NOT NULL,
  `styleid` int(10) unsigned NOT NULL,
  `site_info` text NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `bindhost` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `bindhost` (`bindhost`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_site_nav`
-- ----------------------------
DROP TABLE IF EXISTS `ims_site_nav`;
CREATE TABLE `ims_site_nav` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `multiid` int(10) unsigned NOT NULL,
  `section` tinyint(4) NOT NULL DEFAULT '1',
  `module` varchar(50) NOT NULL DEFAULT '',
  `displayorder` smallint(5) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL DEFAULT '',
  `position` tinyint(4) NOT NULL DEFAULT '1',
  `url` varchar(255) NOT NULL DEFAULT '',
  `icon` varchar(500) NOT NULL DEFAULT '',
  `css` varchar(1000) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `categoryid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `multiid` (`multiid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_site_page`
-- ----------------------------
DROP TABLE IF EXISTS `ims_site_page`;
CREATE TABLE `ims_site_page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `multiid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `params` longtext NOT NULL,
  `html` longtext NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `multiid` (`multiid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_site_slide`
-- ----------------------------
DROP TABLE IF EXISTS `ims_site_slide`;
CREATE TABLE `ims_site_slide` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `displayorder` tinyint(4) NOT NULL DEFAULT '0',
  `multiid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `multiid` (`multiid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_site_styles`
-- ----------------------------
DROP TABLE IF EXISTS `ims_site_styles`;
CREATE TABLE `ims_site_styles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `templateid` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_site_styles_vars`
-- ----------------------------
DROP TABLE IF EXISTS `ims_site_styles_vars`;
CREATE TABLE `ims_site_styles_vars` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `templateid` int(10) unsigned NOT NULL,
  `styleid` int(10) unsigned NOT NULL,
  `variable` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `description` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_site_templates`
-- ----------------------------
DROP TABLE IF EXISTS `ims_site_templates`;
CREATE TABLE `ims_site_templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '',
  `title` varchar(30) NOT NULL,
  `description` varchar(500) NOT NULL DEFAULT '',
  `author` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `sections` int(10) unsigned NOT NULL,
  `version` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_solution_acl`
-- ----------------------------
DROP TABLE IF EXISTS `ims_solution_acl`;
CREATE TABLE `ims_solution_acl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `module` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `eid` int(10) unsigned NOT NULL DEFAULT '0',
  `do` varchar(255) NOT NULL,
  `state` varchar(1000) NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_module` (`module`),
  KEY `idx_eid` (`eid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stat_keyword`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stat_keyword`;
CREATE TABLE `ims_stat_keyword` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `rid` varchar(10) NOT NULL,
  `kid` int(10) unsigned NOT NULL,
  `hit` int(10) unsigned NOT NULL,
  `lastupdate` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_createtime` (`createtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stat_msg_history`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stat_msg_history`;
CREATE TABLE `ims_stat_msg_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `kid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `module` varchar(50) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `type` varchar(10) NOT NULL DEFAULT '',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_createtime` (`createtime`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stat_rule`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stat_rule`;
CREATE TABLE `ims_stat_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `hit` int(10) unsigned NOT NULL,
  `lastupdate` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_createtime` (`createtime`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_bigwheel_exchange`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_bigwheel_exchange`;
CREATE TABLE `ims_stonefish_bigwheel_exchange` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `tickettype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖类型1为前端后台2为店员3为商家网点',
  `awardingtype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '单独兑奖1统一兑奖2',
  `beihuo` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启备货1开启0关闭',
  `beihuo_tips` varchar(20) DEFAULT '' COMMENT '备货提示词',
  `awardingpas` varchar(10) DEFAULT '' COMMENT '兑奖密码',
  `inventory` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖后库存1中奖减少2为兑奖后减少',
  `awardingstarttime` int(10) DEFAULT '0' COMMENT '兑奖开始时间',
  `awardingendtime` int(10) DEFAULT '0' COMMENT '兑奖结束时间',
  `awarding_tips` varchar(50) DEFAULT '' COMMENT '兑奖参数提示词',
  `awardingaddress` varchar(50) DEFAULT '' COMMENT '兑奖地点',
  `awardingtel` varchar(50) DEFAULT '' COMMENT '兑奖电话',
  `baidumaplng` varchar(10) DEFAULT '' COMMENT '兑奖导航',
  `baidumaplat` varchar(10) DEFAULT '' COMMENT '兑奖导航',
  `before` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖资料活动前还是中奖后1前2为后',
  `isrealname` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入姓名0为不需要1为需要',
  `ismobile` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入手机号0为不需要1为需要',
  `isqq` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入QQ号0为不需要1为需要',
  `isemail` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入邮箱0为不需要1为需要',
  `isaddress` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入地址0为不需要1为需要',
  `isgender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入性别0为不需要1为需要',
  `istelephone` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入固定电话0为不需要1为需要',
  `isidcard` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入证件号码0为不需要1为需要',
  `iscompany` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入公司名称0为不需要1为需要',
  `isoccupation` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职业0为不需要1为需要',
  `isposition` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职位0为不需要1为需要',
  `isfans` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0只保存本模块下1同步更新至官方FANS表',
  `isfansname` varchar(225) NOT NULL DEFAULT '真实姓名,手机号码,QQ号,邮箱,地址,性别,固定电话,证件号码,公司名称,职业,职位' COMMENT '显示字段名称',
  `tmplmsg_participate` int(11) DEFAULT '0' COMMENT '参与消息模板',
  `tmplmsg_winning` int(11) DEFAULT '0' COMMENT '中奖消息模板',
  `tmplmsg_exchange` int(11) DEFAULT '0' COMMENT '兑奖消息模板',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_bigwheel_fans`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_bigwheel_fans`;
CREATE TABLE `ims_stonefish_bigwheel_fans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid',
  `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `realname` varchar(20) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `qq` varchar(15) NOT NULL DEFAULT '' COMMENT '联系QQ号码',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '联系邮箱',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '联系地址',
  `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '固定电话',
  `idcard` varchar(30) NOT NULL DEFAULT '' COMMENT '证件号码',
  `company` varchar(50) NOT NULL DEFAULT '' COMMENT '公司名称',
  `occupation` varchar(30) NOT NULL DEFAULT '' COMMENT '职业',
  `position` varchar(30) NOT NULL DEFAULT '' COMMENT '职位',
  `inpoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '起始数',
  `outpoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '已兑换数',
  `sharepoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '分享助力',
  `sharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享量',
  `share_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享量',
  `sharetime` int(10) unsigned NOT NULL COMMENT '最后分享时间',
  `createtime` int(10) unsigned NOT NULL COMMENT '注册时间',
  `lasttime` int(10) unsigned NOT NULL COMMENT '最后参与时间',
  `tickettype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖类型1为前端后台2为店员3为商家网点',
  `ticketid` int(11) DEFAULT '0' COMMENT '店员或商家网点ID',
  `ticketname` varchar(50) DEFAULT '' COMMENT '店员或商家网点名称',
  `zhongjiang` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否中奖',
  `xuni` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否虚拟中奖',
  `todaynum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '今日参与次数',
  `totalnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总参与次数',
  `tosharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享使用次数',
  `awardnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '获奖次数',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否禁止',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_bigwheel_fansaward`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_bigwheel_fansaward`;
CREATE TABLE `ims_stonefish_bigwheel_fansaward` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `from_user` varchar(50) DEFAULT '0' COMMENT '用户openid',
  `prizeid` int(11) DEFAULT '0' COMMENT '奖品ID',
  `codesn` varchar(20) DEFAULT '0' COMMENT '中奖唯一码',
  `createtime` int(10) DEFAULT '0' COMMENT '领取时间',
  `consumetime` int(10) DEFAULT '0' COMMENT '使用时间',
  `openstatus` tinyint(1) DEFAULT '0' COMMENT '是否拆开',
  `zhongjiangtime` int(10) DEFAULT '0' COMMENT '中奖时间',
  `zhongjiang` tinyint(1) DEFAULT '0' COMMENT '是否中奖0未中奖1中奖2兑奖',
  `xuni` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否虚拟中奖',
  `tickettype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖类型1为前端后台2为店员3为商家网点',
  `ticketid` int(11) DEFAULT '0' COMMENT '店员或商家网点ID',
  `ticketname` varchar(50) DEFAULT '' COMMENT '店员或商家网点名称',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_prizeid` (`prizeid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_bigwheel_fanstmplmsg`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_bigwheel_fanstmplmsg`;
CREATE TABLE `ims_stonefish_bigwheel_fanstmplmsg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `from_user` varchar(50) DEFAULT '0' COMMENT '用户openid',
  `tmplmsgid` int(11) DEFAULT '0' COMMENT '消息模板ID',
  `tmplmsg` text NOT NULL COMMENT '发送内容',
  `createtime` int(10) DEFAULT '0' COMMENT '发送时间',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_prizeid` (`tmplmsgid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_bigwheel_prize`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_bigwheel_prize`;
CREATE TABLE `ims_stonefish_bigwheel_prize` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `prizetype` varchar(20) NOT NULL COMMENT '奖品类型真实虚拟积分等',
  `prizevalue` int(10) NOT NULL COMMENT '积分或实物以及虚拟价值',
  `prizerating` varchar(50) NOT NULL COMMENT '奖品等级',
  `prizename` varchar(50) NOT NULL COMMENT '奖品名称',
  `prizepic` varchar(255) NOT NULL COMMENT '奖品图片',
  `prizetotal` int(10) NOT NULL COMMENT '奖品数量',
  `prizedraw` int(10) NOT NULL COMMENT '中奖数量',
  `prizeren` int(10) NOT NULL COMMENT '每人最多中奖',
  `prizeday` int(10) NOT NULL COMMENT '每天最多发奖',
  `probalilty` varchar(5) NOT NULL COMMENT '中奖概率%',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '描述',
  `break` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '需要帮助人数',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_bigwheel_prizemika`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_bigwheel_prizemika`;
CREATE TABLE `ims_stonefish_bigwheel_prizemika` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `prizeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '奖品ID',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid',
  `mikacodesn` varchar(100) NOT NULL COMMENT '密卡字符串',
  `virtual_value` int(10) NOT NULL COMMENT '积分或实物以及虚拟价值',
  `actionurl` varchar(200) NOT NULL COMMENT '激活地址',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '描述',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否领取1为领取过',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_bigwheel_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_bigwheel_reply`;
CREATE TABLE `ims_stonefish_bigwheel_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `templateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动模板ID',
  `title` varchar(50) DEFAULT '' COMMENT '活动标题',
  `description` varchar(255) DEFAULT '' COMMENT '活动简介',
  `start_picurl` varchar(200) DEFAULT '' COMMENT '活动开始图片',
  `end_title` varchar(50) DEFAULT '' COMMENT '结束标题',
  `end_description` varchar(200) DEFAULT '' COMMENT '活动结束简介',
  `end_picurl` varchar(200) DEFAULT '' COMMENT '活动结束图片',
  `isshow` tinyint(1) DEFAULT '1' COMMENT '活动是否停止0为暂停1为活动中',
  `starttime` int(10) DEFAULT '0' COMMENT '开始时间',
  `endtime` int(10) DEFAULT '0' COMMENT '结束时间',
  `music` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否打开背景音乐',
  `musicurl` varchar(255) NOT NULL DEFAULT '' COMMENT '背景音乐地址',
  `mauto` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '音乐是否自动播放',
  `mloop` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否循环播放',
  `issubscribe` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '参与类型0为任意1为关注粉丝2为会员',
  `visubscribe` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '助力类型',
  `fansnum` int(10) DEFAULT '0' COMMENT '参与人数',
  `viewnum` int(10) DEFAULT '0' COMMENT '访问次数',
  `prize_num` int(10) DEFAULT '0' COMMENT '奖品总数',
  `award_num` int(11) DEFAULT '0' COMMENT '每人最多获奖次数',
  `award_num_tips` varchar(100) DEFAULT '' COMMENT '超过中奖数量提示',
  `number_times` int(11) DEFAULT '0' COMMENT '每人最多参与次数',
  `number_times_tips` varchar(100) DEFAULT '' COMMENT '超过总次数提示',
  `day_number_times` int(11) DEFAULT '0' COMMENT '每人每天最多参与次数',
  `day_number_times_tips` varchar(100) DEFAULT '' COMMENT '超过每天次数提示',
  `viewawardnum` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '首页显示中奖人数',
  `viewranknum` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '排行榜人数',
  `showprize` tinyint(1) DEFAULT '0' COMMENT '是否显示奖品',
  `prizeinfo` text NOT NULL COMMENT '奖品详细介绍',
  `awardtext` varchar(1000) DEFAULT '' COMMENT '中奖提示文字',
  `notawardtext` varchar(1000) DEFAULT '' COMMENT '没有中奖提示文字',
  `notprizetext` varchar(1000) DEFAULT '' COMMENT '没有奖品提示文字',
  `msgadpic` varchar(1000) DEFAULT '' COMMENT '消息提示广告图',
  `msgadpictime` tinyint(1) unsigned NOT NULL DEFAULT '5' COMMENT '消息提示时效',
  `tips` varchar(200) DEFAULT '' COMMENT '活动次数提示',
  `copyright` varchar(20) DEFAULT '' COMMENT '版权',
  `inpointstart` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '初始分值1',
  `inpointend` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '初始分值2',
  `power` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否获取助力者头像昵称1opneid 2头像昵称',
  `poweravatar` varchar(3) DEFAULT '0' COMMENT '头像大小',
  `powertype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '助力类型0访问助力1点击助力',
  `randompointstart` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '助力随机金额范围开始数',
  `randompointend` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '助力随机金额范围结束数',
  `addp` tinyint(1) DEFAULT '100' COMMENT '好友助力机率%',
  `limittype` tinyint(1) DEFAULT '0' COMMENT '限制类型0为只能一次1为每天一次',
  `totallimit` tinyint(1) DEFAULT '1' COMMENT '好友助力总次数制',
  `helptype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '互助0为互助1为禁止',
  `xuninum` int(10) unsigned NOT NULL DEFAULT '500' COMMENT '虚拟人数',
  `xuninumtime` int(10) unsigned NOT NULL DEFAULT '86400' COMMENT '虚拟间隔时间',
  `xuninuminitial` int(10) unsigned NOT NULL DEFAULT '10' COMMENT '虚拟随机数值1',
  `xuninumending` int(10) unsigned NOT NULL DEFAULT '100' COMMENT '虚拟随机数值2',
  `xuninum_time` int(10) unsigned NOT NULL COMMENT '虚拟更新时间',
  `adpic` varchar(255) DEFAULT '' COMMENT '活动页顶部广告图',
  `adpicurl` varchar(255) DEFAULT '' COMMENT '活动页顶部广告链接',
  `homepictime` tinyint(1) unsigned NOT NULL COMMENT '首页秒显图片显示时间',
  `homepictype` tinyint(1) unsigned NOT NULL COMMENT '首页广告类型1为每次2为每天3为每周4为仅1次',
  `homepic` varchar(225) NOT NULL COMMENT '首页秒显图片',
  `opportunity` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '参与次数选项0活动设置1商户赠送2为积分购买',
  `opportunity_txt` text NOT NULL COMMENT '商户赠送/积分购买说明',
  `othermodule` varchar(50) DEFAULT '' COMMENT '其他模块',
  `credit_type` varchar(20) DEFAULT '' COMMENT '积分类型',
  `credit_value` int(11) DEFAULT '0' COMMENT '积分购买多少积分',
  `turntable` tinyint(1) DEFAULT '0' COMMENT '转盘类型0普通1为九宫格',
  `turntablenum` tinyint(1) DEFAULT '6' COMMENT '奖品数量',
  `bigwheelpic` varchar(225) NOT NULL COMMENT '转盘图',
  `bigwheelimg` varchar(225) NOT NULL COMMENT '指针图',
  `bigwheelimgan` varchar(225) NOT NULL COMMENT '九宫格按钮',
  `bigwheelimgbg` varchar(225) NOT NULL COMMENT '九宫格转动背景图',
  `prizeDeg` varchar(225) NOT NULL COMMENT '中奖角度设置',
  `lostDeg` varchar(225) NOT NULL COMMENT '未中奖角度设置',
  `againDeg` varchar(225) NOT NULL COMMENT '再来一次角度设置',
  `createtime` int(10) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_bigwheel_share`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_bigwheel_share`;
CREATE TABLE `ims_stonefish_bigwheel_share` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `acid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '子公众号ID',
  `help_url` varchar(255) DEFAULT '' COMMENT '帮助关注引导页',
  `share_url` varchar(255) DEFAULT '' COMMENT '参与关注引导页',
  `share_open_close` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启作用',
  `share_title` varchar(50) DEFAULT '' COMMENT '分享标题',
  `share_desc` varchar(100) DEFAULT '' COMMENT '分享简介',
  `share_txt` text NOT NULL COMMENT '参与活动规则',
  `share_img` varchar(255) NOT NULL COMMENT '分享朋友或朋友圈图',
  `share_anniu` varchar(255) NOT NULL COMMENT '分享朋友或朋友圈按钮或文字',
  `share_firend` varchar(255) NOT NULL COMMENT '助力按钮',
  `share_pic` varchar(255) NOT NULL COMMENT '分享弹出图片',
  `share_confirm` varchar(200) DEFAULT '' COMMENT '分享成功提示语',
  `share_confirmurl` varchar(255) DEFAULT '' COMMENT '分享成功跳转URL',
  `share_fail` varchar(200) DEFAULT '' COMMENT '分享失败提示语',
  `share_cancel` varchar(200) DEFAULT '' COMMENT '分享中途取消提示语',
  `sharetimes` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为每天次数2为总次数',
  `sharetype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '分享赠送类型0分享立即赠送1分享成功赠送',
  `sharenumtype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '分享赠送机会类型0单独赠送机会1每人赠送机会2分享共计赠送',
  `sharenum` varchar(5) DEFAULT '0' COMMENT '分享赠送礼盒基数',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_acid` (`acid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_bigwheel_sharedata`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_bigwheel_sharedata`;
CREATE TABLE `ims_stonefish_bigwheel_sharedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '分享人openid',
  `fromuser` varchar(50) NOT NULL DEFAULT '' COMMENT '访问人openid',
  `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `visitorsip` varchar(15) NOT NULL DEFAULT '' COMMENT '访问IP',
  `visitorstime` int(10) unsigned NOT NULL COMMENT '访问时间',
  `point` decimal(10,2) DEFAULT '0.00' COMMENT '助力金额',
  `viewnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看次数',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_bigwheel_template`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_bigwheel_template`;
CREATE TABLE `ims_stonefish_bigwheel_template` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `title` varchar(20) DEFAULT '' COMMENT '模板名称',
  `thumb` varchar(255) DEFAULT '' COMMENT '模板缩略图',
  `fontsize` varchar(2) DEFAULT '12' COMMENT '文字大小',
  `bgimg` varchar(255) DEFAULT '' COMMENT '背景图',
  `bgcolor` varchar(7) DEFAULT '' COMMENT '背景色',
  `textcolor` varchar(7) DEFAULT '' COMMENT '文字色',
  `textcolorlink` varchar(7) DEFAULT '' COMMENT '链接文字色',
  `buttoncolor` varchar(7) DEFAULT '' COMMENT '按钮色',
  `buttontextcolor` varchar(7) DEFAULT '' COMMENT '按钮文字色',
  `rulecolor` varchar(7) DEFAULT '' COMMENT '规则框背景色',
  `ruletextcolor` varchar(7) DEFAULT '' COMMENT '规则框文字色',
  `navcolor` varchar(7) DEFAULT '' COMMENT '导航色',
  `navtextcolor` varchar(7) DEFAULT '' COMMENT '导航文字色',
  `navactioncolor` varchar(7) DEFAULT '' COMMENT '导航选中文字色',
  `watchcolor` varchar(7) DEFAULT '' COMMENT '弹出框背景色',
  `watchtextcolor` varchar(7) DEFAULT '' COMMENT '弹出框文字色',
  `awardcolor` varchar(7) DEFAULT '' COMMENT '兑奖框背景色',
  `awardtextcolor` varchar(7) DEFAULT '' COMMENT '兑奖框文字色',
  `awardscolor` varchar(7) DEFAULT '' COMMENT '兑奖框成功背景色',
  `awardstextcolor` varchar(7) DEFAULT '' COMMENT '兑奖框成功文字色',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_bigwheel_tmplmsg`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_bigwheel_tmplmsg`;
CREATE TABLE `ims_stonefish_bigwheel_tmplmsg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `template_id` varchar(50) DEFAULT '' COMMENT '模板ID',
  `template_name` varchar(20) DEFAULT '' COMMENT '模板名称',
  `topcolor` varchar(7) DEFAULT '' COMMENT '通知文字色',
  `first` varchar(100) DEFAULT '' COMMENT '标题',
  `firstcolor` varchar(7) DEFAULT '' COMMENT '标题文字色',
  `keyword1` varchar(100) DEFAULT '' COMMENT '参数1',
  `keyword1code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword1color` varchar(7) DEFAULT '' COMMENT '参数1文字色',
  `keyword2` varchar(100) DEFAULT '' COMMENT '参数2',
  `keyword2code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword2color` varchar(7) DEFAULT '' COMMENT '参数2文字色',
  `keyword3` varchar(100) DEFAULT '' COMMENT '参数3',
  `keyword3code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword3color` varchar(7) DEFAULT '' COMMENT '参数3文字色',
  `keyword4` varchar(100) DEFAULT '' COMMENT '参数4',
  `keyword4code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword4color` varchar(7) DEFAULT '' COMMENT '参数4文字色',
  `keyword5` varchar(100) DEFAULT '' COMMENT '参数5',
  `keyword5code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword5color` varchar(7) DEFAULT '' COMMENT '参数5文字色',
  `keyword6` varchar(100) DEFAULT '' COMMENT '参数6',
  `keyword6code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword6color` varchar(7) DEFAULT '' COMMENT '参数6文字色',
  `keyword7` varchar(100) DEFAULT '' COMMENT '参数7',
  `keyword7code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword7color` varchar(7) DEFAULT '' COMMENT '参数7文字色',
  `keyword8` varchar(100) DEFAULT '' COMMENT '参数8',
  `keyword8code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword8color` varchar(7) DEFAULT '' COMMENT '参数8文字色',
  `keyword9` varchar(100) DEFAULT '' COMMENT '参数9',
  `keyword9code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword9color` varchar(7) DEFAULT '' COMMENT '参数9文字色',
  `keyword10` varchar(100) DEFAULT '' COMMENT '参数10',
  `keyword10code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword10color` varchar(7) DEFAULT '' COMMENT '参数10文字色',
  `remark` varchar(100) DEFAULT '' COMMENT '备注',
  `remarkcolor` varchar(7) DEFAULT '' COMMENT '备注文字色',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_chailihe_banner`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_chailihe_banner`;
CREATE TABLE `ims_stonefish_chailihe_banner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `bannerpic` varchar(255) NOT NULL COMMENT '幻灯图片',
  `bannerurl` varchar(255) NOT NULL COMMENT '幻灯链接',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_chailihe_exchange`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_chailihe_exchange`;
CREATE TABLE `ims_stonefish_chailihe_exchange` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `tickettype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖类型1为前端后台2为店员3为商家网点',
  `awardingtype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '单独兑奖1统一兑奖2',
  `beihuo` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启备货1开启0关闭',
  `beihuo_tips` varchar(20) DEFAULT '' COMMENT '备货提示词',
  `awardingpas` varchar(10) DEFAULT '' COMMENT '兑奖密码',
  `inventory` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖后库存1中奖减少2为兑奖后减少',
  `awardingstarttime` int(10) DEFAULT '0' COMMENT '兑奖开始时间',
  `awardingendtime` int(10) DEFAULT '0' COMMENT '兑奖结束时间',
  `awarding_tips` varchar(50) DEFAULT '' COMMENT '兑奖参数提示词',
  `awardingaddress` varchar(50) DEFAULT '' COMMENT '兑奖地点',
  `awardingtel` varchar(50) DEFAULT '' COMMENT '兑奖电话',
  `baidumaplng` varchar(10) DEFAULT '' COMMENT '兑奖导航',
  `baidumaplat` varchar(10) DEFAULT '' COMMENT '兑奖导航',
  `isexchange` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0领取礼盒时输入1中奖后输入',
  `isrealname` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入姓名0为不需要1为需要',
  `ismobile` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入手机号0为不需要1为需要',
  `isqq` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入QQ号0为不需要1为需要',
  `isemail` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入邮箱0为不需要1为需要',
  `isaddress` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入地址0为不需要1为需要',
  `isgender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入性别0为不需要1为需要',
  `istelephone` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入固定电话0为不需要1为需要',
  `isidcard` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入证件号码0为不需要1为需要',
  `iscompany` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入公司名称0为不需要1为需要',
  `isoccupation` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职业0为不需要1为需要',
  `isposition` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职位0为不需要1为需要',
  `isfans` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0只保存本模块下1同步更新至官方FANS表',
  `isfansname` varchar(225) NOT NULL DEFAULT '真实姓名,手机号码,QQ号,邮箱,地址,性别,固定电话,证件号码,公司名称,职业,职位' COMMENT '显示字段名称',
  `tmplmsg_participate` int(11) DEFAULT '0' COMMENT '参与消息模板',
  `tmplmsg_winning` int(11) DEFAULT '0' COMMENT '中奖消息模板',
  `tmplmsg_exchange` int(11) DEFAULT '0' COMMENT '兑奖消息模板',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_chailihe_fans`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_chailihe_fans`;
CREATE TABLE `ims_stonefish_chailihe_fans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid',
  `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `realname` varchar(20) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `qq` varchar(15) NOT NULL DEFAULT '' COMMENT '联系QQ号码',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '联系邮箱',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '联系地址',
  `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '固定电话',
  `idcard` varchar(30) NOT NULL DEFAULT '' COMMENT '证件号码',
  `company` varchar(50) NOT NULL DEFAULT '' COMMENT '公司名称',
  `occupation` varchar(30) NOT NULL DEFAULT '' COMMENT '职业',
  `position` varchar(30) NOT NULL DEFAULT '' COMMENT '职位',
  `sharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享量',
  `share_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享量',
  `sharetime` int(10) unsigned NOT NULL COMMENT '最后分享时间',
  `createtime` int(10) unsigned NOT NULL COMMENT '注册时间',
  `lasttime` int(10) unsigned NOT NULL COMMENT '最后参与时间',
  `tickettype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖类型1为前端后台2为店员3为商家网点',
  `ticketid` int(11) DEFAULT '0' COMMENT '店员或商家网点ID',
  `ticketname` varchar(50) DEFAULT '' COMMENT '店员或商家网点名称',
  `zhongjiang` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否中奖',
  `xuni` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否虚拟中奖',
  `todaynum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '今日参与次数',
  `totalnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总参与次数',
  `tosharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享使用次数',
  `awardnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '获奖次数',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否禁止',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_chailihe_fansaward`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_chailihe_fansaward`;
CREATE TABLE `ims_stonefish_chailihe_fansaward` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `from_user` varchar(50) DEFAULT '0' COMMENT '用户openid',
  `prizeid` int(11) DEFAULT '0' COMMENT '奖品ID',
  `liheid` int(11) DEFAULT '0' COMMENT '礼盒样式ID',
  `codesn` varchar(20) DEFAULT '0' COMMENT '中奖唯一码',
  `createtime` int(10) DEFAULT '0' COMMENT '领取时间',
  `consumetime` int(10) DEFAULT '0' COMMENT '使用时间',
  `sharenum` int(10) DEFAULT '0' COMMENT '拆开人数',
  `openstatus` tinyint(1) DEFAULT '0' COMMENT '是否拆开',
  `zhongjiangtime` int(10) DEFAULT '0' COMMENT '中奖时间',
  `zhongjiang` tinyint(1) DEFAULT '0' COMMENT '是否中奖0未中奖1中奖2兑奖',
  `xuni` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否虚拟中奖',
  `tickettype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖类型1为前端后台2为店员3为商家网点',
  `ticketid` int(11) DEFAULT '0' COMMENT '店员或商家网点ID',
  `ticketname` varchar(50) DEFAULT '' COMMENT '店员或商家网点名称',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_prizeid` (`prizeid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_chailihe_fanstmplmsg`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_chailihe_fanstmplmsg`;
CREATE TABLE `ims_stonefish_chailihe_fanstmplmsg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `from_user` varchar(50) DEFAULT '0' COMMENT '用户openid',
  `tmplmsgid` int(11) DEFAULT '0' COMMENT '消息模板ID',
  `tmplmsg` text NOT NULL COMMENT '发送内容',
  `createtime` int(10) DEFAULT '0' COMMENT '发送时间',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_prizeid` (`tmplmsgid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_chailihe_lihestyle`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_chailihe_lihestyle`;
CREATE TABLE `ims_stonefish_chailihe_lihestyle` (
  `liheid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `title` varchar(20) DEFAULT '' COMMENT '样式名称',
  `thumb1` varchar(255) DEFAULT '' COMMENT '礼盒展示图',
  `thumb2` varchar(255) DEFAULT '' COMMENT '礼盒拆开图',
  `thumb3` varchar(255) DEFAULT '' COMMENT '礼盒显示图',
  `shangjialogo` varchar(255) DEFAULT '' COMMENT '商家LOGO',
  `music` varchar(2) DEFAULT '' COMMENT '礼盒声音',
  PRIMARY KEY (`liheid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_chailihe_prize`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_chailihe_prize`;
CREATE TABLE `ims_stonefish_chailihe_prize` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `liheid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '礼盒样式ID',
  `prizetype` varchar(20) NOT NULL COMMENT '奖品类型真实虚拟积分等',
  `prizevalue` int(10) NOT NULL COMMENT '积分或实物以及虚拟价值',
  `prizerating` varchar(50) NOT NULL COMMENT '奖品等级',
  `prizename` varchar(50) NOT NULL COMMENT '奖品名称',
  `prizepic` varchar(255) NOT NULL COMMENT '奖品图片',
  `prizetotal` int(10) NOT NULL COMMENT '奖品数量',
  `prizedraw` int(10) NOT NULL COMMENT '中奖数量',
  `prizeren` int(10) NOT NULL COMMENT '每人最多中奖',
  `prizeday` int(10) NOT NULL COMMENT '每天最多发奖',
  `probalilty` varchar(5) NOT NULL COMMENT '中奖概率%',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '描述',
  `break` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '需要帮助人数',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_chailihe_prizemika`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_chailihe_prizemika`;
CREATE TABLE `ims_stonefish_chailihe_prizemika` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `prizeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '奖品ID',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid',
  `mikacodesn` varchar(100) NOT NULL COMMENT '密卡字符串',
  `virtual_value` int(10) NOT NULL COMMENT '积分或实物以及虚拟价值',
  `actionurl` varchar(200) NOT NULL COMMENT '激活地址',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '描述',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否领取1为领取过',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_chailihe_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_chailihe_reply`;
CREATE TABLE `ims_stonefish_chailihe_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `templateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动模板ID',
  `slidevertical` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '多个礼盒切换效果2左右1上下',
  `tips` varchar(300) DEFAULT '' COMMENT '活动提示',
  `title` varchar(50) DEFAULT '' COMMENT '活动标题',
  `description` varchar(255) DEFAULT '' COMMENT '活动简介',
  `start_picurl` varchar(200) DEFAULT '' COMMENT '活动开始图片',
  `end_title` varchar(50) DEFAULT '' COMMENT '结束标题',
  `end_description` varchar(200) DEFAULT '' COMMENT '活动结束简介',
  `end_picurl` varchar(200) DEFAULT '' COMMENT '活动结束图片',
  `isshow` tinyint(1) DEFAULT '1' COMMENT '活动是否停止0为暂停1为活动中',
  `starttime` int(10) DEFAULT '0' COMMENT '开始时间',
  `endtime` int(10) DEFAULT '0' COMMENT '结束时间',
  `music` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否打开背景音乐',
  `musicurl` varchar(255) NOT NULL DEFAULT '' COMMENT '背景音乐地址',
  `mauto` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '音乐是否自动播放',
  `mloop` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否循环播放',
  `issubscribe` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '参与类型0为任意1为关注粉丝2为会员',
  `visubscribe` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '助力类型',
  `fansnum` int(10) DEFAULT '0' COMMENT '参与人数',
  `viewnum` int(10) DEFAULT '0' COMMENT '访问次数',
  `prize_num` int(10) DEFAULT '0' COMMENT '奖品总数',
  `award_num` int(11) DEFAULT '0' COMMENT '每人最多获奖次数',
  `number_times` int(11) DEFAULT '0' COMMENT '每人最多参与次数',
  `day_number_times` int(11) DEFAULT '0' COMMENT '每人每天最多参与次数',
  `viewawardnum` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '首页显示中奖人数',
  `viewranknum` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '排行榜人数',
  `showprize` tinyint(1) DEFAULT '0' COMMENT '是否显示奖品',
  `prizeinfo` text NOT NULL COMMENT '奖品详细介绍',
  `awardtext` varchar(1000) DEFAULT '' COMMENT '中奖提示文字',
  `notawardtext` varchar(1000) DEFAULT '' COMMENT '没有中奖提示文字',
  `noprizepic` varchar(1000) DEFAULT '' COMMENT '没有中奖提示图',
  `notprizetext` varchar(1000) DEFAULT '' COMMENT '没有奖品提示文字',
  `copyright` varchar(20) DEFAULT '' COMMENT '版权',
  `power` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否获取助力者头像昵称1opneid 2头像昵称',
  `poweravatar` varchar(3) DEFAULT '0' COMMENT '头像大小',
  `powertype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '助力类型0访问助力1点击助力',
  `limittype` tinyint(1) DEFAULT '0' COMMENT '限制类型0为只能一次1为每天一次',
  `totallimit` tinyint(1) DEFAULT '1' COMMENT '好友助力总次数制',
  `helptype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '互助0为互助1为禁止',
  `helpfans` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0全部用户1只能助力1人',
  `helplihe` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0所有礼盒1单独礼盒',
  `xuninum` int(10) unsigned NOT NULL DEFAULT '500' COMMENT '虚拟人数',
  `xuninumtime` int(10) unsigned NOT NULL DEFAULT '86400' COMMENT '虚拟间隔时间',
  `xuninuminitial` int(10) unsigned NOT NULL DEFAULT '10' COMMENT '虚拟随机数值1',
  `xuninumending` int(10) unsigned NOT NULL DEFAULT '100' COMMENT '虚拟随机数值2',
  `xuninum_time` int(10) unsigned NOT NULL COMMENT '虚拟更新时间',
  `adpic` varchar(255) DEFAULT '' COMMENT '活动页顶部广告图',
  `adpicurl` varchar(255) DEFAULT '' COMMENT '活动页顶部广告链接',
  `homepictime` tinyint(1) unsigned NOT NULL COMMENT '首页秒显图片显示时间',
  `homepictype` tinyint(1) unsigned NOT NULL COMMENT '首页广告类型1为每次2为每天3为每周4为仅1次',
  `homepic` varchar(225) NOT NULL COMMENT '首页秒显图片',
  `opportunity` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '参与次数选项0活动设置1商户赠送2为积分购买',
  `opportunity_txt` text NOT NULL COMMENT '商户赠送/积分购买说明',
  `credit_type` varchar(20) DEFAULT '' COMMENT '积分类型',
  `credit_value` int(11) DEFAULT '0' COMMENT '积分购买多少积分',
  `createtime` int(10) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_chailihe_share`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_chailihe_share`;
CREATE TABLE `ims_stonefish_chailihe_share` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `acid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '子公众号ID',
  `help_url` varchar(255) DEFAULT '' COMMENT '帮助关注引导页',
  `share_url` varchar(255) DEFAULT '' COMMENT '参与关注引导页',
  `share_open_close` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启作用',
  `share_title` varchar(50) DEFAULT '' COMMENT '分享标题',
  `share_desc` varchar(100) DEFAULT '' COMMENT '分享简介',
  `share_txt` text NOT NULL COMMENT '参与活动规则',
  `share_img` varchar(255) NOT NULL COMMENT '分享朋友或朋友圈图',
  `share_anniu` varchar(255) NOT NULL COMMENT '分享朋友或朋友圈按钮或文字',
  `share_firend` varchar(255) NOT NULL COMMENT '助力按钮',
  `share_pic` varchar(255) NOT NULL COMMENT '分享弹出图片',
  `share_confirm` varchar(200) DEFAULT '' COMMENT '分享成功提示语',
  `share_confirmurl` varchar(255) DEFAULT '' COMMENT '分享成功跳转URL',
  `share_fail` varchar(200) DEFAULT '' COMMENT '分享失败提示语',
  `share_cancel` varchar(200) DEFAULT '' COMMENT '分享中途取消提示语',
  `sharetimes` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为每天次数2为总次数',
  `sharetype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '分享赠送类型0分享立即赠送1分享成功赠送',
  `sharenumtype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '分享赠送机会类型0单独赠送机会1每人赠送机会2分享共计赠送',
  `sharenum` int(11) DEFAULT '0' COMMENT '分享赠送礼盒基数',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_acid` (`acid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_chailihe_sharedata`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_chailihe_sharedata`;
CREATE TABLE `ims_stonefish_chailihe_sharedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `fid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '礼盒记录ID',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '分享人openid',
  `fromuser` varchar(50) NOT NULL DEFAULT '' COMMENT '访问人openid',
  `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `visitorsip` varchar(15) NOT NULL DEFAULT '' COMMENT '访问IP',
  `visitorstime` int(10) unsigned NOT NULL COMMENT '访问时间',
  `viewnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看次数',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_chailihe_template`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_chailihe_template`;
CREATE TABLE `ims_stonefish_chailihe_template` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `title` varchar(20) DEFAULT '' COMMENT '模板名称',
  `thumb` varchar(255) DEFAULT '' COMMENT '模板缩略图',
  `fontsize` varchar(2) DEFAULT '12' COMMENT '文字大小',
  `bgimg` varchar(255) DEFAULT '' COMMENT '背景图',
  `bgimglihe` varchar(255) DEFAULT '' COMMENT '领取礼盒背景图',
  `bgimgprize` varchar(255) DEFAULT '' COMMENT '中奖背景图',
  `bgcolor` varchar(7) DEFAULT '' COMMENT '背景色',
  `textcolor` varchar(7) DEFAULT '' COMMENT '文字色',
  `textcolorlink` varchar(7) DEFAULT '' COMMENT '链接文字色',
  `buttoncolor` varchar(7) DEFAULT '' COMMENT '按钮色',
  `buttontextcolor` varchar(7) DEFAULT '' COMMENT '按钮文字色',
  `rulecolor` varchar(7) DEFAULT '' COMMENT '规则框背景色',
  `ruletextcolor` varchar(7) DEFAULT '' COMMENT '规则框文字色',
  `navcolor` varchar(7) DEFAULT '' COMMENT '导航色',
  `navtextcolor` varchar(7) DEFAULT '' COMMENT '导航文字色',
  `navactioncolor` varchar(7) DEFAULT '' COMMENT '导航选中文字色',
  `watchcolor` varchar(7) DEFAULT '' COMMENT '弹出框背景色',
  `watchtextcolor` varchar(7) DEFAULT '' COMMENT '弹出框文字色',
  `awardcolor` varchar(7) DEFAULT '' COMMENT '兑奖框背景色',
  `awardtextcolor` varchar(7) DEFAULT '' COMMENT '兑奖框文字色',
  `awardscolor` varchar(7) DEFAULT '' COMMENT '兑奖框成功背景色',
  `awardstextcolor` varchar(7) DEFAULT '' COMMENT '兑奖框成功文字色',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_chailihe_tmplmsg`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_chailihe_tmplmsg`;
CREATE TABLE `ims_stonefish_chailihe_tmplmsg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `template_id` varchar(50) DEFAULT '' COMMENT '模板ID',
  `template_name` varchar(20) DEFAULT '' COMMENT '模板名称',
  `topcolor` varchar(7) DEFAULT '' COMMENT '通知文字色',
  `first` varchar(100) DEFAULT '' COMMENT '标题',
  `firstcolor` varchar(7) DEFAULT '' COMMENT '标题文字色',
  `keyword1` varchar(100) DEFAULT '' COMMENT '参数1',
  `keyword1code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword1color` varchar(7) DEFAULT '' COMMENT '参数1文字色',
  `keyword2` varchar(100) DEFAULT '' COMMENT '参数2',
  `keyword2code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword2color` varchar(7) DEFAULT '' COMMENT '参数2文字色',
  `keyword3` varchar(100) DEFAULT '' COMMENT '参数3',
  `keyword3code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword3color` varchar(7) DEFAULT '' COMMENT '参数3文字色',
  `keyword4` varchar(100) DEFAULT '' COMMENT '参数4',
  `keyword4code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword4color` varchar(7) DEFAULT '' COMMENT '参数4文字色',
  `keyword5` varchar(100) DEFAULT '' COMMENT '参数5',
  `keyword5code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword5color` varchar(7) DEFAULT '' COMMENT '参数5文字色',
  `keyword6` varchar(100) DEFAULT '' COMMENT '参数6',
  `keyword6code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword6color` varchar(7) DEFAULT '' COMMENT '参数6文字色',
  `keyword7` varchar(100) DEFAULT '' COMMENT '参数7',
  `keyword7code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword7color` varchar(7) DEFAULT '' COMMENT '参数7文字色',
  `keyword8` varchar(100) DEFAULT '' COMMENT '参数8',
  `keyword8code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword8color` varchar(7) DEFAULT '' COMMENT '参数8文字色',
  `keyword9` varchar(100) DEFAULT '' COMMENT '参数9',
  `keyword9code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword9color` varchar(7) DEFAULT '' COMMENT '参数9文字色',
  `keyword10` varchar(100) DEFAULT '' COMMENT '参数10',
  `keyword10code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword10color` varchar(7) DEFAULT '' COMMENT '参数10文字色',
  `remark` varchar(100) DEFAULT '' COMMENT '备注',
  `remarkcolor` varchar(7) DEFAULT '' COMMENT '备注文字色',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_luckynum`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_luckynum`;
CREATE TABLE `ims_stonefish_luckynum` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `starttime` int(10) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  `isshow` tinyint(1) DEFAULT '0',
  `title` varchar(100) DEFAULT '',
  `shareimg` varchar(255) DEFAULT '',
  `sharetitle` varchar(100) DEFAULT '',
  `sharedesc` varchar(300) DEFAULT '',
  `show_instruction` varchar(100) DEFAULT '',
  `time_instruction` varchar(100) DEFAULT '',
  `limit_instruction` varchar(100) DEFAULT '',
  `end_instruction` varchar(100) DEFAULT '',
  `awardnum_instruction` varchar(100) DEFAULT '',
  `award_instruction` varchar(100) DEFAULT '',
  `luckynumstart` int(10) unsigned NOT NULL DEFAULT '0',
  `luckynumfilter` varchar(100) NOT NULL DEFAULT '',
  `awardprompt` varchar(200) NOT NULL DEFAULT '',
  `currentprompt` varchar(200) NOT NULL DEFAULT '',
  `limittype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '参与限制0为无限制1为只能一次',
  `awardnum` tinyint(1) NOT NULL DEFAULT '0' COMMENT '中奖限制次数',
  `ticketinfo` varchar(50) DEFAULT '' COMMENT '兑奖参数提示词',
  `isrealname` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入姓名0为不需要1为需要',
  `ismobile` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入手机号0为不需要1为需要',
  `isqq` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入QQ号0为不需要1为需要',
  `isemail` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入邮箱0为不需要1为需要',
  `isaddress` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入地址0为不需要1为需要',
  `isgender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入性别0为不需要1为需要',
  `istelephone` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入固定电话0为不需要1为需要',
  `isidcard` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入证件号码0为不需要1为需要',
  `iscompany` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入公司名称0为不需要1为需要',
  `isoccupation` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职业0为不需要1为需要',
  `isposition` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职位0为不需要1为需要',
  `isfans` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0只保存本模块下1同步更新至官方FANS表',
  `isfansname` varchar(225) NOT NULL DEFAULT '真实姓名,手机号码,QQ号,邮箱,地址,性别,固定电话,证件号码,公司名称,职业,职位' COMMENT '显示字段名称',
  `sponsors1` varchar(50) DEFAULT '',
  `sponsors1link` varchar(255) DEFAULT '',
  `sponsors2` varchar(50) DEFAULT '',
  `sponsors2link` varchar(255) DEFAULT '',
  `sponsors3` varchar(50) DEFAULT '',
  `sponsors3link` varchar(255) DEFAULT '',
  `sponsors4` varchar(50) DEFAULT '',
  `sponsors4link` varchar(255) DEFAULT '',
  `sponsors5` varchar(50) DEFAULT '',
  `sponsors5link` varchar(255) DEFAULT '',
  `ruletext` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_luckynum_award`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_luckynum_award`;
CREATE TABLE `ims_stonefish_luckynum_award` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `numbers` text NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(200) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_luckynum_fans`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_luckynum_fans`;
CREATE TABLE `ims_stonefish_luckynum_fans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `number` int(10) unsigned NOT NULL DEFAULT '0',
  `from_user` varchar(50) NOT NULL DEFAULT '0',
  `award_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` char(15) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `realname` varchar(20) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `qq` varchar(15) NOT NULL DEFAULT '' COMMENT '联系QQ号码',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '联系邮箱',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '联系地址',
  `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '固定电话',
  `idcard` varchar(30) NOT NULL DEFAULT '' COMMENT '证件号码',
  `company` varchar(50) NOT NULL DEFAULT '' COMMENT '公司名称',
  `occupation` varchar(30) NOT NULL DEFAULT '' COMMENT '职业',
  `position` varchar(30) NOT NULL DEFAULT '' COMMENT '职位',
  `zhongjiang` tinyint(1) NOT NULL DEFAULT '0',
  `xuni` tinyint(1) DEFAULT '0',
  `dateline` int(10) DEFAULT '0',
  `consumetime` int(10) DEFAULT '0',
  `awardingid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '兑奖地址ID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `rid_number_UNIQUE` (`rid`,`number`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_planting_award`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_planting_award`;
CREATE TABLE `ims_stonefish_planting_award` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0' COMMENT '公众号ID',
  `rid` int(11) DEFAULT '0',
  `fid` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '0' COMMENT '用户ID',
  `prizeid` int(11) DEFAULT '0' COMMENT '奖品ID',
  `shengzhangid` tinyint(1) DEFAULT '0' COMMENT '种子生成级别',
  `prizename` varchar(50) DEFAULT '' COMMENT '奖品名称',
  `prizetype` varchar(10) DEFAULT '' COMMENT '类型',
  `description` varchar(200) DEFAULT '' COMMENT '描述',
  `createtime` int(10) DEFAULT '0' COMMENT '中奖时间',
  `consumetime` int(10) DEFAULT '0' COMMENT '领奖时间',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态0为未领取1为领取',
  `xuni` tinyint(1) DEFAULT '0',
  `tickettype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '兑奖类型1为前端后台2为店员3为商家网点',
  `ticketid` int(11) DEFAULT '0' COMMENT '兑奖人ID',
  `ticketname` varchar(50) DEFAULT '' COMMENT '兑奖人姓名',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_planting_data`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_planting_data`;
CREATE TABLE `ims_stonefish_planting_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(11) DEFAULT '0' COMMENT '公众号ID',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid',
  `fromuser` varchar(50) NOT NULL DEFAULT '' COMMENT '分享人openid',
  `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `visitorsip` varchar(15) NOT NULL DEFAULT '' COMMENT '访问IP',
  `visitorstime` int(10) unsigned NOT NULL COMMENT '访问时间',
  `viewnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看次数',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_planting_fans`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_planting_fans`;
CREATE TABLE `ims_stonefish_planting_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `uniacid` int(11) DEFAULT '0' COMMENT '公众号ID',
  `seedid` int(11) DEFAULT '0' COMMENT '种子ID',
  `fansID` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '' COMMENT '用户ID',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `realname` varchar(20) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `qq` varchar(15) NOT NULL DEFAULT '' COMMENT '联系QQ号码',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '联系邮箱',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '联系地址',
  `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '固定电话',
  `idcard` varchar(30) NOT NULL DEFAULT '' COMMENT '证件号码',
  `company` varchar(50) NOT NULL DEFAULT '' COMMENT '公司名称',
  `occupation` varchar(30) NOT NULL DEFAULT '' COMMENT '职业',
  `position` varchar(30) NOT NULL DEFAULT '' COMMENT '职位',
  `sharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享量',
  `sharetime` int(10) DEFAULT '0' COMMENT '最后分享时间',
  `awardingid` int(10) DEFAULT '0' COMMENT '兑奖地址ID',
  `choujiang` tinyint(1) DEFAULT '0' COMMENT '抽奖状态',
  `last_time` int(10) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `zhongjiang` tinyint(1) DEFAULT '0',
  `xuni` tinyint(1) DEFAULT '0',
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_planting_prize`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_planting_prize`;
CREATE TABLE `ims_stonefish_planting_prize` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0' COMMENT '公众号ID',
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `sharenum` int(10) DEFAULT '0' COMMENT '抽奖所需助力值',
  `prizetype` varchar(50) NOT NULL COMMENT '奖品类别',
  `prizename` varchar(50) NOT NULL COMMENT '奖品名称',
  `prizepro` double DEFAULT '0' COMMENT '奖品概率',
  `prizetotal` int(10) NOT NULL COMMENT '奖品数量',
  `prizedraw` int(10) NOT NULL COMMENT '中奖数量',
  `prizepic` varchar(255) NOT NULL COMMENT '奖品图片',
  `prizetxt` text NOT NULL COMMENT '奖品说明',
  `credit` int(10) NOT NULL COMMENT '积分',
  `credit_type` varchar(20) DEFAULT '' COMMENT '积分类型',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_planting_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_planting_reply`;
CREATE TABLE `ims_stonefish_planting_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT '0',
  `uniacid` int(11) DEFAULT '0' COMMENT '公众号ID',
  `title` varchar(50) DEFAULT '' COMMENT '活动名称',
  `description` varchar(255) DEFAULT '' COMMENT '活动简介',
  `start_picurl` varchar(200) DEFAULT '' COMMENT '活动开始图片',
  `isshow` tinyint(1) DEFAULT '0',
  `award_times` int(11) DEFAULT '0' COMMENT '每人最多获奖次数',
  `award_type` tinyint(1) DEFAULT '0' COMMENT '抽奖机会1为保留0为越级',
  `ticket_information` varchar(200) DEFAULT '' COMMENT '兑奖信息',
  `starttime` int(10) DEFAULT '0' COMMENT '活动开始时间',
  `endtime` int(10) DEFAULT '0' COMMENT '活动结束时间',
  `end_theme` varchar(50) DEFAULT '' COMMENT '结束标题',
  `end_instruction` varchar(200) DEFAULT '' COMMENT '活动结束简介',
  `end_picurl` varchar(200) DEFAULT '' COMMENT '活动结束图片',
  `adpic` varchar(200) DEFAULT '' COMMENT '活动页顶部广告图',
  `adpicurl` varchar(200) DEFAULT '' COMMENT '活动页顶部广告链接',
  `total_num` int(11) DEFAULT '0' COMMENT '奖品数量(自动加)',
  `copyright` varchar(20) DEFAULT '' COMMENT '自定义版权',
  `show_num` tinyint(1) DEFAULT '0' COMMENT '是否显示奖品数量',
  `viewnum` int(11) DEFAULT '0' COMMENT '浏览次数',
  `fansnum` int(11) DEFAULT '0' COMMENT '参与人数',
  `seedid` varchar(100) NOT NULL COMMENT '种子集',
  `limittype` tinyint(1) DEFAULT '0' COMMENT '限制类型0为只能一次1为每天一次',
  `totallimit` tinyint(1) DEFAULT '1' COMMENT '好友助力总次数制',
  `awardnum` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '首页滚动中奖人数显示',
  `createtime` int(10) DEFAULT '0' COMMENT '活动创建时间',
  `ticketinfo` varchar(50) DEFAULT '' COMMENT '兑奖参数提示词',
  `tickettype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖类型1为前端后台2为店员3为商家网点',
  `duijiangtype` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '库存类型1抽中减少2兑奖减少',
  `isrealname` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入姓名0为不需要1为需要',
  `ismobile` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入手机号0为不需要1为需要',
  `isqq` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入QQ号0为不需要1为需要',
  `isemail` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入邮箱0为不需要1为需要',
  `isaddress` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入地址0为不需要1为需要',
  `isgender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入性别0为不需要1为需要',
  `istelephone` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入固定电话0为不需要1为需要',
  `isidcard` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入证件号码0为不需要1为需要',
  `iscompany` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入公司名称0为不需要1为需要',
  `isoccupation` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职业0为不需要1为需要',
  `isposition` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职位0为不需要1为需要',
  `isfans` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0只保存本模块下1同步更新至官方FANS表',
  `isfansname` varchar(225) NOT NULL DEFAULT '真实姓名,手机号码,QQ号,邮箱,地址,性别,固定电话,证件号码,公司名称,职业,职位' COMMENT '显示字段名称',
  `xuninum` int(10) unsigned NOT NULL DEFAULT '500' COMMENT '虚拟人数',
  `xuninumtime` int(10) unsigned NOT NULL DEFAULT '86400' COMMENT '虚拟间隔时间',
  `xuninuminitial` int(10) unsigned NOT NULL DEFAULT '10' COMMENT '虚拟随机数值1',
  `xuninumending` int(10) unsigned NOT NULL DEFAULT '100' COMMENT '虚拟随机数值2',
  `xuninum_time` int(10) unsigned NOT NULL COMMENT '虚拟更新时间',
  `homepictime` int(3) unsigned NOT NULL COMMENT '首页秒显图片显示时间',
  `homepic` varchar(225) NOT NULL COMMENT '首页秒显图片',
  `opportunity` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '参与选项 0任何人1关注粉丝2为商户赠送',
  `opportunity_txt` text NOT NULL COMMENT '商户赠送参数说明',
  `award_info` text NOT NULL COMMENT '奖品详细介绍',
  `credit_times` tinyint(1) DEFAULT '0',
  `credit_type` varchar(20) DEFAULT '',
  `showparameters` varchar(1000) NOT NULL COMMENT '显示界面参数：背景色、背景图以及文字色等',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_planting_seed`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_planting_seed`;
CREATE TABLE `ims_stonefish_planting_seed` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0' COMMENT '公众号ID',
  `seedname` varchar(50) NOT NULL DEFAULT '' COMMENT '种子名称',
  `seedimg` varchar(512) NOT NULL DEFAULT '' COMMENT '种子形象图',
  `seedad` varchar(150) NOT NULL DEFAULT '' COMMENT '种子介绍',
  `seedinfo` text NOT NULL COMMENT '种子介绍',
  `seedimg01` varchar(512) NOT NULL DEFAULT '' COMMENT '胚胎',
  `seedimg02` varchar(512) NOT NULL DEFAULT '' COMMENT '发芽',
  `seedimg03` varchar(512) NOT NULL DEFAULT '' COMMENT '生长',
  `seedimg04` varchar(512) NOT NULL DEFAULT '' COMMENT '发枝',
  `seedimg05` varchar(512) NOT NULL DEFAULT '' COMMENT '繁荣',
  `seedimg06` varchar(512) NOT NULL DEFAULT '' COMMENT '开花',
  `seedimg07` varchar(512) NOT NULL DEFAULT '' COMMENT '结果',
  `seedimg08` varchar(512) NOT NULL DEFAULT '' COMMENT '成熟',
  `seed01` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '胚胎量',
  `seed02` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '发芽量',
  `seed03` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '生长量',
  `seed04` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '发枝量',
  `seed05` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '繁荣量',
  `seed06` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '开花量',
  `seed07` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '开花量',
  `seed08` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '成熟量',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_planting_share`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_planting_share`;
CREATE TABLE `ims_stonefish_planting_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `acid` int(11) DEFAULT '0',
  `uniacid` int(11) DEFAULT '0' COMMENT '公众号ID',
  `share_title` varchar(200) DEFAULT '',
  `share_desc` varchar(300) DEFAULT '',
  `share_url` varchar(255) DEFAULT '',
  `share_txt` text NOT NULL COMMENT '参与活动规则',
  `share_imgurl` varchar(255) NOT NULL COMMENT '分享朋友或朋友圈图',
  `share_picurl` varchar(255) NOT NULL COMMENT '分享图片按钮',
  `share_pic` varchar(255) NOT NULL COMMENT '分享弹出图片',
  `share_confirm` varchar(200) DEFAULT '' COMMENT '分享成功提示语',
  `share_fail` varchar(200) DEFAULT '' COMMENT '分享失败提示语',
  `share_cancel` varchar(200) DEFAULT '' COMMENT '分享中途取消提示语',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_acid` (`acid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_planting_token`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_planting_token`;
CREATE TABLE `ims_stonefish_planting_token` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `access_token` varchar(1000) NOT NULL,
  `expires_in` int(11) DEFAULT NULL,
  `createtime` int(10) unsigned NOT NULL COMMENT '日期',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_redenvelope_award`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_redenvelope_award`;
CREATE TABLE `ims_stonefish_redenvelope_award` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `fansID` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '0' COMMENT '用户ID',
  `name` varchar(50) DEFAULT '' COMMENT '名称',
  `description` varchar(200) DEFAULT '' COMMENT '描述',
  `prizetype` varchar(10) DEFAULT '' COMMENT '类型',
  `prize` int(11) DEFAULT '0' COMMENT '奖品ID',
  `award_sn` varchar(50) DEFAULT '' COMMENT 'SN',
  `createtime` int(10) DEFAULT '0',
  `consumetime` int(10) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `xuni` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_redenvelope_data`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_redenvelope_data`;
CREATE TABLE `ims_stonefish_redenvelope_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid',
  `fromuser` varchar(50) NOT NULL DEFAULT '' COMMENT '分享人openid',
  `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `visitorsip` varchar(15) NOT NULL DEFAULT '' COMMENT '访问IP',
  `visitorstime` int(10) unsigned NOT NULL COMMENT '访问时间',
  `point` decimal(10,2) DEFAULT '0.00' COMMENT '助力金额',
  `viewnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看次数',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_redenvelope_fans`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_redenvelope_fans`;
CREATE TABLE `ims_stonefish_redenvelope_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `fansID` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '' COMMENT '用户ID',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `realname` varchar(20) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `qq` varchar(15) NOT NULL DEFAULT '' COMMENT '联系QQ号码',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '联系邮箱',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '联系地址',
  `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '固定电话',
  `idcard` varchar(30) NOT NULL DEFAULT '' COMMENT '证件号码',
  `company` varchar(50) NOT NULL DEFAULT '' COMMENT '公司名称',
  `occupation` varchar(30) NOT NULL DEFAULT '' COMMENT '职业',
  `position` varchar(30) NOT NULL DEFAULT '' COMMENT '职位',
  `inpoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '起始金额',
  `outpoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '已兑换金额',
  `sharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享量',
  `sharetime` int(10) DEFAULT '0' COMMENT '最后分享时间',
  `awardingid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '兑奖地址ID',
  `last_time` int(10) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `zhongjiang` tinyint(1) DEFAULT '0',
  `xuni` tinyint(1) DEFAULT '0',
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_redenvelope_prize`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_redenvelope_prize`;
CREATE TABLE `ims_stonefish_redenvelope_prize` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `point` decimal(10,2) DEFAULT '0.00' COMMENT '奖品需要金额',
  `prizetype` varchar(50) NOT NULL COMMENT '奖品类别',
  `prizename` varchar(50) NOT NULL COMMENT '奖品名称',
  `prizepro` double DEFAULT '0' COMMENT '奖品概率',
  `prizetotal` int(10) NOT NULL COMMENT '奖品数量',
  `prizedraw` int(10) NOT NULL COMMENT '中奖数量',
  `prizepic` varchar(255) NOT NULL COMMENT '奖品图片',
  `prizetxt` text NOT NULL COMMENT '奖品说明',
  `credit` int(10) NOT NULL COMMENT '积分',
  `credit_type` varchar(20) DEFAULT '' COMMENT '积分类型',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_redenvelope_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_redenvelope_reply`;
CREATE TABLE `ims_stonefish_redenvelope_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `title` varchar(50) DEFAULT '' COMMENT '活动名称',
  `description` varchar(255) DEFAULT '' COMMENT '活动简介',
  `start_picurl` varchar(200) DEFAULT '' COMMENT '活动开始图片',
  `isshow` tinyint(1) DEFAULT '0',
  `envelope` tinyint(1) DEFAULT '0' COMMENT '红包类型0为实物奖品1为现金',
  `award_times` int(11) DEFAULT '0' COMMENT '每人最多获奖次数',
  `ticket_information` varchar(200) DEFAULT '' COMMENT '兑奖信息',
  `starttime` int(10) DEFAULT '0' COMMENT '活动开始时间',
  `endtime` int(10) DEFAULT '0' COMMENT '活动结束时间',
  `end_theme` varchar(50) DEFAULT '' COMMENT '结束标题',
  `end_instruction` varchar(200) DEFAULT '' COMMENT '活动结束简介',
  `end_picurl` varchar(200) DEFAULT '' COMMENT '活动结束图片',
  `adpic` varchar(200) DEFAULT '' COMMENT '活动页顶部广告图',
  `adpicurl` varchar(200) DEFAULT '' COMMENT '活动页顶部广告链接',
  `total_num` int(11) DEFAULT '0' COMMENT '奖品数量(自动加)',
  `sn_rename` varchar(20) DEFAULT '',
  `copyright` varchar(20) DEFAULT '' COMMENT '自定义版权',
  `show_num` tinyint(1) DEFAULT '0' COMMENT '是否显示奖品数量',
  `viewnum` int(11) DEFAULT '0' COMMENT '浏览次数',
  `awardnum` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '首页滚动中奖人数显示',
  `fansnum` int(11) DEFAULT '0' COMMENT '参与人数',
  `cardbg` varchar(255) NOT NULL COMMENT '抽奖卡片背景图片',
  `inpointstart` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '初始分值1',
  `inpointend` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '初始分值2',
  `randompointstart` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '助力随机金额范围开始数',
  `randompointend` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '助力随机金额范围结束数',
  `addp` tinyint(1) DEFAULT '100' COMMENT '好友帮助攒钱机率%',
  `limittype` tinyint(1) DEFAULT '0' COMMENT '限制类型0为只能一次1为每天一次',
  `totallimit` tinyint(1) DEFAULT '1' COMMENT '好友助力总次数制',
  `incomelimit` float(10,2) unsigned NOT NULL DEFAULT '10000.00' COMMENT '最高金额限制',
  `tixianlimit` float(10,2) unsigned NOT NULL DEFAULT '100.00' COMMENT '提现金额限制',
  `countlimit` int(5) NOT NULL COMMENT '活动总人数限制',
  `createtime` int(10) DEFAULT '0' COMMENT '活动创建时间',
  `share_acid` int(10) DEFAULT '0' COMMENT '默认分享公众号ID',
  `sharetip` varchar(100) NOT NULL COMMENT '分享提示内容',
  `fanpaitip` varchar(100) NOT NULL COMMENT '好友翻牌小提示',
  `awardtip` varchar(200) NOT NULL COMMENT '中奖小提示说明',
  `sharebtn` varchar(10) NOT NULL COMMENT '邀请好友攒钱文字',
  `fsharebtn` varchar(10) NOT NULL COMMENT '好友帮助邀请攒钱文字',
  `bgcolor` varchar(10) DEFAULT '' COMMENT '背景颜色',
  `fontcolor` varchar(10) DEFAULT '' COMMENT '文字颜色',
  `btncolor` varchar(10) DEFAULT '' COMMENT '按钮颜色',
  `btnfontcolor` varchar(10) DEFAULT '' COMMENT '按钮文字颜色',
  `txcolor` varchar(10) DEFAULT '' COMMENT '提现按钮颜色',
  `txfontcolor` varchar(10) DEFAULT '' COMMENT '提现按钮文字颜色',
  `rulebgcolor` varchar(10) DEFAULT '' COMMENT '规则框背景颜色',
  `ticketinfo` varchar(50) DEFAULT '' COMMENT '兑奖参数提示词',
  `isrealname` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入姓名0为不需要1为需要',
  `ismobile` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入手机号0为不需要1为需要',
  `isqq` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入QQ号0为不需要1为需要',
  `isemail` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入邮箱0为不需要1为需要',
  `isaddress` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入地址0为不需要1为需要',
  `isgender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入性别0为不需要1为需要',
  `istelephone` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入固定电话0为不需要1为需要',
  `isidcard` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入证件号码0为不需要1为需要',
  `iscompany` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入公司名称0为不需要1为需要',
  `isoccupation` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职业0为不需要1为需要',
  `isposition` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职位0为不需要1为需要',
  `isfans` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0只保存本模块下1同步更新至官方FANS表',
  `isfansname` varchar(225) NOT NULL DEFAULT '真实姓名,手机号码,QQ号,邮箱,地址,性别,固定电话,证件号码,公司名称,职业,职位' COMMENT '显示字段名称',
  `xuninum` int(10) unsigned NOT NULL DEFAULT '500' COMMENT '虚拟人数',
  `xuninumtime` int(10) unsigned NOT NULL DEFAULT '86400' COMMENT '虚拟间隔时间',
  `xuninuminitial` int(10) unsigned NOT NULL DEFAULT '10' COMMENT '虚拟随机数值1',
  `xuninumending` int(10) unsigned NOT NULL DEFAULT '100' COMMENT '虚拟随机数值2',
  `xuninum_time` int(10) unsigned NOT NULL COMMENT '虚拟更新时间',
  `homepictime` int(3) unsigned NOT NULL COMMENT '首页秒显图片显示时间',
  `homepic` varchar(225) NOT NULL COMMENT '首页秒显图片',
  `opportunity` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '参与选项 0任何人1关注粉丝2为商户赠送',
  `opportunity_txt` text NOT NULL COMMENT '商户赠送参数说明',
  `award_info` text NOT NULL COMMENT '奖品详细介绍',
  `credit_times` tinyint(1) DEFAULT '0',
  `credit_type` varchar(20) DEFAULT '',
  `showparameters` varchar(1000) NOT NULL COMMENT '显示界面参数：背景色、背景图以及文字色等',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_redenvelope_share`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_redenvelope_share`;
CREATE TABLE `ims_stonefish_redenvelope_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `acid` int(11) DEFAULT '0',
  `share_title` varchar(200) DEFAULT '',
  `share_desc` varchar(300) DEFAULT '',
  `share_url` varchar(255) DEFAULT '',
  `share_txt` text NOT NULL COMMENT '参与活动规则',
  `share_imgurl` varchar(255) NOT NULL COMMENT '分享朋友或朋友圈图',
  `share_picurl` varchar(255) NOT NULL COMMENT '分享图片按钮',
  `share_pic` varchar(255) NOT NULL COMMENT '分享弹出图片',
  `share_confirm` varchar(200) DEFAULT '' COMMENT '分享成功提示语',
  `share_fail` varchar(200) DEFAULT '' COMMENT '分享失败提示语',
  `share_cancel` varchar(200) DEFAULT '' COMMENT '分享中途取消提示语',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_acid` (`acid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_redenvelope_token`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_redenvelope_token`;
CREATE TABLE `ims_stonefish_redenvelope_token` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `access_token` varchar(1000) NOT NULL,
  `expires_in` int(11) DEFAULT NULL,
  `createtime` int(10) unsigned NOT NULL COMMENT '日期',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_scratch_exchange`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_scratch_exchange`;
CREATE TABLE `ims_stonefish_scratch_exchange` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `tickettype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖类型1为前端后台2为店员3为商家网点',
  `awardingtype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '单独兑奖1统一兑奖2',
  `beihuo` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启备货1开启0关闭',
  `beihuo_tips` varchar(20) DEFAULT '' COMMENT '备货提示词',
  `awardingpas` varchar(10) DEFAULT '' COMMENT '兑奖密码',
  `inventory` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖后库存1中奖减少2为兑奖后减少',
  `awardingstarttime` int(10) DEFAULT '0' COMMENT '兑奖开始时间',
  `awardingendtime` int(10) DEFAULT '0' COMMENT '兑奖结束时间',
  `awarding_tips` varchar(50) DEFAULT '' COMMENT '兑奖参数提示词',
  `awardingaddress` varchar(50) DEFAULT '' COMMENT '兑奖地点',
  `awardingtel` varchar(50) DEFAULT '' COMMENT '兑奖电话',
  `baidumaplng` varchar(10) DEFAULT '' COMMENT '兑奖导航',
  `baidumaplat` varchar(10) DEFAULT '' COMMENT '兑奖导航',
  `before` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖资料活动前还是中奖后1前2为后',
  `isrealname` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入姓名0为不需要1为需要',
  `ismobile` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入手机号0为不需要1为需要',
  `isqq` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入QQ号0为不需要1为需要',
  `isemail` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入邮箱0为不需要1为需要',
  `isaddress` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入地址0为不需要1为需要',
  `isgender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入性别0为不需要1为需要',
  `istelephone` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入固定电话0为不需要1为需要',
  `isidcard` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入证件号码0为不需要1为需要',
  `iscompany` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入公司名称0为不需要1为需要',
  `isoccupation` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职业0为不需要1为需要',
  `isposition` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职位0为不需要1为需要',
  `isfans` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0只保存本模块下1同步更新至官方FANS表',
  `isfansname` varchar(225) NOT NULL DEFAULT '真实姓名,手机号码,QQ号,邮箱,地址,性别,固定电话,证件号码,公司名称,职业,职位' COMMENT '显示字段名称',
  `tmplmsg_participate` int(11) DEFAULT '0' COMMENT '参与消息模板',
  `tmplmsg_winning` int(11) DEFAULT '0' COMMENT '中奖消息模板',
  `tmplmsg_exchange` int(11) DEFAULT '0' COMMENT '兑奖消息模板',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_scratch_fans`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_scratch_fans`;
CREATE TABLE `ims_stonefish_scratch_fans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid',
  `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `realname` varchar(20) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `qq` varchar(15) NOT NULL DEFAULT '' COMMENT '联系QQ号码',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '联系邮箱',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '联系地址',
  `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '固定电话',
  `idcard` varchar(30) NOT NULL DEFAULT '' COMMENT '证件号码',
  `company` varchar(50) NOT NULL DEFAULT '' COMMENT '公司名称',
  `occupation` varchar(30) NOT NULL DEFAULT '' COMMENT '职业',
  `position` varchar(30) NOT NULL DEFAULT '' COMMENT '职位',
  `inpoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '起始数',
  `outpoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '已兑换数',
  `sharepoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '分享助力',
  `sharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享量',
  `share_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享量',
  `sharetime` int(10) unsigned NOT NULL COMMENT '最后分享时间',
  `createtime` int(10) unsigned NOT NULL COMMENT '注册时间',
  `lasttime` int(10) unsigned NOT NULL COMMENT '最后参与时间',
  `tickettype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖类型1为前端后台2为店员3为商家网点',
  `ticketid` int(11) DEFAULT '0' COMMENT '店员或商家网点ID',
  `ticketname` varchar(50) DEFAULT '' COMMENT '店员或商家网点名称',
  `zhongjiang` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否中奖',
  `xuni` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否虚拟中奖',
  `todaynum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '今日参与次数',
  `totalnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总参与次数',
  `tosharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享使用次数',
  `awardnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '获奖次数',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否禁止',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_scratch_fansaward`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_scratch_fansaward`;
CREATE TABLE `ims_stonefish_scratch_fansaward` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `from_user` varchar(50) DEFAULT '0' COMMENT '用户openid',
  `prizeid` int(11) DEFAULT '0' COMMENT '奖品ID',
  `codesn` varchar(20) DEFAULT '0' COMMENT '中奖唯一码',
  `createtime` int(10) DEFAULT '0' COMMENT '领取时间',
  `consumetime` int(10) DEFAULT '0' COMMENT '使用时间',
  `openstatus` tinyint(1) DEFAULT '0' COMMENT '是否拆开',
  `zhongjiangtime` int(10) DEFAULT '0' COMMENT '中奖时间',
  `zhongjiang` tinyint(1) DEFAULT '0' COMMENT '是否中奖0未中奖1中奖2兑奖',
  `xuni` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否虚拟中奖',
  `tickettype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖类型1为前端后台2为店员3为商家网点',
  `ticketid` int(11) DEFAULT '0' COMMENT '店员或商家网点ID',
  `ticketname` varchar(50) DEFAULT '' COMMENT '店员或商家网点名称',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_prizeid` (`prizeid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_scratch_fanstmplmsg`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_scratch_fanstmplmsg`;
CREATE TABLE `ims_stonefish_scratch_fanstmplmsg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `from_user` varchar(50) DEFAULT '0' COMMENT '用户openid',
  `tmplmsgid` int(11) DEFAULT '0' COMMENT '消息模板ID',
  `tmplmsg` text NOT NULL COMMENT '发送内容',
  `createtime` int(10) DEFAULT '0' COMMENT '发送时间',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_prizeid` (`tmplmsgid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_scratch_prize`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_scratch_prize`;
CREATE TABLE `ims_stonefish_scratch_prize` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `prizetype` varchar(20) NOT NULL COMMENT '奖品类型真实虚拟积分等',
  `prizevalue` int(10) NOT NULL COMMENT '积分或实物以及虚拟价值',
  `prizerating` varchar(50) NOT NULL COMMENT '奖品等级',
  `prizename` varchar(50) NOT NULL COMMENT '奖品名称',
  `prizepic` varchar(255) NOT NULL COMMENT '奖品图片',
  `prizetotal` int(10) NOT NULL COMMENT '奖品数量',
  `prizedraw` int(10) NOT NULL COMMENT '中奖数量',
  `prizeren` int(10) NOT NULL COMMENT '每人最多中奖',
  `prizeday` int(10) NOT NULL COMMENT '每天最多发奖',
  `probalilty` varchar(5) NOT NULL COMMENT '中奖概率%',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '描述',
  `break` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '需要帮助人数',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_scratch_prizemika`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_scratch_prizemika`;
CREATE TABLE `ims_stonefish_scratch_prizemika` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `prizeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '奖品ID',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid',
  `mikacodesn` varchar(100) NOT NULL COMMENT '密卡字符串',
  `virtual_value` int(10) NOT NULL COMMENT '积分或实物以及虚拟价值',
  `actionurl` varchar(200) NOT NULL COMMENT '激活地址',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '描述',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否领取1为领取过',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_scratch_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_scratch_reply`;
CREATE TABLE `ims_stonefish_scratch_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `templateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动模板ID',
  `title` varchar(50) DEFAULT '' COMMENT '活动标题',
  `description` varchar(255) DEFAULT '' COMMENT '活动简介',
  `start_picurl` varchar(200) DEFAULT '' COMMENT '活动开始图片',
  `end_title` varchar(50) DEFAULT '' COMMENT '结束标题',
  `end_description` varchar(200) DEFAULT '' COMMENT '活动结束简介',
  `end_picurl` varchar(200) DEFAULT '' COMMENT '活动结束图片',
  `isshow` tinyint(1) DEFAULT '1' COMMENT '活动是否停止0为暂停1为活动中',
  `starttime` int(10) DEFAULT '0' COMMENT '开始时间',
  `endtime` int(10) DEFAULT '0' COMMENT '结束时间',
  `music` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否打开背景音乐',
  `musicurl` varchar(255) NOT NULL DEFAULT '' COMMENT '背景音乐地址',
  `mauto` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '音乐是否自动播放',
  `mloop` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否循环播放',
  `issubscribe` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '参与类型0为任意1为关注粉丝2为会员',
  `visubscribe` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '助力类型',
  `fansnum` int(10) DEFAULT '0' COMMENT '参与人数',
  `viewnum` int(10) DEFAULT '0' COMMENT '访问次数',
  `prize_num` int(10) DEFAULT '0' COMMENT '奖品总数',
  `award_num` int(11) DEFAULT '0' COMMENT '每人最多获奖次数',
  `award_num_tips` varchar(100) DEFAULT '' COMMENT '超过中奖数量提示',
  `number_times` int(11) DEFAULT '0' COMMENT '每人最多参与次数',
  `number_times_tips` varchar(100) DEFAULT '' COMMENT '超过总次数提示',
  `day_number_times` int(11) DEFAULT '0' COMMENT '每人每天最多参与次数',
  `day_number_times_tips` varchar(100) DEFAULT '' COMMENT '超过每天次数提示',
  `viewawardnum` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '首页显示中奖人数',
  `viewranknum` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '排行榜人数',
  `showprize` tinyint(1) DEFAULT '0' COMMENT '是否显示奖品',
  `prizeinfo` text NOT NULL COMMENT '奖品详细介绍',
  `awardtext` varchar(1000) DEFAULT '' COMMENT '中奖提示文字',
  `notawardtext` varchar(1000) DEFAULT '' COMMENT '没有中奖提示文字',
  `notprizetext` varchar(1000) DEFAULT '' COMMENT '没有奖品提示文字',
  `tips` varchar(200) DEFAULT '' COMMENT '活动次数提示',
  `copyright` varchar(20) DEFAULT '' COMMENT '版权',
  `inpointstart` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '初始分值1',
  `inpointend` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '初始分值2',
  `power` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否获取助力者头像昵称1opneid 2头像昵称',
  `poweravatar` varchar(3) DEFAULT '0' COMMENT '头像大小',
  `powertype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '助力类型0访问助力1点击助力',
  `randompointstart` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '助力随机金额范围开始数',
  `randompointend` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '助力随机金额范围结束数',
  `addp` tinyint(1) DEFAULT '100' COMMENT '好友助力机率%',
  `limittype` tinyint(1) DEFAULT '0' COMMENT '限制类型0为只能一次1为每天一次',
  `totallimit` tinyint(1) DEFAULT '1' COMMENT '好友助力总次数制',
  `helptype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '互助0为互助1为禁止',
  `xuninum` int(10) unsigned NOT NULL DEFAULT '500' COMMENT '虚拟人数',
  `xuninumtime` int(10) unsigned NOT NULL DEFAULT '86400' COMMENT '虚拟间隔时间',
  `xuninuminitial` int(10) unsigned NOT NULL DEFAULT '10' COMMENT '虚拟随机数值1',
  `xuninumending` int(10) unsigned NOT NULL DEFAULT '100' COMMENT '虚拟随机数值2',
  `xuninum_time` int(10) unsigned NOT NULL COMMENT '虚拟更新时间',
  `adpic` varchar(255) DEFAULT '' COMMENT '活动页顶部广告图',
  `adpicurl` varchar(255) DEFAULT '' COMMENT '活动页顶部广告链接',
  `homepictime` tinyint(1) unsigned NOT NULL COMMENT '首页秒显图片显示时间',
  `homepictype` tinyint(1) unsigned NOT NULL COMMENT '首页广告类型1为每次2为每天3为每周4为仅1次',
  `homepic` varchar(225) NOT NULL COMMENT '首页秒显图片',
  `opportunity` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '参与次数选项0活动设置1商户赠送2为积分购买',
  `opportunity_txt` text NOT NULL COMMENT '商户赠送/积分购买说明',
  `credit_type` varchar(20) DEFAULT '' COMMENT '积分类型',
  `credit_value` int(11) DEFAULT '0' COMMENT '积分购买多少积分',
  `createtime` int(10) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_scratch_share`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_scratch_share`;
CREATE TABLE `ims_stonefish_scratch_share` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `acid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '子公众号ID',
  `help_url` varchar(255) DEFAULT '' COMMENT '帮助关注引导页',
  `share_url` varchar(255) DEFAULT '' COMMENT '参与关注引导页',
  `share_open_close` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启作用',
  `share_title` varchar(50) DEFAULT '' COMMENT '分享标题',
  `share_desc` varchar(100) DEFAULT '' COMMENT '分享简介',
  `share_txt` text NOT NULL COMMENT '参与活动规则',
  `share_img` varchar(255) NOT NULL COMMENT '分享朋友或朋友圈图',
  `share_anniu` varchar(255) NOT NULL COMMENT '分享朋友或朋友圈按钮或文字',
  `share_firend` varchar(255) NOT NULL COMMENT '助力按钮',
  `share_pic` varchar(255) NOT NULL COMMENT '分享弹出图片',
  `share_confirm` varchar(200) DEFAULT '' COMMENT '分享成功提示语',
  `share_confirmurl` varchar(255) DEFAULT '' COMMENT '分享成功跳转URL',
  `share_fail` varchar(200) DEFAULT '' COMMENT '分享失败提示语',
  `share_cancel` varchar(200) DEFAULT '' COMMENT '分享中途取消提示语',
  `sharetimes` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为每天次数2为总次数',
  `sharetype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '分享赠送类型0分享立即赠送1分享成功赠送',
  `sharenumtype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '分享赠送机会类型0单独赠送机会1每人赠送机会2分享共计赠送',
  `sharenum` varchar(5) DEFAULT '0' COMMENT '分享赠送礼盒基数',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_acid` (`acid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_scratch_sharedata`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_scratch_sharedata`;
CREATE TABLE `ims_stonefish_scratch_sharedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '分享人openid',
  `fromuser` varchar(50) NOT NULL DEFAULT '' COMMENT '访问人openid',
  `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `visitorsip` varchar(15) NOT NULL DEFAULT '' COMMENT '访问IP',
  `visitorstime` int(10) unsigned NOT NULL COMMENT '访问时间',
  `point` decimal(10,2) DEFAULT '0.00' COMMENT '助力金额',
  `viewnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看次数',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_scratch_template`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_scratch_template`;
CREATE TABLE `ims_stonefish_scratch_template` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `title` varchar(20) DEFAULT '' COMMENT '模板名称',
  `thumb` varchar(255) DEFAULT '' COMMENT '模板缩略图',
  `fontsize` varchar(2) DEFAULT '12' COMMENT '文字大小',
  `bgimg` varchar(255) DEFAULT '' COMMENT '背景图',
  `bgcolor` varchar(7) DEFAULT '' COMMENT '背景色',
  `textcolor` varchar(7) DEFAULT '' COMMENT '文字色',
  `textcolorlink` varchar(7) DEFAULT '' COMMENT '链接文字色',
  `buttoncolor` varchar(7) DEFAULT '' COMMENT '按钮色',
  `buttontextcolor` varchar(7) DEFAULT '' COMMENT '按钮文字色',
  `rulecolor` varchar(7) DEFAULT '' COMMENT '规则框背景色',
  `ruletextcolor` varchar(7) DEFAULT '' COMMENT '规则框文字色',
  `navcolor` varchar(7) DEFAULT '' COMMENT '导航色',
  `navtextcolor` varchar(7) DEFAULT '' COMMENT '导航文字色',
  `navactioncolor` varchar(7) DEFAULT '' COMMENT '导航选中文字色',
  `watchcolor` varchar(7) DEFAULT '' COMMENT '弹出框背景色',
  `watchtextcolor` varchar(7) DEFAULT '' COMMENT '弹出框文字色',
  `awardcolor` varchar(7) DEFAULT '' COMMENT '兑奖框背景色',
  `awardtextcolor` varchar(7) DEFAULT '' COMMENT '兑奖框文字色',
  `awardscolor` varchar(7) DEFAULT '' COMMENT '兑奖框成功背景色',
  `awardstextcolor` varchar(7) DEFAULT '' COMMENT '兑奖框成功文字色',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_stonefish_scratch_tmplmsg`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stonefish_scratch_tmplmsg`;
CREATE TABLE `ims_stonefish_scratch_tmplmsg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `template_id` varchar(50) DEFAULT '' COMMENT '模板ID',
  `template_name` varchar(20) DEFAULT '' COMMENT '模板名称',
  `topcolor` varchar(7) DEFAULT '' COMMENT '通知文字色',
  `first` varchar(100) DEFAULT '' COMMENT '标题',
  `firstcolor` varchar(7) DEFAULT '' COMMENT '标题文字色',
  `keyword1` varchar(100) DEFAULT '' COMMENT '参数1',
  `keyword1code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword1color` varchar(7) DEFAULT '' COMMENT '参数1文字色',
  `keyword2` varchar(100) DEFAULT '' COMMENT '参数2',
  `keyword2code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword2color` varchar(7) DEFAULT '' COMMENT '参数2文字色',
  `keyword3` varchar(100) DEFAULT '' COMMENT '参数3',
  `keyword3code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword3color` varchar(7) DEFAULT '' COMMENT '参数3文字色',
  `keyword4` varchar(100) DEFAULT '' COMMENT '参数4',
  `keyword4code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword4color` varchar(7) DEFAULT '' COMMENT '参数4文字色',
  `keyword5` varchar(100) DEFAULT '' COMMENT '参数5',
  `keyword5code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword5color` varchar(7) DEFAULT '' COMMENT '参数5文字色',
  `keyword6` varchar(100) DEFAULT '' COMMENT '参数6',
  `keyword6code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword6color` varchar(7) DEFAULT '' COMMENT '参数6文字色',
  `keyword7` varchar(100) DEFAULT '' COMMENT '参数7',
  `keyword7code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword7color` varchar(7) DEFAULT '' COMMENT '参数7文字色',
  `keyword8` varchar(100) DEFAULT '' COMMENT '参数8',
  `keyword8code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword8color` varchar(7) DEFAULT '' COMMENT '参数8文字色',
  `keyword9` varchar(100) DEFAULT '' COMMENT '参数9',
  `keyword9code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword9color` varchar(7) DEFAULT '' COMMENT '参数9文字色',
  `keyword10` varchar(100) DEFAULT '' COMMENT '参数10',
  `keyword10code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword10color` varchar(7) DEFAULT '' COMMENT '参数10文字色',
  `remark` varchar(100) DEFAULT '' COMMENT '备注',
  `remarkcolor` varchar(7) DEFAULT '' COMMENT '备注文字色',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_str_config`
-- ----------------------------
DROP TABLE IF EXISTS `ims_str_config`;
CREATE TABLE `ims_str_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `paytime_limit` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_str_dish`
-- ----------------------------
DROP TABLE IF EXISTS `ims_str_dish`;
CREATE TABLE `ims_str_dish` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `sid` int(10) unsigned NOT NULL DEFAULT '0',
  `cid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(30) NOT NULL,
  `price` int(10) unsigned NOT NULL DEFAULT '0',
  `total` int(10) NOT NULL DEFAULT '0',
  `sailed` int(10) unsigned NOT NULL,
  `is_display` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `thumb` varchar(60) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `description` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_str_dish_category`
-- ----------------------------
DROP TABLE IF EXISTS `ims_str_dish_category`;
CREATE TABLE `ims_str_dish_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `sid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(20) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_str_dish_comment`
-- ----------------------------
DROP TABLE IF EXISTS `ims_str_dish_comment`;
CREATE TABLE `ims_str_dish_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `oid` int(10) unsigned NOT NULL,
  `did` int(10) unsigned NOT NULL,
  `score` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `oid` (`oid`),
  KEY `did` (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_str_order`
-- ----------------------------
DROP TABLE IF EXISTS `ims_str_order`;
CREATE TABLE `ims_str_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `sid` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `address` varchar(100) NOT NULL,
  `note` varchar(200) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `num` tinyint(3) unsigned NOT NULL,
  `delivery_time` varchar(15) NOT NULL,
  `pay_type` varchar(15) NOT NULL,
  `dish` varchar(3000) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) NOT NULL DEFAULT '2',
  `comment` tinyint(3) unsigned NOT NULL DEFAULT '2',
  `print_nums` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid_sid` (`uniacid`,`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_str_order_print`
-- ----------------------------
DROP TABLE IF EXISTS `ims_str_order_print`;
CREATE TABLE `ims_str_order_print` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `sid` int(10) unsigned NOT NULL DEFAULT '0',
  `pid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `oid` int(10) unsigned NOT NULL DEFAULT '0',
  `foid` varchar(50) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '2',
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `addtime` (`addtime`),
  KEY `foid` (`foid`),
  KEY `uniacid` (`uniacid`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_str_print`
-- ----------------------------
DROP TABLE IF EXISTS `ims_str_print`;
CREATE TABLE `ims_str_print` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `sid` int(10) unsigned NOT NULL,
  `name` varchar(20) NOT NULL,
  `print_no` varchar(30) NOT NULL,
  `key` varchar(30) NOT NULL,
  `print_nums` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `qrcode_link` varchar(100) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_str_store`
-- ----------------------------
DROP TABLE IF EXISTS `ims_str_store`;
CREATE TABLE `ims_str_store` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(30) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `business_hours` varchar(200) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `send_price` smallint(5) unsigned NOT NULL DEFAULT '0',
  `delivery_price` smallint(5) unsigned NOT NULL DEFAULT '0',
  `delivery_time` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `serve_radius` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `delivery_area` varchar(50) NOT NULL,
  `thumbs` varchar(1000) NOT NULL,
  `district` varchar(40) NOT NULL,
  `address` varchar(50) NOT NULL,
  `location_x` varchar(15) NOT NULL,
  `location_y` varchar(15) NOT NULL,
  `email_notice` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `email` varchar(30) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `notice_acid` int(10) unsigned NOT NULL DEFAULT '0',
  `groupid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_sunshine_gaokaotoutiao_member`
-- ----------------------------
DROP TABLE IF EXISTS `ims_sunshine_gaokaotoutiao_member`;
CREATE TABLE `ims_sunshine_gaokaotoutiao_member` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `openid` varchar(500) NOT NULL DEFAULT '',
  `realname` varchar(500) NOT NULL DEFAULT '',
  `place` text NOT NULL,
  `add_time` datetime NOT NULL,
  `type_id` int(10) NOT NULL DEFAULT '0' COMMENT 'sucai_id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_sunshine_gaokaotoutiao_sucai`
-- ----------------------------
DROP TABLE IF EXISTS `ims_sunshine_gaokaotoutiao_sucai`;
CREATE TABLE `ims_sunshine_gaokaotoutiao_sucai` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `img_url` varchar(500) NOT NULL DEFAULT '',
  `add_time` datetime NOT NULL,
  `is_del` enum('0','1') DEFAULT '0' COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_superman_floor`
-- ----------------------------
DROP TABLE IF EXISTS `ims_superman_floor`;
CREATE TABLE `ims_superman_floor` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `awardprompt` text NOT NULL,
  `currentprompt` text NOT NULL,
  `floorprompt` text NOT NULL,
  `setting` text NOT NULL,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_superman_floor_award`
-- ----------------------------
DROP TABLE IF EXISTS `ims_superman_floor_award`;
CREATE TABLE `ims_superman_floor_award` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `floors` varchar(1000) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_superman_floor_winner`
-- ----------------------------
DROP TABLE IF EXISTS `ims_superman_floor_winner`;
CREATE TABLE `ims_superman_floor_winner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `floor` int(4) unsigned NOT NULL DEFAULT '0',
  `uid` int(4) unsigned NOT NULL DEFAULT '0',
  `openid` varchar(50) NOT NULL DEFAULT '0',
  `award_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` char(15) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `realname` varchar(128) NOT NULL DEFAULT '',
  `mobile` varchar(20) NOT NULL DEFAULT '',
  `qq` varchar(20) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `rid_floor_UNIQUE` (`rid`,`floor`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_super_securitycode_data_moban`
-- ----------------------------
DROP TABLE IF EXISTS `ims_super_securitycode_data_moban`;
CREATE TABLE `ims_super_securitycode_data_moban` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `brand` varchar(20) DEFAULT NULL,
  `spec` varchar(20) DEFAULT NULL,
  `weight` varchar(20) DEFAULT NULL,
  `factory` varchar(500) NOT NULL,
  `remarks` varchar(100) DEFAULT NULL,
  `creditname` varchar(20) NOT NULL,
  `creditnum` int(10) unsigned NOT NULL,
  `creditstatus` tinyint(1) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `stime` int(10) unsigned NOT NULL,
  `createtime` decimal(11,0) NOT NULL,
  `num` int(10) NOT NULL,
  `tourl` varchar(500) DEFAULT NULL,
  `img_banner` varchar(500) DEFAULT NULL,
  `img_logo` varchar(500) DEFAULT NULL COMMENT '图片',
  `video` varchar(500) DEFAULT NULL COMMENT '视频',
  `buyurl` varchar(500) DEFAULT NULL COMMENT '购买链接',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_super_securitycode_logs`
-- ----------------------------
DROP TABLE IF EXISTS `ims_super_securitycode_logs`;
CREATE TABLE `ims_super_securitycode_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `code` varchar(50) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_super_securitycode_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_super_securitycode_reply`;
CREATE TABLE `ims_super_securitycode_reply` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `weid` int(10) NOT NULL,
  `Reply` varchar(1000) NOT NULL,
  `Integral` int(10) NOT NULL,
  `tnumber` int(10) NOT NULL,
  `Failure` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_survey`
-- ----------------------------
DROP TABLE IF EXISTS `ims_survey`;
CREATE TABLE `ims_survey` (
  `sid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) unsigned NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL,
  `content` varchar(1000) NOT NULL,
  `information` varchar(500) NOT NULL DEFAULT '',
  `thumb` varchar(200) NOT NULL DEFAULT '',
  `inhome` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL,
  `pertotal` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `suggest_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`sid`),
  KEY `weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_survey_data`
-- ----------------------------
DROP TABLE IF EXISTS `ims_survey_data`;
CREATE TABLE `ims_survey_data` (
  `sdid` bigint(20) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL,
  `srid` int(11) NOT NULL,
  `sfid` int(11) NOT NULL,
  `data` varchar(800) NOT NULL,
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`sdid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_survey_fields`
-- ----------------------------
DROP TABLE IF EXISTS `ims_survey_fields`;
CREATE TABLE `ims_survey_fields` (
  `sfid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(11) unsigned NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `essential` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `bind` varchar(30) NOT NULL DEFAULT '',
  `value` varchar(300) NOT NULL DEFAULT '',
  `description` varchar(500) NOT NULL DEFAULT '',
  `displayorder` int(10) unsigned NOT NULL,
  PRIMARY KEY (`sfid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_survey_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_survey_reply`;
CREATE TABLE `ims_survey_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_survey_rows`
-- ----------------------------
DROP TABLE IF EXISTS `ims_survey_rows`;
CREATE TABLE `ims_survey_rows` (
  `srid` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `suggest` varchar(500) NOT NULL,
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`srid`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_tg_address`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_address`;
CREATE TABLE `ims_tg_address` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `openid` varchar(300) NOT NULL,
  `cname` varchar(30) NOT NULL COMMENT '收货人名称',
  `tel` varchar(20) NOT NULL COMMENT '手机号',
  `province` varchar(20) NOT NULL COMMENT '省',
  `city` varchar(20) NOT NULL COMMENT '市',
  `county` varchar(20) NOT NULL COMMENT '县(区)',
  `detailed_address` varchar(225) NOT NULL COMMENT '详细地址',
  `uniacid` int(10) NOT NULL COMMENT '公众号id',
  `addtime` varchar(45) NOT NULL,
  `status` int(2) NOT NULL COMMENT '1为默认',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_tg_admin`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_admin`;
CREATE TABLE `ims_tg_admin` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `username` varchar(30) NOT NULL COMMENT '管理员名称',
  `password` varchar(20) NOT NULL COMMENT '管理员密码',
  `email` varchar(60) NOT NULL COMMENT '邮箱',
  `tel` varchar(20) NOT NULL COMMENT '手机号',
  `uniacid` int(10) DEFAULT NULL COMMENT '公众号id',
  `openid` varchar(100) DEFAULT NULL COMMENT '用户openid',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `openid` (`openid`),
  UNIQUE KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_tg_adv`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_adv`;
CREATE TABLE `ims_tg_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_tg_category`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_category`;
CREATE TABLE `ims_tg_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `thumb` varchar(255) NOT NULL COMMENT '分类图片',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0',
  `description` varchar(500) NOT NULL COMMENT '分类介绍',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_tg_collect`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_collect`;
CREATE TABLE `ims_tg_collect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `openid` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_tg_goods`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_goods`;
CREATE TABLE `ims_tg_goods` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `gname` varchar(225) NOT NULL COMMENT '商品名称',
  `fk_typeid` int(10) unsigned NOT NULL COMMENT '所属分类id',
  `gsn` varchar(50) NOT NULL COMMENT '商品货号',
  `gnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品库存',
  `groupnum` int(10) unsigned NOT NULL COMMENT '最低拼团人数',
  `mprice` decimal(10,2) NOT NULL,
  `gprice` decimal(10,2) NOT NULL COMMENT '团购价',
  `oprice` decimal(10,2) NOT NULL COMMENT '单买价',
  `freight` decimal(10,2) NOT NULL,
  `gdesc` longtext NOT NULL COMMENT '商品简介',
  `gdesc1` varchar(100) DEFAULT NULL COMMENT '商品特点1',
  `gdesc2` varchar(100) DEFAULT NULL COMMENT '商品特点2',
  `gdesc3` varchar(100) DEFAULT NULL COMMENT '商品特点3',
  `gimg` varchar(225) DEFAULT NULL COMMENT '商品图片路径',
  `gubtime` int(10) unsigned NOT NULL COMMENT '商品上架时间',
  `isshow` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否上架',
  `salenum` int(10) unsigned NOT NULL COMMENT '销量',
  `ishot` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否热卖',
  `displayorder` int(11) NOT NULL,
  `createtime` int(10) unsigned NOT NULL COMMENT '最后修改时间',
  `uniacid` int(10) NOT NULL COMMENT '公众号的id',
  `endtime` int(11) NOT NULL COMMENT '团购限时（小时数）',
  PRIMARY KEY (`id`),
  UNIQUE KEY `gname` (`gname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_tg_goods_atlas`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_goods_atlas`;
CREATE TABLE `ims_tg_goods_atlas` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `g_id` int(11) NOT NULL COMMENT '商品id',
  `thumb` varchar(145) NOT NULL COMMENT '图片路径',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_tg_goods_imgs`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_goods_imgs`;
CREATE TABLE `ims_tg_goods_imgs` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `fk_gid` int(10) NOT NULL COMMENT '对应商品的id',
  `albumpath` varchar(225) NOT NULL COMMENT '图片路径',
  `uniacid` int(10) NOT NULL COMMENT '公众号id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `fk_gid` (`fk_gid`),
  UNIQUE KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_tg_goods_param`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_goods_param`;
CREATE TABLE `ims_tg_goods_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `value` text,
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`goodsid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_tg_goods_type`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_goods_type`;
CREATE TABLE `ims_tg_goods_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `cname` varchar(30) NOT NULL COMMENT '分类名称',
  `pid` int(10) DEFAULT NULL COMMENT '上级分类的id',
  `uniacid` int(10) DEFAULT NULL COMMENT '公众号的id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_tg_member`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_member`;
CREATE TABLE `ims_tg_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号id',
  `from_user` varchar(50) NOT NULL COMMENT '微信会员openID',
  `nickname` varchar(20) NOT NULL COMMENT '昵称',
  `avatar` varchar(255) NOT NULL COMMENT '头像',
  `addtime` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_tg_order`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_order`;
CREATE TABLE `ims_tg_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uniacid` varchar(45) NOT NULL COMMENT '公众号',
  `gnum` int(11) NOT NULL COMMENT '购买数量',
  `openid` varchar(45) NOT NULL COMMENT '用户名',
  `ptime` varchar(45) NOT NULL COMMENT '支付成功时间',
  `orderno` varchar(45) NOT NULL COMMENT '订单编号',
  `price` varchar(45) NOT NULL COMMENT '价格',
  `status` int(9) NOT NULL COMMENT '订单状态0未支1支付，2已发货，3完成订单，9取消订单',
  `addressid` int(11) NOT NULL COMMENT '地址id',
  `g_id` int(11) NOT NULL COMMENT '商品id',
  `tuan_id` int(11) NOT NULL COMMENT '团id',
  `is_tuan` int(2) NOT NULL COMMENT '是否为团1为团0为单人',
  `createtime` varchar(45) NOT NULL COMMENT '订单生成时间',
  `pay_type` int(4) NOT NULL COMMENT '支付方式',
  `starttime` varchar(45) NOT NULL COMMENT '开始时间',
  `endtime` int(45) NOT NULL COMMENT '结束时间（小时）',
  `tuan_first` int(11) NOT NULL COMMENT '团长',
  `express` varchar(50) DEFAULT NULL COMMENT '快递公司名称',
  `expresssn` varchar(50) DEFAULT NULL COMMENT '快递单号',
  `transid` varchar(50) NOT NULL,
  `remark` varchar(100) NOT NULL,
  `success` int(11) NOT NULL,
  `addname` varchar(50) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `address` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_tg_order_goods`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_order_goods`;
CREATE TABLE `ims_tg_order_goods` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `fk_orderid` int(10) NOT NULL COMMENT '订单id',
  `fk_goodid` int(10) NOT NULL COMMENT '商品id',
  `uniacid` int(10) NOT NULL COMMENT '公众号id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `fk_orderid` (`fk_orderid`),
  UNIQUE KEY `fk_goodid` (`fk_goodid`),
  UNIQUE KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_tg_order_print`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_order_print`;
CREATE TABLE `ims_tg_order_print` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `sid` int(10) NOT NULL,
  `pid` int(3) NOT NULL,
  `oid` int(10) NOT NULL,
  `foid` varchar(50) NOT NULL,
  `status` int(3) NOT NULL,
  `addtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_tg_print`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_print`;
CREATE TABLE `ims_tg_print` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `sid` int(10) NOT NULL,
  `name` varchar(45) NOT NULL,
  `print_no` varchar(50) NOT NULL,
  `key` varchar(50) NOT NULL,
  `member_code` varchar(50) NOT NULL,
  `print_nums` int(3) NOT NULL,
  `qrcode_link` varchar(100) NOT NULL,
  `status` int(3) NOT NULL,
  `mode` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_tg_refund_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_refund_record`;
CREATE TABLE `ims_tg_refund_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transid` int(11) NOT NULL COMMENT '订单编号',
  `createtime` varchar(45) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0未成功1成功',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_tg_rules`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_rules`;
CREATE TABLE `ims_tg_rules` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `rulesname` varchar(40) NOT NULL COMMENT '规则名称',
  `rulesdetail` varchar(4000) DEFAULT NULL COMMENT '规则详情',
  `uniacid` int(10) NOT NULL COMMENT '公众号的id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `rulesname` (`rulesname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_tg_users`
-- ----------------------------
DROP TABLE IF EXISTS `ims_tg_users`;
CREATE TABLE `ims_tg_users` (
  `id` int(10) NOT NULL COMMENT '主键',
  `username` varchar(30) NOT NULL COMMENT '用户名',
  `password` varchar(20) NOT NULL COMMENT '用户密码',
  `email` varchar(60) NOT NULL COMMENT '邮箱',
  `tel` varchar(20) NOT NULL COMMENT '电话',
  `uniacid` int(10) NOT NULL COMMENT '公众号id',
  `openid` varchar(100) NOT NULL COMMENT '用户openid',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `uniacid` (`uniacid`),
  UNIQUE KEY `openid` (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_thinkidea_phonebook_category`
-- ----------------------------
DROP TABLE IF EXISTS `ims_thinkidea_phonebook_category`;
CREATE TABLE `ims_thinkidea_phonebook_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '行业名称',
  `parent_id` int(11) NOT NULL COMMENT '父id',
  `display` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `isshow` smallint(1) DEFAULT '1' COMMENT '是否显示',
  `dateline` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分类表';

-- ----------------------------
--  Table structure for `ims_thinkidea_phonebook_info`
-- ----------------------------
DROP TABLE IF EXISTS `ims_thinkidea_phonebook_info`;
CREATE TABLE `ims_thinkidea_phonebook_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(250) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `zone` smallint(6) NOT NULL,
  `category` smallint(6) NOT NULL,
  `address` varchar(250) NOT NULL,
  `isauth` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否认证',
  `coordinate` varchar(50) NOT NULL COMMENT '坐标',
  `dateline` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dateline` (`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='电话本内容';

-- ----------------------------
--  Table structure for `ims_thinkidea_phonebook_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_thinkidea_phonebook_reply`;
CREATE TABLE `ims_thinkidea_phonebook_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` smallint(6) NOT NULL,
  `weid` smallint(6) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `avatar` varchar(250) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `dateline` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_thinkidea_phonebook_zone`
-- ----------------------------
DROP TABLE IF EXISTS `ims_thinkidea_phonebook_zone`;
CREATE TABLE `ims_thinkidea_phonebook_zone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '名称',
  `parent_id` int(11) NOT NULL COMMENT '父id',
  `display` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `isshow` smallint(1) DEFAULT '1' COMMENT '是否显示',
  `dateline` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='区域表';

-- ----------------------------
--  Table structure for `ims_thinkidea_rencai_adslider`
-- ----------------------------
DROP TABLE IF EXISTS `ims_thinkidea_rencai_adslider`;
CREATE TABLE `ims_thinkidea_rencai_adslider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` smallint(6) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '广告名称',
  `position` tinyint(1) NOT NULL COMMENT '幻灯、ad在的位置',
  `url` varchar(250) NOT NULL,
  `link` varchar(250) NOT NULL COMMENT '链接地址',
  `isshow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `display` smallint(6) NOT NULL DEFAULT '0' COMMENT '排序',
  `dateline` int(11) NOT NULL DEFAULT '0',
  `exprtime` int(11) NOT NULL DEFAULT '0' COMMENT '过期时间',
  PRIMARY KEY (`id`),
  KEY `weid` (`weid`,`position`,`isshow`,`display`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='幻灯、广告投放管理';

-- ----------------------------
--  Table structure for `ims_thinkidea_rencai_apply_jobs`
-- ----------------------------
DROP TABLE IF EXISTS `ims_thinkidea_rencai_apply_jobs`;
CREATE TABLE `ims_thinkidea_rencai_apply_jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL COMMENT '公众号',
  `person_id` int(11) NOT NULL COMMENT '求职者个人id',
  `company_id` int(11) NOT NULL COMMENT '公司id',
  `job_id` int(11) NOT NULL COMMENT '职位id',
  `dateline` int(11) NOT NULL COMMENT '申请时间',
  PRIMARY KEY (`id`),
  KEY `weid` (`weid`,`person_id`,`job_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='求职者申请职位表';

-- ----------------------------
--  Table structure for `ims_thinkidea_rencai_category`
-- ----------------------------
DROP TABLE IF EXISTS `ims_thinkidea_rencai_category`;
CREATE TABLE `ims_thinkidea_rencai_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `isshow` smallint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `display` smallint(1) NOT NULL DEFAULT '0' COMMENT '排序',
  `ishot` smallint(6) NOT NULL DEFAULT '0' COMMENT '是否热门',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='职位分类表';

-- ----------------------------
--  Table structure for `ims_thinkidea_rencai_company`
-- ----------------------------
DROP TABLE IF EXISTS `ims_thinkidea_rencai_company`;
CREATE TABLE `ims_thinkidea_rencai_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL DEFAULT '0',
  `from_user` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(100) DEFAULT NULL COMMENT '公司名称',
  `industry` smallint(1) DEFAULT NULL COMMENT '公司所属行业类别',
  `address` varchar(250) DEFAULT NULL COMMENT '公司地址',
  `contact` varchar(20) DEFAULT NULL COMMENT '联系人',
  `mobile` char(11) DEFAULT NULL COMMENT '手机',
  `scale` smallint(1) NOT NULL DEFAULT '0' COMMENT '规模',
  `type` tinyint(1) DEFAULT '0' COMMENT '企业类型',
  `description` text COMMENT '公司简介',
  `license` varchar(250) DEFAULT NULL COMMENT '公司营业执照',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否通过审核',
  `isauth` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否认证',
  `view_resume_nums` smallint(6) NOT NULL DEFAULT '0' COMMENT '已经查看简历数',
  `view_resume_total` smallint(6) NOT NULL DEFAULT '0' COMMENT '查看简历数上限',
  `dateline` int(11) NOT NULL,
  `coordinate` varchar(255) NOT NULL DEFAULT '',
  `logo` varchar(255) NOT NULL DEFAULT '',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '企业封面',
  `position` tinyint(1) NOT NULL DEFAULT '0' COMMENT '推荐位',
  PRIMARY KEY (`id`),
  KEY `weid_from_user` (`weid`,`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_thinkidea_rencai_industry`
-- ----------------------------
DROP TABLE IF EXISTS `ims_thinkidea_rencai_industry`;
CREATE TABLE `ims_thinkidea_rencai_industry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '行业名称',
  `parent_id` int(11) NOT NULL COMMENT '父id',
  `display` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `ishot` smallint(1) DEFAULT '0' COMMENT '是否热门',
  `isshow` smallint(1) DEFAULT '1' COMMENT '是否显示',
  `dateline` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='行业分类表';

-- ----------------------------
--  Table structure for `ims_thinkidea_rencai_job`
-- ----------------------------
DROP TABLE IF EXISTS `ims_thinkidea_rencai_job`;
CREATE TABLE `ims_thinkidea_rencai_job` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `mid` int(11) NOT NULL COMMENT '企业id',
  `title` varchar(100) NOT NULL COMMENT '职位名称',
  `cid` int(11) NOT NULL COMMENT '职位类别id',
  `end_time` varchar(20) NOT NULL COMMENT '岗位截止日期',
  `payroll` smallint(6) NOT NULL COMMENT '薪资',
  `educational` tinyint(4) NOT NULL COMMENT '学历',
  `workexperience` tinyint(4) NOT NULL COMMENT '工作经验',
  `welfare` varchar(50) NOT NULL COMMENT '福利保障',
  `positiontype` tinyint(4) NOT NULL COMMENT '职位类型',
  `nums` int(11) NOT NULL COMMENT '招聘人数',
  `workaddress` varchar(50) NOT NULL COMMENT '工作地点',
  `description` varchar(255) NOT NULL COMMENT '职位信息描述',
  `views` int(11) NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `istop` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否置顶',
  `ishot` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否热门',
  `expiration` int(11) NOT NULL DEFAULT '0' COMMENT '置顶过期时间',
  `dateline` int(11) NOT NULL COMMENT '发布时间',
  PRIMARY KEY (`id`),
  KEY `weid_from_dateline` (`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='公司职位信息';

-- ----------------------------
--  Table structure for `ims_thinkidea_rencai_jobs_comments`
-- ----------------------------
DROP TABLE IF EXISTS `ims_thinkidea_rencai_jobs_comments`;
CREATE TABLE `ims_thinkidea_rencai_jobs_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` smallint(6) DEFAULT NULL,
  `mid` int(11) DEFAULT NULL COMMENT '用户id可以是求职者也可以是招聘者',
  `jobid` int(11) DEFAULT NULL COMMENT '如果1jobid如果2resumeid',
  `content` varchar(250) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `dateline` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='评论表';

-- ----------------------------
--  Table structure for `ims_thinkidea_rencai_member`
-- ----------------------------
DROP TABLE IF EXISTS `ims_thinkidea_rencai_member`;
CREATE TABLE `ims_thinkidea_rencai_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `from_user` varchar(50) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL COMMENT '是1企业还是2个人',
  `status` smallint(1) NOT NULL DEFAULT '0' COMMENT '状态。是否可用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='注册用户表含企业、个人。作快速查询使用';

-- ----------------------------
--  Table structure for `ims_thinkidea_rencai_person`
-- ----------------------------
DROP TABLE IF EXISTS `ims_thinkidea_rencai_person`;
CREATE TABLE `ims_thinkidea_rencai_person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `from_user` varchar(100) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `headimgurl` varchar(250) DEFAULT NULL,
  `sex` smallint(1) DEFAULT NULL,
  `mobile` varchar(11) DEFAULT NULL,
  `qq` varchar(20) DEFAULT NULL,
  `age` smallint(6) DEFAULT NULL,
  `educational` tinyint(1) DEFAULT NULL COMMENT '我的学历',
  `professional` varchar(50) DEFAULT NULL COMMENT '我的专业',
  `workexperience` smallint(6) DEFAULT NULL COMMENT '工作经验',
  `workaddress` varchar(50) DEFAULT NULL COMMENT '期望工作地点',
  `assessment` varchar(255) DEFAULT NULL COMMENT '自我评价',
  `istop` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否置顶该简历',
  `expiration` int(11) NOT NULL DEFAULT '0',
  `dateline` int(11) NOT NULL,
  `views` int(11) NOT NULL COMMENT '被浏览数',
  `updatetime` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `weid_from_user` (`weid`,`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='求职个人表';

-- ----------------------------
--  Table structure for `ims_thinkidea_rencai_person_collect`
-- ----------------------------
DROP TABLE IF EXISTS `ims_thinkidea_rencai_person_collect`;
CREATE TABLE `ims_thinkidea_rencai_person_collect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `person_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `job_id` int(11) DEFAULT NULL COMMENT '职位id',
  `dateline` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='收藏职位表';

-- ----------------------------
--  Table structure for `ims_thinkidea_rencai_person_resume`
-- ----------------------------
DROP TABLE IF EXISTS `ims_thinkidea_rencai_person_resume`;
CREATE TABLE `ims_thinkidea_rencai_person_resume` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person_id` int(11) DEFAULT NULL,
  `weid` int(11) DEFAULT NULL,
  `company_name` varchar(50) DEFAULT NULL COMMENT '公司名称',
  `start_time` char(11) DEFAULT NULL COMMENT '开始时间',
  `end_time` char(11) DEFAULT NULL COMMENT '结束时间',
  `wage` int(11) DEFAULT NULL COMMENT '税前工资',
  `work_description` text COMMENT '工作描述',
  `dateline` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='个人简历';

-- ----------------------------
--  Table structure for `ims_thinkidea_rencai_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_thinkidea_rencai_reply`;
CREATE TABLE `ims_thinkidea_rencai_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acid` int(11) DEFAULT NULL,
  `rid` int(11) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `avatar` varchar(250) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `dateline` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_thinkidea_rencai_share`
-- ----------------------------
DROP TABLE IF EXISTS `ims_thinkidea_rencai_share`;
CREATE TABLE `ims_thinkidea_rencai_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `default_title` varchar(250) NOT NULL DEFAULT '0',
  `default_desc` varchar(250) NOT NULL DEFAULT '0',
  `default_pic` varchar(250) NOT NULL DEFAULT '0',
  `index_title` varchar(250) NOT NULL COMMENT '首页title',
  `index_desc` varchar(250) NOT NULL COMMENT '首页描述',
  `index_pic` varchar(250) NOT NULL,
  `zhao_title` varchar(250) NOT NULL COMMENT '招聘列表页title',
  `zhao_desc` varchar(250) NOT NULL COMMENT '招聘列表页描述',
  `zhao_pic` varchar(250) NOT NULL,
  `qiu_title` varchar(250) NOT NULL COMMENT '求职列表页title',
  `qiu_desc` varchar(250) NOT NULL COMMENT '求职列表页描述',
  `qiu_pic` varchar(250) NOT NULL,
  `uniacid` int(11) NOT NULL DEFAULT '0' COMMENT '统一公众号',
  `mobile_title` varchar(255) DEFAULT NULL COMMENT '手机端title',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='设置分享';

-- ----------------------------
--  Table structure for `ims_thinkidea_secondmarket_category`
-- ----------------------------
DROP TABLE IF EXISTS `ims_thinkidea_secondmarket_category`;
CREATE TABLE `ims_thinkidea_secondmarket_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentid` int(11) NOT NULL DEFAULT '0' COMMENT '父栏目id',
  `weid` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_thinkidea_secondmarket_goods`
-- ----------------------------
DROP TABLE IF EXISTS `ims_thinkidea_secondmarket_goods`;
CREATE TABLE `ims_thinkidea_secondmarket_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `title` varchar(20) NOT NULL,
  `rolex` varchar(30) NOT NULL,
  `pcate` int(10) unsigned NOT NULL DEFAULT '0',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0',
  `price` int(10) NOT NULL,
  `realname` varchar(18) NOT NULL,
  `sex` int(1) NOT NULL,
  `mobile` varchar(12) NOT NULL,
  `description` longtext NOT NULL,
  `thumb1` varchar(200) DEFAULT NULL,
  `thumb2` varchar(200) DEFAULT NULL,
  `thumb3` varchar(200) DEFAULT NULL,
  `createtime` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_thinkidea_secondmarket_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_thinkidea_secondmarket_reply`;
CREATE TABLE `ims_thinkidea_secondmarket_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `acid` int(11) NOT NULL,
  `title` text NOT NULL,
  `avatar` text NOT NULL,
  `description` text NOT NULL,
  `dateline` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_uni_account`
-- ----------------------------
DROP TABLE IF EXISTS `ims_uni_account`;
CREATE TABLE `ims_uni_account` (
  `uniacid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `groupid` int(10) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  `default_acid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uniacid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_uni_account_group`
-- ----------------------------
DROP TABLE IF EXISTS `ims_uni_account_group`;
CREATE TABLE `ims_uni_account_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `groupid` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_uni_account_modules`
-- ----------------------------
DROP TABLE IF EXISTS `ims_uni_account_modules`;
CREATE TABLE `ims_uni_account_modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `module` varchar(50) NOT NULL DEFAULT '',
  `enabled` tinyint(1) unsigned NOT NULL,
  `settings` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_module` (`module`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_uni_account_users`
-- ----------------------------
DROP TABLE IF EXISTS `ims_uni_account_users`;
CREATE TABLE `ims_uni_account_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `role` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_memberid` (`uid`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_uni_group`
-- ----------------------------
DROP TABLE IF EXISTS `ims_uni_group`;
CREATE TABLE `ims_uni_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `modules` varchar(5000) NOT NULL DEFAULT '',
  `templates` varchar(5000) NOT NULL DEFAULT '',
  `uniacid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_uni_settings`
-- ----------------------------
DROP TABLE IF EXISTS `ims_uni_settings`;
CREATE TABLE `ims_uni_settings` (
  `uniacid` int(10) unsigned NOT NULL,
  `passport` varchar(200) NOT NULL DEFAULT '',
  `oauth` varchar(100) NOT NULL DEFAULT '',
  `uc` varchar(500) NOT NULL,
  `notify` varchar(2000) NOT NULL DEFAULT '',
  `creditnames` varchar(500) NOT NULL DEFAULT '',
  `creditbehaviors` varchar(500) NOT NULL DEFAULT '',
  `welcome` varchar(60) NOT NULL DEFAULT '',
  `default` varchar(60) NOT NULL DEFAULT '',
  `default_message` varchar(1000) NOT NULL DEFAULT '',
  `shortcuts` varchar(5000) NOT NULL DEFAULT '',
  `payment` varchar(2000) NOT NULL DEFAULT '',
  `stat` varchar(300) NOT NULL,
  `menuset` text NOT NULL,
  `default_site` int(10) unsigned DEFAULT '0',
  `sync` varchar(100) NOT NULL,
  `jsauth_acid` int(10) unsigned NOT NULL,
  `recharge` varchar(500) NOT NULL,
  `tplnotice` varchar(1000) NOT NULL,
  `grouplevel` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_uni_verifycode`
-- ----------------------------
DROP TABLE IF EXISTS `ims_uni_verifycode`;
CREATE TABLE `ims_uni_verifycode` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `receiver` varchar(50) NOT NULL,
  `verifycode` varchar(6) NOT NULL,
  `total` tinyint(3) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_userapi_cache`
-- ----------------------------
DROP TABLE IF EXISTS `ims_userapi_cache`;
CREATE TABLE `ims_userapi_cache` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(32) NOT NULL,
  `content` text NOT NULL,
  `lastupdate` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_userapi_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_userapi_reply`;
CREATE TABLE `ims_userapi_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `description` varchar(300) NOT NULL DEFAULT '',
  `apiurl` varchar(300) NOT NULL DEFAULT '',
  `token` varchar(32) NOT NULL DEFAULT '',
  `default_text` varchar(100) NOT NULL DEFAULT '',
  `cachetime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_users`
-- ----------------------------
DROP TABLE IF EXISTS `ims_users`;
CREATE TABLE `ims_users` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `groupid` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL,
  `password` varchar(200) NOT NULL,
  `salt` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `joindate` int(10) unsigned NOT NULL DEFAULT '0',
  `joinip` varchar(15) NOT NULL DEFAULT '',
  `lastvisit` int(10) unsigned NOT NULL DEFAULT '0',
  `lastip` varchar(15) NOT NULL DEFAULT '',
  `remark` varchar(500) NOT NULL DEFAULT '',
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_users_group`
-- ----------------------------
DROP TABLE IF EXISTS `ims_users_group`;
CREATE TABLE `ims_users_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `package` varchar(5000) NOT NULL DEFAULT '',
  `maxaccount` int(10) unsigned NOT NULL DEFAULT '0',
  `maxsubaccount` int(10) unsigned NOT NULL,
  `timelimit` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_users_invitation`
-- ----------------------------
DROP TABLE IF EXISTS `ims_users_invitation`;
CREATE TABLE `ims_users_invitation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(64) NOT NULL,
  `fromuid` int(10) unsigned NOT NULL,
  `inviteuid` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_users_permission`
-- ----------------------------
DROP TABLE IF EXISTS `ims_users_permission`;
CREATE TABLE `ims_users_permission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `url` varchar(255) NOT NULL,
  `type` varchar(30) NOT NULL,
  `permission` varchar(10000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_users_profile`
-- ----------------------------
DROP TABLE IF EXISTS `ims_users_profile`;
CREATE TABLE `ims_users_profile` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `realname` varchar(10) NOT NULL DEFAULT '',
  `nickname` varchar(20) NOT NULL DEFAULT '',
  `avatar` varchar(100) NOT NULL DEFAULT '',
  `qq` varchar(15) NOT NULL DEFAULT '',
  `mobile` varchar(11) NOT NULL DEFAULT '',
  `fakeid` varchar(30) NOT NULL,
  `vip` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `gender` tinyint(1) NOT NULL DEFAULT '0',
  `birthyear` smallint(6) unsigned NOT NULL DEFAULT '0',
  `birthmonth` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `birthday` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `constellation` varchar(10) NOT NULL DEFAULT '',
  `zodiac` varchar(5) NOT NULL DEFAULT '',
  `telephone` varchar(15) NOT NULL DEFAULT '',
  `idcard` varchar(30) NOT NULL DEFAULT '',
  `studentid` varchar(50) NOT NULL DEFAULT '',
  `grade` varchar(10) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `zipcode` varchar(10) NOT NULL DEFAULT '',
  `nationality` varchar(30) NOT NULL DEFAULT '',
  `resideprovince` varchar(30) NOT NULL DEFAULT '',
  `residecity` varchar(30) NOT NULL DEFAULT '',
  `residedist` varchar(30) NOT NULL DEFAULT '',
  `graduateschool` varchar(50) NOT NULL DEFAULT '',
  `company` varchar(50) NOT NULL DEFAULT '',
  `education` varchar(10) NOT NULL DEFAULT '',
  `occupation` varchar(30) NOT NULL DEFAULT '',
  `position` varchar(30) NOT NULL DEFAULT '',
  `revenue` varchar(10) NOT NULL DEFAULT '',
  `affectivestatus` varchar(30) NOT NULL DEFAULT '',
  `lookingfor` varchar(255) NOT NULL DEFAULT '',
  `bloodtype` varchar(5) NOT NULL DEFAULT '',
  `height` varchar(5) NOT NULL DEFAULT '',
  `weight` varchar(5) NOT NULL DEFAULT '',
  `alipay` varchar(30) NOT NULL DEFAULT '',
  `msn` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `taobao` varchar(30) NOT NULL DEFAULT '',
  `site` varchar(30) NOT NULL DEFAULT '',
  `bio` text NOT NULL,
  `interest` text NOT NULL,
  `workerid` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_video_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_video_reply`;
CREATE TABLE `ims_video_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `mediaid` varchar(255) NOT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_vktv_classify`
-- ----------------------------
DROP TABLE IF EXISTS `ims_vktv_classify`;
CREATE TABLE `ims_vktv_classify` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `ser_window` varchar(30) NOT NULL,
  `department_id` int(11) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `ser_picurl` varchar(200) NOT NULL,
  `ser_info` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_vktv_comments`
-- ----------------------------
DROP TABLE IF EXISTS `ims_vktv_comments`;
CREATE TABLE `ims_vktv_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `title` varchar(60) NOT NULL,
  `lead_name` varchar(30) NOT NULL,
  `lead_position` varchar(30) NOT NULL,
  `lead_picurl` varchar(100) NOT NULL,
  `info` varchar(300) NOT NULL,
  `department_id` int(11) NOT NULL,
  `comm_content` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_vktv_poster`
-- ----------------------------
DROP TABLE IF EXISTS `ims_vktv_poster`;
CREATE TABLE `ims_vktv_poster` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `thurl` varchar(200) NOT NULL COMMENT '相册url',
  `title` varchar(30) NOT NULL,
  `thumb` varchar(2000) NOT NULL,
  `department_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='宣传海报';

-- ----------------------------
--  Table structure for `ims_vktv_project`
-- ----------------------------
DROP TABLE IF EXISTS `ims_vktv_project`;
CREATE TABLE `ims_vktv_project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `ser_name` varchar(30) NOT NULL,
  `classify_id` int(11) NOT NULL,
  `classify_picurl` varchar(100) NOT NULL,
  `kbox` varchar(50) NOT NULL,
  `price` int(11) NOT NULL,
  `project_info` varchar(300) NOT NULL,
  `ishow` int(1) NOT NULL,
  `total` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_vktv_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_vktv_reply`;
CREATE TABLE `ims_vktv_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `weid` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `department` varchar(30) NOT NULL,
  `picurl` varchar(500) NOT NULL,
  `info_picurl` varchar(500) NOT NULL,
  `order_picurl` varchar(500) NOT NULL,
  `order_info` varchar(500) NOT NULL,
  `cosmtment_phone` varchar(12) NOT NULL,
  `address` varchar(100) NOT NULL,
  `cosmtment_info` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_vktv_reservation`
-- ----------------------------
DROP TABLE IF EXISTS `ims_vktv_reservation`;
CREATE TABLE `ims_vktv_reservation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `openid` varchar(100) NOT NULL,
  `truename` varchar(20) NOT NULL,
  `mobile` varchar(12) NOT NULL,
  `ser_name` varchar(30) NOT NULL,
  `info` varchar(100) NOT NULL,
  `createtime` int(11) NOT NULL,
  `remate` int(1) NOT NULL COMMENT '订单状态',
  `reid` int(11) NOT NULL COMMENT '服务类型ID',
  `kfinfo` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_voice_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_voice_reply`;
CREATE TABLE `ims_voice_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `mediaid` varchar(255) NOT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_vote_fans`
-- ----------------------------
DROP TABLE IF EXISTS `ims_vote_fans`;
CREATE TABLE `ims_vote_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_user` varchar(50) DEFAULT '',
  `rid` int(11) DEFAULT '0',
  `votes` varchar(255) DEFAULT '',
  `votetime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_votetime` (`votetime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_vote_option`
-- ----------------------------
DROP TABLE IF EXISTS `ims_vote_option`;
CREATE TABLE `ims_vote_option` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `thumb` varchar(60) DEFAULT '',
  `content` text,
  `vote_num` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_vote_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_vote_reply`;
CREATE TABLE `ims_vote_reply` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rid` int(10) DEFAULT '0',
  `weid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `thumb` varchar(60) DEFAULT '',
  `votetype` tinyint(4) DEFAULT '0',
  `votetotal` int(10) DEFAULT '0',
  `status` int(10) DEFAULT '0',
  `votenum` int(10) DEFAULT '0',
  `votetimes` int(10) DEFAULT '0',
  `votelimit` int(10) DEFAULT '0',
  `viewnum` int(10) DEFAULT '0',
  `starttime` int(10) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  `isimg` int(10) DEFAULT '0',
  `isshow` int(10) DEFAULT '0',
  `share_title` varchar(200) DEFAULT '',
  `share_desc` varchar(300) DEFAULT '',
  `share_url` varchar(100) DEFAULT '',
  `share_txt` varchar(500) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_water_query2_info`
-- ----------------------------
DROP TABLE IF EXISTS `ims_water_query2_info`;
CREATE TABLE `ims_water_query2_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `keyword` varchar(50) NOT NULL,
  `info` varchar(500) NOT NULL,
  `infophoto` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_water_query_info`
-- ----------------------------
DROP TABLE IF EXISTS `ims_water_query_info`;
CREATE TABLE `ims_water_query_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `keyword` varchar(50) NOT NULL,
  `info` varchar(500) NOT NULL,
  `infophoto` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_water_super_address`
-- ----------------------------
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_water_super_areas`
-- ----------------------------
DROP TABLE IF EXISTS `ims_water_super_areas`;
CREATE TABLE `ims_water_super_areas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `cityid` int(11) NOT NULL,
  `areaname` varchar(50) NOT NULL COMMENT '名称',
  `areaunicode` varchar(200) NOT NULL COMMENT '名称的Unicode码',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_water_super_cardnumber`
-- ----------------------------
DROP TABLE IF EXISTS `ims_water_super_cardnumber`;
CREATE TABLE `ims_water_super_cardnumber` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `themecode` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_water_super_citys`
-- ----------------------------
DROP TABLE IF EXISTS `ims_water_super_citys`;
CREATE TABLE `ims_water_super_citys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `cityname` varchar(20) NOT NULL,
  `cityinfo` varchar(500) NOT NULL,
  `cityphoto` varchar(200) NOT NULL,
  `cityunicode` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_water_super_coupon`
-- ----------------------------
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

-- ----------------------------
--  Table structure for `ims_water_super_coupon_record`
-- ----------------------------
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

-- ----------------------------
--  Table structure for `ims_water_super_employees`
-- ----------------------------
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_water_super_express`
-- ----------------------------
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_water_super_goods`
-- ----------------------------
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_water_super_members`
-- ----------------------------
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_water_super_orders`
-- ----------------------------
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_water_super_rnumber`
-- ----------------------------
DROP TABLE IF EXISTS `ims_water_super_rnumber`;
CREATE TABLE `ims_water_super_rnumber` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_water_super_shop`
-- ----------------------------
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wdl_hchighguess_images`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wdl_hchighguess_images`;
CREATE TABLE `ims_wdl_hchighguess_images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `wid` int(10) unsigned NOT NULL COMMENT '词条ID',
  `rid` int(10) unsigned NOT NULL,
  `mid` int(10) unsigned NOT NULL COMMENT '会员ID',
  `image` varchar(255) DEFAULT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wdl_hchighguess_member`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wdl_hchighguess_member`;
CREATE TABLE `ims_wdl_hchighguess_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) DEFAULT NULL,
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号码',
  `avatar` varchar(255) DEFAULT NULL,
  `realname` varchar(50) NOT NULL DEFAULT '',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wdl_hchighguess_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wdl_hchighguess_reply`;
CREATE TABLE `ims_wdl_hchighguess_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `cover` varchar(255) DEFAULT NULL,
  `description` text,
  `sharetitle` varchar(255) DEFAULT NULL,
  `sharecover` varchar(255) DEFAULT NULL,
  `sharedescription` text,
  `gzurl` varchar(255) DEFAULT NULL,
  `level` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wdl_hchighguess_selectlog`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wdl_hchighguess_selectlog`;
CREATE TABLE `ims_wdl_hchighguess_selectlog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `wid` int(10) unsigned NOT NULL,
  `imgid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) DEFAULT NULL,
  `realname` varchar(50) NOT NULL DEFAULT '',
  `image` varchar(255) DEFAULT NULL,
  `word` varchar(20) DEFAULT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wdl_hchighguess_words`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wdl_hchighguess_words`;
CREATE TABLE `ims_wdl_hchighguess_words` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `word` varchar(20) DEFAULT NULL,
  `words` varchar(100) DEFAULT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `isopen` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wdl_quickspread_active_channel`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wdl_quickspread_active_channel`;
CREATE TABLE `ims_wdl_quickspread_active_channel` (
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(100) NOT NULL,
  `channel` int(10) NOT NULL,
  PRIMARY KEY (`weid`,`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wdl_quickspread_blacklist`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wdl_quickspread_blacklist`;
CREATE TABLE `ims_wdl_quickspread_blacklist` (
  `from_user` varchar(50) NOT NULL DEFAULT '',
  `weid` int(10) unsigned NOT NULL,
  `access_time` int(10) unsigned NOT NULL,
  `hit` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`from_user`,`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wdl_quickspread_channel`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wdl_quickspread_channel`;
CREATE TABLE `ims_wdl_quickspread_channel` (
  `channel` int(10) NOT NULL AUTO_INCREMENT,
  `active` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(1024) NOT NULL,
  `thumb` varchar(1024) NOT NULL,
  `bg` varchar(1024) NOT NULL,
  `desc` varchar(1024) NOT NULL,
  `url` varchar(1024) NOT NULL,
  `bgparam` varchar(10240) NOT NULL,
  `click_credit` int(10) NOT NULL COMMENT '未关注的用户关注,送分享者积分',
  `sub_click_credit` int(10) NOT NULL COMMENT '未关注的用户关注,送上线积分',
  `newbie_credit` int(10) NOT NULL COMMENT '通过本渠道关注微信号，送新用户大礼包积分',
  `weid` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`channel`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wdl_quickspread_credit`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wdl_quickspread_credit`;
CREATE TABLE `ims_wdl_quickspread_credit` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(100) NOT NULL,
  `type` varchar(20) NOT NULL,
  `credit` int(10) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wdl_quickspread_fans`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wdl_quickspread_fans`;
CREATE TABLE `ims_wdl_quickspread_fans` (
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(100) NOT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`weid`,`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wdl_quickspread_follow`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wdl_quickspread_follow`;
CREATE TABLE `ims_wdl_quickspread_follow` (
  `weid` int(10) unsigned NOT NULL,
  `leader` varchar(100) NOT NULL,
  `follower` varchar(100) NOT NULL,
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '渠道唯一标示符',
  `credit` int(10) NOT NULL DEFAULT '0',
  `createtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`weid`,`leader`,`follower`,`channel`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wdl_quickspread_iptable`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wdl_quickspread_iptable`;
CREATE TABLE `ims_wdl_quickspread_iptable` (
  `weid` int(10) unsigned NOT NULL,
  `ip` varchar(64) NOT NULL,
  `credit` int(10) unsigned NOT NULL,
  `track_id` varchar(50) NOT NULL DEFAULT '',
  `track_type` varchar(20) NOT NULL DEFAULT '',
  `from_user` int(10) unsigned NOT NULL,
  `spreadid` int(10) unsigned NOT NULL,
  `title` varchar(128) NOT NULL,
  `access_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ip`,`weid`,`spreadid`,`access_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wdl_quickspread_qr`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wdl_quickspread_qr`;
CREATE TABLE `ims_wdl_quickspread_qr` (
  `weid` int(10) unsigned NOT NULL,
  `scene_id` varchar(50) NOT NULL,
  `qr_url` varchar(1024) NOT NULL,
  `media_id` varchar(1024) NOT NULL,
  `createtime` int(11) NOT NULL,
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '渠道唯一标示符',
  `from_user` varchar(100) NOT NULL,
  PRIMARY KEY (`weid`,`scene_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wdl_quickspread_scene_id`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wdl_quickspread_scene_id`;
CREATE TABLE `ims_wdl_quickspread_scene_id` (
  `weid` int(10) unsigned NOT NULL,
  `scene_id` int(10) NOT NULL,
  PRIMARY KEY (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wdl_quickspread_spread`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wdl_quickspread_spread`;
CREATE TABLE `ims_wdl_quickspread_spread` (
  `spreadid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `status` int(10) unsigned NOT NULL DEFAULT '1',
  `poster_img` varchar(1024) NOT NULL,
  `preview_img` varchar(1024) NOT NULL,
  `background` varchar(1024) NOT NULL,
  `register_button` varchar(640) NOT NULL,
  `fillform_button` varchar(640) NOT NULL,
  `fillform_url` varchar(640) NOT NULL,
  `pos_top` int(10) unsigned NOT NULL,
  `pos_left` int(10) unsigned NOT NULL,
  `timestart` int(10) unsigned NOT NULL,
  `timeend` int(10) unsigned NOT NULL,
  `share_title` varchar(64) NOT NULL,
  `share_award` varchar(64) NOT NULL,
  `timelinetext` varchar(640) NOT NULL,
  `buttonimg` varchar(640) NOT NULL,
  `share_content` mediumtext NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `click_credit` int(10) unsigned NOT NULL DEFAULT '0',
  `share_credit` int(10) unsigned NOT NULL DEFAULT '0',
  `fillform_credit` int(10) unsigned NOT NULL DEFAULT '0',
  `max_credit` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`spreadid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wdl_quickspread_user`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wdl_quickspread_user`;
CREATE TABLE `ims_wdl_quickspread_user` (
  `from_user` varchar(50) NOT NULL DEFAULT '',
  `weid` int(10) unsigned NOT NULL,
  `mobile` varchar(50) NOT NULL DEFAULT '',
  `realname` varchar(50) NOT NULL DEFAULT '',
  `address` varchar(256) NOT NULL DEFAULT '',
  `memo` varchar(1024) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`from_user`,`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wdl_weizanxiu_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wdl_weizanxiu_reply`;
CREATE TABLE `ims_wdl_weizanxiu_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `title` varchar(200) NOT NULL,
  `thumb` varchar(60) NOT NULL,
  `author` varchar(20) NOT NULL,
  `description` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `share_url` varchar(255) NOT NULL DEFAULT '',
  `createtime` int(10) unsigned NOT NULL,
  `share_title` varchar(255) DEFAULT NULL,
  `share_description` varchar(255) DEFAULT NULL,
  `share_thumb` varchar(255) DEFAULT NULL,
  `share_302` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_we7car_album`
-- ----------------------------
DROP TABLE IF EXISTS `ims_we7car_album`;
CREATE TABLE `ims_we7car_album` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `type_id` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `thumb` varchar(100) NOT NULL DEFAULT '',
  `content` varchar(1000) NOT NULL DEFAULT '',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `isview` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ims_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_we7car_album_photo`
-- ----------------------------
DROP TABLE IF EXISTS `ims_we7car_album_photo`;
CREATE TABLE `ims_we7car_album_photo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `albumid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `attachment` varchar(100) NOT NULL DEFAULT '',
  `ispreview` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ims_weid` (`weid`),
  KEY `ims_albumid_order` (`albumid`,`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_we7car_brand`
-- ----------------------------
DROP TABLE IF EXISTS `ims_we7car_brand`;
CREATE TABLE `ims_we7car_brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `listorder` int(11) NOT NULL,
  `title` varchar(25) NOT NULL,
  `officialweb` varchar(100) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `createtime` int(10) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_weid_order` (`weid`,`listorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_we7car_care`
-- ----------------------------
DROP TABLE IF EXISTS `ims_we7car_care`;
CREATE TABLE `ims_we7car_care` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `brand_id` int(10) unsigned NOT NULL,
  `brand_cn` varchar(50) NOT NULL,
  `series_id` int(10) unsigned NOT NULL,
  `series_cn` varchar(50) NOT NULL,
  `type_id` int(10) unsigned NOT NULL,
  `type_cn` varchar(50) NOT NULL,
  `car_note` varchar(50) NOT NULL,
  `car_no` varchar(50) NOT NULL,
  `car_userName` varchar(50) NOT NULL,
  `car_mobile` varchar(15) NOT NULL,
  `car_startTime` int(10) unsigned NOT NULL,
  `car_photo` varchar(100) NOT NULL,
  `car_insurance_lastDate` int(10) unsigned NOT NULL,
  `car_insurance_lastCost` mediumint(10) unsigned NOT NULL,
  `car_care_mileage` int(11) NOT NULL,
  `car_care_lastDate` int(10) unsigned NOT NULL,
  `car_care_lastCost` mediumint(10) unsigned NOT NULL,
  `createtime` int(10) NOT NULL,
  `isshow` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ims_weid` (`weid`),
  KEY `ims_createtime` (`createtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_we7car_message_list`
-- ----------------------------
DROP TABLE IF EXISTS `ims_we7car_message_list`;
CREATE TABLE `ims_we7car_message_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `nickname` varchar(30) DEFAULT NULL,
  `info` varchar(200) DEFAULT NULL,
  `fid` int(11) DEFAULT '0',
  `isshow` tinyint(1) DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `from_user` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ims_weid` (`weid`),
  KEY `ims_fid_time` (`fid`,`create_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_we7car_message_set`
-- ----------------------------
DROP TABLE IF EXISTS `ims_we7car_message_set`;
CREATE TABLE `ims_we7car_message_set` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `thumb` varchar(200) NOT NULL,
  `status` int(1) NOT NULL,
  `isshow` tinyint(1) NOT NULL,
  `create_time` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ims_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_we7car_news`
-- ----------------------------
DROP TABLE IF EXISTS `ims_we7car_news`;
CREATE TABLE `ims_we7car_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `iscommend` tinyint(1) NOT NULL DEFAULT '0',
  `ishot` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `category_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `template` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `thumb` varchar(100) NOT NULL DEFAULT '' COMMENT '缩略图',
  `source` varchar(50) NOT NULL DEFAULT '' COMMENT '来源',
  `author` varchar(50) NOT NULL COMMENT '作者',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ims_category_id` (`category_id`),
  KEY `ims_weid` (`weid`),
  KEY `ims_createtime` (`createtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_we7car_news_category`
-- ----------------------------
DROP TABLE IF EXISTS `ims_we7car_news_category`;
CREATE TABLE `ims_we7car_news_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `title` varchar(50) NOT NULL COMMENT '分类名称',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `description` varchar(100) NOT NULL DEFAULT '' COMMENT '分类描述',
  `thumb` varchar(60) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ims_weid_title` (`weid`,`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_we7car_order_data`
-- ----------------------------
DROP TABLE IF EXISTS `ims_we7car_order_data`;
CREATE TABLE `ims_we7car_order_data` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL,
  `srid` int(11) NOT NULL,
  `sfid` int(11) NOT NULL,
  `data` varchar(500) NOT NULL,
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ims_sid` (`sid`),
  KEY `ims_srid` (`srid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_we7car_order_fields`
-- ----------------------------
DROP TABLE IF EXISTS `ims_we7car_order_fields`;
CREATE TABLE `ims_we7car_order_fields` (
  `fid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(11) unsigned NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `value` varchar(300) NOT NULL DEFAULT '',
  PRIMARY KEY (`fid`),
  KEY `ims_sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_we7car_order_list`
-- ----------------------------
DROP TABLE IF EXISTS `ims_we7car_order_list`;
CREATE TABLE `ims_we7car_order_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(10) unsigned NOT NULL,
  `yytype` tinyint(11) NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `brand` int(10) unsigned NOT NULL,
  `brand_cn` varchar(15) NOT NULL,
  `serie` int(10) unsigned NOT NULL,
  `serie_cn` varchar(15) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `type_cn` varchar(15) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  `createtime` int(10) NOT NULL,
  `note` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ims_sid` (`sid`),
  KEY `ims_createtime` (`createtime`),
  KEY `ims_dateline` (`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_we7car_order_set`
-- ----------------------------
DROP TABLE IF EXISTS `ims_we7car_order_set`;
CREATE TABLE `ims_we7car_order_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL,
  `yytype` tinyint(2) NOT NULL,
  `pertotal` tinyint(3) unsigned NOT NULL,
  `description` varchar(500) NOT NULL,
  `start_time` int(10) unsigned NOT NULL,
  `end_time` int(10) unsigned NOT NULL,
  `address` varchar(200) NOT NULL,
  `mobile` varchar(30) NOT NULL,
  `location_x` float NOT NULL,
  `location_y` float NOT NULL,
  `topbanner` varchar(150) DEFAULT NULL,
  `isshow` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `note` varchar(50) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ims_weid` (`weid`),
  KEY `ims_createtime` (`createtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_we7car_series`
-- ----------------------------
DROP TABLE IF EXISTS `ims_we7car_series`;
CREATE TABLE `ims_we7car_series` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `bid` int(11) NOT NULL,
  `listorder` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `subtitle` varchar(20) NOT NULL,
  `thumb` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `createtime` int(10) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ims_weid_order` (`weid`,`listorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_we7car_services`
-- ----------------------------
DROP TABLE IF EXISTS `ims_we7car_services`;
CREATE TABLE `ims_we7car_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `listorder` int(11) NOT NULL,
  `kefuname` varchar(50) NOT NULL,
  `headthumb` varchar(200) NOT NULL,
  `kefutel` varchar(20) NOT NULL,
  `pre_sales` tinyint(2) NOT NULL,
  `aft_sales` tinyint(2) NOT NULL,
  `description` text NOT NULL,
  `createtime` int(10) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ims_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_we7car_set`
-- ----------------------------
DROP TABLE IF EXISTS `ims_we7car_set`;
CREATE TABLE `ims_we7car_set` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `address` varchar(60) NOT NULL,
  `opentime` varchar(60) NOT NULL,
  `pre_consult` varchar(60) NOT NULL,
  `aft_consult` varchar(60) NOT NULL,
  `thumbArr` varchar(500) NOT NULL,
  `weicar_logo` varchar(200) NOT NULL,
  `shop_logo` varchar(200) NOT NULL,
  `guanhuai_thumb` varchar(200) NOT NULL,
  `typethumb` varchar(70) NOT NULL,
  `yuyue1thumb` varchar(70) NOT NULL,
  `yuyue2thumb` varchar(70) NOT NULL,
  `kefuthumb` varchar(70) NOT NULL,
  `messagethumb` varchar(70) NOT NULL,
  `carethumb` varchar(70) NOT NULL,
  `status` int(1) NOT NULL,
  `isshow` tinyint(1) NOT NULL,
  `tools` varchar(50) NOT NULL,
  `create_time` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ims_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_we7car_type`
-- ----------------------------
DROP TABLE IF EXISTS `ims_we7car_type`;
CREATE TABLE `ims_we7car_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `listorder` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `weid` int(11) NOT NULL,
  `bid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `pyear` varchar(10) NOT NULL,
  `price1` varchar(50) NOT NULL,
  `price2` varchar(50) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `thumbArr` varchar(500) NOT NULL,
  `description` varchar(512) NOT NULL DEFAULT '' COMMENT '品牌描述',
  `output` varchar(10) NOT NULL,
  `gearnum` varchar(10) NOT NULL,
  `gear_box` varchar(30) NOT NULL,
  `xiangceid` int(11) NOT NULL,
  `createtime` int(10) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ims_weid_order` (`weid`,`listorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wechat_attachment`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wechat_attachment`;
CREATE TABLE `ims_wechat_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `filename` varchar(255) NOT NULL,
  `attachment` varchar(255) NOT NULL,
  `media_id` varchar(255) NOT NULL,
  `width` int(10) unsigned NOT NULL,
  `height` int(10) unsigned NOT NULL,
  `type` varchar(15) NOT NULL,
  `model` varchar(25) NOT NULL,
  `tag` varchar(5000) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `media_id` (`media_id`),
  KEY `acid` (`acid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wechat_news`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wechat_news`;
CREATE TABLE `ims_wechat_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned DEFAULT NULL,
  `attach_id` int(10) unsigned NOT NULL,
  `thumb_media_id` varchar(60) NOT NULL,
  `title` varchar(50) NOT NULL,
  `author` varchar(30) NOT NULL,
  `digest` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `content_source_url` varchar(200) NOT NULL,
  `show_cover_pic` tinyint(3) unsigned NOT NULL,
  `url` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `attach_id` (`attach_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weihaom_wb_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weihaom_wb_reply`;
CREATE TABLE `ims_weihaom_wb_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `cover` varchar(255) DEFAULT NULL,
  `description` text,
  `title1` varchar(255) DEFAULT NULL,
  `description1` text,
  `fimg` varchar(255) DEFAULT NULL,
  `bimg` varchar(255) DEFAULT NULL,
  `bgmusic` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weihaom_wb_user`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weihaom_wb_user`;
CREATE TABLE `ims_weihaom_wb_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) DEFAULT NULL,
  `realname` varchar(50) DEFAULT NULL,
  `score` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weilvyou_dianping`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weilvyou_dianping`;
CREATE TABLE `ims_weilvyou_dianping` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `xingming` varchar(30) NOT NULL DEFAULT '',
  `zhiwei` varchar(50) NOT NULL DEFAULT '',
  `jianjie1` varchar(255) NOT NULL DEFAULT '',
  `jianjie2` varchar(255) NOT NULL DEFAULT '',
  `sort` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `img` varchar(255) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weilvyou_haibao`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weilvyou_haibao`;
CREATE TABLE `ims_weilvyou_haibao` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `img1` varchar(255) NOT NULL DEFAULT '',
  `img2` varchar(255) NOT NULL DEFAULT '',
  `img3` varchar(255) NOT NULL DEFAULT '',
  `img4` varchar(255) NOT NULL DEFAULT '',
  `img5` varchar(255) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weilvyou_jianjie`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weilvyou_jianjie`;
CREATE TABLE `ims_weilvyou_jianjie` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `top_img` varchar(255) NOT NULL DEFAULT '' COMMENT '头部图片',
  `location_x` float unsigned NOT NULL,
  `location_y` float unsigned NOT NULL,
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '详细地址',
  `jianjie_1` text NOT NULL COMMENT '服务理念简介',
  `jianjie_2` text NOT NULL COMMENT '旅游区简介',
  `jianjie_3` text NOT NULL COMMENT '服务配套',
  `mobile` varchar(30) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weilvyou_jingdian`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weilvyou_jingdian`;
CREATE TABLE `ims_weilvyou_jingdian` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `jid` int(10) unsigned NOT NULL DEFAULT '0',
  `weid` int(10) unsigned DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `jianjie1` varchar(255) DEFAULT NULL,
  `jianjie2` varchar(255) DEFAULT NULL,
  `jianjie3` varchar(255) DEFAULT NULL,
  `sort` mediumint(8) unsigned DEFAULT '0',
  `img` varchar(255) DEFAULT NULL,
  `dateline` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weilvyou_jingqu`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weilvyou_jingqu`;
CREATE TABLE `ims_weilvyou_jingqu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0',
  `sort` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `jianjie` varchar(255) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weilvyou_xiangce`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weilvyou_xiangce`;
CREATE TABLE `ims_weilvyou_xiangce` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0',
  `sort` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `jianjie` varchar(255) NOT NULL DEFAULT '',
  `img` varchar(255) DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weilvyou_yinxiang`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weilvyou_yinxiang`;
CREATE TABLE `ims_weilvyou_yinxiang` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `jid` int(10) unsigned NOT NULL DEFAULT '0',
  `weid` int(10) unsigned NOT NULL DEFAULT '0',
  `from_user` varchar(60) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `sort` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `num` int(10) unsigned NOT NULL DEFAULT '1',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weishare`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weishare`;
CREATE TABLE `ims_weishare` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) unsigned DEFAULT NULL,
  `title` varchar(100) NOT NULL COMMENT '活动标题',
  `thumb` varchar(100) NOT NULL COMMENT '活动图片',
  `description` varchar(100) NOT NULL COMMENT '活动描述',
  `image` varchar(100) NOT NULL COMMENT '背景图片',
  `max` int(11) NOT NULL COMMENT '得分极限',
  `start` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '分值',
  `step` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '助力积分',
  `steprandom` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '助力随机积分',
  `steptype` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '助力随机积分方式',
  `rule` text NOT NULL COMMENT '规则',
  `url` varchar(250) NOT NULL COMMENT '引导关注素材',
  `count` int(11) NOT NULL COMMENT '领卡数量限制',
  `background` varchar(100) NOT NULL COMMENT '背景颜色',
  `tip` varchar(100) NOT NULL COMMENT '提示语',
  `unit` varchar(100) NOT NULL COMMENT '单位',
  `cardname` varchar(100) NOT NULL COMMENT '卡片名称',
  `helplimit` int(11) NOT NULL COMMENT '每天助力限制次数',
  `totallimit` int(11) NOT NULL COMMENT '总得助力次数',
  `limittype` int(1) NOT NULL COMMENT '限制类型',
  `createtime` int(10) unsigned NOT NULL COMMENT '日期',
  `endtime` int(11) unsigned NOT NULL COMMENT '日期',
  `shareIcon` varchar(200) NOT NULL COMMENT '分享图标',
  `shareTitle` varchar(200) NOT NULL,
  `shareContent` varchar(200) NOT NULL,
  `copyright` varchar(100) NOT NULL COMMENT '版权',
  `showu` varchar(1) NOT NULL DEFAULT '0',
  `sortcount` varchar(100) NOT NULL DEFAULT '10',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weishare_firend`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weishare_firend`;
CREATE TABLE `ims_weishare_firend` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '分享用户的id',
  `sid` int(10) NOT NULL DEFAULT '0',
  `openid` varchar(50) NOT NULL COMMENT '用户唯一身份ID',
  `createtime` int(10) unsigned NOT NULL COMMENT '日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weishare_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weishare_reply`;
CREATE TABLE `ims_weishare_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `sid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `new_title` varchar(100) NOT NULL COMMENT '图文标题',
  `new_pic` varchar(100) NOT NULL COMMENT '图文图片',
  `new_desc` varchar(100) NOT NULL COMMENT '图文描述',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weishare_setting`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weishare_setting`;
CREATE TABLE `ims_weishare_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) unsigned DEFAULT NULL,
  `appid` varchar(200) NOT NULL COMMENT 'appid',
  `secret` varchar(200) NOT NULL COMMENT 'secret',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weishare_user`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weishare_user`;
CREATE TABLE `ims_weishare_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(10) NOT NULL DEFAULT '0',
  `from_user` varchar(50) NOT NULL COMMENT '用户唯一身份ID',
  `tel` varchar(50) NOT NULL,
  `income` float(10,2) unsigned NOT NULL DEFAULT '0.00',
  `createtime` int(10) unsigned NOT NULL COMMENT '日期',
  `helpcount` int(11) DEFAULT '0' COMMENT '助力次数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_audio_music`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_audio_music`;
CREATE TABLE `ims_weisrc_audio_music` (
  `mid` mediumint(8) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `url` varchar(500) NOT NULL COMMENT '歌曲链接',
  `title` char(255) NOT NULL COMMENT '歌曲名称',
  `cover` varchar(500) NOT NULL COMMENT '唱片封面',
  `singer` char(255) NOT NULL COMMENT '歌手',
  `intro` char(255) NOT NULL COMMENT '解说',
  `collect` int(11) DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `displayorder` int(11) DEFAULT '0',
  `dateline` int(11) DEFAULT '0',
  PRIMARY KEY (`mid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_audio_music_user`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_audio_music_user`;
CREATE TABLE `ims_weisrc_audio_music_user` (
  `did` mediumint(8) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `openid` char(255) NOT NULL,
  `mid` mediumint(8) NOT NULL,
  `title` char(255) NOT NULL,
  `cover` char(255) NOT NULL,
  `singer` char(255) NOT NULL,
  `intro` char(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_audio_setting`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_audio_setting`;
CREATE TABLE `ims_weisrc_audio_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) DEFAULT '' COMMENT '版权名称',
  `bg` varchar(500) DEFAULT '' COMMENT '背景图',
  `bg_rand` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '随机背景',
  `bg_setting` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '随机背景',
  `bg_url` varchar(500) DEFAULT '' COMMENT '自定义背景图',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_businesscenter_category`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_businesscenter_category`;
CREATE TABLE `ims_weisrc_businesscenter_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `cityid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '城市id',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `logo` varchar(500) DEFAULT '' COMMENT '商家logo',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `isfirst` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '首页推荐',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_businesscenter_city`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_businesscenter_city`;
CREATE TABLE `ims_weisrc_businesscenter_city` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '城市名称',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_businesscenter_feedback`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_businesscenter_feedback`;
CREATE TABLE `ims_weisrc_businesscenter_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL COMMENT '公众号ID',
  `storeid` int(11) NOT NULL COMMENT '商家ID',
  `parentid` int(11) DEFAULT '0' COMMENT '父级ID',
  `from_user` varchar(100) DEFAULT NULL,
  `nickname` varchar(30) DEFAULT NULL,
  `content` varchar(600) DEFAULT NULL,
  `top` tinyint(1) NOT NULL DEFAULT '0' COMMENT '置顶',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `dateline` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_businesscenter_news`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_businesscenter_news`;
CREATE TABLE `ims_weisrc_businesscenter_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商家id',
  `title` varchar(200) NOT NULL DEFAULT '',
  `thumb` varchar(500) NOT NULL DEFAULT '',
  `summary` varchar(1000) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `address` varchar(200) NOT NULL DEFAULT '',
  `start_time` int(10) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(10) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `url` varchar(200) NOT NULL DEFAULT '',
  `isfirst` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否在首页显示',
  `top` tinyint(1) NOT NULL DEFAULT '0' COMMENT '置顶',
  `mode` tinyint(1) NOT NULL DEFAULT '0' COMMENT '加入方式 0:后台 1:申请',
  `checked` tinyint(1) NOT NULL DEFAULT '1' COMMENT '审核',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_businesscenter_setting`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_businesscenter_setting`;
CREATE TABLE `ims_weisrc_businesscenter_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL COMMENT '所属帐号',
  `title` varchar(100) NOT NULL DEFAULT '',
  `bg` varchar(500) NOT NULL DEFAULT '',
  `announcement` text NOT NULL COMMENT '公告',
  `address` varchar(200) NOT NULL DEFAULT '' COMMENT '地址',
  `tel` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `place` varchar(200) NOT NULL DEFAULT '',
  `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度',
  `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度',
  `location_p` varchar(100) NOT NULL DEFAULT '' COMMENT '省',
  `location_c` varchar(100) NOT NULL DEFAULT '' COMMENT '市',
  `location_a` varchar(100) NOT NULL DEFAULT '' COMMENT '区',
  `pagesize` int(10) unsigned NOT NULL DEFAULT '5' COMMENT '每页显示数据量',
  `topcolor` varchar(20) NOT NULL DEFAULT '' COMMENT '顶部字体颜色',
  `topbgcolor` varchar(20) NOT NULL DEFAULT '' COMMENT '顶部字体颜色',
  `announcebordercolor` varchar(20) NOT NULL DEFAULT '' COMMENT '公告边框颜色',
  `announcebgcolor` varchar(20) NOT NULL DEFAULT '' COMMENT '公告背景颜色',
  `announcecolor` varchar(20) NOT NULL DEFAULT '' COMMENT '公告字体颜色',
  `storestitlecolor` varchar(20) NOT NULL DEFAULT '' COMMENT '商家名称颜色',
  `storesstatuscolor` varchar(20) NOT NULL DEFAULT '' COMMENT '商家状态颜色',
  `showcity` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示城市选择',
  `settled` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启入驻',
  `feedback_show_enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `feedback_check_enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '留言是否需要审核',
  `scroll_announce` varchar(500) NOT NULL DEFAULT '' COMMENT '公告',
  `scroll_announce_speed` tinyint(2) unsigned NOT NULL DEFAULT '6' COMMENT '公告滚动速度',
  `scroll_announce_link` varchar(500) NOT NULL DEFAULT '' COMMENT '公告链接',
  `scroll_announce_enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示顶部公告',
  `copyright` varchar(500) NOT NULL DEFAULT '' COMMENT '底部版权',
  `copyright_link` varchar(500) NOT NULL DEFAULT '' COMMENT '底部版权链接',
  `menuname1` varchar(50) NOT NULL DEFAULT '' COMMENT '菜单1名称',
  `menulink1` varchar(500) NOT NULL DEFAULT '' COMMENT '菜单1链接',
  `menuname2` varchar(50) NOT NULL DEFAULT '' COMMENT '菜单2名称',
  `menulink2` varchar(500) NOT NULL DEFAULT '' COMMENT '菜单2链接',
  `menuname3` varchar(50) NOT NULL DEFAULT '' COMMENT '菜单3名称',
  `menulink3` varchar(500) NOT NULL DEFAULT '' COMMENT '菜单3链接',
  `appid` varchar(300) NOT NULL DEFAULT '' COMMENT 'appid',
  `secret` varchar(300) NOT NULL DEFAULT '' COMMENT 'secret',
  `dateline` int(10) unsigned NOT NULL,
  `share_title` varchar(100) NOT NULL DEFAULT '',
  `share_image` varchar(500) NOT NULL DEFAULT '',
  `share_desc` varchar(200) NOT NULL DEFAULT '',
  `share_cancel` varchar(200) NOT NULL DEFAULT '',
  `share_url` varchar(200) NOT NULL DEFAULT '',
  `share_num` int(10) NOT NULL DEFAULT '0',
  `follow_url` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_businesscenter_slide`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_businesscenter_slide`;
CREATE TABLE `ims_weisrc_businesscenter_slide` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `cityid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '城市id',
  `title` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(200) NOT NULL DEFAULT '',
  `storeid` int(10) unsigned NOT NULL DEFAULT '0',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `attachment` varchar(100) NOT NULL DEFAULT '',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `isfirst` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否在首页显示',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_businesscenter_stores`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_businesscenter_stores`;
CREATE TABLE `ims_weisrc_businesscenter_stores` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL DEFAULT '0' COMMENT '公众号id',
  `cityid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '城市id',
  `pcate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '类别id',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '类别id',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `description` text,
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '微站网址',
  `site_name` varchar(100) NOT NULL DEFAULT '' COMMENT '微站按钮名称',
  `site_url` varchar(200) NOT NULL DEFAULT '' COMMENT '微站网址',
  `shop_name` varchar(100) NOT NULL DEFAULT '' COMMENT '折扣按钮名称',
  `shop_url` varchar(400) NOT NULL DEFAULT '' COMMENT '折扣链接',
  `logo` varchar(200) NOT NULL DEFAULT '' COMMENT '商家logo',
  `qrcode` varchar(200) NOT NULL DEFAULT '' COMMENT '商家logo',
  `qrcode_url` varchar(400) NOT NULL DEFAULT '' COMMENT '素材链接',
  `qrcode_description` varchar(200) NOT NULL DEFAULT '' COMMENT '二维码文字提示',
  `services` varchar(200) NOT NULL DEFAULT '' COMMENT '服务范围',
  `username` varchar(20) NOT NULL DEFAULT '' COMMENT '联系人',
  `tel` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `address` varchar(200) NOT NULL COMMENT '地址',
  `discounts` varchar(200) NOT NULL COMMENT '会员折扣',
  `consume` varchar(20) NOT NULL COMMENT '人均消费',
  `level` tinyint(1) NOT NULL DEFAULT '1' COMMENT '级别',
  `place` varchar(200) NOT NULL DEFAULT '',
  `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度',
  `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度',
  `hours` varchar(200) NOT NULL DEFAULT '' COMMENT '营业时间',
  `starttime` varchar(10) NOT NULL DEFAULT '09:00' COMMENT '开始时间',
  `endtime` varchar(10) NOT NULL DEFAULT '18:00' COMMENT '结束时间',
  `location_p` varchar(100) NOT NULL DEFAULT '' COMMENT '省',
  `location_c` varchar(100) NOT NULL DEFAULT '' COMMENT '市',
  `location_a` varchar(100) NOT NULL DEFAULT '' COMMENT '区',
  `isfirst` tinyint(1) NOT NULL DEFAULT '0' COMMENT '首页推荐',
  `top` tinyint(1) NOT NULL DEFAULT '0' COMMENT '推荐商家，相当于置顶',
  `from_user` varchar(50) NOT NULL DEFAULT '',
  `businesslicense` varchar(200) NOT NULL DEFAULT '' COMMENT '营业执照',
  `mode` tinyint(1) NOT NULL DEFAULT '0' COMMENT '加入方式 0:后台 1:申请入驻',
  `checked` tinyint(1) NOT NULL DEFAULT '1' COMMENT '审核',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否在手机端显示',
  `displayorder` tinyint(3) NOT NULL DEFAULT '0',
  `updatetime` int(10) NOT NULL DEFAULT '0',
  `dateline` int(10) NOT NULL DEFAULT '0',
  `time_enable1` tinyint(1) NOT NULL DEFAULT '1' COMMENT '启用营业时间1',
  `time_enable2` tinyint(1) NOT NULL DEFAULT '1' COMMENT '启用营业时间2',
  `time_enable3` tinyint(1) NOT NULL DEFAULT '1' COMMENT '启用营业时间3',
  `starttime2` varchar(10) NOT NULL DEFAULT '09:00' COMMENT '开始时间',
  `endtime2` varchar(10) NOT NULL DEFAULT '18:00' COMMENT '结束时间',
  `starttime3` varchar(10) NOT NULL DEFAULT '09:00' COMMENT '开始时间',
  `endtime3` varchar(10) NOT NULL DEFAULT '18:00' COMMENT '结束时间',
  `share_title` varchar(100) NOT NULL DEFAULT '',
  `share_desc` varchar(200) NOT NULL DEFAULT '',
  `share_cancel` varchar(200) NOT NULL DEFAULT '',
  `share_url` varchar(200) NOT NULL DEFAULT '',
  `share_num` int(10) NOT NULL DEFAULT '0',
  `follow_url` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_dish_address`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_dish_address`;
CREATE TABLE `ims_weisrc_dish_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `realname` varchar(20) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `address` varchar(300) NOT NULL,
  `dateline` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_dish_area`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_dish_area`;
CREATE TABLE `ims_weisrc_dish_area` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '区域名称',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_dish_blacklist`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_dish_blacklist`;
CREATE TABLE `ims_weisrc_dish_blacklist` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(100) DEFAULT '' COMMENT '用户ID',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_dish_cart`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_dish_cart`;
CREATE TABLE `ims_weisrc_dish_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `goodsid` int(11) NOT NULL,
  `goodstype` tinyint(1) NOT NULL DEFAULT '1',
  `price` varchar(10) NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `total` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_dish_category`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_dish_category`;
CREATE TABLE `ims_weisrc_dish_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `storeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店id',
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_dish_collection`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_dish_collection`;
CREATE TABLE `ims_weisrc_dish_collection` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `dateline` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_dish_email_setting`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_dish_email_setting`;
CREATE TABLE `ims_weisrc_dish_email_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `email_enable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '开启邮箱提醒',
  `email_host` varchar(50) DEFAULT '' COMMENT '邮箱服务器',
  `email_send` varchar(100) DEFAULT NULL,
  `email_pwd` varchar(20) DEFAULT '' COMMENT '邮箱密码',
  `email_user` varchar(100) DEFAULT '' COMMENT '发信人名称',
  `email` varchar(100) DEFAULT NULL,
  `email_business_tpl` varchar(200) DEFAULT '' COMMENT '商户接收内容模板',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_dish_goods`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_dish_goods`;
CREATE TABLE `ims_weisrc_dish_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `storeid` int(10) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL,
  `pcate` int(10) unsigned NOT NULL DEFAULT '0',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `recommend` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `thumb` varchar(100) NOT NULL DEFAULT '',
  `unitname` varchar(5) NOT NULL DEFAULT '份',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `taste` varchar(1000) NOT NULL DEFAULT '' COMMENT '口味',
  `isspecial` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `marketprice` varchar(10) NOT NULL DEFAULT '',
  `productprice` varchar(10) NOT NULL DEFAULT '',
  `credit` int(10) NOT NULL DEFAULT '0',
  `subcount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '被点次数',
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_dish_intelligent`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_dish_intelligent`;
CREATE TABLE `ims_weisrc_dish_intelligent` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `storeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店id',
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` int(10) NOT NULL DEFAULT '0' COMMENT '适用人数',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '菜品',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_dish_mealtime`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_dish_mealtime`;
CREATE TABLE `ims_weisrc_dish_mealtime` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `begintime` varchar(20) DEFAULT '09:00' COMMENT '开始时间',
  `endtime` varchar(20) DEFAULT '18:00' COMMENT '结束时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_dish_nave`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_dish_nave`;
CREATE TABLE `ims_weisrc_dish_nave` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `type` int(10) NOT NULL DEFAULT '-1' COMMENT '链接类型 -1:自定义 1:首页2:门店3:菜单列表4:我的菜单',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '导航名称',
  `link` varchar(200) NOT NULL DEFAULT '' COMMENT '导航链接',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_dish_order`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_dish_order`;
CREATE TABLE `ims_weisrc_dish_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号id',
  `storeid` int(10) unsigned NOT NULL COMMENT '门店id',
  `from_user` varchar(50) NOT NULL,
  `ordersn` varchar(30) NOT NULL COMMENT '订单号',
  `totalnum` tinyint(4) DEFAULT NULL COMMENT '总数量',
  `totalprice` varchar(10) NOT NULL COMMENT '总金额',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为确认付款方式，2为成功',
  `paytype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1余额，2在线，3到付',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `address` varchar(250) NOT NULL DEFAULT '' COMMENT '地址',
  `tel` varchar(50) NOT NULL DEFAULT '' COMMENT '联系电话',
  `reply` varchar(1000) NOT NULL DEFAULT '' COMMENT '回复',
  `meal_time` varchar(50) NOT NULL DEFAULT '' COMMENT '就餐时间',
  `counts` tinyint(4) DEFAULT '0' COMMENT '预订人数',
  `seat_type` tinyint(1) DEFAULT '0' COMMENT '位置类型1大厅2包间',
  `carports` tinyint(3) DEFAULT '0' COMMENT '车位',
  `dining_mode` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '用餐类型 1:到店 2:外卖',
  `remark` varchar(1000) NOT NULL DEFAULT '' COMMENT '备注',
  `tables` varchar(10) NOT NULL DEFAULT '' COMMENT '桌号',
  `print_sta` tinyint(1) DEFAULT '-1' COMMENT '打印状态',
  `sign` tinyint(1) NOT NULL DEFAULT '0' COMMENT '-1拒绝，0未处理，1已处理',
  `isfinish` tinyint(1) NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号',
  `goodsprice` decimal(10,2) DEFAULT '0.00',
  `dispatchprice` decimal(10,2) DEFAULT '0.00',
  `isemail` tinyint(1) NOT NULL DEFAULT '0',
  `issms` tinyint(1) NOT NULL DEFAULT '0',
  `istpl` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_dish_order_goods`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_dish_order_goods`;
CREATE TABLE `ims_weisrc_dish_order_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `goodsid` int(10) unsigned NOT NULL,
  `price` varchar(10) NOT NULL,
  `total` int(10) unsigned NOT NULL DEFAULT '1',
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_dish_print_order`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_dish_print_order`;
CREATE TABLE `ims_weisrc_dish_print_order` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `print_usr` varchar(50) DEFAULT '',
  `print_status` tinyint(1) DEFAULT '-1',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_dish_print_setting`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_dish_print_setting`;
CREATE TABLE `ims_weisrc_dish_print_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `title` varchar(200) DEFAULT '',
  `print_status` tinyint(1) NOT NULL,
  `print_type` tinyint(1) NOT NULL,
  `print_usr` varchar(50) DEFAULT '',
  `print_nums` tinyint(3) DEFAULT '1',
  `print_top` varchar(40) DEFAULT '',
  `print_bottom` varchar(40) DEFAULT '',
  `dateline` int(10) DEFAULT '0',
  `qrcode_status` tinyint(1) NOT NULL DEFAULT '0',
  `qrcode_url` varchar(200) DEFAULT '',
  `type` varchar(50) DEFAULT 'hongxin',
  `member_code` varchar(100) DEFAULT '' COMMENT '商户代码',
  `feyin_key` varchar(100) DEFAULT '' COMMENT 'api密钥',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_dish_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_dish_reply`;
CREATE TABLE `ims_weisrc_dish_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '入口类型',
  `storeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '入口门店',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `picture` varchar(255) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加日期',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_dish_setting`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_dish_setting`;
CREATE TABLE `ims_weisrc_dish_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) DEFAULT '' COMMENT '网站名称',
  `thumb` varchar(200) DEFAULT '' COMMENT '背景图',
  `storeid` int(10) unsigned NOT NULL DEFAULT '0',
  `entrance_type` tinyint(1) unsigned NOT NULL COMMENT '入口类型1:首页2门店列表3菜品列表4我的菜单',
  `entrance_storeid` tinyint(1) unsigned NOT NULL COMMENT '入口门店id',
  `order_enable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '订餐开启',
  `dining_mode` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '用餐类型 1:到店 2:外卖',
  `dateline` int(10) DEFAULT '0',
  `istplnotice` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否模版通知',
  `tplneworder` varchar(200) DEFAULT '' COMMENT '模板id',
  `tpluser` text COMMENT '通知用户',
  `searchword` varchar(1000) DEFAULT '' COMMENT '搜索关键字',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_dish_sms_checkcode`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_dish_sms_checkcode`;
CREATE TABLE `ims_weisrc_dish_sms_checkcode` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(100) DEFAULT '' COMMENT '用户ID',
  `mobile` varchar(30) DEFAULT '' COMMENT '手机',
  `checkcode` varchar(100) DEFAULT '' COMMENT '验证码',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '状态 0未使用1已使用',
  `dateline` int(10) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_dish_sms_setting`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_dish_sms_setting`;
CREATE TABLE `ims_weisrc_dish_sms_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `sms_enable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '开启短信提醒',
  `sms_verify_enable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '开启短信验证提醒',
  `sms_username` varchar(20) DEFAULT '' COMMENT '平台帐号',
  `sms_pwd` varchar(20) DEFAULT '' COMMENT '平台密码',
  `sms_mobile` varchar(20) DEFAULT '' COMMENT '商户接收短信手机',
  `sms_verify_tpl` varchar(120) DEFAULT '' COMMENT '验证短信模板',
  `sms_business_tpl` varchar(120) DEFAULT '' COMMENT '商户短信模板',
  `sms_user_tpl` varchar(120) DEFAULT '' COMMENT '用户短信模板',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_dish_stores`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_dish_stores`;
CREATE TABLE `ims_weisrc_dish_stores` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL DEFAULT '0' COMMENT '公众号id',
  `areaid` int(10) NOT NULL DEFAULT '0' COMMENT '区域id',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `logo` varchar(200) NOT NULL DEFAULT '' COMMENT '商家logo',
  `info` varchar(1000) NOT NULL DEFAULT '' COMMENT '简短描述',
  `content` text NOT NULL COMMENT '简介',
  `tel` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `location_p` varchar(100) NOT NULL DEFAULT '' COMMENT '省',
  `location_c` varchar(100) NOT NULL DEFAULT '' COMMENT '市',
  `location_a` varchar(100) NOT NULL DEFAULT '' COMMENT '区',
  `address` varchar(200) NOT NULL COMMENT '地址',
  `place` varchar(200) NOT NULL DEFAULT '',
  `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度',
  `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度',
  `password` varchar(20) NOT NULL DEFAULT '' COMMENT '登录密码',
  `hours` varchar(200) NOT NULL DEFAULT '' COMMENT '营业时间',
  `recharging_password` varchar(20) NOT NULL DEFAULT '' COMMENT '充值密码',
  `thumb_url` varchar(1000) DEFAULT NULL,
  `enable_wifi` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有wifi',
  `enable_card` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否能刷卡',
  `enable_room` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有包厢',
  `enable_park` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有停车',
  `is_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否在手机端显示',
  `is_meal` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否店内点餐',
  `is_delivery` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否外卖订餐',
  `sendingprice` varchar(10) NOT NULL DEFAULT '' COMMENT '起送价格',
  `displayorder` tinyint(3) NOT NULL DEFAULT '0',
  `updatetime` int(10) NOT NULL DEFAULT '0',
  `is_sms` tinyint(1) NOT NULL DEFAULT '0',
  `dateline` int(10) NOT NULL DEFAULT '0',
  `dispatchprice` decimal(10,2) DEFAULT '0.00',
  `is_hot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '搜索页显示',
  `freeprice` decimal(10,2) DEFAULT '0.00',
  `begintime` varchar(20) DEFAULT '09:00' COMMENT '开始时间',
  `announce` varchar(1000) NOT NULL DEFAULT '' COMMENT '通知',
  `endtime` varchar(20) DEFAULT '18:00' COMMENT '结束时间',
  `consume` varchar(20) NOT NULL COMMENT '人均消费',
  `level` tinyint(1) NOT NULL DEFAULT '1' COMMENT '级别',
  `is_rest` tinyint(1) NOT NULL DEFAULT '0',
  `typeid` int(10) NOT NULL DEFAULT '0' COMMENT '商家类型',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_dish_store_setting`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_dish_store_setting`;
CREATE TABLE `ims_weisrc_dish_store_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `order_enable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '订餐开启',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_dish_type`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_dish_type`;
CREATE TABLE `ims_weisrc_dish_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '类型名称',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_dragonboat_fans`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_dragonboat_fans`;
CREATE TABLE `ims_weisrc_dragonboat_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '' COMMENT '用户ID',
  `nickname` varchar(50) DEFAULT '',
  `headimgurl` varchar(500) DEFAULT '',
  `username` varchar(50) DEFAULT '',
  `tel` varchar(20) DEFAULT '' COMMENT '登记信息(手机等)',
  `credit` decimal(10,2) DEFAULT '0.00' COMMENT '单次最高分数',
  `totalcredit` decimal(10,2) DEFAULT '0.00' COMMENT '累计分数',
  `totalnum` int(11) DEFAULT '0' COMMENT '总次数',
  `todaynum` int(11) DEFAULT '0' COMMENT '今天次数',
  `lasttime` int(11) DEFAULT '0' COMMENT '最后游戏时间',
  `sharenum` int(11) DEFAULT '0' COMMENT '总分享次数',
  `sharelotterynum` int(11) DEFAULT '0' COMMENT '分享抽奖次数',
  `todaysharenum` int(11) DEFAULT '0' COMMENT '今日分享次数',
  `lastsharetime` int(10) DEFAULT '0',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_dragonboat_record`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_dragonboat_record`;
CREATE TABLE `ims_weisrc_dragonboat_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '0' COMMENT '用户ID',
  `fansid` int(11) DEFAULT '0',
  `credit` int(10) DEFAULT '0',
  `dateline` int(10) DEFAULT '0',
  `status` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_dragonboat_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_dragonboat_reply`;
CREATE TABLE `ims_weisrc_dragonboat_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT '0',
  `weid` int(11) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `content` varchar(200) DEFAULT '',
  `rule` text,
  `award` text,
  `bg` varchar(500) DEFAULT '',
  `logo` varchar(500) DEFAULT '',
  `start_picurl` varchar(500) DEFAULT '',
  `end_theme` varchar(50) DEFAULT '',
  `end_instruction` varchar(200) DEFAULT '',
  `end_picurl` varchar(500) DEFAULT '',
  `banner` varchar(200) DEFAULT '',
  `starttime` int(10) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  `number_times` int(11) DEFAULT '0' COMMENT '总游戏次数',
  `most_num_times` int(11) DEFAULT '0' COMMENT '每天游戏次数',
  `daysharenum` int(11) DEFAULT '0' COMMENT '日分享次数',
  `sharelotterynum` int(11) DEFAULT '0' COMMENT '分享后奖励次数',
  `viewnum` int(11) DEFAULT '0',
  `sharenum` int(11) DEFAULT '0',
  `gametime` int(11) DEFAULT '15',
  `gamelevel` int(11) DEFAULT '3',
  `cover` varchar(500) DEFAULT '',
  `showusernum` int(11) DEFAULT '20',
  `share_title` varchar(200) DEFAULT '',
  `share_url` varchar(100) DEFAULT '',
  `share_desc` varchar(300) DEFAULT '',
  `share_image` varchar(500) DEFAULT '',
  `follow_url` varchar(100) DEFAULT '',
  `follow_title` varchar(100) DEFAULT '',
  `copyright` varchar(100) DEFAULT '',
  `copyrighturl` varchar(200) DEFAULT '',
  `isneedfollow` tinyint(1) DEFAULT '1',
  `mode` tinyint(1) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_invitative_activity`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_invitative_activity`;
CREATE TABLE `ims_weisrc_invitative_activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT '0',
  `weid` int(10) unsigned DEFAULT '0',
  `reply_title` varchar(100) DEFAULT '图文标题',
  `description` varchar(255) DEFAULT '' COMMENT '描述',
  `thumb` varchar(500) NOT NULL DEFAULT '' COMMENT '封面',
  `title` varchar(100) DEFAULT '' COMMENT '活动标题',
  `content` text NOT NULL COMMENT '活动介绍',
  `organizers` varchar(100) DEFAULT '' COMMENT '举办者',
  `bg` varchar(500) DEFAULT '' COMMENT '背景',
  `cardtype` tinyint(1) DEFAULT '1' COMMENT '卡片类型',
  `cardbg` varchar(500) DEFAULT '' COMMENT '卡片背景',
  `thumbs` varchar(1000) DEFAULT '' COMMENT '活动图片',
  `musicurl` varchar(500) DEFAULT '' COMMENT '音乐链接',
  `tel` varchar(20) NOT NULL COMMENT '联系电话',
  `address` varchar(200) NOT NULL COMMENT '地址',
  `place` varchar(200) NOT NULL DEFAULT '',
  `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度',
  `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `displayorder` int(11) DEFAULT '0',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_invitative_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_invitative_reply`;
CREATE TABLE `ims_weisrc_invitative_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `activityid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_invitative_user`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_invitative_user`;
CREATE TABLE `ims_weisrc_invitative_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned DEFAULT '0',
  `from_user` varchar(100) DEFAULT '',
  `activityid` int(10) unsigned NOT NULL DEFAULT '0',
  `nickname` varchar(100) DEFAULT '',
  `headimgurl` varchar(500) DEFAULT '',
  `username` varchar(100) DEFAULT '' COMMENT '用户名称',
  `tel` varchar(50) DEFAULT '' COMMENT '联系电话',
  `company` varchar(200) DEFAULT '' COMMENT '公司',
  `position` varchar(200) DEFAULT '' COMMENT '职位',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_pano_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_pano_reply`;
CREATE TABLE `ims_weisrc_pano_reply` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL DEFAULT '0',
  `weid` int(10) NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `title` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `picture` varchar(200) NOT NULL,
  `picture1` varchar(200) NOT NULL,
  `picture2` varchar(200) NOT NULL,
  `picture3` varchar(200) NOT NULL,
  `picture4` varchar(200) NOT NULL,
  `picture5` varchar(200) NOT NULL,
  `picture6` varchar(200) NOT NULL,
  `music` varchar(400) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `dateline` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_truth_question`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_truth_question`;
CREATE TABLE `ims_weisrc_truth_question` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned DEFAULT '0',
  `from_user` varchar(100) DEFAULT '',
  `title` varchar(200) NOT NULL DEFAULT '',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_truth_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_truth_reply`;
CREATE TABLE `ims_weisrc_truth_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned DEFAULT '0',
  `qid` int(10) unsigned DEFAULT '0',
  `from_user` varchar(100) DEFAULT '',
  `parentid` int(10) unsigned DEFAULT '0',
  `parentopenid` varchar(100) DEFAULT '',
  `nickname` varchar(100) DEFAULT '',
  `headimgurl` varchar(500) DEFAULT '',
  `content` varchar(200) DEFAULT '' COMMENT '回复内容',
  `sharecount` int(10) unsigned DEFAULT '0' COMMENT '分享次数',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_truth_setting`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_truth_setting`;
CREATE TABLE `ims_weisrc_truth_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned DEFAULT '0',
  `title` varchar(100) DEFAULT '' COMMENT '网站名称',
  `share_title` varchar(100) NOT NULL DEFAULT '',
  `share_image` varchar(500) NOT NULL DEFAULT '',
  `share_desc` varchar(200) NOT NULL DEFAULT '',
  `share_cancel` varchar(200) NOT NULL DEFAULT '',
  `share_url` varchar(200) NOT NULL DEFAULT '',
  `share_num` int(10) NOT NULL DEFAULT '0',
  `follow_url` varchar(200) NOT NULL DEFAULT '',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weisrc_truth_share`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weisrc_truth_share`;
CREATE TABLE `ims_weisrc_truth_share` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned DEFAULT '0',
  `qid` int(10) unsigned DEFAULT '0',
  `from_user` varchar(100) DEFAULT '',
  `parentid` int(10) unsigned DEFAULT '0',
  `parentopenid` varchar(100) DEFAULT '',
  `nickname` varchar(100) DEFAULT '',
  `headimgurl` varchar(500) DEFAULT '',
  `content` varchar(200) DEFAULT '' COMMENT '回复内容',
  `sharecount` int(10) unsigned DEFAULT '0' COMMENT '分享次数',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weixin_awardlist`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weixin_awardlist`;
CREATE TABLE `ims_weixin_awardlist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `displayid` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  `weid` int(10) NOT NULL COMMENT '主公众号',
  `luck_name` varchar(100) NOT NULL DEFAULT '' COMMENT '奖品名称',
  `luckid` int(10) NOT NULL DEFAULT '0' COMMENT '奖项活动ID来此关键词的rid也是按人数抽奖的id',
  `num` int(10) NOT NULL DEFAULT '0' COMMENT '此项奖品的已经中奖人数',
  `tag_name` varchar(100) NOT NULL DEFAULT '' COMMENT '第几等奖',
  `tagNum` int(10) NOT NULL DEFAULT '0' COMMENT '奖品数量',
  `num_exclude` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否准许按人数抽奖的时候重复中奖',
  `tag_exclude` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否准许按第几等奖抽奖的时候重复中奖',
  `nd` varchar(500) DEFAULT NULL COMMENT '内定抽奖粉丝ID字符串',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weixin_cookie`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weixin_cookie`;
CREATE TABLE `ims_weixin_cookie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cookie` text NOT NULL,
  `cookies` text NOT NULL,
  `token` int(11) NOT NULL,
  `weid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `ims_weixin_flag`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weixin_flag`;
CREATE TABLE `ims_weixin_flag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `openid` varchar(255) NOT NULL,
  `fakeid` varchar(100) NOT NULL,
  `flag` int(11) NOT NULL,
  `vote` int(11) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `avatar` text NOT NULL,
  `content` text NOT NULL,
  `sex` varchar(255) NOT NULL,
  `cjstatu` tinyint(4) NOT NULL DEFAULT '0',
  `rid` int(10) unsigned NOT NULL COMMENT '用户当前所在的微信墙话题',
  `isjoin` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否正在加入话题',
  `isblacklist` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户是否是黑名单',
  `lastupdate` int(10) unsigned NOT NULL COMMENT '用户最后发表时间',
  `verify` varchar(10) NOT NULL,
  `status` int(1) NOT NULL,
  `othid` int(10) NOT NULL,
  `sign` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户是否已经签到',
  `signtime` int(12) NOT NULL DEFAULT '0' COMMENT '用户签到时间',
  `getaward` int(12) NOT NULL DEFAULT '0',
  `msgid` varchar(12) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `realname` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weixin_luckuser`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weixin_luckuser`;
CREATE TABLE `ims_weixin_luckuser` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL COMMENT '主公众号',
  `awardid` int(10) NOT NULL DEFAULT '0' COMMENT '奖项活动ID',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '中奖时间',
  `openid` varchar(200) NOT NULL DEFAULT '' COMMENT '粉丝标识',
  `bypername` varchar(200) DEFAULT NULL COMMENT '默认为空，只要选择了按人数才能有值',
  `rid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weixin_shake_data`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weixin_shake_data`;
CREATE TABLE `ims_weixin_shake_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `openid` varchar(255) NOT NULL,
  `point` int(11) NOT NULL,
  `avatar` text NOT NULL,
  `rid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weixin_shake_toshake`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weixin_shake_toshake`;
CREATE TABLE `ims_weixin_shake_toshake` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `openid` varchar(255) NOT NULL,
  `point` int(11) NOT NULL,
  `avatar` text NOT NULL,
  `rid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weixin_vote`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weixin_vote`;
CREATE TABLE `ims_weixin_vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `name` text NOT NULL,
  `res` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weixin_wall`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weixin_wall`;
CREATE TABLE `ims_weixin_wall` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `openid` varchar(100) NOT NULL,
  `messageid` int(11) NOT NULL,
  `num` int(11) NOT NULL,
  `content` text NOT NULL,
  `nickname` text NOT NULL,
  `avatar` text NOT NULL,
  `ret` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `image` text NOT NULL,
  `type` varchar(10) NOT NULL COMMENT '发表内容类型',
  `isshow` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示',
  `createtime` int(10) NOT NULL,
  `rid` int(10) unsigned NOT NULL COMMENT '用户当前所在的微信墙话题',
  `isblacklist` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weixin_wall_num`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weixin_wall_num`;
CREATE TABLE `ims_weixin_wall_num` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL COMMENT '用户当前所在的微信墙话题',
  `num` int(11) NOT NULL,
  `weid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_weixin_wall_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_weixin_wall_reply`;
CREATE TABLE `ims_weixin_wall_reply` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL COMMENT '规则ID',
  `weid` int(10) NOT NULL,
  `enter_tips` varchar(300) NOT NULL DEFAULT '' COMMENT '进入提示',
  `subit_tips` varchar(300) NOT NULL DEFAULT '' COMMENT '首次关注进入提示',
  `quit_tips` varchar(300) NOT NULL DEFAULT '' COMMENT '退出提示',
  `send_tips` varchar(300) NOT NULL DEFAULT '' COMMENT '发表提示',
  `quit_command` varchar(10) NOT NULL DEFAULT '' COMMENT '退出指令',
  `timeout` int(10) NOT NULL DEFAULT '0' COMMENT '超时时间',
  `isshow` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否需要审核',
  `lurumobile` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否需要审核',
  `chaoshi_tips` varchar(300) NOT NULL DEFAULT '' COMMENT '发表提示',
  `isopen` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '摇一摇状态',
  `votetitle` varchar(300) NOT NULL DEFAULT '' COMMENT '投票标题',
  `votepower` varchar(300) NOT NULL DEFAULT '' COMMENT '投票页面版权',
  `yyyzhuti` varchar(300) NOT NULL DEFAULT '' COMMENT '摇一摇主题',
  `cjname` varchar(300) NOT NULL DEFAULT '' COMMENT '抽奖名字',
  `cjimgurl` varchar(300) NOT NULL DEFAULT '' COMMENT '抽奖主题图片',
  `loginpass` varchar(300) NOT NULL DEFAULT '' COMMENT '主持人登录密码',
  `indexstyle` varchar(300) NOT NULL DEFAULT '' COMMENT '风格',
  `danmutime` int(10) NOT NULL DEFAULT '20' COMMENT '弹幕时间',
  `refreshtime` int(10) NOT NULL DEFAULT '0' COMMENT '刷新时间',
  `yyyendtime` int(10) NOT NULL DEFAULT '0' COMMENT '摇一摇结束总摇晃数目',
  `yyyshowperson` int(10) NOT NULL DEFAULT '0' COMMENT '摇一摇结果显示人数',
  `voterefreshtime` int(10) NOT NULL DEFAULT '0' COMMENT 'tp刷新时间',
  `qdqshow` int(10) NOT NULL DEFAULT '0' COMMENT '签到墙是否显示',
  `yyyshow` int(10) NOT NULL DEFAULT '0' COMMENT '摇一摇是否显示',
  `ddpshow` int(10) NOT NULL DEFAULT '0' COMMENT '对对碰是否显示',
  `tpshow` int(10) NOT NULL DEFAULT '0' COMMENT '投票是否显示',
  `cjshow` int(10) NOT NULL DEFAULT '0' COMMENT '抽奖是否显示',
  `danmushow` int(10) NOT NULL DEFAULT '0' COMMENT '抽奖是否显示',
  `cjnum_tag` int(10) NOT NULL DEFAULT '0' COMMENT '按人数抽奖是否开启',
  `cjnum_exclude` int(10) NOT NULL DEFAULT '0' COMMENT '按人数抽奖是否可以重复中奖',
  `cjtag_exclude` int(10) NOT NULL DEFAULT '0' COMMENT '按人数抽奖是否可以重复中奖',
  `defaultshow` int(10) NOT NULL DEFAULT '2' COMMENT '默认打开哪面墙',
  `yyyrealman` int(10) NOT NULL DEFAULT '0' COMMENT '真实人数',
  `yyybgimg` varchar(300) NOT NULL COMMENT '摇一摇背景',
  `danmubgimg` varchar(300) NOT NULL COMMENT '弹幕背景',
  `saywords` varchar(300) NOT NULL COMMENT '摇一摇背景',
  `signwords` varchar(300) NOT NULL COMMENT '摇一摇背景',
  `cjwords` varchar(300) NOT NULL COMMENT '摇一摇背景',
  `votewords` varchar(300) NOT NULL COMMENT '摇一摇背景',
  `ddpwords` varchar(300) NOT NULL COMMENT '摇一摇背景',
  `danmuwords` varchar(300) NOT NULL COMMENT '弹幕标题',
  `toplogo` varchar(300) NOT NULL COMMENT '弹幕标题',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wmb_goshare_convert`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wmb_goshare_convert`;
CREATE TABLE `ims_wmb_goshare_convert` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `themeid` int(11) NOT NULL,
  `themename` varchar(200) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `giftid` int(11) NOT NULL,
  `giftname` varchar(100) NOT NULL,
  `code` varchar(100) NOT NULL,
  `codetime` varchar(100) NOT NULL,
  `istake` int(2) NOT NULL,
  `cookieid` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wmb_goshare_cookie`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wmb_goshare_cookie`;
CREATE TABLE `ims_wmb_goshare_cookie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `themeid` int(11) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `cookieid` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wmb_goshare_gift`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wmb_goshare_gift`;
CREATE TABLE `ims_wmb_goshare_gift` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `themeid` int(11) NOT NULL,
  `groupid` int(11) NOT NULL,
  `groupname` varchar(200) NOT NULL,
  `stdname` varchar(100) NOT NULL,
  `unit` varchar(10) NOT NULL,
  `desc` varchar(100) NOT NULL,
  `amount` int(11) NOT NULL,
  `needscore` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `left` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wmb_goshare_giftgroup`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wmb_goshare_giftgroup`;
CREATE TABLE `ims_wmb_goshare_giftgroup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `themeid` int(11) NOT NULL,
  `themename` varchar(200) NOT NULL,
  `groupname` varchar(100) NOT NULL,
  `groupstate` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wmb_goshare_member`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wmb_goshare_member`;
CREATE TABLE `ims_wmb_goshare_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `themeid` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `cookieid` varchar(200) NOT NULL,
  `helpid` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wmb_goshare_theme`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wmb_goshare_theme`;
CREATE TABLE `ims_wmb_goshare_theme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `groupid` int(11) NOT NULL,
  `groupname` varchar(200) NOT NULL,
  `themename` varchar(100) NOT NULL,
  `headtitle` varchar(200) NOT NULL,
  `themetitle` varchar(1000) NOT NULL,
  `themelogo` varchar(512) NOT NULL,
  `undertaker` varchar(200) NOT NULL,
  `ad1` varchar(200) NOT NULL,
  `ad1content` varchar(1000) NOT NULL,
  `ad2` varchar(200) NOT NULL,
  `ad2content` varchar(1000) NOT NULL,
  `ad3` varchar(200) NOT NULL,
  `ad3content` varchar(1000) NOT NULL,
  `ad3pic` varchar(512) NOT NULL,
  `url` varchar(1000) NOT NULL,
  `place` varchar(200) NOT NULL,
  `tel` varchar(30) NOT NULL,
  `begintime` date NOT NULL,
  `endtime` date NOT NULL,
  `footpic` varchar(512) NOT NULL,
  `overtitle` varchar(1000) NOT NULL,
  `sharepic` varchar(512) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wmb_goshare_transmit`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wmb_goshare_transmit`;
CREATE TABLE `ims_wmb_goshare_transmit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `themeid` int(11) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `helpid` varchar(200) NOT NULL,
  `cookieid` varchar(200) NOT NULL,
  `helpcookid` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wxwall_award`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wxwall_award`;
CREATE TABLE `ims_wxwall_award` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wxwall_members`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wxwall_members`;
CREATE TABLE `ims_wxwall_members` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from_user` varchar(50) NOT NULL COMMENT '用户的唯一身份ID',
  `avatar` varchar(255) NOT NULL COMMENT '粉丝头像',
  `rid` int(10) unsigned NOT NULL COMMENT '用户当前所在的微信墙话题',
  `isjoin` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否正在加入话题',
  `isblacklist` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户是否是黑名单',
  `lastupdate` int(10) unsigned NOT NULL COMMENT '用户最后发表时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wxwall_message`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wxwall_message`;
CREATE TABLE `ims_wxwall_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `from_user` varchar(50) NOT NULL COMMENT '用户的唯一ID',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '用户发表的内容',
  `type` varchar(10) NOT NULL COMMENT '发表内容类型',
  `isshow` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wxwall_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wxwall_reply`;
CREATE TABLE `ims_wxwall_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `acid` int(10) NOT NULL,
  `enter_tips` varchar(300) NOT NULL DEFAULT '' COMMENT '进入提示',
  `quit_tips` varchar(300) NOT NULL DEFAULT '' COMMENT '退出提示',
  `send_tips` varchar(300) NOT NULL DEFAULT '' COMMENT '发表提示',
  `quit_command` varchar(10) NOT NULL DEFAULT '' COMMENT '退出指令',
  `timeout` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '超时时间',
  `isshow` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要审核',
  `logo` varchar(255) NOT NULL DEFAULT '',
  `background` varchar(255) NOT NULL DEFAULT '',
  `syncwall` varchar(2000) NOT NULL DEFAULT '' COMMENT '第三方墙',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_wx_tuijian`
-- ----------------------------
DROP TABLE IF EXISTS `ims_wx_tuijian`;
CREATE TABLE `ims_wx_tuijian` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '公众号名称',
  `description` varchar(100) NOT NULL DEFAULT '' COMMENT '公众号名称',
  `guanzhuUrl` varchar(255) NOT NULL DEFAULT '' COMMENT '引导关注',
  `type` varchar(1) NOT NULL DEFAULT '1',
  `clickNum` int(10) unsigned NOT NULL DEFAULT '0',
  `ipclient` varchar(50) NOT NULL DEFAULT '' COMMENT 'ip',
  `thumb` varchar(500) NOT NULL DEFAULT '' COMMENT '缩略图',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `hot` int(1) NOT NULL COMMENT '是否热门 0默认 1热门',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_activity`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_activity`;
CREATE TABLE `ims_xcommunity_activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `regionid` varchar(500) NOT NULL,
  `starttime` int(11) NOT NULL,
  `endtime` int(11) NOT NULL,
  `enddate` varchar(30) NOT NULL,
  `picurl` varchar(100) NOT NULL,
  `number` int(11) NOT NULL DEFAULT '1',
  `content` varchar(2000) NOT NULL,
  `status` int(1) NOT NULL,
  `createtime` int(11) unsigned NOT NULL,
  `resnumber` int(11) unsigned NOT NULL COMMENT '报名人数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_admap`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_admap`;
CREATE TABLE `ims_xcommunity_admap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `regionid` int(11) NOT NULL,
  `adid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_announcement`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_announcement`;
CREATE TABLE `ims_xcommunity_announcement` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `regionid` int(10) unsigned NOT NULL COMMENT '小区编号',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `author` varchar(50) NOT NULL COMMENT '作者',
  `createtime` int(10) unsigned NOT NULL,
  `starttime` int(11) unsigned NOT NULL COMMENT '开始时间',
  `endtime` int(11) unsigned NOT NULL COMMENT '结束时间',
  `status` tinyint(1) NOT NULL COMMENT '状态 1禁用，2启用',
  `enable` tinyint(1) NOT NULL COMMENT '模板类型',
  `datetime` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL COMMENT '通知范围',
  `reason` varchar(100) NOT NULL COMMENT '通知范围',
  `remark` varchar(100) NOT NULL COMMENT '通知备注',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='发布公告';

-- ----------------------------
--  Table structure for `ims_xcommunity_business`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_business`;
CREATE TABLE `ims_xcommunity_business` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `mobile` varchar(12) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL,
  `photo` varchar(100) NOT NULL,
  `qq` int(11) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `status` int(1) unsigned NOT NULL COMMENT '0未审核，1审核',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_carpool`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_carpool`;
CREATE TABLE `ims_xcommunity_carpool` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `seat` int(2) unsigned NOT NULL,
  `sprice` int(10) unsigned NOT NULL,
  `month` int(2) unsigned NOT NULL,
  `yday` int(2) unsigned NOT NULL,
  `contact` varchar(50) NOT NULL,
  `mobile` varchar(13) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `start_position` varchar(100) NOT NULL,
  `end_position` varchar(100) NOT NULL,
  `startMinute` int(10) unsigned NOT NULL,
  `startSeconds` int(10) unsigned NOT NULL,
  `license_number` varchar(100) NOT NULL,
  `car_model` varchar(100) NOT NULL,
  `car_brand` varchar(100) NOT NULL,
  `content` varchar(300) NOT NULL,
  `enable` int(1) NOT NULL COMMENT '1开启,0关闭',
  `createtime` int(10) unsigned NOT NULL,
  `regionid` int(10) unsigned NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_fled`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_fled`;
CREATE TABLE `ims_xcommunity_fled` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `title` varchar(20) NOT NULL,
  `rolex` varchar(30) NOT NULL,
  `category` varchar(30) NOT NULL,
  `yprice` int(10) NOT NULL,
  `zprice` int(10) NOT NULL,
  `realname` varchar(18) NOT NULL,
  `mobile` varchar(12) NOT NULL,
  `description` varchar(100) NOT NULL,
  `regionid` int(10) NOT NULL,
  `createtime` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `images` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_member`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_member`;
CREATE TABLE `ims_xcommunity_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) unsigned NOT NULL,
  `regionid` int(10) unsigned NOT NULL COMMENT '小区编号',
  `openid` varchar(50) NOT NULL,
  `realname` varchar(50) NOT NULL COMMENT '真实姓名',
  `mobile` varchar(15) NOT NULL COMMENT '手机号',
  `regionname` varchar(50) NOT NULL COMMENT '小区名称',
  `address` varchar(100) NOT NULL COMMENT '楼栋门牌',
  `remark` varchar(1000) NOT NULL COMMENT '备注',
  `status` tinyint(1) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `manage_status` tinyint(1) unsigned NOT NULL COMMENT '授权管理员',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='注册用户';

-- ----------------------------
--  Table structure for `ims_xcommunity_nav`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_nav`;
CREATE TABLE `ims_xcommunity_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `displayorder` int(10) NOT NULL,
  `pcate` int(10) NOT NULL,
  `title` varchar(30) NOT NULL COMMENT '菜单标题',
  `url` varchar(1000) NOT NULL COMMENT '菜单链接',
  `styleid` int(10) NOT NULL COMMENT '风格id',
  `status` int(1) NOT NULL COMMENT '状态',
  `icon` varchar(50) NOT NULL COMMENT '系统图标',
  `bgcolor` varchar(20) NOT NULL COMMENT '背景颜色',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_navextension`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_navextension`;
CREATE TABLE `ims_xcommunity_navextension` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `navurl` varchar(100) NOT NULL,
  `icon` varchar(20) NOT NULL,
  `content` text NOT NULL COMMENT '说明',
  `cate` int(1) NOT NULL,
  `bgcolor` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_notice_setting`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_notice_setting`;
CREATE TABLE `ims_xcommunity_notice_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `regionid` int(10) unsigned NOT NULL,
  `template_id_1` varchar(100) NOT NULL,
  `template_id_2` varchar(100) NOT NULL,
  `template_id_3` varchar(100) NOT NULL,
  `template_id_4` varchar(100) NOT NULL,
  `template_id_5` varchar(100) NOT NULL,
  `template_id_6` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='公告模板消息ID设置';

-- ----------------------------
--  Table structure for `ims_xcommunity_phone`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_phone`;
CREATE TABLE `ims_xcommunity_phone` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `regionid` int(10) unsigned NOT NULL COMMENT '小区编号',
  `weid` int(11) unsigned NOT NULL COMMENT '公众号',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `phone` varchar(50) NOT NULL COMMENT '号码',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='常用电话';

-- ----------------------------
--  Table structure for `ims_xcommunity_property`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_property`;
CREATE TABLE `ims_xcommunity_property` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL COMMENT '标题',
  `topPicture` varchar(255) NOT NULL COMMENT '照片',
  `mcommunity` varchar(255) NOT NULL COMMENT '微小区URL',
  `content` varchar(2000) NOT NULL COMMENT '内容',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='物业介绍';

-- ----------------------------
--  Table structure for `ims_xcommunity_propertyfree`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_propertyfree`;
CREATE TABLE `ims_xcommunity_propertyfree` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `mobile` varchar(13) NOT NULL,
  `username` varchar(30) NOT NULL,
  `homenumber` varchar(15) NOT NULL,
  `profree` varchar(10) NOT NULL,
  `tcf` varchar(10) NOT NULL,
  `gtsf` varchar(10) NOT NULL,
  `gtdf` varchar(10) NOT NULL,
  `protimeid` int(10) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `status` int(1) unsigned NOT NULL COMMENT '1代表缴费，0代表未缴费',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付',
  `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_protime`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_protime`;
CREATE TABLE `ims_xcommunity_protime` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `protime` varchar(30) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_region`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_region`;
CREATE TABLE `ims_xcommunity_region` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `linkmen` varchar(50) NOT NULL COMMENT '联系人',
  `linkway` varchar(50) NOT NULL COMMENT '联系电话',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='添加小区信息';

-- ----------------------------
--  Table structure for `ims_xcommunity_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_reply`;
CREATE TABLE `ims_xcommunity_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `reportid` int(10) unsigned NOT NULL COMMENT '报告ID',
  `isreply` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是回复',
  `content` varchar(5000) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_report`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_report`;
CREATE TABLE `ims_xcommunity_report` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(50) NOT NULL COMMENT '用户身份',
  `weid` int(11) unsigned NOT NULL COMMENT '公众号ID',
  `regionid` int(10) unsigned NOT NULL COMMENT '小区编号',
  `type` tinyint(1) NOT NULL COMMENT '1为报修，2为投诉',
  `category` varchar(50) NOT NULL DEFAULT '' COMMENT '类目',
  `content` varchar(255) NOT NULL COMMENT '投诉内容',
  `requirement` varchar(1000) NOT NULL,
  `createtime` int(11) unsigned NOT NULL COMMENT '投诉日期',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态,1已解决,0未解决,2为用户取消',
  `newmsg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有新信息',
  `rank` tinyint(3) unsigned NOT NULL COMMENT '评级 1满意，2一般，3不满意',
  `comment` varchar(1000) NOT NULL,
  `resolve` varchar(1000) NOT NULL COMMENT '处理结果',
  `resolver` varchar(50) NOT NULL COMMENT '处理人',
  `resolvetime` int(10) NOT NULL COMMENT '处理时间',
  `images` text,
  `print_sta` int(3) NOT NULL COMMENT '打印状态',
  PRIMARY KEY (`id`),
  KEY `idx_weid_regionid` (`weid`,`regionid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_res`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_res`;
CREATE TABLE `ims_xcommunity_res` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(100) NOT NULL,
  `truename` varchar(30) NOT NULL,
  `mobile` varchar(12) NOT NULL,
  `num` int(2) unsigned NOT NULL,
  `rid` int(11) unsigned NOT NULL,
  `sex` varchar(6) NOT NULL,
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_search`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_search`;
CREATE TABLE `ims_xcommunity_search` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `sname` varchar(30) NOT NULL,
  `surl` varchar(100) NOT NULL,
  `status` int(1) NOT NULL,
  `icon` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_service`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_service`;
CREATE TABLE `ims_xcommunity_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `regionid` int(10) unsigned NOT NULL,
  `servicecategory` int(10) unsigned NOT NULL COMMENT '生活服务大分类 1家政服务，2租赁服务',
  `servicesmallcategory` varchar(50) NOT NULL COMMENT '生活服务小分类',
  `requirement` varchar(255) NOT NULL COMMENT '精准要求,如保洁需要填写 平米大小',
  `remark` varchar(500) NOT NULL COMMENT '备注',
  `contacttype` int(10) unsigned NOT NULL COMMENT '联系类型:1.随时联系;2.白天联系;3:晚上联系;4:自定义',
  `contactdesc` varchar(255) NOT NULL COMMENT '联系描述',
  `status` int(10) unsigned NOT NULL COMMENT '状态',
  `createtime` int(10) unsigned NOT NULL,
  `images` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_servicecategory`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_servicecategory`;
CREATE TABLE `ims_xcommunity_servicecategory` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned DEFAULT NULL,
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `description` varchar(50) NOT NULL COMMENT '分类描述',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_shopping_address`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_shopping_address`;
CREATE TABLE `ims_xcommunity_shopping_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `realname` varchar(20) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `province` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `area` varchar(30) NOT NULL,
  `address` varchar(300) NOT NULL,
  `isdefault` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `regionid` int(11) unsigned NOT NULL COMMENT '当前小区ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_shopping_cart`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_shopping_cart`;
CREATE TABLE `ims_xcommunity_shopping_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `goodsid` int(11) NOT NULL,
  `goodstype` tinyint(1) NOT NULL DEFAULT '1',
  `from_user` varchar(50) NOT NULL,
  `total` int(10) unsigned NOT NULL,
  `optionid` int(10) DEFAULT '0',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_shopping_category`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_shopping_category`;
CREATE TABLE `ims_xcommunity_shopping_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `thumb` varchar(255) NOT NULL COMMENT '分类图片',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0',
  `description` varchar(500) NOT NULL COMMENT '分类介绍',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  `regionid` varchar(1000) NOT NULL COMMENT '小区ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='超市商品分类';

-- ----------------------------
--  Table structure for `ims_xcommunity_shopping_dispatch`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_shopping_dispatch`;
CREATE TABLE `ims_xcommunity_shopping_dispatch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `dispatchname` varchar(50) DEFAULT '',
  `dispatchtype` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `firstprice` decimal(10,2) DEFAULT '0.00',
  `secondprice` decimal(10,2) DEFAULT '0.00',
  `firstweight` int(11) DEFAULT '0',
  `secondweight` int(11) DEFAULT '0',
  `express` int(11) DEFAULT '0',
  `description` text,
  `regionid` varchar(1000) NOT NULL COMMENT '小区ID',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_shopping_express`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_shopping_express`;
CREATE TABLE `ims_xcommunity_shopping_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `express_name` varchar(50) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `express_price` varchar(10) DEFAULT '',
  `express_area` varchar(100) DEFAULT '',
  `express_url` varchar(255) DEFAULT '',
  `regionid` varchar(1000) NOT NULL COMMENT '小区ID',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_shopping_feedback`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_shopping_feedback`;
CREATE TABLE `ims_xcommunity_shopping_feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为维权，2为告擎',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态0未解决，1用户同意，2用户拒绝',
  `feedbackid` varchar(30) NOT NULL COMMENT '投诉单号',
  `transid` varchar(30) NOT NULL COMMENT '订单号',
  `reason` varchar(1000) NOT NULL COMMENT '理由',
  `solution` varchar(1000) NOT NULL COMMENT '期待解决方案',
  `remark` varchar(1000) NOT NULL COMMENT '备注',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`),
  KEY `idx_feedbackid` (`feedbackid`),
  KEY `idx_createtime` (`createtime`),
  KEY `idx_transid` (`transid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_shopping_goods`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_shopping_goods`;
CREATE TABLE `ims_xcommunity_shopping_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `pcate` int(10) unsigned NOT NULL DEFAULT '0',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为实体，2为虚拟',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `unit` varchar(5) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `goodssn` varchar(50) NOT NULL DEFAULT '',
  `productsn` varchar(50) NOT NULL DEFAULT '',
  `marketprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `productprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `costprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `originalprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价',
  `total` int(10) NOT NULL DEFAULT '0',
  `totalcnf` int(11) DEFAULT '0' COMMENT '0 拍下减库存 1 付款减库存 2 永久不减',
  `sales` int(10) unsigned NOT NULL DEFAULT '0',
  `spec` varchar(5000) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `weight` decimal(10,2) NOT NULL DEFAULT '0.00',
  `credit` int(11) DEFAULT '0',
  `maxbuy` int(11) DEFAULT '0',
  `hasoption` int(11) DEFAULT '0',
  `dispatch` int(11) DEFAULT '0',
  `thumb_url` text,
  `isnew` int(11) DEFAULT '0',
  `ishot` int(11) DEFAULT '0',
  `isdiscount` int(11) DEFAULT '0',
  `isrecommand` int(11) DEFAULT '0',
  `istime` int(11) DEFAULT '0',
  `timestart` int(11) DEFAULT '0',
  `timeend` int(11) DEFAULT '0',
  `viewcount` int(11) DEFAULT '0',
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `regionid` varchar(1000) NOT NULL COMMENT '小区ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_shopping_goods_option`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_shopping_goods_option`;
CREATE TABLE `ims_xcommunity_shopping_goods_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `thumb` varchar(60) DEFAULT '',
  `productprice` decimal(10,2) DEFAULT '0.00',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  `costprice` decimal(10,2) DEFAULT '0.00',
  `stock` int(11) DEFAULT '0',
  `weight` decimal(10,2) DEFAULT '0.00',
  `displayorder` int(11) DEFAULT '0',
  `specs` text,
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`goodsid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_shopping_goods_param`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_shopping_goods_param`;
CREATE TABLE `ims_xcommunity_shopping_goods_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `value` text,
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`goodsid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_shopping_order`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_shopping_order`;
CREATE TABLE `ims_xcommunity_shopping_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `ordersn` varchar(20) NOT NULL,
  `price` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功',
  `sendtype` tinyint(1) unsigned NOT NULL COMMENT '1为快递，2为自提',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付',
  `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号',
  `goodstype` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `remark` varchar(1000) NOT NULL DEFAULT '',
  `addressid` int(10) unsigned NOT NULL,
  `expresscom` varchar(30) NOT NULL DEFAULT '',
  `expresssn` varchar(50) NOT NULL DEFAULT '',
  `express` varchar(200) NOT NULL DEFAULT '',
  `goodsprice` decimal(10,2) DEFAULT '0.00',
  `dispatchprice` decimal(10,2) DEFAULT '0.00',
  `dispatch` int(10) DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  `regionid` int(11) unsigned NOT NULL COMMENT '当前小区ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_shopping_order_goods`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_shopping_order_goods`;
CREATE TABLE `ims_xcommunity_shopping_order_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `goodsid` int(10) unsigned NOT NULL,
  `price` decimal(10,2) DEFAULT '0.00',
  `total` int(10) unsigned NOT NULL DEFAULT '1',
  `optionid` int(10) DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  `optionname` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_shopping_product`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_shopping_product`;
CREATE TABLE `ims_xcommunity_shopping_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goodsid` int(11) NOT NULL,
  `productsn` varchar(50) NOT NULL,
  `title` varchar(1000) NOT NULL,
  `marketprice` decimal(10,0) unsigned NOT NULL,
  `productprice` decimal(10,0) unsigned NOT NULL,
  `total` int(11) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `spec` varchar(5000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_goodsid` (`goodsid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_shopping_slide`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_shopping_slide`;
CREATE TABLE `ims_xcommunity_shopping_slide` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  `regionid` varchar(1000) NOT NULL COMMENT '小区ID',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_shopping_spec`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_shopping_spec`;
CREATE TABLE `ims_xcommunity_shopping_spec` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `displaytype` tinyint(3) unsigned NOT NULL,
  `content` text NOT NULL,
  `goodsid` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_shopping_spec_item`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_shopping_spec_item`;
CREATE TABLE `ims_xcommunity_shopping_spec_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `specid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `show` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_specid` (`specid`),
  KEY `indx_show` (`show`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_sjdp`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_sjdp`;
CREATE TABLE `ims_xcommunity_sjdp` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `uid` int(11) NOT NULL,
  `regionid` varchar(50) NOT NULL,
  `sjname` varchar(30) NOT NULL,
  `picurl` varchar(100) NOT NULL,
  `contactname` varchar(30) NOT NULL,
  `mobile` varchar(12) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `qq` int(11) NOT NULL,
  `province` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `dist` varchar(50) NOT NULL,
  `address` varchar(150) NOT NULL,
  `shopdesc` varchar(500) NOT NULL,
  `businnesstime` varchar(20) NOT NULL,
  `businessurl` varchar(100) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_slide`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_slide`;
CREATE TABLE `ims_xcommunity_slide` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `displayorder` int(10) NOT NULL,
  `title` varchar(30) NOT NULL,
  `thumb` varchar(200) NOT NULL,
  `url` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_template`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_template`;
CREATE TABLE `ims_xcommunity_template` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `styleid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='模板设置';

-- ----------------------------
--  Table structure for `ims_xcommunity_verifycode`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_verifycode`;
CREATE TABLE `ims_xcommunity_verifycode` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `verifycode` varchar(6) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `total` tinyint(3) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xcommunity_wechat_notice`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xcommunity_wechat_notice`;
CREATE TABLE `ims_xcommunity_wechat_notice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `regionid` int(10) unsigned NOT NULL,
  `fansopenid` varchar(30) NOT NULL,
  `repair_status` int(1) NOT NULL,
  `report_status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信设置';

-- ----------------------------
--  Table structure for `ims_xc_article_adv_cache`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xc_article_adv_cache`;
CREATE TABLE `ims_xc_article_adv_cache` (
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `adv_on_off` varchar(10) NOT NULL DEFAULT 'off',
  `adv_top` text NOT NULL,
  `adv_status` text NOT NULL,
  `adv_bottom` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xc_article_article`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xc_article_article`;
CREATE TABLE `ims_xc_article_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `iscommend` tinyint(1) NOT NULL DEFAULT '0',
  `ishot` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pcate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '一级分类',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二级分类',
  `template` varchar(300) NOT NULL DEFAULT '' COMMENT '内容模板目录',
  `templatefile` varchar(300) NOT NULL DEFAULT '' COMMENT '内容模板文件',
  `title` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(100) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `thumb` varchar(1024) NOT NULL DEFAULT '' COMMENT '内容配图',
  `sharethumb` varchar(1024) NOT NULL DEFAULT '' COMMENT '分享缩率图',
  `source` varchar(50) NOT NULL DEFAULT '' COMMENT '来源',
  `author` varchar(50) NOT NULL COMMENT '作者',
  `recommendation` text NOT NULL COMMENT '推荐ID列表',
  `recommendation_source` varchar(20) NOT NULL COMMENT '推荐来源user自定义rand随机none没有',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `linkurl` varchar(500) NOT NULL DEFAULT '',
  `redirect_url` varchar(500) NOT NULL DEFAULT '',
  `share_credit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享积分奖励',
  `click_credit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击积分奖励',
  `max_credit` int(10) NOT NULL DEFAULT '0' COMMENT '积分奖励上限',
  `per_user_credit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '单个用户送积分上限，0表示不限制',
  `praise_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数',
  `read_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '阅读数',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `adv_on_off` varchar(10) NOT NULL DEFAULT 'off',
  `adv_top` text NOT NULL,
  `adv_status` text NOT NULL,
  `adv_bottom` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_iscommend` (`iscommend`),
  KEY `idx_ishot` (`ishot`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xc_article_article_category`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xc_article_article_category`;
CREATE TABLE `ims_xc_article_article_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `nid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联导航id',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  `icon` varchar(100) NOT NULL DEFAULT '' COMMENT '分类图标',
  `thumb` varchar(1024) NOT NULL DEFAULT '' COMMENT '分类图片',
  `description` varchar(100) NOT NULL DEFAULT '' COMMENT '分类描述',
  `template` varchar(300) NOT NULL DEFAULT '' COMMENT '分类模板目录',
  `templatefile` varchar(300) NOT NULL DEFAULT '' COMMENT '分类模板文件',
  `linkurl` varchar(500) NOT NULL DEFAULT '',
  `ishomepage` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xc_article_article_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xc_article_article_reply`;
CREATE TABLE `ims_xc_article_article_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `articleid` int(11) NOT NULL,
  `isfill` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xc_article_share_track`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xc_article_share_track`;
CREATE TABLE `ims_xc_article_share_track` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `credit` int(10) unsigned NOT NULL,
  `clicker_id` varchar(100) NOT NULL DEFAULT '',
  `shareby` varchar(100) NOT NULL DEFAULT '',
  `track_type` varchar(100) NOT NULL DEFAULT '',
  `track_sub_type` varchar(100) NOT NULL DEFAULT '',
  `track_msg` varchar(100) NOT NULL DEFAULT '',
  `detail_id` varchar(50) NOT NULL DEFAULT '' COMMENT '具体来源',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '文章标题',
  `extra` varchar(50) NOT NULL COMMENT '附加信息',
  `access_time` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xfcommunity_images`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xfcommunity_images`;
CREATE TABLE `ims_xfcommunity_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `src` varchar(255) DEFAULT NULL,
  `file` longtext,
  `type` int(11) NOT NULL COMMENT '报修1，租赁2',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xhw_face`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xhw_face`;
CREATE TABLE `ims_xhw_face` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `description` mediumtext CHARACTER SET utf8 NOT NULL,
  `picurl` varchar(1000) CHARACTER SET utf8 NOT NULL,
  `grade` varchar(10) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `ims_xhw_face_link`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xhw_face_link`;
CREATE TABLE `ims_xhw_face_link` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `weid` int(20) NOT NULL,
  `link` varchar(500) CHARACTER SET utf8 NOT NULL,
  `number` int(10) NOT NULL,
  `api_key` varchar(100) NOT NULL,
  `api_secret` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `ims_xhw_picvote`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xhw_picvote`;
CREATE TABLE `ims_xhw_picvote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `weid` int(10) NOT NULL,
  `title` varchar(200) NOT NULL,
  `photo` varchar(200) NOT NULL,
  `smalltext` varchar(300) NOT NULL,
  `share_title` varchar(500) NOT NULL,
  `share_desc` varchar(500) NOT NULL,
  `follow_url` varchar(300) NOT NULL,
  `rule_url` varchar(500) NOT NULL,
  `bgcolor` varchar(20) NOT NULL,
  `rule` text NOT NULL,
  `submit_url` varchar(500) NOT NULL,
  `starttime` int(20) NOT NULL,
  `endtime` int(20) NOT NULL,
  `logo` varchar(500) NOT NULL,
  `imgnum` int(10) NOT NULL,
  `mynum` int(10) NOT NULL COMMENT '投票上限',
  `cnzz` varchar(500) NOT NULL,
  `pass` int(10) NOT NULL,
  `anum` int(10) NOT NULL,
  `bnum` int(10) NOT NULL,
  `adpic` varchar(500) NOT NULL,
  `adlink` varchar(500) NOT NULL,
  `ad` varchar(5000) NOT NULL,
  `adpass` int(10) NOT NULL,
  `adimg` varchar(5000) NOT NULL,
  `adimglink` varchar(5000) NOT NULL,
  `adaimg` text NOT NULL,
  `imagnum` int(10) NOT NULL,
  `adiamglink` text NOT NULL,
  `day` int(10) NOT NULL,
  `hot` int(10) NOT NULL,
  `sharenum` int(10) NOT NULL,
  `viewnum` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xhw_picvote_log`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xhw_picvote_log`;
CREATE TABLE `ims_xhw_picvote_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL COMMENT '项目ID',
  `openid` varchar(100) CHARACTER SET utf8 NOT NULL,
  `numid` int(10) NOT NULL COMMENT '被投票ID',
  `time` int(20) NOT NULL,
  `ip` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `ims_xhw_picvote_reg`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xhw_picvote_reg`;
CREATE TABLE `ims_xhw_picvote_reg` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL COMMENT '项目ID',
  `weid` int(10) NOT NULL,
  `title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `openid` varchar(500) CHARACTER SET utf8 NOT NULL,
  `nickname` varchar(100) CHARACTER SET utf8 NOT NULL,
  `avatar` varchar(500) CHARACTER SET utf8 NOT NULL,
  `phone` text CHARACTER SET utf8 NOT NULL,
  `pass` int(10) NOT NULL,
  `num` int(11) NOT NULL COMMENT '赞',
  `sharenum` int(10) NOT NULL COMMENT '朋友圈浏览次数',
  `time` int(12) NOT NULL,
  `img` varchar(5000) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`(333))
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `ims_xhw_picvote_setting`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xhw_picvote_setting`;
CREATE TABLE `ims_xhw_picvote_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `jssdkpass` int(10) NOT NULL,
  `openidpass` int(10) NOT NULL,
  `followpass` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `ims_xhw_voice`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xhw_voice`;
CREATE TABLE `ims_xhw_voice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `weid` int(10) NOT NULL,
  `title` varchar(200) NOT NULL,
  `photo` varchar(200) NOT NULL,
  `smalltext` varchar(300) NOT NULL,
  `share_title` varchar(500) NOT NULL,
  `share_desc` varchar(500) NOT NULL,
  `follow_url` varchar(300) NOT NULL,
  `rule_url` varchar(500) NOT NULL,
  `submit_url` varchar(500) NOT NULL,
  `rules_url` varchar(500) NOT NULL,
  `starttime` int(20) NOT NULL,
  `endtime` int(20) NOT NULL,
  `logo` varchar(500) NOT NULL,
  `mynum` int(10) NOT NULL COMMENT '投票上限',
  `cnzz` varchar(500) NOT NULL,
  `adpic` varchar(500) NOT NULL,
  `adlink` varchar(500) NOT NULL,
  `ad` varchar(5000) NOT NULL,
  `adpass` int(10) NOT NULL,
  `anum` int(10) NOT NULL,
  `bnum` int(10) NOT NULL,
  `adimg` varchar(5000) NOT NULL,
  `adimglink` varchar(5000) NOT NULL,
  `day` int(10) NOT NULL,
  `hot` int(10) NOT NULL,
  `sharenum` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xhw_voice_log`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xhw_voice_log`;
CREATE TABLE `ims_xhw_voice_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL COMMENT '项目ID',
  `openid` varchar(100) CHARACTER SET utf8 NOT NULL,
  `numid` int(10) NOT NULL COMMENT '被投票ID',
  `time` int(20) NOT NULL,
  `ip` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `ims_xhw_voice_reg`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xhw_voice_reg`;
CREATE TABLE `ims_xhw_voice_reg` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL COMMENT '项目ID',
  `weid` int(10) NOT NULL,
  `title` varchar(500) CHARACTER SET utf8 NOT NULL,
  `mediaid` varchar(500) CHARACTER SET utf8 NOT NULL,
  `mp3` varchar(500) CHARACTER SET utf8 NOT NULL,
  `openid` varchar(500) CHARACTER SET utf8 NOT NULL,
  `nickname` varchar(100) CHARACTER SET utf8 NOT NULL,
  `avatar` varchar(500) CHARACTER SET utf8 NOT NULL,
  `phone` text NOT NULL,
  `pass` int(10) NOT NULL,
  `num` int(11) NOT NULL COMMENT '赞',
  `sharenum` int(10) NOT NULL COMMENT '朋友圈浏览次数',
  `time` int(12) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`(333))
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `ims_xhw_voice_setting`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xhw_voice_setting`;
CREATE TABLE `ims_xhw_voice_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `appid` varchar(100) CHARACTER SET utf8 NOT NULL,
  `appsecret` varchar(100) CHARACTER SET utf8 NOT NULL,
  `accesskey` varchar(100) CHARACTER SET utf8 NOT NULL,
  `secretkey` varchar(100) CHARACTER SET utf8 NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `link` varchar(100) NOT NULL,
  `settingpass` int(10) NOT NULL,
  `openidpass` int(10) NOT NULL,
  `followpass` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `ims_xwz_queue_data`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xwz_queue_data`;
CREATE TABLE `ims_xwz_queue_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `typeid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `number` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `giveuptime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xwz_queue_fans`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xwz_queue_fans`;
CREATE TABLE `ims_xwz_queue_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `nickname` varchar(255) DEFAULT '' COMMENT '昵称',
  `headimgurl` varchar(255) DEFAULT '' COMMENT '头像',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态 -1 黑名单 0 正常',
  `suc` int(11) DEFAULT '0' COMMENT '取号次数',
  `past` int(11) DEFAULT '0' COMMENT '过号次数',
  `cancel` int(11) DEFAULT '0' COMMENT '取消次数',
  `createtime` int(11) DEFAULT '0' COMMENT '提交时间',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_rid` (`rid`),
  KEY `idx_status` (`status`),
  KEY `idx_createtime` (`createtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xwz_queue_manager`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xwz_queue_manager`;
CREATE TABLE `ims_xwz_queue_manager` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `username` varchar(100) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xwz_queue_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xwz_queue_reply`;
CREATE TABLE `ims_xwz_queue_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `thumb` varchar(200) DEFAULT '',
  `heading` varchar(255) DEFAULT '',
  `smallheading` varchar(255) DEFAULT '',
  `tel` varchar(255) DEFAULT '',
  `followurl` varchar(255) DEFAULT '',
  `intro` text,
  `status` tinyint(1) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `num` int(11) DEFAULT '0',
  `beforenum` int(11) DEFAULT '0',
  `screenbg` varchar(255) DEFAULT '',
  `qrcode` varchar(1000) DEFAULT '',
  `qrcodetype` tinyint(3) DEFAULT '0',
  `templateid` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_rid` (`rid`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_xwz_queue_type`
-- ----------------------------
DROP TABLE IF EXISTS `ims_xwz_queue_type`;
CREATE TABLE `ims_xwz_queue_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `tag` varchar(255) DEFAULT '',
  `title` varchar(255) DEFAULT '',
  `num` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_rid` (`rid`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_yhc_ecard`
-- ----------------------------
DROP TABLE IF EXISTS `ims_yhc_ecard`;
CREATE TABLE `ims_yhc_ecard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(50) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(300) DEFAULT NULL,
  `company` varchar(300) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `avatar` varchar(200) DEFAULT NULL,
  `ext` text,
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_yhc_ecard_collect`
-- ----------------------------
DROP TABLE IF EXISTS `ims_yhc_ecard_collect`;
CREATE TABLE `ims_yhc_ecard_collect` (
  `cardid` int(11) NOT NULL,
  `openid` varchar(50) NOT NULL,
  PRIMARY KEY (`cardid`,`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_yhc_ecard_pocket`
-- ----------------------------
DROP TABLE IF EXISTS `ims_yhc_ecard_pocket`;
CREATE TABLE `ims_yhc_ecard_pocket` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(50) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(300) DEFAULT NULL,
  `company` varchar(300) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `avatar` varchar(200) DEFAULT NULL,
  `remote` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_yhc_onenavi`
-- ----------------------------
DROP TABLE IF EXISTS `ims_yhc_onenavi`;
CREATE TABLE `ims_yhc_onenavi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  `lat` varchar(20) DEFAULT NULL COMMENT '坐标经度',
  `lng` varchar(20) DEFAULT NULL COMMENT '坐标维度',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_yobydashan_friend`
-- ----------------------------
DROP TABLE IF EXISTS `ims_yobydashan_friend`;
CREATE TABLE `ims_yobydashan_friend` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) DEFAULT NULL,
  `fromuser` varchar(60) DEFAULT NULL COMMENT '我的id',
  `wid` varchar(10) DEFAULT NULL COMMENT '朋友id',
  `yname` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_yobydashan_sms`
-- ----------------------------
DROP TABLE IF EXISTS `ims_yobydashan_sms`;
CREATE TABLE `ims_yobydashan_sms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(144) DEFAULT NULL COMMENT '字数',
  `createtime` int(10) DEFAULT NULL,
  `fromuser` varchar(64) DEFAULT NULL,
  `touser` varchar(64) DEFAULT NULL,
  `isread` tinyint(1) DEFAULT '0',
  `weid` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_yobydashan_user`
-- ----------------------------
DROP TABLE IF EXISTS `ims_yobydashan_user`;
CREATE TABLE `ims_yobydashan_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) DEFAULT NULL COMMENT '微信编号',
  `wid` varchar(10) DEFAULT NULL COMMENT '聊天编号',
  `sex` tinyint(1) DEFAULT '0',
  `yname` varchar(30) DEFAULT NULL COMMENT '姓名',
  `xi` varchar(100) DEFAULT NULL COMMENT '年级与系别',
  `fromuser` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_zam_chatlog`
-- ----------------------------
DROP TABLE IF EXISTS `ims_zam_chatlog`;
CREATE TABLE `ims_zam_chatlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(100) NOT NULL,
  `toopenid` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `tousername` varchar(100) NOT NULL,
  `content` varchar(300) NOT NULL,
  `createtime` int(12) NOT NULL,
  `weid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_zam_cookie`
-- ----------------------------
DROP TABLE IF EXISTS `ims_zam_cookie`;
CREATE TABLE `ims_zam_cookie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cookie` text NOT NULL,
  `cookies` text NOT NULL,
  `token` int(11) NOT NULL,
  `weid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `ims_zam_userinfo`
-- ----------------------------
DROP TABLE IF EXISTS `ims_zam_userinfo`;
CREATE TABLE `ims_zam_userinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(255) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `avatar` text NOT NULL,
  `sex` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `province` varchar(20) NOT NULL,
  `city` varchar(20) NOT NULL,
  `area` varchar(20) NOT NULL,
  `banji` varchar(50) NOT NULL,
  `createtime` int(12) NOT NULL,
  `jointime` int(12) NOT NULL,
  `fakeid` varchar(100) NOT NULL,
  `msgid` varchar(12) NOT NULL,
  `chattime` int(100) NOT NULL,
  `isblacklist` tinyint(1) NOT NULL,
  `weid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_zerobuy_code`
-- ----------------------------
DROP TABLE IF EXISTS `ims_zerobuy_code`;
CREATE TABLE `ims_zerobuy_code` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `did` int(10) unsigned NOT NULL COMMENT '活动详情ID',
  `uid` int(10) unsigned NOT NULL,
  `jointime` int(10) NOT NULL COMMENT '参与时间',
  `code` char(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='参与号码表';

-- ----------------------------
--  Table structure for `ims_zerobuy_detail`
-- ----------------------------
DROP TABLE IF EXISTS `ims_zerobuy_detail`;
CREATE TABLE `ims_zerobuy_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `lid` int(10) unsigned NOT NULL COMMENT '商品ID',
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `title` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1-即将进行，2-正在进行，3-已结束，未开奖，4-已开奖，5-已开奖，无人中奖',
  `starttime` int(10) NOT NULL,
  `endtime` int(10) NOT NULL,
  `zerobuy_price` varchar(10) NOT NULL,
  `join_num` int(10) unsigned NOT NULL COMMENT '参与人数',
  `exchange` smallint(4) unsigned NOT NULL COMMENT '积分兑换比例',
  `draw_code` char(5) NOT NULL COMMENT '开奖码',
  `win_code` char(5) NOT NULL COMMENT '中奖码',
  `winner_uid` int(10) unsigned NOT NULL COMMENT '中奖用户ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='活动详情';

-- ----------------------------
--  Table structure for `ims_zerobuy_list`
-- ----------------------------
DROP TABLE IF EXISTS `ims_zerobuy_list`;
CREATE TABLE `ims_zerobuy_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `price` varchar(10) NOT NULL,
  `use_nums` int(11) NOT NULL COMMENT '活动次数',
  `thumb` varchar(100) NOT NULL,
  `info` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `inventory` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='活动商品清单';

-- ----------------------------
--  Table structure for `ims_zerobuy_rule`
-- ----------------------------
DROP TABLE IF EXISTS `ims_zerobuy_rule`;
CREATE TABLE `ims_zerobuy_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `rule` text NOT NULL,
  `rule_draw` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-相对规则，1-绝对规则',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='活动规则';

-- ----------------------------
--  Table structure for `ims_zzz_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ims_zzz_reply`;
CREATE TABLE `ims_zzz_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `uniacid` int(10) unsigned NOT NULL,
  `picture` varchar(100) NOT NULL COMMENT '活动图片',
  `description` text NOT NULL COMMENT '活动描述',
  `rule` text NOT NULL COMMENT '活动描述',
  `periodlottery` smallint(10) unsigned NOT NULL DEFAULT '1' COMMENT '0为无周期',
  `maxlottery` tinyint(3) unsigned NOT NULL COMMENT '系统每天赠送次数',
  `guzhuurl` varchar(255) NOT NULL DEFAULT '',
  `prace_times` int(10) NOT NULL DEFAULT '100',
  `title` varchar(100) NOT NULL DEFAULT '',
  `bgurl` varchar(255) NOT NULL DEFAULT '',
  `bigunit` varchar(50) NOT NULL DEFAULT '',
  `smallunit` varchar(50) NOT NULL DEFAULT '',
  `start_time` int(10) NOT NULL DEFAULT '0',
  `end_time` int(10) NOT NULL DEFAULT '1600000000',
  `sharevalue` int(10) unsigned NOT NULL COMMENT '分享赠送体力',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_zzz_share`
-- ----------------------------
DROP TABLE IF EXISTS `ims_zzz_share`;
CREATE TABLE `ims_zzz_share` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `fanid` int(10) unsigned NOT NULL COMMENT '粉丝ID',
  `sharefid` int(10) unsigned NOT NULL COMMENT '分享者ID',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`,`fanid`,`sharefid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_zzz_user`
-- ----------------------------
DROP TABLE IF EXISTS `ims_zzz_user`;
CREATE TABLE `ims_zzz_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL DEFAULT '0',
  `fanid` int(10) unsigned NOT NULL COMMENT '粉丝ID',
  `count` int(10) NOT NULL DEFAULT '0',
  `points` int(10) NOT NULL DEFAULT '0',
  `friendcount` int(10) NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL COMMENT '日期',
  `sharevalue` int(10) unsigned NOT NULL COMMENT '分享获得体力',
  PRIMARY KEY (`id`),
  KEY `idx_fanid` (`fanid`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `ims_zzz_winner`
-- ----------------------------
DROP TABLE IF EXISTS `ims_zzz_winner`;
CREATE TABLE `ims_zzz_winner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `point` int(10) unsigned NOT NULL DEFAULT '0',
  `fanid` int(10) unsigned NOT NULL COMMENT '粉丝ID',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未领奖，1不需要领奖，2已领奖',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '获奖日期',
  PRIMARY KEY (`id`),
  KEY `idx_fanid` (`fanid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `sv_dpt`
-- ----------------------------
DROP TABLE IF EXISTS `sv_dpt`;
CREATE TABLE `sv_dpt` (
  `sv_dpt_id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `acid` int(10) NOT NULL,
  `sv_dpt_name` varchar(255) NOT NULL,
  `sv_dpt_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`sv_dpt_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `sv_qr`
-- ----------------------------
DROP TABLE IF EXISTS `sv_qr`;
CREATE TABLE `sv_qr` (
  `sv_qr_id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `acid` int(10) NOT NULL,
  `dptid` int(10) NOT NULL,
  `videoid` int(10) NOT NULL,
  `scancount` int(10) DEFAULT '0',
  PRIMARY KEY (`sv_qr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `sv_videos`
-- ----------------------------
DROP TABLE IF EXISTS `sv_videos`;
CREATE TABLE `sv_videos` (
  `sv_video_id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `acid` int(10) NOT NULL,
  `sv_video_name` varchar(255) NOT NULL,
  `sv_video_code` varchar(255) NOT NULL,
  `sv_video_time` int(10) NOT NULL,
  PRIMARY KEY (`sv_video_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

