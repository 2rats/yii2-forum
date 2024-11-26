<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var rats\forum\models\Post $model */

$this->title = Yii::t('app', 'Update Post');
?>
<div class="post-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'post_form' => $model,
        'fk_thread' => $model->post->fk_thread,
        'fk_parent' => $model->post->fk_parent,
    ]) ?>

</div>