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
        'db' =>  [
           'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=10.10.15.238;dbname=audit',
            'username' => 'audit',
            'password' => 'Wt3s4rTFfbfuTKDP',
            'charset' => 'utf8',
        ],
    ],
    'params' => $params,
];
