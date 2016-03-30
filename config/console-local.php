<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

$params = require(__DIR__ . '/params.php');

return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\FileTarget',

                    'levels' => ['error', 'warning'],

                    'categories' => ['command'],

                    'logFile' => '@app/runtime/logs/command/requests.log',

                    'maxFileSize' => 1024 * 2,

                    'maxLogFiles' => 20,
                ],
            ],
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
        'db' =>  [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.1.10;dbname=audit',
            'username' => 'platform',
            'password' => 'platform123',
            'charset' => 'utf8',
        ],
    ],
    'params' => $params,
];
