<?php
/**
 * php yii console/bank 2

 */

namespace console\controllers;

use common\components\debugger\Debugger;
use common\components\Game\Prizes;
use common\models\Tariffs;
use common\models\User;
use common\models\UserPrize;
use common\models\UsersLog;
use common\models\UserTariff;
use Yii;
use yii\console\Exception;
use yii\console\ExitCode;


class ConsoleController extends \yii\console\Controller
{


    public function actionBank($n)
    {

        $data = UserPrize::find()->where(['status'=>Prizes::PRIZE_STATUS_CONFIRMED,'ptid'=>Prizes::MANY_TYPE_ID])->limit($n)->all();
        if($data){
            foreach($data as $k=>$v){
                $prize = Prizes::init(Prizes::PRYZE_CLASS_MANY);
                $prize->sendPrize($v);
            }
        }

        return ExitCode::OK;
    }














    public function actionStatus()
    {
        $users = User::find()
            ->where(['status'=>User::STATUS_INACTIVE])
            ->andWhere(['<','created_at',time() - Yii::$app->params['time_life_inactive_user']])
            ->all();
        //удаление неактивных пользоваетелй
        if(is_array($users)){
            foreach($users as $k=> $v){
                $v->delete();
                // при удалении пользователя все его логи также удаляются (тут лог один - регистрация без подтверждения)
                /*
                            $model = new UsersLog();
                            $model->user_id = $v->id;
                            $model->actions_id = 11;
                            $model->description = $v->username;
                            $model->ip = "127.0.0.1";
                            $model->save();*/
                //
            }
        }


        $users_tariff = UserTariff::find()
            ->where(['<','expiration_date',time()])
            ->andWhere(['deleted' => null])
            ->all();
    //    Debugger::PrintR( $users_tariff);

        foreach($users_tariff as $k => $v){

            $this->changeStatus($v);


        }




        return ExitCode::OK;
    }




