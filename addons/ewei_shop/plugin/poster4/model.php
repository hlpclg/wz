<?php

//微赞科技 by QQ:800083075 http://www.012wz.com/
if (!defined('IN_IA')) {
	die('Access Denied');
}
if (!class_exists('PosterModel')) {
	class PosterModel extends PluginModel
	{
		public function checkScan()
		{
			global $_W, $_GPC;
			$openid = m('user')->getOpenid();
			$posterid = intval($_GPC['posterid']);
			if (empty($posterid)) {
				return;
			}
			$poster = pdo_fetch('select id,times from ' . tablename('ewei_shop_poster') . ' where id=:id and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':id' => $posterid));
			if (empty($poster)) {
				return;
			}
			$mid = intval($_GPC['mid']);
			if (empty($mid)) {
				return;
			}
			$parent = m('member')->getMember($mid);
			if (empty($parent)) {
				return;
			}
			$this->scanTime($openid, $parent['openid'], $poster);
		}
		public function scanTime($openid, $from_openid, $poster)
		{
			if ($openid == $from_openid) {
				return;
			}
			global $_W, $_GPC;
			$scancount = pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_poster_scan') . ' where openid=:openid  and posterid=:posterid and uniacid=:uniacid limit 1', array(':openid' => $openid, ':posterid' => $poster['id'], ':uniacid' => $_W['uniacid']));
			if ($scancount <= 0) {
				$scan = array('uniacid' => $_W['uniacid'], 'posterid' => $poster['id'], 'openid' => $openid, 'from_openid' => $from_openid, 'scantime' => time());
				pdo_insert('ewei_shop_poster_scan', $scan);
				pdo_update('ewei_shop_poster', array('times' => $poster['times'] + 1), array('id' => $poster['id']));
			}
		}
		public function createCommissionPoster($openid, $goodsid = 0)
		{
			global $_W;
			$type = 2;
			if (!empty($goodsid)) {
				$type = 3;
			}
			$poster = pdo_fetch('select * from ' . tablename('ewei_shop_poster') . ' where uniacid=:uniacid and type=:type and isdefault=1 limit 1', array(':uniacid' => $_W['uniacid'], ':type' => $type));
			if (empty($poster)) {
				return '';
			}
			$member = m('member')->getMember($openid);
			if (empty($poster)) {
				return "";
			}
			$qr = $this->getQR($poster, $member, $goodsid);
			if (empty($qr)) {
				return "";
			}
			return $this->createPoster($poster, $member, $qr, false);
		}
		public function getQR($poster, $member, $goodsid = 0)
		{
			global $_W, $_GPC;
			$acid = $_W['acid'];
			if ($poster['type'] == 1) {
				$qrimg = m('qrcode')->createShopQrcode($member['id'], $poster['id']);
				$qr = pdo_fetch('select * from ' . tablename('ewei_shop_poster_qr') . ' where openid=:openid and acid=:acid and type=:type limit 1', array(':openid' => $member['openid'], ':acid' => $_W['acid'], ':type' => 1));
				if (empty($qr)) {
					$qr = array('acid' => $acid, 'openid' => $member['openid'], 'type' => 1, 'qrimg' => $qrimg);
					pdo_insert('ewei_shop_poster_qr', $qr);
					$qr['id'] = pdo_insertid();
				}
				$qr['current_qrimg'] = $qrimg;
				return $qr;
			} else {
				if ($poster['type'] == 2) {
					$p = p('commission');
					if ($p) {
						$qrimg = $p->createMyShopQrcode($member['id'], $poster['id']);
						$qr = pdo_fetch('select * from ' . tablename('ewei_shop_poster_qr') . ' where openid=:openid and acid=:acid and type=:type limit 1', array(':openid' => $member['openid'], ':acid' => $_W['acid'], ':type' => 2));
						if (empty($qr)) {
							$qr = array('acid' => $acid, 'openid' => $member['openid'], 'type' => 2, 'qrimg' => $qrimg);
							pdo_insert('ewei_shop_poster_qr', $qr);
							$qr['id'] = pdo_insertid();
						}
						$qr['current_qrimg'] = $qrimg;
						return $qr;
					}
				} else {
					if ($poster['type'] == 3) {
						$qrimg = m('qrcode')->createGoodsQrcode($member['id'], $goodsid, $poster['id']);
						$qr = pdo_fetch('select * from ' . tablename('ewei_shop_poster_qr') . ' where openid=:openid and acid=:acid and type=:type and goodsid=:goodsid limit 1', array(':openid' => $member['openid'], ':acid' => $_W['acid'], ':type' => 3, ':goodsid' => $goodsid));
						if (empty($qr)) {
							$qr = array('acid' => $acid, 'openid' => $member['openid'], 'type' => 3, 'goodsid' => $goodsid, 'qrimg' => $qrimg);
							pdo_insert('ewei_shop_poster_qr', $qr);
							$qr['id'] = pdo_insertid();
						}
						$qr['current_qrimg'] = $qrimg;
						return $qr;
					} else {
						if ($poster['type'] == 4) {
							$uniacccount = WeAccount::create($acid);
							$qr = pdo_fetch('select * from ' . tablename('ewei_shop_poster_qr') . ' where openid=:openid and acid=:acid and type=4 limit 1', array(':openid' => $member['openid'], ':acid' => $acid));
							if (empty($qr)) {
								$sceneid = pdo_fetchcolumn("SELECT qrcid FROM " . tablename('qrcode') . " WHERE acid = :acid and model=2 ORDER BY qrcid DESC LIMIT 1", array(':acid' => $acid));
								$barcode['action_info']['scene']['scene_id'] = intval($sceneid) + 1;
								if ($barcode['action_info']['scene']['scene_id'] > 100000) {
									return error(-1, '抱歉，永久二维码已经生成最大数量，请先删除一些。');
								}
								$barcode['action_name'] = 'QR_LIMIT_SCENE';
								$result = $uniacccount->barCodeCreateFixed($barcode);
								if (is_error($result)) {
									return error(-1, "公众平台返回接口错误. <br />错误代码为: {$result['errorcode']} <br />错误信息为: {$result['message']}");
								}
								$qrimg = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=" . $result['ticket'];
								$ims_qrcode = array('uniacid' => $_W['uniacid'], 'acid' => $_W['acid'], 'qrcid' => $barcode['action_info']['scene']['scene_id'], "model" => 2, "name" => "EWEI_SHOP_POSTER_QRCODE", "keyword" => 'EWEI_SHOP_POSTER', "expire" => 0, "createtime" => time(), "status" => 1, 'url' => $result['url'], "ticket" => $result['ticket']);
								pdo_insert('qrcode', $ims_qrcode);
								$qr = array('acid' => $acid, 'openid' => $member['openid'], 'type' => 4, 'sceneid' => $barcode['action_info']['scene']['scene_id'], 'ticket' => $result['ticket'], 'qrimg' => $qrimg, 'url' => $result['url']);
								pdo_insert('ewei_shop_poster_qr', $qr);
								$qr['id'] = pdo_insertid();
								$qr['current_qrimg'] = $qrimg;
							} else {
								$qr['current_qrimg'] = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=" . $qr['ticket'];
							}
							return $qr;
						}
					}
				}
			}
		}
		public function getRealData($data)
		{
			$data['left'] = intval(str_replace('px', '', $data['left'])) * 2;
			$data['top'] = intval(str_replace('px', '', $data['top'])) * 2;
			$data['width'] = intval(str_replace('px', '', $data['width'])) * 2;
			$data['height'] = intval(str_replace('px', '', $data['height'])) * 2;
			$data['size'] = intval(str_replace('px', '', $data['size'])) * 2;
			$data['src'] = tomedia($data['src']);
			return $data;
		}
		public function createImage($imgurl)
		{
			load()->func('communication');
			$resp = ihttp_request($imgurl);
			return imagecreatefromstring($resp['content']);
		}
		public function mergeImage($target, $data, $imgurl)
		{
			$img = $this->createImage($imgurl);
			$w = imagesx($img);
			$h = imagesy($img);
			imagecopyresized($target, $img, $data['left'], $data['top'], 0, 0, $data['width'], $data['height'], $w, $h);
			imagedestroy($img);
			return $target;
		}
		public function mergeText($target, $data, $text)
		{
			$font = IA_ROOT . "/addons/ewei_shop/static/fonts/msyh.ttf";
			$colors = $this->hex2rgb($data['color']);
			$color = imagecolorallocate($target, $colors['red'], $colors['green'], $colors['blue']);
			imagettftext($target, $data['size'], 0, $data['left'], $data['top'] + $data['size'], $color, $font, $text);
			return $target;
		}
		function hex2rgb($colour)
		{
			if ($colour[0] == '#') {
				$colour = substr($colour, 1);
			}
			if (strlen($colour) == 6) {
				list($r, $g, $b) = array($colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5]);
			} elseif (strlen($colour) == 3) {
				list($r, $g, $b) = array($colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]);
			} else {
				return false;
			}
			$r = hexdec($r);
			$g = hexdec($g);
			$b = hexdec($b);
			return array('red' => $r, 'green' => $g, 'blue' => $b);
		}
		public function createPoster($poster, $member, $qr, $upload = true)
		{
			global $_W;
			$path = IA_ROOT . "/addons/ewei_shop/data/poster/" . $_W['uniacid'] . "/";
			if (!is_dir($path)) {
				load()->func('file');
				mkdirs($path);
			}
			if (!empty($qr['goodsid'])) {
				$goods = pdo_fetch('select id,title,thumb,commission_thumb,marketprice,productprice from ' . tablename('ewei_shop_goods') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $qr['goodsid'], ':uniacid' => $_W['uniacid']));
				if (empty($goods)) {
					m('message')->sendCustomNotice($member['openid'], '未找到商品，无法生成海报');
					die;
				}
			}
			$md5 = md5(json_encode(array('openid' => $member['openid'], 'goodsid' => $qr['goodsid'], 'data' => $poster['data'], 'version' => 1)));
			$file = $md5 . '.jpg';
			if (!is_file($path . $file) || $qr['qrimg'] != $qr['current_qrimg']) {
				set_time_limit(0);
				@ini_set('memory_limit', '256M');
				$target = imagecreatetruecolor(640, 1008);
				$bg = $this->createImage(tomedia($poster['bg']));
				imagecopy($target, $bg, 0, 0, 0, 0, 640, 1008);
				imagedestroy($bg);
				$data = json_decode(str_replace('&quot;', "'", $poster['data']), true);
				foreach ($data as $d) {
					$d = $this->getRealData($d);
					if ($d['type'] == 'head') {
						$avatar = preg_replace('/\/0$/i', '/96', $member['avatar']);
						$target = $this->mergeImage($target, $d, $avatar);
					} else {
						if ($d['type'] == 'img') {
							$target = $this->mergeImage($target, $d, $d['src']);
						} else {
							if ($d['type'] == 'qr') {
								$target = $this->mergeImage($target, $d, tomedia($qr['current_qrimg']));
							} else {
								if ($d['type'] == 'nickname') {
									$target = $this->mergeText($target, $d, $member['nickname']);
								} else {
									if (!empty($goods)) {
										if ($d['type'] == 'title') {
											$target = $this->mergeText($target, $d, $goods['title']);
										} else {
											if ($d['type'] == 'thumb') {
												$thumb = !empty($goods['commission_thumb']) ? tomedia($goods['commission_thumb']) : tomedia($goods['thumb']);
												$target = $this->mergeImage($target, $d, $thumb);
											} else {
												if ($d['type'] == 'marketprice') {
													$target = $this->mergeText($target, $d, $goods['marketprice']);
												} else {
													if ($d['type'] == 'productprice') {
														$target = $this->mergeText($target, $d, $goods['productprice']);
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
				imagejpeg($target, $path . $file);
				imagedestroy($target);
				if ($qr['qrimg'] != $qr['current_qrimg']) {
					pdo_update('ewei_shop_poster_qr', array('qrimg' => $qr['current_qrimg']), array('id' => $qr['id']));
				}
			}
			$img = $_W['siteroot'] . "addons/ewei_shop/data/poster/" . $_W['uniacid'] . "/" . $file;
			if (!$upload) {
				return $img;
			}
			if ($qr['qrimg'] != $qr['current_qrimg'] || empty($qr['mediaid']) || empty($qr['createtime']) || $qr['createtime'] + 3600 * 24 * 3 - 7200 < time()) {
				$mediaid = $this->uploadImage($path . $file);
				$qr['mediaid'] = $mediaid;
				pdo_update('ewei_shop_poster_qr', array('mediaid' => $mediaid, 'createtime' => time()), array('id' => $qr['id']));
			}
			return array('img' => $img, 'mediaid' => $qr['mediaid']);
		}
		public function uploadImage($img)
		{
			load()->func('communication');
			$account = m('common')->getAccount();
			$access_token = $account->fetch_token();
			$resp = ihttp_request("http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token={$access_token}&type=image", array('media' => '@' . $img));
			$content = @json_decode($resp['content'], true);
			return $content['media_id'];
		}
		public function getQRBySceneid($sceneid = 0)
		{
			global $_W;
			if (empty($sceneid)) {
				return false;
			}
			return pdo_fetch('select * from ' . tablename('ewei_shop_poster_qr') . ' where sceneid=:sceneid and acid=:acid and type=4 limit 1', array(':sceneid' => $sceneid, ':acid' => $_W['acid']));
		}
		public function checkMember($openid = '')
		{
			global $_W;
			$acc = WeiXinAccount::create($_W['acid']);
			$userinfo = $acc->fansQueryInfo($openid);
			load()->model('mc');
			$uid = mc_openid2uid($openid);
			pdo_update('mc_members', array('nickname' => $userinfo['nickname'], 'gender' => $userinfo['sex'], 'nationality' => $userinfo['country'], 'resideprovince' => $userinfo['province'], 'residecity' => $userinfo['city'], 'avatar' => $userinfo['headimgurl']), array('uid' => $uid));
			pdo_update('mc_mapping_fans', array('nickname' => $userinfo['nickname']), array('uniacid' => $_W['uniacid'], 'openid' => $openid));
			$model = m('member');
			$member = $model->getMember($openid);
			if (empty($member)) {
				$mc = mc_fetch($uid, array('realname', 'nickname', 'mobile', 'avatar', 'resideprovince', 'residecity', 'residedist'));
				$member = array('uniacid' => $_W['uniacid'], 'uid' => $uid, 'openid' => $openid, 'realname' => $mc['realname'], 'mobile' => $mc['mobile'], 'nickname' => $mc['nickname'], 'avatar' => $mc['avatar'], 'gender' => $mc['gender'], 'province' => $mc['residecity'], 'city' => $mc['residecity'], 'area' => $mc['resizedist'], 'createtime' => time(), 'status' => 0);
				pdo_insert('ewei_shop_member', $member);
				$member['id'] = pdo_insertid();
				$member['isnew'] = true;
			} else {
				$member['nickname'] = $userinfo['nickname'];
				$member['avatar'] = $userinfo['headimgurl'];
				$member['province'] = $userinfo['province'];
				$member['city'] = $userinfo['city'];
				pdo_update('ewei_shop_member', $member, array('id' => $member['id']));
				$member['isnew'] = false;
			}
			return $member;
		}
		function perms()
		{
			return array('poster' => array('text' => $this->getName(), 'isplugin' => true, 'view' => '浏览', 'add' => '添加-log', 'edit' => '修改-log', 'delete' => '删除-log', 'log' => '扫描记录', 'clear' => '清除缓存-log', 'setdefault' => '设置默认海报-log'));
		}
	}
}