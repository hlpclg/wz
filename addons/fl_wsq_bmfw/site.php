
<?php
defined('IN_IA') or exit('Access Denied');
class fl_wsq_bmfwModuleSite extends WeModuleSite
{
    var $config;
    public function __construct()
    {
        global $_W, $_GPC;
        $sql          = "select * from " . tablename("fl_wsq_config") . " where weid={$_W['weid']}";
        $config       = pdo_fetch($sql);
        $this->config = $config;
    }
    public function doWebHomeSetting()
    {
        global $_W;
        $root   = $_W['siteroot'];
        $weid   = $_W['weid'];
        $url    = "{$root}app/index.php?i={$weid}&c=entry&do=lists&m=fl_wsq_bmfw";
        $images = MODULE_URL . "images/";
        include $this->template('homeSetting');
    }
    public function doMobileLists()
    {
        global $_W, $_GPC;
        $img    = MODULE_URL . "template/mobile/img/";
        $css    = MODULE_URL . "template/mobile/css/";
        $js     = MODULE_URL . "template/mobile/js/";
        $number = 10;
        if ($_GPC['keyword']) {
            $where      = " and (tel like '%{$_GPC['keyword']}%' or name like '%{$_GPC['keyword']}%')";
            $insertArr  = Array(
                "keywords" => $_GPC['keyword'],
                "create_time" => time(),
                "openid" => $_W['openid']
            );
            $insert_log = pdo_insert("fl_wsq_search_log", $insertArr);
        }
        $sql  = "select * from " . tablename("fl_wsq_shoping") . " where weid={$_W['weid']} $where and status=1 order by orders desc limit 0,{$number}";
        $list = pdo_fetchall($sql);
        include $this->template('lists');
    }
    public function doMobileAjaxLists()
    {
        global $_W, $_GPC;
        $page       = $_GPC['page'] ? $_GPC['page'] : 1;
        $number     = $_GPC['pagenumber'];
        $pagenumber = ($_GPC['page'] - 1) * $number;
        if ($_GPC['keyword']) {
            $where = " and (tel like '%{$_GPC['keyword']}%' or name like '%{$_GPC['keyword']}%')";
        }
        $sql  = "select * from " . tablename("fl_wsq_shoping") . " where weid={$_W['weid']} $where and status=1 order by orders desc limit {$pagenumber},{$number} ";
        $data = pdo_fetchall($sql);
        echo json_encode($data);
    }
    public function doMobileReg()
    {
        global $_W, $_GPC;
        $img  = MODULE_URL . "template/mobile/img/";
        $css  = MODULE_URL . "template/mobile/css/";
        $js   = MODULE_URL . "template/mobile/js/";
        $sql  = "select * from " . tablename("fl_wsq_shoping_type") . " where weid={$_W['weid']}";
        $type = pdo_fetchall($sql);
        include $this->template('reg');
    }
    public function doMobilesavereg()
    {
        global $_GPC, $_W;
        $insertArr = Array(
            "name" => $_GPC['name'],
            "tel" => $_GPC['tel'],
            "address" => $_GPC['address'],
            "create_time" => time(),
            "openid" => $_W['openid'],
            "status" => 0,
            "contact_name" => $_GPC['contact_name'],
            "contact_tel" => $_GPC['contact_tel'],
            "content" => $_GPC['content'],
            "weid" => $_W['weid'],
            "tid" => $_GPC['tid']
        );
        pdo_insert('fl_wsq_shoping_reg', $insertArr);
        message('申请成功，请等待管理员审核', $this->createMobileUrl("lists"), "success");
    }
    public function doWebList()
    {
        global $_W, $_GPC;
        $pageNumber   = 10;
        $_GPC['page'] = $_GPC['page'] ? $_GPC['page'] : 1;
        $pages        = ($_GPC['page'] - 1) * $pageNumber;
        $sql          = "select * from " . tablename("fl_wsq_shoping") . " where weid={$_W['weid']} order by `orders` desc limit {$pages},{$pageNumber}";
        $list         = pdo_fetchall($sql);
        $total        = pdo_fetchcolumn("select count(*) from " . tablename("fl_wsq_shoping") . " where weid={$_W['weid']}");
        $pagination   = pagination($total, $_GPC['page'], 10);
        foreach ($list as $key => $value) {
            $ids[]                 = $value['id'];
            $ids_key[$value['id']] = $key;
        }
        if ($ids) {
            $ids  = implode(",", $ids);
            $sql  = "select stb.*,sp.name as tname from " . tablename("fl_wsq_shoping_type_bind") . " stb
				left join " . tablename("fl_wsq_shoping_type") . " sp on sp.id=stb.tid
					where sid in ({$ids})
				";
            $type = pdo_fetchall($sql);
            if ($type) {
                foreach ($type as $value) {
                    $list[$ids_key[$value['sid']]]['type'][] = $value['tname'];
                }
            }
        }
        foreach ($list as $key => $value) {
            $list[$key]['tname'] = implode(",", $value['type']);
        }
        include $this->template('list');
    }
    public function doWebadd()
    {
        global $_W, $_GPC;
        load()->func('tpl');
        $sql   = "select * from " . tablename("fl_wsq_shoping_type") . " where weid={$_W['weid']}";
        $types = pdo_fetchall($sql);
        include $this->template('save');
    }
    public function doWebSave()
    {
        global $_GPC, $_W;
        $data = Array(
            "name" => $_GPC['name'],
            "tel" => $_GPC['tel'],
            "address" => $_GPC['address'],
            "status" => $_GPC['status'],
            "orders" => $_GPC['orders']
        );
        if ($_GPC['id']) {
            $where = Array(
                "id" => $_GPC['id']
            );
            $save  = pdo_update("fl_wsq_shoping", $data, $where);
            pdo_delete('fl_wsq_shoping_type_bind', array(
                'sid' => $_GPC['id']
            ));
            foreach ($_GPC['tid'] as $value) {
                $insertData = Array(
                    "tid" => $value,
                    "sid" => $_GPC['id'],
                    "create_time" => time()
                );
                pdo_insert('fl_wsq_shoping_type_bind', $insertData);
            }
            message('编辑商家成功！', referer(), 'success');
        } else {
            $data['create_time'] = time();
            $data['weid']        = $_W['weid'];
            $save                = pdo_insert("fl_wsq_shoping", $data);
            $sid                 = pdo_insertid();
            foreach ($_GPC['tid'] as $value) {
                $insertData = Array(
                    "tid" => $value,
                    "sid" => $sid,
                    "create_time" => time()
                );
                pdo_insert('fl_wsq_shoping_type_bind', $insertData);
            }
            message('新增商家成功！', referer(), 'success');
        }
    }
    public function doWebdelete()
    {
        global $_GPC;
        $where = Array(
            "id" => $_GPC['id']
        );
        pdo_delete('fl_wsq_shoping', $where);
        message('删除成功！', referer(), 'success');
    }
    public function doWebedit()
    {
        global $_GPC;
        load()->func('tpl');
        $sql = "select * from " . tablename("fl_wsq_shoping") . " where id={$_GPC['id']}";
        $row = pdo_fetch($sql);
        include $this->template('save');
    }
    public function doWebReglist()
    {
        global $_W, $_GPC;
        $sql  = "select * from " . tablename("fl_wsq_shoping_reg") . " where weid={$_W['weid']} and status=0";
        $list = pdo_fetchall($sql);
        include $this->template('reglist');
    }
    public function doWebCheckReg()
    {
        global $_W, $_GPC;
        $status = $_GPC['status'];
        $update = Array(
            "status" => $status
        );
        $where  = Array(
            "id" => $_GPC['id']
        );
        $sql    = "select * from " . tablename("fl_wsq_shoping_reg") . " where id={$_GPC['id']}";
        $row    = pdo_fetch($sql);
        if ($status == 1) {
            $insertArr = Array(
                "name" => $row['name'],
                "tel" => $row['tel'],
                "address" => $row['address'],
                "create_time" => time(),
                "status" => 1,
                "orders" => 0,
                "weid" => $row['weid']
            );
            pdo_insert('fl_wsq_shoping', $insertArr);
        } elseif ($status == 2) {
        }
        pdo_update("fl_wsq_shoping_reg", $update, $where);
        message('审核完毕！', referer(), 'success');
    }
    public function doWebGroup()
    {
        global $_W, $_GPC;
        $pageNumber   = 10;
        $_GPC['page'] = $_GPC['page'] ? $_GPC['page'] : 1;
        $pages        = ($_GPC['page'] - 1) * $pageNumber;
        $sql          = "select * from " . tablename("fl_wsq_shoping_type") . " where weid={$_W['weid']}  limit {$pages},{$pageNumber}";
        $list         = pdo_fetchall($sql);
        $total        = pdo_fetchcolumn("select count(*) from " . tablename("fl_wsq_shoping_type") . " where weid={$_W['weid']}");
        $pagination   = pagination($total, $_GPC['page'], 10);
        include $this->template('group');
    }
    public function doWebGroupadd()
    {
        load()->func('tpl');
        include $this->template('groupsave');
    }
    public function doWebeditGroup()
    {
        global $_W, $_GPC;
        load()->func('tpl');
        $sql  = "select * from " . tablename("fl_wsq_shoping_type") . " where id={$_GPC['id']}";
        $data = pdo_fetch($sql);
        include $this->template('groupsave');
    }
    public function doWebDeleteGroup()
    {
        global $_W, $_GPC;
        $where = Array(
            "id" => $_GPC['id']
        );
        pdo_delete('fl_wsq_shoping_type', $where);
        message('分类删除成功！', referer(), 'success');
    }
    public function doWebGroupSave()
    {
        global $_W, $_GPC;
        $data = Array(
            "name" => $_GPC['name'],
            "images" => $_GPC['images']
        );
        if ($_GPC['id']) {
            $where = Array(
                "id" => $_GPC['id']
            );
            pdo_update('fl_wsq_shoping_type', $data, $where);
            message('分类编辑完毕！', referer(), 'success');
        } else {
            $data['create_time'] = time();
            $data['weid']        = $_W['weid'];
            pdo_insert('fl_wsq_shoping_type', $data);
            message('分类新增完毕！', referer(), 'success');
        }
    }
    public function doWebArea()
    {
        global $_W, $_GPC;
        $sql  = "select * from " . tablename("fl_wsq_area") . " where weid={$_W['weid']}";
        $area = pdo_fetchall($sql);
        include $this->template('area');
    }
    public function doWebAreaadd()
    {
        global $_W, $_GPC;
        $sql   = "select * from " . tablename("fl_wsq_area") . " where fid=0 and weid={$_W['weid']}";
        $farea = pdo_fetchall($sql);
        include $this->template('areasave');
    }
    public function doWebAreaSave()
    {
        global $_W, $_GPC;
        $data = Array(
            "name" => $_GPC['name'],
            "fid" => $_GPC['fid'],
            "status" => 1
        );
        if ($_GPC['id']) {
            $where = Array(
                "id" => $_GPC['id']
            );
            pdo_update('fl_wsq_area', $data, $where);
            message('地区编辑完毕！', referer(), 'success');
        } else {
            $data['create_time'] = time();
            $data['weid']        = $_W['weid'];
            pdo_insert('fl_wsq_area', $data);
            message('地区新增完毕！', referer(), 'success');
        }
    }
}