    private function changeStatus($item)
    {
        $all_user_status = UserTariff::find()
            ->where(['user_id' => $item->user_id])
            ->andWhere(['!=','status', UserTariff::STATUS_BLOCKED])
            ->andWhere(['!=','status', UserTariff::STATUS_NEXT_ACTIVE])
            ->andWhere(['!=','status', UserTariff::STATUS_REQUIRED_NEXT_ACTIVE])
            ->all();
        $status_next_active = UserTariff::find()
            ->where(['user_id' => $item->user_id, 'status' => UserTariff::STATUS_NEXT_ACTIVE,'deleted' => null])
            ->orderBy(['expiration_date'=> SORT_ASC])
            ->all();
        $all_user_status_revers = array_reverse($all_user_status);
        $item->scenario = UserTariff::SCENARIO_UPDATE;
        $item->deleted = 1;
        $item->update();

        switch ($item->status){
            case UserTariff::STATUS_BLOCKED:

                $user = User::findOne($item->user_id);
                if($all_user_status_revers[0]->status == UserTariff::STATUS_REQUIRED){
                    $status_before = User::STATUS_ACTIVE;
                }elseif($all_user_status_revers[0]->status == UserTariff::STATUS_ACTIVE){
                    $status_before = User::STATUS_ACTIVE_PAY;
                }else{
                    $status_before = User::STATUS_BLOCK;
                }

                $user->status = $status_before;
                $user->update();

                $model = new UserTariff();
                $model->scenario = UserTariff::SCENARIO_CREATE;
                $model->status = $all_user_status_revers[0]->status;
                $model->tariff_id = $all_user_status_revers[0]->tariff_id;
                $model->tariff_price = $all_user_status_revers[0]->tariff_price;
                $model->tariff_time = $all_user_status_revers[0]->tariff_time;
                $model->user_id = $all_user_status_revers[0]->user_id;
                $model->expiration_date = $all_user_status_revers[0]->expiration_date;
                $model->save();


                $this->userIdLog = $item->user_id;
                $this->actionLog = 12;
                $this->descriptionLog = json_encode([
                    'username' => $user->username,
                    'user_status' => $user->status,
                    'tariff_id' => $all_user_status_revers[0]->tariff_id,
                    'tariff_price' => $all_user_status_revers[0]->tariff_price,
                    'tariff_time' => $all_user_status_revers[0]->tariff_time,
                    'expiration_date' => $all_user_status_revers[0]->expiration_date

                ]);
                $this->setUserLog();


               // Debugger::PrintR($model->getErrors());
                if($all_user_status_revers[0]->expiration_date < time()){
                    $this->changeStatus($model);

                }

                break;
            case UserTariff::STATUS_ACTIVE:

                if(!empty($status_next_active)){

                    $model = new UserTariff();
                    $model->scenario = UserTariff::SCENARIO_CREATE;
                    $model->status = UserTariff::STATUS_ACTIVE;
                    $model->tariff_id = $status_next_active[0]->tariff_id;
                    $model->tariff_price = $status_next_active[0]->price;//$all_user_status_revers[0]->tariff_price;
                    $model->tariff_time = $status_next_active[0]->time_month;//$all_user_status_revers[0]->tariff_time;
                    $model->user_id = $status_next_active[0]->user_id;
                    $model->expiration_date = $status_next_active[0]->expiration_date;
                    $model->save();

                }else{

                    $user = User::findOne($item->user_id);
                    $user->status = User::STATUS_ACTIVE;
                    $user->update();

                    $this->userIdLog = $user->id;
                    $this->actionLog = 13;
                    $this->descriptionLog = json_encode([
                        'username' => $user->username,
                        'user_status' => $user->status,
                        'tariff_id' => $item->tariff_id,
                        'tariff_price' => $item->tariff_price,
                        'tariff_time' => $item->tariff_time,
                        'expiration_tariff_date' => $item->expiration_date

                    ]);
                    $this->setUserLog();


                    $tariff = Tariffs::findOne($all_user_status_revers[0]->tariff_id);

                    $model = new UserTariff();
                    $model->scenario = UserTariff::SCENARIO_CREATE;
                    $model->status = UserTariff::STATUS_REQUIRED;
                    $model->tariff_id = $all_user_status_revers[0]->tariff_id;
                    $model->tariff_price = $tariff->price;//$all_user_status_revers[0]->tariff_price;
                    $model->tariff_time = $tariff->time_month;//$all_user_status_revers[0]->tariff_time;
                    $model->user_id = $all_user_status_revers[0]->user_id;
                    $model->expiration_date = $this->expirationDateDay(Yii::$app->params['time_life_next_pay_waiting_user']);
                    $model->save();

                }

                break;
            case UserTariff::STATUS_REQUIRED:
                $user = User::findOne($item->user_id);
                $user->status = User::STATUS_DELETED;
                $user->update();

                $log_method = 3;

                foreach($all_user_status as $key => $val){
                    if($val->status == UserTariff::STATUS_ACTIVE){
                        $log_method = 4;
                    }
                }

                $this->userIdLog = $user->id;
                $this->actionLog = $log_method;
                $this->descriptionLog = json_encode([
                    'username' => $user->username,
                    'user_status' => $user->status,
                    'tariff_id' => $item->tariff_id,
                    'tariff_price' => $item->tariff_price,
                    'tariff_time' => $item->tariff_time,
                    'expiration_tariff_date' => $item->expiration_date

                ]);
                $this->setUserLog();
                break;
            default:
                break;
        }
    }

    private function expirationDateDay($days)
    {
        return time() + $days * 86400;
    }

    public function setUserLog()
    {
        $model = new UsersLog();
        $model->user_id = $this->userIdLog;
        $model->actions_id = $this->actionLog;
        $model->description = $this->descriptionLog;
        $model->ip = "127.0.0.1";
        $model->save();
    }

    public function actionTest()
    {
        $this->userIdLog = 1;
        $this->actionLog = 4;
        $this->descriptionLog = 'test';
        $this->setUserLog();

        return ExitCode::OK;
    }

}