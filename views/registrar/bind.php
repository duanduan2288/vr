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
                    <a>注册商管理&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                    <a href="#">API接口管理</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                    <a href="javascript:history.go(-1);"><i class="icon-reply"></i>返回</a>&nbsp;&nbsp;
                </div>
            </div>

            <div class="portlet-body form">
                 <form class="form-horizontal tmdb-create-form" enctype="multipart/form-data" action="" method="post" id="tmdb-create-form">
                    <input type="hidden" name="id" value="<?php echo $model->id; ?>">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>账号类型<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <select id="account_type" name="account_type" class="form-control">
                                    <option value="">请选择账号类型</option>
                                    <option value="epp" <?php echo $model->type == 'epp'? 'selected' : ''; ?>>EPP</option>
                                    <option value="webservice" <?php echo $model->type == 'webservice'? 'selected' : ''; ?>>Webservice</option>
                                    <option value="tmdb" <?php echo $model->type == 'tmdb'? 'selected' : ''; ?>>TMDB</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>账号名称<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <input id="show_name" type="text" value="<?php echo $model->show_name; ?>" class="form-control" name="show_name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>账号ID<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <input id="account_id" type="text" value="<?php echo $model->account; ?>" class="form-control" name="account_id">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>账号密码<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <input id="account_password" autocomplete="off" type="password" name="account_password" type="text" value="" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>确认密码<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <input id="account_rpassword" autocomplete="off" class="form-control" type="password" placeholder="确认密码" name="account_rpassword"  value="">
                            </div>
                            <span class="help-inline">两次密码要一致</span>
                        </div>
                         <div class="form-group">
                            <label class="col-md-3 control-label"><strong>IANA ID<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <input id="iana_id" type="text" value="<?php echo $model->iana_id; ?>" class="form-control" name="iana_id">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>签约注册商&nbsp;</strong></label>
                            <div class="col-md-3">
                                <select id="registrar_name" name="registrar_name" class="form-control">
                                    <option value="">请选择签约注册商</option>
                                    <option <?php if($model->registrar_name=='广东互易网络知识产权有限公司'): ?> selected <?php endif;?> value="广东互易网络知识产权有限公司">广东互易网络知识产权有限公司</option>
                                        <option <?php if($model->registrar_name=='环球互易资讯香港有限公司'): ?> selected <?php endif;?> value="环球互易资讯香港有限公司">环球互易资讯香港有限公司</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-actions fluid">
                            <div class="col-md-offset-3 col-md-6">
                                <button id="webservice-submit-btn" class="btn blue" type="submit"><i class="icon-save">&nbsp;&nbsp;</i>保存</button>
                            </div>
                        </div>
                    </div>
                    <br><br><br><br><br><br><br><br>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $('.tmdb-create-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                account_type: {
                    required: true
                },
                show_name: {
                    required: true
                },
                account_id: {
                    required: true
                },
                iana_id:{
                    required: true
                },
                account_password: {
                    required: true
                },
                account_rpassword: {
                    equalTo: "#account_password"
                }
            },

            messages: {
                account_type: {
                    required: "请选择账号类型"
                },
                show_name: {
                    required: '请输入账号名称'
                },
                account_id: {
                    required: "请输入账号ID"
                },
                iana_id:{
                    required: '请输入IANA ID'
                },
                account_password: {
                    required: "请输入账号密码"
                },
                account_rpassword:{
                    equalTo:'密码不一致',
                    required:'请确认密码'
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
            errorPlacement: function (error, element) {
                if (element.closest('.input-icon').size() === 1) {
                    error.insertAfter(element.closest('.input-icon'));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function (form) {
                // form.submit();
                var loadi = layer.load('加载中…');
                $('#tmdb-create-form input').blur();
                $.ajax({
                    type: 'POST',
                    url: '/registrar/save',
                    data: $('#tmdb-create-form').serialize(),
                    dataType:'json',
                    success: function(json) {
                        layer.close(loadi);
                        if (json.info != 'ok') {
                            layer.msg(json.msg,2,-1);
                            return false;
                        }
                        layer.msg(json.msg, 2, 1, function(){window.location.href='/registrar/index';});
                      }
                });
            }
        });
    });
</script>