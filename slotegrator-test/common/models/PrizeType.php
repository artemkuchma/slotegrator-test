<?php

namespace common\models;

use Yii;
use yii\helpers\HtmlPurifier;

/**
 * This is the model class for table "prize_type".
 *
 * @property int $id
 * @property string $name
 * @property int|null $total
 * @property int|null $interval_from
 * @property int|null $interval_to
 * @property float|null $coefficient_to_many
 * @property int $created_at
 * @property int $updated_at
 *
 * @property UserPrize[] $userPrizes
 */
class PrizeType extends \yii\db\ActiveRecord
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
        return 'prize_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'default', 'value' => time(), 'on' => self::SCENARIO_CREATE],
            [['name', 'created_at', 'updated_at'], 'required'],
            [['total', 'interval_from', 'interval_to', 'created_at', 'updated_at'], 'integer'],
            [['coefficient_to_many'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['name', 'total','interval_from','interval_to','coefficient_to_many', 'created_at', 'updated_at'];
        $scenarios[self::SCENARIO_UPDATE] = ['name', 'total','interval_from','interval_to','coefficient_to_many', 'created_at', 'updated_at'];;
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
            'total' => 'Total',
            'interval_from' => 'Interval From',
            'interval_to' => 'Interval To',
            'coefficient_to_many' => 'Coefficient To Many',
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
        return $this->hasMany(UserPrize::className(), ['ptid' => 'id']);
    }
}
