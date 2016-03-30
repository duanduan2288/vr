<?php
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    use yii\captcha\Captcha;
?>
<!-- BEGIN LOGIN FORM -->
<form class="login-form" action="/site/checklogin" method="post">
    <h3 class="form-title">用户登录</h3>
    <?php if(Yii::$app->getSession()->hasFlash('login')): ?>
        <div class="alert alert-danger">
        <span class="close" data-close="alert"></span>
			<span>
                 <i class="icon icon-warning-sign"></i>
                <?php echo Yii::$app->getSession()->getFlash('login'); ?>
			</span>
    </div>
    <?php endif;?>
    <div class="form-group">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <label class="control-label visible-ie8 visible-ie9">用户名</label>
        <div class="input-icon">
            <i class="icon icon-user"></i>
            <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="用户名" name="username" value=""/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">密码</label>
        <div class="input-icon">
            <i class="icon icon-lock"></i>
            <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="密码" name="password"/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9 required">验证码</label>
        <div class="input-icon">
            <i class="icon-link"></i>
            <input class="form-control placeholder-no-fix required" type="text" autocomplete="off" data-rule-remote="<?php echo Yii::$app->getUrlManager()->createUrl(['/site/getverifycode']);?>" data-msg-remote="验证码不正确" placeholder="请输入 验证码" name="verifyCode" style="width: 170px;margin-right:20px;float: left;"/>
            <?php echo Captcha::widget(['name'=>'captchaimg','imageOptions'=>['id'=>'captchaimg', 'title'=>'换一个', 'alt'=>'换一个', 'style'=>'cursor:pointer;vertical-align: middle; width:110px; height:34px;'],'template'=>'{image}']); ?>
            <?php //$this->widget('CCaptcha',array('showRefreshButton'=>false,'clickableImage'=>true,'imageOptions'=>array('alt'=>'看不清？单击换图','title'=>'看不清？单击换图','style'=>'cursor: pointer; vertical-align: middle; width:110px; height:34px;'))); ?>
        </div>
    </div>
    <div class="form-actions">
        <label class="checkbox">
            <!--<input type="checkbox" name="remember" value="1"/> 记住我 </label>-->
        <button type="submit" class="btn blue pull-right" id="btn_submit">
            登录 <i class="m-icon-swapright m-icon-white"></i>
        </button>
    </div>
</form>
