<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Game';

?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please click the button to start the game.</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'game-form']); ?>

            <?= $form->field($model, 'check')->hiddenInput(['value' => null]) ?>

            <div class="form-group">
                <?= Html::submitButton('Start', ['class' => 'btn btn-primary', 'name' => 'game-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
