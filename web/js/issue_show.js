/**
 * Created by Administrator on 2014-12-17.
 */
jQuery(document).ready(function () {
    $('#fileupload').validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        rules: {
            finance_amount: {
                required: true
            },
            content: {
                required: true
            }
        },

        messages: {
            finance_amount: {
                required: "请输入金额"
            },
            content: {
                required: "请输入回复内容"
            }
        },

        invalidHandler: function (event, validator) { //display error alert on form submit
            $('.alert-danger', $('#fileupload')).show();
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

    $('#fileupload input').keypress(function (e) {
        if (e.which == 13) {
            if ($('#fileupload').validate().form()) {
                $('#fileupload').submit();
            }
            return false;
        }
    });

    /***
     * 删除附件
     */
    $('#attachment').on('click','.label-danger',function(e){
        $(this).closest('.hidden_value').remove();
        var t = $('#attachment').html();
        t = t.replace(/[\r\n]/g,"").replace(/[ ]/g,"");
        if(t == ''){
            $('#attachment').closest('.form-group').hide();
        }
    });
});
//上传附件
function ajaxFileUpload(obj) {
    var fileName = $(obj).attr('id');
    var uploadInfo = $(obj).parent();
    
    var fileInput = $("#"+fileName)[0];
    if (fileInput.files && fileInput.files[0]) {
        var size = fileInput.files[0].fileSize;
        if(size>1024*1024*2 || size<6*1024){
            common_layer('文件大小不能超过20M','');
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
                'file_name': fileName
            },
            success: function (data, status) {
                layer.close(load);
                if (typeof(data.error) != 'undefined' && data.error != '') {
                    layer.msg(data.error,5,-1);
                } else {
                    var str = '<div class="hidden_value"><input type="hidden" name="attachment[]" value="' + data.guid + '" >' +
                        '<span class="help-inline" style="padding-top:0px;"><a href="/upload/showuploadfile?id=' + data.guid + '" target="_blank">' + data.name + '</a></span>'+
                        '<span class="label label-sm label-danger"><a href="javascript:;" style="color:#fff;">删除</a></span></div>';
                    $('#attachment').append(str);
                    $('#attachment').closest('.form-group').show();
                }
            },
            error: function (data, status, e) {
                layer.close(load);
                layer.msg('上传失败，请稍后重试',5,-1);
            }
        }
    );
    return false;
}
