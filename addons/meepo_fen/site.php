<?php
/**
 * 全民总动员模块订阅器
 *
 * @author meepo
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
define('INC_PATH',IA_ROOT.'/addons/meepo_fen/inc/');
define('TEMPLATE_PATH','../addons/meepo_fen/template/mobile/resource/');
include INC_PATH.'core/class/mload.class.php';
mload()->func('common');
load()->model('activity');

class Meepo_fenModuleSite extends WeModuleSite {
	public $modulename = 'meepo_fen';
	public function __construct(){
		mload()->model('frame');
		$do = $_GPC['do'];
		$doo = $_GPC['doo'];
		$act = $_GPC['act'];
	
		global $frames;
		$frames = getModuleFrames('meepo_fen');
		_calc_current_frames2($frames);
	}
	
	public function get_set(){
		global $_W;
		$set = pdo_fetch("SELECT * FROM ".tablename('meepo_fen_set')." WHERE uniacid = '{$_W['uniacid']}'");
		$set = iunserializer($set['set']);
		return $set;
	}
}