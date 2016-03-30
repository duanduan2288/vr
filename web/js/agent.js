/**
 * Created by Administrator on 2015-01-15.
 */
$(function(){
    $('.synchronous').click(function(event) {
        var str = $(this).attr('data-id');
        var name = $("input[name='manager_name']").val();
        var position = $("input[name='manager_position']").val();
        var office_address = $("input[name='manager_office_address']").val();
        var office_telephone = $("input[name='manager_office_telephone']").val();
        var mobile = $("input[name='manager_mobile']").val();
        var email = $("input[name='manager_email']").val();
        if (name==''||position==''||office_address==''||office_telephone==''||mobile==''||email=='') return;
        $("input[name='"+str+"_name']").val(name);
        $("input[name='"+str+"_position']").val(position);
        $("input[name='"+str+"_office_address']").val(office_address);
        $("input[name='"+str+"_office_telephone']").val(office_telephone);
        $("input[name='"+str+"_mobile']").val(mobile);
        $("input[name='"+str+"_email']").val(email);
    });
    /**主营销售模式默认**/
    if ($('input:radio[name=company_main_sales_model]:checked').attr('data-val')=='qita') {
        $('#sales_model_box_desc input').attr('name', 'company_main_sales_model_description');
        $('#sales_model_box_desc').show();
    }
    /**是否有分公司默认**/
    if ($('input:radio[name=company_has_branch]:checked').attr('data-val')=='yes') {
        $('#branch_box_desc').show();
        $('#branch_box_add').show();
    }
    /**是否有其他知识产权相关从业资质默认**/
    if ($('input:radio[name=company_other_ip]:checked').attr('data-val')=='yes') {
        $('#company_other_ip_box_desc input').attr('name', 'company_other_ip_description');
        $('#company_other_ip_box_desc').show();
    }
    /**证件类型默认**/
    if ($('input:radio[name=applicant_code_type]:checked').attr('data-val')=='business') {
        $('#applicant_business input').attr('name', 'applicant_business_license_code');
        $('#applicant_business').show();
        $('#applicant_organization input').attr('name', '');
        $('#applicant_organization').hide();
    }else if($('input:radio[name=applicant_code_type]:checked').attr('data-val')=='organization'){
        $('#applicant_organization input').attr('name', 'applicant_organization_code');
        $('#applicant_organization').show();
        $('#applicant_business input').attr('name', '');
        $('#applicant_business').hide();
    }
    /**公司拟采用的销售类型默认**/
    var  sales_type = $('input:radio[name=company_other_ip]:checked').attr('data-val');
    if (sales_type == 'zhixiao' || sales_type == 'dianxiao') {
        $('#company_sales_type_box_desc1 input').attr('name', 'company_sales_type_description');
        $('#company_sales_type_box_desc2 input').attr('name', '');
        $('#company_sales_type_box_desc3 input').attr('name', '');
        $('#company_sales_type_box_desc1').show();
        $('#company_sales_type_box_desc2').hide();
        $('#company_sales_type_box_desc3').hide();
    }else if(sales_type == 'webxiao'){
        $('#company_sales_type_box_desc1 input').attr('name', '');
        $('#company_sales_type_box_desc2 input').attr('name', 'company_sales_type_description');
        $('#company_sales_type_box_desc3 input').attr('name', '');
        $('#company_sales_type_box_desc1').hide();
        $('#company_sales_type_box_desc2').show();
        $('#company_sales_type_box_desc3').hide();
    }else if(sales_type == 'qita'){
        $('#company_sales_type_box_desc1 input').attr('name', '');
        $('#company_sales_type_box_desc2 input').attr('name', '');
        $('#company_sales_type_box_desc3 input').attr('name', 'company_sales_type_description');
        $('#company_sales_type_box_desc1').hide();
        $('#company_sales_type_box_desc2').hide();
        $('#company_sales_type_box_desc3').show();
    }
    /**公司是否会进行“.商标”域名审核默认**/
    if ($('input:radio[name=company_is_domain_audit]:checked').attr('data-val')=='yes') {
        $('#company_is_domain_audit_box_desc input').attr('name', 'company_domain_audit_number');
        $('#company_is_domain_audit_box_desc').show();
    }
    /**公司拟采用注册商提供的平台手工注册或进行二次开发后再网站上提供默认**/
    if ($('input:radio[name=company_use_type]:checked').attr('data-val')=='shougong') {
        $('#company_use_type_box_desc1 input').attr('name', 'company_shougong_number');
        $('#company_use_type_box_desc2 input').attr('name', '');
        $('#company_use_type_box_desc1').show();
        $('#company_use_type_box_desc2').hide();
    }else{
        $('#company_use_type_box_desc1 input').attr('name', '');
        $('#company_use_type_box_desc2 input').attr('name', 'company_kaifa_number');
        $('#company_use_type_box_desc1').hide();
        $('#company_use_type_box_desc2').show();
    }


    /**主营销售模式**/
    $('#sales_model_box').on('click','input:radio[name=company_main_sales_model]',function(){
        var str = $(this).attr('data-val');
        if (str == 'qita') {
            $('#sales_model_box_desc input').attr('name', 'company_main_sales_model_description');
            $('#sales_model_box_desc').show();
        }else{
            $('#sales_model_box_desc input').attr('name', '');
            $('#sales_model_box_desc').hide();
        }
    });
    /**是否有分公司**/
    $('#branch_box').on('click','input:radio[name=company_has_branch]',function(){
        var str = $(this).attr('data-val');
        if (str == 'yes') {
            $('#branch_box_desc').show();
            $('#branch_box_add').show();
        }else{
            $('#branch_box_desc').hide();
            $('#branch_box_add').hide();
        }
    });
    $('.form-group').on('click','#branch_box_add',function(){
        var html = '<div class="form-group branch_box"><label class="col-md-3 control-label"></label><div class="col-md-1"><input placeholder="分公司电话" type="text" name="branch_phone[]" value="" class="form-control branch_phone" ></div><div class="col-md-3"><input placeholder="分公司地址" type="text" name="branch_address[]" value="" class="form-control branch_address" ></div><div class="col-md-1"><input type="button" class="btn red branch_del" value="删除" /></div></div>';
        $('#branch_box_desc').append(html);
    });
    $('#branch_box_desc').on('click','.branch_del',function(){
        $(this).closest('.form-group').remove();
        if ($('#branch_box_desc .form-group').length == 0) {
            $('#branch_box_desc').append('<div class="form-group branch_box"><label class="col-md-3 control-label"></label><div class="col-md-1"><input placeholder="分公司电话" type="text" name="branch_phone[]" value="" class="form-control branch_phone" ></div><div class="col-md-3"><input placeholder="分公司地址" type="text" name="branch_address[]" value="" class="form-control branch_address" ></div></div>');
        }
    });

    /**是否有其他知识产权相关从业资质**/
    $('#company_other_ip_box').on('click','input:radio[name=company_other_ip]',function(){
        var str = $(this).attr('data-val');
        if (str == 'yes') {
            $('#company_other_ip_box_desc input').attr('name', 'company_other_ip_description');
            $('#company_other_ip_box_desc').show();
        }else{
            $('#company_other_ip_box_desc input').attr('name', '');
            $('#company_other_ip_box_desc').hide();
        }
    });

    /**证件类型**/
    $('#applicant_code_type_box').on('click','input:radio[name=applicant_code_type]',function(){
        var str = $(this).attr('data-val');
        if (str == 'business') {
            $('#applicant_business input').attr('name', 'applicant_business_license_code');
            $('#applicant_business').show();
            $('#applicant_organization input').attr('name', '');
            $('#applicant_organization').hide();
        }else{
            $('#applicant_organization input').attr('name', 'applicant_organization_code');
            $('#applicant_organization').show();
            $('#applicant_business input').attr('name', '');
            $('#applicant_business').hide();
        }
    });

    /**公司拟采用的销售类型**/
    $('#company_sales_type_box').on('click','input:radio[name=company_sales_type]',function(){
        var str = $(this).attr('data-val');
        if (str == 'zhixiao' || str == 'dianxiao') {
            $('#company_sales_type_box_desc1 input').attr('name', 'company_sales_type_description');
            $('#company_sales_type_box_desc2 input').attr('name', '');
            $('#company_sales_type_box_desc3 input').attr('name', '');
            $('#company_sales_type_box_desc1').show();
            $('#company_sales_type_box_desc2').hide();
            $('#company_sales_type_box_desc3').hide();
        }else if(str == 'webxiao'){
            $('#company_sales_type_box_desc1 input').attr('name', '');
            $('#company_sales_type_box_desc2 input').attr('name', 'company_sales_type_description');
            $('#company_sales_type_box_desc3 input').attr('name', '');
            $('#company_sales_type_box_desc1').hide();
            $('#company_sales_type_box_desc2').show();
            $('#company_sales_type_box_desc3').hide();
        }else if(str == 'qita'){
            $('#company_sales_type_box_desc1 input').attr('name', '');
            $('#company_sales_type_box_desc2 input').attr('name', '');
            $('#company_sales_type_box_desc3 input').attr('name', 'company_sales_type_description');
            $('#company_sales_type_box_desc1').hide();
            $('#company_sales_type_box_desc2').hide();
            $('#company_sales_type_box_desc3').show();
        }else{
            $('#company_sales_type_box_desc1 input').attr('name', '');
            $('#company_sales_type_box_desc2 input').attr('name', '');
            $('#company_sales_type_box_desc3 input').attr('name', '');
            $('#company_sales_type_box_desc1').hide();
            $('#company_sales_type_box_desc2').hide();
            $('#company_sales_type_box_desc3').hide();
        }
    });

    /**公司是否会进行“.商标”域名审核**/
    $('#company_is_domain_audit_box').on('click','input:radio[name=company_is_domain_audit]',function(){
        var str = $(this).attr('data-val');
        if (str == 'yes') {
            $('#company_is_domain_audit_box_desc input').attr('name', 'company_domain_audit_number');
            $('#company_is_domain_audit_box_desc').show();
        }else{
            $('#company_is_domain_audit_box_desc input').attr('name', '');
            $('#company_is_domain_audit_box_desc').hide();
        }
    });

    /**公司拟采用注册商提供的平台手工注册或进行二次开发后再网站上提供**/
    $('#company_use_type_box').on('click','input:radio[name=company_use_type]',function(){
        var str = $(this).attr('data-val');
        if (str == 'shougong') {
            $('#company_use_type_box_desc1 input').attr('name', 'company_shougong_number');
            $('#company_use_type_box_desc2 input').attr('name', '');
            $('#company_use_type_box_desc1').show();
            $('#company_use_type_box_desc2').hide();
        }else{
            $('#company_use_type_box_desc1 input').attr('name', '');
            $('#company_use_type_box_desc2 input').attr('name', 'company_kaifa_number');
            $('#company_use_type_box_desc1').hide();
            $('#company_use_type_box_desc2').show();
        }
    });

    /****有效期显示****/
    if (!jQuery().datepicker) {
        return;
    }
    $('.date').datepicker({
            rtl: App.isRTL(),
            autoclose: true,
            endDate: maxdate
        }
    );

    //附件删除
    $('.fileupload').on('click','.red',function(e){
        $(this).siblings('.hidden_value').val('');
        $(this).siblings('.btn-file').find('.fileupload-new').show();
        $(this).siblings('.btn-file').find('.fileupload-exists').hide();
        $(this).siblings('.input-group-btn').find('.icon-file').addClass('fileupload-exists');
        $(this).siblings('.input-group-btn').find('.fileupload-preview').html('');
        $(this).hide();
    });

    /***电话号***/
    jQuery.validator.addMethod("checktelephone", function(value, element) {
        return this.optional(element) || /^[0-9-.+]+$/.test(value);
    }, "请正确输入电话号码");
    /***手机号***/
    jQuery.validator.addMethod("checkmobile", function(value, element) {
        return this.optional(element) || /^1[0-9]{10}$/.test(value);
    }, "请正确输入手机号");

    /***提交表单****/
    $('#agent_form').validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",
        rules:{
            registrar_id : {
                required : true
            },
            company_name : {
                required : true
            },
            company_set_up_time : {
                required : true
            },
            company_registered_capital : {
                required : true
            },
            //company_type : {
            //    required : true
            //},
            // company_management_personnel_number : {
            //     required : true
            // },
            // company_sale_personnel_number : {
            //     required : true
            // },
            // company_service_personnel_number : {
            //     required : true
            // },
            // company_market_personnel_number : {
            //     required : true
            // },
            // company_technical_personnel_number : {
            //     required : true
            // },
            // company_executives_personnel_number : {
            //     required : true
            // },
            // company_finance_personnel_number : {
            //     required : true
            // },
            company_total_number : {
                required : true
            },
            company_main_business : {
                required : true
            },
            company_main_sales_model : {
                required : true
            },
            company_main_sales_model_description : {
                required : true
            },
            company_has_branch : {
                required : true
            },
            company_is_trademark_agency : {
                required : true
            },
            company_other_ip : {
                required : true
            },
            company_other_ip_description : {
                required : true
            },
            //applicant_name : {
            //    required : true
            //},
            applicant_code_type : {
                required : true
            },
            applicant_business_license_code : {
                required : true
            },
            applicant_organization_code : {
                required : true
            },
            //applicant_company : {
            //    required : true
            //},
            applicant_url : {
                required : true
            },
            applicant_address : {
                required : true
            },
            applicant_telephone : {
                required : true,
                checktelephone:true
            },
            manager_name : {
                required : true
            },
            manager_position : {
                required : true
            },
            manager_office_address : {
                required : true
            },
            manager_office_telephone : {
                required : true,
                checktelephone:true
            },
            manager_mobile : {
                required : true,
                checkmobile:true
            },
            manager_email : {
                required : true,
                email:true
            },
            apply_name : {
                required : true
            },
            apply_position : {
                required : true
            },
            apply_office_address : {
                required : true
            },
            apply_office_telephone : {
                required : true,
                checktelephone:true
            },
            apply_mobile : {
                required : true,
                checkmobile:true
            },
            apply_email : {
                required : true,
                email:true
            },
            finance_name : {
                required : true
            },
            finance_position : {
                required : true
            },
            finance_office_address : {
                required : true
            },
            finance_office_telephone : {
                required : true,
                checktelephone:true
            },
            finance_mobile : {
                required : true,
                checkmobile:true
            },
            finance_email : {
                required : true,
                email:true
            },
            company_customer_number : {
                required : true,
                number : true
            },
            company_sales_target : {
                required : true,
                number : true
            },
            company_sales_type : {
                required : true
            },
            company_sales_type_description : {
                required : true
            },
            company_is_domain_audit : {
                required : true
            },
            company_domain_audit_number : {
                required : true,
                number : true
            },
            company_domain_service_number : {
                required : true,
                number : true
            },
            company_use_type : {
                required : true
            },
            company_business_license : {
                required : true
            },
            company_legal_person_id : {
                required : true
            },
            // company_agreement_file : {
            //     required : true
            // },
            // company_payment_proof : {
            //     required : true
            // },
            contract_main: {
                required : true
            },
            company_area: {
                required : true
            },
            reseller_group: {
                required : true
            },
            belong_group: {
                required : true
            },
            contract_head: {
                required : true
            },
            belong_manager: {
                required : true
            },
            company_con_version: {
                required : true
            },
            // company_caution_money: {
            //     required : true
            // },
            register_price: {
                required : true
            },
            renew_price: {
                required : true
            },
            change_price: {
                required : true
            },
            redeem_price: {
                required : true
            },
            contract_price: {
                required : true
            },
            return_condition: {
                required : true
            },
            registrar_epp_id: {
                required : true
            },
            registrar_webservice_id: {
                required : true
            },
            registrar_tmdb_id: {
                required : true
            },
            company_province: {
                required : true
            },
            // company_city: {
            //     required : true
            // },
            company_genre: {
                required : true
            }
        },
        messages: {
            registrar_id : {
                required: '请选择所属注册商'
            },
            company_name: {
                required: '请输入公司名称'
            },
            company_set_up_time : {
                required : '请选择公司成立时间'
            },
            company_registered_capital : {
                required : '请输入注册资本'
            },
            company_type : {
                required : '请输入单位类型'
            },
            company_total_number : {
                required : '请选择人员规模'
            },
            company_main_business : {
                required : '请输入主营业务'
            },
            company_main_sales_model : {
                required : '请选择主营销售模式'
            },
            company_main_sales_model_description : {
                required : '请输入主营销售模式的描述'
            },
            company_has_branch : {
                required : '请选择是否有分公司'
            },
            company_is_trademark_agency : {
                required : '请选择是否具备国家商标局认定的商标代理机构资质'
            },
            company_other_ip : {
                required : '请选择是否有其他知识产权相关从业资质'
            },
            company_other_ip_description : {
                required : '请输入是否有其他知识产权相关从业资质的描述'
            },
            //applicant_name : {
            //    required : '请输入代理商的法人名称'
            //},
            applicant_code_type : {
                required : '请选择证件类型'
            },
            applicant_business_license_code : {
                required : '请输入工商营业执照号码'
            },
            applicant_organization_code : {
                required : '请输入组织机构代码证号码'
            },
            //applicant_company : {
            //    required : '请输入代理商的企业名称和商号'
            //},
            applicant_url : {
                required : '请输入代理商的公司网站'
            },
            applicant_address : {
                required : '请输入代理商的办公地址'
            },
            applicant_telephone : {
                required : '请输入代理商的办公电话',
                checktelephone:'请正确输入电话号码'
            },
            manager_name : {
                required : '请输入负责人姓名'
            },
            manager_position : {
                required : '请输入负责人职务'
            },
            manager_office_address : {
                required : '请输入负责人办公地址'
            },
            manager_office_telephone : {
                required : '请输入负责人办公电话',
                checktelephone:'请正确输入电话号码'
            },
            manager_mobile : {
                required : '请输入负责人手机',
                checkmobile:'请正确输入手机号'
            },
            manager_email : {
                required : '请输入负责人email',
                email:'请正确输入电子邮箱'
            },
            apply_name : {
                required : '请输入负责联系人姓名'
            },
            apply_position : {
                required : '请输入负责联系人职务'
            },
            apply_office_address : {
                required : '请输入负责联系人办公地址'
            },
            apply_office_telephone : {
                required : '请输入负责联系人办公电话',
                checktelephone:'请正确输入电话号码'
            },
            apply_mobile : {
                required : '请输入负责联系人手机',
                checkmobile:'请正确输入手机号'
            },
            apply_email : {
                required : '请输入负责联系人email',
                email:'请正确输入电子邮箱'
            },
            finance_name : {
                required : '请输入财务主管姓名'
            },
            finance_position : {
                required : '请输入财务主管职务'
            },
            finance_office_address : {
                required : '请输入财务主管办公地址'
            },
            finance_office_telephone : {
                required : '请输入财务主管办公电话',
                checktelephone:'请正确输入电话号码'
            },
            finance_mobile : {
                required : '请输入财务主管手机',
                checkmobile:'请正确输入手机号'
            },
            finance_email : {
                required : '请输入财务主管email',
                email:'请正确输入电子邮箱'
            },
            company_customer_number : {
                required : '请输入公司潜在目标客户（商标持有人）数量',
                number:'请输入数字'
            },
            company_sales_target : {
                required : '请输入公司月度销售目标',
                number:'请输入数字'
            },
            company_sales_type : {
                required : '请选择公司拟采用的销售类型'
            },
            company_sales_type_description : {
                required : '请输入公司拟采用的销售类型描述',
                checknumber:'请输入数字'
            },
            company_is_domain_audit : {
                required : '请选择公司是否会进行“.商标”域名审核'
            },
            company_domain_audit_number : {
                required : '请输入公司配备的“.商标”域名审核人员数量',
                number:'请输入数字'
            },
            company_domain_service_number : {
                required : '公司拟配备的针对“.商标”域名的客服人员数量',
                number:'请输入数字'
            },
            company_use_type : {
                required : '请选择注册系统类型'
            },
            company_business_license : {
                required : '请上传公司营业执照复印件/组织机构代码证（加盖公司公章）'
            },
            company_legal_person_id : {
                required : '请上传法人身份证复印件'
            },
            // company_agreement_file : {
            //     required : '请上传与注册商签订的协议复印件'
            // },
            // company_payment_proof : {
            //     required : '请上传代理商风险保证金的缴纳证明'
            // },
            contract_main: {
                required : '请选择签约主体'
            },
            company_area: {
                required : '请选择地区'
            },
            reseller_group: {
                required : '请选择代理商组 ( 报表权限 )'
            },
            belong_group: {
                required : '请选择代理商组 ( 渠道权限 )'
            },
            contract_head: {
                required : '请选择渠道开发人'
            },
            belong_manager: {
                required : '请选择渠道经理'
            },
            company_con_version: {
                required : '请选择合同版本'
            },
            // company_caution_money: {
            //     required : '请输入保证金'
            // },
            register_price: {
                required : '请输入注册单价'
            },
            renew_price: {
                required : '请输入续费单价'
            },
            change_price: {
                required : '请输入转移单价'
            },
            redeem_price: {
                required : '请输入赎回单价'
            },
            contract_price: {
                required : '请输入合同结算单价'
            },
            return_condition: {
                required : '请输入返款条件'
            },
            registrar_epp_id: {
                required : '请选择EPP账号'
            },
            registrar_webservice_id: {
                required : '请选择Webservice账号'
            },
            registrar_tmdb_id: {
                required : '请选择TMDB账号'
            },
            company_province: {
                required : '请输入所属省份'
            },
            // company_city: {
            //     required : '请输入所属城市'
            // },
            company_genre: {
                required : '请选择代理商类型'
            }
        },
        invalidHandler: function (event, validator) { //display error alert on form submit
            $('.alert-danger', $('.login-form')).show();
        },

        highlight: function (element) { // hightlight error inputs
            $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            var eclass = $(element).attr('class');
            if(eclass=='hidden_value'){
                $(element).siblings('.input-group-btn').find('.uneditable-input').css({border: "1px solid #b94a48"});
            }
        },

        success: function (label) {
            label.closest('.form-group').removeClass('has-error');
            label.siblings('.col-md-5').find('.uneditable-input').css({border: "1px solid #e5e5e5"});
            label.remove();
        },

        errorPlacement: function (error, element) {
            error.insertAfter(element.closest('.col-md-5'));
        },

        submitHandler: function (form) {
            form.submit();
        }
    });
    var saveflag = false;
    if(saveflag==false){
        $('#saveagent').click(function(){
            saveflag = true;
            var flag = true;
            var branch = $('input:radio[name=company_has_branch]:checked').val();
            if (branch == '有') {
                $('#branch_box_desc .branch_box').each(function(index, el) {
                    var phone = $(this).find('.branch_phone').val();
                    var address = $(this).find('.branch_address').val();
                    if (phone == '' || address == '') {
                        flag = false;
                    }
                })
            }
            if (!flag) {
                common_layer('请输入完整的分公司信息');
                return;
            }
            if($('#agent_form').validate().form() && flag){
                var load = layer.load('正在提交，请稍后~~');
                $.ajax({
                    type: 'POST',
                    url: '/agent/create',
                    data: $('#agent_form').serialize(),
                    dataType:'json',
                    success: function (json) {
                        layer.close(load);
                        saveflag = false;
                        if('err'==json.info){
                            common_layer(json.data);
                        }else{
                            common_layer(json.data,'/agent/index');
                        }
                    },
                    error: function () {
                        layer.close(load);
                        saveflag = false;
                        common_layer('保存失败');
                    }
                });
            }else{
                common_layer('表单验证失败，请检查输入');
            }
        });
    }else{
        common_layer('数据已提交');
    }
});
//上传附件
function ajaxFileUpload(obj) {

    var fileName = $(obj).attr('id');
    var uploadInfo = $(obj).parent();
    var load = layer.load('正在上传');
    $.ajaxFileUpload
    (
        {
            url: '/upload/uploadfile',
            secureuri: false,
            fileElementId: fileName,
            dataType: 'json',
            data: {
                'file_name': fileName,
                'type':'bmp,jpeg,jpg,png',
                'minsize':6
            },
            success: function (data, status) {
                layer.close(load);
                if (typeof(data.error) != 'undefined' && data.error != '') {
                    common_layer(data.error);
                } else {
                    /***给隐藏域赋值***/
                    uploadInfo.siblings('.hidden_value').val(data.guid);
                    //删除之前错误提示
                    uploadInfo.siblings('.input-group-btn').find('.uneditable-input').css({border: "1px solid #e5e5e5"});
                    uploadInfo.closest('.form-group').removeClass('has-error');
                    uploadInfo.closest('.form-group').find('.help-block').hide();
                    uploadInfo.closest('.form-group').find('.business_license_help').hide();
                    /***输入框显示文件名***/
                    uploadInfo.siblings('.input-group-btn').find('.icon-file').removeClass('fileupload-exists');
                    uploadInfo.siblings('.input-group-btn').find('.fileupload-preview').html(data.name);
                    /****remove change 替换***/
                    uploadInfo.find('.fileupload-new').hide();
                    uploadInfo.find('.fileupload-exists').show();
                    uploadInfo.siblings('.fileupload-exists').show();

                }
            },
            error: function (data, status, e) {
                layer.close(load);
                common_layer('上传失败，请稍后重试');
            }
        }
    );
    return false;
}

