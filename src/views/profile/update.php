<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var rats\forum\models\form\ProfileForm $profileFormModel */
/** @var rats\forum\models\form\ImageUploadForm $imageUploadFormModel */

$this->title = Yii::t('app', 'Update profile');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Profile'), 'url' => ['view', 'id' => Yii::$app->user->identity->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="profile-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'profileFormModel' => $profileFormModel,
        'imageUploadFormModel' => $imageUploadFormModel,
    ]) ?>

</div>