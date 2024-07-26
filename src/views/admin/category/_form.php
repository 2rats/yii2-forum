<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use kartik\select2\Select2;
use rats\forum\models\Category;

/* @var yii\web\View $this */
/* @var rats\forum\models\Category $model */
/* @var yii\bootstrap5\ActiveForm $form */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->widget(Select2::class, [
        'data' => [
            Category::STATUS_INACTIVE => Yii::t('app', 'Inactive'),
            Category::STATUS_ACTIVE => Yii::t('app', 'Active'),
        ],
        'hideSearch' => false,
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
