<?php
use yii\widgets\LinkPager;
?>
<div class="portlet-body" style="overflow: hidden;">
     <form role="form" id="company_form" class="form-horizontal" >
        <div class="form-body">
            <table class="table table-striped table-hover table-bordered table-advance">
                <thead>
                    <tr>
                        <th width="10%">序号</th>
                        <th>拒绝原因</th>
                        <th width="10%">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($data)):?>
                        <?php foreach($data as $value):?>
                            <tr>
                            <td><?php echo $value['id'];?></td>
                            <td><?php echo $value['content'];?></td>
                            <td>
                                <input type="radio" name="reason_id" class="reason" data-value="<?php echo $value['content'];?>" value="<?php echo $value['id'];?>" />
                            </td>
                        </tr>
                        <?php endforeach;?>
                    <?php else:?>
                        <tr><?php echo '暂无数据,请先添加';?></tr>
                    <?php endif;?>
                </tbody>
             </table>
            <div class="pull-right">
                <?php
                    echo LinkPager::widget([
                        'pagination' => $pages,
                    ]);
                ?>
            </div>
             <div class="form-group">
                <label class="col-md-3 control-label">对外显示原因</label>
                <div class="col-md-6">
                    <input id="display_reason"  name="display_reason" type="text" value="" placeholder="" class="form-control" >
                    <span class="help-block" style="color: #b94a48;" for="display_reason">请慎重修改对外显示原因，此原因将提供给终端客户使用</span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label">原因备注</label>
                <div class="col-md-6">
                    <input id="memo"  name="memo" type="text" value="" placeholder="审核不确定或拒绝可选填" class="form-control" >
                </div>
            </div>
             <div class="form-group">
                <label class="col-md-3 control-label"><strong>附件&nbsp;&nbsp;</strong></label>
                <div class="col-md-6">
                    <div class="fileupload-buttonbar minibtn">
                        <span class="btn blue fileinput-button">
                             <i class="icon-upload"></i>
                            <span>
                                添加附件
                            </span>
                            <input type="file" id="file_caac" name="file_caac" size="45" class="filebtn2" onchange="return ajaxFileUpload(this)"/>
                        </span>
                        <span class="help-block"  id="message_file"></span>
                    </div>
                </div>
            </div>
             <div class="form-group">
                <label class="col-md-3 control-label"><strong></strong></label>
                <div class="col-md-6" id="attachment">
                </div>
            </div>
             <div class="form-actions fluid">
                <div class="col-md-offset-3 col-md-6">
                     <input type="hidden" name="audit_data_id" value="<?php echo $guid;?>" />
                     <input type="hidden" name="type" id="audit_type" value="<?php echo $type;?>" />
                     <button class="btn blue" type="button" id="reject_cancel">取消</button>&nbsp;&nbsp;&nbsp;&nbsp;
                     <button class="btn blue" type="button" id="reject_submit">确定</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $(function() {
        $('#reason-table .pagination a').each(function () {
            $(this).click(function (ev) {
                ev.preventDefault();
                $.get(this.href, {ajax: true}, function (html) {
                    $("#reason-table").empty().append(html);
                });
            });
        });
    });
</script>