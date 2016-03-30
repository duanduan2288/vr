<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-cog"></i>
                    <a>域名回访管理&nbsp;&nbsp;<i class="icon-angle-right"></i></a>
                    <a href="#">创建回访状态</a>
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
                            <label class="col-md-3 control-label"><strong>回访状态<span class="required">*</span></strong></label>
                            <div class="col-md-6">
                                <input id="title" autocomplete="off" name="review_status" type="text" value="<?php echo $model->review_status;?>" class="form-control" >
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>备注<span class="required">*</span></strong></label>
                            <div class="col-md-4">
                                <textarea class="form-control required" rows="10" name="remark" data-rule-maxlength="500" placeholder="请填写备注信息" data-msg-maxlength="备注不要超过500字" style="width: 804px; resize: none;" value="<?php echo $model->remark;?>"></textarea>
                            </div>
                        </div>
                        <div class="form-actions fluid">
                            <div class="col-md-offset-3 col-md-6">
                                <button id="saveform" class="btn blue" type="submit"><i class="icon-save">&nbsp;&nbsp;</i>保存</button>
                            </div>
                        </div>
                    </div>
                    <br><br><br><br><br><br><br><br>
                </form>
            </div>
    </div>
    </div>
    </div>
</div>