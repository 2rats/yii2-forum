<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MagazineAdmin $model */

$this->title = Yii::t("app", "Add moderator to forum");
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Forums'), 'url' => ['admin/forum/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="forum-moderator-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
