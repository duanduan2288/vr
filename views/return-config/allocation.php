<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<style>
    .table th a{ color:#000;text-decoration:none;}
    .table td a{text-decoration:none;}
    .input-small {width: 250px !important;}
    .table .asc {
        padding-right: 15px;
        background: url('/img/asc.png') right center no-repeat;
    }
    .table .desc {
        padding-right: 15px;
        background: url('/img/desc.png') right center no-repeat;
    }
    .table .default_sort {
        padding-right: 15px;
        background: url('/img/default.png') right center no-repeat;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-cog"></i>
                    <a>注册商管理&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                    <a href="#">回访管理配置</a>
                </div>
                <div class="tools">
                    <a  id="add_category"  href="javascript:;"><i class="icon-plus"></i>添加</a>&nbsp;&nbsp;
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-hover table-bordered table-advance">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>策略</th>
                        <th>规则详情</th>
                        <th>处理方式</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if($data) { ?>
                        <?php foreach($data as $list) { ?>
                            <tr>
                                <td><?php echo $list['id']?></td>
                                <td><?php echo $list['strategy']?></td>
                                <td><?php echo $list['detail']?></td>
                                <td><?php echo $list['treatment']?></td>
                                <td><?php echo $list['status']?></td>
                                <td>
                                    <a class="edit" data-strategy="<?php echo $list['strategy'];?>"data-id="<?php echo $list['guid'];?>"  data-detail="<?php echo $list['detail'];?>" data-treatment="<?php echo $list['treatment'];?>" data-status="<?php echo $list['status'];?>" href="javascript:;">编辑</a>&nbsp;&nbsp;
                                    <?php if($list['status']=='有效'){?>
                                    <a onclick="return confirm('确认设置？');" href="<?php echo Yii::$app->getUrlManager()->createUrl(['return-config/delete','id' => $list['id']]); ?>" >设置为无效</a>
                                    <?php }else{?>
                                    <a onclick="return confirm('确认设置？');" href="<?php echo Yii::$app->getUrlManager()->createUrl(['return-config/delete','id' => $list['id']]); ?>" >设置为有效</a>
                            <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td align="center" colspan="6">无记录</td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="pull-right">
                    <?php
                    echo LinkPager::widget([
                        'pagination' => $pages,
                    ]);
                    ?>
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
                <h4 class="modal-title">创建回访管理配置</h4>
            </div>
            <div class="modal-body form-horizontal">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-md-3 control-label">策略</label>
                            <div class="col-md-6">
                                <input type="hidden" id="guid">
                                <select class="form-control" name="strategy" id="strategy">
                                    <option value=''>--请选择策略--</option>
                                    <option>全部</option>
                                    <option>按注册商ID</option>
                                    <option>按联系人识别码</option>
                                    <option>按是否需要回访标记</option>
                                    <option>按终端客户ID</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" >规则详情</label>
                            <div class="col-md-6">
                                <input type="hidden" id="guid">
                                <input id="detail" name="detail" type="text" value="" placeholder="" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">处理方式</label>
                            <div class="col-md-6">
                                <input type="hidden" id="guid">
                                <select class="form-control" id="treatment" name="treatment">
                                    <option value=''>--请选择处理方式--</option>
                                    <option>需回访</option>
                                    <option>无需回访直接通过</option>
                                    <option>无需回访直接不通过</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">状态</label>
                            <div class="col-md-6">
                                <input type="hidden" id="guid">
<!--                                <input name="status" id="status" type="checkbox" value="有效" checked="checked" />有效-->
<!--                                <input name="status" id="status" type="checkbox" value="无效" checked="checked" />无效-->
                               <select class="form-control" name="status" id="status">
                                    <option value=''>--请选择状态--</option>
                                   <option>有效</option>
                                   <option>无效</option>
                                </select>
                            </div>
                        </div>
<!--                        <div class="form-group">-->
<!--                            <label class="col-md-3 control-label" >状态</label>-->
<!--                            <div class="col-md-6" style="padding-top: 8px;">-->
<!--                                <input type="hidden" id="guid">-->
<!--                                <input name="status" id="status" type="checkbox" value="有效" checked="checked" />有效-->
<!--                                <input name="status" id="status" type="checkbox" value="无效" checked="checked" />无效-->
<!--                            </div>-->
<!--                        </div>-->
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
        $('.tools').on('click','#add_category',function(){
            $('#strategy').val('');
            $('#detail').val('');
            $('#treatment').val('');
            $('#status').val('');
            $('#guid').val('');
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
            var strategy = $(this).attr('data-strategy');
            var detail = $(this).attr('data-detail');
            var treatment = $(this).attr('data-treatment');
            var status = $(this).attr('data-status');
            var guid = $(this).attr('data-id');
            $('#strategy').val(strategy);
            $('#detail').val(detail);
            $('#treatment').val(treatment);
            $('#status').val(status);
            $('#guid').val(guid);

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
            var strategy = $('#strategy').val();
            var detail = $('#detail').val();
            var treatment = $('#treatment').val();
            var status = $('#status').val();
            var guid = $('#guid').val();

            $.ajax({
                type: 'POST',
                url: '/return-config/create',
                data:{'strategy':strategy,'detail':detail,'treatment':treatment,'status':status,'guid':guid},
                dataType:'json',
                success: function(json) {
                    if(json.info=='ok'){
                        common_layer(json.data,'/return-config/index');
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


