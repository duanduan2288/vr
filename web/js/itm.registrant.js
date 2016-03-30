/**
 * Created by Administrator on 2014-12-17.
 */
$(function(){
    /***提交表单****/
    $('#company_form').validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",
        rules:{
            company:{
                required : true
            },
            company_no:{
                required : true
            },
            address:{
                required:true
            },
            company_type:{
                required : true
            },
            legal_person:{
                required : true
            },
            business_scope:{
                required : true
            }
        },
        messages: {
            company:{
                required : '请输入公司名称'
            },
            company_no:{
                required : '请输入公司注册号'
            },
            address:{
                required:'请输入公司注册地址'
            },
            company_type:{
                required : '请输入企业类型'
            },
            legal_person:{
                required : '请输入公司法人名称'
            },
            business_scope:{
                required : '请输入公司经营范围'
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
            label.remove();
        },

        errorPlacement: function (error, element) {
            error.insertAfter(element.closest('.col-md-6'));
        },

        submitHandler: function (form) {
            form.submit();
        }


    });
    var saveflag = false;
    if(saveflag==false){
        $('#savecompany').click(function(){
            saveflag = true;
            if($('#company_form').validate().form()){
                var load = layer.load('正在提交，请稍后...');
                $.ajax({
                    type: 'POST',
                    url: '/brandLogo/saveRegistrant',
                    data: $('#company_form').serialize(),
                    dataType:'json',
                    success: function (r) {
                        layer.close(load);
                        saveflag = false;
                        if('err'==r.info){
                            common_layer(r.data,'');
                        }else{
                            var url=encodeURI("/brandLogo/auditThree?id="+ r.data);
                            location.href=url;
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

