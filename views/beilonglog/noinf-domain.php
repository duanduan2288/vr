<?php
    use yii\widgets\LinkPager;
    use yii\helpers\Url;
    use app\models\Service;
?>
<style>
.table th a{ color:#000;text-decoration:none;}
.table td a{text-decoration:none;}
.input-small {width: 250px !important;}
.table .asc {
    padding-right: 15px;
    background: url('/img/asc.png') right center no-repeat;
}
.table .desc {
    padding-right: 15px;
    background: url('/img/desc.png') right center no-repeat;
}
.table .default_sort {
    padding-right: 15px;
    background: url('/img/default.png') right center no-repeat;
}
.form-group select{width: 100px}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-cog"></i>
                    <a>域名审核管理&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                    <a href="#">未上传资料域名</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                </div>
            </div>
            <div class="portlet-body form">
                <form class="form-inline" role="form" method="get">
                    <div class="form-body">
                        <div class="form-group">
                            <input type="text" class="form-control input-small" id='search_name' name="search_name" placeholder="请输入注册商名称" value="<?php echo $search_name;?>">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control input-small" id="search_domain" name="search_domain" placeholder="请输入域名名称" value="<?php echo $search_domain;?>">
                        </div>
                        <div class="form-group">
                            <div class="input-group input-large date-picker input-daterange">
                                <input id="start_date" type="text" class="form-control" name="start_date" value="<?php echo $start_date; ?>" placeholder="起止日">
                                <span class="input-group-addon">至</span>
                                <input id="end_date" type="text" class="form-control" name="end_date" value="<?php echo $end_date; ?>" placeholder="截止日">
                            </div>
                        </div>
                        <button class="btn blue" type="submit"><i class="icon-search"></i> 查询</button>
                        <button id="order_download" class="btn blue" type="button"><i class="icon-download"></i> 导出CSV</button>
                        <button id="order_downloadexcel" class="btn blue" type="button"><i class="icon-download"></i> 导出EXCEL</button>
                    </div>
                </form>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-hover table-bordered table-advance">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>注册商名称</th>
                            <th>操作类型</th>
                            <th>单据号码（序列号）</th>
                            <th>商标域名</th>
                            <th>操作期限</th>
                            <th>费用</th>
                            <th>费用类型</th>
                            <th>操作员</th>
                            <th>操作日期</th>
                            <th>备注</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($logs) { ?>
                            <?php foreach($logs as $list) { ?>
                                <tr>
                                    <td><?php echo $list['id']?></td>
                                    <td><?php echo $list['registrar_name']?></td>
                                    <td><?php echo $list['operation_type']?></td>
                                    <td><?php echo $list['sequence_number']?></td>
                                    <td><?php echo $list['domain_name']?></td>
                                    <td><?php echo $list['operation_deadline']?></td>
                                    <td><?php echo $list['cost']?></td>
                                    <td><?php echo $list['cost_type']?></td>
                                    <td><?php echo $list['operator']?></td>
                                    <td><?php echo $list['operation_date']?></td>
                                    <td><?php echo $list['remarks']?></td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td align="center" colspan="7">无记录</td>
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
    $(function(){
        if (jQuery().datepicker) {
            $('.date-picker').datepicker({
                rtl: App.isRTL(),
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
        /***导出csv***/
        $('#order_download').click(function(event) {
            var search_domain = $('#search_domain').val();
            var search_name = $('#search_name').val();
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            window.location.href = '/export/finance?search_domain='+search_domain+'&search_name='+search_name+'&start_date='+start_date+'&end_date='+end_date+'&dtype=csv&w=1';
        });
        /***导出excel***/
        $('#order_downloadexcel').click(function(event) {
            var search_domain = $('#search_domain').val();
            var search_name = $('#search_name').val();
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();

            window.location.href = '/export/finance?search_domain='+search_domain+'&search_name='+search_name+'&start_date='+start_date+'&end_date='+end_date+'&dtype=excel&w=1';
        });
    });
</script>