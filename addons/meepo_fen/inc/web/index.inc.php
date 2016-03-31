<?php
global $_W,$_GPC;
mload()->func('table');
table_schema('meepo_common_setting');
include $this->template('index');