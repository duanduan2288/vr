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
                        <i class="icon-cog"></i>
                        <a>账户设置&nbsp;&nbsp;<i class="icon-angle-right"></i></a><a href="#">修改密码</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"> <i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                    <a href="javascript:history.go(-1);"><i class="icon-reply"></i>返回</a>&nbsp;&nbsp;
                    <!-- <a href="/kb"><i class="icon-question"></i>帮助</a> -->
                </div>
            </div>

            <div class="portlet-body form">
                <form class="form-horizontal create-form" enctype="multipart/form-data" action="/site/change_password" method="post">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>姓</strong></label>
                            <div class="col-md-3">
                                <input type="text" value="<?php echo $info['last_name']; ?>" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>名</strong></label>
                            <div class="col-md-3">
                                <input type="text" value="<?php echo $info['first_name']; ?>" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>旧密码<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <input id="old_password" autocomplete="off" type="password" name="old_password" type="text" value="" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>新密码<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <input id="create_password" autocomplete="off" type="password" name="password" type="text" value="" class="form-control" >
                            </div>
                            <span class="help-inline">密码必须包含大写字母、小写字母、数字</span>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>确认密码<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <input autocomplete="off" class="form-control" type="password" placeholder="确认密码" name="rpassword"  value="">
                            </div>
                            <!-- <span class="help-inline">两次密码要一致</span> -->
                        </div>
                        <div class="form-actions fluid">
                            <div class="col-md-offset-3 col-md-6">
                                <button id="create-submit-btn" class="btn blue" type="submit"><i class="icon-save">&nbsp;&nbsp;</i>保存</button>
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

            /***密码***/
            jQuery.validator.addMethod("checkpassword", function(value, element) {
                return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[\s\S]*$/.test(value);
            }, "密码必须包含大写字母、小写字母、数字");

            $('.create-form').validate({
	            errorElement: 'span', //default input error message container
	            errorClass: 'help-block', // default input error message class
	            focusInvalid: false, // do not focus the last invalid input
	            ignore: "",
	            rules: {
	                old_password: {
	                    required: true,
	                    // old_password: true,
                        remote:{
                            url:'/site/checkoldpassword',
                            type:'post',
                            data:{
                                'old_password':function(){
                                    return $('#old_password').val();
                                }
                            }
                        }
	                },
	                password: {
	                    required: true,
                        checkpassword:true
	                },
	                rpassword: {
	                    equalTo: "#create_password"
	                }
	            },

	            messages: {
                    old_password: {
                        required: "请输入旧密码",
                        remote:'旧密码不正确'
                    },
                    password: {
                         required: "请输入密码",
                         checkpassword:'密码必须包含大写字母、小写字母、数字'
                    },
                    rpassword:{
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
		$('.create-form input').keypress(function (e) {
            if (e.which == 13) {
                if ($('.create-form').validate().form()) {
                    $('.create-form').submit();
                }
                return false;
            }
        });
		})
</script>