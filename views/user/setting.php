<style>
.table th a{ color:#000;text-decoration:none;}
.table td a{text-decoration:none;}
.input-small {width: 150px !important;}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <!-- <div class="portlet-title">
                <div class="caption"><i class="icon-reorder"></i>用户分配权限</div>
                <div class="tools">
                    <a href="javascript:history.go(-1);"><i class="icon-reply"></i>返回</a>
                </div>
            </div> -->
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-cog"></i>
                    <a>用户分配权限&nbsp;&nbsp;<i class="icon-angle-right"></a></i>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                    <a href="javascript:history.go(-1);"><i class="icon-reply"></i>返回</a>&nbsp;&nbsp;
                </div>
            </div>

            <div class="portlet-body form">
                <form role="form" id="myform" class="form-horizontal validate" enctype="multipart/form-data" action="/user/save" method="post">
                    <input type="hidden" name="user_id" value="<?php echo $user->id; ?>" />
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>用户</strong></label>
                            <div class="col-md-3">
                                <input disabled type="text" name="username" value="<?php echo $user->last_name.'&nbsp;'.$user->first_name; ?>" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><strong>选择角色<span class="required">*</span></strong></label>
                            <div class="col-md-2">
                                <select name="role_id" class="form-control">
                                    <?php foreach ($roles as $key => $value) : ?>
                                    <option value="<?php echo $value['id'] ?>" <?php echo in_array($value['id'], $user_has_role) ? 'selected' : '';?>><?php echo $value['platform'].'/'.$value['name']; ?></option>
                                    `<?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-actions fluid">
                            <div class="col-md-offset-3 col-md-6">
                                <button class="btn blue" type="submit"><i class="icon-save">&nbsp;&nbsp;</i>保存</button>
                            </div>
                        </div>
                    </div>
                    <br><br><br><br><br><br><br><br>
                </form>
            </div>
        </div>
    </div>
</div>