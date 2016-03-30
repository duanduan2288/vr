/**
 * Created by Administrator on 2014-12-17.
 */
function process_all($this){
    var checked = $this.attr('checked');
    $this.closest('.col-md-6').find('.checkbox-inline').find('.category').each(function(){
        if(checked){
            $(this).attr("checked",checked);
            $(this).closest('span').addClass('checked');
            $(this).closest('.form-group').removeClass('has-error');
            $(this).siblings('.trademark_category_help').remove();
        }else{
            $(this).attr("checked",false);
            $(this).closest('span').removeClass('checked');
        }
    });
}

function process_one(id,$this){
    var $subs = id.find("input[name='category[]']");
    if($this.attr('checked')){
        $this.closest('.form-group').removeClass('has-error');
        $this.siblings('.trademark_category_help').remove();
    }
    var checked = $subs.length == $subs.filter(":checked").length ? true :false;
    $this.closest('.form-group').find(".checkAll").attr("checked" ,checked);
    if(checked){
        $this.closest('.form-group').find(".checkAll").closest('span').addClass('checked');
    }else{
        $this.closest('.form-group').find(".checkAll").closest('span').removeClass('checked');
    }
}

function process_org(id,$this){
    var value = $this.val();
    if(value=='组织机构代码证'){
        id.find('.organization_number').empty().html('组织机构代码证编号');
        id.find('.business_license_file').empty().html('组织机构代码证复印件');
        id.find('.organization_number').closest('.control-label').siblings('.help-inline').show();
        $this.closest('.col-md-6').siblings('.help-inline').show();
        id.find('.organization_number').closest('.form-group').removeClass('has-error');
        id.find('.organization_number').closest('.control-label').siblings('.help-block').remove();
    }else{
        id.find('.organization_number').empty().html('营业执照编号');
        id.find('.organization_number').closest('.control-label').siblings('.help-inline').hide();
        $this.closest('.col-md-6').siblings('.help-inline').hide();
        id.find('.organization_number').closest('.form-group').removeClass('has-error');
        id.find('.organization_number').closest('.control-label').siblings('.help-block').remove();
        id.find('.business_license_file').empty().html('营业执照复印件');
    }
}

