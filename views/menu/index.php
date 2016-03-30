<?php
    use yii\widgets\LinkPager;
    use app\models\AuthMenu;
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
                    <a><?php echo $platform; ?>菜单&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                    <a href="#"><?php echo $platform; ?>菜单列表</a>
                </div>
                <div class="tools">
                    <a href="/menu/create"><i class="icon-plus"></i>添加</a>&nbsp;&nbsp;
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                    <!-- <a href="javascript:history.go(-1);"><i class="icon-reply"></i>返回</a>&nbsp;&nbsp; -->
                </div>
            </div>
            <div class="portlet-body form">
                <form class="form-inline" role="form" method="get">
                    <input type="hidden" name="type" value="<?php echo $type;?>">
                    <div class="form-body">
                        <div class="form-group">
                            <input type="text" class="form-control input-small" name="search_name" placeholder="请输入菜单名称" value="<?php echo $search_name;?>">
                        </div>
                        <div class="form-group">
                            <select id="search_parent_id" name="search_parent_id" class="form-control input-small">
                                <option value="">请选择父菜单</option>
                                <?php foreach ($parent_menus as $key => $value): ?>
                                    <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                <?php endforeach ?>
                            </select>
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
                            <th><a href="<?php echo $name_sort; ?>" class="<?php echo $order === 'asc' ? 'asc' : 'desc'; ?>">菜单名称</a></th>
                            <th>en</th>
                            <th><a href="<?php echo $weight_sort; ?>" class="<?php echo $order === 'asc' ? 'asc' : 'desc'; ?>">排序</a></th>
                            <th><a href="<?php echo $parent_id_sort; ?>" class="<?php echo $order === 'asc' ? 'asc' : 'desc'; ?>">父菜单</a></th>
                            <th>Link</th>
                            <th>Image</th>
                            <th>是否默认</th>
                            <th>Target</th>
                            <th>删除</th>
                            <th>所属平台</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($menus) { ?>
                            <?php foreach($menus as $list) { ?>
                                <tr>
                                    <td><?php echo $list['id']?></td>
                                    <td><?php echo $list['name']?></td>
                                    <td><?php echo $list['name_en']?></td>
                                    <td><?php echo $list['weight']?></td>
                                    <td><?php echo !empty($list['parent_id'])?AuthMenu::findOne(['id'=>$list['parent_id']])->name:'';?></td>
                                    <td><?php echo $list['link']?></td>
                                    <td><?php echo $list['image']?></td>
                                    <td><?php echo $list['default_menu']?></td>
                                    <td><?php echo $list['target']?></td>
                                    <td><span class="label label-sm label-<?php echo $list['deleted']=='否'?'success':'danger'; ?>"><?php echo $list['deleted']?></span></td>
                                    <td><?php echo $list['platform']?></td>
                                    <td><?php echo $list['created']?></td>
                                    <td>
                                        <a class="delete" href="<?php echo Yii::$app->getUrlManager()->createUrl(['menu/create','id' => $list['id'],'type' => $type]); ?>">编辑</a>&nbsp;&nbsp;
                                        <a onclick="return confirm('您确定要删除吗')" href="<?php echo Yii::$app->getUrlManager()->createUrl(['menu/delete', 'id' => $list['id'],'type' => $type]); ?>" class="delete"><?php echo $list['deleted'] == '是' ? '恢复' : '删除'; ?></a>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td align="center" colspan="13">无记录</td>
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