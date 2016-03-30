<link href="/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet"/>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-suitcase"></i>
                    <a href="#">代理商支持&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                    <a href="#">添加合规通知</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                    <a href="javascript:history.go(-1);"><i class="icon-reply"></i>返回</a>&nbsp;&nbsp;
                </div>
            </div>

            <div class="portlet-body form">
                <form role="form" id="myform" class="form-horizontal validate" enctype="multipart/form-data">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>请选择代理商<span class="required">*</span></strong></label>
                            <div class="col-md-5">
                                <select name="assignee_company_id" class="form-control">
                                    <?php if(!empty($agents)):?>
                                        <?php foreach($agents as $v):?>
                                            <?php if ($flag): ?>
                                                <?php if (in_array($v['id'], $agent_ids)): ?>
                                                    <option value="<?php echo $v['id'] ?>"><?php echo $v['company_name']; ?></option>
                                                <?php endif ?>
                                            <?php else: ?>
                                                    <option value="<?php echo $v['id'] ?>"><?php echo $v['company_name']; ?></option>
                                            <?php endif ?>
                                    <?php endforeach;endif;?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>紧急度<span class="required">*</span></strong></label>
                            <div class="col-md-5">
                                <select name="priority" class="form-control">
                                    <option value="普通">普通</option>
                                    <option value="紧急">紧急</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>标题<span class="required">*</span></strong></label>
                            <div class="col-md-5">
                                <input type="text" name="name" value="" class="form-control required" data-rule-maxlength="250" data-msg-maxlength="标题过长">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>描述<span class="required">*</span></strong></label>
                            <div class="col-md-5">
                                <textarea class="form-control required" rows="10" name="content" data-rule-maxlength="500" placeholder="请填写详细信息" data-msg-maxlength="说明不要超过500字"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong><?php echo Yii::t('Base','附件');?>&nbsp;&nbsp;</strong></label>
                            <div class="col-md-5">
                                <div class="fileupload-buttonbar minibtn">
                                    <span class="btn blue fileinput-button">
                                         <i class="icon-upload"></i>
                                        <span>
                                            添加附件
                                        </span>
                                        <input type="file" id="file_caac" name="file_caac" size="45" class="filebtn2" onchange="return ajaxFileUpload(this)"/>
                                    </span>
                                </div>
                                <br/>
                                 <span class="help-inline">允许上传格式：zip,rar,doc,docx,xls,xlsx,png,jpg,jpeg,gif,pdf 。 大小不超过20M</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong></strong></label>
                            <div class="col-md-5" id="attachment">
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
<script type="text/javascript" src="/js/ajaxfileupload.js"></script>
<script type="text/javascript" src="/js/issue_show.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        $('#myform').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                content: {
                    required: true
                },
                name:{
                    required:true
                }
            },

            messages: {
                content: {
                    required: "请输入内容"
                },
                name:{
                    required:"请输入标题"
                }
            },

            invalidHandler: function (event, validator) { //display error alert on form submit
                $('.alert-danger', $('#myform')).show();
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

                error.insertAfter(element.closest('.form-control'));
            },

            submitHandler: function (form) {
                form.submit();
            }
        });

        $('#myform input').keypress(function (e) {
            if (e.which == 13) {
                if ($('#myform').validate().form()) {
                    $('#myform').submit();
                }
                return false;
            }
        })
        var saveflag = false;
        if(saveflag==false){
            $('#saveform').click(function(){
                saveflag = true;
                if($('#myform').validate().form()){
                    var load = layer.load('正在提交，请稍后...');
                    $.ajax({
                        type: 'POST',
                        url: '/issue/createComplianceIssue',
                        data: $('#myform').serialize(),
                        dataType:'json',
                        success: function (r) {
                            layer.close(load);
                            saveflag = false;
                            if('err'==r.info){
                                common_layer(r.data,'');
                            }else{
                                // var url=encodeURI("/domainRegister/register?domain="+ r.data);
                                common_layer(r.data,'/issue/complianceIssue');
                                // location.href='/finance/CreditIssue/agent_id/'+$('#agent_id').val();
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
    });
</script>
