<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<style>
    .table th a{ color:#000;text-decoration:none;}
    .table td a{text-decoration:none;}
    .input-small,.input-large {width: 260px !important;}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-cog"></i>
                    <a href="javascript:;">域名回访管理<i class="icon-angle-right"></i></a>
                    <a href="#">回访状态管理</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                </div>
            </div>
            <div class="portlet-body form">
                <form class="form-inline" id="search_form" role="form" method="get" action="">
                    <div class="form-body">
                        <input type="hidden" name="help" value="" />
                        <input type="checkbox" name="deleted" value="yes" <?php if($deleted=='yes'):?> checked="checked" <?php endif;?>class="form-control"/>隐藏已删除


                        <button class="btn blue" type="submit"><i class="icon-search"></i> 查询</button>
                        <a  id="add_category" class="btn blue" href="javascript:;" ><i class="glyphicon glyphicon-ok-sign"></i> 创建回访状态</a>
                    </div>
                </form>
            </div>
            <div class="portlet-body auditlist">
                <table class="table table-striped table-hover table-bordered table-advance">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>回访状态</th>
                        <th>备注</th>
                        <th>创建时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <?php if($data) { ?>
                        <?php foreach($data as $list) { ?>
                            <tr>
                                <td><?php echo $list['id']?></td>
                                <td><?php echo $list['review_status']?></td>
                                <td><?php echo $list['remark']?></td>
                                <td><?php echo $list['created']?></td>
                                <?php if($list['type']=='其他'){ ?>
                                <td>
                                    <a class="edit" data-value="<?php echo $list['review_status'];?>" data-remark="<?php echo $list['remark'];?>" data-id="<?php echo $list['guid'];?>" href="javascript:;">编辑</a>&nbsp;&nbsp;
                                    <a onclick="return confirm('您确定要删除吗')" href="<?php echo Yii::$app->getUrlManager()->createUrl(['return/state-delete','guid' => $list['guid']]); ?>" class="delete">删除</a>
                                </td>
                                <?php }else{ ?>
                                <td>
                                    &nbsp;---  &nbsp;　---
                                </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td align="center" colspan="3">无记录</td>
                        </tr>
                    <?php } ?>
                </table>
                <div class="pull-right">

                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .modal-dialog {padding-top: 60px;}
</style>
<div id="stack1" class="modal" tabindex="-1" data-width="400">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close a_clase"></button>
                <h4 class="modal-title">创建回访状态</h4>
            </div>
            <div class="modal-body form-horizontal">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-md-3 control-label">回访状态</label>
                            <div class="col-md-6">
                                <input type="hidden" id="guid">
                                <input id="review_status" name="review_status" type="text" value="" placeholder="" class="form-control" >
                                <span style="color: #b94a48;">
                                    长度应该在1~10位之间
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">备 &nbsp;&nbsp;&nbsp;注&nbsp;&nbsp;</label>
                            <div class="col-md-6">
                                <input type="hidden" id="guid">
                                <textarea class="form-control required" rows="10" name="remark" id="remark" data-rule-maxlength="500" placeholder="请填写备注信息" data-msg-maxlength="备注不要超过500字" style=" resize: none;" ></textarea>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn a_clase">取消</button>
                <button type="button" class="btn blue queren">确认</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        //添加原因类别
        $('.form').on('click','#add_category',function(){
            $('#guid').val('');
            $('#review_status').val('');
            $('#remark').val('');
            i = $.layer({
                type : 1,
                title : false,
                closeBtn : false,
                border : [0],
                area : ['400px',''],
                page : {dom : '#stack1'}
            });
            $('.a_clase').on('click', function(){
                layer.close(i);
                $('#memo').val('');
            });
        });
        //修改原因类别
        $('.portlet-body').on('click','.edit',function(){
            var guid = $(this).attr('data-id');
            var review_status = $(this).attr('data-value');
            var remark = $(this).attr('data-remark');
            $('#guid').val(guid);
            $('#review_status').val(review_status);
            $('#remark').val(remark);

            i = $.layer({
                type : 1,
                title : false,
                closeBtn : false,
                border : [0],
                area : ['400px',''],
                page : {dom : '#stack1'}
            });
            $('.a_clase').on('click', function(){
                layer.close(i);
                $('#memo').val('');
            });
        });
        /**
         * 保存or修改原因类别
         */
        $('#stack1').on('click','.queren',function(){
            var review_status = $('#review_status').val();
            var remark = $('#remark').val();

            if($.trim(review_status)==''){
                alert('请输入回访状态');
                return false;
            }
            var guid = $('#guid').val();
            $.ajax({
                type: 'POST',
                url: '/return/state-create',
                data:{'guid':guid,'review_status':review_status,'remark':remark},
                dataType:'json',
                success: function(json) {
                    if(json.info=='ok'){
                        common_layer(json.data,'/return/state');
                    }else{
                        common_layer(json.data);
                    }
                },
                error:function(){
                    common_layer('系统繁忙，请稍后重试');
                }
            });
        });
    });
</script>
