<?php

namespace common\models;

use Yii;
use yii\helpers\HtmlPurifier;

/**
 * This is the model class for table "prize_status".
 *
 * @property int $id
 * @property string $name
 */
class PrizeStatus extends \yii\db\ActiveRecord
{


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->name = HtmlPurifier::process($this->name);

            return true;
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prize_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }
}
