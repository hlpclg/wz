<?php
/**
 * 敲钟上市
 *
 * @author ewei
 */
defined('IN_IA') or exit('Access Denied');

class Ewei_shangshiModuleSite extends WeModuleSite {

    public function doMobileIndex() {
    
        global $_W,$_GPC;
        
        //是否关注
        $follow = false;
        $openid = $_W['openid'];
        if(!empty($openid)){
            $f = pdo_fetch("select follow from " . tablename('mc_mapping_fans') . " where openid=:openid limit 1", array(":openid" => $openid));
            $follow = $f['follow']==1;
         }
         
        $followneed = !empty($this->module['config']['followneed']);
        $copyright = empty($this->module['config']['copyright']) ?$_W['account']['name']:$this->module['config']['copyright'];
        $followurl = empty($this->module['config']['followurl']) ?'':$this->module['config']['followurl'];
        
        $corp = $_GPC['corp'];
        if(empty($corp)){
            $corp = $copyright;
        }
        include $this->template('index');
        
    }
    
}