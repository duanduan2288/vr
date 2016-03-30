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
                    <a href="#">审核原因列表</a>
                </div>
                <div class="tools">
                    <a href="/audit-reason/create"><i class="icon-plus"></i>添加</a>&nbsp;&nbsp;
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                </div>
            </div>
            <div class="portlet-body form">
                <form class="form-inline" role="form" method="get">
                    <div class="form-body">
                        <div class="form-group">
                            <select name="category_id" class="form-control required">
                                <option value="">请选择原因类别</option>
                                <?php if(!empty($categorys)) : ?>
                                    <?php foreach ($categorys as $key=>$value) : ?>
                                        <option value='<?php echo $key; ?>' <?php if($key==$category_id):?>selected="selected" <?php endif;?>><?php echo $value; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                         <div class="form-group">
                             <input type="checkbox" name="deleted" value="yes" <?php if($deleted=='yes'):?> checked="checked" <?php endif;?>class="form-control"/>隐藏已删除
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
                            <th width="15%">原因类别</th>
                            <th width="30%">内容</th>
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
                                    <td><?php echo $list['content']?></td>
                                    <td><?php echo $list['created']?></td>
                                    <td>
                                    	<a class="delete" href="<?php echo Yii::$app->getUrlManager()->createUrl(['audit-reason/create','guid' => $list['guid']]); ?>">编辑</a>&nbsp;&nbsp;
                                        <?php if($list['deleted']=='否'):?>
                                            <a onclick="return confirm('您确定要删除吗');" href="<?php echo Yii::$app->getUrlManager()->createUrl(['audit-reason/delete','guid' => $list['guid']]); ?>" class="delete">删除</a>
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