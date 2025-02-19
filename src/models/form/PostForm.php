<?php

namespace rats\forum\models\form;

use rats\forum\LoadingOverlayAsset;
use rats\forum\models\Post;
use rats\forum\models\Thread;
use rats\forum\models\User;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\helpers\Html;

/**
 * PostForm is the model behind the login form.
 *
 */
class PostForm extends Model
{
    public $fk_parent;

    public $fk_thread;

    public $content;

    public $images = [];

    /**
     * @var Post|null
     */
    private $post = null;

    public function __construct($config = [], ?Post $post = null)
    {
        parent::__construct($config);
        $this->post = $post;
        if ($post !== null) {
            $this->fk_parent = $post->fk_parent;
            $this->fk_thread = $post->fk_thread;
            $this->content = $post->content;
        }
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['images', 'file', 'extensions' => 'png, jpg, jpeg, gif', 'maxFiles' => 50, 'skipOnEmpty' => true],

            [['content'], 'required'],
            [['content'], 'string'],
            [['content'], 'validateMaxlength', 'params' => ['max' => 5000]],
            ['content', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            [['fk_parent'], 'exist', 'skipOnError' => false, 'targetClass' => Post::class, 'targetAttribute' => ['fk_parent' => 'id']],
            [['fk_thread'], 'exist', 'skipOnError' => false, 'targetClass' => Thread::class, 'targetAttribute' => ['fk_thread' => 'id']],
        ];
    }

    public function validateMaxlength($attribute, $params)
    {
        if (mb_strlen($this->$attribute) > $params['max']) {
            $this->addError($attribute, Yii::t(
                'app',
                '{attribute} should contain at most {max, number} characters. (currently {current})',
                [
                    'attribute' => $this->attributeLabels()[$attribute],
                    'max' => $params['max'],
                    'current' => mb_strlen($this->$attribute),
                ]
            ));
        }
    }

    public function attributeLabels()
    {
        return [
            'content' => \Yii::t('app', 'Post text'),
        ];
    }

    public function load($data, $formName = null)
    {
        $this->images = UploadedFile::getInstancesByName($formName . '[images]');
        return parent::load($data, $formName);
    }

    public function save()
    {
        if ($this->post === null) {
            $this->post = new Post();
            $this->post->fk_parent = $this->fk_parent;
            $this->post->fk_thread = $this->fk_thread;
            $this->post->status = Post::STATUS_ACTIVE;
        }

        $this->parseImages();

        $this->post->content = $this->content;

        if ($this->post->save()) {
            $user = User::findOne(Yii::$app->user->id);
            $user->last_post_id = $this->post->id;
            $user->save();
            return true;
        }

        $this->post = null;
        return false;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    private function parseImages(): void
    {
        foreach ($this->images as $image) {
            $imageUploadForm = new ImageUploadForm(ImageUploadForm::DIR_PATH_POST);
            $imageUploadForm->file = $image;
            $uploadedImage = $imageUploadForm->upload();
            if ($uploadedImage !== false) {
                $this->content .= Html::img($uploadedImage->getFileUrl());
            }
        }
    }

    public function registerJs(string $formName = 'PostForm'): void
    {
        LoadingOverlayAsset::register(Yii::$app->getView());
        Yii::$app->getView()->registerJs('
        $("#post-form").dropzone({
            clickable: ".dz-message",
            acceptedFiles: ".png,.jpeg,.jpg,.gif",
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 100,
            addRemoveLinks: true,
            maxFiles: 100,
            previewsContainer: ".dropzone-previews",
            maxFilesize: 5,

            // translations
            dictDefaultMessage: "' . Yii::t('app', 'Drop files here to upload') . '",
            dictFallbackMessage: "' . Yii::t('app', 'Your browser does not support drag\'n\'drop file uploads.') . '",
            dictFallbackText: "' . Yii::t('app', 'Please use the fallback form below to upload your files like in the olden days.') . '",
            dictFileTooBig: "' . Yii::t('app', 'File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.') . '",
            dictInvalidFileType: "' . Yii::t('app', 'You can\'t upload files of this type.') . '",
            dictResponseError: "' . Yii::t('app', 'Server responded with {{statusCode}} code.') . '",
            dictCancelUpload: "' . Yii::t('app', 'Cancel upload') . '",
            dictCancelUploadConfirmation: "' . Yii::t('app', 'Are you sure you want to cancel this upload?') . '",
            dictRemoveFile: "' . Yii::t('app', 'Remove file') . '",
            dictMaxFilesExceeded: "' . Yii::t('app', 'You can not upload any more files.') . '",

            paramName: "' . $formName . '[images][]",

            init: function() {
                var myDropzone = this;

                this.element.querySelector("button[type=submit]").addEventListener("click", function(e) {
                    if(myDropzone.getQueuedFiles().length === 0) {
                        return;
                    }

                    e.preventDefault();
                    e.stopPropagation();

                    $("#post-form").LoadingOverlay("show");

                    tinymce.triggerSave();

                    myDropzone.processQueue();
                });

                this.on("successmultiple", function(file, responseText, e) {
                    if(responseText.success) {
                        window.location = responseText.url;
                        return;
                    }
                    $("#post-form").LoadingOverlay("hide");
                    $("#post-form").yiiActiveForm("updateMessages", responseText, true);
                    myDropzone.files = myDropzone.files.map(function(file) {
                        file.status = Dropzone.QUEUED;
                        return file;
                    });
                });
            }
        });
    ');
    }
}
