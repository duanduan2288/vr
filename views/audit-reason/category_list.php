<?php
    use yii\widgets\LinkPager;
?>
<style>
.table th a{ color:#000;text-decoration:none;}
.table td a{text-decoration:none;}
.input-small {width: 250px !important;}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-cog"></i>
                    <a>域名审核管理&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                    <a href="#">审核原因类别</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                </div>
            </div>
            <div class="portlet-body form">
                <form class="form-inline" role="form" method="get">
                    <div class="form-body">
                         <div class="form-group">
                             <input type="hidden" name="help" value="" />
                             <input type="checkbox" name="deleted" value="yes" <?php if($deleted=='yes'):?>checked="checked" <?php endif;?>class="form-control"/>隐藏已删除
                        </div>&nbsp;&nbsp;
                        <button class="btn blue" type="submit"><i class="icon-search"></i> 查询</button>
                        <a  id="add_category" class="btn blue" href="javascript:;" style="margin-left:50px;"><i class="icon-plus"></i> 创建原因类别</a>
                    </div>
                </form>
            </div>

            <div class="portlet-body">
                <table class="table table-striped table-hover table-bordered table-advance">
                    <thead>
                        <tr>
                            <th>序号</th>
                            <th>标题</th>
                            <th>创建时间</th>
                            <th width="10%">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($data) { ?>
                            <?php foreach($data as $list) { ?>
                                <tr>
                                    <td><?php echo $list['id']?></td>
                                    <td><?php echo $list['name']?></td>
                                    <td><?php echo $list['created']?></td>
                                    <td>
                                    	<a class="edit" data-value="<?php echo $list['name'];?>" data-id="<?php echo $list['guid'];?>" href="javascript:;">编辑</a>&nbsp;&nbsp;
                                        <?php if($list['deleted']=='否'):?>
                                            <a onclick="return confirm('您确定要删除吗')" href="<?php echo Yii::$app->getUrlManager()->createUrl(['audit-reason/category-delete','guid' => $list['guid']]); ?>" class="delete">删除</a>
                                        <?php endif;?>
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
                <h4 class="modal-title">创建原因类别</h4>
            </div>
            <div class="modal-body form-horizontal">
                <div class="row">
                    <div class="col-md-12">
                         <div class="form-group">
                            <label class="col-md-3 control-label">原因类别</label>
                            <div class="col-md-6">
                                <input type="hidden" id="guid">
                                <input id="name" name="name" type="text" value="" placeholder="" class="form-control" >
                                <span style="color: #b94a48;">
                                    长度应该在1~30位之间
                                </span>
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
            var name = $(this).attr('data-value');
            $('#guid').val(guid);
            $('#name').val(name);
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
            var name = $('#name').val();
            if($.trim(name)==''){
                alert('请输入原因类别');
                return false;
            }
            var guid = $('#guid').val();
            $.ajax({
                type: 'POST',
                url: '/audit-reason/create-category',
                data:{'guid':guid,'name':name},
                dataType:'json',
                success: function(json) {
                    if(json.info=='ok'){
                        common_layer(json.data,'/audit-reason/category-list');
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