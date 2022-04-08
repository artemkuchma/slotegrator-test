<?php

namespace frontend\models;

use common\components\debugger\Debugger;
use common\components\Game\Prizes;
use common\models\UserPrize;
use common\models\UsersInfo;
use Yii;
use yii\base\Model;

/**
 * AddressForm is the model behind the address form.
 */
class BonusContactForm extends Model
{
    public $name;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function saveContact(UsersInfo $userInfo)
    {
        //изменение статуса призов с типом many
        if ($this->validate()) {
            $prizes = UserPrize::find()->where(['status' => Prizes::PRIZE_STATUS_SELECTED, 'ptid' => Prizes::BONUS_TYPE_ID])->all();

            if(is_array($prizes)){
                foreach ($prizes as $k => $v) {
                    $v->scenario = UserPrize::SCENARIO_UPDATE;
                    $v->status = Prizes::PRIZE_STATUS_CONFIRMED;
                    $v->update();
                }
            }

            return true;
        }

        return false;

    }

}
