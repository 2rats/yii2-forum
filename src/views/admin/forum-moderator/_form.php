<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var rats\forum\models\ForumModerator $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="magazine-admin-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fk_user')->widget(Select2::class, [
        'hideSearch' => true,
        'options' => ['prompt' => ''],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 3,
            'ajax' => [
                'url' => \yii\helpers\Url::to(['user-list']),
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(model) { return model.text; }'),
            'templateSelection' => new JsExpression('function (model) { return model.text; }'),
        ],
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Uložit', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
