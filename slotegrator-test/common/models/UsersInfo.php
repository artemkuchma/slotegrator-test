<?php

namespace common\models;

use Yii;
use yii\helpers\HtmlPurifier;

/**
 * This is the model class for table "users_info".
 *
 * @property int $id
 * @property int $uid
 * @property string|null $card_n
 * @property string|null $address
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $u
 */
class UsersInfo extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'Create';
    const SCENARIO_UPDATE = 'Update';

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->address = HtmlPurifier::process($this->address);
            $this->card_n = HtmlPurifier::process($this->card_n);

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
        return 'users_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'default', 'value' => time(), 'on' => self::SCENARIO_CREATE],
            [['uid', 'created_at', 'updated_at'], 'required'],
            [['uid', 'created_at', 'updated_at'], 'integer'],
            [['card_n', 'address'], 'string', 'max' => 255],
            [['uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['uid' => 'id']],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['uid', 'card_n', 'created_at', 'updated_at', 'address'];
        $scenarios[self::SCENARIO_UPDATE] = ['uid', 'card_n', 'created_at', 'updated_at', 'address'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'card_n' => 'Card N',
            'address' => 'Address',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[U]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getU()
    {
        return $this->hasOne(User::className(), ['id' => 'uid']);
    }
}
