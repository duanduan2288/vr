<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "review_config".
 *
 * @property integer $id
 * @property string $strategy
 * @property string $detail
 * @property string $treatment
 * @property string $status
 * @property string $created
 * @property string $modified
 * @property integer $operator_id
 * @property string $guid
 */
class ReviewConfig extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'review_config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['strategy', 'treatment', 'status'], 'string'],
            [['detail', 'created', 'modified', 'operator_id', 'guid'], 'required'],
            [['created', 'modified'], 'safe'],
            [['operator_id'], 'integer'],
            [['detail'], 'string', 'max' => 250],
            [['guid'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'strategy' => 'Strategy',
            'detail' => 'Detail',
            'treatment' => 'Treatment',
            'status' => 'Status',
            'created' => 'Created',
            'modified' => 'Modified',
            'operator_id' => 'Operator ID',
            'guid' => 'Guid',
        ];
    }
}
