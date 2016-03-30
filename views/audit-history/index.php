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
                    <a>域名审核管理&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                    <a href="#">审核流水</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                </div>
            </div>
            <div class="portlet-body form">
                <form class="form-inline" id="search_form" role="form" method="get">
                    <div class="form-body">
                        <div class="form-group">
                            <input type="text" class="form-control input-small" name="domain" placeholder="请输入域名" value="<?php echo $domain;?>">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control input-small" name="contact_id" placeholder="请输入注册人Id" value="<?php echo $contact_id;?>">
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
                            <select  name="registrar_epp_id" class="form-control input-small">
                                <option value="">请选择注册商ID</option>
                                <?php foreach($registrar_epp_ids as $epp_id):?>
                                    <option value="<?php echo $epp_id;?>" <?php echo $registrar_epp_id==$epp_id?'selected':''; ?>>
                                        <?php echo $epp_id;?>
                                    </option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="form-group">
                            <select  name="audit_status" class="form-control input-small">
                                <option value="">请选择审核状态</option>
                                <?php foreach($audit_statuss as $key=>$status):?>
                                    <option value="<?php echo $key;?>" <?php echo $audit_status==$key?'selected':''; ?>>
                                        <?php echo $status;?>
                                    </option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="form-group">
                            <select  name="audit_user_id" class="form-control input-small">
                                <option value="">请选择审核人</option>
                                <?php foreach($audit_user_ids as $key=>$name):?>
                                    <option value="<?php echo $key;?>" <?php echo $audit_user_id==$key?'selected':''; ?>>
                                        <?php echo $name;?>
                                    </option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="form-group">
                            <select  name="feedback_status" class="form-control input-small">
                                <option value="">请选择回访状态</option>
                                <?php foreach($feedback_statuss as $status):?>
                                    <option value="<?php echo $status;?>" <?php echo $feedback_status==$status?'selected':''; ?>>
                                        <?php echo $status;?>
                                    </option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <input type="hidden" name="sort_audit_time" value="<?php echo $sort_audit_time;?>">
                        <button class="btn blue" type="submit"><i class="icon-search"></i> 查询</button>
                        <button id="order_download" class="btn blue" type="button"><i class="icon-download"></i> 导出CSV</button>
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
                        <th>审核状态</th>
                        <th>
                            <a href="<?php echo $url;?>" class="<?php echo $sort_audit_time=='desc'?'asc':'desc'?>">
                                审核时间
                            </a>
                        </th>
                        <th>审核人</th>
                        <th>回访状态</th>
                        <th width="10%">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if($data) { ?>
                        <?php foreach($data as $list) { ?>
                            <tr>
                                <td>
                                    <a href="<?php echo Url::to(['/audit-history/detail','guid'=>$list['guid']]);?>"><?php echo $list['domain']?></a>
                                </td>
                                <td><?php echo Service::get_company_name_by_id($list['registrar_id'])?></td>
                                <td>
                                    <?php $code = Service::get_agent_code_by_contact($list['contact_id']);
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
                                <td><?php echo $list['audit_category'].$list['audit_result'];?></td>
                                <td>
                                    <?php echo $list['audit_time']?>
                                </td>
                                <td>
                                    <?php echo Service::get_user_name($list['audit_user_id']);?>
                                </td>
                                <td><?php echo $list['feedback_status'];?></td>
                                <td>
                                    <a  href="<?php echo Yii::$app->getUrlManager()->createUrl(['audit-history/history','id' => $list['issue_id'],'guid'=>$list['guid']]); ?>">历史</a>&nbsp;&nbsp;
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
        $('#order_download').click(function(event) {
            var data = $('#search_form').serialize();
            window.location.href = '/export/history?'+data+'&dtype=csv';
        });
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