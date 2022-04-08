<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'User Data';

?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>



        <p>
            Для получения приза необходимо указать адрес отправки.
        </p>

        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                <?= $form->field($model, 'address')->textInput(['autofocus' => true, 'value' => $userInfo->address]) ?>


                <div class="form-group">
                    <?= Html::submitButton('Сохранить контакты', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>



</div>
