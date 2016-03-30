<link href="/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet"/>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-suitcase"></i>
                    <a href="#">代理商支持&nbsp;&nbsp;<i class="icon-angle-right"></i></a>
                    <a href="#">添加客户服务</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                    <a href="javascript:history.go(-1);"><i class="icon-reply"></i>返回</a>&nbsp;&nbsp;
                </div>
            </div>

            <div class="portlet-body form">
                <form role="form" id="myform" class="form-horizontal" enctype="multipart/form-data">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>服务类型<span class="required">*</span></strong></label>
                            <div class="col-md-4">
                                <select name="big_type" class="form-control required">
                                    <option value="">--请选择服务类型--</option>
                                    <option value="咨询">咨询</option>
                                    <option value="投诉">投诉</option>
                                    <option value="调查">调查</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>细分类型<span class="required">*</span></strong></label>
                            <div class="col-md-4">
                                <select name="small_type" class="form-control required">
                                    <option value="">--请选择细分类型--</option>
                                    <option value="产品">产品</option>
                                    <option value="价格">价格</option>
                                    <option value="注册规则">注册规则</option>
                                    <option value="域名使用">域名使用</option>
                                    <option value="域名解析">域名解析</option>
                                    <option value="其他">其他</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>客户类型<span class="required">*</span></strong></label>
                            <div class="col-md-4">
                                <select name="customer_type" class="form-control required">
                                    <option value="">--请选择客户类型--</option>
                                    <option value="终端客户">终端客户</option>
                                    <option value="代理商">代理商</option>
                                    <option value="注册商">注册商</option>
                                    <option value="互易商务">互易商务</option>
                                    <option value="代理咨询">代理咨询</option>
                                    <option value="其他">其他</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>选择代理商<span class="required">*</span></strong></label>
                            <div class="col-md-4" id="select_agent">
                                <div class="radio-list">
                                    <label class="radio-inline">
                                        <input type="radio" name="select_agent" value="1"  checked="checked" /> 已有代理商
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="select_agent" value="2" />自定义代理商
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div id="yiyou_agent">
                            <div class="form-group">
                                <label class="col-md-3 control-label"><strong>请选择代理商<span class="required">*</span></strong></label>
                                <div class="col-md-4">
                                    <select id="agent_name" name="agent_name" class="form-control agent_name required">
                                        <option value="">--请选择代理商--</option>
                                        <?php if(!empty($agents)):?>
                                            <?php foreach($agents as $v):?>
                                                <?php if ($flag): ?>
                                                    <?php if (in_array($v['id'], $agent_ids)): ?>
                                                        <option value="<?php echo $v['company_name'] ?>"><?php echo $v['company_name']; ?></option>
                                                    <?php endif ?>
                                                <?php else: ?>
                                                        <option value="<?php echo $v['company_name'] ?>"><?php echo $v['company_name']; ?></option>
                                                <?php endif ?>
                                                <!-- <option value="<?php //echo $v['company_name'];?>"><?php //echo $v['company_name'];?></option> -->
                                        <?php endforeach;endif;?>
                                    </select>
                                </div>
                            </div>
                            <div id="three" style="display: none;">
                            </div>
                        </div>
                        <div id="zidingyi_agent" style="display: none;">
                            <div class="form-group">
                                <label class="col-md-3 control-label"><strong>自定义代理商<span class="required">*</span></strong></label>
                                <div class="col-md-4">
                                    <input type="text" name="" class="form-control agent_name required" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><strong>地区<span class="required">*</span></strong></label>
                                <div class="col-md-4">
                                    <select name="" class="form-control diqu required">
                                        <option value="">--请选择地区--</option>
                                        <option value="华南">华南</option>
                                        <option value="华北">华北</option>
                                        <option value="华中">华中</option>
                                        <option value="华东">华东</option>
                                        <option value="西部">西部</option>
                                        <option value="广东省">广东省</option>
                                        <option value="香港">香港</option>
                                        <option value="海外">海外</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><strong>联系人<span class="required">*</span></strong></label>
                                <div class="col-md-4">
                                    <input type="text" name="" class="form-control contact required" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><strong>联系电话<span class="required">*</span></strong></label>
                                <div class="col-md-4">
                                    <input type="text" name="" class="form-control cellphone required"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>标题<span class="required">*</span></strong></label>
                            <div class="col-md-4">
                                <input type="text" name="name" class="form-control required"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>备注<span class="required">*</span></strong></label>
                            <div class="col-md-4">
                                <textarea class="form-control required" rows="10" name="content" data-rule-maxlength="500" placeholder="请填写备注信息" data-msg-maxlength="备注不要超过500字"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong><?php echo Yii::t('Base','附件');?>&nbsp;&nbsp;</strong></label>
                            <div class="col-md-4">
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
                            <div class="col-md-4" id="attachment">
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
        $('#yiyou_agent').on('change','#agent_name',function(){
            var name = $(this).val();
            if (name=='') {
                $('#three').empty().hide();
                return;
            }
            $.ajax({
                type: 'POST',
                url: '/issue/getAgent',
                data: {'name':name},
                dataType:'json',
                success: function (json) {
                    if('ok'!=json.info){
                        common_layer(json.data);
                    }else{
                        var data = json.data;
                        var str = '';
                        str += '<div class="form-group"><label class="col-md-3 control-label"><strong>地区<span class="required">*</span></strong></label><div class="col-md-4"><input readonly type="text" name="diqu" value="'+data['company_area']+'" class="form-control diqu required" /></div></div><div class="form-group"><label class="col-md-3 control-label"><strong>联系人<span class="required">*</span></strong></label><div class="col-md-4"><input readonly type="text" name="contact" value="'+data['manager_name']+'" class="form-control contact required" /></div></div><div class="form-group"><label class="col-md-3 control-label"><strong>联系电话<span class="required">*</span></strong></label><div class="col-md-4"><input readonly type="text" name="cellphone" value="'+data['manager_mobile']+'" class="form-control cellphone required"/></div></div>';
                        $('#three').html(str).show();
                    }
                },
                error: function () {
                    common_layer('获取公司信息失败');
                }
            });
        });
        $('#select_agent').on('click','input:radio[name=select_agent]',function(){
            var str = $(this).val();
            if (str == '1') {
                $('#yiyou_agent .agent_name').attr('name', 'agent_name');
                $('#yiyou_agent .diqu').attr('name', 'diqu');
                $('#yiyou_agent .contact').attr('name', 'contact');
                $('#yiyou_agent .cellphone').attr('name', 'cellphone');
                $('#zidingyi_agent .agent_name').attr('name', '');
                $('#zidingyi_agent .diqu').attr('name', '');
                $('#zidingyi_agent .contact').attr('name', '');
                $('#zidingyi_agent .cellphone').attr('name', '');
                $('#yiyou_agent').show();
                $('#zidingyi_agent').hide();
                $('#yiyou_agent .agent_name').val('');
                $('#three').empty().hide();
            }else{
                $('#zidingyi_agent .agent_name').attr('name', 'agent_name');
                $('#zidingyi_agent .diqu').attr('name', 'diqu');
                $('#zidingyi_agent .contact').attr('name', 'contact');
                $('#zidingyi_agent .cellphone').attr('name', 'cellphone');
                $('#yiyou_agent .agent_name').attr('name', '');
                $('#yiyou_agent .diqu').attr('name', '');
                $('#yiyou_agent .contact').attr('name', '');
                $('#yiyou_agent .cellphone').attr('name', '');
                $('#zidingyi_agent').show();
                $('#yiyou_agent').hide();
                $('#yiyou_agent .agent_name').val('');
                $('#three').empty().hide();
            }
        });
        $('#myform').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                big_type: {
                    required: true
                },
                small_type:{
                    required:true
                },
                select_agent: {
                    required: true
                },
                agent_name:{
                    required:true
                },
                diqu: {
                    required: true
                },
                contact:{
                    required:true
                },
                cellphone: {
                    required: true
                },
                // neirong:{
                //     required:true
                // },
                // genjin: {
                //     required: true
                // },
                name: {
                    required: true
                },
                content: {
                    required: true
                }
            },

            messages: {
                big_type: {
                    required: "请选择服务类型"
                },
                small_type:{
                    required: "请选择细分类型"
                },
                customer_type:{
                    required: "请选择客户类型"
                },
                select_agent: {
                    required: "请选择代理商"
                },
                agent_name:{
                    required: "请选择代理商"
                },
                diqu: {
                    required: "请选择地区"
                },
                contact:{
                    required: "请输入联系人"
                },
                cellphone: {
                    required: "请输入联系电话"
                },
                name: {
                    required: "请输入标题"
                },
                content: {
                    required: "请输入内容"
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
                        url: '/issue/createServiceIssue',
                        data: $('#myform').serialize(),
                        dataType:'json',
                        success: function (r) {
                            layer.close(load);
                            saveflag = false;
                            if('err'==r.info){
                                common_layer(r.data,'');
                            }else{
                                // var url=encodeURI("/domainRegister/register?domain="+ r.data);
                                common_layer(r.data,'/issue/serviceIssue');
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
