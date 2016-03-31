<?php
/**
 * [WeiZan System] Copyright (c) 2014 012wz.com
 * WeiZan is  a free software, it under the license terms, visited http://www.012wz.com/ for more details.
 */
global $_W, $_GPC;
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$weid= $_W['uniacid'];

if ($op == 'display') {
	if (!empty($_GPC['displayorder'])) {
		foreach ($_GPC['displayorder'] as $id => $displayorder) {
			$update = array('displayorder' => $displayorder);
			pdo_update('fineness_article_category', $update, array('id' => $id));
		}
		message('分类排序更新成功！', 'refresh', 'success');
	}
	$children = array();
	$category = pdo_fetchall("SELECT * FROM ".tablename('fineness_article_category')." WHERE uniacid =$weid ORDER BY parentid, displayorder DESC, id");
	foreach ($category as $index => $row) {
		if (!empty($row['parentid'])){
			$children[$row['parentid']][] = $row;
			unset($category[$index]);
		}
	}

} elseif ($op == 'post') {
	load()->func('tpl');
	$parentid = intval($_GPC['parentid']);

	$id = intval($_GPC['id']);
	if(!empty($id)) {
		$category = pdo_fetch("SELECT * FROM ".tablename('fineness_article_category')." WHERE id = '$id' AND uniacid =$weid ");
		if(empty($category)) {
			message('分类不存在或已删除', '', 'error');
		}
	}
	if (!empty($parentid)) {
		$parent = pdo_fetch("SELECT id, name FROM ".tablename('fineness_article_category')." WHERE id = '$parentid'");
		if (empty($parent)) {
			message('抱歉，上级分类不存在或是已经被删除！', $this->createWebUrl('category', array('do' => 'display')), 'error');
		}
	}

	$category['type'] = explode(',', $category['type']);
	$keywords = pdo_fetchcolumn('SELECT content FROM ' . tablename('rule_keyword') . ' WHERE id = :id AND uniacid = :uniacid ', array(':id' => $category['kid'], ':uniacid' => $weid));


	if (checksubmit('submit')) {
		if (empty($_GPC['cname'])) {
			message('抱歉，请输入分类名称！');
		}
		$data = array(
			'uniacid' => $weid,
			'name' => $_GPC['cname'],
			'displayorder' => intval($_GPC['displayorder']),
			'parentid' => intval($parentid),
			'description' => $_GPC['description'],
			'template' => $_GPC['template'],
            'templatefile' => "themes/list".$_GPC['template'],
			'thumb' => $_GPC['thumb'],
            'createtime' => TIMESTAMP
		);
		if(!empty($_GPC['keyword'])) {
			$rule['uniacid'] = $weid;
			$rule['name'] = '文章分类：' . $_GPC['cname'] . ' 触发规则';
			$rule['module'] = 'news';
			$rule['status'] = 1;

			$keyword = array('uniacid' => $weid);
			$keyword['module'] = 'news';
			$keyword['content'] = $_GPC['keyword'];
			$keyword['status'] = 1;
			$keyword['type'] = 1;
			$keyword['displayorder'] = 1;

			$reply['title'] = $_GPC['cname'];
			$reply['description'] = $_GPC['description'];
			$reply['thumb'] = $_GPC['thumb'];
			$reply['displayorder'] = $_GPC['displayorder'];
			$reply['url'] = murl('entry/module/index', array('m'=>'amouse_article','cid' => $id));
		}

		if (!empty($id)) {
			unset($data['parentid']);
			pdo_delete('rule', array('id' => $category['rid'], 'uniacid' => $weid));
			pdo_delete('rule_keyword', array('rid' => $category['rid'], 'uniacid' => $weid));
			pdo_delete('news_reply', array('rid' => $category['rid']));

			if(!empty($_GPC['keyword'])) {
				pdo_insert('rule', $rule);
				$rid = pdo_insertid();

				$keyword['rid'] = $rid;
				pdo_insert('rule_keyword', $keyword);
				$kid = pdo_insertid();
				$reply['rid'] = $rid;
				$alist = pdo_fetchall("SELECT * FROM ".tablename('fineness_article')." WHERE weid= $weid AND ccate=$id  ORDER BY displayorder ASC limit 8 ") ;
				if($alist){
					foreach($alist as $par) {
						$reply2['title'] = $par['title'];
						$reply2['description'] = $par['description'];

						$reply2['thumb'] = $par['thumb'];
						$reply2['url'] = murl('entry/module/detail', array('m'=>'amouse_article','id' => $par['id']));
						$reply2['rid'] = $rid;
						pdo_insert('news_reply', $reply2);
					}
				}else{
					pdo_insert('news_reply', $reply);
					$data['rid'] = $rid;
					$data['kid'] = $kid;
				}
			} else {
				$data['rid'] = 0;
				$data['kid'] = 0;
			}
			pdo_update('fineness_article_category', $data, array('id' => $id));
		} else {

			if(!empty($_GPC['keyword'])) {
				pdo_insert('rule', $rule);
				$rid = pdo_insertid();

				$keyword['rid'] = $rid;
				pdo_insert('rule_keyword', $keyword);
				$kid = pdo_insertid();

				$reply['rid'] = $rid;
				$data['rid'] = $rid;
				$data['kid'] = $kid;
			}
			pdo_insert('fineness_article_category', $data);
			$aid = pdo_insertid();
			if(!empty($_GPC['keyword'])) {//关键字
				$alist = pdo_fetchall("SELECT * FROM ".tablename('fineness_article')." WHERE weid={$weid} AND ccate={$aid}  ORDER BY displayorder ASC limit 8 ") ;
				if($alist){
					foreach($alist as $par) {
						$reply2['title'] = $par['title'];
						$reply2['description'] = $par['description'];
						$reply2['thumb'] = $par['thumb'];
						$reply2['url'] = murl('entry/module/detail', array('m'=>'amouse_article','id' => $par[id]));
						$reply2['rid'] = $rid;
						pdo_insert('news_reply', $reply2);
					}
				}else{
					$reply['title'] = $_GPC['cname'];
					$reply['description'] = $_GPC['description'];
					$reply['thumb'] = $_GPC['thumb'];
					$reply['displayorder'] = $_GPC['displayorder'];
					$reply['url'] = murl('entry/module/index', array('m'=>'amouse_article','cid' => $aid));
					pdo_insert('news_reply', $reply);
				}
			}
		}
        message('更新分类成功！', $this->createWebUrl('category', array('do' => 'display')), 'success');
	}
} elseif ($op == 'fetch') {
	$category = pdo_fetchall("SELECT id, name FROM ".tablename('fineness_article_category')." WHERE parentid = '".intval($_GPC['parentid'])."' ORDER BY id ASC, displayorder ASC, id ASC ");
	message($category, '', 'ajax');
} elseif ($op == 'delete') {
	load()->func('file');
	$id = intval($_GPC['id']);
	pdo_delete('fineness_article_category', array('id' => $id));
    message('分类删除成功！', $this->createWebUrl('category', array('do' => 'display')), 'success');
}

include $this->template('category');