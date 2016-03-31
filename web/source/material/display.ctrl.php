<?php
/**
 * [Weizan System] Copyright (c) 2014 012WZ.COM
 * Weizan is NOT a free software, it under the license terms, visited http://www.012wz.com/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
uni_user_permission_check('mc_material_display');
$_W['page']['title'] = '永久素材-微信素材';
$dos = array('image', 'del', 'export', 'news', 'down', 'list', 'purview', 'modal', 'send');
$do = in_array($do, $dos) ? $do : 'list';
if($do == 'down') {
	load()->func('file');
	set_time_limit(0);
	$acc = WeAccount::create();
	if(is_error($acc)) {
		message($acc, '', 'ajax');
	}
	$post = $_GPC['__input'];
	$type = $types = $post['type'];
	$pindex = max(1, intval($post['page']));
	$psize = 15;
	$offset = ($pindex - 1) * $psize;
	$result = $acc->batchGetMaterial($type, $offset, $psize);
	if(is_error($result)) {
		message($result, '', 'ajax');
	}
	if($result['item_count'] == 0 || count($result['data']) == 0) {
		message(error(-2, $result['total_count']), '', 'ajax');
	}
	$fail = array();
	if($type == 'voice') {
		$type = 'audio';
	}
	foreach($result['data'] as $data) {
		if($type != 'news') {
			$media = pdo_get('wechat_attachment', array('uniacid' => $_W['uniacid'], 'media_id' => $data['media_id']));
			$is_down = 0;
			$url = $tag = '';
			if($type == 'image') {
				if(!empty($media) && !empty($media['attachment'])) {
					if(strexists($media['attachment'], 'https://mmbiz.qlogo.cn') || (file_exists(ATTACHMENT_ROOT . $media['attachment']))) {
						continue;
					}
				}
				if(strexists($data['url'], 'https://mmbiz.qlogo.cn')) {
					$url = $tag = $data['url'];
					$is_down = 1;
				}
			} elseif($types == 'voice') {
				if(!empty($media) && !empty($media['attachment'])) {
					if(file_exists(ATTACHMENT_ROOT . $media['attachment'])) {
						continue;
					}
				}
			}

			if(!$is_down) {
								$stream = $acc->getMaterial($data['media_id'], $types);
				if(is_error($stream)) {
					$data['message'] = $stream['message'];
					$fail[$data['media_id']] = $data;
					continue;
				}
				if($type == 'image' || $type == 'audio') {
					$path = ATTACHMENT_ROOT . "/{$type}s/{$_W['uniacid']}/material";
					mkdirs($path);
					$is_ok = file_put_contents($path."/{$data['media_id']}", $stream);
					if(!$is_ok && !$fail[$data['media_id']]) {
						$data['message'] = '保存文件失败，请检查目录权限';
						$fail[$data['media_id']] = $data;
					}
					$tag = '';
					$url = "{$type}s/{$_W['uniacid']}/material/{$data['media_id']}";
				} elseif($type == 'video') {
					$tag = $url = '';
					$tag = array(
						'title' => $stream['title'],
						'description' => $stream['description'],
						'down_url' => $stream['down_url'],
					);
					$tag = iserializer($tag);
				}
			}

			$insert = array(
				'uniacid' => $_W['uniacid'],
				'acid' => $_W['acid'],
				'uid' => $_W['uid'],
				'filename' => $data['name'],
				'attachment' => $url,
				'media_id' => $data['media_id'],
				'type' => $types,
				'model' => 'perm',
				'tag' => $tag,
				'createtime' => $data['update_time']
			);
			if(empty($media)) {
				pdo_insert('wechat_attachment', $insert);
			} else {
				pdo_update('wechat_attachment', $insert, array('uniacid' => $_W['uniacid'], 'media_id' => $data['media_id']));
			}
		} else {
			$media = pdo_get('wechat_attachment', array('uniacid' => $_W['uniacid'], 'media_id' => $data['media_id']));
			if(empty($media)) {
				$insert = array(
					'uniacid' => $_W['uniacid'],
					'acid' => $_W['acid'],
					'uid' => $_W['uid'],
					'media_id' => $data['media_id'],
					'type' => $types,
					'model' => 'perm',
					'createtime' => $data['update_time']
				);
				pdo_insert('wechat_attachment', $insert);
				$insert_id = pdo_insertid();
			} else {
				pdo_update('wechat_attachment', array('createtime' => $data['update_time']), array('uniacid' => $_W['uniacid'], 'media_id' => $data['media_id']));
				$insert_id = $media['id'];
				pdo_delete('wechat_news', array('uniacid' => $_W['uniacid'], 'attach_id' => $insert_id));
			}
						$items = $data['content']['news_item'];
			if(!empty($items)) {
				foreach($items as $item) {
					$item['attach_id'] = $insert_id;
					$item['uniacid'] = $_W['uniacid'];
					pdo_insert('wechat_news', $item);
				}
			}
		}
	}
	message(error($result['total_count'], array('fail' => $fail, 'item_count' => $result['item_count'])), '', 'ajax');
}

if($do == 'list') {
	$type = trim($_GPC['type']) ? trim($_GPC['type']) : 'image';
	$condition = " WHERE uniacid = :uniacid AND type = :type AND model = :model AND media_id != ''";
	$params = array(':uniacid' => $_W['uniacid'], ':type' => $type, ':model' => 'perm');
	$id = intval($_GPC['id']);
	if($id > 0) {
		$condition .= ' AND id = :id';
		$params[':id'] = $id;
	}

	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$limit = " ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ", {$psize}";

	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wechat_attachment') . $condition, $params);
	$lists = pdo_fetchall('SELECT * FROM ' . tablename('wechat_attachment') . $condition . $limit, $params);
	if(!empty($lists)) {
		foreach($lists as &$row) {
			if($type == 'video') {
				$row['tag'] = iunserializer($row['tag']);
			} elseif($type == 'news') {
				$row['items'] = pdo_getall('wechat_news', array('uniacid' => $_W['uniacid'], 'attach_id' => $row['id']));
			}
		}
	}
	$pager = pagination($total, $pindex, $psize);

		$groups = pdo_fetch('SELECT * FROM ' . tablename('mc_fans_groups') . ' WHERE uniacid = :uniacid AND acid = :acid', array(':uniacid' => $_W['uniacid'], ':acid' => $_W['acid']));
	if(!empty($groups)) {
		$groups = iunserializer($groups['groups']);
	}
}

if($do == 'purview') {
	$wxname = trim($_GPC['wxname']);
	if(empty($wxname)) {
		exit('微信号不能为空');
	}
	$type = trim($_GPC['type']);
	$id = intval($_GPC['id']);
	$media = pdo_get('wechat_attachment', array('uniacid' => $_W['uniacid'], 'id' => $id));
	if(empty($media)) {
		exit('素材不存在或已经删除');
	}
	$media_id = trim($media['media_id']);
	$acc = WeAccount::create();
	$data = $acc->fansSendPreview($wxname, $media_id, $type);
	if(is_error($data)) {
		exit($data['message']);
	}
	exit('success');
}

if($do == 'send') {
	$group = intval($_GPC['group']);
	$type = trim($_GPC['type']);
	$id = intval($_GPC['id']);
	$media = pdo_get('wechat_attachment', array('uniacid' => $_W['uniacid'], 'id' => $id));
	if(empty($media)) {
		exit('素材不存在或已经删除');
	}
	$media_id = trim($media['media_id']);
	$acc = WeAccount::create();
	$data = $acc->fansSendAll($group, $type, $media['media_id']);
	if(is_error($data)) {
		exit($data['message']);
	}
		$groups = pdo_fetch('SELECT * FROM ' . tablename('mc_fans_groups') . ' WHERE uniacid = :uniacid AND acid = :acid', array(':uniacid' => $_W['uniacid'], ':acid' => $_W['acid']));
	if(!empty($groups)) {
		$groups = iunserializer($groups['groups']);
	}
	$record = array(
		'uniacid' => $_W['uniacid'],
		'acid' => $_W['acid'],
		'groupname' => $groups[$group]['name'],
		'fansnum' => $groups[$group]['count'],
		'msgtype' => $type,
		'group' => $group,
		'attach_id' => $id,
		'status' => 0,
		'type' => 0,
		'sendtime' => TIMESTAMP,
		'createtime' => TIMESTAMP,
	);
	pdo_insert('mc_mass_record', $record);
	exit('success');
}



if($do == 'del') {
	$id = intval($_GPC['id']);
	$media = pdo_get('wechat_attachment', array('uniacid' => $_W['uniacid'], 'id' => $id));
	if(empty($media)) {
		exit('素材不存在或已经删除');
	}
	$media_id = trim($media['media_id']);
	$acc = WeAccount::create();
	$data = $acc->delMaterial($media_id);
	if(is_error($data)) {
		exit($data['message']);
	} else {
		pdo_delete('wechat_attachment', array('uniacid' => $_W['uniacid'], 'id' => $id));
		if($type == 'image' || $type == 'voice') {
			$path = ATTACHMENT_ROOT . "/{$media['type']}s/{$_W['uniacid']}/material/{$media['$media_id']}";
			@unlink($path);
		} elseif($type == 'news') {
			pdo_delete('wechat_news', array('uniacid' => $_W['uniacid'], 'attach_id' => $id));
		}
		exit('success');
	}
}
template('material/display');
