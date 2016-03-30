<?php
    use \yii\helpers\Url;
    use app\models\Registrar;
    use app\models\AuthRole;
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
                    <a>用户管理&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                    <a href="#">角色用户列表</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                </div>
            </div>
            <div class="portlet-body">
                <h3 class="form-section"><?php echo $role_info['name']; ?></h3>
                <table class="table table-striped table-hover table-bordered table-advance">
                    <thead>
                    <tr>
                        <th>登录名</th>
                        <th>用户名</th>
                        <th>用户等级</th>
                        <th>状态</th>
                        <th>创建时间</th>
                        <th width="12%">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if($users) { ?>
                        <?php foreach($users as $list) { ?>
                            <tr>
                                <td><?php echo $list['logonName'];?></td>
                                <td><?php echo $list['userName']?></td>
                                <td><?php echo Service::getRoleName($list['userRole']); ?></td>
                                <td><?php echo $list['status'];?></td>
                                <td><?php echo $list['createTime']?></td>
                                <td>
                                    <?php if('正常'==$list['status']):?>
                                        <a class="open" data-id="<?php echo $list['id'];?>" data-value="deleted" href="javascript:;">删除</a>
                                    <?php else:?>
                                        <a class="open" data-id="<?php echo $list['id'];?>" data-value="ok" href="javascript:;">恢复</a>
                                    <?php endif;?>
                                    <a class="delete" href="<?php echo Url::to(['user/edit','id' => $list['id']]); ?>">
                                        编辑
                                    </a><br/>
                                    <a class="delete" href="<?php echo Url::to(['user/setting','id' => $list['id']]); ?>">分配权限</a><br/>
                                </td>
                            </tr>
                        <?php } ?>
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
<script type="text/javascript">
    $(function(){
        $('.open').click(function(){

            var user_id = $(this).attr('data-id');
            var type = $(this).attr('data-value');
            if ( confirm('确定进行此操作吗?')) {
                $.ajax({
                    type: 'POST',
                    url: '/user/delete',
                    data: {'id':user_id},
                    dataType:'json',
                    success: function (r) {
                        if('err'==r.info){
                            common_layer(r.data,location.href);
                        }else{
                            common_layer(r.data,location.href);
                        }
                    },
                    error: function () {
                        common_layer('删除失败，请稍后重试',location.href);
                    }
                });
            }
        });
    });
</script>