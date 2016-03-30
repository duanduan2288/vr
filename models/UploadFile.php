<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "upload_file".
 *
 * @property string $id
 * @property string $guid
 * @property string $filename
 * @property string $filetype
 * @property string $original_filename
 * @property string $upload_role
 * @property integer $company_id
 * @property integer $user_id
 * @property string $ip
 * @property string $created
 */
class UploadFile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'upload_file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['guid', 'filename', 'filetype', 'original_filename', 'upload_role', 'company_id', 'user_id', 'ip', 'created'], 'required'],
            [['upload_role'], 'string'],
            [['company_id', 'user_id'], 'integer'],
            [['created'], 'safe'],
            [['guid'], 'string', 'max' => 64],
            [['filename'], 'string', 'max' => 500],
            [['filetype'], 'string', 'max' => 50],
            [['original_filename'], 'string', 'max' => 250],
            [['ip'], 'string', 'max' => 15]
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
            'filename' => 'Filename',
            'filetype' => 'Filetype',
            'original_filename' => 'Original Filename',
            'upload_role' => 'Upload Role',
            'company_id' => 'Company ID',
            'user_id' => 'User ID',
            'ip' => 'Ip',
            'created' => 'Created',
        ];
    }
}
