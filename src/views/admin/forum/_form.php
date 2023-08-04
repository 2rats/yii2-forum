<?php

use kartik\select2\Select2;
use rats\forum\models\Category;
use rats\forum\models\Forum;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

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

    <?php
    $parent_forums = Forum::find();
    if ($model->id !== null) {
        $parent_forums->andWhere(['!=', 'id', $model->id])->andWhere(['!=', 'fk_parent', $model->id]);
    } ?>
    <?= $form->field($model, 'fk_parent')->widget(Select2::class, [
        'data' => ArrayHelper::map($parent_forums->all(), 'id', 'name'),
        'hideSearch' => false,
        'options' => [
            'prompt' => ''
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]) ?>



    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>


    <?= $form->field($model, 'status')->widget(Select2::class, [
        'data' => [
            Forum::STATUS_INACTIVE => Yii::t('app', 'Inactive'),
            Forum::STATUS_ACTIVE_UNLOCKED => Yii::t('app', 'Unlocked'),
            Forum::STATUS_ACTIVE_LOCKED => Yii::t('app', 'Locked'),
        ],
        'hideSearch' => false,
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>