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
                    <a>域名回访管理&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                    <a href="#">回访权限管理</a>
                </div>
                <div class="tools">
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
                            <input type="text" class="form-control input-small" name="search_email" placeholder="请输入用户邮箱" value="<?php echo $search_email;?>">
                        </div>
                        <button class="btn blue" type="submit"><i class="icon-search"></i> 查询</button>
                    </div>
                </form>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-hover table-bordered table-advance">
                    <thead>
                    <tr>
                        <th>姓名</th>
                        <th>邮箱</th>
                        <th width="30%">当前回访注册商</th>
                        <th width="30%">当前回访代理商</th>
                        <th>管理方式</th>
                        <th width="12%">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if($users) { ?>
                        <?php foreach($users as $list) { ?>
                            <tr>
                                <td><?php echo $list['last_name'].$list['first_name'];?></td>
                                <td><?php echo $list['email']?></td>
                                <td><?php
                                        $data = Service::get_registrar_id_by_user_id($list['id']);
                                        echo implode('<span style="color:red;"> | </span>',$data['name']);
                                    ?>
                                </td>
                                <td><?php
                                        $data = Service::get_agent_id_by_user_id($list['id']);
                                        echo implode('<span style="color:red;"> | </span>',$data['name']);
                                    ?>
                                </td>
                                <td><?php
                                        echo $list['manage_type'];
                                    ?>
                                </td>
                                <td>
                                    <a  href="<?php echo Url::to(['auth-feedback/setting','id' => $list['guid']]); ?>" class="delete">
                                        修改管理方式
                                    </a><br/>
                                    <?php if('注册商方式'==$list['manage_type']):?>
                                        <a  href="<?php echo Url::to(['auth-feedback/registrar','id' => $list['guid']]); ?>" class="delete">
                                            回访注册商调整
                                        </a><br/>
                                    <?php endif;?>
                                    <?php if('代理商方式'==$list['manage_type']):?>
                                        <a  href="<?php echo Url::to(['auth-feedback/agent','id' => $list['guid']]); ?>" class="delete">
                                            回访代理商调整
                                        </a>
                                    <?php endif;?>
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