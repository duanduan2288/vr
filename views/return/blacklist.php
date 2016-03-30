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
                    <a href="#">黑名单列表</a>
                </div>
                <div class="tools">
                    <a href="<?php echo Url::to(['return/blacklist-creat']); ?>" onclick=""><i class="icon-plus"></i>添加</a>&nbsp;&nbsp;
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                </div>
            </div>
            <div class="portlet-body form">
                <form class="form-inline" role="form" method="get">
                    <div class="form-body">


                    </div>
                </form>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-hover table-bordered table-advance">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>电话</th>
                        <th>创建时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if($data) { ?>
                        <?php foreach($data as $list) { ?>
                            <tr>
                                <td><?php echo $list['id']?></td>
                                <td><?php echo $list['phone']?></td>
                                <td><?php echo $list['created']?></td>
                                <td>
                                    <a class="delete" href="<?php echo Url::to(['return/blacklist-edit','id' => $list['id']]); ?>"> <i class="icon-edit"></i>修改</a>&nbsp;&nbsp;
                                    <a onclick="return confirm('您确定要删除吗')" href="<?php echo Yii::$app->getUrlManager()->createUrl(['return/blacklist-delete','id' => $list['id']]); ?>" class="delete">删除</a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td align="center" colspan="4">无记录</td>
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

<script>

</script>