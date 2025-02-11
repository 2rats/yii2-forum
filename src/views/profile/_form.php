<?php

/** @var yii\web\View $this */
/** @var rats\forum\models\form\ProfileForm $profileFormModel */
/** @var rats\forum\models\form\ImageUploadForm $imageUploadFormModel */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

?>

<?php $form = ActiveForm::begin([
    'method' => 'post',
]); ?>
<div class="row justify-content-center card shadow-sm p-4">
    <div class="col-11">
    </div>
    <div class="row">
        <div class="col-6">
            <?= $form->field($profileFormModel, 'real_name')->textInput(['maxlength' => true]); ?>
        </div>
        <div class="col-6">
            <?= $form->field($profileFormModel, 'username')->textInput(['maxlength' => true]); ?>
        </div>

        <?= $form->field($profileFormModel, 'signature')->textArea(['maxlength' => true, 'rows' => 6]); ?>
    </div>

    <div class="d-flex justify-content-between">
        <?= Html::a(Yii::t('app', 'Remove profile picture'), ['remove-image', 'id' => $profileFormModel->_profile->id], ['class' => 'btn btn-danger btn-sm']) ?>
        <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-outline-dark']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>