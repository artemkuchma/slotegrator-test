<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_prize".
 *
 * @property int $id
 * @property int $uid
 * @property int|null $ptid
 * @property int|null $bonus
 * @property int|null $item_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Items $item
 * @property PrizeType $pt
 * @property User $u
 */
class UserPrize extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_prize';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'created_at', 'updated_at'], 'required'],
            [['uid', 'ptid', 'bonus', 'item_id', 'created_at', 'updated_at'], 'integer'],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Items::className(), 'targetAttribute' => ['item_id' => 'id']],
            [['ptid'], 'exist', 'skipOnError' => true, 'targetClass' => PrizeType::className(), 'targetAttribute' => ['ptid' => 'id']],
            [['uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['uid' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'ptid' => 'Ptid',
            'bonus' => 'Bonus',
            'item_id' => 'Item ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Item]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Items::className(), ['id' => 'item_id']);
    }

    /**
     * Gets query for [[Pt]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPt()
    {
        return $this->hasOne(PrizeType::className(), ['id' => 'ptid']);
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
