<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrderData */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-data-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'order_id')->textInput() ?>

    <?= $form->field($model, 'log_date')->textInput() ?>

    <?= $form->field($model, 'impression_count')->textInput() ?>

    <?= $form->field($model, 'click_count')->textInput() ?>

    <?= $form->field($model, '25viewed_count')->textInput() ?>

    <?= $form->field($model, '50viewed_count')->textInput() ?>

    <?= $form->field($model, '75viewed_count')->textInput() ?>

    <?= $form->field($model, '100viewed_count')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