$(function(){
    /**显示所有分类**/
    $('#company_form').on('click','.morecategoty',function(){
        $(this).closest('ul').siblings('.more_category').show();
        $(this).closest('li').remove();
    });
    $('#create_trademark').on('click','.morecategoty',function(){
        $(this).closest('ul').siblings('.more_category').show();
        $(this).closest('li').remove();
    });
    $('#show_trademark').on('click','.morecategoty',function(){
        $(this).closest('ul').siblings('.more_category').show();
        $(this).closest('li').remove();
    });
    /**
     * 全选
     */
    $('#company_form').on('click','.checkAll',function() {

        process_all($(this));
    });
    $('#create_trademark').on('click','.checkAll',function() {
        process_all($(this));
    });
    $('#show_trademark').on('click','.checkAll',function() {
        process_all($(this));
    });


    /***单选**/
    $('#company_form').on('click','.category',function(){
        process_one($('#company_form'),$(this));
    });
    /***单选**/
    $('#create_trademark').on('click','.category',function(){
        process_one($('#create_trademark'),$(this));
    });
    /***单选**/
    $('#show_trademark').on('click','.category',function(){
        process_one($('#show_trademark'),$(this));
    });

    /***根据根据组织证件类型选标题****/
    $('#company_form').on('change','.organization',function(e){
        process_org($('#company_form'),$(this));
    });

    /***根据根据组织证件类型选标题****/
    $('#create_trademark').on('change','.organization',function(){
        process_org($('#create_trademark'),$(this));
    });
    /***根据根据组织证件类型选标题****/
    $('#show_trademark').on('change','.organization',function(){
        process_org($('#show_trademark'),$(this));
    });
    /****有效期显示****/
    if (!jQuery().datepicker) {
        return;
    }
    $('.date').datepicker({
            rtl: App.isRTL(),
            autoclose: true
        }
    );
    $("#to").inputmask("y-m-d", {
        mask: true
    });
    $("#from").inputmask("y-m-d", {
        mask: true
    });
    $("#otherto").inputmask("y-m-d", {
        mask: true
    });
    $("#otherfrom").inputmask("y-m-d", {
        mask: true
    });
    $("#otherrenew_date").inputmask("y-m-d", {
        mask: true
    });
    $("#renew_date").inputmask("y-m-d", {
        mask: true
    });
    $("#showto").inputmask("y-m-d", {
        mask: true
    });
    $("#showfrom").inputmask("y-m-d", {
        mask: true
    });
    $("#showrenew_date").inputmask("y-m-d", {
        mask: true
    });
    //渲染颁发国
    renderRegionSelect();

    //选择分类


    //附件删除
    $('.fileupload').on('click','.red',function(e){
        $(this).siblings('.hidden_value').val('');
        $(this).siblings('.btn-file').find('.fileupload-new').show();
        $(this).siblings('.btn-file').find('.fileupload-exists').hide();
        $(this).siblings('.input-group-btn').find('.icon-file').addClass('fileupload-exists');
        $(this).siblings('.input-group-btn').find('.fileupload-preview').html('');
        $(this).siblings('.green').hide();
        $(this).hide();
    });

    //组织机构代码证编号
    jQuery.validator.addMethod("checkorglength", function(value, element) {
        var type = $('#organization_type').val();
        if(type=='组织机构代码证'){
            if(value.replace(/(^\s*)|(\s*$)/g, "")==''){
                return false;
            }
            return /^[0-9]{9,10}$/.test(value);
        }else{
            return true;
        }
    }, "长度为9-10位");
    //营业执照编号
    jQuery.validator.addMethod("checkbussinesslength", function(value, element) {
        var type = $('#organization_type').val();
        if(type=='营业执照'){
            if(value.replace(/(^\s*)|(\s*$)/g, "")==''){
                return false;
            }
            return /^[0-9]{15}$/.test(value) || /^[0-9]{13}$/.test(value);
        }else{
           return true;
        }
    }, "长度为13-15位");

    //营业执照
    jQuery.validator.addMethod("checkbussiness", function(value, element) {
        var type = $('#organization_type').val();
        var flag = $('#bussiness').attr('checked');
        if(type=='营业执照'  && flag){
            if(value.replace(/(^\s*)|(\s*$)/g, "")==''){
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }
    }, "请上传营业执照复印件");

    //组织机构代码证
    jQuery.validator.addMethod("checkorgcopy", function(value, element) {
        var type = $('#organization_type').val();
        var flag = $('#bussiness').attr('checked');
        if(type=='组织机构代码证'  && flag){
            if(value.replace(/(^\s*)|(\s*$)/g, "")==''){
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }
    }, "请上传组织机构代码证复印件");

    jQuery.validator.addMethod("checkdate", function(value, element) {
        if(value>date){
            return false;
        }else{
            return true;
        }
    }, "Please input correct start date.");

    /***提交表单****/
    $('#company_form').validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",
        rules:{
            trademark_name:{
                required : true
            },
            start_date:{
                checkdate : true
            },
            trademark_category:{
                required:true
            },
            business_license:{
                checkbussiness:true
            }
        },
        messages: {
            trademark_name: {
                required: "请输入商标名称"
            },
            start_date: {
                checkdate: "请正确输入商标证书有效期"
            },
            trademark_category:{
                required:'请按照商标证书上商标分类填写'
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
            label.siblings('.col-md-6').find('.uneditable-input').css({border: "1px solid #e5e5e5"});
            label.remove();
        },

        errorPlacement: function (error, element) {
            if(element.closest('.col-md-6').next('.help-inline').html()!=undefined){
                error.insertAfter(element.closest('.col-md-6').next('.help-inline'));
            }else{
                error.insertAfter(element.closest('.col-md-6'));
            }
        },

        submitHandler: function (form) {
            form.submit();
        }
    });

    /***提交表单****/
    $('#show_form').validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",
        rules:{
            trademark_name:{
                required : true
            },
            start_date:{
                checkdate : true
            },
            trademark_category:{
                required:true
            }
        },
        messages: {
            trademark_name: {
                required: "请输入商标名称"
            },
            start_date: {
                checkdate: "请正确输入商标证书有效期"
            },
            trademark_category:{
                required:'请按照商标证书上商标分类填写'
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
            label.siblings('.col-md-6').find('.uneditable-input').css({border: "1px solid #e5e5e5"});
            label.remove();
        },

        errorPlacement: function (error, element) {
            if(element.closest('.col-md-6').next('.help-inline').html()!=undefined){
                error.insertAfter(element.closest('.col-md-6').next('.help-inline'));
            }else{
                error.insertAfter(element.closest('.col-md-6'));
            }
        },

        submitHandler: function (form) {
            form.submit();
        }


    });
    /***提交表单****/
    $('#create_form').validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",
        rules:{
            trademark_name:{
                required : true
            },
            start_date:{
                checkdate : true
            },
            trademark_category:{
                required:true
            }
        },
        messages: {
            trademark_name: {
                required: "请输入商标名称"
            },
            start_date: {
                checkdate: "请正确输入商标证书有效期"
            },
            trademark_category:{
                required:'请按照商标证书上商标分类填写'
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
            label.siblings('.col-md-6').find('.uneditable-input').css({border: "1px solid #e5e5e5"});
            label.remove();
        },

        errorPlacement: function (error, element) {
            if(element.closest('.col-md-6').next('.help-inline').html()!=undefined){
                error.insertAfter(element.closest('.col-md-6').next('.help-inline'));
            }else{
                error.insertAfter(element.closest('.col-md-6'));
            }
        },

        submitHandler: function (form) {
            form.submit();
        }
    });
    //保存第一条数据
    var saveflag = false;
    if(saveflag==false){
        $('#savecompany').click(function(){
            send_data($('#company_form'),saveflag,'next','','');
        });
    }else{
        common_layer('数据已提交','');
    }

    //保存其他商标信息
    var saveother = false;
    if(saveother==false){
        $('#create_trademark').on('click','#saveother',function(){
            send_data($('#create_form'),saveother,'close',j,'add');
        });
    }else{
        common_layer('数据已提交','');
    }

    var saveshow = false;
    if(saveshow==false){
        $('#show_trademark').on('click','#saveshow',function(){
            send_data($('#show_form'),saveshow,'close',i,'');
        });
    }else{
        common_layer('数据已提交','');
    }

    //删除商标
    $('#company_form').on('click','.deletedtrademark',function(){
        var that = $(this).closest('.trademarked');
        var id = $(this).attr('data-id');
        var guid = $(this).attr('data-value');

        var load = layer.load('正在查询，请稍后...');

        $.ajax({
            type: 'POST',
            url: '/brandLogo/deleteTrademark',
            data: {'id':id,'guid':guid},
            dataType:'json',
            success: function (r) {
                layer.close(load);
                if('err'==r.info){
                    common_layer('删除失败');
                    return false;
                }else{
                    common_layer('删除成功');
                    that.remove();
                    var t = $('#trademarks').html();
                    t = t.replace(/[\r\n]/g,"").replace(/[ ]/g,"");
                    if(t == ''){
                        $('#trademarks').closest('.form-group').hide();
                    }
                }
            },
            error: function () {
                layer.close(load);
                common_layer('删除失败','');
            }
        });
    });
    //添加更多商标
    $("#company_form").on('click','#addtrademark',function() {
        //表单reset
        $('#create_form')[0].reset();
        j= $.layer({
            type : 1,
            title : '添加其他商标信息',
            maxmin: true,
            offset: ['46px', ''],
            closeBtn: [0, true],
            border : [0],
            fix: false,
            // shadeClose: true,
            area : ['960px',''],
            btn: ['确定', '取消'],
            page : {dom : '#create_trademark'}
        });
        $('.cancle').on('click', function(){
            layer.close(j);
        });
    });

    //查看更多商标
    $("#company_form").on('click','.showtrademark',function() {
        var id = $(this).attr('data-id');
        var guid = $('#brand_logo_id').val();

        var load = layer.load('正在查询，请稍后...');
        $.ajax({
            type: 'POST',
            url: '/brandLogo/getTrademark',
            data: {'id':id,'guid':guid},
            //dataType:'json',
            success: function (r) {
                layer.close(load);
                if($.trim(r).indexOf("err:")>-1){
                    r = r.split("err:")[1];
                    common_layer(r);
                    return false;
                }else{
                    $("#show_trademark").empty().append(r);
                    i= $.layer({
                        type : 1,
                        title : '查看其他商标信息',
                        maxmin: true,
                        offset: ['46px', ''],
                        closeBtn: [0, true],
                        border : [0],
                        fix: false,
                        // shadeClose: true,
                        area : ['960px',''],
                        btn: ['确定', '取消'],
                        page : {dom : '#show_trademark'}
                    });
                }
            },
            error: function () {
                layer.close(load);
                common_layer('查询失败','');
            }
        });

        $('.cancle').on('click', function(){
            layer.close(i);
        });
    });
});
/**
 * 保存商标信息
 * @param id
 * @param saveflag
 */
