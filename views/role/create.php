<style>
    .col-md-3 .checkbox-list{margin-bottom: 15px;}
    .col-md-3 .checkbox-list .checkbox-inline{margin-left: 0;padding-left: 0}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-cog"></i>
                    <a>系统管理&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                    <a href="#">添加<?php echo $platform; ?>角色</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                    <a href="javascript:history.go(-1);"><i class="icon-reply"></i>返回</a>&nbsp;&nbsp;
                </div>
            </div>

            <div class="portlet-body form">
                <form role="form" id="myform" class="form-horizontal validate" enctype="multipart/form-data" method="post">
                    <input id="role_id" type="hidden" name='id' value="<?php echo $model->id; ?>">
                    <input id="role_type" type="hidden" value="<?php echo $type; ?>">
                    <input id="role_platform" type="hidden" value="<?php echo $platform; ?>">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>所属<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <input type="text" class="form-control required" value="<?php echo $platform; ?>" disabled  />
                                <input type="hidden" name="Role[platform]" value="<?php echo $platform; ?>"  />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>角色中文名称<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <input id="role_name" type="text" name="Role[name]" value="<?php echo $model->name; ?>" class="form-control required" data-rule-maxlength="50" data-msg-maxlength="角色名称不要超过50字">
                                <span class="help-block"></span>
                            </div>
                            <span class="help-inline">角色名称不要超过50字</span>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>角色英文名称</strong></label>
                            <div class="col-md-3">
                                <input id="role_name_en" type="text" name="Role[name_en]" value="<?php echo $model->name_en; ?>" class="form-control" data-rule-maxlength="50" data-msg-maxlength="角色英文名称不要超过50字">
                                <span class="help-block"></span>
                            </div>
                            <span class="help-inline">角色英文名称不要超过50字</span>
                        </div>

                        <?php if (!empty($menus)) : ?>
                            <div class="form-group">
                                <label class="col-md-3 control-label"></label>
                                <div class="col-md-9">
                                    <?php foreach ($menus as $key => $value) : ?>
                                        <?php if ($value['parent_id']==0) : ?>
                                            <div class="checkbox-list">
                                                <label class="checkbox-inline">
                                                   <input type="checkbox" class="fu_box" value="<?php echo $value['id']; ?>" <?php if(in_array($value['id'], $role_has_menus)) : ?>checked<?php endif; ?> /><strong><?php echo $value['name']; ?></strong>
                                                </label>
                                                <?php foreach ($menus as $k => $v) : ?>
                                                    <?php if ($v['parent_id'] == $value['id']) : ?>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="box" value="<?php echo $v['id']; ?>" <?php if(in_array($v['id'], $role_has_menus)) : ?>checked<?php endif; ?> /> <?php echo $v['name']; ?>
                                                    </label>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="form-actions fluid">
                            <div class="col-md-offset-3 col-md-6">
                                <button class="btn blue" type="button" id="submit"><i class="icon-save">&nbsp;&nbsp;</i>保存</button>
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
        $('.checkbox-list').on('click','span',function()
        {
            var index = $('span',$(this).parents('.checkbox-list')).index(this),
                fnAdd = $('span').addClass ,
                fnRemove =  $('span').removeClass,
                fn = null;
            if( !index )
            {
                fn =$(this).hasClass('checked') ? fnAdd : fnRemove  ;
                fn.call( $('span' , $(this).parents('.checkbox-list')),'checked' )
            }
            else
            {
                fn = !!$('span:not(:first).checked', $(this).parents('.checkbox-list') ).length ? fnAdd : fnRemove;
                fn.call($('span:first', $(this).parents('.checkbox-list') ) ,'checked') ;
            }
        })
        questionLoading = false;
        $("#submit").click(function(){
            var role_menu = [];
            var role_id = $('#role_id').val();
            var role_name = $('#role_name').val();
            var role_name_en = $('#role_name_en').val();
            var role_type = $('#role_type').val();
            var role_platform = $('#role_platform').val();
            if (role_name == '') {
                layer.msg('请填写角色名称',2,-1);
                $("#role_name").focus();
                return;
            }
            $("span.checked").each(function(){
                var $_input = $('input[type=checkbox]',this);
                if( $_input.length)
                {
                    role_menu.push($_input.val());
                }
            });
            if (role_menu == '') {
                layer.msg('请为角色选择权限',2,-1);
                return;
            }
            if (questionLoading == true) {
                layer.msg('正在处理中请稍后...',2,-1);
                return;
            }
            var loadi = layer.load('加载中…',1);
            $.ajax({
                type: 'POST',
                url: '/role/save',
                data: {'role_id':role_id,'role_name':role_name,'role_name_en':role_name_en,'role_menu':role_menu,'role_platform':role_platform},
                dataType:'json',
                success: function(json) {
                    // alert(json.info);return;
                    layer.close(loadi);
                    questionLoading = false;
                    if (json.info != 'ok') {
                        layer.msg(json.msg,2,-1);
                        return false;
                    }
                    layer.msg(json.msg, 2, 1, function(){window.location.href='/role/index';});
                  }
            });
            // alert(aa);
        })
    })
</script>