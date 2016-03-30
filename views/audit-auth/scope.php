<?php
    use app\models\Service;
?>
<style>
.table th a{ color:#000;text-decoration:none;}
.table td a{text-decoration:none;}
.input-small {width: 250px !important;}
.select{
    min-height:600px;
    width:100%;
    line-height:30px;
    cursor:pointer;
    font-size: 16px;
    border-width: 1px;
    border-top-style: none;
    border-right-style: none;
    /*border-left-style: none;*/
    border-bottom-style: none;
    outline: none;
}
.tishi{
    width:100%;
    text-align:center;
    line-height:30px;
    font-size:16px;
    border-bottom: 1px solid #75736E;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-cog"></i>
                    <a>审核权限管理&nbsp;&nbsp;<i class="icon-angle-right"></i></a>
                    <a href="#">管理范围调整</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                    <a href="/srbcSupport/userScope"><i class="icon-reply"></i>返回</a>&nbsp;&nbsp;
                </div>
            </div>

            <div class="portlet-body">
                <table class="table table-striped table-hover table-bordered table-advance">
                    <tbody>
                        <tr>
                            <td align="right" width="41%">用户名&nbsp;:</td>
                            <td><?php echo $user->last_name.$user->first_name; ?></td>
                        </tr>
                        <tr>
                            <td align="right">用户角色&nbsp;:</td>
                            <td><?php echo Service::getRoleName($user->user_role); ?></td>
                        </tr>
                        <tr>
                            <td align="right">已管理注册商&nbsp;:</td>
                            <td>
                                <?php
                                    $str1 = '';
                                    foreach ($employee_agents as $k => $v) {
                                        if ($k!=count($employee_agents)-1) {
                                            $str1.= Service::get_company_name_by_id($v).'<span style="color:red;">&nbsp;|&nbsp;</span>';
                                        }else{
                                            $str1.= Service::get_company_name_by_id($v);
                                        }
                                    }
                                    echo trim($str1,',');
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="portlet-body">
                <form role="form" id="myform" class="form-horizontal validate" enctype="multipart/form-data" method="post">
                    <input type="hidden" id="user_id" name="user_id" value="<?php echo $user->guid; ?>">
                    <div class="form-body">
                        <div class="form-group">
                            <div class="col-md-5">
                                <div class="tishi" style="color:red;">未管理注册商</div>
                                <select class="select" id="select1" multiple="multiple">
                                    <?php foreach ($registrars as $v) : ?>
                                        <option value="<?php echo $v; ?>">
                                            <?php
                                               echo Service::get_company_name_by_id($v);
                                            ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div style="border-top:1px solid #75736E"></div>
                            </div>
                            <div style="width:2%;float:left;padding-top:200px;margin-right:10px;">
                                <a id="button1" class="btn default"><i class="icon-long-arrow-right"></i></a><br><br>
                                <a id="button2" class="btn default"><i class="icon-long-arrow-left"></i></a>
                            </div>
                            <div class="col-md-5">
                                <div class="tishi" style="color:green;">已管理注册商</div>
                                <select class="select" name="registrar_ids" id="select2" multiple="multiple">
                                    <?php foreach ($employee_agents as $v) : ?>
                                        <option value="<?php echo $v; ?>">
                                            <?php
                                               echo Service::get_company_name_by_id($v);
                                            ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div style="border-top:1px solid #75736E"></div>
                            </div>
                        </div>
                        <div class="form-actions fluid">
                            <div class="col-md-offset-5 col-md-6">
                                <button id="saveform" class="btn blue" type="button"><i class="icon-save">&nbsp;&nbsp;</i>保存</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        var saveflag = false;
        if(saveflag==false){
            $('#saveform').click(function(){
                saveflag = true;
                var user_id = $('#user_id').val();
                var arr = [];
                $('#select2').find('option').each(function(i) {
                    arr.push(this.value);
                });
                var agent_ids = $('#select2').val();
                if (confirm('确定执行此操作吗')) {
                    var load = layer.load('正在提交，请稍后...');
                    $.ajax({
                        type: 'POST',
                        url: '/audit-auth/scope',
                        data: {'user_id':user_id,'registrar_ids':arr},
                        dataType:'json',
                        success: function (r) {
                            layer.close(load);
                            saveflag = false;
                            if('err'==r.info){
                                common_layer(r.data,'');
                            }else{
                                common_layer(r.data,'/audit-auth/registry_list');
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
        $('.form-group').on('click','#button1',function(){
            var val = $('#select1').val();
            if (val == null) return;
            var str = '';
            for(var i in val)
            {
                var text = $('#select1').find("option[value='"+val[i]+"']").text()
                str += '<option value="'+val[i]+'">'+text+'</option>';
            }
            $('#select2').prepend(str);
            for(var i in val)
            {
                $('#select1').find("option[value='"+val[i]+"']").remove()
            }
        });
        $('.form-group').on('click','#button2',function(){
            var val = $('#select2').val();
            if (val == null) return;
            var str = '';
            for(var i in val)
            {
                var text = $('#select2').find("option[value='"+val[i]+"']").text()
                str += '<option value="'+val[i]+'">'+text+'</option>';
            }
            $('#select1').prepend(str);
            for(var i in val)
            {
                $('#select2').find("option[value='"+val[i]+"']").remove()
            }
        });

    })
</script>