<?php
/**
 * 微小区模块
 *
 * [晓锋] Copyright (c) 2013 qfinfo.cn
 */
/**
 * 后台超市管理
 */
defined('IN_IA') or exit('Access Denied');
	global $_W,$_GPC;
	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'order';
	$operation = !empty($_GPC['operation']) ? $_GPC['operation'] : 'display';
	$regions = $this->regions();
	if($op == 'nav'){
		//导航管理
		include $this->template('shopping_nav');
	}elseif($op == 'category'){
		//商品分类
		if ($operation == 'display') {
			if (!empty($_GPC['displayorder'])) {
				foreach ($_GPC['displayorder'] as $id => $displayorder) {
					pdo_update('shopping_category', array('displayorder' => $displayorder), array('id' => $id));
				}
				message('分类排序更新成功！', referer(), 'success');
			}
			$children = array();
			$category = pdo_fetchall("SELECT * FROM " . tablename('xcommunity_shopping_category') . " WHERE weid = '{$_W['uniacid']}' ORDER BY parentid ASC, displayorder DESC");
			foreach ($category as $index => $row) {
				if (!empty($row['parentid'])) {
					$children[$row['parentid']][] = $row;
					unset($category[$index]);
				}
			}

		}elseif ($operation == 'post') {
			$parentid = intval($_GPC['parentid']);
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				$category = pdo_fetch("SELECT * FROM " . tablename('xcommunity_shopping_category') . " WHERE id = '$id'");
				$regs = unserialize($category['regionid']);
			} else {
				$category = array(
					'displayorder' => 0,
				);
			}
			if (!empty($parentid)) {
				$parent = pdo_fetch("SELECT id, name FROM " . tablename('xcommunity_shopping_category') . " WHERE id = '$parentid'");
				if (empty($parent)) {
					message('抱歉，上级分类不存在或是已经被删除！', referer(), 'error');
				}
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['catename'])) {
					message('抱歉，请输入分类名称！');
				}
				$data = array(
					'weid' => $_W['uniacid'],
					'name' => $_GPC['catename'],
					'enabled' => intval($_GPC['enabled']),
					'displayorder' => intval($_GPC['displayorder']),
					'isrecommand' => intval($_GPC['isrecommand']),
					'description' => $_GPC['description'],
					'parentid' => intval($parentid),
					'thumb' => $_GPC['thumb'],
					'regionid' => serialize($_GPC['regionid']),
				);
				if (!empty($id)) {
					unset($data['parentid']);
					pdo_update('xcommunity_shopping_category', $data, array('id' => $id));
					load()->func('file');
					file_delete($_GPC['thumb_old']);
				} else {
					pdo_insert('xcommunity_shopping_category', $data);
					$id = pdo_insertid();
				}
				message('更新分类成功！', referer(), 'success');
			}
		}elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$category = pdo_fetch("SELECT id, parentid FROM " . tablename('xcommunity_shopping_category') . " WHERE id = '$id'");
			if (empty($category)) {
				message('抱歉，分类不存在或是已经被删除！',referer(), 'error');
			}
			pdo_delete('xcommunity_shopping_category', array('id' => $id, 'parentid' => $id), 'OR');
			message('分类删除成功！', referer(), 'success');
		}
		include $this->template('shopping_category');
	}elseif($op == 'goods'){
		//商品管理
		load()->func('tpl');
		$category = pdo_fetchall("SELECT * FROM " . tablename('xcommunity_shopping_category') . " WHERE weid = '{$_W['uniacid']}' ORDER BY parentid ASC, displayorder DESC", array(), 'id');
		if (!empty($category)) {
			$children = '';
			foreach ($category as $cid => $cate) {
				if (!empty($cate['parentid'])) {
					$children[$cate['parentid']][$cate['id']] = array($cate['id'], $cate['name']);
				}
			}
		}
		if ($operation == 'post') {
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				$item = pdo_fetch("SELECT * FROM " . tablename('xcommunity_shopping_goods') . " WHERE id = :id", array(':id' => $id));
				$regs = iunserializer($item['regionid']);
				if (empty($item)) {
					message('抱歉，商品不存在或是已经删除！', '', 'error');
				}
				$allspecs = pdo_fetchall("select * from " . tablename('xcommunity_shopping_spec')." where goodsid=:id order by displayorder asc",array(":id"=>$id));
				foreach ($allspecs as &$s) {
					$s['items'] = pdo_fetchall("select * from " . tablename('xcommunity_shopping_spec_item') . " where specid=:specid order by displayorder asc", array(":specid" => $s['id']));
				}
				unset($s);
				$params = pdo_fetchall("select * from " . tablename('xcommunity_shopping_goods_param') . " where goodsid=:id order by displayorder asc", array(':id' => $id));
				$piclist1 = unserialize($item['thumb_url']);
				$piclist = array();
				if(is_array($piclist1)){
					foreach($piclist1 as $p){
						$piclist[] = is_array($p)?$p['attachment']:$p;
					}
				}
				//处理规格项
				$html = "";
				$options = pdo_fetchall("select * from " . tablename('xcommunity_shopping_goods_option') . " where goodsid=:id order by id asc", array(':id' => $id));
				//排序好的specs
				$specs = array();
				//找出数据库存储的排列顺序
				if (count($options) > 0) {
					$specitemids = explode("_", $options[0]['specs'] );
					foreach($specitemids as $itemid){
						foreach($allspecs as $ss){
							$items = $ss['items'];
							foreach($items as $it){
								if($it['id']==$itemid){
									$specs[] = $ss;
									break;
								}
							}
						}
					}
					$html = '';
					$html .= '<table class="table table-bordered table-condensed">';
					$html .= '<thead>';
					$html .= '<tr class="active">';
					$len = count($specs);
					$newlen = 1; //多少种组合
					$h = array(); //显示表格二维数组
					$rowspans = array(); //每个列的rowspan
					for ($i = 0; $i < $len; $i++) {
						//表头
						$html .= "<th style='width:80px;'>" . $specs[$i]['title'] . "</th>";
						//计算多种组合
						$itemlen = count($specs[$i]['items']);
						if ($itemlen <= 0) {
							$itemlen = 1;
						}
						$newlen *= $itemlen;
						//初始化 二维数组
						$h = array();
						for ($j = 0; $j < $newlen; $j++) {
							$h[$i][$j] = array();
						}
						//计算rowspan
						$l = count($specs[$i]['items']);
						$rowspans[$i] = 1;
						for ($j = $i + 1; $j < $len; $j++) {
							$rowspans[$i]*= count($specs[$j]['items']);
						}
					}
					// print_r($rowspans);exit();
					$html .= '<th class="info" style="width:130px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">库存</div><div class="input-group"><input type="text" class="form-control option_stock_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_stock\');"></a></span></div></div></th>';
					$html .= '<th class="success" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">销售价格</div><div class="input-group"><input type="text" class="form-control option_marketprice_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_marketprice\');"></a></span></div></div></th>';
					$html .= '<th class="warning" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">市场价格</div><div class="input-group"><input type="text" class="form-control option_productprice_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_productprice\');"></a></span></div></div></th>';
					$html .= '<th class="danger" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">成本价格</div><div class="input-group"><input type="text" class="form-control option_costprice_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_costprice\');"></a></span></div></div></th>';
					$html .= '<th class="info" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">重量（克）</div><div class="input-group"><input type="text" class="form-control option_weight_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_weight\');"></a></span></div></div></th>';
					$html .= '</tr></thead>';
					for($m=0;$m<$len;$m++){
						$k = 0;$kid = 0;$n=0;
						for($j=0;$j<$newlen;$j++){
							$rowspan = $rowspans[$m]; //9
							if( $j % $rowspan==0){
								$h[$m][$j]=array("html"=> "<td rowspan='".$rowspan."'>".$specs[$m]['items'][$kid]['title']."</td>","id"=>$specs[$m]['items'][$kid]['id']);
								// $k++; if($k>count($specs[$m]['items'])-1) { $k=0; }
							}
							else{
								$h[$m][$j]=array("html"=> "","id"=>$specs[$m]['items'][$kid]['id']);
							}
							$n++;
							if($n==$rowspan){
								$kid++; if($kid>count($specs[$m]['items'])-1) { $kid=0; }
								$n=0;
							}
						}
					}
					$hh = "";
					for ($i = 0; $i < $newlen; $i++) {
						$hh.="<tr>";
						$ids = array();
						for ($j = 0; $j < $len; $j++) {
							$hh.=$h[$j][$i]['html'];
							$ids[] = $h[$j][$i]['id'];
						}
						$ids = implode("_", $ids);
						$val = array("id" => "","title"=>"", "stock" => "", "costprice" => "", "productprice" => "", "marketprice" => "", "weight" => "");
						foreach ($options as $o) {
							if ($ids === $o['specs']) {
								$val = array(
									"id" => $o['id'],
									"title" =>$o['title'],
									"stock" => $o['stock'],
									"costprice" => $o['costprice'],
									"productprice" => $o['productprice'],
									"marketprice" => $o['marketprice'],
									"weight" => $o['weight']
								);
								break;
							}
						}
						$hh .= '<td class="info">';
						$hh .= '<input name="option_stock_' . $ids . '[]"  type="text" class="form-control option_stock option_stock_' . $ids . '" value="' . $val['stock'] . '"/></td>';
						$hh .= '<input name="option_id_' . $ids . '[]"  type="hidden" class="form-control option_id option_id_' . $ids . '" value="' . $val['id'] . '"/>';
						$hh .= '<input name="option_ids[]"  type="hidden" class="form-control option_ids option_ids_' . $ids . '" value="' . $ids . '"/>';
						$hh .= '<input name="option_title_' . $ids . '[]"  type="hidden" class="form-control option_title option_title_' . $ids . '" value="' . $val['title'] . '"/>';
						$hh .= '</td>';
						$hh .= '<td class="success"><input name="option_marketprice_' . $ids . '[]" type="text" class="form-control option_marketprice option_marketprice_' . $ids . '" value="' . $val['marketprice'] . '"/></td>';
						$hh .= '<td class="warning"><input name="option_productprice_' . $ids . '[]" type="text" class="form-control option_productprice option_productprice_' . $ids . '" " value="' . $val['productprice'] . '"/></td>';
						$hh .= '<td class="danger"><input name="option_costprice_' . $ids . '[]" type="text" class="form-control option_costprice option_costprice_' . $ids . '" " value="' . $val['costprice'] . '"/></td>';
						$hh .= '<td class="info"><input name="option_weight_' . $ids . '[]" type="text" class="form-control option_weight option_weight_' . $ids . '" " value="' . $val['weight'] . '"/></td>';
						$hh .= '</tr>';
					}
					$html .= $hh;
					$html .= "</table>";
				}
			}
			if (empty($category)) {
				message('抱歉，请您先添加商品分类！', $this->createWebUrl('shopping', array('operation' => 'post','op' => 'category')), 'error');
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['goodsname'])) {
					message('请输入商品名称！');
				}
				if (empty($_GPC['pcate'])) {
					message('请选择商品分类！');
				}
				if(empty($_GPC['thumbs'])){
					$_GPC['thumbs'] = array();
				}
				$data = array(
					'weid' => intval($_W['uniacid']),
					'displayorder' => intval($_GPC['displayorder']),
					'title' => $_GPC['goodsname'],
					'pcate' => intval($_GPC['pcate']),
					'ccate' => intval($_GPC['ccate']),
					'thumb'=>$_GPC['thumb'],
					'type' => intval($_GPC['type']),
					'isrecommand' => intval($_GPC['isrecommand']),
					'ishot' => intval($_GPC['ishot']),
					'isnew' => intval($_GPC['isnew']),
					'isdiscount' => intval($_GPC['isdiscount']),
					'istime' => intval($_GPC['istime']),
					'timestart' => strtotime($_GPC['timestart']),
					'timeend' => strtotime($_GPC['timeend']),
					'description' => $_GPC['description'],
					'content' => htmlspecialchars_decode($_GPC['content']),
					'goodssn' => $_GPC['goodssn'],
					'unit' => $_GPC['unit'],
					'createtime' => TIMESTAMP,
					'total' => intval($_GPC['total']),
					'totalcnf' => intval($_GPC['totalcnf']),
					'marketprice' => $_GPC['marketprice'],
					'weight' => $_GPC['weight'],
					'costprice' => $_GPC['costprice'],
					'originalprice' => $_GPC['originalprice'],
					'productprice' => $_GPC['productprice'],
					'productsn' => $_GPC['productsn'],
					'credit' => intval($_GPC['credit']),
					'maxbuy' => intval($_GPC['maxbuy']),
					'hasoption' => intval($_GPC['hasoption']),
					'sales' => intval($_GPC['sales']),
					'status' => intval($_GPC['status']),
					'regionid' => serialize($_GPC['regionid']),
				);
				if ($data['total'] === -1) {
					$data['total'] = 0;
					$data['totalcnf'] = 2;
				}
				
				if(is_array($_GPC['thumbs'])){
					$data['thumb_url'] = serialize($_GPC['thumbs']);
				}
				if (empty($id)) {
					pdo_insert('xcommunity_shopping_goods', $data);
					$id = pdo_insertid();
				} else {
					unset($data['createtime']);
					pdo_update('xcommunity_shopping_goods', $data, array('id' => $id));
				}
				$totalstocks = 0;
				//处理自定义参数
				$param_ids = $_POST['param_id'];
				$param_titles = $_POST['param_title'];
				$param_values = $_POST['param_value'];
				$param_displayorders = $_POST['param_displayorder'];
				$len = count($param_ids);
				$paramids = array();
				for ($k = 0; $k < $len; $k++) {
					$param_id = "";
					$get_param_id = $param_ids[$k];
					$a = array(
						"title" => $param_titles[$k],
						"value" => $param_values[$k],
						"displayorder" => $k,
						"goodsid" => $id,
					);
					if (!is_numeric($get_param_id)) {
						pdo_insert("xcommunity_shopping_goods_param", $a);
						$param_id = pdo_insertid();
					} else {
						pdo_update("xcommunity_shopping_goods_param", $a, array('id' => $get_param_id));
						$param_id = $get_param_id;
					}
					$paramids[] = $param_id;
				}
				if (count($paramids) > 0) {
					pdo_query("delete from " . tablename('xcommunity_shopping_goods_param') . " where goodsid=$id and id not in ( " . implode(',', $paramids) . ")");
				}
				else{
					pdo_query("delete from " . tablename('xcommunity_shopping_goods_param') . " where goodsid=$id");
				}
