<?php

namespace common\components\Game\Prizes;


use common\components\Game\Prizes;
use common\models\PrizeType;
use common\models\User;
use common\models\UserPrize;
use common\models\UsersInfo;

use common\components\TestBank\TestBank;


/**
 * Class Many
 * @package common\components\Game\Prizes
 */
class Many extends Prizes
{

    /**
     * Checking the availability many prizes before the game
     * @return array|bool
     */
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
    /**
     * Getting the interval of acceptable values for prizes
     * @return array
     */
    public function getIntervalRepeatability()
    {
        return [
            'from' => \Yii::$app->params['Many.repeatabilityFrom'],
            'to' => \Yii::$app->params['Many.repeatabilityTo'],
        ];
    }

    /**
     * Saving the user's contact details for sending the prize
     * @return mixed
     */
    public function userContacts()
    {

        $model = 'frontend\models\\' . Prizes::PRYZE_CLASS_MANY . 'ContactForm';
        return new $model();
    }

    /**
     * Getting the view name
     * @return string
     */
    public function getViewName()
    {
        return 'user-data-many';
    }

    /**
     * Saving Prize Data
     * @param $prize_array
     * @return bool
     * @throws \Exception
     * @throws \Throwable
     */
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

    /**
     * Changing the total number of prizes of a certain type
     * @param $value
     * @return bool
     */
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

    /**
     * Prize Cancellation
     * @param UserPrize $prize
     * @return bool
     * @throws \Exception
     * @throws \Throwable
     */
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

    /**
     * Sending a Prize
     * @param UserPrize $prize
     * @return array
     * @throws \Exception
     * @throws \Throwable
     */
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

    /**
     * Prize conversion
     * @param UserPrize $prize
     * @return string
     */
    public function prizeConvert(UserPrize $prize)
    {
        return 'Not available';
    }


}