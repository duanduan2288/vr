<link rel="stylesheet" type="text/css" href="/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="/plugins/bootstrap-fileupload/bootstrap-fileupload.css"/>
<style>
    i.icon-trash ,a .icon-trash{color:#fff;}
    .form-group li{list-style:none;margin:0px;display: inline;width:1%;}
    .radio-inline{padding-left: 0px;}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-cog"></i>
                    <a href="#">注册商管理&nbsp;&nbsp;<i class="icon-angle-right"></i></a>
                    <a href="#">注册商接口</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                    <a href="javascript:history.go(-1);"><i class="icon-reply">&nbsp;</i>返回</a>&nbsp;&nbsp;
                </div>
            </div>
            <div class="portlet-body form">
                <form role="form" id="agent_form" class="form-horizontal" enctype="multipart/form-data" method="post" action="/registrar/account">
                     <div class="form-wizard">
                        <div class="form-body">
                            <div class="form-group ">
                                <label class="col-md-3 control-label"><strong>EPP账号<span class="required">*</span></strong></label>
                                <?php
                                    $criteria = new CDbCriteria;
                                    $criteria->condition = "deleted = '否'";
                                    $es = RegistrarEpp::model()->findAll($criteria);
                                 ?>
                                <div class="col-md-4">
                                    <select name="registrar_epp_id" class="form-control">
                                        <option value="">--请选择EPP账号--</option>
                                        <?php foreach ($es as $key => $value): ?>
                                            <?php if ($value['type'] == 'epp'): ?>
                                            <option value="<?php echo $value['id'] ?>" <?php echo $model->registrar_epp_id==$value['id']?'selected':''; ?>><?php echo $value['account']; ?></option>
                                            <?php endif ?>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label class="col-md-3 control-label"><strong>Webservice账号<span class="required">*</span></strong></label>
                                <div class="col-md-4">
                                    <select name="registrar_webservice_id" class="form-control">
                                        <option value="">--请选择Webservice账号--</option>
                                        <?php foreach ($es as $key => $value): ?>
                                            <?php if ($value['type'] == 'webservice'): ?>
                                            <option value="<?php echo $value['id'] ?>" <?php echo $model->registrar_webservice_id==$value['id']?'selected':''; ?>><?php echo $value['account']; ?></option>
                                            <?php endif ?>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label class="col-md-3 control-label"><strong>TMDB账号<span class="required">*</span></strong></label>
                                <div class="col-md-4">
                                    <select name="registrar_tmdb_id" class="form-control">
                                        <option value="">--请选择TMDB账号--</option>
                                        <?php foreach ($es as $key => $value): ?>
                                            <?php if ($value['type'] == 'tmdb'): ?>
                                            <option value="<?php echo $value['id'] ?>" <?php echo $model->registrar_tmdb_id==$value['id']?'selected':''; ?>><?php echo $value['account']; ?></option>
                                            <?php endif ?>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                        <!-- 提交 -->
                            <div class="form-actions fluid">
                                <div class="col-md-offset-4 col-md-4">
                                    <input type="hidden" name="id" value="<?php echo $model->guid; ?>">
                                    <button class="btn blue" type="submit" id="saveagent">提交</button>
                                </div>
                            </div>
                        </div>
                     </div>
                </form>
            </div>
        </div>
    </div>
</div>
