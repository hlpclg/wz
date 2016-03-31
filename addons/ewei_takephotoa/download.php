<?php
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
global $_GPC,$_W;
$rid= intval($_GPC['rid']);
if(empty($rid)){
    message('抱歉，传递的参数错误！','', 'error');              
}

  $list = pdo_fetchall("SELECT * FROM " . tablename('ewei_takephotoa_fans') . " WHERE rid = {$rid}  ORDER BY score DESC ");
  $tableheader = array('ID','排名', '微信码', '昵称','分数','参与时间');
$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) {
	$html .= $value . "\t ,";
}
$html .= "\n";
foreach ($list as $key => $value) {
	$html .= $value['id'] . "\t ,";
                     $html .= ($key +1) . "\t ,";
	 $html .= $value['openid'] . "\t ,";	
	$html .= $value['nickname'] . "\t ,";	
	$html .= $value['score'] . "\t ,";	
        	$html .= date('Y-m-d H:i:s', $value['createtime']) . "\n";	
}


header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=全部数据.csv");

echo $html;
exit();
