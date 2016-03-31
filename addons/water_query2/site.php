<?php
/**
 * 查询模块微站定义
 *
 * @author 岳来越好
 * @url http://bbs.012wz.com/
 */
defined ( 'IN_IA' ) or exit ( 'Access Denied' );
class water_query2ModuleSite extends WeModuleSite {
	

	
	/**
	 * 访问首页
	 */
	public function doMobileIndex() {
		global $_GPC, $_W;

		include $this->template ( 'index' );
		
	}

	/**
	 * 查询查询结果
	 */
	public function doMobileQuery() {
		global $_GPC, $_W;
		$keyword = $_GPC ['code'];
		if(empty($keyword)){
			message('参数为空');
		}
		$info = pdo_fetch ( "SELECT * FROM " . tablename ( 'water_query2_info' ) . " WHERE  uniacid = '" . $_W ['uniacid'] . "' and keyword= '" . $keyword."'" );
		if(empty($info)){
			$info['keyword'] = $keyword;
			$info['ordercode'] = ' 对不起，没有查到该对应的信息，请核实手机号码后再查询。';
		}
		include $this->template ( 'result' );
	
	}
	

	

	
	
	public function dowebInfo() {
		global $_W, $_GPC;
		$pageNumber = max ( 1, intval ( $_GPC ['page'] ) );
		$pageSize = 10;
		$sql = "SELECT * FROM " . tablename ( 'water_query2_info') . " WHERE uniacid = '" . $_W ['uniacid'] . "' ORDER BY id LIMIT " . ($pageNumber - 1) * $pageSize . ',' . $pageSize;
		$list = pdo_fetchall ( $sql );
		$total = pdo_fetchcolumn ( 'SELECT COUNT(*) FROM ' . tablename ( 'water_query2_info' ) . " WHERE uniacid = '" . $_W ['uniacid'] . "' ORDER BY id DESC" );
		$pager = pagination ( $total, $pageNumber, $pageSize );
		include $this->template ( 'info' );
	}

	public function doWebBatchAddInfo() {
		global $_W, $_GPC;
		load ()->func ( 'tpl' );
		if (checksubmit ()) {
			$excelfile =  $_FILES ['exceldata'];
			if(empty($excelfile)){
				message('请先上传文件');
			}
			$str = explode(".",$excelfile['name']);
			$filetype = strtolower($str[1]);
			if(!($filetype == 'xlsx' || $filetype == 'xls')){
				message('上传文件类型不正确！');
			}
 			$path='../attachment/excel/water/';
 			if(!file_exists($path)){
 				mkdir($path);
 			}
 			$time = time();
 			$filePath=$path.$time.'.'.$filetype;
 			move_uploaded_file($excelfile['tmp_name'],$filePath);
 			
			//首先导入PHPExcel
			require_once 'PHPExcel.php';
			
			//$filePath = "../addons/water_query2/test.xlsx";
			
			//建立reader对象
			$PHPReader = new PHPExcel_Reader_Excel2007();
			if(!$PHPReader->canRead($filePath)){
			    $PHPReader = new PHPExcel_Reader_Excel5();
			    if(!$PHPReader->canRead($filePath)){
			        echo 'no Excel';
			        return ;
			    }
			}
			
			//建立excel对象，此时你即可以通过excel对象读取文件，也可以通过它写入文件
			$PHPExcel = $PHPReader->load($filePath);
			
			/**读取excel文件中的第一个工作表*/
			$currentSheet = $PHPExcel->getSheet(0);
			/**取得最大的列号*/
			$allColumn = $currentSheet->getHighestColumn();
			echo $allColumn.'---';
			/**取得一共有多少行*/
			$allRow = $currentSheet->getHighestRow();
			echo $allRow.'----';
			//循环读取每个单元格的内容。注意行从1开始，列从A开始
			$data = array(
					'uniacid'=>$_W ['uniacid'],
			);
			for($rowIndex=1;$rowIndex<=$allRow;$rowIndex++){
			    for($colIndex='A';$colIndex<=$allColumn;$colIndex++){
			        $addr = $colIndex.$rowIndex;
					$cell = $currentSheet->getCell($addr)->getValue();
			        if($cell instanceof PHPExcel_RichText){    //富文本转换字符串
						$cell = $cell->__toString();
			        }   
			        //echo '第'.$rowIndex.'行，第'.$colIndex.'列：'.$cell.'</br>';
			       if($colIndex == 'A'){
			       		$data['keyword'] = $cell;
			       }else{
			       		$data['ordercode'] = $cell;
			       }
			    }
			    pdo_insert ( 'water_query2_info', $data );
			
			}

			message ( '导入成功！', $this->createWebUrl ( 'Info' ), 'success' );
		}else{
			include $this->template ( 'batchaddinfo' );
		}
	}
	
	
	public function doWebAddInfo() {
		global $_W, $_GPC;
		load ()->func ( 'tpl' );
		$infoid = intval ( $_GPC ['infoid'] );
		if ($infoid) {
			$info = pdo_fetch ( "SELECT * FROM " . tablename ( 'water_query2_info' ) . " WHERE id= " . $infoid );
		}
		if ($_GPC ['op'] == 'delete') {
			$infoid = intval ( $_GPC ['infoid'] );
			$info = pdo_fetch ( "SELECT id FROM " . tablename ( 'water_query2_info' ) . " WHERE id = " . $infoid );
			if (empty ( $info )) {
				message ( '抱歉，信息不存在或是已经被删除！' );
			}
			pdo_delete ( 'water_query2_info', array (
					'id' => $infoid 
			) );
			message ( '删除成功！', referer (), 'success' );
		}
		
		if (checksubmit ()) {
			$data = array (
					'keyword' => $_GPC ['keyword'],
					'ordercode' => $_GPC ['ordercode'],
					'info' => htmlspecialchars_decode ( $_GPC ['info'] ),
					'infophoto' => $_GPC ['infophoto'],
			);
			
			if (! empty ( $infoid )) {
				pdo_update ( 'water_query2_info', $data, array (
						'id' => $infoid 
				) );
			} else {
				$data ['uniacid'] = $_W ['uniacid'];
				pdo_insert ( 'water_query2_info', $data );
				$infoid = pdo_insertid ();
			}
			message ( '更新成功！', referer (), 'success' );
		}
		include $this->template ( 'addinfo' );
	}

}