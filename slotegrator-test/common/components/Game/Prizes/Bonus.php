<?php

namespace common\components\Game\Prizes;

use common\components\debugger\Debugger;
use common\components\Game\Prizes;
use common\models\PrizeType;
use common\models\UserPrize;


/**
 * Created by PhpStorm.
 * User: artem
 * Date: 06.04.22
 * Time: 16:37
 */
class Bonus extends Prizes
{


    public function checkBeforeGame()
    {
        $prizeTypeData = $this->getPrizeTypeData(Bonus::BONUS_TYPE_ID);

        return [
            'prize_type_id' => Prizes::BONUS_TYPE_ID,
            'interval_from' => $prizeTypeData->interval_from,
            'interval_to' => $prizeTypeData->interval_to
        ];
    }

    public function getIntervalRepeatability()
    {
        return [
            'from' => \Yii::$app->params['Bonus.repeatabilityFrom'],
            'to' => \Yii::$app->params['Bonus.repeatabilityTo'],
        ];
    }

    public function userContacts()
    {

        $model = 'frontend\models\\'.Prizes::PRYZE_CLASS_BONUS .'ContactForm';
        return new $model();
    }

    public function getViewName()
    {
        return 'user-data-bonus';
    }

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


    public function changePrizeNumber($value)
    {
        return true;
    }

    public function cancelPrize(UserPrize $prize)
    {
            $prize->scenario = UserPrize::SCENARIO_UPDATE;
            $prize->status = Prizes::PRIZE_STATUS_CANCELED;
            $prize->update();
    }

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

    public function prizeConvert(UserPrize $prize)
    {
        return '';
    }

}