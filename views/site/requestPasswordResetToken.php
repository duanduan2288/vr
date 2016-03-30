<!-- BEGIN FORGOT PASSWORD FORM -->
<form class="forget-form" action="/site/checkfound" method="post" style="display: block;">
    <h3>忘记密码 ?</h3>
    <p>
        提交注册时的电子邮箱
    </p>
    <?php if(Yii::app()->user->hasFlash('found')):?>
        <div class="alert alert-danger display-hide">
            <button class="close" data-close="alert"></button>
			<span>
           <?php echo Yii::app()->user->getFlash('found');?>
			</span>
        </div>
    <?php endif;?>
    <div class="form-group">
        <div class="input-icon">
            <i class="icon icon-envelope"></i>
            <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" value="<?php echo $email;?>"/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9 required">验证码</label>
        <div class="input-icon">
            <i class="icon-link"></i>
            <input class="form-control placeholder-no-fix required" type="text" autocomplete="off" data-rule-remote="<?php echo $this->createUrl('/site/getverifycode');?>"
                   data-msg-remote="验证码不正确" placeholder="请输入验证码" name="verifyCode" style="width: 170px;margin-right:20px;float: left;"/>
            <?php $this->widget('CCaptcha',array('showRefreshButton'=>false,'clickableImage'=>true,'imageOptions'=>array('alt'=>'看不清？单击换图','title'=>'看不清？单击换图','style'=>'cursor: pointer; vertical-align: middle; width:95px; height:34px;'))); ?>
        </div>
    </div>
    <div class="form-actions">
        <button type="button" id="back-btn" class="btn">
            <i class="m-icon-swapleft"></i> 返回 </button>
        <button type="submit" class="btn blue pull-right">
            提交 <i class="m-icon-swapright m-icon-white"></i>
        </button>
    </div>
</form>
