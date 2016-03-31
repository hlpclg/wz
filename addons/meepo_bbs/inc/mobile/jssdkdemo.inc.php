<?php /*折翼天使资源社区 www.zheyitianshi.com*/
global $_W,$_GPC;

$tempalte = $this->module['config']['name']?$this->module['config']['name']:'default';

include $this->template($tempalte.'/templates/jssdkdemo');