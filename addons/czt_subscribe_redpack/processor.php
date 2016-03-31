<?php
//加密方式：php源码混淆类加密。免费版地址:http://www.zhaoyuanma.com/phpjm.html 免费版不能解密,可以使用VIP版本。

//发现了time,请自行验证这套程序是否有时间限制.
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (免费版）在线逆向还原，QQ：7530782 
?>
<?php
/**
 * 关注送红包模块处理程序
 *
 * @author 
 * @url http://bbs.we7.cc/
 */

defined('IN_IA') or exit('Access Denied');
define('M_PATH', IA_ROOT . '/addons/czt_subscribe_redpack');

class Czt_subscribe_redpackModuleProcessor extends WeModuleProcessor {

	public function respond() {
		// $content = $this->message['content'];
		// return;
		if ($this->message['event']= "subscribe") {
			$activity = $this->module['config']['activity'];
			if (TIMESTAMP>$activity['time']['start']&&TIMESTAMP<$activity['time']['end']) {
				$r=$this->send($this->message['from']);
				if ($r===true) {
					return $this->respText('恭喜您领到一个红包了！');
				}
				if ($r==='success') {
					return $this->respText('您已经领过红包了！');
				}
				return $this->respText('出错，未能领到红包:(');
			}else{
				return $this->respText('活动已经结束了！');
			}
      	}
	}

	protected function send($openid) {
        global $_W;
        $uniacid = $_W['uniacid'];
        $activity = $this->module['config']['activity'];
        if (empty($openid)) return;
        if(empty($activity)) {
            return error(-2, '系统还未开放');
        }
        $api = $this->module['config']['api'];
        if(empty($api)) {
            return error(-2, '系统还未开放');
        }

        $condition = "`uniacid`=:uniacid AND `openid`=:openid";
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        $pars[':openid'] = $openid;
        $sql = "SELECT * FROM " . tablename('czt_subscribe_redpack_records') . " WHERE {$condition}";
        $ret = pdo_fetch($sql, $pars);

        if (!empty($ret)&&$ret['status']==1) {
        	return 'success';
        }

        if (empty($ret)) {
        	$fee = rand($activity['fee']['downline'] * 100, $activity['fee']['upline'] * 100);
	        $r = array();
	        $r['uniacid'] = $uniacid;
	        $r['openid'] = $openid;
	        $r['log'] = '';
	        $r['create_t'] = time();
	        $r['success_t'] = 0;
	        $r['status'] = 0;
	        $r['fee'] = sprintf('%.2f', $fee / 100);
	        $ret = pdo_insert('czt_subscribe_redpack_records', $r);
	        
	        if(!empty($ret)) {
	            $record_id = pdo_insertid();
	        }else{
	        	return;
	        }
        }else{
        	$record_id=$ret['id'];
        	$fee=$ret['fee']*100;
        }

        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
        $pars = array();
        $pars['nonce_str'] = random(32);
        $pars['mch_billno'] = $api['mchid'] . date('Ymd') . sprintf('%010d', $record_id);
        $pars['mch_id'] = $api['mchid'];
        $pars['wxappid'] = $api['appid'];
        $pars['nick_name'] = $activity['provider'];
        $pars['send_name'] = $activity['provider'];
        $pars['re_openid'] = $openid;
        $pars['total_amount'] = $fee;
        $pars['min_value'] = $pars['total_amount'];
        $pars['max_value'] = $pars['total_amount'];
        $pars['total_num'] = 1;
        $pars['wishing'] = $activity['wish'];
        $pars['client_ip'] = $api['ip'];
        $pars['act_name'] = $activity['title'];
        $pars['remark'] = $activity['remark'];
        $pars['logo_imgurl'] = tomedia($activity['image']);
        $pars['share_content'] = $activity['content'];
        $pars['share_imgurl'] = tomedia($activity['image']);
        $pars['share_url'] = 'https://www.baidu.com/';

        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key={$api['password']}";
        $pars['sign'] = strtoupper(md5($string1));
        $xml = array2xml($pars);
        $extras = array();
        $extras['CURLOPT_CAINFO'] = M_PATH . '/cert/rootca.pem.' . $uniacid;
        $extras['CURLOPT_SSLCERT'] = M_PATH . '/cert/apiclient_cert.pem.' . $uniacid;
        $extras['CURLOPT_SSLKEY'] = M_PATH . '/cert/apiclient_key.pem.' . $uniacid;

        load()->func('communication');
        $procResult = null;
        $resp = ihttp_request($url, $xml, $extras);
        if(is_error($resp)) {
            $procResult = $resp;
        } else {
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
            $dom = new \DOMDocument();
            if($dom->loadXML($xml)) {
                $xpath = new \DOMXPath($dom);
                $code = $xpath->evaluate('string(//xml/return_code)');
                $ret = $xpath->evaluate('string(//xml/result_code)');
                if(strtolower($code) == 'success' && strtolower($ret) == 'success') {
                    $procResult = true;
                } else {
                    $error = $xpath->evaluate('string(//xml/err_code_des)');
                    $procResult = error(-2, $error);
                }
            } else {
                $procResult = error(-1, 'error response');
            }
        }

        if(is_error($procResult)) {
            $filters = array();
            $filters['uniacid'] = $uniacid;
            $filters['id'] = $record_id;
            $rec = array();
            $rec['log'] = $procResult['message'];
            pdo_update('czt_subscribe_redpack_records', $rec, $filters);
            return $procResult;
        } else {
            $filters = array();
            $filters['uniacid'] = $uniacid;
            $filters['id'] = $record_id;
            $rec = array();
            $rec['status'] = 1;
            $rec['success_t'] = time();
            pdo_update('czt_subscribe_redpack_records', $rec, $filters);
            return true;
        }
    }
}