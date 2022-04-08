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
class ManyContactForm extends Model
{
    public $card;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // address are required
            [['card'], 'required'],
            [['card'], 'integer'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function saveContact(UsersInfo $userInfo)
    {
        if ($this->validate()) {

            $transaction = UsersInfo::getDb()->beginTransaction();
            try {
                //сохранение контактный данных для отправки приза
                $userInfo->uid = Yii::$app->user->id;
                $userInfo->card_n = $this->card;
                $userInfo->validate();
                $userInfo->save();
                //изменение статуса призов с типом many

                $prizes = UserPrize::find()->where(['status' => Prizes::PRIZE_STATUS_SELECTED, 'ptid' => Prizes::MANY_TYPE_ID])->all();
                if(is_array($prizes)) {
                    foreach ($prizes as $k => $v) {
                        $v->scenario = UserPrize::SCENARIO_UPDATE;
                        $v->status = Prizes::PRIZE_STATUS_CONFIRMED;
                        $v->update();
                    }
                }
                $transaction->commit();

                return true;

            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }


        }
        return false;
    }

}
