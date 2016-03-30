<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth_menu_function".
 *
 * @property integer $id
 * @property integer $menu_id
 * @property integer $function_id
 * @property string $created
 * @property string $modified
 */
class AuthMenuFunction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vr_auth_menu_function';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_id', 'function_id', 'created', 'modified'], 'required'],
            [['menu_id', 'function_id'], 'integer'],
            [['created', 'modified'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'menu_id' => 'Menu ID',
            'function_id' => 'Function ID',
            'created' => 'Created',
            'modified' => 'Modified',
        ];
    }
}
