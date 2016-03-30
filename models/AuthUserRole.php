<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth_user_role".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $role_id
 * @property string $created
 */
class AuthUserRole extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_user_role}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'role_id', 'created'], 'required'],
            [['user_id', 'role_id'], 'integer'],
            [['created'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'role_id' => 'Role ID',
            'created' => 'Created',
        ];
    }
}
