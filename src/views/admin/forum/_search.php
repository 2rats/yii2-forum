<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var yii\web\View $this */
/* @var rats\forum\models\ForumSearch $model */
/* @var yii\widgets\ActiveForm $form */
?>

<div class="forum-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'fk_category') ?>

    <?= $form->field($model, 'fk_parent') ?>

    <?= $form->field($model, 'fk_last_post') ?>

    <?= $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'description')
    ?>

    <?php // echo $form->field($model, 'status')
    ?>

    <?php // echo $form->field($model, 'threads')
    ?>

    <?php // echo $form->field($model, 'posts')
    ?>

    <?php // echo $form->field($model, 'created_by')
    ?>

    <?php // echo $form->field($model, 'updated_by')
    ?>

    <?php // echo $form->field($model, 'created_at')
    ?>

    <?php // echo $form->field($model, 'updated_at')
    ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
