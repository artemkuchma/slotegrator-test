<?php

namespace common\components\Game\Prizes;


use common\components\Game\Prizes;
use common\models\Items;
use common\models\PrizeType;
use common\models\UserPrize;
use common\models\UsersInfo;


/**
 * Class Item
 * @package common\components\Game\Prizes
 */
class Item extends Prizes
{

    /**
     * Checking the availability of item prizes before the game
     * @return array|bool
     */
    public function checkBeforeGame()
    {
        $prizeTypeData = $this->getPrizeTypeData(Item::ITEM_TYPE_ID);
        if ($prizeTypeData->total >= 1) {

            return [
                'prize_type_id' => Prizes::ITEM_TYPE_ID,
                'interval_from' => $prizeTypeData->interval_from,
                'interval_to' => $prizeTypeData->interval_to,
                'id_list' => $this->getIdArray(),
            ];
        }

        return false;
    }

    /**
     * Getting the interval of acceptable values for prizes
     * @return array
     */
    public function getIntervalRepeatability()
    {
        return [
            'from' => \Yii::$app->params['Item.repeatabilityFrom'],
            'to' => \Yii::$app->params['Item.repeatabilityTo'],
        ];
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
            //сохранение данных о выбраном призе в статусе -Выбрано
            $UserPrize = new UserPrize();
            $UserPrize->scenario = UserPrize::SCENARIO_CREATE;
            $UserPrize->uid = \Yii::$app->user->id;
            $UserPrize->ptid = isset($prize_array['prize_type_id']) ? $prize_array['prize_type_id'] : null;
            $UserPrize->item_id = isset($prize_array['value']) ? $prize_array['value'] : null;
            $UserPrize->status = Prizes::PRIZE_STATUS_SELECTED;
            $UserPrize->save();
            //уменьшение общей суммы призов типа item в таблице типов призов
            $prize = PrizeType::findOne(Prizes::ITEM_TYPE_ID);
            $prize->scenario = PrizeType::SCENARIO_UPDATE;
            $prize->total -= 1;
            $prize->update();
            //уменьшение суммы единиц приза определенного типа в таблице items
            $item = Items::findOne($UserPrize->item_id);
            $item->scenario = Items::SCENARIO_UPDATE;
            $item->number -= 1;
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
     * Saving the user's contact details for sending the prize
     * @return mixed
     */
    public function userContacts()
    {

        $model = 'frontend\models\\' . Prizes::PRYZE_CLASS_ITEM . 'ContactForm';
        return new $model();
    }

    /**
     * Getting the view name
     * @return string
     */
    public function getViewName()
    {
        return 'user-data-item';
    }

    /**
     * @return array
     */
    private function getIdArray()
    {
        $item_array = [];
        foreach (Items::find()->select('id')->all() as $k => $v) {
            $item_array[] = $v->id;
        }

        return $item_array;
    }

    /**
     * Changing the total number of item prizes
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

            //уменьшение общей суммы призов типа item в таблице типов призов
            $prizeType = PrizeType::findOne(Prizes::ITEM_TYPE_ID);
            $prizeType->scenario = PrizeType::SCENARIO_UPDATE;
            $prizeType->total += 1;
            $prizeType->update();
            //уменьшение суммы единиц приза определенного типа в таблице items
            $item = Items::findOne($prize->item_id);
            $item->scenario = Items::SCENARIO_UPDATE;
            $item->number += 1;
            $item->update();

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


        return [
            'response' => '',
            'text' => 'Приз был отправлен по адресу: ' . $userData->address
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