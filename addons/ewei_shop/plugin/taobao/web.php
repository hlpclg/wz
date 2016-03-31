<?php
//捌零网络科技有限公司QQ2316571101
if (!defined('IN_IA')) {
    exit('Access Denied');
}
require_once 'model.php';
class TaobaoWeb extends Plugin
{
    public function __construct()
    {
        parent::__construct('taobao');
    }
    public function index()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function fetch()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
}