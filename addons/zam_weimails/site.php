<?php
/**
 * 微信邮件模块微站定义
 *
 * @author meepo_Zam
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class Zam_weimailsModuleSite extends WeModuleSite {
	 public function doWebList() {

        global $_GPC, $_W;
		load()->func('tpl');
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'display') {
            if (!empty($_GPC['displayorder'])) {
                foreach ($_GPC['displayorder'] as $id => $displayorder) {
                    pdo_update('meepomailattachment', array('displayorder' => $displayorder), array('id' => $id));
                }
                message('分类排序更新成功！', $this->createWebUrl('List', array('op' => 'display')), 'success');
            }
            
            $list = pdo_fetchall("SELECT * FROM " . tablename('meepomailattachment') . " WHERE weid = '{$_W['weid']}' ORDER BY id ASC, displayorder DESC");
            include $this->template('list');
        } elseif ($operation == 'post') {
           
            
            $id = intval($_GPC['id']);
            if (!empty($id)) {
                $list = pdo_fetch("SELECT * FROM " . tablename('meepomailattachment') . " WHERE id = '$id'");
            } else {
                $list = array(
                    'displayorder' => 0,
                );
            }
           
            if (checksubmit('submit')) {
				if(empty($_GPC['attachmentname']) || empty($_GPC['description'])){
				   message('文件名称以及文件描述是必填项！', $this->createWebUrl('list'), 'error');
				}
                $data = array(
                    'weid' => $_W['weid'],
                    'attachmentname' => $_GPC['attachmentname'],
                    'isshow' => intval($_GPC['isshow']),
                    'displayorder' => intval($_GPC['displayorder']),
                    'description' => $_GPC['description'],
                );
                if (!empty($_FILES['thumb']['tmp_name'])) {
					load()->func('file');
                    file_delete($_GPC['thumb_old']);
                    $upload = $this->file_upload($data['attachmentname'],$_FILES['thumb']);
					
					if (is_error($upload)) {
                        message($upload['message'], '', 'error');
                    }
                    $data['thumb'] = $upload['path'];
                }

                if (!empty($id)) {
                    unset($data['id']);
                    pdo_update('meepomailattachment', $data, array('id' => $id));
                } else {
                    pdo_insert('meepomailattachment', $data);
                    $id = pdo_insertid();
                }
                message('更新附件成功！', $this->createWebUrl('list', array('op' => 'display')), 'success');
            }
            include $this->template('list');
        } elseif ($operation == 'delete') {
            $id = intval($_GPC['id']);
            $category = pdo_fetch("SELECT id FROM " . tablename('meepomailattachment') . " WHERE id = '$id'");
            if (empty($category)) {
                message('抱歉，附件不存在或是已经被删除！', $this->createWebUrl('list', array('op' => 'display')), 'error');
            }
            pdo_delete('meepomailattachment', array('id' => $id), 'OR');
            message('附件信息删除成功！', $this->createWebUrl('list', array('op' => 'display')), 'success');
        }
    }


public function file_upload($attname='',$file, $type = 'image', $name = '') {
	if(empty($file)) {
		return error(-1, '没有上传内容');
	}
	
	
	global $_W;
	$cfg = $this->module['config'];
	if(empty($cfg['size'])){
	   $defsize = 10;
	}else{
	   $defsize = $cfg['size'];
	}
	if(!empty($cfg['type'])){
	  $deftype = explode(',',$cfg['type']);
	 
	}else{
      $deftype = array('jpg','gif','png','zip','rar','js','css','html');
	}
	if (empty($_W['uploadsetting'])) {
		$_W['uploadsetting'] = array();
		$_W['uploadsetting'][$type]['folder'] = 'images';
		$_W['uploadsetting'][$type]['extentions'] = $deftype;
		$_W['uploadsetting'][$type]['limit'] = 1024*$defsize;
	}
	
	$settings = $_W['uploadsetting'];
	if(!array_key_exists($type, $settings)) {
		return error(-1, '未知的上传类型');
	}
	$extention = pathinfo($file['name'], PATHINFO_EXTENSION);
	if(!in_array(strtolower($extention), $settings[$type]['extentions'])) {
		return error(-1, '不允许上传此类文件');
	}
	if(!empty($settings[$type]['limit']) && $settings[$type]['limit'] * 1024 < filesize($file['tmp_name'])) {
		return error(-1, "上传的文件超过大小限制，请上传小于 {$settings[$type]['limit']}k 的文件");
	}
	$result = array();
	
	if(empty($name) || $name == 'auto') {
		$result['path'] = "{$settings[$type]['folder']}/" . date('Y/m/');
		mkdirs(ATTACHMENT_ROOT . '/' . $result['path']);
		do {
			if(empty($attname)){
			$filename = random(30) . ".{$extention}";
		    }else{
			$file['name'] = str_replace(".".$extention,'',$file['name']);
			$filename = $file['name'] .random(2). ".{$extention}";
			}
		} while(file_exists(ATTACHMENT_ROOT . '/' . $result['path'] . $filename));
		$result['path'] .= $filename;
	} else {
		$result['path'] = $name . '.' .$extention;
	}
	
	if(!file_move($file['tmp_name'], ATTACHMENT_ROOT . '/' . $result['path'])) {
		return error(-1, '保存上传文件失败');
	}
	
	$result['success'] = true;
	return $result; 
}
/**
 * 上传文件保存，缩略图暂未实现
 * @param string $fname 上传的$_FILE字段
 * @param string $type 上传类型（将按分类保存不同子目录，image -> images）
 * @param string $sname 保存的文件名，如果为 auto 则自动生成文件名，否则请指定从附件目录开始的完整相对路径（包括文件名，不包括文件扩展名）
 * @return array 返回结果数组，字段包括：success => bool 是否上传成功，path => 保存路径（从附件目录开始的完整相对路径），message => 提示信息
 */
