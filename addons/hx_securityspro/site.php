<?php
/**
 * 防伪码增强版模块微站定义
 *
 * @author 华轩科技
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
@require_once('Excel/reader.php');
class Hx_securitysproModuleSite extends WeModuleSite {

	public $cfg = array();

	public function __construct(){
		global $_W;
		$this->data = 'hx_securityspro_data_'.$_W['uniacid'];
		$this->moban = 'hx_securityspro_data_moban';
		$this->i_logs = 'hx_securityspro_logs';
		$sql = "CREATE TABLE IF NOT EXISTS ".tablename($this->data)." LIKE ".tablename($this->moban);
		pdo_query($sql);
	}

	public function doWebList() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_GPC, $_W;
		load()->func('tpl');
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$where ="";
		$sStr = $_GPC['sStr'];
		$code = $_GPC['code'];
		$type = $_GPC['type'];
		$factory = $_GPC['factory'];
		$creditname = $_GPC['creditname'];
		$creditstatus = $_GPC['creditstatus'];
		if(!empty($code)){
			$where .= " AND code = '$code'";
		}
		if(!empty($sStr)){
			$where .= " AND code LIKE '$sStr%'";
		}
		if(!empty($type)){
			$where .= " AND type = '$type'";
		}
		if(!empty($factory)){
			$where .= " AND factory = '$factory'";
		}
		if(!empty($creditname)){
			$where .= " AND creditname = '$creditname'";
		}
		if(!empty($creditstatus)){
			$where .= " AND creditstatus = '$creditstatus'";
		}
		if (!empty($_GPC['createtime'])) {
			$c_s = strtotime($_GPC['createtime']['start']);
			$c_e = strtotime($_GPC['createtime']['end']);
			$where .= " AND createtime >= '$c_s' AND createtime <= '$c_e'";
		}
		if (empty($_GPC['createtime'])) {
			$c_s = time() - 86400*30;
			$c_e = time() + 84400;
		}
		if (!empty($_GPC['Deleteall']) && !empty($_GPC['select'])) {
			foreach ($_GPC['select'] as $k => $v) {
				pdo_delete($this->data, array('id' => $v));	
			}
			message('成功删除选中的防伪码！', referer(), 'success');
		}
		if (!empty($_GPC['Frozenall']) && !empty($_GPC['select'])) {
			foreach ($_GPC['select'] as $k => $v) {
				pdo_update($this->data, array('status' => 0), array('id' => $v));
			}
			message('成功冻结选中的防伪码！', referer(), 'success');
		}
		if (checksubmit('submit2')) {
			if (!$_W['isfounder']) {
				message('非管理员无权限，谢谢使用');
			}
			$listall = pdo_fetchall("SELECT *  from ".tablename($this->data)." where status ='1' $where order by id asc");
			foreach ($listall as &$value) {
				$arraydata[] = $value['code'];
			}
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Content-Type: application/vnd.ms-execl");
			header("Content-Type: application/force-download");
			header("Content-Type: application/download");
			header("Content-Disposition: attachment; filename=".date('Ymd').'.xls');
			header("Content-Transfer-Encoding: binary");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo  implode("\t\n",$arraydata);
			exit();
		}
		$list = pdo_fetchall("SELECT *  from ".tablename($this->data)." where status ='1' $where order by id asc LIMIT ". ($pindex -1) * $psize . ',' .$psize );
		$total = pdo_fetchcolumn("SELECT COUNT(*)  from ".tablename($this->data)." where status ='1' $where order by id asc");
		$pager = pagination($total, $pindex, $psize);
		include $this->template('display');
	}
	public function doWebCreate() {
		global $_GPC, $_W;
		if (!$_W['isfounder']) {
			message('非管理员无权限，谢谢使用');
		}
		load()->func('tpl');
		if (checksubmit('submit')) {
			$rule = $_GPC['rule'];
			$list = pdo_fetchall("SELECT *  from ".tablename($this->data)." where code like '{$_GPC['sStr']}%'");
			if (!empty($list)) {
				message('防伪码前缀已存在，请修改');
			}
			$i=1;
			while($i<=intval($_GPC['sNum'])){
				$code = $this->random(intval($_GPC['slen']),$rule,false);
				$data =array(
					'code' => $_GPC['sStr'].$code,
					'type' => $_GPC['sName'],
					'factory' => $_GPC['sFactory'],
					'stime' => strtotime($_GPC['sTime_1']),
					'createtime' => time(),
					'creditname' => $_GPC['creditname'],
					'creditnum' => intval($_GPC['creditnum']),
					'creditstatus' => intval($_GPC['creditstatus']),
					'num' => 0,
					'status'=>1,
				);
				pdo_insert($this->data, $data);
				$i++;
			}
			message('成功生成'.intval($_GPC['sNum']).'条防伪码！', referer(), 'success');
		}
		if (checksubmit('submitone')) {
			$security = $_GPC['security'];
			$list = pdo_fetchall("SELECT *  from ".tablename($this->data)." where code = '{$security}'");
			if (!empty($list)) {
				message('防伪码已存在，请修改');
			}
			$insert =array(
				'code' => $security,
				'type' => $_GPC['sName2'],
				'factory' => $_GPC['sFactory2'],
				'stime' => strtotime($_GPC['sTime_2']),
				'createtime' => time(),
				'creditname' => $_GPC['creditname2'],
				'creditnum' => intval($_GPC['creditnum2']),
				'creditstatus' => intval($_GPC['creditstatus2']),
				'num' => 0,
				'status'=>1,
			);
			pdo_insert($this->data, $insert);
			message('成功添加防伪码！', referer(), 'success');
		}
		include $this->template('create');
	}

	public function doWebInsert(){
		global $_GPC, $_W ;
		if (!$_W['isfounder']) {
			message('非管理员无权限，谢谢使用');
		}
		load()->func('file');
		load()->func('tpl');
		if (checksubmit('submit')) {
			$tmp = $_FILES['file']['tmp_name'];
			if (empty ($tmp)) {
				message('请选择要导入的EXCEL或TXT(.xls,.txt)文件！', referer(), 'error');	
			}
			switch ($_FILES['file']['type']){
				case "application/kset" :
					break;
				case "application/excel" :
					break;
				case "application/vnd.ms-excel" :
					break;
				case "application/msexcel" :
					break;
				case "application/msexcel" :
					break;
				case "text/plain" :
					break;
				default:
					$flag = 1;
			}
			if($flag ==1){
				message('目前只支持EXCEL和TXT(.xls,.txt)格式文件！', referer(), 'error');	
			}
			$save_path =IA_ROOT."/attachment/";
			if (strpos($_FILES['file']['type'],'excel')) {
				$file_name = $save_path.date('Ymdhis') . ".xls";
				if (move_uploaded_file($tmp, $file_name)) {
					$xls = new Spreadsheet_Excel_Reader();
					$xls->setOutputEncoding('utf-8');
					$xls->read($file_name);
					$i=1;
					$len = $xls->sheets[0]['numRows'];
			
					while($i<=$len){
						$temp = $xls->sheets[0]['cells'][$i][1];
						if(!empty($temp)){
							$data =array(
								'code' => $temp,
								'type' => $_GPC['sName'],
								'factory' => $_GPC['sFactory'],
								'stime' => strtotime($_GPC['sTime']),
								'createtime' => time(),
								'creditname' => $_GPC['creditname'],
								'creditnum' => intval($_GPC['creditnum']),
								'creditstatus' => intval($_GPC['creditstatus']),
								'num' => 0,
								'status'=>1,
							);	
							pdo_insert($this->data, $data);
						}				
						$i++;
					}
					unlink($file_name);
					message('成功导入'.$len.'条防伪码！', referer(), 'success');
				}
			}elseif (strpos($_FILES['file']['type'],'plain')) {
				$file_name = $save_path.date('Ymdhis') . ".txt";
				if (move_uploaded_file($tmp, $file_name)) {
					$txt = file_get_contents($file_name);
					$txt = explode("\r\n",$txt);
					$len = count($txt);
					foreach ($txt as $key => $value) {
						if(!empty($value)){
							$data =array(
								'code' => $value,
								'type' => $_GPC['sName'],
								'factory' => $_GPC['sFactory'],
								'stime' => strtotime($_GPC['sTime']),
								'createtime' => time(),
								'creditname' => $_GPC['creditname'],
								'creditnum' => intval($_GPC['creditnum']),
								'creditstatus' => intval($_GPC['creditstatus']),
								'num' => 0,
								'status'=>1,
							);	
							pdo_insert($this->data, $data);
						}		
					}
					unlink($file_name);
					message('成功导入'.$len.'条防伪码！', referer(), 'success');
				}
			}else{
				echo strpos($_FILES['file']['type'],'plain');
				message('目前只支持EXCEL和TXT(.xls,.txt)格式文件！~~');	
			}
		}
		include $this->template('insert');
	}
	/**
	 * 生成随机数
	 *
	 * @param int $length 生成字符串长度
	 * @param int $type 字符串类型
	 * @param bool $special 是否使用特殊字符
	 * @return string 返回生成的随机字符串
	 * @example random(10, null, true);
	 */
	public function random($length, $type = NULL, $special = FALSE){
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

	//冻结 status至设为0
	public function doWebFrozen(){
		global $_GPC, $_W;
		pdo_update($this->data, array('status' => 0), array('id' => $_GPC['id']));	
		message('成功冻结该防伪码！', referer(), 'success');	
	}
	//删除防伪码 彻底删除数据
	public function doWebDelete(){
		global $_GPC, $_W;
		if(!empty($_GPC['id'])){
			$set = pdo_delete($this->data, array('id' => $_GPC['id']));	
			message('成功删除此条防伪码！', referer(), 'success');	
		}
	}

	public function doWebCheckepre(){
		global $_GPC, $_W;
		$sStr = $_GPC['sStr'];
		$list = pdo_fetchall("SELECT *  from ".tablename($this->data)." where code like '{$sStr}%'");
		if (!empty($list)) {
			echo count($list);
		}else{
			echo '0';
		}
	}

	public function doWebCheckesecurity(){
		global $_GPC, $_W;
		$security = $_GPC['security'];
		$list = pdo_fetchall("SELECT *  from ".tablename($this->data)." where code = '{$security}'");
		if (!empty($list)) {
			echo '1';
		}else{
			echo '0';
		}
	}

	public function doWebLogs(){
		$t = mktime(0, 0, 0, date("m",time()), date("d",time()), date("y",time()));
		$t1 = $t - 7 * 86400;
		$t2 = $t - 6 * 86400;
		$t3 = $t - 5 * 86400;
		$t4 = $t - 4 * 86400;
		$t5 = $t - 3 * 86400;
		$t6 = $t - 2 * 86400;
		$t7 = $t - 1 * 86400;
		$t8 = $t + 1 * 86400;
		$labels = '"'.date('Y-m-d',$t1).'","'.date('Y-m-d',$t2).'","'.date('Y-m-d',$t3).'","'.date('Y-m-d',$t4).'","'.date('Y-m-d',$t5).'","'.date('Y-m-d',$t6).'","'.date('Y-m-d',$t7).'","'.date('Y-m-d',$t).'"';
		$d1_1 = $this->igetlog($t1,$t2,'2');
		$d1_2 = $this->igetlog($t1,$t2,'1');
		$d2_1 = $this->igetlog($t2,$t3,'2');
		$d2_2 = $this->igetlog($t2,$t3,'1');
		$d3_1 = $this->igetlog($t3,$t4,'2');
		$d3_2 = $this->igetlog($t3,$t4,'1');
		$d4_1 = $this->igetlog($t4,$t5,'2');
		$d4_2 = $this->igetlog($t4,$t5,'1');
		$d5_1 = $this->igetlog($t5,$t6,'2');
		$d5_2 = $this->igetlog($t5,$t6,'1');
		$d6_1 = $this->igetlog($t6,$t7,'2');
		$d6_2 = $this->igetlog($t6,$t7,'1');
		$d7_1 = $this->igetlog($t7,$t,'2');
		$d7_2 = $this->igetlog($t7,$t,'1');
		$d8_1 = $this->igetlog($t,$t8,'2');
		$d8_2 = $this->igetlog($t,$t8,'1');
		$data_1 = $d1_1.','.$d2_1.','.$d3_1.','.$d4_1.','.$d5_1.','.$d6_1.','.$d7_1.','.$d8_1;
		$data_2 = $d1_2.','.$d2_2.','.$d3_2.','.$d4_2.','.$d5_2.','.$d6_2.','.$d7_2.','.$d8_2;
		$data_1_all = $this->igetlog('0',time(),'2');
		$data_2_all = $this->igetlog('0',time(),'1');
		$data_3_all = $this->igetlog('0',time(),'0');
		include $this->template('logs');
	}

	protected function igetlog($t1,$t2,$status){
		global $_GPC, $_W;
		if ($status == '2') {
			$data = pdo_fetchcolumn("SELECT COUNT(*)  from ".tablename($this->i_logs)." where weid ='{$_W['uniacid']}' and createtime >= '{$t1}' and createtime <= '{$t2}'");
		}else{
			$data = pdo_fetchcolumn("SELECT COUNT(*)  from ".tablename($this->i_logs)." where weid ='{$_W['uniacid']}' and createtime >= '{$t1}' and createtime <= '{$t2}' and status = '{$status}'");
		}
		return $data;
	}

}