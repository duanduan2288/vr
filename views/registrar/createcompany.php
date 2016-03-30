<style>
    .table th a{ color:#000;text-decoration:none;}
    .table td a{text-decoration:none;}
    .input-small {width: 150px !important;display:inline;}
    .close {text-indent: -10000px;}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-cog"></i>
                    <a>注册商管理&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                    <a href="#"><?php if(!empty($model->id)):?>修改公司信息<?php  else: ?>添加公司信息 <?php endif;?></a>
                </div>
                <div class="tools">
                    <a href="javascript:history.go(-1);"><i class="icon-reply"></i>返回</a>
                </div>
            </div>

            <div class="portlet-body form">
                <form role="form" id="company_form" class="form-horizontal validate" enctype="multipart/form-data">
                    <input id="company_id" type="hidden" name='id' value="<?php echo $model->id; ?>">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>注册商IANA ID<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <input type="text" name="code" value="<?php echo $model->code; ?>" class="form-control required" >
                                <span class="help-block"></span>
                            </div>
                            <span class="help-inline"></span>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>EPP ID<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <input type="text" name="epp_id" value="<?php echo $model->epp_id; ?>" class="form-control required" >
                                <span class="help-block"></span>
                            </div>
                            <span class="help-inline"></span>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>Webservice账号<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <input type="text" name="webservice_id" value="<?php echo $model->webservice_id; ?>" class="form-control" >
                                <span class="help-block"></span>
                            </div>
                            <span class="help-inline"></span>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>Webservice密码<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <input type="text" name="webservice_password" value="" class="form-control" >
                                <span class="help-block"></span>
                            </div>
                            <span class="help-inline"></span>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>单位简称<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <input type="text" name="abbreviation" value="<?php echo $model->abbreviation; ?>" class="form-control required">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>单位名称（中文）<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <input type="text" name="company_name_zh_cn" value="<?php echo $model->company_name_zh_cn; ?>" class="form-control required">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>单位名称（英文）</strong></label>
                            <div class="col-md-3">
                                <input type="text" name="company_name_en_us" value="<?php echo $model->company_name_en_us; ?>" class="form-control">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>单位网址 <span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <input type="text" name="company_url" value="<?php echo $model->company_url; ?>" class="form-control required">
                                <span class="help-block"></span>
                            </div>
                        </div>
                           <div class="form-group">
                            <label class="col-md-3 control-label"><strong>公司负责人</strong></label>
                            <div class="col-md-3">
                                <input type="text" name="company_leader" value="<?php echo $model->company_leader; ?>" class="form-control">
                                <span class="help-block"></span>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-md-3 control-label"><strong>公司负责人电话</strong></label>
                            <div class="col-md-3">
                                <input type="text" name="company_leader_phone" value="<?php echo $model->company_leader_phone; ?>" class="form-control">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>公司联系人</strong></label>
                            <div class="col-md-3">
                                <input type="text" name="company_contact" value="<?php echo $model->company_contact; ?>" class="form-control">
                                <span class="help-block"></span>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-md-3 control-label"><strong>公司联系人电话</strong></label>
                            <div class="col-md-3">
                                <input type="text" name="company_contact_phone" value="<?php echo $model->company_contact_phone; ?>" class="form-control">
                                <span class="help-block"></span>
                            </div>
                        </div>
                          <div class="form-group">
                            <label class="col-md-3 control-label"><strong>国家代码<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <select class="country form-control input-small" name="country_code" id="country" style="display:inline;">
                                </select>
                                <!--<input type="text" name="country_code" value="--><?php //echo $model->country_code; ?><!--" class="form-control">-->
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label  class="col-md-3 control-label"><strong>国家所属<span class="required">*</span></strong></label>
                            <div class="col-md-5">
                                <div class="radio-list">
                                    <label class="radio-inline">
                                        <input type="radio" name="country_type" value="国内" <?php echo $model->country_type == '国内' || $model->country_type == ''?'checked':''; ?> /> 国内
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="country_type" value="国外" <?php echo $model->country_type == '国外' ? 'checked':''; ?> /> 国外
                                    </label>
                                </div>
                            </div>
                        </div>

                         <div class="form-group">
                            <label class="col-md-3 control-label"><strong>所在省份<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <input type="text" name="province_zh_cn" value="<?php echo $model->province_zh_cn; ?>" class="form-control required">
                                <span class="help-block"></span>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-md-3 control-label"><strong>所在城市<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <input type="text" name="city_zh_cn" value="<?php echo $model->city_zh_cn; ?>" class="form-control required">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>单位主要办公场地</strong></label>
                            <div class="col-md-3">
                                <input type="text" name="company_address" value="<?php echo $model->company_address; ?>" class="form-control">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>其他&nbsp;&nbsp;</strong></label>
                            <div class="col-md-5">
                                <textarea class="form-control" rows="4" name="other" data-rule-maxlength="150"
                                          placeholder="可不填,仅做备注" data-msg-maxlength="说明不要超过100字"><?php echo $model->other; ?></textarea>
                            </div>
                            <span class="help-inline">尽量控制在100文字以内</span>
                        </div>
                        <div class="form-actions fluid">
                            <div class="col-md-offset-3 col-md-6">
                                <button class="btn blue" type="button" id="savecompany"><i class="icon-save">&nbsp;&nbsp;</i>保存</button>
                            </div>
                        </div>
                    </div>
                    <br><br><br><br><br><br><br><br>
                </form>
            </div>
            <!-- END SAMPLE FORM PORTLET-->
        </div>
    </div>
</div>
<script src="/js/itm.region.js"></script>
<script>
    $(function(){
        var submitbutton = true;
        $('#company_form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules:{
                code:{
                    required : true
                },
                epp_id:{
                    required : true
                },
                abbreviation:{
                    required:true
                },
                company_name_zh_cn:{
                    required:true
                },
                company_url:{
                    required:true
                },
                province_zh_cn:{
                    required:true
                },
                city_zh_cn:{
                    required:true
                }
            },
            messages: {
                code:{
                    required : '请输入注册商IANA ID'
                },
                epp_id:{
                    required : '请输入EPP ID'
                },
                abbreviation:{
                    required:'请输入单位简称'
                },
                company_name_zh_cn:{
                    required:'请输入单位名称'
                },
                company_url:{
                    required:'请输入单位网址'
                },
                province_zh_cn:{
                    required:'请输入所属省份'
                },
                city_zh_cn:{
                    required:'请输入所属城市'
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
                $('.alert-danger', $('.login-form')).show();
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
        if(submitbutton){
            $('#savecompany').click(function(){
                if($('#company_form').validate().form()){
                    submitbutton = false;
                    $.ajax({
                        type: 'POST',
                        url: '/registrar/savecompany',
                        data: $('#company_form').serialize(),
                        dataType:'json',
                        success: function (r) {
                            if('err'==r.info){
                                layer.msg(r.data,2,-1,function(){ window.location.reload();});
                                submitbutton = true;
                            }else {
                                layer.msg(r.data, 1, 1, function () {
                                    location.href="/user/company";
                                });
                            }
                        },
                        error: function () {
                            //layer.msg('添加失败',2,-1,function(){ window.location.reload();});
                        }
                    });
                }
            });
        }else{
            layer.msg('数据已经提交',2,-1,function(){ window.location.reload();});
        }
        renderRegionSelect();
    });

    /*****渲染国家****/
    function renderRegionSelect(){
        var regions = region.getAllRegions();
        for ( var i in regions) {
            $('#country').append('<option value="'+regions[i].code+'">'
            + regions[i].title + '</option>');
        }
    }
</script>