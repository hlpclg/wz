<?php
// 增加分享次数
if (!pdo_fieldexists('qiyue_qiuqian', 'sharenum')) {
    pdo_query("ALTER TABLE " . tablename('qiyue_qiuqian') . " ADD `sharenum` int(10) NOT NULL DEFAULT '0';");
}

// 增加浏览量
if (!pdo_fieldexists('qiyue_qiuqian', 'viewnum')) {
    pdo_query("ALTER TABLE " . tablename('qiyue_qiuqian') . " ADD `viewnum` int(10) NOT NULL DEFAULT '0';");
}

/*
 * 增加 morepic字段
 */
if (!pdo_fieldexists('qiyue_qiuqian', 'morepic')) {
    // 增加字段
    pdo_query("ALTER TABLE " . tablename('qiyue_qiuqian') . " ADD `morepic` mediumtext NOT NULL;");
}
if (pdo_fieldexists('qiyue_qiuqian', 'title') && pdo_fieldexists('qiyue_qiuqian', 'filename') && pdo_fieldexists('qiyue_qiuqian', 'myorder')) {
    // 索引使用过该模块的公众号
    $all = pdo_fetchall("SELECT * FROM " . tablename('qiyue_qiuqian') . " WHERE 1=1 GROUP BY uniacid ORDER BY id ASC");
    if ($all) {
        foreach ($all as $val) {
            // 索引该公众号的所有签文 组成 morepic
            $qian = pdo_fetchall("SELECT * FROM " . tablename('qiyue_qiuqian') . " WHERE uniacid=:uniacid ORDER BY myorder ASC", array(':uniacid' => $val['uniacid']));
            $f_exp = "::::::";
            $r_exp = PHP_EOL;
            $morepic = "";
            for ($i = 0; $i < count($qian); $i++) {
                //替换非法字符
                $name = str_replace($f_exp, "", $qian[$i]['filename']);
                $name = str_replace($r_exp, "", $name);
                $pic = str_replace($f_exp, "", $qian[$i]['filename']);
                $pic = str_replace($r_exp, "", $spic);
                if ($pic) {
                    $morepic .= $pic . $f_exp . $name . $r_exp;
                }
            }
            // 去掉最后的字符
            $morepic = substr($morepic, 0, strlen($morepic) - 2);
            // 填充morepic
            pdo_update('qiyue_qiuqian', array('morepic' => $morepic), array('uniacid' => $val['uniacid']));
            //删除其它多余的数据
            pdo_query("DELETE FROM " . tablename('qiyue_qiuqian') . " WHERE uniacid=:uniacid AND id<>:id", array(':uniacid' => $val['uniacid'], ':id' => $val['id']));
        }
    }
    // 删除字段
    pdo_query("ALTER TABLE " . tablename('qiyue_qiuqian') . " DROP `title`;");
    pdo_query("ALTER TABLE " . tablename('qiyue_qiuqian') . " DROP `filename`;");
    pdo_query("ALTER TABLE " . tablename('qiyue_qiuqian') . " DROP `myorder`;");
}
?>