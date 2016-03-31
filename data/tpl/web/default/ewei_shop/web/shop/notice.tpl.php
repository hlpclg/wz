<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_header', TEMPLATE_INCLUDEPATH)) : (include template('web/_header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/shop/tabs', TEMPLATE_INCLUDEPATH)) : (include template('web/shop/tabs', TEMPLATE_INCLUDEPATH));?>
<?php  if($operation == 'display') { ?>
     <form action="" method="post">
<div class="panel panel-default">
    <div class="panel-body table-responsive">
        <table class="table table-hover">
            <thead class="navbar-inner">
                <tr>
                    <th style="width:30px;">ID</th>
                    <th style='width:80px'>显示顺序</th>					
                    <th>标题</th>
                    <th>连接</th>
                    <th>状态</th>
                    <th >操作</th>
                </tr>
            </thead>
            <tbody>
                <?php  if(is_array($list)) { foreach($list as $row) { ?>
                <tr>
                    <td><?php  echo $row['id'];?></td>
                    <td>    <?php if(cv('shop.notice.edit')) { ?>
                           <input type="text" class="form-control" name="displayorder[<?php  echo $row['id'];?>]" value="<?php  echo $row['displayorder'];?>">
                        <?php  } else { ?>
                           <?php  echo $row['displayorder'];?>
                        <?php  } ?></td>
                    <td><?php  echo $row['title'];?></td>
                    <td><?php  echo $row['link'];?></td>
                       <td>
                                    <?php  if($row['status']==1) { ?>
                                    <span class='label label-success'>显示</span>
                                    <?php  } else { ?>
                                    <span class='label label-danger'>隐藏</span>
                                    <?php  } ?>
                                </td>
                    <td style="text-align:left;">
                        <?php if(cv('shop.notice.view|shop.notice.edit')) { ?><a href="<?php  echo $this->createWebUrl('shop/notice', array('op' => 'post', 'id' => $row['id']))?>" class="btn btn-default btn-sm"  title="<?php if(cv('shop.notice.edit')) { ?>修改<?php  } else { ?>查看<?php  } ?>"><i class="fa fa-edit"></i></a><?php  } ?>
                        <?php if(cv('shop.notice.delete')) { ?><a href="<?php  echo $this->createWebUrl('shop/notice', array('op' => 'delete', 'id' => $row['id']))?>"class="btn btn-default btn-sm" 
                                                     onclick="return confirm('确认删除此公告?')"
                                                     title="删除"><i class="fa fa-times"></i></a><?php  } ?>
                    </td>
                </tr>
                <?php  } } ?>
                <tr>
                    <td colspan='6'>
                            <?php if(cv('shop.notice.add')) { ?>
                          <a class='btn btn-default' href="<?php  echo $this->createWebUrl('shop/notice',array('op'=>'post'))?>"><i class='fa fa-plus'></i> 添加公告</a>
                          <?php  } ?>
                          
                              <?php if(cv('shop.notice.edit')) { ?>
                          <input name="submit" type="submit" class="btn btn-primary" value="提交排序">
                           <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                           <?php  } ?>
                         
                    </td>
                </tr>
            </tbody>
        </table>
        <?php  echo $pager;?>
    </div>
</div>
</form>
<script>
    require(['bootstrap'], function ($) {
        $('.btn').hover(function () {
            $(this).tooltip('show');
        }, function () {
            $(this).tooltip('hide');
        });
    });
</script>

<?php  } else if($operation == 'post') { ?>

<div class="main">
    <form     <?php if( ce('shop.notice' ,$notice) ) { ?>action="" method="post"<?php  } ?> class="form-horizontal form" enctype="multipart/form-data" onsubmit='return formcheck()'>
        <input type="hidden" name="id" value="<?php  echo $notice['id'];?>" />
        <div class="panel panel-default">
            <div class="panel-heading">
                店铺公告设置
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">排序</label>
                    <div class="col-sm-9 col-xs-12">
                         <?php if( ce('shop.notice' ,$notice) ) { ?>
                        <input type="text" name="displayorder" class="form-control" value="<?php  echo $notice['displayorder'];?>" />
                        <?php  } else { ?>
                        <div class='form-control-static'><?php  echo $notice['displayorder'];?></div>
                        <?php  } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>公告标题</label>
                    <div class="col-sm-9 col-xs-12">
                            <?php if( ce('shop.notice' ,$notice) ) { ?>
                        <input type="text" id='title' name="title" class="form-control" value="<?php  echo $notice['title'];?>" />
                           <?php  } else { ?>
                        <div class='form-control-static'><?php  echo $notice['title'];?></div>
                        <?php  } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">公告图片</label>
                    <div class="col-sm-9 col-xs-12">
                           <?php if( ce('shop.notice' ,$notice) ) { ?>
                        <?php  echo tpl_form_field_image('thumb', $notice['thumb'])?>
                        <span class="help-block">正方型图片</span>
                          <?php  } else { ?>
                            <?php  if(!empty($notice['thumb'])) { ?>
                            <a href='<?php  echo tomedia($notice['thumb'])?>' target='_blank'>
                            <img src="<?php  echo tomedia($notice['thumb'])?>" style='width:100px;border:1px solid #ccc;padding:1px' />
                                <?php  } ?>
 
                        <?php  } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">公告连接</label>
                    <div class="col-sm-9 col-xs-12">
                        <?php if( ce('shop.notice' ,$notice) ) { ?>
                        <input type="text" name="link" class="form-control" value="<?php  echo $notice['link'];?>" />
                        <span class="help-block">如果输入链接，则不显示内容，直接跳转</span>
                            <?php  } else { ?>
                        <div class='form-control-static'><?php  echo $notice['link'];?></div>
                        <?php  } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">公告内容</label>
                    <div class="col-sm-9 col-xs-12">    
                        <?php if( ce('shop.notice' ,$notice) ) { ?>
                            <?php  echo tpl_ueditor('detail',$notice['detail'])?>
                            <?php  } else { ?>
                            <textarea id='detail' style='display:none'><?php  echo $notice['detail'];?></textarea>
                            <a href='javascript:preview_html("#detail")' class="btn btn-default">查看内容</a>
                            <?php  } ?>
                            
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否显示</label>
                    <div class="col-sm-9 col-xs-12">
                            <?php if( ce('shop.notice' ,$notice) ) { ?>
                        <label class='radio-inline'>
                            <input type='radio' name='status' value=1' <?php  if($notice['status']==1) { ?>checked<?php  } ?> /> 是
                        </label>
                        <label class='radio-inline'>
                            <input type='radio' name='status' value=0' <?php  if($notice['status']==0) { ?>checked<?php  } ?> /> 否
                        </label>
                            <?php  } else { ?>
                             <div class='form-control-static'><?php  if(empty($notice['status'])) { ?>否<?php  } else { ?>是<?php  } ?></div>
                            <?php  } ?>
                    </div>
                </div>
                 <div class="form-group"></div>
            <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                    <div class="col-sm-9 col-xs-12">
                        <?php if( ce('shop.notice' ,$notice) ) { ?>
                            <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1"  />
                            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                        <?php  } ?>
                       <input type="button" name="back" onclick='history.back()' <?php if(cv('shop.notice.add|shop.notice.edit')) { ?>style='margin-left:10px;'<?php  } ?> value="返回列表" class="btn btn-default" />
                    </div>
            </div>
                
                
            </div>
        </div>
      
    </form>
</div>

<script language='javascript'>
    function formcheck() {
        if ($("#title").isEmpty()) {
            Tip.focus("title", "请填写公告标题!");
            return false;
        }
        return true;
    }
</script>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>