//				if ($totalstocks > 0) {
//					pdo_update("shopping_goods", array("total" => $totalstocks), array("id" => $id));
//				}
				//处理商品规格
				$files = $_FILES;
				$spec_ids = $_POST['spec_id'];
				$spec_titles = $_POST['spec_title'];
				$specids = array();
				$len = count($spec_ids);
				$specids = array();
				$spec_items = array();
				for ($k = 0; $k < $len; $k++) {
					$spec_id = "";
					$get_spec_id = $spec_ids[$k];
					$a = array(
						"weid" => $_W['uniacid'],
						"goodsid" => $id,
						"displayorder" => $k,
						"title" => $spec_titles[$get_spec_id]
					);
					if (is_numeric($get_spec_id)) {
						pdo_update("xcommunity_shopping_spec", $a, array("id" => $get_spec_id));
						$spec_id = $get_spec_id;
					} else {
						pdo_insert("xcommunity_shopping_spec", $a);
						$spec_id = pdo_insertid();
					}
					//子项
					$spec_item_ids = $_POST["spec_item_id_".$get_spec_id];
					$spec_item_titles = $_POST["spec_item_title_".$get_spec_id];
					$spec_item_shows = $_POST["spec_item_show_".$get_spec_id];
					$spec_item_thumbs = $_POST["spec_item_thumb_".$get_spec_id];
					$spec_item_oldthumbs = $_POST["spec_item_oldthumb_".$get_spec_id];
					$itemlen = count($spec_item_ids);
					$itemids = array();
					for ($n = 0; $n < $itemlen; $n++) {
						$item_id = "";
						$get_item_id = $spec_item_ids[$n];
						$d = array(
							"weid" => $_W['uniacid'],
							"specid" => $spec_id,
							"displayorder" => $n,
							"title" => $spec_item_titles[$n],
							"show" => $spec_item_shows[$n],
							"thumb"=>$spec_item_thumbs[$n]
						);
						$f = "spec_item_thumb_" . $get_item_id;
						if (is_numeric($get_item_id)) {
							pdo_update("xcommunity_shopping_spec_item", $d, array("id" => $get_item_id));
							$item_id = $get_item_id;
						} else {
							pdo_insert("xcommunity_shopping_spec_item", $d);
							$item_id = pdo_insertid();
						}
						$itemids[] = $item_id;
						//临时记录，用于保存规格项
						$d['get_id'] = $get_item_id;
						$d['id']= $item_id;
						$spec_items[] = $d;
					}
					//删除其他的
					if(count($itemids)>0){
						 pdo_query("delete from " . tablename('xcommunity_shopping_spec_item') . " where weid={$_W['uniacid']} and specid=$spec_id and id not in (" . implode(",", $itemids) . ")");	
					}
					else{
						 pdo_query("delete from " . tablename('xcommunity_shopping_spec_item') . " where weid={$_W['uniacid']} and specid=$spec_id");	
					}
					//更新规格项id
					pdo_update("shopping_spec", array("content" => serialize($itemids)), array("id" => $spec_id));
					$specids[] = $spec_id;
				}
				//删除其他的
				if( count($specids)>0){
					pdo_query("delete from " . tablename('xcommunity_shopping_spec') . " where weid={$_W['uniacid']} and goodsid=$id and id not in (" . implode(",", $specids) . ")");
				}
				else{
					pdo_query("delete from " . tablename('xcommunity_shopping_spec') . " where weid={$_W['uniacid']} and goodsid=$id");
				}
				//保存规格
				$option_idss = $_POST['option_ids'];
				$option_productprices = $_POST['option_productprice'];
				$option_marketprices = $_POST['option_marketprice'];
				$option_costprices = $_POST['option_costprice'];
				$option_stocks = $_POST['option_stock'];
				$option_weights = $_POST['option_weight'];
				$len = count($option_idss);
				$optionids = array();
				for ($k = 0; $k < $len; $k++) {
					$option_id = "";
					$get_option_id = $_GPC['option_id_' . $ids][0];
					$ids = $option_idss[$k]; $idsarr = explode("_",$ids);
					$newids = array();
					foreach($idsarr as $key=>$ida){
						foreach($spec_items as $it){
							if($it['get_id']==$ida){
								$newids[] = $it['id'];
								break;
							}
						}
					}
					$newids = implode("_",$newids);
					$a = array(
						"title" => $_GPC['option_title_' . $ids][0],
						"productprice" => $_GPC['option_productprice_' . $ids][0],
						"costprice" => $_GPC['option_costprice_' . $ids][0],
						"marketprice" => $_GPC['option_marketprice_' . $ids][0],
						"stock" => $_GPC['option_stock_' . $ids][0],
						"weight" => $_GPC['option_weight_' . $ids][0],
						"goodsid" => $id,
						"specs" => $newids
					);
					$totalstocks+=$a['stock'];
					if (empty($get_option_id)) {
						pdo_insert("xcommunity_shopping_goods_option", $a);
						$option_id = pdo_insertid();
					} else {
						pdo_update("xcommunity_shopping_goods_option", $a, array('id' => $get_option_id));
						$option_id = $get_option_id;
					}
					$optionids[] = $option_id;
				}
				if (count($optionids) > 0) {
					pdo_query("delete from " . tablename('xcommunity_shopping_goods_option') . " where goodsid=$id and id not in ( " . implode(',', $optionids) . ")");
				}
				else{
					pdo_query("delete from " . tablename('xcommunity_shopping_goods_option') . " where goodsid=$id");
				}
				//总库存
				if ( ($totalstocks > 0) && ($data['totalcnf'] != 2) ) {
					pdo_update("xcommunity_shopping_goods", array("total" => $totalstocks), array("id" => $id));
				}
				//message('商品更新成功！', $this->createWebUrl('goods', array('op' => 'display')), 'success');
				message('商品更新成功！', referer(), 'success');
			}
		} elseif ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = '';
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
			}
			if (!empty($_GPC['cate_2'])) {
				$cid = intval($_GPC['cate_2']);
				$condition .= " AND ccate = '{$cid}'";
			} elseif (!empty($_GPC['cate_1'])) {
				$cid = intval($_GPC['cate_1']);
				$condition .= " AND pcate = '{$cid}'";
			}
			if (isset($_GPC['status'])) {
				$condition .= " AND status = '" . intval($_GPC['status']) . "'";
			}
			$list = pdo_fetchall("SELECT * FROM " . tablename('xcommunity_shopping_goods') . " WHERE weid = '{$_W['uniacid']}' and deleted=0 $condition ORDER BY status DESC, displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('xcommunity_shopping_goods') . " WHERE weid = '{$_W['uniacid']}'  and deleted=0 $condition");
			$pager = pagination($total, $pindex, $psize);
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id, thumb FROM " . tablename('xcommunity_shopping_goods') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，商品不存在或是已经被删除！');
			}
