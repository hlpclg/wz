<?php

/**
 * 调研模块微站定义
 *
 * @author WeiZan System
 * @url http://bbs.012wz.com
 */
defined('IN_IA') or exit('Access Denied');

class We7_surveyModuleSite extends WeModuleSite {

    public function getHomeTiles() {
        global $_W;
        $urls = array();
        $list = pdo_fetchall("SELECT title, sid FROM " . tablename('survey') . " WHERE weid = '{$_W['uniacid']}'");
        if (!empty($list)) {
            foreach ($list as $row) {
                $urls[] = array('title' => $row['title'], 'url' =>$_W['siteroot']."app/".$this->createMobileUrl('survey', array('id' => $row['sid'])));
            }
        }
        return $urls;
    }

    // 调研活动搜索查询
    public function doWebQuery() {
        global $_W, $_GPC;
        $kwd = $_GPC['keyword'];
        $sql = 'SELECT * FROM ' . tablename('survey') . ' WHERE `weid`=:weid AND status = :status AND `title` LIKE :title ORDER BY sid DESC LIMIT 0,8';
        $params = array();
        $params[':weid'] = $_W['uniacid'];
        $params[':title'] = "%{$kwd}%";
        $params[':status'] = 1;
        $ds = pdo_fetchall($sql, $params);
        foreach ($ds as &$row) {
            $r = array();
            $r['title'] = $row['title'];
            $r['description'] = cutstr(strip_tags($row['description']), 50);
            $r['thumb'] = $row['thumb'];
            $r['sid'] = $row['sid'];
            $row['entry'] = $r;
        }
        include $this->template('query');
    }

    public function doWebDetail() {
        global $_W, $_GPC;
        $srid = intval($_GPC['id']);
        $sql = 'SELECT * FROM ' . tablename('survey_rows') . " WHERE `srid`=:srid";
        $params = array();
        $params[':srid'] = $srid;
        $row = pdo_fetch($sql, $params);
        load()->model('mc');
        $user = mc_fetch($row['openid'], array('realname', 'mobile'));
        $row['realname'] = $user['realname'];
        $row['mobile'] = $user['mobile'];
        if (empty($row)) {
            message('访问非法.');
        }
        $sql = 'SELECT * FROM ' . tablename('survey') . ' WHERE `weid`=:weid AND `sid`=:sid';
        $params = array();
        $params[':weid'] = $_W['uniacid'];
        $params[':sid'] = $row['sid'];
        $activity = pdo_fetch($sql, $params);
        if (empty($activity)) {
            message('非法访问.');
        }
        $sql = 'SELECT * FROM ' . tablename('survey_fields') . ' WHERE `sid`=:sid ORDER BY `sfid`';
        $params = array();
        $params[':sid'] = $row['sid'];
        $fields = pdo_fetchall($sql, $params);
        if (empty($fields)) {
            message('非法访问.');
        }
        $ds = array();
        $fids = array();
        foreach ($fields as $f) {
            $ds[$f['sfid']]['fid'] = $f['title'];
            $ds[$f['sfid']]['type'] = $f['type'];
            $fids[] = $f['sfid'];
        }

        $fids = implode(',', $fids);
        $row['fields'] = array();
        $sql = 'SELECT * FROM ' . tablename('survey_data') . " WHERE `sid`=:sid AND `srid`='{$row['srid']}' AND `sfid` IN ({$fids})";
        $fdatas = pdo_fetchall($sql, $params);
        foreach ($fdatas as $fd) {
            if ($ds[$fd['sfid']]['type'] == 'checkbox') {
                $a[$fd['sfid']][] = $fd['data'];
                $row['fields'][$fd['sfid']] = implode(',', $a[$fd['sfid']]);
            } else {
                $row['fields'][$fd['sfid']] = $fd['data'];
            }
        }

        include $this->template('detail');
    }

