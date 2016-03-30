<div class="row">
    <div class="col-md-10 coming-soon-content">
        <?php if($status == 0):?>
            <h1><?php echo $msg;?></h1>
        <?php else:?>
        <h1><?php echo $msg;?></h1>
        <p>
            <a href="/site/index">返回首页</a>
        </p>
        <?php endif;?>
    </div>
    <div class="col-md-6 coming-soon-countdown">
        <div id="defaultCountdown">
        </div>
    </div>
</div>