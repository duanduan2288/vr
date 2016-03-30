<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-cog"></i>
                    <a href="/send-message/index">信息管理&nbsp;&nbsp;<i class="icon-angle-right"></i></a>
                    <a href="">发送邮件/短信</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                </div>
            </div>
            <div class="portlet-body form">
               <form class="form-horizontal" enctype="multipart/form-data" id="domain-form">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>域名<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                               <div class="input-group">
                                    <input type="text" name="domain" value="" id="domain" class="form-control required" placeholder="请输入域名">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn default" tabindex="-1">.商标</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions fluid">
                            <div class="col-md-offset-3 col-md-6">
                                <a id="create-submit-btn" class="btn blue" ><i class="icon-save">&nbsp;&nbsp;</i>保存</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- END SAMPLE FORM PORTLET-->
        </div>
    </div>
</div>
<script>
    $(function(){
        /***回车键***/
        $('#domain-form input').keypress(function (e) {
            if (e.which == 13) {
                submit_data();
                return false;
            }
        });
        $('#create-submit-btn').click(function(){
            submit_data();
        });

    });
    /**
     *查询域名
     * @returns {boolean}
     */
    function submit_data() {
        var value = $('#domain').val();
        if (value == '') {
            domain_layer('请先输入要查询的域名');
            return false;
        }

        var qreg = new RegExp(/^[-a-zA-Z0-9\u4E00-\u9FA5]{1,}$/);
        var regHead = /^[a-zA-Z0-9\u4E00-\u9FA5]/;
        var regTail = /[a-zA-Z0-9\u4E00-\u9FA5]$/;
        var length = getBytesLen(value);
        var message = '';
        if(!(qreg.test(value) && regHead.test(value) && regTail.test(value))){
            message = '不符合域名注册规则，请重新输入！';
            domain_layer(message);
            return false;
        }

        var load = layer.load('正在发送，请稍后~~');
        $.ajax({
            type: 'POST',
            url: '/send-message/send',
            data: $('#domain-form').serialize(),
            dataType: 'json',
            success: function (r) {
                layer.close(load);
                domain_layer(r.data);
            },
            error: function () {
                layer.close(load);
                domain_layer('发送失败');
            }
        });
    }

    function domain_layer(r){
        $.layer({
            shade: [0.5, '#000'],
            area: ["350px",'150px'],
            border: [1, 0.3, '#ccc'],
            title:['信息提示'],
            dialog: {
                msg: r,
                btns: 1,
                type: -1,
                btn: ['关闭'],
                yes: function(){
                    window.location.reload();
                }
            }
        });
    }
</script>