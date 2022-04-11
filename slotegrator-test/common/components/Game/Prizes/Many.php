<?php

namespace common\components\Game\Prizes;

use common\components\debugger\Debugger;
use common\components\Game\Prizes;
use common\models\PrizeType;
use common\models\User;
use common\models\UserPrize;
use common\models\UsersInfo;

use common\components\TestBank\TestBank;


/**
 * Created by PhpStorm.
 * User: artem
 * Date: 06.04.22
 * Time: 16:37
 */
class Many extends Prizes
{


    public function checkBeforeGame()
    {
        $prizeTypeData = $this->getPrizeTypeData(Many::MANY_TYPE_ID);
        if ($prizeTypeData->total >= $prizeTypeData->interval_from) {
            return [
                'prize_type_id' => Prizes::MANY_TYPE_ID,
                'interval_from' => $prizeTypeData->interval_from,
                'interval_to' => $prizeTypeData->total >= $prizeTypeData->interval_to ? $prizeTypeData->interval_to : $prizeTypeData->total
            ];
        }
        return false;
    }

    //интервал в котором рандомно выбирается количество раз повторения приза в массиве
    public function getIntervalRepeatability()
    {
        return [
            'from' => \Yii::$app->params['Many.repeatabilityFrom'],
            'to' => \Yii::$app->params['Many.repeatabilityTo'],
        ];
    }

    public function userContacts()
    {

        $model = 'frontend\models\\' . Prizes::PRYZE_CLASS_MANY . 'ContactForm';
        return new $model();
    }

    public function getViewName()
    {
        return 'user-data-many';
    }

    public function savePrize($prize_array)
    {
        $transaction = UserPrize::getDb()->beginTransaction();
        try {
            $UserPrize = new UserPrize();
            $UserPrize->scenario = UserPrize::SCENARIO_CREATE;
            $UserPrize->uid = \Yii::$app->user->id;
            $UserPrize->ptid = isset($prize_array['prize_type_id']) ? $prize_array['prize_type_id'] : null;
            $UserPrize->many = isset($prize_array['value']) ? $prize_array['value'] : null;
            $UserPrize->status = Prizes::PRIZE_STATUS_SELECTED;
            $UserPrize->save();

            $prize = PrizeType::findOne(Prizes::MANY_TYPE_ID);
            $prize->scenario = PrizeType::SCENARIO_UPDATE;
            $prize->total -= isset($prize_array['value']) ? $prize_array['value'] : null;
            $prize->update();
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

    public function changePrizeNumber($value)
    {
        $prize = PrizeType::findOne(Prizes::MANY_TYPE_ID);
        $prize->scenario = PrizeType::SCENARIO_UPDATE;
        $prize->total -= $value;
        if ($prize->update()) {
            return true;
        }
        return false;
    }

    public function cancelPrize(UserPrize $prize)
    {
            $transaction = UserPrize::getDb()->beginTransaction();
            try {

                $prize->scenario = UserPrize::SCENARIO_UPDATE;
                $prize->status = Prizes::PRIZE_STATUS_CANCELED;
                $prize->update();

                $prize_type = PrizeType::findOne(Prizes::MANY_TYPE_ID);
                $prize_type->scenario = PrizeType::SCENARIO_UPDATE;
                $prize_type->total += $prize->many;
                $prize_type->update();


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

    public function sendPrize(UserPrize $prize)
    {
        $userData = UsersInfo::find()
            ->where(['uid' => $prize->uid])
            ->one();

        $prize->scenario = UserPrize::SCENARIO_UPDATE;
        $prize->status = Prizes::PRIZE_STATUS_SENT;
        $prize->update();

         $bank = new TestBank();
        return [
            'response' =>$bank->sendMany($userData->card_n, $prize->many),
            'text' => 'Денежный приз перечислен на указанную карту: '.$userData->card_n .' пользователя ID - ' .$prize->uid
        ];


    }

    public function prizeConvert(UserPrize $prize)
    {
        return 'Not available';
    }


}