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


            [
                'attribute' => 'many',
                'label' => 'Денежный приз',
            ],
            [
                'attribute' => 'bonus',
                'label' => 'Бонусный приз',
            ],
            [
                'attribute' => 'item_id',
                'label' => 'Материальный приз',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $column) {
                    return isset($model->item) ? $model->item->name : null;
                }
            ],
            [
                'attribute' => 'status',
                'label' => 'Статус',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $column) {

                    return $model->statusD->name;
                }
            ],

            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, \common\models\UserPrize $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 },
                 'template' => '{delete}{confirm}',

                'buttons' => [
                    'delete' => function ($url, $model, $key) {
                    if($model->status == \common\components\Game\Prizes::PRIZE_STATUS_CANCELED || $model->status == \common\components\Game\Prizes::PRIZE_STATUS_SENT){
                        return null;
                    }
                    return '<a class="btn btn-danger" href="'.$url.'">Отказатьс от приза</a>';
                },
                'confirm' => function ($url, $model, $key) {
                    if($model->status == \common\components\Game\Prizes::PRIZE_STATUS_SELECTED ){
                        $text = $model->ptid == 2 ? 'Подтвердить приз' : 'Добавить данные для отправки';
                        return '<a class="btn btn-success" href="/site/user-data?prize_type='.$model->ptid.'">'.$text.'</a>';
                    }
                    return null;
                },]
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
