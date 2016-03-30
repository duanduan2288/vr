<link href="/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet"/>
<style>
    .control-label{font-weight:bold;}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-cog"></i>
                    <a href="javascript:;">域名审核管理&nbsp;&nbsp;<i class="icon-angle-right"></i></a>
                    <a href="/audit-data/index">初审</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                    <a href="javascript:history.go(-1);"><i class="icon-reply">&nbsp;</i>返回</a>&nbsp;&nbsp;
                </div>
            </div>
            <div class="portlet-body form">
                <form role="form" class="form-horizontal" >
                    <div class="form-wizard">
                        <div class="form-body">
                            <!--域名信息-->
                                <h4 class="form-section">域名信息</h4>
                                <div class="form-group">
                                    <label class="control-label col-md-3">注册域名:</label>
                                    <div class="col-md-4">
                                        <p class="form-control-static">
                                             <?php echo $auditdata['domain'];?>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">域名注册年限:</label>
                                    <div class="col-md-4">
                                        <p class="form-control-static">
                                            <?php echo isset($auditdata['registered_years'])?$auditdata['registered_years']:'';?>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">域名注册时间:</label>
                                    <div class="col-md-4">
                                        <p class="form-control-static">
                                            <?php echo isset($auditdata['service_start_time'])?$auditdata['service_start_time']:'';?>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">域名解析url:</label>
                                    <div class="col-md-4">
                                        <p class="form-control-static">
                                            <?php echo isset($auditdata['parse_url'])?$auditdata['parse_url']:'';?>
                                        </p>
                                    </div>
                                </div>
                                <h4 class="form-section">商标信息</h4>
                            <?php if(null!==$auditdata):?>
                                <div class="form-group">
                                    <label class="control-label col-md-3">商标注册类型:</label>
                                    <div class="col-md-4">
                                        <p class="form-control-static">
                                            <?php echo $auditdata['registrant_type'];?>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">商标注册号:</label>
                                    <div class="col-md-4">
                                        <p class="form-control-static">
                                            <?php echo $auditdata['trademark_reg_no'];?>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">商标注册类别:</label>
                                    <div class="col-md-4">
                                        <p class="form-control-static">
                                            <?php echo $auditdata['tm_class_type'];?>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">商标颁发国:</label>
                                    <div class="col-md-4">
                                        <p class="form-control-static">
                                            <?php echo $auditdata['tm_issuing_country'];?>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">商标到期日期:</label>
                                    <div class="col-md-4">
                                        <p class="form-control-static">
                                            <?php echo $auditdata['tm_expires_date'];?>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">组织机构代码证编号:</label>
                                    <div class="col-md-4">
                                        <p class="form-control-static">
                                            <?php echo $auditdata['org_code'];?>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">营业执照编号:</label>
                                    <div class="col-md-4">
                                        <p class="form-control-static">
                                            <?php echo $auditdata['business_license'];?>
                                        </p>
                                    </div>
                                </div>
                                <?php if(!empty($auditdata['business_file'])):?>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">营业执照:</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static">
                                                <a href="/upload/showuploadfile?id=<?php echo $auditdata['business_file'];?>" target="_blank">
                                                    <img src="/upload/showuploadfile?id=<?php echo $auditdata['business_file'];?>" style="width:100px;height:100px;" title="点击看大图"/>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <?php if(!empty($auditdata['business_file'])):?>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">营业执照:</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static">
                                                <a href="/upload/showuploadfile?id=<?php echo $auditdata['business_file'];?>" target="_blank">
                                                    <img src="/upload/showuploadfile?id=<?php echo $auditdata['business_file'];?>" style="width:100px;height:100px;" title="点击看大图"/>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <?php if(!empty($auditdata['id_file'])):?>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">联系人证件:</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static">
                                                <a href="/upload/showuploadfile?id=<?php echo $auditdata['id_file'];?>" target="_blank">
                                                    <img src="/upload/showuploadfile?id=<?php echo $auditdata['id_file'];?>" style="width:100px;height:100px;" title="点击看大图"/>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <?php if(!empty($auditdata['org_file'])):?>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">组织机构证:</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static">
                                                <a href="/upload/showuploadfile?id=<?php echo $auditdata['org_file'];?>" target="_blank">
                                                    <img src="/upload/showuploadfile?id=<?php echo $auditdata['org_file'];?>" style="width:100px;height:100px;" title="点击看大图"/>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <?php if(!empty($auditdata['other_file'])):?>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">其他:</label>
                                        <div class="col-md-4">
                                            <?php
                                                $files = json_decode($auditdata['other_file'],true);
                                                foreach((array)$files as $file):
                                            ?>
                                            <p class="form-control-static">
                                                <a href="/upload/showuploadfile?id=<?php echo $file;?>" target="_blank">
                                                    <img src="/upload/showuploadfile?id=<?php echo $file;?>" style="width:100px;height:100px;" title="点击看大图"/>
                                                </a>
                                            </p>
                                            <?php endforeach;?>
                                        </div>
                                    </div>
                                <?php endif;?>
                            <?php endif;?>
                                <h4 class="form-section">注册联系人信息</h4>
                                <div class="form-group">
                                    <label class="control-label col-md-3">注册人ID:</label>
                                    <div class="col-md-4">
                                        <p class="form-control-static">
                                            <?php echo $auditdata['contact_id'];?>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">注册人组织:</label>
                                    <div class="col-md-4">
                                        <p class="form-control-static">
                                            <?php echo $auditdata['registrant_organization'];?>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">注册人姓名:</label>
                                    <div class="col-md-4">
                                        <p class="form-control-static">
                                            <?php echo $auditdata['registrant_name'];?>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">域名经办人:</label>
                                    <div class="col-md-4">
                                        <p class="form-control-static">
                                            <?php echo $auditdata['domain_agent'];?>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">域名经办人电话:</label>
                                    <div class="col-md-4">
                                        <p class="form-control-static">
                                            <?php echo $auditdata['domain_agent_tel'];?>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">域名经办人手机:</label>
                                    <div class="col-md-4">
                                        <p class="form-control-static">
                                            <?php echo $auditdata['domain_agent_mobile'];?>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">域名经办人邮箱:</label>
                                    <div class="col-md-4">
                                        <p class="form-control-static">
                                            <?php echo $auditdata['domain_agent_email'];?>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">域名经办人地址:</label>
                                    <div class="col-md-4">
                                        <p class="form-control-static">
                                            <?php echo $auditdata['domain_agent_address'];?>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-actions fluid">
                                    <div class="col-md-offset-3 col-md-6">
                                        <button class="btn blue" type="button" id="reject_button" data-value="<?php echo $auditdata['guid'];?>">
                                            审核拒绝
                                        </button>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <button class="btn blue" type="button" id="back_button" data-value="<?php echo $auditdata['guid'];?>">
                                            审核退回
                                        </button>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <button class="btn blue button-previous" type="button" data-value="<?php echo $auditdata['guid'];?>" id="pass_button" style="display: inline-block;">
                                            审核通过
                                        </button>
                                    </div>
                                </div>
                            <br><br><br><br><br><br><br><br>
                        </div>
                    </div>
                </form>
            </div>
            <!-- END SAMPLE FORM PORTLET-->
        </div>
    </div>
