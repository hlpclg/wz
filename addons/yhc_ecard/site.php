<?php
#ini_set("display_errors", "On");
#error_reporting(0);
/**
 * 微名片模块微站定义
 *
 * @author yhctech
 */
defined('IN_IA') or exit('Access Denied');
define('MODULE_ROOT', IA_ROOT . '/addons/yhc_ecard');
require_once "phpqrcode.php";
require_once "weixin.php";
class Yhc_ecardModuleSite extends WeModuleSite {

	public function doMobileIndex() {
		//这个操作被定义用来呈现 功能封面
        $this->__mobile(__FUNCTION__);
	}
    public function doMobileCollect() {
        //这个操作被定义用来呈现 功能封面
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileCollectView() {
        //这个操作被定义用来呈现 功能封面
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileCollectDelete() {
        //这个操作被定义用来呈现 功能封面
        $this->__mobile(__FUNCTION__);
    }
    public function doMobilePocketView() {
        //这个操作被定义用来呈现 功能封面
        $this->__mobile(__FUNCTION__);
    }
    public function doMobilePocketForm() {
        //这个操作被定义用来呈现 功能封面
        $this->__mobile(__FUNCTION__);
    }
    public function doMobilePocketList() {
        //这个操作被定义用来呈现 功能封面
        $this->__mobile(__FUNCTION__);
    }
    public function doMobilePocketUpdate() {
        //这个操作被定义用来呈现 功能封面
        $this->__mobile(__FUNCTION__);
    }
    public function doMobilePocketDelete() {
        //这个操作被定义用来呈现 功能封面
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileQr() {
        //这个操作被定义用来呈现 功能封面
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileUpdate() {
        //这个操作被定义用来呈现 功能封面
        $this->__mobile(__FUNCTION__);
    }
	public function doWebList() {
		//这个操作被定义用来呈现 管理中心导航菜单
        $this->__web(__FUNCTION__);
	}

    //后台管理程序 web文件夹下
    public function __web($f_name) {
        global $_W, $_GPC;
        checklogin();
        //每个页面都要用的公共信息，今后可以考虑是否要运用到缓存
        include_once 'web/'.strtolower(substr($f_name,8 , 1)).substr($f_name,9).'.php';
    }

    public function __mobile($f_name){
        global $_W,$_GPC;
        $openid=$_W['fans']['from_user'] ;
        $weid=$_W['uniacid'];
        $setting= $this->get_sysset($weid);
        // $this->checkIsWeixin();

        include_once 'mobile/'.strtolower(substr($f_name,8 , 1)).substr($f_name,9).'.php';
    }

    private function checkIsWeixin(){
        $user_agent= $_SERVER['HTTP_USER_AGENT'];
        if(strpos($user_agent, 'MicroMessenger') === false) {
            message("抱歉，请在微信内部打开", "", "info");
            exit;
        }
    }

}