function send_data(id,saveflag,type,closetype,add){
    saveflag = true;
    var flag = true;
    var guid = $('#brand_logo_id').val();
    var $subs = id.find("input[name='category[]']");
    var length = $subs.filter(":checked").length;
    if(length==0){
        id.find('.label_category').closest('.form-group').addClass('has-error');
        id.find('.label_category').siblings('.col-md-6').after('<span style="color:#b94a48;" class="trademark_category_help">请选择商标分类</span>');
        flag = false;
    }
    if(id.validate().form() && flag){
        var load = layer.load('正在提交，请稍后...');
        $.ajax({
            type: 'POST',
            url: '/brandLogo/saveTrademark',
            data: id.serialize(),
            dataType:'json',
            success: function (r) {
                layer.close(load);
                saveflag = false;
                if('err'==r.info){
                    common_layer(r.data,'');
                }else{
                    if('next'==type){
                        var url=encodeURI("/brandLogo/auditRegistrant?id="+ r.data);
                        location.href=url;
                    }else{
                        if(''!=add){
                            var str = '<div  class="trademarked">' +
                                '<a href="javascript:;" class="showtrademark" data-id="'+r.data+'" data-value="'+guid+'">商标Id：'+r.data+'</a>' +
                                '<span class="label label-sm label-danger deletedtrademark" data-id="'+r.data+'" data-value="'+r.data+'">' +
                                '<a href="javascript:;" style="color:#fff;">删除</a>' +
                                '</span>';
                            $('#trademarks').append(str);
                            $('#trademarks').closest('.form-group').show();
                        }

                       if(''!=closetype){
                           layer.close(closetype);
                       }
                    }

                }
            },
            error: function () {
                layer.close(load);
                saveflag = false;
                common_layer('保存失败','');
            }
        });
    }
}
//上传附件
function ajaxFileUpload(obj) {

    var fileName = $(obj).attr('id');
    var uploadInfo = $(obj).parent();
    var file = $(obj).val();
    var fileType = file.substring(file.lastIndexOf(".")+1);
    fileType = fileType.toLowerCase();
    var allows = ['png','jpg','jpeg','bmp'];
    if($.inArray(fileType,allows)==-1){
        common_layer('上传文件格式不允许','');
        return;
    }
    var fileInput = $("#"+fileName)[0];
    if (fileInput.files && fileInput.files[0]) {
       var size = fileInput.files[0].fileSize;
        if(size>1024*1024*2 || size<6*1024){
            common_layer('File size is required between 5K-2M.','');
            return false;
        }
    }

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
                'minsize':5
            },
            success: function (data, status) {
                layer.close(load);
                if (typeof(data.error) != 'undefined' && data.error != '') {
                    common_layer(data.error,'');
                } else {
                    /***给隐藏域赋值***/
                    uploadInfo.siblings('.hidden_value').val(data.guid);
                    uploadInfo.siblings('.green').attr('href','/upload/showUploadfile?id='+data.guid);
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
                common_layer('上传失败，请稍后重试','');
            }
        }
    );
    return false;
}