    public function doWebManage() {
        global $_W, $_GPC;
        $sid = intval($_GPC['id']);
        $sql = 'SELECT * FROM ' . tablename('survey') . ' WHERE `weid`=:weid AND `sid`=:sid';
        $params = array();
        $params[':weid'] = $_W['uniacid'];
        $params[':sid'] = $sid;
        $activity = pdo_fetch($sql, $params);
        if (empty($activity)) {
            message('非法访问.');
        }
        $sql = 'SELECT * FROM ' . tablename('survey_fields') . ' WHERE `sid`=:sid ORDER BY displayorder,sfid';
        $params = array();
        $params[':sid'] = $sid;
        $fields = pdo_fetchall($sql, $params);
        if (empty($fields)) {
            message('非法访问.');
        }
        $ds = array();
        foreach ($fields as $f) {
            $ds[$f['sfid']] = $f['title'];
        }

        $starttime = !is_array($_GPC['daterange']) ? strtotime('-1 month') : strtotime($_GPC['daterange']['start']);
        $endtime = !is_array($_GPC['daterange'])? TIMESTAMP : strtotime($_GPC['daterange']['end']) + 86399;
        $select = array();
        if (!empty($_GPC['select'])) {
            foreach ($_GPC['select'] as $field) {
                if (isset($ds[$field])) {
                    $select[] = $field;
                }
            }
        }
        $sfid = implode(',', $select);
        if (!empty($sfid)) {
            $datas = pdo_fetchall("select * from " . tablename('survey_fields') . " WHERE `sid`=:sid AND `sfid` IN ({$sfid})", array(':sid' => $sid));
        } else {
            $datas = pdo_fetchall("select * from " . tablename('survey_fields') . " WHERE `sid`=:sid AND (`type`='checkbox' OR `type`='radio')", array(':sid' => $sid));
        }
        foreach ($datas as $key => $field) {
            $sql = 'SELECT COUNT(*) FROM ' . tablename('survey_data') . " WHERE `sfid` = :sfid";
            $total = pdo_fetchcolumn($sql, array(':sfid' => $field['sfid']));
            $datas[$key]['title'] = "'" . $field['title'] . "'";
            if (in_array($field['type'], array('radio', 'checkbox'))) {
                $value = explode("\r\n", $field['value']);
                foreach ($value as $val) {
                    $sql = "SELECT COUNT(*) FROM " . tablename('survey_data') . " WHERE `sid`=:sid AND `sfid`=:sfid AND `createtime` > {$starttime}
                            AND `createtime` < {$endtime} AND `data` = :data";
                    $params = array(':sfid' => $field['sfid'], ':sid' => $sid, ':data' => $val);
                    $num = pdo_fetchcolumn($sql, $params);
                    if ($field['type'] == 'radio') {
                        $datas[$key]['str'] .= $a . "\"" . $val . "({$num}人)" . "\"";
                    } else {
                        $datas[$key]['str'] .= $a . "\"" . $val . "({$num}次)" . "\"";
                    }
                    $datas[$key]['values'][] = $val;
                    $datas[$key]['nums'] .= ($total == 0 ? 0 : $a . (round($num / $total * 100, 2)));
                    $a = ',';
                }
                $a = '';
            }
        }
        include $this->template('manage');
    }

