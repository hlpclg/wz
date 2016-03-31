<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_header', TEMPLATE_INCLUDEPATH)) : (include template('web/_header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/member/tabs', TEMPLATE_INCLUDEPATH)) : (include template('web/member/tabs', TEMPLATE_INCLUDEPATH));?>

<?php  if($operation=='display') { ?>
<div class="panel panel-info">
    <div class="panel-heading">筛选</div>
    <div class="panel-body">
        <form action="./index.php" method="get" class="form-horizontal" role="form" id="form1">
            <input type="hidden" name="c" value="site" />
            <input type="hidden" name="a" value="entry" />
            <input type="hidden" name="m" value="ewei_shop" />
            <input type="hidden" name="do" value="member" />
                 <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">ID</label>
                <div class="col-sm-8 col-lg-9 col-xs-12">
                    <input type="text" class="form-control"  name="mid" value="<?php  echo $_GPC['mid'];?>"/> 
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">会员信息</label>
                <div class="col-sm-8 col-lg-9 col-xs-12">
                    <input type="text" class="form-control"  name="realname" value="<?php  echo $_GPC['realname'];?>" placeholder="可搜索昵称/姓名/手机号"/> 
                </div>
            </div>
               <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">是否关注</label>
                <div class="col-sm-8 col-lg-9 col-xs-12">
                       <select name='followed' class='form-control'>
                        <option value=''></option>
                        <option value='0' <?php  if($_GPC['followed']=='0') { ?>selected<?php  } ?>>未关注</option>
                        <option value='1' <?php  if($_GPC['followed']=='1') { ?>selected<?php  } ?>>已关注</option>
                        <option value='2' <?php  if($_GPC['followed']=='2') { ?>selected<?php  } ?>>取消关注</option>
                    </select>
                </div>
            </div>
             <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">会员等级</label>
                <div class="col-sm-8 col-lg-9 col-xs-12">
                       <select name='level' class='form-control'>
                        <option value=''></option>
                        <?php  if(is_array($levels)) { foreach($levels as $level) { ?>
                        <option value='<?php  echo $level['id'];?>' <?php  if($_GPC['level']==$level['id']) { ?>selected<?php  } ?>><?php  echo $level['levelname'];?></option>
                        <?php  } } ?>
                    </select>
                </div>
            </div>
             <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">会员分组</label>
                <div class="col-sm-8 col-lg-9 col-xs-12">
                       <select name='groupid' class='form-control'>
                        <option value=''></option>
                        <?php  if(is_array($groups)) { foreach($groups as $group) { ?>
                        <option value='<?php  echo $group['id'];?>' <?php  if($_GPC['groupid']==$group['id']) { ?>selected<?php  } ?>><?php  echo $group['groupname'];?></option>
                        <?php  } } ?>
                    </select>
                </div>
        
            </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">注册时间</label>
                      <div class="col-sm-2">
                            <label class='radio-inline'>
                                <input type='radio' value='0' name='searchtime' <?php  if($_GPC['searchtime']=='0') { ?>checked<?php  } ?>>不搜索
                            </label>
                             <label class='radio-inline'>
                                <input type='radio' value='1' name='searchtime' <?php  if($_GPC['searchtime']=='1') { ?>checked<?php  } ?>>搜索
                            </label>
                     </div>
                    <div class="col-sm-7 col-lg-9 col-xs-12">
                        <?php  echo tpl_form_field_daterange('time', array('starttime'=>date('Y-m-d H:i', $starttime),'endtime'=>date('Y-m-d  H:i', $endtime)),true);?>
                    </div>
                         
                </div> 
              <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label"></label>
                    <div class="col-sm-7 col-lg-9 col-xs-12">
                       <button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
                       <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                       <?php if(cv('member.member.export')) { ?>   
                        <button type="submit" name="export" value="1" class="btn btn-primary">导出 Excel</button>
                        <?php  } ?>
                       
                    </div>
               </div> 
          
            
            <div class="form-group">
            </div>
        </form>
    </div>
</div><div class="clearfix">

<div class="panel panel-default">
    <div class="panel-heading">总数：<?php  echo $total;?>   </div>
    <div class="panel-body">
        <table class="table table-hover" style="overflow:visible;">
            <thead class="navbar-inner">
                <tr>
                    <th style='width:80px;'>会员ID</th>
		  <?php  if($opencommission) { ?>
			<th style='width:120px;'>推荐人</th>	
		  <?php  } ?>

                    <th style='width:120px;'>粉丝</th>
                    <th style='width:80px;'>会员姓名</th>
                    <th style='width:120px;'>手机号码</th>
                    <th style='width:120px;'>会员等级/分组</th>
                    <th style='width:130px;'>注册时间</th>
                    <th style='width:80px;'>积分</th>
                    <th style='width:80px;'>余额</th>
                    <th style='width:80px;'>成交订单</th>
                    <th style='width:80px;'>成交金额</th> 
                    <th style='width:100px'>关注</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php  if(is_array($list)) { foreach($list as $row) { ?>
                <tr>
                    <td>   <?php  echo $row['id'];?></td>
		  <?php  if($opencommission) { ?>
		    <td  <?php  if(!empty($row['agentid'])) { ?>title='ID: <?php  echo $row['agentid'];?>'<?php  } ?>>
				<?php  if(empty($row['agentid'])) { ?>
				  <?php  if($row['isagent']==1) { ?>
				      <label class='label label-primary'>总店</label>
				      <?php  } else { ?>
				       <label class='label label-default'>暂无</label>
				      <?php  } ?>
				<?php  } else { ?>
				
                    	<?php  if(!empty($row['agentavatar'])) { ?>
                         <img src='<?php  echo $row['agentavatar'];?>' style='width:30px;height:30px;padding1px;border:1px solid #ccc' />
                       <?php  } ?>
                       <?php  if(empty($row['agentnickname'])) { ?>未更新<?php  } else { ?><?php  echo $row['agentnickname'];?><?php  } ?>
					   <?php  } ?>
                        
                    </td>
		  <?php  } ?>
		  
                    <td>
                    	<?php  if(!empty($row['avatar'])) { ?>
                         <img src='<?php  echo $row['avatar'];?>' style='width:30px;height:30px;padding1px;border:1px solid #ccc' />
                       <?php  } ?>
                       <?php  if(empty($row['nickname'])) { ?>未更新<?php  } else { ?><?php  echo $row['nickname'];?><?php  } ?>
                        
                    </td>
                    <td><?php  echo $row['realname'];?></td>
                    <td><?php  echo $row['mobile'];?></td>
                    <td><?php  if(empty($row['levelname'])) { ?>普通会员<?php  } else { ?><?php  echo $row['levelname'];?><?php  } ?>
                        <br/><?php  if(empty($row['groupname'])) { ?>无分组<?php  } else { ?><?php  echo $row['groupname'];?><?php  } ?></td>
      
                    <td><?php  echo date('Y-m-d H:i',$row['createtime'])?></td>
                    <td><?php  echo $row['credit1'];?></td>
                    <td><?php  echo $row['credit2'];?></td>
                    <td><?php  echo $row['ordercount'];?></td>
                    <td><?php  echo floatval($row['ordermoney'])?></td>
                    <td>  <?php  if(empty($row['followed'])) { ?>
                        <?php  if(empty($row['uid'])) { ?>
                        <label class='label label-default'>未关注</label>
                        <?php  } else { ?>
                        <label class='label label-warning'>取消关注</label>
                        <?php  } ?>
                        <?php  } else { ?>
                    <label class='label label-success'>已关注</label>    
                    <?php  } ?></td>
             
                            <td  style="overflow:visible;">
                        
                        <div class="btn-group btn-group-sm" >
                                <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="javascript:;">操作 <span class="caret"></span></a>
                                <ul class="dropdown-menu dropdown-menu-left" role="menu" style='z-index: 9999'>
                               
                        <?php if(cv('member.member.view|member.member.edit')) { ?><li><a href="<?php  echo $this->createWebUrl('member',array('op'=>'detail','id' => $row['id']));?>" title="会员详情"><i class='fa fa-edit'></i> 会员详情</a></li><?php  } ?>
                        <?php if(cv('order')) { ?><li><a  href="<?php  echo $this->createWebUrl('order', array('op' => 'display','member'=>$row['nickname']))?>" title='会员订单'><i class='fa fa-list'></i> 会员订单</a></li><?php  } ?>
                        <?php if(cv('finance.recharge.credit1')) { ?><li><a href="<?php  echo $this->createWebUrl('finance/recharge', array('op'=>'credit1','id'=>$row['id']))?>" title='充值积分'><i class='fa fa-credit-card'></i> 充值积分</a></li><?php  } ?>
                        <?php if(cv('finance.recharge.credit2')) { ?><li><a href="<?php  echo $this->createWebUrl('finance/recharge', array('op'=>'credit2','id'=>$row['id']))?>" title='充值余额'><i class='fa fa-money'></i> 充值余额 </a></li><?php  } ?>
                        <?php if(cv('member.member.delete')) { ?><li><a  href="<?php  echo $this->createWebUrl('member',array('op'=>'delete','id' => $row['id']));?>" title='删除会员' onclick="return confirm('确定要删除该会员吗？');"><i class='fa fa-remove'></i> 删除会员</a></li><?php  } ?>
                                </ul>
                            </div>

               
                    </td>
                   
                    </td>
                </tr>
                <?php  } } ?>
            </tbody>
        </table>
           <?php  echo $pager;?>
    </div>
</div>
</div>
<?php  } else if($operation=='detail') { ?>

<form <?php  if('member.member.edit') { ?>action="" method='post'<?php  } ?> class='form-horizontal'>
    <input type="hidden" name="id" value="<?php  echo $member['id'];?>">
    <input type="hidden" name="op" value="detail">
    <input type="hidden" name="c" value="site" />
    <input type="hidden" name="a" value="entry" />
    <input type="hidden" name="m" value="ewei_shop" />
    <input type="hidden" name="do" value="member" />
    <div class='panel panel-default'>
        <div class='panel-heading'>
            会员详细信息
        </div>
        <div class='panel-body'>
             <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">粉丝</label>
                <div class="col-sm-9 col-xs-12">
                    <img src='<?php  echo $member['avatar'];?>' style='width:100px;height:100px;padding:1px;border:1px solid #ccc' />
                         <?php  echo $member['nickname'];?>
                </div>
            </div>
               <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">OPENID</label>
                <div class="col-sm-9 col-xs-12">
                    <div class="form-control-static"><?php  echo $member['openid'];?></div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">会员等级</label>
                <div class="col-sm-9 col-xs-12">
                    <?php if(cv('member.member.edit')) { ?>
                      <select name='data[level]' class='form-control'>
                        <option value=''>普通会员</option>
                        <?php  if(is_array($levels)) { foreach($levels as $level) { ?>
                        <option value='<?php  echo $level['id'];?>' <?php  if($member['level']==$level['id']) { ?>selected<?php  } ?>><?php  echo $level['levelname'];?></option>
                        <?php  } } ?>
                    </select>
                    <?php  } else { ?>
                    <div class='form-control-static'>
                        <?php  if(empty($member['level'])) { ?>
                        普通会员
                        <?php  } else { ?>
                        <?php  echo pdo_fetchcolumn('select levelname from '.tablename('ewei_shop_member_level').' where id=:id limit 1',array(':id'=>$member['level']))?>
                        <?php  } ?>
                    </div>
                    <?php  } ?>
                </div>
            </div>
              <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">会员分组</label>
                <div class="col-sm-9 col-xs-12">
                       <?php if(cv('member.member.edit')) { ?>
                      <select name='data[groupid]' class='form-control'>
                        <option value=''>无分组</option>
                        <?php  if(is_array($groups)) { foreach($groups as $group) { ?>
                        <option value='<?php  echo $group['id'];?>' <?php  if($member['groupid']==$group['id']) { ?>selected<?php  } ?>><?php  echo $group['groupname'];?></option>
                        <?php  } } ?>
                    </select>
                          <?php  } else { ?>
                    <div class='form-control-static'>
                        <?php  if(empty($member['groupid'])) { ?>
                        无分组
                        <?php  } else { ?>
                        <?php  echo pdo_fetchcolumn('select groupname from '.tablename('ewei_shop_member_group').' where id=:id limit 1',array(':id'=>$member['groupid']))?>
                        <?php  } ?>
                    </div>
                    <?php  } ?>
                </div>
            </div>
             
        
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">真实姓名</label>
                <div class="col-sm-9 col-xs-12">
                      <?php if(cv('member.member.edit')) { ?>
                    <input type="text" name="data[realname]" class="form-control" value="<?php  echo $member['realname'];?>"  />
                    <?php  } else { ?>
                    <div class='form-control-static'><?php  echo $member['realname'];?></div>
                    <?php  } ?>
                </div>
            </div>

            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">手机号码</label>
                <div class="col-sm-9 col-xs-12">
                        <?php if(cv('member.member.edit')) { ?>
                    <input type="text" name="data[mobile]" class="form-control" value="<?php  echo $member['mobile'];?>"  />
                      <?php  } else { ?>
                    <div class='form-control-static'><?php  echo $member['mobile'];?></div>
                    <?php  } ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">微信号</label>
                <div class="col-sm-9 col-xs-12">
                    <?php if(cv('member.member.edit')) { ?>
                          <input type="text" name="data[weixin]" class="form-control" value="<?php  echo $member['weixin'];?>"  />
                      <?php  } else { ?>
                         <div class='form-control-static'><?php  echo $member['weixin'];?></div>
                    <?php  } ?>
                </div>
            </div>
           <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">积分</label>
                <div class="col-sm-3">
                      <?php if(cv('finance.recharge.credit1')) { ?>
                     <div class='input-group'>
                        <div class=' input-group-addon'  style='width:200px;text-align: left;'><?php  echo $member['credit1'];?></div>
                      <div class='input-group-btn'>
                         <a class='btn btn-primary' href="<?php  echo $this->createWebUrl('finance/recharge', array('op'=>'credit1','id'=>$member['id']))?>">充值</a>
                          </div>
                      </div>
                      <?php  } else { ?>
                       <div class='form-control-static'><?php  echo $member['credit1'];?></div>
                      <?php  } ?>
          
                </div>
            </div>
              <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">余额</label>
                <div class="col-sm-3">  
                    <?php if(cv('finance.recharge.credit2')) { ?>
                    <div class='input-group'>
                        <div class=' input-group-addon' style='width:200px;text-align: left;'><?php  echo $member['credit2'];?></div>
                       
                        <div class='input-group-btn'><a class='btn btn-primary' href="<?php  echo $this->createWebUrl('finance/recharge', array('op'=>'credit2','id'=>$member['id']))?>">充值</a>
                            </div>
                   
                    </div>
                    <?php  } else { ?>
                      <div class='form-control-static'><?php  echo $member['credit2'];?></div>
                      <?php  } ?>
                </div>
            </div>
             <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">成交订单数</label>
                <div class="col-sm-9 col-xs-12">
                    <div class='form-control-static'><?php  echo $member['self_ordercount'];?></div>
                </div>
            </div>
               <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">成交金额</label>
                <div class="col-sm-9 col-xs-12">
                    <div class='form-control-static'><?php  echo $member['self_ordermoney'];?> 元</div>
                </div>
            </div>
               <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">注册时间</label>
                <div class="col-sm-9 col-xs-12">
                    <div class='form-control-static'><?php  echo date('Y-m-d H:i:s', $member['createtime']);?></div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">关注状态</label>
                <div class="col-sm-9 col-xs-12">
                    <div class='form-control-static'>
                        <?php  $followed = m('user')->followed($member['openid'])?>
                         <?php  if(!$followed) { ?>
                            <?php  if(empty($member['uid'])) { ?>
                            <label class='label label-default'>未关注</label>
                            <?php  } else { ?>
                            <label class='label label-warning'>取消关注</label>
                            <?php  } ?>
                            <?php  } else { ?>
                        <label class='label label-success'>已关注</label>    
                        <?php  } ?>
                        
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">备注</label>
                <div class="col-sm-9 col-xs-12">
                      <?php if(cv('member.member.edit')) { ?>
                    <textarea name="data[content]" class='form-control'><?php  echo $member['content'];?></textarea>
                      <?php  } else { ?>
                         <div class='form-control-static'><?php  echo $member['content'];?></div>
                    <?php  } ?>
                </div>
            </div>
        </div>

        <?php  if($hascommission && cv('commission.agent.changeagent')) { ?>
        <div class='panel-heading'>
            设置分销商
        </div>
           <div class='panel-body'>
<div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">上级分销商</label>
                    <div class="col-sm-4">
                       <input type="hidden" value="<?php  echo $member['agentid'];?>" id='agentid' name='adata[agentid]' class="form-control"  />
                        
                      <?php if(cv('commission.agent.edit')) { ?>
                        <div class='input-group'>
                            <input type="text" name="parentagent" maxlength="30" value="<?php  if(!empty($parentagent)) { ?><?php  echo $parentagent['nickname'];?>/<?php  echo $parentagent['realname'];?>/<?php  echo $parentagent['mobile'];?><?php  } ?>" id="parentagent" class="form-control" readonly />
                            <div class='input-group-btn'>
                                <button class="btn btn-default" type="button" onclick="popwin = $('#modal-module-menus-notice').modal();">选择上级分销商</button>
                                <button class="btn btn-danger" type="button" onclick="$('#agentid').val('');$('#parentagent').val('');$('#parentagentavatar').hide()">清除选择</button>
                            </div> 
                        </div>
                        <span id="parentagentavatar" class='help-block' <?php  if(empty($parentagent)) { ?>style="display:none"<?php  } ?>><img  style="width:100px;height:100px;border:1px solid #ccc;padding:1px" src="<?php  echo $parentagent['avatar'];?>"/></span>
                         
                        <div id="modal-module-menus-notice"  class="modal fade" tabindex="-1">
                            <div class="modal-dialog" style='width: 920px;'>
                                <div class="modal-content">
                                    <div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>选择上级分销商</h3></div>
                                    <div class="modal-body" >
                                        <div class="row">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="keyword" value="" id="search-kwd-notice" placeholder="请输入分销商昵称/姓名/手机号" />
                                                <span class='input-group-btn'><button type="button" class="btn btn-default" onclick="search_members();">搜索</button></span>
                                            </div>
                                        </div>
                                        <div id="module-menus-notice" style="padding-top:5px;"></div>
                                    </div>
                                    <div class="modal-footer"><a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a></div>
                                </div>

                            </div>
                        </div>
                        <span class="help-block">修改后， 只有关系链改变, 以往的订单佣金都不会改变,新的订单才按新关系计算佣金 ,请谨慎选择</span>
                        <?php  } else { ?>
                        <div class='form-control-static'>
                            <?php  if(!empty($parentagent)) { ?><img  style="width:100px;height:100px;border:1px solid #ccc;padding:1px" src="<?php  echo $parentagent['avatar'];?>"/><?php  } else { ?>无<?php  } ?>
                         </div>
                        <?php  } ?>
                        
                    </div>
                </div>
            
			     <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否固定上级</label>
                <div class="col-sm-9 col-xs-12">
                     <?php if(cv('commission.agent.check')) { ?>
                    <label class="radio-inline"><input type="radio" name="adata[fixagentid]" value="1" <?php  if($member['fixagentid']==1) { ?>checked<?php  } ?>>是</label>
                    <label class="radio-inline" ><input type="radio" name="adata[fixagentid]" value="0" <?php  if($member['fixagentid']==0) { ?>checked<?php  } ?>>否</label>
                    <span class="help-block">固定上级后，任何条件也无法改变其上级，如果不选择上级分销商，且固定上级，则上级永远为总店（是分销商）或无上线（非分销商）</span>
                    <?php  } else { ?>
                      <input type='hidden' name='adata[fixagentid]' value='<?php  echo $member['fixagentid'];?>' />
                      <div class='form-control-static'><?php  if($member['fixagentid']==1) { ?>是<?php  } else { ?>否<?php  } ?></div>
                    <?php  } ?>
                    
                </div>
            </div>
			   
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">分销商等级</label>
               <div class="col-sm-9 col-xs-12">
                         <?php if(cv('commission.agent.edit')) { ?>
                    <select name='adata[agentlevel]' class='form-control'>
                        <option value='0'><?php echo empty($plugin_com_set['levelname'])?'普通等级':$plugin_com_set['levelname']?></option>
                         <?php  if(is_array($agentlevels)) { foreach($agentlevels as $level) { ?>
                        <option value='<?php  echo $level['id'];?>' <?php  if($member['agentlevel']==$level['id']) { ?>selected<?php  } ?>><?php  echo $level['levelname'];?></option>
                        <?php  } } ?>
                    </select>
                         <?php  } else { ?>
                             <input type="hidden" name="adata[agentlevel]" class="form-control" value="<?php  echo $member['agentlevel'];?>"  />
                             
                              <?php  if(empty($member['agentlevel'])) { ?>
                            <?php echo empty($plugin_com_set['levelname'])?'普通等级':$plugin_com_set['levelname']?>
                                <?php  } else { ?>
                                <?php  echo pdo_fetchcolumn('select levelname from '.tablename('ewei_shop_commission_level').' where id=:id limit 1',array(':id'=>$member['agentlevel']))?>
                                <?php  } ?>
                         <?php  } ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">累计佣金</label>
                <div class="col-sm-9 col-xs-12">
                    <div class='form-control-static'> <?php  echo $member['commission_total'];?></div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">已打款佣金</label>
                <div class="col-sm-9 col-xs-12">
                    <div class='form-control-static'> <?php  echo $member['commission_pay'];?></div>
                </div>
            </div>
			   <?php  if($member['agenttime']!='1970-01-01 08:00') { ?>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">成为分销商时间</label>
                <div class="col-sm-9 col-xs-12">
                    <div class='form-control-static'><?php  echo $member['agenttime'];?></div> 
                </div>
            </div>
			   <?php  } ?>
           <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">分销商权限</label>
                <div class="col-sm-9 col-xs-12">
                     <?php if(cv('commission.agent.check')) { ?>
                    <label class="radio-inline"><input type="radio" name="adata[isagent]" value="1" <?php  if($member['isagent']==1) { ?>checked<?php  } ?>>是</label>
                    <label class="radio-inline" ><input type="radio" name="adata[isagent]" value="0" <?php  if($member['isagent']==0) { ?>checked<?php  } ?>>否</label>
                    <?php  } else { ?>
                      <input type='hidden' name='adata[isagent]' value='<?php  echo $member['isagent'];?>' />
                      <div class='form-control-static'><?php  if($member['isagent']==1) { ?>是<?php  } else { ?>否<?php  } ?></div>
                    <?php  } ?>
                    
                </div>
            </div>
       
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">审核通过</label>
                <div class="col-sm-9 col-xs-12">
                     <?php if(cv('commission.agent.check')) { ?>
                    <label class="radio-inline"><input type="radio" name="adata[status]" value="1" <?php  if($member['status']==1) { ?>checked<?php  } ?>>是</label>
                    <label class="radio-inline" ><input type="radio" name="adata[status]" value="0" <?php  if($member['status']==0) { ?>checked<?php  } ?>>否</label>
                    <input type='hidden' name='oldstatus' value="<?php  echo $member['status'];?>" />
                       <?php  } else { ?>
                      <input type='hidden' name='adata[status]' value='<?php  echo $member['status'];?>' />
                      <div class='form-control-static'><?php  if($member['status']==1) { ?>是<?php  } else { ?>否<?php  } ?></div>
                    <?php  } ?>
                </div>
            </div>

             <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">强制不自动升级</label>
                <div class="col-sm-9 col-xs-12">
                      <?php if(cv('commission.agent.edit')) { ?>
                    <label class="radio-inline" ><input type="radio" name="adata[agentnotupgrade]" value="0" <?php  if($member['agentnotupgrade']==0) { ?>checked<?php  } ?>>允许自动升级</label>
                    <label class="radio-inline"><input type="radio" name="adata[agentnotupgrade]" value="1" <?php  if($member['agentnotupgrade']==1) { ?>checked<?php  } ?>>强制不自动升级</label>
                    <span class="help-block">如果强制不自动升级，满足任何条件，此分销商的级别也不会改变</span>
                    <?php  } else { ?>
                         <input type="hidden" name="adata[agentnotupgrade]" class="form-control" value="<?php  echo $member['agentnotupgrade'];?>"  />
                      <div class='form-control-static'><?php  if($member['agentnotupgrade']==1) { ?>强制不自动升级<?php  } else { ?>允许自动升级<?php  } ?></div>
                    <?php  } ?>
                </div>
            </div>
        
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">自选商品</label>
                <div class="col-sm-9 col-xs-12">
                      <?php if(cv('commission.agent.edit')) { ?>
                    <label class="radio-inline" ><input type="radio" name="adata[agentselectgoods]" value="0" <?php  if($member['agentselectgoods']==0) { ?>checked<?php  } ?>>系统设置</label>
                    <label class="radio-inline"><input type="radio" name="adata[agentselectgoods]" value="1" <?php  if($member['agentselectgoods']==1) { ?>checked<?php  } ?>>强制禁止</label>
                    <label class="radio-inline"><input type="radio" name="adata[agentselectgoods]" value="2" <?php  if($member['agentselectgoods']==2) { ?>checked<?php  } ?>>强制开启</label>
                    <span class="help-block">系统设置： 跟随系统设置，系统关闭自选则为禁止，系统开启自选则为允许</span>
                    <span class="help-block">强制禁止： 无论系统自选商品是否关闭或开启，此分销商永不能自选商品</span>
                    <span class="help-block">强制允许： 无论系统自选商品是否关闭或开启，此分销商永可以自选商品</span>
                    <?php  } else { ?>
                      <input type="hidden" name="adata[agentselectgoods]" class="form-control" value="<?php  echo $member['agentselectgoods'];?>"  />
                      <div class='form-control-static'><?php  if($member['agentnotselectgoods']==1) { ?>
                          强制禁止 
                          <?php  } else if($member['agentselectgoods']==2) { ?>
                          强制允许
                          <?php  } else { ?>
                          <?php  if($plugin_com_set['select_goods']==1) { ?>系统允许<?php  } else { ?>系统禁止<?php  } ?>
                          <?php  } ?></div>
                    <?php  } ?>
                </div>
            </div>
        </div>
        <?php  } ?>
        <div class='panel-body'>
          <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                <div class="col-sm-9 col-xs-12">
                    <?php if(cv('member.member.edit')) { ?>
                  <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1" />
	<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                  <?php  } ?>
                <input type="button" class="btn btn-default" name="submit" onclick="history.go(-1)" value="返回列表" <?php if(cv('member.member.edit')) { ?>style='margin-left:10px;'<?php  } ?> />
                </div>
            </div>
         </div>
        
    </div>   
</form>
<?php  } ?>
<script language='javascript'>
    
         function search_members() {
             if( $.trim($('#search-kwd-notice').val())==''){
                 Tip.focus('#search-kwd-notice','请输入关键词');
                 return;
             }
		$("#module-menus-notice").html("正在搜索....")
		$.get('<?php  echo $this->createPluginWebUrl('commission/agent')?>', {
			keyword: $.trim($('#search-kwd-notice').val()),'op':'query',selfid:"<?php  echo $id;?>"
		}, function(dat){
			$('#module-menus-notice').html(dat);
		});
	}
	function select_member(o) {
		$("#agentid").val(o.id);
                  $("#parentagentavatar").show();
                  $("#parentagentavatar").find('img').attr('src',o.avatar);
		$("#parentagent").val( o.nickname+ "/" + o.realname + "/" + o.mobile );
		$("#modal-module-menus-notice .close").click();
	}
        
    </script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>