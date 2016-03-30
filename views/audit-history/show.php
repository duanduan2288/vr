<?php
    use app\models\Dictionary;
    use app\models\Service;
    use app\models\AuditIssue;
    use app\components\Util;
?>
<link href="/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet"/>
<link href="/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet"/>
<link href="/css/pages/profile.css" rel="stylesheet" type="text/css"/>
<style>
    .sale-summary li .sale-num {
        font-size: 14px;
    }
    .chats li{font-size:14px;line-height:2.2}
    .col-md-2 {
        width: 10.6667%;
    }
    .col-md-offset-2 {
        margin-left: 10.6667%;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                 <div class="caption cap-head">
                    <i class="icon icon-cog"></i>
                    <a>域名审核管理&nbsp;&nbsp;<i class="icon-angle-right"></i></a>
                     <a href="#">审核历史</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                    <a href="javascript:history.go(-1);"><i class="icon-reply"></i>返回</a>&nbsp;&nbsp;
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-9">
            <div class="portlet">
                <div class="portlet-body" >
                    <h4><strong>当前域名&nbsp;&nbsp;&nbsp;<?php echo $issue->domain;?></strong></h4>
                    <hr/>
                    <ul class="chats">
                        <?php if (!empty($issueoperation)):
                            foreach ($issueoperation as $v):
                                if ($v['operator_id']>0):
                                    $company_name = '注册局';
                                    ?>
                                <li class="out">
                                    <span class="avatar" title="<?php echo $company_name;?>"><?php echo Util::cut_str($company_name,3);?></span>
                                    <div class="message">
                                        <span class="arrow">
                                        </span>
                                        <span class="name" style="font-weight: bold;">
                                            <?php echo $company_name.'&nbsp;'.Service::get_user_name($v['operator_id']); ?>
                                            <?php echo '&nbsp;&nbsp;&nbsp;'.$v['audit_status']; ?>
                                        </span>
                                        <span class="datetime" style="font-weight: bold;">
                                             <?php echo $v['created']; ?>
                                        </span>
                                        <span class="body">
                                            <?php if(isset($v['reason']) && !empty($v['reason'])):?>
                                                <strong>拒绝原因：</strong><br/>
                                                <?php echo $v['reason'];?><br/>
                                            <?php endif;?>

                                            <?php if(isset($v['display_reason']) && !empty($v['display_reason'])):?>
                                                <strong>对外显示原因：</strong><br/>
                                                <?php echo $v['display_reason'];?><br/>
                                            <?php endif;?>

                                            <strong>原因备注：</strong><br/>
                                             <?php echo $v['memo']; ?><br/>

                                            <?php if (!empty($v['attachment'])):?>
                                                <strong>相关附件：</strong><br/>
                                                <?php foreach ($v['attachment'] as $url):
                                                    echo $url;
                                                    ?>
                                            <?php endforeach;endif; ?>

                                        </span>
                                    </div>
                                </li>
                        <?php endif;endforeach;endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-3" style="float: right;">
            <div class="portlet sale-summary">
                <div class="portlet-body">
                     <h4><strong>详细信息</strong></h4>
                    <hr/>
                    <ul class="list-unstyled">
                        <li>
                            <span class="sale-info">
                                注册商<i class="fa fa-img-up"></i>
                            </span>
                            <span class="sale-num">
                                <?php echo Service::get_company_name_by_id($auditdata['registrar_id']); ?>
                            </span>
                        </li>
                        <li>
                            <span class="sale-info">
                                代理商<i class="fa fa-img-up"></i>
                            </span>
                            <span class="sale-num">
                                <?php echo Service::get_agent_code_by_contact($auditdata['contact_id']); ?>
                            </span>
                        </li>
                        <li>
                            <span class="sale-info">
                                注册人组织<i class="fa fa-img-up"></i>
                            </span>
                            <span class="sale-num">
                                <?php echo $auditdata['registrant_organization']; ?>
                            </span>
                        </li>
                        <li>
                            <span class="sale-info">
                                注册人姓名<i class="fa fa-img-up"></i>
                            </span>
                            <span class="sale-num">
                                <?php echo $auditdata['registrant_name']; ?>
                            </span>
                        </li>
                        <li>
                            <span class="sale-info">
                                注册类型<i class="fa fa-img-up"></i>
                            </span>
                            <span class="sale-num">
                                <?php echo $auditdata['registrant_type']; ?>
                            </span>
                        </li>
                        <li>
                            <span class="sale-info">
                                注册年限<i class="fa fa-img-up"></i>
                            </span>
                            <span class="sale-num">
                                <?php echo $auditdata['registered_years']; ?>
                            </span>
                        </li>
                        <li>
                            <span class="sale-info">
                                证件类型<i class="fa fa-img-up"></i>
                            </span>
                            <span class="sale-num">
                                <?php echo $auditdata['organization_type']; ?>
                            </span>
                        </li>
                        <li>
                            <span class="sale-info">
                                证件编号<i class="fa fa-img-up"></i>
                            </span>
                            <span class="sale-num">
                                 <?php echo $auditdata['organization_type']=='营业执照'?$auditdata['business_license']:$auditdata['org_code'];?>
                            </span>
                        </li>
                        <li>
                            <span class="sale-info">
                                证书到期时间<i class="fa fa-img-up"></i>
                            </span>
                            <span class="sale-num">
                                 <?php echo $auditdata['tm_expires_date'];?>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>