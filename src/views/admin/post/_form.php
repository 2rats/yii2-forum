<?php

use kartik\select2\Select2;
use rats\forum\widgets\TinyMce;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\JsExpression;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var rats\forum\models\Post $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fk_thread')->widget(Select2::class, [
        'hideSearch' => false,
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 3,
            'ajax' => [
                'url' => \yii\helpers\Url::to(['thread-list']),
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(model) { return model.text; }'),
            'templateSelection' => new JsExpression('function (model) { return model.text; }'),
        ],
    ]) ?>

    <?= $form->field($model, 'fk_parent')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->widget(TinyMce::class, [
        'imageUploadUrl' => Url::to(['post/upload-image']),
    ]); ?>

    <?= $form->field($model, 'status')->widget(Select2::class, [
        'data' => $model::getStatusOptions(),
        'hideSearch' => false,
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
