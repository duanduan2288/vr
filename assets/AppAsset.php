<?php
    /**
     * @link http://www.yiiframework.com/
     * @copyright Copyright (c) 2008 Yii Software LLC
     * @license http://www.yiiframework.com/license/
     */

    namespace app\assets;

    use yii\web\AssetBundle;

    /**
     * @author Qiang Xue <qiang.xue@gmail.com>
     * @since 2.0
     */
    class AppAsset extends AssetBundle
    {
        public $basePath = '@webroot';
        public $baseUrl = '@web';
        public $css = [
            'plugins/font-awesome/css/font-awesome.min.css',
            'plugins/bootstrap/css/bootstrap.min.css',
            'plugins/uniform/css/uniform.default.css',
            'plugins/select2/select2_metro.css',
            'css/style-metronic.css',
            'css/style.css',
            'css/plugins.css',
            'css/style-responsive.css',
            'css/themes/default.css',
            'css/pages/login-soft.css',
            'css/style-responsive.css',
            'css/custom.css',
            'plugins/layer/skin/layer.css',
            'plugins/bootstrap-datepicker/css/datepicker.css'
        ];
        public $js = [
            'plugins/layer/layer.min.js',
            'plugins/jquery-migrate-1.2.1.min.js',
            'plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js',
            'plugins/bootstrap/js/bootstrap.min.js',
            'plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js',
            'plugins/jquery-slimscroll/jquery.slimscroll.min.js',
            'plugins/jquery.blockui.min.js',
            'plugins/jquery.cokie.min.js',
            'plugins/uniform/jquery.uniform.min.js',
            'plugins/select2/select2.min.js',
            'plugins/backstretch/jquery.backstretch.min.js',
            'plugins/jquery-validation/dist/jquery.validate.min.js',
            'plugins/jquery-validation/dist/jquery.validate.bootstrap.js',
            'plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
            'plugins/layer/extend/layer.ext.js'
        ];
        public $depends = [
            //'yii\web\YiiAsset',
            //'yii\bootstrap\BootstrapAsset',
        ];
    }
