<?php
    use app\models\Dictionary;
?>
<style>
    .table th a{ color:#000;text-decoration:none;}
    .table td a{text-decoration:none;}
    .input-small {width: 150px !important;}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-cog"></i>
                    <a>域名审核管理&nbsp;&nbsp;<i class="icon-angle-right"></i></a>
                    <a href="/audit-data/index">域名初审&nbsp;&nbsp;<i class="icon-angle-right"></i></a>
                    <a href="#">信息补录</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                    <a href="javascript:history.go(-1);"><i class="icon-reply"></i>返回</a>&nbsp;&nbsp;
                </div>
            </div>

            <div class="portlet-body form">
                <form class="form-horizontal create-form" enctype="multipart/form-data"  method="post">
                    <input type="hidden" name='guid' id="guid" value="<?php echo $model->guid; ?>">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>域名</strong></label>
                            <div class="col-md-3">
                                <input  name="domain" type="text" disabled value="<?php echo $model->domain;?>" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>域名解析网址</strong></label>
                            <div class="col-md-3 ">
                                <input  name="parse_url" type="text"  value="<?php echo $model->parse_url;?>" class="form-control" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>商标注册号</strong></label>
                            <div class="col-md-3">
                                <input name="trademark_reg_no" type="text" value="<?php echo $model->trademark_reg_no;?>" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>商标颁发国</strong></label>
                            <div class="col-md-3">
                                <select class="country form-control input-small" name="tm_issuing_country" id="country" style="display:inline;">
                                    <?php if(isset($model['tm_issuing_country'])):?>
                                        <option value="<?php echo $model['tm_issuing_country'];?>"><?php echo Dictionary::$countries[$model['tm_issuing_country']];?></option>
                                    <?php endif;?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3"><strong class="start_date">商标注册证书到期日期</strong></label>
                            <div class="col-md-3">
                                <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">
                                    <input class="form-control" type="text" name="tm_expires_date" readonly=""  style="cursor: default;" value="<?php echo isset($model['tm_expires_date'])?$model['tm_expires_date']:'';?>">
                                    <span class="input-group-btn">
                                    <button class="btn default" type="button">
                                        <i class="icon icon-calendar"></i>
                                    </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>商标类别</strong></label>
                            <div class="col-md-3">
                                <textarea name="tm_class_type" class="form-control" ><?php echo $model->tm_class_type;?></textarea>
                            </div>
                        </div>
                        <div class="form-actions fluid">
                            <div class="col-md-offset-3 col-md-6">
                                <button id="create-submit-btn" class="btn blue" type="button"><i class="icon-save">&nbsp;&nbsp;</i>保存</button>
                            </div>
                        </div>
                    </div>
                    <br><br><br><br><br><br><br><br>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/js/itm.region.js"></script>
<!--<script type="text/javascript" src="/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>-->
<script type="text/javascript">
    var maxdate = '<?php echo date('Y-m-d');?>';
    $(function(){
        /****有效期显示****/
        if (!jQuery().datepicker) {
            return;
        }
        $('.date').datepicker({
                rtl: App.isRTL(),
                autoclose: true,
                startDate: maxdate,
                endDate:Infinity
            }
        );
        //渲染颁发国
        renderRegionSelect();

        var saveflag = false;
        if(saveflag==false){
            $('#create-submit-btn').click(function(){
                saveflag = true;
                var load = layer.load('正在提交，请稍后...');
                $.ajax({
                    type: 'POST',
                    url: '/audit-data/back-tracking',
                    data: $('.create-form').serialize(),
                    dataType:'json',
                    success: function (r) {
                        layer.close(load);
                        saveflag = false;
                        if('err'==r.info){
                            common_layer(r.data,'');
                        }else{
                            var url=encodeURI("/audit-data/index");
                            common_layer(r.data,url);
                        }
                    },
                    error: function () {
                        layer.close(load);
                        saveflag = false;
                        common_layer('补录失败','');
                    }
                });
            });
        }else{
            common_layer('数据已提交','');
        }
    });

</script>