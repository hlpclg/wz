<?php defined('IN_IA') or exit('Access Denied');?><ul class="nav nav-tabs">
    <?php if(cv('shop.goods.view')) { ?><li <?php  if($_GPC['p'] == 'goods' || empty($_GPC['p'])) { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('shop/goods')?>">商品管理</a></li><?php  } ?>
    <?php if(cv('shop.category.view')) { ?><li <?php  if($_GPC['p'] == 'category') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('shop/category')?>">商品分类管理</a></li><?php  } ?>
    <?php if(cv('shop.dispatch.view')) { ?><li <?php  if($_GPC['p'] == 'dispatch') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('shop/dispatch')?>">配送方式</a></li><?php  } ?>
    <?php if(cv('shop.adv.view')) { ?><li <?php  if($_GPC['p'] == 'adv') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('shop/adv')?>">幻灯片管理</a></li><?php  } ?>
    <?php if(cv('shop.notice.view')) { ?><li <?php  if($_GPC['p'] == 'notice') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('shop/notice')?>">公告管理</a></li><?php  } ?>
    <?php if(cv('shop.comment.view')) { ?><li <?php  if($_GPC['p'] == 'comment') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('shop/comment')?>">评价管理</a></li><?php  } ?>
</ul>
