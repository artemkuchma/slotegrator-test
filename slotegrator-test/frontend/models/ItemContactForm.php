<?php

namespace frontend\models;

use common\components\Game\Prizes;
use common\models\UserPrize;
use common\models\UsersInfo;
use Yii;
use yii\base\Model;

/**
 * AddressForm is the model behind the address form.
 */
class ItemContactForm extends Model
{
    public $address;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // address are required
            [['address'], 'required'],

        ];
    }

    /**
     * @param UsersInfo $userInfo
     * @return bool
     * @throws \Exception
     * @throws \Throwable
     */
    public function saveContact(UsersInfo $userInfo)
    {
        if ($this->validate()) {
            $transaction = UsersInfo::getDb()->beginTransaction();
            try {

                //сохранение контактный данных для отправки приза
                $userInfo->uid = Yii::$app->user->id;
                $userInfo->address = $this->address;
                $userInfo->save();

                //изменение статуса призов с типом many

                $prizes = UserPrize::find()->where(['status' => Prizes::PRIZE_STATUS_SELECTED, 'ptid' => Prizes::ITEM_TYPE_ID])->all();
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
