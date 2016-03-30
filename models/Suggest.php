<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%suggest}}".
 *
 * @property integer $id
 * @property string $userId
 * @property string $content
 * @property string $source_type
 * @property string $createTime
 * @property string $phone
 */
class Suggest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%suggest}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'userId', 'content', 'createTime'], 'required'],
            [['id'], 'integer'],
            [['createTime'], 'safe'],
            [['userId'], 'string', 'max' => 200],
            [['content'], 'string', 'max' => 500],
            [['source_type'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userId' => 'User ID',
            'content' => 'Content',
            'source_type' => 'Source Type',
            'createTime' => 'Create Time',
            'phone' => 'Phone',
        ];
    }
}
