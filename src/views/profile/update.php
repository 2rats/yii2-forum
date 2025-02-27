<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var rats\forum\models\form\ProfileForm $profileFormModel */
/** @var rats\forum\models\form\ImageUploadForm $imageUploadFormModel */
/** @var rats\forum\models\File[] $previousImages */

$this->title = Yii::t('app', 'Update profile');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Profile'), 'url' => ['view', 'id' => Yii::$app->user->identity->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<div class="profile-update">

    <div class="pe-lg-4">
        <div class="card shadow-sm border text-bg-secondary">
            <div class="card-body p-3">
                <h1 class="h4 text-tertiary mb-3"><?= Html::encode($this->title) ?></h1>


                <?= $this->render('_form', [
                    'profileFormModel' => $profileFormModel,
                    'imageUploadFormModel' => $imageUploadFormModel,
                    'previousImages' => $previousImages,
                ]) ?>

            </div>
        </div>
    </div>
</div>