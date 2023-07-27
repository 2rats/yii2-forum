<?php

/** @var yii\web\View $this */

use rats\forum\ForumModule;
use rats\forum\models\User;
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
    <h4><?= Yii::t('app', 'Add post') ?></h4>
    <div class="col-11 post border shadow-sm bg-white rounded-1 text-secondary my-1">
        <div style="min-height: 20vh;" class="row rounded-1">
            <div class="py-2 col-12 col-md-2 bg-lighter border-md-end border-bottom border-md-bottom-0">
                <p class="fw-bold m-0 text-center">
                    <?= $user->username ?>
                </p>
                <div class="d-flex justify-content-center">
                    <?php foreach ($user->roles as $role) : ?>
                        <small class="w-fit bg-light rounded-1 m-1 px-2 py-1 "><?= ucfirst($role->name) ?></small>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="py-2 col-12 col-md-10 rounded-1">
                <div class="d-flex flex-column h-100">

                    <?php $form = ActiveForm::begin([
                        'action' => Url::to(['/' . ForumModule::getInstance()->id . '/post/create']),
                        'method' => 'post',
                        'enableAjaxValidation' => true,
                    ]); ?>

                    <div class="reply mx-3 small border-start border-3 p-2 my-2 bg-lighter position-relative" style="display: none;">
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

                    <?= $form->field($post_form, 'content')->widget(\yii2mod\markdown\MarkdownEditor::class, [
                        'editorOptions' => [
                            'showIcons' => ['code', 'table', 'horizontal-rule', 'heading-1', 'heading-2', 'heading-3'],
                            'hideIcons' => ['fullscreen', 'guide', 'side-by-side', 'heading'],
                            'status' => false,
                            'insertTexts' => [
                                'image' => ["![" . Yii::t('app', 'Image description') . "](https://", ")"],
                                'link' => ["[" . Yii::t('app', 'Link text'), "](https://)"],
                                'table' => ["", "\n\n| Text | Text | Text |\n|------|------|------|\n| Text | Text | Text |\n"],
                            ],
                            'spellChecker' => false,
                            'toolbarTips' => false,
                            'placeholder' => Yii::t('app', 'Post text') . '..'
                        ],
                    ])->label(false); ?>

                    <?= $form->field($post_form, 'fk_thread')->hiddenInput(['value' => $fk_thread])->label(false) ?>
                    <?= $form->field($post_form, 'fk_parent')->hiddenInput(['value' => null])->label(false) ?>

                    <div class="d-flex justify-content-end">
                        <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-outline-dark']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
</div>