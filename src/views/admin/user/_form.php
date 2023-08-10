<?php

use kartik\select2\Select2;
use rats\forum\models\User;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/* @var yii\web\View $this */
/* @var rats\forum\models\User $model */
/* @var yii\bootstrap5\ActiveForm $form */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->widget(Select2::class, [
        'data' => [
            User::STATUS_ACTIVE => Yii::t('app', 'Active'),
            User::STATUS_DELETED => Yii::t('app', 'Deleted'),
            User::STATUS_SILENCED => Yii::t('app', 'Silenced'),
        ],
        'hideSearch' => false,
    ]) ?>

    <div class="form-group field-user-role required">
        <label class="control-label" for="user-password_hash">Role</label>
        <?= Select2::widget([
            'name' => 'role',
            'id' => 'user-role',
            'options' => ['placeholder' => Yii::t('app', 'Choose role..')],
            'value' => array_keys($model->roles),
            'data' => User::getAvailableRoles(),
            'hideSearch' => true,
        ]); ?>
        <div class="help-block"></div>
        <div class="invalid-feedback"></div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
