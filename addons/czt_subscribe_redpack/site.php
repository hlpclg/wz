<?php
//加密方式：php源码混淆类加密。免费版地址:http://www.zhaoyuanma.com/phpjm.html 免费版不能解密,可以使用VIP版本。
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (免费版）在线逆向还原，QQ：7530782 
?>
<?php
/**
 * 关注送红包模块微站定义
 *
 * @author 
 * @url 折翼天使资源社区
 */

defined('IN_IA') or exit('Access Denied');
define('M_PATH', IA_ROOT . '/addons/czt_subscribe_redpack');

class Czt_subscribe_redpackModuleSite extends WeModuleSite {

	public function doWebApi() {
        global $_W, $_GPC;
        if(checksubmit()) {
            load()->func('file');
            mkdirs(M_PATH . '/cert');
            $r = true;
            if(!empty($_GPC['cert'])) {
                $ret = file_put_contents(M_PATH . '/cert/apiclient_cert.pem.' . $_W['uniacid'], trim($_GPC['cert']));
                $r = $r && $ret;
            }
            if(!empty($_GPC['key'])) {
                $ret = file_put_contents(M_PATH . '/cert/apiclient_key.pem.' . $_W['uniacid'], trim($_GPC['key']));
                $r = $r && $ret;
            }
            if(!empty($_GPC['ca'])) {
                $ret = file_put_contents(M_PATH . '/cert/rootca.pem.' . $_W['uniacid'], trim($_GPC['ca']));
                $r = $r && $ret;
            }
            if(!$r) {
                message('证书保存失败, 请保证 /addons/czt_subscribe_redpack/cert/ 目录可写');
            }
            $input = array_elements(array('appid', 'secret', 'mchid', 'password', 'ip'), $_GPC);
            $input['appid'] = trim($input['appid']);
            $input['secret'] = trim($input['secret']);
            $input['mchid'] = trim($input['mchid']);
            $input['password'] = trim($input['password']);
            $input['ip'] = trim($input['ip']);
            $setting = $this->module['config'];
            $setting['api'] = $input;
            if($this->saveSettings($setting)) {
                message('保存参数成功', 'refresh');
            }
        }
        $config = $this->module['config']['api'];
        if(empty($config['ip'])) {
            $config['ip'] = $_SERVER['SERVER_ADDR'];
        }
        include $this->template('api');
	}

	public function doWebRecord() {
		global $_W, $_GPC;
		$pageindex = max(intval($_GPC['page']), 1); // 当前页码
		$pagesize = 15; // 设置分页大小
		$where='';
		if (!empty($_GPC['openid'])) {
			$where .=' and openid=\''.$_GPC['openid'].'\' ';
		}
		if (!empty($_GPC['status'])) {
			$where .= ' and status='.intval($_GPC['status']);
		}
		$sql = 'SELECT COUNT(*) FROM '.tablename('czt_subscribe_redpack_records').' where uniacid = :uniacid'.$where;
		$total = pdo_fetchcolumn($sql, array(':uniacid'=>$_W['uniacid']));
		$pager = pagination($total, $pageindex, $pagesize);
		
		$sql = 'SELECT * FROM '.tablename('czt_subscribe_redpack_records')." where uniacid = :uniacid".$where." ORDER BY id asc LIMIT ".(($pageindex -1) * $pagesize).','. $pagesize;
		$list = pdo_fetchall($sql,  array(':uniacid'=>$_W['uniacid']), 'id');
		include $this->template('records');
	}

	public function doWebHelp() {
		include $this->template('help');
	}

	public function doWebActivity() {
		global $_W, $_GPC;
        if($_W['ispost']) {
        	$input = array_elements(array('title', 'provider', 'wish', 'remark', 'fee', 'time', 'image', 'stitle', 'content'), $_GPC);
            $input['time']['start'] = strtotime($input['time']['start'] . ':00');
            $input['time']['end'] = strtotime($input['time']['end'] . ':59');
            $setting = $this->module['config'];
            $setting['activity'] = $input;
            if($this->saveSettings($setting)) {
                message('保存红包设置成功', 'refresh');
            }
        }

        $activity = $this->module['config']['activity'];
        if(empty($activity)) {
            $activity = array();
            $activity['fee']['downline'] = '1';
            $activity['fee']['upline'] = '1';
        }
        if(!is_array($activity['fee'])) {
            $fee = $activity['fee'];
            $activity['fee'] = array();
            $activity['fee']['downline'] = $fee;
            $activity['fee']['upline'] = $fee;
        }
        if(!is_array($activity['time'])) {
            $activity['time'] = array(
                'start' => TIMESTAMP,
                'end'   => TIMESTAMP + 6 * 24
            );
        }
        $activity['time']['start'] = date('Y-m-d H:i', $activity['time']['start']);
        $activity['time']['end'] = date('Y-m-d H:i', $activity['time']['end']);
		load()->func('tpl');
		include $this->template('activity');
	}
}
?>