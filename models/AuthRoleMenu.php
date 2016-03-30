<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth_role_menu".
 *
 * @property integer $id
 * @property integer $role_id
 * @property integer $menu_id
 */
class AuthRoleMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_role_menu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'menu_id'], 'required'],
            [['role_id', 'menu_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_id' => 'Role ID',
            'menu_id' => 'Menu ID',
        ];
    }
}
