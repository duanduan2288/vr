<?php
    use yii\widgets\LinkPager;
    use app\models\Service;
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
                    <a>域名审核管理&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                    <a href="#">域名复审</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                </div>
            </div>
            <div class="portlet-body form">
                <form class="form-inline" role="form" method="get">
                    <div class="form-body">
                        <div class="form-group">
                            <input type="text" class="form-control input-small" name="domain" placeholder="请输入域名" value="<?php echo $domain;?>">
                        </div>
                        <div class="form-group">
                            <div class="input-group input-large date-picker input-daterange">
                                <input style="cursor: pointer;" readonly id="start_date" type="text" class="form-control" name="start_date" value="<?php echo $start_date ; ?>">
                                <span class="input-group-addon">至</span>
                                <input style="cursor: pointer;" readonly id="end_date" type="text" class="form-control" name="end_date" value="<?php echo $end_date ; ?>">
                            </div>
                        </div>
                        <button class="btn blue" type="submit"><i class="icon-search"></i> 查询</button>
                    </div>
                </form>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-hover table-bordered table-advance">
                    <thead>
                    <tr>
                        <th>域名</th>
                        <th>注册商</th>
                        <th>代理商</th>
                        <th>注册人组织/姓名</th>
                        <th>注册类型</th>
                        <th>注册年限</th>
                        <th>证件类型/证件编号</th>
                        <th>证书到期日期</th>
                        <th>
                            <a href="<?php echo $url;?>" class="<?php echo $sort_start_date=='desc'?'asc':'desc'?>">
                                注册时间/剩余天数
                            </a>
                        </th>
                        <th>
                            <a href="<?php echo $url;?>" class="<?php echo $sort_status=='desc'?'asc':'desc'?>">
                                回访状态
                            </a>
                        </th>
                        <th width="10%">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if($data) { ?>
                        <?php foreach($data as $list) { ?>
                            <tr>
                                <td>
                                    <?php echo $list['domain']?>
                                </td>
                                <td><?php echo Service::get_company_name_by_id($list['registrar_id'])?></td>
                                <td><?php
                                        $code = Service::get_agent_code_by_contact($list['contact_id']);
                                        echo Service::get_agent_name_by_code($code);?>
                                </td>
                                <td>
                                    <?php echo $list['registrant_organization'];?><br/>
                                    <?php echo $list['registrant_name'];?>
                                </td>
                                <td><?php echo $list['registrant_type'];?></td>
                                <td><?php echo $list['registered_years'];?></td>
                                <td>
                                    <?php
                                        echo $list['organization_type'];
                                    ?><br/>
                                    <?php echo $list['organization_type']=='营业执照'?$list['business_license']:$list['org_code'];?>
                                </td>
                                <td><?php echo $list['tm_expires_date'];?></td>
                                <td>
                                    <?php echo $list['service_start_time']?><br/>
                                    <?php echo Service::get_remaining_days($list['service_start_time']);?>
                                </td>
                                <td><?php echo $list['feedback_status'];?></td>
                                <td>
                                    <a  href="<?php echo Yii::$app->getUrlManager()->createUrl(['audit-history/history','id' => $list['issue_id'],'guid'=>$list['guid']]); ?>">历史</a>&nbsp;&nbsp;
                                    <?php
                                        if('初审'==$list['audit_category'] && '审核通过'==$list['audit_result']):
                                            ?>
                                            <a class="delete" href="<?php echo Yii::$app->getUrlManager()->createUrl(['review-audit/audit','guid' => $list['guid']]); ?>">审核</a>&nbsp;&nbsp;
                                        <?php endif;?>
                                    <?php
                                        $feedbackurl = HelpAudit::get_feedback_url($list['registrant_name']);
                                        if($feedbackurl):?>
                                        <a  href="<?php echo $feedbackurl;?>" data-id="<?php echo  $list['guid'];?>">回访查看</a>
                                    <?php endif;?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td align="center" colspan="11">无记录</td>
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
<script>
    jQuery(document).ready(function () {
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
        dd.setDate(dd.getDate() + AddDayCount);//获取AddDayCount天后的日期
        var y = dd.getFullYear();
        var m = dd.getMonth() + 1;//获取当前月份的日期
        var d = dd.getDate();
        return y + "-" + m + "-" + d;
    }
</script>