<?php

use kartik\select2\Select2;
use rats\forum\models\Forum;
use rats\forum\models\Thread;
use rats\forum\components\StatusColumn;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var rats\forum\models\Thread $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="thread-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'fk_forum')->widget(Select2::class, [
        'data' => ArrayHelper::map($model->getForum()->all(), 'id', 'name'),
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
        'data' => $model::getStatusOptions(),
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

    <?= $form->field($model, 'seo_title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'seo_description')->textarea(['rows' => 3]) ?>
    <?= $form->field($model, 'seo_keywords')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
