<?php
    use yii\widgets\LinkPager;
    use yii\helpers\Url;
    use app\models\Service;
?>
<link rel="stylesheet" type="text/css" href="/plugins/bootstrap-datepicker/css/datepicker.css"/>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-cog"></i>
                    <a>系统管理&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                    <a href="#">操作日志</a>
                </div>
                <div class="tools">
                    <!--<a href="--><?php //echo $this->createUrl('/issue/create/'); ?><!--"><i class="icon-plus"></i> 添加</a>-->
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                </div>
            </div>

            <div class="portlet-body">
                <ul class="nav nav-tabs">
                </ul>
                <table class="table table-striped table-hover table-bordered table-advance">
                    <thead>
                    <tr>
                        <th>操作类型</th>
                        <th>操作人</th>
                        <th>操作人IP</th>
                        <th>操作时间</th>
                        <th>查看</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if($model) { ?>
                        <?php foreach($model as $list) { ?>
                            <tr>
                                <td><?php echo $list['operation_type'];?></td>
                                <td><?php echo Service::get_user_name($list['operator_id']);?></td>
                                <td> <?php echo $list['ip'];?></td>
                                <td><?php echo $list['created'];?></td>
                                <td>
                                    <a class="delete" href="/operation-log/detail?id=<?php echo $list['id'];?>">
                                        查看
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td align="center" colspan="5">无记录</td>
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
<script type="text/javascript" src="/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script>
    jQuery(document).ready(function() {
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
        dd.setDate(dd.getDate()+AddDayCount);//获取AddDayCount天后的日期
        var y = dd.getFullYear();
        var m = dd.getMonth()+1;//获取当前月份的日期
        var d = dd.getDate();
        return y+"-"+m+"-"+d;
    }
</script>