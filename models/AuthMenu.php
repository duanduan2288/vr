<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth_menu".
 *
 * @property integer $id
 * @property string $name
 * @property string $name_en
 * @property string $description
 * @property integer $weight
 * @property string $link
 * @property integer $parent_id
 * @property string $image
 * @property string $target
 * @property string $default_menu
 * @property string $deleted
 * @property string $created
 * @property string $modified
 */
class AuthMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_menu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'created', 'modified'], 'required'],
            [['weight', 'parent_id'], 'integer'],
            [['target', 'default_menu', 'deleted'], 'string'],
            [['created', 'modified'], 'safe'],
            [['name', 'name_en'], 'string', 'max' => 100],
            [['description', 'link', 'image'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'name_en' => 'Name En',
            'description' => 'Description',
            'weight' => 'Weight',
            'link' => 'Link',
            'parent_id' => 'Parent ID',
            'image' => 'Image',
            'target' => 'Target',
            'default_menu' => 'Default Menu',
            'deleted' => 'Deleted',
            'created' => 'Created',
            'modified' => 'Modified',
        ];
    }
}
