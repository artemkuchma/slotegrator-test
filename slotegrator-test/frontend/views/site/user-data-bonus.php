<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'User Data';

?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

        <p>
            Ваш бонусный приз будет зачислен на счет вашего аккаунта.
        </p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

            <?= $form->field($model, 'name')->hiddenInput([ 'value' => 1])->label(false) ?>


            <div class="form-group">
                <?= Html::submitButton('Подтвердить получение бонусного приза', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>


</div>
