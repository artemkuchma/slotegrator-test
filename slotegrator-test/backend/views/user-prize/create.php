<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserPrize */

$this->title = 'Create User Prize';
$this->params['breadcrumbs'][] = ['label' => 'User Prizes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-prize-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
