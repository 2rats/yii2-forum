<?php

/** @var yii\web\View $this */
/** @var rats\forum\models\form\ProfileForm $profileFormModel */
/** @var rats\forum\models\form\ImageUploadForm $imageUploadFormModel */
/** @var rats\forum\models\File[] $previousImages */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

rats\forum\ImagePickerAsset::register($this);
?>

<?php $form = ActiveForm::begin([
    'method' => 'post',
]); ?>

<div class="row justify-content-center card shadow-sm p-4">
    <div class="row">
        <div class="col-6">
            <?= $form->field($profileFormModel, 'real_name')->textInput(['maxlength' => true]); ?>
        </div>
        <div class="col-6">
            <?= $form->field($profileFormModel, 'username')->textInput(['maxlength' => true]); ?>
        </div>

        <?= $form->field($profileFormModel, 'signature')->textArea(['maxlength' => true, 'rows' => 6]); ?>

        <div class="col-12">
            <?= $form->field($imageUploadFormModel, 'file')->fileInput()->label(Yii::t('app', 'Profile picture')); ?>

            <div class="d-flex flex-wrap">
                <?php foreach ($previousImages as $image): ?>
                    <div class="m-1">
                        <label class="image-checkbox border" style="height: 6rem; width: 6rem;">
                            <?= Html::img($image->getFileUrl(), [
                                'class' => 'w-100 h-100 rounded',
                                'style' => 'width: 100%; height: 100%; object-fit: cover; overflow-clip-margin: unset;'
                            ]); ?>
                            <?= $form->field($profileFormModel, 'fk_image')->checkbox([
                                'checked' => $image->id == $profileFormModel->_profile->fk_image,
                                'value' => $image->id,
                                'uncheck' => null,
                            ])->label(false) ?>
                            <i class="fas fa-check" style="display: none;"></i>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?= $form->field($imageUploadFormModel, 'file')->fileInput()->label(Yii::t('app', 'Profile picture')); ?>
    </div>

    <div class="d-flex justify-content-between">
        <?= Html::a(Yii::t('app', 'Remove profile picture'), ['remove-image', 'id' => $profileFormModel->_profile->id], ['class' => 'btn btn-danger btn-sm']) ?>
        <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-primary m-2']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>