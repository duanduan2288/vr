<?php
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
                    <a>用户管理&nbsp;&nbsp;<i class="icon-angle-right"></i></a>
                    <a href="#">Function列表</a>
                </div>
                <div class="tools">
                    <a href="/function/create"><i class="icon-plus"></i>添加</a>&nbsp;&nbsp;
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                </div>
            </div>
            <div class="portlet-body form">
                <form class="form-inline" role="form" method="get">
                    <div class="form-body">
                        <div class="form-group">
                            <input type="text" class="form-control input-small" name="search_controller" placeholder="请输入控制器名称" value="<?php echo $search_controller;?>">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control input-small" name="search_action" placeholder="请输入方法名称" value="<?php echo $search_action;?>">
                        </div>
                        <button class="btn blue" type="submit"><i class="icon-search"></i> 查询</button>
                    </div>
                </form>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-hover table-bordered table-advance">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Controller</th>
                            <th>Action</th>
                            <th>说明</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($functions) { ?>
                            <?php foreach($functions as $list) { ?>
                                <tr>
                                    <td><?php echo $list['id']?></td>
                                    <td><?php echo $list['controller']?></td>
                                    <td><?php echo $list['action']?></td>
                                    <td><?php echo $list['name']?></td>
                                    <td><?php echo $list['created']?></td>
                                    <td>
                                    		<a class="delete" href="<?php echo Yii::$app->getUrlManager()->createUrl(['function/create','id' => $list['id'],'type' => $type]); ?>">设置</a>&nbsp;&nbsp;
                                    		<a onclick="return confirm('您确定要删除吗')" href="<?php echo Yii::$app->getUrlManager()->createUrl(['function/delete','id' => $list['id'],'type' => $type]); ?>" class="delete">删除</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td align="center" colspan="7">无记录</td>
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