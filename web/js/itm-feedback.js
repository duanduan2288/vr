/**
 * Created by duan on 2015-9-18.
 */
jQuery(document).ready(function () {

    if (jQuery().datepicker) {
        $('.date-picker').datepicker({
            autoclose: true,
            isRTL: App.isRTL(),
            format: "yyyy-mm-dd"
        });
        $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
    }

    /*****取消标记****/
    $('.portlet-body').on('click','.cancel_update',function(e){

        if(!confirm('确定执行此操作吗？')) return false;

        var type = $(this).attr('data-value');
        var guid = $(this).attr('data-id');
        if($.trim(guid)==''){
            common_layer('参数错误');
        }
        var load = layer.load('正在提交~~');
        $.ajax({
            type: 'POST',
            url: '/feedback/cancel-flag',
            dataType:'json',
            data:{'guid':guid,'type':type},
            success: function(json) {
                layer.close(load);
                if(json.info=='ok'){
                    var url = window.location.pathname+decodeURI(window.location.search);
                    common_layer(json.data,url);
                }else{
                    common_layer(json.data);
                }
            },
            error:function(){
                common_layer('系统繁忙，请稍后重试','');
            }
        });
    });

    /******添加回访状态******/
    $('.portlet-body').on('click','.add_feedback',function(){
        var guid = $(this).attr('data-id');
        $('#feedback_id').val(guid);
        j= $.layer({
            type : 1,
            title : '添加回访',
            maxmin: true,
            offset: ['46px', ''],
            closeBtn: [0, true],
            border : [0],
            fix: false,
            // shadeClose: true,
            area : ['700px',''],
            btn: ['确定', '取消'],
            page : {dom : '#pass-table'}
        });
        $('.cancle,#pass_cancel').on('click', function(){
            layer.close(j);
        });
    });

   /*****添加****/
    var pass_flag = false;
    $('#pass-table').on('click','#pass_submit',function(){
        if(pass_flag){
            common_layer('数据已提交','');
            return false;
        }
        pass_flag = true;
        var url ='/feedback/add-record';

        var load = layer.load('正在提交~~');
        $.ajax({
            type: 'POST',
            url: url,
            dataType:'json',
            data:$('#pass_form').serialize(),
            success: function(json) {
                pass_flag = false;
                layer.close(load);
                if(json.info=='ok'){
                    common_layer(json.data,'/feedback/index');
                }else{
                    common_layer(json.data);
                }
            },
            error:function(){
                pass_flag = false;
                common_layer('系统繁忙，请稍后重试','');
            }
        });
    });

    /*****修改联系方式****/

    /***电话号***/
    jQuery.validator.addMethod("checktelephone", function(value, element) {
        return this.optional(element) || /^((0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$/.test(value);
    }, "请正确输入电话号码");
    /***手机号***/
    jQuery.validator.addMethod("checkmobile", function(value, element) {
        return this.optional(element) || /^1[0-9]{10}$/.test(value);
    }, "请正确输入手机号");
    $('#myform').validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        rules: {
            telephone : {
                checktelephone:true
            },
            cellphone : {
                checkmobile:true
            },
            email : {
                email:true
            }
        },

        messages: {
            telephone : {
                checktelephone:'请正确输入电话号码'
            },
            cellphone : {
                checkmobile:'请正确输入手机号'
            },
            email : {
                email:'请正确输入电子邮箱'
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
    var saveflag = false;
    if(saveflag==false){
        $('#saveform').click(function(){
            var telephone = $('#telephone').val();
            var cellphone = $('#cellphone').val();
            var email = $('#email').val();
            if (telephone==''&&cellphone==''&&email=='') {
                alert('请至少填写一个联系方式');
                return;
            }
            saveflag = true;
            if($('#myform').validate().form()){
                var load = layer.load('正在提交，请稍后...');
                $.ajax({
                    type: 'POST',
                    url: '/feedback/edit-contact',
                    data: $('#myform').serialize(),
                    dataType:'json',
                    success: function (r) {
                        layer.close(load);
                        saveflag = false;
                        if('err'==r.info){
                            common_layer(r.data,'');
                        }else{
                            common_layer(r.data,'/feedback/index');
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
    /***
     * 删除附件
     */
    $('#attachment_pass').on('click','.label-danger',function(e){
        $(this).closest('.hidden_value').remove();
        var t = $('#attachment_pass').html();
        t = t.replace(/[\r\n]/g,"").replace(/[ ]/g,"");
        if(t == ''){
            $('#attachment_pass').closest('.form-group').hide();
        }
    });

    //注册域名列表
    /*********修改回访状态*********/
    $('.portlet-body').on('click','.update_status',function(){
        var guid = $(this).attr('data-id');
        $('#audit_data_id').val(guid);
        j= $.layer({
            type : 1,
            title : '修改回访记录',
            maxmin: true,
            offset: ['46px', ''],
            closeBtn: [0, true],
            border : [0],
            fix: false,
            // shadeClose: true,
            area : ['700px',''],
            btn: ['确定', '取消'],
            page : {dom : '#add-table'}
        });
        $('.cancle,#add_cancel').on('click', function(){
            layer.close(j);
        });
    });
    var addflag = false;
    if(addflag==false){
        $('#add_submit').click(function(){
            var feedback_status = $('#feedback_status').val();
            if (feedback_status=='') {
                alert('请选择回访状态');
                return;
            }
            addflag = true;
            var load = layer.load('正在提交，请稍后...');
            $.ajax({
                type: 'POST',
                url: '/feedback/edit-status',
                data: $('#add_form').serialize(),
                dataType:'json',
                success: function (r) {
                    layer.close(load);
                    addflag = false;
                    if('err'==r.info){
                        common_layer(r.data,'');
                    }else{
                        var url = window.location.pathname+decodeURI(window.location.search);
                        common_layer(r.data,url);
                    }
                },
                error: function () {
                    layer.close(load);
                    addflag = false;
                    common_layer('保存失败','');
                }
            });
        });
    }else{
        common_layer('数据已提交','');
    }

    /*****设为主要****/
    $('.portlet-body').on('click','.add_main',function(e){
        e.preventDefault();
        if(!confirm('确定执行此操作吗？')) return false;

        var guid = $(this).attr('data-id');
        if($.trim(guid)==''){
            common_layer('参数错误');
        }
        $('')
        var load = layer.load('正在提交~~');
        $.ajax({
            type: 'POST',
            url: '/feedback/setting-main',
            dataType:'json',
            data:{'guid':guid},
            success: function(json) {
                layer.close(load);
                if(json.info=='ok'){
                    var url = window.location.pathname+decodeURI(window.location.search);
                    common_layer(json.data,url);
                }else{
                    common_layer(json.data);
                }
            },
            error:function(){
                common_layer('系统繁忙，请稍后重试','');
            }
        });
    });

    /*****选择回访录音文件****/
    $('#wav_file_upload').click(function(e){
        e.preventDefault();
        i= $.layer({
            type : 1,
            title : '录音文件',
            maxmin: true,
            offset: ['46px', ''],
            closeBtn: [0, true],
            border : [0],
            fix: false,
            // shadeClose: true,
            area : ['700px',''],
            btn: ['确定', '取消'],
            page : {dom : '#wav-table'}
        });
        $('.cancle').on('click', function(){
            layer.close(i);
        });
    });
    /******获取子文件****/
    $('#wav-table').on('click','.tree-folder-header',function(e){
        e.preventDefault();
        var _that = $(this);
        var datamode = $(this).attr('data-mode');
        if('show'==datamode){
            _that.closest('.tree-folder-header').siblings('.tree-folder-content').hide();
            $(this).attr('data-mode','hashide');
            return false;
        }
        if('hashide'==datamode){
            _that.closest('.tree-folder-header').siblings('.tree-folder-content').show();
            $(this).attr('data-mode','show');
            return false;
        }
        var path = $(this).attr('data-value');
        $.ajax({
            url: '/feedback/get-attachment',
            data: {'path':path},
            type: 'POST',
            //dataType: 'json',
            success: function (response) {
                if($.trim(response).indexOf("err:")>-1){
                    response=response.split("err:")[1];
                    common_layer(response);
                    return false;
                }
                _that.closest('.tree-folder-header').siblings('.tree-folder-content').show();
                _that.closest('.tree-folder-header').siblings('.tree-folder-content').empty().append(response);
                _that.attr('data-mode','show');
            },
            error: function (response) {
                //console.log(response);
            }
        });
    });

    /*****选中***/
    $('#wav-table').on('click','.tree-item-name',function(e){
        var path = $(this).attr('data-value');
        var name = $(this).attr('data-id');
        var html='<i class="icon icon-check"></i>';
        var checktag = $(this).attr('data-mode');
        if('selected'==checktag){
            $(this).attr('data-mode','unselected');
            $(this).find('.icon-check').remove();
            $('#attachment_wav').find("."+path).remove();
        }else{
            if(checktag=='selected' && $('#attachment_wav').find("."+path)){
                $(this).prepend(html);
                $(this).attr('data-mode','selected');
            }else{
                var str = '<div class="hidden_value '+path+'"><input type="hidden" name="wav_attachment[]" value="' + path+ '" >' +
                    '<span class="help-inline" style="padding-top:0px;"><a href="/' + path + '" target="_blank">' + name + '</a></span>'+
                    '<span class="label label-sm label-danger"><a href="javascript:;" style="color:#fff;">删除</a></span></div>';
                $(this).prepend(html);
                $(this).attr('data-mode','selected');
                $('#attachment_wav').append(str);
            }
        }
    });
    /***
     * 删除附件
     */
    $('#attachment_wav').on('click','.label-danger',function(e){
        $(this).closest('.hidden_value').remove();
        var t = $('#attachment_wav').html();
        t = t.replace(/[\r\n]/g,"").replace(/[ ]/g,"");
        if(t == ''){
            $('#attachment_wav').closest('.form-group').hide();
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
            url: '/upload/upload-file',
            secureuri: false,
            fileElementId: fileName,
            dataType: 'json',
            data: {
                'file_name': fileName
            },
            success: function (data, status) {
                if (typeof(data.error) != 'undefined' && data.error != '') {
                    $('#message_file_pass').html(data.error).show();

                } else {
                    var str = '<div class="hidden_value"><input type="hidden" name="attachment[]" value="' + data.guid + '" >' +
                        '<span class="help-inline" style="padding-top:0px;"><a href="/upload/showuploadfile?id=' + data.guid + '" target="_blank">' + data.name + '</a></span>'+
                        '<span class="label label-sm label-danger"><a href="javascript:;" style="color:#fff;">删除</a></span></div>';

                    $('#attachment_pass').append(str);
                }
            },
            error: function (data, status, e) {
                alert(e);
            }
        }
    );
    return false;
}