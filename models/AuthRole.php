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
 * @property string $platform
 * @property string $type
 * @property integer $registrar_id
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
        return 'vr_auth_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['guid', 'creator', 'created'], 'required'],
            [['platform', 'type'], 'string'],
            [['registrar_id', 'creator'], 'integer'],
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
            'platform' => 'Platform',
            'type' => 'Type',
            'registrar_id' => 'Registrar ID',
            'creator' => 'Creator',
            'created' => 'Created',
        ];
    }
}
