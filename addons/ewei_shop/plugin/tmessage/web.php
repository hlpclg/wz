<?php
//捌零网络科技有限公司QQ2316571101
if (!defined('IN_IA')) {
    exit('Access Denied');
}
class TmessageWeb extends Plugin
{
    public function __construct()
    {
        parent::__construct('tmessage');
    }
    public function index()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
}