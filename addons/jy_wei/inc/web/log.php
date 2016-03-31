<?php
$title = "日志分析";
// 展示

// 公司点击量
$sql = 'SELECT count(1) as count,c.name FROM '.tablename('jy_wei_log')." as l LEFT JOIN "
	.tablename('jy_wei_company')." as c ON l.companyid=c.id "
	."WHERE l.uniacid=:uniacid AND l.positionid=:positionid GROUP BY `companyid`";
$company = pdo_fetchall($sql,array(':uniacid'=>$_W['uniacid'],':positionid'=>0));

// 申请
$sql = 'SELECT count(1) as count,c.name FROM '.tablename('jy_wei_invitation')." as i LEFT JOIN "
	.tablename('jy_wei_company')." as c ON i.companyid=c.id "
	."WHERE i.uniacid=:uniacid GROUP BY `companyid`";
$invitation = pdo_fetchall($sql,array(':uniacid'=>$_W['uniacid']));

// 职位点击量
$sql = 'SELECT count(1) as count,p.name FROM '.tablename('jy_wei_invitation')." as i LEFT JOIN "
	.tablename('jy_wei_position')." as p ON i.positionid=p.id "
	."WHERE i.uniacid=:uniacid GROUP BY `positionid`";
$position = pdo_fetchall($sql,array(':uniacid'=>$_W['uniacid']));

include $this->template('log');
?>