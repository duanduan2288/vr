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
                    <a>代理商管理&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                    <a href="#">代理商角色用户列表</a>
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
                            <th>ID</th>
                            <th>用户名称</th>
                            <th>邮箱</th>
                            <th>所属公司</th>
                            <th>状态</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($users) { ?>
                            <?php foreach($users as $list) { ?>
                                <tr>
                                    <td><?php echo $list['id']?></td>
                                    <td><?php echo $list['last_name'].' '.$list['first_name']; ?></td>
                                    <td><?php echo $list['email']?></td>
                                    <td><?php echo $this->getAgentName($list['agent_id']); ?></td>
                                    <td><?php echo $list['status']?></td>
                                    <td><?php echo $list['created']?></td>
                                    <td>
                                    	<a class="delete" href="<?php echo $this->createUrl('user/registrar_edit', array('id' => $list['id'])); ?>">编辑</a>&nbsp;&nbsp;
                                        <?php if(isset($list['ip']) && $list['ip']):?>
                                            <a class="delete" href="<?php echo $this->createUrl('user/ipmanage', array('id' => $list['id'])); ?>">修改登录ip</a>&nbsp;&nbsp;
                                        <?php else:?>
                                            <a class="delete" href="<?php echo $this->createUrl('user/ipmanage', array('id' => $list['id'])); ?>">添加登录ip</a>&nbsp;&nbsp;
                                        <?php endif;?>
                                        <?php if($list['ip_limit']=='否'):?>
                                            <a class="delete" href="<?php echo $this->createUrl('user/ipadd', array('id' => $list['id'])); ?>">
                                                开启IP限制
                                            </a>&nbsp;&nbsp;
                                        <?php else:?>
                                            <a onclick="return confirm('您确定要关闭IP限制吗')" class="delete" href="<?php echo $this->createUrl('user/closeLimit', array('id' => $list['id'])); ?>">
                                                关闭IP限制
                                            </a>&nbsp;&nbsp;
                                        <?php endif;?>
                                    	<a onclick="return confirm('您确定要执行此操作吗')" href="<?php echo $this->createUrl('user/registrar_delete', array('id' => $list['id'])); ?>" class="delete"><?php echo $list['status']=='正常'?'挂起':'恢复'; ?></a>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td align="center" colspan="7">暂无数据</td>
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