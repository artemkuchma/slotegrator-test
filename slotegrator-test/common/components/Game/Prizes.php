<?php

namespace common\components\Game;
use common\components\debugger\Debugger;
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


    public static function init($className){


        $p = 'common\components\Game\Prizes\\'.$className;

        return new $p();

    }

    public static function initById($id){

        if(isset(self::$className[$id])){
            $p = 'common\components\Game\Prizes\\'.self::$className[$id];
            return new $p();
        }

        throw new NotFoundHttpException('Class not found.');

    }

    abstract public function checkBeforeGame();


    abstract public function userContacts();


    abstract public function getIntervalRepeatability();


    abstract public function changePrizeNumber($value);


    abstract public function savePrize($prize_array);


    abstract public function getViewName();


    abstract public function cancelPrize(UserPrize $prize);


    abstract public function sendPrize(UserPrize $prize);

    abstract public function prizeConvert(UserPrize $prize);


    protected function getPrizeTypeData($id){

        return PrizeType::findOne($id);

    }

}