    public function doWebManagelist() {
        global $_W, $_GPC;
        $sid = intval($_GPC['id']);
        $sql = 'SELECT * FROM ' . tablename('survey') . ' WHERE `weid` = :weid AND `sid` = :sid';
        $params = array(':sid' => $sid);
        $params[':weid'] = $_W['uniacid'];
        $activity = pdo_fetch($sql, $params);
        if (empty($activity)) {
            message('非法访问.');
        }

        $sql = 'SELECT * FROM ' . tablename('survey_fields') . ' WHERE `sid` = :sid ORDER BY `displayorder`, `sfid`';
        $params = array(':sid' => $sid);
        $fields = pdo_fetchall($sql, $params, 'sfid');
        if (empty($fields)) {
            message('非法访问.');
        }

        $ds = array();
        foreach ($fields as $f) {
            $ds[$f['sfid']] = $f['title'];
        }
        $starttime = !is_array($_GPC['daterange']) ? strtotime('-1 month') : strtotime($_GPC['daterange']['start']);
        $endtime = !is_array($_GPC['daterange'])? TIMESTAMP : strtotime($_GPC['daterange']['end']) + 86399;

        $select = array();
        if (!empty($_GPC['select'])) {
            foreach ($_GPC['select'] as $field) {
                if (isset($ds[$field])) {
                    $select[] = $field;
                }
            }
        }
        if (!empty($_GPC['export'])) {
            $select = array_keys($fields);
        }
        $sta = array();
        $tableHeader = array('realname' => '姓名', 'mobile' => '手机号');
        foreach ($fields as $f) {
            $sta[$f['sfid']]['type'] = $f['type'];
            $tableHeader[$f['sfid']] = $f['title'];
        }
        $tableHeader['suggest'] = '意见和建议';

        $pindex = max(1, intval($_GPC['page']));
        $psize = 15;
        $sql = 'SELECT COUNT(*) FROM ' . tablename('survey_rows') . ' WHERE `sid` = :sid AND `createtime` > :starttime
                AND `createtime` < :endtime';
        $params = array(':sid' => $sid, ':starttime' => $starttime, ':endtime' => $endtime);
        $total = pdo_fetchcolumn($sql, $params);
        if (!empty($total)) {
            $sql = 'SELECT * FROM ' . tablename('survey_rows') . ' WHERE `sid` = :sid AND `createtime` > :starttime AND
                    `createtime` < :endtime ORDER BY `createtime` DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
            $list = pdo_fetchall($sql, $params);
            $pager = pagination($total, $pindex, $psize);
        }
        // 添加lists数组, 取回全部数据, 并非一页数据.
        $sql = 'SELECT * FROM' . tablename('survey_rows') . 'WHERE `sid` = :sid';
        $params = array(':sid' => $sid);
        $lists = pdo_fetchall($sql, $params);
        
        load()->model('mc');
        foreach ($lists as &$value) {
            $member = mc_fetch($value['openid'], array('nickname', 'realname', 'mobile'));
            $value['realname'] = $member['realname'];
            $value['mobile'] = $member['mobile'];
            $value['nickname'] = $member['nickname'];
            //$sql = 'SELECT `nickname` FROM ' . tablename('mc_mapping_fans') . ' WHERE `openid`  = :openid';
            //$value['nickname'] = pdo_fetchcolumn($sql, array(':openid' => $value['openid']));
        }

        if (!empty($select)) {
            $fids = implode(',', $select);
            $params = array(':sid' => $sid);
            foreach ($lists as &$value) {
                $value['fields'] = array();
                $sql = 'SELECT * FROM ' . tablename('survey_data') . ' WHERE `sid` = :sid AND `srid` = :srid AND `sfid`
                        IN (' . $fids . ')';
                $params[':srid'] = $value['srid'];
                $fdatas = pdo_fetchall($sql, $params);
                foreach ($fdatas as $fd) {
                    if ($sta[$fd['sfid']]['type'] == 'checkbox') {
                        $a[$fd['srid']][$fd['sfid']][] = $fd['data'];
                        $value['fields'][$fd['sfid']] = implode(' ', $a[$fd['srid']][$fd['sfid']]);
                    } else {
                        $value['fields'][$fd['sfid']] = $fd['data'];
                    }
                }
            }
        }

        if (!empty($_GPC['export'])) {
            $tablelength = count($tableHeader) + 1;

            /* 输入到CSV文件 */
            $html = "\xEF\xBB\xBF";

            /* 输出表头 */
            foreach ($tableHeader as $header) {
                $html .= $header . "\t ,";
            }
            $html .= "创建时间\t ,\n";

            /* 输出内容 */
            foreach ($lists as $data) {
                foreach ($tableHeader as $key => $header) {
                    if (is_numeric($key)) {
                        $html .= $data['fields'][$key] . "\t ,";
                    } else {
                        $html .= $data[$key] . "\t ,";
                    }
                }
                $html .= date('Y-m-d H:i:s', $value['createtime']) . "\t ,";
                $html .= "\n";
            }

            /* 输出CSV文件 */
            header("Content-type:text/csv");
            header("Content-Disposition:attachment; filename=全部数据.csv");
            echo $html;
            exit();
        }

        include $this->template('managelist');
    }

    public function doWebDisplay() {
        global $_W, $_GPC;
        if ($_W['ispost']) {
            $sid = intval($_GPC['sid']);
            $switch = intval($_GPC['switch']);
            $sql = 'UPDATE ' . tablename('survey') . ' SET `status`=:status WHERE `sid`=:sid';
            $params = array();
            $params[':status'] = $switch;
            $params[':sid'] = $sid;
            pdo_query($sql, $params);
            exit();
        }
        
        $keyword = trim($_GPC['keyword']);
        $sta = isset($_GPC['status']) ? intval($_GPC['status']) : 1;
        if (empty($keyword)) {
            $sql = 'SELECT * FROM ' . tablename('survey') . " WHERE `weid`=:weid AND `status`=:status";
        } else {
            $sql = 'SELECT * FROM ' . tablename('survey') . " WHERE `weid`=:weid AND `status`=:status AND `title` LIKE '%{$keyword}%'";
        }
        $ds = pdo_fetchall($sql, array(':weid' => $_W['uniacid'], ':status' => $sta));
        foreach ($ds as &$item) {
            $item['isstart'] = $item['starttime'] > 0;
            $item['switch'] = $item['status'];
            $item['link'] = $_W['siteroot']."app/".$this->createMobileUrl('survey', array('id' => $item['sid']));
        }
        include $this->template('display');
       
    }

