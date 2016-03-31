<?php
/**
 *
 */
defined('IN_IA') or exit('Access Denied');
define('RES', '../addons/bobo_love/template/');

class bobo_loveModuleSite extends WeModuleSite
{
    public $title = '七夕我爱你';

    function __construct()
    {
        global $_W, $_GPC;

    }

    public function doMobileIndex()
    {
        global $_W, $_GPC;
        if($_POST){
        	$data['me']=$_POST['me'];
        	$data['you']=$_POST['you'];
        	$data['yi']=$_POST['yi'];
        	$data['wu']=$_POST['wu'];
        	$data['year']=$_POST['year'];
        	$data['yue']=$_POST['yue'];
        	$data['ri']=$_POST['ri'];
        	$url=$this->createMobileUrl('show', $data);
        	message('', $url, '');exit;
        }
        include $this->template('index');
    }
    public function doMobileShow(){
    	$me=$_GET['me'];
    	$you=$_GET['you'];
    	$yi=$_GET['yi'];
    	$wu=$_GET['wu'];
    	$year=$_GET['year'];
    	$yue=$_GET['yue'];
    	$ri=$_GET['ri'];
    	include $this->template('showlove');
    }
  
}