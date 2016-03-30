<?php
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
    <title><?= Html::encode($this->title) ?></title>
    <link rel="stylesheet" type="text/css" href="/plugins/select2/select2_metro.css"/>
    <link rel="stylesheet" type="text/css" href="/css/style-metronic.css"/>
    <link rel="stylesheet" type="text/css" href="/css/pages/login-soft.css"/>
    <?php $this->head() ?>
    <?php $this->registerJsFile('/plugins/jquery-1.10.2.min.js', ['position' => \yii\web\View::POS_HEAD])?>
    <!--[if lt IE 9]>
    <?php $this->registerJsFile('/plugins/respond.min.js');?>
    <?php  $this->registerJsFile('/plugins/excanvas.min.js');?>
    <![endif]-->
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
<?php $this->beginBody() ?>
<!-- BEGIN LOGO -->
<div class="logo">
    <img src="/img/logo-big.png" alt=""/>
</div>
<!-- END LOGO -->
<!-- BEGIN LOGIN -->
<div class="content">
    <?= $content;?>
</div>
<div class="copyright">
    2013 &copy; Metronic - Admin Dashboard Template.
</div>
<?php $this->registerJsFile('/js/login-soft.js');?>
    <!-- END PAGE LEVEL SCRIPTS -->
<?php $this->beginBlock('myjs') ?>
    jQuery(document).ready(function() {
            Login.init();
        });
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['myjs'], \yii\web\View::POS_READY); ?>
<?php $this->registerJsFile("/js/app.js",['position' => \yii\web\View::POS_READY]) ?>
<?php $this->registerJsFile("/js/common.js",['position' => \yii\web\View::POS_READY]) ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>