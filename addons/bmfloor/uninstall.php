<?php
/**
 * 抢楼活动模块定义
 *
 * @author 美丽心情
 * @qq 513316788
 */

$res = pdo_fetchall("SELECT id FROM ".tablename('rule')." WHERE module = :module", array(':module' => 'bmfloor'));
if ($res) {
	foreach ($res as $row) {
		$sql = "DROP TABLE `ims_bmfloor_{$row['id']}`";	
		pdo_query($sql);
	}	
}
pdo_delete('rule', array('module' => 'bmfloor'));
pdo_delete('rule_keyword', array('module' => 'bmfloor'));
$sql =<<<EOF
DROP TABLE `ims_bmfloor_award`;
DROP TABLE `ims_bmfloor_member`;
DROP TABLE `ims_bmfloor_winner`;
DROP TABLE `ims_bmfloor`;
EOF;
pdo_run($sql);
