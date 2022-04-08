<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\UserPrizeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ваши призы';

?>
<div class="user-prize-index">

    <h1><?= Html::encode($this->title) ?></h1>



    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],


            'uid',
            'ptid',
            'bonus',
            'many',
            //'item_id',
            //'status',

            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, \common\models\UserPrize $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
