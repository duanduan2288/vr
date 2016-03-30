<!-- BEGIN FORGOT PASSWORD FORM -->
<form class="reset-form" action="/site/checkchange" method="post" style="display: block;">
    <h3>找回密码</h3>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">新密码</label>
        <div class="input-icon">
            <i class="icon icon-lock"></i>
            <input class="form-control placeholder-no-fix" id="password" type="password" autocomplete="off" placeholder="新密码" name="password"/>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">确认密码</label>
        <div class="input-icon">
            <i class="icon icon-lock"></i>
            <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="确认密码" name="rpassword"/>
        </div>
    </div>
    <div class="form-actions">
        <input type="hidden" name="email" value="<?php echo $email;?>" />
        <input type="hidden" name="code" value="<?php echo $code;?>" />
        <button type="submit" class="btn blue pull-right" id="btn_submit">
            提交 <i class="m-icon-swapright m-icon-white"></i>
        </button>
    </div>
</form>
