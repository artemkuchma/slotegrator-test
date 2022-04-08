<?php

namespace common\models;

use Yii;
use yii\helpers\HtmlPurifier;

/**
 * This is the model class for table "items".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $number
 * @property int $created_at
 * @property int $updated_at
 *
 * @property UserPrize[] $userPrizes
 */
class Items extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'Create';
    const SCENARIO_UPDATE = 'Update';



    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->name = HtmlPurifier::process($this->name);

            return true;
        } else {
            return false;
        }
    }
    public function beforeValidate()
    {
        if(self::SCENARIO_UPDATE){
            $this->updated_at = time();
        }
        return parent::beforeValidate();
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'default', 'value' => time(), 'on' => self::SCENARIO_CREATE],
            [['name', 'created_at', 'updated_at'], 'required'],
            [['number', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['name', 'number', 'created_at', 'updated_at'];
        $scenarios[self::SCENARIO_UPDATE] = ['name', 'number', 'created_at', 'updated_at'];;
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'number' => 'Number',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[UserPrizes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserPrizes()
    {
        return $this->hasMany(UserPrize::className(), ['item_id' => 'id']);
    }
}
