<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth_role".
 *
 * @property integer $id
 * @property string $guid
 * @property string $name
 * @property string $name_en
 * @property string $type
 * @property integer $creator
 * @property string $created
 */
class AuthRole extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_role}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['guid', 'creator', 'created'], 'required'],
            [['type'], 'string'],
            [['creator'], 'integer'],
            [['created'], 'safe'],
            [['guid', 'name_en'], 'string', 'max' => 100],
            [['name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'guid' => 'Guid',
            'name' => 'Name',
            'name_en' => 'Name En',
            'type' => 'Type',
            'creator' => 'Creator',
            'created' => 'Created',
        ];
    }
}