</div>
<!--审核拒绝原因-->
<div class="portlet box" id="reason-table" style="display: none;width: 700px;">
</div>
<!--审核通过-->
<div class="portlet box" id="pass-table" style="display: none;width: 700px;">
    <div class="portlet-body" style="overflow: hidden;">
         <form role="form" id="pass_form" class="form-horizontal" >
            <div class="form-body">
                 <div class="form-group">
                    <label class="col-md-3 control-label">原因备注</label>
                    <div class="col-md-6">
                        <input id="reason"  name="memo" type="text" value="" placeholder="选填" class="form-control" >
                    </div>
                </div>
                 <div class="form-group">
                    <label class="col-md-3 control-label"><strong>附件&nbsp;&nbsp;</strong></label>
                    <div class="col-md-6">
                        <div class="fileupload-buttonbar minibtn">
                            <span class="btn blue fileinput-button">
                                 <i class="icon-upload"></i>
                                <span>
                                    添加附件
                                </span>
                                <input type="file" id="file_caac_pass" name="file_caac_pass" size="45" class="filebtn2" onchange="return ajaxFileUpload(this)"/>
                            </span>
                            <span class="help-block"  id="message_file_pass"></span>
                        </div>
                    </div>
                </div>
                 <div class="form-group">
                    <label class="col-md-3 control-label"><strong></strong></label>
                    <div class="col-md-6" id="attachment_pass">
                    </div>
                </div>
                 <div class="form-actions fluid">
                    <div class="col-md-offset-3 col-md-6">
                        <input type="hidden" name="audit_data_id" value="<?php echo $auditdata['guid'];?>" />
                        <button class="btn blue" id="pass_cancel" type="button">取消</button>&nbsp;&nbsp;&nbsp;&nbsp;
                        <button class="btn blue" id="pass_submit" type="button">确定</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="/js/ajaxfileupload.js"></script>
<script type="text/javascript" src="/js/itm-audit.js"></script>
