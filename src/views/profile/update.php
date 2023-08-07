<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var rats\forum\models\Thread $model */

$this->title = Yii::t('app', 'Update profile');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Profile'), 'url' => ['view', 'id' => Yii::$app->user->identity->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="thread-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>