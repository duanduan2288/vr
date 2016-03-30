<?php
    use app\models\Service;
    use app\assets\AppAsset;
    use yii\helpers\Html;
    AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>

        <title>注册局审核平台</title>
        <?php $this->head() ?>
        <?php $this->registerJsFile('/plugins/jquery-1.10.2.min.js', ['position' => \yii\web\View::POS_HEAD])?>
    </head>

    <body class="page-header-fixed page-sidebar-fixed boday">
    <?php $this->beginBody() ?>
        <!--[if lt IE 10]><div class="ie6" style="color: red;">您正在使用的浏览器版本过低（IE 10以下，包括套用了IE内核的360浏览器、搜狗浏览器等）。<br />如果不能正常浏览本站或某些功能无法使用，请到微软官网下载升级 <a style="color: #111;" rel="nofollow" href="http://windows.microsoft.com/zh-CN/internet-explorer/downloads/ie" target="_blank">Internet Explorer</a> 或使用 <a style="color: #111;" href="http://rj.baidu.com/soft/detail/14744.html?ald" target="_blank">谷歌 Google Chrome</a> 浏览器&nbsp;<a style="color: #111;" href="http://www.firefox.com.cn/download/" target="_blank">火狐 Firefox</a> 浏览器。</div><![endif]-->
        <div class="header navbar navbar-inverse navbar-fixed-top">
            <div class="header-inner">
                <a class="navbar-brand" href="/">
                    <b style="font-size: 22px;color: #fff;height: 45px;line-height: 45px;">&nbsp;&nbsp;&nbsp;VR管理系统</b>
                    <!-- <img src="/img/logo1.jpg" alt="logo" class="img-responsive"/> -->
                </a>
                <ul class="nav navbar-nav pull-right">
                    <!--<li class="dropdown" id="header_notification_bar" style="margin-right: 15px;">-->
                    <!--    <a href="/notification/my" class="dropdown-toggle" style="padding: 15px 10px 7px;">-->
                    <!--        <i class="icon-envelope"></i>-->
                    <!--        <span id="notification_num" class="badge" style="display: none;left: 25px;right: 0">0</span>-->
                    <!--    </a>-->
                    <!--</li>-->
                    <?php if(Yii::$app->user->id):?>
                    <li class="dropdown user">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" style="padding-top: 14px;">
                            <!-- <img alt="" src="/img/avatar1_small.jpg"/> -->
                    				<span class="username">
                    					<?php echo Service::get_user_name(Yii::$app->user->id);?>
                    				</span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- <li>
                                <a href="javascript:;" id="trigger_fullscreen"><i class="fa fa-move"></i>全屏</a>
                            </li> -->
                            <li>
                                <a href="/site/change_password"><i class="icon-edit"></i>&nbsp;&nbsp;修改密码</a>
                            </li>
                            <li>
                                <a href="/site/logout"><i class="icon-power-off"></i>&nbsp;&nbsp;退出</a>
                            </li>

                        </ul>
                    </li>
                    <?php endif;?>
                </ul>
            </div>
        </div>

        <div class="clearfix"></div>
        <div class="page-container">
            <div class="page-sidebar-wrapper">
                <div class="page-sidebar navbar-collapse collapse">
                    <ul class="page-sidebar-menu">
                        <li class="sidebar-toggler-wrapper">
                            <div class="sidebar-toggler hidden-phone">
                        </li>
                        <li class="start ">
                            <a href="/">
                                <i class="icon icon-home"></i><span class="title">首页</span>
                            </a>
                        </li>
                        <?php
                            use app\components\MenuWidget;
                        ?>
                        <?= MenuWidget::widget(['menuId' => Yii::$app->request->get ( 'menuid' )]) ?>
                    </ul>
                </div>
            </div>
            <div class="page-content-wrapper">
                <div class="page-content">
                    <?= $content;?>
                </div>
            </div>
        </div>

        <div class="footer" style="border-top: 1px solid #ccc;background: #eee">
            <div class="footer-inner">
                <b>Copyright © 2015 · 环球互易集团 版权所有.</b>
            </div>
            <div class="footer-tools">
        		<span class="go-top">
        			<i class="fa fa-angle-up"></i>
        		</span>
            </div>
        </div>

        <!--[if lt IE 9]>
    <?php $this->registerJsFile('/plugins/respond.min.js')?>
    <?php $this->registerJsFile('/plugins/excanvas.min.js')?>
        <![endif]-->
    <?php $this->registerJsFile('/js/app.js')?>
    <?php $this->registerJsFile('/js/common.js')?>
    <?php $this->beginBlock('myjs') ?>
        $(function(){
                App.init();
                //Hide the overview when click
                $('#someid').on('click', function () {
                    $('#OverviewcollapseButton').removeClass("collapse").addClass("expand");
                    $('#PaymentOverview').hide();
                });
            });
    <?php $this->endBlock() ?>
    <?php $this->registerJs($this->blocks['myjs'], \yii\web\View::POS_READY); ?>

    <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>