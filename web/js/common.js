
var unreadIntval;
var unread_ok = false;
$(function(){
    var notification = $.cookie('notification');
    if (notification > 0) {
        $('#notification_num').text(notification).show();
    }
    _beat_unread_count(5);
    $('.cn_more').click(function(event) {
        var flag = $(this).attr('data-id');
        if (flag=='zhan') {
            $(this).attr('data-id','shou');
            $(this).html('收起&nbsp;<i class="icon-chevron-up"></i>');
            $(this).prev().css('height', 'auto');
        }else if(flag=='shou'){
            $(this).attr('data-id','zhan');
            $(this).html('展开&nbsp;<i class="icon-chevron-down"></i>');
            $(this).prev().css('height', '20px');
        }
    });
    // $('.checkall').click(function(event) {
    //     var _this = $(this);
    //     var i = $('.checkall').index(this);
    //     if (this.checked) {
    //         $('.c').attr('checked', 'checked');
    //     }
    // });
});

function _beat_unread_count(time)
{
    //priceIntval = setInterval(function(){
    //    _get_update_notification()
    //}, time*1000);
}

function _get_update_notification()
{
    if (unread_ok == true) return;
    unread_ok = true;
    $.ajax({
        type: 'POST',
        url: '/notification/update',
        data: {},
        dataType:'json',
        success:  function(json){
        		if (json.info != 'ok')
            {
                return false;
            }
            unread_ok = false;
            var data = json.data;
            if (data['num'] > 0) {
            		$('#notification_num').text(data['num']).show();
            }
        }
    });
}
/**长度**/
function getBytesLen(val){
    var len=0;
    for (var i = 0; i < val.length; i++)
    {
        len = len + ((val.charCodeAt(i) >= 0x4e00 && val.charCodeAt(i) <= 0x9fa5) ? 3 : 1);
    }
    return len;
}
/**
 * 通用弹窗
 * @param r
 * @param url
 */
function common_layer(r,url){
    var layerc = $.layer({
        shade: [0.5, '#000'],
        area: ['400px','210px'],
        border: [1, 0.3, '#ccc'],
        title:['信息提示'],
        dialog: {
            msg: r,
            btns: 1,
            type: -1,
            btn: ['关闭'],
            yes: function(){
                if(url=='' || url==undefined){
                    layer.close(layerc);
                }else{
                    location.href= encodeURI(url);
                }
            }
        }
    });
}

