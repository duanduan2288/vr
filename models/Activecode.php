<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%activecode}}".
 *
 * @property integer $id
 * @property string $activeCode
 * @property integer $status
 * @property string $createTime
 * @property string $modifyTime
 */
class Activecode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%activecode}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'activeCode', 'status', 'createTime'], 'required'],
            [['id', 'status'], 'integer'],
            [['createTime', 'modifyTime'], 'safe'],
            [['activeCode'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activeCode' => 'Active Code',
            'status' => 'Status',
            'createTime' => 'Create Time',
            'modifyTime' => 'Modify Time',
        ];
    }
}
