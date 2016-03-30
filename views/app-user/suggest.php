<?php
use yii\widgets\LinkPager;
use app\models\Service;
use yii\helpers\Url;
?>
<style>
	.table th a{ color:#000;text-decoration:none;}
	.table td a{text-decoration:none;}
	.input-small {width: 250px !important;}
	.table .asc {
		background: rgba(0, 0, 0, 0) url("/img/asc.png") no-repeat scroll right center;
		padding-right: 15px;
	}
	.table .desc {
		background: rgba(0, 0, 0, 0) url("/img/desc.png") no-repeat scroll right center;
		padding-right: 15px;
	}
</style>
<div class="row">
	<div class="col-md-12">
		<div class="portlet">
			<div class="portlet-title">
				<div class="caption cap-head">
					<i class="icon icon-cog"></i>
					<a>用户管理&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
					<a href="#">用户反馈列表</a>
				</div>
				<div class="tools">
					<a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
				</div>
			</div>
			<div class="portlet-body form">
				<form class="form-inline" role="form" method="get">
					<div class="form-body">
						<div class="form-group">
							<div class="input-group input-large date-picker input-daterange">
								<input style="cursor: pointer;" readonly id="start_date" type="text" placeholder="提交时间" class="form-control" name="start_date" value="<?php echo $start_date ; ?>">
								<span class="input-group-addon">至</span>
								<input style="cursor: pointer;" readonly id="end_date" type="text" placeholder="提交时间" class="form-control" name="end_date" value="<?php echo $end_date ; ?>">
							</div>
						</div>
						<button class="btn blue" type="submit"><i class="icon-search"></i> 查询</button>
					</div>
				</form>
			</div>
			<div class="portlet-body">
				<table class="table table-striped table-hover table-bordered table-advance">
					<thead>
					<tr>
						<th>登录名</th>
						<th>用户名</th>
						<th>用户机型</th>
						<th>反馈内容</th>
						<th>提交时间</th>
						<th width="10%">操作</th>
					</tr>
					</thead>
					<tbody>
					<?php if($data) { ?>
						<?php foreach($data as $list) { ?>
							<tr>
								<td><?php echo Service::get_user_name($list['userId'])?></td>
								<td>
									<?php echo Service::get_user_name($list['userId'])?>
								</td>
								<td>
									<?php echo $list['source_type'];?>
								</td>
								<td><?php echo $list['content'];?></td>
								<td><?php echo $list['createTime'];?></td>
								<td>
								</td>
							</tr>
						<?php } ?>
					<?php } else { ?>
						<tr>
							<td align="center" colspan="6">无记录</td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
				<div class="pull-right">
					<?php
					echo LinkPager::widget([
						'pagination' => $pages,
					]);
					?>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	jQuery(document).ready(function () {
		var today = GetDateStr(0);
		if (jQuery().datepicker) {
			$('.date-picker').datepicker({
				autoclose: true,
				isRTL: App.isRTL(),
				format: "yyyy-mm-dd",
				endDate: today
			});
			$('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
		}
	});
	function GetDateStr(AddDayCount) {
		var dd = new Date();
		dd.setDate(dd.getDate() + AddDayCount);//获取AddDayCount天后的日期
		var y = dd.getFullYear();
		var m = dd.getMonth() + 1;//获取当前月份的日期
		var d = dd.getDate();
		return y + "-" + m + "-" + d;
	}
</script>