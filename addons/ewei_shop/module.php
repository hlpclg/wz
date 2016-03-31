<?php
//捌零网络科技有限公司QQ2316571101
if (!defined('IN_IA')) {
    exit('Access Denied');
}
class Ewei_shopModule extends WeModule
{
    public function fieldsFormDisplay($rid = 0)
    {
    }
    public function fieldsFormSubmit($rid = 0)
    {
        return true;
    }
    public function settingsDisplay($settings)
    {
    }
}