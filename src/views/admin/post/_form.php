<?php

use kartik\select2\Select2;
use rats\forum\models\Post;
use rats\forum\models\Thread;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var rats\forum\models\Post $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fk_thread')->widget(Select2::class, [
        'data' => \yii\helpers\ArrayHelper::map(Thread::find()->all(), 'id', 'name'),
        'hideSearch' => false,
    ]) ?>

    <?= $form->field($model, 'fk_parent')->widget(Select2::class, [
        'data' => \yii\helpers\ArrayHelper::map(Post::find()->all(), 'id', function ($model) {
            return "{$model->createdBy->username} - {$model->created_at} (ID: {$model->id})";
        }),
        'hideSearch' => false,
        'options' => [
            'prompt' => ''
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->widget(Select2::class, [
        'data' => [
            Post::STATUS_ACTIVE => Yii::t('app', 'Active'),
            Post::STATUS_DELETED => Yii::t('app', 'Deleted'),
        ],
        'hideSearch' => false,
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>