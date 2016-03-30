<link href="/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet"/>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-cog"></i>
                    <a href="#">域名回访管理&nbsp;&nbsp;<i class="icon-angle-right"></i></a>
                    <a href="#">修改联系方式</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                    <a href="javascript:history.go(-1);"><i class="icon-reply"></i>返回</a>&nbsp;&nbsp;
                </div>
            </div>

            <div class="portlet-body form">
                <form role="form" id="myform" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="guid" value="<?php echo $data['guid']; ?>">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>终端客户<span class="required">*</span></strong></label>
                            <div class="col-md-5">
                                <p class="form-control-static"><?php echo $data['registrant_name']; ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>客户公司<span class="required">*</span></strong></label>
                            <div class="col-md-5">
                                <p class="form-control-static"><?php echo $data['registrant_organization']; ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>更新电话</strong></label>
                            <div class="col-md-5">
                                <input id="telephone" type="text" name="telephone" value="" class="form-control">
                                <span class="help-block"></span>
                            </div>
                            <span class="help-inline">eg：010-12345678 , 010-1234567</span>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>更新手机</strong></label>
                            <div class="col-md-5">
                                <input id="cellphone" type="text" name="mobile" value="" class="form-control">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>更新邮箱</strong></label>
                            <div class="col-md-5">
                                <input id="email" type="text" name="email" value="" class="form-control">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>备注</strong></label>
                            <div class="col-md-5">
                                <textarea class="form-control" rows="5" name="content" data-rule-maxlength="500" placeholder="请填写备注信息" data-msg-maxlength="备注不要超过500字"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>附件&nbsp;&nbsp;</strong></label>
                            <div class="col-md-5">
                                <div class="fileupload-buttonbar minibtn">
                                    <span class="btn blue fileinput-button">
                                         <i class="icon-upload"></i>
                                        <span>
                                            添加附件
                                        </span>
                                        <input type="file" id="file_caac" name="file_caac" size="45" class="filebtn2" onchange="return ajaxFileUpload(this)"/>
                                    </span>
                                </div>
                                <br/>
                                <span class="help-inline">允许上传格式：wav,zip,rar,doc,docx,xls,xlsx,png,jpg,jpeg,gif,pdf 。 大小不超过20M</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong></strong></label>
                            <div class="col-md-5" id="attachment_pass">
                            </div>
                        </div>
                        <div class="form-actions fluid">
                            <div class="col-md-offset-3 col-md-6">
                                <button id="saveform" class="btn blue" type="button"><i class="icon-save">&nbsp;&nbsp;</i>保存</button>
                            </div>
                        </div>
                    </div>
                    <br><br><br><br><br><br><br><br>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/js/ajaxfileupload.js"></script>
<script type="text/javascript" src="/js/itm-feedback.js"></script>
