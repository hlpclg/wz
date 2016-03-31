CREATE TABLE IF NOT EXISTS `sv_dpt` (
  `sv_dpt_id` int(11) NOT NULL auto_increment,
  `uniacid` int(10) NOT NULL,
  `acid` int(10) NOT NULL,
  `sv_dpt_name` varchar(255) NOT NULL,
  `sv_dpt_time` int(10) default NULL,
  PRIMARY KEY  (`sv_dpt_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `sv_qr` (
  `sv_qr_id` int(11) NOT NULL auto_increment,
  `uniacid` int(10) NOT NULL,
  `acid` int(10) NOT NULL,
  `dptid` int(10) NOT NULL,
  `videoid` int(10) NOT NULL,
  `scancount` int(10) default '0',
  PRIMARY KEY  (`sv_qr_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `sv_videos` (
  `sv_video_id` int(11) NOT NULL auto_increment,
  `uniacid` int(10) NOT NULL,
  `acid` int(10) NOT NULL,
  `sv_video_name` varchar(255) NOT NULL,
  `sv_video_code` varchar(255) NOT NULL,
  `sv_video_time` int(10) NOT NULL,
  PRIMARY KEY  (`sv_video_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

