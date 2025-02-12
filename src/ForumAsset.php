<?php

namespace rats\forum;

use yii\web\AssetBundle;

class ForumAsset extends AssetBundle
{
    public $sourcePath = '@rats/forum/assets';

    public $js = [
        'postScroll.js',
        'create_post.js',
        'fancybox.umd.js',
        'forum-fancybox.js',
        'tinymce/cs.js',
        'dropzone/dropzone.min.js',
    ];
    public $css = [
        'forum.css',
        'fancybox.css',
        'forum-fancybox.css',
        'tinymce/custom.css',
        'dropzone/dropzone.min.css',
        'dropzone/custom.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap5\BootstrapAsset',
        'dosamigos\tinymce\TinyMceAsset',
    ];
}
