<link rel="stylesheet" type="text/css" href="/plugins/jquery-multi-select/css/multi-select.css"/>
<script src="/plugins/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"></script> 
<script src="/plugins/jquery-validation/dist/jquery.validate.bootstrap.js" type="text/javascript"></script>
<script src="/plugins/jquery-multi-select/js/jquery.multi-select.js" type="text/javascript"></script>
<style>
.table th a{ color:#000;text-decoration:none;}
.table td a{text-decoration:none;}
.input-small {width: 150px !important;}
.ms-container {width: 670px;}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-cog"></i>
                    <a>用户管理&nbsp;&nbsp;<i class="icon-angle-right"></i></a>
                    <a href="#">添加菜单</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                    <a href="javascript:history.go(-1);"><i class="icon-reply"></i>返回</a>&nbsp;&nbsp;
                </div>
            </div>

            <div class="portlet-body form">
                <form role="form" id="myform" class="form-horizontal validate" enctype="multipart/form-data" action="/menu/create" method="post">
                    <input type="hidden" name='id' value="<?php echo $model->id; ?>">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>菜单名称<span class="required">*</span></strong></label>
                            <div class="col-md-5">
                                <input type="text" name="Menu[name]" value="<?php echo $model->name; ?>" class="form-control required" data-rule-maxlength="20" data-msg-maxlength="菜单名称不要超过20字">
                                <span class="help-block"></span>
                            </div>
                            <span class="help-inline">菜单名称不要超过10字</span>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>菜单英文名称<span class="required">*</span></strong></label>
                            <div class="col-md-5">
                                <input type="text" name="Menu[name_en]" value="<?php echo $model->name_en; ?>" class="form-control required" data-rule-maxlength="20" data-msg-maxlength="菜单名称不要超过20字">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>排序权重<span class="required">*</span></strong></label>
                            <div class="col-md-5">
                                <input type="text" name="Menu[weight]" value="<?php echo $model->weight; ?>" class="form-control required">
                                <span class="help-block"></span>
                            </div>
                            <span class="help-inline">排序权重为数字，越小越靠前</span>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>图标</strong></label>
                            <div class="col-md-5">
                                <input type="text" name="Menu[image]" value="<?php echo $model->image; ?>" class="form-control">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>菜单链接</strong></label>
                            <div class="col-md-5">
                                <input type="text" name="Menu[link]" value="<?php echo $model->link; ?>" class="form-control">
                                <span class="help-block"></span>
                            </div>
                            <span class="help-inline"></span>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>父级菜单<span class="required">*</span></strong></label>
                            <div class="col-md-5">
                                <select name="Menu[parent_id]" class="form-control">
                                    <option value="0" <?php echo $model->parent_id == 0 ? 'selected' : '';?>>顶级菜单</option>
                                    <?php foreach ($parent_menus as $item): ?>
                                        <option value="<?php echo $item['id']; ?>" <?php echo $model->parent_id == $item['id'] ? 'selected' : '';?>><?php echo $item['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label  class="col-md-3 control-label"><strong>是否默认菜单</strong>&nbsp;&nbsp;</label>
                            <div class="col-md-5">
                                <div class="radio-list">
                                    <label class="radio-inline">
                                        <input type="radio" name="Menu[default_menu]" value="是" <?php echo $model->default_menu == '是' ? 'checked' : '';?> /> 是
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="Menu[default_menu]" value="否" <?php echo $model->default_menu == '否' || $model->default_menu == '' ? 'checked' : '';?> /> 否
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Function</label>
                            <div class="col-md-9">
                                <select multiple="multiple" class="multi-select" id="my_multi_select1" name="function[]">
                                    <?php if(!empty($functions)) : ?>
                                        <?php foreach ($functions as $value) : ?>
                                            <option value='<?php echo $value['id']; ?>' <?php echo in_array($value['id'], $menu_functions)? 'selected' : ''; ?>><?php echo $value['controller'].'/'.$value['action']; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label  class="col-md-3 control-label"><strong>打开方式</strong>&nbsp;&nbsp;</label>
                            <div class="col-md-5">
                                <div class="radio-list">
                                    <label class="radio-inline">
                                        <input type="radio" name="Menu[target]" value="blank" <?php echo $model->target == 'blank' ? 'checked' : '';?> /> 新页面打开
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="Menu[target]" value="self" <?php echo $model->target == 'self' || $model->target == '' ? 'checked' : '';?> /> 本页面打开
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>菜单说明&nbsp;&nbsp;</strong></label>
                            <div class="col-md-5">
                                <textarea class="form-control" rows="4" name="Menu[description]" data-rule-maxlength="500" placeholder="可不填,仅做备注" data-msg-maxlength="说明不要超过500字"><?php echo $model->description; ?></textarea>
                            </div>
                            <span class="help-inline">尽量控制在500文字以内</span>
                        </div>
                        <div class="form-actions fluid">
                            <div class="col-md-offset-3 col-md-6">
                                <button class="btn blue" type="submit"><i class="icon-save">&nbsp;&nbsp;</i>保存</button>
                            </div>
                        </div>
                    </div>
                    <br><br><br><br><br><br><br><br>
                </form>
            </div>
            <!-- END SAMPLE FORM PORTLET-->
        </div>
    </div>
</div>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {
   $('#my_multi_select1').multiSelect();
});
</script>