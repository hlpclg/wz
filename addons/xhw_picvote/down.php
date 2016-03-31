<?php
ini_set("display_errors", "ON");
error_reporting(E_ALL);
define("IN_IA",true);
require '../../data/config.php';
$config = $config['db'];

 

$conn=@mysql_connect($config['host'],$config['username'],$config['password']) or die (mysql_error());
mysql_select_db($config['database'],$conn);
mysql_query("SET NAMES 'GBK'");
$result = mysql_query("SELECT * FROM  `ims_xhw_voice_reg` WHERE  `weid` =".$_GET['weid']." AND  `pass` =1 ORDER BY  `num` DESC LIMIT 0 , 5000");   
 
    $str = "姓名,昵称,票数,人气,手机\n"; 
   while($row=mysql_fetch_array($result))   
    {   
       $title = $row['title'];
        $nickname = $row['nickname'];
        $phone = $row['phone'];
        $num = $row['num'];
        $sharenum = $row['sharenum'];
        $str .= $title.",".$nickname.",".$num.",".$sharenum.",".$phone."\n"; 
    }   
    $filename = date('Ymd').'.csv'; //设置文件名   
    export_csv($filename,$str); //导出   


function export_csv($filename,$data)   
{   
    header("Content-type:text/csv");   
    header("Content-Disposition:attachment;filename=".$filename);   
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');   
    header('Expires:0');   
    header('Pragma:public');   
    echo $data;   
}  
