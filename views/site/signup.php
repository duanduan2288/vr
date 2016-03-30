<form class="register-form" action="/site/signup" method="post" style="display: block;">
    <h3>用户注册</h3>
    <?php if(yii::app()->user->hasFlash('register')): ?>
        <div class="alert alert-danger">
            <span class="close" data-close="alert"></span>
			<span>
				<?php echo yii::app()->user->getFlash('register'); ?>
			</span>
        </div>
    <?php endif;?>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">用户名</label>
        <div class="input-icon">
            <i class="icon icon-user"></i>
            <input class="form-control placeholder-no-fix" type="text" id="username" autocomplete="off" placeholder="用户名" name="username" value="<?php echo $username;?>"/>
        </div>
    </div>
    <div class="form-group">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <label class="control-label visible-ie8 visible-ie9">邮箱</label>
        <div class="input-icon">
            <i class="icon icon-envelope"></i>
            <input class="form-control placeholder-no-fix" type="text" autocomplete="off" id = "email" placeholder="邮箱" name="email" value="<?php echo $email;?>"/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">密码</label>
        <div class="input-icon">
            <i class="icon icon-lock"></i>
            <input class="form-control placeholder-no-fix" type="password" autocomplete="off" id="register_password" placeholder="密码" name="password" value=""/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">确认密码</label>
        <div class="controls">
            <div class="input-icon">
                <i class="icon icon-check"></i>
                <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="确认密码" name="rpassword"/>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9 required">验证码</label>
        <div class="input-icon">
            <i class="icon-link"></i>
            <input class="form-control placeholder-no-fix required" type="text" autocomplete="off" data-rule-remote="<?php echo $this->createUrl('/site/getverifycode');?>" data-msg-remote="验证码不正确" placeholder="请输入 验证码" name="verifyCode" style="width: 170px;margin-right:20px;float: left;"/>
            <?php $this->widget('CCaptcha',array('showRefreshButton'=>false,'clickableImage'=>true,'imageOptions'=>array('alt'=>'看不清？单击换图','title'=>'看不清？单击换图','style'=>'cursor: pointer; vertical-align: middle; width:95px; height:34px;'))); ?>
        </div>
    </div>
    <div class="form-group">
        <label>
            <input type="checkbox" name="tnc"/> 我接受 <a href="#">服务条款</a> 及 <a href="#">政策</a>
        </label>
        <div id="register_tnc_error">
        </div>
    </div>
    <div class="form-actions">
        <button type="button" id="back-btn" class="btn">
            <i class="m-icon-swapleft"></i> 返回 </button>
        <button type="submit" id="register-submit-btn" class="btn blue pull-right">
            立即注册 <i class="m-icon-swapright m-icon-white"></i>
        </button>
    </div>
</form>