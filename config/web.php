<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'app',
    'timeZone'=>'Asia/Shanghai',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'app\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '2A9lvTRQGnaJCh7Vg3JoCicATadmKwqM',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            //'class'=>'app\models\User'
        ],
        'db' =>  [
            'class'     => 'yii\db\Connection',
            'dsn'       => 'mysql:host=localhost;dbname=vr',
            'username'  => 'root',
            'password'  => '',
            'charset'   => 'utf8'
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                //"<module:\w+>/<controller:\w+>/<action:\w+>"=>"<module>/<controller>/<action>",
                //'<controller:\w+>/<id:\d+>'=>'<controller>/view',
                //'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                //'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                //'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '10.10.5.76',
            'port' => 6379,
            'database' => 0,
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' =>false,//这句一定有，false发送邮件，true只是生成邮件在runtime文件夹下，不发邮件
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'mail.itmnic.com',
                'username' => 'noreply@itmnic.com',
                'password' => 'itm123ITM!@#',
                'port' => '25',
                // 'encryption' => 'tls',

            ],
            'messageConfig'=>[
                'charset'=>'UTF-8',
                'from'=>['15618380091@163.com'=>'admin']
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\FileTarget',

                    'levels' => ['error', 'warning'],

                    'categories' => ['webservice'],

                    'logFile' => '@app/runtime/logs/webservice/requests.log',

                    'maxFileSize' => 1024 * 2,

                    'maxLogFiles' => 20,
                ],
                [
                    'class' => 'yii\log\FileTarget',

                    'levels' => ['error', 'warning'],

                    'categories' => ['epp'],

                    'logFile' => '@app/runtime/logs/epp/requests.log',

                    'maxFileSize' => 1024 * 2,

                    'maxLogFiles' => 20,
                ],
                [
                    'class' => 'yii\log\FileTarget',

                    'levels' => ['error', 'warning'],

                    'categories' => ['operation'],

                    'logFile' => '@app/runtime/logs/operation/operation.log',

                    'maxFileSize' => 1024 * 2,

                    'maxLogFiles' => 20,
                ],
                [
                    'class' => 'yii\log\FileTarget',

                    'levels' => ['error', 'warning'],

                    'categories' => ['audit'],

                    'logFile' => '@app/runtime/logs/audit/operation.log',

                    'maxFileSize' => 1024 * 2,

                    'maxLogFiles' => 20,
                ],
                [
                    'class' => 'yii\log\FileTarget',

                    'levels' => ['error', 'warning','info'],

                    'categories' => ['api'],

                    'logFile' => '@app/runtime/logs/api/operation.log',

                    'maxFileSize' => 1024 * 2,

                    'maxLogFiles' => 20,
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'assetManager'=>[
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js'=>[]
                ],
            ]
        ],
    ],
    'params' => $params
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
