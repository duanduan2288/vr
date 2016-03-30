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
                    <a>系统管理&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                    <a href="#">绑定TMDB账号</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>
                </div>
            </div>

            <div class="portlet-body form">
                 <form class="form-horizontal tmdb-create-form" enctype="multipart/form-data" action="/registrar/tmdbBind" method="post">
                    <div class="form-body">
                        <h3 class="form-section">绑定TMDB账号</h3>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>TMDB ID<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <input id="tmdb_id" type="text" value="<?php echo $id;?>" class="form-control" name="tmdb_id">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>TMDB密码<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <input id="tmdb_password" autocomplete="off" type="password" name="tmdb_password" type="text" value="" class="form-control" >
                            </div>
                            <!-- <span class="help-inline">密码必须包含大写字母、小写字母、数字</span> -->
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>确认密码<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <input autocomplete="off" class="form-control" type="password" placeholder="确认密码" name="tmdb_rpassword"  value="">
                            </div>
                            <span class="help-inline">两次密码要一致</span>
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
                tmdb_id: {
                    required: true
                },
                tmdb_password: {
                    required: true
                    // checkpasswor:true
                },
                tmdb_rpassword: {
                    equalTo: "#tmdb_password"
                }
            },

            messages: {
                tmdb_id: {
                    required: "请输入TMDB ID"
                },
                tmdb_password: {
                    required: "请输入TMDB密码"
                    // checkpassword:'密码必须包含大写字母、小写字母、数字'
                },
                tmdb_rpassword:{
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
                form.submit();
            }
        });
    });
</script>