<?php

namespace rats\forum\widgets;

use yii\helpers\Url;
use yii\web\JsExpression;

class TinyMce extends \dosamigos\tinymce\TinyMce
{
    public function __construct($config = [])
    {
        $this->clientOptions['language'] = 'cs';
        $this->clientOptions['language_url'] = Url::to('@web/js/tinymce/cs.js');

        $this->imageUploadUrl = $config['imageUploadUrl'] ?? null;

        if (isset($this->imageUploadUrl) && $this->imageUploadUrl !== null) {

            $this->clientOptions['relative_urls'] = false;
            $this->clientOptions['remove_script_host'] = false;

            $this->clientOptions['images_upload_handler'] = new JsExpression('(blobInfo, progress) => new Promise((resolve, reject) => {
                    const xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;

                    xhr.open("POST", "' . $this->imageUploadUrl . '");

                    xhr.setRequestHeader("X-CSRF-Token", "' . \Yii::$app->request->getCsrfToken() . '");

                    xhr.upload.onprogress = (e) => {
                        progress(e.loaded / e.total * 100);
                    };

                    xhr.onload = () => {
                        if (xhr.status === 403) {
                            reject({ message: "HTTP Error: " + xhr.status, remove: true });
                            return;
                        }

                        if (xhr.status < 200 || xhr.status >= 300) {
                            reject({ message: "HTTP Error: " + xhr.status, remove: true });
                            return;
                        }

                        const json = JSON.parse(xhr.responseText);

                        if (!json || typeof json.filename != "string") {
                            if(typeof json.error == "string") {
                                reject({ message: json.error, remove: true });
                                return;
                            }
                            reject({ message: "Invalid JSON: " + xhr.responseText, remove: true });
                            return;
                        }

                        resolve(json.filename);
                    };

                    xhr.onerror = () => {
                        reject({ message: "Error: " + xhr.status, remove: true });
                    };

                    const formData = new FormData();
                    formData.append("file", blobInfo.blob(), blobInfo.filename());

                    xhr.send(formData);
                })');

            $this->clientOptions['file_picker_callback'] = new JsExpression("function(cb, value, meta) {
                    const input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');
                    input.addEventListener('change', (e) => {
                        const file = e.target.files[0];
                        let formData = new FormData();
                        formData.append('file', file);
                        formData.append('" . \Yii::$app->request->csrfParam . "', '" . \Yii::$app->request->getCsrfToken() . "');
                        fetch('" . $this->imageUploadUrl . "', {
                            method: 'POST',
                            body: formData
                        }).then(response => response.json()).then(data => {
                            if(data.error) {
                                alert(data.error);
                                return;
                            }
                            cb(data.filename);
                        });
                    });
                    input.click();
                }");
        }

        parent::__construct($config);
    }

    public $imageUploadUrl;

    public $clientOptions =  [
        'image_title' => true,
        'automatic_uploads' => true,

        'file_picker_types' => 'image',

        'plugins' => [
            "link",
            "image",
            "lists",
            "table",
            "codesample",
        ],

        'menubar' => false,
        'statusbar' => false,
        'object_resizing' => false,
        'image_dimensions' => false,
        'image_title' => false,

        'codesample_languages' => [
            [
                'text' => 'text',
                'value' => 'text'
            ]
        ],

        'table_appearance_options' => true,
        'toolbar' => "undo redo | bold italic h1 h2 h3 | codesample bullist numlist | link image table hr",
        'promotion' => false,
        'branding' => false,
        'license_key' => 'gpl',
        'color_map' => [
            '#008fab',
            'Primary color',
            '#BFEDD2',
            'Light Green',
            '#FBEEB8',
            'Light Yellow',
            '#F8CAC6',
            'Light Red',
            '#ECCAFA',
            'Light Purple',
            '#C2E0F4',
            'Light Blue',
            '#2DC26B',
            'Green',
            '#F1C40F',
            'Yellow',
            '#E03E2D',
            'Red',
            '#B96AD9',
            'Purple',
            '#3598DB',
            'Blue',
            '#169179',
            'Dark Turquoise',
            '#E67E23',
            'Orange',
            '#BA372A',
            'Dark Red',
            '#843FA1',
            'Dark Purple',
            '#236FA1',
            'Dark Blue',
            '#ECF0F1',
            'Light Gray',
            '#CED4D9',
            'Medium Gray',
            '#95A5A6',
            'Gray',
            '#7E8C8D',
            'Dark Gray',
            '#34495E',
            'Navy Blue',
            '#000000',
            'Black',
            '#ffffff',
            'White'
        ]
    ];
}
