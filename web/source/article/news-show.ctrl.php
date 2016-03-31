<?php
/**
 * [WEIZAN System] Copyright (c) 2015 012WZ.COM
 * WeiZan is NOT a free software, it under the license terms, visited http://www.012wz.com/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
$dos = array( 'detail', 'list');
$do = in_array($do, $dos) ? $do : 'list';
load()->model('article');

if($do == 'detail') {
	$id = intval($_GPC['id']);
	$news = article_news_info($id);
	if(is_error($news)) {
		message('新闻不存在或已删除', referer(), 'error');
	}
}

if($do == 'list') {
	$categroys = article_categorys('news');
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;

	$cateid = intval($_GPC['cateid']);
	$filter = array('cateid' => $cateid);
	$newss = article_news_all($filter, $pindex, $psize);
	$total = intval($newss['total']);
	$data = $newss['news'];
	$pager = pagination($total, $pindex, $psize);
}

template('article/news-show');
