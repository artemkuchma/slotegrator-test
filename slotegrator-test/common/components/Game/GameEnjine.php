<?php
/**
 *
 * Prizes draw mechanism
 */

namespace common\components\Game;


use common\components\debugger\Debugger;
use common\models\PrizeType;

class GameEnjine
{
    public $mainArray;


    public function game($n_empty)
    {
        $this->mainArray = [];

        $this->setPrizeForGame(Prizes::PRYZE_CLASS_MANY);
        $this->setPrizeForGame(Prizes::PRYZE_CLASS_BONUS);
        $this->setPrizeForGame(Prizes::PRYZE_CLASS_ITEM);


        $empty_array = array_fill(0, $n_empty, -1); //range(0,$n_empty);
        $this->mainArray = array_merge($this->mainArray, $empty_array);

        shuffle($this->mainArray);

     //   Debugger::PrintR($this->mainArray);
      //  Debugger::testDie();

        $result_number = array_rand($this->mainArray);
//Debugger::EhoBr('t');
      //  Debugger::PrintR($this->mainArray[$result_number]);
      //  Debugger::EhoBr($this->mainArray[$result_number]);


       if(isset($this->mainArray[$result_number])){
           if(is_array($this->mainArray[$result_number])){
               $prize = Prizes::initById($this->mainArray[$result_number]['prize_type_id']);
               $prize->savePrize($this->mainArray[$result_number]);

           }

           return $this->mainArray[$result_number];
       }

        return false;

    }

    protected function setPrizeForGame($prizeType){

        $prize = Prizes::init($prizeType);
        $data = $prize->checkBeforeGame();

        if($data){
            $intervaRepeatability = $prize->getIntervalRepeatability();
            for($i = 1; $i <= mt_rand($intervaRepeatability['from'], $intervaRepeatability['to']) ; $i++){

                $data['value'] = $data['prize_type_id']== Prizes::ITEM_TYPE_ID ?
                    $data['id_list'][array_rand($data['id_list'])]  :
                    mt_rand($data['interval_from'], $data['interval_to']);

                $this->mainArray[] = $data;
            }

        }
    }



}