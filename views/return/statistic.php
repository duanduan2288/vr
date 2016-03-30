<style>
    .table th a{ color:#000;text-decoration:none;}
    .table td a{text-decoration:none;}
    .input-small,.input-large {width: 260px !important;}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-globe"></i>
                    <a href="javascript:;">域名回访管理<i class="icon-angle-right"></i></a>
                    <a href="#">客户回访统计</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                    <a href="#"><i class="icon-question"></i>帮助</a>&nbsp;&nbsp;
                </div>
            </div>
            <div class="portlet-body form">
                <form class="form-inline" id="search_form" role="form" method="get" action="">
                    <input type="hidden" name="sort" value="<?php// echo $sort; ?>">
                    <input type="hidden" name="order" value="<?php //echo $order; ?>">
                    <div class="form-body">
                        历史状态回顾：&nbsp;&nbsp;&nbsp;&nbsp;
                        <input name="search_company_areas[]" type="checkbox" value="" checked="checked"/>审核通过&nbsp;&nbsp;
                        <input name="search_company_areas[]" type="checkbox" value="" checked="checked"/>需修改电话&nbsp;&nbsp;
                        <input name="search_company_areas[]" type="checkbox" value="" checked="checked"/>需修改邮箱&nbsp;&nbsp;

                        <button class="btn blue" type="submit"><i class="icon-search"></i> 查询</button>
                        <button id="download" class="btn blue" type="button"><i class="icon-download"></i> 导出EXCEL</button>
                    </div>
                </form>
            </div>
            <div class="portlet-body auditlist">
                <table class="table table-striped table-hover table-bordered table-advance">
                    <thead>
                    <tr>
                        <th>注册商</th>
                        <th>代理商</th>
                        <th>域名</th>
                        <th>是否一次通过</th>
                        <th>修改次数</th>
                        <th>历史状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
<!--                    <tbody>-->
<!--                    --><?php //if($data) { ?>
<!--                        --><?php //foreach($data as $list) { ?>
<!--                            <tr>-->
<!---->
<!--                                <td> <a  href="/domainRegister/auditDetail?id=--><?php //echo $list['id'];?><!--" data-id="--><?php //echo $list['id'];?><!--">--><?php //echo $list['domain_name']?><!--</a></td>-->
<!--                                <td>--><?php //echo Service::get_company_name_by_id($list['registrar_id'])?><!--</td>-->
<!--                                <td>--><?php //echo Service::get_contract_head($list['registrar_id']);?><!--</td>-->
<!--                                <td>--><?php //echo $list['created'];$array = ['命名审核拒绝','资料审核拒绝'];?><!--</td>-->
<!--                                <td><button class="btn btn-sm blue audit_state" data-value="--><?php //echo $list['id'];?><!--" data-id="--><?php //echo $list['domain_name'];?><!--" data-mode="2">刷新</button>-->
<!--                                    <span class="audit_status" data-value="--><?php //echo in_array($list['audit_status'],$array) ? Yii::t('Base',$list['reason']):'';?><!--">--><?php //echo $list['audit_status'];?><!--</span>-->
<!--                                </td>-->
<!--                                <td>-->
<!--                                    <span class="last_refresh_time">--><?php //echo $list['last_refresh_time'];?><!--</span>-->
<!--                                </td>-->
<!--                                <td>--><?php //echo $list['source_type'];?><!--</td>-->
<!--                                <td>-->
<!--                                    <a  href="/domainRegister/auditDetail?id=--><?php //echo $list['id'];?><!--" data-id="--><?php //echo $list['id'];?><!--">详情</a>-->
<!--                                </td>-->
<!--                            </tr>-->
<!--                        --><?php //} ?>
<!--                    --><?php //} ?>
<!--                    </tbody>-->
                </table>
                <div class="pull-right">

                </div>
            </div>
        </div>
    </div>
</div>
