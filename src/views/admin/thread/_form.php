<?php

use kartik\select2\Select2;
use rats\forum\models\Forum;
use rats\forum\models\Thread;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\JsExpression;

/** @var yii\web\View $this */
/** @var rats\forum\models\Thread $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="thread-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fk_forum')->widget(Select2::class, [
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 3,
            'ajax' => [
                'url' => \yii\helpers\Url::to(['forum-list']),
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(model) { return model.text; }'),
            'templateSelection' => new JsExpression('function (model) { return model.text; }'),
        ],
        'hideSearch' => false,
    ]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->widget(Select2::class, [
        'data' => [
            Thread::STATUS_INACTIVE => Yii::t('app', 'Inactive'),
            Thread::STATUS_ACTIVE_UNLOCKED => Yii::t('app', 'Unlocked'),
            Thread::STATUS_ACTIVE_LOCKED => Yii::t('app', 'Locked'),
        ],
        'hideSearch' => false,
    ]) ?>

    <?= $form->field($model, 'views')->textInput() ?>

    <?= $form->field($model, 'pinned')->widget(Select2::class, [
        'data' => [
            Thread::PINNED_FALSE => Yii::t('app', 'No'),
            Thread::PINNED_TRUE => Yii::t('app', 'Yes'),
        ],
        'hideSearch' => false,
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>