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
                    <a>后台管理&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                    <a href="#">分配管理范围</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                </div>
            </div>
            <div class="portlet-body form">
                <form class="form-inline" role="form" method="get" action="/user/scope">
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
                            <th>ID</th>
                            <th>姓名</th>
                            <th>角色/ID</th>
                            <th>邮箱</th>
                            <th>代理商组</th>
                            <th>管理代理商方式</th>
                            <th>手机/电话</th>
                            <th>状态</th>
                            <th width="12%">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($users) { ?>
                            <?php foreach($users as $list) { ?>
                                <tr>
                                    <td><?php echo $list['id']; ?></td>
                                    <td><?php echo $list['last_name'].$list['first_name'];?></td>
                                    <td><?php echo $this->getRoleName($list['user_role']); ?><br></td>
                                    <td><?php echo $list['email']?></td>
                                    <td>
                                        <?php
                                            $group_ids = Yii::app()->db->createCommand()
                                                    ->select('group_id')
                                                    ->from('user_belong_group')
                                                    ->where("user_id = ".$list['id'])
                                                    ->queryColumn();
                                            if (!empty($group_ids)) {
                                                foreach ($group_ids as $k => $v) {
                                                    echo Service::get_group_name($v).'<br>';
                                                }
                                            }else{
                                                echo '---';
                                            }
                                         ?>
                                    </td>
                                    <td><?php echo $list['agent_type']?></td>
                                    <td><?php echo $list['cellphone'].'<br>'.$list['phone'];?></td>
                                    <td>
                                        <span class="label label-sm label-<?php echo $list['status']=='正常'?'success':($list['status']=='挂起'?'danger':'info'); ?>">
                                            <?php echo $list['status']=='挂起'?'锁定':$list['status']?>
                                        </span>
                                    </td>
                                    <td>
                                        <a class="delete" href="<?php echo $this->createUrl('user/scopeList', array('id' => $list['guid'])); ?>">
                                            编辑管理范围
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="pull-right">      
                    <?php    
                        $this->widget('CLinkPager',array(    
                            'htmlOptions' => array('class' => 'pagination'),
                            'header'=>'',    
                            'firstPageLabel' => '首页',    
                            'lastPageLabel' => '末页',    
                            'prevPageLabel' => '上一页',    
                            'nextPageLabel' => '下一页',
                            'pages' => $pages,
                            'maxButtonCount'=>13
                        ));
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>