<?php
global $_W,$_GPC;
$title = '联盟便民导航';
/* id uniacid code title */
$data = pdo_fetchall("SELECT title,url FROM ".tablename('meepo_danmu_data')." WHERE uniacid = '{$_W['uniacid']}' AND status = 1"); 

$articleList = json_encode($data);

include $this->template('index');

