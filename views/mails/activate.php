<div>
    <p><?php echo $user->last_name.'&nbsp;'.$user->first_name;?>，您好！</p>
    <p>商标注册局管理系统在<?php echo $user->created;?>收到了邮箱<?php echo $user->email;?>激活申请。请点击以下的链接激活：</p>
    <p><a target="_blank" href="<?php echo $url;?>"><?php echo $url;?></a></p>
    <p>如果邮箱中不能打开链接，您也可以将它复制到浏览器地址栏中打开。</p>
</div>