    public function doWebDelete() {
        global $_W, $_GPC;
        $sid = intval($_GPC['id']);
        if ($sid > 0) {
            $params = array();
            $params[':sid'] = $sid;
            $sql = 'DELETE FROM ' . tablename('survey') . ' WHERE `sid`=:sid';
            pdo_query($sql, $params);
            $sql = 'DELETE FROM ' . tablename('survey_rows') . ' WHERE `sid`=:sid';
            pdo_query($sql, $params);
            $sql = 'DELETE FROM ' . tablename('survey_fields') . ' WHERE `sid`=:sid';
            pdo_query($sql, $params);
            $sql = 'DELETE FROM ' . tablename('survey_data') . ' WHERE `sid`=:sid';
            pdo_query($sql, $params);
            $sql = 'DELETE FROM ' . tablename('survey_reply') . ' WHERE `sid`=:sid';
            pdo_query($sql, $params);
            message('操作成功.', referer());
        }
        message('非法访问.');
    }

    public function doWebSurveyDelete() {
        global $_W, $_GPC;
        $id = intval($_GPC['id']);
        if (!empty($id)) {
            pdo_delete('survey_rows', array('srid' => $id));
            pdo_delete('survey_data', array('srid' => $id));
        }
        message('操作成功.', referer());
    }

    public function doWebPost() {
        global $_W, $_GPC;
        $sid = intval($_GPC['id']); //调研id
        $hasData = false;
        if ($sid) {
            $sql = 'SELECT COUNT(*) FROM ' . tablename('survey_rows') . ' WHERE `sid`=' . $sid;
            if (pdo_fetchcolumn($sql) > 0) {
                $hasData = true;
            }
        }
        if (checksubmit()) {
            $recrod = array();
            $recrod['title'] = trim($_GPC['title']) ? trim($_GPC['title']) : message('请填写调研标题.');
            $recrod['weid'] = $_W['uniacid'];
            $recrod['description'] = trim($_GPC['description']) ? trim($_GPC['description']) : message('请填写调研简介.');
            $recrod['content'] = trim($_GPC['content']) ? trim($_GPC['content']) : message('请填写调研内容.');
            $recrod['information'] = trim($_GPC['information']) ? trim($_GPC['information']) : message('请填写调研提交成功提示信息.');
            $recrod['thumb'] = trim($_GPC['thumb']);
            $recrod['pertotal'] = intval($_GPC['pertotal']) ? intval($_GPC['pertotal']) : 1;
            $recrod['status'] = intval($_GPC['status']);
            $recrod['suggest_status'] = intval($_GPC['suggest_status']);
            $recrod['inhome'] = intval($_GPC['inhome']);
            $recrod['starttime'] = strtotime($_GPC['starttime']);
            $recrod['endtime'] = strtotime($_GPC['endtime']);
            if (empty($sid)) {
                $recrod['status'] = 1;
                $recrod['createtime'] = TIMESTAMP;
                pdo_insert('survey', $recrod);
                $sid = pdo_insertid();
                if (!$sid) {
                    message('保存调研失败, 请稍后重试.', 'error');
                }
            } else {
                if (pdo_update('survey', $recrod, array('sid' => $sid)) === false) {
                    message('保存调研失败, 请稍后重试.');
                }
            }
            if (!$hasData) {
                $sql = 'DELETE FROM ' . tablename('survey_fields') . ' WHERE `sid`=:sid';
                $params = array();
                $params[':sid'] = $sid;
                pdo_query($sql, $params);
                foreach ($_GPC['titles'] as $k => $v) {
                    $field = array();
                    $field['sid'] = $sid;
                    $field['title'] = trim($v);
                    $field['type'] = $_GPC['type'][$k];
                    $field['essential'] = intval($_GPC['essentials'][$k]);
                    $field['value'] = trim($_GPC['options'][$k]);
                    $field['value'] = urldecode($field['value']);
                    $field['description'] = $_GPC['descriptions'][$k];
                    $field['displayorder'] = intval($_GPC['displayorder'][$k]);
                    pdo_insert('survey_fields', $field);
                }
            }
            message('保存调研成功.', $this->createWebUrl('display', array('id' => $row['sid'])));
        }

        $types = array();
        $types['textarea'] = '文本(textarea)';
        $types['radio'] = '单选(radio)';
        $types['checkbox'] = '多选(checkbox)';

        if ($sid) {
            $sql = 'SELECT * FROM ' . tablename('survey') . ' WHERE `weid`=:weid AND `sid`=:sid';
            $params = array();
            $params[':weid'] = $_W['uniacid'];
            $params[':sid'] = $sid;
            $activity = pdo_fetch($sql, $params);
            $activity['starttime'] && $activity['starttime'] = date('Y-m-d H:i:s', $activity['starttime']);
            $activity['endtime'] && $activity['endtime'] = date('Y-m-d H:i:s', $activity['endtime']);

            if ($activity) {
                $sql = 'SELECT * FROM ' . tablename('survey_fields') . ' WHERE `sid` = :sid ORDER BY displayorder ASC,sfid ASC';
                $params = array();
                $params[':sid'] = $sid;
                $ds = pdo_fetchall($sql, $params);
            }
        }
        include $this->template('post');
    }

