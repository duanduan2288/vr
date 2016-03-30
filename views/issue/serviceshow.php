<link href="/css/pages/blog.css" rel="stylesheet" type="text/css"/>
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
                    <i class="icon icon-suitcase"></i>
                    <a href="">代理商支持&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                    <a href="/issue/serviceIssue">客户服务记录&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                    <a href="#">处理客户服务记录</a>
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
                <div class="portlet-body">
                    <h4><strong>#<?php echo $issue->id . ' 客户服务记录 ' . $issue->name; ?></strong></h4>
                    <hr/>
                    <!--<div class="scroller" style="height: auto;" data-always-visible="1" data-rail-visible1="1">-->
                    <ul class="chats">
                        <?php if (!empty($issueoperation)):
                            foreach ($issueoperation as $v):
                                if (!empty($v['content'])):
                                    $company_name = Service::get_company_name_by_id(Service::get_user_company_id_by_user_id($v['creator']));
                                    ?>
                                    <li class="<?php if ($v['creator']<100000) {
                                        echo 'out';
                                    } else {
                                        echo 'in';
                                    } ?>">
                                        <span class="avatar" title="<?php echo $company_name;?>"><?php echo Util::cut_str($company_name,3);?></span>
                                        <div class="message">
                                            <span class="arrow"></span>
                                            <span class="name" style="font-weight: bold;">
                                                <?php echo Service::get_company_name_by_id(Service::get_user_company_id_by_user_id($v['creator']));?>
                                                <?php echo Service::get_user_name($v['creator']); ?>
                                            </span>
                                            <span class="datetime" style="font-weight: bold;">
                                                 <?php echo $v['created']; ?>
                                            </span>
                                            <span class="body">
                                                 <?php echo $v['content']; ?><br/>
                                                <?php if (!empty($v['attachment'])):?>
                                                    <strong>相关附件：</strong><br/>
                                                    <?php foreach ($v['attachment'] as $url):
                                                        echo $url;
                                                        ?>
                                                    <?php endforeach;endif; ?>
                                            </span>
                                        </div>
                                    </li>
                                    <hr/>
                                <?php endif;endforeach;endif; ?>
                        </ul>
                        <?php $user_id = Yii::app()->user->id; ?>
                        <?php //$issue->approval &&  ?>
                    <?php if ($issue->current_state != '已关闭' && $issue->current_state != '已完成'&&($issue->creator==$user_id || $issue->assignee_id==$user_id || $issue->assignee_id==0)): ?>
                    <div class="form">
                        <form role="form" action="/issue/processService" id="fileupload" method="POST"
                              enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-2 control-label"><strong>回复<span class="required">*</span></strong></label>
                                <div class="col-md-9">
                                    <textarea class="col-md-9 form-control" rows="10" name="content"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                    <label class="col-md-2 control-label"><strong>状态<span class="required">*</span></strong></label>

                                    <div class="col-md-5">
                                        <select name="current_state" class="form-control input-medium">
                                            <?php foreach ($statuslist as $status): ?>
                                                <option value="<?php echo $status; ?>">
                                                    <?php if('已提交'==$status){
                                                            $status='待处理';
                                                            }
                                                            if('已关闭'==$status){
                                                                $status="已取消";
                                                            }
                                                            echo $status; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label"><strong>附件&nbsp;&nbsp;</strong></label>
                                <div class="col-md-5">
                                    <div class="fileupload-buttonbar minibtn">
                                            <span class="btn blue fileinput-button">
                                                <i class="icon-upload"></i>
                                                <span>
                                                    添加附件
                                                </span>
                                                <input type="file" id="file_caac" name="file_caac" size="45"
                                                       class="filebtn2" onchange="return ajaxFileUpload(this)"/>
                                            </span>
                                    </div>
                                    <br>
                                    <span class="help-inline">允许上传格式：zip,rar,doc,docx,xls,xlsx,png,jpg,jpeg,gif,pdf 。 大小不超过20M</span>
                                </div>
                            </div>
                            <div class="form-group" style="display:none;">
                                <label class="col-md-2 control-label"><strong></strong></label>

                                <div class="col-md-5" id="attachment">
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <label class="col-md-1 control-label"><strong></strong>&nbsp;&nbsp;</label>

                                <div class="col-md-5">
                                    <div class="checkbox-list">
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="current_state" class="box" value="已完成"/>如已解决请勾选
                                        </label>
                                    </div>
                                </div>
                            </div> -->
                            <div class="form-actions fluid">
                                <div class="col-md-offset-2 col-md-5">
                                    <input type="hidden" name="issue_id" value="<?php echo $issue->guid; ?>">
                                    <button class="margin-top-20 btn blue" type="submit"><i class="icon-save">
                                            &nbsp;&nbsp;</i>提交
                                    </button>
                                </div>
                            </div>
                            <br><br><br><br><br><br><br><br>
                        </form>
                    </div>
                <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-3" style="float: right;">
            <div class="portlet sale-summary">
                <div class="portlet-body">
                    <h4><strong>客户服务记录信息</strong></h4>
                    <hr/>
                    <ul class="list-unstyled">
                        <li>
                            <span class="sale-info">
                                创建人 <i class="fa fa-img-up"></i>
                            </span>
                            <span class="sale-num">
                                <?php echo Service::get_user_name($issue->creator); ?>
                            </span>
                        </li>
                        <?php
                            $attached_data = json_decode($issue->attached_data, true);
                            $big_type = isset($attached_data['big_type'])?$attached_data['big_type'] : '--';
                            $small_type = isset($attached_data['small_type'])?$attached_data['small_type'] : '--';
                            $agent_name = isset($attached_data['agent_name'])?$attached_data['agent_name'] : '--';
                            $diqu = isset($attached_data['diqu'])?$attached_data['diqu'] : '--';
                            $contact = isset($attached_data['contact'])?$attached_data['contact'] : '--';
                            $cellphone = isset($attached_data['cellphone'])?$attached_data['cellphone'] : '--';
                            $customer_type = isset($attached_data['customer_type'])?$attached_data['customer_type'] : '--';
                        ?>
                        <li>
                            <span class="sale-info">
                               服务类型 <i class="fa fa-img-up"></i>
                            </span>
                            <span class="sale-num">
                               <?php echo $big_type.'/'.$small_type;?>
                            </span>
                        </li>
                        <li>
                            <span class="sale-info">
                               代理商 <i class="fa fa-img-up"></i>
                            </span>
                            <span class="sale-num">
                               <?php echo $agent_name;?>
                            </span>
                        </li>
                        <li>
                            <span class="sale-info">
                               地区 <i class="fa fa-img-up"></i>
                            </span>
                            <span class="sale-num">
                               <?php echo $diqu;?>
                            </span>
                        </li>
                        <li>
                            <span class="sale-info">
                               客户类型 <i class="fa fa-img-up"></i>
                            </span>
                            <span class="sale-num">
                               <?php echo $customer_type;?>
                            </span>
                        </li>
                        <li>
                            <span class="sale-info">
                               联系人 <i class="fa fa-img-up"></i>
                            </span>
                            <span class="sale-num">
                               <?php echo $contact;?>
                            </span>
                        </li>
                        <li>
                            <span class="sale-info">
                               联系电话 <i class="fa fa-img-up"></i>
                            </span>
                            <span class="sale-num">
                               <?php echo $cellphone;?>
                            </span>
                        </li>
                        <?php if ($issue->assignee_id > 0): ?>
                            <li>
                                <span class="sale-info">
                                    处理人 <i class="fa fa-img-down"></i>
                                </span>
                                <span class="sale-num">
                                    <?php echo Service::get_user_name($issue->assignee_id); ?>
                                </span>
                            </li>
                        <?php endif; ?>
                        <li>
                            <span class="sale-info">
                                工单状态
                            </span>
                            <span class="sale-num">
                                <?php
                                    if('已关闭'==$issue->current_state){
                                        echo '已取消';
                                    }elseif('已提交'==$issue->current_state){
                                        echo '待处理';
                                    }else{
                                        echo $issue->current_state;
                                    }
                                ?>
                            </span>
                        </li>

                        <li>
                            <span class="sale-info">
                                创建时间
                            </span>
                            <span class="sale-num">
                                <?php echo $issue->created; ?>
                            </span>
                        </li>
                        <?php if ($issue->assignee_id > 0 && $issue->current_state != '已关闭'): ?>
                            <li>
                                <span class="sale-info">
                                    处理时间
                                </span>
                                <span class="sale-num">
                                    <?php echo Service::get_issue_process_time($issue->guid, '处理中'); ?>
                                </span>
                            </li>
                        <?php endif; ?>
                        <?php if ($issue->assignee_id > 0 && $issue->current_state != '已关闭'): ?>
                            <li>
                                <span class="sale-info">
                                    完成时间
                                </span>
                                <span class="sale-num">
                                    <?php echo Service::get_issue_process_time($issue->guid, '已完成'); ?>
                                </span>
                            </li>
                        <?php endif; ?>
                        <?php if ($issue->current_state == '已关闭'): ?>
                            <li>
                                <span class="sale-info">
                                    关闭时间
                                </span>
                                <span class="sale-num">
                                    <?php echo Service::get_issue_process_time($issue->guid, '已关闭'); ?>
                                </span>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/js/ajaxfileupload.js"></script>
<script type="text/javascript" src="/js/issue_show.js"></script>