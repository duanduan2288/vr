<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%appverify}}".
 *
 * @property integer $id
 * @property string $appKey
 * @property string $appToken
 * @property string $userId
 */
class Appverify extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%appverify}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'appKey', 'appToken', 'userId'], 'required'],
            [['id'], 'integer'],
            [['appKey', 'appToken', 'userId'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'appKey' => 'App Key',
            'appToken' => 'App Token',
            'userId' => 'User ID',
        ];
    }
}
