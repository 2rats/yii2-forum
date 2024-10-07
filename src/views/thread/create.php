<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use rats\forum\ForumModule;

/**
 * @var yii\web\View $this
 * @var rats\forum\models\Thread $model
 * @var rats\forum\models\Forum $forum
 */

$this->title = Yii::t('app', 'Create Thread');
$this->params['breadcrumbs'][] = ['label' => $forum->name, 'url' => ['/' . ForumModule::getInstance()->id . '/forum/view', 'id' => $forum->id, 'path' => $forum->slug]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row justify-content-center mb-4">
    <div class="col-11 thread-create border rounded-1 text-secondary">

        <h1><?= Html::encode($this->title) ?></h1>

        <div class="thread-form">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>

    </div>
</div>