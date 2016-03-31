<?php
/**
 * 微官网模块微站定义
 *
 * @author WeEngine Team
 * @url http://www.we7.cc
 */
defined('IN_IA') or exit('Access Denied');


include_once IA_ROOT . '/addons/xc_article/define.php';
include_once INC_PHP . 'template.php';
include_once INC_PHP . 'model.php';
require IA_ROOT . '/framework/function/tpl.func.php';


class XC_ArticleModuleSite extends WeModuleSiteMore {

  public function doWebTest() {
    global $_GPC;
    preg_match('/attachment\/(.*?)(\.gif|\.jpg|\.png|\.bmp)/', $_GPC['content'], $match);
    print_r($match);
    preg_match('/(http|https):\/\/(.*?)(\.gif|\.jpg|\.png|\.bmp)/', $_GPC['content'], $match);
    print_r($match);
  }

	public function doWebCategory() {
		global $_W, $_GPC;
    load()->func('file');
    $mn = strtolower($this->module['name']);
		$foo = !empty($_GPC['foo']) ? $_GPC['foo'] : 'display';
		if ($foo == 'display') {
			if (!empty($_GPC['displayorder'])) {
				foreach ($_GPC['displayorder'] as $id => $displayorder) {
					pdo_update('xc_article_article_category', array('displayorder' => $displayorder), array('id' => $id));
				}
				message('分类排序更新成功！', 'refresh', 'success');
			}
			$children = array();
			$category = pdo_fetchall("SELECT * FROM ".tablename('xc_article_article_category')." WHERE weid = '{$_W['weid']}' ORDER BY parentid ASC, displayorder ASC, id ASC ");
			foreach ($category as $index => $row) {
				if (!empty($row['parentid'])){
					$children[$row['parentid']][] = $row;
					unset($category[$index]);
				}
			}
			include $this->template('category');
		} elseif ($foo == 'post') {
			$parentid = intval($_GPC['parentid']);
			$id = intval($_GPC['id']);
			//微站风格模板
			$template = $this->account_template();
      if(!empty($id)) {
				$category = pdo_fetch("SELECT * FROM ".tablename('xc_article_article_category')." WHERE id = '$id'");
				if (!empty($category['nid'])) {
					$nav = pdo_fetch("SELECT * FROM ".tablename('site_nav')." WHERE id = :id" , array(':id' => $category['nid']));
					$nav['css'] = unserialize($nav['css']);
					if (strexists($nav['icon'], 'images/')) {
						$nav['fileicon'] = $nav['icon'];
						$nav['icon'] = '';
					}
				}
			} else {
				$category = array(
					'displayorder' => 0,
				);
			}
			if (!empty($parentid)) {
				$parent = pdo_fetch("SELECT id, name FROM ".tablename('xc_article_article_category')." WHERE id = '$parentid'");
				if (empty($parent)) {
					message('抱歉，上级分类不存在或是已经被删除！', $this->createWebUrl('category', array('foo' => 'display')), 'error');
				}
			}
			if (checksubmit('fileupload-delete')) {
				file_delete($_GPC['fileupload-delete']);
				pdo_update('site_nav', array('icon' => ''), array('id' => $category['nid']));
				message('删除成功！', referer(), 'success');
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['cname'])) {
					message('抱歉，请输入分类名称！');
				}
				$pathinfo = pathinfo($_GPC['file']);
        list($gpc_template, $gpc_templatefile) = explode(':', $_GPC['template'], 2);
				$data = array(
					'weid' => $_W['weid'],
					'name' => $_GPC['cname'],
					'displayorder' => intval($_GPC['displayorder']),
					'parentid' => intval($parentid),
					'description' => $_GPC['description'],
					'thumb' => $_GPC['thumb'],
					'template' => $gpc_template,
					'templatefile' => $gpc_templatefile,
					'linkurl' => $_GPC['linkurl'],
					'ishomepage' => intval($_GPC['ishomepage']),
				);
				if (!empty($id)) {
					unset($data['parentid']);
					pdo_update('xc_article_article_category', $data, array('id' => $id));
				} else {
					pdo_insert('xc_article_article_category', $data);
					$id = pdo_insertid();
				}
				if (!empty($_GPC['isnav'])) {
					$nav = array(
						'weid' => $_W['weid'],
						'name' => $data['name'],
						'displayorder' => 0,
						'position' => 1,
						'url' => $this->createMobileUrl('list', array('cid' => $id)),
						'issystem' => 0,
						'status' => 1,
					);
					$nav['css'] = serialize(array(
						'icon' => array(
							'font-size' => $_GPC['icon']['size'],
							'color' => $_GPC['icon']['color'],
							'width' => $_GPC['icon']['size'],
							'icon' => $_GPC['icon']['icon'],
						),
					));
					if (!empty($_FILES['icon']['tmp_name'])) {
						file_delete($_GPC['icon_old']);
						$upload = file_upload($_FILES['icon']);
						if (is_error($upload)) {
							message($upload['message'], '', 'error');
						}
						$nav['icon'] = $upload['path'];
					}
					if (empty($category['nid'])) {
						pdo_insert('site_nav', $nav);
						pdo_update('xc_article_article_category', array('nid' => pdo_insertid()), array('id' => $id));
					} else {
						pdo_update('site_nav', $nav, array('id' => $category['nid']));
					}
				} else {
					pdo_delete('site_nav', array('id' => $category['nid']));
					pdo_update('xc_article_article_category', array('nid' => 0), array('id' => $id));
				}
				message('更新分类成功！', $this->createWebUrl('category'), 'success');
			}
			include $this->template('category');
		} elseif ($foo == 'fetch') {
			$category = pdo_fetchall("SELECT id, name FROM ".tablename('xc_article_article_category')." WHERE parentid = '".intval($_GPC['parentid'])."' ORDER BY id ASC, displayorder ASC, id ASC ");
			message($category, '', 'ajax');
		} elseif ($foo == 'delete') {
			$id = intval($_GPC['id']);
			$category = pdo_fetch("SELECT id, parentid, nid FROM ".tablename('xc_article_article_category')." WHERE id = '$id'");
			if (empty($category)) {
				message('抱歉，分类不存在或是已经被删除！', $this->createWebUrl('category'), 'error');
			}
			$navs = pdo_fetchall("SELECT icon, id FROM ".tablename('site_nav')." WHERE id IN (SELECT nid FROM ".tablename('xc_article_article_category')." WHERE id = {$id} OR parentid = '$id')", array(), 'id');
			if (!empty($navs)) {
				foreach ($navs as $row) {
					file_delete($row['icon']);
				}
				pdo_query("DELETE FROM ".tablename('site_nav')." WHERE id IN (".implode(',', array_keys($navs)).")");
			}
			pdo_delete('xc_article_article_category', array('id' => $id, 'parentid' => $id), 'OR');
			message('分类删除成功！', $this->createWebUrl('category'), 'success');
    }
  }

	public function doWebArticle() {
		global $_W, $_GPC;
    load()->func('file');
		$foo = !empty($_GPC['foo']) ? $_GPC['foo'] : 'display';
		$category = pdo_fetchall("SELECT * FROM ".tablename('xc_article_article_category')." WHERE weid = '{$_W['weid']}' ORDER BY parentid ASC, displayorder ASC, id ASC ", array(), 'id');

		if ($foo == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$condition = '';
			$params = array();
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND title LIKE :keyword";
				$params[':keyword'] = "%{$_GPC['keyword']}%";
			}

			if (!empty($_GPC['cate_1'])) {
				$cid = intval($_GPC['cate_1']);
				$condition .= " AND pcate = '{$cid}'";
			}

			$list = pdo_fetchall("SELECT * FROM ".tablename('xc_article_article')." WHERE weid = '{$_W['weid']}' $condition ORDER BY displayorder DESC, id DESC LIMIT ".($pindex - 1) * $psize.','.$psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('xc_article_article') . " WHERE weid = '{$_W['weid']}' $condition", $params);
			$pager = pagination($total, $pindex, $psize);

			include $this->template('article');
		} elseif ($foo == 'post') {
			$id = intval($_GPC['id']);
      $parent = array();
      $children = array();

      if (!empty($category)) {
        $children = '';
        foreach ($category as $cid => $cate) {
          if (!empty($cate['parentid'])) {
            $children[$cate['parentid']][] = $cate;
          } else {
            $parent[$cate['id']] = $cate;
          }
        }
      }

			//微站风格模板
			$template = $this->account_template();
      $adv_cache = pdo_fetch("SELECT * FROM ".tablename('xc_article_adv_cache')." WHERE weid={$_W['weid']}");
			if (!empty($id)) {
				$item = pdo_fetch("SELECT * FROM ".tablename('xc_article_article')." WHERE id = :id" , array(':id' => $id));
				if (empty($item)) {
					message('抱歉，文章不存在或是已经删除！', '', 'error');
				}
        $item['type'] = explode(',', $item['type']);
        $pcate = $item['pcate'];
        $ccate = $item['ccate'];
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['title'])) {
					message('标题不能为空，请输入标题!');
        }
        /*
        if (empty($_GPC['template'])) {
					message('必须选择模板!');
        }
         */
        list($gpc_template, $gpc_templatefile) = explode(':', $_GPC['template'], 2);
        $rec_size = count($_GPC['rec-title']);
        $rec = array();
        for ($i = 0; $i < $rec_size; $i++) {
          if (!empty($_GPC['rec-title'])) {
            $rec[] = array('title'=>$_GPC['rec-title'][$i], 'url'=>$_GPC['rec-url'][$i]);
          }
        }
        $searilaized_recommendation = serialize($rec);
        $data = array(
					'weid' => $_W['weid'],
					'iscommend' => intval($_GPC['option']['commend']),
					'ishot' => intval($_GPC['option']['hot']),
					'pcate' => intval($_GPC['category']['parentid']),
					'ccate' => intval($_GPC['category']['childid']),
					'template' => $gpc_template,
					'templatefile' => $gpc_templatefile,
					'title' => $_GPC['title'],
					'description' => $_GPC['description'],
					'sharethumb' => $_GPC['sharethumb'],
					'content' => htmlspecialchars_decode($_GPC['content']),
					'source' => $_GPC['source'],
					'author' => $_GPC['author'],
          'recommendation' => $searilaized_recommendation,
          'recommendation_source' => $_GPC['recommendation_source'],
					'displayorder' => intval($_GPC['displayorder']),
					'linkurl' => $_GPC['linkurl'],
					'redirect_url' => $_GPC['redirect_url'],
					'share_credit' => intval($_GPC['share_credit']),
					'click_credit' => intval($_GPC['click_credit']),
					'max_credit' => intval($_GPC['max_credit']),
					'per_user_credit' => intval($_GPC['per_user_credit']),
					'praise_count' => intval($_GPC['praise_count']),
					'read_count' => intval($_GPC['read_count']),
          'adv_on_off'=>$_GPC['adv_on_off'],
          'adv_top'=>$_GPC['adv_top'],
          'adv_status'=>$_GPC['adv_status'],
          'adv_bottom'=>$_GPC['adv_bottom'],
					'createtime' => TIMESTAMP,
				);
				if (!empty($_GPC['thumb'])) {
					$data['thumb'] = $_GPC['thumb'];
					file_delete($_GPC['thumb-old']);
				} elseif (!empty($_GPC['autolitpic'])) {
					$match = array();
					preg_match('/attachment\/(.*?)(\.gif|\.jpg|\.png|\.bmp)/', $_GPC['content'], $match);
					if (!empty($match[1])) {
						$data['thumb'] = $match[1].$match[2];
          } else {
            preg_match('/(http|https):\/\/(.*?)(\.gif|\.jpg|\.png|\.bmp)/', $_GPC['content'], $match);
            $data['thumb'] = $match[0];
          }
				}
				if (empty($id)) {
					pdo_insert('xc_article_article', $data);
				} else {
					unset($data['createtime']);
					pdo_update('xc_article_article', $data, array('id' => $id));
				}
        $adv_data = array(
          'weid'=>$_W['weid'],
          'adv_on_off'=>$_GPC['adv_on_off'],
          'adv_top'=>$_GPC['adv_top'],
          'adv_status'=>$_GPC['adv_status'],
          'adv_bottom'=>$_GPC['adv_bottom']
          );
        if (empty($adv_cache)) {
          pdo_insert('xc_article_adv_cache', $adv_data);
        } else {
          pdo_update('xc_article_adv_cache', $adv_data, array('weid'=>$_W['weid']));
        }
				message('文章更新成功！', $this->createWebUrl('article', array('foo' => 'display')), 'success');
			} else {
        $recommendation = unserialize($item['recommendation']);
				include $this->template('article');
			}

		} elseif ($foo == 'delete') {
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id, thumb FROM ".tablename('xc_article_article')." WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，文章不存在或是已经被删除！');
			}
			if (!empty($row['thumb'])) {
				file_delete($row['thumb']);
			}
			pdo_delete('xc_article_article', array('id' => $id));
			message('删除成功！', referer(), 'success');
		}
	}

  // 通过关键词查询文章，来组织自动回复
	public function doWebQuery() {
		global $_W, $_GPC;
		$kwd = $_GPC['keyword'];
		$sql = 'SELECT * FROM ' . tablename('xc_article_article') . ' WHERE `weid`=:weid AND `title` LIKE :title ORDER BY id DESC LIMIT 0,8';
		$params = array();
		$params[':weid'] = $_W['weid'];
		$params[':title'] = "%{$kwd}%";
		$ds = pdo_fetchall($sql, $params);
		foreach($ds as &$row) {
			$r = array();
			$r['id'] = $row['id'];
			$r['title'] = $row['title'];
			$r['description'] = cutstr(strip_tags($row['content']), 100);
			$r['thumb'] = $row['thumb'];
			$row['entry'] = $r;
		}
		include $this->template('query');
	}

	public function doWebDelete() {
		global $_W, $_GPC;
		$id = intval($_GPC['id']);
		pdo_delete('xc_article_article_reply', array('id' => $id));
		message('删除成功！', referer(), 'success');
	}

	private function trackRead($detail) {
    pdo_update('xc_article_article', array('read_count' => $detail['read_count'] + 1), array('id' => $detail['id']));
  }

  /* 算法
   * 1. 检查是否能追踪到分享人，如果不能，退出。
   * 2. 
   */
	private function trackAccess($detail) {
		global $_W, $_GPC;
    $credit_cost = 0;

		if (!isset($_GPC['shareby'])) {
			return; // 没有设置track
		}

		// 奖励积分
		// 并记录到积分记录表中
		$shareby = $_GPC['shareby'];
		$track_type = $_GPC['track_type'];
		$track_msg = $_GPC['track_msg'];
		$credit = 0;
    $clicker_id = $_W['fans']['from_user']; //$this->getUserOpenID();

		$fans = $this->fans_search($shareby);

    //WeUtility::logging('trackAcess-shareby ' . $shareby, $fans);

		if (empty($fans)) {
      //WeUtility::logging('invalid sharer', $shareby);
			return -1;
		}

    // 检查cookie，防止重复点击--站内多少秒内不得重复点击
    if (true) {
      $cookie_name = "xc_article-1-" . $_W['weid'];
      if (isset($_COOKIE[$cookie_name])) {
        return 0;
      } else {
        setcookie($cookie_name, 'killed', TIMESTAMP + $this->module['config']['prohibit_site_click_interval']); // 多少天后再点可以重复送分 
      }
    }

    // 检查cookie，防止重复点击-单篇文章多少秒内不得重复点击. 但是点击不同人分享给他的，他可以给不同人涨粉。
    if (true) {
      $cookie_name = "xc_article-1-" . $_W['weid'] . "-" . $shareby . "-" . $detail['id'];
      if (isset($_COOKIE[$cookie_name])) {
        return 0;
      } else {
        //setcookie($cookie_name, 'killed', TIMESTAMP+60*60*24*7); // 7天内的每个文章最多点一次，多余点击不送分
        setcookie($cookie_name, 'killed', TIMESTAMP + $this->module['config']['prohibit_single_article_click_interval']); // 多少天后再点可以重复送分 
      }
    }

    // 检查是否点击过，如果已经点击过，说明分享积分、首次点击积分均已送过
    $click_history = pdo_fetch("SELECT * FROM  " . tablename('xc_article_share_track') .
      " WHERE weid=:weid AND shareby=:shareby AND detail_id=:detail_id AND track_type=:track_type AND clicker_id=:clicker_id",
      array(':weid'=>$_W['weid'], ':shareby'=>$shareby, ':detail_id'=>$detail['id'], ':track_type'=>'click', ':clicker_id'=>$clicker_id));
    if (!empty($click_history)) {
      //WeUtility::logging('already have click history', $shareby);
      return 0;
    }


    $per_user_credit = pdo_fetch("SELECT SUM(credit) as total_credit FROM " . tablename('xc_article_share_track') . " WHERE detail_id = :detail_id AND shareby=:shareby",
      array(':detail_id'=>$detail['id'],  ':shareby'=> $shareby));
    //WeUtility::logging('track_type', $track_type);
    //WeUtility::logging('detail', $detail);
    //WeUtility::logging('per_user_credit', $per_user_credit);

		if ($track_type == 'click' and $detail['click_credit'] > 0) {
      if ( (0 >= $detail['max_credit'])
        or ($detail['per_user_credit'] > 0 and $per_user_credit['total_credit'] >= $detail['per_user_credit'])) {
        $credit = 0; // 不奖励积分，但记录到案
      } else {
        $credit = $detail['click_credit'];
        $credit_cost += $credit;
      }
      $this->addCredit($shareby, $credit);
			pdo_insert('xc_article_share_track',
				array(
					'weid' => $_W['weid'],
					'credit' => $credit,
					'shareby' => $shareby,
					'track_type' => $track_type,
					'track_msg' => $track_msg,
					'detail_id' => $detail['id'],
					'title' => $detail['title'],
					'access_time'=>TIMESTAMP,
          'ip' => getip(),
          'clicker_id' => $clicker_id,
        ));
    }

    // 追送分享积分
    if ($track_type == 'click'  and $detail['share_credit'] > 0 and !empty($shareby))
    {
      if ($credit >= $detail['max_credit']) {
        $credit = 0; // 不奖励积分，但记录到案
      } else {
        $credit = $detail['share_credit'];
        $credit_cost += $credit;
      }

      $share_credit_info = pdo_fetch("SELECT * FROM  " . tablename('xc_article_share_track') .
        " WHERE weid=:weid AND shareby=:shareby AND detail_id=:detail_id AND track_type=:track_type",
        array(':weid'=>$_W['weid'], ':shareby'=>$shareby, ':detail_id'=>$detail['id'], ':track_type'=>'share'));
      if (false == $share_credit_info) { // 首次点击，还没有送过分享分。现在送分享积分！
        $this->addCredit($shareby, $credit);
        pdo_insert('xc_article_share_track',
          array(
            'weid' => $_W['weid'],
            'credit' => $credit,
            'shareby' => $shareby,
            'track_type' => 'share',
            'track_msg' => $track_msg,
            'detail_id' => $detail['id'],
            'title' => $detail['title'],
            'access_time'=>TIMESTAMP,
            'ip' => getip(),
            'clicker_id' => $clicker_id,
          ));
      }
    }
    // 减去剩余积分
    if ($credit_cost > 0 and !empty($detail['id'])) {
      $sql = "UPDATE " . tablename('xc_article_article') . " SET max_credit = max_credit - " . $credit_cost . " WHERE id=:id AND weid=:weid";
      pdo_query($sql, array(":weid"=>$_W['weid'], ":id"=>$detail['id']));
    }
    WeUtility::logging('byebye '. $shareby);
	}


	public function doWebShareTrack() {
		global $_W, $_GPC;
		$psize = 200;
		$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($op == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$condition = '';
			$params = array();
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND title LIKE :keyword";
				$params[':keyword'] = "%{$_GPC['keyword']}%";
			}
			if (!empty($_GPC['shareby'])) {
				$condition .= " AND shareby = :shareby";
				$params[':shareby'] = "{$_GPC['shareby']}";
			}
			$list = pdo_fetchall("SELECT * FROM ".tablename('xc_article_share_track')." WHERE weid = '{$_W['weid']}' $condition ORDER BY access_time DESC LIMIT ".($pindex - 1) * $psize.','.$psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('xc_article_share_track') . " WHERE weid = '{$_W['weid']}' $condition", $params);
			$users = array();
			foreach($list as $i_item) {
				$users[] = $i_item['shareby'];
			}
			$fans = $this->fans_search($users);
			$pager = pagination($total, $pindex, $psize);

		} else if ($op == 'display_user_summary') {
			$pindex = max(1, intval($_GPC['page']));
			$condition = '';
			$params = array();
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND title LIKE :keyword";
				$params[':keyword'] = "%{$_GPC['keyword']}%";
			}
			$list = pdo_fetchall("SELECT SUM(credit)  as total_credit, shareby, count(credit) as total_click  FROM ".tablename('xc_article_share_track')." WHERE weid = '{$_W['weid']}' $condition GROUP BY shareby ORDER BY access_time DESC LIMIT ".($pindex - 1) * $psize.','.$psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM (SELECT COUNT(*) FROM ' . tablename('xc_article_share_track') . " WHERE weid = '{$_W['weid']}' $condition GROUP BY shareby) as tmp", $params);
			$users = array();
			foreach($list as $item) {
				$users[] = $item['shareby'];
			}
			$fans = $this->fans_search($users);
			$pager = pagination($total, $pindex, $psize);

		} else if ($op == 'display_article_user_summary') {
			$pindex = max(1, intval($_GPC['page']));
			$condition = '';
			$params = array();
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND title LIKE :keyword";
				$params[':keyword'] = "%{$_GPC['keyword']}%";
			}
			$list = pdo_fetchall("SELECT SUM(credit) as total_credit, count(credit) as total_click, title, shareby, detail_id  FROM ".tablename('xc_article_share_track')." WHERE weid = '{$_W['weid']}' $condition GROUP BY detail_id, shareby ORDER BY access_time DESC LIMIT ".($pindex - 1) * $psize.','.$psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM (SELECT COUNT(*) FROM ' . tablename('xc_article_share_track') . " WHERE weid = '{$_W['weid']}' $condition GROUP BY detail_id, shareby) as tmp", $params);
			$users = array();
			foreach($list as $item) {
				$users[] = $item['shareby'];
			}
			$fans = $this->fans_search($users);
			$pager = pagination($total, $pindex, $psize);
		}
		else if ($op == 'display_article_summary') {
			$pindex = max(1, intval($_GPC['page']));
			$condition = '';
			$params = array();
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND title LIKE :keyword";
				$params[':keyword'] = "%{$_GPC['keyword']}%";
			}
			$list = pdo_fetchall("SELECT SUM(credit) as total_credit, count(credit) as total_click, title, detail_id  FROM ".tablename('xc_article_share_track')." WHERE weid = '{$_W['weid']}' $condition GROUP BY detail_id ORDER BY access_time DESC LIMIT ".($pindex - 1) * $psize.','.$psize, $params);
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM (SELECT count(*)  FROM ".tablename('xc_article_share_track')." WHERE weid = '{$_W['weid']}' $condition GROUP BY detail_id) as tmp", $params);
			$pager = pagination($total, $pindex, $psize);


		} else if ($op == 'delete') {
			$id = intval($_GPC['id']);
			if (empty($id)) {
				message("没有指定记录", "", "error");
			}
			pdo_delete("xc_article_share_track", array("id"=>$id));
			message("删除成功", $this->createWebUrl("sharetrack"), "success");


		}
		include $this->template('sharetrack');
	}


	public function doWebHelp() {
		global $_W;
		include $this->template('help');
	}


	public function doMobileList() {
		global $_GPC, $_W;
    $cid = intval($_GPC['cid']);
    /* 本查询是为2级目录服务，对于一级目录数据读取，见model.php */
		$category = pdo_fetch("SELECT * FROM ".tablename('xc_article_article_category')." WHERE id = '{$cid}' ");
		if (empty($category)) {
			message('分类不存在或是已经被删除！');
		}
		if (!empty($category['linkurl'])) {
			header('Location: '.$category['linkurl']);
			exit;
		}
		$title = $category['name'];

    $_share = array();
    $_share['title'] = $title;
    $_share['imgUrl'] = $_W['attachurl'] . $category['thumb'];
    $_share['desc'] = $category['description'];
    $_share['link'] =  $_W['siteroot'] . '/app/' . $this->createMobileUrl('category', array('cid'=>$category['id'])) . '&shareby=' . $_W['fans']['from_user'] . '&track_type=click';

    //独立选择分类模板
    if(!empty($category['template'])) {
      $_W['account']['template'] = $category['template'];
    }
    if(!empty($category['templatefile'])) {
      include $this->template($category['templatefile']);
      exit;
    }
    include $this->template('list');
  }

  // 返回0表示praise成功，1表示已经praise过, 2表示其他异常
	public function doMobilePraise() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$sql = "SELECT * FROM " . tablename('xc_article_article') . " WHERE `id`=:id";
		$detail = pdo_fetch($sql, array(':id'=>$id));
    if (!empty($detail)) {
      // 检查cookie，防止重复点击
      if (true) {
        $cookie_name = "xc_article-2-" . $_W['weid'] . "-" . $detail['id'];
        if (isset($_COOKIE[$cookie_name])) {
          die(json_encode(array("result"=>1)));
        } else {
          setcookie($cookie_name, 'read', TIMESTAMP+60*60*24*7); // 7天内的每个文章最多点一次，多余点击不送分
        }
      }
      pdo_update('xc_article_article', array('praise_count' => $detail['praise_count'] + 1), array('id' => $id));
      die(json_encode(array("result"=>0)));
    }
    die(json_encode(array("result"=>2)));
  }

	public function doMobileDetail() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$sql = "SELECT * FROM " . tablename('xc_article_article') . " WHERE `id`=:id";
		$detail = pdo_fetch($sql, array(':id'=>$id));
		$detail = istripslashes($detail);

    // $clicker_id = $this->getUserOpenID();
    if (!empty($detail)) {
      $this->trackRead($detail);
    }

		if (!empty($detail['redirect_url'])) {
			header('Location: '.$detail['redirect_url']);
			exit(0);
    }

    if ( (!empty($_GPC['shareby'])) and ($_GPC['shareby'] != $_W['fans']['from_user']) ) {
      $this->trackAccess($detail);
      // 如果是分享者自己看到这个页面，则不跳转。分享者分享到朋友圈后，好友打开后跳转。
      if (!empty($_GPC['linkurl']) && strlen($_GPC['linkurl']) > 0) {
        header('Location:' . base64_decode($_GPC['linkurl']));
        exit;
      }
    } else {
			// nop; // 自己点击无效
			// echo $_GPC['shareby'];
    }

    $recommendation = unserialize($detail['recommendation']);
    $detail['thumb'] = trim((strpos($detail['thumb'], 'http://') === FALSE) ? $_W['attachurl'] . $detail['thumb'] : $detail['thumb']);
    $detail['title'] = $this->parseTemplate($detail, $detail['title']);
    $detail['source'] = $this->parseTemplate($detail, $detail['source']);
    $detail['author'] = $this->parseTemplate($detail, $detail['author']);
    if ($detail['adv_on_off'] == 'off') {
      $detail['adv_top'] = $detail['adv_status'] = $detail['adv_bottom'] = '';
    } else {
      $detail['adv_top'] = $this->parseTemplate($detail, $detail['adv_top']);
      $detail['adv_status'] = $this->parseTemplate($detail, $detail['adv_status']);
      $detail['adv_bottom'] = $this->parseTemplate($detail, $detail['adv_bottom']);
    }
    $title = $detail['title'];

    $_share = array();
    $_share['title'] = $title;
    $_share['imgUrl'] = $_W['attachurl'] . $detail['sharethumb'];
    $_share['desc'] = $detail['description'];
    $_share['link'] =  $_W['siteroot'] . '/app/' . $this->createMobileUrl('detail', array('id'=>$detail['id'])) . '&shareby=' . $_W['fans']['from_user'] . '&track_type=click';

		//独立选择内容模板
		if(!empty($detail['template'])) {
			$_W['account']['template'] = $detail['template'];
		}
    if(!empty($detail['templatefile'])) {
      include $this->template($detail['templatefile']);
      exit;
    }
		include $this->template('detail');
	}

	// reply ajax request
	public function doMobileShareTrack() {
		//if ($_GPC['shareby'] == $_W['fans']['from_user']) { 
		//	return; // 自己分享无效
		//}
		global $_GPC;
		$id = intval($_GPC['id']);
		$sql = "SELECT * FROM " . tablename('xc_article_article') . " WHERE `id`=:id";
		$detail = pdo_fetch($sql, array(':id'=>$id));
		$this->trackAccess($detail);
	}


	public function getCategoryTiles() {
		global $_W;
		$category = pdo_fetchall("SELECT id, name FROM ".tablename('xc_article_article_category')." WHERE enabled = '1' AND weid = '{$_W['weid']}' ORDER BY parentid ASC, displayorder ASC, id ASC ");
		if (!empty($category)) {
			foreach ($category as $row) {
				$urls[] = array('title' => $row['name'], 'url' => $this->createMobileUrl('list', array('cid' => $row['id'])));
			}
			return $urls;
		}
  }

  private function addCredit($from_user, $credit_value) {
    global $_GPC;
    load()->model('mc');
    $uid = mc_openid2uid($from_user);
    mc_credit_update($uid, 'credit1', $credit_value, '文章分享点击');
  }

	public function doWebAjaxSearch() {
		global $_GPC, $_W;
    $result = array();
    if (is_numeric($_GPC['search-keyword'])) {
      $id = intval($_GPC['search-keyword']);
      $cond = "`id` = {$id}";
    } else {
      $keyword = $_GPC['search-keyword'];
      $cond = "`title` LIKE '%{$keyword}%'";
    }
    $sql = "SELECT title, id FROM " . tablename('xc_article_article') . " WHERE weid={$_W['weid']} AND {$cond} LIMIT 1";
    $detail = pdo_fetch($sql, array(':id'=>$id));
    if (false != $detail) {
      $result = array('err'=>0, 'title'=>$detail['title'], 'url'=>$this->createMobileUrl('detail', array('id'=>$detail['id'])));
    } else {
      $result = array('err'=>1); // 没找到
    }
    exit(json_encode($result));
	}

  private function parseTemplate($detail, $str) {
    $str = preg_replace('/share_credit/', $detail['share_credit'], $str);//分享积分
    $str = preg_replace('/click_credit/', $detail['click_credit'], $str);//点击积分
    $str = preg_replace('/max_credit/', $detail['max_credit'], $str);//剩余积分
    $str = preg_replace('/read_count/', $detail['read_count'], $str);//阅读数
    $str = preg_replace('/source/', $detail['source'], $str);//来源
    $str = preg_replace('/author/', $detail['author'], $str);//作者
    $str = preg_replace('/title/', $detail['title'], $str); //标题
    $str = preg_replace('/createtime/', date('Y-m-d', $detail['createtime']), $str); // 创建时间
    return $str;
  }

  public function doMobileGetContent() {
    global $_W, $_GPC;
    $url = urldecode($_GPC['url']);
    if (!empty($url)) {
      $data = file_get_contents($url);
      include $this->template('content');
      exit(0);
    }
    include $this->template('userform');
  }

  private function fans_search($openid) {
    global $_W;
    $result = array();
    $openid_str = implode("','", is_array($openid) ? $openid : array($openid));
    $query = 'SELECT a.openid from_user, b.* FROM ' . tablename('mc_mapping_fans') . ' a LEFT JOIN ' . tablename('mc_members') . ' b '
      . ' ON a.uid = b.uid WHERE a.openid IN (\'' . $openid_str . '\') AND a.uniacid='.$_W['uniacid'];
    if (is_string($openid)) {
      $result = pdo_fetch($query, null, 'from_user');
    } else if (is_array($openid)) {
      $result = pdo_fetchall($query, null, 'from_user');
    }
    return $result;
  }

}
