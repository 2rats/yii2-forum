<?php

use rats\forum\widgets\TinyMce;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var rats\forum\models\form\ThreadCreateForm $model
 * @var rats\forum\models\Forum $forum
 */

$this->title = Yii::t('app', 'Create Thread');
$this->params['breadcrumbs'][] = ['label' => $forum->name, 'url' => $forum->getUrl()];
$this->params['breadcrumbs'][] = $this->title;
?>



<div class="pe-4">
    <div class="card shadow-sm border">
        <div class="card-body p-3">
            <h1 class="h4 text-primary mb-3"><?= Html::encode($this->title) ?></h1>

            <?php $form = ActiveForm::begin([
                'method' => 'post',
                'enableAjaxValidation' => true,
                'options' => [
                    'id' => 'post-form',
                    'class' => 'dropzone',
                ],
            ]); ?>


            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'content')->widget(TinyMce::class, [
                'imageUploadUrl' => Url::to(['post/upload-image']),
            ]); ?>

            <div class="dz-message m-0 text-start btn btn-outline-primary btn-sm">
                <?= Yii::t('app', 'Upload multiple images') ?>
            </div>

            <div class="dropzone-previews"></div>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Create'), [
                    'class' => 'btn btn-primary px-4',
                ]) ?>
            </div>

            <?php ActiveForm::end(); ?>

            <?php $model->registerJs(); ?>
        </div>
    </div>
</div>