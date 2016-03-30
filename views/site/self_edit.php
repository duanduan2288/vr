<?php
use app\models\AuthRole;
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
                        <i class="icon-cog"></i>
                        <a>账户设置&nbsp;&nbsp;<i class="icon-angle-right"></i></a>
                        <a href="#">个人设置</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                    <a href="javascript:history.go(-1);"><i class="icon-reply"></i>返回</a>&nbsp;&nbsp;
                </div>
            </div>

            <div class="portlet-body form">
                <form class="form-horizontal create-form" enctype="multipart/form-data" action="/site/self-edit" method="post">
                    <input type="hidden" name='id' id="user_id" value="<?php echo $model->id; ?>">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>姓<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <input id="last_name" autocomplete="off" name="last_name" type="text" value="<?php echo $model->last_name;?>" class="form-control" >
                            </div>
                            <!-- <span class="help-inline">姓不要超过50字</span> -->
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>名<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <input id="first_name" autocomplete="off" name="first_name" type="text" value="<?php echo $model->first_name;?>" class="form-control" >
                            </div>
                            <!-- <span class="help-inline">名不要超过50字</span> -->
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>性别<span class="required">*</span></strong></label>
                            <div class="col-md-3 ">
                                <div class="radio-list">
                                    <label class="radio-inline">
                                        <input type="radio" name="gender" value="先生"  <?php if($model->gender=='先生'):?>checked="checked"<?php endif;?> /> 先生
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="gender" value="女士" <?php if($model->gender=='女士'):?>checked="checked"<?php endif;?>/>女士
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>用户角色<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <select class="form-control" name="user_role" disabled="disabled">
                                    <option value=''><?php echo AuthRole::findOne($model->user_role)->name;?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>用户邮箱<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <input readonly id="email" autocomplete="off" name="email" type="text" value="<?php echo $model->email; ?>" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>手机</strong></label>
                            <div class="col-md-3">
                                <input name="cellphone" type="text" value="<?php echo $model->cellphone;?>" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>电话</strong></label>
                            <div class="col-md-3">
                                <input name="phone" type="text" value="<?php echo $model->phone;?>" class="form-control" >
                            </div>
                             <span class="help-inline">电话格式010-12345678</span>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>QQ</strong></label>
                            <div class="col-md-3">
                                <input name="qq" type="text" value="<?php echo $model->qq;?>" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>Skype</strong></label>
                            <div class="col-md-3">
                                <input name="skype" type="text" value="<?php echo $model->skype;?>" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>微信</strong></label>
                            <div class="col-md-3">
                                <input name="wechat" type="text" value="<?php echo $model->wechat;?>" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>备用邮箱</strong></label>
                            <div class="col-md-3">
                                <input name="backup_email" type="text" value="<?php echo $model->backup_email;?>" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>职位</strong></label>
                            <div class="col-md-3">
                                <input name="title" type="text" value="<?php echo $model->title;?>" class="form-control" >
                            </div>
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
<script>
    $(function(){
        /***手机号***/
        jQuery.validator.addMethod("checkcellphone", function(value, element) {
            return this.optional(element) || /^1[0-9]{10}$/.test(value);
        }, "请正确输入手机号");

        /***电话***/
        jQuery.validator.addMethod("checkphone", function(value, element) {
            return this.optional(element) || /^((0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$/.test(value);
        }, "请正确输入电话");


        $('.create-form').validate({
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
                last_name: {
                    required: true
                },
                first_name: {
                    required: true
                },

                cellphone:{
                    checkcellphone:true
                },
                phone:{
                    checkphone:true
                },
                backup_email:{
                    email:true
                }

            },

            messages: {
                last_name: {
                    required: "请输入用户姓"
                },
                first_name: {
                    required: "请输入用户名"
                },
                user_role:{
                    required:"请选择用户角色"
                },
                email: {
                    required: "请输入邮箱",
                    remote:'该Emai已注册'
                },
                cellphone:{
                    checkcellphone:'请输入正确的手机号'
                },
                phone:{
                    checkphone:'请正确输入电话号码'
                },

                backpup_email:{
                    email:'请正确输入备用邮箱'
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
    });
    /***获取字符串的长度**/
    function getByteLen(str){
//编码为GBK时，非ASCII字符占用两个字节宽
        return (str.replace((/[^\x00-\xff]/g,'xx').length)).length;
//编码为UTF-8时，非ASCII字符占用三个字节宽
    }
</script>