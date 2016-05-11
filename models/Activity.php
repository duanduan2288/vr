<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%activity}}".
 *
 * @property integer $id
 * @property string $activityId
 * @property string $actTitle
 * @property string $actCover
 * @property string $actContent
 * @property string $createTime
 * @property string $modifyTime
 * @property integer $clickNum
 * @property integer $releaseShow
 */
class Activity extends \yii\db\ActiveRecord
{
    use Author;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%activity}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'activityId', 'actTitle', 'actCover', 'actContent', 'createTime', 'modifyTime'], 'required'],
            [['id', 'clickNum', 'releaseShow'], 'integer'],
            [['createTime', 'modifyTime'], 'safe'],
            [['activityId', 'actTitle', 'actCover', 'actContent'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activityId' => 'Activity ID',
            'actTitle' => 'Act Title',
            'actCover' => 'Act Cover',
            'actContent' => 'Act Content',
            'createTime' => 'Create Time',
            'modifyTime' => 'Modify Time',
            'clickNum' => 'Click Num',
            'releaseShow' => 'Release Show',
        ];
    }
}
