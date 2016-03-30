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
                    <a>系统管理&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                    <a href="#">注册局角色用户列表</a>
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
                            <th>姓名</th>
                            <th>角色/ID</th>
                            <th>邮箱</th>
                            <th>手机/电话</th>
                            <th>IP</th>
                            <th>状态</th>
                            <th>IP限制</th>
                            <th>创建时间</th>
                            <th width="12%">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($users) { ?>
                            <?php foreach($users as $list) { ?>
                                <tr>
                                    <td><?php echo $list['last_name'].'&nbsp;'.$list['first_name'];?></td>

                                    <td><?php
                                            $roleinfo = AuthRole::findOne($list['user_role']);
                                            echo !empty($roleinfo) ? $roleinfo->name : '--'; ?><br/>
                                            <?php echo $list['id'];?>
                                    </td>
                                    <td><?php echo $list['email']?></td>
                                    <td><?php echo $list['cellphone']?><br><?php echo $list['phone']?></td>
                                    <td><?php echo $list['ip_limit'];?></td>
                                    <td><span class="label label-sm label-<?php echo $list['status']=='正常'?'success':($list['status']=='挂起'?'danger':'info'); ?>"><?php echo $list['status']=='挂起'?'锁定':$list['status']?></span></td>
                                    <td><span class="label label-sm label-<?php echo $list['ip_limit_flag']=='是'?'danger':'info'; ?>"><?php echo $list['ip_limit_flag']=='是'?'已开启':'已关闭'; ?></span></td>
                                    <td><?php echo $list['created']?></td>
                                    <?php if ($platform == '注册商'): ?>
                                    <td>
                                        <a class="delete" href="<?php echo Url::to(['user/registry_edit', 'id' => $list['guid']]); ?>">编辑</a>&nbsp;&nbsp;
                                        <?php if($list['ip_limit_flag']=='否'):?>
                                            <a class="delete" href="<?php echo  Url::to(['user/ipRegistryManage', 'id' => $list['guid']]); ?>">
                                                开启IP限制
                                            </a>&nbsp;&nbsp;
                                        <?php else:?>
                                            <a onclick="return confirm('您确定要关闭IP限制吗')" class="delete" href="<?php echo Url::to(['user/closeLimit','id' => $list['id']]); ?>">
                                                关闭IP限制
                                            </a>&nbsp;&nbsp;
                                        <?php endif;?>
                                            <br/>
                                        <?php if($list['status']=='挂起'):?>
                                            <a onclick="return confirm('您确定要执行此操作吗')" href="<?php echo Url::to(['user/registry_delete', 'id' => $list['guid']]); ?>" class="delete">
                                                解锁
                                            </a>
                                        <?php endif;?>
                                        <?php if($list['status']=='待激活'):?>
                                            <a onclick="return confirm('您确定要执行此操作吗')" href="<?php echo Url::to(['user/registry_delete','id' => $list['guid']]); ?>" class="delete">
                                                激活
                                            </a>
                                        <?php endif;?>
                                        <?php if($list['status']=='正常'):?>
                                            <a onclick="return confirm('您确定要执行此操作吗')" href="<?php echo Url::to(['user/registry_delete','id' => $list['guid']]); ?>" class="delete">
                                                锁定
                                            </a>
                                        <?php endif;?>&nbsp;&nbsp;
                                        <a class="delete" href="<?php echo Url::to(['user/setting', 'id' => $list['id']]); ?>">分配权限</a>
                                    </td>
                                    <?php else: ?>
                                    <td>
                                        <a class="delete" href="<?php echo Url::to(['user/registrar_edit', 'id' => $list['guid']]); ?>">编辑</a>&nbsp;&nbsp;
                                        <?php if($list['ip_limit_flag']=='否'):?>
                                            <a class="delete" href="<?php echo Url::to(['user/ipmanage','id' => $list['guid']]); ?>">
                                                开启IP限制
                                            </a>&nbsp;&nbsp;
                                        <?php else:?>
                                           <a onclick="return confirm('您确定要关闭IP限制吗')" class="delete" href="<?php echo Url::to(['user/closeLimit','id' => $list['id']]); ?>">
                                                关闭IP限制
                                            </a>&nbsp;&nbsp;
                                        <?php endif;?>
                                        <br/>
                                        <?php if($list['status']=='挂起'):?>
                                            <a onclick="return confirm('您确定要执行此操作吗')" href="<?php echo Url::to(['user/registrar_delete','id' => $list['guid']]); ?>" class="delete">
                                                解锁
                                            </a>
                                        <?php endif;?>
                                        <?php if($list['status']=='待激活'):?>
                                            <a onclick="return confirm('您确定要执行此操作吗')" href="<?php echo Url::to(['user/registrar_delete','id' => $list['guid']]); ?>" class="delete">
                                                激活
                                            </a>
                                        <?php endif;?>
                                        <?php if($list['status']=='正常'):?>
                                            <a onclick="return confirm('您确定要执行此操作吗')" href="<?php echo Url::to(['user/registrar_delete', 'id' => $list['guid']]); ?>" class="delete">
                                                锁定
                                            </a>
                                        <?php endif;?>&nbsp;&nbsp;
                                        <a class="delete" href="<?php echo Url::to(['user/setting','id' => $list['id']]); ?>">分配权限</a>
                                    </td>
                                    <?php endif ?>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td align="center" colspan="10">暂无数据</td>
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