    public function doMobileSurvey() {
        checkauth();
        global $_W, $_GPC;
        $sid = intval($_GPC['id']);
        $sql = 'SELECT * FROM ' . tablename('survey') . ' WHERE `weid`=:weid AND `sid`=:sid';
        $params = array();
        $params[':weid'] = $_W['uniacid'];
        $params[':sid'] = $sid;
        $activity = pdo_fetch($sql, $params);
        $activity['content'] = htmlspecialchars_decode($activity['content']);
        $title = $activity['title'];
        //分享处理
        $_share_img = $_W['attachurl'] . $activity['thumb'];
        if ($activity['status'] != '1') {
            message('当前调研活动已经停止.');
        }
        if (!$activity) {
            message('非法访问.');
        }
        if ($activity['starttime'] > TIMESTAMP) {
            message('当前调研活动还未开始！');
        }
        if ($activity['endtime'] < TIMESTAMP) {
            message('当前调研活动已经结束！');
        }
        $sql = 'SELECT * FROM ' . tablename('survey_fields') . ' WHERE `sid`=:sid ORDER BY `displayorder` ASC,sfid ASC';
        $params = array();
        $params[':sid'] = $sid;
        $ds = pdo_fetchall($sql, $params);
        if (!$ds) {
            message('非法访问.');
        }
        $pertotal = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('survey_rows') . " WHERE sid = :sid AND openid = :openid", array(':sid' => $sid, ':openid' => $_W['fans']['from_user']));
        if ($pertotal >= $activity['pertotal']) {
            $pererror = 1;
        }
        $user = mc_fetch($_W['fans']['from_user'], array('realname', 'mobile'));
        if (empty($user['realname']) || empty($user['mobile'])) {
            $userinfo = 0;
        }
        if (checksubmit()) {
            if ($pertotal >= $activity['pertotal']) {
                message('抱歉!每人只能提交' . $activity['pertotal'] . "次！", referer(), 'error');
            }
            //更新粉丝的手机号和姓名
            if ($userinfo == '0') {
                mc_update($_W['fans']['from_user'], array('realname' => trim($_GPC['username']), 'mobile' => trim($_GPC['telephone'])));
            }
            $row = array();
            $row['sid'] = $sid;
            $row['openid'] = $_W['fans']['from_user'];
            $row['suggest'] = trim($_GPC['suggest']);
            $row['createtime'] = TIMESTAMP;
            $datas = array();
            $fields = array();
            foreach ($ds as $r) {
                $fields[$r['sfid']] = $r;
            }
            foreach ($_GPC as $key => $value) {
                if (strexists($key, 'field_')) {
                    $sfid = intval(str_replace('field_', '', $key));
                    $field = $fields[$sfid];
                    if ($sfid && $field) {
                        if (in_array($field['type'], array('textarea', 'radio'))) {
                            $entry = array();
                            $entry['sid'] = $sid;
                            $entry['srid'] = 0;
                            $entry['sfid'] = $sfid;
                            $entry['createtime'] = TIMESTAMP;
                            $entry['data'] = strval($value);
                            $datas[] = $entry;
                        }
                        if (in_array($field['type'], array('checkbox'))) {
                            $value = explode("||", $value);
                            if (!is_array($value))
                                continue;
                            foreach ($value as $k => $v) {
                                $entry['sid'] = $sid;
                                $entry['srid'] = 0;
                                $entry['sfid'] = $sfid;
                                $entry['createtime'] = TIMESTAMP;
                                $entry['data'] = strval($v);
                                $datas[] = $entry;
                            }
                        }
                    }
                }
            }
            if (empty($datas)) {
                message('非法访问.', '', 'error');
            }
            if (pdo_insert('survey_rows', $row) != 1) {
                message('保存失败.');
            }
            $srid = pdo_insertid();
            if (empty($srid)) {
                message('保存失败.');
            }
            foreach ($datas as &$r) {
                $r['srid'] = $srid;
                pdo_insert('survey_data', $r);
            }
            if (empty($activity['starttime'])) {
                $record = array();
                $record['starttime'] = TIMESTAMP;
                pdo_update('survey', $record, array('sid' => $sid));
            }
            message($activity['information'], 'refresh');
        }
        foreach ($ds as &$r) {
            if ($r['value']) {
                $r['options'] = explode("\r\n", $r['value']);
            }
        }
        include $this->template('submit');
    }

    public function doMobileMysurvey() {
        global $_W, $_GPC;
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : '';
        if ($operation == 'display') {
            $rows = pdo_fetchall("SELECT * FROM " . tablename('survey_rows') . " WHERE openid = :openid", array(':openid' => $_W['fans']['from_user']));
            if (!empty($rows)) {
                foreach ($rows as $row) {
                    $sids[$row['sid']] = $row['sid'];
                }
                $survey = pdo_fetchall("SELECT * FROM " . tablename('survey') . " WHERE sid IN (" . implode(',', $sids) . ")", array(), 'sid');
            }
        } elseif ($operation == 'detail') {
            $id = intval($_GPC['id']);
            $row = pdo_fetch("SELECT * FROM " . tablename('survey_rows') . " WHERE openid = :openid AND rerid = :rerid", array(':openid' => $_W['fans']['from_user'], ':rerid' => $id));
            if (empty($row)) {
                message('我的预约不存在或是已经被删除！');
            }
            $survey = pdo_fetch("SELECT * FROM " . tablename('survey') . " WHERE sid = :sid", array(':sid' => $row['sid']));
            $survey['fields'] = pdo_fetchall("SELECT a.title, a.type, b.data FROM " . tablename('survey_fields') . " AS a LEFT JOIN " . tablename('survey_data') . " AS b ON a.refid = b.refid WHERE a.sid = :sid AND b.rerid = :rerid", array(':sid' => $row['sid'], ':rerid' => $id));
        }
        include $this->template('research');
    }

    public function doMobileMyResearch() {
    	global $_W, $_GPC;
    
    	$pindex = max(1, intval($_GPC['page']));
    	$psize = 3;
    
    	$weid = $_GPC['i'];
    	$sql = 'SELECT * FROM ' . tablename('survey') . ' WHERE `weid` = :weid';
    	$params = array(':weid' => $weid);
    	$research = pdo_fetchall($sql, $params);
    
    	// 还没发起过调研
    	if (empty($research)) {
    		message('您还没有没有任何调研记录', referer(), 'error');
    	}
    
    	// 否则, 构建调研记录数组.
    	$i = 0;
    	$result = array();
    	foreach ($research as $re) {
    		$result[$i] = array(
    				'index' => $i,
    				'title' => $re['title'],
    				'description' => $re['description'],
    				'starttime' => date('Y-m-d', $re['starttime']),
    				'endtime' => date('Y-m-d', $re['endtime']),
    		);
    		$i++;
    	}
    
    	// 构建分页数组
    	$page_arr = array();
    	for ($j = ($pindex - 1) * $psize; $j < $pindex * $psize; $j++) {
    		$page_arr[$j] = $result[$j];
    	}
    	$page_arr = array_filter($page_arr);
    
    	// i 表示记录总数.
    	$pager = pagination($i, $pindex, $psize);
    	include $this->template('myresearch');
    }
}
