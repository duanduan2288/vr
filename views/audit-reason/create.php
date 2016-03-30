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
                    <a>域名审核管理&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                    <a href="#">审核原因管理</a>
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
                            <label class="col-md-3 control-label"><strong>标题<span class="required">*</span></strong></label>
                            <div class="col-md-6">
                                <input id="title" autocomplete="off" name="title" type="text" value="<?php echo $model->title;?>" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>原因类别<span class="required">*</span></strong></label>
                            <div class="col-md-6">
                                 <select class="form-control" name="category_id">
                                    <option value=''>--请选择原因类别--</option>
                                     <?php if(!empty($categorys)) : ?>
                                         <?php foreach ($categorys as $key=>$value) : ?>
                                             <option value='<?php echo $key; ?>' <?php if($key==$model->category_id):?>selected="selected" <?php endif;?>><?php echo $value; ?></option>
                                         <?php endforeach; ?>
                                     <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>审核拒绝原因<span class="required">*</span></strong></label>
                            <div class="col-md-6">
                                 <textarea class="form-control" rows="10" name="content"  placeholder="请输入审核拒绝原因" ><?php echo $model->content;?></textarea>
                            </div>
                        </div>
                        <div class="form-actions fluid">
                            <div class="col-md-offset-3 col-md-6">
                                <input type="hidden" name="guid" value="<?php echo $model->guid?>" />
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

        $('#myform').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                category_id: {
                    required: true
                },
                title: {
                    required: true
                },
                content: {
                    required: true
                }
            },

            messages: {
                category_id: {
                    required: "请选择原因类别"
                },
                title: {
                    required: "请输入标题"
                },
                content: {
                    required: "请输入原因"
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

        var saveflag = false;
        if(saveflag==false){
            $('#saveform').click(function(){
                saveflag = true;
                if($('#myform').validate().form()){
                    var load = layer.load('正在提交，请稍后...');
                    $.ajax({
                        type: 'POST',
                        url: '/audit-reason/create',
                        data: $('#myform').serialize(),
                        dataType:'json',
                        success: function (r) {
                            layer.close(load);
                            saveflag = false;
                            if('err'==r.info){
                                common_layer(r.data,'');
                            }else{
                                common_layer(r.data,'/audit-reason/index');
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