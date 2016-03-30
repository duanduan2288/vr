<link rel="stylesheet" type="text/css" href="/plugins/bootstrap-datepicker/css/datepicker.css"/>
<style>
.table th a{ color:#000;text-decoration:none;}
.table td a{text-decoration:none;}
.input-small,.input-large {width: 250px !important;}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-suitcase"></i>
                    <a>代理商支持&nbsp;&nbsp;<i class="icon-angle-right"></i></a>
                    <a href="#">合规通知列表</a>
                </div>
                <div class="tools">
                    <a href="<?php echo $this->createUrl('/issue/createComplianceIssue'); ?>"><i class="icon-plus"></i> 添加</a>&nbsp;&nbsp;
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                </div>
            </div>
            <div class="portlet-body form">
                <form class="form-inline" role="form" method="get">
                    <div class="form-body">
                        <div class="form-group">
                            <input type="text" class="form-control input-small" name="issue_id" placeholder="通知编号" value="<?php echo $issue_id;?>">
                        </div>
                        <div class="form-group">
                            <div class="input-group input-large date-picker input-daterange">
                                <input style="cursor: pointer;" readonly id="start_date" type="text" class="form-control" name="start_date" value="<?php echo $start_date ; ?>">
                                <span class="input-group-addon">至</span>
                                <input style="cursor: pointer;" readonly id="end_date" type="text" class="form-control" name="end_date" value="<?php echo $end_date ; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <div class="form-group">
                            <input type="text" class="form-control input-small" name="name" placeholder="通知标题" value="<?php echo $name;?>">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control input-small" name="agent_name" placeholder="请输入代理商名称" value="<?php echo $agent_name;?>">
                        </div>
                        <button class="btn blue" type="submit"><i class="icon-search"></i> 查询</button>
                    </div>
                </form>
            </div>

            <div class="portlet-body">
                <ul class="nav nav-tabs">
                    <li class="<?php if($s==''){echo 'active';}?>">
                        <a href="<?php echo $this->createUrl('/issue/complianceIssue');?>">全部通知</a>
                    </li>
                    <li class="<?php if($s==Issue::ISSUE_STATE_COMMITTED){echo 'active';}?>">
                        <a href="<?php echo $this->createUrl('/issue/complianceIssue',array('s'=>Issue::ISSUE_STATE_COMMITTED));?>">待处理通知</a>
                    </li>
                    <li class="<?php if($s==Issue::ISSUE_STATE_PROCESSING){echo 'active';}?>">
                        <a href="<?php echo $this->createUrl('/issue/complianceIssue',array('s'=>Issue::ISSUE_STATE_PROCESSING));?>" >处理中通知</a>
                    </li>
                    <li class="<?php if($s==Issue::ISSUE_STATE_COMPLETED){echo 'active';}?>">
                        <a href="<?php echo  $this->createUrl('/issue/complianceIssue',array('s'=>Issue::ISSUE_STATE_COMPLETED));?>" >已完成通知</a>
                    </li>
                    <!--<li class="--><?php //if($s==Issue::ISSUE_STATE_CANCEL){echo 'active';}?><!--">-->
                    <!--    <a href="--><?php //echo  $this->createUrl('/issue/complianceIssue',array('s'=>Issue::ISSUE_STATE_CANCEL));?><!--" >已取消通知</a>-->
                    <!--</li>-->
                </ul>
                <table class="table table-striped table-hover table-bordered table-advance">
                    <thead>
                    <tr>
                        <th>编号</th>
                        <th>通知标题</th>
                        <th>状态</th>
                        <th>创建人</th>
                        <th>受理公司</th>
                        <th>创建时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if($model) { ?>
                        <?php foreach($model as $list) { ?>
                            <tr>
                                <td><?php echo $list['id'];?></td>
                                <td>
                                    <a href="/issue/complianceView?id=<?php echo $list['guid']; ?>">
                                    <?php echo $list['name'];?>
                                    </a>
                                </td>
                                <td><?php
                                        $status = $list['current_state'];
                                        if($list['current_state']=='已提交'){
                                            $status = '待处理';
                                            $class="label-warning";
                                        }else if($list['current_state']=="处理中"){
                                            $class="label-primary";
                                        }else{
                                            $class="label-success";
                                        }?>
                                    <span class="label label-sm <?php echo $class;?>">
                                        <?php echo '已关闭'==$status?'已取消':$status;?>
                                    </span>
                                    <?php if($list['priority']=='紧急') : ?>&nbsp;&nbsp;&nbsp;
                                        <span class="label label-sm label-danger"><?php echo $list['priority']?></span>
                                    <?php endif; ?></td>
                                <td><?php echo Service::get_user_name($list['creator']);?></td>
                                <td><?php echo Service::get_company_name_by_id($list['assignee_company_id']);?></td>
                                <td><?php echo $list['created'];?></td>
                                <td>
                                    <a class="delete" href="/issue/complianceView?id=<?php echo $list['guid']; ?>">
                                        查看
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
<script type="text/javascript" src="/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="/js/form-components.js"></script>
<script>
    jQuery(document).ready(function() {
        var today = GetDateStr(0);
        if (jQuery().datepicker) {
            $('.date-picker').datepicker({
                autoclose: true,
                isRTL: App.isRTL(),
                format: "yyyy-mm-dd",
                endDate: today
            });
            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        }
    });
    function GetDateStr(AddDayCount) {
        var dd = new Date();
        dd.setDate(dd.getDate()+AddDayCount);//获取AddDayCount天后的日期
        var y = dd.getFullYear();
        var m = dd.getMonth()+1;//获取当前月份的日期
        var d = dd.getDate();
        return y+"-"+m+"-"+d;
    }
</script>