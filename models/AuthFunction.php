<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth_function".
 *
 * @property integer $id
 * @property string $name
 * @property string $controller
 * @property string $action
 * @property string $url
 * @property string $platform
 * @property string $created
 * @property string $modified
 */
class AuthFunction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vr_auth_function';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['controller', 'action', 'created', 'modified'], 'required'],
            [['platform'], 'string'],
            [['created', 'modified'], 'safe'],
            [['name', 'controller', 'action'], 'string', 'max' => 100],
            [['url'], 'string', 'max' => 250]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'controller' => 'Controller',
            'action' => 'Action',
            'url' => 'Url',
            'platform' => 'Platform',
            'created' => 'Created',
            'modified' => 'Modified',
        ];
    }
}
