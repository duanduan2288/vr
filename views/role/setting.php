<style>
.col-md-3 {width: auto;}
.col-md-9 .checkbox-list .checkbox-inline{margin-left: 0;padding-left: 0}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-cog"></i>
                    <a>系统管理&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                    <a href="#">权限设置</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                     <a href="javascript:history.go(-1);"><i class="icon-reply"></i>返回</a>&nbsp;&nbsp;
                </div>
            </div>
            <div class="portlet-body form">
            <form id="myform" class="form-horizontal" enctype="multipart/form-data" action="/role/save" method="post">
                <input id='role_id' type="hidden" name='role_id' value="<?php echo $role['id']; ?>">
                <div class="form-body">
                    <h3 class="form-section"><?php echo $role['platform'].':'.$role['name']; ?></h3>
                    <?php if (!empty($menus)) : ?>
                    <?php foreach ($menus as $key => $value) : ?>
                        <?php if ($value['parent_id']==0) : ?>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><input type="checkbox" name="menu_id[]" class="fu_box" value="<?php echo $value['id']; ?>" <?php if(in_array($value['id'], $role_has_menus)) : ?>checked<?php endif; ?> /><strong><?php echo $value['name']; ?></strong>&nbsp;&nbsp;</label>
                            <div class="col-md-9">
                                <div class="checkbox-list">
                                    <?php foreach ($menus as $k => $v) : ?>
                                        <?php if ($v['parent_id'] == $value['id']) : ?>
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="menu_id[]" class="box" value="<?php echo $v['id']; ?>" <?php if(in_array($v['id'], $role_has_menus)) : ?>checked<?php endif; ?> /> <?php echo $v['name']; ?>
                                        </label>
                                         <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php endif; ?>

                    <div class="form-actions fluid">
                        <div class="col-md-offset-3 col-md-6">
                            <button id="submit" class="btn blue" type="button"><i class="icon-save">&nbsp;&nbsp;</i>保存</button>
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
        $('.form-group').on('click','span',function()
        {
            var index = $('span',$(this).parents('.form-group')).index(this),
                fnAdd = $('span').addClass ,
                fnRemove =  $('span').removeClass,
                fn = null;
            if( !index )
            {
                fn =$(this).hasClass('checked') ? fnAdd : fnRemove  ;
                fn.call( $('span' , $(this).parents('.form-group')),'checked' )
            }
            else
            {
                fn = !!$('span:not(:first).checked', $(this).parents('.form-group') ).length ? fnAdd : fnRemove;
                fn.call($('span:first', $(this).parents('.form-group') ) ,'checked') ;
            }
        })
        questionLoading = false;
        $("#submit").click(function(){
            var role_menu = [];
            var role_id = $('#role_id').val();
            $("span.checked").each(function(){
                var $_input = $('input[type=checkbox]',this);
                if( $_input.length)
                {
                    role_menu.push($_input.val());
                }
            });
            if (role_menu.length == 0) {
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
                data: {'role_id':role_id,'role_menu':role_menu},
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
        })
    })
</script>