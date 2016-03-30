<link href="/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet"/>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption"><i class="icon-reorder"></i>创建工单</div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                    <a href="javascript:history.go(-1);"><i class="icon-reply"></i>返回</a>&nbsp;&nbsp;
                </div>
            </div>

            <div class="portlet-body form">
                <form role="form" id="myform" class="form-horizontal validate" enctype="multipart/form-data" action="/issue/create" method="post">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>工单类型<span class="required">*</span></strong></label>
                            <div class="col-md-2">
                                <select name="type" class="form-control">
                                    <option value="RegistrarIssue">代理商申请</option>
                                    <option value="FinanceIssue">财务问题</option>
                                    <option value="OtherIssue">其他</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>紧急度<span class="required">*</span></strong></label>
                            <div class="col-md-2">
                                <select name="priority" class="form-control">
                                    <option value="普通">普通</option>
                                    <option value="紧急">紧急</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>标题<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <input type="text" name="name" value="" class="form-control required" data-rule-maxlength="250" data-msg-maxlength="标题过长">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>描述<span class="required">*</span></strong></label>
                            <div class="col-md-5">
                                <textarea class="form-control required" rows="4" name="content" data-rule-maxlength="500" placeholder="请填写详细信息" data-msg-maxlength="说明不要超过500字"></textarea>
                            </div>
                            <span class="help-inline">请输入详细描述信息</span>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>附件&nbsp;&nbsp;</strong></label>
                            <div class="col-md-5">
                                <div class="fileupload-buttonbar minibtn">
                                    <span class="btn blue fileinput-button">
                                         <i class="icon-upload"></i>
                                        <span>
                                            添加附件
                                        </span>
                                        <input type="file" id="file_caac" name="file_caac" size="45" class="filebtn2" onchange="return ajaxFileUpload(this)"/>
                                    </span>
                                    <span class="help-block"  id="message_file"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong></strong></label>
                            <div class="col-md-5" id="attachment">
                            </div>
                        </div>
                        <div class="form-actions fluid">
                            <div class="col-md-offset-3 col-md-6">
                                <button class="btn blue" type="submit"><i class="icon-save">&nbsp;&nbsp;</i>保存</button>
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
        });
    });
    //上传附件
    function ajaxFileUpload(obj) {
        var fileName = $(obj).attr('id');
        var uploadInfo = $(obj).parent();
        $(this).nextAll('.loading').ajaxStart(function () {
            $(this).show();
        }).ajaxComplete(function () {
            $(this).hide();
        });
        $.ajaxFileUpload
        (
            {
                url: '/upload/uploadfile',
                secureuri: false,
                fileElementId: fileName,
                dataType: 'json',
                data: {
                    'file_name': fileName
                },
                success: function (data, status) {
                    if (typeof(data.error) != 'undefined' && data.error != '') {
                        $('#message_file').html(data.error).show();
                    } else {
                        var str = '<input type="hidden" name="attachment[]" value="'+data.guid+'" >' +
                            '<span class="help-inline"><a href="/upload/showuploadfile?id='+data.guid+'" target="_blank">'+data.name+'</a></span>';
                        $('#attachment').append(str);
                    }
                },
                error: function (data, status, e) {
                    alert(e);
                }
            }
        );
        return false;
    }
</script>
