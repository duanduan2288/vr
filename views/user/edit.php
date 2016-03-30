<?php
use app\models\Service;
?>
<script src="/plugins/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"></script>
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
                    <a>后台管理&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                    <a href="#">修改后台用户</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                    <a href="javascript:history.go(-1);"><i class="icon-reply"></i>返回</a>&nbsp;&nbsp;
                </div>
            </div>

            <div class="portlet-body form">
                <form id="myform" class="form-horizontal create-form" enctype="multipart/form-data" method="post">
                    <input type="hidden" id="user_id" value="<?php echo $model->id; ?>">
                    <input type="hidden" name='id' id="guid" value="<?php echo $model->guid; ?>">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>姓<span class="required">*</span></strong></label>
                            <div class="col-md-6">
                                <input id="last_name" autocomplete="off" name="last_name" type="text" value="<?php echo $model->last_name;?>" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>名<span class="required">*</span></strong></label>
                            <div class="col-md-6">
                                <input id="first_name" autocomplete="off" name="first_name" type="text" value="<?php echo $model->first_name;?>" class="form-control" >
                            </div>
                        </div>
                          <div class="form-group">
                            <label class="col-md-3 control-label"><strong>性别<span class="required">*</span></strong></label>
                            <div class="col-md-6 ">
                                <div class="radio-list">
                                    <label class="radio-inline">
                                        <input type="radio" name="gender" value="先生"  <?php if($model->gender=='先生'):?>checked="checked"<?php endif;?> /> 先生
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="gender" value="女士" <?php if($model->gender=='女士'):?>checked="checked"<?php endif;?> />女士
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>用户角色<span class="required">*</span></strong></label>
                            <div class="col-md-6">
                                <select class="form-control required" name="user_role">
                                    <option value=''>--请选择用户所属角色--</option>
                                    <?php if(!empty($roles)) : ?>
                                        <?php foreach ($roles as $value) : ?>
                                            <option value='<?php echo $value['id']; ?>' <?php echo $model->user_role == $value['id'] ? 'selected' : ''; ?>><?php echo $value['name']; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>用户邮箱<span class="required">*</span></strong></label>
                            <div class="col-md-6">
                                <input id="email" autocomplete="off" name="email" type="text" value="<?php echo $model->email; ?>" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>用户密码</strong></label>
                            <div class="col-md-6">
                                <input id="create_password" autocomplete="off" type="password" name="password" type="text" value="" class="form-control" >
                            </div>
                            <span class="help-inline">密码必须包含大写字母、小写字母、数字</span>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>确认密码</strong></label>
                            <div class="col-md-6">
                                <input autocomplete="off" class="form-control" type="password" placeholder="确认密码" name="rpassword"  value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>手机</strong></label>
                            <div class="col-md-6">
                                <input name="cellphone" type="text" value="<?php echo $model->cellphone;?>" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>电话</strong></label>
                            <div class="col-md-6">
                                <input name="phone" type="text" value="<?php echo $model->phone;?>" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>QQ</strong></label>
                            <div class="col-md-6">
                                <input name="qq" type="text" value="<?php echo $model->qq;?>" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>Skype</strong></label>
                            <div class="col-md-6">
                                <input name="skype" type="text" value="<?php echo $model->skype;?>" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>微信</strong></label>
                            <div class="col-md-6">
                                <input name="wechat" type="text" value="<?php echo $model->wechat;?>" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>备用邮箱</strong></label>
                            <div class="col-md-6">
                                <input name="backup_email" type="text" value="<?php echo $model->backup_email;?>" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>职位</strong></label>
                            <div class="col-md-6">
                                <input name="title" type="text" value="<?php echo $model->title;?>" class="form-control" >
                            </div>
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
                email: {
                    required: true,
                    email: true,
                    remote:{
                        url:'/site/check-email',
                        type:'post',
                        data:{
                            'email':function(){
                                return $('#email').val();
                            },
                            'id':function(){
                                return $('#user_id').val();
                            }
                        }
                    }
                },
                registrar_id: {
                    required: true
                },
                last_name: {
                    required: true
                },
                first_name: {
                    required: true
                },
                user_role:{
                    required:true
                },
                // agent_type:{
                //     required:true
                // },
                // is_operator_audit:{
                //     required:true
                // },
                cellphone:{
                    checkcellphone:true
                },
                phone:{
                    checkphone:true
                },
                backup_email:{
                    email:true
                },
                password: {
                    checkpassword:true,
                    passwordlength:true
                },
                rpassword: {
                    equalTo: "#create_password"
                }
            },

            messages: {
                registrar_id: {
                    required: "请选择所属公司"
                },
                last_name: {
                    required: "请输入用户姓"
                },
                first_name: {
                    required: "请输入用户名"
                },
                user_role:{
                    required:"请选择用户角色"
                },
                // agent_type:{
                //     required:"请选择管理代理商方式"
                // },
                // is_operator_audit:{
                //     required:"请选择是否是业务操作员或业务审核员"
                // },
                email: {
                    required: "请输入邮箱",
                    email: "请输入正确的邮箱",
                    remote:'该Emai已注册'
                },
                cellphone:{
                    checkcellphone:'请输入正确的手机号'
                },
                phone:{
                    checkphone:'请正确输入电话号码'
                },
                password: {
                    checkpassword:'密码必须包含大写字母、小写字母、数字',
                    passwordlength:'密码长度6-20位'
                },
                backpup_email:{
                    email:'请正确输入备用邮箱'
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
                        url: '/user/registry_edit',
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