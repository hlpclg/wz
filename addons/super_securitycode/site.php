<?php
/**
 * 超强防伪码模块微站定义
 *
 * @author 超级防伪码
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
@require_once('Excel/reader.php');
class Super_securitycodeModuleSite extends WeModuleSite
{
    public $cfg = array();
    public function __construct()
    {
        global $_W;
        $this->data   = 'super_securitycode_data_' . $_W['uniacid'];
        $this->moban  = 'super_securitycode_data_moban';
        $this->i_logs = 'super_securitycode_logs';
        $sql          = "CREATE TABLE IF NOT EXISTS " . tablename($this->data) . " LIKE " . tablename($this->moban);
        pdo_query($sql);
    }
    public function doMobileTest()
    {
        header("Location:http://mp.weixin.qq.com/s?__biz=MzA4MTUzODQwMA==&mid=204532048&idx=1&sn=9a6b58d8cf398954f1b44a63803605c7#rd");
    }
    public function doMobileIndex()
    {
        global $_GPC, $_W;
        $tourl = true;
        load()->model('mc');
        $info  = mc_oauth_userinfo($_W['acid']);
        $info2 = mc_fansinfo($info['openid'], $_W['acid']);
        if ($info2['follow'] == 1) {
            $tourl = false;
        }
        if (isset($_GPC['wd_code'])) {
            $title        = '防伪测试';
            $footer_off   = 1;
            $SecurityCode = $_GPC['wd_code'];
            $logs['code'] = $SecurityCode;
            $sql          = "SELECT * FROM " . tablename($this->data) . " WHERE code='{$SecurityCode}' LIMIT 1";
            $member       = pdo_fetch($sql);
            $states       = 0;
            if (!empty($member)) {
                if ($tourl) {
                    if (empty($member['tourl'])) {
                        $member['tourl'] = "http://www.baidu.com";
                    }
                    header('Location:' . $member['tourl']);
                }
                include $this->template('index');
            } else {
                echo '您查询的防伪码不存在，请核对后重试！';
            }
        } else {
            echo '您查询的防伪码不存在，请核对后重试！';
        }
        exit();
    }
    public function doMobileResult()
    {
        global $_GPC, $_W;
        if (isset($_GPC['wd_code'])) {
            $title        = '防伪验真';
            $footer_off   = 1;
            $SecurityCode = $_GPC['wd_code'];
            load()->model('mc');
            $openid         = $this->message['from'];
            $logs['openid'] = $openid;
            $logs['weid']   = $_W['uniacid'];
            $fans           = pdo_fetch("SELECT fanid,uid FROM " . tablename('mc_mapping_fans') . " WHERE `openid`='$openid' LIMIT 1");
            $uid            = '0';
            if ($fans['uid'] != '0') {
                $uid = $fans['uid'];
            } else {
                $uid = mc_update($uid, array(
                    'email' => md5($_W['openid']) . '@012wz.com'
                ));
                if (!empty($fans['fanid']) && !empty($uid)) {
                    pdo_update('mc_mapping_fans', array(
                        'uid' => $uid
                    ), array(
                        'fanid' => $fans['fanid']
                    ));
                }
            }
            $logs['code'] = $SecurityCode;
            $sql          = "SELECT * FROM " . tablename($this->data) . " WHERE code='{$SecurityCode}' LIMIT 1";
            $member       = pdo_fetch($sql);
            $states       = 0;
            if (!empty($member)) {
                if ($member['stime'] <= TIME()) {
                    $logs['status'] = '0';
                    $reply          = '您查询的防伪码已过期! ';
                } else {
                    $member['num'] = intval($member['num']) + 1;
                    $data          = array(
                        'num' => $member['num']
                    );
                    pdo_update($this->data, $data, array(
                        'id' => $member['id']
                    ));
                    $states         = 1;
                    $logs['status'] = '1';
                }
                if ($member['creditstatus'] == '0') {
                    mc_credit_update($uid, 'credit1', $member['creditnum'], array(
                        '1',
                        '防伪码自动增加积分，积分名称：' . $member['creditname']
                    ));
                    pdo_update($this->data, array(
                        'creditstatus' => '1'
                    ), array(
                        'id' => $member['id']
                    ));
                }
                $logs['createtime'] = time();
                pdo_insert('super_securitycode_logs', $logs);
                $sql     = "SELECT a.*,b.residecity,b.resideprovince FROM " . tablename("super_securitycode_logs") . " as a

                left JOIN ims_mc_mapping_fans as c on a.openid=c.openid
left join ims_mc_members as b on c.uid=b.uid
                WHERE a.code='{$SecurityCode}' and a.status=1 order by a.createtime DESC  LIMIT 0," . $member['num'];
                $loglist = pdo_fetchall($sql);
                include $this->template('index2');
            } else {
                $reply = '您查询的防伪码不存在，请核对后重试！';
            }
        } else {
            $reply = '您查询的防伪码不存在，请核对后重试！';
        }
        echo $reply;
        exit();
    }
    public function doWebList()
    {
        global $_GPC, $_W;
        load()->func('tpl');
        $pindex  = max(1, intval($_GPC['page']));
        $psize   = 20;
        $where   = "";
        $sStr    = $_GPC['sStr'];
        $code    = $_GPC['code'];
        $type    = $_GPC['sName'];
        $brand   = $_GPC['sBrand'];
        $spec    = $_GPC['sSpec'];
        $weight  = $_GPC['sWeight'];
        $factory = $_GPC['sFactory'];
        $remarks = $_GPC['sRemarks'];
        $openurl = $_W['siteroot'] . 'app/index.php?c=entry&do=index&m=super_securitycode&i=' . $_W['uniacid'];
        $openurl = urlencode($openurl);
        $arr     = false;
        if ($arr) {
            $url_info = json_decode($arr);
            $url_info = $url_info[0];
            $url_info = $url_info->url_short . '?';
        } else {
            $url_info = urldecode($openurl);
        }
        $creditname   = $_GPC['creditname'];
        $creditstatus = $_GPC['creditstatus'];
        if (!empty($code)) {
            $where .= " AND code = '$code'";
        }
        if (!empty($sStr)) {
            $where .= " AND code LIKE '$sStr%'";
        }
        if (!empty($type)) {
            $where .= " AND type LIKE '%$type%'";
        }
        if (!empty($brand)) {
            $where .= " AND brand LIKE '%$brand%'";
        }
        if (!empty($spec)) {
            $where .= " AND spec LIKE '%$spec%'";
        }
        if (!empty($weight)) {
            $where .= " AND weight = '$weight'";
        }
        if (!empty($factory)) {
            $where .= " AND factory LIKE '%$factory%'";
        }
        if (!empty($remarks)) {
            $where .= " AND remarks LIKE '%$remarks%'";
        }
        if (!empty($creditname)) {
            $where .= " AND creditname = '$creditname'";
        }
        if (!empty($creditstatus)) {
            $where .= " AND creditstatus = '$creditstatus'";
        }
        if (!empty($_GPC['createtime'])) {
            $c_s = strtotime($_GPC['createtime']['start']);
            $c_e = strtotime($_GPC['createtime']['end']);
            $where .= " AND createtime >= '$c_s' AND createtime <= '$c_e'";
        }
        if (empty($_GPC['createtime'])) {
            $c_s = time() - 86400 * 30;
            $c_e = time() + 84400;
        }
        if (!empty($_GPC['Deleteall']) && !empty($_GPC['select'])) {
            foreach ($_GPC['select'] as $k => $v) {
                pdo_delete($this->data, array(
                    'id' => $v
                ));
            }
            message('成功删除选中的防伪码！', referer(), 'success');
        }
        if (!empty($_GPC['Frozenall']) && !empty($_GPC['select'])) {
            foreach ($_GPC['select'] as $k => $v) {
                pdo_update($this->data, array(
                    'status' => 0
                ), array(
                    'id' => $v
                ));
            }
            message('成功冻结选中的防伪码！', referer(), 'success');
        }
        if (checksubmit('submit2')) {
            $title       = array(
                'ID',
                '防伪码',
                '产品名称',
                '产品品牌',
                '规格参数',
                '重量',
                '生产厂家',
                '备注',
                '积分类型',
                '积分数',
                '积分状态',
                '有效日期',
                '查询次数'
            );
            $arraydata[] = iconv("UTF-8", "GB2312//IGNORE", implode("\t", $title));
            $listall     = pdo_fetchall("SELECT *  from " . tablename($this->data) . " where status ='1' $where order by id asc");
            foreach ($listall as &$value) {
                $tmp_value   = array(
                    $value['id'],
                    $value['code'],
                    $value['type'],
                    $value['brand'],
                    $value['spec'],
                    $value['weight'],
                    $value['factory'],
                    $value['remarks'],
                    $value['creditname'],
                    $value['creditnum'],
                    ($value['creditstatus'] == 1) ? '已发放' : '未发放',
                    date('Y-m-d', $value['createtime']),
                    $value['num']
                );
                $arraydata[] = iconv("UTF-8", "GB2312//IGNORE", implode("\t", $tmp_value));
            }
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Content-Type: application/vnd.ms-execl');
            header('Content-Type: application/force-download');
            header('Content-Type: application/download');
            header('Content-Disposition: attachment; filename=' . date('Ymd') . '.xls');
            header('Content-Transfer-Encoding: binary');
            header('Pragma: no-cache');
            header('Expires: 0');
            echo implode('	
', $arraydata);
            exit();
        }
        if (checksubmit('download')) {
            $listall   = pdo_fetchall("SELECT *  from " . tablename($this->data) . " where status ='1' $where order by id asc");
            $qr_topath = ATTACHMENT_ROOT . '/qrcode';
            if (!is_dir($qr_topath)) {
                mkdir($qr_topath, 0777);
            }
            $timepath = TIMESTAMP;
            $topath   = $qr_topath . '/' . $timepath;
            if (!is_dir($topath)) {
                mkdir($topath, 0777);
            }
            require(IA_ROOT . '/framework/library/qrcode/phpqrcode.php');
            $errorCorrectionLevel = "L";
            $matrixPointSize      = "5";
            foreach ($listall as &$v) {
                $url = ($url_info . "&wd_code=" . $v['code']);
                QRcode::png($url, $topath . '/' . $v['code'] . '.png', $errorCorrectionLevel, $matrixPointSize);
            }
            $zip      = new ZipArchive();
            $filename = $qr_topath . "/" . $timepath . '.zip';
            if ($zip->open($filename, ZipArchive::OVERWRITE) === TRUE) {
                $this->addFileToZip($topath . '/', $zip);
                $zip->close();
            }
            header('Pragma: no-cache');
            header('Location: ' . '../../attachment/qrcode/' . $timepath . '.zip');
            exit();
        }
        $list  = pdo_fetchall("SELECT *  from " . tablename($this->data) . " where status ='1' $where order by id asc LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
        $total = pdo_fetchcolumn("SELECT COUNT(*)  from " . tablename($this->data) . " where status ='1' $where order by id asc");
        $pager = pagination($total, $pindex, $psize);
        foreach ($list as $k => $v) {
            $list[$k]['url'] = urlencode($url_info . "&wd_code=" . $v['code']);
        }
        include $this->template('display');
    }
    public function doWebCreate()
    {
        global $_GPC, $_W;
        load()->func('tpl');
        if (checksubmit('submit')) {
            $rule = $_GPC['rule'];
            $list = pdo_fetchall("SELECT *  from " . tablename($this->data) . " where code like '{$_GPC['sStr']}%'");
            if (!empty($list)) {
                message('防伪码前缀已存在，请修改');
            }
            $i = 1;
            while ($i <= intval($_GPC['sNum'])) {
                $code = $this->random(intval($_GPC['slen']), $rule, false);
                $data = array(
                    'code' => $_GPC['sStr'] . $code,
                    'type' => $_GPC['sName'],
                    'brand' => $_GPC['sBrand'],
                    'spec' => $_GPC['sSpec'],
                    'weight' => $_GPC['sWeight'],
                    'factory' => $_GPC['sFactory'],
                    'remarks' => $_GPC['sRemarks'],
                    'stime' => strtotime($_GPC['sTime_1']),
                    'createtime' => time(),
                    'creditname' => $_GPC['creditname'],
                    'creditnum' => intval($_GPC['creditnum']),
                    'creditstatus' => intval($_GPC['creditstatus']),
                    'num' => 0,
                    'status' => 1,
                    'tourl' => $_GPC['tourl'],
                    'img_logo' => $_GPC['img_logo'],
                    'img_banner' => $_GPC['img_banner'],
                    'video' => $_GPC['video'],
                    'buyurl' => $_GPC['buyurl']
                );
                pdo_insert($this->data, $data);
                $i++;
            }
            message('成功生成' . intval($_GPC['sNum']) . '条防伪码！', referer(), 'success');
        }
        if (checksubmit('submitone')) {
            $security = $_GPC['security'];
            $list     = pdo_fetchall("SELECT *  from " . tablename($this->data) . " where code = '{$security}'");
            if (!empty($list)) {
                message('防伪码已存在，请修改');
            }
            $insert = array(
                'code' => $security,
                'type' => $_GPC['sName2'],
                'brand' => $_GPC['sBrand2'],
                'spec' => $_GPC['sSpec2'],
                'weight' => $_GPC['sWeight2'],
                'factory' => $_GPC['sFactory2'],
                'remarks' => $_GPC['sRemarks2'],
                'stime' => strtotime($_GPC['sTime_2']),
                'createtime' => time(),
                'creditname' => $_GPC['creditname2'],
                'creditnum' => intval($_GPC['creditnum2']),
                'creditstatus' => intval($_GPC['creditstatus2']),
                'num' => 0,
                'status' => 1,
                'img_logo' => $_GPC['img_logo'],
                'img_banner' => $_GPC['img_banner'],
                'video' => $_GPC['video'],
                'buyurl' => $_GPC['buyurl']
            );
            pdo_insert($this->data, $insert);
            message('成功添加防伪码！', referer(), 'success');
        }
        $have_shop = pdo_tableexists("shopping_goods");
        if ($have_shop) {
            $sql      = 'SELECT * FROM ' . tablename('shopping_category') . ' WHERE `weid` = :weid ORDER BY `parentid`, `displayorder` DESC';
            $category = pdo_fetchall($sql, array(
                ':weid' => $_W['uniacid']
            ), 'id');
            if (!empty($category)) {
                $parent = $children = array();
                foreach ($category as $cid => $cate) {
                    if (!empty($cate['parentid'])) {
                        $children[$cate['parentid']][] = $cate;
                    } else {
                        $parent[$cate['id']] = $cate;
                    }
                }
            }
        }
        include $this->template('create');
    }
    public function doWebInsert()
    {
        global $_GPC, $_W;
        load()->func('file');
        load()->func('tpl');
        if (checksubmit('submit')) {
            $tmp = $_FILES['file']['tmp_name'];
            if (empty($tmp)) {
                message('请选择要导入的EXCEL或TXT(.xls,.txt)文件！', referer(), 'error');
            }
            switch ($_FILES['file']['type']) {
                case "application/kset":
                    break;
                case 'application/excel':
                    break;
                case 'application/vnd.ms-excel':
                    break;
                case 'application/msexcel':
                    break;
                case 'application/msexcel':
                    break;
                case 'text/plain':
                    break;
                default:
                    $flag = 1;
            }
            if ($flag == 1) {
                message('目前只支持EXCEL和TXT(.xls,.txt)格式文件！', referer(), 'error');
            }
            $save_path = IA_ROOT . "/attachment/";
            if (strpos($_FILES['file']['type'], 'excel')) {
                $file_name = $save_path . date('Ymdhis') . ".xls";
                if (move_uploaded_file($tmp, $file_name)) {
                    $xls = new Spreadsheet_Excel_Reader();
                    $xls->setOutputEncoding('utf-8');
                    $xls->read($file_name);
                    $i   = 1;
                    $len = $xls->sheets[0]['numRows'];
                    while ($i <= $len) {
                        $temp = $xls->sheets[0]['cells'][$i][1];
                        if (!empty($temp)) {
                            $data = array(
                                'code' => $temp,
                                'type' => $_GPC['sName'],
                                'brand' => $_GPC['sBrand'],
                                'spec' => $_GPC['sSpec'],
                                'weight' => $_GPC['sWeight'],
                                'factory' => $_GPC['sFactory'],
                                'remarks' => $_GPC['sRemarks'],
                                'stime' => strtotime($_GPC['sTime']),
                                'createtime' => time(),
                                'creditname' => $_GPC['creditname'],
                                'creditnum' => intval($_GPC['creditnum']),
                                'creditstatus' => intval($_GPC['creditstatus']),
                                'num' => 0,
                                'status' => 1
                            );
                            pdo_insert($this->data, $data);
                        }
                        $i++;
                    }
                    unlink($file_name);
                    message('成功导入' . $len . '条防伪码！', referer(), 'success');
                }
            } elseif (strpos($_FILES['file']['type'], 'plain')) {
                $file_name = $save_path . date('Ymdhis') . ".txt";
                if (move_uploaded_file($tmp, $file_name)) {
                    $txt = file_get_contents($file_name);
                    $txt = explode("\r\n", $txt);
                    $len = count($txt);
                    foreach ($txt as $key => $value) {
                        if (!empty($value)) {
                            $data = array(
                                'code' => $value,
                                'type' => $_GPC['sName'],
                                'brand' => $_GPC['sBrand'],
                                'spec' => $_GPC['sSpec'],
                                'weight' => $_GPC['sWeight'],
                                'factory' => $_GPC['sFactory'],
                                'remarks' => $_GPC['sRemarks'],
                                'stime' => strtotime($_GPC['sTime']),
                                'createtime' => time(),
                                'creditname' => $_GPC['creditname'],
                                'creditnum' => intval($_GPC['creditnum']),
                                'creditstatus' => intval($_GPC['creditstatus']),
                                'num' => 0,
                                'status' => 1
                            );
                            pdo_insert($this->data, $data);
                        }
                    }
                    unlink($file_name);
                    message('成功导入' . $len . '条防伪码！', referer(), 'success');
                }
            } else {
                echo strpos($_FILES['file']['type'], 'plain');
                message('目前只支持EXCEL和TXT(.xls,.txt)格式文件！~~');
            }
        }
        include $this->template('insert');
    }
    public function random($length, $type = NULL, $special = FALSE)
    {
        $str = "";
        switch ($type) {
            case 1:
                $str = "0123456789";
                break;
            case 2:
                $str = "abcdefghijklmnopqrstuvwxyz";
                break;
            case 3:
                $str = "abcdefghijklmnopqrstuvwxyz0123456789";
                break;
            default:
                $str = "abcdefghijklmnopqrstuvwxyz0123456789";
                break;
        }
        return substr(str_shuffle(($special != FALSE) ? '!@#$%^&*()_+' . $str : $str), 0, $length);
    }
    public function doWebFrozen()
    {
        global $_GPC, $_W;
        pdo_update($this->data, array(
            'status' => 0
        ), array(
            'id' => $_GPC['id']
        ));
        message('成功冻结该防伪码！', referer(), 'success');
    }
    public function doWebDelete()
    {
        global $_GPC, $_W;
        if (!empty($_GPC['id'])) {
            $set = pdo_delete($this->data, array(
                'id' => $_GPC['id']
            ));
            message('成功删除此条防伪码！', referer(), 'success');
        }
    }
    public function doWebCheckepre()
    {
        global $_GPC, $_W;
        $sStr = $_GPC['sStr'];
        $list = pdo_fetchall("SELECT *  from " . tablename($this->data) . " where code like '{$sStr}%'");
        if (!empty($list)) {
            echo count($list);
        } else {
            echo '0';
        }
    }
    public function doWebGetgoods()
    {
        global $_GPC, $_W;
        $ccate = intval($_GPC['ccate']);
        $list  = pdo_fetchall("SELECT id,title,thumb from " . tablename("shopping_goods") . " where ccate=$ccate");
        if (!empty($list)) {
            echo json_encode($list);
        } else {
            echo '0';
        }
    }
    public function doWebCheckesecurity()
    {
        global $_GPC, $_W;
        $security = $_GPC['security'];
        $list     = pdo_fetchall("SELECT *  from " . tablename($this->data) . " where code = '{$security}'");
        if (!empty($list)) {
            echo '1';
        } else {
            echo '0';
        }
    }
    public function doWebLogs()
    {
        $t          = mktime(0, 0, 0, date("m", time()), date("d", time()), date("y", time()));
        $t1         = $t - 7 * 86400;
        $t2         = $t - 6 * 86400;
        $t3         = $t - 5 * 86400;
        $t4         = $t - 4 * 86400;
        $t5         = $t - 3 * 86400;
        $t6         = $t - 2 * 86400;
        $t7         = $t - 1 * 86400;
        $t8         = $t + 1 * 86400;
        $labels     = '"' . date('Y-m-d', $t1) . '","' . date('Y-m-d', $t2) . '","' . date('Y-m-d', $t3) . '","' . date('Y-m-d', $t4) . '","' . date('Y-m-d', $t5) . '","' . date('Y-m-d', $t6) . '","' . date('Y-m-d', $t7) . '","' . date('Y-m-d', $t) . '"';
        $d1_1       = $this->igetlog($t1, $t2, '2');
        $d1_2       = $this->igetlog($t1, $t2, '1');
        $d2_1       = $this->igetlog($t2, $t3, '2');
        $d2_2       = $this->igetlog($t2, $t3, '1');
        $d3_1       = $this->igetlog($t3, $t4, '2');
        $d3_2       = $this->igetlog($t3, $t4, '1');
        $d4_1       = $this->igetlog($t4, $t5, '2');
        $d4_2       = $this->igetlog($t4, $t5, '1');
        $d5_1       = $this->igetlog($t5, $t6, '2');
        $d5_2       = $this->igetlog($t5, $t6, '1');
        $d6_1       = $this->igetlog($t6, $t7, '2');
        $d6_2       = $this->igetlog($t6, $t7, '1');
        $d7_1       = $this->igetlog($t7, $t, '2');
        $d7_2       = $this->igetlog($t7, $t, '1');
        $d8_1       = $this->igetlog($t, $t8, '2');
        $d8_2       = $this->igetlog($t, $t8, '1');
        $data_1     = $d1_1 . ',' . $d2_1 . ',' . $d3_1 . ',' . $d4_1 . ',' . $d5_1 . ',' . $d6_1 . ',' . $d7_1 . ',' . $d8_1;
        $data_2     = $d1_2 . ',' . $d2_2 . ',' . $d3_2 . ',' . $d4_2 . ',' . $d5_2 . ',' . $d6_2 . ',' . $d7_2 . ',' . $d8_2;
        $data_1_all = $this->igetlog('0', time(), '2');
        $data_2_all = $this->igetlog('0', time(), '1');
        $data_3_all = $this->igetlog('0', time(), '0');
        include $this->template('logs');
    }
    protected function igetlog($t1, $t2, $status)
    {
        global $_GPC, $_W;
        if ($status == '2') {
            $data = pdo_fetchcolumn("SELECT COUNT(*)  from " . tablename($this->i_logs) . " where weid ='{$_W['uniacid']}' and createtime >= '{$t1}' and createtime <= '{$t2}'");
        } else {
            $data = pdo_fetchcolumn("SELECT COUNT(*)  from " . tablename($this->i_logs) . " where weid ='{$_W['uniacid']}' and createtime >= '{$t1}' and createtime <= '{$t2}' and status = '{$status}'");
        }
        return $data;
    }
    function addFileToZip($path, $zip)
    {
        $handler = opendir($path);
        while (($filename = readdir($handler)) !== false) {
            if ($filename != "." && $filename != "..") {
                if (is_dir($path . "/" . $filename)) {
                    addFileToZip($path . "/" . $filename, $zip);
                } else {
                    $zip->addFile($path . "/" . $filename);
                }
            }
        }
        @closedir($path);
    }
}