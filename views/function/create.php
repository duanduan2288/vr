<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-cog"></i>
                    <a>用户管理&nbsp;&nbsp;<i class="icon-angle-right"></i></a>
                    <a href="#">添加Function</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                    <a href="javascript:history.go(-1);"><i class="icon-reply"></i>返回</a>&nbsp;&nbsp;
                </div>
            </div>

            <div class="portlet-body form">
                <form role="form" id="myform" class="form-horizontal validate" enctype="multipart/form-data" action="/function/create" method="post">
                    <input type="hidden" name='id' value="<?php echo $model->id; ?>">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>控制器名称<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <input type="text" name="Fun[controller]" value="<?php echo $model->controller; ?>" class="form-control required" data-rule-maxlength="20" data-msg-maxlength="控制器名称不要超过20字">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>方法名称<span class="required">*</span></strong></label>
                            <div class="col-md-3">
                                <input type="text" name="Fun[action]" value="<?php echo $model->action; ?>" class="form-control required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>URL</strong></label>
                            <div class="col-md-3">
                                <input type="text" name="Fun[url]" value="<?php echo $model->url; ?>" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>说明&nbsp;&nbsp;</strong></label>
                            <div class="col-md-5">
                                <textarea class="form-control" rows="4" name="Fun[name]" data-rule-maxlength="150" placeholder="可不填,仅做备注" data-msg-maxlength="说明不要超过100字"><?php echo $model->name; ?></textarea>
                            </div>
                            <span class="help-inline">尽量控制在100文字以内</span>
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
        </div>
    </div>
</div>