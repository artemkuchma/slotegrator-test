<?php

namespace common\components\Game\Prizes;


use common\components\Game\Prizes;
use common\models\PrizeType;
use common\models\UserPrize;


/**
 * Class Bonus
 * @package common\components\Game\Prizes
 */
class Bonus extends Prizes
{

    /**
     * Checking the availability bonus prizes before the game
     * @return array
     */
    public function checkBeforeGame()
    {
        $prizeTypeData = $this->getPrizeTypeData(Bonus::BONUS_TYPE_ID);

        return [
            'prize_type_id' => Prizes::BONUS_TYPE_ID,
            'interval_from' => $prizeTypeData->interval_from,
            'interval_to' => $prizeTypeData->interval_to
        ];
    }

    /**
     * Getting the interval of acceptable values for prizes
     * @return array
     */
    public function getIntervalRepeatability()
    {
        return [
            'from' => \Yii::$app->params['Bonus.repeatabilityFrom'],
            'to' => \Yii::$app->params['Bonus.repeatabilityTo'],
        ];
    }

    /**
     * Saving the user's contact details for sending the prize
     * @return mixed
     */
    public function userContacts()
    {

        $model = 'frontend\models\\'.Prizes::PRYZE_CLASS_BONUS .'ContactForm';
        return new $model();
    }

    /**
     * Getting the view name
     * @return string
     */
    public function getViewName()
    {
        return 'user-data-bonus';
    }

    /**
     * Saving Prize Data
     * @param $prize_array
     * @return bool
     */
    public function savePrize($prize_array)
    {

        $UserPrize = new UserPrize();
        $UserPrize->scenario = UserPrize::SCENARIO_CREATE;
        $UserPrize->uid = \Yii::$app->user->id;
        $UserPrize->ptid = isset($prize_array['prize_type_id']) ? $prize_array['prize_type_id'] : null;
        $UserPrize->bonus = isset($prize_array['value']) ? $prize_array['value'] : null;
        $UserPrize->status = Prizes::PRIZE_STATUS_SELECTED;
        if ($UserPrize->save()) {
            return true;
        }
        return false;
    }

    /**
     * Changing the total number of bonus prizes
     * @param $value
     * @return bool
     */
    public function changePrizeNumber($value)
    {
        return true;
    }

    /**
     * Prize Cancellation
     * @param UserPrize $prize
     * @throws \Exception
     * @throws \Throwable
     */
    public function cancelPrize(UserPrize $prize)
    {
            $prize->scenario = UserPrize::SCENARIO_UPDATE;
            $prize->status = Prizes::PRIZE_STATUS_CANCELED;
            $prize->update();
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
        $prize->scenario = UserPrize::SCENARIO_UPDATE;
        $prize->status = Prizes::PRIZE_STATUS_SENT;
        $prize->update();

        return [
            'response' =>'',
            'text' => 'Бонусный приз был зачислен на  бонусный счет пользователя ID - '.$prize->uid
        ];

    }

    /**
     * Prize conversion
     * @param UserPrize $prize
     * @return bool
     * @throws \Exception
     * @throws \Throwable
     */
    public function prizeConvert(UserPrize $prize)
    {
        $transaction = UserPrize::getDb()->beginTransaction();
        try {

            $prizeTypeBonus = PrizeType::findOne(Prizes::BONUS_TYPE_ID);

        $prize->scenario = UserPrize::SCENARIO_UPDATE;
        $prize->ptid = Prizes::MANY_TYPE_ID;
        $prize->many =  (int)($prize->bonus * $prizeTypeBonus->coefficient_to_many);
        $prize->bonus = null;
        $prize->status = Prizes::PRIZE_STATUS_SELECTED;
        $prize->update();


        $prizeTypeMany = PrizeType::findOne(Prizes::MANY_TYPE_ID);
        $prizeTypeMany->scenario = PrizeType::SCENARIO_UPDATE;
        $prizeTypeMany->total += $prize->many;
        $prizeTypeMany->update();

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
}