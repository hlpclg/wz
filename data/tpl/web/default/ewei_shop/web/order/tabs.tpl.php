<?php defined('IN_IA') or exit('Access Denied');?><ul class="nav nav-tabs">
      <?php if(cv('order.view.status0|order.view.status1|order.view.status2|order.view.status3|order.view.status4|order.view.status_1')) { ?>
    <li <?php  if($operation == 'display' && $status == '' && $_GPC['refund']!='1') { ?>class="active"<?php  } ?>>
        <a href="<?php  echo $this->createWebUrl('order', array('op' => 'display'))?>">全部订单(<?php  echo $totals['all'];?>)</a>
    </li>
    <?php  } ?>
    
    <?php if(cv('order.view.status0')) { ?>
    <li <?php  if($operation == 'display' && $status == '0') { ?>class="active"<?php  } ?>>
        <a href="<?php  echo $this->createWebUrl('order', array('op' => 'display', 'status' => 0))?>">待付款(<?php  echo $totals['status0'];?>)</a>
    </li>
    <?php  } ?>
    
    <?php if(cv('order.view.status1')) { ?>
    <li <?php  if($operation == 'display' && $status == '1') { ?> class="active"<?php  } ?>>
        <a href="<?php  echo $this->createWebUrl('order', array('op' => 'display', 'status' => 1))?>">待发货(<?php  echo $totals['status1'];?>)</a>
    </li>
    <?php  } ?>
    
    <?php if(cv('order.view.status2')) { ?>
    <li <?php  if($operation == 'display' && $status == '2') { ?>class="active"<?php  } ?>>
        <a href="<?php  echo $this->createWebUrl('order', array('op' => 'display', 'status' => 2))?>">待收货(<?php  echo $totals['status2'];?>)</a>
    </li>
    <?php  } ?>
    
    <?php if(cv('order.view.status3')) { ?>
    <li <?php  if($operation == 'display' && $status == '3') { ?>class="active"<?php  } ?>>
        <a href="<?php  echo $this->createWebUrl('order', array('op' => 'display', 'status' => 3))?>">已完成(<?php  echo $totals['status3'];?>)</a>
    </li>
    <?php  } ?>
    
     <?php if(cv('order.view.status_1')) { ?>
    <li <?php  if($operation == 'display' && $status == '-1') { ?>class="active"<?php  } ?>>
        <a href="<?php  echo $this->createWebUrl('order', array('op' => 'display', 'status' => -1))?>">已关闭(<?php  echo $totals['status_1'];?>)</a>
    </li>
    <?php  } ?>
      
    
    <?php if(cv('order.view.status4')) { ?>
     <li <?php  if($operation == 'display' && $status== '4') { ?>class="active"<?php  } ?>>
        <a href="<?php  echo $this->createWebUrl('order', array('op' => 'display', 'status' => 4))?>">退款申请(<?php  echo $totals['status4'];?>)</a>
    </li>
    <?php  } ?>
     
    <?php if(cv('order.view.status5')) { ?>
    <li <?php  if($operation == 'display' && $status == '5') { ?>class="active"<?php  } ?>>
        <a href="<?php  echo $this->createWebUrl('order', array('op' => 'display', 'status' => 5))?>">已退款(<?php  echo $totals['status5'];?>)</a>
    </li>
    <?php  } ?>
    
    <?php  if($operation == 'detail') { ?>
    <li class="active">
        <a href="#">订单详情</a>
    </li>
    <?php  } ?>
</ul>
