<?php defined('IN_IA') or exit('Access Denied');?><div class="clearfix user-browser">
	<div class="form-horizontal">
		<div class="form-group">
			<label class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label">用户名</label>
			<div class="col-xs-10 col-md-10 col-lg-10">
				<div class="input-group">
					<input id="keyword" type="text" class="form-control" value="<?php  echo $_GPC['keyword'];?>" />
					<div class="input-group-btn">
						<button class="btn btn-default pull-right" onclick="<?php  echo $callback;?>.pIndex=1;<?php  echo $callback;?>.query();"><i class="fa fa-search"></i> 搜索</button>
					</div>
				</div>
			</div>
		</div>
		<table class="table tb" style="min-width:568px;">
			<thead>
				<tr>
					<th style="width: 100px;">用户名</th>
					<th style="width: 100px;">用户组</th>
					<th style="width:100px;">说明</th>
					<th style="width: 50px;" class="text-center"></th>
				</tr>
			</thead>
			<tbody>
				<?php  if(is_array($list)) { foreach($list as $row) { ?>
				<tr>
					<td><label for="chk_user_<?php  echo $row['uid'];?>" style="font-weight:normal;"><?php  echo $row['username'];?></label></td>
					<td><label class="label label-info"><?php  echo $usergroups[$row['groupid']]['name'];?></label></td>
					<td title="<?php  echo $row['remark'];?>"><?php  echo cutstr($row['remark'], 13, '.')?></td>
					<td><a class="btn btn-default <?php  if(in_array($row['uid'], $uidArr)) { ?>btn-primary<?php  } else { ?>js-btn-select<?php  } ?>" js-uid="<?php  echo $row['uid'];?>" onclick="$(this).toggleClass('btn-primary')">选取</a></td>
				</tr>
				<?php  } } ?>
			</tbody>
		</table>
		<?php  echo $pager;?>
	</div>
</div>