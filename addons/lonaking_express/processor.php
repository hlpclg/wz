<?php
/**
 * 快递专家模块处理程序
 *
 * @author lonaking
 * @url http://bbs.012wz.com/thread-7940-1-1.html
 */
defined('IN_IA') or exit('Access Denied');

class Lonaking_expressModuleProcessor extends WeModuleProcessor
{

    /**
     * 表名称
     */
    private $table_express_info = 'express_info';

    public function respond()
    {
        global $_W;
        $openid = $_W['openid'];
        $uniacid = $_W['uniacid'];
        $msg = $this->message['content'];
        // 退出当前会话
        if ($msg == "退出") {
            $this->endContext();
            return $this->respText("您已经成功退出快递专家");
        }
        
        // 开启会话
        if (! $this->inContext) {
            $this->beginContext(1800);
        }
        $this->refreshContext(1800);
        
        // 判断输入是否为数字 也就是是否为运单号
        
        $express_name_en = $this->get_express_name($msg);
        $is_express = $this->check_is_express_id($express_name_en);
        if ($is_express) {
            $order_id = $msg;
            $express_info = $this->get_express_info($order_id);
            if ($express_info['status'] == "200") {
                $db_insert_info = $this->compire_info($express_info);
                $flag = false;
                $flag = $this->exist_express($openid, $order_id);
                if ($flag == false) {
                    $this->insert_info($db_insert_info);
                }
            }
            $express_info = $this->transfer_express_info($express_info);
            
            return $this->respText("您查询的快递单号是:\n".$order_id."\n(". $db_insert_info['company'] .")\n\n".$express_info);
        } else {
            //return $this->respText("您好，您输入的".$msg."并不是快递单号");
        }
        $last_express_info = $this->get_last_express_info($openid, $uniacid);
        if (is_null($last_express_info)) {
            // 1.首次查询，请输入您的订单号
            return $this->respText("请输入您的运单号");
        } else {
            // 2. 二次来 您上次的快递是。。。。如果您要查询 请输入运单号。。。。
            return $this->respText("您上一次查询的单号是:\n" . $last_express_info['order_id'] . ",\n(" . $last_express_info['company'] . ")\n\n" . $last_express_info['express_info'] . "\n\n如果您要查询别的单号,请直接输入单号。\n\n tip:输入“退出”指令即可退出快递查询");
        }
    }

    /**
     * 检测运单号是否为快递单号
     * @param unknown $express_id
     * @return boolean
     */
    private function check_is_express_id($express_name_en){
    	if("未知" == $express_name_en)
    		return false;
    	return true;
    }
    /**
     * 判断数据库中是否存在一个订单号
     * 
     * @param unknown $openid            
     * @param unknown $express_id            
     * @return boolean
     */
    private function exist_express($openid, $express_id)
    {
        $sql = "SELECT id,weid,express_id,`status`,company,create_at,update_at,openid FROM " . tablename($this->table_express_info) . " WHERE `openid` = '{$openid}' AND `express_id` = '{$express_id}' ORDER BY id desc";
        $all = pdo_fetchall($sql);
        if (empty($all))
            return false;
        return true;
    }

    /**
     * 获取最后一次查询的记录
     * 
     * @param unknown $openid            
     * @param unknown $uniacid            
     * @return string|NULL
     */
    private function get_last_express_info($openid, $uniacid)
    {
        $sql = "SELECT id,weid,express_id,`status`,company,create_at,update_at,openid FROM " . tablename($this->table_express_info) . " WHERE `openid` = '{$openid}' AND `weid` = '{$uniacid}' ORDER BY id desc";
        $exist_expresses = pdo_fetchall($sql);
        if (! empty($exist_expresses)) {
            $express = $exist_expresses[0];
            if (! empty($express)) {
                $order_id = $express['express_id'];
                $express_info = $this->get_express_info($order_id);
                $express_info = $this->transfer_express_info($express_info);
                $express['order_id'] = $order_id;
                $express['express_info'] = $express_info;
                return $express;
                // return $this->respText ( "您上一次查询的单号是:\n" . $order_id . ",\n" . $express_info . "\n\n如果您要查询别的单号,请直接输入单号" );
            }
        }
        return null;
    }

    private function get($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }

    private function get_express_name($order)
    {
        $name = $this->get("http://www.kuaidi100.com/autonumber/auto?num=" . $order);
        $json_result = json_decode($name, true);
        
        $expres_pinyin_name = $json_result[0]['comCode'];
        if ($expres_pinyin_name == "" || $expres_pinyin_name == null)
            $expres_pinyin_name = "未知";
        return $expres_pinyin_name;
    }

    private function get_detail($express_id)
    {
        $express_name = $this->get_express_name($express_id);
        $url = "http://www.kuaidi100.com/query?type=" . $express_name . "&postid=" . $express_id;
        $json_result = $this->get($url);
        $result = json_decode($json_result, true);
        return $result;
    }

    private function get_last_update($json)
    {
        $express_detail = $json['data'];
        $len = count($express_detail);
        if ($len == 0) {
            return "暂无此单号信息";
        }
        $last_update_time = $express_detail[0]['time'];
        $last_content = $express_detail[0]['context'];
        return "最后一次更新是:\n" . $last_content . "\n\n更新时间:\n" . $last_update_time;
    }

    /**
     * 获取快递最后一次更新时间
     *
     * @param unknown $express_id            
     * @return string
     */
    private function get_express_info($express_id)
    {
        if ($express_id != "" || null != $express_id) {
            $detail = $this->get_detail($express_id);
            return $detail;
        }
    }

    /**
     * 简化信息
     *
     * @param unknown $info            
     * @return string
     */
    private function transfer_express_info($info)
    {
        $detail = $this->get_last_update($info);
        return $detail;
    }

    private function compire_info($info)
    {
        global $_W;
        $insert_express_info = array();
        $insert_express_info['weid'] = $_W['acid'];
        $insert_express_info['openid'] = $_W['openid'];
        $insert_express_info['express_id'] = $info['nu'];
        $insert_express_info['status'] = $info['status'];
        //$insert_express_info['company'] = $info['com'];
        $company_name = $this->trans_pin_to_cn($info['com']);
        $insert_express_info['company'] = $company_name;
        $insert_express_info['create_at'] = $_W['timestamp'];
        $insert_express_info['update_at'] = TIMESTAMP;
        return $insert_express_info;
    }
    /**
     * 将公司拼音转为文字
     * @param unknown $com
     */
    private function trans_pin_to_cn($com){
    	include_once 'company.php';
    	return $company[$com];
    }

    /**
     * 业务逻辑
     *
     * @param unknown $info            
     */
    private function insert_info($info)
    {
        pdo_insert($this->table_express_info, $info);
    }

    private function update_info($info)
    {
        pdo_insert($this->table_express_info, $info);
    }
}