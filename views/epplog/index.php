<?php
    use yii\widgets\LinkPager;
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
.form-group select{width: 100px}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-cog"></i>
                    <a href="/epplog">EppLog列表</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                </div>
            </div>
            <div class="portlet-body form">
                <form class="form-inline" role="form" method="get">
                    <div class="form-body">
                        <div class="form-group">
                            <input type="text" class="form-control input-small" name="search_code" placeholder="请输入Log代码" value="<?php echo $search_code;?>">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control input-small" name="search_command" placeholder="请输入命令名称" value="<?php echo $search_command;?>">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control input-small" name="search_company_id" placeholder="请输入公司ID" value="<?php echo $search_company_id;?>">
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
                            <th>Code</th>
                            <th>Command</th>
                            <th>公司</th>
                            <th>操作人</th>
                            <th>操作时间</th>
                            <th>操作人IP</th>
                            <th>查看</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($logs) { ?>
                            <?php foreach($logs as $list) { ?>
                                <tr>
                                    <td><?php echo $list['id']?></td>
                                    <td><?php echo $list['code']?></td>
                                    <td><?php echo $list['command']?></td>
                                    <td><?php echo Service::get_company_name_by_id($list['company_id']); ?></td>
                                    <td><?php echo Service::get_user_name($list['creator']); ?></td>
                                    <td><?php echo $list['created']?></td>
                                    <td><?php echo $list['creator_ip']?></td>
                                    <td>
                                        <a class="delete" href="<?php echo Url::to(['epplog/show','id' => $list['id']]); ?>">查看</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td align="center" colspan="8">无记录</td>
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