//			if (!empty($row['thumb'])) {
//				file_delete($row['thumb']);
//			}
//			pdo_delete('shopping_goods', array('id' => $id));
			//修改成不直接删除，而设置deleted=1
			pdo_update("xcommunity_shopping_goods", array("deleted" => 1), array('id' => $id));
			message('删除成功！', referer(), 'success');
		} elseif ($operation == 'productdelete') {
			$id = intval($_GPC['id']);
			pdo_delete('xcommunity_shopping_product', array('id' => $id));
			message('删除成功！', '', 'success');
		}
		include $this->template('shopping_goods');






	}elseif($op == 'express'){
		//物流管理
			if ($operation == 'display') {
				$list = pdo_fetchall("SELECT * FROM " . tablename('xcommunity_shopping_express') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
			} elseif ($operation == 'post') {
				$id = intval($_GPC['id']);
				if (checksubmit('submit')) {
					if (empty($_GPC['express_name'])) {
						message('抱歉，请输入物流名称！');
					}
					$data = array(
						'weid' => $_W['uniacid'],
						'displayorder' => intval($_GPC['displayorder']),
						'express_name' => $_GPC['express_name'],
						'express_url' => $_GPC['express_url'],
						'express_area' => $_GPC['express_area'],
						'regionid' => serialize($_GPC['regionid']),
					);
					if (!empty($id)) {
						unset($data['parentid']);
						pdo_update('xcommunity_shopping_express', $data, array('id' => $id));
					} else {
						pdo_insert('xcommunity_shopping_express', $data);
						$id = pdo_insertid();
					}
					message('更新物流成功！', referer(), 'success');
				}
				if ($id) {
					//修改
					$express = pdo_fetch("SELECT * FROM " . tablename('xcommunity_shopping_express') . " WHERE id = '$id' and weid = '{$_W['uniacid']}'");
					$regs = unserialize($express['regionid']);
				}
				
			} elseif ($operation == 'delete') {
				$id = intval($_GPC['id']);
				$express = pdo_fetch("SELECT id  FROM " . tablename('xcommunity_shopping_express') . " WHERE id = '$id' AND weid=" . $_W['uniacid'] . "");
				if (empty($express)) {
					message('抱歉，物流方式不存在或是已经被删除！', referer(), 'error');
				}
				pdo_delete('xcommunity_shopping_express', array('id' => $id));
				message('物流方式删除成功！', referer(), 'success');
			} else {
				message('请求方式不存在');
			}
			include $this->template('shopping_express');

	}elseif($op == 'dispatch'){
		//配送方式
		if ($operation == 'display') {
			$list = pdo_fetchall("SELECT * FROM " . tablename('xcommunity_shopping_dispatch') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			$express = pdo_fetchall("select * from " . tablename('xcommunity_shopping_express') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");

			if (checksubmit('submit')) {
				$data = array(
					'weid' => $_W['uniacid'],
					'displayorder' => intval($_GPC['displayorder']),
					'dispatchtype' => intval($_GPC['dispatchtype']),
					'dispatchname' => $_GPC['dispatchname'],
					'express' => $_GPC['express'],
					'firstprice' => $_GPC['firstprice'],
					'firstweight' => $_GPC['firstweight'],
					'secondprice' => $_GPC['secondprice'],
					'secondweight' => $_GPC['secondweight'],
					'description' => $_GPC['description'],
					'regionid' => serialize($_GPC['regionid']),
				);
				if (!empty($id)) {
					pdo_update('xcommunity_shopping_dispatch', $data, array('id' => $id));
				} else {
					pdo_insert('xcommunity_shopping_dispatch', $data);
					$id = pdo_insertid();
				}
				message('更新配送方式成功！', referer(), 'success');
			}
			if ($id) {
				//修改
				$dispatch = pdo_fetch("SELECT * FROM " . tablename('xcommunity_shopping_dispatch') . " WHERE id = '$id' and weid = '{$_W['uniacid']}'");
				$regs = unserialize($dispatch['regionid']);
			}

		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$dispatch = pdo_fetch("SELECT id FROM " . tablename('xcommunity_shopping_dispatch') . " WHERE id = '$id' AND weid=" . $_W['uniacid'] . "");
			if (empty($dispatch)) {
				message('抱歉，配送方式不存在或是已经被删除！', referer(), 'error');
			}
			pdo_delete('xcommunity_shopping_dispatch', array('id' => $id));
			message('配送方式删除成功！', referer(), 'success');
		} else {
			message('请求方式不存在');
		}
		include $this->template('shopping_dispatch');
	}elseif($op == 'slide'){
		//幻灯管理
		load()->func('tpl');
			if (checksubmit('submit')) {
				$data = array(
					'weid' => $_W['uniacid'],
					'advname' => $_GPC['advname'],
					'link' => $_GPC['link'],
					'enabled' => intval($_GPC['enabled']),
					'displayorder' => intval($_GPC['displayorder']),
					'thumb'=>$_GPC['thumb'],
					'regionid' => serialize($_GPC['regionid']),
				);
				if (!empty($id)) {
					pdo_update('xcommunity_shopping_slide', $data, array('id' => $id));
				} else {
					pdo_insert('xcommunity_shopping_slide', $data);
					$id = pdo_insertid();
				}
				message('更新幻灯片成功！',referer(), 'success');
			}
			$adv = pdo_fetch("select * from " . tablename('xcommunity_shopping_slide') . " where weid=:weid limit 1", array( ":weid" => $_W['uniacid']));
			$regs = unserialize($adv['regionid']);
			
		

		include $this->template('shopping_slide');
	}elseif ($op == 'notice') {
		//维权与告警
		load()->func('tpl');
		$pindex = max(1, intval($_GPC['page']));
		$psize = 50;
		if (!empty($_GPC['date'])) {
			$starttime = strtotime($_GPC['date']['start']);
			$endtime = strtotime($_GPC['date']['end']) + 86399;
		} else {
			$starttime = strtotime('-1 month');
			$endtime = time();
		}
		$where = " WHERE `weid` = :weid AND `createtime` >= :starttime AND `createtime` < :endtime";
		$paras = array(
			':weid' => $_W['uniacid'],
			':starttime' => $starttime,
			':endtime' => $endtime
		);
		$keyword = $_GPC['keyword'];
		if (!empty($keyword)) {
			$where .= " AND `feedbackid`=:feedbackid";
			$paras[':feedbackid'] = $keyword;
		}
		$type = empty($_GPC['type']) ? 0 : $_GPC['type'];
		$type = intval($type);
		if ($type != 0) {
			$where .= " AND `type`=:type";
			$paras[':type'] = $type;
		}
		$status = empty($_GPC['status']) ? 0 : intval($_GPC['status']);
		$status = intval($status);
		if ($status != -1) {
			$where .= " AND `status` = :status";
			$paras[':status'] = $status;
		}
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('xcommunity_shopping_feedback') . $where, $paras);
		$list = pdo_fetchall("SELECT * FROM " . tablename('xcommunity_shopping_feedback') . $where . " ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $paras);
		$pager = pagination($total, $pindex, $psize);
		$transids = array();
		foreach ($list as $row) {
			$transids[] = $row['transid'];
		}
		if (!empty($transids)) {
			$sql = "SELECT * FROM " . tablename('xcommunity_sshopping_order') . " WHERE weid='{$_W['uniacid']}' AND transid IN ( '" . implode("','", $transids) . "' )";
			$orders = pdo_fetchall($sql, array(), 'transid');
		}
		$addressids = array();
		if(is_array($orders)){
			foreach ($orders as $transid => $order) {
				$addressids[] = $order['addressid'];
			}
		}
		$addresses = array();
		if (!empty($addressids)) {
			$sql = "SELECT * FROM " . tablename('xcommunity_sshopping_address') . " WHERE weid='{$_W['uniacid']}' AND id IN ( '" . implode("','", $addressids) . "' )";
			$addresses = pdo_fetchall($sql, array(), 'id');
		}
		foreach ($list as &$feedback) {
			$transid = $feedback['transid'];
			$order = $orders[$transid];
			$feedback['order'] = $order;
			$addressid = $order['addressid'];
			$feedback['address'] = $addresses[$addressid];
		}
		include $this->template('shopping_notice');
	}elseif ($op == 'setgoodsproperty') {
		$id = intval($_GPC['id']);
		$type = $_GPC['type'];
		$data = intval($_GPC['data']);
		if (in_array($type, array('new', 'hot', 'recommand', 'discount'))) {
			$data = ($data==1?'0':'1');
			pdo_update("xcommunity_shopping_goods", array("is" . $type => $data), array("id" => $id, "weid" => $_W['uniacid']));
			die(json_encode(array("result" => 1, "data" => $data)));
		}
		if (in_array($type, array('status'))) {
			$data = ($data==1?'0':'1');
			pdo_update("xcommunity_shopping_goods", array($type => $data), array("id" => $id, "weid" => $_W['uniacid']));
			die(json_encode(array("result" => 1, "data" => $data)));
		}
		if (in_array($type, array('type'))) {
			$data = ($data==1?'2':'1');
			pdo_update("xcommunity_shopping_goods", array($type => $data), array("id" => $id, "weid" => $_W['uniacid']));
			die(json_encode(array("result" => 1, "data" => $data)));
		}
		die(json_encode(array("result" => 0)));
	}elseif ($op == 'param') {
		$tag = random(32);
		include $this->template('shopping_param');
	}elseif ($op == 'spec') {
		$spec = array(
			"id" => random(32),
			"title" => $_GPC['title']
		);
		include $this->template('shopping_spec');
	}elseif ($op == 'specitem') {
		load()->func('tpl');
		$spec = array(
			"id" => $_GPC['specid']
		);
		$specitem = array(
			"id" => random(32),
			"title" => $_GPC['title'],
			"show" => 1
		);
		include $this->template('shopping_spec_item');
	}elseif ($op == 'order') {
		load()->func('tpl');
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$status = $_GPC['status'];
			$sendtype = !isset($_GPC['sendtype']) ? 0 : $_GPC['sendtype'];
			$condition = " o.weid = :weid";
			$paras = array(':weid' => $_W['uniacid']);
			if (empty($starttime) || empty($endtime)) {
				$starttime = strtotime('-1 month');
				$endtime = time();
			}
			if (!empty($_GPC['time'])) {
				$starttime = strtotime($_GPC['time']['start']);
				$endtime = strtotime($_GPC['time']['end']) + 86399;
				$condition .= " AND o.createtime >= :starttime AND o.createtime <= :endtime ";
				$paras[':starttime'] = $starttime;
				$paras[':endtime'] = $endtime;
			}
			if (!empty($_GPC['paytype'])) {
				$condition .= " AND o.paytype = '{$_GPC['paytype']}'";
			} elseif ($_GPC['paytype'] === '0') {
				$condition .= " AND o.paytype = '{$_GPC['paytype']}'";
			}
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND o.ordersn LIKE '%{$_GPC['keyword']}%'";
			}
			if (!empty($_GPC['member'])) {
				$condition .= " AND (a.realname LIKE '%{$_GPC['member']}%' or a.mobile LIKE '%{$_GPC['member']}%')";
			}
			if ($status != '') {
				$condition .= " AND o.status = '" . intval($status) . "'";
			}
			if (!empty($sendtype)) {
				$condition .= " AND o.sendtype = '" . intval($sendtype) . "' AND status != '3'";
			}
			$sql = "select o.* , a.realname,a.mobile from ".tablename('xcommunity_shopping_order')." o"
					." left join ".tablename('xcommunity_shopping_address')." a on o.addressid = a.id "
					. " where $condition ORDER BY o.status DESC, o.createtime DESC "
					. "LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
			$list = pdo_fetchall($sql,$paras);
			$paytype = array (
					'0' => array('css' => 'default', 'name' => '未支付'),
					'1' => array('css' => 'danger','name' => '余额支付'),
					'2' => array('css' => 'info', 'name' => '在线支付'),
					'3' => array('css' => 'warning', 'name' => '货到付款')
			);
			$orderstatus = array (
					'-1' => array('css' => 'default', 'name' => '已取消'),
					'0' => array('css' => 'danger', 'name' => '待付款'),
					'1' => array('css' => 'info', 'name' => '待发货'),
					'2' => array('css' => 'warning', 'name' => '待收货'),
					'3' => array('css' => 'success', 'name' => '已完成')
			);
			foreach ($list as &$value) {
				$s = $value['status'];
				$value['statuscss'] = $orderstatus[$value['status']]['css'];
				$value['status'] = $orderstatus[$value['status']]['name'];
				if ($s < 1) {
					$value['css'] = $paytype[$s]['css'];
					$value['paytype'] = $paytype[$s]['name'];
					continue;
				}
				$value['css'] = $paytype[$value['paytype']]['css'];
				if ($value['paytype'] == 2) {
					if (empty($value['transid'])) {
						$value['paytype'] = '支付宝支付';
					} else {
						$value['paytype'] = '微信支付';
					}
				} else {
					$value['paytype'] = $paytype[$value['paytype']]['name'];
				}
			}
			$total = pdo_fetchcolumn(
						'SELECT COUNT(*) FROM ' . tablename('xcommunity_shopping_order') . " o "
						." left join ".tablename('xcommunity_shopping_address')." a on o.addressid = a.id "
						." WHERE $condition", $paras);
			$pager = pagination($total, $pindex, $psize);
			if (!empty($list)) {
				foreach ($list as &$row) {
					// !empty($row['addressid']) && $addressids[$row['addressid']] = $row['addressid'];
					$row['dispatch'] = pdo_fetch("SELECT * FROM " . tablename('xcommunity_shopping_dispatch') . " WHERE id = :id", array(':id' => $row['dispatch']));
				}
				unset($row);
			}
//			if (!empty($addressids)) {
//				$address = pdo_fetchall("SELECT * FROM " . tablename('shopping_address') . " WHERE id IN ('" . implode("','", $addressids) . "')", array(), 'id');
//			}
		} elseif ($operation == 'detail') {
			$id = intval($_GPC['id']);
			$item = pdo_fetch("SELECT * FROM " . tablename('xcommunity_shopping_order') . " WHERE id = :id", array(':id' => $id));
			if (empty($item)) {
				message("抱歉，订单不存在!", referer(), "error");
			}
			if (checksubmit('confirmsend')) {
				if (!empty($_GPC['isexpress']) && empty($_GPC['expresssn'])) {
					message('请输入快递单号！');
				}
				$item = pdo_fetch("SELECT transid FROM " . tablename('xcommunity_shopping_order') . " WHERE id = :id", array(':id' => $id));
				$address = pdo_fetch("SELECT * FROM".tablename('xcommunity_shopping_address')."WHERE openid = '{$item['from_user']}' AND weid='{$_W['uniacid']}'");
				if (!empty($item['transid'])) {
					$this->changeWechatSend($id, 1);
				}
				//发货短信提醒
				if ($this->module['config']['shopping_status']) {
					load()->func('communication');
					$tpl_id    = $this->module['config']['shopping_id'];
					$expresscom = $_GPC['expresscom'];
					$expresssn = $_GPC['expresssn'];
					$tpl_value = urlencode("#express_name#=$expresscom&#express_code#=$expresssn");
					$appkey    = $this->module['config']['sms_account'];
					$params    = "mobile=".$address['mobile']."&tpl_id=".$tpl_id."&tpl_value=".$tpl_value."&key=".$appkey;
					$url       = 'http://v.juhe.cn/sms/send';
					$content   = ihttp_post($url,$params);
				}
				//微信模板通知提醒
				if ($this->module['config']['wechat_status']) {
					$openid = $item['from_user'];
					$url = '';
					$template_id = $this->module['config']['shopping_tplid'];
					$createtime = date('Y-m-d H:i:s', $_W['timestamp']);
					$content = array(
							'first' => array(
									'value' => '您好，您订购的货物已经发货了',
								),
							'keyword1' => array(
									'value' => $_GPC['expresscom'].'-'.$_GPC['expresssn'],
								),
							'keyword2' => array(
									'value' => $item['ordersn'],
								),
							'keyword3'    => array(
									'value' => $createtime,
								),
							'remark'    => array(
								'value' => '有任何问题请随时与我们联系，谢谢。',
							),	
						);
					$this->sendtpl($openid,$url,$template_id,$content);
				}
				pdo_update(
					'xcommunity_shopping_order',
					array(
						'status' => 2,
						'remark' => $_GPC['remark'],
						'express' => $_GPC['express'],
						'expresscom' => $_GPC['expresscom'],
						'expresssn' => $_GPC['expresssn'],
					),
					array('id' => $id)
				);
				message('发货操作成功！', referer(), 'success');
			}
			if (checksubmit('cancelsend')) {
				$item = pdo_fetch("SELECT transid FROM " . tablename('xcommunity_shopping_order') . " WHERE id = :id", array(':id' => $id));
				if (!empty($item['transid'])) {
					$this->changeWechatSend($id, 0, $_GPC['cancelreson']);
				}
				pdo_update(
					'xcommunity_shopping_order',
					array(
						'status' => 1,
						'remark' => $_GPC['remark'],
					),
					array('id' => $id)
				);
				message('取消发货操作成功！', referer(), 'success');
			}
			if (checksubmit('finish')) {
				pdo_update('xcommunity_shopping_order', array('status' => 3, 'remark' => $_GPC['remark']), array('id' => $id));
				message('订单操作成功！', referer(), 'success');
			}
			if (checksubmit('cancel')) {
				pdo_update('xcommunity_shopping_order', array('status' => 1, 'remark' => $_GPC['remark']), array('id' => $id));
				message('取消完成订单操作成功！', referer(), 'success');
			}
			if (checksubmit('cancelpay')) {
				pdo_update('xcommunity_shopping_order', array('status' => 0, 'remark' => $_GPC['remark']), array('id' => $id));
				//设置库存
				$this->setOrderStock($id, false);
				//减少积分
				$this->setOrderCredit($id, false);

				message('取消订单付款操作成功！', referer(), 'success');
			}
			if (checksubmit('confrimpay')) {
				pdo_update('xcommunity_shopping_order', array('status' => 1, 'paytype' => 2, 'remark' => $_GPC['remark']), array('id' => $id));
				//设置库存
				$this->setOrderStock($id);
				//增加积分
				$this->setOrderCredit($id);
				message('确认订单付款操作成功！', referer(), 'success');
			}
			if (checksubmit('close')) {
				$item = pdo_fetch("SELECT transid FROM " . tablename('xcommunity_shopping_order') . " WHERE id = :id", array(':id' => $id));
				if (!empty($item['transid'])) {
					$this->changeWechatSend($id, 0, $_GPC['reson']);
				}
				pdo_update('xcommunity_shopping_order', array('status' => -1, 'remark' => $_GPC['remark']), array('id' => $id));
				message('订单关闭操作成功！', referer(), 'success');
			}
			if (checksubmit('open')) {
				pdo_update('xcommunity_shopping_order', array('status' => 0, 'remark' => $_GPC['remark']), array('id' => $id));
				message('开启订单操作成功！', referer(), 'success');
			}
			$dispatch = pdo_fetch("SELECT * FROM " . tablename('xcommunity_shopping_dispatch') . " WHERE id = :id", array(':id' => $item['dispatch']));
			if (!empty($dispatch) && !empty($dispatch['express'])) {
				$express = pdo_fetch("select * from " . tablename('xcommunity_shopping_express') . " WHERE id=:id limit 1", array(":id" => $dispatch['express']));
			}
			$item['user'] = pdo_fetch("SELECT * FROM " . tablename('xcommunity_shopping_address') . " WHERE id = {$item['addressid']}");
			$goods = pdo_fetchall("SELECT g.*, o.total,g.type,o.optionname,o.optionid,o.price as orderprice FROM " . tablename('xcommunity_shopping_order_goods') .
					" o left join " . tablename('xcommunity_shopping_goods') . " g on o.goodsid=g.id " . " WHERE o.orderid='{$id}'");
			$item['goods'] = $goods;
		} elseif ($operation == 'delete') {
			/*订单删除*/
			$orderid = intval($_GPC['id']);
			if (pdo_delete('xcommunity_shopping_order', array('id' => $orderid))) {
				message('订单删除成功', $this->createWebUrl('shopping',array('op' => 'order','operation' => 'display')), 'success');
			}
		}
		include $this->template('shopping_order');

	}


















