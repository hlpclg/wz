<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_header', TEMPLATE_INCLUDEPATH)) : (include template('web/_header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/shop/tabs', TEMPLATE_INCLUDEPATH)) : (include template('web/shop/tabs', TEMPLATE_INCLUDEPATH));?>

<?php  if($operation == 'post') { ?>
<div class="main">
    
    <form  <?php if( ce('shop.category' ,$item) ) { ?>action="" method="post"<?php  } ?> class="form-horizontal form" enctype="multipart/form-data" >
    
        <div class="panel panel-default">
            <div class="panel-heading">
                商品分类
            </div>
            <div class="panel-body">
                
                <?php  if(!empty($item)) { ?>
                 <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">分类连接(点击复制)</label>
                <div class="col-sm-9 col-xs-12">
                    <p class='form-control-static'><a href='javascript:;' title='点击复制连接' id='cp'>
                           <?php  if(empty($parent)) { ?>
                           <?php  echo $this->createMobileUrl('shop/list',array('pcate'=>$item['id']))?>
                           <?php  } else { ?>
                               <?php  if(empty($parent1)) { ?>
                                 <?php  echo $this->createMobileUrl('shop/list',array('ccate'=>$item['id']))?>
                               <?php  } else { ?>
                                 <?php  echo $this->createMobileUrl('shop/list',array('tcate'=>$item['id']))?>
                               <?php  } ?>
                           <?php  } ?>
                        </a>
                    </p>
                </div>
            </div>
                <?php  } ?>
                
                <?php  if(!empty($parentid)) { ?>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">上级分类</label>
                    <div class="col-sm-9 col-xs-12 control-label" style="text-align:left;">
                        <?php  if(!empty($parent1)) { ?><?php  echo $parent1['name'];?> >> <?php  } ?>
                        <?php  echo $parent['name'];?></div>
                </div>
                <?php  } ?>
                
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">排序</label>
                    <div class="col-sm-9 col-xs-12">
                        <?php if( ce('shop.category' ,$item) ) { ?>
                           <input type="text" name="displayorder" class="form-control" value="<?php  echo $item['displayorder'];?>" />
                        <?php  } else { ?>
                           <div class='form-control-static'><?php  echo $item['displayorder'];?></div>
                        <?php  } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>分类名称</label>
                    <div class="col-sm-9 col-xs-12">
                         <?php if( ce('shop.category' ,$item) ) { ?>
                        <input type="text" name="catename" class="form-control" value="<?php  echo $item['name'];?>" />
                           <?php  } else { ?>
                           <div class='form-control-static'><?php  echo $item['name'];?></div>
                        <?php  } ?>
                    </div>
                </div>
               <?php  if(!empty($parentid)) { ?>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">分类图片</label>
                    <div class="col-sm-9 col-xs-12">
                      <?php if( ce('shop.category' ,$item) ) { ?>
                             <?php  echo tpl_form_field_image('thumb', $item['thumb'])?>
                            <span class="help-block">建议尺寸: 100*100，或正方型图片 </span>
                        <?php  } else { ?>
                            <?php  if(!empty($item['thumb'])) { ?>
                                  <a href='<?php  echo tomedia($item['thumb'])?>' target='_blank'>
                                <img src="<?php  echo tomedia($item['thumb'])?>" style='width:100px;border:1px solid #ccc;padding:1px' />
                                  </a>
                            <?php  } ?>
                        <?php  } ?>
                    </div>
                </div>
               <?php  } ?>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">分类描述</label>
                    <div class="col-sm-9 col-xs-12">
                        <?php if( ce('shop.category' ,$item) ) { ?>
                        <textarea name="description" class="form-control" cols="70"><?php  echo $item['description'];?></textarea>
                        <?php  } else { ?>
                         <div class='form-control-static'><?php  echo $item['description'];?></div>
                        <?php  } ?>
                        
                    </div>
                </div> 
               <?php  if($level<=2) { ?>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">分类广告</label>
                    <div class="col-sm-9 col-xs-12">
                        <?php if( ce('shop.category' ,$item) ) { ?>
                        <?php  echo tpl_form_field_image('advimg', $item['advimg'])?>
                        <span class="help-block">建议尺寸: 640*320</span>
                         <?php  } else { ?>
                           <?php  if(!empty($item['advimg'])) { ?>
                                 <a href='<?php  echo tomedia($item['advimg'])?>' target='_blank'>
                                <img src="<?php  echo tomedia($item['advimg'])?>" style='width:100px;border:1px solid #ccc;padding:1px' />
                                 </a>
                           <?php  } ?>
                        <?php  } ?>
                    </div>
                </div>
                 <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">分类广告链接</label>
                    <div class="col-sm-9 col-xs-12">
                         <?php if( ce('shop.category' ,$item) ) { ?>
                        <input type="text" name="advurl" class="form-control" value="<?php  echo $item['advurl'];?>" />
                        <?php  } else { ?>
                         <div class='form-control-static'><?php  echo $item['advurl'];?></div>
                         <?php  } ?>
                    </div>
                </div>
               <?php  } ?>
               <?php  if(!empty($parentid)) { ?>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否推荐</label>
                    <div class="col-sm-9 col-xs-12">
                            <?php if( ce('shop.category' ,$item) ) { ?>
                        <label class='radio-inline'>
                            <input type='radio' name='isrecommand' value=1' <?php  if($item['isrecommand']==1) { ?>checked<?php  } ?> /> 是
                        </label>
                        <label class='radio-inline'>
                            <input type='radio' name='isrecommand' value=0' <?php  if($item['isrecommand']==0) { ?>checked<?php  } ?> /> 否
                        </label>
                            <?php  } else { ?>
                           <div class='form-control-static'><?php  if(empty($item['isrecommand'])) { ?>否<?php  } else { ?>是<?php  } ?></div>
                           <?php  } ?>
                    </div> 
                </div>
               <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">首页推荐</label>
                    <div class="col-sm-9 col-xs-12">
                            <?php if( ce('shop.category' ,$item) ) { ?>
                        <label class='radio-inline'>
                            <input type='radio' name='ishome' value=1' <?php  if($item['ishome']==1) { ?>checked<?php  } ?> /> 是
                        </label>
                        <label class='radio-inline'>
                            <input type='radio' name='ishome' value=0' <?php  if($item['ishome']==0) { ?>checked<?php  } ?> /> 否
                        </label>
                           <?php  } else { ?>
                           <div class='form-control-static'><?php  if(empty($item['ishome'])) { ?>否<?php  } else { ?>是<?php  } ?></div>
                           <?php  } ?>
                    </div> 
                </div>
                <?php  } ?>  
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否显示</label>
                    <div class="col-sm-9 col-xs-12">
                        <?php if( ce('shop.category' ,$item) ) { ?>
                        <label class='radio-inline'>
                            <input type='radio' name='enabled' value=1' <?php  if($item['enabled']==1) { ?>checked<?php  } ?> /> 是
                        </label>
                        <label class='radio-inline'>
                            <input type='radio' name='enabled' value=0' <?php  if($item['enabled']==0) { ?>checked<?php  } ?> /> 否
                        </label>
                          <?php  } else { ?>
                           <div class='form-control-static'><?php  if(empty($item['enabled'])) { ?>否<?php  } else { ?>是<?php  } ?></div>
                           <?php  } ?>
                    </div>
                </div>
                
                 <div class="form-group"></div>
            <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                    <div class="col-sm-9 col-xs-12">
                           <?php if( ce('shop.category' ,$item) ) { ?>
                            <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1" onclick="return formcheck()" />
                            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                        <?php  } ?>
                       <input type="button" name="back" onclick='history.back()' <?php if(cv('shop.category.add|shop.category.edit')) { ?>style='margin-left:10px;'<?php  } ?> value="返回列表" class="btn btn-default col-lg-1" />
                    </div>
            </div>
                
            </div>
        </div>
      
    </form>
</div>
<script language='javascript'>
         require(['util'],function(u){
    $('#cp').each(function(){
	u.clip(this, $(this).text());
	});
    })
    $('form').submit(function(){
        if($(':input[name=catename]').isEmpty()){
            Tip.focus(':input[name=catename]','请输入分类名称!');
            return false;
        }
        return true;
    });
</script>
<?php  } else if($operation == 'display') { ?>
<script language="javascript" src="../addons/ewei_shop/static/js/dist/nestable/jquery.nestable.js"></script>
<link rel="stylesheet" type="text/css" href="../addons/ewei_shop/static/js/dist/nestable/nestable.css" />
<style type='text/css'>
    .dd-handle { height: 40px; line-height: 30px}
</style>
<div class="main">
    <div class="category">
        <form action="" method="post">
            <div class="panel panel-default">
                <div class="panel-body table-responsive">

                        <div class="dd" id="div_nestable">
                            <ol class="dd-list">

                               <?php  if(is_array($category)) { foreach($category as $row) { ?>
                                 <?php  if(empty($row['parentid'])) { ?>
                                <li class="dd-item" data-id="<?php  echo $row['id'];?>">

                                    <div class="dd-handle"  style='width:100%;'>
                                        [ID: <?php  echo $row['id'];?>] <?php  echo $row['name'];?> 
                                        <span class="pull-right">
                                            <?php if(cv('shop.category.add')) { ?><a class='btn btn-default btn-sm' href="<?php  echo $this->createWebUrl('shop/category', array('parentid' => $row['id'], 'op' => 'post'))?>" title='添加子分类' ><i class="fa fa-plus"></i></a><?php  } ?>
                                            <?php if(cv('shop.category.edit|shop.category.view')) { ?>
                                             <a class='btn btn-default btn-sm' href="<?php  echo $this->createWebUrl('shop/category', array('id' => $row['id'], 'op' => 'post'))?>" title="<?php if(cv('shop.category.edit')) { ?>修改<?php  } else { ?>查看<?php  } ?>" ><i class="fa fa-edit"></i></a>
                                            <?php  } ?>
                                            <?php if(cv('shop.category.delete')) { ?><a class='btn btn-default btn-sm' href="<?php  echo $this->createWebUrl('shop/category', array('id' => $row['id'], 'op' => 'delete'))?>" title='删除' onclick="return confirm('确认删除此分类吗？');return false;"><i class="fa fa-remove"></i></a><?php  } ?>
                                        </span>
                                    </div>
                                    <?php  if(count($children[$row['id']])>0) { ?>
                                    
                                    <ol class="dd-list"  style='width:100%;'>
                                        <?php  if(is_array($children[$row['id']])) { foreach($children[$row['id']] as $child) { ?>
                                        <li class="dd-item" data-id="<?php  echo $child['id'];?>">
                                            <div class="dd-handle">
                                                <img src="<?php  echo tomedia($child['thumb']);?>" width='30' height="30" onerror="$(this).remove()" style='padding:1px;border: 1px solid #ccc;float:left;' /> &nbsp;
                                                [ID: <?php  echo $child['id'];?>] <?php  echo $child['name'];?>
                                                <span class="pull-right">
                                                    <?php  if(intval($shopset['catlevel'])==3) { ?>
                                                     <?php if(cv('shop.category.add')) { ?><a class='btn btn-default btn-sm' href="<?php  echo $this->createWebUrl('shop/category', array('parentid' => $child['id'], 'op' => 'post'))?>" title='添加子分类' ><i class="fa fa-plus"></i></a><?php  } ?>
                                                     <?php  } ?>
                                                      <?php if(cv('shop.category.edit|shop.category.view')) { ?><a class='btn btn-default btn-sm' href="<?php  echo $this->createWebUrl('shop/category', array('id' => $child['id'], 'op' => 'post'))?>" title="<?php if(cv('shop.category.edit')) { ?>修改<?php  } else { ?>查看<?php  } ?>" ><i class="fa fa-edit"></i></a><?php  } ?>
                                                      <?php if(cv('shop.category.delete')) { ?> <a class='btn btn-default btn-sm' href="<?php  echo $this->createWebUrl('shop/category', array('id' => $child['id'], 'op' => 'delete'))?>" title='删除' onclick="return confirm('确认删除此分类吗？');return false;"><i class="fa fa-remove"></i></a><?php  } ?>
                                                </span>
                                            </div>
                                                  <?php  if(count($children[$child['id']])>0 && intval($shopset['catlevel'])==3) { ?>

                                                    <ol class="dd-list"  style='width:100%;'>
                                                        <?php  if(is_array($children[$child['id']])) { foreach($children[$child['id']] as $third) { ?>
                                                        <li class="dd-item" data-id="<?php  echo $third['id'];?>">
                                                            <div class="dd-handle">
                                                                <img src="<?php  echo tomedia($third['thumb']);?>" width='30' height="30" onerror="$(this).remove()" style='padding:1px;border: 1px solid #ccc;float:left;' /> &nbsp;
                                                                [ID: <?php  echo $third['id'];?>] <?php  echo $third['name'];?>
                                                                <span class="pull-right">
                                                                        <?php if(cv('shop.category.edit|shop.category.view')) { ?><a class='btn btn-default btn-sm' href="<?php  echo $this->createWebUrl('shop/category', array('id' => $third['id'], 'op' => 'post'))?>" title="<?php if(cv('shop.category.edit')) { ?>修改<?php  } else { ?>查看<?php  } ?>" ><i class="fa fa-edit"></i></a><?php  } ?>
                                                                      <?php if(cv('shop.category.delete')) { ?><a class='btn btn-default btn-sm' href="<?php  echo $this->createWebUrl('shop/category', array('id' => $third['id'], 'op' => 'delete'))?>" title='删除' onclick="return confirm('确认删除此分类吗？');return false;"><i class="fa fa-remove"></i></a><?php  } ?>
                                                                </span>
                                                            </div>
                                                        </li>
                                                        <?php  } } ?>
                                                    </ol>
                                                    <?php  } ?>
                                        </li>
                                        <?php  } } ?>
                                    </ol>
                                    <?php  } ?>
                                    
                                </li>
                                <?php  } ?>
                              <?php  } } ?>
                                
                            </ol>
                            <table class='table'>
                                <tr>
                                <td>
                                    <?php if(cv('shop.category.add')) { ?>
                                    <a href="<?php  echo $this->createWebUrl('shop/category',array('op' => 'post'))?>" class="btn btn-default"><i class="fa fa-plus"></i> 添加新分类</a>
                                    <?php  } ?>
                                    <?php if(cv('shop.category.edit')) { ?>
                                    <input id="save_category" type="button" class="btn btn-primary" value="保存分类修改">
                                    <?php  } ?>
                                    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                                    <input type="hidden" name="datas" value="" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
</div>
    <script language='javascript'>
     
      
      
    $(function(){
      var depth = <?php  echo intval($shopset['catlevel'])?>;
      if(depth<=0) {
          depth =2;
      }
      $('#div_nestable').nestable({maxDepth: depth });
         
        $(".dd-handle a,dd-handle embed,dd-handle div").mousedown(function (e) {
            e.stopPropagation();
        }); 
        var $expand = false;
        $('#nestableMenu').on('click', function(e)
        {
            if ($expand) {
                $expand = false;
                $('.dd').nestable('expandAll');
            }else {
                $expand = true;
                $('.dd').nestable('collapseAll');
            }
        });
        
        $("#save_category").click(function(){
             var json = window.JSON.stringify($('#div_nestable').nestable("serialize"));
             $(':input[name=datas]').val(json);
             $('form').submit();
        })
        
    })
    </script>
 
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>

