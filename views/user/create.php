<style>
.table th a{ color:#000;text-decoration:none;}
.table td a{text-decoration:none;}
.input-small {width: 150px !important;}
.radio-inline{width: 150px;}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-cog"></i>
                    <a>用户管理&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                    <a href="#">添加管理员</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                    <a href="javascript:history.go(-1);"><i class="icon-reply"></i>返回</a>&nbsp;&nbsp;
                </div>
            </div>

            <div class="portlet-body form">
                <form id="myform" class="form-horizontal" enctype="multipart/form-data" method="post">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>登陆名<span class="required">*</span></strong></label>
                            <div class="col-md-6">
                                <input id="logonName" autocomplete="off" name="logonName" type="text" value="" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>昵称<span class="required">*</span></strong></label>
                            <div class="col-md-6">
                                <input id="userName" autocomplete="off" name="userName" type="text" value="" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>角色选择<span class="required">*</span></strong></label>
                            <div class="col-md-6">
                                <select class="form-control" name="user_role">
                                    <option value=''>--请选择用户角色--</option>
                                    <?php if(!empty($roles)) : ?>
                                        <?php foreach ($roles as $value) : ?>
                                            <option value='<?php echo $value['id']; ?>'><?php echo $value['name']; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>用户密码<span class="required">*</span></strong></label>
                            <div class="col-md-6">
                                <input id="create_password" autocomplete="off" type="password" name="password" type="text" value="" class="form-control" >
                            </div>
                            <span class="help-inline">密码必须包含大写字母、小写字母、数字</span>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>确认密码<span class="required">*</span></strong></label>
                            <div class="col-md-6">
                                <input autocomplete="off" class="form-control" type="password" placeholder="确认密码" name="rpassword"  value="">
                            </div>
                            <!-- <span class="help-inline">两次密码要一致</span> -->
                        </div>
                        <div class="form-actions fluid">
                            <div class="col-md-offset-3 col-md-6">
                                <button id="saveform" class="btn blue" type="button"><i class="icon-save">&nbsp;&nbsp;</i>保存</button>
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
        /***手机号***/
        jQuery.validator.addMethod("checkcellphone", function(value, element) {
            return this.optional(element) || /^1[0-9]{10}$/.test(value);
        }, "请正确输入手机号");

        /***电话***/
        jQuery.validator.addMethod("checkphone", function(value, element) {
            return this.optional(element) || /^((0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$/.test(value);
        }, "请正确输入电话");

        jQuery.validator.addMethod("checkpassword", function(value, element) {
            return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[\s\S]*$/.test(value);
        }, "密码必须包含大写字母、小写字母、数字");

        jQuery.validator.addMethod("passwordlength", function(value, element) {
            var nlen = getByteLen(value);
            return this.optional(element) || (nlen > 5 && nlen < 21);
        }, "密码长度6-20位");

        $('#myform').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                logonName: {
                    required: true,
                    remote:{
                        url:'/site/check-username',
                        type:'post',
                        data:{
                            'email':function(){
                                return $('#logonName').val();
                            }
                        }
                    }
                },
                userName: {
                    required: true
                },
                user_role:{
                    required:true
                },
                password: {
                    required: true,
                    checkpassword:true,
                    passwordlength:true
                },
                rpassword: {
                    equalTo: "#create_password"
                }
            },

            messages: {
                userName: {
                    required: "请输入昵称"
                },
                user_role:{
                    required:"请选择用户角色"
                },
                logonName: {
                    required: "请输入登陆名",
                    remote:'该登陆名已存在'
                },
                password: {
                    required: "请输入密码",
                    checkpassword:'密码必须包含大写字母、小写字母、数字',
                    passwordlength:'密码长度6-20位'
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
        // $('#myform input').keypress(function (e) {
        //     if (e.which == 13) {
        //         if ($('#myform').validate().form()) {
        //             $('#myform').submit();
        //         }
        //         return false;
        //     }
        // });

        var saveflag = false;
        if(saveflag==false){
            $('#saveform').click(function(){
                saveflag = true;
                if($('#myform').validate().form()){
                    var load = layer.load('正在提交，请稍后...');
                    $.ajax({
                        type: 'POST',
                        url: '/user/create',
                        data: $('#myform').serialize(),
                        dataType:'json',
                        success: function (r) {
                            layer.close(load);
                            saveflag = false;
                            if('err'==r.info){
                                common_layer(r.data,'');
                            }else{
                                common_layer(r.data,'/user/registry_list');
                                // window.history.back(-1);
                            }
                        },
                        error: function () {
                            layer.close(load);
                            saveflag = false;
                            common_layer('保存失败','');
                        }
                    });
                }
            });
        }else{
            common_layer('数据已提交','');
        }
    })
    /***获取字符串的长度**/
    function getByteLen(str){
        //编码为GBK时，非ASCII字符占用两个字节宽
        return (str.replace((/[^\x00-\xff]/g,'xx').length)).length;
        //编码为UTF-8时，非ASCII字符占用三个字节宽
    }
</script>