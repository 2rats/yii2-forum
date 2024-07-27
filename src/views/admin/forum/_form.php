<?php

use kartik\select2\Select2;
use rats\forum\models\Category;
use rats\forum\models\Forum;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\JsExpression;

/* @var yii\web\View $this */
/* @var rats\forum\models\Forum $model */
/* @var yii\bootstrap5\ActiveForm $form */
?>

<div class="forum-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fk_category')->widget(Select2::class, [
        'data' => ArrayHelper::map(Category::find()->all(), 'id', 'name'),
        'hideSearch' => false,
        'options' => [
            'prompt' => ''
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]) ?>

    <?= $form->field($model, 'fk_parent')->widget(Select2::class, [
        'hideSearch' => false,
        'options' => [
            'prompt' => ''
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 3,
            'ajax' => [
                'url' => \yii\helpers\Url::to(['parent-forum-list']),
                'dataType' => 'json',
                'data' => new JsExpression('function(params) {return {q:params.term, forum:' . ($model->id ?? 'null') . '}; }')
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(model) { return model.text; }'),
            'templateSelection' => new JsExpression('function (model) { return model.text; }'),
        ],
    ]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->widget(Select2::class, [
        'data' => $model::getStatusOptions(),
        'hideSearch' => false,
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
