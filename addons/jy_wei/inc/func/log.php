<?php
$data['positionid'] = intval($position['id']);
$data['companyid'] = intval($company['id']);
$data['uid'] = $_W['member']['uid'];
$data['uniacid'] = $_W['uniacid'];
$result = pdo_insert('jy_wei_log',$data);
?>