<?php defined('IN_IA') or exit('Access Denied');?><ul class="nav nav-tabs">
    
    <?php  if($_GPC['p'] == 'recharge' && $_GPC['op']=='credit1') { ?>
    <li class="active"><a href="<?php  echo $this->createWebUrl('finance/recharge',array('op'=>'credit1'))?>">充值积分</a></li>
    <?php  } ?>
    
    <?php  if($_GPC['p'] == 'recharge' && $_GPC['op']=='credit2') { ?>
    <li class="active"><a href="<?php  echo $this->createWebUrl('finance/recharge',array('op'=>'credit2'))?>">充值余额</a></li>
    <?php  } ?>
    <?php if(cv('finance.recharge.view')) { ?><li <?php  if(empty($_GPC['p']) || ( $_GPC['p'] == 'log' && $_GPC['type']==0)) { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('finance/log',array('type'=>0))?>">充值记录</a></li><?php  } ?>
    <?php if(cv('finance.withdraw.view')) { ?><li <?php  if($_GPC['p'] == 'log' && $_GPC['type']==1) { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('finance/log',array('type'=>1))?>">提现申请</a></li><?php  } ?>
    <?php if(cv('finance.downloadbill')) { ?><li <?php  if($_GPC['p'] == 'downloadbill' ) { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('finance/downloadbill')?>">下载对账单</a></li><?php  } ?>
    
</ul> 
 