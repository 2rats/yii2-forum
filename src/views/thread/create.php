<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use rats\forum\ForumModule;
use rats\forum\widgets\TinyMce;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var rats\forum\models\form\ThreadCreateForm $model
 * @var rats\forum\models\Forum $forum
 */

$this->title = Yii::t('app', 'Create Thread');
$this->params['breadcrumbs'][] = ['label' => $forum->name, 'url' => $forum->getUrl()];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row justify-content-center mb-4">
    <div class="col-11 thread-create border rounded-1 text-secondary">

        <h1><?= Html::encode($this->title) ?></h1>

        <div class="thread-form">
            <?php $form = ActiveForm::begin([
                'method' => 'post',
                'enableAjaxValidation' => true,
                'options' => [
                    'id' => 'post-form',
                    'class' => 'dropzone',
                ],
            ]); ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'content')->widget(TinyMce::class, [
                'imageUploadUrl' => Url::to(['post/upload-image']),
            ])->label(false); ?>

            <div class="dz-message m-0 text-start btn btn-outline-primary btn-sm">
                <?= Yii::t('app', 'Upload multiple images') ?>
            </div>

            <div class="dropzone-previews"></div>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

            <?php $this->registerJs('
                $("#post-form").dropzone({
                    clickable: ".dz-message",
                    acceptedFiles: "image/*",
                    autoProcessQueue: false,
                    uploadMultiple: true,
                    parallelUploads: 100,
                    addRemoveLinks: true,
                    maxFiles: 100,
                    previewsContainer: ".dropzone-previews",
                    maxFilesize: 5,
                    dictCancelUpload: "Zrušit",
                    dictCancelUploadConfirmation: "Opravdu chcete zrušit nahrávání?",
                    dictRemoveFile: "Odstranit",
                    dictFileTooBig: "Soubor je příliš velký ({{filesize}}MiB). Maximální velikost souboru je {{maxFilesize}}MiB.",
                    dictMaxFilesExceeded: "Nelze nahrát více souborů.",

                    paramName: "ThreadCreateForm[images][]",

                    init: function() {
                        var myDropzone = this;

                        this.element.querySelector("button[type=submit]").addEventListener("click", function(e) {
                            if(myDropzone.getQueuedFiles().length === 0) {
                                return;
                            }

                            e.preventDefault();
                            e.stopPropagation();

                            tinymce.triggerSave();

                            myDropzone.processQueue();
                        });

                        this.on("successmultiple", function(file, responseText, e) {
                            if(responseText.success) {
                                window.location = responseText.url;
                                return;
                            }
                            $("#post-form").yiiActiveForm("updateMessages", responseText, true);
                            myDropzone.files = myDropzone.files.map(function(file) {
                                file.status = Dropzone.QUEUED;
                                return file;
                            });
                        });
                    }
                });
            '); ?>

        </div>

    </div>
</div>