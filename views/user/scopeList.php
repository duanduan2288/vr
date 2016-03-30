<style>
.table th a{ color:#000;text-decoration:none;}
.table td a{text-decoration:none;}
.input-small {width: 150px !important;}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-cog"></i>
                    <a>后台管理&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                    <a href="#">分配管理范围</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                    <a href="javascript:history.go(-1);"><i class="icon-reply"></i>返回</a>&nbsp;&nbsp;
                </div>
            </div>

            <div class="portlet-body form">
                <form id="myform" class="form-horizontal" enctype="multipart/form-data" method="post">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>用户名称</strong></label>
                            <div class="col-md-6">
                                <input type="hidden" name="guid" value="<?php echo $user->guid;?>" />
                                <input type="text" class="form-control" disabled style="cursor: default;" value="<?php echo Service::get_user_name($user->id);?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>角色名称</strong></label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" disabled style="cursor: default;" value="<?php echo $this->getRoleName($user->user_role); ?>" />
                            </div>
                        </div>
                       <div class="form-group">
                            <label class="col-md-3 control-label"><strong>可管理代理商组</strong></label>
                            <div class="col-md-9">
                                <div class="checkbox-list">
                                    <?php $groups= Service::get_all_groups(); ?>
                                    <?php foreach($groups as $v): ?>
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="agent_group[]" class="box" value="<?php echo $v['id']; ?>" <?php if(in_array($v['id'], $group_ids)) : ?>checked<?php endif; ?> /> <?php echo $v['name']; ?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions fluid">
                            <div class="col-md-offset-3 col-md-6">
                                <button id="saveform" class="btn blue" type="button"><i class="icon-save">&nbsp;&nbsp;</i>保存</button>
                            </div>
                        </div>
                    </div>
                    <br><br><br><br><br><br><br><br>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        var saveflag = false;
        if(saveflag==false){
            $('#saveform').click(function(){
                var i = 0;
                $(".box").each(function(){
                    if($(this).attr("checked")){
                        i++;
                    }
                })
                saveflag = true;
                if (i==0) {
                    common_layer('请选择可管理代理商组','');
                    return;
                }
                var load = layer.load('正在提交，请稍后...');
                $.ajax({
                    type: 'POST',
                    url: '/user/saveScope',
                    data: $('#myform').serialize(),
                    dataType:'json',
                    success: function (r) {
                        layer.close(load);
                        saveflag = false;
                        if('err'==r.info){
                            common_layer(r.data,'');
                        }else{
                            common_layer(r.data,'/user/scope');
                            // window.history.back(-1);
                        }
                    },
                    error: function () {
                        layer.close(load);
                        saveflag = false;
                        common_layer('保存失败','');
                    }
                });
            });
        }else{
            common_layer('数据已提交','');
        }
    })
</script>