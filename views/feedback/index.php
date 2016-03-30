<link href="/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="/plugins/fuelux/css/tree-metronic.css"/>
<?php
    use yii\widgets\LinkPager;
    use app\models\Service;
    use yii\helpers\Url;
    use app\models\HelpAudit;
?>
<style>
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
                    <a href="#">终端客户回访</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                </div>
            </div>
            <div class="portlet-body form">
                <form class="form-inline" id="search_form" role="form" method="get">
                    <div class="form-body">
                        <div class="form-group">
                            <input type="text" class="form-control" name="registrant_name" placeholder="请输入终端客户名称" value="<?php echo $registrant_name;?>">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control " name="registrant_organization" placeholder="请输入终端客户公司" value="<?php echo $registrant_organization;?>">
                        </div>
                        <div class="form-group">
                            <select  name="feedback_status" class="form-control">
                                <option value="">请选择回访状态</option>
                                <?php foreach($statuss as $key=>$status):?>
                                    <option value="<?php echo $status;?>" <?php echo $feedback_status==$status?'selected':''; ?>>
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
                        <th>ID</th>
                        <th>终端客户</th>
                        <th>客户公司</th>
                        <th>联系电话</th>
                        <th>联系手机</th>
                        <th>联系邮箱</th>
                        <th>注册域名数量</th>
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
                                    <?php echo $list['id'];?>
                                </td>
                                <td>
                                    <a href="<?php echo Url::to(['/feedback/domain-list','registrant_name'=>$list['registrant_name']]);?>">
                                        <?php echo $list['registrant_name'];?>
                                    </a>
                                </td>
                                <td><?php echo $list['registrant_organization'];?></td>
                                <td>
                                    <?php if($list['contact_update_flag']=='是'):?>
                                        <span class="badge badge-roundless badge-important">
                                                new
                                            </span>
                                    <?php endif;?>
                                    <?php
                                        if(HelpAudit::check_telephone_from_blacklist($list['telephone'])):?>
                                            <i class="icon icon-book"></i>
                                        <?php endif;?>
                                    <?php echo $list['telephone'];?>
                                </td>
                                <td><?php echo $list['mobile'];?></td>
                                <td><?php echo $list['email'];?></td>
                                <td>
                                    <?php
                                        echo $list['domain_count'];
                                    ?>
                                </td>
                                <td><?php echo $list['feedback_status'];?></td>
                                <td>
                                    <a  href="<?php echo Yii::$app->getUrlManager()->createUrl(['feedback/history','id' => $list['issue_id'],'guid'=>$list['guid']]); ?>">
                                        回访历史
                                    </a>&nbsp;&nbsp;
                                    <a  href="<?php echo Yii::$app->getUrlManager()->createUrl(['feedback/edit-contact','guid' => $list['guid']]); ?>">
                                        修改联系方式
                                    </a><br/>
                                    <a  class="add_feedback"  href="javascript:;" data-id="<?php echo  $list['guid'];?>">
                                        添加回访
                                    </a>
                                    <?php if('是'==$list['contact_update_flag']):?>
                                        <a class="cancel_update" data-value="customer_feedback" href="javascript:;" data-id="<?php echo  $list['guid'];?>">取消标记</a>
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
<!--添加回访记录-->
<div class="portlet box" id="pass-table" style="display: none;width: 700px;">
    <div class="portlet-body" style="overflow: hidden;">
        <form role="form" id="pass_form" class="form-horizontal" >
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label"><strong>回访状态&nbsp;&nbsp;</strong></label>
                    <div class="col-md-6">
                        <select  name="feedback_status" class="form-control">
                            <option value="">请选择回访状态</option>
                            <?php foreach($statuss as $key=>$status):?>
                                <option value="<?php echo $status;?>">
                                    <?php echo $status;?>
                                </option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label"><strong>回访记录&nbsp;&nbsp;</strong></label>
                    <div class="col-md-6">
                        <textarea name="content" class="form-control"></textarea>
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
                <div class="form-group">
                    <label class="col-md-3 control-label"><strong>录音文件&nbsp;&nbsp;</strong></label>
                    <div class="col-md-6">
                        <span class="btn blue" id="wav_file_upload">选择文件</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label"><strong></strong></label>
                    <div class="col-md-6" id="attachment_wav">
                    </div>
                </div>
                <div class="form-actions fluid">
                    <div class="col-md-offset-3 col-md-6">
                        <input type="hidden" name="feedback_id" id="feedback_id" />
                        <button class="btn blue" id="pass_cancel" type="button">取消</button>&nbsp;&nbsp;&nbsp;&nbsp;
                        <button class="btn blue" id="pass_submit" type="button">确定</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!--录音文件-->
<div class="portlet box" id="wav-table" style="display: none;width: 700px;">
    <div class="portlet-body">
        <div class="tree tree-plus-minus" id="MyTree2">
            <div style="display: block;" class="tree-folder">
                <div class="tree-folder-header" data-value="<?php echo Yii::$app->params['upload']['telepbone_attachment_dir'];?>" data-mode="hide">
                    <i class="fa fa-folder-open"></i>

                    <div class="tree-folder-name" >
                        upload
                        <div class="tree-actions"></div>
                    </div>
                </div>
                <div id="mycontent" class="tree-folder-content" style="display: block;">

                </div>
        </div>
    </div>
</div>
<!--<script src="/plugins/fuelux/js/tree.min.js"></script>-->
<script type="text/javascript" src="/js/ajaxfileupload.js"></script>
<script type="text/javascript" src="/js/itm-feedback.js"></script>
<script type="text/javascript" >
    jQuery(document).ready(function () {
        $('#order_download').click(function(event) {
            var data = $('#search_form').serialize();
            window.location.href = '/export/feedback?'+data+'&dtype=excel';
        });
    });
</script>