<?php
    use yii\helpers\Url;
    use app\models\Service;
    use yii\widgets\LinkPager;
?>
<style>
.table th a{ color:#000;text-decoration:none;}
.table td a{text-decoration:none;}
.input-small {width: 250px !important;}
.form-group select{width: 100px}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon-envelope"></i>
                    <!-- <a>信息管理&nbsp;&nbsp;<i class="icon-angle-right"></a></i> -->
                    <a href="#">消息列表</a>
                </div>
                <div class="tools">
                    <!--<a href="--><?php //echo Url::to(['/notification/create/']); ?><!--"><i class="icon-plus"></i>发消息</a>&nbsp;&nbsp;-->
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i> 刷新</a>&nbsp;&nbsp;
                </div>
            </div>
            <div class="portlet-body form">
                <form class="form-inline" role="form" method="get">
                    <div class="form-body">
                        <div class="form-group">
                            <input type="text" class="form-control input-small" name="search_title" placeholder="请输入消息名称" value="<?php echo $search_title;?>">
                        </div>
                        <div class="form-group">
                            <select name="search_priority" class="form-control required">
                                <option value="" >全部</option>
                                <option value="普通" <?php echo $search_priority == '普通' ? 'selected' : '';?>>普通</option>
                                <option value="紧急" <?php echo $search_priority == '紧急' ? 'selected' : '';?>>紧急</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <select name="search_status" class="form-control required">
                            		<option value="" >全部</option>
                                <option value="已读" <?php echo $search_status == '已读' ? 'selected' : '';?>>已读</option>
                                <option value="未读" <?php echo $search_status == '未读' ? 'selected' : '';?>>未读</option>
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
                            <th width="30%">标题</th>
                            <th>工单</th>
                            <th>发送者</th>
                            <th>接受者</th>
                            <th style="width:80px;">紧急程度</th>
                            <th>状态</th>
                            <th>创建时间</th>
                            <th style="width:100px;">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($notifications) { ?>
                            <?php foreach($notifications as $list) { ?>
                                <tr>
                                    <td><?php echo $list['id']?></td>
                                    <td><a href="/notification/show/id/<?php echo $list['id']?>"><?php echo $list['title']?></a><?php if($list['priority']=='紧急') : ?>&nbsp;&nbsp;&nbsp;<span class="label label-sm label-danger"><?php echo $list['priority']?></span><?php endif; ?></td>
                                    <td><?php echo $list['issue_id']?></td>
                                    <td><?php echo Service::get_user_name($list['creator']);?></td>
                                    <td><?php echo Service::get_user_name($list['receiver']);?></td>
                                    <td><?php echo $list['priority']?></td>
                                    <td><span class="label label-sm label-<?php echo $list['status']=='已读'?'success':'info'; ?>"><?php echo $list['status']?></span></td>
                                    <td><?php echo $list['created']?></td>
                                    <td>
                                    	<a class="delete" href="<?php echo Url::to(['notification/show','id' => $list['id']]); ?>">查看</a>&nbsp;&nbsp;
                                    	<a onclick="return confirm('您确定要删除吗')" href="<?php echo Url::to(['notification/delete','id' => $list['id']]); ?>" class="delete">删除</a>
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