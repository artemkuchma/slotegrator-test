<?php

namespace frontend\controllers;

use common\components\Game\Prizes;
use common\models\UserPrize;
use common\models\UserPrizeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * UserPrizeController implements the CRUD actions for UserPrize model.
 */
class UserPrizeController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'only' => ['index','view','delete', 'bonus-convert'],
                    'rules' => [

                        [
                            'actions' => ['index','view', 'delete', 'bonus-convert'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [

                    ],
                ],
            ]
        );
    }

    /**
     * Lists all UserPrize models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserPrizeSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserPrize model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Deletes an existing UserPrize model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $prize = Prizes::initById($model->ptid);

        $prize->cancelPrize($model);

        return $this->redirect(['index']);
    }





    public function actionBonusConvert($id)
    {
        $model = $this->findModel($id);

        $prize = Prizes::initById($model->ptid);

        $prize->prizeConvert($model);

        return $this->redirect(['index']);
    }









    /**
     * Finds the UserPrize model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return UserPrize the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserPrize::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
