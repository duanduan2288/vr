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
                    <a>域名回访管理&nbsp;&nbsp;<i class="icon-angle-right"></i></a>
                     <a href="#">回访历史</a>
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
                    <h4><strong>当前客户&nbsp;&nbsp;&nbsp;<?php echo $issue->registrant_name;?></strong></h4>
                    <hr/>
                    <ul class="chats">
                        <?php if (!empty($issueoperation)):
                            foreach ($issueoperation as $key=>$v):
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
                                            <?php echo '&nbsp;&nbsp;&nbsp;'.$v['feedback_status']; ?>
                                        </span>
                                        <span class="datetime" style="font-weight: bold;">
                                             <?php echo $v['created']; ?>
                                        </span>
                                        <span class="body">
                                            <?php if(isset($v['mobile']) && !empty($v['mobile'])):?>
                                                <strong>手机：</strong><br/>
                                                <?php echo $v['mobile'];?><br/>
                                            <?php endif;?>

                                            <?php if(isset($v['telephone']) && !empty($v['telephone'])):?>
                                                <strong>电话：</strong><br/>
                                                <?php echo $v['telephone'];?><br/>
                                            <?php endif;?>
                                            <?php if(isset($v['email']) && !empty($v['email'])):?>
                                                <strong>邮箱：</strong><br/>
                                                <?php echo $v['email'];?><br/>
                                            <?php endif;?>

                                            <strong>回访记录：</strong><br/>
                                             <?php echo $v['content']; ?><br/>

                                            <?php if (isset($v['wavattachment']) && !empty($v['wavattachment'])):
                                                $wavattachment = json_decode($v['wavattachment'],true);
                                                ?>
                                                <strong>音频附件：</strong><br/>
                                                <?php foreach ($wavattachment as $url):
                                                    $showurl = Yii::$app->request->hostInfo.$url;
                                                    if('IE'==$browser_type):
                                                    ?>
                                                        <embed src="<?php echo $showurl;?>" autostart="false" loop="false" width="150" height="26">
                                                            <noembed>
                                                                You should be able to see a sound controller now, but if you see this, you`re browser doesn`t support the appropriate element or ActiveX is disabled.
                                                            </noembed>
                                                        </embed>
                                                    <?php else:?>
                                                        <audio controls><source src="<?php echo $showurl;?>" type="audio/wav"></audio>
                                                    <?php endif;?>
                                                <?php endforeach;endif; ?><br/>

                                           <?php if (!empty($v['attachment'])):?>
                                                <strong>其他附件：</strong><br/>
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
                                域名注册数量<i class="fa fa-img-up"></i>
                            </span>
                            <span class="sale-num">
                                <?php echo $data['domain_count']; ?>
                            </span>
                        </li>
                        <li>
                            <span class="sale-info">
                                客户电话<i class="fa fa-img-up"></i>
                            </span>
                            <span class="sale-num">
                                <?php echo $data['telephone']; ?>
                            </span>
                        </li>
                        <li>
                            <span class="sale-info">
                                客户邮箱<i class="fa fa-img-up"></i>
                            </span>
                            <span class="sale-num">
                                <?php echo $data['email']; ?>
                            </span>
                        </li>
                        <li>
                            <span class="sale-info">
                                终端客户公司<i class="fa fa-img-up"></i>
                            </span>
                            <span class="sale-num">
                                <?php echo $data['registrant_organization']; ?>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/js/html5media.min.js"></script>