<?php
/**
 * @author WeiZan System
 * @url http://bbs.012wz.com/forum.php?mod=forumdisplay&fid=36&filter=typeid&typeid=1
 */
defined('IN_IA') or exit('Access Denied');

class We7_businessModuleSite extends WeModuleSite {

    public function doWebPost() {
        global $_GPC, $_W;
        if (empty($_GPC['do'])) {
            $_GPC['do'] = 'post';
        }
        $id = intval($_GPC['id']);
        if (!empty($id)) {
            $item = pdo_fetch("SELECT * FROM " . tablename('business') . " WHERE id = :id", array(':id' => $id));
            if (empty($item)) {
                message('抱歉，商户不存在或是已经删除！', '', 'error');
            }
        }
        if (checksubmit('submit')) {
            if (empty($_GPC['title'])) {
                message('请输入商户名称！');
            }
            $data = array(
                'weid' => $_W['weid'],
                'title' => $_GPC['title'],
                'content' => htmlspecialchars_decode($_GPC['content']),
                'phone' => $_GPC['phone'],
                'qq' => $_GPC['qq'],
                'province' => $_GPC['district']['province'],
                'city' => $_GPC['district']['city'],
                'dist' =>$_GPC['district']['district'],
                'address' => $_GPC['address'],
                'lng' => $_GPC['baidumap']['lng'],
                'lat' => $_GPC['baidumap']['lat'],
                'industry1' => $_GPC['industry']['parent'],
                'industry2' => $_GPC['industry']['child'],
                'createtime' => TIMESTAMP,
            );
            if (!empty($_GPC['thumb'])) {
                $data['thumb'] = $_GPC['thumb'];
				load()->func('file');
                file_delete($_GPC['thumb-old']);
            }
            if (empty($id)) {
                pdo_insert('business', $data);
            } else {
                unset($data['createtime']);
                pdo_update('business', $data, array('id' => $id));
            }
            message('商户信息更新成功！', $this->createWebUrl('display'), 'success');
        }
        include $this->template('post');
    }

    public function doWebDisplay() {
        global $_W, $_GPC;

        $pindex = max(1, intval($_GPC['page']));
        $psize = 10;
        $condition = '';
        if (!empty($_GPC['keyword'])) {
            $condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
        }
        if (is_array($_GPC['industry'])) {
            if (!empty($_GPC['industry']['parent'])) {
                $condition .= " AND industry1 = '{$_GPC['industry']['parent']}'";
            }
            if (!empty($_GPC['industry']['child'])) {
                $condition .= " AND industry2 = '{$_GPC['industry']['child']}'";
            }
        }

        $sql = 'SELECT COUNT(*) FROM ' . tablename('business') . ' WHERE `weid` = :weid ' . $condition;
        $params = array(':weid' => $_W['uniacid']);
        $total = pdo_fetchcolumn($sql, $params);
        if ($total > 0) {
            $sql = 'SELECT * FROM ' . tablename('business') . ' WHERE `weid` = :weid ' . $condition . ' ORDER BY `id`
                    DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
            $list = pdo_fetchall($sql, $params);
            $pager = pagination($total, $pindex, $psize);
        }

        if (!empty($_GPC['export'])) {
            /* 输入到CSV文件 */
            $html = "\xEF\xBB\xBF";

            /* 输出表头 */
            $filter = array(
                'title' => '名称',
                'phone' => '电话',
                'address' => '地址',
                'industry1' => '行业',
            );

            foreach ($filter as $key => $value) {
                $html .= $value . "\t,";
            }

            $html .= "\n";
            $key_array = array_keys($filter);

            foreach ($list as $k => $v) {
                foreach ($v as $k1 => $v1) {
                    if (in_array($k1, $key_array)) {
                        if ($k1 == 'createtime' || $k1 == 'dateline') {
                            $html .= date('Y-m-d H:i:s', $v1) . "\t ,";
                        } else {
                            $html .= $v1 . "\t,";
                        }
                    }
                }
                $html .= "\n";
            }

            /* 输出CSV文件 */
            header("Content-type:text/csv");
            header("Content-Disposition:attachment; filename=全部数据.csv");
            echo $html;
            exit();
        }

        load()->func('tpl');

        include $this->template('display');
    }

    public function doWebDelete() {
        global $_GPC;
        $id = intval($_GPC['id']);
        $item = pdo_fetch("SELECT * FROM " . tablename('business') . " WHERE id = :id", array(':id' => $id));
        if (empty($item)) {
            message('抱歉，商户不存在或是已经删除！', '', 'error');
        }
        if (!empty($item['thumb'])) {
			load()->func('file');
            file_delete($item['thumb']);
        }
        pdo_delete('business', array('id' => $item['id']));
        message('删除成功！', referer(), 'success');
    }

    public function doMobileDetail() {
        global $_W, $_GPC;
        $id = intval($_GPC['id']);
        $item = pdo_fetch("SELECT * FROM " . tablename('business') . " WHERE id = :id", array(':id' => $id));
        if (empty($item)) {
            message('抱歉，该商家不存在或是已经被删除！');
        }
        $content = strip_tags($item['content']);
        $content = cutstr($content, 50, true);
        include $this->template('detail');
    }

    public function getHomeTiles() {
        global $_W;
        $urls = array();

        $sql = 'SELECT `id`, `title` FROM ' . tablename('business') . ' WHERE `weid` = :weid';
        $replies = pdo_fetchall($sql, array(':weid' => $_W['uniacid']));
        if (!empty($replies)) {
            foreach ($replies as $reply) {
                $urls[] = array('title' => $reply['title'], 'url' => $this->createMobileUrl('detail', array('id' => $reply['id'])));
            }
        }

        return $urls;
    }

}
