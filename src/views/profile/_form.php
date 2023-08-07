<?php

/** @var yii\web\View $this */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

?>

<?php $form = ActiveForm::begin([
    'method' => 'post',
]); ?>
<div class="row justify-content-center card shadow-sm p-4">
    <div class="col-11">
    </div>
    <div class="row">
        <div class="col-6">
            <?= $form->field($model, 'real_name')->textInput(['maxlength' => true]); ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'username')->textInput(['maxlength' => true]); ?>
        </div>
        <?= $form->field($model, 'email')->textInput(['maxlength' => true]); ?>
    </div>

    <div class="d-flex justify-content-end">
        <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-outline-dark']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>