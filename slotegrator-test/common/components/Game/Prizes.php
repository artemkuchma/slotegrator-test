<?php

namespace common\components\Game;

use common\models\PrizeType;
use common\models\UserPrize;
use yii\web\NotFoundHttpException;

/**
 * Prize property management
 *
 */
abstract class Prizes
{
    const MANY_TYPE_ID = 1;
    const BONUS_TYPE_ID = 2;
    const ITEM_TYPE_ID = 3;
    const PRYZE_CLASS_MANY = 'Many';
    const PRYZE_CLASS_BONUS = 'Bonus';
    const PRYZE_CLASS_ITEM = 'Item';
    const PRIZE_STATUS_SELECTED = 1;
    const PRIZE_STATUS_CONFIRMED = 2;
    const PRIZE_STATUS_SENT = 3;
    const PRIZE_STATUS_CANCELED = 4;

    public static $className = [
        self::MANY_TYPE_ID => self::PRYZE_CLASS_MANY,
        self::BONUS_TYPE_ID => self::PRYZE_CLASS_BONUS,
        self::ITEM_TYPE_ID => self::PRYZE_CLASS_ITEM
    ];

    /**
     * @param $className
     * @return mixed
     */
    public static function init($className){


        $p = 'common\components\Game\Prizes\\'.$className;

        return new $p();

    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public static function initById($id){

        if(isset(self::$className[$id])){
            $p = 'common\components\Game\Prizes\\'.self::$className[$id];
            return new $p();
        }

        throw new NotFoundHttpException('Class not found.');

    }

    /**
     * Checking the availability of all types of prizes before the game
     * @return mixed
     */
    abstract public function checkBeforeGame();

    /**
     * Saving the user's contact details for sending the prize
     * @return mixed
     */
    abstract public function userContacts();

    /**
     * Getting the interval of acceptable values for prizes
     * @return mixed
     */
    abstract public function getIntervalRepeatability();

    /**
     * Changing the total number of prizes of a certain type
     * @return mixed
     */
    abstract public function changePrizeNumber($value);

    /**
     * Saving Prize Data
     * @return mixed
     */
    abstract public function savePrize($prize_array);

    /**
     * Getting the view name
     * @return mixed
     */
    abstract public function getViewName();

    /**
     * Prize Cancellation
     * @return mixed
     */
    abstract public function cancelPrize(UserPrize $prize);

    /**
     * Sending a Prize
     * @return mixed
     */
    abstract public function sendPrize(UserPrize $prize);

    /**
     * Prize conversion
     * @return mixed
     */
    abstract public function prizeConvert(UserPrize $prize);

    /**
     * Prize type data
     * @return mixed
     */
    protected function getPrizeTypeData($id){

        return PrizeType::findOne($id);

    }

}