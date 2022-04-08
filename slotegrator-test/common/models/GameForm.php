<?php

namespace common\models;

use common\components\Game\GameEnjine;
use Yii;
use yii\base\Model;

/**
 * Game start form
 */
class GameForm extends Model
{

public $check;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // check must be empty

            ['check', 'boolean', 'trueValue' => null, 'strict' => true]
        ];
    }



    /**
     * Logs in a user using the provided username and password.
     *
     * @return array|int|null
     */
    public function startGame()
    {
        if ($this->validate()) {

            $gameEnjine = new GameEnjine();


            return $gameEnjine->game(Yii::$app->params['gameEmptyValueNumber']);
        }
        
        return false;
    }


}
