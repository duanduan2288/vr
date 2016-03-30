<?php
    use yii\widgets\LinkPager;
    use yii\db\Query;
    use yii\helpers\Url;
    use app\models\Service;
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
                    <a>用户管理&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                    <a href="#">管理员列表</a>
                </div>
                <div class="tools">
                    <a href="<?php echo Url::to(['user/create']); ?>"><i class="icon-plus"></i>添加</a>&nbsp;&nbsp;
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                </div>
            </div>
            <div class="portlet-body form">
                <form class="form-inline" role="form" method="get">
                    <div class="form-body">
                        <div class="form-group">
                            <input type="text" class="form-control input-small" name="search_username" placeholder="请输入用户名称" value="<?php echo $search_username;?>">
                        </div>
                        <div class="form-group">
                            <div class="input-group input-large date-picker input-daterange">
                                <input style="cursor: pointer;" readonly id="start_date" type="text" placeholder="创建时间" class="form-control" name="start_date" value="<?php echo $start_date ; ?>">
                                <span class="input-group-addon">至</span>
                                <input style="cursor: pointer;" readonly id="end_date" type="text" placeholder="创建时间" class="form-control" name="end_date" value="<?php echo $end_date ; ?>">
                            </div>
                        </div>
                        <button class="btn blue" type="submit"><i class="icon-search"></i> 查询</button>
                    </div>
                </form>
            </div>
            <div class="portlet-body">
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
                                            <a class="open" data-id="<?php echo $list['id'];?>" data-value="deleted" href="javascript:;">删除</a><br/>
                                        <?php else:?>
                                            <a class="open" data-id="<?php echo $list['id'];?>" data-value="ok" href="javascript:;">恢复</a><br/>
                                        <?php endif;?>
                                        <a class="delete" href="<?php echo Url::to(['user/edit','id' => $list['id']]); ?>">
                                            编辑
                                        </a>&nbsp;
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
        var today = GetDateStr(0);
        if (jQuery().datepicker) {
            $('.date-picker').datepicker({
                autoclose: true,
                isRTL: App.isRTL(),
                format: "yyyy-mm-dd",
                endDate: today
            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }

        $('.open').click(function(){

            var user_id = $(this).attr('data-id');
            var type = $(this).attr('data-value');
            if ( confirm('确定进行此操作吗?')) {
                $.ajax({
                    type: 'POST',
                    url: '/user/ip-manage',
                    data: {'user_id':user_id,'ip_limit':name,'ip_limit_flag':'是'},
                    dataType:'json',
                    success: function (r) {
                        if('err'==r.info){
                            common_layer(r.data,location.href);
                        }else{
                            common_layer('开启IP限制成功',location.href);
                        }
                    },
                    error: function () {
                        common_layer('开启IP限制失败，请稍后重试',location.href);
                    }
                });
            }
        });
    });
    function GetDateStr(AddDayCount) {
        var dd = new Date();
        dd.setDate(dd.getDate() + AddDayCount);//获取AddDayCount天后的日期
        var y = dd.getFullYear();
        var m = dd.getMonth() + 1;//获取当前月份的日期
        var d = dd.getDate();
        return y + "-" + m + "-" + d;
    }
</script>