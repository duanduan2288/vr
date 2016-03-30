/**
 * Created by Administrator on 2015-9-6.
 */
$(function(){

    /****审核拒绝*****/
    $('#reject_button').click(function(){
        var guid = $(this).attr('data-value');
        get_reason(guid,'reject');
    });

    /****审核退回*****/
    $('#back_button').click(function(){
        var guid = $(this).attr('data-value');
        get_reason(guid,'return');
    });

    /***
     * 删除附件--审核拒绝
     */
    $('#attachment').on('click','.label-danger',function(e){
        $(this).closest('.hidden_value').remove();
        var t = $('#attachment').html();
        t = t.replace(/[\r\n]/g,"").replace(/[ ]/g,"");
        if(t == ''){
            $('#attachment').closest('.form-group').hide();
        }
    });

    /*****审核拒绝取消*****/
    $('#reason-table').on('click','#reject_cancel',function(){
        layer.close(i);
    });

    $('#reason-table').on('click','.reason',function(){
        var content = $(this).attr('data-value');
        $('#display_reason').val(content);
        $('#memo').val(content);
    });

    /******审核拒绝确定*****/
    var submit_flag = false;

    $('#reason-table').on('click','#reject_submit',function(){
        if(submit_flag){
            common_layer('数据已提交','');
            return false;
        }
        submit_flag = true;
        var audit_type = $('#audit_type').val();
        var url = audit_type=='reject'?'/audit-data/reject':'/audit-data/return';

        var load = layer.load('正在提交~~');
        $.ajax({
            type: 'POST',
            url: url,
            dataType:'json',
            data:$('#company_form').serialize(),
            success: function(json) {
                submit_flag = false;
                layer.close(load);
                if(json.info=='ok'){
                    common_layer(json.data,'/audit-data/index');
                }else{
                    common_layer(json.data);
                }
            },
            error:function(){
                submit_flag = false;
                common_layer('系统繁忙，请稍后重试','');
            }
        });
    });

    /****审核通过*****/
    $('#pass_button').click(function(){
        j= $.layer({
            type : 1,
            title : '域名审核',
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


    /***
     * 审核通过
     */

    var pass_flag = false;
    $('#pass-table').on('click','#pass_submit',function(){
        if(pass_flag){
            common_layer('数据已提交','');
            return false;
        }
        pass_flag = true;
        var url ='/audit-data/pass';

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
                    common_layer(json.data,'/audit-data/index');
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
    /***
     * 删除附件--审核通过
     */
    $('#attachment_pass').on('click','.label-danger',function(e){
        $(this).closest('.hidden_value').remove();
        var t = $('#attachment_pass').html();
        t = t.replace(/[\r\n]/g,"").replace(/[ ]/g,"");
        if(t == ''){
            $('#attachment_pass').closest('.form-group').hide();
        }
    });

});
/***获取审核拒绝/审核退回原因****/
function get_reason(guid,type){
    var load = layer.load('加载中~~');

    $.ajax({
        type: 'POST',
        url: '/audit-reason/get-reason',
        data:{'guid':guid,'type':type},
        //dataType:'json',
        success: function(json) {
            layer.close(load);
            if($.trim(json).indexOf("err:")>-1){
                json=json.split("err:")[1];
                common_layer(json);
                return false;
            }

            $("#reason-table").empty().append(json);
            i= $.layer({
                type : 1,
                title : '域名审核',
                maxmin: true,
                offset: ['46px', ''],
                closeBtn: [0, true],
                border : [0],
                fix: false,
                // shadeClose: true,
                area : ['700px',''],
                btn: ['确定', '取消'],
                page : {dom : '#reason-table'}
            });
            $('.cancle').on('click', function(){
                layer.close(i);
            });
        }
    });
}
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
                    if('file_caac_pass'==fileName){
                        $('#message_file_pass').html(data.error).show();
                    }else{
                        $('#message_file').html(data.error).show();
                    }

                } else {
                    var str = '<div class="hidden_value"><input type="hidden" name="attachment[]" value="' + data.guid + '" >' +
                        '<span class="help-inline" style="padding-top:0px;"><a href="/upload/showuploadfile?id=' + data.guid + '" target="_blank">' + data.name + '</a></span>'+
                        '<span class="label label-sm label-danger"><a href="javascript:;" style="color:#fff;">删除</a></span></div>';

                    if('file_caac_pass'==fileName){
                        $('#attachment_pass').append(str);
                    }else{
                        $('#attachment').append(str);
                    }
                }
            },
            error: function (data, status, e) {
                alert(e);
            }
        }
    );
    return false;
}