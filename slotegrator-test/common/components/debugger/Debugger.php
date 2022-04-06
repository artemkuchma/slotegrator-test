<?php

namespace common\components\debugger;
use Yii;
//use yii\base\Component;

 abstract class Debugger
{

     private static $ip = '62.205.133.244';


    public static function PrintR($item)
    {
       // if ($_SERVER["REMOTE_ADDR"] == self::$ip) {
            switch (gettype($item)) {
                case 'array':
                    echo '<br/><b> Its Array</b><br/>';
                    break;
                case 'object':
                    echo '<br/><b> Its Object</b><br/>';
                    break;
                default:
                    echo '<br/><b> Its NOT array or object</b><br/>';
            }
            if (gettype($item) == 'array' || gettype($item) == 'object') {
                echo '<pre>';
                print_r($item);
                echo '</pre>';
            }
     //   }

    }

    public static function VarDamp($item)
    {
     //   if ($_SERVER["REMOTE_ADDR"] == self::$ip) {

            echo '<pre>';
            var_dump($item);
            echo '</pre>';
      //  }
    }

    public static function Eho($item)
    {
     //   if ($_SERVER["REMOTE_ADDR"] == self::$ip) {
            echo $item . '</br>';
      //  }

    }

    public static function testDie()
    {
        die('ups');
    }

     public static function EhoBr($item)
     {
       //  if ($_SERVER["REMOTE_ADDR"] == self::$ip) {
             echo '</br>';
             echo '</br>';
             echo '</br>';
             echo '</br>';
             echo '</br>';
             echo '</br>';
             echo $item . '</br>';
      //   }
     }
     public static function Br()
     {
         echo '</br>';
         echo '</br>';
         echo '</br>';
         echo '</br>';
         echo '</br>';
         echo '</br>';
     }


}