<link href="/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet"/>
<?php
    use yii\widgets\LinkPager;
    use app\models\Service;
    use yii\helpers\Url;
    use app\models\HelpAudit;
?>
<style>
    .table th a{ color:#000;text-decoration:none;}
    .table td a{text-decoration:none;}
    .input-small {width: 250px !important;}
    .table .asc {
        background: rgba(0, 0, 0, 0) url("/img/asc.png") no-repeat scroll right center;
        padding-right: 15px;
    }
    .table .desc {
        background: rgba(0, 0, 0, 0) url("/img/desc.png") no-repeat scroll right center;
        padding-right: 15px;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-cog"></i>
                    <a>域名回访管理&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                    <a href="/feedback/index">终端客户回访&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                    <a href="#">域名注册列表</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                </div>
            </div>
            <div class="portlet-body form">
                <form class="form-inline" role="form" method="get">
                    <div class="form-body">
                        <div class="form-group">
                            <input type="text" class="form-control input-small" name="registrant_name" placeholder="请输入终端客户名称" value="<?php echo $registrant_name;?>">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control input-small" name="registrant_organization" placeholder="请输入终端客户公司" value="<?php echo $registrant_organization;?>">
                        </div>
                    </div>
                    <div class="form-body">
                        <div class="form-group">
                            <select  name="time_type" class="form-control input-small">
                                <?php foreach($types as $key=>$type):?>
                                    <option value="<?php echo $key;?>" <?php echo $time_type==$key?'selected':''; ?>>
                                        <?php echo $type;?>
                                    </option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="input-group input-large date-picker input-daterange">
                                <input style="cursor: pointer;" placeholder="请输入开始时间" readonly id="start_date" type="text" class="form-control" name="start_date" value="<?php echo $start_date ; ?>">
                                <span class="input-group-addon">至</span>
                                <input style="cursor: pointer;" placeholder="请输入结束时间" readonly id="end_date" type="text" class="form-control" name="end_date" value="<?php echo $end_date ; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <select  name="audit_status" class="form-control input-small">
                                <option value="">请选择审核状态</option>
                                <?php foreach($statuss as $key=>$status):?>
                                    <option value="<?php echo $key;?>" <?php echo $audit_status==$key?'selected':''; ?>>
                                        <?php echo $status;?>
                                    </option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <button class="btn blue" type="submit"><i class="icon-search"></i> 查询</button>
                        <button id="order_download" class="btn blue" type="button"><i class="icon-download"></i> 导出EXCEL</button>
                    </div>
                </form>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-hover table-bordered table-advance">
                    <thead>
                    <tr>
                        <th>域名</th>
                        <th>联系电话</th>
                        <th>联系手机</th>
                        <th>联系邮箱</th>
                        <th>审核状态</th>
                        <th>是否需回访</th>
                        <th>
                            <a href="<?php echo $url;?>" class="<?php echo $sort_created=='desc'?'asc':'desc'?>">
                                注册时间
                            </a>
                        </th>
                        <th>
                            <a href="<?php echo $url;?>" class="<?php echo $sort_update_date=='desc'?'asc':'desc'?>">
                                联系人更新时间
                            </a>
                        </th>
                        <th>
                            <a href="<?php echo $url;?>" class="<?php echo $sort_status=='desc'?'asc':'desc'?>">
                                回访状态
                            </a>
                        </th>
                        <th width="12%">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if($data) { ?>
                        <?php foreach($data as $list) { ?>
                            <tr>
                                <td>
                                    <a href="<?php echo Url::to(['/audit-history/detail','guid'=>$list['guid']]);?>">
                                        <?php if($list['contact_update_flag']=='是'):?>
                                            <span class="badge badge-roundless badge-important">
                                                    new
                                                </span>
                                        <?php endif;?>
                                    <?php echo $list['domain'];?>
                                    </a>
                                </td>
                                <td>
                                    <?php
                                        if(HelpAudit::check_telephone_from_blacklist($list['domain_agent_tel'])):?>
                                            <i class="icon icon-book"></i>
                                        <?php endif;?>
                                    <?php echo $list['domain_agent_tel'];?></td>
                                <td><?php echo $list['domain_agent_mobile'];?></td>
                                <td>
                                    <?php echo $list['domain_agent_email'];?>
                                </td>
                                <td>
                                    <?php echo $list['audit_category'].$list['audit_result'];?>
                                </td>
                                <td><?php echo $list['need_feedback'];?></td>
                                <td><?php echo $list['service_start_time'];?></td>
                                <td>
                                    <?php
                                        echo $list['contact_update'];
                                    ?>
                                </td>
                                <td><?php echo $list['feedback_status'];?></td>
                                <td>
                                    <?php if($list['is_main']=='否'):?>
                                        <a class="add_main" href="javascript:;" data-id="<?php echo  $list['guid'];?>">
                                            设为主要
                                        </a>&nbsp;&nbsp;
                                    <?php endif;?>
                                    <?php if('是'==$list['contact_update_flag']):?>
                                        <a class="cancel_update" href="javascript:;" data-value="audit_data" data-id="<?php echo  $list['guid'];?>">取消标记</a><br/>
                                    <?php endif;?>
                                    <a  class="update_status" href="javascript:;" data-id="<?php echo  $list['guid'];?>">
                                        修改回访状态
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td align="center" colspan="12">无记录</td>
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
<!--修改回访状态-->
<div class="portlet box" id="add-table" style="display: none;width: 700px;">
    <div class="portlet-body" style="overflow: hidden;">
        <form role="form" id="add_form" class="form-horizontal" >
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">回访状态</label>
                    <div class="col-md-6">
                        <select  name="feedback_status" id="feedback_status" class="form-control input-small">
                            <option value="">请选择回访状态</option>
                            <?php foreach($feedbackstatuss as $key=>$status):?>
                                <option value="<?php echo $status;?>">
                                    <?php echo $status;?>
                                </option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="form-actions fluid">
                    <div class="col-md-offset-3 col-md-6">
                        <input type="hidden" name="audit_data_id" id="audit_data_id" />
                        <button class="btn blue" id="add_cancel" type="button">取消</button>&nbsp;&nbsp;&nbsp;&nbsp;
                        <button class="btn blue" id="add_submit" type="button">确定</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="/js/ajaxfileupload.js"></script>
<script type="text/javascript" src="/js/itm-feedback.js"></script>
<script type="text/javascript" >
    jQuery(document).ready(function () {
        $('#order_download').click(function(event) {
            var data = $('#search_form').serialize();
            window.location.href = '/export/domain-list?'+data+'&dtype=excel';
        });
    });
</script>