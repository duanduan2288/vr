<?php
use app\models\Service;
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-globe"></i>
                    <a href="/domainRegister">用户登录&nbsp;&nbsp;<i class="icon-angle-right"></i></a>
                    <a href="#">所有用户</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"> <i class="icon-refresh"></i>刷新</a>
                    <a href="/kb"> <i class="icon-question"></i>帮助</a>
                </div>
            </div>
            <div class="portlet-body form">
                <form role="form" id="company_form" method="POST" class="form-horizontal" enctype="multipart/form-data" action="/s6qd7p2r/save">
                     <div class="form-wizard">
                        <div class="form-body">
                            <h3 class="block">所有用户</h3>
                                <div class="form-group">
                                    <div class="col-md-10">
                                        <div class="radio-list" >
                                             <?php foreach($data as $key=>$v):?>
                                                <label class="radio-inline" style="width:330px;<?php if(0==$key):?>padding-left: 20px;<?php else:?>margin-left: 0px;<?php endif;?>">
                                                    <input type="radio" name="username" value="<?php echo $v['guid'];?>"/><?php echo $v['email'];?>
                                                    （<?php echo Service::get_user_name($v['id']);?>）
                                                </label>&nbsp;
                                             <?php endforeach;?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions fluid">
                                    <div class="col-md-offset-4 col-md-6">
                                        <button class="btn blue" type="submit" id="savecompany">登录</button>
                                    </div>
                                </div>
                            </div>
                            <br><br><br><br><br><br><br><br>
                     </div>
                </form>
            </div>
            <!-- END SAMPLE FORM PORTLET-->
        </div>
    </div>
</div>