<?php

/** @var yii\web\View $this */
/** @var rats\forum\models\form\PostForm $post_form */
/** @var string|null $formAction */
/** @var int|null $fk_thread */

use rats\forum\models\User;
use rats\forum\widgets\TinyMce;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$user = User::findOne(Yii::$app->user->identity->id);

$this->registerCss('
.CodeMirror, .CodeMirror-scroll {
	min-height: 100px;
    color: #5e5e5e;
}
');

?>

<div class="row justify-content-center mt-2 mb-5 post-container text-secondary">
    <?php if ($post_form->post === null): ?>
        <h4><?= Yii::t('app', 'Add post') ?></h4>
    <?php endif; ?>
    <div class="col-11 post border shadow-sm bg-white rounded-1 text-secondary my-1">
        <div style="min-height: 20vh;" class="row rounded-1">
            <div class="py-2 col-12 col-md-2 bg-secondary border-md-end border-bottom border-md-bottom-0">
                <p class="fw-bold m-0 text-center text-break">
                    <?= $user->getDisplayName() ?>
                </p>
                <div class="d-flex justify-content-center flex-wrap">
                    <?php foreach ($user->roles as $role): ?>
                        <small class="w-fit text-bg-primary rounded-1 m-1 px-2 py-1 "><?= ucfirst($role->name) ?></small>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="py-2 col-12 col-md-10 rounded-1">
                <div class="d-flex flex-column h-100">

                    <?php $form = ActiveForm::begin([
                        'action' => $formAction ?? '',
                        'method' => 'post',
                        'enableAjaxValidation' => true,
                        'options' => [
                            'id' => 'post-form',
                            'class' => 'dropzone',
                        ],
                    ]); ?>

                    <div class="reply mx-3 small border-start border-primary border-3 p-2 my-2 bg-secondary position-relative" style="display: none;">
                        <div class="mb-1 d-flex">
                            <span>
                                <svg class="mb-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-reply-fill" viewBox="0 0 16 16">
                                    <path d="M5.921 11.9 1.353 8.62a.719.719 0 0 1 0-1.238L5.921 4.1A.716.716 0 0 1 7 4.719V6c1.5 0 6 0 7 8-2.5-4.5-7-4-7-4v1.281c0 .56-.606.898-1.079.62z" />
                                </svg>
                            </span>
                            <span class="fw-medium ms-1 author">.reply .author</span>
                            <span class="ms-auto">
                                <svg class="reply-remove" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </span>
                        </div>
                        <div class="small content markdown-body">.reply .content</div>
                    </div>

                    <?= $form->field($post_form, 'content')->widget(TinyMce::class, [
                        'imageUploadUrl' => Url::to(['post/upload-image']),
                    ])->label(false); ?>

                    <div class="dz-message m-0 text-start btn btn-outline-primary btn-sm">
                        <?= Yii::t('app', 'Upload multiple images') ?>
                    </div>

                    <div class="dropzone-previews"></div>

                    <?= $form->field($post_form, 'fk_thread')->hiddenInput(['value' => $fk_thread ?? $post_form->fk_thread])->label(false) ?>
                    <?= $form->field($post_form, 'fk_parent')->hiddenInput()->label(false) ?>

                    <div class="d-flex justify-content-end">
                        <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-primary']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                    <?php $post_form->registerJs(); ?>
                </div>
            </div>
        </div>
    </div>
</div>