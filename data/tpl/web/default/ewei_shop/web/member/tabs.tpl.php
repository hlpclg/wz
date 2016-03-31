<?php defined('IN_IA') or exit('Access Denied');?><ul class="nav nav-tabs">
    <li <?php  if(($_GPC['p'] == 'list' || empty($_GPC['p']))) { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('member/list',array('isagent'=>0))?>">会员管理</a></li>
    
    <?php  if($_GPC['p'] == 'recharge' && $_GPC['op']=='credit1') { ?>
    <li class="active"><a href="<?php  echo $this->createWebUrl('member/recharge',array('op'=>'credit1'))?>">充值积分</a></li>
    <?php  } ?>
    
    <?php  if($_GPC['p'] == 'recharge' && $_GPC['op']=='credit2') { ?>
    <li class="active"><a href="<?php  echo $this->createWebUrl('member/recharge',array('op'=>'credit2'))?>">充值余额</a></li>
    <?php  } ?>
    
    <li <?php  if($_GPC['p'] == 'level') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('member/level')?>">会员等级</a></li>
    <li <?php  if($_GPC['p'] == 'group') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('member/group')?>">会员分组</a></li>
   
</ul> 
