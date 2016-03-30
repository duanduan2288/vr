<style>
    select.form-control {width: 20%;display:inline-block;}
    /*select.form-control:focus {border: none;}*/
    option{outline: none;}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-bell"></i>
                    <a>信息管理&nbsp;&nbsp;<i class="icon-angle-right"></i></a>
                    <a href="#">发送消息</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                    <a href="javascript:history.go(-1);"><i class="icon-reply"></i>返回</a>&nbsp;&nbsp;
                </div>
            </div>
            <div class="portlet-body form">
                <form role="form" id="myform" class="form-horizontal validate" enctype="multipart/form-data" method="post" action="/notification/save">
                    <div class="form-body">
                        <div class="form-group" id="registrar">
                            <label class="col-md-3 control-label"><strong>代理商接收人<span class="required">*</span></strong></label>
                            <div class="col-md-9">
                                <select id="company" multiple="multiple" size="10" name="notification[registrar]" class="form-control required">
                                    <?php foreach ($agents as $k => $v) : ?>
                                        <option value="<?php echo $v['id']; ?>"><?php echo $v['company_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select style="display: none;" id="role" multiple="multiple" size="10" name="notification[registrar_role]" class="form-control">
                                </select>
                                <select style="display: none;" id="user" multiple="multiple" size="10" name="notification[registrar_user][]" class="form-control">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-3" style="color: #aaa;">
                               选择用户时可以按住ctrl键+鼠标左键进行多选
                            </div>
                        </div>
                        <div class="form-group">
                            <label  class="col-md-3 control-label"><strong>消息类型</strong>&nbsp;&nbsp;</label>
                            <div class="col-md-5">
                                <div class="radio-list">
                                    <label class="radio-inline">
                                        <input type="radio" name="notification[priority]" value="普通" checked /> 普通
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="notification[priority]" value="紧急"  /> 紧急
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>消息标题<span class="required">*</span></strong></label>
                            <div class="col-md-5">
                                <input id="title" type="text" name="notification[title]" value="" class="form-control required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>消息内容<span class="required">*</span></strong></label>
                            <div class="col-md-5">
                                <textarea id="content" class="form-control required" rows="4" name="notification[content]" data-rule-maxlength="500"></textarea>
                            </div>
                        </div>
                        <div class="form-actions fluid"  id="submit">
                            <div class="col-md-offset-3 col-md-6">
                                <button class="btn blue" type="button"><i class="icon-save">&nbsp;&nbsp;</i>保存</button>
                            </div>
                        </div>
                    </div>
                    <br><br><br><br><br><br><br><br>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        questionLoading = false;
        $('#registrar').on('change', '#company', function(event) {
            event.preventDefault();
            $('#role,#user').html('').hide();
            var role = $('#role').val();
            // alert(role);
            if (role!=null) return;
            $.ajax({
                type: 'POST',
                url: '/notification/getroles',
                data: {},
                dataType:'json',
                success:  function(json){
                            if(json.info!='ok'){
                                alert('获取信息失败');
                                return;
                            }
                            var data = json.data;
                            var html = '<option value="all">全部</option>';
                            for (var i = 0; i < data.length; i++) {
                                html+= '<option value="'+data[i]['id']+'">'+data[i]['name']+'</option>';
                            }
                            $('#role').html(html).show();
                }
            })
        });

        $('#registrar').on('change', '#role', function(event) {
            event.preventDefault();
            var company = $('#company').val();
            var role = $(this).val();
            $.ajax({
                type: 'POST',
                url: '/notification/getusers',
                data: {'company':company,'role':role},
                dataType:'json',
                success:  function(json){
                            if(json.info!='ok'){
                                alert('获取信息失败');
                                return;
                            }
                            var data = json.data;
                            var html = '';
                            for (var i = 0; i < data.length; i++) {
                                html+= '<option value="'+data[i]['id']+'">'+data[i]['email']+'</option>';
                            }
                            $('#user').html(html).show();
                }
            })
        });

        $('#myform').on('click', '#submit', function(event) {
            event.preventDefault();
            var company = $("#company").find("option:selected").text();
            var role = $("#role").find("option:selected").text();
            var user = $("#user").find("option:selected").text();
            var flag = $("#user").find("option").text();
            var title = $("#title").val();
            var content = $("#content").val();
            if (company == '') {
                layer.msg('请选择注册商公司',1,-1);
                // $("#company").focus();
                return;
            }
            if (role == '') {
                layer.msg('请选择注册商公司的权限列表',1,-1);
                // $("#role").focus();
                return;
            }
            if (title == '') {
                layer.msg('请填写信息标题',1,-1);
                $("#title").focus();
                return;
            }
            if (content == '') {
                layer.msg('请填写信息内容',1,-1);
                $("#content").focus();
                return;
            }
            if (questionLoading == true) {
                layer.msg('正在处理中请稍后...',1,-1);
                return;
            }
            if (role != '' && flag == '') {
                layer.msg('<'+company+'>的 '+role+' 列表里暂无用户',2,-1);
                return;
            }
            var s = '确定要发送给列表中的用户吗';
            if (role != '' && flag != '' && user == '') {
                s= '确定要发给<'+company+'>的 '+role+' 列表里的所有用户吗';
            }
            layer.confirm(s,function(i){
                layer.close(i);
                $('#myform').submit();
                // var loadi = layer.load('加载中…',1);
                // $.ajax({
                //     type: 'POST',
                //     url: '/notification/save',
                //     data: $('#myform').serialize(),
                //     dataType:'json',
                //     success: function(json) {
                //         alert('111');return;
                //         // layer.close(loadi);
                //         // questionLoading = false;
                //         // if (json.info != 'ok') {
                //         //     layer.msg(json.msg,2);
                //         //     return false;
                //         // }
                //         // layer.msg(json.msg, 2, function(){window.location.href='/notification/index';});
                //       }
                // });
            });
            // layer.msg(json.data, 1, 1, function(){window.location.reload();});
        });
    })
</script>