public function file_upload52($file, $type = 'image', $sname = 'auto') {
	if(empty($file)) {
		return error(-1, '没有上传内容');
	}
	global $_W;
	if (empty($_W['uploadsetting'])) {
		$_W['uploadsetting'] = array();
		$_W['uploadsetting'][$type]['folder'] = 'images';
		$_W['uploadsetting'][$type]['extentions'] = array('jpg','gif','png','zip','rar','js','css','html');
		$_W['uploadsetting'][$type]['limit'] = 1024*10;
	}
	$settings = $_W['uploadsetting'];
	if(!array_key_exists($type, $settings)) {
		return error(-1, '未知的上传类型');
	}
	$extention = pathinfo($file['name'], PATHINFO_EXTENSION);
	if(!in_array(strtolower($extention), $settings[$type]['extentions'])) {
		return error(-1, '不允许上传此类文件');
	}
	if(!empty($settings[$type]['limit']) && $settings[$type]['limit'] * 1024 < filesize($file['tmp_name'])) {
		return error(-1, "上传的文件超过大小限制，请上传小于 {$settings[$type]['limit']}k 的文件");
	}
	$result = array();
	$path = '/'.$_W['config']['upload']['attachdir'];

	if($sname == 'auto') {
		$result['path'] = "{$settings[$type]['folder']}/" . date('Y/m/');
		mkdirs(IA_ROOT . $path . $result['path']);
		do {
			$filename = random(30) . ".{$extention}";
		} while(file_exists(IA_ROOT . $path . $filename));
		$result['path'] .= $filename;
	} else {
		$result['path'] = "{$settings[$type]['folder']}/" . $sname . '.' . $extention;  
		mkdirs(IA_ROOT . dirname($path));
	}
	$filename = IA_ROOT . $path . $result['path'];
	if(!file_move($file['tmp_name'], $filename)) {
		return error(-1, '保存上传文件失败');
	}
	$result['success'] = true;
	return $result; 
}
}