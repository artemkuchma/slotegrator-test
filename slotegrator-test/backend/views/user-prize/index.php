<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\UserPrizeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User Prizes';
$this->params['breadcrumbs'][] = $this->title;
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
                'attribute' => 'uid',
                'label' => 'Пользователь',
            ],

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
                'template' => '{send}',

                'buttons' => ['send' => function ($url, $model, $key) {
                    if($model->status != \common\components\Game\Prizes::PRIZE_STATUS_CONFIRMED){
                        return null;
                    }
                    return '<a class="btn btn-success" href="/admin/user-prize/send-prize?id='.$model->id.'">Отправить приз</a>';
                },
                    ]
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
